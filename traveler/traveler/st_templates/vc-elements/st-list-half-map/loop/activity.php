<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Activity element loop 2
 *
 * Created by ShineTheme
 *
 */

$col = 12 / 3;
$info_price = STActivity::get_info_price();
$price = $info_price['price'];
$count_sale = $info_price['discount'];
if(!empty($count_sale)){
    $price = $info_price['price'];
    $price_sale = $info_price['price_old'];
}
?>
<?php
$link=st_get_link_with_search(get_permalink(),array('start','end','duration','people'),$_GET);
$thumb_url = wp_get_attachment_url(get_post_thumbnail_id(get_the_ID()));
?>
<div class="div_item_map <?php echo 'div_map_item_'.get_the_ID() ?>" >
    <!--<div class="st_featured"><?php /*echo esc_html($post_type) */?></div>-->
    <?php echo STFeatured::get_featured(); ?>
    <div class="thumb item_map">
        <header class="thumb-header">
            <div class="booking-item-img-wrap st-popup-gallery">
                <a href="<?php echo esc_url($thumb_url) ?>" class="st-gp-item">
                    <?php 
                    if(has_post_thumbnail() and get_the_post_thumbnail()){
                        the_post_thumbnail(array(360, 270, 'bfi_thumb' => TRUE)) ;
                    }else {
                        echo st_get_default_image();
                    }
                        ?>
                </a>
                <?php
                $count = 0;
                $gallery = get_post_meta(get_the_ID(), 'gallery', TRUE);
                $gallery = explode(',', $gallery);
                if (!empty($gallery) and $gallery[0]) {
                    $count += count($gallery);
                }
                if (has_post_thumbnail()) {$count++;}
                if ($count) {
                    echo '<div class="booking-item-img-num"><i class="fa fa-picture-o"></i>';
                    echo esc_attr($count);
                    echo '</div>';
                }
                ?>
                <div class="hidden">
                    <?php if (!empty($gallery) and $gallery[0]) {
                        $count += count($gallery);
                        foreach ($gallery as $key => $value) {
                            $img_link = wp_get_attachment_image_src($value, array(800, 600, 'bfi_thumb' => TRUE));
                            if (isset($img_link[0]))
                                echo "<a class='st-gp-item' href='{$img_link[0]}'></a>";
                        }
                    } ?>
                </div>
            </div>
        </header>
        <div class="thumb-caption">
            <ul class="icon-group text-tiny text-color">
                <?php echo  TravelHelper::rate_to_string(STReview::get_avg_rate()); ?>
            </ul>
            <h5 class="thumb-title">
                <a href="<?php echo esc_url($link) ?>" class="text-darken">
                    <?php the_title(); ?>
                </a>
            </h5>
            <p class="mb0">
                <small><i class="fa fa-map-marker"></i>
                    <?php $address = get_post_meta(get_the_ID(),'address',true); ?>
                    <?php
                    if(!empty($address)){
                        echo esc_html($address);
                    }
                    ?>
                </small>
            </p>
            <p class="mb0 text-darken item_price_map">

                <span class="text-lg lh1em">
                      <?php echo STActivity::get_price_html(get_the_ID(),false,'<br>'); ?>
                   </span>
            </p>
            <a class="btn btn-primary btn_book" href="<?php echo esc_url($link)?>"><?php _e("Book Now",ST_TEXTDOMAIN) ?></a>
            <button class="btn btn-default pull-right close_map" ><?php _e("Close",ST_TEXTDOMAIN) ?></button>
        </div>
        <script>
            jQuery(function($){
                $('.st_list_map .div_item_map').hide();
                $('.st_list_map .div_item_map').fadeIn(1000);
                $('.close_map').click(function(){
                    $(this).parent().parent().parent().fadeOut(500);
                });
                $('.st-popup-gallery').each(function () {
                    $(this).magnificPopup({
                        delegate: '.st-gp-item',
                        type: 'image',
                        gallery: {
                            enabled: true
                        }
                    });
                });
            })
        </script>
    </div>
</div>


