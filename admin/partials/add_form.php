<?php
$form_id  = isset( $_GET['form_id'] ) ? $_GET['form_id'] : '';
if( $form_id ){
  $page_url = admin_url('admin.php?page=wordsysform-create&form_id='.$form_id, '');
}
else{
  $page_url = admin_url('admin.php?page=wordsysform-create', '');
}
$pagedata = array(
    'title'    =>'Wordsys Form',
    'page_url' =>$page_url,
    'list_url' =>admin_url('admin.php?page=wordsysform', ''),
);

global $wpdb, $table_prefix;
$table = $table_prefix.'wordsys_form';	
$user_id = get_current_user_id();

$q = "SELECT * FROM `$table` WHERE form_id=$form_id";
$row = $wpdb->get_row($q); 

$title        = isset( $_POST['title'] ) ? $_POST['title'] : $row->title;
$form         = isset( $_POST['form'] ) ? $_POST['form'] : $row->form; 
$mail         = isset( $_POST['mail'] ) ? $_POST['mail'] : (array)json_decode($row->mail);
$messages     = isset( $_POST['messages'] ) ? $_POST['messages'] : (array)json_decode($row->messages);
$action       = isset( $_POST['action'] ) ? $_POST['action'] : '';

$validation = array();
if( $action == 'save' ){   
    $validation   = isset( $_POST['validation'] ) ? $_POST['validation'] : array();
    $validation_array = array();
    $fields  = isset( $validation['field_name'] ) ? $validation['field_name'] : array();
    $type    = isset( $validation['type'] ) ? $validation['type'] : array();
    $msg     = isset( $validation['msg'] ) ? $validation['msg'] : array();
    $min     = isset( $validation['min'] ) ? $validation['min'] : array();
    $max     = isset( $validation['max'] ) ? $validation['max'] : array();
    if($fields){    
      foreach($fields as $key=>$val){
        $validation_array[] = array(
          'field_name'=>$val,
          'type'=>$type[$key],
          'msg'=>$msg[$key],
          'min'=>$min[$key],
          'max'=>$max[$key],
        );
      }
    }
    $validation = $validation_array;
}
elseif($form_id){
    $validation   = (array)json_decode($row->validation);
    $validation_array = array();
    if($validation){    
      foreach($validation as $val){
        $validation_array[] = (array) $val;
      }
  }
  $validation = $validation_array;
}


if( $action == 'save' && !$form_id ){   

    $table_insert_data = array(
      'title'=>$title,
      'user_id'=>$user_id,
      'form'=>wp_unslash($form),
      'validation'=>json_encode($validation),
      'mail'=>json_encode(wp_unslash($mail)),
      'messages'=>json_encode($messages), 
      'create_date'=>date('Y-m-d H:i:s'),
      'modified_date'=>date('Y-m-d H:i:s'),     
    );
    $wpdb->insert($table, $table_insert_data);

    $form_id = $wpdb->insert_id; 
    $shortcode = '[wordsysform id="'.$form_id.'"]';

    $table_update_data = array(
      'shortcode'=>$shortcode,      
    );
    $wpdb->update($table, $table_update_data, array('form_id' => $form_id));
    $_SESSION['success_msg'] = 'Data Inserted successfully';        
    ?>
    <script>  
    location.href = '<?php echo $pagedata['list_url'] ?>'
    </script>
    <?php  
    exit;
}
elseif( $action == 'save' && $form_id ){ 
  
  $table_update_data = array(
    'title'=>$title,
    'user_id'=>$user_id,
    'form'=>wp_unslash($form),
    'validation'=>json_encode($validation),
    'mail'=>json_encode(wp_unslash($mail)),
    'messages'=>json_encode($messages),    
    'modified_date'=>date('Y-m-d H:i:s'),     
  );
  $wpdb->update($table, $table_update_data, array('form_id' => $form_id));
  $_SESSION['success_msg'] = 'Data updated successfully';        
  ?>
  <script>  
  location.href = '<?php echo $pagedata['list_url'] ?>'
  </script>
  <?php  
  exit;
}
$form_fields = array(
  'text',
  'email',
  'url',
  'tel',
  'number',
  'date',
  'textarea',
  'dropdownmenu',
  'checkboxes',
  'radiobuttons',
  'file',  
);

// echo '<pre>';
// print_r($validation);
// echo '</pre>';
?>

<div class="wordsysform">
  <div class="container">
    
    <?php
    if($form_id){
      echo '<h3>Edit Wordsys Form</h3>';
    }
    else{
      echo '<h3>Add Wordsys Form</h3>';
    }
    ?>    

    <form id="wordsysform-form" method="post" action="<?php echo $pagedata['page_url']; ?>">
    <input type="hidden" name="action" value="save">
    <div class="mb-3">
    <input type="text" class="form-control" id="title" name="title" value="<?php echo $title ?>" placeholder="Title">
    </div>

    <div class="tab">
      <button type="button" class="tablinks active" onclick="openTab(event, 'Form')">Form</button>
      <button type="button" class="tablinks" onclick="openTab(event, 'Mail')">Mail</button>
      <button type="button" class="tablinks" onclick="openTab(event, 'Messages')">Messages</button>
    </div>

    <!-- Tab content -->
    <div id="Form" class="tabcontent" style="display: block;">
    <div style="display: flex;">
    
      <div class="mb-3 pt-2" style="width: 48%;">
      <p>
      You can edit the form template here<br />
      <b>Rules : </b>
      <ol class="rules">
      <li>Do not use open and close tags of form element <span style="text-decoration: line-through">&#x3C;form&#x3E;&#x3C;/form&#x3E;</span></li>
      <li>Please use meaningful field name like first_name, last_name instead of <span style="text-decoration: line-through">fname</span>, <span style="text-decoration: line-through">lname</span></li>
      <li>insert &lt;small&gt;&lt;/small&gt; tag below the required input field</li>
      </ol>
      </p>

      <?php
      /*
      <div id="tag-lists" class="mb-1">
      <button type="button" class="btn btn-secondary btn-sm" id="myBtn">text</button>
      <button type="button" class="btn btn-secondary btn-sm">email</button>
      <button type="button" class="btn btn-secondary btn-sm">URL</button>
      <button type="button" class="btn btn-secondary btn-sm">tel</button>
      <button type="button" class="btn btn-secondary btn-sm">number</button>
      <button type="button" class="btn btn-secondary btn-sm">date</button>
      <button type="button" class="btn btn-secondary btn-sm">textarea</button>
      <button type="button" class="btn btn-secondary btn-sm">dropdown menu</button>
      <button type="button" class="btn btn-secondary btn-sm">checkboxes</button>
      <button type="button" class="btn btn-secondary btn-sm">radiobuttons</button>
      <button type="button" class="btn btn-secondary btn-sm">file</button>
      </div>
      */
      ?>

      <b>Form Html</b>
      <textarea class="form-control" id="form" name="form" rows="25"><?php  echo htmlentities($form, ENT_QUOTES, 'UTF-8') ?></textarea>
      </div> 
    
      <div class="ml-5 pt-5" id="wordsysform-validation">
      <b>Form validation</b>
      <table class="mt-2">
        <tbody>
          <tr>  
            <th>Field Name</th>
            <th>Type</th>     
            <th>Validation</th> 
            <th>Option</th>                
          </tr>

          <?php
          if($validation){
            $count = 0;
            foreach($validation as $val){
              $count++;
              ?>
              <tr class="control-group">

              <td>
              <input type="text" name="validation[field_name][]" value="<?php echo $val['field_name'] ?>" placeholder="Name">
              </td>

              <td>
              <select class="form-select" name="validation[type][]" onchange=show_more_options(this.value,<?php echo $count ?>)>
              <?php
              foreach($form_fields as $val2){
                if( $val2 == $val['type'] ){
                  $selected = 'selected';
                }
                else{
                  $selected = '';
                }

                if( $val['type'] === 'number' ){
                  $num_style = 'style="display:flex"';
                }
                else{
                  $num_style = 'style="display:none"';
                }
                ?>
                <option value="<?php echo $val2 ?>" <?php echo $selected ?>><?php echo $val2 ?></option>
                <?php
              }
              ?>                         
              </select>
              </td>              

              <td>
              <textarea class="form-control" rows="2" name="validation[msg][]" placeholder="This is required"><?php echo $val['msg'] ?></textarea>

              <div class="more_options" id="num-<?php echo $count ?>" <?php echo $num_style; ?>>
              <input type="number" name="validation[min][]" value="<?php echo $val['min'] ?>" placeholder="Min">
              <input type="number" name="validation[max][]" value="<?php echo $val['max'] ?>" placeholder="Max">
              </div>

              </td>

              <td>
              <button type="button" class="remove">X</button> 
              </td>

              </tr>
              <?php
            }
          }
          ?>
          
          <tr id="after-add-more">
            <td colspan="4" style="text-align: center;">
            <button type="button" class="btn btn-lg" style="width: 100%;padding: 5px;" onclick="add_more_fields()">Add Fields</button>
            </td>
          </tr>

        </tbody>
      </table>
      </div>
    </div> 
    </div>

    <div id="Mail" class="tabcontent">
      <p class="mb-3">You can edit the mail template here. For details</p>
      <div class="mb-1">
        <label>To</label>
        <input type="text" class="form-control" id="mail_to" name="mail[to]" value="<?php echo isset( $mail['to'] ) ? $mail['to'] : ''; ?>" placeholder="">
      </div>
      <div class="mb-1">
        <label>From</label>
        <input type="text" class="form-control" id="mail_from" name="mail[from]" value="<?php echo isset( $mail['from'] ) ? $mail['from'] : ''; ?>" placeholder="">
      </div>
      <div class="mb-1">     
        <label>Subject</label>
        <input type="text" class="form-control" id="mail_subject" name="mail[subject]" value="<?php echo isset( $mail['subject'] ) ? $mail['subject'] : ''; ?>" placeholder="">
      </div>
      <div class="mb-1">     
        <label>
        Message body
        <p>       
        <b>Rules : </b>
        <ol class="rules">        
        <li>For getting dynamic field name use curly brackets <b>{}</b> Example : {first_name}, {last_name}, {email} etc.</li>
        </ol>
        </p>
        </label>
        <textarea class="form-control" id="mail_body" name="mail[body]" rows="10"><?php echo isset( $mail['body'] ) ? $mail['body'] : ''; ?></textarea>
      </div>
    </div>

    <div id="Messages" class="tabcontent">
      <p class="mb-3">You can edit messages used in various situations here</p>
      <div class="mb-1">     
        <label>Mail success Text</label>
        <input type="text" class="form-control" id="messages_mail_success" name="messages[mail_success]" value="<?php echo isset( $messages['mail_success'] ) ? $messages['mail_success'] : ''; ?>" placeholder="">
      </div>
    </div>

    <div class="pt-3">
      <button type="submit" class="btn btn-lg">Save</button>
    </div>
    
    </form>

  </div>


  <div id="myModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <p>Some text in the Modal..</p>
    </div>
  </div>

<div>

<script>
var option_count = 10000
function add_more_fields(){
      option_count++     

      var html =''
      html    += '<tr class="control-group">'

      html    += '<td>'
      html    += '<input type="text" name="validation[field_name][]" placeholder="Name">'
      html    += '</td>'
      
      html    += '<td>'
      html    += '<select class="form-select" name="validation[type][]" onchange=show_more_options(this.value,'+option_count+')>'
      <?php
      foreach($form_fields as $val){
        ?>
        html  += '<option value="<?php echo $val ?>"><?php echo $val ?></option>'
        <?php
      }
      ?>                         
      html    += '</select>'
      html    += '</td>'     

      html    += '<td>'
      html    += '<textarea class="form-control" rows="2" name="validation[msg][]" placeholder="This is required"></textarea>'

      html    +='<div class="more_options" id="num-'+option_count+'">'
      html    +='<input type="number" name="validation[min][]" placeholder="Min">'
      html    +='<input type="number" name="validation[max][]" placeholder="Max">'
      html    +='</div>'

      html    += '</td>'

      html    += '<td>'
      html    += '<button type="button" class="remove">X</button>' 
      html    += '</td>'
      
      html    += '</tr>';	  

      var my_elem = document.getElementById('after-add-more'); 
      my_elem.insertAdjacentHTML("beforeBegin",html);   
      remove()
      
}
function remove(){
  var removes = document.querySelectorAll('.remove')
  for(var i = 0; i < removes.length; i++) {
    var anchor = removes[i];
    anchor.onclick = function(e) {
      this.closest('.control-group').remove()
    }
  }
}
remove()

function show_more_options(type,id){
  var num_node = document.getElementById('num-'+id)
  if(type==='number'){   
    num_node.style.display = 'flex'
  }
  else{
    num_node.style.display = 'none'
  }
  
}
</script>
