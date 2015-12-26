<?php 
// login dropdown 
// from 1.1.9
if (empty($container)){$container = "div"; }
if (empty($class)) {$class = "top-user-area-avatar" ;}
$class_default = "nav-drop" ; 

$login_fb=st()->get_option('social_fb_login','on');
$login_gg=st()->get_option('social_gg_login','on');
$login_tw=st()->get_option('social_tw_login','on');

$is_user_nav = st()->get_option('enable_user_nav','on') ?>
<?php if($is_user_nav == 'on'): ?>
    <?php if(is_user_logged_in()):?>
    <?php echo balanceTags('<'.$container.' class="'.$class.'">'); ?>    
        <?php
        $account_dashboard = st()->get_option('page_my_account_dashboard');
        $location='#';
        if(!empty($account_dashboard)){
            $location = esc_url(add_query_arg( 'page_id', $account_dashboard, home_url() ));
        }
        ?>
        <a href="<?php echo esc_url($location) ?>">
            <?php
            $current_user = wp_get_current_user();
             echo st_get_profile_avatar($current_user->ID,40);
            //echo st_get_language('hi').', '.$current_user->display_name;
            printf(__('hi, %s',ST_TEXTDOMAIN),$current_user->display_name);
            ?>
        </a>
    <?php echo  balanceTags('</'.$container.'>') ;?>
    <?php echo  balanceTags('<'.$container.'>') ;?>
        <a class="btn-st-logout" href="<?php echo wp_logout_url(home_url())?>"><?php st_the_language('sign_out')?></a>
    <?php echo  balanceTags('</'.$container.'>') ;?>
    <?php else: ?>
         <?php echo balanceTags('<'.$container.' class="'.$class_default.'">'); ?>    
            <?php
            $page_login = st()->get_option('page_user_login');
            ?>
            <a href="#" onclick="return false;"><?php st_the_language('sign_in')?><i class="fa fa-angle-down"></i><i class="fa fa-angle-up"></i></a>
            <ul class="list nav-drop-menu user_nav_big social_login_nav_drop" >
                <li><a  class="" href="<?php echo get_permalink($page_login) ?>"><?php st_the_language('sign_in')?></a></li>

                <?php if($login_fb=="on"): ?>
                <li><a onclick="return false" class="btn_login_fb_link login_social_link" href="<?php echo STSocialLogin::get_provider_login_url('Facebook') ?>"><?php st_the_language('connect_with')?> <i class="fa fa-facebook"></i></a></li>
                <?php endif;?>

                <?php if($login_gg=="on"): ?>
                <li><a onclick="return false" class="btn_login_gg_link login_social_link" href="<?php echo STSocialLogin::get_provider_login_url('Google') ?>"><?php st_the_language('connect_with')?> <i class="fa fa-google-plus"></i></a></li>

                <?php endif;?>

                <?php if($login_tw=="on"): ?>
                <li><a onclick="return false" class="btn_login_tw_link login_social_link" href="<?php echo STSocialLogin::get_provider_login_url('Twitter') ?>"><?php st_the_language('connect_with')?> <i class="fa fa-twitter"></i></a></li>
                <?php endif;?>
            </ul>
        <?php echo  balanceTags('</'.$container.'>') ;?>
    <?php endif;?>
<?php endif; ?>