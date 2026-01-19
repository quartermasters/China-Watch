<?php
// public_html/debug_decoder_remote.php
header('Content-Type: text/plain');

$urls = [
    // The problematic link from logs
    "https://news.google.com/rss/articles/CBMiWkFVX3lxTFBLNU8wS3YtcXpNc25uLWZkaW5WazFZSnB0cVUzbExpNDZxQ2Zyc3VINndKRkg1cnl3UzN6T0lNYlRfbjQ0V2tFRTVqZzRmT0dhUTNEVjgzZERSQQ?oc=5",
    // Standard shorter one
    "https://news.google.com/rss/articles/CBMiRGh0dHBzOi8vd3d3LnRoZXF1YW4?oc=5"
];

foreach ($urls as $url) {
    echo "Testing: $url\n";

    if (preg_match('/articles\/([a-zA-Z0-9\-_]+)/', $url, $matches)) {
        $base64 = $matches[1];
        echo "   Captured Base64: " . substr($base64, 0, 20) . "...\n";

        // Strategy 1: Standard Decode
        $decoded = base64_decode($base64);
        echo "   Decoded (Standard): " . extract_url($decoded) . "\n";

        // Strategy 2: URL Safe Decode (+/- chars)
        $base64url = str_replace(['-', '_'], ['+', '/'], $base64);
        $decoded2 = base64_decode($base64url);
        echo "   Decoded (URL-Safe): " . extract_url($decoded2) . "\n";

        // Strategy 3: Strip CBM prefix (if it looks like prefix)
        // Usually CBM starts therewith.
        // Let's try decoding substrings
        for ($i = 0; $i < 5; $i++) {
            $sub = substr($base64, $i);
            $d = base64_decode($sub);
            $u = extract_url($d);
            if ($u != 'FAIL')
                echo "   Decoded (Offset $i): $u\n";
        }

    } else {
        echo "   ❌ Regex failed.\n";
    }
    echo "------------------\n";
}

function extract_url($raw)
{
    // Look for http/https
    if (preg_match('/(https?:\/\/[a-zA-Z0-9\-\._~:\/\?#\[\]@!$&\'\(\)\*\+,;=]+)/i', $raw, $matches)) {
        return $matches[1];
    }
    return "FAIL";
}
