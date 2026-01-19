-- Red Pulse Database Schema
-- Optimized for Hostinger Shared (MySQL 8.0/MariaDB)

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- 
-- 1. Sources Configuration
-- 
CREATE TABLE `sources` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `osint_id` varchar(50) NOT NULL COMMENT 'e.g. SHANGHAI_PORT',
  `name` varchar(100) NOT NULL,
  `url` varchar(500) NOT NULL,
  `type` enum('economic','political','proxy') NOT NULL,
  `frequency_minutes` int(11) DEFAULT 60,
  `last_checked_at` datetime DEFAULT NULL,
  `active` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_osint_id` (`osint_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 
-- 2. Signals (Raw Time-Series)
-- 
CREATE TABLE `signals` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `source_id` int(11) NOT NULL,
  `metric_code` varchar(50) NOT NULL,
  `value` decimal(16,4) NOT NULL,
  `captured_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `raw_context` text COMMENT 'Optional snippet for AI context',
  PRIMARY KEY (`id`),
  KEY `idx_metric_time` (`metric_code`,`captured_at`),
  KEY `idx_source` (`source_id`),
  CONSTRAINT `fk_signals_source` FOREIGN KEY (`source_id`) REFERENCES `sources` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 
-- 3. Daily Snapshots (Aggregated Cache)
-- 
CREATE TABLE `daily_snapshots` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `metric_code` varchar(50) NOT NULL,
  `snapshot_date` date NOT NULL,
  `avg_value` decimal(16,4) NOT NULL,
  `min_value` decimal(16,4) NOT NULL,
  `max_value` decimal(16,4) NOT NULL,
  `variance_score` decimal(8,4) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_metric_date` (`metric_code`,`snapshot_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 
-- 4. Anomalies (Alerts)
-- 
CREATE TABLE `anomalies` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `signal_id` bigint(20) DEFAULT NULL,
  `severity` enum('info','warning','critical') NOT NULL DEFAULT 'info',
  `message` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 
-- 5. Jobs (Queue)
-- 
CREATE TABLE `jobs` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `handler` varchar(255) NOT NULL,
  `payload` json NOT NULL,
  `available_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_available` (`available_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 
-- Seed Data: Initial Monitors
-- 
INSERT INTO `sources` (`osint_id`, `name`, `url`, `type`, `frequency_minutes`) VALUES
('SHANGHAI_PORT', 'Shanghai Port Traffic (SIPG)', 'https://www.portshanghai.com.cn/traffic', 'economic', 60),
('LITHIUM_PRICE', 'Lithium Carbonate Spot', 'http://www.100ppi.com/cindex/', 'economic', 60),
('RITUAL_security', 'Keyword: Security (People\'s Daily)', 'http://paper.people.com.cn/', 'political', 60),
('PROXY_kenya', 'Kenya Tender Watch', 'https://tenders.go.ke/', 'proxy', 120);

COMMIT;
