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
        content="<?= htmlspecialchars($meta_description ?? 'China Watch: Real-time intelligence on China\'s economy, policy, and geopolitics. AI-powered analysis of economic signals and regulatory changes.') ?>">
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
    <meta property="og:title" content="<?= htmlspecialchars($page_title ?? 'China Watch // Intel') ?>">
    <meta property="og:description"
        content="<?= htmlspecialchars($meta_description ?? 'Real-time intelligence on China') ?>">
    <meta property="og:url" content="<?= $canonical_url ?? 'https://chinawatch.blog' . $_SERVER['REQUEST_URI'] ?>">
    <meta property="og:site_name" content="China Watch">
    <meta property="og:image" content="<?= $og_image ?? 'https://chinawatch.blog/public/assets/og-default.jpg' ?>">

    <!-- Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($page_title ?? 'China Watch // Intel') ?>">
    <meta name="twitter:description"
        content="<?= htmlspecialchars($meta_description ?? 'Real-time intelligence on China') ?>">
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
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">
    <link rel="dns-prefetch" href="https://unpkg.com">

    <!-- Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=JetBrains+Mono:wght@400;700&display=swap"
        rel="stylesheet">

    <!-- CSS (Versioned to force refresh) -->
    <link rel="stylesheet" href="/css/main.min.css?v=2.0">

    <!-- Schema.org Organization -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "China Watch",
      "legalName": "Distributed Intelligence Node",
      "url": "https://chinawatch.blog",
      "logo": "https://chinawatch.blog/public/assets/logo.png",
      "description": "Real-time intelligence platform monitoring China's economy, policy, and geopolitical developments.",
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
      "description": "Real-time intelligence on China's economy, policy, and geopolitical developments. AI-powered OSINT analysis.",
      "publisher": {
        "@type": "Organization",
        "name": "China Watch",
        "url": "https://chinawatch.blog"
      },
      "potentialAction": {
        "@type": "SearchAction",
        "target": {
          "@type": "EntryPoint",
          "urlTemplate": "https://chinawatch.blog/entities?q={search_term_string}"
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
    
    // We want full width for: /reports/slug, /entity/id, AND Static Pages (About, Contact, Methodology)
    $static_pages = ['/about', '/contact', '/methodology', '/privacy', '/terms'];
    $is_static_page = in_array($uri, $static_pages);

    // Detect Reading Mode (Single Report or Entity Detail)
    $is_report_detail = (strpos($uri, '/reports/') === 0 && $uri !== '/reports');
    $is_entity_detail = (strpos($uri, '/entity/') === 0);

    // Apply Reading Mode class if any of the above are true
    $reading_mode_class = ($is_report_detail || $is_entity_detail || $is_static_page) ? 'layout-reading' : '';
    ?>
    <div class="app-container <?= $reading_mode_class ?>">
        <!-- Header -->
        <header class="header">
            <div class="logo">
                <a href="/" style="text-decoration:none; color:inherit; display:flex; align-items:center; gap:8px;">
                    <div
                        style="width:12px; height:12px; background:var(--signal-red); border-radius:50%; box-shadow: 0 0 10px var(--signal-red);">
                    </div>
                    China Watch <span style="opacity:0.5; font-weight:400; font-family:var(--font-ui);">// INTEL</span>
                </a>
            </div>

            <!-- Mobile Menu Button -->
            <button id="menu-toggle" class="font-mono text-secondary"
                style="background:none; border:none; font-size:1.5rem; cursor:pointer; display:none;"
                aria-label="Toggle Navigation Menu">
                ☰
            </button>
            <script>
                // Simple Mobile Menu Logic
                document.getElementById('menu-toggle').addEventListener('click', () => {
                    document.querySelector('.main-nav').classList.toggle('open');
                });
                // Show button only on mobile via JS check or CSS media query
                if (window.innerWidth <= 1024) {
                    document.getElementById('menu-toggle').style.display = 'block';
                }
            </script>

            <nav class="main-nav">
                <a href="/" class="nav-link <?= $uri === '/' ? 'active' : '' ?>">DASHBOARD</a>
                <a href="/reports" class="nav-link <?= strpos($uri, '/reports') === 0 ? 'active' : '' ?>">REPORTS</a>
                <a href="/entities" class="nav-link <?= strpos($uri, '/entit') === 0 ? 'active' : '' ?>">ENTITIES</a>
                <a href="/methodology" class="nav-link">METHODOLOGY</a>
                <a href="/about" class="nav-link">About</a>
                <a href="/contact" class="nav-link">Contact</a>
            </nav>

            <div class="status-indicator">
                <span class="font-mono text-green">● SYSTEM OPTIMAL</span>
            </div>
        </header>

        <!-- Sidebar (Nav) - Hidden for now, moved to top -->
        <!-- <nav class="sidebar"></nav> -->

        <!-- Main Content -->
        <?= $content ?>

        <!-- Ticker (Hide in Reading Mode) -->
        <?php if (!$is_report_detail && !$is_entity_detail && !$is_static_page): ?>
            <aside class="ticker-panel">
                <h3 class="font-mono" style="margin-top:0; color:var(--text-secondary)">// LIVE ANOMALIES</h3>
                <ul id="ticker-feed" class="ticker-list" hx-get="/api/ticker" hx-trigger="load, every 30s">
                    <!-- HTMX will load list items here -->
                    <li class="font-mono text-amber">Loading stream...</li>
                </ul>
            </aside>
        <?php endif; ?>
    </div>

    <!-- AI Analyst Widget -->
    <div id="ai-widget"
        style="position:fixed; bottom:20px; right:20px; width:350px; background:var(--bg-surface); border:1px solid var(--signal-blue); border-radius:12px; overflow:hidden; z-index:100;">
        <div class="chat-header"
            style="background:var(--signal-blue); padding:10px; font-family:var(--font-mono); font-weight:bold; cursor:pointer;"
            onclick="const body = document.getElementById('chat-body'); body.style.display = (body.style.display === 'none') ? 'flex' : 'none';">
            // CHINA WATCH ANALYST
        </div>
        <div id="chat-body" style="display:none; height:300px; flex-direction:column;">
            <div id="chat-history" style="flex:1; padding:10px; overflow-y:auto; font-size:0.9rem;">
                <div class="message bot" style="color:var(--signal-blue)">System Online. Awaiting query.</div>
            </div>
            <form hx-post="/api/chat" hx-target="#chat-history" hx-swap="beforeend" hx-on::after-request="this.reset()"
                style="display:flex; border-top:1px solid var(--border-subtle);">
                <input type="text" name="question" placeholder="Ask about China's economy..." autocomplete="off"
                    style="flex:1; background:var(--bg-void); color:white; border:none; padding:10px; outline:none;">
                <button type="submit"
                    style="background:var(--bg-surface); color:var(--signal-blue); border:none; padding:0 15px; cursor:pointer;">></button>
            </form>
        </div>
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