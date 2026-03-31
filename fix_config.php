<?php
require_once('assets/init.php');
global $db, $wo;

// Insert missing config keys
$keys = array(
    'can_use_story' => '1',
);

foreach ($keys as $name => $value) {
    $existing = $db->where('name', $name)->getOne(T_CONFIG);
    if (!$existing) {
        $db->insert(T_CONFIG, array('name' => $name, 'value' => $value));
        echo "INSERTED: $name = $value\n";
    } else {
        echo "EXISTS: $name = " . $existing['value'] . "\n";
    }
}

// Show result
$row = $db->where('name', 'can_use_story')->getOne(T_CONFIG, array('name', 'value'));
echo "FINAL: can_use_story = " . ($row ? $row['value'] : 'NOT FOUND') . "\n";
echo "DONE\n";
