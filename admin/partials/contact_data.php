<?php
$pagedata = array(
    'title'         =>'Wordsys Contact Data',
    'page_slug'     =>'wordsysform-contact-data',
    'page_url'      =>admin_url('admin.php?page=wordsysform-contact-data', ''),   
    'view_url'      =>admin_url('admin.php?page=wordsysform-view-contact-data', ''), 
);

global $wpdb, $table_prefix;
$table  = $table_prefix.'wordsys_form';	
$table2 = $table_prefix.'wordsys_form_contact';	

$search             = isset( $_GET['search'] ) ? $_GET['search'] : '';
$page_no            = isset( $_GET['p'] ) ? $_GET['p'] : 1;
$contact_id_array   = isset( $_GET['id'] ) ? $_GET['id'] : array();
$contact_id         = isset( $_GET['contact_id'] ) ? $_GET['contact_id'] : '';
$action             = isset( $_GET['action'] ) ? $_GET['action'] : '';

if( $action == 'delete' && $contact_id ){    
    $q = "DELETE FROM $table2 WHERE contact_id = $contact_id ";
    $wpdb->query($q);      
    $_SESSION['success_msg'] = 'deleted successfully'; 
    ?>
    <script>     
    window.history.pushState('', '', '<?php echo $pagedata['page_url'] ?>');
    </script>
    <?php
    
}
elseif( $action == 'delete' && $contact_id_array ){
    foreach($contact_id_array as $val){
        $q = "DELETE FROM $table2 WHERE contact_id = $val ";
        $wpdb->query($q);  
    }
    $_SESSION['success_msg'] = 'deleted successfully'; 
    ?>
    <script>     
    window.history.pushState('', '', '<?php echo $pagedata['page_url'] ?>');
    </script>
    <?php 
}

$pagi_url_array  = [];
if($search){
    $pagi_url_array[]  = array(
        'key'  =>'search',
        'value'=>$search
    );
}

$pagi_url = $pagedata['page_url'];
if($pagi_url_array){
    $count = 0;
    foreach($pagi_url_array as $val){
        $count++;
        if($count == 1){
            $pagi_url.='&'.$val['key'].'='.$val['value'];
        }
        else{
            $pagi_url.='&'.$val['key'].'='.$val['value'];
        }        
    }
}

$filter_q = " WHERE 
`form_data` LIKE '%".$search."%' OR 
`ip_address` LIKE  '%".$search."%' OR
`browser` LIKE  '%".$search."%' 
";

$q = "SELECT COUNT(*) as total FROM $table2 $filter_q";
$row = $wpdb->get_row($q); 
$total_items = $row->total;

$items_per_page  = 2;
$limit_from      = ($page_no - 1) * $items_per_page;

$q = "SELECT *,
(SELECT title FROM $table WHERE form_id=T2.form_id) as form_title
FROM $table2 as T2 $filter_q
ORDER BY create_date desc
LIMIT $limit_from,$items_per_page ";
$result = $wpdb->get_results($q); 

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'class-wordsysform-pagination.php';
$paginate = new Pagination();
$pagiHtml = $paginate->paginate($total_items, $items_per_page, $page_no, 3, $pagi_url);


// echo '<pre>';
// print_r($result);
// echo '</pre>';
// die;
?>

<div class="wordsysform">
    <div class="container">

        <h3>
        <?php echo $pagedata['title']; ?>
        </h3>
        
        <?php if (isset($_SESSION['success_msg'] )) { ?>
        <div id="message" class="notice notice-success m-0">
        <p>
        <?php 
        echo $_SESSION['success_msg']; 
        unset($_SESSION['success_msg']);
        ?>
        </p> 
        </div>       
        <?php } ?>          
        
        <form id="wordsysform-cntact-data" method="get" action="<?php echo $pagedata['page_url']; ?>">
        <input type="hidden" name="page" value="<?php echo $pagedata['page_slug']; ?>">
        <div class="mt-3 right">
            <div>
            <input type="text" class="form-control" id="search" name="search" value="<?php echo $search; ?>" placeholder="Search...">
            </div>
            <div style="max-width: 50%; padding-top:7px">
            <button type="submit" class="btn btn-md ml-2">Filter</button>
            </div>
        </div>
        </form>

        <form id="wordsysform-cntact-data" method="get" action="<?php echo $pagedata['page_url']; ?>">
        <input type="hidden" name="page" value="<?php echo $pagedata['page_slug']; ?>">
        <table>
            <tr>
                <th style="width: 10px;"><input class="checkall" type="checkbox"></th>
                <th>Form Title</th>
                <th>Contact data</th>
                <th>IP Address</th>                
                <th>Date</th>
            </tr>
            <?php
            if($result){
                foreach($result as $val){
                    ?>
                    <tr>
                        <td><input type="checkbox" class="checkall-item" name="id[]" value="<?php echo $val->contact_id; ?>"></td>
                        <td><?php echo $val->form_title; ?></td>
                        <td>
                            <?php                             
                            $data = Wordsysform_Admin::getArrayData($val->form_data);                            
                            if($data){
                                $count = 0;
                                foreach($data as $val2){
                                    $count++;
                                    if( $count <= 3){
                                        echo '<b>'.$val2['name'].'</b>: ';
                                        echo $val2['value'].'<br />';
                                    }                                    
                                }
                            }
                            ?>
                            <div class="pt-2">  
                            <a class="link" href="javascript:void(0);" onClick="view_wordsysform_data('<?php echo $val->contact_id; ?>','<?php echo admin_url('admin-ajax.php'); ?>');">View</a>  
                            <span class="devider">|</span>
                            <a class="link" href="javascript:void(0);" onClick="confirm_delete('<?php echo $pagedata['page_url']; ?>&contact_id=<?php echo $val->contact_id ?>&action=delete');">Trash</a>  
                            </div>  
                        </td>
                        <td><?php echo $val->ip_address; ?></td>                        
                        <td><?php echo $val->create_date; ?></td>
                    </tr>
                <?php
                }
             
            }
            else{
                ?>
                <tr>
                    <td colspan="5">
                    No record Found
                    </td>
                </tr>
                <?php
            }
            ?>            
        </table>
        <div class="left mt-3" style="width:450px;">
            <div style="width:250px;">
                <select name="action" id="frm_action" class="form-control">
                    <option value="">Choose an action...</option>
                    <option value="delete">Delete</option>
                </select>
            </div>
            <div style="width:200px; padding-top:7px">
            <button type="button" class="btn btn-md ml-2" onClick="confirm_apply('<?php echo $pagedata['page_url']; ?>');">Apply</button>
            </div>
        </div>
        </form>

        <div class="pt-3" style="clear: both;">        
        <?php echo $pagiHtml; ?>
        </div>

</div>

<div id="deleteModal" class="modal" >
    <div class="modal-content" style="width: 350px;">
      <span class="close wordsysform-close-1">&times;</span>
      <div style="text-align: center;">
      <h2>Are you sure?</h2>
      <p>You want to delete selected item(s)</p>
      <div class="swal2-actions mt-3">
        <button type="button" class="btn btn-md ml-2 delete_it wordsysform-action-1">Yes, delete it!</button>
        <button type="button" class="btn btn-md ml-2 wordsysform-close-1">No, cancel!</button>
      </div>
      </div>
    </div>
</div>

<div id="applyModal" class="modal" >
    <div class="modal-content" style="width: 350px;">
      <span class="close wordsysform-close-2">&times;</span>
      <div style="text-align: center;">
      <h2>Are you sure?</h2>
      <p>You want to perform this action</p>
      <div class="swal2-actions mt-3">
        <button type="button" class="btn btn-md ml-2 delete_it wordsysform-action-2">Yes, Do it!</button>
        <button type="button" class="btn btn-md ml-2 wordsysform-close-2">No, cancel!</button>
      </div>
      </div>
    </div>
</div>

<div id="viewDataModal" class="modal" >
    <div class="modal-content" style="width: 800px;">
      <span class="close wordsysform-close-3">&times;</span>
      <div style="text-align: left;">      
      <div id="wordsysform-view"></div>  
      </div>
    </div>
</div>

<div>
