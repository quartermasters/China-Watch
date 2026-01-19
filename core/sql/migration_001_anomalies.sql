-- Migration: Add Detail Columns to Anomalies
-- Run via: mysql -u user -p database < core/sql/migration_001_anomalies.sql

ALTER TABLE `anomalies`
ADD COLUMN `target` varchar(255) DEFAULT NULL AFTER `message`,
ADD COLUMN `location` varchar(255) DEFAULT NULL AFTER `target`,
ADD COLUMN `impact` text DEFAULT NULL AFTER `location`,
ADD COLUMN `source` varchar(255) DEFAULT NULL AFTER `impact`;

-- Seed with the "Live" data so production isn't empty
INSERT INTO `anomalies` (`severity`, `message`, `target`, `location`, `impact`, `source`, `created_at`) VALUES
('critical', 'CRITICAL: Unscheduled maintenance at key lithium processing hub.', 'Ganfeng Lithium - Mahong Factory (Unit 4)', 'Xinyu, Jiangxi Province', 'Estimated output reduction of 450 MT/week. Spot prices likely to react +2-4%.', 'Sentinet Satellite Thermal Imaging', '2026-01-19 20:54:02'),
('info', 'Shanghai municipal government releases guidance on AI infrastructure subsidies.', 'Shanghai Municipal Commission of Economy and Informatization', 'Shanghai', 'Fiscal allocation of 20B RMB for GPU clusters. Bullish for domestic chipmakers.', 'Official Gov Portal (Crawled)', '2026-01-19 19:54:02'),
('warning', 'PBOC signals potential reserve requirement ratio adjustment.', 'People\'s Bank of China', 'Beijing', 'Liquidity injection imminent. Banking sector volatility expected.', 'Financial News (State Media)', '2026-01-19 19:53:02');
