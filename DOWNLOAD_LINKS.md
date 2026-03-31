# 📥 Quick Download Links for FacesOfNaija Setup

## 🎯 Recommended: XAMPP (All-in-One Solution)

### XAMPP 8.0.30 (Recommended)
**Includes:** PHP 8.0.30 + MariaDB 10.4.28 + Apache 2.4.56

**Direct Download:**
- **Windows 64-bit:** https://sourceforge.net/projects/xampp/files/XAMPP%20Windows/8.0.30/xampp-windows-x64-8.0.30-0-VS16-installer.exe/download
- **Size:** ~150 MB
- **Installation Guide:** See INSTALL_PHP_MYSQL.md

### XAMPP 7.4.33 (Alternative)
**Includes:** PHP 7.4.33 + MariaDB 10.4.28 + Apache 2.4.56

**Direct Download:**
- **Windows 64-bit:** https://sourceforge.net/projects/xampp/files/XAMPP%20Windows/7.4.33/xampp-windows-x64-7.4.33-1-VS16-installer.exe/download
- **Size:** ~150 MB

---

## 🔧 Alternative: WampServer

### WampServer 3.3.0
**Includes:** PHP 8.1/8.0/7.4 + MySQL 8.0 + Apache 2.4

**Download Page:**
- **Official Site:** https://www.wampserver.com/en/
- **Direct Link:** https://sourceforge.net/projects/wampserver/files/WampServer%203/WampServer%203.0.0/wampserver3.3.0_x64.exe/download
- **Size:** ~320 MB

---

## 📚 Manual Installation (Advanced Users)

### PHP 8.0
**Direct Download:**
- **PHP 8.0.30 (Thread Safe):** https://windows.php.net/downloads/releases/php-8.0.30-Win32-vs16-x64.zip
- **Size:** ~30 MB

### PHP 7.4
**Direct Download:**
- **PHP 7.4.33 (Thread Safe):** https://windows.php.net/downloads/releases/php-7.4.33-Win32-vs16-x64.zip
- **Size:** ~28 MB

### MySQL 8.0
**Direct Download:**
- **MySQL Installer:** https://dev.mysql.com/get/Downloads/MySQLInstaller/mysql-installer-community-8.0.35.0.msi
- **Size:** ~400 MB
- **Includes:** MySQL Server, MySQL Workbench, MySQL Shell

---

## 📝 Quick Installation Steps

### Using XAMPP (Easiest)

```powershell
# 1. Download XAMPP
# Click the link above or visit: https://www.apachefriends.org/download.html

# 2. Run installer as Administrator
# Right-click installer → Run as Administrator

# 3. Select Components:
#    ✅ Apache
#    ✅ MySQL
#    ✅ PHP
#    ✅ phpMyAdmin

# 4. Install to: C:\xampp

# 5. Start XAMPP Control Panel
# Start Apache and MySQL services

# 6. Test installation
# Browser: http://localhost
# phpMyAdmin: http://localhost/phpmyadmin
```

---

## ✅ Verification Commands

After installation, open PowerShell and verify:

```powershell
# Check PHP version
C:\xampp\php\php -v
# Expected output: PHP 8.0.30 or 7.4.33

# Check MySQL version
C:\xampp\mysql\bin\mysql --version
# Expected output: mysql Ver 10.4.28-MariaDB

# Test PHP connection
C:\xampp\php\php -r "echo 'PHP is working!';"
# Expected output: PHP is working!

# Test database connection
C:\xampp\php\php -r "mysqli_connect('localhost', 'root', '') or die('Connection failed');"
# Expected: No output (success) or "Connection failed" error
```

---

## 🚀 Next Steps After Installation

1. **Move project to web root:**
   ```powershell
   # Create symbolic link
   New-Item -ItemType SymbolicLink -Path "C:\xampp\htdocs\facesofnaija-web" -Target "C:\Users\Dell\source\repos\workspace\facesofnaija-web"
   ```

2. **Setup database:**
   ```powershell
   cd C:\Users\Dell\source\repos\workspace\facesofnaija-web
   C:\xampp\mysql\bin\mysql -u root < database_init.sql
   ```

3. **Access application:**
   ```
   http://localhost/facesofnaija-web
   ```

---

## 📞 Support Links

- **XAMPP Documentation:** https://www.apachefriends.org/faq_windows.html
- **PHP Windows:** https://windows.php.net/
- **MySQL Downloads:** https://dev.mysql.com/downloads/
- **Installation Guide:** INSTALL_PHP_MYSQL.md

---

## ⚠️ System Requirements

### Minimum Requirements:
- **OS:** Windows 7/8/10/11 (64-bit)
- **RAM:** 4 GB minimum (8 GB recommended)
- **Disk Space:** 2 GB free space
- **Ports:** 80 (Apache), 3306 (MySQL) must be available

### Check Port Availability:
```powershell
# Check if ports are in use
netstat -ano | findstr :80
netstat -ano | findstr :3306
```

---

**Choose XAMPP for easiest setup! All-in-one package with everything you need.**
