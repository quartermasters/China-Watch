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

    <!-- Topic Galaxy Grid -->
    <style>
        .topic-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 12px;
            grid-auto-flow: dense;
        }

        .topic-card {
            background: var(--bg-card);
            border: 1px solid var(--border-light);
            border-radius: 6px;
            padding: 1rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .topic-card:hover {
            border-color: var(--brand-primary);
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(6, 182, 212, 0.15);
            background: linear-gradient(180deg, var(--bg-card) 0%, rgba(6, 182, 212, 0.05) 100%);
        }

        .topic-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        }

        .topic-lg {
            grid-column: span 2;
            grid-row: span 2;
            background: linear-gradient(135deg, rgba(6, 182, 212, 0.05) 0%, var(--bg-card) 100%);
            border-color: rgba(6, 182, 212, 0.3);
        }

        .topic-lg .topic-name {
            font-size: 1.5rem;
            color: white;
        }

        .topic-md {
            grid-column: span 2;
        }

        .topic-md .topic-name {
            font-size: 1.1rem;
            color: #e2e8f0;
        }

        .topic-sm .topic-name {
            font-size: 0.9rem;
            color: #94a3b8;
        }

        .topic-count {
            font-family: var(--font-mono);
            background: rgba(0, 0, 0, 0.3);
            border-radius: 4px;
            padding: 2px 6px;
            font-size: 0.7rem;
            color: var(--text-muted);
            align-self: flex-start;
            margin-top: 8px;
        }

        .topic-lg .topic-count {
            background: var(--brand-primary);
            color: black;
            font-weight: bold;
        }

        @media (max-width: 640px) {

            .topic-lg,
            .topic-md {
                grid-column: span 1;
                grid-row: span 1;
            }
        }
    </style>

    <div class="topic-grid">
        <?php if (empty($topics)): ?>
            <div class="col-span-full text-center py-20 border border-dashed border-gray-800 rounded-lg">
                <div class="text-4xl mb-4 opacity-50">📂</div>
                <h3 class="text-white font-bold mb-2">No Topics Found</h3>
                <p class="text-gray-500 text-sm">Try broadening your search.</p>
            </div>
        <?php else: ?>
            <?php
            $i = 0;
            foreach ($topics as $name => $count):
                // Determine Size Class based on rank/count
                $sizeClass = 'topic-sm';
                if ($i < 2)
                    $sizeClass = 'topic-lg';      // Top 2: Large Square
                elseif ($i < 8)
                    $sizeClass = 'topic-md';  // Next 6: Wide Rectangle
                $i++;
                ?>
                <a href="/research?tag=<?= urlencode($name) ?>" class="topic-card <?= $sizeClass ?>">
                    <span class="topic-name font-bold leading-tight">
                        <?= htmlspecialchars($name) ?>
                    </span>
                    <span class="topic-count">
                        <?= $count ?> RESOURCES
                    </span>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</main>