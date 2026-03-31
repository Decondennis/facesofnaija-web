# Fix MySQL Collation Errors - FacesOfNaija
# This script fixes utf8mb4_0900_ai_ci collation errors for MariaDB compatibility
# Run this in PowerShell

Write-Host "═══════════════════════════════════════════════════════════" -ForegroundColor Cyan
Write-Host " MySQL Collation Fix for FacesOfNaija" -ForegroundColor Cyan
Write-Host "═══════════════════════════════════════════════════════════" -ForegroundColor Cyan
Write-Host ""

# Prompt for SQL file
Write-Host "Enter the path to your SQL file:" -ForegroundColor Yellow
Write-Host "Example: C:\Users\Dell\Downloads\database.sql" -ForegroundColor Gray
$sqlFile = Read-Host "File path"

# Check if file exists
if (-not (Test-Path $sqlFile)) {
    Write-Host ""
    Write-Host "ERROR: File not found!" -ForegroundColor Red
    Write-Host "Please check the path and try again." -ForegroundColor Yellow
    pause
    exit 1
}

# Get file info
$fileInfo = Get-Item $sqlFile
$fileSize = [math]::Round($fileInfo.Length / 1MB, 2)

Write-Host ""
Write-Host "File found: $($fileInfo.Name)" -ForegroundColor Green
Write-Host "Size: $fileSize MB" -ForegroundColor Gray
Write-Host ""

# Create output filename
$directory = Split-Path $sqlFile -Parent
$filename = [System.IO.Path]::GetFileNameWithoutExtension($sqlFile)
$extension = [System.IO.Path]::GetExtension($sqlFile)
$outputFile = Join-Path $directory "$($filename)_fixed$extension"

Write-Host "Output file will be: $outputFile" -ForegroundColor Gray
Write-Host ""

$continue = Read-Host "Continue with fix? (y/n)"
if ($continue -ne 'y') {
    Write-Host "Operation cancelled." -ForegroundColor Yellow
    exit 0
}

Write-Host ""
Write-Host "Processing file... Please wait..." -ForegroundColor Yellow

try {
    # Read the file
    Write-Host "  [1/4] Reading SQL file..." -ForegroundColor Gray
    $content = Get-Content $sqlFile -Raw
    
    # Count replacements
    $count1 = ([regex]::Matches($content, 'utf8mb4_0900_ai_ci')).Count
    $count2 = ([regex]::Matches($content, 'utf8mb3')).Count
    
    Write-Host "  [2/4] Found $count1 instances of 'utf8mb4_0900_ai_ci'" -ForegroundColor Gray
    Write-Host "  [2/4] Found $count2 instances of 'utf8mb3'" -ForegroundColor Gray
    
    # Fix collations
    Write-Host "  [3/4] Replacing incompatible collations..." -ForegroundColor Gray
    $content = $content -replace 'utf8mb4_0900_ai_ci', 'utf8mb4_unicode_ci'
    $content = $content -replace 'utf8mb3', 'utf8'
    
    # Save fixed file
    Write-Host "  [4/4] Saving fixed file..." -ForegroundColor Gray
    Set-Content $outputFile $content -Encoding UTF8
    
    Write-Host ""
    Write-Host "✓ Success!" -ForegroundColor Green
    Write-Host ""
    Write-Host "Fixed file saved to:" -ForegroundColor Green
    Write-Host "$outputFile" -ForegroundColor White
    Write-Host ""
    Write-Host "Changes made:" -ForegroundColor Yellow
    Write-Host "  • Replaced $count1 instances of 'utf8mb4_0900_ai_ci' with 'utf8mb4_unicode_ci'" -ForegroundColor White
    Write-Host "  • Replaced $count2 instances of 'utf8mb3' with 'utf8'" -ForegroundColor White
    Write-Host ""
    Write-Host "═══════════════════════════════════════════════════════════" -ForegroundColor Cyan
    Write-Host " Next Steps:" -ForegroundColor Yellow
    Write-Host "═══════════════════════════════════════════════════════════" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "1. Open phpMyAdmin: http://localhost/phpmyadmin" -ForegroundColor White
    Write-Host "2. Select your database: facesofnaija" -ForegroundColor White
    Write-Host "3. Click 'Import' tab" -ForegroundColor White
    Write-Host "4. Choose the fixed file:" -ForegroundColor White
    Write-Host "   $outputFile" -ForegroundColor Gray
    Write-Host "5. Click 'Go' to import" -ForegroundColor White
    Write-Host ""
    
    # Ask if user wants to open the file
    $openFile = Read-Host "Open the fixed file now? (y/n)"
    if ($openFile -eq 'y') {
        Start-Process "notepad.exe" -ArgumentList $outputFile
    }
    
} catch {
    Write-Host ""
    Write-Host "ERROR: Failed to process file" -ForegroundColor Red
    Write-Host $_.Exception.Message -ForegroundColor Red
    Write-Host ""
    pause
    exit 1
}

Write-Host ""
Write-Host "Done! Press any key to exit..." -ForegroundColor Green
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
