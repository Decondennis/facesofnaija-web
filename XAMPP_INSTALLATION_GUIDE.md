# 🚀 XAMPP Installation Guide for FacesOfNaija

## 📋 What is XAMPP?

XAMPP is a free, open-source cross-platform web server package that includes:
- ✅ **Apache** - Web server
- ✅ **MySQL** (MariaDB) - Database server
- ✅ **PHP** - Programming language
- ✅ **phpMyAdmin** - Database management tool

**Perfect for FacesOfNaija development!**

---

## 📥 Step 1: Download XAMPP

### Recommended Version for FacesOfNaija

**XAMPP with PHP 7.4** (Most Stable for this project)

### Download Links

#### Option 1: PHP 7.4 (Recommended)
```
https://sourceforge.net/projects/xampp/files/XAMPP%20Windows/7.4.33/xampp-windows-x64-7.4.33-0-VC15-installer.exe
```

#### Option 2: PHP 8.0 (Also Compatible)
```
https://sourceforge.net/projects/xampp/files/XAMPP%20Windows/8.0.30/xampp-windows-x64-8.0.30-0-VS16-installer.exe
```

#### Option 3: Latest Version
```
https://www.apachefriends.org/download.html
```

### Quick Download (Copy & Paste in Browser)

**For PHP 7.4:**
```
https://www.apachefriends.org/xampp-files/7.4.33/xampp-windows-x64-7.4.33-0-VC15-installer.exe
```

---

## 🔧 Step 2: Install XAMPP

### Installation Steps

1. **Run the Installer**
   - Double-click the downloaded `.exe` file
   - Click **"Yes"** if Windows asks for permission

2. **Disable Antivirus (Temporarily)**
   - Some antivirus may block XAMPP
   - Click **"Yes"** to continue

3. **Select Components**
   ✅ Apache (Required)
   ✅ MySQL (Required)
   ✅ PHP (Required)
   ✅ phpMyAdmin (Required)
   ❌ FileZilla FTP Server (Optional)
   ❌ Mercury Mail Server (Optional)
   ❌ Tomcat (Optional)
   ❌ Perl (Optional)

4. **Choose Installation Folder**
   - Default: `C:\xampp`
   - **Recommended:** Keep default
   - Click **"Next"**

5. **Bitnami Information**
   - Uncheck "Learn more about Bitnami for XAMPP"
   - Click **"Next"**

6. **Start Installation**
   - Click **"Next"** to begin installation
   - Wait 5-10 minutes for installation to complete

7. **Complete Installation**
   - Check "Do you want to start the Control Panel now?"
   - Click **"Finish"**

---

## ⚙️ Step 3: Configure XAMPP

### Launch XAMPP Control Panel

1. **Start XAMPP Control Panel**
   - Windows Start Menu → XAMPP → XAMPP Control Panel
   - Or run: `C:\xampp\xampp-control.exe`

2. **Start Required Services**
   - Click **"Start"** next to **Apache**
   - Click **"Start"** next to **MySQL**
   
   Both should turn **GREEN** when running

### Configure Apache (If Port 80 Conflict)

If Apache won't start (Port 80 in use):

1. Click **"Config"** next to Apache → **"httpd.conf"**
2. Find line: `Listen 80`
3. Change to: `Listen 8080`
4. Save and close
5. Restart Apache

### Configure MySQL (If Port 3306 Conflict)

If MySQL won't start:

1. Click **"Config"** next to MySQL → **"my.ini"**
2. Find line: `port=3306`
3. Change to: `port=3307`
4. Save and close
5. Restart MySQL

---

## ✅ Step 4: Verify Installation

### Test Apache

1. **Open Browser**
2. **Navigate to:** `http://localhost`
3. **You should see:** XAMPP Dashboard

### Test PHP

1. **Create test file:** `C:\xampp\htdocs\phpinfo.php`
2. **Add this code:**
   ```php
   <?php
   phpinfo();
   ?>
   ```
3. **Open browser:** `http://localhost/phpinfo.php`
4. **You should see:** PHP information page

### Test MySQL/phpMyAdmin

1. **Open browser:** `http://localhost/phpmyadmin`
2. **You should see:** phpMyAdmin login page
3. **Default login:**
   - Username: `root`
   - Password: (leave empty)

---

## 🔐 Step 5: Secure MySQL

### Set Root Password (Recommended)

1. **Open phpMyAdmin:** `http://localhost/phpmyadmin`
2. **Click "User accounts"** tab
3. **Click "Edit privileges"** for root user
4. **Click "Change password"**
5. **Set password:** `root123` (or your choice)
6. **Click "Go"**

### Update phpMyAdmin Config

1. **Open file:** `C:\xampp\phpMyAdmin\config.inc.php`
2. **Find line:** `$cfg['Servers'][$i]['password'] = '';`
3. **Change to:** `$cfg['Servers'][$i]['password'] = 'root123';`
4. **Save file**
5. **Refresh phpMyAdmin**

---

## 📁 Step 6: Set Up FacesOfNaija Project

### Copy Project to XAMPP

1. **Open location:**
   ```
   C:\xampp\htdocs\
   ```

2. **Copy your project:**
   ```
   Copy: C:\Users\Dell\source\repos\workspace\facesofnaija-web
   To:   C:\xampp\htdocs\facesofnaija-web
   ```

3. **Or use command:**
   ```powershell
   Copy-Item "C:\Users\Dell\source\repos\workspace\facesofnaija-web" -Destination "C:\xampp\htdocs\facesofnaija-web" -Recurse
   ```

### Update config.php

1. **Open:** `C:\xampp\htdocs\facesofnaija-web\config.php`

2. **Update database password if you set one:**
   ```php
   $sql_db_pass = "root123"; // If you set MySQL root password
   // OR
   $sql_db_pass = ""; // If no password (default)
   ```

3. **Update site URL:**
   ```php
   $site_url = "http://localhost/facesofnaija-web";
   ```

---

## 🗄️ Step 7: Create Database

### Option 1: Using phpMyAdmin

1. **Open:** `http://localhost/phpmyadmin`
2. **Click "SQL"** tab
3. **Paste this:**
   ```sql
   CREATE DATABASE facesofnaija CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   CREATE USER 'facesofnaija_user'@'localhost' IDENTIFIED BY 'facesofnaija_pass123';
   GRANT ALL PRIVILEGES ON facesofnaija.* TO 'facesofnaija_user'@'localhost';
   FLUSH PRIVILEGES;
   ```
4. **Click "Go"**

### Option 2: Using MySQL Command Line

1. **Open XAMPP Control Panel**
2. **Click "Shell"** button
3. **Run commands:**
   ```bash
   mysql -u root -p
   # Enter password (if set), or just press Enter
   
   source C:/xampp/htdocs/facesofnaija-web/database_init.sql
   ```

### Import Schema

```bash
mysql -u facesofnaija_user -p facesofnaija < C:/xampp/htdocs/facesofnaija-web/assets/includes/schema.sql
# Password: facesofnaija_pass123
```

---

## 🌐 Step 8: Access Your Application

1. **Ensure Apache & MySQL are running** (Green in XAMPP Control Panel)
2. **Open browser:**
   ```
   http://localhost/facesofnaija-web
   ```
3. **You should see:** FacesOfNaija welcome page!

---

## 🐛 Common Issues & Solutions

### Issue 1: Apache Won't Start - Port 80 Conflict

**Cause:** Skype, IIS, or other program using port 80

**Solution:**
```
1. Open XAMPP Control Panel
2. Click "Config" → "httpd.conf"
3. Change "Listen 80" to "Listen 8080"
4. Save and restart Apache
5. Access via: http://localhost:8080/facesofnaija-web
```

### Issue 2: MySQL Won't Start - Port 3306 Conflict

**Cause:** Another MySQL instance running

**Solution:**
```
1. Open Services (Windows + R → services.msc)
2. Find "MySQL" or "MySQL80" service
3. Right-click → Stop
4. Start XAMPP MySQL
```

### Issue 3: Antivirus Blocking XAMPP

**Solution:**
```
1. Add C:\xampp to antivirus exclusions
2. Temporarily disable antivirus during installation
3. Re-enable after installation
```

### Issue 4: Access Denied - phpMyAdmin

**Solution:**
```
1. Username: root
2. Password: (empty by default)
3. If you set password, use that password
```

### Issue 5: "localhost refused to connect"

**Solution:**
```
1. Check Apache is running (green in XAMPP)
2. Clear browser cache
3. Try http://127.0.0.1 instead
4. Check firewall settings
```

### Issue 6: PHP Extensions Missing

**Solution:**
```
1. Open: C:\xampp\php\php.ini
2. Remove semicolon (;) before these lines:
   extension=mysqli
   extension=curl
   extension=gd
   extension=zip
   extension=mbstring
3. Save file
4. Restart Apache
```

---

## ✨ XAMPP Tips & Tricks

### Make Apache & MySQL Start Automatically

1. **Open XAMPP Control Panel**
2. **Click "Config"** (top right)
3. **Check:**
   - ✅ Apache
   - ✅ MySQL
4. **Click "Save"**

### Create Desktop Shortcut

```
Right-click on C:\xampp\xampp-control.exe
→ Send to → Desktop (create shortcut)
```

### Enable mod_rewrite (For Clean URLs)

1. **Open:** `C:\xampp\apache\conf\httpd.conf`
2. **Find:** `#LoadModule rewrite_module modules/mod_rewrite.so`
3. **Remove #** to uncomment
4. **Save and restart Apache**

### Set Upload Limits

Edit: `C:\xampp\php\php.ini`
```ini
upload_max_filesize = 256M
post_max_size = 256M
memory_limit = 512M
max_execution_time = 300
```
**Restart Apache after changes**

---

## 📊 Quick Reference

### XAMPP Locations
```
Installation:     C:\xampp\
Web Root:         C:\xampp\htdocs\
PHP Config:       C:\xampp\php\php.ini
Apache Config:    C:\xampp\apache\conf\httpd.conf
MySQL Config:     C:\xampp\mysql\bin\my.ini
phpMyAdmin:       C:\xampp\phpMyAdmin\
```

### Default URLs
```
Dashboard:        http://localhost
phpMyAdmin:       http://localhost/phpmyadmin
Your Project:     http://localhost/facesofnaija-web
```

### Default Credentials
```
MySQL User:       root
MySQL Password:   (empty by default)
phpMyAdmin:       root / (empty)
```

### Service Ports
```
Apache:           80 (or 8080 if changed)
MySQL:            3306
```

---

## 🔄 Alternative: Standalone Installation

If you prefer separate installations:

### PHP
```
Download: https://windows.php.net/download/
Version: PHP 7.4 Thread Safe (x64)
```

### MySQL
```
Download: https://dev.mysql.com/downloads/installer/
Version: MySQL Community Server 8.0
```

### Apache
```
Download: https://www.apachelounge.com/download/
Version: Apache 2.4 Win64
```

**Note:** XAMPP is much easier for beginners!

---

## ✅ Installation Checklist

After installation, verify:

- [ ] XAMPP Control Panel opens
- [ ] Apache starts (green)
- [ ] MySQL starts (green)
- [ ] `http://localhost` shows XAMPP dashboard
- [ ] `http://localhost/phpmyadmin` works
- [ ] PHP version is 7.4+ (`http://localhost/phpinfo.php`)
- [ ] All required PHP extensions enabled
- [ ] Database `facesofnaija` created
- [ ] Project copied to `C:\xampp\htdocs\facesofnaija-web`
- [ ] `http://localhost/facesofnaija-web` loads

---

## 🎓 Next Steps

1. ✅ **XAMPP installed and running**
2. ✅ **Database created**
3. ✅ **Project in htdocs folder**
4. ➡️ **Import full database dump**
5. ➡️ **Test application features**
6. ➡️ **Start development!**

---

## 📞 Support Resources

- **XAMPP Documentation:** https://www.apachefriends.org/docs/
- **XAMPP Forum:** https://community.apachefriends.org/
- **FacesOfNaija Setup:** See SETUP_GUIDE.md

---

**Installation complete! You're ready to run FacesOfNaija! 🚀**
