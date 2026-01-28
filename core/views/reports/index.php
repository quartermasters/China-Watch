<div class="archive-header text-center py-16">
    <h1 class="text-4xl font-headline mb-4">Research & Publications</h1>
    <p class="text-[var(--text-secondary)]">In-depth analysis on China's economy, policy, and geopolitical impact</p>

    <!-- Search Form -->
    <div class="mt-8 max-w-md mx-auto">
        <form action="/reports" method="GET" class="relative">
            <input type="text" name="q" value="<?= htmlspecialchars($search_query ?? '') ?>"
                placeholder="Search intelligence database..."
                class="w-full bg-[var(--bg-light)] border border-[var(--border-light)] text-[var(--text-primary)] rounded-full py-3 px-6 pl-12 focus:outline-none focus:border-[var(--brand-primary)] transition-all">
            <svg class="w-5 h-5 absolute left-4 top-1/2 transform -translate-y-1/2 text-[var(--text-muted)]" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </form>
    </div>

    <div class="mt-4 text-xs font-mono text-[var(--text-muted)]">
        Showing <span class="text-[var(--brand-primary)] font-bold"><?= count($reports) ?></span> of <span
            class="text-white"><?= $total_results ?? 'MANY' ?></span> Declassified Reports
    </div>
</div>

<div class="bento-grid" style="grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));">
    <?php foreach ($reports as $r): ?>
        <a href="/research/<?= $r['slug'] ?>" class="tile block" style="text-decoration: none; color: inherit;">
            <div class="text-xs font-mono text-[var(--brand-primary)] mb-2">
                <?= date('M d, Y', strtotime($r['published_at'])) ?>
            </div>
            <h2 class="text-xl font-bold mb-3 hover:text-[var(--brand-primary)] transition-colors">
                <?= $r['title'] ?>
            </h2>
            <p class="text-sm text-[var(--text-secondary)] line-clamp-3">
                <?= $r['summary'] ?>
            </p>
            <div class="mt-4 flex gap-2">
                <?php
                $tags = json_decode($r['tags'], true) ?? [];
                // Show first 2 tags
                $tags = array_slice($tags, 0, 2);
                foreach ($tags as $tag): ?>
                    <span class="text-[10px] uppercase border border-[var(--border-subtle)] px-2 py-1 rounded">
                        <?= $tag ?>
                    </span>
                <?php endforeach; ?>
            </div>
        </a>
    <?php endforeach; ?>
</div>

<!-- Pagination -->
<div class="flex justify-center gap-4 mt-12 mb-20">
    <?php if ($current_page > 1): ?>
            <a href="?page=<?= $current_page - 1 ?><?= $search_query ? '&q=' . urlencode($search_query) : '' ?>" 
               class="px-6 py-2 rounded-full border border-[var(--border-light)] hover:bg-[var(--bg-light)] transition-colors">
               &larr; Previous
            </a>
    <?php endif; ?>

    <?php if ($current_page < $total_pages): ?>
            <a href="?page=<?= $current_page + 1 ?><?= $search_query ? '&q=' . urlencode($search_query) : '' ?>" 
               class="px-6 py-2 rounded-full bg-[var(--brand-primary)] text-black font-bold hover:brightness-110 transition-all">
               Next &rarr;
            </a>
    <?php endif; ?>
</div>