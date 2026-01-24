<main class="page-container" style="max-width: 1200px; margin: 0 auto; padding: 2rem;">

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1 class="font-headline text-3xl" style="color: var(--text-primary);">Topics & Issue Areas</h1>
            <p style="color: var(--text-muted);">Explore our research by subject area, organization, and key figures.</p>
        </div>

        <form action="/topics" method="GET" style="display:flex;">
            <input type="text" name="q" value="<?= htmlspecialchars($search_query) ?>" placeholder="Search topics..."
                style="background: var(--bg-light); border: 1px solid var(--border-light); color: var(--text-body); padding: 0.5rem 1rem; border-radius: 4px; outline:none;">
            <button type="submit"
                style="background: var(--brand-primary); color: white; border: none; padding: 0.5rem 1rem; margin-left: 0.5rem; border-radius: 4px; cursor: pointer;">
                Search
            </button>
        </form>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">

        <!-- Organizations -->
        <section>
            <h2 class="font-headline text-xl"
                style="border-bottom: 2px solid var(--brand-primary); padding-bottom: 0.5rem; margin-bottom: 1rem; color: var(--text-primary);">
                Organizations
            </h2>
            <div class="entity-list">
                <?php if (empty($grouped_entities['ORG'])): ?>
                    <p style="color: var(--text-muted); font-style: italic;">No organizations found.</p>
                <?php else: ?>
                    <?php foreach ($grouped_entities['ORG'] as $ent): ?>
                        <a href="/topic/<?= $ent['id'] ?>" class="entity-card">
                            <span class="name">
                                <?= htmlspecialchars($ent['name']) ?>
                            </span>
                            <span class="badge">
                                <?= $ent['report_count'] ?> articles
                            </span>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <!-- People -->
        <section>
            <h2 class="font-headline text-xl"
                style="border-bottom: 2px solid var(--brand-primary); padding-bottom: 0.5rem; margin-bottom: 1rem; color: var(--text-primary);">
                Key Figures
            </h2>
            <div class="entity-list">
                <?php if (empty($grouped_entities['PERSON'])): ?>
                    <p style="color: var(--text-muted); font-style: italic;">No key figures found.</p>
                <?php else: ?>
                    <?php foreach ($grouped_entities['PERSON'] as $ent): ?>
                        <a href="/topic/<?= $ent['id'] ?>" class="entity-card">
                            <span class="name">
                                <?= htmlspecialchars($ent['name']) ?>
                            </span>
                            <span class="badge">
                                <?= $ent['report_count'] ?> articles
                            </span>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <!-- Locations -->
        <section>
            <h2 class="font-headline text-xl"
                style="border-bottom: 2px solid var(--brand-primary); padding-bottom: 0.5rem; margin-bottom: 1rem; color: var(--text-primary);">
                Locations
            </h2>
            <div class="entity-list">
                <?php if (empty($grouped_entities['GPE'])): ?>
                    <p style="color: var(--text-muted); font-style: italic;">No locations found.</p>
                <?php else: ?>
                    <?php foreach ($grouped_entities['GPE'] as $ent): ?>
                        <a href="/topic/<?= $ent['id'] ?>" class="entity-card">
                            <span class="name">
                                <?= htmlspecialchars($ent['name']) ?>
                            </span>
                            <span class="badge">
                                <?= $ent['report_count'] ?> articles
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
        background: var(--bg-white);
        padding: 0.75rem 1rem;
        border-radius: 6px;
        border: 1px solid var(--border-light);
        color: var(--text-primary);
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .entity-card:hover {
        border-color: var(--brand-primary);
        transform: translateX(4px);
        background: var(--bg-light);
    }

    .entity-card .name {
        font-weight: 500;
        color: var(--text-primary);
    }

    .entity-card .badge {
        background: var(--bg-light);
        color: var(--text-muted);
        font-size: 0.75rem;
        padding: 2px 10px;
        border-radius: 12px;
    }
</style>
