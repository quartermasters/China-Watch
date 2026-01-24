<main class="page-container" style="max-width: 900px; margin: 0 auto; padding: 2rem;">

    <!-- Breadcrumb -->
    <div style="margin-bottom: 2rem;">
        <a href="/entities" style="color: var(--text-muted); text-decoration: none;">&larr; Back to Knowledge Graph</a>
    </div>

    <!-- Header -->
    <div class="entity-header">
        <span class="type-tag">
            <?= htmlspecialchars($entity['type']) ?>
        </span>
        <h1 class="title">
            <?= htmlspecialchars($entity['name']) ?>
        </h1>
        <p class="meta">First tracked:
            <?= date('M j, Y', strtotime($entity['created_at'])) ?>
        </p>
    </div>

    <!-- Reports Timeline -->
    <div style="margin-top: 3rem;">
        <h2 class="font-mono text-lg" style="color: var(--text-secondary); margin-bottom: 2rem;">
            // ASSOCIATED INTELLIGENCE REPORTS (
            <?= count($reports) ?>)
        </h2>

        <?php if (empty($reports)): ?>
            <div class="empty-state">
                <p>No reports currently linked to this entity.</p>
            </div>
        <?php else: ?>
            <div class="timeline">
                <?php foreach ($reports as $report): ?>
                    <div class="timeline-item">
                        <div class="date font-mono">
                            <?= date('M d', strtotime($report['published_at'])) ?>
                        </div>
                        <div class="content">
                            <h3 class="report-title">
                                <a href="#" onclick="alert('Report Detail View (Phase 8.1)'); return false;">
                                    <?= htmlspecialchars($report['title']) ?>
                                </a>
                            </h3>
                            <p class="summary">
                                <?= htmlspecialchars($report['summary']) ?>
                            </p>
                            <?php
                            $tags = json_decode($report['tags'], true) ?? [];
                            foreach ($tags as $tag): ?>
                                <span class="tag">#
                                    <?= htmlspecialchars($tag) ?>
                                </span>
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
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.8em;
        font-family: var(--font-mono);
        text-transform: uppercase;
    }

    .title {
        font-size: 2.5rem;
        margin: 1rem 0 0.5rem 0;
        color: var(--text-primary);
    }

    .meta {
        color: var(--text-muted);
        font-family: var(--font-mono);
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
        font-weight: bold;
        margin-bottom: 0.5rem;
    }

    .report-title a {
        color: var(--text-primary);
        text-decoration: none;
        font-size: 1.2rem;
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
        font-family: var(--font-mono);
        font-size: 0.85rem;
        margin-right: 1rem;
    }
</style>