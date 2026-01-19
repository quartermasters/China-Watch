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
     * Can be called by cron for a specific source OR a topic.
     */
    public function crawl_source(int $sourceId): array
    {
        $source = DB::query("SELECT * FROM sources WHERE id = ?", [$sourceId])[0] ?? null;
        if (!$source)
            return ['status' => 'error', 'message' => 'Source ID not found'];

        echo "🕷️ Spider Target (Source): {$source['name']} ({$source['url']})...\n";
        return $this->process_url($source['url'], $source['name']);
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

            $exists = DB::query("SELECT id FROM reports WHERE source_url = ?", [$link]);
            if (!empty($exists))
                continue;

            // DECODE GOOGLE NEWS LINK
            $realUrl = $this->resolve_google_news_link($link);
            echo "   Found: {$title}\n";
            echo "   -> Resolved: $realUrl\n";

            $result = $this->process_url($realUrl, "Google News: {$topic['keyword']}");

            if ($result['status'] === 'success' && ($result['action'] ?? '') === 'report_generated') {
                echo "   ✅ SUCCESS: Report generated (ID: {$result['report_id']})\n";
                $count++;
            } else {
                echo "   ❌ SKIPPED: {$result['message']}\n";
            }
        }

        DB::query("UPDATE topics SET last_crawled_at = NOW() WHERE id = ?", [$topic['id']]);

        return ['status' => 'success', 'crawled_count' => $count];
    }

    /**
     * Google News Decoder
     * Extracts the real URL from the protobuf/base64 'CBM' string to avoid redirects/consent walls.
     */
    private function resolve_google_news_link(string $url): string
    {
        // 1. Check for Base64 pattern (usually after 'articles/')
        if (preg_match('/articles\/([a-zA-Z0-9\-_]+)/', $url, $matches)) {
            $base64 = $matches[1];

            // 2. Decode
            // Determine if it needs URL-safe decoding fixes
            $decoded = base64_decode($base64);
            if ($decoded) {
                // 3. Find URL inside binary mess
                // Look for http/https followed by legitimate chars
                if (preg_match('/(https?:\/\/[a-zA-Z0-9\-\._~:\/\?#\[\]@!$&\'\(\)\*\+,;=]+)/i', $decoded, $urlMatches)) {
                    return $urlMatches[1];
                }
            }
        }
        return $url; // Fallback to original
    }

    private function process_url(string $url, string $sourceName): array
    {
        // 1. Fetch
        $html = $this->fetch($url);
        if (!$html)
            return ['status' => 'error', 'message' => 'Connection Failed/Blocked (403/404)'];

        // 2. Extract Data
        $data = $this->extract($html);

        // 3. Stats Check
        $len = strlen($data['text']);
        echo "      -> Content Length: {$len} chars\n";

        if ($len < 500) {
            return ['status' => 'skipped', 'message' => "Content too short/empty ({$len} chars). Possible JS-wall."];
        }

        // 4. Send to The Analyst
        $analyst = new \RedPulse\Services\AI\ReportGenerator();
        $reportId = $analyst->generate_report($sourceName, $url, $data['text']);

        return ['status' => 'success', 'action' => 'report_generated', 'report_id' => $reportId];
    }

    /**
     * Robust HTTP Client (Stealth Mode + GZIP + IPv4)
     */
    private function fetch(string $url): ?string
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

        // VITAL FIX: Handle GZIP automatically
        curl_setopt($ch, CURLOPT_ENCODING, '');
        // VITAL FIX: Force IPv4 to avoid some blocklists
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

        // Browser Headers
        $headers = [
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
            'Accept-Language: en-US,en;q=0.9',
            'Upgrade-Insecure-Requests: 1',
            'User-Agent: ' . $agents[array_rand($agents)]
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Cookie Handling
        $cookieFile = sys_get_temp_dir() . '/spider_cookie.txt';
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);

        // SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $output = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode >= 200 && $httpCode < 400 && $output) {
            return $output;
        }

        // Fallback: file_get_contents
        if ($httpCode === 403 || empty($output)) {
            $context = stream_context_create([
                'http' => [
                    'header' => "User-Agent: " . $agents[0] . "\r\n" .
                        "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8\r\n",
                    'follow_location' => 1,
                    'timeout' => 30
                ]
            ]);
            $fallback = @file_get_contents($url, false, $context);
            if ($fallback)
                return $fallback;
        }

        return null;
    }

    /**
     * Content Extraction Engine (Improved)
     */
    private function extract(string $html): array
    {
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        // UTF-8 Hack to prevent encoding issues
        @$dom->loadHTML('<?xml encoding="UTF-8">' . $html);
        libxml_clear_errors();

        $xpath = new DOMXPath($dom);

        // Remove junk
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
