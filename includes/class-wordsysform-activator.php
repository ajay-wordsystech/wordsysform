<?php
class Wordsysform_Activator {

	public function __construct(){
		//===
	}	
	public static function activate(){

		global $wpdb,$table_prefix;
		$table  = $table_prefix.'wordsys_form';
		$table2 = $table_prefix.'wordsys_form_contact';

		$q = "CREATE TABLE IF NOT EXISTS $table (	
			`form_id` int(11) NOT NULL AUTO_INCREMENT,		
			`title` varchar(255) NOT NULL,
			`shortcode` varchar(255) NOT NULL,
			`user_id` int(11) NOT NULL,
			`form` text NOT NULL,
			`validation` text NOT NULL,
			`mail` text NOT NULL,
			`messages` text NOT NULL,
			`create_date` datetime DEFAULT NULL,
			`modified_date` datetime DEFAULT NULL,
			PRIMARY KEY (`form_id`)
		) ENGINE=InnoDB;";
		$wpdb->query($q);       

		// $table_data = array(
		// 	'title'=>'form 1',
		// 	'shortcode'=>'',
		// 	'user_id'=>'1',
		// 	'form'=>'',
		// 	'validation'=>'',
		// 	'mail'=>'',
		// 	'messages'=>'',
		// 	'create_date'=>date('Y-m-d H:i:s'),
		// 	'modified_date'=>date('Y-m-d H:i:s')
		// );
		// $wpdb->insert($table,$table_data);  
		
		$q = "CREATE TABLE IF NOT EXISTS $table2 (
			`contact_id` int(11) NOT NULL AUTO_INCREMENT,
			`form_id` int(11) NOT NULL,
			`form_data` text NOT NULL,
			`ip_address` varchar(255) NOT NULL,
			`browser` text NOT NULL,
			`create_date` datetime NOT NULL,
			PRIMARY KEY (`contact_id`)
		  ) ENGINE=InnoDB;";
		$wpdb->query($q);      
	}

}
