@echo off
echo ========================================
echo Repair Corrupted MySQL System Tables
echo ========================================
echo.
echo This will repair the crashed mysql.db table
echo.
pause

cd C:\xampp\mysql\bin

echo.
echo Step 1: Repairing mysql.db table...
myisamchk.exe -r -f ..\data\mysql\db.MYI
echo.

echo Step 2: Repairing other mysql system tables...
myisamchk.exe -r -f ..\data\mysql\*.MYI
echo.

echo ========================================
echo Repair completed!
echo Now try starting MySQL in XAMPP
echo ========================================
pause
