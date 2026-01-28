<?php
// Fallback for variables
$current_page = $current_page ?? 1;
$total_pages = $total_pages ?? 1;
$total_results = $total_results ?? 0;
$search_query = $search_query ?? '';
$current_limit = $current_limit ?? 25;
$current_sort = $current_sort ?? 'newest';
$current_tag = $current_tag ?? '';
$date_from = $date_from ?? '';
$date_to = $date_to ?? '';
$all_tags = $all_tags ?? [];

$sort_labels = [
    'newest' => 'Newest First',
    'oldest' => 'Oldest First',
    'views' => 'Most Viewed',
    'title_az' => 'Title A-Z',
    'title_za' => 'Title Z-A',
];
$limit_options = [25, 50, 100, 250, 500];
$from_record = $total_results > 0 ? ($current_page - 1) * $current_limit + 1 : 0;
$to_record = min($current_page * $current_limit, $total_results);
?>

<style>
    /* =============================================
   REPORTS ENGINE - NEXT-GEN DESIGN SYSTEM
   Self-contained styles with high specificity
   ============================================= */

    /* CSS Variables for Reports Engine */
    .reports-engine {
        --re-bg-dark: #0a0f1a;
        --re-bg-card: rgba(17, 24, 39, 0.8);
        --re-bg-glass: rgba(30, 41, 59, 0.5);
        --re-border: rgba(255, 255, 255, 0.08);
        --re-border-hover: rgba(255, 255, 255, 0.15);
        --re-text-primary: #f1f5f9;
        --re-text-secondary: #94a3b8;
        --re-text-muted: #64748b;
        --re-accent: #06b6d4;
        --re-accent-glow: rgba(6, 182, 212, 0.4);
        --re-accent-soft: rgba(6, 182, 212, 0.15);
        --re-danger: #ef4444;
        --re-success: #10b981;
        --re-radius-sm: 6px;
        --re-radius-md: 10px;
        --re-radius-lg: 14px;
        --re-radius-full: 9999px;
        --re-shadow-glow: 0 0 20px var(--re-accent-glow);
        --re-transition: 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Page Header */
    .reports-engine .re-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
        padding: 48px 0 24px;
        flex-wrap: wrap;
    }

    .reports-engine .re-header-left h1 {
        font-family: 'Inter', system-ui, sans-serif;
        font-size: 2rem;
        font-weight: 700;
        color: var(--re-text-primary);
        margin: 0 0 4px;
        letter-spacing: -0.02em;
    }

    .reports-engine .re-header-left p {
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.75rem;
        color: var(--re-text-muted);
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 0.1em;
    }

    /* View Mode Toggle */
    .reports-engine .re-view-toggle {
        display: flex;
        gap: 2px;
        padding: 4px;
        background: var(--re-bg-card);
        border: 1px solid var(--re-border);
        border-radius: var(--re-radius-md);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
    }

    .reports-engine .re-view-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background: transparent;
        border: none;
        border-radius: var(--re-radius-sm);
        color: var(--re-text-muted);
        cursor: pointer;
        transition: all var(--re-transition);
    }

    .reports-engine .re-view-btn:hover {
        color: var(--re-text-primary);
        background: rgba(255, 255, 255, 0.05);
    }

    .reports-engine .re-view-btn.active {
        color: var(--re-accent);
        background: var(--re-accent-soft);
        box-shadow: inset 0 0 0 1px var(--re-accent), var(--re-shadow-glow);
    }

    .reports-engine .re-view-btn svg {
        width: 20px;
        height: 20px;
    }

    /* Control Panel - Glassmorphism */
    .reports-engine .re-controls {
        background: var(--re-bg-glass);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid var(--re-border);
        border-radius: var(--re-radius-lg);
        padding: 20px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .reports-engine .re-controls::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
    }

    /* Control Rows */
    .reports-engine .re-control-row {
        display: flex;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    }

    .reports-engine .re-control-row+.re-control-row {
        margin-top: 16px;
        padding-top: 16px;
        border-top: 1px solid var(--re-border);
    }

    /* Search Input */
    .reports-engine .re-search-wrap {
        flex: 1;
        min-width: 250px;
        position: relative;
    }

    .reports-engine .re-search-wrap svg {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--re-text-muted);
        width: 18px;
        height: 18px;
        pointer-events: none;
        transition: color var(--re-transition);
    }

    .reports-engine .re-search {
        width: 100%;
        padding: 12px 16px 12px 44px;
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.875rem;
        background: var(--re-bg-dark);
        border: 1px solid var(--re-border);
        border-radius: var(--re-radius-md);
        color: var(--re-text-primary);
        outline: none;
        transition: all var(--re-transition);
    }

    .reports-engine .re-search::placeholder {
        color: var(--re-text-muted);
    }

    .reports-engine .re-search:focus {
        border-color: var(--re-accent);
        box-shadow: 0 0 0 3px var(--re-accent-soft), var(--re-shadow-glow);
    }

    .reports-engine .re-search:focus+svg,
    .reports-engine .re-search-wrap:focus-within svg {
        color: var(--re-accent);
    }

    /* Dropdowns */
    .reports-engine .re-select-group {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .reports-engine .re-select-wrap {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .reports-engine .re-select-label {
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: var(--re-text-muted);
    }

    .reports-engine .re-select {
        padding: 10px 36px 10px 12px;
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.8rem;
        background: var(--re-bg-dark);
        border: 1px solid var(--re-border);
        border-radius: var(--re-radius-sm);
        color: var(--re-text-primary);
        cursor: pointer;
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg width='12' height='8' viewBox='0 0 12 8' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1.5L6 6.5L11 1.5' stroke='%2364748b' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        transition: all var(--re-transition);
        min-width: 140px;
    }

    .reports-engine .re-select:hover {
        border-color: var(--re-border-hover);
    }

    .reports-engine .re-select:focus {
        outline: none;
        border-color: var(--re-accent);
        box-shadow: 0 0 0 3px var(--re-accent-soft);
    }

    /* Tag Chips */
    .reports-engine .re-tags-wrap {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        flex: 1;
    }

    .reports-engine .re-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.7rem;
        background: transparent;
        border: 1px solid var(--re-border);
        border-radius: var(--re-radius-full);
        color: var(--re-text-secondary);
        cursor: pointer;
        transition: all var(--re-transition);
        white-space: nowrap;
    }

    .reports-engine .re-tag:hover {
        border-color: var(--re-text-muted);
        color: var(--re-text-primary);
        transform: translateY(-1px);
    }

    .reports-engine .re-tag.active {
        background: var(--re-accent-soft);
        border-color: var(--re-accent);
        color: var(--re-accent);
        box-shadow: var(--re-shadow-glow);
    }

    .reports-engine .re-tag .count {
        font-size: 0.6rem;
        opacity: 0.6;
        margin-left: 2px;
    }

    .reports-engine .re-tag-more {
        border-style: dashed;
        opacity: 0.7;
    }

    /* Reset Button */
    .reports-engine .re-reset-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.7rem;
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.3);
        border-radius: var(--re-radius-full);
        color: var(--re-danger);
        cursor: pointer;
        transition: all var(--re-transition);
    }

    .reports-engine .re-reset-btn:hover {
        background: rgba(239, 68, 68, 0.2);
        border-color: var(--re-danger);
        transform: translateY(-1px);
    }

    /* Meta Bar */
    .reports-engine .re-meta-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        margin-bottom: 20px;
        border-bottom: 1px solid var(--re-border);
        flex-wrap: wrap;
        gap: 12px;
    }

    .reports-engine .re-meta-left {
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.75rem;
        color: var(--re-text-muted);
    }

    .reports-engine .re-meta-left .highlight {
        color: var(--re-text-primary);
    }

    .reports-engine .re-meta-left .accent {
        color: var(--re-accent);
        text-shadow: 0 0 8px var(--re-accent-glow);
    }

    /* Mini Pagination */
    .reports-engine .re-mini-pagination {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .reports-engine .re-page-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.875rem;
        background: var(--re-bg-card);
        border: 1px solid var(--re-border);
        border-radius: var(--re-radius-sm);
        color: var(--re-text-secondary);
        cursor: pointer;
        transition: all var(--re-transition);
    }

    .reports-engine .re-page-btn:hover:not(:disabled) {
        border-color: var(--re-accent);
        color: var(--re-accent);
    }

    .reports-engine .re-page-btn:disabled {
        opacity: 0.3;
        cursor: not-allowed;
    }

    .reports-engine .re-page-info {
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.75rem;
        color: var(--re-text-secondary);
        min-width: 100px;
        text-align: center;
    }

    /* Loading Skeleton */
    .reports-engine .re-skeleton {
        display: none;
    }

    .reports-engine .re-skeleton.loading {
        display: block;
    }

    .reports-engine .re-skeleton-card {
        background: var(--re-bg-card);
        border: 1px solid var(--re-border);
        border-radius: var(--re-radius-md);
        padding: 20px;
        margin-bottom: 16px;
    }

    .reports-engine .re-skeleton-line {
        height: 14px;
        background: linear-gradient(90deg, var(--re-bg-dark) 25%, rgba(255, 255, 255, 0.05) 50%, var(--re-bg-dark) 75%);
        background-size: 200% 100%;
        animation: re-shimmer 1.5s infinite;
        border-radius: 4px;
        margin-bottom: 10px;
    }

    .reports-engine .re-skeleton-line.short {
        width: 40%;
    }

    .reports-engine .re-skeleton-line.medium {
        width: 70%;
    }

    .reports-engine .re-skeleton-line.long {
        width: 90%;
    }

    @keyframes re-shimmer {
        0% {
            background-position: 200% 0;
        }

        100% {
            background-position: -200% 0;
        }
    }

    /* Empty State */
    .reports-engine .re-empty {
        text-align: center;
        padding: 80px 20px;
        border: 1px dashed var(--re-border);
        border-radius: var(--re-radius-lg);
    }

    .reports-engine .re-empty-icon {
        font-size: 48px;
        margin-bottom: 16px;
        opacity: 0.5;
    }

    .reports-engine .re-empty h3 {
        color: var(--re-text-primary);
        margin: 0 0 8px;
    }

    .reports-engine .re-empty p {
        color: var(--re-text-muted);
        font-size: 0.875rem;
        margin: 0;
    }

    /* =============================================
   GRID VIEW - Card Layout
   ============================================= */
    .reports-engine .re-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
        gap: 20px;
    }

    .reports-engine .re-card {
        display: flex;
        flex-direction: column;
        background: var(--re-bg-card);
        border: 1px solid var(--re-border);
        border-radius: var(--re-radius-md);
        overflow: hidden;
        transition: all var(--re-transition);
        text-decoration: none;
        color: inherit;
        position: relative;
    }

    .reports-engine .re-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.08), transparent);
    }

    .reports-engine .re-card:hover {
        border-color: var(--re-border-hover);
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.4), var(--re-shadow-glow);
    }

    .reports-engine .re-card-body {
        padding: 20px;
        flex: 1;
    }

    .reports-engine .re-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
    }

    .reports-engine .re-card-tag {
        display: inline-block;
        padding: 4px 10px;
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        background: var(--re-accent-soft);
        border: 1px solid rgba(6, 182, 212, 0.3);
        border-radius: var(--re-radius-sm);
        color: var(--re-accent);
    }

    .reports-engine .re-card-date {
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.7rem;
        color: var(--re-text-muted);
    }

    .reports-engine .re-card-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--re-text-primary);
        margin: 0 0 10px;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .reports-engine .re-card-summary {
        font-size: 0.875rem;
        color: var(--re-text-secondary);
        line-height: 1.6;
        margin: 0;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .reports-engine .re-card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 14px 20px;
        border-top: 1px solid var(--re-border);
        background: rgba(0, 0, 0, 0.2);
    }

    .reports-engine .re-card-meta {
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.65rem;
        color: var(--re-text-muted);
        text-transform: uppercase;
    }

    .reports-engine .re-card-cta {
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.75rem;
        color: var(--re-accent);
        transition: all var(--re-transition);
    }

    .reports-engine .re-card:hover .re-card-cta {
        text-shadow: 0 0 10px var(--re-accent-glow);
    }

    /* =============================================
   LIST VIEW - Compact Rows
   ============================================= */
    .reports-engine .re-list {
        display: none;
    }

    .reports-engine .re-list.active {
        display: block;
    }

    .reports-engine .re-list-item {
        display: grid;
        grid-template-columns: 100px 1fr auto auto;
        align-items: center;
        gap: 20px;
        padding: 16px 20px;
        border-bottom: 1px solid var(--re-border);
        text-decoration: none;
        color: inherit;
        transition: all var(--re-transition);
    }

    .reports-engine .re-list-item:first-child {
        border-top: 1px solid var(--re-border);
    }

    .reports-engine .re-list-item:hover {
        background: rgba(255, 255, 255, 0.02);
    }

    .reports-engine .re-list-item .re-card-tag {
        font-size: 0.6rem;
    }

    .reports-engine .re-list-content h3 {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--re-text-primary);
        margin: 0 0 4px;
    }

    .reports-engine .re-list-content p {
        font-size: 0.8rem;
        color: var(--re-text-muted);
        margin: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .reports-engine .re-list-meta {
        text-align: right;
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.7rem;
        color: var(--re-text-muted);
        white-space: nowrap;
    }

    .reports-engine .re-list-meta span {
        display: block;
    }

    .reports-engine .re-list-cta {
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.75rem;
        color: var(--re-accent);
        opacity: 0;
        transition: opacity var(--re-transition);
    }

    .reports-engine .re-list-item:hover .re-list-cta {
        opacity: 1;
    }

    /* =============================================
   TABLE VIEW - Data Dense
   ============================================= */
    .reports-engine .re-table-wrap {
        display: none;
        overflow-x: auto;
        border: 1px solid var(--re-border);
        border-radius: var(--re-radius-md);
    }

    .reports-engine .re-table-wrap.active {
        display: block;
    }

    .reports-engine .re-table {
        width: 100%;
        border-collapse: collapse;
    }

    .reports-engine .re-table thead {
        background: var(--re-bg-dark);
    }

    .reports-engine .re-table th {
        padding: 14px 16px;
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--re-text-muted);
        text-align: left;
        border-bottom: 1px solid var(--re-border);
    }

    .reports-engine .re-table td {
        padding: 14px 16px;
        font-size: 0.875rem;
        color: var(--re-text-secondary);
        border-bottom: 1px solid rgba(255, 255, 255, 0.03);
    }

    .reports-engine .re-table tr {
        cursor: pointer;
        transition: background var(--re-transition);
    }

    .reports-engine .re-table tbody tr:hover {
        background: rgba(255, 255, 255, 0.03);
    }

    .reports-engine .re-table .title-cell {
        color: var(--re-text-primary);
        font-weight: 500;
        max-width: 400px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .reports-engine .re-table .id-cell,
    .reports-engine .re-table .date-cell,
    .reports-engine .re-table .views-cell {
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.75rem;
        color: var(--re-text-muted);
        white-space: nowrap;
    }

    .reports-engine .re-table .cta-cell {
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.75rem;
        color: var(--re-accent);
    }

    /* =============================================
   BOTTOM PAGINATION
   ============================================= */
    .reports-engine .re-pagination {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 32px 0;
        flex-wrap: wrap;
    }

    .reports-engine .re-pagination-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 16px;
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.75rem;
        background: var(--re-bg-card);
        border: 1px solid var(--re-border);
        border-radius: var(--re-radius-sm);
        color: var(--re-text-secondary);
        cursor: pointer;
        transition: all var(--re-transition);
    }

    .reports-engine .re-pagination-btn:hover:not(:disabled) {
        border-color: var(--re-accent);
        color: var(--re-accent);
    }

    .reports-engine .re-pagination-btn:disabled {
        opacity: 0.3;
        cursor: not-allowed;
    }

    .reports-engine .re-page-numbers {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .reports-engine .re-page-num {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.8rem;
        background: transparent;
        border: 1px solid transparent;
        border-radius: var(--re-radius-full);
        color: var(--re-text-muted);
        cursor: pointer;
        transition: all var(--re-transition);
    }

    .reports-engine .re-page-num:hover {
        border-color: var(--re-border);
        color: var(--re-text-primary);
    }

    .reports-engine .re-page-num.active {
        background: var(--re-accent);
        color: white;
        border-color: var(--re-accent);
        box-shadow: var(--re-shadow-glow);
    }

    .reports-engine .re-page-ellipsis {
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.8rem;
        color: var(--re-text-muted);
        padding: 0 8px;
    }

    .reports-engine .re-jump-wrap {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-left: 16px;
    }

    .reports-engine .re-jump-label {
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.7rem;
        color: var(--re-text-muted);
    }

    .reports-engine .re-jump-input {
        width: 60px;
        padding: 8px 10px;
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.8rem;
        background: var(--re-bg-dark);
        border: 1px solid var(--re-border);
        border-radius: var(--re-radius-sm);
        color: var(--re-text-primary);
        text-align: center;
    }

    .reports-engine .re-jump-input:focus {
        outline: none;
        border-color: var(--re-accent);
        box-shadow: 0 0 0 3px var(--re-accent-soft);
    }

    .reports-engine .re-pagination-summary {
        width: 100%;
        text-align: center;
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.75rem;
        color: var(--re-text-muted);
        margin-top: 16px;
    }

    /* =============================================
   RESPONSIVE DESIGN
   ============================================= */
    @media (max-width: 768px) {
        .reports-engine .re-header {
            flex-direction: column;
            gap: 16px;
        }

        .reports-engine .re-control-row {
            flex-direction: column;
            align-items: stretch;
        }

        .reports-engine .re-search-wrap {
            min-width: 100%;
        }

        .reports-engine .re-select-group {
            width: 100%;
        }

        .reports-engine .re-select-wrap {
            flex: 1;
        }

        .reports-engine .re-select {
            width: 100%;
        }

        .reports-engine .re-grid {
            grid-template-columns: 1fr;
        }

        .reports-engine .re-list-item {
            grid-template-columns: 1fr;
            gap: 8px;
        }

        .reports-engine .re-list-content {
            order: -1;
        }

        .reports-engine .re-list-meta {
            text-align: left;
        }

        .reports-engine .re-list-cta {
            display: none;
        }

        .reports-engine .re-meta-bar {
            flex-direction: column;
            align-items: flex-start;
        }

        .reports-engine .re-pagination {
            gap: 4px;
        }

        .reports-engine .re-pagination-btn {
            padding: 8px 12px;
            font-size: 0.7rem;
        }

        .reports-engine .re-page-numbers {
            display: none;
        }

        .reports-engine .re-jump-wrap {
            margin: 16px auto 0;
        }
    }
</style>

<!-- Reports Engine Container -->
<div class="reports-engine" id="reports-app">
    <div class="container">
        <!-- Header -->
        <div class="re-header">
            <div class="re-header-left">
                <h1>Research Library</h1>
                <p>// Global Analysis Archive</p>
            </div>
            <div class="re-view-toggle" role="group" aria-label="View mode">
                <button class="re-view-btn active" data-view="grid" title="Grid View" aria-pressed="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                    </svg>
                </button>
                <button class="re-view-btn" data-view="list" title="List View" aria-pressed="false">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="8" y1="6" x2="21" y2="6"></line>
                        <line x1="8" y1="12" x2="21" y2="12"></line>
                        <line x1="8" y1="18" x2="21" y2="18"></line>
                        <line x1="3" y1="6" x2="3.01" y2="6"></line>
                        <line x1="3" y1="12" x2="3.01" y2="12"></line>
                        <line x1="3" y1="18" x2="3.01" y2="18"></line>
                    </svg>
                </button>
                <button class="re-view-btn" data-view="table" title="Table View" aria-pressed="false">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                        <line x1="3" y1="9" x2="21" y2="9"></line>
                        <line x1="3" y1="15" x2="21" y2="15"></line>
                        <line x1="9" y1="3" x2="9" y2="21"></line>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Control Panel -->
        <div class="re-controls">
            <!-- Row 1: Search + Dropdowns -->
            <div class="re-control-row">
                <div class="re-search-wrap">
                    <input type="text" class="re-search" id="reports-search"
                        value="<?= htmlspecialchars($search_query) ?>" placeholder="Search reports..."
                        autocomplete="off" aria-label="Search reports">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </div>
                <div class="re-select-group">
                    <div class="re-select-wrap">
                        <label class="re-select-label" for="reports-sort">Sort By</label>
                        <select id="reports-sort" class="re-select" aria-label="Sort order">
                            <?php foreach ($sort_labels as $key => $label): ?>
                                <option value="<?= $key ?>" <?= $current_sort === $key ? 'selected' : '' ?>><?= $label ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="re-select-wrap">
                        <label class="re-select-label" for="reports-limit">Per Page</label>
                        <select id="reports-limit" class="re-select" aria-label="Results per page">
                            <?php foreach ($limit_options as $opt): ?>
                                <option value="<?= $opt ?>" <?= $current_limit === $opt ? 'selected' : '' ?>><?= $opt ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Row 2: Tag Filters -->
            <?php if (!empty($all_tags)): ?>
                <div class="re-control-row">
                    <div class="re-tags-wrap" role="group" aria-label="Filter by tag">
                        <button class="re-tag <?= $current_tag === '' ? 'active' : '' ?>" data-tag="">All</button>
                        <?php
                        $shown = 0;
                        foreach ($all_tags as $tagName => $count):
                            if ($shown >= 12)
                                break;
                            $shown++;
                            ?>
                            <button class="re-tag <?= $current_tag === $tagName ? 'active' : '' ?>"
                                data-tag="<?= htmlspecialchars($tagName) ?>">
                                <?= htmlspecialchars($tagName) ?>
                                <span class="count"><?= $count ?></span>
                            </button>
                        <?php endforeach; ?>
                        <?php if (count($all_tags) > 12): ?>
                            <button class="re-tag re-tag-more" id="show-more-tags">+<?= count($all_tags) - 12 ?> more</button>
                        <?php endif; ?>
                    </div>
                    <?php if ($current_tag !== '' || $search_query !== ''): ?>
                        <button class="re-reset-btn" id="reset-filters" aria-label="Reset all filters">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                            Reset
                        </button>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Meta Bar -->
        <div class="re-meta-bar">
            <div class="re-meta-left">
                DISPLAYING <span class="highlight" id="results-range"><?= $from_record ?>-<?= $to_record ?></span>
                OF <span class="accent" id="results-total"><?= number_format($total_results) ?></span> RECORDS
                <?php if ($search_query): ?>
                    &mdash; MATCHING "<span class="highlight"><?= htmlspecialchars($search_query) ?></span>"
                <?php endif; ?>
                <?php if ($current_tag): ?>
                    &mdash; TAGGED "<span class="accent"><?= htmlspecialchars($current_tag) ?></span>"
                <?php endif; ?>
            </div>
            <div class="re-mini-pagination">
                <button class="re-page-btn" id="page-prev" <?= $current_page <= 1 ? 'disabled' : '' ?>
                    aria-label="Previous page">&larr;</button>
                <span class="re-page-info">PAGE <strong id="page-current"><?= $current_page ?></strong> / <span
                        id="page-total"><?= $total_pages ?></span></span>
                <button class="re-page-btn" id="page-next" <?= $current_page >= $total_pages ? 'disabled' : '' ?>
                    aria-label="Next page">&rarr;</button>
            </div>
        </div>

        <!-- Loading Skeleton -->
        <div class="re-skeleton" id="reports-skeleton">
            <div class="re-skeleton-card">
                <div class="re-skeleton-line short"></div>
                <div class="re-skeleton-line long"></div>
                <div class="re-skeleton-line medium"></div>
            </div>
            <div class="re-skeleton-card">
                <div class="re-skeleton-line short"></div>
                <div class="re-skeleton-line long"></div>
                <div class="re-skeleton-line medium"></div>
            </div>
            <div class="re-skeleton-card">
                <div class="re-skeleton-line short"></div>
                <div class="re-skeleton-line long"></div>
                <div class="re-skeleton-line medium"></div>
            </div>
        </div>

        <!-- Reports Container -->
        <div id="reports-container">
            <?php if (empty($reports)): ?>
                <div class="re-empty">
                    <div class="re-empty-icon">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.5">
                            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                        </svg>
                    </div>
                    <h3>No Reports Found</h3>
                    <p>Try adjusting your search or filter criteria.</p>
                </div>
            <?php else: ?>
                <!-- Grid View -->
                <div class="re-grid" id="view-grid">
                    <?php foreach ($reports as $r): ?>
                        <?php
                        $tags = json_decode($r['tags'] ?? '[]', true) ?? [];
                        $primary_tag = $tags[0] ?? 'GENERAL';
                        $views = $r['views'] ?? 0;
                        ?>
                        <a href="/research/<?= $r['slug'] ?>" class="re-card">
                            <div class="re-card-body">
                                <div class="re-card-header">
                                    <span class="re-card-tag"><?= htmlspecialchars($primary_tag) ?></span>
                                    <span class="re-card-date"><?= date('M d, Y', strtotime($r['published_at'])) ?></span>
                                </div>
                                <h3 class="re-card-title"><?= htmlspecialchars($r['title']) ?></h3>
                                <p class="re-card-summary"><?= htmlspecialchars($r['summary'] ?? '') ?></p>
                            </div>
                            <div class="re-card-footer">
                                <span class="re-card-meta">RPT-<?= $r['id'] ?> &bull; <?= number_format($views) ?> views</span>
                                <span class="re-card-cta">ACCESS &raquo;</span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>

                <!-- List View -->
                <div class="re-list" id="view-list">
                    <?php foreach ($reports as $r): ?>
                        <?php
                        $tags = json_decode($r['tags'] ?? '[]', true) ?? [];
                        $primary_tag = $tags[0] ?? 'GENERAL';
                        $views = $r['views'] ?? 0;
                        ?>
                        <a href="/research/<?= $r['slug'] ?>" class="re-list-item">
                            <span class="re-card-tag"><?= htmlspecialchars($primary_tag) ?></span>
                            <div class="re-list-content">
                                <h3><?= htmlspecialchars($r['title']) ?></h3>
                                <p><?= htmlspecialchars($r['summary'] ?? '') ?></p>
                            </div>
                            <div class="re-list-meta">
                                <span><?= date('M d, Y', strtotime($r['published_at'])) ?></span>
                                <span><?= number_format($views) ?> views</span>
                            </div>
                            <span class="re-list-cta">ACCESS &raquo;</span>
                        </a>
                    <?php endforeach; ?>
                </div>

                <!-- Table View -->
                <div class="re-table-wrap" id="view-table">
                    <table class="re-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Date</th>
                                <th>Views</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reports as $r): ?>
                                <?php
                                $tags = json_decode($r['tags'] ?? '[]', true) ?? [];
                                $primary_tag = $tags[0] ?? 'GENERAL';
                                $views = $r['views'] ?? 0;
                                ?>
                                <tr onclick="window.location='/research/<?= $r['slug'] ?>'">
                                    <td class="id-cell">RPT-<?= $r['id'] ?></td>
                                    <td class="title-cell"><?= htmlspecialchars($r['title']) ?></td>
                                    <td><span class="re-card-tag"><?= htmlspecialchars($primary_tag) ?></span></td>
                                    <td class="date-cell"><?= date('Y-m-d', strtotime($r['published_at'])) ?></td>
                                    <td class="views-cell"><?= number_format($views) ?></td>
                                    <td class="cta-cell">ACCESS &raquo;</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- Bottom Pagination -->
        <div class="re-pagination">
            <button class="re-pagination-btn" id="page-first" <?= $current_page <= 1 ? 'disabled' : '' ?>>&laquo;
                First</button>
            <button class="re-pagination-btn" id="page-prev-bottom" <?= $current_page <= 1 ? 'disabled' : '' ?>>&larr;
                Prev</button>

            <div class="re-page-numbers" id="page-numbers">
                <?php
                $range = 2;
                $startPage = max(1, $current_page - $range);
                $endPage = min($total_pages, $current_page + $range);
                if ($startPage > 1): ?>
                    <button class="re-page-num" data-page="1">1</button>
                    <?php if ($startPage > 2): ?><span class="re-page-ellipsis">...</span><?php endif; ?>
                <?php endif;
                for ($p = $startPage; $p <= $endPage; $p++): ?>
                    <button class="re-page-num <?= $p === $current_page ? 'active' : '' ?>"
                        data-page="<?= $p ?>"><?= $p ?></button>
                <?php endfor;
                if ($endPage < $total_pages): ?>
                    <?php if ($endPage < $total_pages - 1): ?><span class="re-page-ellipsis">...</span><?php endif; ?>
                    <button class="re-page-num" data-page="<?= $total_pages ?>"><?= $total_pages ?></button>
                <?php endif; ?>
            </div>

            <button class="re-pagination-btn" id="page-next-bottom" <?= $current_page >= $total_pages ? 'disabled' : '' ?>>Next &rarr;</button>
            <button class="re-pagination-btn" id="page-last" <?= $current_page >= $total_pages ? 'disabled' : '' ?>>Last
                &raquo;</button>

            <div class="re-jump-wrap">
                <label class="re-jump-label" for="page-jump-input">Go to:</label>
                <input type="number" id="page-jump-input" class="re-jump-input" min="1" max="<?= $total_pages ?>"
                    value="<?= $current_page ?>">
            </div>
        </div>

        <div class="re-pagination-summary">
            Page <?= $current_page ?> of <?= $total_pages ?> &bull; <?= number_format($total_results) ?> total reports
        </div>
    </div>
</div>

<!-- Initial state for JS engine -->
<script>
    window.__REPORTS_STATE__ = {
        page: <?= $current_page ?>,
        limit: <?= $current_limit ?>,
        sort: <?= json_encode($current_sort) ?>,
        search: <?= json_encode($search_query) ?>,
        tag: <?= json_encode($current_tag) ?>,
        totalPages: <?= $total_pages ?>,
        totalResults: <?= $total_results ?>,
        viewMode: 'grid'
    };
</script>
<script src="/js/reports-engine.js?v=1.1"></script>