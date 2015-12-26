<div class="tab-pane row active" id="tab_info">
    <!-- start description -->
    <div class='location_desc_container col-md-12 col-xs-12'>
        <div class='row'>
            <?php 
               if (get_post_meta(get_the_ID(), 'info_location_tab_item_enable' , true) =="on"){

                  $nav_position =  get_post_meta(get_the_ID() , 'info_location_tab_item_position' , true);
                  
                  
                  $array_class = array(
                        'nav_position' => $nav_position,                        
                     );

                  if ($nav_position == "left"){
                     echo st()->load_template('location/location_info/nav' , NULL , $array_class);
                     }
                  if ($nav_position == "top"){
                  echo st()->load_template('location/location_info/nav' , NULL , $array_class);
                  }
                  
                  echo st()->load_template('location/location_info/content' , NULL , $array_class);

                  if ($nav_position == "right"){
                     echo st()->load_template('location/location_info/nav' , NULL , $array_class);
                     }

               }else {
                  ?>
                <div class='col-md-12'>
                    <?php while(have_posts()){
                    the_post();
                    the_content() ; 
                       if ( comments_open() || '0' != get_comments_number() ) :
                           comments_template();
                       endif;
                    }?>

                </div>

                <?php }
            ?>
        </div>
    </div>

    <!-- end description -->
</div>
<?php 
   $show_map = false ; 
      $meta = get_post_meta(get_the_ID()  , 'location_tab_item' ,true )  ;
      if (!$meta) {
        $meta = STLocation::get_opt_list_std();
      }
      if(!empty ($meta) and is_array($meta)){
         foreach ($meta as $key => $value) {
            if ($value['tab_type'] =='st_map'){
               $show_map = true; 
               if (isset($value['map_spots'] )) $number = $value['map_spots'] ; 
               if (isset($value['map_height'] ))$map_height = $value['map_height'];
               if (isset($value['map_location_style'] ))$map_location_style = $value['map_location_style'];
            }
         }
      }
      
   ?>
    <?php 

   if ($show_map) {?>
        <div class="tab-pane row fade" id="google-map-tab" data-lat="<?php echo esc_attr(get_post_meta(get_the_ID() , 'map_lat' , true)); ?>" data-long="<?php echo esc_attr(get_post_meta(get_the_ID() , 'map_lng' , true)); ?>">
            <div class='col-md-12 col-xs-12'>

                <?php if (get_post_meta(get_the_ID() , 'map_lat' , true) and get_post_meta(get_the_ID() , 'map_lng' , true) ){?>

                    <?php 
               $st_type ; 

               if (!$number){$number = 36;} 

               if (!$map_height){$map_height = 500;}  

               if (!isset($map_location_style)){$map_location_style = 'normal';}

               $zoom = get_post_meta(get_the_ID(),'map_zoom_location' , true);
               if (!$zoom){$zoom = 15;}    

               $list_post_type = STLocation::get_post_type_list_active();
               $shortcode_string = "";
               if (is_array($list_post_type) and !empty($list_post_type)) {
                foreach ($list_post_type as $key => $value) {
                     if(get_post_meta(get_the_ID(),'tab_enable_'.$value , true) =='on'){
                        $flag = $value; 
                     }
                     if ($key != 0){
                        $shortcode_string .= ",".$value;
                     }else {
                        $shortcode_string .= $value;
                     }
                  }
               };
               $show_data_list_map = apply_filters('show_data_list_map' , 'no');
               echo do_shortcode('[st_list_map 
                  st_type= "'.$shortcode_string.'" 
                  number="'.$number.'" 
                  zoom="'.$zoom.'" 
                  height="'.$map_height.'" 
                  style_map="'.$map_location_style.'" 
                  st_list_location="'.get_the_ID().'" 
                  show_data_list_map = "'.$show_data_list_map.'" 
                  show_search_box = "no"]');

               ?>

                        <?php };?>


            </div>

        </div>

        <?php }
   
   if(!empty ($meta) and is_array($meta)){
      foreach ($meta as $key => $value) {   
         if (!empty($value['tab_type']) and $value['tab_type'] !='st_map' ){            
            ?>
            <div class="tab-pane row fade" id="<?php echo esc_attr($value['tab_type']).$key;?>">
                <?php echo st()->load_template('location/location_tabcontent',$value['tab_type']);?>
            </div>
            <?php
         }
      }
   }
 ?>