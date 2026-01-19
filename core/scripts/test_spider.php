<?php
// core/scripts/test_spider.php
// Run this in browser to debug Spider Logic

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>🕷️ Spider Diagnostic Tool</h1>";

function test_url($url)
{
    echo "<h3>Testing: $url</h3>";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow Redirects
    curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_ENCODING, ''); // Enable GZIP Decoding
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4); // Force IPv4

    // Stealth Headers
    $headers = [
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
        'Accept-Language: en-US,en;q=0.9',
        'Upgrade-Insecure-Requests: 1',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Safari/537.36'
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $output = curl_exec($ch);
    $info = curl_getinfo($ch);

    if (curl_errno($ch)) {
        echo "<div style='color:red'>cURL Error: " . curl_error($ch) . "</div>";
    }

    curl_close($ch);

    echo "<ul>";
    echo "<li>HTTP Code: <b>" . $info['http_code'] . "</b></li>";
    echo "<li>Redirect Count: " . $info['redirect_count'] . "</li>";
    echo "<li>Effective URL: " . $info['url'] . "</li>";
    echo "<li>Content Type: " . $info['content_type'] . "</li>";
    echo "<li>Download Size: " . $info['size_download'] . " bytes</li>";
    echo "</ul>";

    if ($output) {
        $len = strlen($output);
        echo "<p>Received <b>$len</b> bytes.</p>";

        // Peek at content
        $preview = htmlspecialchars(substr($output, 0, 500));
        echo "<div style='background:#f0f0f0; padding:10px; border:1px solid #ccc; font-family:monospace; white-space:pre-wrap;'>$preview</div>";

        // Check for common blocks
        if (stripos($output, 'captcha') !== false)
            echo "<b style='color:red'>WARNING: CAPTCHA detected</b><br>";
        if (stripos($output, 'cloudflare') !== false)
            echo "<b style='color:red'>WARNING: Cloudflare detected</b><br>";
        if (stripos($output, 'google.com/sorry') !== false)
            echo "<b style='color:red'>WARNING: Google Bot Block detected</b><br>";

        // Try DOM Extract
        $dom = new DOMDocument();
        @$dom->loadHTML('<?xml encoding="UTF-8">' . $output);
        $title = $dom->getElementsByTagName('title')->item(0)?->textContent ?? 'No Title';
        echo "<p>Extracted Title: <b>$title</b></p>";
    } else {
        echo "<p style='color:red'>NO OUTPUT RECEIVED</p>";
    }
    echo "<hr>";
}

// 1. Test GZIP Capability
test_url("http://httpbin.org/gzip");

// 2. Test IPv4/Connection (Reuters) - Expect Block or Content
test_url("https://www.reuters.com/world/china/");

// 3. Test Google News Redirect (Real Scenario)
// Using a sample Google News link (Quantum Computing item)
test_url("https://news.google.com/rss/articles/CBMiRGh0dHBzOi8vd3d3LnRoZXF1YW4?oc=5");

