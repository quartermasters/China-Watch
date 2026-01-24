#!/bin/bash
# deploy_final_sync.sh
# Safely add EVERYTHING, rebase, and push

echo "1. Staging entire codebase..."
git add .

echo "2. Committing..."
git commit -m "Feat: Codebase Synchronization (UI, Perplexity, Config)"

echo "3. Pulling & Rebasing from Origin (to fix divergence)..."
git pull --rebase origin main

echo "4. Pushing to GitHub..."
git push origin main

echo "✅ Codebase Synced Successfully."
