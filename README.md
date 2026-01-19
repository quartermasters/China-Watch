# China Watch

A neutral, learning-first tracker for what's happening in and around China. Real-time news aggregation, event clustering, rumor tracking, and economic data visualization with Bloomberg Terminal-inspired design.

## Features

### Core Functionality
- **Live News Feed**: Real-time aggregation from diverse sources with confidence scoring
- **Event Clustering**: Automatic grouping of related news stories into timelines
- **Rumor Radar**: Track unverified reports through verification stages
- **Data Deck**: Economic indicators with charts (CPI, PMI, Exports, Property)
- **Full-text Search**: Search across headlines, summaries, and content
- **Email Subscriptions**: Daily briefings with top developments

### Technical Highlights
- **Bloomberg-inspired UI**: Dark theme, information-dense layout, professional dashboard
- **Fast Performance**: Target <1.8s page load, optimized for mobile
- **SEO Optimized**: Clean URLs, JSON-LD structured data, sitemaps
- **Source Transparency**: All sources labeled with reputation scores and confidence levels
- **Neutral Coverage**: Multi-source verification, clear bias labeling

## Tech Stack

- **Backend**: PHP 8.2, MySQL 8.0 with InnoDB
- **Frontend**: Vanilla JavaScript, Custom CSS, Chart.js
- **Server**: Apache with mod_rewrite
- **Data Sources**: RSS feeds, official Chinese statistics APIs
- **Deployment**: Standard LAMP stack, cron-based ingestion

## Quick Start

### Requirements
- PHP 8.2+
- MySQL 8.0+
- Apache with mod_rewrite enabled
- 512MB+ RAM, 2GB+ disk space

### Installation

1. **Clone and Setup**
```bash
git clone <repository>
cd chinawatch
cp config/env.php.example config/env.php
