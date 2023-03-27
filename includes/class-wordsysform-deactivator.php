<?php
class Wordsysform_Deactivator {

	public function __construct(){
		//===
	}	
	public static function deactivate() {
		global $wpdb,$table_prefix;
		$table  = $table_prefix.'wordsys_form';	
		$table2 = $table_prefix.'wordsys_form_contact';		
		
		// $q = "TRUNCATE `$table`";
		// $wpdb->query($q);   

		// $q = "TRUNCATE `$table2`";
		// $wpdb->query($q);   
	}

}
