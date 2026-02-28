# XAMPP MySQL Diagnostic Script
Write-Host "========================================"  -ForegroundColor Cyan
Write-Host "XAMPP MySQL Diagnostic Tool" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Check port 3306
Write-Host "1. Checking Port 3306..." -ForegroundColor Yellow
$port3306 = Get-NetTCPConnection -LocalPort 3306 -ErrorAction SilentlyContinue
if ($port3306) {
    Write-Host "   WARNING: Port 3306 is IN USE!" -ForegroundColor Red
    Write-Host "   Process ID: $($port3306.OwningProcess)" -ForegroundColor Red
    $process = Get-Process -Id $port3306.OwningProcess -ErrorAction SilentlyContinue
    if ($process) {
        Write-Host "   Process Name: $($process.Name)" -ForegroundColor Red
        Write-Host "   Solution: Stop this process or change MySQL port" -ForegroundColor Yellow
    }
} else {
    Write-Host "   OK - Port 3306 is FREE" -ForegroundColor Green
}
Write-Host ""

# Check MySQL processes
Write-Host "2. Checking MySQL Processes..." -ForegroundColor Yellow
$mysqlProcesses = Get-Process -Name "*mysql*" -ErrorAction SilentlyContinue
if ($mysqlProcesses) {
    Write-Host "   WARNING - Found MySQL processes running:" -ForegroundColor Red
    foreach ($proc in $mysqlProcesses) {
        Write-Host "   - $($proc.Name) (PID: $($proc.Id))" -ForegroundColor Red
    }
    Write-Host "   Solution: Kill these processes from Task Manager" -ForegroundColor Yellow
} else {
    Write-Host "   OK - No MySQL processes running" -ForegroundColor Green
}
Write-Host ""

# Check MySQL services
Write-Host "3. Checking MySQL Services..." -ForegroundColor Yellow
$services = Get-Service -Name "*mysql*", "*mariadb*" -ErrorAction SilentlyContinue
if ($services) {
    foreach ($svc in $services) {
        if ($svc.Status -eq "Running") {
            Write-Host "   WARNING - $($svc.DisplayName) is RUNNING" -ForegroundColor Red
            Write-Host "   Service Name: $($svc.Name)" -ForegroundColor Red
            Write-Host "   Solution: Stop this service with: net stop $($svc.Name)" -ForegroundColor Yellow
        } else {
            Write-Host "   OK - $($svc.DisplayName) is $($svc.Status)" -ForegroundColor Green
        }
    }
} else {
    Write-Host "   OK - No MySQL Windows services found" -ForegroundColor Green
}
Write-Host ""

# Check XAMPP installation
Write-Host "4. Checking XAMPP Installation..." -ForegroundColor Yellow
$xamppPath = "C:\xampp\mysql\bin\mysqld.exe"
if (Test-Path $xamppPath) {
    Write-Host "   OK - XAMPP MySQL found at: $xamppPath" -ForegroundColor Green
} else {
    Write-Host "   WARNING - XAMPP MySQL not found at default location" -ForegroundColor Red
    Write-Host "   Check if XAMPP is installed in a different location" -ForegroundColor Yellow
}
Write-Host ""

# Summary
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "SUMMARY AND RECOMMENDED ACTIONS:" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan

if ($port3306) {
    Write-Host "ISSUE: Port 3306 is blocked" -ForegroundColor Red
    Write-Host "   Action: Change MySQL port to 3307 in my.ini" -ForegroundColor Yellow
    Write-Host "   Or stop the process using port 3306" -ForegroundColor Yellow
}

if ($mysqlProcesses) {
    Write-Host "ISSUE: MySQL processes are running" -ForegroundColor Red
    Write-Host "   Action: Open Task Manager (Ctrl+Shift+Esc)" -ForegroundColor Yellow
    Write-Host "   Go to Details tab, find mysqld.exe" -ForegroundColor Yellow
    Write-Host "   Right-click and End Task" -ForegroundColor Yellow
}

if ($services | Where-Object {$_.Status -eq "Running"}) {
    Write-Host "ISSUE: MySQL service is running" -ForegroundColor Red
    Write-Host "   Action: Run as Administrator:" -ForegroundColor Yellow
    foreach ($svc in $services | Where-Object {$_.Status -eq "Running"}) {
        Write-Host "   net stop $($svc.Name)" -ForegroundColor Yellow
    }
}

if (-not $port3306 -and -not $mysqlProcesses -and -not ($services | Where-Object {$_.Status -eq "Running"})) {
    Write-Host "All checks passed!" -ForegroundColor Green
    Write-Host "  MySQL should start now. If not, check:" -ForegroundColor Yellow
    Write-Host "  - XAMPP error logs" -ForegroundColor Yellow
    Write-Host "  - Antivirus settings" -ForegroundColor Yellow
    Write-Host "  - Corrupted MySQL data files" -ForegroundColor Yellow
}

Write-Host ""
Write-Host "Press any key to exit..."
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
