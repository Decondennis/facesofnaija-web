@echo off
echo ========================================
echo XAMPP MySQL - Aria Log Fix
echo ========================================
echo.
echo This will reset corrupted Aria log files
echo.
pause

echo Stopping any MySQL processes...
taskkill /F /IM mysqld.exe 2>nul

echo.
echo Backing up Aria log files...
cd C:\xampp\mysql\data
if exist aria_log_control (
    copy aria_log_control aria_log_control.backup
    echo   - aria_log_control backed up
)
if exist aria_log.00000001 (
    copy aria_log.00000001 aria_log.00000001.backup
    echo   - aria_log.00000001 backed up
)

echo.
echo Deleting corrupted Aria log files...
del /F aria_log_control 2>nul
del /F aria_log.00000001 2>nul
echo   - Aria logs deleted

echo.
echo Deleting temporary InnoDB file...
del /F ibtmp1 2>nul
echo   - ibtmp1 deleted

echo.
echo ========================================
echo Done! Now try starting MySQL in XAMPP
echo ========================================
pause
