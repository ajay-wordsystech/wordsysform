<?php
class Wordsysform_Public {
	
	private $plugin_name;	
	private $version;

	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	public function init_form() {	
		if ( !is_admin() ) {
			add_shortcode( 'wordsysform', array('Wordsysform_Public', 'call_form') );
		}	
		add_action( 'wp_ajax_custom_action', array('Wordsysform_Public', 'custom_action') );
        add_action( 'wp_ajax_nopriv_custom_action', array('Wordsysform_Public', 'custom_action') );	
	}

	public function call_form($attributes) {	
		// echo'<pre>';
		// print_r($attributes);
		// echo'</pre>';
		//return'<div>dzffdfd'.$attributes.'</div>';
		include plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/form.php';
		//echo'<div>dzffdfd</div>';
	}

	public function enqueue_styles() {	
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wordsysform-public.css', array(), $this->version, 'all' );
	}

	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wordsysform-public.js', array( 'jquery' ), $this->version, true );
	}
	public function custom_action() {	

        global $wpdb, $table_prefix;
		$form_id  = isset( $_POST['form_id'] ) ? $_POST['form_id'] : '';		
		$table    = $table_prefix.'wordsys_form';	
		$table2   = $table_prefix.'wordsys_form_contact';	

		$q = "SELECT * FROM $table WHERE form_id=$form_id";
		$row = $wpdb->get_row($q); 

		$mail_data     = (array)json_decode($row->mail);
        $messages_data = (array)json_decode($row->messages);

		$post_data  = $_POST;
		if(isset($post_data['action'])){
			unset($post_data['action']);
		}
		if(isset($post_data['form_id'])){
			unset($post_data['form_id']);
		}

		//=== Insert Data
		$table_insert_data = array(
		'form_id'=>$form_id,
		'form_data'=>json_encode($post_data),
		'ip_address'=>Wordsysform_Public::getIPAddress(),
		'browser'=>$_SERVER['HTTP_USER_AGENT'],		
		'create_date'=>date('Y-m-d H:i:s')		
		);
		$wpdb->insert($table2, $table_insert_data);		
		
		// echo '<pre>';
		// print_r($table_insert_data);
		// echo '</pre>';

		//=== Email
		//https://developer.wordpress.org/reference/functions/wp_mail/
		$headers 	= array('Content-Type: text/html; charset=UTF-8');
		$to 		= isset( $mail_data['to'] ) ? $mail_data['to'] : '';
		$from 		= isset( $mail_data['from'] ) ? $mail_data['from'] : '';
		$subject 	= isset( $mail_data['subject'] ) ? $mail_data['subject'] : '';
		$body 		= isset( $mail_data['body'] ) ? $mail_data['body'] : '';
		
		$body = str_replace('{site_name}', get_bloginfo('name'), $body);		
		$body = str_replace('{site_logo}', esc_url( wp_get_attachment_url( get_theme_mod( 'custom_logo' ) ) ), $body);		
		foreach($post_data as $key=>$val){
			$body = str_replace('{'.$key.'}', $val, $body);			
		}
		wp_mail( $to, $subject, $body, $headers );
		
		echo json_encode(
		array(
		'status'  			=> 'success', 		
		'success_message' 	=> $messages_data['mail_success']
		));		
		exit;			
		
	}
	public function getIPAddress() {	
		//whether ip is from the share internet  
		if(!empty($_SERVER['HTTP_CLIENT_IP'])) {  
			$ip = $_SERVER['HTTP_CLIENT_IP'];  
		}  
		//whether ip is from the proxy  
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {  
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];  
		}  
		//whether ip is from the remote address  
		else{  
			$ip = $_SERVER['REMOTE_ADDR'];  
		}  
		return $ip;  
	}

}
Wordsysform_Public::init_form();
