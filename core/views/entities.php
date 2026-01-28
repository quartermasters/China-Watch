<main class="container py-8">

    <div class="flex justify-between items-end mb-8 border-b border-gray-800 pb-4 flex-wrap gap-4">
        <div>
            <h1 class="font-headline text-3xl text-white mb-2">Analysis Topics</h1>
            <p class="text-gray-500 text-sm font-mono uppercase tracking-widest">// SUBJECT MATTER INDEX</p>
        </div>

        <form action="/topics" method="GET" class="flex relative">
            <input type="text" name="q" value="<?= htmlspecialchars($search_query) ?>" placeholder="Filter topics..."
                class="bg-[var(--bg-light)] border border-[var(--border-light)] text-white px-4 py-2 rounded-l-md focus:outline-none focus:border-[var(--brand-primary)] font-mono text-sm w-64">
            <button type="submit"
                class="bg-[var(--brand-primary)] text-black font-bold px-4 py-2 rounded-r-md hover:brightness-110 transition-all font-mono text-sm uppercase">
                Search
            </button>
        </form>
    </div>

    <!-- Topics Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <?php if (empty($topics)): ?>
            <div class="col-span-full text-center py-20 border border-dashed border-gray-800 rounded-lg">
                <div class="text-4xl mb-4 opacity-50">📂</div>
                <h3 class="text-white font-bold mb-2">No Topics Found</h3>
                <p class="text-gray-500 text-sm">Try broadening your search.</p>
            </div>
        <?php else: ?>
            <?php foreach ($topics as $name => $count): ?>
                <a href="/research?tag=<?= urlencode($name) ?>" class="block group no-underline">
                    <div
                        class="flex justify-between items-center p-4 bg-[var(--bg-card)] border border-[var(--border-light)] rounded hover:border-[var(--brand-primary)] transition-all h-full">
                        <span class="text-white font-mono text-sm group-hover:text-[var(--brand-primary)]">
                            <?= htmlspecialchars($name) ?>
                        </span>
                        <span
                            class="text-[10px] text-gray-500 font-mono border border-gray-800 px-2 py-0.5 rounded-full bg-black">
                            <?= $count ?>
                        </span>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</main>