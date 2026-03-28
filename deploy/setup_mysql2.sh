#!/bin/bash
TEMP_PASS='a-.=ftDfG5FI'

echo "[1] Setting root password to a permanent one..."
mysql --connect-expired-password -u root -p"${TEMP_PASS}" -e "ALTER USER 'root'@'localhost' IDENTIFIED BY 'RootMySQL_2026!';" 2>&1

echo "[2] Creating database and user..."
mysql -u root -pRootMySQL_2026! -e "
DROP DATABASE IF EXISTS facesofnaija;
CREATE DATABASE facesofnaija CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
DROP USER IF EXISTS 'facesofnaija_user'@'localhost';
CREATE USER 'facesofnaija_user'@'localhost' IDENTIFIED BY 'FacesDB_2026!';
GRANT ALL PRIVILEGES ON facesofnaija.* TO 'facesofnaija_user'@'localhost';
FLUSH PRIVILEGES;
" 2>&1

echo "[3] Importing database (this may take a moment)..."
mysql -u facesofnaija_user -pFacesDB_2026! facesofnaija < /tmp/facesofnaija_backup.sql 2>&1

echo "[4] Verifying row counts..."
mysql -u facesofnaija_user -pFacesDB_2026! facesofnaija -e "
SELECT COUNT(*) as table_count FROM information_schema.tables WHERE table_schema='facesofnaija';
SELECT COUNT(*) as users FROM wo_users;
SHOW VARIABLES LIKE 'lower_case_table_names';
SELECT COUNT(*) as banned_ip_rows FROM Wo_Banned_Ip;
" 2>/dev/null

echo "SETUP_DONE"
