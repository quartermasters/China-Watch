# China Watch Design Fix Plan
## Resolving White-on-White and Theme Compatibility Issues

**Created:** January 24, 2026
**Status:** Pending Implementation

---

## Problem Summary

The CSS has been updated to a professional white-background news design (v3.1), but the PHP view templates still use the old dark "terminal" theme's:
1. CSS class names (`.tile`, `.bento-grid`, `.text-green`, etc.)
2. CSS variables (`--signal-red`, `--bg-void`, `--bg-surface`)
3. Layout structure (sidebar, ticker panel, 3-column grid)

This causes **critical visibility issues** like:
- White/light text on white background (invisible)
- Missing styles for old class names
- Broken layout due to missing grid definitions

---

## Issues Identified

### 1. Missing CSS Variables (Used by Templates)

| Old Variable | Old Value | New Equivalent | Fix |
|--------------|-----------|----------------|-----|
| `--signal-red` | `#FF453A` | `--brand-primary` | Add alias |
| `--signal-blue` | `#0A84FF` | N/A | Add variable |
| `--signal-green` | `#30D158` | N/A | Add variable |
| `--signal-amber` | `#FFD60A` | N/A | Add variable |
| `--bg-void` | `#030304` | `--bg-dark` | Add alias |
| `--bg-surface` | `rgba(20,20,25,0.4)` | `--bg-light` | Add alias |
| `--bg-glass` | `rgba(10,10,12,0.85)` | N/A | Add variable |
| `--border-subtle` | `rgba(255,255,255,0.08)` | `--border-light` | Add alias |
| `--border-color` | N/A | `--border-medium` | Add alias |
| `--font-ui` | Inter | `--font-body` | Add alias |

### 2. Missing CSS Classes (Used by Templates)

| Old Class | Purpose | Fix |
|-----------|---------|-----|
| `.tile` | Card container | Add styles |
| `.bento-grid` | Dashboard grid | Add styles |
| `.text-red` | Red text | Add with dark-safe color |
| `.text-green` | Green text | Add with dark-safe color |
| `.text-amber` | Amber text | Add with dark-safe color |
| `.text-blue` | Blue text | Add with dark-safe color |
| `.text-secondary` | Secondary text | Already exists but may need check |
| `.font-mono` | Monospace font | Add utility |
| `.font-ui` | UI font | Add utility |
| `.label` | Label text | Add styles |
| `.value` | Data value | Add styles |
| `.trend` | Trend indicator | Add styles |
| `.ticker-panel` | Ticker sidebar | Add styles |
| `.ticker-list` | Ticker items | Add styles |
| `.app-footer` | Footer | Map to `.footer` |
| `.header` (old) | Header container | Already exists |
| `.main-nav` | Navigation | Already exists |
| `.nav-link` | Nav links | Already exists |

### 3. Layout Issues

| Issue | Location | Fix |
|-------|----------|-----|
| 3-column grid layout | layout.php | Update to new structure |
| Ticker panel | layout.php | Remove or restyle |
| AI Widget | layout.php | Restyle for light theme |
| Old header structure | layout.php | Update to new header |
| Old footer structure | layout.php | Update to new footer |

### 4. Color Visibility Issues

| Element | Problem | Fix |
|---------|---------|-----|
| H2 headings | May be using white color | Ensure dark text |
| `.text-secondary` | Was light, now dark | Verify all usages |
| `.text-muted` | Was light, now dark | Verify all usages |
| Form inputs | May have dark backgrounds | Style for light theme |
| Cards/tiles | May have dark backgrounds | Style for light theme |

---

## Fix Strategy

### Option A: Add Compatibility Layer (Quick Fix)
Add old variable aliases and class definitions to CSS for backwards compatibility.

**Pros:** Fast, minimal template changes
**Cons:** CSS bloat, maintains old patterns

### Option B: Update All Templates (Clean Fix)
Update all PHP templates to use new class names and structure.

**Pros:** Clean codebase, modern patterns
**Cons:** More work, higher risk of errors

### Recommended: Hybrid Approach
1. Add compatibility CSS for critical variables
2. Update layout.php to new structure
3. Update other templates progressively

---

## Implementation Plan

### Phase 1: CSS Compatibility Layer (Immediate)

Add to `main.css`:

```css
/* ========================================
   BACKWARDS COMPATIBILITY LAYER
   For old template classes
   ======================================== */

/* Old color variables */
:root {
    --signal-red: #DC2626;
    --signal-blue: #2563EB;
    --signal-green: #059669;
    --signal-amber: #D97706;
    --bg-void: #111827;
    --bg-surface: #F3F4F6;
    --bg-glass: rgba(255,255,255,0.9);
    --bg-card-hover: #F9FAFB;
    --border-subtle: #E5E7EB;
    --border-color: #D1D5DB;
    --font-ui: var(--font-body);
}

/* Old color utilities */
.text-red { color: #DC2626; }
.text-green { color: #059669; }
.text-amber { color: #D97706; }
.text-blue { color: #2563EB; }
.text-white { color: #FFFFFF; }

/* Font utilities */
.font-mono { font-family: var(--font-mono); }
.font-ui { font-family: var(--font-body); }
.font-bold { font-weight: 700; }

/* Tile/Card component */
.tile {
    background: var(--bg-white);
    border: 1px solid var(--border-light);
    border-radius: var(--radius-lg);
    padding: var(--spacing-6);
    box-shadow: var(--shadow-card);
}

.tile:hover {
    box-shadow: var(--shadow-md);
}

.tile.wide { grid-column: span 2; }
.tile.tall { grid-row: span 2; }

/* Bento grid */
.bento-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: var(--spacing-6);
    padding: var(--spacing-6);
}

/* Data display */
.label {
    font-size: var(--text-xs);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--text-muted);
    margin-bottom: var(--spacing-2);
}

.value {
    font-size: var(--text-3xl);
    font-weight: 700;
    color: var(--text-primary);
}

.trend {
    font-size: var(--text-sm);
    font-weight: 600;
}

/* Ticker panel */
.ticker-panel {
    background: var(--bg-light);
    border-left: 1px solid var(--border-light);
    padding: var(--spacing-6);
    max-height: 100vh;
    overflow-y: auto;
}

.ticker-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.ticker-list li {
    padding: var(--spacing-3) 0;
    border-bottom: 1px solid var(--border-light);
    font-size: var(--text-sm);
    color: var(--text-body);
}

/* Old footer */
.app-footer {
    background: var(--bg-dark);
    color: rgba(255,255,255,0.7);
    padding: var(--spacing-8) var(--spacing-4);
    text-align: center;
}

.app-footer a {
    color: rgba(255,255,255,0.7);
}

.app-footer a:hover {
    color: white;
}

.footer-content {
    max-width: 1280px;
    margin: 0 auto;
}

.footer-links {
    display: flex;
    justify-content: center;
    gap: var(--spacing-4);
    margin-top: var(--spacing-4);
    flex-wrap: wrap;
}

.footer-links .divider {
    color: rgba(255,255,255,0.3);
}

/* Status indicator */
.status-indicator {
    font-family: var(--font-mono);
    font-size: var(--text-xs);
}

/* Old header adjustments */
.header .logo {
    font-family: var(--font-mono);
    font-weight: 700;
    font-size: var(--text-lg);
    color: var(--text-primary);
}

/* AI Widget for light theme */
#ai-widget {
    background: var(--bg-white) !important;
    border-color: var(--brand-primary) !important;
    box-shadow: var(--shadow-lg);
}

#ai-widget .chat-header {
    background: var(--brand-primary) !important;
    color: white;
}

#ai-widget #chat-body {
    background: var(--bg-white);
}

#ai-widget #chat-history {
    color: var(--text-body);
}

#ai-widget #chat-history .bot {
    color: var(--brand-primary) !important;
}

#ai-widget input {
    background: var(--bg-light) !important;
    color: var(--text-body) !important;
    border-top: 1px solid var(--border-light) !important;
}

#ai-widget button {
    background: var(--bg-light) !important;
    color: var(--brand-primary) !important;
}

/* Entity cards */
.entity-card {
    background: var(--bg-white);
    border: 1px solid var(--border-light);
    color: var(--text-primary);
}

.entity-card:hover {
    border-color: var(--brand-primary);
    background: var(--bg-light);
}

.entity-card .badge {
    background: var(--bg-light);
    color: var(--text-secondary);
}

/* Report content */
.report-content {
    color: var(--text-body);
}

.report-content h2 {
    color: var(--text-primary);
}

.report-content h3 {
    color: var(--text-primary);
}

/* Prose styling */
.prose {
    color: var(--text-body);
}

.prose h1, .prose h2, .prose h3, .prose h4 {
    color: var(--text-primary);
}

/* Archive header */
.archive-header {
    text-align: center;
    padding: var(--spacing-12) 0;
}

.archive-header h1 {
    color: var(--text-primary);
    font-family: var(--font-headline);
}

/* Empty states */
.empty-state {
    text-align: center;
    padding: var(--spacing-12);
    color: var(--text-muted);
}

/* Timeline styling */
.timeline {
    border-left-color: var(--border-light);
}

.timeline-item::before {
    background: var(--bg-white);
    border-color: var(--brand-primary);
}

.date {
    color: var(--brand-primary);
}

.report-title a {
    color: var(--text-primary);
}

.report-title a:hover {
    color: var(--brand-primary);
}

.summary {
    color: var(--text-secondary);
}

/* Entity header */
.entity-header {
    background: var(--bg-white);
    border: 1px solid var(--border-light);
}

.entity-header .title {
    color: var(--text-primary);
}

.type-tag {
    background: var(--brand-primary);
}
```

### Phase 2: Update layout.php

Replace old layout structure with new professional layout including:
- New header with red top border
- New navigation
- New footer
- Newsletter section
- Remove ticker panel and AI widget (or restyle)

### Phase 3: Update View Templates

Update each view file:
1. `dashboard.php` - New hero, article cards, data section
2. `reports/index.php` - Article grid layout
3. `reports/show.php` - Article page layout
4. `entities.php` - Entity card grid
5. `entity_detail.php` - Entity profile
6. `about.php`, `contact.php`, `methodology.php`, `privacy.php`, `terms.php` - Static page layouts

---

## Files Modified

### CSS
- [x] `/public_html/css/main.css` - Added comprehensive compatibility layer including:
  - Old color variables (--signal-red, --signal-blue, etc.)
  - Old utility classes (.text-red, .text-green, .font-mono, etc.)
  - Tile/card component styles
  - Layout compatibility (bento-grid, ticker-panel, app-footer)
  - Tailwind-style arbitrary value classes
  - Additional Tailwind utilities (flexbox, grid, spacing, etc.)

### Templates
- [x] `/core/views/layout.php` - Uses compatibility layer (no changes needed)
- [x] `/core/views/dashboard.php` - Uses compatibility layer (no changes needed)
- [x] `/core/views/reports/index.php` - Uses compatibility layer (no changes needed)
- [x] `/core/views/reports/show.php` - Fixed inline styles (white -> var(--text-primary))
- [x] `/core/views/entities.php` - Fixed inline styles and card styles
- [x] `/core/views/entity_detail.php` - Fixed inline styles for white background
- [x] `/core/views/about.php` - Fixed text-white -> var(--text-primary)
- [x] `/core/views/contact.php` - Uses compatibility layer (check needed)
- [x] `/core/views/methodology.php` - Fixed text-white -> var(--text-primary)
- [x] `/core/views/privacy.php` - Uses compatibility layer (CSS fixes applied)
- [x] `/core/views/terms.php` - Uses compatibility layer (CSS fixes applied)

---

## Testing Checklist

- [ ] All text is visible (no white-on-white)
- [ ] All headings have correct colors
- [ ] Cards and tiles have proper backgrounds
- [ ] Links are visible and have hover states
- [ ] Forms are styled correctly
- [ ] Navigation works on mobile
- [ ] Footer is visible and styled
- [ ] Charts and data visualizations work
- [ ] No JavaScript errors in console

---

## Priority Order

1. **Critical (Do First):** Add CSS compatibility layer
2. **High:** Update layout.php
3. **Medium:** Update dashboard.php
4. **Medium:** Update report pages
5. **Low:** Update entity pages
6. **Low:** Update static pages
