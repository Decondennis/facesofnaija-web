# 🎯 Quick XAMPP Download & Install Guide

## ⚡ 3-Step Quick Install

### Step 1: Download XAMPP (2 minutes)

**Click one of these links to download:**

#### ✅ Recommended: XAMPP with PHP 7.4
```
https://sourceforge.net/projects/xampp/files/XAMPP%20Windows/7.4.33/xampp-windows-x64-7.4.33-0-VC15-installer.exe/download
```

#### Alternative: XAMPP with PHP 8.0
```
https://sourceforge.net/projects/xampp/files/XAMPP%20Windows/8.0.30/xampp-windows-x64-8.0.30-0-VS16-installer.exe/download
```

#### Official Site
```
https://www.apachefriends.org/download.html
```

**File size:** ~150 MB  
**Time to download:** 2-5 minutes (depending on internet speed)

---

### Step 2: Install XAMPP (5 minutes)

1. **Run the installer** (xampp-windows-x64-7.4.33-0-VC15-installer.exe)

2. **Click through wizard:**
   - ✅ Select components: Apache, MySQL, PHP, phpMyAdmin
   - ✅ Installation folder: `C:\xampp` (recommended)
   - ✅ Uncheck Bitnami
   - ✅ Click "Next" → "Next" → "Finish"

3. **Wait for installation** (5-10 minutes)

---

### Step 3: Start Services (1 minute)

1. **Open XAMPP Control Panel**
   - Start Menu → XAMPP → XAMPP Control Panel
   - Or run: `C:\xampp\xampp-control.exe`

2. **Start Services:**
   - Click **"Start"** next to **Apache** (should turn green)
   - Click **"Start"** next to **MySQL** (should turn green)

3. **Test installation:**
   - Open browser: `http://localhost`
   - Should see XAMPP welcome page ✅

---

## 🚀 Automated Installation (Alternative)

Run this script in PowerShell (as Administrator):

```powershell
# Navigate to project folder
cd C:\Users\Dell\source\repos\workspace\facesofnaija-web

# Run automated installer
.\install-xampp.ps1
```

This will:
- ✅ Download XAMPP automatically
- ✅ Install XAMPP
- ✅ Configure PHP
- ✅ Set up project in htdocs
- ✅ Create database

---

## 📦 What Gets Installed

| Component | Version | Purpose |
|-----------|---------|---------|
| **Apache** | 2.4.x | Web server |
| **MySQL** | 8.0.x (MariaDB) | Database server |
| **PHP** | 7.4.33 | Programming language |
| **phpMyAdmin** | 5.x | Database management |

---

## ✅ Verification Checklist

After installation:

- [ ] XAMPP Control Panel opens
- [ ] Apache shows **green** (started)
- [ ] MySQL shows **green** (started)
- [ ] `http://localhost` shows XAMPP dashboard
- [ ] `http://localhost/phpmyadmin` opens (user: root, password: empty)

---

## 🔧 Quick Setup After XAMPP Install

### 1. Copy Project to XAMPP

```powershell
# Copy project to htdocs
Copy-Item "C:\Users\Dell\source\repos\workspace\facesofnaija-web" -Destination "C:\xampp\htdocs\facesofnaija-web" -Recurse
```

### 2. Create Database

Open: `http://localhost/phpmyadmin`

Run this SQL:
```sql
CREATE DATABASE facesofnaija CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'facesofnaija_user'@'localhost' IDENTIFIED BY 'facesofnaija_pass123';
GRANT ALL PRIVILEGES ON facesofnaija.* TO 'facesofnaija_user'@'localhost';
FLUSH PRIVILEGES;
```

### 3. Import Schema

Open XAMPP Shell and run:
```bash
mysql -u facesofnaija_user -p facesofnaija < C:/xampp/htdocs/facesofnaija-web/assets/includes/schema.sql
# Password: facesofnaija_pass123
```

### 4. Access Application

Open browser:
```
http://localhost/facesofnaija-web
```

---

## 🐛 Common Issues

### Apache Won't Start (Port 80 in use)

**Solution 1:** Stop Skype/IIS
```
1. Close Skype
2. Or in Skype: Tools → Options → Advanced → Connection
3. Uncheck "Use port 80 and 443"
```

**Solution 2:** Change Apache Port
```
1. XAMPP Control → Config → httpd.conf
2. Change "Listen 80" to "Listen 8080"
3. Access via: http://localhost:8080
```

### MySQL Won't Start (Port 3306 in use)

**Solution:**
```
1. Open Services (Win + R → services.msc)
2. Find "MySQL" or "MySQL80"
3. Stop the service
4. Start XAMPP MySQL
```

### "Access Denied" in phpMyAdmin

**Default credentials:**
```
Username: root
Password: (leave empty)
```

---

## 📞 Need Help?

- **Detailed Guide:** See `XAMPP_INSTALLATION_GUIDE.md`
- **Setup Guide:** See `SETUP_GUIDE.md`
- **XAMPP Forums:** https://community.apachefriends.org/

---

## 🎯 Quick Command Reference

| Task | Command/URL |
|------|-------------|
| Open XAMPP Control | `C:\xampp\xampp-control.exe` |
| Access localhost | `http://localhost` |
| Access phpMyAdmin | `http://localhost/phpmyadmin` |
| Access project | `http://localhost/facesofnaija-web` |
| Web root folder | `C:\xampp\htdocs\` |
| PHP config | `C:\xampp\php\php.ini` |

---

**That's it! You're ready to develop! 🚀**

For detailed instructions, see **XAMPP_INSTALLATION_GUIDE.md**
