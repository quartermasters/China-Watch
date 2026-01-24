<div class="report-container" style="max-width: 800px; margin: 0 auto; padding: 2rem 1rem;">

    <nav aria-label="Breadcrumb" class="mb-8 text-sm" style="color: var(--text-secondary);">
        <a href="/" style="color: var(--text-secondary); text-decoration: none;">Home</a>
        <span class="mx-2">/</span>
        <a href="/research" style="color: var(--text-secondary); text-decoration: none;">Research</a>
        <span class="mx-2">/</span>
        <span style="color: var(--brand-primary);"><?= htmlspecialchars(substr($report['title'], 0, 50)) ?>...</span>
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
          "name": "China Watch",
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
      "articleSection": "Research",
      "speakable": {
        "@type": "SpeakableSpecification",
        "cssSelector": [".report-content h1", ".report-content h2", ".report-content p:first-of-type"]
      },
      "mainEntityOfPage": {
        "@type": "WebPage",
        "@id": "https://chinawatch.blog/research/<?= $report['slug'] ?>"
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
          "name": "Research",
          "item": "https://chinawatch.blog/research"
        },
        {
          "@type": "ListItem",
          "position": 3,
          "name": "<?= addslashes($report['title']) ?>",
          "item": "https://chinawatch.blog/research/<?= $report['slug'] ?>"
        }
      ]
    }
    </script>

    <article class="prose lg:prose-xl">
        <header class="mb-10 border-b pb-8" style="border-color: var(--border-light);">
            <div class="flex items-center gap-4 text-sm mb-4" style="color: var(--text-muted);">
                <time datetime="<?= date('c', strtotime($report['published_at'])) ?>">
                    <?= date('F j, Y', strtotime($report['published_at'])) ?>
                </time>
                <span><?= $reading_time ?? 5 ?> min read</span>
                <span><?= number_format($report['views']) ?> views</span>
            </div>

            <h1 class="text-4xl md:text-5xl font-headline mb-6 leading-tight tracking-tight" style="color: var(--text-primary);">
                <?= $report['title'] ?>
            </h1>

            <p class="text-xl leading-relaxed" style="color: var(--text-secondary); font-style: italic;">
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

        <div class="report-content text-lg" style="color: var(--text-body); line-height: 1.8;">
            <?= $report['content'] ?>
        </div>

        <footer class="mt-12 pt-8" style="border-top: 1px solid var(--border-light);">
            <div class="flex flex-wrap gap-2">
                <?php
                $tags = json_decode($report['tags'], true) ?? [];
                foreach ($tags as $tag): ?>
                    <a href="/topics?q=<?= urlencode($tag) ?>"
                        class="px-3 py-1 text-xs rounded-full transition-colors"
                        style="background: var(--bg-light); border: 1px solid var(--border-light); color: var(--text-secondary);">
                        <?= htmlspecialchars($tag) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </footer>

        <!-- Data Sources Section -->
        <section class="data-provenance mt-8 pt-8" style="border-top: 1px solid var(--border-light);">
            <h3 class="text-sm mb-4" style="color: var(--text-secondary); font-weight: 600;">Sources & Methodology</h3>
            <div class="rounded-lg p-5 text-sm space-y-3" style="background: var(--bg-light);">
                <?php if (!empty($report['source_url'])): ?>
                    <p class="flex items-start gap-2">
                        <span style="color: var(--brand-primary); font-weight: 600;">Primary Source:</span>
                        <a href="<?= htmlspecialchars($report['source_url']) ?>" target="_blank" rel="noopener noreferrer"
                            style="color: var(--text-secondary);" class="hover:underline break-all">
                            <?= parse_url($report['source_url'], PHP_URL_HOST) ?? 'External Source' ?>
                        </a>
                    </p>
                <?php endif; ?>

                <p class="flex items-start gap-2">
                    <span style="color: var(--brand-primary); font-weight: 600;">Published:</span>
                    <time datetime="<?= date('c', strtotime($report['published_at'])) ?>"
                        style="color: var(--text-secondary);">
                        <?= date('F j, Y', strtotime($report['published_at'])) ?>
                    </time>
                </p>
                <p class="flex items-start gap-2">
                    <span style="color: var(--brand-primary); font-weight: 600;">Classification:</span>
                    <span style="color: var(--text-secondary);">Open Source Research</span>
                </p>
            </div>
        </section>

        <?php if (!empty($related_reports)): ?>
            <div class="mt-16 pt-8" style="border-top: 1px solid var(--border-light);">
                <h3 class="text-sm mb-6" style="color: var(--text-secondary); font-weight: 600;">Related Research</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <?php foreach ($related_reports as $related): ?>
                        <a href="/research/<?= $related['slug'] ?>"
                            class="group block p-4 rounded-lg transition-all"
                            style="background: var(--bg-light); border: 1px solid var(--border-light);">
                            <span class="text-xs mb-2 block" style="color: var(--text-muted);">
                                <?= date('M d, Y', strtotime($related['published_at'])) ?>
                            </span>
                            <h4 class="font-bold leading-tight" style="color: var(--text-primary);">
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
        color: var(--text-body);
    }

    .report-content h2 {
        color: var(--text-primary);
        font-size: 1.8rem;
        font-weight: 700;
        margin-top: 2.5rem;
        margin-bottom: 1rem;
        font-family: var(--font-headline);
    }

    .report-content h3 {
        color: var(--text-primary);
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
        color: var(--text-body);
    }

    .report-content a {
        color: var(--brand-primary);
        text-decoration: underline;
    }

    .report-content a:hover {
        opacity: 0.8;
    }

    /* Related research hover */
    .group:hover {
        border-color: var(--brand-primary) !important;
    }

    .group:hover h4 {
        color: var(--brand-primary) !important;
    }
</style>
