# 🚀 FacesOfNaija - Quick Start Guide

## ⚡ 5-Minute Setup

### Step 1: Database Setup (2 minutes)

```bash
# Open MySQL and run:
mysql -u root -p < database_init.sql
mysql -u facesofnaija_user -p facesofnaija < assets/includes/schema.sql
```

**Default Database Credentials:**
- User: `facesofnaija_user`
- Password: `facesofnaija_pass123`
- Database: `facesofnaija`

### Step 2: Configuration (1 minute)

The `config.php` is already set up for local development!

Just verify these settings match your environment:
- Database user: `facesofnaija_user`
- Database password: `facesofnaija_pass123`
- Site URL: `http://localhost/facesofnaija-web`

### Step 3: Run Setup Script (1 minute)

```powershell
# Windows PowerShell (Run as Administrator)
.\setup.ps1
```

This will check:
- ✓ PHP installation
- ✓ Required extensions
- ✓ Database connection
- ✓ Directory permissions

### Step 4: Access Application (1 minute)

1. Start your web server (XAMPP, WAMP, etc.)
2. Open browser: `http://localhost/facesofnaija-web`
3. You should see the FacesOfNaija welcome page!

---

## 📋 One-Line Commands

### Windows (PowerShell)

```powershell
# Complete setup in one go:
mysql -u root -p < database_init.sql; mysql -u facesofnaija_user -p facesofnaija < assets\includes\schema.sql; .\setup.ps1
```

### Linux/Mac

```bash
# Complete setup in one go:
mysql -u root -p < database_init.sql && \
mysql -u facesofnaija_user -p facesofnaija < assets/includes/schema.sql && \
chmod -R 777 upload/ cache/
```

---

## 🆘 Troubleshooting

### Issue: "Database connection failed"

**Fix:**
```bash
# Check if MySQL is running
# Windows: Check XAMPP Control Panel
# Linux: sudo systemctl status mysql

# Verify credentials in config.php
# Make sure database exists:
mysql -u root -p -e "SHOW DATABASES LIKE 'facesofnaija';"
```

### Issue: "Blank page / 500 error"

**Fix:**
```bash
# Check PHP extensions
php -m | grep -E "mysqli|curl|gd|zip"

# Enable error display temporarily
# Add to config.php:
ini_set('display_errors', 1);
error_reporting(E_ALL);
```

### Issue: "404 Not Found" on pages

**Fix:**
```bash
# Apache: Enable mod_rewrite
# Linux:
sudo a2enmod rewrite
sudo systemctl restart apache2

# Windows XAMPP: Check httpd.conf
# Uncomment: LoadModule rewrite_module modules/mod_rewrite.so
```

### Issue: "Upload directory not writable"

**Fix:**
```bash
# Windows: Right-click upload/ → Properties → Security → Full Control
# Linux/Mac:
chmod -R 777 upload/ cache/
```

---

## 🔑 Default Access

**Admin Panel:**
- URL: `http://localhost/facesofnaija-web/admin-cp`
- (Create admin user via database if needed)

**Database Access:**
- phpMyAdmin: `http://localhost/phpmyadmin`
- User: `facesofnaija_user`
- Password: `facesofnaija_pass123`

---

## 📁 Important Files

| File | Purpose |
|------|---------|
| `config.php` | Database and site configuration |
| `database_init.sql` | Creates database and user |
| `assets/includes/schema.sql` | Community table schema |
| `setup.ps1` | Automated setup script |
| `SETUP_GUIDE.md` | Detailed setup instructions |
| `DATABASE_SETUP.md` | Database setup details |

---

## 🎯 Next Steps

After successful setup:

1. ✅ **Test the application** - Browse around
2. ✅ **Create admin user** - Via database or admin panel
3. ✅ **Configure settings** - Admin panel → Settings
4. ✅ **Customize theme** - themes/facesofnaija/
5. ✅ **Read documentation** - SETUP_GUIDE.md

---

## 💡 Quick Tips

### Clear Cache
```bash
# After making changes
rm -rf cache/*.tpl  # Linux/Mac
Remove-Item cache\*.tpl  # Windows PowerShell
```

### Backup Database
```bash
mysqldump -u facesofnaija_user -p facesofnaija > backup.sql
```

### Check Logs
```bash
# PHP errors
tail -f /var/log/apache2/error.log  # Linux

# MySQL errors
tail -f /var/log/mysql/error.log  # Linux

# Windows: Check in XAMPP/error.log
```

### Test Database Connection
```bash
php -r "mysqli_connect('localhost', 'facesofnaija_user', 'facesofnaija_pass123', 'facesofnaija') or die('Failed');"
```

---

## 🔗 Useful Links

- **Repository:** https://gitlab.com/kemonai/external/facesofnaija/webapp
- **Setup Guide:** [SETUP_GUIDE.md](SETUP_GUIDE.md)
- **Database Guide:** [DATABASE_SETUP.md](DATABASE_SETUP.md)
- **Checklist:** [SETUP_CHECKLIST.md](SETUP_CHECKLIST.md)

---

## 📞 Need Help?

1. Check `SETUP_GUIDE.md` for detailed instructions
2. Review `SETUP_CHECKLIST.md` to ensure all steps are completed
3. Look at `DATABASE_SETUP.md` for database issues
4. Check GitLab repository issues

---

**Happy Coding! 🎉**

<div align="center">

Made with ❤️ for FacesOfNaija

</div>
