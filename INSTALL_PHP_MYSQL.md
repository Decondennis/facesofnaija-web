# 🚀 Installing PHP & MySQL for FacesOfNaija

## ✅ Recommended Versions

Based on FacesOfNaija requirements:
- **PHP:** 7.4 or 8.0 (Minimum: 5.5.0)
- **MySQL:** 8.0 or MariaDB 10.5+ (Minimum: 5.6)

---

## 🎯 Option 1: XAMPP (Recommended - All-in-One)

XAMPP includes everything you need: PHP, MySQL (MariaDB), Apache, and phpMyAdmin.

### Download XAMPP

1. **Go to official website:**
   ```
   https://www.apachefriends.org/download.html
   ```

2. **Download XAMPP for Windows:**
   - **XAMPP 8.0.x** (Includes PHP 8.0 + MariaDB 10.4)
   - **XAMPP 7.4.x** (Includes PHP 7.4 + MariaDB 10.4)
   
   **Direct Download Links:**
   - PHP 8.0: https://sourceforge.net/projects/xampp/files/XAMPP%20Windows/8.0.30/
   - PHP 7.4: https://sourceforge.net/projects/xampp/files/XAMPP%20Windows/7.4.33/

3. **Choose installer:**
   - File: `xampp-windows-x64-8.0.30-0-VS16-installer.exe` (for PHP 8.0)
   - Size: ~150 MB

### Install XAMPP

1. **Run the installer** (Right-click → Run as Administrator)

2. **Select Components:**
   - ✅ Apache (Web Server)
   - ✅ MySQL (Database)
   - ✅ PHP
   - ✅ phpMyAdmin
   - ⬜ FileZilla (Optional)
   - ⬜ Mercury (Optional)
   - ⬜ Tomcat (Optional)

3. **Choose Installation Folder:**
   - Default: `C:\xampp`
   - Click "Next"

4. **Wait for installation** (3-5 minutes)

5. **Finish installation**
   - ✅ Check "Do you want to start the Control Panel now?"
   - Click "Finish"

### Configure XAMPP

1. **Start XAMPP Control Panel**

2. **Start Services:**
   - Click "Start" next to **Apache**
   - Click "Start" next to **MySQL**
   - Both should show green "Running" status

3. **Test PHP:**
   - Open browser: `http://localhost`
   - You should see XAMPP dashboard
   - Click "phpInfo" to see PHP version

4. **Test MySQL:**
   - Open browser: `http://localhost/phpmyadmin`
   - You should see phpMyAdmin login
   - Default user: `root` (no password)

### Configure PHP Extensions

1. **Open php.ini:**
   - In XAMPP Control Panel, click "Config" next to Apache
   - Select "PHP (php.ini)"

2. **Enable Required Extensions:**
   Find and uncomment (remove `;` from start):
   ```ini
   extension=mysqli
   extension=curl
   extension=gd
   extension=zip
   extension=mbstring
   ```

3. **Update Settings:**
   ```ini
   upload_max_filesize = 256M
   post_max_size = 256M
   memory_limit = 512M
   max_execution_time = 300
   max_input_time = 300
   ```

4. **Save and Close**

5. **Restart Apache** in XAMPP Control Panel

### Verify Installation

```powershell
# Open PowerShell and run:
C:\xampp\php\php -v
# Should show: PHP 8.0.x or 7.4.x

C:\xampp\mysql\bin\mysql --version
# Should show: MySQL 10.4.x (MariaDB)
```

---

## 🎯 Option 2: WampServer (Alternative)

WampServer is another popular all-in-one solution.

### Download WampServer

1. **Go to official website:**
   ```
   https://www.wampserver.com/en/
   ```

2. **Download WampServer 3.3.x:**
   - Includes PHP 8.1, 8.0, 7.4
   - Includes MySQL 8.0
   - Includes Apache 2.4

3. **Download link:**
   ```
   https://sourceforge.net/projects/wampserver/files/WampServer%203/
   ```

### Install WampServer

1. **Run installer as Administrator**

2. **Choose installation directory:**
   - Default: `C:\wamp64`

3. **Install with defaults**

4. **Start WampServer:**
   - System tray icon should be green
   - If orange/red, some services aren't running

5. **Access:**
   - Browser: `http://localhost`
   - phpMyAdmin: `http://localhost/phpmyadmin`

---

## 🎯 Option 3: Manual Installation (Advanced)

For advanced users who want more control.

### Download PHP

1. **Go to PHP Downloads:**
   ```
   https://windows.php.net/download/
   ```

2. **Download PHP 8.0 or 7.4:**
   - Choose: "VS16 x64 Thread Safe" (Zip)
   - Example: `php-8.0.30-Win32-vs16-x64.zip`

3. **Extract to:**
   ```
   C:\php
   ```

4. **Add to PATH:**
   - Open System Environment Variables
   - Add `C:\php` to PATH

5. **Configure php.ini:**
   - Copy `php.ini-development` to `php.ini`
   - Enable extensions as shown above

### Download MySQL

1. **Go to MySQL Downloads:**
   ```
   https://dev.mysql.com/downloads/installer/
   ```

2. **Download MySQL Installer:**
   - Choose: "Windows (x86, 32-bit), MSI Installer"
   - Size: ~400 MB

3. **Run MySQL Installer:**
   - Setup Type: "Developer Default"
   - Click "Execute" to download components

4. **Configure MySQL:**
   - Type: "Development Computer"
   - Port: 3306 (default)
   - Root Password: Set a strong password

5. **Complete installation**

6. **Add to PATH:**
   - Add `C:\Program Files\MySQL\MySQL Server 8.0\bin` to PATH

---

## ✅ Post-Installation Setup

### 1. Verify Installations

```powershell
# Check PHP
php -v
# Expected: PHP 7.4.x or 8.0.x

# Check MySQL
mysql --version
# Expected: mysql Ver 8.0.x

# Check PHP Extensions
php -m | Select-String -Pattern "mysqli|curl|gd|zip|mbstring"
```

### 2. Configure for FacesOfNaija

#### Update config.php

Your `config.php` is already configured with:
```php
$sql_db_host = "localhost";
$sql_db_user = "facesofnaija_user";
$sql_db_pass = "facesofnaija_pass123";
$sql_db_name = "facesofnaija";
```

#### Create Database

```powershell
# Run from project directory
mysql -u root -p < database_init.sql
```

Or use phpMyAdmin:
1. Open: `http://localhost/phpmyadmin`
2. Click "Import"
3. Choose `database_init.sql`
4. Click "Go"

### 3. Move Project to Web Root

#### For XAMPP:
```powershell
# Copy project to htdocs
Copy-Item -Path "C:\Users\Dell\source\repos\workspace\facesofnaija-web" -Destination "C:\xampp\htdocs\" -Recurse

# Or create symbolic link
New-Item -ItemType SymbolicLink -Path "C:\xampp\htdocs\facesofnaija-web" -Target "C:\Users\Dell\source\repos\workspace\facesofnaija-web"
```

#### For WampServer:
```powershell
# Copy project to www
Copy-Item -Path "C:\Users\Dell\source\repos\workspace\facesofnaija-web" -Destination "C:\wamp64\www\" -Recurse
```

### 4. Set Permissions

```powershell
# Navigate to project
cd C:\xampp\htdocs\facesofnaija-web

# Set permissions
icacls upload /grant Everyone:F /T
icacls cache /grant Everyone:F /T
```

### 5. Test Installation

1. **Start Web Server** (Apache in XAMPP/WAMP)

2. **Access Application:**
   ```
   http://localhost/facesofnaija-web
   ```

3. **Check phpInfo:**
   Create `test.php` in project root:
   ```php
   <?php phpinfo(); ?>
   ```
   Access: `http://localhost/facesofnaija-web/test.php`

---

## 🐛 Troubleshooting

### Issue: "Port 80 already in use"

**Solution:**
```
1. Another program is using port 80 (often Skype, IIS)
2. In XAMPP: Config → Apache (httpd.conf)
3. Change: Listen 80 → Listen 8080
4. Restart Apache
5. Access: http://localhost:8080/facesofnaija-web
```

### Issue: "MySQL won't start"

**Solution:**
```
1. Check if another MySQL is running
2. Task Manager → Services → Stop "MySQL"
3. In XAMPP: Config → MySQL (my.ini)
4. Change port if needed: port=3306 → port=3307
5. Restart MySQL
```

### Issue: "PHP extensions not loading"

**Solution:**
```
1. Check php.ini location: php --ini
2. Verify extension_dir in php.ini
3. Ensure extensions are uncommented (no ;)
4. Restart Apache/Web Server
```

### Issue: "Cannot find php.exe"

**Solution:**
```powershell
# Add PHP to PATH
[Environment]::SetEnvironmentVariable("Path", "$env:Path;C:\xampp\php", "User")

# Or for WAMP:
[Environment]::SetEnvironmentVariable("Path", "$env:Path;C:\wamp64\bin\php\php7.4.33", "User")

# Restart PowerShell and test
php -v
```

---

## 📋 Quick Installation Checklist

### XAMPP Installation
- [ ] Downloaded XAMPP installer
- [ ] Installed XAMPP to C:\xampp
- [ ] Started Apache service
- [ ] Started MySQL service
- [ ] Verified http://localhost works
- [ ] Verified http://localhost/phpmyadmin works
- [ ] Configured php.ini extensions
- [ ] Updated PHP settings (memory, upload size)
- [ ] Restarted Apache

### Database Setup
- [ ] Accessed phpMyAdmin
- [ ] Imported database_init.sql
- [ ] Created facesofnaija database
- [ ] Created facesofnaija_user
- [ ] Imported schema.sql
- [ ] Verified tables exist

### Project Setup
- [ ] Moved/Linked project to htdocs/www
- [ ] Updated config.php if needed
- [ ] Set upload/ permissions
- [ ] Set cache/ permissions
- [ ] Tested http://localhost/facesofnaija-web
- [ ] Application loads successfully

---

## 🎯 Recommended Configuration

### For Development (Your Current Setup)

**XAMPP 8.0.30:**
- PHP 8.0.30
- MariaDB 10.4.28
- Apache 2.4.56
- phpMyAdmin 5.2.1

**Installation Path:** `C:\xampp`

**Project Path:** `C:\xampp\htdocs\facesofnaija-web`

**Access URL:** `http://localhost/facesofnaija-web`

**phpMyAdmin:** `http://localhost/phpmyadmin`

---

## 📞 Additional Resources

### Official Documentation
- PHP Manual: https://www.php.net/manual/en/
- MySQL Docs: https://dev.mysql.com/doc/
- XAMPP FAQ: https://www.apachefriends.org/faq_windows.html

### Video Tutorials
- XAMPP Installation: Search YouTube for "XAMPP installation Windows 2024"
- PHP MySQL Setup: Search "PHP MySQL local development setup"

### Community Support
- Stack Overflow: https://stackoverflow.com/questions/tagged/xampp
- PHP Community: https://www.php.net/support.php

---

## ✅ Success Criteria

Installation is successful when:
- ✅ `php -v` shows PHP 7.4+ or 8.0+
- ✅ `mysql --version` shows MySQL 5.6+
- ✅ `http://localhost` displays XAMPP/WAMP dashboard
- ✅ `http://localhost/phpmyadmin` opens successfully
- ✅ All required PHP extensions are loaded
- ✅ Database created and accessible
- ✅ FacesOfNaija application loads without errors

---

## 🚀 Next Steps

After successful installation:

1. ✅ Complete database setup (run `database_init.sql`)
2. ✅ Import schema (run `assets\includes\schema.sql`)
3. ✅ Follow QUICK_START.md for application setup
4. ✅ Access application at http://localhost/facesofnaija-web
5. ✅ Review SETUP_GUIDE.md for configuration

---

**Good luck with your installation! 🎉**

For any issues, refer to the troubleshooting section or check the official XAMPP documentation.
