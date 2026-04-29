#!/bin/bash
# =============================================================================
# Equiterra Seed File Script
# =============================================================================
# Copies demo images, analysis files, and medical records from seed_data/
# into the correct upload directories inside the app.
#
# Run from the project root:
#   bash seed_data/seed_files.sh
# =============================================================================

SEED_DIR="$(dirname "$0")"
APP_DIR="app"

echo "======================================"
echo " Equiterra Seed File Setup"
echo "======================================"

# =============================================================================
# HORSE IMAGES
# Distributed across horses using the 6 stock images
# =============================================================================

echo ""
echo "Creating horse image directories..."

horses=(
    "Copper"
    "Luna"
    "Biscuit"
    "Maverick"
    "Rio"
    "Thunder"
    "Stella"
    "Duke"
    "Penny"
    "Atlas"
    "Zara"
    "Goliath"
)

stock_images=(
    "stock (1).jpg"
    "stock (2).jpg"
    "stock (3).jpg"
    "stock (4).jpg"
    "stock (5).jpg"
    "stock (6).jpg"
)

stock_count=${#stock_images[@]}

for i in "${!horses[@]}"; do
    horse="${horses[$i]}"
    dir="$APP_DIR/uploads/images/horses_by_name/$horse"
    mkdir -p "$dir"

    # Cycle through stock images
    stock="${stock_images[$((i % stock_count))]}"
    ext="${stock##*.}"
    dest="$dir/${horse}_seed_image.$ext"

    cp "$SEED_DIR/images/$stock" "$dest"
    echo "  ✔ $dest"
done

# =============================================================================
# ANALYSIS FILES
# Copy the three analysis images to all analysis record paths
# Radiograph → dorso-plantar_hoof-balance.png
# Gait Analysis → hoof_deflection.png
# Posture / Equigate → hoof_angle.png
# =============================================================================

echo ""
echo "Creating analysis directories and files..."

mkdir -p "$APP_DIR/uploads/analysis"

declare -A analysis_files
analysis_files["Radiograph"]="$SEED_DIR/analysis/dorso-plantar_hoof-balance.png"
analysis_files["Gait Analysis"]="$SEED_DIR/analysis/hoof_deflection.png"
analysis_files["Posture"]="$SEED_DIR/analysis/hoof_angle.png"
analysis_files["Equigate"]="$SEED_DIR/analysis/hoof_angle.png"

analysis_records=(
    "uploads/analysis/Biscuit_xray_2023-10.pdf|Radiograph"
    "uploads/analysis/Rio_gait_2023-11.pdf|Gait Analysis"
    "uploads/analysis/Zara_posture_2023-11.pdf|Posture"
    "uploads/analysis/Copper_gait_2023-05.pdf|Gait Analysis"
    "uploads/analysis/Copper_posture_2023-07.pdf|Posture"
    "uploads/analysis/Copper_xray_2023-11.pdf|Radiograph"
    "uploads/analysis/Luna_posture_2023-08.pdf|Posture"
    "uploads/analysis/Luna_gait_2023-10.pdf|Gait Analysis"
    "uploads/analysis/Maverick_gait_2023-04.pdf|Gait Analysis"
    "uploads/analysis/Maverick_gait_2023-07.pdf|Gait Analysis"
    "uploads/analysis/Maverick_posture_2023-10.pdf|Posture"
    "uploads/analysis/Stella_gait_2023-08.pdf|Gait Analysis"
    "uploads/analysis/Stella_equigate_2023-10.pdf|Equigate"
    "uploads/analysis/Stella_posture_2023-10.pdf|Posture"
    "uploads/analysis/Duke_xray_2023-01.pdf|Radiograph"
    "uploads/analysis/Duke_gait_2023-05.pdf|Gait Analysis"
    "uploads/analysis/Duke_gait_2023-11.pdf|Gait Analysis"
    "uploads/analysis/Duke_xray_2023-07.pdf|Radiograph"
    "uploads/analysis/Atlas_posture_2023-08.pdf|Posture"
    "uploads/analysis/Atlas_equigate_2023-11.pdf|Equigate"
    "uploads/analysis/Atlas_gait_2023-09.pdf|Gait Analysis"
)

for entry in "${analysis_records[@]}"; do
    path="${entry%%|*}"
    type="${entry##*|}"
    source="${analysis_files[$type]}"
    dest="$APP_DIR/$path"
    cp "$source" "$dest"
    echo "  ✔ $dest"
done

# =============================================================================
# MEDICAL RECORD FILES
# All records use the sample vet report PDF
# =============================================================================

echo ""
echo "Creating medical record directories and files..."

medical_records=(
    "uploads/medical_records/Copper/Copper_Dr. Sarah Mitchell_2023-08-10.pdf"
    "uploads/medical_records/Luna/Luna_Dr. Linda Park_2023-09-20.pdf"
    "uploads/medical_records/Biscuit/Biscuit_Dr. James Holloway_2023-07-15.pdf"
    "uploads/medical_records/Maverick/Maverick_Dr. Sarah Mitchell_2023-09-05.pdf"
    "uploads/medical_records/Rio/Rio_Dr. Linda Park_2023-11-08.pdf"
    "uploads/medical_records/Thunder/Thunder_Dr. James Holloway_2023-10-30.pdf"
    "uploads/medical_records/Stella/Stella_Dr. Sarah Mitchell_2023-08-12.pdf"
    "uploads/medical_records/Duke/Duke_Dr. James Holloway_2023-07-20.pdf"
    "uploads/medical_records/Duke/Duke_Dr. James Holloway_2023-11-20.pdf"
    "uploads/medical_records/Penny/Penny_Dr. Sarah Mitchell_2023-10-25.pdf"
    "uploads/medical_records/Atlas/Atlas_Dr. Linda Park_2023-10-15.pdf"
    "uploads/medical_records/Zara/Zara_Dr. Sarah Mitchell_2023-11-12.pdf"
    "uploads/medical_records/Goliath/Goliath_Dr. James Holloway_2023-11-02.pdf"
)

for path in "${medical_records[@]}"; do
    dest="$APP_DIR/$path"
    dir="$(dirname "$dest")"
    mkdir -p "$dir"
    cp "$SEED_DIR/medical/medical_vet-report_SAMPLE.pdf" "$dest"
    echo "  ✔ $dest"
done

echo ""
echo "======================================"
echo " Seed file setup complete!"
echo "======================================"