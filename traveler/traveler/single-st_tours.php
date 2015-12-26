<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Single tours
 *
 * Created by ShineTheme
 *
 */
get_header();

$detail_tour_layout=apply_filters('st_tours_detail_layout',st()->get_option('tours_layout'));
if(get_post_meta($detail_tour_layout , 'is_breadcrumb' , true) !=='off'){
    get_template_part('breadcrumb');
}
$layout_class = get_post_meta($detail_tour_layout , 'layout_size' , true);
if (!$layout_class) $layout_class = "container";
        
?>
<div class="<?php echo balanceTags($layout_class) ; ?>">
    <div class="booking-item-details no-border-top">
        <header class="booking-item-header">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="lh1em featured_single featured_single_tour">
                        <?php the_title()?><?php echo STFeatured::get_featured(); ?>
                    </h2>
                    <?php if($meta=get_post_meta(get_the_ID(),'address',true)){?>
                    <p class="lh1em text-small"><i class="fa fa-map-marker"></i> <?php echo esc_html($meta) ?></p>
                    <?php }?>
                    <ul class="list list-inline text-small">
                        <?php if($email=get_post_meta(get_the_ID(),'email',true)):?>
                        <li><a href="mailto:<?php echo esc_url($email)?>"><i class="fa fa-envelope"></i> <?php st_the_language('tour_email')?></a>
                        </li>
                        <?php endif;?>
                        <?php if($website=get_post_meta(get_the_ID(),'website',true)):?>
                            <li><a target="_blank" href="<?php echo esc_url( $website )?>"> <i class="fa fa-home"></i> <?php st_the_language('tour_website')?></a>
                            </li>
                        <?php endif;?>
                        <?php if($phone=get_post_meta(get_the_ID(),'phone',true)):?>
                            <li><a href="tel:<?php echo str_replace(' ','',trim($phone)) ?>"> <i class="fa fa-phone"></i> <?php echo esc_html( $phone)?></a>
                            </li>
                        <?php endif;?>
                        <?php if($fax=get_post_meta(get_the_ID(),'fax',true)):?>
                            <li><a href="tel:<?php echo str_replace(' ','',trim($fax)) ?>"> <i class="fa fa-fax"></i> <?php echo esc_html( $fax)?></a>
                            </li>
                        <?php endif;?>
                    </ul>
                </div>

            </div>
        </header>
        <?php        
        if($detail_tour_layout)
        {
            echo STTemplate::get_vc_pagecontent($detail_tour_layout);
        }else{
            //Default Layout
            echo st()->load_template('tours/single','default');
        }
        ?>
    </div><!-- End .booking-item-details-->
</div>
<?php  get_footer( ) ?>