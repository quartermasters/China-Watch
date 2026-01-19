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
     * Can be called by cron for a specific source or a batch.
     */
    public function crawl_source(int $sourceId): array
    {
        $source = DB::query("SELECT * FROM sources WHERE id = ?", [$sourceId])[0] ?? null;

        if (!$source) {
            return ['status' => 'error', 'message' => 'Source ID not found'];
        }

        echo "🕷️ Spider Target: {$source['name']} ({$source['url']})...\n";

        // 1. Fetch
        $html = $this->fetch($source['url']);

        if (!$html) {
            // Log failure
            return ['status' => 'error', 'message' => 'Connection Failed/Blocked'];
        }

        // 2. Extract Data (Text & Links)
        $data = $this->extract($html);

        // 3. Diff Check (Has content changed significantly?)
        // For MVP, we just check if we have a recent report for this source.
        $recentReport = DB::query("SELECT id FROM reports WHERE source_url = ? AND published_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)", [$source['url']]);

        if (!empty($recentReport)) {
            return ['status' => 'skipped', 'message' => 'Recently analyzed. specific new content triggers not met.'];
        }

        // 4. Send to The Analyst (AI) for Processing
        // We queue a job or call directly. For MVP, call directly.
        // We only trigger AI if the content looks "meaty" (e.g. > 500 words)
        if (strlen($data['text']) > 1000) {
            $analyst = new \RedPulse\Services\AI\ReportGenerator();
            $reportId = $analyst->generate_report($source['name'], $source['url'], $data['text']);
            return ['status' => 'success', 'action' => 'report_generated', 'report_id' => $reportId];
        }

        return ['status' => 'success', 'action' => 'monitored_no_action', 'stats' => ['length' => strlen($data['text'])]];
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
