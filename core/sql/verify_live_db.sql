-- Clean up duplicates and add a verification signal
-- Run via: mysql -u user -p database < core/sql/verify_live_db.sql

-- 1. Truncate (Wipe) the anomalies table to remove duplicates
TRUNCATE TABLE `anomalies`;

-- 2. Re-insert the standard seed data (Once)
INSERT INTO `anomalies` (`severity`, `message`, `target`, `location`, `impact`, `source`, `created_at`) VALUES
('critical', 'CRITICAL: Unscheduled maintenance at key lithium processing hub.', 'Ganfeng Lithium - Mahong Factory (Unit 4)', 'Xinyu, Jiangxi Province', 'Estimated output reduction of 450 MT/week. Spot prices likely to react +2-4%.', 'Sentinet Satellite Thermal Imaging', '2026-01-19 20:54:02'),
('info', 'Shanghai municipal government releases guidance on AI infrastructure subsidies.', 'Shanghai Municipal Commission of Economy and Informatization', 'Shanghai', 'Fiscal allocation of 20B RMB for GPU clusters. Bullish for domestic chipmakers.', 'Official Gov Portal (Crawled)', '2026-01-19 19:54:02'),
('warning', 'PBOC signals potential reserve requirement ratio adjustment.', 'People\'s Bank of China', 'Beijing', 'Liquidity injection imminent. Banking sector volatility expected.', 'Financial News (State Media)', '2026-01-19 19:53:02');

-- 3. Add a "Proof of Life" Signal with the current timestamp
INSERT INTO `anomalies` (`severity`, `message`, `target`, `location`, `impact`, `source`) VALUES
('info', 'SYSTEM VERIFICATION: Live Database Connection Confirmed.', 'Quartermasters Server', 'Distributed Cloud', 'Confirmation that the Dashboard is reading from the Production DB.', 'Automated System Check');
