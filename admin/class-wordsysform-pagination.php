<?php
class Pagination{

    public function paginate($total_items, $item_per_page, $current_page, $adjacents, $url = NULL){
        
        if ($url === NULL) {
            $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        }        
		
        $total_pages = $total_items;
        $limit       = $item_per_page; 
        $page        = $current_page; 		
        
        if ($page == 0){
            $page = 1; 
        }
        $prev       = $page - 1; 
        $next       = $page + 1; 
        $lastpage   = ceil($total_pages / $limit); 
        $lpm1       = $lastpage - 1;         
			
        $pagination = "";

        if ($lastpage > 1) {
            $pagination .= "<div class=\"pagination\">";
            if ($page > 1) {                
                $pagination .= "<a href=\"{$url}&p={$prev}\">&laquo;</a>";                
            } else {
                $pagination .= "<a href=\"javascript:void(0)\">&laquo;</a>";
               
            }            
            if ($lastpage < 7 + ($adjacents * 2)) { 
                for ($counter = 1; $counter <= $lastpage; $counter++) {
                    if ($counter == $page)
                        $pagination .= "<a href=\"{$url}&p={$counter}\" class=\"active\">{$counter}</a>";                    
                    else
                        $pagination .= "<a href=\"{$url}&p={$counter}\" >{$counter}</a>";
                }
            } elseif ($lastpage > 5 + ($adjacents * 2)) { 
               
                if ($page <= 1 + ($adjacents * 2)) {
                    for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                        if ($counter == $page)
                            $pagination .= "<a href=\"{$url}&p={$counter}\" class=\"active\">{$counter}</a>";
                        else
                            $pagination .= "<a href=\"{$url}&p={$counter}\">{$counter}</a>";                        
                    }
                    $pagination .= "<a href=\"javascript:void(0)\">...</a>";
                    $pagination .= "<a href=\"{$url}&p={$lpm1}\" >{$lpm1}</a>";
                    $pagination .= "<a href=\"{$url}&p={$lastpage}\" >{$lastpage}</a>";
                }
                elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                    $pagination .= "<a href=\"{$url}&p=1\" >1</a>";                   
                    $pagination .= "<a href=\"{$url}&p=2\" >2</a>";
                    $pagination .= "...";
                    for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                        if ($counter == $page)
                            $pagination .= "<a href=\"{$url}&p={$counter}\" class=\"active\">{$counter}</a>";
                       
                        else
                            $pagination .= "<a href=\"{$url}&p={$counter}\">{$counter}</a>";
                        
                    }
                    $pagination .= "<a href=\"javascript:void(0)\">...</a>";
                    $pagination .= "<a href=\"{$url}&p={$lpm1}\" >{$lpm1}</a>";
                    $pagination .= "<a href=\"{$url}&p={$lastpage}\" >{$lastpage}</a>";
                   
                }
                else {
                    $pagination .= "<a href=\"{$url}&p=1\">1</a>";
                    $pagination .= "<a href=\"{$url}&p=2\">2</a>";
                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                        if ($counter == $page)
                            $pagination .= "<a href=\"{$url}&p={$counter}\" class=\"active\">{$counter}</a>";
                       
                        else
                            $pagination .= "<a href=\"{$url}&p={$counter}\" >{$counter}</a>";
                        
                    }
                }
            }
            //next button
            if ($page < $counter - 1) {
                $pagination .= "<a href=\"{$url}&p={$next}\">&raquo;</a>";
               
            } else {
                $pagination .= "<a href=\"javascript:void(0)\" disabled>&raquo;</a>";
                
            }
            $pagination .= "</div>";
			
			$pagination_row = '<div class="right">'.$pagination.'</div>';
			
            $start_text   = ($total_pages) ? (($page - 1) * $limit) + 1 : 0;
            $end_text     = ((($page - 1) * $limit) > ($total_pages - $limit)) ? $total_pages : ((($page - 1) * $limit) + $limit);
            $display_text = 'Showing ' . $start_text . ' to ' . $end_text . ' of ' . $total_pages . ' results';
			
			$text_row = '<div class="right" style="line-height: 35px; padding-right:10px">'.$display_text.'</div>';
			
            $pagination = $pagination_row.$text_row;
        }
		
       return $pagination;
    }
   
}


