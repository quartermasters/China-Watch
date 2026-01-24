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
        "<?= !empty($report['featured_image']) ? htmlspecialchars($report['featured_image']) : 'https://chinawatch.blog/public/assets/og-default.jpg' ?>"
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
      "articleSection": "Intelligence",
      "speakable": {
        "@type": "SpeakableSpecification",
        "cssSelector": [".report-content h1", ".report-content h2", ".report-content p:first-of-type"]
      },
      "mainEntityOfPage": {
        "@type": "WebPage",
        "@id": "https://chinawatch.blog/reports/<?= $report['slug'] ?>"
      }
    }
    </script>

    <!-- BreadcrumbList Schema -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "BreadcrumbList",
      "itemListElement": [
        {
          "@type": "ListItem",
          "position": 1,
          "name": "Home",
          "item": "https://chinawatch.blog/"
        },
        {
          "@type": "ListItem",
          "position": 2,
          "name": "Intelligence Archive",
          "item": "https://chinawatch.blog/reports"
        },
        {
          "@type": "ListItem",
          "position": 3,
          "name": "<?= addslashes($report['title']) ?>",
          "item": "https://chinawatch.blog/reports/<?= $report['slug'] ?>"
        }
      ]
    }
    </script>

    <article class="prose prose-invert lg:prose-xl">
        <header class="mb-10 border-b border-[var(--border-subtle)] pb-8">
            <div class="flex items-center gap-4 text-xs font-mono text-[var(--signal-blue)] mb-4">
                <span>// CLASSIFIED: PUBLIC</span>
                <time datetime="<?= date('c', strtotime($report['published_at'])) ?>">
                    <?= date('Y-m-d H:i', strtotime($report['published_at'])) ?>
                </time>
                <span><?= $reading_time ?? 5 ?> MIN READ</span>
                <span>VIEWS: <?= $report['views'] ?></span>
            </div>

            <h1 class="text-4xl md:text-5xl font-bold font-ui mb-6 leading-tight tracking-tight">
                <?= $report['title'] ?>
            </h1>

            <p class="text-xl text-[var(--text-secondary)] leading-relaxed font-serif italic">
                <?= $report['summary'] ?>
            </p>
        </header>

        <?php if (!empty($report['featured_image'])):
            // Smart crop with face detection via Cloudinary
            $cloudName = defined('CLOUDINARY_CLOUD_NAME') ? CLOUDINARY_CLOUD_NAME : null;
            $originalImage = $report['featured_image'];

            if ($cloudName && !str_contains($originalImage, 'cloudinary.com')) {
                // Wrap in Cloudinary fetch URL with face detection
                $smartImage = "https://res.cloudinary.com/{$cloudName}/image/fetch/c_fill,g_face,w_1200,h_675,q_auto,f_auto/" . urlencode($originalImage);
            } else {
                $smartImage = $originalImage;
            }
        ?>
        <figure class="featured-image mb-10 -mx-4 md:-mx-8 overflow-hidden rounded-lg">
            <img src="<?= htmlspecialchars($smartImage) ?>"
                 alt="<?= htmlspecialchars($report['title']) ?>"
                 style="width: 100%; height: auto; max-height: 450px; object-fit: cover;"
                 loading="lazy"
                 onerror="this.src='<?= htmlspecialchars($originalImage) ?>'; this.style.objectPosition='top';">
        </figure>
        <?php endif; ?>

        <div class="report-content text-gray-300 leading-8 font-ui text-lg">
            <?= $report['content'] ?>
        </div>

        <footer class="mt-12 pt-8 border-t border-[var(--border-subtle)]">
            <div class="flex flex-wrap gap-2">
                <?php
                $tags = json_decode($report['tags'], true) ?? [];
                foreach ($tags as $tag): ?>
                    <a href="/tag/<?= urlencode(strtolower($tag)) ?>"
                        class="px-3 py-1 bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-xs font-mono rounded-full hover:border-[var(--signal-blue)] hover:text-[var(--signal-blue)] transition-colors">
                        #<?= strtoupper(htmlspecialchars($tag)) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </footer>

        <!-- Data Sources Section (GEO Optimization) -->
        <section class="data-provenance mt-8 pt-8 border-t border-[var(--border-subtle)]">
            <h3 class="font-mono text-sm text-[var(--text-secondary)] mb-4">// DATA PROVENANCE</h3>
            <div class="bg-[var(--bg-surface)] rounded-lg p-5 text-sm space-y-3">
                <?php if (!empty($report['source_url'])): ?>
                    <p class="flex items-start gap-2">
                        <span class="text-[var(--signal-blue)] font-mono">PRIMARY SOURCE:</span>
                        <a href="<?= htmlspecialchars($report['source_url']) ?>" target="_blank" rel="noopener noreferrer"
                            class="text-[var(--text-secondary)] hover:text-[var(--signal-blue)] hover:underline break-all">
                            <?= parse_url($report['source_url'], PHP_URL_HOST) ?? 'External Source' ?>
                        </a>
                    </p>
                <?php endif; ?>

                <p class="flex items-start gap-2">
                    <span class="text-[var(--signal-blue)] font-mono">PUBLISHED:</span>
                    <time datetime="<?= date('c', strtotime($report['published_at'])) ?>"
                        class="text-[var(--text-secondary)]">
                        <?= date('F j, Y \a\t H:i', strtotime($report['published_at'])) ?> UTC
                    </time>
                </p>
                <p class="flex items-start gap-2">
                    <span class="text-[var(--signal-blue)] font-mono">CLASSIFICATION:</span>
                    <span class="text-[var(--text-secondary)]">PUBLIC // OPEN SOURCE INTELLIGENCE</span>
                </p>
            </div>
        </section>

        <?php if (!empty($related_reports)): ?>
            <div class="mt-16 pt-8 border-t border-[var(--border-subtle)]">
                <h3 class="font-mono text-[var(--signal-amber)] text-sm mb-6">// RELATED INTELLIGENCE</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <?php foreach ($related_reports as $related): ?>
                        <a href="/reports/<?= $related['slug'] ?>"
                            class="group block p-4 rounded-lg bg-[var(--bg-surface)] hover:bg-[var(--bg-glass)] transition-all border border-transparent hover:border-[var(--border-subtle)]">
                            <span
                                class="text-xs font-mono text-[var(--text-muted)] mb-2 block"><?= date('M d, Y', strtotime($related['published_at'])) ?></span>
                            <h4
                                class="text-white group-hover:text-[var(--signal-blue)] font-bold leading-tight transition-colors">
                                <?= $related['title'] ?>
                            </h4>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
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