# China Watch - Production Deployment Guide (Hostinger)

## Overview
This guide will help you deploy China Watch to Hostinger shared hosting or VPS.

## Prerequisites
- Hostinger hosting account with PHP 8.2+ support
- MySQL database access
- Domain name configured

## Step 1: Environment Variables Configuration

### Required Environment Variables
Configure these in your Hostinger control panel under "Environment Variables" or create a `.env` file:

```bash
# Database Configuration (MySQL)
DB_HOST=localhost
DB_NAME=your_database_name
DB_USER=your_database_user
DB_PASS=your_database_password
DB_PORT=3306

# Site Configuration
BASE_URL=https://your-domain.com

# Security Tokens (Generate random secure tokens!)
CRON_TOKEN=your_secure_random_token_32_chars
EXPORT_TOKEN=your_secure_random_token_32_chars
ADMIN_TOKEN=your_secure_random_token_32_chars

# Email Configuration (Required for Newsletter)
SMTP_HOST=smtp.hostinger.com
SMTP_USER=your_email@your-domain.com
SMTP_PASS=your_email_password
FROM_EMAIL=noreply@your-domain.com

# Feature Flags
ADS_ENABLED=true
AI_MODE=rules

# Optional
GA_TRACKING_ID=your_google_analytics_id
OPENAI_API_KEY=your_openai_key
```

### Security Token Generation
Use this command to generate secure tokens:
```bash
# Linux/Mac
openssl rand -hex 16

# Or online generator
# Visit: https://www.random.org/strings/ (32 characters, hex)
```

## Step 2: Database Setup

1. **Create MySQL Database** in Hostinger control panel
2. **Import Schema**: Upload and run `sql/migrations.sql`
3. **Update Connection**: Ensure environment variables match your database credentials

## Step 3: File Upload

1. **Upload Files**: Upload all files to your domain's public_html folder
2. **Set Permissions**:
   - `/storage/` folder: 755 (writable)
   - `/storage/cache/` folder: 755 (writable)
   - `/storage/logs/` folder: 755 (writable)

## Step 4: Apache Configuration

Create `.htaccess` file in your public_html root:

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ /index.php [QSA,L]

# Security headers
Header always set X-Frame-Options "SAMEORIGIN"
Header always set X-Content-Type-Options "nosniff"
Header always set Referrer-Policy "strict-origin-when-cross-origin"

# Cache static assets
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
    ExpiresByType image/png "access plus 1 month"
    ExpiresByType image/jpg "access plus 1 month"
    ExpiresByType image/jpeg "access plus 1 month"
    ExpiresByType image/gif "access plus 1 month"
    ExpiresByType image/ico "access plus 1 month"
</IfModule>
```

## Step 5: Cron Job Setup

Configure cron job in Hostinger control panel to run data ingestion:

**Command:**
```bash
/usr/bin/php /home/username/public_html/scripts/ingest.php
```

**Schedule:** Every 30 minutes
```
*/30 * * * *
```

## Step 6: Testing

1. **Visit Your Domain**: Check if the website loads
2. **Test Database**: Verify data is displaying
3. **Test Newsletter**: Try subscribing with a test email
4. **Check Admin Panel**: Visit `/admin` (no authentication required)
5. **Test Newsletter Popup**: Should appear after 3 seconds

## Step 7: Final Checks

- [ ] Website loads correctly
- [ ] Database connection working
- [ ] Newsletter signup works
- [ ] Admin panel accessible
- [ ] Cron job running (check ingested news items)
- [ ] SSL certificate active
- [ ] Newsletter popup appears

## Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Check DB credentials in environment variables
   - Verify database exists and user has permissions

2. **Newsletter Popup Not Appearing**
   - Check browser console for JavaScript errors
   - Clear browser cache

3. **Admin Panel Not Working**
   - Verify all authentication was removed
   - Check file permissions on storage folders

4. **Cron Job Not Running**
   - Verify cron command path is correct
   - Check CRON_TOKEN matches in environment variables

### Support Files
- Configuration: `config/env.php`
- Database: `src/Core/DB.php`
- Admin Panel: `src/Controllers/AdminController.php`
- Newsletter: `public/js/newsletter-popup.js`

## Security Notes

- All default authentication has been removed
- Admin panel is now publicly accessible
- Make sure your security tokens are random and secure
- Consider adding IP restrictions for admin access if needed