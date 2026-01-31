@echo off
echo ===============================================
echo  CHINA WATCH - AUTOMATED DEPLOYMENT SYNC
echo ===============================================

echo 1. Staging Files...
git add core/src/Core/Router.php
git add core/src/Controllers/ReportController.php
git add core/views/reports/index.php
git add core/views/dashboard.php
git add core/views/layout.php
git add public_html/css/main.css

echo 2. Committing Changes...
git commit -m "Feat: Redesign Landing Page (Command Center Theme) & Fix Router/Report Errors"

echo 3. Pulling Latest Changes (Rebase)...
git pull --rebase origin main

echo 4. Pushing to GitHub...
git push origin main

echo ===============================================
echo  SYNC COMPLETE.
echo ===============================================
pause
