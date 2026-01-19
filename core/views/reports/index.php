<div class="archive-header text-center py-16">
    <h1 class="text-4xl font-mono mb-4">INTELLIGENCE ARCHIVE</h1>
    <p class="text-[var(--text-secondary)]">Automated Analysis & Strategic Briefings</p>
</div>

<div class="bento-grid" style="grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));">
    <?php foreach ($reports as $r): ?>
        <a href="/reports/<?= $r['slug'] ?>" class="tile block" style="text-decoration: none; color: inherit;">
            <div class="text-xs font-mono text-[var(--signal-blue)] mb-2">
                <?= date('M d, Y', strtotime($r['published_at'])) ?>
            </div>
            <h2 class="text-xl font-bold mb-3 hover:text-[var(--signal-red)] transition-colors">
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