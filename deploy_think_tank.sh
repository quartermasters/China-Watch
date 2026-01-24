#!/bin/bash
# Deploy Think Tank Transformation
# China Watch - January 2026

set -e

REMOTE_USER="u542596555"
REMOTE_HOST="145.79.24.172"
REMOTE_PATH="/home/u542596555/domains/chinawatch.blog"
LOCAL_PATH="/mnt/d/China Watch"

echo "=========================================="
echo "DEPLOYING THINK TANK TRANSFORMATION"
echo "=========================================="

# Core Router (CRITICAL - fixes closure error)
echo "[1/18] Deploying Router.php..."
scp "$LOCAL_PATH/core/src/Core/Router.php" "$REMOTE_USER@$REMOTE_HOST:$REMOTE_PATH/core/src/Core/Router.php"

# Entry Point
echo "[2/18] Deploying index.php..."
scp "$LOCAL_PATH/public_html/index.php" "$REMOTE_USER@$REMOTE_HOST:$REMOTE_PATH/public_html/index.php"

# Layout
echo "[3/18] Deploying layout.php..."
scp "$LOCAL_PATH/core/views/layout.php" "$REMOTE_USER@$REMOTE_HOST:$REMOTE_PATH/core/views/layout.php"

# Dashboard & Data Center
echo "[4/18] Deploying dashboard.php..."
scp "$LOCAL_PATH/core/views/dashboard.php" "$REMOTE_USER@$REMOTE_HOST:$REMOTE_PATH/core/views/dashboard.php"

echo "[5/18] Deploying data_center.php (NEW)..."
scp "$LOCAL_PATH/core/views/data_center.php" "$REMOTE_USER@$REMOTE_HOST:$REMOTE_PATH/core/views/data_center.php"

# Static Pages
echo "[6/18] Deploying about.php..."
scp "$LOCAL_PATH/core/views/about.php" "$REMOTE_USER@$REMOTE_HOST:$REMOTE_PATH/core/views/about.php"

echo "[7/18] Deploying methodology.php..."
scp "$LOCAL_PATH/core/views/methodology.php" "$REMOTE_USER@$REMOTE_HOST:$REMOTE_PATH/core/views/methodology.php"

echo "[8/18] Deploying contact.php..."
scp "$LOCAL_PATH/core/views/contact.php" "$REMOTE_USER@$REMOTE_HOST:$REMOTE_PATH/core/views/contact.php"

echo "[9/18] Deploying privacy.php..."
scp "$LOCAL_PATH/core/views/privacy.php" "$REMOTE_USER@$REMOTE_HOST:$REMOTE_PATH/core/views/privacy.php"

echo "[10/18] Deploying terms.php..."
scp "$LOCAL_PATH/core/views/terms.php" "$REMOTE_USER@$REMOTE_HOST:$REMOTE_PATH/core/views/terms.php"

# Topics/Entities
echo "[11/18] Deploying entities.php..."
scp "$LOCAL_PATH/core/views/entities.php" "$REMOTE_USER@$REMOTE_HOST:$REMOTE_PATH/core/views/entities.php"

echo "[12/18] Deploying entity_detail.php..."
scp "$LOCAL_PATH/core/views/entity_detail.php" "$REMOTE_USER@$REMOTE_HOST:$REMOTE_PATH/core/views/entity_detail.php"

# Research/Reports
echo "[13/18] Deploying reports/index.php..."
scp "$LOCAL_PATH/core/views/reports/index.php" "$REMOTE_USER@$REMOTE_HOST:$REMOTE_PATH/core/views/reports/index.php"

echo "[14/18] Deploying reports/show.php..."
scp "$LOCAL_PATH/core/views/reports/show.php" "$REMOTE_USER@$REMOTE_HOST:$REMOTE_PATH/core/views/reports/show.php"

# Controllers
echo "[15/18] Deploying DashboardController.php..."
scp "$LOCAL_PATH/core/src/Controllers/DashboardController.php" "$REMOTE_USER@$REMOTE_HOST:$REMOTE_PATH/core/src/Controllers/DashboardController.php"

echo "[16/18] Deploying SitemapController.php..."
scp "$LOCAL_PATH/core/src/Controllers/SitemapController.php" "$REMOTE_USER@$REMOTE_HOST:$REMOTE_PATH/core/src/Controllers/SitemapController.php"

# CSS
echo "[17/18] Deploying main.css..."
scp "$LOCAL_PATH/public_html/css/main.css" "$REMOTE_USER@$REMOTE_HOST:$REMOTE_PATH/public_html/css/main.css"

echo "[18/18] Deploying main.min.css..."
scp "$LOCAL_PATH/public_html/css/main.min.css" "$REMOTE_USER@$REMOTE_HOST:$REMOTE_PATH/public_html/css/main.min.css"

echo ""
echo "=========================================="
echo "DEPLOYMENT COMPLETE!"
echo "=========================================="
echo ""
echo "Test URLs:"
echo "  - https://chinawatch.blog/"
echo "  - https://chinawatch.blog/research"
echo "  - https://chinawatch.blog/topics"
echo "  - https://chinawatch.blog/data"
echo "  - https://chinawatch.blog/about"
echo ""
echo "301 Redirects (verify with curl -I):"
echo "  - /reports -> /research"
echo "  - /entities -> /topics"
echo ""
