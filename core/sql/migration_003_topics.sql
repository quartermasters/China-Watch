-- Migration: Topics & Keywords Database
-- Run via: mysql -u user -p database < core/sql/migration_003_topics.sql

-- 1. Create Topics Table
CREATE TABLE `topics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` enum('Economics','Politics','Technology','Geopolitics','Social','Composite') NOT NULL,
  `keyword` varchar(255) NOT NULL,
  `search_query` varchar(255) NOT NULL COMMENT 'Optimized query string',
  `importance` int(11) DEFAULT 5,
  `last_crawled_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_keyword` (`keyword`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Seed Data (Top 100+ Keywords of the Decade)
INSERT INTO `topics` (`category`, `keyword`, `search_query`, `importance`) VALUES
-- ECONOMIC STRATEGY
('Economics', 'Dual Circulation', 'Dual Circulation strategy China economy', 10),
('Economics', 'Common Prosperity', 'Common Prosperity policy China', 10),
('Economics', 'New Quality Productive Forces', 'New Quality Productive Forces China economic policy', 10),
('Economics', 'Supply Side Reform', 'Supply-side structural reform China', 8),
('Economics', 'Deleveraging', 'China corporate deleveraging campaign', 7),
('Economics', 'Made in China 2025', 'Made in China 2025 industrial policy', 9),
('Economics', 'State-Owned Enterprise Reform', 'China SOE reform latest news', 7),
('Economics', 'Internal Circulation', 'China internal circulation domestic demand', 8),
('Economics', 'Unified National Market', 'China unified national market guidelines', 7),

-- TECHNOLOGY & INNOVATION
('Technology', 'Semiconductor Independence', 'China semiconductor self-sufficiency chips', 10),
('Technology', 'Artificial Intelligence', 'China AI development LLM regulation', 10),
('Technology', 'Digital Yuan (e-CNY)', 'Digital Yuan adoption e-CNY PBOC', 9),
('Technology', 'Quantum Computing', 'China quantum computing breakthroughs', 8),
('Technology', 'New Energy Vehicles', 'China NEV EV market dominance', 9),
('Technology', 'Battery Technology', 'China lithium battery CATL BYD technology', 8),
('Technology', '5G Infrastructure', 'China 5G rollout industrial internet', 7),
('Technology', 'Smart Cities', 'China smart city surveillance brain', 6),
('Technology', 'Biotech', 'China biotechnology innovation pharma', 7),
('Technology', 'Space Station', 'Tiangong space station CMSA updates', 7),

-- GEOPOLITICS
('Geopolitics', 'Belt and Road Initiative', 'Belt and Road Initiative latest projects investment', 10),
('Geopolitics', 'Wolf Warrior Diplomacy', 'China Wolf Warrior diplomacy analysis', 8),
('Geopolitics', 'Global Security Initiative', 'Global Security Initiative Xi Jinping', 9),
('Geopolitics', 'Global Development Initiative', 'Global Development Initiative China UN', 8),
('Geopolitics', 'South China Sea', 'South China Sea dispute militarization', 10),
('Geopolitics', 'Taiwan Strait', 'Taiwan Strait tension millitary exercise', 10),
('Geopolitics', 'China-US Decoupling', 'US-China economic decoupling de-risking', 10),
('Geopolitics', 'BRICS Expansion', 'BRICS expansion China influence', 8),
('Geopolitics', 'Shanghai Cooperation Organization', 'SCO summit China security cooperation', 7),
('Geopolitics', 'Debt Trap Diplomacy', 'China debt trap diplomacy narrative', 7),

-- CRITICAL RESOURCES
('Economics', 'Lithium Security', 'China lithium supply chain security', 9),
('Economics', 'Rare Earths', 'China rare earth export control quota', 9),
('Economics', 'Food Security', 'China food security grain reserves', 9),
('Economics', 'Energy Security', 'China energy security strategic oil reserve', 9),

-- POLITICAL & SOCIAL
('Politics', 'Xi Jinping Thought', 'Xi Jinping Thought socialism with Chinese characteristics', 10),
('Politics', 'National Security Law', 'Hong Kong National Security Law impact', 9),
('Politics', 'Anti-Corruption Campaign', 'China anti-corruption campaign tigers and flies', 8),
('Social', 'Demographic Crisis', 'China birth rate decline population aging', 10),
('Social', 'Youth Unemployment', 'China youth unemployment rate lying flat', 9),
('Social', 'Lying Flat (Tang Ping)', 'Tang Ping movement China social trend', 6),
('Social', '996 Work Culture', '996 work culture China tech sector regulation', 6),

-- SECTORS
('Economics', 'Property Crisis', 'China real estate crisis Evergrande Country Garden', 10),
('Economics', 'Local Government Debt', 'LGFV debt crisis China local government', 10),
('Economics', 'Shadow Banking', 'China shadow banking wealth management products', 8),
('Economics', 'Fintech Crackdown', 'China fintech regulation Ant Group', 7),
('Economics', 'Cross-Border E-commerce', 'China cross-border e-commerce SHEIN Temu', 8),
('Economics', 'Green Finance', 'China green finance bonds carbon market', 7),

-- REGIONAL
('Politics', 'Greater Bay Area', 'Guangdong-Hong Kong-Macao Greater Bay Area integration', 8),
('Politics', 'Xiongan New Area', 'Xiongan New Area construction progress', 7),
('Politics', 'Hainan Free Trade Port', 'Hainan Free Trade Port policy', 7),
('Geopolitics', 'China-Africa Relations', 'FOCAC China Africa trade investment', 8),
('Geopolitics', 'China-Middle East', 'China Middle East energy deal mediation', 8),
('Geopolitics', 'China-Russia Partnership', 'China Russia limitless partnership trade', 9),
('Geopolitics', 'China-EU Relations', 'EU-China CAI investment agreement de-risking', 9);
