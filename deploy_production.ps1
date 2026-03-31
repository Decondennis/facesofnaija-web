# Production Deployment Script for Image Upload Handlers

$server = "root@172.236.19.52"
$remoteDir = "/var/www/html/xhr/"
$localDir = "c:\Users\Dell\source\repos\workspace\facesofnaija-web\xhr\"

$files = @(
    'get_user_profile_cover_image_post.php',
    'update_user_cover_picture.php',
    'update_user_avatar_picture.php',
    'upload_image.php',
    'update_page_cover_picture.php',
    'update_page_avatar_picture.php',
    'update_group_cover_picture.php',
    'update_group_avatar_picture.php',
    'update_event_cover_picture.php',
    'update_community_cover_picture.php',
    'update_community_avatar_picture.php'
)

Write-Host "Deploying to production server: 172.236.19.52" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green

$successCount = 0
$failCount = 0

foreach ($file in $files) {
    $localFile = "$localDir$file"
    $remoteFile = "$remoteDir$file"
    
    Write-Host "Deploying $file..." -ForegroundColor Cyan
    
    if (Test-Path $localFile) {
        scp -o StrictHostKeyChecking=no -q "$localFile" "$server`:$remoteFile" 2>$null
        if ($LASTEXITCODE -eq 0) {
            Write-Host "  ✓ Success" -ForegroundColor Green
            $successCount++
        } else {
            Write-Host "  ✗ Failed" -ForegroundColor Red
            $failCount++
        }
    } else {
        Write-Host "  ✗ File not found: $localFile" -ForegroundColor Red
        $failCount++
    }
}

Write-Host "========================================" -ForegroundColor Green
Write-Host "Deployment Summary:" -ForegroundColor Yellow
Write-Host "  Successful: $successCount / $($files.Count)" -ForegroundColor Green
Write-Host "  Failed: $failCount / $($files.Count)" -ForegroundColor $(if($failCount -gt 0) {"Red"} else {"Green"})

if ($failCount -eq 0) {
    Write-Host "`n✓ All files deployed successfully to production!" -ForegroundColor Green
    
    # Verify deployment
    Write-Host "`nVerifying deployment..." -ForegroundColor Cyan
    ssh -o StrictHostKeyChecking=no -q root@172.236.19.52 "cd /var/www/html/xhr && ls -lh update_user_cover_picture.php get_user_profile_cover_image_post.php && echo '✓ Verification complete'"
} else {
    Write-Host "`n✗ Some files failed to deploy. Please check the errors above." -ForegroundColor Red
    Exit 1
}
