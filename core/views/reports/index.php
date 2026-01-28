<?php
// Fallback for pagination variables to prevent undefined errors if controller isn't sync'd
$current_page = $current_page ?? 1;
$total_pages = $total_pages ?? 1;
$search_query = $search_query ?? '';
?>

<!-- New Terminal-Style Header -->
<div class="container pt-16 pb-8">
    <div class="max-w-2xl mx-auto text-center">
        <h1 class="text-3xl font-headline text-white mb-2">Intelligence Archive</h1>
        <p class="text-gray-500 text-sm font-mono mb-8">// COMPLETE DATABASE ACCESS</p>

        <!-- Spotlight Search Bar -->
        <form action="/reports" method="GET" class="relative group">
            <div class="search-spotlight rounded-lg overflow-hidden flex items-center">
                <div class="pl-4 text-gray-500">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </div>
                <input type="text" name="q" value="<?= htmlspecialchars($search_query) ?>"
                    placeholder="Search keywords, tickers, or sectors..."
                    class="w-full bg-transparent border-none text-white px-4 py-4 focus:ring-0 placeholder-gray-600 font-mono text-sm">
                <?php if ($search_query): ?>
                    <a href="/reports" class="pr-4 text-xs text-gray-500 hover:text-white uppercase font-mono">Clear</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<!-- Results Meta -->
<div class="container mb-8 border-b border-gray-800 pb-4 flex justify-between items-end">
    <div class="text-xs font-mono text-gray-500">
        QUERY_STATUS: <span class="text-neon">READY</span>
    </div>
    <div class="text-xs font-mono text-gray-500">
        DISPLAYING <span class="text-white"><?= count($reports) ?></span> RECORDS
        <?php if ($search_query): ?>
            MATCHING "<?= htmlspecialchars($search_query) ?>"
        <?php endif; ?>
    </div>
</div>

<!-- Report Grid -->
<div class="container mb-20">
    <?php if (empty($reports)): ?>
        <div class="text-center py-20 border border-dashed border-gray-800 rounded-lg">
            <div class="text-4xl mb-4">📂</div>
            <h3 class="text-white font-bold mb-2">No Reports Found</h3>
            <p class="text-gray-500 text-sm">Try adjusting your search filters.</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($reports as $r): ?>
                <?php
                $tags = json_decode($r['tags'] ?? '[]', true) ?? [];
                $primary_tag = $tags[0] ?? 'GENERAL';
                ?>
                <a href="/research/<?= $r['slug'] ?>" class="block h-full no-underline">
                    <div class="card-terminal p-6">
                        <div>
                            <div class="flex justify-between items-start mb-4">
                                <span class="tag-terminal"><?= htmlspecialchars($primary_tag) ?></span>
                                <span
                                    class="text-xs font-mono text-gray-500"><?= date('M d', strtotime($r['published_at'])) ?></span>
                            </div>
                            <h3 class="text-lg font-bold text-white mb-3 leading-tight group-hover:text-neon transition-colors">
                                <?= $r['title'] ?>
                            </h3>
                            <p class="text-sm text-gray-400 line-clamp-3 mb-4">
                                <?= $r['summary'] ?>
                            </p>
                        </div>
                        <div class="pt-4 border-t border-gray-800 flex justify-between items-center bg-transparent">
                            <span class="text-[10px] text-gray-600 font-mono uppercase">ID: RPT-<?= $r['id'] ?></span>
                            <span class="text-neon text-xs font-mono hover:underline">ACCESS >></span>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Pagination -->
<div class="container pb-20 flex justify-center">
    <div class="inline-flex rounded-md shadow-sm" role="group">
        <?php if ($current_page > 1): ?>
            <a href="?page=<?= $current_page - 1 ?><?= $search_query ? '&q=' . urlencode($search_query) : '' ?>"
                class="pagination-btn px-4 py-2 rounded-l-md border-r-0">
                &larr; PREV
            </a>
        <?php else: ?>
            <button disabled class="pagination-btn px-4 py-2 rounded-l-md border-r-0 opacity-50 cursor-not-allowed">&larr;
                PREV</button>
        <?php endif; ?>

        <div class="pagination-btn px-4 py-2 border-x-0 text-white bg-gray-900 border-gray-800">
            PAGE <?= $current_page ?> / <?= $total_pages ?>
        </div>

        <?php if ($current_page < $total_pages): ?>
            <a href="?page=<?= $current_page + 1 ?><?= $search_query ? '&q=' . urlencode($search_query) : '' ?>"
                class="pagination-btn px-4 py-2 rounded-r-md border-l-0">
                NEXT &rarr;
            </a>
        <?php else: ?>
            <button disabled class="pagination-btn px-4 py-2 rounded-r-md border-l-0 opacity-50 cursor-not-allowed">NEXT
                &rarr;</button>
        <?php endif; ?>
    </div>
</div>