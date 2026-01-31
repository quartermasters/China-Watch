/**
 * Reports Engine v1.1
 * Next-Gen AJAX-powered reports browser with state management, URL sync, and localStorage persistence.
 */
(function () {
    'use strict';

    // ========================================
    // STATE
    // ========================================
    const state = Object.assign({
        page: 1,
        limit: 25,
        sort: 'newest',
        search: '',
        tag: '',
        totalPages: 1,
        totalResults: 0,
        viewMode: 'grid',
    }, window.__REPORTS_STATE__ || {});

    // Restore preferences from localStorage
    const savedLimit = localStorage.getItem('rpt_limit');
    const savedView = localStorage.getItem('rpt_viewMode');
    const savedSort = localStorage.getItem('rpt_sort');

    if (savedLimit && [25, 50, 100, 250, 500].includes(Number(savedLimit))) {
        if (!new URLSearchParams(window.location.search).has('limit')) {
            state.limit = Number(savedLimit);
        }
    }
    if (savedView && ['grid', 'list', 'table'].includes(savedView)) {
        state.viewMode = savedView;
    }
    if (savedSort && !new URLSearchParams(window.location.search).has('sort')) {
        state.sort = savedSort;
    }

    let searchTimeout = null;
    let isLoading = false;

    // ========================================
    // DOM HELPERS
    // ========================================
    const $ = (sel) => document.querySelector(sel);
    const $$ = (sel) => document.querySelectorAll(sel);

    // ========================================
    // URL STATE SYNC
    // ========================================
    function buildQueryString() {
        const params = new URLSearchParams();
        if (state.page > 1) params.set('page', state.page);
        if (state.limit !== 25) params.set('limit', state.limit);
        if (state.sort !== 'newest') params.set('sort', state.sort);
        if (state.search) params.set('q', state.search);
        if (state.tag) params.set('tag', state.tag);
        const qs = params.toString();
        return qs ? '?' + qs : '';
    }

    function pushURL() {
        const url = '/research' + buildQueryString();
        if (url !== window.location.pathname + window.location.search) {
            history.pushState(state, '', url);
        }
    }

    function restoreFromURL() {
        const params = new URLSearchParams(window.location.search);
        state.page = Number(params.get('page')) || 1;
        state.limit = Number(params.get('limit')) || state.limit;
        state.sort = params.get('sort') || state.sort;
        state.search = params.get('q') || '';
        state.tag = params.get('tag') || '';
    }

    // ========================================
    // SAVE PREFERENCES
    // ========================================
    function savePreferences() {
        localStorage.setItem('rpt_limit', state.limit);
        localStorage.setItem('rpt_viewMode', state.viewMode);
        localStorage.setItem('rpt_sort', state.sort);
    }

    // ========================================
    // FETCH REPORTS
    // ========================================
    async function fetchReports() {
        if (isLoading) return;
        isLoading = true;

        // Show skeleton
        const skeleton = $('#reports-skeleton');
        const container = $('#reports-container');
        if (skeleton) skeleton.classList.add('loading');
        if (container) container.style.opacity = '0.3';

        const params = new URLSearchParams();
        params.set('page', state.page);
        params.set('limit', state.limit);
        params.set('sort', state.sort);
        if (state.search) params.set('q', state.search);
        if (state.tag) params.set('tag', state.tag);

        try {
            const resp = await fetch('/api/reports?' + params.toString());
            if (!resp.ok) throw new Error('Network error');
            const data = await resp.json();

            state.totalPages = data.total_pages;
            state.totalResults = data.total;

            renderReports(data.reports);
            renderMetaBar();
            renderPagination();
            renderTagChips(data.all_tags);
            pushURL();
            savePreferences();
        } catch (err) {
            console.error('Reports Engine: fetch failed', err);
            if (container) {
                container.innerHTML = '<div class="re-empty"><div class="re-empty-icon">&#9888;</div><h3>Connection Error</h3><p>Failed to load reports. Please try again.</p></div>';
            }
        } finally {
            if (skeleton) skeleton.classList.remove('loading');
            if (container) container.style.opacity = '1';
            isLoading = false;
        }
    }

    // ========================================
    // RENDER FUNCTIONS
    // ========================================
    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str || '';
        return div.innerHTML;
    }

    function formatDate(dateStr, format) {
        const d = new Date(dateStr);
        if (isNaN(d)) return dateStr;
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        if (format === 'short') return months[d.getMonth()] + ' ' + String(d.getDate()).padStart(2, '0') + ', ' + d.getFullYear();
        if (format === 'iso') return d.toISOString().split('T')[0];
        return months[d.getMonth()] + ' ' + String(d.getDate()).padStart(2, '0') + ', ' + d.getFullYear();
    }

    function renderReports(reports) {
        const container = $('#reports-container');
        if (!container) return;

        if (!reports || reports.length === 0) {
            container.innerHTML = '<div class="re-empty"><div class="re-empty-icon">&#128194;</div><h3>No Reports Found</h3><p>Try adjusting your search or filter criteria.</p></div>';
            return;
        }

        // Build all three views
        let gridHtml = '<div class="re-grid" id="view-grid"' + (state.viewMode !== 'grid' ? ' style="display:none"' : '') + '>';
        let listHtml = '<div class="re-list' + (state.viewMode === 'list' ? ' active' : '') + '" id="view-list">';
        let tableHtml = '<div class="re-table-wrap' + (state.viewMode === 'table' ? ' active' : '') + '" id="view-table"><table class="re-table"><thead><tr><th>ID</th><th>Title</th><th>Category</th><th>Date</th><th>Views</th><th></th></tr></thead><tbody>';

        reports.forEach(function (r) {
            const tags = r.tags_array || (function () { try { return JSON.parse(r.tags || '[]'); } catch (e) { return []; } })();
            const primaryTag = tags[0] || 'GENERAL';
            const views = Number(r.views || 0);

            // Grid card
            gridHtml += '<a href="/research/' + r.slug + '" class="re-card">' +
                '<div class="re-card-body">' +
                '<div class="re-card-header">' +
                '<span class="re-card-tag">' + escapeHtml(primaryTag) + '</span>' +
                '<span class="re-card-date">' + formatDate(r.published_at, 'short') + '</span>' +
                '</div>' +
                '<h3 class="re-card-title">' + escapeHtml(r.title) + '</h3>' +
                '<p class="re-card-summary">' + escapeHtml(r.summary) + '</p>' +
                '</div>' +
                '<div class="re-card-footer">' +
                '<span class="re-card-meta">RPT-' + r.id + ' &bull; ' + views.toLocaleString() + ' views</span>' +
                '<span class="re-card-cta">ACCESS &raquo;</span>' +
                '</div></a>';

            // List item
            listHtml += '<a href="/research/' + r.slug + '" class="re-list-item">' +
                '<span class="re-card-tag">' + escapeHtml(primaryTag) + '</span>' +
                '<div class="re-list-content"><h3>' + escapeHtml(r.title) + '</h3>' +
                '<p>' + escapeHtml(r.summary) + '</p></div>' +
                '<div class="re-list-meta"><span>' + formatDate(r.published_at, 'short') + '</span><span>' + views.toLocaleString() + ' views</span></div>' +
                '<span class="re-list-cta">ACCESS &raquo;</span></a>';

            // Table row
            tableHtml += '<tr onclick="window.location=\'/research/' + r.slug + '\'">' +
                '<td class="id-cell">RPT-' + r.id + '</td>' +
                '<td class="title-cell">' + escapeHtml(r.title) + '</td>' +
                '<td><span class="re-card-tag">' + escapeHtml(primaryTag) + '</span></td>' +
                '<td class="date-cell">' + formatDate(r.published_at, 'iso') + '</td>' +
                '<td class="views-cell">' + views.toLocaleString() + '</td>' +
                '<td class="cta-cell">ACCESS &raquo;</td></tr>';
        });

        gridHtml += '</div>';
        listHtml += '</div>';
        tableHtml += '</tbody></table></div>';

        container.innerHTML = gridHtml + listHtml + tableHtml;

        // Apply current view mode
        setViewMode(state.viewMode, false);
    }

    function renderMetaBar() {
        const fromRec = state.totalResults > 0 ? (state.page - 1) * state.limit + 1 : 0;
        const toRec = Math.min(state.page * state.limit, state.totalResults);

        const rangeEl = $('#results-range');
        const totalEl = $('#results-total');
        if (rangeEl) rangeEl.textContent = fromRec + '-' + toRec;
        if (totalEl) totalEl.textContent = state.totalResults.toLocaleString();

        // Update meta left text
        const metaLeft = $('.re-meta-left');
        if (metaLeft) {
            let text = 'DISPLAYING <span class="highlight">' + fromRec + '-' + toRec + '</span> OF <span class="accent">' + state.totalResults.toLocaleString() + '</span> RECORDS';
            if (state.search) text += ' &mdash; MATCHING "<span class="highlight">' + escapeHtml(state.search) + '</span>"';
            if (state.tag) text += ' &mdash; TAGGED "<span class="accent">' + escapeHtml(state.tag) + '</span>"';
            metaLeft.innerHTML = text;
        }
    }

    function renderPagination() {
        // Top pagination
        const prevBtn = $('#page-prev');
        const nextBtn = $('#page-next');
        const currentSpan = $('#page-current');
        const totalSpan = $('#page-total');

        if (prevBtn) prevBtn.disabled = state.page <= 1;
        if (nextBtn) nextBtn.disabled = state.page >= state.totalPages;
        if (currentSpan) currentSpan.textContent = state.page;
        if (totalSpan) totalSpan.textContent = state.totalPages;

        // Bottom pagination
        const first = $('#page-first');
        const prevB = $('#page-prev-bottom');
        const nextB = $('#page-next-bottom');
        const last = $('#page-last');
        if (first) first.disabled = state.page <= 1;
        if (prevB) prevB.disabled = state.page <= 1;
        if (nextB) nextB.disabled = state.page >= state.totalPages;
        if (last) last.disabled = state.page >= state.totalPages;

        // Page numbers
        const pageNumbers = $('#page-numbers');
        if (pageNumbers) {
            let html = '';
            const range = 2;
            const startPage = Math.max(1, state.page - range);
            const endPage = Math.min(state.totalPages, state.page + range);

            if (startPage > 1) {
                html += '<button class="re-page-num" data-page="1">1</button>';
                if (startPage > 2) html += '<span class="re-page-ellipsis">...</span>';
            }
            for (let p = startPage; p <= endPage; p++) {
                html += '<button class="re-page-num' + (p === state.page ? ' active' : '') + '" data-page="' + p + '">' + p + '</button>';
            }
            if (endPage < state.totalPages) {
                if (endPage < state.totalPages - 1) html += '<span class="re-page-ellipsis">...</span>';
                html += '<button class="re-page-num" data-page="' + state.totalPages + '">' + state.totalPages + '</button>';
            }
            pageNumbers.innerHTML = html;

            // Re-bind page number clicks
            pageNumbers.querySelectorAll('.re-page-num').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    goToPage(Number(this.dataset.page));
                });
            });
        }

        // Jump input
        const jumpInput = $('#page-jump-input');
        if (jumpInput) {
            jumpInput.value = state.page;
            jumpInput.max = state.totalPages;
        }

        // Bottom summary
        const summary = $('.re-pagination-summary');
        if (summary) {
            summary.textContent = 'Page ' + state.page + ' of ' + state.totalPages + ' \u2022 ' + state.totalResults.toLocaleString() + ' total reports';
        }
    }

    function renderTagChips(allTags) {
        const tagsWrap = $('.re-tags-wrap');
        if (!tagsWrap || !allTags) return;

        let html = '<button class="re-tag' + (state.tag === '' ? ' active' : '') + '" data-tag="">All</button>';
        const tagEntries = Object.entries(allTags);
        const limit = 12;
        tagEntries.slice(0, limit).forEach(function (entry) {
            const tagName = entry[0];
            const count = entry[1];
            html += '<button class="re-tag' + (state.tag === tagName ? ' active' : '') + '" data-tag="' + escapeHtml(tagName) + '">' + escapeHtml(tagName) + '<span class="count">' + count + '</span></button>';
        });
        if (tagEntries.length > limit) {
            html += '<button class="re-tag re-tag-more" id="show-more-tags">+' + (tagEntries.length - limit) + ' more</button>';
        }
        tagsWrap.innerHTML = html;

        // Re-bind tag clicks
        bindTagChips();

        // Show/hide reset button
        updateResetButton();
    }

    function updateResetButton() {
        const row = $('.re-control-row:last-child');
        let resetBtn = $('#reset-filters');

        if (state.tag || state.search) {
            if (!resetBtn && row) {
                const btn = document.createElement('button');
                btn.className = 're-reset-btn';
                btn.id = 'reset-filters';
                btn.setAttribute('aria-label', 'Reset all filters');
                btn.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg> Reset';
                row.appendChild(btn);
                btn.addEventListener('click', resetFilters);
            }
        } else if (resetBtn) {
            resetBtn.remove();
        }
    }

    // ========================================
    // NAVIGATION
    // ========================================
    function goToPage(page) {
        page = Math.max(1, Math.min(page, state.totalPages));
        if (page === state.page) return;
        state.page = page;
        fetchReports();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function resetFilters() {
        state.search = '';
        state.tag = '';
        state.page = 1;

        const searchInput = $('#reports-search');
        if (searchInput) searchInput.value = '';

        fetchReports();
    }

    // ========================================
    // VIEW MODE
    // ========================================
    function setViewMode(mode, save = true) {
        state.viewMode = mode;
        if (save) savePreferences();

        // Toggle visibility
        const grid = $('#view-grid');
        const list = $('#view-list');
        const table = $('#view-table');

        if (grid) grid.style.display = mode === 'grid' ? '' : 'none';
        if (list) {
            list.classList.toggle('active', mode === 'list');
            list.style.display = '';
        }
        if (table) {
            table.classList.toggle('active', mode === 'table');
            table.style.display = '';
        }

        // Update toggle buttons
        $$('.re-view-btn').forEach(function (btn) {
            const isActive = btn.dataset.view === mode;
            btn.classList.toggle('active', isActive);
            btn.setAttribute('aria-pressed', isActive ? 'true' : 'false');
        });
    }

    // ========================================
    // EVENT BINDING
    // ========================================
    function bindTagChips() {
        $$('.re-tag:not(.re-tag-more)').forEach(function (chip) {
            chip.addEventListener('click', function () {
                state.tag = this.dataset.tag;
                state.page = 1;
                fetchReports();
            });
        });
    }

    function init() {
        // Apply saved view mode on load
        setViewMode(state.viewMode, false);

        // Apply saved sort/limit to dropdowns
        const sortSelect = $('#reports-sort');
        const limitSelect = $('#reports-limit');
        if (sortSelect) sortSelect.value = state.sort;
        if (limitSelect) limitSelect.value = state.limit;

        // If saved preferences differ from server-rendered state, re-fetch
        const serverState = window.__REPORTS_STATE__ || {};
        if (state.limit !== serverState.limit || state.sort !== serverState.sort) {
            state.page = 1;
            fetchReports();
        }

        // Search input with debounce
        const searchInput = $('#reports-search');
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function () {
                    state.search = searchInput.value.trim();
                    state.page = 1;
                    fetchReports();
                }, 400);
            });
            searchInput.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    clearTimeout(searchTimeout);
                    state.search = searchInput.value.trim();
                    state.page = 1;
                    fetchReports();
                }
            });
        }

        // Sort dropdown
        if (sortSelect) {
            sortSelect.addEventListener('change', function () {
                state.sort = this.value;
                state.page = 1;
                fetchReports();
            });
        }

        // Limit dropdown
        if (limitSelect) {
            limitSelect.addEventListener('change', function () {
                state.limit = Number(this.value);
                state.page = 1;
                fetchReports();
            });
        }

        // Tag chips
        bindTagChips();

        // View mode toggle
        $$('.re-view-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                setViewMode(this.dataset.view);
            });
        });

        // Top pagination
        const prevBtn = $('#page-prev');
        const nextBtn = $('#page-next');
        if (prevBtn) prevBtn.addEventListener('click', function () { goToPage(state.page - 1); });
        if (nextBtn) nextBtn.addEventListener('click', function () { goToPage(state.page + 1); });

        // Bottom pagination
        const first = $('#page-first');
        const prevB = $('#page-prev-bottom');
        const nextB = $('#page-next-bottom');
        const last = $('#page-last');
        if (first) first.addEventListener('click', function () { goToPage(1); });
        if (prevB) prevB.addEventListener('click', function () { goToPage(state.page - 1); });
        if (nextB) nextB.addEventListener('click', function () { goToPage(state.page + 1); });
        if (last) last.addEventListener('click', function () { goToPage(state.totalPages); });

        // Page number buttons
        $$('.re-page-num').forEach(function (btn) {
            btn.addEventListener('click', function () {
                goToPage(Number(this.dataset.page));
            });
        });

        // Jump to page
        const jumpInput = $('#page-jump-input');
        if (jumpInput) {
            jumpInput.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    const p = Number(this.value);
                    if (p >= 1 && p <= state.totalPages) goToPage(p);
                }
            });
        }

        // Reset filters
        const resetBtn = $('#reset-filters');
        if (resetBtn) {
            resetBtn.addEventListener('click', resetFilters);
        }

        // Browser back/forward
        window.addEventListener('popstate', function () {
            restoreFromURL();
            fetchReports();
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function (e) {
            if (e.target.tagName === 'INPUT' || e.target.tagName === 'SELECT' || e.target.tagName === 'TEXTAREA') {
                if (e.key === 'Escape') {
                    e.target.blur();
                }
                return;
            }

            if (e.key === 'ArrowLeft') {
                e.preventDefault();
                goToPage(state.page - 1);
            } else if (e.key === 'ArrowRight') {
                e.preventDefault();
                goToPage(state.page + 1);
            } else if (e.key === '/') {
                e.preventDefault();
                const searchInput = $('#reports-search');
                if (searchInput) searchInput.focus();
            }
        });
    }

    // ========================================
    // BOOT
    // ========================================
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
