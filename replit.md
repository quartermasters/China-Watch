# Overview

China Watch is a neutral, learning-first news aggregation and analysis platform focused on events in and around China. The system provides real-time news feeds, event clustering, rumor tracking, and economic data visualization through a Bloomberg Terminal-inspired interface. Built as a fast, SEO-optimized web application that aggregates content from multiple sources while maintaining transparency about source reliability and potential biases.

# User Preferences

Preferred communication style: Simple, everyday language.

# System Architecture

## Frontend Architecture
- **UI Framework**: Vanilla JavaScript with custom CSS, no external frameworks
- **Design System**: Bloomberg Terminal-inspired dark theme with information-dense layouts
- **Performance**: Target <1.8s page load times with mobile optimization
- **Interactivity**: Client-side filtering, infinite scroll, real-time updates via AJAX
- **Charts**: Chart.js (CDN) for economic data visualization

## Backend Architecture
- **Core Language**: PHP 8.2 with object-oriented design patterns
- **Routing**: Custom router with Apache mod_rewrite for clean URLs
- **MVC Pattern**: Controllers, Models, Views with template system
- **Caching**: File-based caching system with plans for Redis migration
- **Services**: Modular services for ingestion, clustering, and SEO

## Data Storage
- **Primary Database**: MySQL 8.0 with InnoDB engine and utf8mb4 charset
- **Schema Design**: 
  - Sources table for news source management
  - Items table for individual news articles with full-text search
  - Events table for clustered/related news stories
  - Metrics table for economic indicators
  - Subscribers table for email capture
- **Indexing**: Full-text search on headlines/summaries, unique URL constraints
- **Data Retention**: Archival system for historical data

## Content Ingestion System
- **Source Management**: JSON configuration for RSS/Atom feeds
- **Processing Pipeline**: Fetch → Parse → Deduplicate → Score → Store
- **Event Clustering**: Automatic grouping of related stories into timelines
- **Rumor Tracking**: Multi-stage verification system (unverified → emerging → corroborated → retracted)
- **Scheduling**: Cron-based ingestion with <45s runtime constraints

## AI Analysis Module (Optional)
- **Operating Modes**: None (rules-only), Rules (heuristic), API (external NLP)
- **Token Efficiency**: Batching, caching, strict prompt templates
- **Capabilities**: Automated summarization, topic/entity tagging, confidence scoring
- **Failover**: Graceful degradation to rules-based analysis

## SEO and Performance
- **URL Structure**: Clean, semantic URLs with slugs
- **Structured Data**: JSON-LD markup for search engines
- **Sitemaps**: Regular and news sitemaps with automatic generation
- **Performance**: Optimized queries, caching, mobile-first responsive design

# External Dependencies

## Content Sources
- **Reuters China**: Independent news source (RSS feed)
- **Xinhua English**: Official Chinese state media (RSS feed)
- **CGTN**: Chinese state broadcaster (RSS feed)
- **South China Morning Post**: Independent Hong Kong-based media (RSS feed)
- **Economic Data APIs**: PBOC, NDRC for official statistics

## Third-Party Services
- **Chart.js**: Client-side data visualization library (CDN)
- **Google Ads**: Revenue generation through responsive ad units
- **Email Service**: SMTP for newsletter delivery
- **OpenAI API**: Optional AI summarization and analysis (when AI_MODE=api)
- **Google Drive**: Optional backup and archival storage

## Infrastructure
- **Web Server**: Apache with mod_rewrite for URL routing
- **PHP Extensions**: Required extensions for MySQL, JSON, cURL
- **Cron Jobs**: System-level scheduling for data ingestion
- **CDN**: For static asset delivery and performance optimization

## Development Tools
- **Error Logging**: PHP error logging with rotation
- **Health Monitoring**: Planned health check endpoints
- **Analytics**: Basic traffic and performance monitoring