#!/bin/bash
# deploy_force_fix.sh
# RESCUE SCRIPT: Fixes detached HEAD and stuck rebase, then Forces Push.

echo "1. Cleaning up stuck rebase..."
rm -rf .git/rebase-merge

echo "2. Saving current work..."
git add .
git commit -m "Rescue: Saving latest state"

echo "3. Forcing MAIN branch to point to this commit..."
# This makes the current state the authoritative 'main'
git branch -f main HEAD
git checkout main

echo "4. Force Pushing to GitHub (Overwriting remote history)..."
git push origin main --force

echo "✅ Repository Reset & Synced."
