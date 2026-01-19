<?php
declare(strict_types=1);

namespace RedPulse\Services;

use RedPulse\Core\DB;
use DOMDocument;
use DOMXPath;

class Spider
{
    /**
     * The Main Crawl Loop
     */
    public function crawl_source(int $sourceId): array
    {
        $source = DB::query("SELECT * FROM sources WHERE id = ?", [$sourceId])[0] ?? null;
        if (!$source)
            return ['status' => 'error', 'message' => 'Source ID not found'];

        echo "🕷️ Spider Target (Source): {$source['name']} ({$source['url']})...\n";
        return $this->process_url($source['url'], $source['name']);
    }

    /**
     * Direct RSS feeds that don't require Google News decoding
     */
    private array $directFeeds = [
        'globaltimes' => 'https://www.globaltimes.cn/rss/outbrain.xml',
        'chinadaily' => 'http://www.chinadaily.com.cn/rss/china_rss.xml',
        'cgtn' => 'https://www.cgtn.com/subscribe/rss/section/china.xml',
    ];

    public function crawl_direct_rss(string $feedKey = 'globaltimes'): array
    {
        $rssUrl = $this->directFeeds[$feedKey] ?? $this->directFeeds['globaltimes'];
        echo "🕷️ Spider Target (Direct RSS): {$feedKey}...\n";

        $xmlContent = $this->fetch($rssUrl);
        if (!$xmlContent)
            return ['status' => 'error', 'message' => 'RSS Fetch Failed'];

        $xml = @simplexml_load_string($xmlContent);
        if (!$xml)
            return ['status' => 'error', 'message' => 'Invalid RSS XML'];

        $count = 0;
        $items = $xml->channel->item ?? [];

        foreach ($items as $item) {
            if ($count >= 1)
                break;

            $link = trim((string) ($item->link ?? ''));
            $title = trim((string) ($item->title ?? ''));

            if (empty($link) || empty($title))
                continue;

            $exists = DB::query("SELECT id FROM reports WHERE source_url = ?", [$link]);
            if (!empty($exists))
                continue;

            echo "   Found: {$title}\n";

            // Check for inline content (China Daily has full articles in RSS)
            $content = trim((string) ($item->content ?? $item->description ?? ''));
            $content = strip_tags(html_entity_decode($content, ENT_QUOTES, 'UTF-8'));
            $content = preg_replace('/\s+/', ' ', $content);

            if (strlen($content) >= 500) {
                echo "      -> Using RSS content: " . strlen($content) . " chars\n";
                $analyst = new \RedPulse\Services\AI\ReportGenerator();
                $reportId = $analyst->generate_report(ucfirst($feedKey), $link, $title . "\n\n" . $content);

                if ($reportId) {
                    echo "   ✅ SUCCESS: Report generated (ID: {$reportId})\n";
                    $count++;
                    continue;
                }
            }

            // Otherwise, scrape the article page
            $result = $this->process_url($link, ucfirst($feedKey));

            if ($result['status'] === 'success' && ($result['action'] ?? '') === 'report_generated') {
                echo "   ✅ SUCCESS: Report generated (ID: {$result['report_id']})\n";
                $count++;
            } else {
                echo "   ❌ SKIPPED: {$result['message']}\n";
            }
        }

        return ['status' => 'success', 'crawled_count' => $count, 'feed' => $feedKey];
    }

    public function crawl_topic(int $topicId): array
    {
        $topic = DB::query("SELECT * FROM topics WHERE id = ?", [$topicId])[0] ?? null;
        if (!$topic)
            return ['status' => 'error', 'message' => 'Topic ID not found'];

        echo "🕷️ Spider Target (Topic): {$topic['keyword']}...\n";

        $query = urlencode($topic['search_query']);
        $rssUrl = "https://news.google.com/rss/search?q={$query}&hl=en-US&gl=US&ceid=US:en";

        $xmlContent = $this->fetch($rssUrl);
        if (!$xmlContent)
            return ['status' => 'error', 'message' => 'RSS Fetch Failed'];

        $xml = @simplexml_load_string($xmlContent);
        if (!$xml)
            return ['status' => 'error', 'message' => 'Invalid RSS XML'];

        $count = 0;
        foreach ($xml->channel->item as $item) {
            if ($count >= 1)
                break;

            $link = (string) $item->link;
            $title = (string) $item->title;
            $description = (string) ($item->description ?? '');

            $exists = DB::query("SELECT id FROM reports WHERE source_url = ?", [$link]);
            if (!empty($exists))
                continue;

            echo "   Found: {$title}\n";

            // 1. TRY DECODING (Best Method - Bypasses Google)
            $realUrl = $this->resolve_google_news_link($link);

            if ($realUrl !== $link) {
                echo "   -> Decoded Base64: $realUrl\n";
            }

            $result = $this->process_url($realUrl, "Google News: {$topic['keyword']}");

            if ($result['status'] === 'success' && ($result['action'] ?? '') === 'report_generated') {
                echo "   ✅ SUCCESS: Report generated (ID: {$result['report_id']})\n";
                $count++;
            } else {
                echo "   ❌ SKIPPED: {$result['message']}\n";

                // FALLBACK: Use RSS description if scraping failed
                $descText = strip_tags(html_entity_decode($description, ENT_QUOTES, 'UTF-8'));
                $descText = preg_replace('/\s+/', ' ', trim($descText));

                if (strlen($descText) >= 150) {
                    echo "   -> Fallback: Using RSS description (" . strlen($descText) . " chars)\n";
                    $contentText = $title . "\n\n" . $descText;

                    $analyst = new \RedPulse\Services\AI\ReportGenerator();
                    $reportId = $analyst->generate_report("Google News: {$topic['keyword']}", $realUrl, $contentText);

                    if ($reportId) {
                        echo "   ✅ SUCCESS (RSS Fallback): Report generated (ID: {$reportId})\n";
                        $count++;
                    }
                }
            }
        }

        DB::query("UPDATE topics SET last_crawled_at = NOW() WHERE id = ?", [$topic['id']]);

        return ['status' => 'success', 'crawled_count' => $count];
    }

    private function resolve_google_news_link(string $url): string
    {
        if (!preg_match('/articles\/([a-zA-Z0-9\-_]+)/', $url, $matches)) {
            return $url;
        }

        $articleId = $matches[1];

        // Convert URL-safe base64 to standard base64
        $base64 = str_replace(['-', '_'], ['+', '/'], $articleId);
        // Add padding if needed
        $padding = 4 - (strlen($base64) % 4);
        if ($padding !== 4) {
            $base64 .= str_repeat('=', $padding);
        }

        $decoded = base64_decode($base64, true);
        if (!$decoded) {
            return $url;
        }

        // Google uses protobuf - URL is usually after a marker byte followed by length
        // Try multiple extraction patterns
        $patterns = [
            '/https?:\/\/(?:www\.)?[a-zA-Z0-9][-a-zA-Z0-9]*\.[a-zA-Z]{2,}[^\x00-\x1F\x7F\s"\'<>]*/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match_all($pattern, $decoded, $urlMatches)) {
                foreach ($urlMatches[0] as $candidate) {
                    // Skip Google domains
                    if (strpos($candidate, 'google.') !== false) continue;
                    if (strpos($candidate, 'gstatic.') !== false) continue;
                    // Return first valid external URL
                    return rtrim($candidate, '.,;:)}]');
                }
            }
        }

        return $url;
    }

    private function process_url(string $url, string $sourceName): array
    {
        // 1. Fetch
        $response = $this->fetch_with_info($url);
        if (!$response['content'])
            return ['status' => 'error', 'message' => 'Connection Failed/Blocked (403/404)'];

        $html = $response['content'];
        $finalUrl = $response['url'];

        // 2. Fallback: Deep Link Hunter (If still on Google)
        if (strpos($finalUrl, 'news.google.com') !== false || strpos($finalUrl, 'google.com/consent') !== false) {
            echo "      -> Detected Google Page. Initiating Deep Link Hunter [DEBUG_V3]...\n";

            $realUrl = $this->hunt_deep_link_regex($html);

            if ($realUrl && $realUrl !== $url) {
                echo "      -> Unmasked Target (Deep Scan): $realUrl\n";
                // Recursion
                return $this->process_url($realUrl, $sourceName);
            } else {
                return ['status' => 'skipped', 'message' => "Stuck on Google Page. Deep Hunter failed."];
            }
        }

        // 3. Extract Data
        $data = $this->extract($html);
        $len = strlen($data['text']);
        echo "      -> Content Length: {$len} chars\n";

        if ($len < 500) {
            return ['status' => 'skipped', 'message' => "Content too short/empty ({$len} chars). Possible JS-wall."];
        }

        // 4. Send to The Analyst
        $analyst = new \RedPulse\Services\AI\ReportGenerator();
        $reportId = $analyst->generate_report($sourceName, $finalUrl, $data['text']);

        return ['status' => 'success', 'action' => 'report_generated', 'report_id' => $reportId];
    }

    /**
     * Scans RAW HTML/JS for ANY external URL.
     * Use this when DOM parsing fails (e.g. JS variables).
     */
    private function hunt_deep_link_regex(string $html): ?string
    {
        // Find all http/https strings
        preg_match_all('/https?:\/\/[^\s"\'<>\\\\]+/', $html, $matches);

        $candidates = $matches[0] ?? [];

        foreach ($candidates as $candidate) {
            $candidate = stripslashes($candidate);
            $candidate = trim($candidate, "\\/\"'"); // Additional trim

            // Filter Junk - STRICT LIST
            if (strpos($candidate, 'google.') !== false)
                continue;
            if (strpos($candidate, 'googleapis.') !== false)
                continue;
            if (strpos($candidate, 'gstatic.') !== false)
                continue;
            if (strpos($candidate, 'googleusercontent.') !== false)
                continue;
            if (strpos($candidate, 'googletagmanager.') !== false)
                continue;
            if (strpos($candidate, 'googlesyndication.') !== false)
                continue;
            if (strpos($candidate, 'doubleclick.') !== false)
                continue;
            if (strpos($candidate, 'ggpht.') !== false)
                continue;
            if (strpos($candidate, 'youtube.') !== false)
                continue;
            if (strpos($candidate, 'blogger.') !== false)
                continue;
            if (strpos($candidate, 'w3.org') !== false)
                continue;
            if (strpos($candidate, 'schema.org') !== false)
                continue;
            if (strpos($candidate, 'purl.org') !== false)
                continue;
            if (strpos($candidate, 'ogp.me') !== false)
                continue;

            // Filter common assets
            if (preg_match('/\.(css|js|png|jpg|jpeg|svg|ico|gif|woff|ttf|eot)$/i', $candidate))
                continue;

            if (strpos($candidate, 'fonts.') !== false)
                continue;

            // Filter Technical/Framework junk
            if (strpos($candidate, 'angular.') !== false)
                continue;
            if (strpos($candidate, 'reactjs.') !== false)
                continue;
            if (strpos($candidate, 'vuejs.') !== false)
                continue;
            if (strpos($candidate, 'github.') !== false)
                continue;
            if (strpos($candidate, 'stackoverflow.') !== false)
                continue;
            if (strpos($candidate, 'sentry.io') !== false)
                continue;
            if (strpos($candidate, 'license') !== false)
                continue;
            if (strpos($candidate, 'privacy') !== false)
                continue;
            if (strpos($candidate, 'terms') !== false)
                continue;
            if (strpos($candidate, 'cdn.') !== false)
                continue;
            if (strpos($candidate, 'assets.') !== false)
                continue;

            // Must be a substantial link
            if (strlen($candidate) < 15)
                continue;

            // Log acceptance? No, too noisy.
            return $candidate;
        }

        return null;
    }

    private function fetch(string $url): ?string
    {
        $res = $this->fetch_with_info($url);
        return $res['content'];
    }

    private function fetch_with_info(string $url): array
    {
        $agents = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Safari/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Safari/537.36'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 7);
        curl_setopt($ch, CURLOPT_TIMEOUT, 45);

        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

        $headers = [
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
            'Accept-Language: en-US,en;q=0.9',
            'Upgrade-Insecure-Requests: 1',
            'User-Agent: ' . $agents[array_rand($agents)]
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $cookieFile = sys_get_temp_dir() . '/spider_cookie.txt';
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $output = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        $content = null;
        if ($info['http_code'] >= 200 && $info['http_code'] < 400 && $output) {
            $content = $output;
        } else if ($info['http_code'] == 403 || empty($output)) {
            $context = stream_context_create([
                'http' => [
                    'header' => "User-Agent: " . $agents[0] . "\r\n",
                    'follow_location' => 1,
                    'timeout' => 30
                ]
            ]);
            $fallback = @file_get_contents($url, false, $context);
            if ($fallback)
                $content = $fallback;
        }

        return [
            'content' => $content,
            'url' => $info['url'],
            'http_code' => $info['http_code']
        ];
    }

    private function extract(string $html): array
    {
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        @$dom->loadHTML('<?xml encoding="UTF-8">' . $html);
        libxml_clear_errors();
        $xpath = new DOMXPath($dom);

        $junkTags = ['script', 'style', 'nav', 'footer', 'header', 'aside', 'iframe', 'noscript', 'form', 'button', 'svg', 'ad', 'place'];
        foreach ($junkTags as $tag) {
            $nodes = $xpath->query("//{$tag}");
            foreach ($nodes as $node) {
                $node->parentNode->removeChild($node);
            }
        }

        $text = '';
        $article = $dom->getElementsByTagName('article')->item(0);
        if ($article) {
            $text = $article->textContent;
        } else {
            $body = $dom->getElementsByTagName('body')->item(0);
            $text = $body ? $body->textContent : '';
        }

        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);

        $titleNode = $dom->getElementsByTagName('title')->item(0);
        $title = $titleNode ? $titleNode->textContent : 'Unknown Title';

        return [
            'title' => trim($title),
            'text' => $text
        ];
    }
}
