<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Single hotel
 *
 * Created by ShineTheme
 *
 */
get_header();
$detail_hotel_layout=apply_filters('st_hotel_detail_layout',st()->get_option('hotel_single_layout'));
if(get_post_meta($detail_hotel_layout , 'is_breadcrumb' , true) !=='off'){
    get_template_part('breadcrumb');
}


$layout_class = get_post_meta($detail_hotel_layout , 'layout_size' , true);
if (!$layout_class) $layout_class = "container";
?>

<div class="<?php echo balanceTags($layout_class) ; ?>">
    <div class="booking-item-details">
        <?php

        if($detail_hotel_layout) {
            $content=STTemplate::get_vc_pagecontent($detail_hotel_layout);
            echo balanceTags($content);
        }else{
            echo st()->load_template('hotel/single','default');
        }
        ?>
        <div class="gap"></div>
    </div>
</div>
<?php  get_footer( ) ?>