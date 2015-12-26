<?php
    get_header();
    global $current_user;

    $user_link=get_permalink();

    $lever = $current_user->roles;
    $url_id_user = '';
    if (!empty($_REQUEST['id_user'])) {
        $id_user_tmp = $_REQUEST['id_user'];
        $current_user = get_userdata($id_user_tmp);
        $url_id_user = $id_user_tmp;
    }
    if (!empty($_REQUEST['sc'])) {
        $sc = $_REQUEST['sc'];
    } else {
        $sc = 'setting';
    }
    
?>
    <div class="container">
        <h1 class="page-title">
            <?php st_the_language('account_settings') ?>
        </h1>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <aside class="user-profile-sidebar">
                    <div class="user-profile-avatar text-center">
                        <?php echo st_get_profile_avatar($current_user->ID, 300); ?>
                        <h5><?php echo esc_html($current_user->display_name) ?></h5>

                        <p><?php echo st_get_language('user_member_since') . mysql2date(' M Y', $current_user->data->user_registered); ?></p>
                    </div>
                    <ul class="list user-profile-nav">
                        <?php
                            if (empty($_REQUEST['id_user'])) {
                                ?>
                                <li <?php if ($sc == 'overview') echo 'class="active"' ?>>
                                    <a href="<?php echo esc_url(add_query_arg('sc', 'overview',$user_link));

                                    ?>"><i class="fa fa-user"></i><?php st_the_language('user_overview') ?></a>
                                </li>
                                <li <?php if ($sc == 'setting') echo 'class="active"' ?>>
                                    <a href="<?php echo esc_url(add_query_arg('sc', 'setting',$user_link)) ?>"><i
                                            class="fa fa-cog"></i><?php st_the_language('user_settings') ?></a>
                                </li>
                                <!--<li <?php /*if($sc == 'photos')echo 'class="active"' */
                                ?>>
                                <a href="<?php /*echo get_permalink().'&sc=photos' */
                                ?>"><i class="fa fa-camera"></i><?php /*st_the_language('user_my_travel_photos') */
                                ?></a>
                            </li>-->
                                <li <?php if ($sc == 'booking-history') echo 'class="active"' ?>>
                                    <a href="<?php echo esc_url(add_query_arg('sc', 'booking-history',$user_link)) ?>"><i
                                            class="fa fa-clock-o"></i><?php st_the_language('user_booking_history') ?>
                                    </a>
                                </li>
                                <li <?php if ($sc == 'wishlist') echo 'class="active"' ?>>
                                    <a href="<?php echo esc_url(add_query_arg('sc', 'wishlist',$user_link)) ?>"><i
                                            class="fa fa-heart-o"></i><?php st_the_language('user_wishlist') ?></a>
                                </li>

                                <?php if (STUser_f::check_lever_partner($lever[0]) and st()->get_option('partner_enable_feature') == 'on'): ?>
                                   
                                            <li <?php if ($sc == 'reports') echo 'class="active"' ?>>
                                                <a href="<?php echo esc_url(add_query_arg('sc', 'reports',$user_link)) ?>"><i class="fa fa-book"></i><?php _e("Reports",ST_TEXTDOMAIN) ?>
                                                </a>
                                            </li>
                                            <?php $df = array(
                                                array(
                                                    'title'      => 'Hotel',
                                                    'id_partner' => 'hotel',
                                                ),
                                                array(
                                                    'title'      => 'Rental',
                                                    'id_partner' => 'rental',
                                                ),
                                                array(
                                                    'title'      => 'Car',
                                                    'id_partner' => 'car',
                                                ),
                                                array(
                                                    'title'      => 'Tour',
                                                    'id_partner' => 'tour',
                                                ),
                                                array(
                                                    'title'      => 'Activity',
                                                    'id_partner' => 'activity',
                                                ),
                                            )
                                            ?>
                                            <?php $list_partner = st()->get_option('list_partner', $df); ?>
                                            <?php foreach ($list_partner as $k => $v): ?>
                                                <?php if ($v['id_partner'] == 'hotel' && st_check_service_available('st_hotel')): ?>
                                                <li class="menu cursor menu_partner">
                                                    <a  class=" cursor" style="cursor: pointer !important"><i class="fa fa-building-o"></i><?php _e('Hotel',ST_TEXTDOMAIN) ?> <i
                                                class="icon_partner fa fa-angle-right"></i></a>
                                                    <ul class="list user-profile-nav sub_partner" style="display: none">
                                                        <li <?php if ($sc == 'create-hotel') echo 'class="active"' ?>>
                                                            <a href="<?php echo esc_url(add_query_arg('sc', 'create-hotel')) ?>"><i class="fa fa-building-o"></i><?php st_the_language('user_create_hotel') ?>
                                                            </a>
                                                        </li>
                                                        <li <?php if ($sc == 'my-hotel') echo 'class="active"' ?>>
                                                            <a href="<?php echo esc_url(add_query_arg('sc', 'my-hotel')) ?>"><i class="fa fa-building-o"></i><?php st_the_language('user_my_hotel') ?>
                                                            </a>
                                                        </li>
                                                        <li <?php if ($sc == 'create-room') echo 'class="active"' ?>>
                                                            <a href="<?php echo esc_url(add_query_arg('sc', 'create-room')) ?>"><i class="fa fa-hotel"></i><?php st_the_language('user_create_room') ?>
                                                            </a>
                                                        </li>
                                                        <li <?php if ($sc == 'my-room') echo 'class="active"' ?>>
                                                            <a href="<?php echo esc_url(add_query_arg('sc', 'my-room')) ?>"><i class="fa fa-hotel"></i><?php st_the_language('user_my_room') ?>
                                                            </a>
                                                        </li>
                                                        <li <?php if ($sc == 'booking-hotel') echo 'class="active"' ?>>
                                                            <a href="<?php echo esc_url(add_query_arg('sc', 'booking-hotel')) ?>"><i class="fa fa-building-o"></i><?php _e("Booking Hotel",ST_TEXTDOMAIN) ?>
                                                            </a>
                                                        </li>
                                                        <?php 
                                                            if(isset($lever[0]) && $lever[0] == 'partner'):
                                                        ?>
                                                            <li <?php if($sc == 'add-hotel-booking') echo 'class="active"'; ?>>
                                                                <a href="<?php echo esc_url(add_query_arg('sc', 'add-hotel-booking')) ?>">
                                                                    <i class="fa fa-building-o"></i><?php _e("Add New Booking",ST_TEXTDOMAIN) ?>
                                                                </a>
                                                            </li>
                                                        <?php endif; ?>
                                                    </ul>
                                                </li>        
                                                <?php endif; ?>

                                                <?php if ($v['id_partner'] == 'tour' && st_check_service_available('st_tour')): ?>
                                                <li class="menu cursor menu_partner">
                                                    <a  class=" cursor" style="cursor: pointer !important"><i
                                                                    class="fa fa-flag-o"></i><?php _e('Tour',ST_TEXTDOMAIN) ?> <i
                                                class="icon_partner fa fa-angle-right"></i></a>
                                                    <ul class="list user-profile-nav sub_partner" style="display: none">
                                                        <li <?php if ($sc == 'create-tours') echo 'class="active"' ?>>
                                                            <a href="<?php echo esc_url(add_query_arg('sc', 'create-tours')) ?>"><i
                                                                    class="fa fa-flag-o"></i><?php st_the_language('user_create_tour') ?>
                                                            </a>
                                                        </li>
                                                        <li <?php if ($sc == 'my-tours') echo 'class="active"' ?>>
                                                            <a href="<?php echo esc_url(add_query_arg('sc', 'my-tours')) ?>"><i
                                                                    class="fa fa-flag-o"></i><?php st_the_language('user_my_tour') ?>
                                                            </a>
                                                        </li>
                                                        <li <?php if ($sc == 'booking-tours') echo 'class="active"' ?>>
                                                            <a href="<?php echo esc_url(add_query_arg('sc', 'booking-tours')) ?>"><i class="fa fa-flag-o"></i><?php _e("Tour Bookings",ST_TEXTDOMAIN) ?>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </li>        
                                                <?php endif; ?>

                                                <?php if ($v['id_partner'] == 'activity' && st_check_service_available('st_activity')): ?>
                                                <li class="menu cursor menu_partner">
                                                    <a  class=" cursor" style="cursor: pointer !important"><i
                                                                    class="fa fa-bolt"></i><?php _e('Activity',ST_TEXTDOMAIN) ?> <i
                                                class="icon_partner fa fa-angle-right"></i></a>
                                                    <ul class="list user-profile-nav sub_partner" style="display: none">
                                                        <li <?php if ($sc == 'create-activity') echo 'class="active"' ?>>
                                                            <a href="<?php echo esc_url(add_query_arg('sc', 'create-activity')) ?>"><i
                                                                    class="fa fa-bolt"></i><?php st_the_language('user_create_activity') ?>
                                                            </a>
                                                        </li>
                                                        <li <?php if ($sc == 'my-activity') echo 'class="active"' ?>>
                                                            <a href="<?php echo esc_url(add_query_arg('sc', 'my-activity')) ?>"><i
                                                                    class="fa fa-bolt"></i><?php st_the_language('user_my_activity') ?>
                                                            </a>
                                                        </li>
                                                        <li <?php if ($sc == 'booking-activity') echo 'class="active"' ?>>
                                                            <a href="<?php echo esc_url(add_query_arg('sc', 'booking-activity')) ?>"><i class="fa fa-bolt"></i><?php _e("Activity Bookings",ST_TEXTDOMAIN) ?>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </li>        
                                                <?php endif; ?>

                                                <?php if ($v['id_partner'] == 'car' && st_check_service_available('st_cars') ): ?>
                                                    <li class="menu cursor menu_partner">
                                                    <a  class=" cursor" style="cursor: pointer !important"><i
                                                                    class="fa fa-cab"></i><?php _e('Car',ST_TEXTDOMAIN) ?> <i
                                                class="icon_partner fa fa-angle-right"></i></a>
                                                    <ul class="list user-profile-nav sub_partner" style="display: none">
                                                        <li <?php if ($sc == 'create-cars') echo 'class="active"' ?>>
                                                            <a href="<?php echo esc_url(add_query_arg('sc', 'create-cars')) ?>"><i
                                                                    class="fa fa-cab"></i><?php st_the_language('user_create_car') ?>
                                                            </a>
                                                        </li>
                                                        <li <?php if ($sc == 'my-cars') echo 'class="active"' ?>>
                                                            <a href="<?php echo esc_url(add_query_arg('sc', 'my-cars')) ?>"><i
                                                                    class="fa fa-cab"></i><?php st_the_language('user_my_car') ?>
                                                            </a>
                                                        </li>
                                                        <li <?php if ($sc == 'booking-cars') echo 'class="active"' ?>>
                                                            <a href="<?php echo esc_url(add_query_arg('sc', 'booking-cars')) ?>"><i class="fa fa-cab"></i><?php _e("Car Bookings",ST_TEXTDOMAIN) ?>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </li>        
                                                <?php endif; ?>

                                                <?php if ($v['id_partner'] == 'rental' && st_check_service_available('st_rental')): ?>
                                                <li class="menu cursor menu_partner">
                                                    <a  class=" cursor" style="cursor: pointer !important"><i
                                                                    class="fa fa-home"></i></i><?php _e('Rental',ST_TEXTDOMAIN) ?> <i
                                                class="icon_partner fa fa-angle-right"></i></a>
                                                    <ul class="list user-profile-nav sub_partner" style="display: none">
                                                        <li <?php if ($sc == 'create-rental') echo 'class="active"' ?>>
                                                            <a href="<?php echo esc_url(add_query_arg('sc', 'create-rental')) ?>"><i
                                                                    class="fa fa-home"></i><?php st_the_language('user_create_rental') ?>
                                                            </a>
                                                        </li>
                                                        <li <?php if ($sc == 'my-rental') echo 'class="active"' ?>>
                                                            <a href="<?php echo esc_url(add_query_arg('sc', 'my-rental')) ?>"><i
                                                                    class="fa fa-home"></i><?php st_the_language('user_my_rental') ?>
                                                            </a>
                                                        </li>
                                                        <li <?php if ($sc == 'create-room-rental') echo 'class="active"' ?>>
                                                            <a href="<?php echo esc_url(add_query_arg('sc', 'create-room-rental')) ?>"><i class="fa fa-hotel"></i><?php echo __('Add new Rental Room', ST_TEXTDOMAIN); ?>
                                                            </a>
                                                        </li>
                                                        <li <?php if ($sc == 'my-room-rental') echo 'class="active"' ?>>
                                                            <a href="<?php echo esc_url(add_query_arg('sc', 'my-room-rental')) ?>"><i class="fa fa-hotel"></i><?php echo __('My Rental Room', ST_TEXTDOMAIN); ?>
                                                            </a>
                                                        </li>
                                                        <li <?php if ($sc == 'booking-rental') echo 'class="active"' ?>>
                                                            <a href="<?php echo esc_url(add_query_arg('sc', 'booking-rental')) ?>"><i class="fa fa-home"></i><?php _e("Rental Bookings/Reservations",ST_TEXTDOMAIN) ?>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </li>        
                                                <?php endif; ?>

                                            <?php endforeach; ?>





                                            <!--<li <?php /*if($sc == 'create-cruise')echo 'class="active"' */ ?>>
                                            <a href="<?php /*echo add_query_arg('sc','create-cruise') */ ?>"><i class="fa fa-ship"></i><?php /*st_the_language('user_create_cruise') */ ?></a>
                                        </li>
                                        <li <?php /*if($sc == 'my-cruise')echo 'class="active"' */ ?>>
                                            <a href="<?php /*echo add_query_arg('sc','my-cruise') */ ?>"><i class="fa fa-ship"></i><?php /*st_the_language('user_my_cruise') */ ?></a>
                                        </li>
                                        <li <?php /*if($sc == 'create-cruise-cabin')echo 'class="active"' */ ?>>
                                            <a href="<?php /*echo add_query_arg('sc','create-cruise-cabin') */ ?>"><i class="fa fa-ship"></i><?php /*st_the_language('user_create_cruise_cabin') */ ?></a>
                                        </li>
                                        <li <?php /*if($sc == 'my-cruise-cabin')echo 'class="active"' */ ?>>
                                            <a href="<?php /*echo add_query_arg('sc','my-cruise-cabin') */ ?>"><i class="fa fa-ship"></i><?php /*st_the_language('user_my_cruise_cabin') */ ?></a>
                                        </li>-->
                                <?php endif ?>
                            <?php } else { ?>
                                <li <?php if ($sc == 'setting-info') echo 'class="active"' ?>>
                                    <a href="<?php echo esc_url(add_query_arg(array('sc'=>'setting-info','id_user'=>$url_id_user),$user_link));?>"><i
                                            class="fa fa-cog"></i><?php st_the_language('user_settings') ?>
                                    </a>
                                </li>
                            <?php } ?>
                    </ul>
                </aside>
            </div>
            <div class="col-md-9">
                <?php
                    if (!empty($_REQUEST['sc'])) {
                        $sc = $_REQUEST['sc'];
                    } else {
                        $sc = 'setting';
                    }
                    if (!empty($_REQUEST['id_user'])) {
                        echo st()->load_template('user/user', 'setting-info', get_object_vars($current_user));
                    } else {
                        echo st()->load_template('user/user', $sc, get_object_vars($current_user));
                    }
                ?>
            </div>
        </div>
    </div>
    <div class="gap"></div>
<?php
    get_footer();
?>