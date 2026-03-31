<?php
require_once('assets/init.php');

// Simulate what template does
echo "Testing template helper functions for calabar community...\n";

$community_id = 3; // calabar
$wo['community_profile'] = Wo_CommunityData($community_id);

// Test each helper function that the template calls
echo "\n1. Testing Wo_GetCommunityMembers...\n";
$start = microtime(true);
$community_members = Wo_GetCommunityMembers($community_id);
$elapsed = microtime(true) - $start;
echo "Result: " . (is_array($community_members) ? "array with " . count($community_members) . " items" : gettype($community_members)) . " (took ${elapsed}s)\n";

echo "\n2. Testing Wo_GetPinnedPost...\n";
$start = microtime(true);
$pinedstory = Wo_GetPinnedPost($community_id, 'community');
$elapsed = microtime(true) - $start;
echo "Result: " . (is_array($pinedstory) ? "array with " . count($pinedstory) . " items" : gettype($pinedstory)) . " (took ${elapsed}s)\n";

echo "\n3. Testing Wo_GetPosts...\n";
$start = microtime(true);
$stories = Wo_GetPosts(array('filter_by' => 'all', 'community_id' => $community_id,'placement' => 'multi_image_post'));
$elapsed = microtime(true) - $start;
echo "Result: " . (is_array($stories) ? "array with " . count($stories) . " items" : gettype($stories)) . " (took ${elapsed}s)\n";

echo "\n4. Testing Wo_CommunitySug...\n";
$start = microtime(true);
$communities = Wo_CommunitySug(5);
$elapsed = microtime(true) - $start;
echo "Result: " . (is_array($communities) ? "array with " . count($communities) . " items" : gettype($communities)) . " (took ${elapsed}s)\n";

echo "\nAll helper functions completed successfully!\n";
?>
