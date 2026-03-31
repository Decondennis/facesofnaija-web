# Profile Cover Image Upload Fix

## Problem
Users experienced an HTTP connection error when attempting to upload a profile background/cover picture. The browser displayed "http://172.236.19.52/decon" with a connection failure message.

## Root Cause
Multiple image upload handlers in the `/xhr/` directory had a critical bug where the `$data` response variable was not initialized with a default value. This caused the following issues:

1. If the image file parameter was not set in the request, the `$data` variable would be undefined
2. When `json_encode($data)` was called on an undefined variable, it would generate a PHP warning/notice
3. This resulted in invalid JSON response or HTML error pages instead of proper JSON responses
4. Client-side JavaScript failed to parse the response, causing AJAX failures
5. When AJAX failed, the form would fall back to regular POST submission, which would POST to the current page URL
6. This explains why users saw "/Decon" (their profile page) in the error URL

## Solution
Fixed all image upload handlers to initialize the `$data` variable with a default error response at the start of each request. This ensures:

1. Valid JSON is always returned, even on error
2. JavaScript AJAX handlers can properly parse responses
3. Error messages are meaningful and traceable

## Files Modified

Core profile handlers:
- `/xhr/update_user_cover_picture.php` - Profile cover image upload
- `/xhr/get_user_profile_cover_image_post.php` - Retrieve profile cover for lightbox  
- `/xhr/update_user_avatar_picture.php` - Profile avatar/picture upload
- `/xhr/upload_image.php` - Generic image upload (used in posts, etc.)

Page handlers:
- `/xhr/update_page_cover_picture.php` - Page cover image
- `/xhr/update_page_avatar_picture.php` - Page avatar

Group handlers:
- `/xhr/update_group_cover_picture.php` - Group cover image
- `/xhr/update_group_avatar_picture.php` - Group avatar

Community handlers:
- `/xhr/update_community_cover_picture.php` - Community cover image
- `/xhr/update_community_avatar_picture.php` - Community avatar

Event handlers:
- `/xhr/update_event_cover_picture.php` - Event cover image

## Testing

### To test locally (XAMPP):
```bash
cd C:\xampp\htdocs\facesofnaija-web
# Navigate to your profile page at http://localhost/facesofnaija-web/profile-or-username
# Verify the "Upload Cover" button works correctly
# Select an image and submit the form
# Verify successful response appears with the new cover image
```

### To test on remote server:
```bash
ssh root@172.236.19.52
cd /var/www/html  # or your web root
# Test by navigating to your profile and uploading a cover image
# Check server logs: tail -f /var/log/apache2/error.log
```

### Expected behavior after fix:
1. User selects a cover image file
2. Form POSTs via AJAX to `/requests.php?f=update_user_cover_picture`
3. Server responds with JSON: `{"status": 200, "img": "...", "cover_or": "...", ...}`
4. JavaScript displays the new cover image on the profile
5. No connection errors or redirects to inappropriate pages

## Code Changes Summary

### Before (Buggy):
```php
<?php
if ($f == 'update_user_cover_picture') {
    if (isset($_FILES['cover']['name'])) {
        // ... upload logic ...
        $data = array('status' => 200, ...);
    }
    // BUG: $data undefined if condition fails!
    echo json_encode($data);
}
```

### After (Fixed):
```php
<?php
if ($f == 'update_user_cover_picture') {
    $data = array('status' => 400, 'message' => 'Invalid request'); // Default error response
    if (isset($_FILES['cover']['name'])) {
        // ... upload logic ...
        $data = array('status' => 200, ...);
    }
    // Always returns valid JSON
    echo json_encode($data);
}
```

## Related Issues
- The "/decon" URL in error messages was a side effect of AJAX failure causing form fallback to regular POST
- "/Decon" is the developer's username, which appears in the site's user profiles
- The fix ensures AJAX responses are always valid JSON, preventing form fallback

## Deployment Notes
- No database migrations needed
- No configuration changes needed
- Changes are fully backward compatible
- Deployment: Push to GitHub and pull on production server

```bash
git add xhr/*.php
git commit -m "Fix: Initialize response data in image upload handlers to prevent undefined variable errors"
git push github restore-2026-02-20
```
