# FacesOfNaija Web Application - Setup Guide

## Project Overview
**Project Name:** FacesOfNaija  
**Type:** Social Networking Platform (Based on WoWonder)  
**Technology Stack:** PHP, MySQL, HTML, CSS, JavaScript

---

## Prerequisites

Before setting up the project, ensure you have the following installed on your system:

1. **PHP** >= 5.5.0 (Recommended: PHP 7.4 or 8.0)
   - Required Extensions:
     - `mysqli`
     - `curl`
     - `gd`
     - `zip`
     - `mbstring`
     - `json`

2. **MySQL/MariaDB** >= 5.6
   - Recommended: MySQL 8.0 or MariaDB 10.5+

3. **Web Server**
   - Apache 2.4+ (with mod_rewrite enabled)
   - OR Nginx 1.18+

4. **Composer** (for dependency management)

---

## Step-by-Step Setup Instructions

### 1. Database Setup

#### Option A: Using MySQL Command Line

1. **Open MySQL Command Line or phpMyAdmin**

2. **Create Database:**
   ```sql
   CREATE DATABASE facesofnaija CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

3. **Create Database User (if needed):**
   ```sql
   CREATE USER 'facesofnaija_user'@'localhost' IDENTIFIED BY 'your_secure_password';
   GRANT ALL PRIVILEGES ON facesofnaija.* TO 'facesofnaija_user'@'localhost';
   FLUSH PRIVILEGES;
   ```

4. **Import Community Request Table:**
   ```bash
   mysql -u root -p facesofnaija < assets/includes/schema.sql
   ```

#### Option B: Using phpMyAdmin

1. Open phpMyAdmin in your browser
2. Click "New" to create a new database
3. Name: `facesofnaija`
4. Collation: `utf8mb4_unicode_ci`
5. Click "Create"
6. Select the database
7. Click "Import" tab
8. Choose file: `assets/includes/schema.sql`
9. Click "Go"

**Note:** The schema.sql file only contains the community request table. You'll need the full database dump for complete setup.

---

### 2. Configuration File Setup

The project already has a `config.php` file. Update it with your local database credentials:

1. **Open `config.php` in the root directory**

2. **Update Database Credentials:**
   ```php
   <?php
   // MySQL Hostname
   $sql_db_host = "localhost";
   
   // MySQL Database User
   $sql_db_user = "facesofnaija_user";  // Change to your DB username
   
   // MySQL Database Password
   $sql_db_pass = "your_secure_password";  // Change to your DB password
   
   // MySQL Database Name
   $sql_db_name = "facesofnaija";
   
   // Site URL
   $site_url = "http://localhost/facesofnaija-web"; // Change to your local URL
   
   // Purchase code
   $purchase_code = "330ec2fb-f1e5-4229-b7d4-866894cff196";
   ?>
   ```

3. **Save the file**

---

### 3. Web Server Configuration

#### For Apache (XAMPP/WAMP/LAMP)

1. **Copy project to web root:**
   ```bash
   # For XAMPP
   Copy to: C:\xampp\htdocs\facesofnaija-web
   
   # For WAMP
   Copy to: C:\wamp64\www\facesofnaija-web
   ```

2. **Enable mod_rewrite** (if not already enabled):
   - Open `httpd.conf`
   - Uncomment: `LoadModule rewrite_module modules/mod_rewrite.so`
   - Restart Apache

3. **Create/Verify .htaccess file** in root (should already exist)

4. **Set Permissions:**
   ```bash
   chmod -R 755 upload/
   chmod -R 755 cache/
   chmod -R 755 themes/
   ```

#### For Nginx

Create a server block configuration:

```nginx
server {
    listen 80;
    server_name localhost;
    root C:/Users/Dell/source/repos/workspace/facesofnaija-web;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~* \.(jpg|jpeg|png|gif|ico|css|js)$ {
        expires 1y;
    }
}
```

---

### 4. Folder Permissions

Ensure the following directories are writable:

```bash
chmod -R 777 upload/
chmod -R 777 cache/
chmod -R 777 themes/facesofnaija/
```

**On Windows:** Right-click folder → Properties → Security → Edit → Full Control

---

### 5. PHP Configuration

Update your `php.ini` file with these recommended settings:

```ini
upload_max_filesize = 256M
post_max_size = 256M
memory_limit = 512M
max_execution_time = 300
max_input_time = 300
display_errors = Off  # For production
error_reporting = E_ALL & ~E_NOTICE & ~E_DEPRECATED
```

Restart your web server after making changes.

---

### 6. Access the Application

1. **Start your web server** (Apache/Nginx)
2. **Start MySQL/MariaDB**
3. **Open browser and navigate to:**
   ```
   http://localhost/facesofnaija-web
   ```
   OR
   ```
   http://localhost:8080/facesofnaija-web
   ```
   (Depending on your server configuration)

---

### 7. Complete Installation

If this is a fresh install, you might need to:

1. **Access the installer** (if available):
   ```
   http://localhost/facesofnaija-web/install
   ```

2. **Or manually import the full database dump:**
   - Request the complete database export from production
   - Import it using MySQL or phpMyAdmin

3. **Create an admin user** via database:
   ```sql
   INSERT INTO Wo_Users (username, email, password, admin) 
   VALUES ('admin', 'admin@facesofnaija.com', MD5('your_password'), 1);
   ```

---

## Post-Installation Configuration

### 1. Update Site Settings

Login to admin panel:
```
http://localhost/facesofnaija-web/admin-cp
```

Configure:
- Site name and description
- Upload directories
- Email settings (SMTP)
- Social login credentials
- Payment gateways

### 2. Theme Configuration

The active theme is set in the database `Wo_Config` table:
```sql
UPDATE Wo_Config SET value = 'facesofnaija' WHERE name = 'theme';
```

### 3. Clear Cache

```bash
# Delete cache files
rm -rf cache/*.tpl
```

---

## Troubleshooting

### Common Issues

1. **Database Connection Error:**
   - Verify credentials in `config.php`
   - Ensure MySQL service is running
   - Check if user has proper privileges

2. **Blank Page / 500 Error:**
   - Check PHP error logs
   - Enable display_errors in php.ini temporarily
   - Verify all required PHP extensions are installed

3. **Upload Directory Not Writable:**
   ```bash
   chmod -R 777 upload/
   chown -R www-data:www-data upload/  # Linux
   ```

4. **Missing Database Tables:**
   - You need the complete database dump
   - The schema.sql file only contains one table
   - Contact the previous developer for full database export

5. **404 Errors on Pages:**
   - Check if mod_rewrite is enabled (Apache)
   - Verify .htaccess file exists
   - Check Nginx rewrite rules

---

## Project Structure

```
facesofnaija-web/
├── admin-panel/          # Admin dashboard
├── assets/               # Core application files
│   ├── includes/         # PHP includes and config
│   │   ├── config.php    # Database configuration
│   │   ├── schema.sql    # Community table schema
│   │   └── *.php         # Function libraries
│   ├── libraries/        # Third-party libraries
│   └── init.php          # Application initialization
├── cache/                # Cache files (auto-generated)
├── sources/              # Page source files
├── themes/               # Theme files
│   └── facesofnaija/     # Active theme
├── upload/               # User uploads
├── config.php            # Main configuration file
└── index.php             # Application entry point
```

---

## Development Workflow

### Local Development

1. **Make changes to source files**
2. **Clear cache:** Delete files in `cache/` directory
3. **Test changes**
4. **Commit to Git:**
   ```bash
   git add .
   git commit -m "Description of changes"
   git push origin master
   ```

### Database Changes

When you make database schema changes:

1. **Export the changes:**
   ```bash
   mysqldump -u root -p facesofnaija > database_backup_YYYYMMDD.sql
   ```

2. **Document changes** in migration file

---

## Security Recommendations

1. **Change default credentials** in config.php
2. **Use strong passwords** for database and admin accounts
3. **Keep PHP and MySQL updated**
4. **Disable error display** in production
5. **Set proper file permissions** (644 for files, 755 for directories)
6. **Enable HTTPS** in production
7. **Regular backups** of database and upload directory

---

## Support & Resources

- **Repository:** https://gitlab.com/kemonai/external/facesofnaija/webapp
- **Original Platform:** WoWonder Social Platform
- **PHP Version:** >= 5.5.0 (Recommended 7.4+)

---

## Quick Start Checklist

- [ ] Install PHP, MySQL, and web server
- [ ] Create database `facesofnaija`
- [ ] Import schema.sql
- [ ] Update config.php with database credentials
- [ ] Set folder permissions (upload/, cache/)
- [ ] Configure web server (Apache/Nginx)
- [ ] Update php.ini settings
- [ ] Access application in browser
- [ ] Complete installation or import full database
- [ ] Configure admin settings
- [ ] Test core functionality

---

**Last Updated:** 2024  
**Maintained By:** Development Team
