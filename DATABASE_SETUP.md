# FacesOfNaija - Database Setup Instructions

## Quick Database Setup

### Option 1: Using MySQL Command Line

1. **Open Command Prompt or PowerShell**

2. **Login to MySQL:**
   ```bash
   mysql -u root -p
   ```

3. **Run the initialization script:**
   ```sql
   source database_init.sql
   ```
   OR
   ```bash
   mysql -u root -p < database_init.sql
   ```

4. **Import the community table:**
   ```bash
   mysql -u facesofnaija_user -p facesofnaija < assets/includes/schema.sql
   ```

### Option 2: Using phpMyAdmin

1. Open phpMyAdmin (usually at http://localhost/phpmyadmin)

2. Click on **SQL** tab

3. Copy and paste the content from `database_init.sql`

4. Click **Go**

5. Select the `facesofnaija` database from the left sidebar

6. Click on **Import** tab

7. Choose file: `assets/includes/schema.sql`

8. Click **Go**

### Option 3: Using MySQL Workbench

1. Open MySQL Workbench

2. Connect to your local MySQL server

3. Click on **File** → **Open SQL Script**

4. Select `database_init.sql`

5. Click **Execute** (Lightning icon)

6. Repeat for `assets/includes/schema.sql`

---

## Default Credentials

After running `database_init.sql`, use these credentials in `config.php`:

```php
$sql_db_host = "localhost";
$sql_db_user = "facesofnaija_user";
$sql_db_pass = "facesofnaija_pass123";  // CHANGE THIS FOR SECURITY!
$sql_db_name = "facesofnaija";
```

**⚠️ IMPORTANT:** Change the default password in both `database_init.sql` and `config.php` for security!

---

## Full Database Import

The `schema.sql` file only contains the community request table. For a complete setup, you need:

1. **Request the full database dump** from the production server
2. **Or request from the previous developer**

The full database should include tables like:
- Wo_Users
- Wo_Posts
- Wo_Messages
- Wo_Groups
- Wo_Pages
- Wo_Config
- And many more...

To import a full database dump:

```bash
mysql -u facesofnaija_user -p facesofnaija < full_database_dump.sql
```

---

## Verify Database Setup

Run this to check if the database was created successfully:

```sql
mysql -u facesofnaija_user -p facesofnaija -e "SHOW TABLES;"
```

You should see at least:
```
+------------------------------+
| Tables_in_facesofnaija      |
+------------------------------+
| Wo_Community_Request        |
+------------------------------+
```

---

## Troubleshooting

### Access Denied Error

If you get "Access denied" error:

```sql
-- Login as root
mysql -u root -p

-- Drop and recreate user
DROP USER IF EXISTS 'facesofnaija_user'@'localhost';
CREATE USER 'facesofnaija_user'@'localhost' IDENTIFIED BY 'your_password';
GRANT ALL PRIVILEGES ON facesofnaija.* TO 'facesofnaija_user'@'localhost';
FLUSH PRIVILEGES;
```

### User Already Exists

If you get "User already exists" error:

```sql
-- Just grant privileges to existing user
GRANT ALL PRIVILEGES ON facesofnaija.* TO 'facesofnaija_user'@'localhost';
FLUSH PRIVILEGES;
```

### Database Already Exists

If database already exists:

```sql
-- Drop and recreate (WARNING: This deletes all data!)
DROP DATABASE IF EXISTS facesofnaija;
CREATE DATABASE facesofnaija CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

---

## Next Steps

After database setup:

1. ✅ Update `config.php` with database credentials
2. ✅ Run the application: `http://localhost/facesofnaija-web`
3. ✅ Complete any installation wizard
4. ✅ Create admin user if needed

For complete setup instructions, see **SETUP_GUIDE.md**
