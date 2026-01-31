<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $page_title ?? 'China Watch' ?>
    </title>

    <!-- SEO Meta Tags -->
    <meta name="description"
        content="<?= htmlspecialchars($meta_description ?? 'China Watch: Independent research institute providing rigorous analysis on China\'s economy, policy, and geopolitical developments.') ?>">
    <link rel="canonical" href="<?= $canonical_url ?? 'https://chinawatch.blog' . $_SERVER['REQUEST_URI'] ?>">

    <!-- Language and International SEO -->
    <meta name="language" content="en">
    <link rel="alternate" hreflang="en"
        href="<?= $canonical_url ?? 'https://chinawatch.blog' . $_SERVER['REQUEST_URI'] ?>">
    <link rel="alternate" hreflang="x-default"
        href="<?= $canonical_url ?? 'https://chinawatch.blog' . $_SERVER['REQUEST_URI'] ?>">

    <!-- Additional SEO Meta -->
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="googlebot" content="index, follow">
    <meta name="author" content="China Watch Intelligence">
    <meta name="publisher" content="China Watch">
    <meta name="geo.region" content="US">
    <meta name="geo.placename" content="Global">
    <meta name="classification" content="Intelligence, Economics, China, Geopolitics">

    <!-- Open Graph -->
    <meta property="og:type" content="<?= $og_type ?? 'website' ?>">
    <meta property="og:title" content="<?= htmlspecialchars($page_title ?? 'China Watch') ?>">
    <meta property="og:description"
        content="<?= htmlspecialchars($meta_description ?? 'Independent research on China\'s economy and policy') ?>">
    <meta property="og:url" content="<?= $canonical_url ?? 'https://chinawatch.blog' . $_SERVER['REQUEST_URI'] ?>">
    <meta property="og:site_name" content="China Watch">
    <meta property="og:image" content="<?= $og_image ?? 'https://chinawatch.blog/public/assets/og-default.jpg' ?>">

    <!-- Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($page_title ?? 'China Watch') ?>">
    <meta name="twitter:description"
        content="<?= htmlspecialchars($meta_description ?? 'Independent research on China\'s economy and policy') ?>">
    <meta name="twitter:image" content="<?= $og_image ?? 'https://chinawatch.blog/public/assets/og-default.jpg' ?>">

    <!-- Article Meta Tags (for reports/articles) -->
    <?php if (isset($article_published_time)): ?>
        <meta property="article:published_time" content="<?= date('c', strtotime($article_published_time)) ?>">
        <meta property="article:modified_time"
            content="<?= date('c', strtotime($article_modified_time ?? $article_published_time)) ?>">
        <meta property="article:section" content="Intelligence">
        <meta property="article:author" content="China Watch Intelligence">
        <?php if (!empty($article_tags)): ?>
            <?php foreach ($article_tags as $tag): ?>
                <meta property="article:tag" content="<?= htmlspecialchars($tag) ?>">
            <?php endforeach; ?>
        <?php endif; ?>
    <?php endif; ?>

    <!-- Resource Hints for Performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://unpkg.com" crossorigin>

    <!-- Preload Critical Assets -->
    <link rel="preload" href="/css/main.min.css?v=2.0" as="style">
    <link rel="preload"
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=JetBrains+Mono:wght@400;700&display=swap"
        as="style">

    <!-- Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=JetBrains+Mono:wght@400;700&display=swap"
        rel="stylesheet">

    <!-- CSS (Versioned to force refresh) -->
    <!-- CSS (Versioned to force refresh) -->
    <link rel="stylesheet" href="/css/main.css?v=3.0">

    <!-- Schema.org Organization -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "China Watch",
      "legalName": "Distributed Intelligence Node",
      "url": "https://chinawatch.blog",
      "logo": "https://chinawatch.blog/public/assets/logo.png",
      "description": "Independent research institute providing analysis on China's economy, policy, and geopolitical developments.",
      "sameAs": [
        "https://twitter.com/chinawatch"
      ]
    }
    </script>

    <!-- Schema.org WebSite with SearchAction -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebSite",
      "name": "China Watch",
      "alternateName": "China Watch Intelligence",
      "url": "https://chinawatch.blog",
      "description": "Independent research on China's economy, policy, and geopolitical developments.",
      "publisher": {
        "@type": "Organization",
        "name": "China Watch",
        "url": "https://chinawatch.blog"
      },
      "potentialAction": {
        "@type": "SearchAction",
        "target": {
          "@type": "EntryPoint",
          "urlTemplate": "https://chinawatch.blog/topics?q={search_term_string}"
        },
        "query-input": "required name=search_term_string"
      }
    }
    </script>
</head>

<body>
    <?php
    // FIX: Define $uri globally for layout logic
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if ($uri !== '/' && substr($uri, -1) === '/')
        $uri = substr($uri, 0, -1); // Normalize trailing slash
    
    // We want full width for: /research/slug, /topic/id, AND Static Pages (About, Contact, Methodology)
    $static_pages = ['/about', '/contact', '/methodology', '/privacy', '/terms', '/data'];
    $is_static_page = in_array($uri, $static_pages);

    // Detect Reading Mode (Single Research article or Topic Detail)
    $is_report_detail = (strpos($uri, '/research/') === 0 && $uri !== '/research');
    $is_entity_detail = (strpos($uri, '/topic/') === 0);

    // Apply Reading Mode class if any of the above are true
    $reading_mode_class = ($is_report_detail || $is_entity_detail || $is_static_page) ? 'layout-reading' : '';
    ?>
    <div class="app-container <?= $reading_mode_class ?>">
        <!-- Header -->
        <header class="header">
            <div class="logo">
                <a href="/" style="text-decoration:none; color:inherit; display:flex; align-items:center; gap:10px;">
                    <span
                        style="font-family: var(--font-headline); font-size: 1.25rem; font-weight: 700; color: var(--text-primary);">
                        China<span style="color: var(--brand-primary);">Watch</span>
                    </span>
                </a>
            </div>

            <!-- Mobile Menu Button -->
            <button id="menu-toggle" class="menu-toggle" aria-label="Toggle Navigation Menu">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </button>
            <script>
                document.getElementById('menu-toggle').addEventListener('click', () => {
                    document.querySelector('.main-nav').classList.toggle('open');
                });
            </script>

            <nav class="main-nav">
                <a href="/research" class="nav-link <?= strpos($uri, '/research') === 0 ? 'active' : '' ?>">Research</a>
                <a href="/topics" class="nav-link <?= strpos($uri, '/topic') === 0 ? 'active' : '' ?>">Topics</a>
                <a href="/data" class="nav-link <?= $uri === '/data' ? 'active' : '' ?>">Data</a>
                <a href="/about" class="nav-link <?= strpos($uri, '/about') === 0 ? 'active' : '' ?>">About</a>
            </nav>

            <div class="header-cta hide-mobile">
                <?php if (\RedPulse\Services\AuthService::isLoggedIn()): ?>
                    <?php $user = \RedPulse\Services\AuthService::getUser(); ?>
                    <div style="display:flex; align-items:center; gap:12px;">
                        <?php if (!empty($user['avatar'])): ?>
                            <img src="<?= htmlspecialchars($user['avatar']) ?>"
                                style="width:32px; height:32px; border-radius:50%; border:1px solid var(--border-light);">
                        <?php endif; ?>
                        <a href="/auth/logout" class="btn btn-outline"
                            style="padding: 0.5rem 1rem; font-size: 0.875rem; border: 1px solid var(--border-light); color: var(--text-primary); text-decoration: none;">Logout</a>
                    </div>
                <?php else: ?>
                    <a href="/auth/login" class="btn btn-primary"
                        style="padding: 0.5rem 1rem; font-size: 0.875rem;">Login</a>
                <?php endif; ?>
            </div>
        </header>

        <!-- Sidebar (Nav) - Hidden for now, moved to top -->
        <!-- <nav class="sidebar"></nav> -->

        <!-- Main Content -->
        <?= $content ?>

    </div>


    <!-- Footer -->
    <footer class="app-footer">
        <div class="footer-content">
            <p>&copy; 2026-2036 <span class="font-bold">Quartermasters FZC</span>. All rights reserved.</p>
            <div class="footer-links">
                <a href="/privacy">Privacy Policy</a>
                <span class="divider">|</span>
                <a href="/terms">Terms of Service</a>
                <span class="divider">|</span>
                <a href="/methodology">Methodology</a>
                <span class="divider">|</span>
                <a href="/contact">Contact</a>
            </div>
        </div>
    </footer>

    <!-- Cookie Banner (Placeholder for Strategy) -->
    <div id="cookie-consent"></div>

    <!-- Modal Container -->
    <div id="anomaly-modal-container"></div>
</body>

</html>