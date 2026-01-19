<?php
// public_html/test_spider_links.php
// v2: Dumps RAW HTML to find JS redirects

ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>🕷️ Spider Link Hunter Diagnostic (Raw Dump)</h1>";

$url = "https://news.google.com/rss/articles/CBMiWkFVX3lxTFBLNU8wS3YtcXpNc25uLWZkaW5WazFZSnB0cVUzbExpNDZxQ2Zyc3VINndKRkg1cnl3UzN6T0lNYlRfbjQ0V2tFRTVqZzRmT0dhUTNEVjgzZERSQQ?oc=5";

echo "<p><b>Target:</b> $url</p>";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_MAXREDIRS, 7);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_ENCODING, '');
curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

$cookieFile = sys_get_temp_dir() . '/spider_debug_cookie.txt';
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);

$headers = [
    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Safari/537.36'
];
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$html = curl_exec($ch);
$info = curl_getinfo($ch);
curl_close($ch);

echo "<p><b>Final URL:</b> " . $info['url'] . "</p>";
echo "<p><b>Size:</b> " . strlen($html) . " bytes</p>";

if ($html) {
    echo "<h3>Raw HTML Dump (First 5000 chars):</h3>";
    $raw = htmlspecialchars(substr($html, 0, 5000));
    // Wrap in pre/code
    echo "<div style='background:#222; color:#0f0; padding:10px; font-family:monospace; white-space:pre-wrap; overflow-x:auto;'>$raw</div>";
} else {
    echo "❌ No HTML content received.";
}
