# FacesOfNaija - Quick Setup Script for Windows
# Run this script in PowerShell as Administrator

Write-Host "=====================================" -ForegroundColor Cyan
Write-Host "FacesOfNaija Setup Script" -ForegroundColor Cyan
Write-Host "=====================================" -ForegroundColor Cyan
Write-Host ""

# Check if running as Administrator
$isAdmin = ([Security.Principal.WindowsPrincipal] [Security.Principal.WindowsIdentity]::GetCurrent()).IsInRole([Security.Principal.WindowsBuiltInRole]::Administrator)
if (-not $isAdmin) {
    Write-Host "ERROR: Please run this script as Administrator!" -ForegroundColor Red
    exit 1
}

# Variables
$projectPath = Get-Location
$cachePath = Join-Path $projectPath "cache"
$uploadPath = Join-Path $projectPath "upload"

# Step 1: Check PHP Installation
Write-Host "[1/7] Checking PHP installation..." -ForegroundColor Yellow
try {
    $phpVersion = php -v 2>$null
    if ($?) {
        Write-Host "✓ PHP is installed" -ForegroundColor Green
        $phpVersion | Select-Object -First 1 | Write-Host -ForegroundColor Gray
    } else {
        throw "PHP not found"
    }
} catch {
    Write-Host "✗ PHP is not installed or not in PATH" -ForegroundColor Red
    Write-Host "  Please install PHP >= 5.5.0 (Recommended: PHP 7.4 or 8.0)" -ForegroundColor Yellow
    exit 1
}

# Step 2: Check PHP Extensions
Write-Host "`n[2/7] Checking required PHP extensions..." -ForegroundColor Yellow
$requiredExtensions = @('mysqli', 'curl', 'gd', 'zip', 'mbstring', 'json')
$missingExtensions = @()

foreach ($ext in $requiredExtensions) {
    $result = php -m | Select-String -Pattern "^$ext$" -Quiet
    if ($result) {
        Write-Host "  ✓ $ext" -ForegroundColor Green
    } else {
        Write-Host "  ✗ $ext (MISSING)" -ForegroundColor Red
        $missingExtensions += $ext
    }
}

if ($missingExtensions.Count -gt 0) {
    Write-Host "`nWARNING: Missing extensions: $($missingExtensions -join ', ')" -ForegroundColor Red
    Write-Host "Please enable these extensions in php.ini" -ForegroundColor Yellow
}

# Step 3: Create/Check Cache Directory
Write-Host "`n[3/7] Setting up cache directory..." -ForegroundColor Yellow
if (-not (Test-Path $cachePath)) {
    New-Item -ItemType Directory -Path $cachePath -Force | Out-Null
    Write-Host "  ✓ Cache directory created" -ForegroundColor Green
} else {
    Write-Host "  ✓ Cache directory exists" -ForegroundColor Green
}

# Create .htaccess and index.html for cache
$htaccessContent = "deny from all"
$htaccessPath = Join-Path $cachePath ".htaccess"
if (-not (Test-Path $htaccessPath)) {
    Set-Content -Path $htaccessPath -Value $htaccessContent
    Write-Host "  ✓ Created cache/.htaccess" -ForegroundColor Green
}

$indexPath = Join-Path $cachePath "index.html"
if (-not (Test-Path $indexPath)) {
    Set-Content -Path $indexPath -Value ""
    Write-Host "  ✓ Created cache/index.html" -ForegroundColor Green
}

# Step 4: Check Upload Directory
Write-Host "`n[4/7] Checking upload directory..." -ForegroundColor Yellow
if (Test-Path $uploadPath) {
    Write-Host "  ✓ Upload directory exists" -ForegroundColor Green
} else {
    Write-Host "  ✗ Upload directory not found" -ForegroundColor Yellow
    Write-Host "  Creating upload directory..." -ForegroundColor Yellow
    New-Item -ItemType Directory -Path $uploadPath -Force | Out-Null
    Write-Host "  ✓ Upload directory created" -ForegroundColor Green
}

# Step 5: Check Configuration File
Write-Host "`n[5/7] Checking configuration file..." -ForegroundColor Yellow
$configPath = Join-Path $projectPath "config.php"
if (Test-Path $configPath) {
    Write-Host "  ✓ config.php exists" -ForegroundColor Green
    
    # Read and display current config (mask password)
    $configContent = Get-Content $configPath -Raw
    if ($configContent -match '\$sql_db_host\s*=\s*"([^"]+)"') {
        Write-Host "  Database Host: $($matches[1])" -ForegroundColor Gray
    }
    if ($configContent -match '\$sql_db_name\s*=\s*"([^"]+)"') {
        Write-Host "  Database Name: $($matches[1])" -ForegroundColor Gray
    }
    if ($configContent -match '\$sql_db_user\s*=\s*"([^"]+)"') {
        Write-Host "  Database User: $($matches[1])" -ForegroundColor Gray
    }
    if ($configContent -match '\$site_url\s*=\s*"([^"]+)"') {
        $siteUrl = $matches[1]
        Write-Host "  Site URL: $siteUrl" -ForegroundColor Gray
        
        # Check if it's still production URL
        if ($siteUrl -eq "https://facesofnaija.com") {
            Write-Host "`n  WARNING: Site URL is still set to production!" -ForegroundColor Red
            Write-Host "  Please update config.php with your local URL" -ForegroundColor Yellow
        }
    }
} else {
    Write-Host "  ✗ config.php not found" -ForegroundColor Red
    Write-Host "  Please copy config.example.php to config.php and update settings" -ForegroundColor Yellow
}

# Step 6: Check MySQL Connection
Write-Host "`n[6/7] Testing MySQL connection..." -ForegroundColor Yellow
if (Test-Path $configPath) {
    # Extract database credentials
    $configContent = Get-Content $configPath -Raw
    if ($configContent -match '\$sql_db_host\s*=\s*"([^"]+)"') { $dbHost = $matches[1] }
    if ($configContent -match '\$sql_db_user\s*=\s*"([^"]+)"') { $dbUser = $matches[1] }
    if ($configContent -match '\$sql_db_pass\s*=\s*"([^"]+)"') { $dbPass = $matches[1] }
    if ($configContent -match '\$sql_db_name\s*=\s*"([^"]+)"') { $dbName = $matches[1] }
    
    # Test connection using PHP
    $testScript = @"
<?php
\$conn = @mysqli_connect('$dbHost', '$dbUser', '$dbPass', '$dbName');
if (\$conn) {
    echo 'SUCCESS';
    mysqli_close(\$conn);
} else {
    echo 'ERROR: ' . mysqli_connect_error();
}
?>
"@
    
    $testResult = $testScript | php 2>$null
    if ($testResult -eq 'SUCCESS') {
        Write-Host "  ✓ MySQL connection successful" -ForegroundColor Green
    } else {
        Write-Host "  ✗ MySQL connection failed" -ForegroundColor Red
        Write-Host "  $testResult" -ForegroundColor Yellow
    }
} else {
    Write-Host "  ⊘ Skipped (config.php not found)" -ForegroundColor Gray
}

# Step 7: Check Database Schema
Write-Host "`n[7/7] Checking database schema..." -ForegroundColor Yellow
$schemaPath = Join-Path $projectPath "assets\includes\schema.sql"
if (Test-Path $schemaPath) {
    Write-Host "  ✓ schema.sql found" -ForegroundColor Green
    Write-Host "  Location: $schemaPath" -ForegroundColor Gray
    
    Write-Host "`n  To import the schema, run:" -ForegroundColor Yellow
    Write-Host "  mysql -u $dbUser -p $dbName < `"$schemaPath`"" -ForegroundColor Cyan
} else {
    Write-Host "  ✗ schema.sql not found" -ForegroundColor Red
}

# Summary
Write-Host "`n=====================================" -ForegroundColor Cyan
Write-Host "Setup Complete!" -ForegroundColor Cyan
Write-Host "=====================================" -ForegroundColor Cyan

Write-Host "`nNext Steps:" -ForegroundColor Yellow
Write-Host "1. Update config.php with your local database credentials"
Write-Host "2. Create the database: CREATE DATABASE facesofnaija;"
Write-Host "3. Import the database schema and data"
Write-Host "4. Access the application at: $siteUrl"
Write-Host "5. Read SETUP_GUIDE.md for detailed instructions"

Write-Host "`nFor full setup guide, see: SETUP_GUIDE.md" -ForegroundColor Green
Write-Host ""
