#!/bin/bash

# Define the Python directory
PYTHON_DIR="core/python"
VENV_DIR="$PYTHON_DIR/venv"

echo "🐍 Setting up Python Environment for Red Pulse..."

# 1. Check if Python3 exists
if ! command -v python3 &> /dev/null; then
    echo "❌ Python3 could not be found. Please install Python3."
    exit 1
fi

# 2. Create Virtual Environment (Avoids 'permission denied' on shared hosts)
if [ ! -d "$VENV_DIR" ]; then
    echo "📦 Creating Virtual Environment in $VENV_DIR..."
    python3 -m venv $VENV_DIR
else
    echo "✅ Virtual Environment already exists."
fi

# 3. Activate and Install
echo "⬇️  Installing Dependencies from requirements.txt..."
source $VENV_DIR/bin/activate

# Upgrade pip just in case
pip install --upgrade pip

# Install deps
pip install -r $PYTHON_DIR/requirements.txt

echo ""
echo "✅ Python Setup Complete!"
echo "ℹ️  NOTE: The 'PythonSpiderWrapper.php' must point to the venv python."
echo "      Start Path: $VENV_DIR/bin/python3"
