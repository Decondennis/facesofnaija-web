# FacesOfNaija - XAMPP Auto Download & Setup Script
# Run this script in PowerShell as Administrator

Write-Host "=================================================================" -ForegroundColor Cyan
Write-Host "   FacesOfNaija - XAMPP Download & Installation Script" -ForegroundColor Cyan
Write-Host "=================================================================" -ForegroundColor Cyan
Write-Host ""

# Check if running as Administrator
$isAdmin = ([Security.Principal.WindowsPrincipal] [Security.Principal.WindowsIdentity]::GetCurrent()).IsInRole([Security.Principal.WindowsBuiltInRole]::Administrator)
if (-not $isAdmin) {
    Write-Host "ERROR: Please run this script as Administrator!" -ForegroundColor Red
    Write-Host "Right-click PowerShell → Run as Administrator" -ForegroundColor Yellow
    pause
    exit 1
}

# Variables
$xamppVersion = "7.4.33"
$downloadUrl = "https://sourceforge.net/projects/xampp/files/XAMPP%20Windows/7.4.33/xampp-windows-x64-7.4.33-0-VC15-installer.exe/download"
$installerPath = "$env:TEMP\xampp-installer.exe"
$xamppPath = "C:\xampp"
$projectSource = "C:\Users\Dell\source\repos\workspace\facesofnaija-web"
$projectDest = "C:\xampp\htdocs\facesofnaija-web"

Write-Host "[1/8] Checking for existing XAMPP installation..." -ForegroundColor Yellow
if (Test-Path $xamppPath) {
    Write-Host "  ⚠ XAMPP already installed at: $xamppPath" -ForegroundColor Yellow
    $overwrite = Read-Host "  Do you want to continue? (y/n)"
    if ($overwrite -ne 'y') {
        Write-Host "  Installation cancelled." -ForegroundColor Red
        exit 0
    }
} else {
    Write-Host "  ✓ No existing XAMPP installation found" -ForegroundColor Green
}

Write-Host "`n[2/8] Downloading XAMPP PHP $xamppVersion..." -ForegroundColor Yellow
Write-Host "  This may take 5-10 minutes depending on your internet speed..." -ForegroundColor Gray

try {
    # Download XAMPP installer
    $ProgressPreference = 'SilentlyContinue'
    Invoke-WebRequest -Uri $downloadUrl -OutFile $installerPath -UseBasicParsing
    Write-Host "  ✓ XAMPP installer downloaded successfully" -ForegroundColor Green
} catch {
    Write-Host "  ✗ Failed to download XAMPP automatically" -ForegroundColor Red
    Write-Host "  Please download manually from:" -ForegroundColor Yellow
    Write-Host "  https://www.apachefriends.org/download.html" -ForegroundColor Cyan
    Write-Host "  Then run: xampp-windows-x64-7.4.33-0-VC15-installer.exe" -ForegroundColor Yellow
    pause
    exit 1
}

Write-Host "`n[3/8] Installing XAMPP..." -ForegroundColor Yellow
Write-Host "  Please follow the installation wizard..." -ForegroundColor Gray
Write-Host "  Recommended settings:" -ForegroundColor Gray
Write-Host "    - Installation folder: C:\xampp" -ForegroundColor Gray
Write-Host "    - Select: Apache, MySQL, PHP, phpMyAdmin" -ForegroundColor Gray
Write-Host ""

try {
    # Run installer
    Start-Process -FilePath $installerPath -Wait
    Write-Host "  ✓ XAMPP installation completed" -ForegroundColor Green
} catch {
    Write-Host "  ✗ Installation failed or was cancelled" -ForegroundColor Red
    pause
    exit 1
}

# Wait for installation to complete
Write-Host "`n  Waiting for XAMPP installation to finish..." -ForegroundColor Gray
Start-Sleep -Seconds 5

Write-Host "`n[4/8] Verifying XAMPP installation..." -ForegroundColor Yellow
if (Test-Path "$xamppPath\xampp-control.exe") {
    Write-Host "  ✓ XAMPP installed successfully at: $xamppPath" -ForegroundColor Green
} else {
    Write-Host "  ✗ XAMPP installation not found" -ForegroundColor Red
    Write-Host "  Please install XAMPP manually" -ForegroundColor Yellow
    pause
    exit 1
}

Write-Host "`n[5/8] Starting XAMPP services..." -ForegroundColor Yellow
Write-Host "  Starting Apache and MySQL..." -ForegroundColor Gray

# Start XAMPP Control Panel
Start-Process "$xamppPath\xampp-control.exe"
Write-Host "  ✓ XAMPP Control Panel launched" -ForegroundColor Green
Write-Host "  Please start Apache and MySQL from the control panel" -ForegroundColor Yellow
Write-Host "  (Click 'Start' button next to Apache and MySQL)" -ForegroundColor Yellow
Write-Host ""
Read-Host "Press Enter after starting Apache and MySQL"

Write-Host "`n[6/8] Configuring PHP..." -ForegroundColor Yellow
$phpIni = "$xamppPath\php\php.ini"

if (Test-Path $phpIni) {
    # Backup original php.ini
    Copy-Item $phpIni "$phpIni.backup" -Force
    
    # Read php.ini
    $content = Get-Content $phpIni
    
    # Enable required extensions
    $content = $content -replace ';extension=mysqli', 'extension=mysqli'
    $content = $content -replace ';extension=curl', 'extension=curl'
    $content = $content -replace ';extension=gd', 'extension=gd'
    $content = $content -replace ';extension=zip', 'extension=zip'
    $content = $content -replace ';extension=mbstring', 'extension=mbstring'
    
    # Update upload limits
    $content = $content -replace 'upload_max_filesize = 2M', 'upload_max_filesize = 256M'
    $content = $content -replace 'post_max_size = 8M', 'post_max_size = 256M'
    $content = $content -replace 'memory_limit = 128M', 'memory_limit = 512M'
    $content = $content -replace 'max_execution_time = 30', 'max_execution_time = 300'
    
    # Save php.ini
    Set-Content $phpIni $content
    
    Write-Host "  ✓ PHP configured successfully" -ForegroundColor Green
    Write-Host "  ✓ Extensions enabled: mysqli, curl, gd, zip, mbstring" -ForegroundColor Green
    Write-Host "  ✓ Upload limits increased to 256M" -ForegroundColor Green
} else {
    Write-Host "  ⚠ php.ini not found at: $phpIni" -ForegroundColor Yellow
}

Write-Host "`n[7/8] Setting up FacesOfNaija project..." -ForegroundColor Yellow

if (Test-Path $projectSource) {
    Write-Host "  Copying project files..." -ForegroundColor Gray
    
    # Create htdocs if not exists
    if (-not (Test-Path "$xamppPath\htdocs")) {
        New-Item -ItemType Directory -Path "$xamppPath\htdocs" -Force | Out-Null
    }
    
    # Copy project
    Copy-Item $projectSource -Destination $projectDest -Recurse -Force
    Write-Host "  ✓ Project copied to: $projectDest" -ForegroundColor Green
    
    # Set permissions on cache and upload folders
    if (Test-Path "$projectDest\cache") {
        icacls "$projectDest\cache" /grant Everyone:F /T | Out-Null
        Write-Host "  ✓ Cache directory permissions set" -ForegroundColor Green
    }
    
    if (Test-Path "$projectDest\upload") {
        icacls "$projectDest\upload" /grant Everyone:F /T | Out-Null
        Write-Host "  ✓ Upload directory permissions set" -ForegroundColor Green
    }
} else {
    Write-Host "  ⚠ Project source not found at: $projectSource" -ForegroundColor Yellow
    Write-Host "  Please copy your project manually to: $projectDest" -ForegroundColor Yellow
}

Write-Host "`n[8/8] Creating database..." -ForegroundColor Yellow
Write-Host "  Opening phpMyAdmin in browser..." -ForegroundColor Gray
Start-Sleep -Seconds 2
Start-Process "http://localhost/phpmyadmin"

Write-Host ""
Write-Host "  Please run these SQL commands in phpMyAdmin:" -ForegroundColor Yellow
Write-Host ""
Write-Host "  CREATE DATABASE facesofnaija CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" -ForegroundColor Cyan
Write-Host "  CREATE USER 'facesofnaija_user'@'localhost' IDENTIFIED BY 'facesofnaija_pass123';" -ForegroundColor Cyan
Write-Host "  GRANT ALL PRIVILEGES ON facesofnaija.* TO 'facesofnaija_user'@'localhost';" -ForegroundColor Cyan
Write-Host "  FLUSH PRIVILEGES;" -ForegroundColor Cyan
Write-Host ""
Read-Host "Press Enter after creating the database"

# Test database connection
Write-Host "`n  Testing database connection..." -ForegroundColor Gray
$testScript = @"
<?php
\$conn = @mysqli_connect('localhost', 'facesofnaija_user', 'facesofnaija_pass123', 'facesofnaija');
if (\$conn) {
    echo 'SUCCESS';
    mysqli_close(\$conn);
} else {
    echo 'FAILED: ' . mysqli_connect_error();
}
?>
"@

$testFile = "$xamppPath\htdocs\test_db.php"
Set-Content $testFile $testScript

$testResult = php $testFile
Remove-Item $testFile -Force

if ($testResult -eq 'SUCCESS') {
    Write-Host "  ✓ Database connection successful!" -ForegroundColor Green
} else {
    Write-Host "  ⚠ Database connection failed: $testResult" -ForegroundColor Yellow
    Write-Host "  Please verify database credentials" -ForegroundColor Yellow
}

# Clean up installer
Write-Host "`n  Cleaning up temporary files..." -ForegroundColor Gray
if (Test-Path $installerPath) {
    Remove-Item $installerPath -Force
    Write-Host "  ✓ Installer removed" -ForegroundColor Green
}

Write-Host ""
Write-Host "=================================================================" -ForegroundColor Cyan
Write-Host "   Installation Complete!" -ForegroundColor Green
Write-Host "=================================================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Next Steps:" -ForegroundColor Yellow
Write-Host "  1. Ensure Apache and MySQL are running (green in XAMPP Control)" -ForegroundColor White
Write-Host "  2. Import schema:" -ForegroundColor White
Write-Host "     mysql -u facesofnaija_user -p facesofnaija < assets\includes\schema.sql" -ForegroundColor Cyan
Write-Host "  3. Access your application:" -ForegroundColor White
Write-Host "     http://localhost/facesofnaija-web" -ForegroundColor Cyan
Write-Host ""
Write-Host "Important Locations:" -ForegroundColor Yellow
Write-Host "  XAMPP:     $xamppPath" -ForegroundColor White
Write-Host "  Project:   $projectDest" -ForegroundColor White
Write-Host "  phpMyAdmin: http://localhost/phpmyadmin" -ForegroundColor White
Write-Host ""
Write-Host "Default Database Credentials:" -ForegroundColor Yellow
Write-Host "  User:     facesofnaija_user" -ForegroundColor White
Write-Host "  Password: facesofnaija_pass123" -ForegroundColor White
Write-Host "  Database: facesofnaija" -ForegroundColor White
Write-Host ""
Write-Host "For detailed setup instructions, see: XAMPP_INSTALLATION_GUIDE.md" -ForegroundColor Green
Write-Host ""

# Open project in browser
$openBrowser = Read-Host "Open project in browser now? (y/n)"
if ($openBrowser -eq 'y') {
    Start-Process "http://localhost/facesofnaija-web"
}

Write-Host ""
Write-Host "Setup complete! Happy coding! 🚀" -ForegroundColor Green
Write-Host ""
