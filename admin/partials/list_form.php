<?php
$pagedata = array(
    'title'         =>'Wordsys Form',
    'page_slug'     =>'wordsysform',
    'page_url'      =>admin_url('admin.php?page=wordsysform', ''),
    'add_url'       =>admin_url('admin.php?page=wordsysform-create', ''),
);

global $wpdb, $table_prefix;
$table = $table_prefix.'wordsys_form';	

$search          = isset( $_GET['search'] ) ? $_GET['search'] : '';
$page_no         = isset( $_GET['p'] ) ? $_GET['p'] : 1;
$form_id_array   = isset( $_GET['id'] ) ? $_GET['id'] : array();
$form_id         = isset( $_GET['form_id'] ) ? $_GET['form_id'] : '';
$action          = isset( $_GET['action'] ) ? $_GET['action'] : '';

if( $action == 'delete' && $form_id ){    
    $q = "DELETE FROM $table WHERE form_id = $form_id ";
    $wpdb->query($q);      
    $_SESSION['success_msg'] = 'deleted successfully'; 
    //set_transient('success_msg', 'deleted successfully', 5*1);// 5 seconds.    
    ?>
    <script>     
    window.history.pushState('', '', '<?php echo $pagedata['page_url'] ?>');
    </script>
    <?php
    
}
elseif( $action == 'delete' && $form_id_array ){
    foreach($form_id_array as $val){
        $q = "DELETE FROM $table WHERE form_id = $val ";
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
`title` LIKE '%".$search."%' OR 
`shortcode` LIKE  '%".$search."%' ";

$q = "SELECT COUNT(*) as total FROM `$table` $filter_q";
$row = $wpdb->get_row($q); 
$total_items = $row->total;

$items_per_page  = 2;
$limit_from      = ($page_no - 1) * $items_per_page;

$q = "SELECT * FROM `$table` $filter_q
ORDER BY title
LIMIT $limit_from,$items_per_page ";
$result = $wpdb->get_results($q); 

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'class-wordsysform-pagination.php';
$paginate = new Pagination();
$pagiHtml = $paginate->paginate($total_items, $items_per_page, $page_no, 3, $pagi_url);


// echo '<pre>';
// print_r($id);
// echo '</pre>';
// die;
?>

<div class="wordsysform">
    <div class="container">

        <h3>
            <?php echo $pagedata['title']; ?>
            <a class="btn btn-sm" href="<?php echo $pagedata['add_url']; ?>">Add New</a>
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
        
        
        <?php /*if ( get_transient('success_msg') ) { ?>
        <div id="message" class="notice notice-success m-0">
        <p>
        <?php 
        echo get_transient('success_msg');         
        ?>
        </p> 
        </div>       
        <?php }*/ ?>    
        
        <form id="wordsysform-form-list" method="get" action="<?php echo $pagedata['page_url']; ?>">
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

        <form id="wordsysform-form-list" method="get" action="<?php echo $pagedata['page_url']; ?>">
        <input type="hidden" name="page" value="<?php echo $pagedata['page_slug']; ?>">
        <table>
            <tr>
                <th style="width: 10px;"><input class="checkall" type="checkbox"></th>
                <th>Title</th>
                <th>Shortcode</th>
                <th>Author</th>
                <th>Date</th>
            </tr>
            <?php
            if($result){
                foreach($result as $val){
                    ?>
                    <tr>
                        <td>
                        <input type="checkbox" class="checkall-item" name="id[]" value="<?php echo $val->form_id; ?>">
                        </td>
                        <td>
                            <?php echo $val->title; ?>
                            <div class="pt-2">  
                            <a class="link" href="<?php echo $pagedata['add_url']; ?>&form_id=<?php echo $val->form_id ?>">Edit</a>  
                            <span class="devider">|</span>
                            <a class="link" href="javascript:void(0);" onClick="confirm_delete('<?php echo $pagedata['page_url']; ?>&form_id=<?php echo $val->form_id ?>&action=delete');">Trash</a>  
                            </div>  
                        </td>
                        <td><?php echo $val->shortcode; ?></td>
                        <td><?php echo $val->user_id; ?></td>
                        <td><?php echo $val->modified_date; ?></td>
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

<div>
