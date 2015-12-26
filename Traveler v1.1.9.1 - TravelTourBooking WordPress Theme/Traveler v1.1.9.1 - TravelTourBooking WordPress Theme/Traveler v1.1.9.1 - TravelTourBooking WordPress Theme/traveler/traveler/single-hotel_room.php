<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Single room
 *
 * Created by ShineTheme
 *
 */
get_header();?>

<?php 
$layout = st()->get_option('hotel_single_room_layout','');
if(get_post_meta(get_the_ID(), 'st_custom_layout', true))            $layout = get_post_meta(get_the_ID(), 'st_custom_layout', true);

if(get_post_meta($layout , 'is_breadcrumb' , true) !=='off'){
    get_template_part('breadcrumb');
}

?>
<?php 
    if(has_post_thumbnail() and get_the_post_thumbnail())
        $thumb_url = wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()) );

    $gallery=get_post_meta(get_the_ID(),'gallery',true);
    $gallery_array=explode(',',$gallery);
    $fancy_arr = array();
    if(is_array($gallery_array) and !empty($gallery_array)){
        foreach($gallery_array as $key=>$value){
            $img_link=wp_get_attachment_image_src($value,array(800,600,'bfi_thumb'=>true));
            $fancy_arr[] = array(
                'href' => $img_link[0],
                'title' => ''
                );
        }
    }

?>
<div id="single-room"  class="booking-item-details">
    <div class="thumb">
        <a href="javascript:;" id="fancy-gallery">
            <?php if(has_post_thumbnail()  and get_the_post_thumbnail() )
            {
                the_post_thumbnail(array(1600, 500), array('class'=> 'fancy-responsive'));
}else{
                echo "<img src='".get_template_directory_uri().'/img/default/1600x500.png'."' class='fancy-responsive' alt='".get_the_title()."'>";
            } ?>
        </a>
    </div>
	<?php
        
        if($layout && !empty($layout))
        {
            echo STTemplate::get_vc_pagecontent($layout);
            
        }else{
            echo do_shortcode('
                [vc_row el_class="custom-row-single-room"][vc_column width="2/3"][st_hotel_room_header][st_hotel_room_facility facility_des=""][st_hotel_room_calendar][st_hotel_room_gallery style="slide"][/vc_column][vc_column width="1/3" el_class="custom-row-single-room"][st_hotel_room_sidebar][/vc_column][/vc_row][vc_row][vc_column css=".vc_custom_1435226180968{margin-top: 20px !important;}"][vc_column_text]
<h4 class="booking-item-title text-color">Reviews</h4>
[/vc_column_text][st_hotel_room_review][/vc_column][/vc_row][vc_row row_fullwidth="yes" el_class="ov-h" css=".vc_custom_1436947692102{padding-right: 0px !important;padding-left: 0px !important;}"][vc_column css=".vc_custom_1436946930085{padding-top: 50px !important;padding-right: 0px !important;padding-left: 0px !important;}"][st_room_map show_circle="yes" zoom="13"][/vc_column][/vc_row]
            ');
        }
    ?>
</div>
<?php 
    if(is_array($fancy_arr) && count($fancy_arr)):
?>
    <script>
        jQuery(document).ready(function($) {
            $('a#fancy-gallery').click(function(event) {
                var list = <?php echo json_encode($fancy_arr); ?>;
                $.fancybox.open(list);
            });
        });
    </script>
<?php endif; ?>
<script>
    jQuery(document).ready(function($) {
        $('a.button-readmore').click(function(){
            if($('#read-more').length > 0){
                $('#read-more').removeClass('hidden');
                $(this).addClass('hidden');
                $('#show-description').remove();
            }
        });
    });
</script>
<?php get_footer( ) ?>
