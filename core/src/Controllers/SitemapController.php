<?php
namespace RedPulse\Controllers;

use RedPulse\Core\DB;

class SitemapController
{
    public function index()
    {
        header('Content-Type: application/xml; charset=utf-8');

        $urls = [];

        // Homepage
        $urls[] = [
            'loc' => 'https://chinawatch.blog/',
            'lastmod' => date('c'),
            'changefreq' => 'hourly',
            'priority' => '1.0'
        ];

        // Research (Publications)
        $reports = DB::query("SELECT slug, published_at FROM reports ORDER BY published_at DESC");
        foreach ($reports as $r) {
            $urls[] = [
                'loc' => 'https://chinawatch.blog/research/' . $r['slug'],
                'lastmod' => date('c', strtotime($r['published_at'])),
                'changefreq' => 'weekly',
                'priority' => '0.8'
            ];
        }

        // Topics (Issue Areas)
        $entities = DB::query("SELECT id, name, created_at FROM entities");
        foreach ($entities as $e) {
            // Check if slug exists, otherwise use ID (handling transition period)
            $slug = $e['slug'] ?? $e['id'];
            $urls[] = [
                'loc' => 'https://chinawatch.blog/topic/' . $slug,
                'lastmod' => date('c', strtotime($e['created_at'])),
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ];
        }

        // Static pages
        $static = ['about', 'methodology', 'privacy', 'terms', 'contact', 'data', 'research', 'topics'];
        foreach ($static as $page) {
            $urls[] = [
                'loc' => 'https://chinawatch.blog/' . $page,
                'lastmod' => date('c'),
                'changefreq' => 'monthly',
                'priority' => '0.5'
            ];
        }

        echo $this->generateXML($urls);
        exit;
    }

    public function news()
    {
        header('Content-Type: application/xml; charset=utf-8');

        // Google News only indexes articles from last 2 days
        $reports = DB::query("
            SELECT slug, title, published_at, tags
            FROM reports
            WHERE published_at >= DATE_SUB(NOW(), INTERVAL 2 DAY)
            ORDER BY published_at DESC
        ");

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
                         xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">';

        foreach ($reports as $r) {
            $tags = json_decode($r['tags'], true) ?? [];
            $xml .= '<url>';
            $xml .= '<loc>https://chinawatch.blog/research/' . $r['slug'] . '</loc>';
            $xml .= '<news:news>';
            $xml .= '<news:publication>';
            $xml .= '<news:name>China Watch Intelligence</news:name>';
            $xml .= '<news:language>en</news:language>';
            $xml .= '</news:publication>';
            $xml .= '<news:publication_date>' . date('c', strtotime($r['published_at'])) . '</news:publication_date>';
            $xml .= '<news:title>' . htmlspecialchars($r['title']) . '</news:title>';
            if (!empty($tags)) {
                $xml .= '<news:keywords>' . htmlspecialchars(implode(', ', $tags)) . '</news:keywords>';
            }
            $xml .= '</news:news>';
            $xml .= '</url>';
        }

        $xml .= '</urlset>';
        echo $xml;
        exit;
    }

    private function generateXML(array $urls): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        foreach ($urls as $url) {
            $xml .= '<url>';
            $xml .= '<loc>' . htmlspecialchars($url['loc']) . '</loc>';
            $xml .= '<lastmod>' . $url['lastmod'] . '</lastmod>';
            $xml .= '<changefreq>' . $url['changefreq'] . '</changefreq>';
            $xml .= '<priority>' . $url['priority'] . '</priority>';
            $xml .= '</url>';
        }

        $xml .= '</urlset>';
        return $xml;
    }
}
