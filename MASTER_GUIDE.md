# 🎯 FacesOfNaija - Complete Setup Master Guide

## 📋 Overview

This is your **complete roadmap** to setting up the FacesOfNaija project from scratch.

**Total Setup Time:** 30-45 minutes  
**Difficulty Level:** Beginner-Friendly

---

## 🗺️ Setup Roadmap

```
Phase 1: Install XAMPP (15-20 min)
    ↓
Phase 2: Set Up Database (5 min)
    ↓
Phase 3: Configure Project (5 min)
    ↓
Phase 4: Test & Launch (5 min)
```

---

## 📦 Phase 1: Install XAMPP (15-20 minutes)

### Quick Links

**Download XAMPP PHP 7.4 (Recommended):**
```
https://sourceforge.net/projects/xampp/files/XAMPP%20Windows/7.4.33/xampp-windows-x64-7.4.33-0-VC15-installer.exe/download
```

**Alternative - Official Site:**
```
https://www.apachefriends.org/download.html
```

### Installation Steps

1. **Download** XAMPP installer (~150 MB) - *2-5 minutes*
2. **Run** installer as Administrator
3. **Select** components: Apache, MySQL, PHP, phpMyAdmin
4. **Install** to `C:\xampp` - *5-10 minutes*
5. **Start** XAMPP Control Panel
6. **Launch** Apache and MySQL (both should be green)

### Detailed Guide
👉 Read: **XAMPP_INSTALLATION_GUIDE.md** (Complete 20-page guide)  
👉 Quick: **XAMPP_QUICK_GUIDE.md** (3-step guide)

### Automated Option
```powershell
# Run this in PowerShell (as Administrator)
.\install-xampp.ps1
```

### Verification
- ✅ `http://localhost` shows XAMPP dashboard
- ✅ `http://localhost/phpmyadmin` opens
- ✅ Apache is GREEN in XAMPP Control Panel
- ✅ MySQL is GREEN in XAMPP Control Panel

---

## 🗄️ Phase 2: Set Up Database (5 minutes)

### Step 1: Copy Project to XAMPP

```powershell
# Copy project to htdocs
Copy-Item "C:\Users\Dell\source\repos\workspace\facesofnaija-web" `
          -Destination "C:\xampp\htdocs\facesofnaija-web" -Recurse
```

Or manually:
```
Copy from: C:\Users\Dell\source\repos\workspace\facesofnaija-web
Copy to:   C:\xampp\htdocs\facesofnaija-web
```

### Step 2: Create Database

**Option A: Using phpMyAdmin (Recommended)**

1. Open: `http://localhost/phpmyadmin`
2. Click **"SQL"** tab
3. Copy and paste this:
   ```sql
   CREATE DATABASE facesofnaija CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   CREATE USER 'facesofnaija_user'@'localhost' IDENTIFIED BY 'facesofnaija_pass123';
   GRANT ALL PRIVILEGES ON facesofnaija.* TO 'facesofnaija_user'@'localhost';
   FLUSH PRIVILEGES;
   ```
4. Click **"Go"**

**Option B: Using XAMPP Shell**

1. Open XAMPP Control Panel → Click "Shell"
2. Run:
   ```bash
   mysql -u root -p < C:/xampp/htdocs/facesofnaija-web/database_init.sql
   # Press Enter (no password by default)
   ```

### Step 3: Import Schema

**Using XAMPP Shell:**
```bash
mysql -u facesofnaija_user -p facesofnaija < C:/xampp/htdocs/facesofnaija-web/assets/includes/schema.sql
# Password: facesofnaija_pass123
```

**Using phpMyAdmin:**
1. Select `facesofnaija` database
2. Click "Import" tab
3. Choose file: `C:\xampp\htdocs\facesofnaija-web\assets\includes\schema.sql`
4. Click "Go"

### Detailed Guide
👉 Read: **DATABASE_SETUP.md**

### Verification
```bash
mysql -u facesofnaija_user -p facesofnaija -e "SHOW TABLES;"
# Should show: Wo_Community_Request
```

---

## ⚙️ Phase 3: Configure Project (5 minutes)

### Step 1: Verify config.php

The file is already configured! Just verify these settings:

**Location:** `C:\xampp\htdocs\facesofnaija-web\config.php`

```php
$sql_db_host = "localhost";
$sql_db_user = "facesofnaija_user";
$sql_db_pass = "facesofnaija_pass123";
$sql_db_name = "facesofnaija";
$site_url = "http://localhost/facesofnaija-web";
```

### Step 2: Set Folder Permissions

**Windows:**
```powershell
icacls "C:\xampp\htdocs\facesofnaija-web\upload" /grant Everyone:F /T
icacls "C:\xampp\htdocs\facesofnaija-web\cache" /grant Everyone:F /T
```

**Or manually:**
- Right-click `upload` folder → Properties → Security → Edit → Everyone → Full Control
- Repeat for `cache` folder

### Step 3: Verify PHP Extensions

Run this to check:
```bash
php -m | findstr /i "mysqli curl gd zip mbstring"
```

Should show all 5 extensions. If not, edit `C:\xampp\php\php.ini` and enable them.

---

## 🚀 Phase 4: Test & Launch (5 minutes)

### Step 1: Access Application

Open browser and navigate to:
```
http://localhost/facesofnaija-web
```

### Step 2: What You Should See

- ✅ **Welcome page** or Homepage loads
- ✅ No database errors
- ✅ No PHP errors

### Step 3: Test phpMyAdmin

Access:
```
http://localhost/phpmyadmin
```

Login:
- Username: `root`
- Password: (empty, just press Enter)

### Step 4: Verify Database

In phpMyAdmin:
1. Click `facesofnaija` database
2. Should see at least 1 table: `Wo_Community_Request`

### Common First-Time Issues

**Issue: "Database connection failed"**
```
Solution: Check credentials in config.php match database_init.sql
```

**Issue: "404 Not Found"**
```
Solution: 
1. Verify project is in C:\xampp\htdocs\facesofnaija-web
2. Check Apache is running (green in XAMPP)
```

**Issue: "Upload directory not writable"**
```
Solution: Run permission commands from Phase 3, Step 2
```

---

## ✅ Complete Setup Checklist

### XAMPP Installation
- [ ] XAMPP downloaded
- [ ] XAMPP installed to C:\xampp
- [ ] Apache started (green)
- [ ] MySQL started (green)
- [ ] `http://localhost` works
- [ ] `http://localhost/phpmyadmin` works

### Database Setup
- [ ] Database `facesofnaija` created
- [ ] User `facesofnaija_user` created
- [ ] Schema imported (Wo_Community_Request table exists)
- [ ] Can connect to database

### Project Configuration
- [ ] Project copied to C:\xampp\htdocs\facesofnaija-web
- [ ] config.php exists and configured
- [ ] upload/ folder has write permissions
- [ ] cache/ folder has write permissions
- [ ] PHP extensions enabled (mysqli, curl, gd, zip, mbstring)

### Testing
- [ ] `http://localhost/facesofnaija-web` loads
- [ ] No database errors
- [ ] No PHP errors
- [ ] Can access phpMyAdmin

---

## 📚 Complete Documentation Index

### Quick Guides (5-10 minutes)
1. **QUICK_START.md** - 5-minute quick start
2. **XAMPP_QUICK_GUIDE.md** - 3-step XAMPP setup

### Installation Guides (Detailed)
3. **XAMPP_INSTALLATION_GUIDE.md** - Complete XAMPP guide (20 pages)
4. **SETUP_GUIDE.md** - Complete project setup (30 pages)
5. **DATABASE_SETUP.md** - Database configuration

### Checklists & References
6. **SETUP_CHECKLIST.md** - Interactive checklist (50+ items)
7. **PROJECT_SUMMARY.md** - Project overview
8. **THIS FILE** - Master guide (you are here)

### Scripts
9. **setup.ps1** - Project setup script
10. **install-xampp.ps1** - XAMPP auto-installer

### Database Files
11. **database_init.sql** - Database creation script
12. **assets/includes/schema.sql** - Community table schema

---

## 🎯 Quick Command Reference

### Start/Stop Services
```powershell
# Open XAMPP Control Panel
C:\xampp\xampp-control.exe

# Start services (click Start buttons for Apache & MySQL)
```

### Access URLs
```
Application:  http://localhost/facesofnaija-web
phpMyAdmin:   http://localhost/phpmyadmin
XAMPP:        http://localhost
```

### Database Commands
```bash
# Create database
mysql -u root -p < database_init.sql

# Import schema
mysql -u facesofnaija_user -p facesofnaija < assets/includes/schema.sql

# Show tables
mysql -u facesofnaija_user -p facesofnaija -e "SHOW TABLES;"

# Backup database
mysqldump -u facesofnaija_user -p facesofnaija > backup.sql
```

### File Locations
```
XAMPP:          C:\xampp\
Web Root:       C:\xampp\htdocs\
Project:        C:\xampp\htdocs\facesofnaija-web\
Config:         C:\xampp\htdocs\facesofnaija-web\config.php
PHP Config:     C:\xampp\php\php.ini
Apache Config:  C:\xampp\apache\conf\httpd.conf
```

---

## 🆘 Get Help

### Issue: Can't find the guides?
```
All guides are in: C:\Users\Dell\source\repos\workspace\facesofnaija-web\
```

### Issue: XAMPP won't start?
```
Read: XAMPP_INSTALLATION_GUIDE.md → Troubleshooting section
```

### Issue: Database errors?
```
Read: DATABASE_SETUP.md → Troubleshooting section
```

### Issue: Application won't load?
```
Read: SETUP_GUIDE.md → Troubleshooting section
```

### Issue: Everything else?
```
Read: SETUP_CHECKLIST.md and verify each item
```

---

## 🎓 Learning Path

### If you're a beginner:
1. Start with **XAMPP_QUICK_GUIDE.md**
2. Then read **QUICK_START.md**
3. Use **SETUP_CHECKLIST.md** to verify

### If you want details:
1. Read **XAMPP_INSTALLATION_GUIDE.md**
2. Then read **SETUP_GUIDE.md**
3. Reference **DATABASE_SETUP.md** as needed

### If you want automation:
1. Run **install-xampp.ps1**
2. Then run **setup.ps1**
3. Follow on-screen instructions

---

## 🎉 Success!

Once all checkboxes are marked:

✅ **XAMPP is installed and running**  
✅ **Database is created and configured**  
✅ **Project is set up correctly**  
✅ **Application loads without errors**

**You're ready to develop FacesOfNaija! 🚀**

---

## 📝 Next Steps After Setup

1. **Get full database dump** from production/previous developer
2. **Import full database** for complete functionality
3. **Create admin account** (if needed)
4. **Explore admin panel** at `/admin-cp`
5. **Test core features** (posts, users, communities)
6. **Start customization** and development

---

## 💡 Pro Tips

- **Bookmark** `http://localhost/facesofnaija-web` in your browser
- **Create desktop shortcut** for XAMPP Control Panel
- **Keep XAMPP Control Panel** open while developing
- **Clear cache** (`cache/` folder) when making changes
- **Backup database** regularly during development
- **Read error logs** when something goes wrong

---

<div align="center">

**🎊 Happy Coding! 🎊**

*You now have everything you need to set up and run FacesOfNaija!*

**Questions?** Check the documentation files listed above.

</div>

---

**Last Updated:** 2024  
**Created By:** GitHub Copilot as Project Team Lead  
**Status:** Complete ✅
