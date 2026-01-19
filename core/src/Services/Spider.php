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

            echo "   Found: {$title}\n";
            $result = $this->process_url($link, "Google News: {$topic['keyword']}");

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
     * Robust HTTP Client (Stealth Mode)
     * Mimics a real browser to avoid 403 blocks.
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
        curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        // Browser Headers
        $headers = [
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8',
            'Accept-Language: en-US,en;q=0.9',
            'Referer: https://www.google.com/',
            'Upgrade-Insecure-Requests: 1',
            'Sec-Ch-Ua: "Not A(Brand";v="99", "Google Chrome";v="121", "Chromium";v="121"',
            'Sec-Ch-Ua-Mobile: ?0',
            'Sec-Ch-Ua-Platform: "Windows"',
            'Sec-Fetch-Dest: document',
            'Sec-Fetch-Mode: navigate',
            'Sec-Fetch-Site: cross-site',
            'Sec-Fetch-User: ?1',
            'Connection: keep-alive'
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_USERAGENT, $agents[array_rand($agents)]);

        // Cookie Handling (Important for sessions)
        $cookieFile = sys_get_temp_dir() . '/spider_cookie.txt';
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);

        // SSL verification (Disable for compatibility)
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $output = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode >= 200 && $httpCode < 400 && $output) {
            return $output;
        }

        // Fallback: file_get_contents (sometimes works where cURL fails)
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
        // Suppress warnings for malformed HTML
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        libxml_clear_errors();

        $xpath = new DOMXPath($dom);

        // Remove junk elements
        $junkTags = ['script', 'style', 'nav', 'footer', 'header', 'aside', 'iframe', 'noscript', 'form', 'button', 'svg'];
        foreach ($junkTags as $tag) {
            $nodes = $xpath->query("//{$tag}");
            foreach ($nodes as $node) {
                $node->parentNode->removeChild($node);
            }
        }

        // Try to find the semantic elements first
        $text = '';
        $article = $dom->getElementsByTagName('article')->item(0);
        $main = $dom->getElementsByTagName('main')->item(0);

        if ($article) {
            $text = $article->textContent;
        } elseif ($main) {
            $text = $main->textContent;
        } else {
            // Fallback: Look for large divs or body
            $body = $dom->getElementsByTagName('body')->item(0);
            $text = $body ? $body->textContent : '';
        }

        // Clean Whitespace
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);

        // Extract Title
        $titleNode = $dom->getElementsByTagName('title')->item(0);
        $title = $titleNode ? $titleNode->textContent : 'Unknown Title';

        return [
            'title' => trim($title),
            'text' => $text
        ];
    }
}
