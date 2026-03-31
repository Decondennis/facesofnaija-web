# FacesOfNaija Setup Checklist

## Pre-Setup Requirements ✓

- [ ] PHP >= 5.5.0 installed (Recommended: 7.4 or 8.0)
- [ ] MySQL/MariaDB installed and running
- [ ] Web server (Apache/Nginx) installed
- [ ] Git installed (optional)
- [ ] Text editor (VS Code, Sublime, etc.)

## Database Setup ✓

- [ ] MySQL service is running
- [ ] Created database using `database_init.sql`
  ```bash
  mysql -u root -p < database_init.sql
  ```
- [ ] Imported schema using `assets/includes/schema.sql`
  ```bash
  mysql -u facesofnaija_user -p facesofnaija < assets/includes/schema.sql
  ```
- [ ] Verified database and tables exist
  ```bash
  mysql -u facesofnaija_user -p facesofnaija -e "SHOW TABLES;"
  ```
- [ ] (If available) Imported full database dump from production

## Application Configuration ✓

- [ ] Copied `config.local.php` to `config.php` (or created from `config.example.php`)
- [ ] Updated database credentials in `config.php`:
  - [ ] `$sql_db_host` = "localhost"
  - [ ] `$sql_db_user` = "facesofnaija_user"
  - [ ] `$sql_db_pass` = "your_password"
  - [ ] `$sql_db_name` = "facesofnaija"
- [ ] Updated `$site_url` to match your local setup:
  - [ ] Example: "http://localhost/facesofnaija-web"
- [ ] Verified `config.php` is NOT tracked by Git (.gitignore)

## PHP Extensions Check ✓

Run: `php -m` to verify these extensions are installed:

- [ ] mysqli
- [ ] curl
- [ ] gd
- [ ] zip
- [ ] mbstring
- [ ] json

If missing, enable in `php.ini` by removing `;` before:
```ini
extension=mysqli
extension=curl
extension=gd
extension=zip
extension=mbstring
```

## File Permissions ✓

### Windows
- [ ] Right-click `upload/` folder → Properties → Security → Full Control
- [ ] Right-click `cache/` folder → Properties → Security → Full Control

### Linux/Mac
```bash
chmod -R 755 /path/to/facesofnaija-web
chmod -R 777 upload/
chmod -R 777 cache/
```

- [ ] `upload/` directory is writable
- [ ] `cache/` directory is writable
- [ ] `cache/.htaccess` exists
- [ ] `cache/index.html` exists

## Web Server Configuration ✓

### Apache
- [ ] Project copied to web root (htdocs/www)
- [ ] mod_rewrite enabled
- [ ] .htaccess files present
- [ ] Virtual host configured (optional)

### Nginx
- [ ] Server block configured
- [ ] Rewrite rules added
- [ ] PHP-FPM configured

## PHP Configuration ✓

Check `php.ini` settings:

- [ ] `upload_max_filesize` >= 256M
- [ ] `post_max_size` >= 256M
- [ ] `memory_limit` >= 512M
- [ ] `max_execution_time` >= 300
- [ ] `max_input_time` >= 300

## Testing & Verification ✓

- [ ] Web server is running
- [ ] MySQL service is running
- [ ] Can access: `http://localhost/facesofnaija-web`
- [ ] No database connection errors
- [ ] Upload directory works
- [ ] Cache directory works
- [ ] Can view homepage/welcome page

## Post-Setup Configuration ✓

- [ ] Access admin panel: `/admin-cp`
- [ ] Configure site settings
- [ ] Set up email (SMTP) settings
- [ ] Configure social login (Facebook, Google, etc.)
- [ ] Set up payment gateways (if needed)
- [ ] Test user registration
- [ ] Test login functionality
- [ ] Test post creation
- [ ] Test file uploads
- [ ] Test communities feature

## Security ✓

- [ ] Changed default database password
- [ ] `config.php` is in `.gitignore`
- [ ] Error display disabled in production
- [ ] Strong admin password set
- [ ] File permissions properly set
- [ ] HTTPS configured (for production)

## Development Tools (Optional) ✓

- [ ] Git repository initialized
- [ ] `.gitignore` configured
- [ ] IDE/Editor configured
- [ ] Database management tool (phpMyAdmin, Workbench)
- [ ] Debugging tools enabled

## Documentation Review ✓

- [ ] Read `SETUP_GUIDE.md`
- [ ] Read `DATABASE_SETUP.md`
- [ ] Read `README.md`
- [ ] Understand project structure
- [ ] Know where to find logs

## Troubleshooting Steps Done ✓

If you encountered issues:

- [ ] Checked PHP error logs
- [ ] Checked MySQL error logs
- [ ] Checked web server logs
- [ ] Verified all prerequisites
- [ ] Cleared cache directory
- [ ] Restarted web server
- [ ] Restarted MySQL service

## Next Steps ✓

- [ ] Create test user account
- [ ] Explore admin panel features
- [ ] Test core functionality
- [ ] Set up development workflow
- [ ] Plan customizations/features
- [ ] Set up backup strategy

## Production Deployment (Future) ✓

- [ ] Set up production server
- [ ] Configure production database
- [ ] Update `config.php` with production credentials
- [ ] Disable debug mode
- [ ] Enable HTTPS/SSL
- [ ] Set up automated backups
- [ ] Configure monitoring
- [ ] Test in production environment
- [ ] Set up domain name
- [ ] Configure email delivery

---

## Quick Command Reference

### Database Commands
```bash
# Create database
mysql -u root -p < database_init.sql

# Import schema
mysql -u facesofnaija_user -p facesofnaija < assets/includes/schema.sql

# Show tables
mysql -u facesofnaija_user -p facesofnaija -e "SHOW TABLES;"

# Backup database
mysqldump -u facesofnaija_user -p facesofnaija > backup_$(date +%Y%m%d).sql
```

### File Permissions
```bash
# Linux/Mac
chmod -R 755 .
chmod -R 777 upload/ cache/

# Windows PowerShell
icacls upload /grant Everyone:F /T
icacls cache /grant Everyone:F /T
```

### Clear Cache
```bash
# Linux/Mac
rm -rf cache/*.tpl

# Windows PowerShell
Remove-Item cache\*.tpl -Force
```

### Check PHP Version & Extensions
```bash
php -v
php -m
php -i | grep mysqli
```

### Restart Services
```bash
# Apache (Linux)
sudo systemctl restart apache2

# Nginx (Linux)
sudo systemctl restart nginx

# MySQL (Linux)
sudo systemctl restart mysql

# XAMPP (Windows)
# Use XAMPP Control Panel
```

---

## Support & Resources

- **Setup Guide:** `SETUP_GUIDE.md`
- **Database Guide:** `DATABASE_SETUP.md`
- **README:** `README.md`
- **Repository:** https://gitlab.com/kemonai/external/facesofnaija/webapp

---

**Last Updated:** 2024
**Version:** 1.0
