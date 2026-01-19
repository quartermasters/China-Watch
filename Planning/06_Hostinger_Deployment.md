# Hostinger Deployment Guide

## 1. Directory Structure
On shared hosting, `public_html` is the only web-accessible folder. We must protect our core logic by placing it *outside* or securing it.
```
/home/u123456789/
├── domains/
│   └── chinawatch.com/
│       ├── public_html/       <-- Only the 'public' folder contents go here
│       │   ├── css/
│       │   ├── js/
│       │   ├── images/
│       │   └── index.php      <-- Entry point
│       │
│       ├── core/              <-- Application Logic (SECURE)
│       │   ├── src/           <-- Classes (Controllers, Domain, adapters)
│       │   ├── config/        <-- Database credentials
│       │   ├── scripts/       <-- Cron scripts
│       │   ├── templates/     <-- Twig files
│       │   └── vendor/        <-- Composer libraries
```

## 2. Installation Steps
1.  **Composer**: Run `composer install` locally, then upload the `vendor` folder.
2.  **Database**:
    -   Create MySQL Database via hPanel.
    -   Run the `schema.sql` manually in phpMyAdmin.
3.  **Config**:
    -   Create `config/env.php` within the `core` directory.
    -   Ensure `public_html/index.php` points to `../../core/src/bootstrap.php`.

## 3. Cron Job Setup (hPanel)
Set up a **single** "Heartbeat" cron job to run every minute (`* * * * *`).
```bash
/usr/bin/php /home/u123456789/domains/chinawatch.com/core/scripts/heartbeat.php
```
*Note*: This script will handle:
*   Queue working (Job processing)
*   Daily cleanup (at 00:00)
*   Anomaly detection

## 4. Git Deployment (Optional but Recommended)
Hostinger Business plan supports Git.
1.  Push code to GitHub/GitLab.
2.  Connect Repo in Hostinger hPanel.
3.  Set "Deployment Pathway" to map `public` -> `public_html`.
