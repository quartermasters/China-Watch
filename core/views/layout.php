<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $page_title ?? 'Red Pulse' ?>
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
            <div class="logo">Red Pulse // <span style="color:white">China Watch</span></div>
            <div class="status-indicator">
                <span class="font-mono text-green">● SYSTEM OPTIMAL</span>
            </div>
        </header>

        <!-- Sidebar (Nav) -->
        <nav class="sidebar">
            <!-- Nav items would go here -->
        </nav>

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
            onclick="document.getElementById('chat-body').classList.toggle('hidden')">
            // RED PULSE ANALYST
        </div>
        <div id="chat-body" class="hidden" style="height:300px; display:flex; flex-direction:column;">
            <div id="chat-history" style="flex:1; padding:10px; overflow-y:auto; font-size:0.9rem;">
                <div class="message bot" style="color:var(--signal-blue)">System Online. Awaiting query.</div>
            </div>
            <form hx-post="/api/chat" hx-target="#chat-history" hx-swap="beforeend"
                style="display:flex; border-top:1px solid var(--border-subtle);">
                <input type="text" name="question" placeholder="Ask about China's economy..."
                    style="flex:1; background:var(--bg-void); color:white; border:none; padding:10px; outline:none;">
                <button type="submit"
                    style="background:var(--bg-surface); color:var(--signal-blue); border:none; padding:0 15px; cursor:pointer;">></button>
            </form>
        </div>
    </div>

    <!-- Cookie Banner (Placeholder for Strategy) -->
    <div id="cookie-consent"></div>
</body>

</html>