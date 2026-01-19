-- Bulk Insert of Intelligence Targets
-- Run via phpMyAdmin

INSERT INTO `sources` (`osint_id`, `name`, `url`, `type`, `frequency_minutes`) VALUES
-- GLOBAL / ENGLISH
('SCMP_CHINA', 'South China Morning Post (Economy)', 'https://www.scmp.com/economy/china-economy', 'economic', 30),
('CAIXIN_GLOBAL', 'Caixin Global', 'https://www.caixinglobal.com/', 'economic', 60),
('REUTERS_CHINA', 'Reuters: China', 'https://www.reuters.com/world/china/', 'political', 45),

-- GOVERNMENT / OFFICIAL (May require advanced parsing later)
('GOV_CN_NEWS', 'Gov.cn Official News', 'https://english.www.gov.cn/news/', 'political', 120),
('MIIT_NEWS', 'Ministry of Industry and IT', 'https://www.miit.gov.cn/english/', 'economic', 240),

-- COMMODITIES / SPECIFIC
('ASIAN_METAL', 'Asian Metal (Lithium)', 'http://www.asianmetal.com/LithiumPrice/Lithium.html', 'economic', 60),
('SMM_CN', 'Shanghai Metals Market', 'https://www.smm.cn/english/', 'economic', 60);

-- Note: The Spider will attempt to crawl these. 
-- Sites with heavy Cloudflare/JS protection may require an external scraping API key in the future.
