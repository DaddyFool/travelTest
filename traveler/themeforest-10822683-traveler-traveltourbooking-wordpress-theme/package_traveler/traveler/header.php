<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Header
 *
 * Created by ShineTheme
 *
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <?php if(!function_exists('_wp_render_title_tag')):?>
        <title><?php wp_title('|',true,'right') ?></title>
    <?php endif;?>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <?php do_action('before_body_content')?>
    <div id="st_header_wrap" class="global-wrap header-wrap <?php echo apply_filters('st_container',true) ?>">
        <div class="row" id="st_header_wrap_inner">
            <?php 
                $is_topbar = st()->get_option('enable_topbar' , 'off') ; 
                if ($is_topbar == "on"){
                    echo st()->load_template('menu/top_bar' , null,  array()) ; 
                }
            ?>
            <?php 
                $menu_style = st()->get_option('menu_style' , "1");
                echo st()->load_template('menu/style' , $menu_style,  array()) ; 
            ?>            
        </div>
    </div>
<div class="global-wrap <?php echo apply_filters('st_container',true) ?>">
<div class="row">