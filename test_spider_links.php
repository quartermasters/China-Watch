<?php
// public_html/test_spider_links.php
// Fetches a Google News link and dumps ALL found links to find the "Consent/Continue" button.

ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>🕷️ Spider Link Hunter Diagnostic</h1>";

// This is a "Long ID" link that likely triggers the consent wall/redirect
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

// Cookie Jar
$cookieFile = sys_get_temp_dir() . '/spider_debug_cookie.txt';
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);

// Headers
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
echo "<p><b>HTTP Code:</b> " . $info['http_code'] . "</p>";
echo "<p><b>Page Size:</b> " . strlen($html) . " bytes</p>";

if ($html) {
    $dom = new DOMDocument();
    @$dom->loadHTML($html);

    echo "<h3>Links Found on Page:</h3>";
    echo "<table border='1' cellspacing='0' cellpadding='5'>";
    echo "<tr><th>Text</th><th>HREF</th><th>Analysis</th></tr>";

    $links = $dom->getElementsByTagName('a');
    foreach ($links as $link) {
        $text = trim($link->textContent);
        $href = $link->getAttribute('href');

        $analysis = "";
        if (strpos($href, 'google.com') !== false)
            $analysis .= "[Internal] ";
        if (strpos($href, '/') === 0)
            $analysis .= "[Relative] ";
        if (strpos($href, 'continue') !== false)
            $analysis .= "<b>[CONTINUE?]</b> ";
        if (strpos($href, 'url=') !== false)
            $analysis .= "<b>[REDIRECT?]</b> ";

        // Check if it's the external hidden link
        if (filter_var($href, FILTER_VALIDATE_URL) && strpos($href, 'google.com') === false) {
            $analysis .= "<b style='color:green'>[EXTERNAL TARGET?]</b>";
        }

        echo "<tr>";
        echo "<td>" . htmlspecialchars(substr($text, 0, 50)) . "</td>";
        echo "<td><textarea cols='40' rows='1'>" . htmlspecialchars($href) . "</textarea></td>";
        echo "<td>$analysis</td>";
        echo "</tr>";
    }
    echo "</table>";

    echo "<h3>Meta Refresh Tags:</h3>";
    $metas = $dom->getElementsByTagName('meta');
    foreach ($metas as $meta) {
        if (strtolower($meta->getAttribute('http-equiv')) === 'refresh') {
            echo "Found Refresh: " . htmlspecialchars($meta->getAttribute('content')) . "<br>";
        }
    }

    echo "<h3>Input/Form Targets:</h3>";
    $inputs = $dom->getElementsByTagName('input');
    foreach ($inputs as $input) {
        if ($input->getAttribute('type') === 'hidden' && $input->getAttribute('name') === 'continue') {
            echo "Found Hidden Continue: " . htmlspecialchars($input->getAttribute('value')) . "<br>";
        }
    }

} else {
    echo "❌ No HTML content received.";
}
