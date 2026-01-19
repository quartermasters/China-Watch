<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $page_title ?? 'China Watch' ?>
    </title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&family=JetBrains+Mono:wght@400;700&display=swap"
        rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="/css/main.css">

    <!-- HTMX -->
    <script src="https://unpkg.com/htmx.org@1.9.10"></script>

    <!-- Google AdSense (Placeholder) -->
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-xxxxxxxxxxxx"
        crossorigin="anonymous"></script>
</head>

<body>
    <div class="app-container">
        <!-- Header -->
        <header class="header">
            <div class="logo">
                <a href="/" style="text-decoration:none; color:inherit;">
                    China Watch // <span style="color:white">Intelligence</span>
                </a>
            </div>

            <nav class="main-nav">
                <a href="/" class="nav-link <?= $uri === '/' ? 'active' : '' ?>">Dashboard</a>
                <a href="/methodology" class="nav-link">Methodology</a>
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

        <!-- Ticker -->
        <aside class="ticker-panel">
            <h3 class="font-mono" style="margin-top:0; color:var(--text-secondary)">// LIVE ANOMALIES</h3>
            <ul id="ticker-feed" class="ticker-list" hx-get="/api/ticker" hx-trigger="load, every 30s">
                <!-- HTMX will load list items here -->
                <li class="font-mono text-amber">Loading stream...</li>
            </ul>
        </aside>
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