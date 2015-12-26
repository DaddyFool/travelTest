<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Custom user menu
 *
 * Created by ShineTheme
 *
 */

?>
<div class="col-md-9">
    <div class="top-user-area clearfix">
        <ul class="top-user-area-list list list-horizontal list-border ">
            <?php echo st()->load_template("menu/login_select" , null ,  array('container' =>"li"  , "class"=>"top-user-area-avatar"));?>
            <?php echo st()->load_template("menu/currency_select" , null ,  array('container' =>"li"  , "class"=>"nav-drop nav-symbol"));?>
            <?php echo st()->load_template("menu/language_select" , null ,  array('container' =>"li"  , "class"=>"top-user-area-lang nav-drop"));?>
        </ul>
        <?php
            $search_header_onoff = st()->get_option('search_header_onoff', 'on');
            if($search_header_onoff == 'on'):
        ?>
        <form class="main-header-search" action="<?php echo home_url( '/' ); ?>" method="get">
            <div class="form-group form-group-icon-left">
                <i class="fa fa-search input-icon"></i>
                <input type="text" data-lang="<?php echo (defined('ICL_LANGUAGE_CODE'))?ICL_LANGUAGE_CODE:false; ?>" name="s" value="<?php echo get_search_query() ?>" class="form-control st-top-ajax-search">
                <input type="hidden" name="post_type" value="post">
            </div>
        </form>
        <?php endif; ?>
    </div>
</div>