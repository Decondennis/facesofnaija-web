@echo off
echo ================================================
echo XAMPP MySQL Diagnostic and Fix Tool
echo ================================================
echo.

echo Step 1: Checking for processes using port 3306...
netstat -ano | findstr :3306
if %errorlevel% equ 0 (
    echo WARNING: Port 3306 is in use!
) else (
    echo OK: Port 3306 is free
)
echo.

echo Step 2: Checking for running MySQL processes...
tasklist | findstr /i "mysql"
echo.

echo Step 3: Checking for MySQL Windows Services...
sc query | findstr /i "mysql"
echo.

echo Step 4: Killing any stuck MySQL processes...
taskkill /F /IM mysqld.exe 2>nul
if %errorlevel% equ 0 (
    echo MySQL process killed
) else (
    echo No MySQL process found
)
echo.

echo ================================================
echo SOLUTIONS:
echo ================================================
echo.
echo SOLUTION 1: Change MySQL Port
echo   1. Open XAMPP Control Panel
echo   2. Click "Config" next to MySQL
echo   3. Select "my.ini"
echo   4. Find "port=3306" and change to "port=3307"
echo   5. Save and try starting MySQL again
echo.
echo SOLUTION 2: Stop Other MySQL Services
echo   Run this command as Administrator:
echo   net stop MySQL80
echo   (or whatever MySQL service is running)
echo.
echo SOLUTION 3: Reset MySQL Data (LAST RESORT - BACKS UP DATA)
echo   See: mysql_recovery_guide.txt
echo.

pause
