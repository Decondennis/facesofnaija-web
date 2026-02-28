<!DOCTYPE html>
<html>
<head>
    <title>Setup Community Requests Table</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        .info { color: blue; }
        pre { background: #f5f5f5; padding: 10px; border-left: 4px solid #007bff; }
    </style>
</head>
<body>
    <h1>Community Request System Setup</h1>
    
    <?php
    require_once('assets/init.php');
    
    echo "<h2>Step 1: Creating Database Table</h2>";
    
    $sql = "CREATE TABLE IF NOT EXISTS `Wo_Community_Requests` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `user_id` int(11) NOT NULL,
      `community_name` varchar(100) NOT NULL,
      `community_title` varchar(100) NOT NULL,
      `about` text,
      `category` int(11) DEFAULT '1',
      `sub_category` varchar(250) DEFAULT '',
      `privacy` int(11) DEFAULT '1',
      `reason` text,
      `status` enum('pending','approved','rejected') DEFAULT 'pending',
      `time` int(11) NOT NULL,
      `reviewed_by` int(11) DEFAULT NULL,
      `reviewed_at` int(11) DEFAULT NULL,
      `admin_notes` text,
      PRIMARY KEY (`id`),
      KEY `user_id` (`user_id`),
      KEY `status` (`status`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    
    if (mysqli_query($sqlConnect, $sql)) {
        echo "<p class='success'>✓ Table created successfully!</p>";
    } else {
        echo "<p class='error'>✗ Error creating table: " . mysqli_error($sqlConnect) . "</p>";
    }
    
    echo "<h2>Step 2: Checking Table Structure</h2>";
    $check = mysqli_query($sqlConnect, "DESCRIBE Wo_Community_Requests");
    if ($check) {
        echo "<p class='success'>✓ Table exists with the following structure:</p>";
        echo "<pre>";
        while ($row = mysqli_fetch_assoc($check)) {
            echo "{$row['Field']} - {$row['Type']}\n";
        }
        echo "</pre>";
    }
    
    echo "<h2>Step 3: Test Request Submission</h2>";
    echo "<p class='info'>Now you can test the Request Community feature:</p>";
    echo "<ol>";
    echo "<li>Log in as a regular user (not admin)</li>";
    echo "<li>Click the 'Request Community' button</li>";
    echo "<li>Fill out the form and submit</li>";
    echo "<li>Your request will be saved with status 'pending'</li>";
    echo "</ol>";
    
    echo "<h2>Step 4: View Pending Requests (Admin)</h2>";
    echo "<p class='info'>To view and approve requests, run this query:</p>";
    echo "<pre>SELECT * FROM Wo_Community_Requests WHERE status = 'pending';</pre>";
    
    $pending = mysqli_query($sqlConnect, "SELECT COUNT(*) as count FROM Wo_Community_Requests WHERE status = 'pending'");
    if ($pending) {
        $count = mysqli_fetch_assoc($pending)['count'];
        echo "<p>Current pending requests: <strong>{$count}</strong></p>";
    }
    
    echo "<h2>Summary</h2>";
    echo "<p class='success'>✓ Request Community feature is now ready to use!</p>";
    echo "<p>Files modified:</p>";
    echo "<ul>";
    echo "<li>themes/facesofnaija/layout/community/request-community.phtml (form template)</li>";
    echo "<li>xhr/communities.php (added request_community handler)</li>";
    echo "<li>Wo_Community_Requests table (created)</li>";
    echo "</ul>";
    ?>
</body>
</html>
