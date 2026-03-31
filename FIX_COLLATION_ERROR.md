# 🔧 MySQL Collation Error Fix Guide

## Problem: Unknown collation 'utf8mb4_0900_ai_ci'

This error occurs when trying to import a MySQL 8.0 database into MariaDB (which comes with XAMPP).

---

## ✅ Solution 1: Fix Individual SQL Queries

If you're running individual SQL queries in phpMyAdmin:

### Replace This:
```sql
COLLATE=utf8mb4_0900_ai_ci
```

### With This:
```sql
COLLATE=utf8mb4_unicode_ci
```

### Example:
**Before (Error):**
```sql
CREATE TABLE `Wo_HTML_Emails` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `value` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
```

**After (Works):**
```sql
CREATE TABLE `Wo_HTML_Emails` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `value` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## ✅ Solution 2: Fix Full Database Dump File

If you have a `.sql` file to import:

### Step 1: Open the SQL file in a text editor

### Step 2: Find and Replace

Replace all instances:
- `utf8mb4_0900_ai_ci` → `utf8mb4_unicode_ci`
- `utf8mb3` → `utf8`

### Step 3: Save and Import

---

## ✅ Solution 3: Automated Fix Script

Use this PowerShell script to automatically fix your SQL file:

```powershell
# Fix SQL collation for MariaDB compatibility
$sqlFile = "C:\path\to\your\database.sql"
$outputFile = "C:\path\to\your\database_fixed.sql"

# Read the SQL file
$content = Get-Content $sqlFile -Raw

# Replace incompatible collations
$content = $content -replace 'utf8mb4_0900_ai_ci', 'utf8mb4_unicode_ci'
$content = $content -replace 'utf8mb3', 'utf8'

# Save the fixed file
Set-Content $outputFile $content

Write-Host "Fixed SQL file saved to: $outputFile" -ForegroundColor Green
```

**How to use:**
1. Save as `fix-sql.ps1`
2. Edit the file paths
3. Run: `.\fix-sql.ps1`
4. Import the `_fixed.sql` file

---

## ✅ Solution 4: Using sed (Linux/Mac/Git Bash)

```bash
sed -i 's/utf8mb4_0900_ai_ci/utf8mb4_unicode_ci/g' database.sql
sed -i 's/utf8mb3/utf8/g' database.sql
```

---

## 🔍 Why This Happens

| Version | Collation Support |
|---------|-------------------|
| **MySQL 8.0+** | ✅ utf8mb4_0900_ai_ci |
| **MariaDB 10.4** | ❌ utf8mb4_0900_ai_ci |
| **MariaDB 10.4** | ✅ utf8mb4_unicode_ci |
| **All versions** | ✅ utf8mb4_general_ci |

XAMPP comes with **MariaDB**, not MySQL 8.0, so you need to use compatible collations.

---

## 📊 Collation Comparison

| Collation | Speed | Accuracy | Compatibility |
|-----------|-------|----------|---------------|
| `utf8mb4_0900_ai_ci` | Fast | High | ❌ MySQL 8.0+ only |
| `utf8mb4_unicode_ci` | Medium | High | ✅ All versions |
| `utf8mb4_general_ci` | Fast | Medium | ✅ All versions |

**Recommended for FacesOfNaija:** `utf8mb4_unicode_ci`

---

## 🎯 For FacesOfNaija Project

### If You're Creating Tables Manually:

Use this template:
```sql
CREATE TABLE `table_name` (
  `id` int NOT NULL AUTO_INCREMENT,
  `column` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### If You're Importing Production Database:

1. Get the database dump from production
2. Run the fix script (Solution 3) to replace collations
3. Import the fixed file

---

## 🐛 Still Getting Errors?

### Error: "Unknown character set: 'utf8mb4'"

**Solution:** Your MySQL/MariaDB is too old. Update to MariaDB 10.2+

```powershell
# Check your version
mysql --version
```

### Error: "Access denied"

**Solution:** Check your database credentials in config.php

### Error: "Table already exists"

**Solution:** Drop the table first:
```sql
DROP TABLE IF EXISTS `Wo_HTML_Emails`;
```

---

## ✅ Verification

After fixing and importing, verify:

```sql
-- Check table was created
SHOW TABLES LIKE 'Wo_HTML_Emails';

-- Check table structure
DESCRIBE Wo_HTML_Emails;

-- Check collation
SHOW TABLE STATUS WHERE Name = 'Wo_HTML_Emails';
```

---

## 💡 Pro Tips

1. **Always use compatible collations** when developing with XAMPP
2. **Test imports** with a small table first
3. **Backup** before making changes
4. **Use `utf8mb4_unicode_ci`** as the default collation

---

## 🔧 Quick Fix for Your Current Error

Just run this in phpMyAdmin:

```sql
CREATE TABLE `Wo_HTML_Emails` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `value` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

✅ This will work with XAMPP's MariaDB!

---

**Problem Solved!** 🎉

For more help, see: SETUP_GUIDE.md → Troubleshooting section
