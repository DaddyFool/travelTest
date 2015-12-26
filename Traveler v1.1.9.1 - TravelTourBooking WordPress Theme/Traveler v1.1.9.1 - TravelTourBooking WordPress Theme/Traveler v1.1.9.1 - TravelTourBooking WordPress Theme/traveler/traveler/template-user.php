<?php
/*
 * Template Name: User Dashboard
*/

get_header();
global $current_user;

$user_link = get_permalink();

$lever       = $current_user->roles;
$url_id_user = '';
if(!empty( $_REQUEST[ 'id_user' ] )) {
    $id_user_tmp  = $_REQUEST[ 'id_user' ];
    $current_user = get_userdata( $id_user_tmp );
    $url_id_user  = $id_user_tmp;
}
$sc = STInput::request( 'sc' , 'dashboard' );
?>
    <div class="container bg-partner-new <?php echo esc_html($sc) ?>">
        <div class="row row_content_partner">
            <div class="col-md-3 user-left-menu ">
                <div class="page-sidebar navbar-collapse st-page-sidebar-new">
                    <ul class="page-sidebar-menu st_menu_new">
                        <li class="heading text-center user-profile-sidebar">
                            <div class="user-profile-avatar text-center">
                                <?php echo st_get_profile_avatar($current_user->ID, 300); ?>
                                <h5><?php echo esc_html($current_user->display_name) ?></h5>

                                <p><?php echo st_get_language('user_member_since') . mysql2date(' M Y', $current_user->data->user_registered); ?></p>
                            </div>
                        </li>
                        <?php if(!empty( $_REQUEST[ 'id_user' ] )) { ?>
                            <li class="item <?php if($sc == 'setting-info') echo 'active' ?> ">

                                <a href="<?php echo esc_url( add_query_arg( array(
                                    'sc'      => 'setting-info' ,
                                    'id_user' => $url_id_user
                                ) , $user_link ) ); ?>">
                                    <i class="fa fa-cog"></i>
                                    <span class="title"><?php st_the_language( 'user_settings' ) ?></span>
                                </a>
                            </li>
                        <?php }else{ ?>
                            <li class="item <?php if($sc == 'dashboard' or $sc == 'dashboard-info') echo 'active' ?>">
                                <a href="<?php echo esc_url( add_query_arg( 'sc' , 'dashboard' , $user_link ) ) ?>">
                                    <i class="fa fa-cogs"></i>
                                    <span class="title"><?php _e("Dashboard",ST_TEXTDOMAIN) ?></span>
                                    <span class="arrow "></span>
                                </a>
                                <ul class="sub-menu item">
                                    <?php if(st_check_service_available('st_hotel')):?>
                                        <li class="<?php if($sc == 'dashboard-info' and STInput::request('type') == 'st_hotel' ) echo 'active' ?>">
                                            <a href="<?php echo esc_url( add_query_arg( array('sc'=>'dashboard-info','type'=>'st_hotel') , $user_link ) ) ?>"><i class="fa fa-building-o"> &nbsp; </i><?php _e("Hotel Statistics",ST_TEXTDOMAIN) ?></a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if(st_check_service_available('st_rental')):?>
                                        <li class="<?php if($sc == 'dashboard-info' and STInput::request('type') == 'st_rental' ) echo 'active' ?>">
                                            <a href="<?php echo esc_url( add_query_arg( array('sc'=>'dashboard-info','type'=>'st_rental') , $user_link ) ) ?>"> <i class="fa fa-home"> &nbsp; </i><?php _e("Rental Statistics",ST_TEXTDOMAIN) ?></a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if(st_check_service_available('st_cars')):?>
                                        <li class="<?php if($sc == 'dashboard-info' and STInput::request('type') == 'st_cars' ) echo 'active' ?>">
                                            <a href="<?php echo esc_url( add_query_arg( array('sc'=>'dashboard-info','type'=>'st_cars') , $user_link ) ) ?>"> <i class="fa fa-cab"> &nbsp; </i><?php _e("Car Statistics",ST_TEXTDOMAIN) ?></a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if(st_check_service_available('st_tours')):?>
                                        <li class="<?php if($sc == 'dashboard-info' and STInput::request('type') == 'st_tours' ) echo 'active' ?>">
                                            <a href="<?php echo esc_url( add_query_arg( array('sc'=>'dashboard-info','type'=>'st_tours') , $user_link ) ) ?>"> <i class="fa fa-flag-o"> &nbsp; </i><?php _e("Tour Statistics",ST_TEXTDOMAIN)?></a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if(st_check_service_available('st_activity')):?>
                                        <li class="<?php if($sc == 'dashboard-info' and STInput::request('type') == 'st_activity' ) echo 'active' ?>">
                                            <a href="<?php echo esc_url( add_query_arg( array('sc'=>'dashboard-info','type'=>'st_activity') , $user_link ) ) ?>"> <i class="fa fa-bolt"> &nbsp; </i><?php _e("Activity Statistics",ST_TEXTDOMAIN) ?></a>
                                        </li>
                                    <?php endif; ?> 
                                </ul>
                            </li>
                            <li class="item <?php if($sc == 'overview') echo 'active' ?>">
                                <a href="<?php echo esc_url( add_query_arg( 'sc' , 'overview' , $user_link ) );?>"><i class="fa fa-user"></i><?php st_the_language( 'user_overview' ) ?></a>
                            </li>
                            <li class="item <?php if($sc == 'setting') echo 'active' ?>">
                                <a href="<?php echo esc_url( add_query_arg( 'sc' , 'setting' , $user_link ) ) ?>"><i
                                        class="fa fa-cog"></i><?php st_the_language( 'user_settings' ) ?></a>
                            </li>
                            <li class="item <?php if($sc == 'booking-history') echo 'active' ?>">
                                <a href="<?php echo esc_url( add_query_arg( 'sc' , 'booking-history' , $user_link ) ) ?>"><i
                                        class="fa fa-clock-o"></i><?php st_the_language( 'user_booking_history' ) ?>
                                </a>
                            </li>
                            <li class="item <?php if($sc == 'wishlist')echo 'active' ?>">
                                <a href="<?php echo esc_url( add_query_arg( 'sc' , 'wishlist' , $user_link ) ) ?>"><i
                                        class="fa fa-heart-o"></i><?php st_the_language( 'user_wishlist' ) ?></a>
                            </li>

                            <?php if(STUser_f::check_lever_partner( $lever[ 0 ] ) and st()->get_option( 'partner_enable_feature' ) == 'on'): ?>

                                <!--<li class="item <?php /*if($sc == 'reports')echo 'active' */?>">
                                    <a href="<?php /*echo esc_url( add_query_arg( 'sc' , 'reports' , $user_link ) ) */?>"><i class="fa fa-book"></i><?php /*_e( "Reports" , ST_TEXTDOMAIN ) */?>
                                    </a>
                                </li>-->
                                <?php 
                                    $list_partner = st()->get_option( 'list_partner');
                                ?>
                                <?php                                 
                                foreach( $list_partner as $k => $v ): ?>

                                    <?php if($v[ 'id_partner' ] == 'hotel' && st_check_service_available( 'st_hotel' )): ?>
                                        <li class="item <?php if(in_array($sc,array('create-hotel','my-hotel','create-room','my-room','booking-hotel'))) echo "active" ?>">
                                            <a class="cursor" style="cursor: pointer !important">
                                                <i class="fa fa-building-o"></i>
                                                <span class="title"><?php _e( 'Hotel' , ST_TEXTDOMAIN ) ?></span>
                                                <span class="arrow"></span>
                                            </a>
                                            <ul class="sub-menu item">
                                                <li <?php if($sc == 'create-hotel')
                                                    echo 'class="active"' ?>>
                                                    <a href="<?php echo esc_url( add_query_arg( 'sc' , 'create-hotel' , $user_link ) ) ?>"><i class="fa fa-building-o">&nbsp;</i><?php st_the_language( 'user_create_hotel' ) ?>
                                                    </a>
                                                </li>
                                                <li <?php if($sc == 'my-hotel')
                                                    echo 'class="active"' ?>>
                                                    <a href="<?php echo esc_url( add_query_arg( 'sc' , 'my-hotel' , $user_link ) ) ?>"><i class="fa fa-building-o">&nbsp;</i><?php st_the_language( 'user_my_hotel' ) ?>
                                                    </a>
                                                </li>
                                                <li <?php if($sc == 'create-room')
                                                    echo 'class="active"' ?>>
                                                    <a href="<?php echo esc_url( add_query_arg( 'sc' , 'create-room' , $user_link ) ) ?>"><i class="fa fa-hotel">&nbsp;</i><?php st_the_language( 'user_create_room' ) ?>
                                                    </a>
                                                </li>
                                                <li <?php if($sc == 'my-room')
                                                    echo 'class="active"' ?>>
                                                    <a href="<?php echo esc_url( add_query_arg( 'sc' , 'my-room' , $user_link ) ) ?>"><i class="fa fa-hotel">&nbsp;</i><?php st_the_language( 'user_my_room' ) ?>
                                                    </a>
                                                </li>
                                                <li <?php if($sc == 'add-hotel-booking')
                                                    echo 'class="active"' ?>>
                                                    <a href="<?php echo esc_url( add_query_arg( 'sc' , 'add-hotel-booking' , $user_link ) ) ?>"><i class="fa fa-building-o">&nbsp;</i><?php _e( "Add Booking Hotel" , ST_TEXTDOMAIN ) ?>
                                                    </a>
                                                </li>
                                                <li <?php if($sc == 'booking-hotel')
                                                    echo 'class="active"' ?>>
                                                    <a href="<?php echo esc_url( add_query_arg( 'sc' , 'booking-hotel' , $user_link ) ) ?>"><i class="fa fa-building-o">&nbsp;</i><?php _e( "Booking Hotel" , ST_TEXTDOMAIN ) ?>
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    <?php endif; ?>

                                    <?php if($v[ 'id_partner' ] == 'tour' && st_check_service_available( 'st_tours' )): ?>
                                        <li class="item <?php if(in_array($sc,array('create-tours','my-tours','booking-tours'))) echo "active" ?>">
                                            <a class=" cursor" style="cursor: pointer !important">
                                                <i class="fa fa-flag-o"></i>
                                                <?php _e( 'Tour' , ST_TEXTDOMAIN ) ?>
                                                <span class="arrow"></span>
                                            </a>
                                            <ul class="sub-menu item">
                                                <li <?php if($sc == 'create-tours')
                                                    echo 'class="active"' ?>>
                                                    <a href="<?php echo esc_url( add_query_arg( 'sc' , 'create-tours' , $user_link ) ) ?>"><i
                                                            class="fa fa-flag-o">&nbsp;</i><?php st_the_language( 'user_create_tour' ) ?>
                                                    </a>
                                                </li>
                                                <li <?php if($sc == 'my-tours')
                                                    echo 'class="active"' ?>>
                                                    <a href="<?php echo esc_url( add_query_arg( 'sc' , 'my-tours' , $user_link ) ) ?>">
                                                        <i class="fa fa-flag-o">&nbsp;</i><?php st_the_language( 'user_my_tour' ) ?>
                                                    </a>
                                                </li>
                                                <li <?php if($sc == 'add-tour-booking')
                                                    echo 'class="active"' ?>>
                                                    <a href="<?php echo esc_url( add_query_arg( 'sc' , 'add-tour-booking' , $user_link ) ) ?>"><i class="fa fa-flag-o">&nbsp;</i><?php _e( "Add Booking Tour" , ST_TEXTDOMAIN ) ?>
                                                    </a>
                                                </li>
                                                <li <?php if($sc == 'booking-tours')
                                                    echo 'class="active"' ?>>
                                                    <a href="<?php echo esc_url( add_query_arg( 'sc' , 'booking-tours' , $user_link ) ) ?>"><i
                                                            class="fa fa-flag-o">&nbsp;</i><?php _e( "Tour Bookings" , ST_TEXTDOMAIN ) ?>
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    <?php endif; ?>

                                    <?php if($v[ 'id_partner' ] == 'activity' && st_check_service_available( 'st_activity' )): ?>
                                        <li class="item <?php if(in_array($sc,array('create-activity','my-activity','booking-activity'))) echo "active" ?>">
                                            <a class="cursor">
                                                <i class="fa fa-bolt"></i>
                                                <?php _e( 'Activity' , ST_TEXTDOMAIN ) ?>
                                                <span class="arrow"></span>
                                            </a>
                                            <ul class="sub-menu item">
                                                <li <?php if($sc == 'create-activity')
                                                    echo 'class="active"' ?>>
                                                    <a href="<?php echo esc_url( add_query_arg( 'sc' , 'create-activity' , $user_link ) ) ?>"><i
                                                            class="fa fa-bolt">&nbsp;</i><?php st_the_language( 'user_create_activity' ) ?>
                                                    </a>
                                                </li>
                                                <li <?php if($sc == 'my-activity')
                                                    echo 'class="active"' ?>>
                                                    <a href="<?php echo esc_url( add_query_arg( 'sc' , 'my-activity' , $user_link ) ) ?>"><i
                                                            class="fa fa-bolt">&nbsp;</i><?php st_the_language( 'user_my_activity' ) ?>
                                                    </a>
                                                </li>
                                                <li <?php if($sc == 'add-activity-booking')
                                                    echo 'class="active"' ?>>
                                                    <a href="<?php echo esc_url( add_query_arg( 'sc' , 'add-activity-booking' , $user_link ) ) ?>"><i class="fa fa-bolt">&nbsp;</i><?php _e( "Add Booking Activity" , ST_TEXTDOMAIN ) ?>
                                                    </a>
                                                </li>
                                                <li <?php if($sc == 'booking-activity')
                                                    echo 'class="active"' ?>>
                                                    <a href="<?php echo esc_url( add_query_arg( 'sc' , 'booking-activity' , $user_link ) ) ?>"><i
                                                            class="fa fa-bolt">&nbsp;</i><?php _e( "Activity Bookings" , ST_TEXTDOMAIN ) ?>
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    <?php endif; ?>

                                    <?php if($v[ 'id_partner' ] == 'car' && st_check_service_available( 'st_cars' )): ?>
                                        <li class="item <?php if(in_array($sc,array('create-cars','my-cars','booking-cars'))) echo "active" ?>">
                                            <a class="cursor" style="cursor: pointer !important">
                                                <i class="fa fa-cab"></i><?php _e( 'Car' , ST_TEXTDOMAIN ) ?>
                                                <span class="arrow"></span>
                                            </a>
                                            <ul class="sub-menu item">
                                                <li <?php if($sc == 'create-cars')
                                                    echo 'class="active"' ?>>
                                                    <a href="<?php echo esc_url( add_query_arg( 'sc' , 'create-cars' , $user_link ) ) ?>">
                                                        <i class="fa fa-cab">&nbsp;</i><?php st_the_language( 'user_create_car' ) ?>
                                                    </a>
                                                </li>
                                                <li <?php if($sc == 'my-cars')
                                                    echo 'class="active"' ?>>
                                                    <a href="<?php echo esc_url( add_query_arg( 'sc' , 'my-cars' , $user_link ) ) ?>">
                                                        <i class="fa fa-cab">&nbsp;</i><?php st_the_language( 'user_my_car' ) ?>
                                                    </a>
                                                </li>
                                                <li <?php if($sc == 'add-car-booking')
                                                    echo 'class="active"' ?>>
                                                    <a href="<?php echo esc_url( add_query_arg( 'sc' , 'add-car-booking' , $user_link ) ) ?>"><i class="fa fa-cab">&nbsp;</i><?php _e( "Add Booking Car" , ST_TEXTDOMAIN ) ?>
                                                    </a>
                                                </li>
                                                <li <?php if($sc == 'booking-cars')
                                                    echo 'class="active"' ?>>
                                                    <a href="<?php echo esc_url( add_query_arg( 'sc' , 'booking-cars' , $user_link ) ) ?>">
                                                        <i class="fa fa-cab">&nbsp;</i><?php _e( "Car Bookings" , ST_TEXTDOMAIN ) ?>
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    <?php endif; ?>

                                    <?php if($v[ 'id_partner' ] == 'rental' && st_check_service_available( 'st_rental' )): ?>
                                        <li class="item <?php if(in_array($sc,array('create-rental','my-rental','create-room-rental','my-room-rental','booking-rental'))) echo "active" ?>">
                                            <a class="cursor" style="cursor: pointer !important">
                                                <i class="fa fa-home"></i></i>
                                                <?php _e( 'Rental' , ST_TEXTDOMAIN ) ?>
                                                <span class="arrow"></span>
                                            </a>
                                            <ul class="sub-menu item">
                                                <li <?php if($sc == 'create-rental')
                                                    echo 'class="active"' ?>>
                                                    <a href="<?php echo esc_url( add_query_arg( 'sc' , 'create-rental' , $user_link ) ) ?>"><i
                                                            class="fa fa-home">&nbsp;</i><?php st_the_language( 'user_create_rental' ) ?>
                                                    </a>
                                                </li>
                                                <li <?php if($sc == 'my-rental')
                                                    echo 'class="active"' ?>>
                                                    <a href="<?php echo esc_url( add_query_arg( 'sc' , 'my-rental' , $user_link ) ) ?>"><i
                                                            class="fa fa-home">&nbsp;</i><?php st_the_language( 'user_my_rental' ) ?>
                                                    </a>
                                                </li>
                                                <li <?php if($sc == 'create-room-rental')
                                                    echo 'class="active"' ?>>
                                                    <a href="<?php echo esc_url( add_query_arg( 'sc' , 'create-room-rental' , $user_link ) ) ?>"><i
                                                            class="fa fa-hotel">&nbsp;</i><?php echo __( 'Add new Rental Room' , ST_TEXTDOMAIN ); ?>
                                                    </a>
                                                </li>
                                                <li <?php if($sc == 'my-room-rental')
                                                    echo 'class="active"' ?>>
                                                    <a href="<?php echo esc_url( add_query_arg( 'sc' , 'my-room-rental' , $user_link ) ) ?>"><i
                                                            class="fa fa-hotel">&nbsp;</i><?php echo __( 'My Rental Room' , ST_TEXTDOMAIN ); ?>
                                                    </a>
                                                </li>
                                                <li <?php if($sc == 'add-rental-booking')
                                                    echo 'class="active"' ?>>
                                                    <a href="<?php echo esc_url( add_query_arg( 'sc' , 'add-rental-booking' , $user_link ) ) ?>"><i class="fa fa-home">&nbsp;</i><?php _e( "Add Booking Rental" , ST_TEXTDOMAIN ) ?>
                                                    </a>
                                                </li>
                                                <li <?php if($sc == 'booking-rental')
                                                    echo 'class="active"' ?>>
                                                    <a href="<?php echo esc_url( add_query_arg( 'sc' , 'booking-rental' , $user_link ) ) ?>"><i
                                                            class="fa fa-home">&nbsp;</i><?php _e( "Rental Bookings/Reservations" , ST_TEXTDOMAIN ) ?>
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif ?>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <div class="user-content col-md-9">
                <div class="st-page-bar">
                    <ul class="page-breadcrumb">
                        <?php echo STUser_f::st_get_breadcrumb_partner() ?>
                    </ul>
                </div>
                <?php
                if(!empty( $_REQUEST[ 'id_user' ] )) {
                    echo st()->load_template( 'user/user' , 'setting-info' , get_object_vars( $current_user ) );
                } else {
                    echo st()->load_template( 'user/user' , $sc , get_object_vars( $current_user ) );
                }
                ?>
            </div>
        </div>
    </div>
    <div class="gap"></div>
<?php
get_footer();
?>