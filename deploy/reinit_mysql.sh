#!/bin/bash
set -e

echo "[1] Stopping MySQL..."
systemctl stop mysql

echo "[2] Adding lower_case_table_names=1 to config..."
sed -i '/^\[mysqld\]/a lower_case_table_names = 1' /etc/mysql/mysql.conf.d/mysqld.cnf

echo "[3] Backing up MySQL data directory..."
mv /var/lib/mysql /var/lib/mysql_backup_20260320

echo "[4] Reinitializing MySQL data directory..."
mysqld --initialize --user=mysql --lower-case-table-names=1 2>&1

echo "[5] Starting MySQL..."
systemctl start mysql
sleep 3

echo "[6] Getting temporary root password..."
TEMP_PASS=$(grep -oP '(?<=A temporary password is generated for root@localhost: )\S+' /var/log/mysql/error.log | tail -1)
echo "Temp password: $TEMP_PASS"

echo "[7] Setting new root password and creating DB..."
mysql --connect-expired-password -u root -p"$TEMP_PASS" <<EOF
ALTER USER 'root'@'localhost' IDENTIFIED WITH auth_socket;
CREATE DATABASE IF NOT EXISTS facesofnaija CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS 'facesofnaija_user'@'localhost' IDENTIFIED BY 'FacesDB_2026!';
GRANT ALL PRIVILEGES ON facesofnaija.* TO 'facesofnaija_user'@'localhost';
FLUSH PRIVILEGES;
EOF

echo "[8] Importing database..."
mysql -u facesofnaija_user -pFacesDB_2026! facesofnaija < /tmp/facesofnaija_backup.sql

echo "[9] Verifying table count..."
mysql -u facesofnaija_user -pFacesDB_2026! facesofnaija -e "SELECT COUNT(*) as tables FROM information_schema.tables WHERE table_schema='facesofnaija';"

echo "[10] Verifying case sensitivity..."
mysql -u facesofnaija_user -pFacesDB_2026! facesofnaija -e "SHOW VARIABLES LIKE 'lower_case_table_names';"

echo "DONE!"
