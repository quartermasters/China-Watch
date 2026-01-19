<div class="report-container" style="max-width: 800px; margin: 0 auto; padding: 2rem 1rem;">

    <div class="mb-8">
        <a href="/reports" class="text-[var(--text-secondary)] hover:text-white font-mono text-sm">&larr; BACK TO
            ARCHIVE</a>
    </div>

    <article class="prose prose-invert lg:prose-xl">
        <header class="mb-10 border-b border-[var(--border-subtle)] pb-8">
            <div class="flex items-center gap-4 text-xs font-mono text-[var(--signal-blue)] mb-4">
                <span>// CLASSIFIED: PUBLIC</span>
                <span>
                    <?= date('Y-m-d H:i', strtotime($report['published_at'])) ?>
                </span>
                <span>VIEWS:
                    <?= $report['views'] ?>
                </span>
            </div>

            <h1 class="text-4xl md:text-5xl font-bold font-ui mb-6 leading-tight tracking-tight">
                <?= $report['title'] ?>
            </h1>

            <p class="text-xl text-[var(--text-secondary)] leading-relaxed font-serif italic">
                <?= $report['summary'] ?>
            </p>
        </header>

        <div class="report-content text-gray-300 leading-8 font-ui text-lg">
            <?= $report['content'] ?>
        </div>

        <footer class="mt-12 pt-8 border-t border-[var(--border-subtle)]">
            <div class="flex gap-2">
                <?php
                $tags = json_decode($report['tags'], true) ?? [];
                foreach ($tags as $tag): ?>
                    <span
                        class="px-3 py-1 bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs font-mono rounded-full">
                        #
                        <?= strtoupper($tag) ?>
                    </span>
                <?php endforeach; ?>
            </div>
        </footer>
    </article>

</div>

<style>
    /* Typography Overrides for Article Readability */
    .report-content p {
        margin-bottom: 1.5rem;
    }

    .report-content h2 {
        color: white;
        font-size: 1.8rem;
        font-weight: 700;
        margin-top: 2.5rem;
        margin-bottom: 1rem;
    }

    .report-content h3 {
        color: var(--text-secondary);
        font-size: 1.4rem;
        font-weight: 600;
        margin-top: 2rem;
        margin-bottom: 1rem;
    }

    .report-content ul {
        list-style-type: disc;
        padding-left: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .report-content li {
        margin-bottom: 0.5rem;
    }
</style>