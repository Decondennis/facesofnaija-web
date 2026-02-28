<!DOCTYPE html>
<html>
<head>
    <title>Test Community Request Form</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .success { color: green; }
        .error { color: red; }
        pre { background: #f5f5f5; padding: 10px; }
    </style>
</head>
<body>
    <h1>Community Request Form - Debugging</h1>
    
    <?php
    require_once('assets/init.php');
    
    echo "<h2>Step 1: Check if Table Exists</h2>";
    $check = mysqli_query($sqlConnect, "SHOW TABLES LIKE 'Wo_Community_Requests'");
    if (mysqli_num_rows($check) > 0) {
        echo "<p class='success'>✓ Table exists</p>";
    } else {
        echo "<p class='error'>✗ Table does NOT exist - Run setup_community_requests.php first!</p>";
    }
    
    echo "<h2>Step 2: Check User Login Status</h2>";
    if ($wo['loggedin']) {
        echo "<p class='success'>✓ User logged in: " . $wo['user']['username'] . " (ID: " . $wo['user']['user_id'] . ")</p>";
        if (Wo_IsAdmin()) {
            echo "<p class='error'>⚠️ You are logged in as ADMIN - The form will reject admin submissions. Log in as regular user to test.</p>";
        } else {
            echo "<p class='success'>✓ Regular user - Can submit requests</p>";
        }
    } else {
        echo "<p class='error'>✗ Not logged in</p>";
    }
    
    echo "<h2>Step 3: Check AJAX Endpoint</h2>";
    echo "<p>XHR File: <code>" . Wo_Ajax_Requests_File() . "</code></p>";
    echo "<p>Request URL: <code>" . Wo_Ajax_Requests_File() . "?f=communities&s=request_community</code></p>";
    
    echo "<h2>Step 4: Test Form Submission (Manual)</h2>";
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['test_submit'])) {
        echo "<h3>Testing...</h3>";
        
        // Simulate form data
        $_POST['community_name'] = 'testcommunity' . rand(1000, 9999);
        $_POST['community_title'] = 'Test Community';
        $_POST['about'] = 'This is a test community';
        $_POST['category'] = 1;
        $_POST['privacy'] = 1;
        $_POST['reason'] = 'Testing the community request feature';
        $_POST['hash_id'] = Wo_CreateSession();
        
        $errors = array();
        
        if (Wo_IsAdmin() || Wo_IsModerator()) {
            $errors[] = 'Admin cannot submit requests';
        }
        elseif (empty($_POST['community_name']) || empty($_POST['community_title']) || empty($_POST['reason'])) {
            $errors[] = 'Missing required fields';
        } else {
            // Insert test
            $insert_data = array(
                'user_id' => $wo['user']['user_id'],
                'community_name' => Wo_Secure($_POST['community_name']),
                'community_title' => Wo_Secure($_POST['community_title']),
                'about' => Wo_Secure($_POST['about']),
                'category' => Wo_Secure($_POST['category']),
                'sub_category' => '',
                'privacy' => Wo_Secure($_POST['privacy']),
                'reason' => Wo_Secure($_POST['reason']),
                'status' => 'pending',
                'time' => time()
            );
            
            $fields_str = '`' . implode('`, `', array_keys($insert_data)) . '`';
            $values_str = '\'' . implode('\', \'', $insert_data) . '\'';
            
            $query = mysqli_query($sqlConnect, "INSERT INTO Wo_Community_Requests ({$fields_str}) VALUES ({$values_str})");
            
            if ($query) {
                echo "<p class='success'>✓ Successfully inserted test request!</p>";
                echo "<pre>";
                print_r($insert_data);
                echo "</pre>";
            } else {
                echo "<p class='error'>✗ Database error: " . mysqli_error($sqlConnect) . "</p>";
            }
        }
        
        if (!empty($errors)) {
            echo "<p class='error'>Errors:</p><pre>";
            print_r($errors);
            echo "</pre>";
        }
    }
    ?>
    
    <form method="POST">
        <button type="submit" name="test_submit" style="padding: 10px 20px; background: #007bff; color: white; border: none; cursor: pointer;">
            Run Test Submission
        </button>
    </form>
    
    <h2>Step 5: View All Requests</h2>
    <?php
    $requests = mysqli_query($sqlConnect, "SELECT * FROM Wo_Community_Requests ORDER BY time DESC LIMIT 10");
    if ($requests && mysqli_num_rows($requests) > 0) {
        echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
        echo "<tr><th>ID</th><th>User ID</th><th>Community Name</th><th>Title</th><th>Status</th><th>Time</th></tr>";
        while ($row = mysqli_fetch_assoc($requests)) {
            $date = date('Y-m-d H:i:s', $row['time']);
            echo "<tr>";
            echo "<td>{$row['id']}</td>";
            echo "<td>{$row['user_id']}</td>";
            echo "<td>{$row['community_name']}</td>";
            echo "<td>{$row['community_title']}</td>";
            echo "<td>{$row['status']}</td>";
            echo "<td>{$date}</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No requests found yet.</p>";
    }
    ?>
    
    <h2>Troubleshooting Steps</h2>
    <ol>
        <li>Make sure table exists (run setup_community_requests.php)</li>
        <li>Log in as a REGULAR user (not admin)</li>
        <li>Clear browser cache (Ctrl+Shift+Delete)</li>
        <li>Open browser console (F12) and check for JavaScript errors</li>
        <li>Try the form at: <a href="<?php echo $site_url; ?>/?link1=request-community">Request Community</a></li>
    </ol>
    
    <h2>JavaScript Console Test</h2>
    <p>Open browser console (F12) and paste this:</p>
    <pre style="background: #2d2d2d; color: #fff; padding: 10px;">
console.log('jQuery loaded:', typeof $ !== 'undefined');
console.log('ajaxForm available:', typeof $.fn.ajaxForm !== 'undefined');
console.log('Wo_Ajax_Requests_File:', typeof Wo_Ajax_Requests_File !== 'undefined');
    </pre>
</body>
</html>
