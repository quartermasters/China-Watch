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

        // Use Google News RSS for efficient, structured topic monitoring
        // URL Encode the query securely
        $query = urlencode($topic['search_query']);
        $rssUrl = "https://news.google.com/rss/search?q={$query}&hl=en-US&gl=US&ceid=US:en";

        // Fetch RSS
        $xmlContent = $this->fetch($rssUrl);
        if (!$xmlContent)
            return ['status' => 'error', 'message' => 'RSS Fetch Failed'];

        // Parse RSS
        $xml = @simplexml_load_string($xmlContent);
        if (!$xml)
            return ['status' => 'error', 'message' => 'Invalid RSS XML'];

        // Process top 3 items
        $count = 0;
        foreach ($xml->channel->item as $item) {
            if ($count >= 1)
                break; // Limit to 1 per run to avoid spamming usage

            $link = (string) $item->link;
            $title = (string) $item->title;

            // Check if we already have this URL
            $exists = DB::query("SELECT id FROM reports WHERE source_url = ?", [$link]);
            if (!empty($exists))
                continue;

            echo "   Found: {$title}\n";
            $result = $this->process_url($link, "Google News: {$topic['keyword']}");

            if ($result['status'] === 'success' && ($result['action'] ?? '') === 'report_generated') {
                $count++;
            }
        }

        // Update Topic Last Checked
        DB::query("UPDATE topics SET last_crawled_at = NOW() WHERE id = ?", [$topic['id']]);

        return ['status' => 'success', 'crawled_count' => $count];
    }

    private function process_url(string $url, string $sourceName): array
    {
        // 1. Fetch
        $html = $this->fetch($url);
        if (!$html)
            return ['status' => 'error', 'message' => 'Connection Failed/Blocked'];

        // 2. Extract Data
        $data = $this->extract($html);

        // 3. Check Stats
        if (strlen($data['text']) < 1000) {
            return ['status' => 'skipped', 'message' => 'Content too short (<1000 chars)'];
        }

        // 4. Send to The Analyst
        $analyst = new \RedPulse\Services\AI\ReportGenerator();
        $reportId = $analyst->generate_report($sourceName, $url, $data['text']);

        return ['status' => 'success', 'action' => 'report_generated', 'report_id' => $reportId];
    }

    /**
     * Robust HTTP Client
     * Mimics a browser to avoid basic blocks.
     */
    private function fetch(string $url): ?string
    {
        $agents = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36',
            'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Safari/537.36'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 45);
        curl_setopt($ch, CURLOPT_USERAGENT, $agents[array_rand($agents)]);
        // SSL verification might fail on some shared hosts, disable if needed but insecure
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $output = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode >= 200 && $httpCode < 400) {
            return $output;
        }

        return null; // Failed
    }

    /**
     * Content Extraction Engine
     * Strips boilerplate, navigations, and ads.
     */
    private function extract(string $html): array
    {
        // Suppress warnings for malformed HTML
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $dom->loadHTML($html);
        libxml_clear_errors();

        $xpath = new DOMXPath($dom);

        // Remove junk elements
        $junkTags = ['script', 'style', 'nav', 'footer', 'header', 'aside', 'iframe', 'noscript'];
        foreach ($junkTags as $tag) {
            $nodes = $xpath->query("//{$tag}");
            foreach ($nodes as $node) {
                $node->parentNode->removeChild($node);
            }
        }

        // Extract Main Text (Naive approach: look for p tags or body text)
        // A better approach is to look for the element with the most text density
        $body = $dom->getElementsByTagName('body')->item(0);
        $text = $body ? $body->textContent : '';

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
