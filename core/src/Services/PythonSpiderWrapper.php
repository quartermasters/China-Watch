<?php
declare(strict_types=1);

namespace RedPulse\Services;

/**
 * The Bridge: Allows PHP to drive Python Microservices
 */
class PythonSpiderWrapper
{
    private string $platform;
    private string $pythonPath;
    private string $scriptsDir;

    public function __construct(string $platform)
    {
        $this->platform = $platform;

        // VENV Creation failed on this server (missing python3-venv).
        // Dependencies (praw) were installed globally.
        // Force System Python.
        $this->pythonPath = 'python3';

        $this->scriptsDir = __DIR__ . '/../../python/scripts';
    }

    /**
     * Execute the Python script for this platform
     */
    public function crawl_source(int $sourceId): array
    {
        // This matching the signature of Spider::crawl_source
        // But for Python, we likely pass the URL or Metadata
        // For now, let's assume we pass the Source ID and Python looks it up, OR we pass URL.

        // Let's assume we pass a test URL for now to verify the bridge
        return $this->process_url("TEST_MODE", "Testing {$this->platform}");
    }

    public function process_url(string $url, string $sourceName): array
    {
        $script = $this->scriptsDir . "/scrape_{$this->platform}.py";

        if (!file_exists($script)) {
            // Fallback for testing: bridge_test.py
            $script = __DIR__ . '/../../python/bridge_test.py';
        }

        $cmd = sprintf(
            '%s %s --url %s --source %s 2>&1',
            escapeshellarg($this->pythonPath),
            escapeshellarg($script),
            escapeshellarg($url),
            escapeshellarg($sourceName)
        );

        echo "🐍 Dispatching to Python ({$this->platform}): $cmd\n";

        $output = [];
        $returnVar = 0;
        exec($cmd, $output, $returnVar);

        $jsonOutput = implode("\n", $output);
        $data = json_decode($jsonOutput, true);

        // Robustness: If full decode fails (due to warnings/logs), try the LAST line only.
        if (json_last_error() !== JSON_ERROR_NONE && !empty($output)) {
            $lastLine = end($output);
            $data = json_decode($lastLine, true);
        }

        if ($returnVar !== 0 || empty($data)) {
            return [
                'status' => 'error',
                'message' => "Python Error (Exit Code $returnVar): " . $jsonOutput
            ];
        }

        return $data;
    }
}
