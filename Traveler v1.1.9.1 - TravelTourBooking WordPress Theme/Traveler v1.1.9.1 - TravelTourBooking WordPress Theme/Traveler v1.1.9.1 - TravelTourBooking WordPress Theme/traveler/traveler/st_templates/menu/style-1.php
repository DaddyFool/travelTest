<header id="main-header">
    <div class="header-top <?php echo apply_filters('st_header_top_class','') ?>">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <a class="logo" href="<?php echo home_url('/')?>">
                        <?php 
                        $logo_url = st()->get_option('logo',get_template_directory_uri().'/img/logo-invert.png');
                        $logo = TravelHelper::get_attchment_size($logo_url , true);
                        ?>
                        <img <?php if ($logo){echo balancetags(" width='".$logo['width']."px' height ='".$logo['height']."px' ") ; } ?> src="<?php echo esc_url($logo_url); ?>" alt="logo" title="<?php bloginfo('name')?>">
                    </a>
                </div>
                <?php get_template_part('users/user','nav');?>
            </div>
        </div>
    </div>
    <div class="main_menu_wrap" id="menu1">
        <div class="container" >
            <div class="nav">
                <?php if(has_nav_menu('primary')){
                    wp_nav_menu(array('theme_location'=>'primary',
                                      "container"=>"",
                                      'items_wrap'      => '<ul id="slimmenu" data-title="'.__("Main Menu",ST_TEXTDOMAIN).'" class="%2$s slimmenu">%3$s</ul>',
                    ));
                }
                ?>
            </div>
    </div><!-- End .main_menu_wrap-->
</header>