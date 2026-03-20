# =============================================================================
# FacesOfNaija - Deployment Script
# Run this from your Windows machine to deploy to Linode
# =============================================================================
# FILL THESE IN BEFORE RUNNING:
$SERVER_IP   = "YOUR_LINODE_IP"       # e.g. "172.234.56.78"
$SERVER_USER = "root"                 # usually root on a fresh Linode
$SERVER_PASS = "YOUR_ROOT_PASSWORD"   # your Linode root password
$DOMAIN      = "facesofnaija.com"     # your domain pointing to the Linode
# =============================================================================

$APP_DIR    = "C:\xampp\htdocs\facesofnaija-web"
$DB_NAME    = "facesofnaija"
$MYSQL_DUMP = "C:\xampp\mysql\bin\mysqldump.exe"
$MYSQL_BIN  = "C:\xampp\mysql\bin\mysql.exe"
$DUMP_FILE  = "$APP_DIR\deploy\db_export.sql"
$REMOTE_DIR = "/var/www/html/facesofnaija"

Write-Host "=== FacesOfNaija Deployment ===" -ForegroundColor Cyan

# --- Step 1: Export database ---
Write-Host "`n[1/4] Exporting database..." -ForegroundColor Yellow
& $MYSQL_DUMP -u root $DB_NAME | Out-File -FilePath $DUMP_FILE -Encoding utf8
if ($LASTEXITCODE -ne 0) { Write-Host "ERROR: DB export failed" -ForegroundColor Red; exit 1 }
Write-Host "    Database exported to deploy\db_export.sql" -ForegroundColor Green

# --- Step 2: Upload files via rsync (using scp fallback) ---
Write-Host "`n[2/4] Uploading application files..." -ForegroundColor Yellow
Write-Host "    This may take a few minutes for the first deploy..."

# Upload entire app excluding unnecessary local-only files
$excludes = @(
    "--exclude=deploy/db_export.sql",
    "--exclude=.git/",
    "--exclude=cache/",
    "--exclude=php-cli-test/",
    "--exclude=*.log",
    "--exclude=*.bak",
    "--exclude=home_test.txt",
    "--exclude=html_output*.txt"
)

# Use rsync if available, fallback message otherwise
$rsync = Get-Command rsync -ErrorAction SilentlyContinue
if ($rsync) {
    $rsyncArgs = @("-avz", "--progress") + $excludes + @(
        "-e", "ssh -o StrictHostKeyChecking=no",
        "$APP_DIR/",
        "${SERVER_USER}@${SERVER_IP}:${REMOTE_DIR}/"
    )
    & rsync @rsyncArgs
} else {
    Write-Host "    rsync not found - using SCP..." -ForegroundColor Yellow
    # SCP the whole directory
    scp -r -o StrictHostKeyChecking=no "$APP_DIR" "${SERVER_USER}@${SERVER_IP}:${REMOTE_DIR}"
}

# --- Step 3: Upload and import database ---
Write-Host "`n[3/4] Uploading and importing database..." -ForegroundColor Yellow
scp -o StrictHostKeyChecking=no "$DUMP_FILE" "${SERVER_USER}@${SERVER_IP}:/tmp/db_export.sql"
ssh -o StrictHostKeyChecking=no "${SERVER_USER}@${SERVER_IP}" @"
mysql -u root facesofnaija < /tmp/db_export.sql
rm /tmp/db_export.sql
"@

# --- Step 4: Update config on server ---
Write-Host "`n[4/4] Updating server config..." -ForegroundColor Yellow
ssh -o StrictHostKeyChecking=no "${SERVER_USER}@${SERVER_IP}" @"
# Update site_url in config.php
sed -i 's|http://facesofnaija-web.local|https://$DOMAIN|g' ${REMOTE_DIR}/config.php

# Update DB credentials (Linode uses a MySQL password, not blank)
sed -i "s/\\\$sql_db_user = \"root\"/\\\$sql_db_user = \"facesofnaija_user\"/" ${REMOTE_DIR}/config.php
sed -i "s/\\\$sql_db_pass = \"\"/\\\$sql_db_pass = \"CHANGE_THIS_DB_PASSWORD\"/" ${REMOTE_DIR}/config.php

# Update site_url in database
mysql -u root facesofnaija -e "UPDATE wo_config SET value='https://$DOMAIN' WHERE name='site_url';"

# Fix permissions
chown -R www-data:www-data ${REMOTE_DIR}
chmod -R 755 ${REMOTE_DIR}
chmod -R 777 ${REMOTE_DIR}/upload
chmod -R 777 ${REMOTE_DIR}/cache

echo "Config updated."
"@

Write-Host "`n=== Deployment complete! ===" -ForegroundColor Green
Write-Host "Visit: https://$DOMAIN" -ForegroundColor Cyan
Write-Host "`nNext steps:" -ForegroundColor Yellow
Write-Host "  1. Run deploy\server_setup.sh on the Linode FIRST if it's a fresh server"
Write-Host "  2. Point your domain DNS A record to: $SERVER_IP"
Write-Host "  3. Run: ssh root@$SERVER_IP 'certbot --apache -d $DOMAIN' for HTTPS"
