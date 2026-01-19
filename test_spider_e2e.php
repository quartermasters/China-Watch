<?php
// public_html/test_spider_e2e.php
// Full Simulation: RSS -> Link -> Article Content

ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>🕷️ Spider End-to-End Test</h1>";

// 1. Fetch RSS
$rssUrl = "https://news.google.com/rss/search?q=China+Economy&hl=en-US&gl=US&ceid=US:en";
echo "<h3>1. Fetching RSS: $rssUrl</h3>";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $rssUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
$rssContent = curl_exec($ch);
curl_close($ch);

if (!$rssContent) {
    die("❌ Failed to fetch RSS");
}
echo "<p>✅ RSS Fetched (" . strlen($rssContent) . " bytes)</p>";

$xml = simplexml_load_string($rssContent);
if (!$xml)
    die("❌ Invalid XML");

// 2. Extract First Link
$item = $xml->channel->item[0];
$title = (string) $item->title;
$link = (string) $item->link;

echo "<h3>2. Found Article</h3>";
echo "<b>Title:</b> $title<br>";
echo "<b>Link:</b> <a href='$link' target='_blank'>$link</a><br>";

// 3. Crawl the Link
echo "<h3>3. Crawling Link...</h3>";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $link);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow Redirects!
curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);

// GZIP & IPv4 (The Fixes)
curl_setopt($ch, CURLOPT_ENCODING, '');
curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

// Cookie Jar (Crucial for Google Redirects)
$cookieFile = sys_get_temp_dir() . '/spider_test_cookie.txt';
if (file_exists($cookieFile))
    unlink($cookieFile);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);

// Headers
$headers = [
    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
    'Accept-Language: en-US,en;q=0.9',
    'Upgrade-Insecure-Requests: 1',
    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Safari/537.36',
    'Referer: https://news.google.com/'
];
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$html = curl_exec($ch);
$info = curl_getinfo($ch);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    die("<div style='color:red'>❌ cURL Error: $error</div>");
}

echo "<ul>";
echo "<li><b>Final URL:</b> " . $info['url'] . "</li>";
echo "<li><b>HTTP Code:</b> " . $info['http_code'] . "</li>";
echo "<li><b>Content Type:</b> " . $info['content_type'] . "</li>";
echo "<li><b>Redirects:</b> " . $info['redirect_count'] . "</li>";
echo "<li><b>Size:</b> " . strlen($html) . " bytes</li>";
echo "</ul>";

// 4. Dump Content Preview
echo "<h3>4. Content Dump</h3>";
$preview = htmlspecialchars(substr($html, 0, 1500));
echo "<div style='background:#f5f5f5; padding:10px; border:1px solid #ccc; font-family:monospace; white-space:pre-wrap; max-height:400px; overflow:auto;'>$preview</div>";

// 5. Check for Red Flags
if (stripos($html, 'Moved Temporarily') !== false)
    echo "⚠️ 'Moved Temporarily' found (Redirect loop?)<br>";
if (stripos($html, 'JavaScript is disabled') !== false)
    echo "⚠️ 'JavaScript is disabled' warning<br>";
if (stripos($html, 'captcha') !== false)
    echo "⚠️ CAPTCHA detected<br>";
if (strlen($html) < 500)
    echo "⚠️ Content too short!<br>";
