<?php
// Fallback variables
$topics = $topics ?? [];
$search_query = $search_query ?? '';
$total_topics = $total_topics ?? count($topics);
$total_references = $total_references ?? array_sum($topics);
$top_topic = $top_topic ?? (count($topics) > 0 ? array_key_first($topics) : 'N/A');
?>

<style>
/* =============================================
   TOPIC EXPLORER - NEXT-GEN DESIGN SYSTEM
   ============================================= */

.topic-explorer {
    --te-bg-dark: #0a0f1a;
    --te-bg-card: rgba(17, 24, 39, 0.8);
    --te-bg-glass: rgba(30, 41, 59, 0.5);
    --te-border: rgba(255, 255, 255, 0.08);
    --te-border-hover: rgba(255, 255, 255, 0.15);
    --te-text-primary: #f1f5f9;
    --te-text-secondary: #94a3b8;
    --te-text-muted: #64748b;
    --te-accent: #06b6d4;
    --te-accent-glow: rgba(6, 182, 212, 0.4);
    --te-accent-soft: rgba(6, 182, 212, 0.15);
    --te-purple: #a855f7;
    --te-purple-soft: rgba(168, 85, 247, 0.15);
    --te-amber: #f59e0b;
    --te-amber-soft: rgba(245, 158, 11, 0.15);
    --te-radius-sm: 6px;
    --te-radius-md: 10px;
    --te-radius-lg: 14px;
    --te-radius-full: 9999px;
    --te-shadow-glow: 0 0 20px var(--te-accent-glow);
    --te-transition: 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Header Section */
.topic-explorer .te-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 24px;
    padding: 48px 0 32px;
    flex-wrap: wrap;
}

.topic-explorer .te-header-left h1 {
    font-family: 'Inter', system-ui, sans-serif;
    font-size: 2rem;
    font-weight: 700;
    color: var(--te-text-primary);
    margin: 0 0 4px;
    letter-spacing: -0.02em;
}

.topic-explorer .te-header-left p {
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.75rem;
    color: var(--te-text-muted);
    margin: 0;
    text-transform: uppercase;
    letter-spacing: 0.1em;
}

/* Stats Cards */
.topic-explorer .te-stats {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.topic-explorer .te-stat {
    background: var(--te-bg-glass);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid var(--te-border);
    border-radius: var(--te-radius-md);
    padding: 12px 20px;
    min-width: 120px;
    position: relative;
    overflow: hidden;
}

.topic-explorer .te-stat::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: var(--te-accent);
}

.topic-explorer .te-stat.purple::before { background: var(--te-purple); }
.topic-explorer .te-stat.amber::before { background: var(--te-amber); }

.topic-explorer .te-stat-value {
    font-family: 'JetBrains Mono', monospace;
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--te-text-primary);
    line-height: 1;
}

.topic-explorer .te-stat-label {
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.65rem;
    color: var(--te-text-muted);
    text-transform: uppercase;
    letter-spacing: 0.1em;
    margin-top: 4px;
}

/* Search Bar */
.topic-explorer .te-search-wrap {
    background: var(--te-bg-glass);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid var(--te-border);
    border-radius: var(--te-radius-lg);
    padding: 16px 20px;
    margin-bottom: 32px;
    display: flex;
    align-items: center;
    gap: 16px;
    position: relative;
    overflow: hidden;
}

.topic-explorer .te-search-wrap::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
}

.topic-explorer .te-search-icon {
    color: var(--te-text-muted);
    flex-shrink: 0;
    transition: color var(--te-transition);
}

.topic-explorer .te-search-input {
    flex: 1;
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.9rem;
    background: transparent;
    border: none;
    color: var(--te-text-primary);
    outline: none;
}

.topic-explorer .te-search-input::placeholder {
    color: var(--te-text-muted);
}

.topic-explorer .te-search-wrap:focus-within {
    border-color: var(--te-accent);
    box-shadow: var(--te-shadow-glow);
}

.topic-explorer .te-search-wrap:focus-within .te-search-icon {
    color: var(--te-accent);
}

.topic-explorer .te-search-hint {
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.65rem;
    color: var(--te-text-muted);
    background: var(--te-bg-dark);
    padding: 4px 8px;
    border-radius: var(--te-radius-sm);
    flex-shrink: 0;
}

/* Topic Grid */
.topic-explorer .te-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 16px;
    grid-auto-flow: dense;
}

/* Topic Cards */
.topic-explorer .te-card {
    background: var(--te-bg-card);
    border: 1px solid var(--te-border);
    border-radius: var(--te-radius-md);
    padding: 20px;
    text-decoration: none;
    color: inherit;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    position: relative;
    overflow: hidden;
    transition: all var(--te-transition);
    min-height: 100px;
}

.topic-explorer .te-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.06), transparent);
}

.topic-explorer .te-card::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 0;
    height: 2px;
    background: var(--te-accent);
    transition: width 0.3s ease;
}

.topic-explorer .te-card:hover {
    border-color: var(--te-border-hover);
    transform: translateY(-4px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.4), var(--te-shadow-glow);
}

.topic-explorer .te-card:hover::after {
    width: 100%;
}

.topic-explorer .te-card-name {
    font-size: 1rem;
    font-weight: 600;
    color: var(--te-text-primary);
    margin: 0;
    line-height: 1.3;
    word-break: break-word;
}

.topic-explorer .te-card-count {
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.7rem;
    color: var(--te-text-muted);
    background: rgba(0, 0, 0, 0.3);
    padding: 3px 8px;
    border-radius: var(--te-radius-sm);
    align-self: flex-start;
    margin-top: 12px;
}

/* Featured Cards (Large) - Top 2 */
.topic-explorer .te-card.featured {
    grid-column: span 2;
    grid-row: span 2;
    background: linear-gradient(135deg, var(--te-accent-soft) 0%, var(--te-bg-card) 100%);
    border-color: rgba(6, 182, 212, 0.3);
    min-height: 200px;
}

.topic-explorer .te-card.featured .te-card-name {
    font-size: 1.75rem;
}

.topic-explorer .te-card.featured .te-card-count {
    background: var(--te-accent);
    color: black;
    font-weight: 600;
}

.topic-explorer .te-card.featured::after {
    background: var(--te-accent);
    height: 3px;
}

/* Medium Cards - Next 6 */
.topic-explorer .te-card.medium {
    grid-column: span 2;
    background: linear-gradient(135deg, var(--te-purple-soft) 0%, var(--te-bg-card) 100%);
    border-color: rgba(168, 85, 247, 0.2);
}

.topic-explorer .te-card.medium .te-card-name {
    font-size: 1.15rem;
}

.topic-explorer .te-card.medium::after {
    background: var(--te-purple);
}

/* Small Cards */
.topic-explorer .te-card.small .te-card-name {
    font-size: 0.95rem;
    color: var(--te-text-secondary);
}

/* Empty State */
.topic-explorer .te-empty {
    text-align: center;
    padding: 80px 20px;
    border: 1px dashed var(--te-border);
    border-radius: var(--te-radius-lg);
    grid-column: 1 / -1;
}

.topic-explorer .te-empty-icon {
    font-size: 48px;
    margin-bottom: 16px;
    opacity: 0.5;
}

.topic-explorer .te-empty h3 {
    color: var(--te-text-primary);
    margin: 0 0 8px;
}

.topic-explorer .te-empty p {
    color: var(--te-text-muted);
    font-size: 0.875rem;
    margin: 0;
}

/* Skeleton Loading */
.topic-explorer .te-skeleton {
    display: none;
}

.topic-explorer .te-skeleton.loading {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 16px;
}

.topic-explorer .te-skeleton-card {
    background: var(--te-bg-card);
    border: 1px solid var(--te-border);
    border-radius: var(--te-radius-md);
    padding: 20px;
    min-height: 100px;
}

.topic-explorer .te-skeleton-line {
    height: 16px;
    background: linear-gradient(90deg, var(--te-bg-dark) 25%, rgba(255,255,255,0.05) 50%, var(--te-bg-dark) 75%);
    background-size: 200% 100%;
    animation: te-shimmer 1.5s infinite;
    border-radius: 4px;
    margin-bottom: 12px;
}

.topic-explorer .te-skeleton-line.short { width: 50%; }

@keyframes te-shimmer {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

/* Footer */
.topic-explorer .te-footer {
    text-align: center;
    padding: 32px 0;
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.75rem;
    color: var(--te-text-muted);
}

/* Responsive */
@media (max-width: 768px) {
    .topic-explorer .te-header {
        flex-direction: column;
    }

    .topic-explorer .te-stats {
        width: 100%;
    }

    .topic-explorer .te-stat {
        flex: 1;
        min-width: auto;
    }

    .topic-explorer .te-card.featured,
    .topic-explorer .te-card.medium {
        grid-column: span 1;
        grid-row: span 1;
    }

    .topic-explorer .te-card.featured .te-card-name {
        font-size: 1.25rem;
    }

    .topic-explorer .te-grid {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    }

    .topic-explorer .te-search-hint {
        display: none;
    }
}

@media (max-width: 480px) {
    .topic-explorer .te-grid {
        grid-template-columns: 1fr 1fr;
    }
}
</style>

<div class="topic-explorer" id="topic-app">
    <div class="container">
        <!-- Header -->
        <div class="te-header">
            <div class="te-header-left">
                <h1>Topic Explorer</h1>
                <p>// Research Subject Index</p>
            </div>
            <div class="te-stats">
                <div class="te-stat">
                    <div class="te-stat-value" id="stat-topics"><?= number_format($total_topics) ?></div>
                    <div class="te-stat-label">Topics</div>
                </div>
                <div class="te-stat purple">
                    <div class="te-stat-value" id="stat-refs"><?= number_format($total_references) ?></div>
                    <div class="te-stat-label">References</div>
                </div>
                <div class="te-stat amber">
                    <div class="te-stat-value" id="stat-top"><?= htmlspecialchars(substr($top_topic, 0, 12)) ?><?= strlen($top_topic) > 12 ? '...' : '' ?></div>
                    <div class="te-stat-label">Top Topic</div>
                </div>
            </div>
        </div>

        <!-- Search -->
        <div class="te-search-wrap">
            <svg class="te-search-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="text" class="te-search-input" id="topic-search"
                   value="<?= htmlspecialchars($search_query) ?>"
                   placeholder="Search topics..."
                   autocomplete="off"
                   aria-label="Search topics">
            <span class="te-search-hint">Press / to focus</span>
        </div>

        <!-- Skeleton Loading -->
        <div class="te-skeleton" id="topic-skeleton">
            <?php for ($i = 0; $i < 12; $i++): ?>
            <div class="te-skeleton-card">
                <div class="te-skeleton-line"></div>
                <div class="te-skeleton-line short"></div>
            </div>
            <?php endfor; ?>
        </div>

        <!-- Topic Grid -->
        <div class="te-grid" id="topic-grid">
            <?php if (empty($topics)): ?>
                <div class="te-empty">
                    <div class="te-empty-icon">&#128194;</div>
                    <h3>No Topics Found</h3>
                    <p>Try broadening your search.</p>
                </div>
            <?php else: ?>
                <?php
                $i = 0;
                foreach ($topics as $name => $count):
                    $sizeClass = 'small';
                    if ($i < 2) $sizeClass = 'featured';
                    elseif ($i < 8) $sizeClass = 'medium';
                    $i++;
                ?>
                    <a href="/research?tag=<?= urlencode($name) ?>" class="te-card <?= $sizeClass ?>">
                        <span class="te-card-name"><?= htmlspecialchars($name) ?></span>
                        <span class="te-card-count"><?= number_format($count) ?> reports</span>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Footer -->
        <div class="te-footer">
            <?= $total_topics ?> topics &bull; <?= number_format($total_references) ?> total references &bull; Updated in real-time
        </div>
    </div>
</div>

<script>
(function() {
    'use strict';

    let searchTimeout = null;
    let isLoading = false;

    const $ = (sel) => document.querySelector(sel);
    const $$ = (sel) => document.querySelectorAll(sel);

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str || '';
        return div.innerHTML;
    }

    async function fetchTopics(search) {
        if (isLoading) return;
        isLoading = true;

        const skeleton = $('#topic-skeleton');
        const grid = $('#topic-grid');
        if (skeleton) skeleton.classList.add('loading');
        if (grid) grid.style.opacity = '0.3';

        try {
            const params = new URLSearchParams();
            if (search) params.set('q', search);

            const resp = await fetch('/api/topics?' + params.toString());
            if (!resp.ok) throw new Error('Network error');
            const data = await resp.json();

            renderTopics(data.topics);
            updateStats(data);
            updateURL(search);
        } catch (err) {
            console.error('Topic Explorer: fetch failed', err);
            if (grid) {
                grid.innerHTML = '<div class="te-empty"><div class="te-empty-icon">&#9888;</div><h3>Connection Error</h3><p>Failed to load topics. Please try again.</p></div>';
            }
        } finally {
            if (skeleton) skeleton.classList.remove('loading');
            if (grid) grid.style.opacity = '1';
            isLoading = false;
        }
    }

    function renderTopics(topics) {
        const grid = $('#topic-grid');
        if (!grid) return;

        const entries = Object.entries(topics || {});
        if (entries.length === 0) {
            grid.innerHTML = '<div class="te-empty"><div class="te-empty-icon">&#128194;</div><h3>No Topics Found</h3><p>Try broadening your search.</p></div>';
            return;
        }

        let html = '';
        entries.forEach(function(entry, i) {
            const name = entry[0];
            const count = entry[1];
            let sizeClass = 'small';
            if (i < 2) sizeClass = 'featured';
            else if (i < 8) sizeClass = 'medium';

            html += '<a href="/research?tag=' + encodeURIComponent(name) + '" class="te-card ' + sizeClass + '">' +
                '<span class="te-card-name">' + escapeHtml(name) + '</span>' +
                '<span class="te-card-count">' + count.toLocaleString() + ' reports</span></a>';
        });

        grid.innerHTML = html;
    }

    function updateStats(data) {
        const statTopics = $('#stat-topics');
        const statRefs = $('#stat-refs');
        const statTop = $('#stat-top');

        if (statTopics) statTopics.textContent = (data.total_topics || 0).toLocaleString();
        if (statRefs) statRefs.textContent = (data.total_references || 0).toLocaleString();
        if (statTop) {
            const top = data.top_topic || 'N/A';
            statTop.textContent = top.length > 12 ? top.substring(0, 12) + '...' : top;
        }
    }

    function updateURL(search) {
        const url = search ? '/topics?q=' + encodeURIComponent(search) : '/topics';
        if (url !== window.location.pathname + window.location.search) {
            history.pushState({}, '', url);
        }
    }

    function init() {
        const searchInput = $('#topic-search');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    fetchTopics(searchInput.value.trim());
                }, 300);
            });

            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    clearTimeout(searchTimeout);
                    fetchTopics(searchInput.value.trim());
                }
            });
        }

        // Keyboard shortcut: / to focus search
        document.addEventListener('keydown', function(e) {
            if (e.target.tagName === 'INPUT' || e.target.tagName === 'SELECT' || e.target.tagName === 'TEXTAREA') {
                if (e.key === 'Escape') e.target.blur();
                return;
            }
            if (e.key === '/') {
                e.preventDefault();
                if (searchInput) searchInput.focus();
            }
        });

        // Browser back/forward
        window.addEventListener('popstate', function() {
            const params = new URLSearchParams(window.location.search);
            const search = params.get('q') || '';
            if (searchInput) searchInput.value = search;
            fetchTopics(search);
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
</script>
