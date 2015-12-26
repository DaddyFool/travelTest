<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Single blog
 *
 * Created by ShineTheme
 *
 */
get_header();
$location_id = get_the_ID();
?>
    <div class="container single-location" id="location-<?php echo get_the_ID() ; ?>">
        <div class="container">
            <div class="breadcrumb">
                <ul class="breadcrumb">
                    <?php st_breadcrumbs(); ?>
                </ul>
            </div>
        </div>
        <h2><?php the_title()?></h2>
        <div class="row" id="st_location_single">
            <?php 
            $sidebar_pos = get_post_meta(get_the_ID() , 'post_sidebar_pos' , true);
            if ($sidebar_pos =='no' )$sidebar_pos ="";
            
            if($sidebar_pos=="left" and get_post_meta(get_the_ID(),'post_sidebar',true)){
                get_sidebar(get_post_meta(get_the_ID(),'post_sidebar',true));

            }

            ?>

            <div class="<?php echo (  (in_array($sidebar_pos, array("left" , "right"))) and get_post_meta(get_the_ID(),'post_sidebar',true) ) ?'col-sm-9 col-xs-12':'col-sm-12 col-xs-12'; ?>">
                
                <?php 
                if ( 'on'== (get_post_meta(get_the_ID() , 'st_location_use_build_layout' , true))) {
                    echo st()->load_template('location/location_gallery' , NULL , array()); 
                    echo st()->load_template('location/location_tab' , NUll , array()) ;
                }else {
                    while ( have_posts() ) : the_post();
                        the_content();
                    endwhile;
                }
                
                ?>
            </div>

            <?php
            if($sidebar_pos=="right" and get_post_meta(get_the_ID(),'post_sidebar',true)){
                get_sidebar(get_post_meta(get_the_ID(),'post_sidebar',true));
            }
            ?>
        </div>
    </div>
<?php
get_footer();