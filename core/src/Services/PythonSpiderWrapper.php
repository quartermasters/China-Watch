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

        // 1. Try Virtual Environment (Best Practice)
        $venvPath = __DIR__ . '/../../python/venv/bin/python3';

        // 2. Fallback to System Python (If venv didn't create properly)
        if (file_exists($venvPath)) {
            $this->pythonPath = $venvPath;
        } else {
            // Log this warning if possible, or just proceed
            $this->pythonPath = 'python3'; // Assumes python3 is in global PATH
        }

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

        if ($returnVar !== 0 || json_last_error() !== JSON_ERROR_NONE) {
            return [
                'status' => 'error',
                'message' => "Python Error (Exit Code $returnVar): " . $jsonOutput
            ];
        }

        return $data;
    }
}
