<main class="page-container" style="max-width: 1200px; margin: 0 auto; padding: 2rem;">

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 2rem;">
        <div>
            <h1 class="font-mono text-xl" style="color: var(--text-primary);">// KNOWLEDGE GRAPH</h1>
            <p style="color: var(--text-muted);">Tracked entities extracted from intelligence reports.</p>
        </div>

        <form action="/entities" method="GET" style="display:flex;">
            <input type="text" name="q" value="<?= htmlspecialchars($search_query) ?>" placeholder="Search entities..."
                style="background: var(--bg-surface); border: 1px solid var(--border-color); color: white; padding: 0.5rem 1rem; border-radius: 4px; outline:none;">
            <button type="submit"
                style="background: var(--signal-blue); color: white; border: none; padding: 0.5rem 1rem; margin-left: 0.5rem; border-radius: 4px; cursor: pointer;">
                FILTER
            </button>
        </form>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">

        <!-- Organizations -->
        <section>
            <h2 class="font-mono text-lg text-secondary"
                style="border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem; margin-bottom: 1rem;">
                ORGANIZATIONS
            </h2>
            <div class="entity-list">
                <?php if (empty($grouped_entities['ORG'])): ?>
                    <p style="color: var(--text-muted); font-style: italic;">No organizations found.</p>
                <?php else: ?>
                    <?php foreach ($grouped_entities['ORG'] as $ent): ?>
                        <a href="/entity/<?= $ent['id'] ?>" class="entity-card">
                            <span class="name">
                                <?= htmlspecialchars($ent['name']) ?>
                            </span>
                            <span class="badge">
                                <?= $ent['report_count'] ?>
                            </span>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <!-- People -->
        <section>
            <h2 class="font-mono text-lg text-secondary"
                style="border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem; margin-bottom: 1rem;">
                KEY FIGURES
            </h2>
            <div class="entity-list">
                <?php if (empty($grouped_entities['PERSON'])): ?>
                    <p style="color: var(--text-muted); font-style: italic;">No key figures found.</p>
                <?php else: ?>
                    <?php foreach ($grouped_entities['PERSON'] as $ent): ?>
                        <a href="/entity/<?= $ent['id'] ?>" class="entity-card">
                            <span class="name">
                                <?= htmlspecialchars($ent['name']) ?>
                            </span>
                            <span class="badge">
                                <?= $ent['report_count'] ?>
                            </span>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <!-- Locations -->
        <section>
            <h2 class="font-mono text-lg text-secondary"
                style="border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem; margin-bottom: 1rem;">
                LOCATIONS
            </h2>
            <div class="entity-list">
                <?php if (empty($grouped_entities['GPE'])): ?>
                    <p style="color: var(--text-muted); font-style: italic;">No locations found.</p>
                <?php else: ?>
                    <?php foreach ($grouped_entities['GPE'] as $ent): ?>
                        <a href="/entity/<?= $ent['id'] ?>" class="entity-card">
                            <span class="name">
                                <?= htmlspecialchars($ent['name']) ?>
                            </span>
                            <span class="badge">
                                <?= $ent['report_count'] ?>
                            </span>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

    </div>

</main>

<style>
    .entity-list {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .entity-card {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: var(--bg-surface);
        padding: 0.75rem 1rem;
        border-radius: 6px;
        border: 1px solid var(--border-subtle);
        color: var(--text-primary);
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .entity-card:hover {
        border-color: var(--signal-blue);
        transform: translateX(4px);
        background: var(--bg-card-hover);
    }

    .entity-card .name {
        font-weight: 500;
    }

    .entity-card .badge {
        background: rgba(255, 255, 255, 0.1);
        color: var(--text-muted);
        font-size: 0.8rem;
        padding: 2px 8px;
        border-radius: 12px;
        font-family: var(--font-mono);
    }
</style>