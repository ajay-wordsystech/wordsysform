<?php
class Wordsysform_Admin {
	
	private $plugin_name;
	private $version;
	
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;		
		add_action('admin_menu', array($this, 'add_menu'));
		add_action('init', array($this, 'check_session_start') );

		add_action( 'wp_ajax_contact_data', array('Wordsysform_Admin', 'getContctFormData') );
        add_action( 'wp_ajax_nopriv_contact_data', array('Wordsysform_Admin', 'getContctFormData') );
			
	}
	
	public function enqueue_styles() {
		//wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wordsysform-admin.css', array(), $this->version, 'all' );
	}	
	public function enqueue_scripts() {
		//wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/bootstrap.min.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wordsysform-admin.js', array( 'jquery' ), $this->version, true );
	}
	public function add_menu() {
		//$admin_page = new Wordsysform_Admin('','');
		//add_menu_page( 'page_title', 'menu_title', 'capability', 'menu_slug', 'callback function', 'icon_url', 'int|float position');
		add_menu_page( 'Wordsys Forms', 'Wordsys Forms', 'manage_options', 'wordsysform', array($this, 'list_form'), '', 50);

		//add_submenu_page( 'parent_slug', 'page_title', 'menu_title', 'capability', 'menu_slug', 'callback function', 'int|float position');	
		add_submenu_page( 'wordsysform', 'Add New', 'Add New', 'manage_options', 'wordsysform-create', array($this, 'add_form'), 1);	
		add_submenu_page( 'wordsysform', 'Contact Data', 'Contact Data', 'manage_options', 'wordsysform-contact-data', array($this, 'contact_data'), 2);		
	}
	function check_session_start(){
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}		
	}
	public function list_form(){		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/list_form.php';
	}
	public function add_form(){
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/add_form.php';
	}
	public function contact_data(){
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/contact_data.php';
	}
	public function getArrayData($data){
		$data = (array)json_decode($data);
		$return_array = [];
		if($data){
			foreach($data as $key=>$val){
				$name = ucwords(str_replace('_',' ', $key));
				$return_array[] = array(
					'key'	=>$key,
					'name'	=>$name,
					'value'	=>$val,
				);
			}
		}
		return $return_array;		
	}
	public function getContctFormData(){
		global $wpdb, $table_prefix;
		$contact_id  = isset( $_POST['contact_id'] ) ? $_POST['contact_id'] : '';		
		$table       = $table_prefix.'wordsys_form';	
		$table2      = $table_prefix.'wordsys_form_contact';	
		
		$q    = "SELECT * FROM $table2 WHERE contact_id=$contact_id";
        $row  = $wpdb->get_row($q); 
		$data = Wordsysform_Admin::getArrayData($row->form_data);  

		$html  = '';
		$html .= '<h2>Contact data</h2>';
		$html .= '<table class="wordsysform-view-table">';
		$html .= '<tbody>';

		$html .= '<tr>';
		$html .= '<td><b>Date</b></td>';
		$html .= '<td>'.$row->create_date.'</td>';
		$html .= '</tr>';  

		if($data){			
			foreach($data as $val){
				$html .= '<tr>';
				$html .= '<td><b>'.$val['name'].'</b></td>';
				$html .= '<td>'.$val['value'].'</td>';
				$html .= '</tr>';          
			}
		}

		$html .= '</tbody>';
		$html .= '</table>';

		$html .= '<p><small>';
		$html .= '<b>IP Address : </b>';
		$html .= $row->ip_address;
		$html .= '</small></p>'; 

		$html .= '<p><small>';
		$html .= '<b>Browser : </b>';
		$html .= $row->browser;
		$html .= '</small></p>'; 

		echo json_encode(
		array(
		'status'  			=> 'success', 		
		'success_message' 	=> $html
		));		
		exit;	
	}	
	
}


