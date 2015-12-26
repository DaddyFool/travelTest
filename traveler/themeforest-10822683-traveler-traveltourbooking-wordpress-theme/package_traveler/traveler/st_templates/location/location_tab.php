<?php 
   
   if (!$tab_nav_position  = get_post_meta(get_the_ID() , 'tab_position',true)){$tab_nav_position = "top" ;} ; 
   
   $map_enable = get_post_meta(get_the_ID() , 'map_tab' , true) ; 
   $map_icon = get_post_meta(get_the_ID() , 'tab_icon_map', true);
   if (!$enable_tab2   = get_post_meta(get_the_ID() , 'info_location_tab_item_enable' , true)){$enable_tab2 = true; } 

   if (!$tab2_position = get_post_meta(get_the_ID() , 'info_location_tab_item_position', true)){$tab2_position = "left"; };
   $tab_info_item = get_post_meta(get_the_ID() , 'info_tab_item');


   $tab_class           = ($tab_nav_position =="top") ? "col-md-12 col-xs-12" : "col-md-12 col-xs-12" ; 
   $tab_nav_class       = ($tab_nav_position =="top") ? "" :"col-md-2 col-xs-2 "." tabs-".$tab_nav_position." " ; 
   $tab_content_class   = ($tab_nav_position =="top") ? " mt15 " :"col-md-10 col-xs-10 " ; 

?>
<!-- start tab -->
<?php if ($tab_nav_position !="top"): ?><div class="row"> <?php endif ; ?>
<div class='mt50 location_tab <?php echo esc_attr($tab_class) ; ?>'>
   <div class="search-tabs search-tabs-bg row">
      <?php if ($tab_nav_position =="left") {
         echo st()->load_template('location/nav_tab' , NULL, array('tab_nav_class'=> $tab_nav_class));
      }?>
      <?php if ($tab_nav_position =="top") {
         echo st()->load_template('location/nav_tab' , NULL, array('tab_nav_class'=> $tab_nav_class));
      }?>
      <div class="tab-content <?php echo esc_attr($tab_content_class)  ;?>">
         <?php 
            echo st()->load_template('location/location-tab-content' , NULL, array());
         ?>         
      </div>
      <?php if ($tab_nav_position =="right") {
         echo st()->load_template('location/nav_tab' , NULL, array('tab_nav_class'=> $tab_nav_class));
      }?>
   </div>
<?php if ($tab_nav_position !="top"): ?></div> <?php  endif; ?>
</div>