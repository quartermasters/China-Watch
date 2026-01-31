#!/bin/bash
echo "==============================================="
echo " CHINA WATCH - TOPIC GALAXY ENGINE (Linux/WSL)"
echo "==============================================="

echo "1. Staging Topic Galaxy Files..."
git add core/src/Controllers/EntitiesController.php
git add core/views/entities.php

echo "2. Committing Changes..."
git commit -m "Feat: Topic Galaxy Engine with robust fallback data and masonry grid UI"

echo "3. Pulling Latest Changes (Rebase)..."
git pull --rebase origin main

echo "4. Pushing to GitHub..."
git push origin main

echo "==============================================="
echo " SYNC COMPLETE."
echo "==============================================="
