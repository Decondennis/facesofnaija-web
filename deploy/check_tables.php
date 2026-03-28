<?php
$conn = new mysqli('localhost', 'facesofnaija_user', 'FacesDB_2026!', 'facesofnaija');
if ($conn->connect_error) { die("DB error: " . $conn->connect_error); }

// Check lower_case_table_names variable
$r = $conn->query("SHOW VARIABLES LIKE 'lower_case_table_names'");
$row = $r->fetch_assoc();
echo "lower_case_table_names = " . $row['Value'] . "\n\n";

// Show all tables
$r = $conn->query("SHOW TABLES");
echo "All tables:\n";
while ($row = $r->fetch_row()) {
    echo "  " . $row[0] . "\n";
}
