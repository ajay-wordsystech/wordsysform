<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb, $table_prefix;
$table  = $table_prefix.'wordsys_form';	
$table2 = $table_prefix.'wordsys_form_contact';	

$q = "DROP TABLE `$table`";
$wpdb->query($q);  

$q = "DROP TABLE `$table2`";
$wpdb->query($q);  
