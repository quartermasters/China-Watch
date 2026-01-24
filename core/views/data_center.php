<main class="page-container" style="max-width: 1200px; margin: 0 auto; padding: 2rem;">

    <div class="text-center mb-12">
        <h1 class="font-headline text-4xl mb-4" style="color: var(--text-primary);">Data Center</h1>
        <p style="color: var(--text-secondary); max-width: 600px; margin: 0 auto;">
            Key economic indicators and metrics tracking China's economy in real-time.
        </p>
    </div>

    <!-- Key Metrics Grid -->
    <div class="metrics-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
        <?php foreach ($signals as $key => $signal):
            $trendClass = $signal['trend'] >= 0 ? 'trend-up' : 'trend-down';
            $trendIcon = $signal['trend'] >= 0 ? '+' : '';
        ?>
            <div class="metric-card" style="background: var(--bg-white); border: 1px solid var(--border-light); border-radius: 8px; padding: 1.5rem;">
                <div style="color: var(--text-muted); font-size: 0.875rem; margin-bottom: 0.5rem;">
                    <?= $signal['label'] ?>
                </div>
                <div style="display: flex; align-items: baseline; gap: 1rem;">
                    <span style="font-size: 2rem; font-weight: 700; color: var(--text-primary);">
                        <?= $signal['value'] ?>
                    </span>
                    <span class="<?= $trendClass ?>" style="font-size: 0.875rem; font-weight: 500;">
                        <?= $trendIcon ?><?= $signal['trend'] ?>%
                    </span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Data Categories -->
    <section class="data-categories" style="margin-top: 3rem;">
        <h2 class="font-headline text-2xl mb-6" style="color: var(--text-primary);">Data Categories</h2>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
            <div class="category-card" style="background: var(--bg-light); border-radius: 8px; padding: 1.5rem;">
                <h3 style="color: var(--text-primary); font-weight: 600; margin-bottom: 0.5rem;">Economic Indicators</h3>
                <p style="color: var(--text-secondary); font-size: 0.875rem;">GDP, industrial production, retail sales, and investment data from official Chinese sources.</p>
            </div>

            <div class="category-card" style="background: var(--bg-light); border-radius: 8px; padding: 1.5rem;">
                <h3 style="color: var(--text-primary); font-weight: 600; margin-bottom: 0.5rem;">Trade & Commerce</h3>
                <p style="color: var(--text-secondary); font-size: 0.875rem;">Import/export volumes, trade balances, and port activity metrics.</p>
            </div>

            <div class="category-card" style="background: var(--bg-light); border-radius: 8px; padding: 1.5rem;">
                <h3 style="color: var(--text-primary); font-weight: 600; margin-bottom: 0.5rem;">Financial Markets</h3>
                <p style="color: var(--text-secondary); font-size: 0.875rem;">Currency rates, stock indices, bond yields, and foreign reserves.</p>
            </div>

            <div class="category-card" style="background: var(--bg-light); border-radius: 8px; padding: 1.5rem;">
                <h3 style="color: var(--text-primary); font-weight: 600; margin-bottom: 0.5rem;">Policy Tracker</h3>
                <p style="color: var(--text-secondary); font-size: 0.875rem;">Regulatory changes, policy announcements, and government initiatives.</p>
            </div>
        </div>
    </section>

    <!-- Methodology Note -->
    <section style="margin-top: 3rem; padding: 1.5rem; background: var(--bg-light); border-radius: 8px;">
        <h3 style="color: var(--text-primary); font-weight: 600; margin-bottom: 0.5rem;">Data Sources & Methodology</h3>
        <p style="color: var(--text-secondary); font-size: 0.875rem; line-height: 1.6;">
            Our data is aggregated from official Chinese government sources (NBS, MOFCOM, PBOC),
            international organizations (IMF, World Bank, WTO), and proprietary research.
            All data is verified and cross-referenced before publication.
            <a href="/methodology" style="color: var(--brand-primary);">Learn more about our methodology</a>.
        </p>
    </section>

</main>

<style>
    .trend-up {
        color: #10B981;
    }

    .trend-down {
        color: #EF4444;
    }

    .metric-card:hover {
        border-color: var(--brand-primary);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .category-card:hover {
        background: var(--bg-white);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }
</style>
