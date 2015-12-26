<?php 

$uri = "http://themeforest.net/item/traveler-traveltourbooking-wordpress-theme/10822683";
$contents = wp_remote_fopen($uri);
echo balanceTags($contents);
?>