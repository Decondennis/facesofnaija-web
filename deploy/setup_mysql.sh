#!/bin/bash
# This script sets up MySQL after reinit
TEMP_PASS='a-.=ftDfG5FI'

echo "[1] Setting root auth to socket (no password needed from root OS user)..."
mysql --connect-expired-password -u root -p"${TEMP_PASS}" -e "ALTER USER 'root'@'localhost' IDENTIFIED WITH auth_socket;" 2>&1

echo "[2] Creating database and user..."
mysql -u root -e "
CREATE DATABASE IF NOT EXISTS facesofnaija CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS 'facesofnaija_user'@'localhost' IDENTIFIED BY 'FacesDB_2026!';
GRANT ALL PRIVILEGES ON facesofnaija.* TO 'facesofnaija_user'@'localhost';
FLUSH PRIVILEGES;
" 2>&1

echo "[3] Importing database..."
mysql -u facesofnaija_user -pFacesDB_2026! facesofnaija < /tmp/facesofnaija_backup.sql 2>&1

echo "[4] Verifying..."
mysql -u facesofnaija_user -pFacesDB_2026! facesofnaija -e "SELECT COUNT(*) as tables FROM information_schema.tables WHERE table_schema='facesofnaija'; SHOW VARIABLES LIKE 'lower_case_table_names';" 2>/dev/null

echo "[5] Checking Wo_Banned_Ip access..."
mysql -u facesofnaija_user -pFacesDB_2026! facesofnaija -e "SELECT COUNT(*) FROM Wo_Banned_Ip;" 2>&1

echo "SETUP_DONE"
