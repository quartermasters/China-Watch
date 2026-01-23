<div class="report-container" style="max-width: 800px; margin: 0 auto; padding: 2rem 1rem;">

    <nav aria-label="Breadcrumb" class="mb-8 font-mono text-sm text-[var(--text-secondary)]">
        <a href="/" class="hover:text-white">DASHBOARD</a>
        <span class="mx-2">/</span>
        <a href="/reports" class="hover:text-white">INTEL ARCHIVE</a>
        <span class="mx-2">/</span>
        <span class="text-[var(--signal-blue)]">REPORT #<?= $report['id'] ?></span>
    </nav>

    <!-- NewsArticle Schema (GEO Optimized) -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "NewsArticle",
      "headline": "<?= addslashes($report['title']) ?>",
      "image": [
        "https://chinawatch.blog/public/assets/og-default.jpg"
      ],
      "datePublished": "<?= date('c', strtotime($report['published_at'])) ?>",
      "dateModified": "<?= date('c', strtotime($report['published_at'])) ?>",
      "author": [{
          "@type": "Organization",
          "name": "China Watch Intelligence",
          "url": "https://chinawatch.blog"
      }],
      "publisher": {
          "@type": "Organization",
          "name": "China Watch",
          "logo": {
            "@type": "ImageObject",
            "url": "https://chinawatch.blog/public/assets/logo.png"
          }
      },
      "description": "<?= addslashes(strip_tags($report['summary'])) ?>",
      "articleSection": "Intelligence"
    }
    </script>

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