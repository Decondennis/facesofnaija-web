<?php
// Activate all pending users
require_once('config.php');

$db = new mysqli($sql_db_host, $sql_db_user, $sql_db_pass, $sql_db_name);

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

echo "<h2>User Activation Status:</h2>";

// Show pending users
$pending = $db->query("SELECT user_id, username, email, active, email_code FROM Wo_Users WHERE active = '0' OR active = '2'");

if ($pending && $pending->num_rows > 0) {
    echo "<h3>Pending Users:</h3>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Active Status</th></tr>";
    
    while ($row = $pending->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['user_id'] . "</td>";
        echo "<td>" . $row['username'] . "</td>";
        echo "<td>" . $row['email'] . "</td>";
        echo "<td>" . ($row['active'] == 0 ? 'Pending' : 'Inactive') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No pending users found.</p>";
}

echo "<hr><h2>Activating All Users...</h2>";

// Activate all users
$activate = $db->query("UPDATE Wo_Users SET active = '1', email_code = '0' WHERE active != '1'");

if ($activate) {
    echo "<h3 style='color:green;'>✓ All users activated successfully!</h3>";
    echo "<p>Affected rows: " . $db->affected_rows . "</p>";
} else {
    echo "<h3 style='color:red;'>✗ Error: " . $db->error . "</h3>";
}

echo "<hr><h2>All Users Status:</h2>";

// Show all users
$all = $db->query("SELECT user_id, username, email, active FROM Wo_Users ORDER BY user_id");

if ($all && $all->num_rows > 0) {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Status</th></tr>";
    
    while ($row = $all->fetch_assoc()) {
        $status = ($row['active'] == 1) ? '<span style="color:green;">✓ Active</span>' : '<span style="color:red;">✗ Inactive</span>';
        echo "<tr>";
        echo "<td>" . $row['user_id'] . "</td>";
        echo "<td>" . $row['username'] . "</td>";
        echo "<td>" . $row['email'] . "</td>";
        echo "<td>" . $status . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

echo "<hr>";
echo "<h3 style='color:green;'>✓ Done!</h3>";
echo "<p><strong>Now you can:</strong></p>";
echo "<ol>";
echo "<li>Delete this file (activate_all_users.php) for security</li>";
echo "<li>Clear browser cache (Ctrl+Shift+Delete)</li>";
echo "<li>Go to: <a href='http://localhost/facesofnaija-web'>Login Page</a></li>";
echo "</ol>";

$db->close();
?>
