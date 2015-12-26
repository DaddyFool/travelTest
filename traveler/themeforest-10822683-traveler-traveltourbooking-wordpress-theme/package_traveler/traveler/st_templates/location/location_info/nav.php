
<?php    
   $class = "col-md-3 col-lg-3 col-xs-3";
   $class_ul = " nav-stacked ";
   if (($nav_position)  =="top"){
      $class_ul = "";
      $class = "col-md-12 col-lg-12 col-xs-12" ; 
   }

?>
<div class="<?php echo esc_html($class);?> page-navigation">
   <ul class="nav nav-tabs <?php echo esc_html($class_ul);?>">
      <?php
         $list_item = get_post_meta(get_the_ID() , 'info_tab_item' , true );
         if (is_array($list_item)  and !empty($list_item)){
            foreach ($list_item as $key => $value) {
               $title  = $value['title'];
               $post_id = $value['post_info_select'];
               if (!$title){$title = get_the_title($post_id) ;}
               
               ?>

               <li class='<?php if ($key ==0) echo esc_attr('active'); ?>'>
                  <a href="#<?php echo esc_attr("tab_".$post_id); ?>" data-toggle="tab"><?php echo esc_attr(ucfirst($title));?></a>
               </li>

               <?php 
            }
         }
         
      ?>      
   </ul>
</div>
