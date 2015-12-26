<div class='location_tab tabbable <?php echo esc_attr($tab_nav_class) ; ?>'>
    <ul class="nav nav-tabs ">
        <li class="active">
            <a href="#tab_info" data-toggle="tab">
            <i class="<?php echo get_post_meta(get_the_ID() ,'tab_icon_info_icon', true); ?>"></i>
            <?php echo esc_attr(get_post_meta(get_the_ID() , 'information_text_title' ,true))?>
         </a>
        </li>
        <?php 
      $meta = get_post_meta(get_the_ID()  , 'location_tab_item' ,true )  ;
      if (!$meta) {
        $meta = STLocation::get_opt_list_std();
      }
      if (!empty($meta) and is_array($meta)){
         foreach ($meta as $key => $value) {
            $href = $value['tab_type'].$key;
            $icon = $value['tab_icon_'] ;
            $name = $value['title'];
            if ($value['tab_type'] == 'st_map'){
               $href = "google-map-tab" ; 
            }
            ?>
            <li>
                <a href="#<?php echo esc_attr($href) ; ?>" data-toggle="tab">
                  <i class=" fa <?php echo esc_attr($icon)?>"></i>
                  <?php echo esc_attr($name); ?>
               </a>
            </li>

            <?php 
           

         }
      }?>

    </ul>
</div>