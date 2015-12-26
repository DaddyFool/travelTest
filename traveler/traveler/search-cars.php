<?php

    /**
     * @package WordPress
     * @subpackage Traveler
     * @since 1.0
     *
     * Search custom cars
     *
     * Created by ShineTheme
     *
     */
    if(!st_check_service_available('st_cars'))
    {
        wp_redirect(home_url());
        die;
    }
    global $wp_query,$st_search_query;
    add_action('pre_get_posts',array(st()->car,'change_search_cars_arg'));
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    query_posts(
        array(
            'post_type'=>'st_cars',
            's'=>get_query_var('s'),
            'paged'     => $paged
        )
    );
    $st_search_query=$wp_query;
    remove_action('pre_get_posts',array(st()->car,'change_search_cars_arg'));

    get_header();
    //$result_string='';
    echo st()->load_template('search-loading');
?>
<?php 
    $cars_search_layout=st()->get_option('cars_layout_layout');

    if(!empty($_REQUEST['layout_id'])){
        $cars_search_layout = $_REQUEST['layout_id'];
    }
    
    $layout_class = get_post_meta($cars_search_layout , 'layout_size' , true);
    if (!$layout_class) $layout_class = "container";
?>

    <div class="mfp-with-anim mfp-dialog mfp-search-dialog mfp-hide" id="search-dialog">
        <?php echo st()->load_template('cars/search-form');?>
    </div>
    <?php 
        if(get_post_meta($cars_search_layout , 'is_breadcrumb' , true) !=='off'){
            get_template_part('breadcrumb');
        }
    ?>
    <div class="<?php echo balanceTags($layout_class) ; ?>">    
        <h3 class="booking-title"><?php echo balanceTags(st()->car->get_result_string())?>
            <small><a class="popup-text" href="#search-dialog" data-effect="mfp-zoom-out"><?php echo __('Change search',ST_TEXTDOMAIN)?></a></small>
        </h3>
        <?php  
            if($cars_search_layout)
            {
                echo  STTemplate::get_vc_pagecontent($cars_search_layout);
            }else{
                echo st()->load_template('cars/search-default');
            }
        ?>
    </div>
<?php
    get_footer();
?>