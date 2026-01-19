-- Migration: Create Reports Table
-- Run via: mysql -u user -p database < core/sql/migration_002_reports.sql

CREATE TABLE `reports` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `summary` text NOT NULL,
  `content` longtext NOT NULL,
  `tags` json DEFAULT NULL,
  `source_url` varchar(500) DEFAULT NULL,
  `published_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `views` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_slug` (`slug`),
  KEY `idx_published` (`published_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed a Welcome Report
INSERT INTO `reports` (`slug`, `title`, `summary`, `content`, `tags`) VALUES 
(
    'china-watch-intelligence-engine-online',
    'China Watch Intelligence Engine Online',
    'The automated intelligence gathering system has been successfully initialized.',
    '<p><strong>BEIJING</strong> — The <em>China Watch</em> automated intelligence engine is now operational.</p><p>This system utilizes advanced web spiders to monitor key economic indicators, regulatory bodies (NDRC, MIIT), and critical infrastructure nodes. Data is processed in real-time by <strong>GPT-5.2</strong> algorithms to generate actionable strategic intelligence.</p><h3>Capabilities</h3><ul><li>Real-time anomaly detection</li><li>Sentiment analysis of state media</li><li>Predictive modeling for supply chain disruptions</li></ul><p><em>Systems Nominal.</em></p>',
    '["System", "Announcement"]'
);
