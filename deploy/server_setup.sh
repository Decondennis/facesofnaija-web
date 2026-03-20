#!/bin/bash
# =============================================================================
# FacesOfNaija - Linode Server Setup Script
# Run this ONCE on a fresh Linode Ubuntu 22.04 server:
#   ssh root@YOUR_IP 'bash -s' < server_setup.sh
# =============================================================================

set -e

DOMAIN="facesofnaija.com"       # Change to your actual domain
DB_NAME="facesofnaija"
DB_USER="facesofnaija_user"
DB_PASS="$(openssl rand -base64 16)"   # Auto-generates a strong password
REMOTE_DIR="/var/www/html/facesofnaija"
PHP_VER="8.1"

echo "=== FacesOfNaija Server Setup ==="

# --- Update system ---
echo "[1/7] Updating system..."
apt-get update -qq && apt-get upgrade -y -qq

# --- Install Apache, PHP, MySQL ---
echo "[2/7] Installing Apache, PHP ${PHP_VER}, MySQL..."
apt-get install -y -qq \
    apache2 \
    php${PHP_VER} \
    php${PHP_VER}-mysql \
    php${PHP_VER}-gd \
    php${PHP_VER}-curl \
    php${PHP_VER}-mbstring \
    php${PHP_VER}-xml \
    php${PHP_VER}-zip \
    php${PHP_VER}-bcmath \
    php${PHP_VER}-intl \
    php${PHP_VER}-imagick \
    libapache2-mod-php${PHP_VER} \
    mysql-server \
    certbot \
    python3-certbot-apache \
    unzip \
    ffmpeg

# --- Configure PHP ---
echo "[3/7] Configuring PHP..."
PHP_INI="/etc/php/${PHP_VER}/apache2/php.ini"
sed -i "s/upload_max_filesize = .*/upload_max_filesize = 100M/" $PHP_INI
sed -i "s/post_max_size = .*/post_max_size = 100M/" $PHP_INI
sed -i "s/memory_limit = .*/memory_limit = 256M/" $PHP_INI
sed -i "s/max_execution_time = .*/max_execution_time = 300/" $PHP_INI

# --- Configure MySQL ---
echo "[4/7] Setting up MySQL database..."
mysql -u root <<EOF
CREATE DATABASE IF NOT EXISTS ${DB_NAME} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';
GRANT ALL PRIVILEGES ON ${DB_NAME}.* TO '${DB_USER}'@'localhost';
FLUSH PRIVILEGES;
EOF

# --- Create web directory ---
echo "[5/7] Creating web directory..."
mkdir -p ${REMOTE_DIR}
chown -R www-data:www-data /var/www/html

# --- Configure Apache VirtualHost ---
echo "[6/7] Configuring Apache VirtualHost..."
cat > /etc/apache2/sites-available/facesofnaija.conf <<VHOST
<VirtualHost *:80>
    ServerName ${DOMAIN}
    ServerAlias www.${DOMAIN}
    DocumentRoot ${REMOTE_DIR}

    <Directory ${REMOTE_DIR}>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog \${APACHE_LOG_DIR}/facesofnaija_error.log
    CustomLog \${APACHE_LOG_DIR}/facesofnaija_access.log combined
</VirtualHost>
VHOST

a2ensite facesofnaija.conf
a2dissite 000-default.conf
a2enmod rewrite
systemctl restart apache2

# --- Set up firewall ---
echo "[7/7] Configuring firewall..."
ufw allow OpenSSH
ufw allow 'Apache Full'
ufw --force enable

# --- Print summary ---
echo ""
echo "======================================"
echo "  Server setup complete!"
echo "======================================"
echo "  Database name:    ${DB_NAME}"
echo "  Database user:    ${DB_USER}"
echo "  Database pass:    ${DB_PASS}"
echo "  Web directory:    ${REMOTE_DIR}"
echo ""
echo "  IMPORTANT: Save the database password above!"
echo "  Update deploy.ps1 with:"
echo "    DB password: ${DB_PASS}"
echo ""
echo "  After deploying files, run for HTTPS:"
echo "    certbot --apache -d ${DOMAIN} -d www.${DOMAIN}"
echo "======================================"
