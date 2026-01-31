-- Migration 005: Performance indexes for Reports Engine
-- Run this migration to optimize sorting and search on the reports table.

-- Index for sorting by views (most viewed)
CREATE INDEX IF NOT EXISTS idx_reports_views ON reports(views DESC);

-- Index for sorting by title
CREATE INDEX IF NOT EXISTS idx_reports_title ON reports(title);

-- Index for sorting by published_at (may already exist, but ensures coverage)
CREATE INDEX IF NOT EXISTS idx_reports_published_at ON reports(published_at DESC);

-- Full-text search index for better search performance (replaces LIKE queries)
-- Note: FULLTEXT indexes require MyISAM or InnoDB (MySQL 5.6+).
-- If this fails, the existing LIKE-based search will continue to work.
ALTER TABLE reports ADD FULLTEXT INDEX ft_reports_search(title, summary);
