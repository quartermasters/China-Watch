<main class="page-container" style="max-width: 900px; margin: 0 auto; padding: 2rem;">

    <!-- Breadcrumb -->
    <div style="margin-bottom: 2rem;">
        <a href="/topics" style="color: var(--text-muted); text-decoration: none;">&larr; Back to Topics</a>
    </div>

    <!-- Header -->
    <div class="entity-header">
        <span class="type-tag">
            <?= htmlspecialchars($entity['type']) ?>
        </span>
        <h1 class="title">
            <?= htmlspecialchars($entity['name']) ?>
        </h1>
        <p class="meta">First covered:
            <?= date('M j, Y', strtotime($entity['created_at'])) ?>
        </p>
    </div>

    <!-- Reports Timeline -->
    <div style="margin-top: 3rem;">
        <h2 class="font-headline text-xl" style="color: var(--text-secondary); margin-bottom: 2rem;">
            Related Publications (<?= count($reports) ?>)
        </h2>

        <?php if (empty($reports)): ?>
            <div class="empty-state">
                <p>No publications currently linked to this topic.</p>
            </div>
        <?php else: ?>
            <div class="timeline">
                <?php foreach ($reports as $report): ?>
                    <div class="timeline-item">
                        <div class="date">
                            <?= date('M d, Y', strtotime($report['published_at'])) ?>
                        </div>
                        <div class="content">
                            <h3 class="report-title">
                                <a href="/research/<?= $report['slug'] ?>">
                                    <?= htmlspecialchars($report['title']) ?>
                                </a>
                            </h3>
                            <p class="summary">
                                <?= htmlspecialchars($report['summary']) ?>
                            </p>
                            <?php
                            $tags = json_decode($report['tags'], true) ?? [];
                            foreach ($tags as $tag): ?>
                                <span class="tag"><?= htmlspecialchars($tag) ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

</main>

<style>
    .entity-header {
        background: var(--bg-white);
        padding: 2rem;
        border-radius: 8px;
        border: 1px solid var(--border-light);
        box-shadow: var(--shadow-card);
    }

    .type-tag {
        background: var(--brand-primary);
        color: white;
        padding: 4px 12px;
        border-radius: 4px;
        font-size: 0.8em;
        text-transform: uppercase;
        font-weight: 500;
    }

    .title {
        font-size: 2.5rem;
        margin: 1rem 0 0.5rem 0;
        color: var(--text-primary);
        font-family: var(--font-headline);
    }

    .meta {
        color: var(--text-muted);
        font-size: 0.9rem;
    }

    /* Timeline */
    .timeline {
        position: relative;
        border-left: 2px solid var(--border-light);
        padding-left: 2rem;
    }

    .timeline-item {
        margin-bottom: 3rem;
        position: relative;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -2.6rem;
        top: 0.5rem;
        width: 1rem;
        height: 1rem;
        background: var(--bg-white);
        border: 2px solid var(--brand-primary);
        border-radius: 50%;
    }

    .date {
        color: var(--brand-primary);
        font-weight: 600;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .report-title a {
        color: var(--text-primary);
        text-decoration: none;
        font-size: 1.2rem;
        font-weight: 600;
    }

    .report-title a:hover {
        color: var(--brand-primary);
    }

    .summary {
        color: var(--text-secondary);
        line-height: 1.6;
        margin-bottom: 1rem;
    }

    .tag {
        display: inline-block;
        color: var(--text-muted);
        font-size: 0.85rem;
        margin-right: 1rem;
        background: var(--bg-light);
        padding: 2px 8px;
        border-radius: 4px;
    }
</style>
