#!/bin/bash
# =============================================================================
# Equiterra Docker Entrypoint
# =============================================================================
# Runs seed file setup on every container start, then hands off to Apache.
# Safe to run multiple times — cp overwrites existing files.
# =============================================================================
 
echo "======================================"
echo " Equiterra Container Startup"
echo "======================================"
 
# Run the seed file script if it exists
if [ -f /seed_data/seed_files.sh ]; then
    echo ""
    echo "Populating demo files..."
    bash /seed_data/seed_files.sh
else
    echo "No seed file script found, skipping."
fi
 
echo ""
echo "Starting Apache..."
echo "======================================"
 
# Hand off to the default Apache entrypoint
exec apache2-foreground
 