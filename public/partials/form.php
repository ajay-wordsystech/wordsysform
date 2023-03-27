<?php
$form_id  = isset( $attributes['id'] ) ? $attributes['id'] : '';
global $wpdb, $table_prefix;
$table = $table_prefix.'wordsys_form';	

$q = "SELECT * FROM $table WHERE form_id=$form_id";
$row = $wpdb->get_row($q); 
$validation = $row->validation;


$action     = isset( $_POST['action'] ) ? $_POST['action'] : '';

$form_html  = '';
$form_html .= '<div class="wordsysform">';
$form_html .= '<div class="notice notice-success m-0" id="notice-success-'.$form_id.'"></div>';
$form_html .= '<form method="post" id="wordsysform-'.$form_id.'" action="'.admin_url('admin-ajax.php').'">';
$form_html .= '<input type="hidden" name="action" value="custom_action">';
$form_html .= '<input type="hidden" name="form_id" value="'.$form_id.'">';
$form_html .= $row->form;
$form_html .= '</form>';
$form_html .= '</div>';
echo $form_html;

// echo '<pre>';
// print_r($validation);
// echo '</pre>';
?>


<script>
document.getElementById('wordsysform-<?php echo $form_id ?>').addEventListener('submit', (event) => {    
    event.preventDefault();

    const form = document.getElementById('wordsysform-<?php echo $form_id ?>');
    const validation  = JSON.parse('<?php echo $validation; ?>');

    if( validate_wordsysform(validation,form) ){  
      var $wpq     = jQuery.noConflict();
      let formData = new FormData(form);       
      $wpq.ajax({
        type: "post",
        dataType: "json",
        url: form.attributes["action"].value,   
        data: formData,  
        processData: false,
        contentType: false,
        success: function(response){
          console.log(response.success_message)
          $wpq('#notice-success-<?php echo $form_id ?>').html(response.success_message)
          $wpq('#notice-success-<?php echo $form_id ?>').show() 
          form.reset();
        }
      });   
    }    
    
});
</script>