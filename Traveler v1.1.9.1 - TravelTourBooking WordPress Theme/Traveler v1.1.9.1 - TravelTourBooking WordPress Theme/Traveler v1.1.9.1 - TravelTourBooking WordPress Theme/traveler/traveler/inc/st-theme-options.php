<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Custom option theme option
 *
 * Created by ShineTheme
 *
 */
if(!class_exists( 'TravelHelper' ) or !class_exists('STHotel'))
    return;



$custom_settings = array(

    'sections' => array(
        array(
            'id'    => 'option_general' ,
            'title' => __( '<i class="fa fa-tachometer"></i> General Options' , ST_TEXTDOMAIN )
        ) ,
        array(
            'id'    => 'option_header' ,
            'title' => __( '<i class="fa fa-header"></i> Header Options' , ST_TEXTDOMAIN )
        ) ,
        array(
            'id'    => 'option_bc' ,
            'title' => __( '<i class="fa fa-tachometer"></i> Breadcrumb Options' , ST_TEXTDOMAIN )
        ) ,
        array(
            'id'    => 'option_style' ,
            'title' => __( '<i class="fa fa-paint-brush"></i> Styling Options' , ST_TEXTDOMAIN )
        ) ,
        array(
            'id'    => 'option_featured' ,
            'title' => __( '<i class="fa fa-flag-checkered"></i> Featured Options' , ST_TEXTDOMAIN )
        ) ,
        array(
            'id'    => 'option_blog' ,
            'title' => __( '<i class="fa fa-bold"></i> Blog Options' , ST_TEXTDOMAIN )
        ) ,
        array(
            'id'    => 'option_page' ,
            'title' => __( '<i class="fa fa-file-text"></i> Page Options' , ST_TEXTDOMAIN )
        ) ,
        array(
            'id'    => 'option_booking' ,
            'title' => __( '<i class="fa fa-book"></i> Booking Options' , ST_TEXTDOMAIN )
        ) ,
        array(
            'id'    => 'option_woo_checkout' ,
            'title' => __( '<i class="fa fa-book"></i> Woocommerce Checkout' , ST_TEXTDOMAIN )
        ) ,
        array(
            'id'    => 'option_tax' ,
            'title' => __( '<i class="fa fa-exchange"></i> Tax Options' , ST_TEXTDOMAIN )
        ) ,
        array(
            'id'          => 'option_location',
           'title'       => __( '<i class="fa fa-location-arrow"></i> Location Options', ST_TEXTDOMAIN )
        ),
        array(
            'id'    => 'option_review' ,
            'title' => __( '<i class="fa fa-comments-o"></i> Review Options' , ST_TEXTDOMAIN )
        ) ,
        array(
            'id'    => 'option_hotel' ,
            'title' => __( '<i class="fa fa-building"></i> Hotel Options' , ST_TEXTDOMAIN )
        ) ,
        array(
            'id'    => 'option_rental' ,
            'title' => __( '<i class="fa fa-home"></i> Rental Options' , ST_TEXTDOMAIN )
        ) ,
        array(
            'id'    => 'option_car' ,
            'title' => __( '<i class="fa fa-car"></i> Car Options' , ST_TEXTDOMAIN )
        ) ,
        //        array(
        //            'id'          => 'option_cruise',
        //            'title'       => __( 'Cruise Options', ST_TEXTDOMAIN )
        //        ),
        array(
            'id'    => 'option_activity_tour' ,
            'title' => __( '<i class="fa fa-suitcase"></i> Tour Options' , ST_TEXTDOMAIN )
        ) ,
        array(
            'id'    => 'option_activity' ,
            'title' => __( '<i class="fa fa-ticket"></i> Activity Options' , ST_TEXTDOMAIN )
        ) ,
        array(
            'id'    => 'option_partner' ,
            'title' => __( '<i class="fa fa-users"></i> Partner Options' , ST_TEXTDOMAIN )
        ) ,
        array(
            'id'    => 'option_search' ,
            'title' => __( '<i class="fa fa-search"></i> Search Options' , ST_TEXTDOMAIN )
        ) ,
        array(
            'id'    => 'option_email' ,
            'title' => __( '<i class="fa fa-envelope"></i> Email Options' , ST_TEXTDOMAIN )
        ) ,
        array(
            'id'    => 'option_email_template' ,
            'title' => __( '<i class="fa fa-envelope"></i> Email Templates' , ST_TEXTDOMAIN )
        ) ,
        array(
            'id'    => 'option_social' ,
            'title' => __( '<i class="fa fa-facebook-official"></i> Social Options' , ST_TEXTDOMAIN )
        ) ,
        array(
            'id'    => 'option_404' ,
            'title' => __( '<i class="fa fa-exclamation-triangle"></i> 404 Options' , ST_TEXTDOMAIN )
        ) ,
//        array(
//            'id'    => 'option_shop' ,
//            'title' => __( '<i class="fa fa-shopping-cart"></i> Shop Options' , ST_TEXTDOMAIN )
//        ) ,
        //        array(
        //            'id'          => 'option_recaptcha',
        //            'title'       => __( 'reCAPTCHA Options', ST_TEXTDOMAIN )
        //        ),
        array(
            'id'    => 'option_advance' ,
            'title' => __( '<i class="fa fa-cogs"></i> Advance Options' , ST_TEXTDOMAIN )
        )
    ) ,
    'settings' => array(
        array(
            'id'      => 'st_text_featured' ,
            'label'   => __( 'Text Featured' , ST_TEXTDOMAIN ) ,
            'desc'    => 'Text Featured' ,
            'type'    => 'text' ,
            'section' => 'option_featured' ,
            'class'   => '' ,
            'std'     => 'Featured'
        ) ,
        array(
            'id'      => 'st_text_featured_color' ,
            'label'   => __( 'Text Color' , ST_TEXTDOMAIN ) ,
            'desc'    => 'Text Color' ,
            'type'    => 'colorpicker' ,
            'section' => 'option_featured' ,
            'class'   => '' ,
            'std'     => '#fff' ,
        ) ,
        array(
            'id'      => 'st_text_featured_bg' ,
            'label'   => __( 'Background color' , ST_TEXTDOMAIN ) ,
            'desc'    => 'Background color' ,
            'type'    => 'colorpicker' ,
            'section' => 'option_featured' ,
            'class'   => '' ,
            'std'     => '#19A1E5' ,
        ) ,
        array(
            'id'      => 'gen_enable_smscroll' ,
            'label'   => __( 'Enable Nice Scroll' , ST_TEXTDOMAIN ) ,
            'desc'    => __( 'This allows you to turn on or off <em>Nice Scroll Effect</em>' , ST_TEXTDOMAIN ) ,
            'type'    => 'on-off' ,
            'section' => 'option_general' ,
            'std'     => 'off'

        ) , 
        array(
            'id'      => 'favicon' ,
            'label'   => __( 'Favicon' , ST_TEXTDOMAIN ) ,
            'desc'    => __( 'This allows you to change favicon of your website' , ST_TEXTDOMAIN ) ,
            'type'    => 'upload' ,
            'section' => 'option_general' ,
            'std'     => get_template_directory_uri() . '/img/favicon.png'
        )
        ,
        array(
            'id'      => 'logo' ,
            'label'   => __( 'Logo' , ST_TEXTDOMAIN ) ,
            'desc'    => __( 'This allows you to change logo' , ST_TEXTDOMAIN ) ,
            'type'    => 'upload' ,
            'section' => 'option_general' ,
            'std'     => get_template_directory_uri() . '/img/logo.png'
        ) ,
        array(
            'id'      => 'logo_retina' ,
            'label'   => __( 'Logo Retina' , ST_TEXTDOMAIN ) ,
            'desc'    => __( 'Note: You MUST re-name Logo Retina to logo-name@2x.ext-name. Example:<br>
                                    Logo is: <em>my-logo.jpg</em><br>Logo Retina must be: <em>my-logo@2x.jpg</em>  ' , ST_TEXTDOMAIN ) ,
            'type'    => 'upload' ,
            'section' => 'option_general' ,
            'std'     => get_template_directory_uri() . '/img/logo@2x.png'
        ) ,
        array(
            'id'      => 'st_seo_option' ,
            'label'   => __( 'SEO options' , ST_TEXTDOMAIN ) ,
            'desc'    => '' ,
            'std'     => '' ,
            'type'    => 'on-off' ,
            'section' => 'option_general' ,
            'class'   => '' ,
        ) ,
        array(
            'id'        => 'st_seo_title' ,
            'label'     => __( 'SEO Title' , ST_TEXTDOMAIN ) ,
            'desc'      => '' ,
            'std'       => '' ,
            'type'      => 'text' ,
            'section'   => 'option_general' ,
            'class'     => '' ,
            'condition' => 'st_seo_option:is(on)' ,
        ) ,
        array(
            'id'        => 'st_seo_desc' ,
            'label'     => __( 'SEO Description' , ST_TEXTDOMAIN ) ,
            'desc'      => '' ,
            'std'       => '' ,
            'rows'      => '5' ,
            'type'      => 'textarea-simple' ,
            'section'   => 'option_general' ,
            'class'     => '' ,
            'condition' => 'st_seo_option:is(on)' ,
        ) ,
        array(
            'id'        => 'st_seo_keywords' ,
            'label'     => __( 'SEO Keywords' , ST_TEXTDOMAIN ) ,
            'desc'      => '' ,
            'std'       => '' ,
            'rows'      => '5' ,
            'type'      => 'textarea-simple' ,
            'section'   => 'option_general' ,
            'class'     => '' ,
            'condition' => 'st_seo_option:is(on)' ,
        ) ,
        array(
            'id'      => 'footer_template' ,
            'label'   => __( 'Page for footer' , ST_TEXTDOMAIN ) ,
            'type'    => 'page-select' ,
            'section' => 'option_general' ,
        ) ,
        array(
            'id'      => 'enable_user_online_noti' ,
            'label'   => __( 'Enable User Online Notification' , ST_TEXTDOMAIN ) ,
            'type'    => 'on-off' ,
            'section' => 'option_general' ,
            'std'     => 'on'
        ) ,
        array(
            'id'      => 'enable_last_booking_noti' ,
            'label'   => __( 'Enable Last Booking Notification' , ST_TEXTDOMAIN ) ,
            'type'    => 'on-off' ,
            'section' => 'option_general' ,
            'std'     => 'on'
        ) ,
        array(
            'id'      => 'enable_user_nav' ,
            'label'   => __( 'Enable User Navigator' , ST_TEXTDOMAIN ) ,
            'type'    => 'on-off' ,
            'section' => 'option_general' ,
            'std'     => 'on'
        ) ,        
        array(
            'id'      => 'admin_menu_normal_user' ,
            'label'   => __( 'Enable normal user admin bar' , ST_TEXTDOMAIN ) ,
            'type'    => 'on-off' ,
            'section' => 'option_general' ,
            'std'     => 'off'
        ) ,
        array(
            'id'      => 'noti_position' ,
            'label'   => __( 'Notification Position' , ST_TEXTDOMAIN ) ,
            'type'    => 'select' ,
            'section' => 'option_general' ,
            'std'     => 'topRight' ,
            'choices' => array(
                array(
                    'label' => __( 'Top Right' , ST_TEXTDOMAIN ) ,
                    'value' => 'topRight'
                ) ,
                array(
                    'label' => __( 'Top Left' , ST_TEXTDOMAIN ) ,
                    'value' => 'topLeft'
                ) ,
                array(
                    'label' => __( 'Bottom Right' , ST_TEXTDOMAIN ) ,
                    'value' => 'bottomRight'
                ) ,
                array(
                    'label' => __( 'Bottom Left' , ST_TEXTDOMAIN ) ,
                    'value' => 'bottomLeft'
                )
            ) ,
        ) ,
        array(
            'id'      => 'once_notification_per_each_session' ,
            'label'   => __( 'Only show notification once per each session?' , ST_TEXTDOMAIN ) ,
            'type'    => 'on-off' ,
            'section' => 'option_general' ,
            'std'     => 'off'
        ),
        array(
            'id'      => 'st_weather_temp_unit' ,
            'label'   => __( 'Weather Temp Unit' , ST_TEXTDOMAIN ) ,
            'type'    => 'select' ,
            'section' => 'option_general' ,
            'std'     => 'c' ,
            'choices' => array(
                array(
                    'label' => __( 'Fahrenheit (f)' , ST_TEXTDOMAIN ) ,
                    'value' => 'f'
                ) ,
                array(
                    'label' => __( 'Celsius (c)' , ST_TEXTDOMAIN ) ,
                    'value' => 'c'
                ) ,
                array(
                    'label' => __( 'Kelvin (k)' , ST_TEXTDOMAIN ) ,
                    'value' => 'k'
                ) ,
            ) ,
        ),
        array(
            'id'=>'list_disabled_feature',
            'label'=>__('Disable Theme Service',ST_TEXTDOMAIN),
            'type'=>'checkbox',
            'section'=>'option_general',
            'choices' => array(
                array(
                    'label' => __( 'Hotel' , ST_TEXTDOMAIN ) ,
                    'value' => 'st_hotel'
                ) ,
                array(
                    'label' => __( 'Car' , ST_TEXTDOMAIN ) ,
                    'value' => 'st_cars'
                ) ,
                array(
                    'label' => __( 'Rental' , ST_TEXTDOMAIN ) ,
                    'value' => 'st_rental'
                ) ,
                array(
                    'label' => __( 'Tour' , ST_TEXTDOMAIN ) ,
                    'value' => 'st_tours'
                ) ,
                array(
                    'label' => __( 'Activity' , ST_TEXTDOMAIN ) ,
                    'value' => 'st_activity'
                ) ,
            ) ,
        ),
        /*------------- heaader options ------------*/
        /*array(
            'id'      => 'top_bar' ,
            'label'   => __( 'Top bar' , ST_TEXTDOMAIN ) ,
            'type'    => 'tab' ,
            'section' => 'option_header' ,
        ) ,
        array(
            'id'      => 'enable_topbar' ,
            'label'   => __( 'Enable Top bar' , ST_TEXTDOMAIN ) ,
            'desc'    => __( 'On to Enable Top bar ' , ST_TEXTDOMAIN ) ,
            'type'    => 'on-off' ,
            'section' => 'option_header' , 
            'std'     => 'off', 
        ),

        array(
            'id'      => 'gen_enable_sticky_topbar' ,
            'label'   => __( 'Enable Sticky Top bar' , ST_TEXTDOMAIN ) ,
            'desc'    => __( 'This allows you to turn on or off <em>Sticky Top bar</em>' , ST_TEXTDOMAIN ) ,
            'type'    => 'on-off' ,
            'condition' => 'enable_topbar:is(on)',
            'section' => 'option_header' ,
            'std'     => 'off'
        ) , 
        array(
            'id'      => 'topbar_left' ,
            'label'   => __( 'Left top bar' , ST_TEXTDOMAIN ) ,
            'desc'    => __( 'Select left top bar content' , ST_TEXTDOMAIN ) ,
            'type'    => 'select' ,
            'condition' => 'enable_topbar:is(on)',
            'section' => 'option_header' ,
            'choices'   => array(
                array('label' => __("Custom Text" ,ST_TEXTDOMAIN) , 'value'=> "text"),
                array('label' => __("Menu select" ,ST_TEXTDOMAIN) , 'value'=> "menu"),                
                ),
            'std'     => 'text', 
        ),
        array(
            'id'      => 'topbar_left_text' ,
            'label'   => __( 'Top bar left text' , ST_TEXTDOMAIN ) ,
            'desc'    => __( 'Left Text for top bar' , ST_TEXTDOMAIN )."<br> Type : [admin_email] get admin email  <br> Type : [languages_select] get languages select <br> Type : [login_select] get login dropdown <br> Type : [currency_select] get currence change select  "  ,
            'type'    => 'textarea-simple' ,
            'section' => 'option_header' ,
            'condition' => 'topbar_left:is(text),enable_topbar:is(on)',
            'std'     => ' ', 
            ),
        array(
            'id'      => 'topbar_left_menu' ,
            'label'   => __( 'Select Menu' , ST_TEXTDOMAIN ) ,
            'type'    => 'select' ,
            'section' => 'option_header' ,
            'condition' => 'topbar_left:is(menu),enable_topbar:is(on)',
            'std'     => '', 
            'choices' => TravelHelper::get_opt_menus()
            ),

        array(
            'id'      => 'topbar_right' ,
            'label'   => __( 'Right top bar' , ST_TEXTDOMAIN ) ,
            'desc'    => __( 'Select right top bar content' , ST_TEXTDOMAIN ),
            'type'    => 'select' ,
            'condition' => 'enable_topbar:is(on)',
            'section' => 'option_header' ,
            'choices'   => array(
                array('label' => __("Custom Text" ,ST_TEXTDOMAIN) , 'value'=> "text"),
                array('label' => __("Menu select" ,ST_TEXTDOMAIN) , 'value'=> "menu"),
                
                ),
            'std'     => 'text', 
        ),

        array(
            'id'      => 'topbar_right_text' ,
            'label'   => __( 'Top bar right text' , ST_TEXTDOMAIN ) ,
            'desc'    => __( 'Left Text for top bar' , ST_TEXTDOMAIN )."<br> Type : [admin_email] get admin email  <br> Type : [languages_select] get languages select <br> Type : [login_select] get login dropdown <br> Type : [currency_select] get currence change select  "  ,
            'type'    => 'textarea-simple' ,
            'section' => 'option_header' ,
            'condition' => 'topbar_right:is(text),enable_topbar:is(on)',
            'std'     => ' ', 
            ),
        array(
            'id'      => 'topbar_right_menu' ,
            'label'   => __( 'Select Menu' , ST_TEXTDOMAIN ) ,
            'type'    => 'select' ,
            'section' => 'option_header' ,
            'condition' => 'topbar_right:is(menu),enable_topbar:is(on)',
            'std'     => '', 
            'choices' => TravelHelper::get_opt_menus()
            ),*/
        array(
            'id'      => 'menu_bar' ,
            'label'   => __( 'Menu' , ST_TEXTDOMAIN ) ,
            'type'    => 'tab' ,
            'section' => 'option_header' ,
        ),
        /*array(
            'id'      => 'menu_style' ,
            'label'   => __( 'Menu style' , ST_TEXTDOMAIN ) ,
            'desc'    => __( 'Select menu style' , ST_TEXTDOMAIN ) ,
            'type'    => 'radio-image' ,
            'section' => 'option_header' , 
            'std'   =>'1',
            'choices' => array(
                array(
                        'value'   => '1',
                        'label'   => __(  'Style 1' , ST_TEXTDOMAIN ),
                        'src'     => get_template_directory_uri().'/img/nav1.png'
                    ),
                    array(
                        'value'   => '2',
                        'label'   => __(  'Style 2' , ST_TEXTDOMAIN ),
                        'src'     => get_template_directory_uri().'/img/nav2.png'
                    )
                )
        ),*/
         
        array(
            'id'      => 'header_transparent' ,
            'label'   => __( 'Header Transparent' , ST_TEXTDOMAIN ) ,
            'type'    => 'on-off' ,
            'section' => 'option_header' ,
            'output'  => '' ,
            'std'     => 'off'
        ) ,
        array(
            'id'      => 'gen_enable_sticky_header' ,
            'label'   => __( 'Enable Sticky Header' , ST_TEXTDOMAIN ) ,
            'desc'    => __( 'This allows you to turn on or off <em>Sticky Header Feature</em>' , ST_TEXTDOMAIN ) ,
            'type'    => 'on-off' ,
            'section' => 'option_header' ,
            'std'     => 'off'
        ) ,
        array(
            'id'      => 'gen_enable_sticky_menu' ,
            'label'   => __( 'Enable Sticky Menu' , ST_TEXTDOMAIN ) ,
            'desc'    => __( 'This allows you to turn on or off <em>Sticky Menu Feature</em>' , ST_TEXTDOMAIN ) ,            
            'type'    => 'on-off' ,
            'section' => 'option_header' ,
            'std'     => 'off'
        ) ,    


        /*--------------Begin Tax Options------------*/
        
        array(
            'id'      => 'tax_enable' ,
            'label'   => __( 'Enable Tax' , ST_TEXTDOMAIN ) ,
            'desc'    => __( 'Enable tax calculations' , ST_TEXTDOMAIN ) ,
            'type'    => 'on-off' ,
            'section' => 'option_tax' ,
            'std'     => 'off'
        )
        ,
        array(
            'id'      => 'st_tax_include_enable' ,
            'label'   => __( 'Include tax in listing page' , ST_TEXTDOMAIN ) ,
            'desc'    => __( 'Include tax in listing page' , ST_TEXTDOMAIN ) ,
            'type'    => 'on-off' ,
            'section' => 'option_tax' ,
            'condition'    => 'tax_enable:is(on)' ,
            'std'     => 'off'
        )
        ,
        array(
            'id'           => 'tax_value' ,
            'label'        => __( 'Tax percentage' , ST_TEXTDOMAIN ) ,
            'desc'         => __( 'Tax percentage' , ST_TEXTDOMAIN ) ,
            'type'         => 'numeric-slider' ,
            'section'      => 'option_tax' ,
            'min_max_step' => '0,100,1' ,
            'condition'    => 'tax_enable:is(on)' ,
            'std'          => 10
        ),
        /*--------------End Tax Options------------*/
        /*--------------Location options ----------*/

        array(
            'id'           => 'location_posts_per_page' ,
            'label'        => __( 'No. posts in Location tab' , ST_TEXTDOMAIN ) ,
            'desc'         => __( 'Default number of posts are shown in Location tab' , ST_TEXTDOMAIN ) ,
            'type'         => 'numeric-slider' ,
            'min_max_step' => '1,15,1' ,
            'section'      => 'option_location' ,
            'std'           =>5
        ),
        array(
            'id'            =>'location_order_by',
            'label'         =>__('Location tab - Order by'),
            'section'      => 'option_location' ,
            'desc'         =>__('Location tab - Order by'),
            'type'          =>'select',
            'std'           =>'ID',
            'choices'   => array(
                array(
                    'value' => 'none' ,
                    'label' => __( 'None' , ST_TEXTDOMAIN )
                ) ,
                array(
                    'value' => 'ID' ,
                    'label' => __( 'ID' , ST_TEXTDOMAIN )
                ) ,
                array(
                    'value' => 'author' ,
                    'label' => __( 'Author' , ST_TEXTDOMAIN )
                ) ,
                array(
                    'value' => 'title' ,
                    'label' => __( 'Title' , ST_TEXTDOMAIN )
                ) ,
                array(
                    'value' => 'name' ,
                    'label' => __( 'Name' , ST_TEXTDOMAIN )
                ) ,
                array(
                    'value' => 'date' ,
                    'label' => __( 'Date' , ST_TEXTDOMAIN )
                ) ,
                array(
                    'value' => 'rand' ,
                    'label' => __( 'Random' , ST_TEXTDOMAIN )
                ) ,
            )
        ) ,
        array(
            'id'        => 'location_order' ,
            'label'     => __( 'Location tab - Order' , ST_TEXTDOMAIN ) ,
            'type'      => 'select' ,
            'section'   => 'option_location' ,
            'label'         =>__('Location tab - Order'),        
            'std'           =>'DESC',
            'choices'   => array(
                array(
                    'value' => 'ASC' ,
                    'label' => __( 'ASC' , ST_TEXTDOMAIN )
                ) ,
                array(
                    'value' => 'DESC' ,
                    'label' => __( 'DESC' , ST_TEXTDOMAIN )
                ) ,
            ) 
        ) ,


        /*--------------End Location options ----------*/

        /*--------------Review Options------------*/
        
        array(
            'id'      => 'review_without_login' ,
            'label'   => __( 'Write reviews without logging in' , ST_TEXTDOMAIN ) ,
            'desc'    => __( '<em>ON:</em> Enable review without logging in <br><em>OFF:Disble review without logging in</em>' , ST_TEXTDOMAIN ) ,
            'section' => 'option_review' ,
            'type'    => 'on-off' ,
            'std'     => 'off'
        ),
        array(
            'id'      => 'review_need_booked' ,
            'label'   => __( 'Only users who booked can review' , ST_TEXTDOMAIN ) ,
            'desc'    => __( '<em>ON:</em> Only user who booked can review<br><em>OFF:</em>Any user can review' , ST_TEXTDOMAIN ) ,
            'section' => 'option_review' ,
            'type'    => 'on-off' ,
            'condition' => 'review_without_login:is(off)' ,
            'std'     => 'on'
        )
        ,
        array(
            'id' => 'review_once',
            'label' => __('Review Once', ST_TEXTDOMAIN),
            'desc' => __('<em>On</em>: review only once <br/> <em>Off</em>: review several times', ST_TEXTDOMAIN),
            'section' => 'option_review',
            'type' => 'on-off',
            'std' => 'off'
        ),
        /*--------------End Review Options------------*/


        /*--------------Blog Options------------*/
        array(
            'id'      => 'blog_sidebar_pos' ,
            'label'   => __( 'Sidebar Position' , ST_TEXTDOMAIN ) ,
            'desc'    => __( 'You can choose No sidebar, Left Sidebar and Right Sidebar' , ST_TEXTDOMAIN ) ,
            'type'    => 'select' ,
            'section' => 'option_blog' ,
            'choices' => array(
                array(
                    'value' => 'no' ,
                    'label' => __( 'No' , ST_TEXTDOMAIN )
                ) ,
                array(
                    'value' => 'left' ,
                    'label' => __( 'Left' , ST_TEXTDOMAIN )
                ) ,
                array(
                    'value' => 'right' ,
                    'label' => __( 'Right' , ST_TEXTDOMAIN )
                )

            ) ,
            'std'     => 'right'
        )
        ,
        array(
            'id'      => 'blog_sidebar_id' ,
            'label'   => __( 'Widget Area' , ST_TEXTDOMAIN ) ,
            'desc'    => __( 'You can choose from the list' , ST_TEXTDOMAIN ) ,
            'type'    => 'sidebar-select' ,
            'section' => 'option_blog' ,
            'std'     => 'blog-sidebar' ,
        ) ,
        /*--------------End Blog Options------------*/
        /*--------------Page Options------------*/
        array(
            'id'      => 'page_my_account_dashboard' ,
            'label'   => __( 'My account dashboard page' , ST_TEXTDOMAIN ) ,
            'type'    => 'page-select' ,
            'section' => 'option_page' ,
        ) ,
        array(
            'id'      => 'page_redirect_to_after_login' ,
            'label'   => __( 'Redirect to after login' , ST_TEXTDOMAIN ) ,
            'type'    => 'page-select' ,
            'section' => 'option_page' ,
        ) ,
        array(
            'id'      => 'page_redirect_to_after_logout' ,
            'label'   => __( 'Redirect to after logout' , ST_TEXTDOMAIN ) ,
            'type'    => 'page-select' ,
            'section' => 'option_page' ,
        ) ,
        array(
            'id'      => 'page_user_login' ,
            'label'   => __( 'User Login' , ST_TEXTDOMAIN ) ,
            'type'    => 'page-select' ,
            'section' => 'option_page' ,
        ) ,
        array(
            'id'      => 'page_checkout' ,
            'label'   => __( 'Checkout Page' , ST_TEXTDOMAIN ) ,
            'type'    => 'page-select' ,
            'section' => 'option_page' ,
        ) ,
        array(
            'id'      => 'page_payment_success' ,
            'label'   => __( 'Payment Success Page' , ST_TEXTDOMAIN ) ,
            'desc'    => __( 'Payment Success or Thank you page' , ST_TEXTDOMAIN ) ,
            'type'    => 'page-select' ,
            'section' => 'option_page' ,
        ) ,
        array(
            'id'      => 'page_order_confirm' ,
            'label'   => __( 'Order Confirmation Page' , ST_TEXTDOMAIN ) ,
            'desc'    => __( 'Order Confirmation Page' , ST_TEXTDOMAIN ) ,
            'type'    => 'page-select' ,
            'section' => 'option_page' ,
        ) ,
        array(
            'id'      => 'page_terms_conditions' ,
            'label'   => __( 'Terms and Conditions Page' , ST_TEXTDOMAIN ) ,
            'desc'    => __( 'Terms and Conditions Page' , ST_TEXTDOMAIN ) ,
            'type'    => 'page-select' ,
            'section' => 'option_page' ,
        ) ,
        /*--------------End Page Options------------*/
        /*------------- Styling Option----------*/


        array(
            'id'      => 'right_to_left' ,
            'label'   => __( 'Right to left' , ST_TEXTDOMAIN ) ,
            'type'    => 'on-off' ,
            'section' => 'option_style' ,
            'output'  => '' ,
            'std'     => 'off'
        ) ,
        array(
            'id'      => 'typography' ,
            'label'   => __( 'Typography' , ST_TEXTDOMAIN ) ,
            'type'    => 'typography' ,
            'section' => 'option_style' ,
            'output'  => 'body'
        ) ,
        array(
            'id'      => 'google_fonts' ,
            'label'   => __( 'Google Fonts' , ST_TEXTDOMAIN ) ,
            'type'    => 'google-fonts' ,
            'section' => 'option_style' ,
        ) ,
        array(
            'id'      => 'star_color' ,
            'label'   => __( 'Star Color' , ST_TEXTDOMAIN ) ,
            'desc'    => __( 'Star Color' , ST_TEXTDOMAIN ) ,
            'type'    => 'colorpicker' ,
            'section' => 'option_style' ,
        ) ,
        array(
            'id'      => 'heading_fonts' ,
            'label'   => __( 'Heading fonts' , ST_TEXTDOMAIN ) ,
            'type'    => 'typography' ,
            'section' => 'option_style' ,
            'output'  => 'h1,h2,h3,h4,h5,.text-hero'
        ) ,
        array(
            'id'      => 'style_layout' ,
            'label'   => __( 'Layout' , ST_TEXTDOMAIN ) ,
            'desc'    => __( 'You can choose Wide or Boxed layout' , ST_TEXTDOMAIN ) ,
            'type'    => 'select' ,
            'section' => 'option_style' ,
            'choices' => array(
                array(
                    'value' => 'wide' ,
                    'label' => __( 'Wide' , ST_TEXTDOMAIN )
                ) ,
                array(
                    'value' => 'boxed' ,
                    'label' => __( 'Boxed' , ST_TEXTDOMAIN )
                )

            )
        ) ,
        array(
            'id'      => 'body_background' ,
            'label'   => __( 'Body Background' , ST_TEXTDOMAIN ) ,
            'desc'    => __( 'Body Background' , ST_TEXTDOMAIN ) ,
            'type'    => 'background' ,
            'section' => 'option_style' ,
            'output'  => 'body'
        ) ,
        array(
            'id'      => 'main_wrap_background' ,
            'label'   => __( 'Main wrap background' , ST_TEXTDOMAIN ) ,
            'desc'    => __( 'Main wrap background' , ST_TEXTDOMAIN ) ,
            'type'    => 'background' ,
            'section' => 'option_style' ,
            'output'  => '.global-wrap'
        ) ,
        array(
            'id'      => 'header_background' ,
            'label'   => __( 'Header background' , ST_TEXTDOMAIN ) ,
            'desc'    => __( 'Header Background' , ST_TEXTDOMAIN ) ,
            'type'    => 'background' ,
            'section' => 'option_style' ,
            'output'  => '.header-top'
        ) ,
        array(
            'id'      => 'style_default_scheme' ,
            'label'   => __( 'Default Color Scheme' , ST_TEXTDOMAIN ) ,
            'desc'    => __( 'Select Default Color Scheme or choose your own main color bellow' , ST_TEXTDOMAIN ) ,
            'type'    => 'select' ,
            'section' => 'option_style' ,
            'output'  => '' ,
            'std'     => '' ,
            'choices' => array(
                array( 'label' => '-- Please Select ---' , 'value' => '' ) ,
                array( 'label' => 'Bright Turquoise' , 'value' => '#0EBCF2' ) ,
                array( 'label' => 'Turkish Rose' , 'value' => '#B66672' ) ,
                array( 'label' => 'Salem' , 'value' => '#12A641' ) ,
                array( 'label' => 'Hippie Blue' , 'value' => '#4F96B6' ) ,
                array( 'label' => 'Mandy' , 'value' => '#E45E66' ) ,
                array( 'label' => 'Green Smoke' , 'value' => '#96AA66' ) ,
                array( 'label' => 'Horizon' , 'value' => '#5B84AA' ) ,
                array( 'label' => 'Cerise' , 'value' => '#CA2AC6' ) ,
                array( 'label' => 'Brick red' , 'value' => '#cf315a' ) ,
                array( 'label' => 'De-York' , 'value' => '#74C683' ) ,
                array( 'label' => 'Shamrock' , 'value' => '#30BBB1' ) ,
                array( 'label' => 'Studio' , 'value' => '#7646B8' ) ,
                array( 'label' => 'Leather' , 'value' => '#966650' ) ,
                array( 'label' => 'Denim' , 'value' => '#1A5AE4' ) ,
                array( 'label' => 'Scarlet' , 'value' => '#FF1D13' ) ,
            )
        ) ,
        array(
            'id'      => 'main_color' ,
            'label'   => __( 'Main Color' , ST_TEXTDOMAIN ) ,
            'desc'    => __( 'Choose your theme\'s main color' , ST_TEXTDOMAIN ) ,
            'type'    => 'colorpicker' ,
            'section' => 'option_style' ,
            'std'     => '#ed8323'
        ) ,
        array(
            'id'      => 'custom_css' ,
            'label'   => __( 'Custom CSS' , ST_TEXTDOMAIN ) ,
            'type'    => 'css' ,
            'section' => 'option_style' ,
        ) ,
        /*------------ Advance Option------------*/
        array(
            'id'      => 'view_star_review' ,
            'label'   => __( 'Enable Hotel Stars or Hotel Review.' , ST_TEXTDOMAIN ) ,
            'desc'    => '' ,
            'type'    => 'select' ,
            'section' => 'option_advance' ,
            'choices' => array(
                array(
                    'label' => __( 'Hotel Stars' , ST_TEXTDOMAIN ) ,
                    'value' => 'star'
                ) ,
                array(
                    'label' => __( 'Hotel Reviews' , ST_TEXTDOMAIN ) ,
                    'value' => 'review'
                )
            ) ,
        ) ,
        array(
            'id'      => 'datetime_format' ,
            'label'   => __( 'Date Format' , ST_TEXTDOMAIN ) ,
            'type'    => 'text' ,
            'std'     => '{mm}/{dd}/{yyyy}' ,
            'section' => 'option_advance' ,
            'desc'    => __( 'The date format, combination of d, dd, m, mm, yy, yyyy. It is surrounded by <code>\'{}\'</code>. Ex: {dd}/{mm}/{yyyy}.
                <ul>
                <li><code>d, dd</code>: Numeric date, no leading zero and leading zero, respectively. Eg, 5, 05.</li>
                <li><code>m, mm</code>: Numeric month, no leading zero and leading zero, respectively. Eg, 7, 07.</li>
                <li><code>M</code>: Abbreviated and full month names, respectively. Eg, Jan, January</li>
                <li><code>yy, yyyy:</code> 2- and 4-digit years, respectively. Eg, 12, 2012.</li>
                </ul>
                ' , ST_TEXTDOMAIN ) ,
        ) ,
        array(
            'id'           => 'update_weather_by' ,
            'label'        => __( 'Weather updates:' , ST_TEXTDOMAIN ) ,
            'type'         => 'numeric-slider' ,
            'min_max_step' => '1,12,1' ,
            'std'          => 12 ,
            'section'      => 'option_advance' ,
            'desc'         => __( 'Weather updates (Unit: hour)' , ST_TEXTDOMAIN ) ,
        ) ,
        array(
            'id'      => 'show_price_free' ,
            'label'   => __( 'Show price when accommodation is free' , ST_TEXTDOMAIN ) ,
            'type'    => 'on-off' ,
            'desc'    => __( 'Price is not shown when accommodation is free' , ST_TEXTDOMAIN ) ,
            'section' => 'option_advance' ,
            'std'     => 'off'
        ) ,
//        array(
//            'id'      => 'adv_compress_html' ,
//            'label'   => __( 'Compress HTML' , ST_TEXTDOMAIN ) ,
//            'desc'    => __( 'This allows you to compress HTML code.' , ST_TEXTDOMAIN ) ,
//            'type'    => 'on-off' ,
//            'section' => 'option_advance' ,
//            'std'     => 'off'
//        )
//        ,
        array(
            'id'      => 'adv_before_body_content' ,
            'label'   => __( 'Before Body Content' , ST_TEXTDOMAIN ) ,
            'desc'    => sprintf( __( 'Right after the opening %s tag.' , ST_TEXTDOMAIN ) , esc_html( '<body>' ) ) ,
            'type'    => 'textarea-simple' ,
            'section' => 'option_advance' ,
            //'std'=>'off'
        )
        ,
        array(
            'id'      => 'edv_enable_demo_mode' ,
            'label'   => __( 'Enable Demo Mode' , ST_TEXTDOMAIN ) ,
            'desc'    => __( 'Do some magical' , ST_TEXTDOMAIN ) ,
            'type'    => 'on-off' ,
            'section' => 'option_advance' ,
            'std'     => 'off' ,
            //'std'=>'off'
        )
        ,
        array(
            'id'      => 'edv_share_code' ,
            'label'   => __( 'Custom Share Code' , ST_TEXTDOMAIN ) ,
            'desc'    => __( 'You you want change the share code in single item. Change this<br><code>[__post_permalink__]</code> for Permalink<br><code>[__post_title__]</code> for Title' , ST_TEXTDOMAIN ) ,
            'type'    => 'textarea-simple' ,
            'section' => 'option_advance' ,
            'std'     => '<li><a href="https://www.facebook.com/sharer/sharer.php?u=[__post_permalink__]&amp;title=[__post_title__]" target="_blank" original-title="Facebook"><i class="fa fa-facebook fa-lg"></i></a></li>
        <li><a href="http://twitter.com/share?url=[__post_permalink__]&amp;title=[__post_title__]" target="_blank" original-title="Twitter"><i class="fa fa-twitter fa-lg"></i></a></li>
        <li><a href="https://plus.google.com/share?url=[__post_permalink__]&amp;title=[__post_title__]" target="_blank" original-title="Google+"><i class="fa fa-google-plus fa-lg"></i></a></li>
        <li><a class="no-open" href="javascript:void((function()%7Bvar%20e=document.createElement(\'script\');e.setAttribute(\'type\',\'text/javascript\');e.setAttribute(\'charset\',\'UTF-8\');e.setAttribute(\'src\',\'http://assets.pinterest.com/js/pinmarklet.js?r=\'+Math.random()*99999999);document.body.appendChild(e)%7D)());" target="_blank" original-title="Pinterest"><i class="fa fa-pinterest fa-lg"></i></a></li>
        <li><a href="http://www.linkedin.com/shareArticle?mini=true&amp;url=[__post_permalink__]&amp;title=[__post_title__]" target="_blank" original-title="LinkedIn"><i class="fa fa-linkedin fa-lg"></i></a></li>'
        ) ,
        /*------------- Booking Option --------------*/

        array(
            'id'        => 'booking_modal' ,
            'label'     => __( 'Booking with Modal' , ST_TEXTDOMAIN ) ,
            'déc'     => __( 'This option only working if you turn off Woocommerce Checkout' , ST_TEXTDOMAIN ) ,
            'type'      => 'on-off' ,
            'std'       => 'off' ,
            'section'   => 'option_booking' ,
        )
        ,
        array(
            'id'      => 'booking_enable_captcha' ,
            'label'   => __( 'Booking Enable Captcha' , ST_TEXTDOMAIN ) ,
            'type'    => 'on-off' ,
            'std'     => 'on' ,
            'section' => 'option_booking' ,
        )
        ,
        array(
            'id'       => 'booking_card_accepted' ,
            'label'    => __( 'Card Accepted' , ST_TEXTDOMAIN ) ,
            'desc'     => __( 'Card Accepted' , ST_TEXTDOMAIN ) ,
            'type'     => 'list-item' ,
            'settings' => array(
                array(
                    'id'    => 'image' ,
                    'label' => __( 'Image' , ST_TEXTDOMAIN ) ,
                    'desc'  => __( 'Image' , ST_TEXTDOMAIN ) ,
                    'type'  => 'upload'
                )
            ) ,
            'std'      => array(
                array(
                    'title' => 'Master Card' ,
                    'image' => get_template_directory_uri() . '/img/card/mastercard.png'
                ) ,
                array(
                    'title' => 'JCB' ,
                    'image' => get_template_directory_uri() . '/img/card/jcb.png'
                ) ,
                array(
                    'title' => 'Union Pay' ,
                    'image' => get_template_directory_uri() . '/img/card/unionpay.png'
                ) ,
                array(
                    'title' => 'VISA' ,
                    'image' => get_template_directory_uri() . '/img/card/visa.png'
                ) ,
                array(
                    'title' => 'American Express' ,
                    'image' => get_template_directory_uri() . '/img/card/americanexpress.png'
                ) ,
            ) ,
            'section'  => 'option_booking' ,
        )
        ,
        array(
            'id'       => 'booking_currency' ,
            'label'    => __( 'Currency List' , ST_TEXTDOMAIN ) ,
            'desc'     => __( 'This allows you to add currency to your website' , ST_TEXTDOMAIN ) ,
            'type'     => 'list-item' ,
            'section'  => 'option_booking' ,
            'settings' => array(
                array(

                    'id'       => 'name' ,
                    'label'    => __( 'Currency Name' , ST_TEXTDOMAIN ) ,
                    'type'     => 'select' ,
                    'operator' => 'and' ,
                    'choices'  => TravelHelper::ot_all_currency()
                ) ,
                array(

                    'id'       => 'symbol' ,
                    'label'    => __( 'Currency Symbol' , ST_TEXTDOMAIN ) ,
                    'type'     => 'text' ,
                    'operator' => 'and'
                ) ,
                array(

                    'id'       => 'rate' ,
                    'label'    => __( 'Exchange rate' , ST_TEXTDOMAIN ) ,
                    'type'     => 'text' ,
                    'operator' => 'and' ,
                    'desc'     => __( 'Exchange rate vs Primary Currency' , ST_TEXTDOMAIN )
                ),
                array(

                    'id'      => 'booking_currency_pos' ,
                    'label'   => __( 'Currency Position' , ST_TEXTDOMAIN ) ,
                    'desc'    => __( 'This controls the position of the currency symbol.<br>Ex: $400 or 400 $' , ST_TEXTDOMAIN ) ,
                    'type'    => 'select' ,
                    'choices' => array(
                        array(
                            'value' => 'left' ,
                            'label' => __( 'Left (£99.99)' , ST_TEXTDOMAIN ) ,
                        ) ,
                        array(
                            'value' => 'right' ,
                            'label' => __( 'Right (99.99£)' , ST_TEXTDOMAIN ) ,
                        ) ,
                        array(
                            'value' => 'left_space' ,
                            'label' => __( 'Left with space (£ 99.99)' , ST_TEXTDOMAIN ) ,
                        ) ,
                        array(
                            'value' => 'right_space' ,
                            'label' => __( 'Right with space (99.99 £)' , ST_TEXTDOMAIN ) ,
                        )
                    ) ,
                    'std'     => 'left'
                ),
                array(
                    'id'=>'currency_rtl_support',
                    'type'=>"on-off",
                    'label'=>__("This currency is use for RTL languages?",ST_TEXTDOMAIN),
                    'std'=>'off'
                ),
                array(

                    'id'      => 'thousand_separator' ,
                    'label'   => __( 'Thousand Separator' , ST_TEXTDOMAIN ) ,
                    'type'    => 'text' ,
                    'std'     => '.' ,
                    'desc'    => __( 'Optional. Specifies what string to use for thousands separator.' , ST_TEXTDOMAIN )
                ),
                array(
                    'id'      => 'decimal_separator' ,
                    'label'   => __( 'Decimal Separator' , ST_TEXTDOMAIN ) ,
                    'type'    => 'text' ,
                    'std'     => ',' ,
                    'desc'    => __( 'Optional. Specifies what string to use for decimal point' , ST_TEXTDOMAIN )
                    
                ),
                array(
                    'id'           => 'booking_currency_precision' ,
                    'label'        => __( 'Currency decimal' , ST_TEXTDOMAIN ) ,
                    'desc'         => __( 'Sets the number of decimal points.' , ST_TEXTDOMAIN ) ,
                    'type'         => 'numeric-slider' ,
                    'min_max_step' => '0,5,1' ,
                    'std'          => 2
                ),

            ) ,
            'std'      => array(
                array(
                    'title'  => 'USD' ,
                    'name'   => 'USD' ,
                    'symbol' => '$' ,
                    'rate'   => 1,
                    'booking_currency_pos'=>'left',
                    'thousand_separator'=>'.',
                    'decimal_separator'=>',',
                    'booking_currency_precision'=>2,

                ) ,
                array(
                    'title'  => 'EUR' ,
                    'name'   => 'EUR' ,
                    'symbol' => '€' ,
                    'rate'   => 0.796491,
                    'booking_currency_pos'=>'left',
                    'thousand_separator'=>'.',
                    'decimal_separator'=>',',
                    'booking_currency_precision'=>2,
                ) ,
                array(
                    'title'  => 'GBP' ,
                    'name'   => 'GBP' ,
                    'symbol' => '£' ,
                    'rate'   => 0.636169,
                    'booking_currency_pos'=>'right',
                    'thousand_separator'=>',',
                    'decimal_separator'=>',',
                    'booking_currency_precision'=>2,
                ) ,
            )

        )
        ,
        array(
            'id'      => 'show_booking_primary_currency' ,
            'label'   => __( 'Show Currency' , ST_TEXTDOMAIN ) ,
            'desc'    => "" ,
            'type'    => 'on-off' ,
            'section' => 'option_booking' ,
            'std'     => 'on'

        ),
        array(
            'id'      => 'booking_primary_currency' ,
            'label'   => __( 'Primary Currency' , ST_TEXTDOMAIN ) ,
            'desc'    => __( 'This allows you to set Primary Currency to your website' , ST_TEXTDOMAIN ) ,
            'type'    => 'select' ,
            'section' => 'option_booking' ,
            'choices' => TravelHelper::get_currency( true ) ,
            'std'     => 'USD'

        ),
        array(
            'id'      => 'is_guest_booking' ,
            'label'   => __( 'Guest Booking' , ST_TEXTDOMAIN ) ,
            'desc'    => __( 'Guest Booking. default off : Guest can"t booking ' , ST_TEXTDOMAIN ) ,
            'section' => 'option_booking' ,
            'type'    => 'on-off' ,
            'std'     => 'off'
        ) ,
        //        array(
        //            'id'          => 'booking_allow_func',
        //            'label'       => __( 'Allow Booking functions for:', ST_TEXTDOMAIN ),
        //            'desc'        =>  __( 'This allows you enable booking features(included search tab, book and order) on each object
        //',ST_TEXTDOMAIN ),
        //            'type'        => 'Checkbox',
        //            'section'     => 'option_booking',
        //            'choices'     =>array(
        //                array(
        //                    'value'=>'hotel',
        //                    'label'=>__('Hotel',ST_TEXTDOMAIN),
        //                ),
        //                array(
        //                    'value'=>'cars',
        //                    'label'=>__('Cars',ST_TEXTDOMAIN),
        //                ),
        ////                array(
        ////                    'value'=>'cruise',
        ////                    'label'=>__('Cruise',ST_TEXTDOMAIN),
        ////                ),
        //                array(
        //                    'value'=>'rental',
        //                    'label'=>__('Rental',ST_TEXTDOMAIN),
        //                ),
        //                array(
        //                    'value'=>'activity',
        //                    'label'=>__('Activity',ST_TEXTDOMAIN),
        //                ),
        //                array(
        //                    'value'=>'tour',
        //                    'label'=>__('Tour',ST_TEXTDOMAIN),
        //                ),
        //            )
        //
        //        ),

        /*------------- End Booking Option --------------*/


        /*------------- Hotel Option --------------*/
        array(
            'id'      => 'hotel_show_min_price' ,
            'label'   => __( "Which Price is shown in listing page" , ST_TEXTDOMAIN ) ,
            'type'    => 'select' ,
            'choices' => array(
                array(
                    'value' => 'avg_price' ,
                    'label' => __( 'Avg Price' , ST_TEXTDOMAIN )

                ) ,
                array(
                    'value' => 'min_price' ,
                    'label' => __( 'Min Price' , ST_TEXTDOMAIN )
                ) ,
            ) ,
            'section' => 'option_hotel' ,
        ) ,
        array(
            'id'      => 'hotel_search_result_page' ,
            'label'   => __( 'Search Result Page' , ST_TEXTDOMAIN ) ,
            'type'    => 'page-select' ,
            'section' => 'option_hotel' ,
        ) ,
        array(
            'id'           => 'hotel_posts_per_page' ,
            'label'        => __( 'Posts Per Page' , ST_TEXTDOMAIN ) ,
            'desc'         => __( 'Posts Per Page' , ST_TEXTDOMAIN ) ,
            'type'         => 'numeric-slider' ,
            'min_max_step' => '1,50,1' ,
            'section'      => 'option_hotel' ,
            'std'          => '12'

        )
        ,
        array(
            'id'      => 'hotel_single_layout' ,
            'label'   => __( 'Hotel Detail Layout' , ST_TEXTDOMAIN ) ,
            'type'    => 'select' ,
            //'post_type'   =>'st_layouts',
            'section' => 'option_hotel' ,
            'choices' => st_get_layout( 'st_hotel' )
        )
        ,
        array(
            'id'      => 'hotel_search_layout' ,
            'label'   => __( 'Hotel Search Layout' , ST_TEXTDOMAIN ) ,
            'type'    => 'select' ,
            'section' => 'option_hotel' ,
            'choices' => st_get_layout( 'st_hotel_search' )
        )
        ,

        array(
            'id'      => 'hotel_single_room_layout' ,
            'label'   => __( 'Hotel Room Detail Layout' , ST_TEXTDOMAIN ) ,
            'type'    => 'select' ,
            //'post_type'   =>'st_layouts',
            'section' => 'option_hotel' ,
            'choices' => st_get_layout( 'hotel_room' )
        ),
        array(
            'id'      => 'hotel_max_adult' ,
            'label'   => __( 'Max Adults In Room' , ST_TEXTDOMAIN ) ,
            'desc'    => __( 'Max Adults In Room' , ST_TEXTDOMAIN ) ,
            'type'    => 'text' ,
            'section' => 'option_hotel' ,
            'std'     => 14

        )
        ,
        array(
            'id'      => 'hotel_max_child' ,
            'label'   => __( 'Max Children In Room' , ST_TEXTDOMAIN ) ,
            'desc'    => __( 'Max Children In Room' , ST_TEXTDOMAIN ) ,
            'type'    => 'text' ,
            'section' => 'option_hotel' ,
            'std'     => 14

        ) ,
        array(
            'id'      => 'hotel_review' ,
            'label'   => __( 'Enable Review' , ST_TEXTDOMAIN ) ,
            'type'    => 'on-off' ,
            'section' => 'option_hotel' ,
            'std'     => 'on'

        ) ,

        array(
            'id'        => 'hotel_review_stats' ,
            'label'     => __( 'Hotel Review Criteria' , ST_TEXTDOMAIN ) ,
            'desc'      => __( 'Hotel Review Criteria' , ST_TEXTDOMAIN ) ,
            'type'      => 'list-item' ,
            'section'   => 'option_hotel' ,
            'condition' => 'hotel_review:is(on)' ,
            'settings'  => array(
                array(
                    'id'       => 'name' ,
                    'label'    => __( 'Stat Name' , ST_TEXTDOMAIN ) ,
                    'type'     => 'textblock' ,
                    'operator' => 'and' ,
                )
            ) ,
            'std'       => array(

                array( 'title' => 'Sleep' ) ,
                array( 'title' => 'Location' ) ,
                array( 'title' => 'Service' ) ,
                array( 'title' => 'Cleanliness' ) ,
                array( 'title' => 'Room(s)' ) ,
            )
        ) ,
        array(
            'id'      => 'hotel_sidebar_pos' ,
            'label'   => __( 'Sidebar Position' , ST_TEXTDOMAIN ) ,
            'type'    => 'select' ,
            'section' => 'option_hotel' ,
            'choices' => array(
                array(
                    'value' => 'no' ,
                    'label' => __( 'No' , ST_TEXTDOMAIN )
                ) ,
                array(
                    'value' => 'left' ,
                    'label' => __( 'Left' , ST_TEXTDOMAIN )
                ) ,
                array(
                    'value' => 'right' ,
                    'label' => __( 'Right' , ST_TEXTDOMAIN )
                )

            ) ,
            'std'     => 'left'

        ) ,
        array(
            'id'      => 'hotel_sidebar_area' ,
            'label'   => __( 'Sidebar Area' , ST_TEXTDOMAIN ) ,
            'type'    => 'sidebar-select' ,
            'section' => 'option_hotel' ,
        ) ,
        array(
            'id'      => 'is_featured_search_hotel' ,
            'label'   => __( 'Show featured rentals on top of search result' , ST_TEXTDOMAIN ) ,
            'type'    => 'on-off' ,
            'std'     => 'off' ,
            'section' => 'option_hotel'
        ) ,
        'flied_hotel'=>array(
            'id'       => 'hotel_search_fields' ,
            'label'    => __( 'Hotel Search Fields' , ST_TEXTDOMAIN ) ,
            'desc'     => __( 'Hotel Search Fields' , ST_TEXTDOMAIN ) ,
            'type'     => 'list-item' ,
            'section'  => 'option_hotel' ,
            'std'      => array(
                array(
                    'title'      => 'Where' ,
                    'name'       => 'location' ,
                    'layout_col' => 4

                ) ,
                array(
                    'title'      => 'Check in' ,
                    'name'       => 'checkin' ,
                    'layout_col' => 2
                ) ,
                array(
                    'title'      => 'Check out' ,
                    'name'       => 'checkout' ,
                    'layout_col' => 2
                ) ,
                array(
                    'title'      => 'Room(s)' ,
                    'name'       => 'room_num' ,
                    'layout_col' => 2
                ) ,
                array(
                    'title'      => 'Adult' ,
                    'name'       => 'adult' ,
                    'layout_col' => 2
                )
            ) ,
            'settings' => array(
                array(
                    'id'       => 'name' ,
                    'label'    => __( 'Field Type' , ST_TEXTDOMAIN ) ,
                    'type'     => 'select' ,
                    'operator' => 'and' ,
                    'choices'  => STHotel::get_search_fields_name()
                ) ,
                array(
                    'id'       => 'placeholder' ,
                    'label'    => __( 'Placeholder' , ST_TEXTDOMAIN ) ,
                    'desc'     => __( 'Placeholder' , ST_TEXTDOMAIN ) ,
                    'type'     => 'text' ,
                    'operator' => 'and' ,
                ) ,
                array(
                    'id'       => 'layout_col' ,
                    'label'    => __( 'Layout 1 Size' , ST_TEXTDOMAIN ) ,
                    'type'     => 'select' ,
                    'operator' => 'and' ,
                    'std'      => 4 ,
                    'choices'  => array(
                        array(
                            'value' => '1' ,
                            'label' => __( 'column 1' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '2' ,
                            'label' => __( 'column 2' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '3' ,
                            'label' => __( 'column 3' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '4' ,
                            'label' => __( 'column 4' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '5' ,
                            'label' => __( 'column 5' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '6' ,
                            'label' => __( 'column 6' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '7' ,
                            'label' => __( 'column 7' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '8' ,
                            'label' => __( 'column 8' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '9' ,
                            'label' => __( 'column 9' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '10' ,
                            'label' => __( 'column 10' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '11' ,
                            'label' => __( 'column 11' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '12' ,
                            'label' => __( 'column 12' , ST_TEXTDOMAIN )
                        ) ,
                    ) ,
                ) ,
                array(
                    'id'       => 'layout2_col' ,
                    'label'    => __( 'Layout 2 Size' , ST_TEXTDOMAIN ) ,
                    'type'     => 'select' ,
                    'operator' => 'and' ,
                    'std'      => 4 ,
                    'choices'  => array(
                        array(
                            'value' => '1' ,
                            'label' => __( 'column 1' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '2' ,
                            'label' => __( 'column 2' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '3' ,
                            'label' => __( 'column 3' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '4' ,
                            'label' => __( 'column 4' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '5' ,
                            'label' => __( 'column 5' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '6' ,
                            'label' => __( 'column 6' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '7' ,
                            'label' => __( 'column 7' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '8' ,
                            'label' => __( 'column 8' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '9' ,
                            'label' => __( 'column 9' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '10' ,
                            'label' => __( 'column 10' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '11' ,
                            'label' => __( 'column 11' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '12' ,
                            'label' => __( 'column 12' , ST_TEXTDOMAIN )
                        ) ,
                    ) ,
                ) ,
                array(
                    'id'        => 'taxonomy' ,
                    'label'     => __( 'Taxonomy' , ST_TEXTDOMAIN ) ,
                    'condition' => 'name:is(taxonomy)' ,
                    'operator'  => 'or' ,
                    'type'      => 'select' ,
                    'choices'   => st_get_post_taxonomy( 'st_hotel' )
                ) ,
                array(
                    'id'        => 'type_show_taxonomy_hotel' ,
                    'label'     => __( 'Type show' , ST_TEXTDOMAIN ) ,
                    'condition' => 'name:is(taxonomy)' ,
                    'operator'  => 'or' ,
                    'type'      => 'select' ,
                    'choices'   => array(
                        array(
                            'value' => 'checkbox' ,
                            'label' => __( 'Checkbox' , ST_TEXTDOMAIN ) ,
                        ) ,
                        array(
                            'value' => 'select' ,
                            'label' => __( 'Select' , ST_TEXTDOMAIN ) ,
                        ) ,
                    )
                ) ,
                array(
                    'id'        => 'taxonomy_room' ,
                    'label'     => __( 'Taxonomy Room' , ST_TEXTDOMAIN ) ,
                    'condition' => 'name:is(taxonomy_room)' ,
                    'operator'  => 'or' ,
                    'type'      => 'select' ,
                    'choices'   => st_get_post_taxonomy( 'hotel_room' )
                ) ,
                array(
                    'id'        => 'type_show_taxonomy_hotel_room' ,
                    'label'     => __( 'Type show' , ST_TEXTDOMAIN ) ,
                    'condition' => 'name:is(taxonomy_room)' ,
                    'operator'  => 'or' ,
                    'type'      => 'select' ,
                    'choices'   => array(
                        array(
                            'value' => 'checkbox' ,
                            'label' => __( 'Checkbox' , ST_TEXTDOMAIN ) ,
                        ) ,
                        array(
                            'value' => 'select' ,
                            'label' => __( 'Select' , ST_TEXTDOMAIN ) ,
                        ) ,
                    )
                ) ,
                array(
                    'id'        => 'max_num' , 
                    'label'     => __("Max number" , ST_TEXTDOMAIN),
                    'condition' => 'name:is(list_name)',
                    'type'  => "text",
                    'std'   => 20
                    ) , 
                array(
                    'id'       => 'is_required' ,
                    'label'    => __( 'Field required' , ST_TEXTDOMAIN ) ,
                    'type'     => 'on-off' ,
                    'operator' => 'and' ,
                    'std'      => 'on' ,
                ) ,

            )
        ) ,
        //        array(
        //            'id'          => 'hotel_allow_search_advance',
        //            'label'       => __( 'Allow Search Advance Field', ST_TEXTDOMAIN ),
        //            'type'        => 'on-off',
        //            'section'     => 'option_hotel',
        //            'std'         =>'off'
        //        ),
        //        array(
        //            'id'          => 'hotel_search_advance',
        //            'label'       => __( 'Hotel Search Advance', ST_TEXTDOMAIN ),
        //            'type'        => 'Slider',
        //            'section'     => 'option_hotel',
        //            'condition'   => 'hotel_allow_search_advance:is(on)',
        //            'settings'    =>array(
        //                array(
        //                    'id'=>'name',
        //                    'label'=>__('Field',ST_TEXTDOMAIN),
        //                    'type'=>'select',
        //                    'operator'    => 'and',
        //                    'choices'     =>STHotel::get_search_fields_name()
        //
        //                ),
        //                array(
        //                    'id'=>'layout_col',
        //                    'label'=>__('Layout 1 Size',ST_TEXTDOMAIN),
        //                    'type'=>'select',
        //                    'operator'    => 'and',
        //                    'std'       =>4,
        //                    'choices'   =>array(
        //                        array(
        //                            'value'=>'1',
        //                            'label'=>__('column 1',ST_TEXTDOMAIN)
        //                        ),
        //                        array(
        //                            'value'=>'2',
        //                            'label'=>__('column 2',ST_TEXTDOMAIN)
        //                        ),
        //                        array(
        //                            'value'=>'3',
        //                            'label'=>__('column 3',ST_TEXTDOMAIN)
        //                        ),
        //                        array(
        //                            'value'=>'4',
        //                            'label'=>__('column 4',ST_TEXTDOMAIN)
        //                        ),
        //                        array(
        //                            'value'=>'5',
        //                            'label'=>__('column 5',ST_TEXTDOMAIN)
        //                        ),
        //                        array(
        //                            'value'=>'6',
        //                            'label'=>__('column 6',ST_TEXTDOMAIN)
        //                        ),
        //                        array(
        //                            'value'=>'7',
        //                            'label'=>__('column 7',ST_TEXTDOMAIN)
        //                        ),
        //                        array(
        //                            'value'=>'8',
        //                            'label'=>__('column 8',ST_TEXTDOMAIN)
        //                        ),
        //                        array(
        //                            'value'=>'9',
        //                            'label'=>__('column 9',ST_TEXTDOMAIN)
        //                        ),
        //                        array(
        //                            'value'=>'10',
        //                            'label'=>__('column 10',ST_TEXTDOMAIN)
        //                        ),
        //                        array(
        //                            'value'=>'11',
        //                            'label'=>__('column 11',ST_TEXTDOMAIN)
        //                        ),
        //                        array(
        //                            'value'=>'12',
        //                            'label'=>__('column 12',ST_TEXTDOMAIN)
        //                        ),
        //                    ),
        //                ),
        //                array(
        //                    'id'=>'layout2_col',
        //                    'label'=>__('Layout 2 Size',ST_TEXTDOMAIN),
        //                    'type'=>'select',
        //                    'operator'    => 'and',
        //                    'std'       =>4,
        //                    'choices'   =>array(
        //                        array(
        //                            'value'=>'1',
        //                            'label'=>__('column 1',ST_TEXTDOMAIN)
        //                        ),
        //                        array(
        //                            'value'=>'2',
        //                            'label'=>__('column 2',ST_TEXTDOMAIN)
        //                        ),
        //                        array(
        //                            'value'=>'3',
        //                            'label'=>__('column 3',ST_TEXTDOMAIN)
        //                        ),
        //                        array(
        //                            'value'=>'4',
        //                            'label'=>__('column 4',ST_TEXTDOMAIN)
        //                        ),
        //                        array(
        //                            'value'=>'5',
        //                            'label'=>__('column 5',ST_TEXTDOMAIN)
        //                        ),
        //                        array(
        //                            'value'=>'6',
        //                            'label'=>__('column 6',ST_TEXTDOMAIN)
        //                        ),
        //                        array(
        //                            'value'=>'7',
        //                            'label'=>__('column 7',ST_TEXTDOMAIN)
        //                        ),
        //                        array(
        //                            'value'=>'8',
        //                            'label'=>__('column 8',ST_TEXTDOMAIN)
        //                        ),
        //                        array(
        //                            'value'=>'9',
        //                            'label'=>__('column 9',ST_TEXTDOMAIN)
        //                        ),
        //                        array(
        //                            'value'=>'10',
        //                            'label'=>__('column 10',ST_TEXTDOMAIN)
        //                        ),
        //                        array(
        //                            'value'=>'11',
        //                            'label'=>__('column 11',ST_TEXTDOMAIN)
        //                        ),
        //                        array(
        //                            'value'=>'12',
        //                            'label'=>__('column 12',ST_TEXTDOMAIN)
        //                        ),
        //                    ),
        //                ),
        //                array(
        //                    'id'=>'taxonomy',
        //                    'label'=>__('Taxonomy',ST_TEXTDOMAIN),
        //                    'choices'=>st_get_post_taxonomy('st_hotel'),
        //                    'operator'    => 'and',
        //                    'type'              =>'select'
        //                ),
        //            )
        //        ),
        array(
            'id'      => 'hotel_nearby_range' ,
            'label'   => __( 'Hotel Nearby Range' , ST_TEXTDOMAIN ) ,
            'type'    => 'text' ,
            'section' => 'option_hotel' ,
            'desc'    => __( 'This allows you to change max range of nearby hotels (in km)' , ST_TEXTDOMAIN ) ,
            'std'     => 10
        ) ,
        array(
            'id'       => 'hotel_unlimited_custom_field' ,
            'label'    => __( 'Hotel custom field' , ST_TEXTDOMAIN ) ,
            'type'     => 'Slider' ,
            'section'  => 'option_hotel' ,
            'desc'     => __( 'Hotel custom field' , ST_TEXTDOMAIN ) ,
            'settings' => array(
                array(
                    'id'       => 'type_field' ,
                    'label'    => __( 'Field type' , ST_TEXTDOMAIN ) ,
                    'type'     => 'select' ,
                    'operator' => 'and' ,
                    'choices'  => array(
                        array(
                            'value' => 'text' ,
                            'label' => __( 'Text field' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => 'textarea' ,
                            'label' => __( 'Textarea field' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => 'date-picker' ,
                            'label' => __( 'Date field' , ST_TEXTDOMAIN )
                        ) ,
                    )

                ) ,
                array(
                    'id'       => 'default_field' ,
                    'label'    => __( 'Default' , ST_TEXTDOMAIN ) ,
                    'type'     => 'text' ,
                    'operator' => 'and'
                ) ,

            ) ,
        ) ,
        /*array(
            'id'      => 'hotel_sidebar_pos',
            'label'   => __('Search Room Order By', ST_TEXTDOMAIN),
            'type'    => 'select',
            'section' => 'option_hotel',
            'choices' => array(
                array(
                    'value' => 'left',
                    'label' => __('Name', ST_TEXTDOMAIN)
                ),
                array(
                    'value' => 'right',
                    'label' => __('Price', ST_TEXTDOMAIN)
                )

            ),
            'std'     => 'left'

        ),*/
        array(
            'id'      => 'st_hotel_icon_map_marker',
            'label'   => __('Icon Marker Map', ST_TEXTDOMAIN),
            'desc'    => __( 'Icon Marker Map' , ST_TEXTDOMAIN ) ,
            'type'    => 'upload',
            'section' => 'option_hotel',
            'std'     => 'http://maps.google.com/mapfiles/marker_black.png'

        ),
        /*------------- End Hotel Option --------------*/
        /*------------- Rental Option -----------------*/
        array(
            'id'      => 'rental_search_result_page' ,
            'label'   => __( 'Search Result Page' , ST_TEXTDOMAIN ) ,
            'type'    => 'page-select' ,
            'section' => 'option_rental' ,
        ) ,
        array(
            'id'      => 'rental_single_layout' ,
            'label'   => __( 'Rental Single Layout' , ST_TEXTDOMAIN ) ,
            'type'    => 'select' ,
            'section' => 'option_rental' ,
            'choices' => st_get_layout( 'st_rental' )

        ) ,
        array(
            'id'      => 'rental_search_layout' ,
            'label'   => __( 'Rental Search Layout' , ST_TEXTDOMAIN ) ,
            'type'    => 'select' ,
            'section' => 'option_rental' ,
            'choices' => st_get_layout( 'st_rental_search' )
        ) ,
        array(
            'id'      => 'rental_room_layout' ,
            'label'   => __( 'Rental Room Default Layout' , ST_TEXTDOMAIN ) ,
            'type'    => 'select' ,
            'section' => 'option_rental' ,
            'choices' => st_get_layout( 'rental_room' )
        ) ,
        array(
            'id'      => 'rental_review' ,
            'label'   => __( 'Enable Review' , ST_TEXTDOMAIN ) ,
            'type'    => 'on-off' ,
            'section' => 'option_rental' ,
            'std'     => 'on'

        ) ,

        array(
            'id'        => 'rental_review_stats' ,
            'label'     => __( 'Rental Review Criteria' , ST_TEXTDOMAIN ) ,
            'desc'      => __( 'Rental Review Criteria' , ST_TEXTDOMAIN ) ,
            'type'      => 'list-item' ,
            'section'   => 'option_rental' ,
            'condition' => 'rental_review:is(on)' ,
            'settings'  => array(
                array(
                    'id'    => 'name' ,
                    'label' => __( 'Stat Name' , ST_TEXTDOMAIN ) ,
                    'type'  => 'textblock' ,
                )
            ) ,
            'std'       => array(

                array( 'title' => 'Sleep' ) ,
                array( 'title' => 'Location' ) ,
                array( 'title' => 'Service' ) ,
                array( 'title' => 'Cleanliness' ) ,
                array( 'title' => 'Room(s)' ) ,
            )
        ) ,
        array(
            'id'      => 'rental_sidebar_pos' ,
            'label'   => __( 'Sidebar Position' , ST_TEXTDOMAIN ) ,
            'type'    => 'select' ,
            'section' => 'option_rental' ,
            'choices' => array(
                array(
                    'value' => 'no' ,
                    'label' => __( 'No' , ST_TEXTDOMAIN )
                ) ,
                array(
                    'value' => 'left' ,
                    'label' => __( 'Left' , ST_TEXTDOMAIN )
                ) ,
                array(
                    'value' => 'right' ,
                    'label' => __( 'Right' , ST_TEXTDOMAIN )
                )

            ) ,
            'std'     => 'left'

        ) ,
        array(
            'id'      => 'rental_sidebar_area' ,
            'label'   => __( 'Sidebar Area' , ST_TEXTDOMAIN ) ,
            'type'    => 'sidebar-select' ,
            'section' => 'option_rental' ,
            'std'     => 'rental-sidebar'

        ) ,
        array(
            'id'      => 'is_featured_search_rental' ,
            'label'   => __( 'Show featured activities on top of search result' , ST_TEXTDOMAIN ) ,
            'type'    => 'on-off' ,
            'std'     => 'off' ,
            'section' => 'option_rental'
        ) ,
        array(
            'id'       => 'rental_search_fields' ,
            'label'    => __( 'Rental Search Fields' , ST_TEXTDOMAIN ) ,
            'desc'     => __( 'Rental Search Fields' , ST_TEXTDOMAIN ) ,
            'type'     => 'Slider' ,
            'section'  => 'option_rental' ,
            'settings' => array(
                array(
                    'id'       => 'name' ,
                    'label'    => __( 'Field Type' , ST_TEXTDOMAIN ) ,
                    'type'     => 'select' ,
                    'operator' => 'and' ,
                    'choices'  => TravelHelper::st_get_field_search( 'st_rental' , 'option_tree' )
                ) ,
                array(
                    'id'       => 'placeholder' ,
                    'label'    => __( 'Placeholder' , ST_TEXTDOMAIN ) ,
                    'desc'     => __( 'Placeholder' , ST_TEXTDOMAIN ) ,
                    'type'     => 'text' ,
                    'operator' => 'and' ,
                ) ,
                array(
                    'id'       => 'layout_col' ,
                    'label'    => __( 'Large-box column size' , ST_TEXTDOMAIN ) ,
                    'type'     => 'select' ,
                    'operator' => 'and' ,
                    'choices'  => array(
                        array(
                            'value' => '1' ,
                            'label' => __( 'column 1' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '2' ,
                            'label' => __( 'column 2' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '3' ,
                            'label' => __( 'column 3' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '4' ,
                            'label' => __( 'column 4' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '5' ,
                            'label' => __( 'column 5' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '6' ,
                            'label' => __( 'column 6' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '7' ,
                            'label' => __( 'column 7' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '8' ,
                            'label' => __( 'column 8' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '9' ,
                            'label' => __( 'column 9' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '10' ,
                            'label' => __( 'column 10' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '11' ,
                            'label' => __( 'column 11' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '12' ,
                            'label' => __( 'column 12' , ST_TEXTDOMAIN )
                        ) ,
                    ) ,
                    'std'      => 4
                ) ,
                array(
                    'id'       => 'layout_col2' ,
                    'label'    => __( 'Small-box column size' , ST_TEXTDOMAIN ) ,
                    'type'     => 'select' ,
                    'operator' => 'and' ,
                    'choices'  => array(
                        array(
                            'value' => '1' ,
                            'label' => __( 'column 1' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '2' ,
                            'label' => __( 'column 2' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '3' ,
                            'label' => __( 'column 3' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '4' ,
                            'label' => __( 'column 4' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '5' ,
                            'label' => __( 'column 5' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '6' ,
                            'label' => __( 'column 6' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '7' ,
                            'label' => __( 'column 7' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '8' ,
                            'label' => __( 'column 8' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '9' ,
                            'label' => __( 'column 9' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '10' ,
                            'label' => __( 'column 10' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '11' ,
                            'label' => __( 'column 11' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '12' ,
                            'label' => __( 'column 12' , ST_TEXTDOMAIN )
                        ) ,
                    ) ,
                    'std'      => 4
                ) ,
                array(
                    'id'       => 'taxonomy' ,
                    'label'    => __( 'Taxonomy' , ST_TEXTDOMAIN ) ,
                    'type'     => 'select' ,
                    'operator' => 'and' ,
                    'condition' => 'name:is(taxonomy)' ,
                    'choices'  => st_get_post_taxonomy( 'st_rental' )
                ) ,
                array(
                    'id'        => 'type_show_taxonomy_rental' ,
                    'label'     => __( 'Type show' , ST_TEXTDOMAIN ) ,
                    'condition' => 'name:is(taxonomy)' ,
                    'operator'  => 'or' ,
                    'type'      => 'select' ,
                    'choices'   => array(
                        array(
                            'value' => 'checkbox' ,
                            'label' => __( 'Checkbox' , ST_TEXTDOMAIN ) ,
                        ) ,
                        array(
                            'value' => 'select' ,
                            'label' => __( 'Select' , ST_TEXTDOMAIN ) ,
                        ) ,
                    )
                ) ,
                array(
                    'id'       => 'max_num' ,
                    'label'    => __( 'Max number' , ST_TEXTDOMAIN ),
                    'type'     => 'text' ,
                    'condition' => 'name:is(list_name)' ,
                    'operator' => 'and' ,
                    'std'  => 20
                ) ,
                array(
                    'id'       => 'is_required' ,
                    'label'    => __( 'Field required' , ST_TEXTDOMAIN ) ,
                    'type'     => 'on-off' ,
                    'operator' => 'and' ,
                    'std'      => 'on' ,
                ) ,
            ) ,
            'std'      => array(
                array(
                    'title'       => 'Where are you going?' ,
                    'name'        => 'location' ,
                    'layout_col'  => '4' ,
                    'layout_col2' => '12'
                ) ,
                array(
                    'title'       => 'Check in' ,
                    'name'        => 'checkin' ,
                    'layout_col'  => '2' ,
                    'layout_col2' => '3'
                ) ,
                array(
                    'title'       => 'Check out' ,
                    'name'        => 'checkout' ,
                    'layout_col'  => '2' ,
                    'layout_col2' => '3'
                ) ,
                array(
                    'title'       => 'Room(s)' ,
                    'name'        => 'room_num' ,
                    'layout_col'  => '2' ,
                    'layout_col2' => '3'
                ) ,
                array(
                    'title'       => 'Adults' ,
                    'name'        => 'adult' ,
                    'layout_col'  => '2' ,
                    'layout_col2' => '3'
                )
            )
        ) ,
        
        array(
            'id'       => 'rental_unlimited_custom_field' ,
            'label'    => __( 'Rental custom field' , ST_TEXTDOMAIN ) ,
            'type'     => 'Slider' ,
            'section'  => 'option_rental' ,
            'desc'     => __( 'Rental custom field' , ST_TEXTDOMAIN ) ,
            'settings' => array(
                array(
                    'id'       => 'type_field' ,
                    'label'    => __( 'Field type' , ST_TEXTDOMAIN ) ,
                    'type'     => 'select' ,
                    'operator' => 'and' ,
                    'choices'  => array(
                        array(
                            'value' => 'text' ,
                            'label' => __( 'Text field' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => 'textarea' ,
                            'label' => __( 'Textarea field' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => 'date-picker' ,
                            'label' => __( 'Date field' , ST_TEXTDOMAIN )
                        ) ,
                    )

                ) ,
                array(
                    'id'       => 'default_field' ,
                    'label'    => __( 'Default' , ST_TEXTDOMAIN ) ,
                    'type'     => 'text' ,
                    'operator' => 'and'
                ) ,

            ) ,
        ) ,
        array(
            'id'      => 'st_rental_icon_map_marker',
            'label'   => __('Icon Marker Map', ST_TEXTDOMAIN),
            'desc'    => __( 'Icon Marker Map' , ST_TEXTDOMAIN ) ,
            'type'    => 'upload',
            'section' => 'option_rental',
            'std'     => 'http://maps.google.com/mapfiles/marker_brown.png'
        ),
        /*------------ End Rental Option --------------*/

        /*------------- Cars Option -----------------*/

        array(
            'id'      => 'cars_search_result_page' ,
            'label'   => __( 'Search Result Page' , ST_TEXTDOMAIN ) ,
            'type'    => 'page-select' ,
            'section' => 'option_car' ,
        ) ,
        array(
            'id'      => 'cars_single_layout' ,
            'label'   => __( 'Cars Single Layout' , ST_TEXTDOMAIN ) ,
            'type'    => 'select' ,
            'section' => 'option_car' ,
            'choices' => st_get_layout( 'st_cars' )
        ) ,
        array(
            'id'      => 'cars_layout_layout' ,
            'label'   => __( 'Cars Search Layout' , ST_TEXTDOMAIN ) ,
            'type'    => 'select' ,
            'section' => 'option_car' ,
            'choices' => st_get_layout( 'st_cars_search' )
        ) ,
//        array(
//            'id'      => 'is_required_country' ,
//            'label'   => __( 'Required Pickup and Dropoff in the same country' , ST_TEXTDOMAIN ) ,
//            'type'    => 'on-off' ,
//            'std'     => 'off' ,
//            'section' => 'option_car' ,
//        ) ,
        array(
            'id'      => 'cars_price_unit' ,
            'label'   => __( 'Price Unit' , ST_TEXTDOMAIN ) ,
            'type'    => 'select' ,
            'section' => 'option_car' ,
            'choices' => STCars::get_option_price_unit() ,
            'std'     => 'day'
        )
        ,
        array(
            'id'      => 'booking_days_included' ,
            'label'   => __( 'Booking Days including check-in day, hour' , ST_TEXTDOMAIN ) ,
            'type'    => 'on-off' ,
            'std'     => 'off' ,
            'section' => 'option_car' ,
            'desc'  => __("This mean <b>check-in day alway rounded 1 day</b> to to your booking" , ST_TEXTDOMAIN)
        ) ,
        array(
            'id'      => 'time_format' ,
            'label'   => __( 'Time Format' , ST_TEXTDOMAIN ) ,
            'type'    => 'select' ,
            'std'     => '12h' ,
            'choices'=>array(
                array(
                    'value'     =>'12h',
                    'label'     =>__('12h',ST_TEXTDOMAIN)
                ),
                array(
                    'value'     =>'24h',
                    'label'     =>__('24h',ST_TEXTDOMAIN)
                ),
            ),
            'section' => 'option_car' ,
        ) ,
        array(
            'id'      => 'is_featured_search_car' ,
            'label'   => __( 'Show featured cars on top of search results' , ST_TEXTDOMAIN ) ,
            'type'    => 'on-off' ,
            'std'     => 'off' ,
            'section' => 'option_car'
        ) ,
        array(
            'id'       => 'car_search_fields' ,
            'label'    => __( 'Car Search Fields' , ST_TEXTDOMAIN ) ,
            'desc'     => __( 'Car Search Fields' , ST_TEXTDOMAIN ) ,
            'type'     => 'Slider' ,
            'section'  => 'option_car' ,
            'settings' => array(

                array(
                    'id'       => 'field_atrribute' ,
                    'label'    => __( 'Field Atrribute' , ST_TEXTDOMAIN ) ,
                    'type'     => 'select' ,
                    'operator' => 'and' ,
                    'choices'  => STCars::get_search_fields_name()
                ) ,
                array(
                    'id'       => 'placeholder' ,
                    'label'    => __( 'Placeholder' , ST_TEXTDOMAIN ) ,
                    'desc'     => __( 'Placeholder' , ST_TEXTDOMAIN ) ,
                    'type'     => 'text' ,
                    'operator' => 'and' ,
                ) ,
                array(
                    'id'       => 'layout_col_normal' ,
                    'label'    => __( 'Layout Normal size' , ST_TEXTDOMAIN ) ,
                    'type'     => 'select' ,
                    'operator' => 'and' ,
                    'choices'  => array(
                        array(
                            'value' => '1' ,
                            'label' => __( 'column 1' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '2' ,
                            'label' => __( 'column 2' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '3' ,
                            'label' => __( 'column 3' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '4' ,
                            'label' => __( 'column 4' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '5' ,
                            'label' => __( 'column 5' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '6' ,
                            'label' => __( 'column 6' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '7' ,
                            'label' => __( 'column 7' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '8' ,
                            'label' => __( 'column 8' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '9' ,
                            'label' => __( 'column 9' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '10' ,
                            'label' => __( 'column 10' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '11' ,
                            'label' => __( 'column 11' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '12' ,
                            'label' => __( 'column 12' , ST_TEXTDOMAIN )
                        ) ,
                    ) ,
                ) ,
                array(
                    'id'        => 'taxonomy' ,
                    'label'     => __( 'Taxonomy' , ST_TEXTDOMAIN ) ,
                    'condition' => 'field_atrribute:is(taxonomy)' ,
                    'type'      => 'select' ,
                    'operator'  => 'and' ,
                    'choices'   => st_get_post_taxonomy( 'st_cars' )
                ) ,
                array(
                    'id'        => 'type_show_taxonomy_cars' ,
                    'label'     => __( 'Type show' , ST_TEXTDOMAIN ) ,
                    'condition' => 'field_atrribute:is(taxonomy)' ,
                    'operator'  => 'or' ,
                    'type'      => 'select' ,
                    'choices'   => array(
                        array(
                            'value' => 'checkbox' ,
                            'label' => __( 'Checkbox' , ST_TEXTDOMAIN ) ,
                        ) ,
                        array(
                            'value' => 'select' ,
                            'label' => __( 'Select' , ST_TEXTDOMAIN ) ,
                        ) ,
                    )
                ) ,
                array(
                    'id'        => 'max_num' ,
                    'label'     => __( 'Max number' , ST_TEXTDOMAIN ) ,
                    'condition' => 'field_atrribute:is(list_name)' ,
                    'type'      => 'text' ,
                    'operator'  => 'and' ,
                    'std'   => 20
                ) ,
                array(
                    'id'       => 'is_required' ,
                    'label'    => __( 'Field required' , ST_TEXTDOMAIN ) ,
                    'type'     => 'on-off' ,
                    'operator' => 'and' ,
                    'std'      => 'on' ,
                ) ,
            ) ,
            'std'      => array(
                array( 'title' => 'Pick Up From, Drop Off To' , 'layout_col_normal' => 6 , 'field_atrribute' => 'location' ) ,
                array( 'title'             => 'Pick-up Date ,Pick-up Time' ,
                       'layout_col_normal' => 6 ,
                       'field_atrribute'   => 'pick-up-date-time'
                ) ,
                array( 'title'             => 'Drop-off Date ,Drop-off Time' ,
                       'layout_col_normal' => 6 ,
                       'field_atrribute'   => 'drop-off-date-time'
                ) ,
            )
        ) ,
        array(
            'id'       => 'car_search_fields_box' ,
            'label'    => __( 'Change Location & Date Box' , ST_TEXTDOMAIN ) ,
            'desc'     => __( 'Search fields in Change Location & Date in single car page' , ST_TEXTDOMAIN ) ,
            'type'     => 'Slider' ,
            'section'  => 'option_car' ,
            'settings' => array(


                array(
                    'id'       => 'field_atrribute' ,
                    'label'    => __( 'Field Atrribute' , ST_TEXTDOMAIN ) ,
                    'type'     => 'select' ,
                    'operator' => 'and' ,
                    'choices'  => STCars::get_search_fields_name()
                ) ,
                array(
                    'id'       => 'placeholder' ,
                    'label'    => __( 'Placeholder' , ST_TEXTDOMAIN ) ,
                    'desc'     => __( 'Placeholder' , ST_TEXTDOMAIN ) ,
                    'type'     => 'text' ,
                    'operator' => 'and' ,
                ) ,
                array(
                    'id'       => 'layout_col_box' ,
                    'label'    => __( 'Layout Box size' , ST_TEXTDOMAIN ) ,
                    'type'     => 'select' ,
                    'operator' => 'and' ,
                    'choices'  => array(
                        array(
                            'value' => '1' ,
                            'label' => __( 'column 1/12' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '2' ,
                            'label' => __( 'column 2/12' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '3' ,
                            'label' => __( 'column 3/12' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '4' ,
                            'label' => __( 'column 4/12' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '5' ,
                            'label' => __( 'column 5/12' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '6' ,
                            'label' => __( 'column 6/12' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '7' ,
                            'label' => __( 'column 7/12' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '8' ,
                            'label' => __( 'column 8/12' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '9' ,
                            'label' => __( 'column 9/12' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '10' ,
                            'label' => __( 'column 10/12' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '11' ,
                            'label' => __( 'column 11/12' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '12' ,
                            'label' => __( 'column 12/12' , ST_TEXTDOMAIN )
                        ) ,
                    ) ,
                    'std'      => 4
                ) ,
                array(
                    'id'        => 'taxonomy' ,
                    'label'     => __( 'Taxonomy' , ST_TEXTDOMAIN ) ,
                    'condition' => 'field_atrribute:is(taxonomy)' ,
                    'type'      => 'select' ,
                    'operator'  => 'and' ,
                    'choices'   => st_get_post_taxonomy( 'st_cars' )
                ) ,
                array(
                    'id'        => 'type_show_taxonomy_cars' ,
                    'label'     => __( 'Type show' , ST_TEXTDOMAIN ) ,
                    'condition' => 'field_atrribute:is(taxonomy)' ,
                    'operator'  => 'or' ,
                    'type'      => 'select' ,
                    'choices'   => array(
                        array(
                            'value' => 'checkbox' ,
                            'label' => __( 'Checkbox' , ST_TEXTDOMAIN ) ,
                        ) ,
                        array(
                            'value' => 'select' ,
                            'label' => __( 'Select' , ST_TEXTDOMAIN ) ,
                        ) ,
                    )
                ) ,
                array(
                    'id'        => 'max_num' ,
                    'label'     => __( 'Max number' , ST_TEXTDOMAIN ) ,
                    'condition' => 'field_atrribute:is(list_name)' ,
                    'type'      => 'text' ,
                    'operator'  => 'and' ,
                    'std'   => 20
                ) ,
                array(
                    'id'       => 'is_required' ,
                    'label'    => __( 'Field required' , ST_TEXTDOMAIN ) ,
                    'type'     => 'on-off' ,
                    'operator' => 'and' ,
                    'std'      => 'on' ,
                ) ,
            ) ,
            'std'      => array(
                array( 'title' => 'Pick Up From, Drop Off To' , 'layout_col_box' => 6 , 'field_atrribute' => 'location' ) ,
                array( 'title' => 'Pick-up Date' , 'layout_col_box' => 3 , 'field_atrribute' => 'pick-up-date' ) ,
                array( 'title' => 'Pick-up Time' , 'layout_col_box' => 3 , 'field_atrribute' => 'pick-up-time' ) ,
                array( 'title' => 'Drop-off Date' , 'layout_col_box' => 3 , 'field_atrribute' => 'drop-off-date' ) ,
                array( 'title' => 'Drop-off Time' , 'layout_col_box' => 3 , 'field_atrribute' => 'drop-off-time' ) ,

            )
        ) ,
        array(
            'id'      => 'car_review' ,
            'label'   => __( 'Enable Review' , ST_TEXTDOMAIN ) ,
            'type'    => 'on-off' ,
            'section' => 'option_car' ,
            'std'     => 'on'

        ) ,
        array(
            'id'        => 'car_review_stats' ,
            'label'     => __( 'Car Review Criteria' , ST_TEXTDOMAIN ) ,
            'desc'      => __( 'Car Review Criteria' , ST_TEXTDOMAIN ) ,
            'type'      => 'list-item' ,
            'section'   => 'option_car' ,
            'condition' => 'hotel_review:is(on)' ,
            'settings'  => array(
                array(
                    'id'       => 'name' ,
                    'label'    => __( 'Stat Name' , ST_TEXTDOMAIN ) ,
                    'type'     => 'textblock' ,
                    'operator' => 'and' ,
                )
            ) ,
            'std'       => array(

                array( 'title' => 'stat name 1' ) ,
                array( 'title' => 'stat name 2' ) ,
                array( 'title' => 'stat name 3' ) ,
                array( 'title' => 'stat name 4' ) ,
                array( 'title' => 'stat name 5' ) ,
            )
        ) ,
        array(
            'id'       => 'st_cars_unlimited_custom_field' ,
            'label'    => __( 'Car custom field' , ST_TEXTDOMAIN ) ,
            'type'     => 'Slider' ,
            'section'  => 'option_car' ,
            'desc'     => __( 'Car custom field' , ST_TEXTDOMAIN ) ,
            'settings' => array(
                array(
                    'id'       => 'type_field' ,
                    'label'    => __( 'Field type' , ST_TEXTDOMAIN ) ,
                    'type'     => 'select' ,
                    'operator' => 'and' ,
                    'choices'  => array(
                        array(
                            'value' => 'text' ,
                            'label' => __( 'Text flied' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => 'date-picker' ,
                            'label' => __( 'Date flied' , ST_TEXTDOMAIN )
                        ) ,
                    )

                ) ,
                array(
                    'id'       => 'default_field' ,
                    'label'    => __( 'Default' , ST_TEXTDOMAIN ) ,
                    'type'     => 'text' ,
                    'operator' => 'and'
                ) ,

            ) ,
        ) ,
        array(
            'id'      => 'st_cars_icon_map_marker',
            'label'   => __('Icon Marker Map', ST_TEXTDOMAIN),
            'desc'    => __( 'Icon Marker Map' , ST_TEXTDOMAIN ) ,
            'type'    => 'upload',
            'section' => 'option_car',
            'std'     => 'http://maps.google.com/mapfiles/marker_green.png'
        ),
        /*------------ End Car Option --------------*/


        /*------------ Begin Email Option --------------*/

        //        array(
        //            'id'          => 'email_enable_to_admin',
        //            'label'       => __( 'Enable Email to Administator', ST_TEXTDOMAIN ),
        //            'type'        => 'on-off',
        //            'section'     => 'option_email',
        //            'desc'        =>__('Enable Email to Administator about new Booking',ST_TEXTDOMAIN),
        //            'std'         =>'on'
        //
        //        ),

        //        array(
        //            'id'          => 'email_subject',
        //            'label'       => __( 'Email Subject', ST_TEXTDOMAIN ),
        //            'type'        => 'text',
        //            'section'     => 'option_email',
        //            'desc'        =>__('Email Subject',ST_TEXTDOMAIN),
        //
        //        ),

        array(
            'id'      => 'email_from' ,
            'label'   => __( 'Email From Name' , ST_TEXTDOMAIN ) ,
            'desc'    => __( 'Email From Name' , ST_TEXTDOMAIN ) ,
            'type'    => 'text' ,
            'section' => 'option_email' ,
            'std'     => 'Traveler Shinetheme'

        ) ,
        array(
            'id'      => 'email_from_address' ,
            'label'   => __( 'Email From Address' , ST_TEXTDOMAIN ) ,
            'desc'    => __( 'Email From Address' , ST_TEXTDOMAIN ) ,
            'type'    => 'text' ,
            'section' => 'option_email' ,
            'std'     => 'traveler@shinetheme.com'

        )
        ,
        array(
            'id'      => 'email_logo' ,
            'label'   => __( 'Logo in Email' , ST_TEXTDOMAIN ) ,
            'type'    => 'upload' ,
            'section' => 'option_email' ,
            'desc'    => __( 'Logo in Email' , ST_TEXTDOMAIN ) ,
            'std'     => get_template_directory_uri() . '/img/logo.png'

        ) ,
        array(
            'id'      => 'enable_email_for_custommer' ,
            'label'   => __( 'Email to customers after booking ' , ST_TEXTDOMAIN ) ,
            'desc'    => __( 'Email to customers after booking ' , ST_TEXTDOMAIN ) ,
            'type'    => 'on-off' ,
            'std'     => 'on' ,
            'section' => 'option_email' ,
        ) ,
        array(
            'id'      => 'enable_email_confirm_for_customer' ,
            'label'   => __( 'Email Confirm to customers after booking ' , ST_TEXTDOMAIN ) ,
            'desc'    => __( 'Email Confirm to customers after booking ' , ST_TEXTDOMAIN ) ,
            'type'    => 'on-off' ,
            'std'     => 'on' ,
            'section' => 'option_email' ,
            'condition' => 'enable_email_for_custommer:is(on)' ,
        ) ,
        array(
            'id'      => 'enable_email_for_admin' ,
            'label'   => __( 'Email to Admin after booking ' , ST_TEXTDOMAIN ) ,
            'desc'    => __( 'Email to Admin after booking ' , ST_TEXTDOMAIN ) ,
            'type'    => 'on-off' ,
            'std'     => 'on' ,
            'section' => 'option_email' ,
        ) ,
        array(
            'id'        => 'email_admin_address' ,
            'label'     => __( 'Admin\' Email Address' , ST_TEXTDOMAIN ) ,
            'desc'      => __( 'Booking information will be sent to here' , ST_TEXTDOMAIN ) ,
            'type'      => 'text' ,
            'condition' => 'enable_email_for_admin:is(on)' ,
            'section'   => 'option_email' ,
        ) ,
        array(
            'id'      => 'enable_email_for_owner_item' ,
            'label'   => __( 'Email after booking for Owner Item' , ST_TEXTDOMAIN ) ,
            'desc'    => __( 'Email after booking for Owner Item' , ST_TEXTDOMAIN ) ,
            'type'    => 'on-off' ,
            'std'     => 'on' ,
            'section' => 'option_email' ,
        ) ,
        /*------------ End Email Option --------------*/
        /*-------------Email Template ----------------*/
        array(
            'id'      => 'tab_email_document' ,
            'label'   => __('Email Documents', ST_TEXTDOMAIN ) ,
            'type'    => 'tab',
            'section' => 'option_email_template',
        ),
        array(
            'id'      => 'email_document' ,
            'label'   => __('Email Documents', ST_TEXTDOMAIN ) ,
            'type'    => 'email_template_document',
            'section' => 'option_email_template',
        ),
        array(
            'id'      => 'tab_email_for_admin' ,
            'label'   => __('Email For Admin', ST_TEXTDOMAIN ) ,
            'type'    => 'tab',
            'section' => 'option_email_template',
        ),
        array(
            'id'      => 'email_for_admin' ,
            'label'   => __('Email For Admin', ST_TEXTDOMAIN ) ,
            'type'    => 'textarea',
            'rows' => '10',
            'section' => 'option_email_template',
            'std'   => function_exists('st_default_email_template_admin')? st_default_email_template_admin():false
        ),

        array(
            'id'      => 'tab_email_for_partner' ,
            'label'   => __('Email For Partner', ST_TEXTDOMAIN ) ,
            'type'    => 'tab',
            'section' => 'option_email_template',
        ),
        array(
            'id'      => 'email_for_partner' ,
            'label'   => __('Email For Partner', ST_TEXTDOMAIN ) ,
            'type'    => 'textarea',
            'rows' => '50',
            'section' => 'option_email_template',
            'std'   => function_exists('st_default_email_template_partner')?st_default_email_template_partner():false
        ),
        array(
            'id'      => 'tab_email_for_customer' ,
            'label'   => __('Email For Customer', ST_TEXTDOMAIN ) ,
            'type'    => 'tab',
            'section' => 'option_email_template',
        ),
        array(
            'id'      => 'email_for_customer' ,
            'label'   => __('Email For Customer', ST_TEXTDOMAIN ) ,
            'type'    => 'textarea',
            'rows' => '50',
            'section' => 'option_email_template',
            'std'   => function_exists('st_default_email_template_customer')? st_default_email_template_customer():false
        ),
        array(
            'id'      => 'tab_email_confirm' ,
            'label'   => __('Email Confirm', ST_TEXTDOMAIN ) ,
            'type'    => 'tab',
            'section' => 'option_email_template',
        ),
        array(
            'id'      => 'email_confirm' ,
            'label'   => __('Email Confirm', ST_TEXTDOMAIN ) ,
            'type'    => 'textarea',
            'rows' => '50',
            'section' => 'option_email_template',
            'std' => function_exists('get_email_confirm_template')?get_email_confirm_template():false
        ),

        /*------------- End Email Template ----------------*/

        /*------------- Activity - Tour Option  -----------------*/
        array(
            'id'      => 'tour_show_calendar' ,
            'label'   => __( 'Show Calendar' , ST_TEXTDOMAIN ) ,
            'type'    => 'on-off' ,
            'section' => 'option_activity_tour' ,
            'std'     => 'on'

        ) ,
        array(
            'id'      => 'tour_show_calendar_below' ,
            'label'   => __( 'Show Below' , ST_TEXTDOMAIN ) ,
            'type'    => 'on-off' ,
            'section' => 'option_activity_tour' ,
            'std'     => 'off',
            'condition' => 'tour_show_calendar:is(on)' ,
        ) ,
        array(
            'id'      => 'activity_tour_review' ,
            'label'   => __( 'Enable Review' , ST_TEXTDOMAIN ) ,
            'type'    => 'on-off' ,
            'section' => 'option_activity_tour' ,
            'std'     => 'std'

        ) ,

        array(
            'id'        => 'tour_review_stats' ,
            'label'     => __( 'Review Criteria' , ST_TEXTDOMAIN ) ,
            'desc'      => __( 'Review Criteria' , ST_TEXTDOMAIN ) ,
            'type'      => 'list-item' ,
            'section'   => 'option_activity_tour' ,
            'condition' => 'activity_tour_review:is(on)' ,
            'settings'  => array(
                array(
                    'id'       => 'name' ,
                    'label'    => __( 'Stat Name' , ST_TEXTDOMAIN ) ,
                    'type'     => 'textblock' ,
                    'operator' => 'and' ,
                )
            ) ,
            'std'       => array(

                array( 'title' => 'Sleep' ) ,
                array( 'title' => 'Location' ) ,
                array( 'title' => 'Service' ) ,
                array( 'title' => 'Cleanliness' ) ,
                array( 'title' => 'Room(s)' ) ,
            )
        ) ,
        array(
            'id'      => 'tours_search_result_page' ,
            'label'   => __( 'Search Result Page' , ST_TEXTDOMAIN ) ,
            'type'    => 'page-select' ,
            'section' => 'option_activity_tour' ,
        ) ,
        array(
            'id'      => 'tours_layout' ,
            'label'   => __( 'Tour Layout' , ST_TEXTDOMAIN ) ,
            'type'    => 'select' ,
            'section' => 'option_activity_tour' ,
            'choices' => st_get_layout( 'st_tours' )
        ) ,
        array(
            'id'      => 'tours_search_layout' ,
            'label'   => __( 'Tour Search Layout' , ST_TEXTDOMAIN ) ,
            'type'    => 'select' ,
            'section' => 'option_activity_tour' ,
            'choices' => st_get_layout( 'st_tours_search' )
        ) ,
        array(
            'id'      => 'tour_sidebar_pos' ,
            'label'   => __( 'Sidebar Position' , ST_TEXTDOMAIN ) ,
            'desc'    => __( 'Just apply for default search layout' , ST_TEXTDOMAIN ) ,
            'type'    => 'select' ,
            'section' => 'option_activity_tour' ,
            'condition' => 'tours_search_layout:is()',
            'choices' => array(
                array(
                    'value' => 'no' ,
                    'label' => __( 'No' , ST_TEXTDOMAIN )
                ) ,
                array(
                    'value' => 'left' ,
                    'label' => __( 'Left' , ST_TEXTDOMAIN )
                ) ,
                array(
                    'value' => 'right' ,
                    'label' => __( 'Right' , ST_TEXTDOMAIN )
                )

            ) ,
            'std'     => 'left'
        ),
        array(
            'id'      => 'tours_similar_tour' ,
            'label'   => __( 'Number of Similar Tours' , ST_TEXTDOMAIN ) ,
            'type'    => 'text' ,
            'section' => 'option_activity_tour' ,
            'std'     => '5' ,
            'desc'    => __( 'Number of Similar Tours' , ST_TEXTDOMAIN )
        ) ,
        array(
            'id'        => 'activity_tour_check_review_admin' ,
            'label'     => __( 'Review must be approved by admin' , ST_TEXTDOMAIN ) ,
            'type'      => 'on-off' ,
            'section'   => 'option_activity_tour' ,
            'condition' => 'activity_tour_review:is(on)' ,
        ) ,
        array(
            'id'      => 'is_featured_search_tour' ,
            'label'   => __( 'Show featured tours on top of search result' , ST_TEXTDOMAIN ) ,
            'type'    => 'on-off' ,
            'std'     => 'off' ,
            'section' => 'option_activity_tour'
        ) ,
        array(
            'id'       => 'activity_tour_search_fields' ,
            'label'    => __( 'Tour Search Fields' , ST_TEXTDOMAIN ) ,
            'desc'     => __( 'Tour Search Fields' , ST_TEXTDOMAIN ) ,
            'type'     => 'Slider' ,
            'section'  => 'option_activity_tour' ,
            'settings' => array(

                array(
                    'id'       => 'tours_field_search' ,
                    'label'    => __( 'Field Type' , ST_TEXTDOMAIN ) ,
                    'type'     => 'select' ,
                    'operator' => 'and' ,
                    'choices'  => STTour::get_search_fields_name() ,
                ) ,
                array(
                    'id'       => 'placeholder' ,
                    'label'    => __( 'Placeholder' , ST_TEXTDOMAIN ) ,
                    'desc'     => __( 'Placeholder' , ST_TEXTDOMAIN ) ,
                    'type'     => 'text' ,
                    'operator' => 'and' ,
                ) ,
                array(
                    'id'       => 'layout_col' ,
                    'label'    => __( 'Layout 1 size' , ST_TEXTDOMAIN ) ,
                    'type'     => 'select' ,
                    'operator' => 'and' ,
                    'choices'  => array(
                        array(
                            'value' => '1' ,
                            'label' => __( 'column 1' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '2' ,
                            'label' => __( 'column 2' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '3' ,
                            'label' => __( 'column 3' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '4' ,
                            'label' => __( 'column 4' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '5' ,
                            'label' => __( 'column 5' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '6' ,
                            'label' => __( 'column 6' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '7' ,
                            'label' => __( 'column 7' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '8' ,
                            'label' => __( 'column 8' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '9' ,
                            'label' => __( 'column 9' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '10' ,
                            'label' => __( 'column 10' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '11' ,
                            'label' => __( 'column 11' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '12' ,
                            'label' => __( 'column 12' , ST_TEXTDOMAIN )
                        ) ,
                    ) ,
                    'std'      => 4
                ) ,
                array(
                    'id'       => 'layout2_col' ,
                    'label'    => __( 'Layout 2 Size' , ST_TEXTDOMAIN ) ,
                    'type'     => 'select' ,
                    'operator' => 'and' ,
                    'std'      => 4 ,
                    'choices'  => array(
                        array(
                            'value' => '1' ,
                            'label' => __( 'column 1' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '2' ,
                            'label' => __( 'column 2' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '3' ,
                            'label' => __( 'column 3' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '4' ,
                            'label' => __( 'column 4' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '5' ,
                            'label' => __( 'column 5' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '6' ,
                            'label' => __( 'column 6' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '7' ,
                            'label' => __( 'column 7' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '8' ,
                            'label' => __( 'column 8' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '9' ,
                            'label' => __( 'column 9' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '10' ,
                            'label' => __( 'column 10' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '11' ,
                            'label' => __( 'column 11' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '12' ,
                            'label' => __( 'column 12' , ST_TEXTDOMAIN )
                        ) ,
                    ) ,
                    'std'      => 4
                ) ,
                array(
                    'id'        => 'taxonomy' ,
                    'label'     => __( 'Taxonomy' , ST_TEXTDOMAIN ) ,
                    'condition' => 'tours_field_search:is(taxonomy)' ,
                    'type'      => 'select' ,
                    'operator'  => 'and' ,
                    'choices'   => st_get_post_taxonomy( 'st_tours' )
                ) ,
                array(
                    'id'        => 'type_show_taxonomy_tours' ,
                    'label'     => __( 'Type show' , ST_TEXTDOMAIN ) ,
                    'condition' => 'tours_field_search:is(taxonomy)' ,
                    'operator'  => 'or' ,
                    'type'      => 'select' ,
                    'choices'   => array(
                        array(
                            'value' => 'checkbox' ,
                            'label' => __( 'Checkbox' , ST_TEXTDOMAIN ) ,
                        ) ,
                        array(
                            'value' => 'select' ,
                            'label' => __( 'Select' , ST_TEXTDOMAIN ) ,
                        ) ,
                    )
                ) ,
                array(
                    'id'        => 'max_num' , 
                    'label'     => __("Max number" , ST_TEXTDOMAIN),
                    'condition' => 'tours_field_search:is(list_name)',
                    'type'  => "text",
                    'std'   => 20
                    ) , 
                array(
                    'id'       => 'is_required' ,
                    'label'    => __( 'Field required' , ST_TEXTDOMAIN ) ,
                    'type'     => 'on-off' ,
                    'operator' => 'and' ,
                    'std'      => 'on' ,
                ) ,
            ) ,
            'std'      => array(
                array( 'title'              => 'Address' ,
                       'layout_col'         => 6 ,
                       'layout2_col'        => 6 ,
                       'tours_field_search' => 'address'
                ) ,
                array( 'title'              => 'Departure date' ,
                       'layout_col'         => 3 ,
                       'layout2_col'        => 3 ,
                       'tours_field_search' => 'check_in'
                ) ,
                array( 'title'              => 'Arrival Date' ,
                       'layout_col'         => 3 ,
                       'layout2_col'        => 3 ,
                       'tours_field_search' => 'check_out'
                ) ,
            )
        ) ,
        array(
            'id'      => 'st_show_number_user_book',
            'label'   => __('Show No. Users who booked', ST_TEXTDOMAIN),
            'desc'    => __( 'Show No. Users who booked' , ST_TEXTDOMAIN ) ,
            'type'    => 'on-off',
            'section' => 'option_activity_tour',
            'std'     => 'off'
        ),
        array(
            'id'       => 'tours_unlimited_custom_field' ,
            'label'    => __( 'Tour custom field' , ST_TEXTDOMAIN ) ,
            'type'     => 'Slider' ,
            'section'  => 'option_activity_tour' ,
            'desc'     => __( 'Tour custom field' , ST_TEXTDOMAIN ) ,
            'settings' => array(
                array(
                    'id'       => 'type_field' ,
                    'label'    => __( 'Field type' , ST_TEXTDOMAIN ) ,
                    'type'     => 'select' ,
                    'operator' => 'and' ,
                    'choices'  => array(
                        array(
                            'value' => 'text' ,
                            'label' => __( 'Text field' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => 'textarea' ,
                            'label' => __( 'Textarea field' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => 'date-picker' ,
                            'label' => __( 'Date field' , ST_TEXTDOMAIN )
                        ) ,
                    )

                ) ,
                array(
                    'id'       => 'default_field' ,
                    'label'    => __( 'Default' , ST_TEXTDOMAIN ) ,
                    'type'     => 'text' ,
                    'operator' => 'and'
                ) ,

            ) ,
        ) ,
        array(
            'id'      => 'st_tours_icon_map_marker',
            'label'   => __('Icon Marker Map', ST_TEXTDOMAIN),
            'desc'    => __( 'Icon Marker Map' , ST_TEXTDOMAIN ) ,
            'type'    => 'upload',
            'section' => 'option_activity_tour',
            'std'     => 'http://maps.google.com/mapfiles/marker_purple.png'
        ),
        /*------------- Activity - Tour Option  -----------------*/

        /*------------- Activity Option  -----------------*/
        array(
            'id'      => 'activity_search_result_page' ,
            'label'   => __( 'Search Result Page' , ST_TEXTDOMAIN ) ,
            'type'    => 'page-select' ,
            'section' => 'option_activity' ,
        ) ,
        array(
            'id'      => 'activity_review' ,
            'label'   => __( 'Enable Review' , ST_TEXTDOMAIN ) ,
            'type'    => 'on-off' ,
            'section' => 'option_activity' ,
            'std'     => 'std'

        ) ,
        array(
            'id'        => 'activity_review_stats' ,
            'label'     => __( 'Review Criteria' , ST_TEXTDOMAIN ) ,
            'desc'      => __( 'Review Criteria' , ST_TEXTDOMAIN ) ,
            'type'      => 'list-item' ,
            'section'   => 'option_activity' ,
            'condition' => 'activity_review:is(on)' ,
            'settings'  => array(
                array(
                    'id'       => 'name' ,
                    'label'    => __( 'Stat Name' , ST_TEXTDOMAIN ) ,
                    'type'     => 'textblock' ,
                    'operator' => 'and' ,
                )
            ) ,
            'std'       => array(

                array( 'title' => 'Sleep' ) ,
                array( 'title' => 'Location' ) ,
                array( 'title' => 'Service' ) ,
                array( 'title' => 'Cleanliness' ) ,
                array( 'title' => 'Room(s)' ) ,
            )
        ) ,
        array(
            'id'      => 'activity_layout' ,
            'label'   => __( 'Activity Layout' , ST_TEXTDOMAIN ) ,
            'type'    => 'select' ,
            'section' => 'option_activity' ,
            'choices' => st_get_layout( 'st_activity' )
        ) ,
        array(
            'id'      => 'activity_search_layout' ,
            'label'   => __( 'Activity Search Layout' , ST_TEXTDOMAIN ) ,
            'type'    => 'select' ,
            'section' => 'option_activity' ,
            'choices' => st_get_layout( 'st_activity_search' )
        ) ,
        array(
            'id'      => 'activity_sidebar_pos' ,
            'label'   => __( 'Sidebar Position' , ST_TEXTDOMAIN ) ,
            'desc'    => __( 'Just apply for default search layout' , ST_TEXTDOMAIN ) ,
            'type'    => 'select' ,
            'section' => 'option_activity' ,
            'condition' => 'activity_search_layout:is()',
            'choices' => array(
                array(
                    'value' => 'no' ,
                    'label' => __( 'No' , ST_TEXTDOMAIN )
                ) ,
                array(
                    'value' => 'left' ,
                    'label' => __( 'Left' , ST_TEXTDOMAIN )
                ) ,
                array(
                    'value' => 'right' ,
                    'label' => __( 'Right' , ST_TEXTDOMAIN )
                )

            ) ,
            'std'     => 'left'
        ),
        array(
            'id'      => 'is_featured_search_activity' ,
            'label'   => __( 'Feature only top search' , ST_TEXTDOMAIN ) ,
            'type'    => 'on-off' ,
            'std'     => 'off' ,
            'section' => 'option_activity'
        ) ,
        array(
            'id'       => 'activity_search_fields' ,
            'label'    => __( 'Activity Search Fields' , ST_TEXTDOMAIN ) ,
            'desc'     => __( 'Activity Search Fields' , ST_TEXTDOMAIN ) ,
            'type'     => 'Slider' ,
            'section'  => 'option_activity' ,
            'settings' => array(

                array(
                    'id'       => 'activity_field_search' ,
                    'label'    => __( 'Field Type' , ST_TEXTDOMAIN ) ,
                    'type'     => 'select' ,
                    'operator' => 'and' ,
                    'choices'  => STActivity::get_search_fields_name()
                ) ,
                array(
                    'id'       => 'placeholder' ,
                    'label'    => __( 'Placeholder' , ST_TEXTDOMAIN ) ,
                    'desc'     => __( 'Placeholder' , ST_TEXTDOMAIN ) ,
                    'type'     => 'text' ,
                    'operator' => 'and' ,
                ) ,
                array(
                    'id'       => 'layout_col' ,
                    'label'    => __( 'Layout 1 size' , ST_TEXTDOMAIN ) ,
                    'type'     => 'select' ,
                    'operator' => 'and' ,
                    'choices'  => array(
                        array(
                            'value' => '1' ,
                            'label' => __( 'column 1' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '2' ,
                            'label' => __( 'column 2' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '3' ,
                            'label' => __( 'column 3' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '4' ,
                            'label' => __( 'column 4' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '5' ,
                            'label' => __( 'column 5' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '6' ,
                            'label' => __( 'column 6' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '7' ,
                            'label' => __( 'column 7' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '8' ,
                            'label' => __( 'column 8' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '9' ,
                            'label' => __( 'column 9' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '10' ,
                            'label' => __( 'column 10' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '11' ,
                            'label' => __( 'column 11' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '12' ,
                            'label' => __( 'column 12' , ST_TEXTDOMAIN )
                        ) ,
                    ) ,
                    'std'      => 4
                ) ,
                array(
                    'id'       => 'layout2_col' ,
                    'label'    => __( 'Layout 2 Size' , ST_TEXTDOMAIN ) ,
                    'type'     => 'select' ,
                    'operator' => 'and' ,
                    'std'      => 4 ,
                    'choices'  => array(
                        array(
                            'value' => '1' ,
                            'label' => __( 'column 1' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '2' ,
                            'label' => __( 'column 2' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '3' ,
                            'label' => __( 'column 3' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '4' ,
                            'label' => __( 'column 4' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '5' ,
                            'label' => __( 'column 5' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '6' ,
                            'label' => __( 'column 6' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '7' ,
                            'label' => __( 'column 7' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '8' ,
                            'label' => __( 'column 8' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '9' ,
                            'label' => __( 'column 9' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '10' ,
                            'label' => __( 'column 10' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '11' ,
                            'label' => __( 'column 11' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '12' ,
                            'label' => __( 'column 12' , ST_TEXTDOMAIN )
                        ) ,
                    ) ,
                    'std'      => 4
                ) ,
                array(
                    'id'        => 'taxonomy' ,
                    'label'     => __( 'Taxonomy' , ST_TEXTDOMAIN ) ,
                    'condition' => 'activity_field_search:is(taxonomy)' ,
                    'type'      => 'select' ,
                    'operator'  => 'and' ,
                    'choices'   => st_get_post_taxonomy( 'st_activity' )
                ) ,
                array(
                    'id'        => 'type_show_taxonomy_activity' ,
                    'label'     => __( 'Type show' , ST_TEXTDOMAIN ) ,
                    'condition' => 'activity_field_search:is(taxonomy)' ,
                    'operator'  => 'or' ,
                    'type'      => 'select' ,
                    'choices'   => array(
                        array(
                            'value' => 'checkbox' ,
                            'label' => __( 'Checkbox' , ST_TEXTDOMAIN ) ,
                        ) ,
                        array(
                            'value' => 'select' ,
                            'label' => __( 'Select' , ST_TEXTDOMAIN ) ,
                        ) ,
                    )
                ) ,
                array(
                    'id'        => 'max_num' ,
                    'label'     => __( 'Max number' , ST_TEXTDOMAIN ) ,
                    'condition' => 'activity_field_search:is(list_name)' ,
                    'type'      => 'text' ,
                    'operator'  => 'and' ,
                    'std'   => '20'
                ) ,
                array(
                    'id'       => 'is_required' ,
                    'label'    => __( 'Field required' , ST_TEXTDOMAIN ) ,
                    'type'     => 'on-off' ,
                    'operator' => 'and' ,
                    'std'      => 'on' ,
                ) ,
            ) ,
            'std'      => array(
                array( 'title'                 => 'Address' ,
                       'layout_col'            => 3 ,
                       'layout2_col'           => 6 ,
                       'activity_field_search' => 'address'
                ) ,
                array( 'title'                 => 'From' ,
                       'layout_col'            => 3 ,
                       'layout2_col'           => 3 ,
                       'activity_field_search' => 'check_in'
                ) ,
                array( 'title'                 => 'To' ,
                       'layout_col'            => 3 ,
                       'layout2_col'           => 3 ,
                       'activity_field_search' => 'check_out'
                ) ,
            )
        ) ,
        
        array(
            'id'       => 'st_activity_unlimited_custom_field' ,
            'label'    => __( 'Activity custom field' , ST_TEXTDOMAIN ) ,
            'type'     => 'Slider' ,
            'section'  => 'option_activity' ,
            'desc'     => __( 'Activity custom field' , ST_TEXTDOMAIN ) ,
            'settings' => array(
                array(
                    'id'       => 'type_field' ,
                    'label'    => __( 'Field type' , ST_TEXTDOMAIN ) ,
                    'type'     => 'select' ,
                    'operator' => 'and' ,
                    'choices'  => array(
                        array(
                            'value' => 'text' ,
                            'label' => __( 'Text field' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => 'textarea' ,
                            'label' => __( 'Textarea field' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => 'date-picker' ,
                            'label' => __( 'Date field' , ST_TEXTDOMAIN )
                        ) ,
                    )

                ) ,
                array(
                    'id'       => 'default_field' ,
                    'label'    => __( 'Default' , ST_TEXTDOMAIN ) ,
                    'type'     => 'text' ,
                    'operator' => 'and'
                ) ,
            ) ,
        ) ,
        array(
            'id'      => 'st_activity_icon_map_marker',
            'label'   => __('Icon Marker Map', ST_TEXTDOMAIN),
            'desc'    => __( 'Icon Marker Map' , ST_TEXTDOMAIN ) ,
            'type'    => 'upload',
            'section' => 'option_activity',
            'std'     => 'http://maps.google.com/mapfiles/marker_yellow.png'
        ),
        /*------------- Activity  Option  -----------------*/
        /*-------------- Location Option -----------------*/

        
        /*-------------- Location Option -----------------*/
        /*------------- Option Partner Option --------------------*/

        array(
            'id'      => 'partner_enable_feature' ,
            'label'   => __( 'Enable Partner Feature' , ST_TEXTDOMAIN ) ,
            'type'    => 'on-off' ,
            'section' => 'option_partner' ,
            'std'     => 'off' ,
        ) ,
        array(
            'id'      => 'partner_post_by_admin' ,
            'label'   => __( 'Partner\'s Post must be aprroved by admin' , ST_TEXTDOMAIN ) ,
            'type'    => 'on-off' ,
            'section' => 'option_partner' ,
            'std'     => 'on'
        ) ,
        array(
            'id'      => 'register_set_auto_for_partner' ,
            'label'   => __( '[Register] Set Auto for Partner' , ST_TEXTDOMAIN ) ,
            'type'    => 'on-off' ,
            'section' => 'option_partner' ,
            'std'     => 'off'
        ) ,
        array(
            'id'      => 'admin_menu_partner' ,
            'label'   => __( 'Enable partner admin bar' , ST_TEXTDOMAIN ) ,
            'type'    => 'on-off' ,
            'section' => 'option_partner' ,
            'std'     => 'off'
        ) ,
        array(
            'id'       => 'list_partner' ,
            'label'    => __( 'Partner\'s accessible functions' , ST_TEXTDOMAIN ) ,
            'desc'     => __( 'Partner\'s accessible functions' , ST_TEXTDOMAIN ) ,
            'type'     => 'list-item' ,
            'section'  => 'option_partner' ,
            'settings' => array(
                array(
                    'id'      => 'id_partner' ,
                    'label'   => __( 'Select functions' , ST_TEXTDOMAIN ) ,
                    'type'    => 'select' ,
                    'desc'    => __( 'Select functions' , ST_TEXTDOMAIN ) ,
                    'choices' => array(
                        array(
                            'value' => 'hotel' ,
                            'label' => __( 'Hotel' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => 'rental' ,
                            'label' => __( 'Rental' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => 'car' ,
                            'label' => __( 'Car' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => 'tour' ,
                            'label' => __( 'Tour' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => 'activity' ,
                            'label' => __( 'Activity' , ST_TEXTDOMAIN )
                        ) ,
                    )
                )
            ) ,
            'std'      => array(
                array(
                    'title'      => 'Hotel' ,
                    'id_partner' => 'hotel' ,
                ) ,
                array(
                    'title'      => 'Rental' ,
                    'id_partner' => 'rental' ,
                ) ,
                array(
                    'title'      => 'Car' ,
                    'id_partner' => 'car' ,
                ) ,
                array(
                    'title'      => 'Tour' ,
                    'id_partner' => 'tour' ,
                ) ,
                array(
                    'title'      => 'Activity' ,
                    'id_partner' => 'activity' ,
                ) ,

            )
        ) ,
        array(
            'id'       => 'partner_commission' ,
            'label'    => __( 'Commission (%)' , ST_TEXTDOMAIN ) ,
            'desc'     => __( 'Commission (%)' , ST_TEXTDOMAIN ) ,
            'type'     => 'numeric-slider' ,
            'section'  => 'option_partner' ,
            'min_max_step' => '0,100,1' ,
        ) ,
        array(
            'id'        => 'partner_set_feature',
            'label'     => 'Partner can set featured',
            'section'   => 'option_partner',
            'type'      =>'on-off',
            'desc'      =>'<strong>Enable</strong> to allow partner set feature of post',
            'std'       =>'off'
            ),
        /*------------- End Option Partner Option --------------------*/

        /*------------- Search Option -----------------*/
        array(
            'id'      => 'search_enable_preload' ,
            'label'   => __( 'Enable Preload' , ST_TEXTDOMAIN ) ,
            'desc'    => __( 'Enable Preload' , ST_TEXTDOMAIN ) ,
            'type'    => 'on-off' ,
            'section' => 'option_search' ,
            'std'     => 'on'
        ) ,
        array(
            'id'        => 'search_preload_image' ,
            'label'     => __( 'Search Preload Image' , ST_TEXTDOMAIN ) ,
            'desc'      => __( 'Search Preload Image' , ST_TEXTDOMAIN ) ,
            'type'      => 'upload' ,
            'section'   => 'option_search' ,
            'condition' => 'search_enable_preload:is(on)'
        ) ,
        array(
            'id'      => 'search_results_view' ,
            'label'   => __( 'Default search result style' , ST_TEXTDOMAIN ) ,
            'type'    => 'select' ,
            'section' => 'option_search' ,
            'desc'    => __( 'List view or Grid view' , ST_TEXTDOMAIN ) ,
            'choices' => array(
                array(
                    'value' => 'list' ,
                    'label' => __( 'List view' , ST_TEXTDOMAIN )
                ) ,
                array(
                    'value' => 'grid' ,
                    'label' => __( 'Grid view' , ST_TEXTDOMAIN )
                ) ,
            )
        ) ,
        //        array(
        //            'id'          => 'search_price_range_min',
        //            'label'       => __( 'Price Range Min', ST_TEXTDOMAIN ),
        //            'type'        => 'text',
        //            'section'     => 'option_search',
        //            'desc'        => __('Minimum value of price range used in search form (usually 0)',ST_TEXTDOMAIN),
        //            'std'         => '0'
        //        ),
        //        array(
        //            'id'          => 'search_price_range_max',
        //            'label'       => __( 'Price Range Max', ST_TEXTDOMAIN ),
        //            'type'        => 'text',
        //            'section'     => 'option_search',
        //            'desc'        => __('Maximum value of price range used in search form',ST_TEXTDOMAIN),
        //            'std'         => '500'
        //        ),
        //        array(
        //            'id'          => 'search_price_range_step',
        //            'label'       => __( 'Price Range Step', ST_TEXTDOMAIN ),
        //            'type'        => 'text',
        //            'section'     => 'option_search',
        //            'desc'        => __('Step value of price range used in search form',ST_TEXTDOMAIN),
        //            'std'         => '0'
        //        ),
        array(
            'id'       => 'search_tabs' ,
            'label'    => __( 'Search Tabs:' , ST_TEXTDOMAIN ) ,
            'desc'     => __( 'Search Tabs on home page' , ST_TEXTDOMAIN ) ,
            'type'     => 'list-item' ,
            'section'  => 'option_search' ,
            'settings' => array(
                array(
                    'id'    => 'check_tab' ,
                    'label' => __( 'Show tab' , ST_TEXTDOMAIN ) ,
                    'type'  => 'on-off' ,
                ) ,
                array(
                    'id'    => 'tab_icon' ,
                    'label' => __( 'Icon' , ST_TEXTDOMAIN ) ,
                    'type'  => 'text' ,
                    'desc'  => __( 'This allows you to change icon next to the title' , ST_TEXTDOMAIN )
                ) ,
                array(
                    'id'    => 'tab_search_title' ,
                    'label' => __( 'Form Title' , ST_TEXTDOMAIN ) ,
                    'type'  => 'text' ,
                    'desc'  => __( 'This allows you to change the text above the form' , ST_TEXTDOMAIN )
                ) ,
                array(
                    'id'      => 'tab_name' ,
                    'label'   => __( 'Choose Tab' , ST_TEXTDOMAIN ) ,
                    'type'    => 'select' ,
                    'choices' => array(
                        array(
                            'value' => 'hotel' ,
                            'label' => __( 'Hotel' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => 'rental' ,
                            'label' => __( 'Rental' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => 'tour' ,
                            'label' => __( 'Tour' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => 'cars' ,
                            'label' => __( 'Car' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => 'activities' ,
                            'label' => __( 'Activities' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => 'all_post_type' ,
                            'label' => __( 'All Post Type' , ST_TEXTDOMAIN )
                        )
                    )
                ),
                array(
                    'id'    => 'tab_html_custom' ,
                    'label' => __( 'Use HTML bellow' , ST_TEXTDOMAIN ) ,
                    'type'  => 'textarea' ,
                    'desc'  => __( 'This allows you to do short code or HTML' , ST_TEXTDOMAIN )
                ) ,
            ) ,
            'std'      => array(
                array(
                    'title'            => 'Hotel' ,
                    'check_tab'        => 'on' ,
                    'tab_icon'         => 'fa-building-o' ,
                    'tab_search_title' => 'Search and Save on Hotels' ,
                    'tab_name'         => 'hotel'
                ) ,
                array(
                    'title'            => 'Cars' ,
                    'check_tab'        => 'on' ,
                    'tab_icon'         => 'fa-car' ,
                    'tab_search_title' => 'Search for Cheap Rental Cars' ,
                    'tab_name'         => 'cars'
                ) ,
                array(
                    'title'            => 'Tours' ,
                    'check_tab'        => 'on' ,
                    'tab_icon'         => 'fa-flag-o' ,
                    'tab_search_title' => 'Tours' ,
                    'tab_name'         => 'tour'
                ) ,
                array(
                    'title'            => 'Rentals' ,
                    'check_tab'        => 'on' ,
                    'tab_icon'         => 'fa-home' ,
                    'tab_search_title' => 'Find Your Perfect Home' ,
                    'tab_name'         => 'rental'
                ) ,
                array(
                    'title'            => 'Activity' ,
                    'check_tab'        => 'on' ,
                    'tab_icon'         => 'fa-bolt' ,
                    'tab_search_title' => 'Find Your Perfect Activity' ,
                    'tab_name'         => 'activities'
                ) ,
            )
        ) ,
        array(
            'id'      => 'all_post_type_search_result_page' ,
            'label'   => __( 'All Post Type Search Result Page' , ST_TEXTDOMAIN ) ,
            'type'    => 'page-select' ,
            'section' => 'option_search' ,
        ) ,
        array(
            'id'       => 'all_post_type_search_fields' ,
            'label'    => __( 'All Post Type Search Fields' , ST_TEXTDOMAIN ) ,
            'desc'     => __( 'All Post Type Search Fields' , ST_TEXTDOMAIN ) ,
            'type'     => 'Slider' ,
            'section'  => 'option_search' ,
            'settings' => array(
                array(
                    'id'       => 'field_search' ,
                    'label'    => __( 'Field Type' , ST_TEXTDOMAIN ) ,
                    'type'     => 'select' ,
                    'operator' => 'and' ,
                    'choices'  => array(
                        array(
                            'value' => 'address' ,
                            'label' => __( 'Address' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => 'item_name' ,
                            'label' => __( 'Name' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => 'post_type' ,
                            'label' => __( 'Post Type' , ST_TEXTDOMAIN )
                        ) ,
                    )
                ) ,
                array(
                    'id'       => 'placeholder' ,
                    'label'    => __( 'Placeholder' , ST_TEXTDOMAIN ) ,
                    'desc'     => __( 'Placeholder' , ST_TEXTDOMAIN ) ,
                    'type'     => 'text' ,
                    'operator' => 'and' ,
                ) ,
                array(
                    'id'       => 'layout_col' ,
                    'label'    => __( 'Layout 1 size' , ST_TEXTDOMAIN ) ,
                    'type'     => 'select' ,
                    'operator' => 'and' ,
                    'choices'  => array(
                        array(
                            'value' => '1' ,
                            'label' => __( 'column 1' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '2' ,
                            'label' => __( 'column 2' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '3' ,
                            'label' => __( 'column 3' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '4' ,
                            'label' => __( 'column 4' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '5' ,
                            'label' => __( 'column 5' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '6' ,
                            'label' => __( 'column 6' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '7' ,
                            'label' => __( 'column 7' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '8' ,
                            'label' => __( 'column 8' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '9' ,
                            'label' => __( 'column 9' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '10' ,
                            'label' => __( 'column 10' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '11' ,
                            'label' => __( 'column 11' , ST_TEXTDOMAIN )
                        ) ,
                        array(
                            'value' => '12' ,
                            'label' => __( 'column 12' , ST_TEXTDOMAIN )
                        ) ,
                    ) ,
                    'std'      => 4
                ) ,
                array(
                    'id'       => 'is_required' ,
                    'label'    => __( 'Field required' , ST_TEXTDOMAIN ) ,
                    'type'     => 'on-off' ,
                    'operator' => 'and' ,
                    'std'      => 'on' ,
                ) ,
            ) ,
            'std'      => array(
                array( 'title'                 => 'Address' ,
                       'layout_col'            => 12 ,
                       'field_search' => 'address'
                ) ,
            )
        ) ,
        array(
            'id'      => 'search_header_onoff' ,
            'label'   => __( 'Enable Header Search' , ST_TEXTDOMAIN ) ,
            'desc'    => __( 'Enable Header Search' , ST_TEXTDOMAIN ) ,
            'type'    => 'on-off' ,
            'section' => 'option_search' ,
            'std'     => 'on'
        ) ,
        array(
            'id'        => 'search_header_orderby' ,
            'label'     => __( 'Header Search - Order By' , ST_TEXTDOMAIN ) ,
            'type'      => 'select' ,
            'section'   => 'option_search' ,
            'desc'      => __( 'Header Search - Order By' , ST_TEXTDOMAIN ) ,
            'condition' => 'search_header_onoff:is(on)' ,
            'choices'   => array(
                array(
                    'value' => 'none' ,
                    'label' => __( 'None' , ST_TEXTDOMAIN )
                ) ,
                array(
                    'value' => 'ID' ,
                    'label' => __( 'ID' , ST_TEXTDOMAIN )
                ) ,
                array(
                    'value' => 'author' ,
                    'label' => __( 'Author' , ST_TEXTDOMAIN )
                ) ,
                array(
                    'value' => 'title' ,
                    'label' => __( 'Title' , ST_TEXTDOMAIN )
                ) ,
                array(
                    'value' => 'name' ,
                    'label' => __( 'Name' , ST_TEXTDOMAIN )
                ) ,
                array(
                    'value' => 'date' ,
                    'label' => __( 'Date' , ST_TEXTDOMAIN )
                ) ,
                array(
                    'value' => 'rand' ,
                    'label' => __( 'Random' , ST_TEXTDOMAIN )
                ) ,
            ) ,
        ) ,
        array(
            'id'        => 'search_header_order' ,
            'label'     => __( 'Header Search - Order' , ST_TEXTDOMAIN ) ,
            'type'      => 'select' ,
            'section'   => 'option_search' ,
            'desc'      => __( 'Header Search - Search by' , ST_TEXTDOMAIN ) ,
            'condition' => 'search_header_onoff:is(on)' ,
            'choices'   => array(
                array(
                    'value' => 'ASC' ,
                    'label' => __( 'ASC' , ST_TEXTDOMAIN )
                ) ,
                array(
                    'value' => 'DESC' ,
                    'label' => __( 'DESC' , ST_TEXTDOMAIN )
                ) ,
            ) ,
        ) ,
        array(
            'id'        => 'search_header_list' ,
            'label'     => __( 'Header Search - Search by' , ST_TEXTDOMAIN ) ,
            'type'      => 'checkbox' ,
            'section'   => 'option_search' ,
            'desc'      => __( 'Header Search - Search by' , ST_TEXTDOMAIN ) ,
            'condition' => 'search_header_onoff:is(on)' ,
            'choices'   => get_list_posttype()
        ) ,


        /*------------- User Option  --------------------*/
        array(
            'id'      => '404_bg' ,
            'label'   => __( '404 Background' , ST_TEXTDOMAIN ) ,
            'type'    => 'upload' ,
            'section' => 'option_404' ,
        ) ,
        array(
            'id'      => '404_text' ,
            'label'   => __( '404 Text' , ST_TEXTDOMAIN ) ,
            'type'    => 'textarea' ,
            'rows'    => '3' ,
            'section' => 'option_404' ,
        )
        /*------------- End User Option  --------------------*/
        /*------------- Begin Social Option  --------------------*/
        ,
        array(
            'id'      => 'social_fb_login' ,
            'label'   => __( 'Facebook Login' , ST_TEXTDOMAIN ) ,
            'type'    => 'on-off' ,
            'std'     => 'on' ,
            'section' => 'option_social'
        )
        ,
        array(
            'id'      => 'social_gg_login' ,
            'label'   => __( 'Google Login' , ST_TEXTDOMAIN ) ,
            'type'    => 'on-off' ,
            'std'     => 'on' ,
            'section' => 'option_social'
        )
        ,
        array(
            'id'      => 'social_tw_login' ,
            'label'   => __( 'Twitter Login' , ST_TEXTDOMAIN ) ,
            'type'    => 'on-off' ,
            'std'     => 'on' ,
            'section' => 'option_social'
        ),
        
        array(
            'id'      => 'bc_show_location_url' ,
            'label'   => __( 'Location link to Location Search Page' , ST_TEXTDOMAIN ) ,
            'déc'     => __( 'Yes for link to Location Search Page, No for link to Location Detail Page' , ST_TEXTDOMAIN ) ,
            'type'    => 'on-off' ,
            'section' => 'option_bc' ,
            'std'     => 'on'
        ),

        array(
            'id'=>'use_woocommerce_for_booking',
            'section'=>'option_woo_checkout',
            'label'   => __( 'Use Woocomerce for Booking' , ST_TEXTDOMAIN ) ,
            'type'    => 'on-off' ,
            'std'     => 'off' ,
        ),
        array(
            'id'=>'woo_checkout_show_shipping',
            'section'=>'option_woo_checkout',
            'label'   => __( 'Show Shipping Information' , ST_TEXTDOMAIN ) ,
            'type'    => 'on-off' ,
            'std'     => 'off' ,
            'condition' => "use_woocommerce_for_booking:is(on)"
        ),
        array(
            'id'=>'st_woo_cart_is_collapse',
            'section'=>'option_woo_checkout',
            'label'   => __( 'Show Cart item Information collapsed' , ST_TEXTDOMAIN ) ,
            'type'    => 'on-off' ,
            'std'     => 'off' ,
            'condition' => "use_woocommerce_for_booking:is(on)"
        ),


    )
);


$taxonomy_hotel = st_get_post_taxonomy( 'st_hotel' );
if(!empty($taxonomy_hotel)){
    foreach($taxonomy_hotel as $k=>$v){
        $terms_hotel = get_terms($v['value']);
        $ids =  array();
        if(!empty($terms_hotel)){
            foreach($terms_hotel as $key => $value){
                $ids[] = array(
                    'value'       => $value->term_id."|".$value->name,
                    'label'       => $value->name,
                );
            }
            $custom_settings['settings']['flied_hotel']['settings'][] = array(
                'id'        => 'custom_terms_'.$v['value'] ,
                'label'     => $v['label'],
                'condition' => 'name:is(taxonomy),taxonomy:is('.$v['value'].')' ,
                'operator'  => 'and' ,
                'type'      => 'checkbox' ,
                'choices'   => $ids,
                'desc'      => __( 'It will show all Hotel theme If you don\'t have any choose.' , ST_TEXTDOMAIN ) ,
            );
            $ids = array();
        }
    }
}
$custom_settings = apply_filters( 'st_option_tree_settings' , $custom_settings );

function ot_type_email_template_document(){
    
    echo '<div class="format-setting type-textblock wide-desc">';
      
    echo '<div class="description">';
?>  
    <style>
        table{
            border: 1px solid #CCC;
        }
        table tr:not(:last-child) td{
            border-bottom: 1px solid #CCC;
        }
        xmp{
            margin: 0;
        }
    </style>
    <p>
        <?php echo __('From version 1.1.9 you can edit email template for Admin, Partner, Customer by use our shortcodes system with some layout we ready build in. Below is the list shortcodes you can use', ST_TEXTDOMAIN); ?>:
    </p>
    <h4><?php echo __('List All Shortcode:', ST_TEXTDOMAIN); ?></h4>
    <ul>
        <li>
            <h5><?php echo __('Custommer Infomation:', ST_TEXTDOMAIN); ?></h5>
            <table width="95%" style="margin-left: 20px;">
                <tr style="background: #CCC;">
                    <th align="center" width="33.3333%"><?php echo __('Name', ST_TEXTDOMAIN); ?></th>
                    <th align="center" width="33.3333%"><?php echo __('Code', ST_TEXTDOMAIN); ?></th>
                    <th align="center" width="33.3333%"><?php echo __('Description', ST_TEXTDOMAIN); ?></th>
                </tr>
                <tr>
                    <td><strong><?php echo __('First Name', ST_TEXTDOMAIN); ?>:</strong></td>
                    <td>[st_email_booking_first_name]</td>
                    <td></td>
                </tr>
                <tr>
                    <td><strong><?php echo __('Last Name', ST_TEXTDOMAIN); ?>:</strong></td>
                    <td>[st_email_booking_last_name]</td>
                    <td></td>
                </tr>
                <tr>
                    <td><strong><?php echo __('Email', ST_TEXTDOMAIN); ?>:</strong></td>
                    <td>[st_email_booking_email]</td>
                    <td></td>
                </tr>
                <tr>
                    <td> <strong><?php echo __('Address', ST_TEXTDOMAIN); ?>:</strong></td>
                    <td>[st_email_booking_address]</td>
                    <td></td>
                </tr>
                <tr>
                    <td><strong><?php echo __('Phone Number', ST_TEXTDOMAIN); ?>:</strong></td>
                    <td>[st_email_booking_phone]</td>
                    <td></td>
                </tr>
                <tr>
                    <td><strong><?php echo __('City', ST_TEXTDOMAIN); ?>:</strong></td>
                    <td>[st_email_booking_city]</td>
                    <td></td>
                </tr>
                <tr>
                    <td><strong><?php echo __('Province', ST_TEXTDOMAIN); ?>:</strong></td>
                    <td>[st_email_booking_province]</td>
                    <td></td>
                </tr>
                <tr>
                    <td><strong><?php echo __('Zipcode', ST_TEXTDOMAIN); ?>:</strong></td>
                    <td>[st_email_booking_zip_code]</td>
                    <td></td>
                </tr>
                <tr>
                    <td><strong><?php echo __('Country', ST_TEXTDOMAIN); ?>:</strong></td>
                    <td>[st_email_booking_country]</td>
                    <td></td>
                </tr>
            </table>
        </li>
        <li>
            <h5><?php echo __('Item booking Infomation', ST_TEXTDOMAIN); ?></h5>
            <table width="95%" style="margin-left: 20px;">
                <tr style="background: #CCC;">
                    <th align="center" width="33.3333%"><?php echo __('Name', ST_TEXTDOMAIN); ?></th>
                    <th align="center" width="33.3333%"><?php echo __('Code', ST_TEXTDOMAIN); ?></th>
                    <th align="center" width="33.3333%"><?php echo __('Description', ST_TEXTDOMAIN); ?></th>
                </tr>
                <tr>
                    <td><strong><?php echo __('Post type name', ST_TEXTDOMAIN); ?></strong></td>
                    <td>[st_email_booking_posttype]</td>
                    <td><em><?php echo __('Show post-type name.', ST_TEXTDOMAIN); ?></em></td>
                </tr>
                <tr>
                    <td><strong><?php echo __('ID', ST_TEXTDOMAIN); ?>:</strong></td>
                    <td>[st_email_booking_id]</td>
                    <td>
                        <em><?php echo __('Display the Order ID', ST_TEXTDOMAIN); ?></em>
                    </td>
                </tr>
                <tr>
                    <td><strong><?php echo __('Thumbnail Image', ST_TEXTDOMAIN); ?>:</strong></td>
                    <td>[st_email_booking_thumbnail]</td>
                    <td>
                        <em><?php echo __('Display the product\'s thumbnail image (if have)', ST_TEXTDOMAIN); ?></em>
                    </td>
                </tr>
                <tr>
                    <td><strong><?php echo __('Date', ST_TEXTDOMAIN); ?>:</strong></td>
                    <td>[st_email_booking_date]</td>
                    <td>
                        <em><?php echo __('Display the booking date', ST_TEXTDOMAIN); ?></em>
                    </td>
                </tr>
                <tr>
                    <td><strong><?php echo __('SpeSpecial Requirements', ST_TEXTDOMAIN); ?>:</strong></td>
                    <td>[st_email_booking_note]</td>
                    <td>
                        <em><?php echo __('Display the information of the \'Special Requirements\' when booking', ST_TEXTDOMAIN); ?></em>
                    </td>
                </tr>
                <tr>
                    <td><strong><?php echo __('Payment Method', ST_TEXTDOMAIN); ?>:</strong></td>
                    <td>[st_email_booking_payment_method]</td>
                    <td>
                        <em><?php echo __('Display the booking method', ST_TEXTDOMAIN); ?></em>
                    </td>
                </tr>
                <tr>
                    <td><strong><?php echo __('Item Name', ST_TEXTDOMAIN); ?>:</strong></td>
                    <td>[st_email_booking_item_name]</td>
                    <td>
                        <em><?php echo __('Display item name of service.', ST_TEXTDOMAIN); ?></em>
                    </td>
                </tr>
                <tr>
                    <td><strong><?php echo __('Item Link', ST_TEXTDOMAIN); ?>:</strong></td>
                    <td>[st_email_booking_item_link]</td>
                    <td>
                        <em><?php echo __('Display the item title with a link under.', ST_TEXTDOMAIN); ?></em>
                    </td>
                </tr>
                <tr>
                    <td><strong><?php echo __('Item Number', ST_TEXTDOMAIN); ?>:</strong></td>
                    <td>[st_email_booking_number_item]</td>
                    <td>
                        <em><?php echo __('Display number of items when booking.', ST_TEXTDOMAIN); ?></em>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong><?php echo __('Check In', ST_TEXTDOMAIN); ?>:</strong><br />
                        <strong><?php echo __('Check Out', ST_TEXTDOMAIN); ?>:</strong>
                    </td>
                    <td>
                        [st_email_booking_check_in]<br />
                        [st_email_booking_check_out]<br/>
                        [st_check_in_out_title] <br/>
                        [st_check_in_out_value]                     
                    </td>
                    <td>
                        <em>
                            1. <?php echo __('Display check in, check out with Hotel and Rental', ST_TEXTDOMAIN); ?><br/>
                            2. <?php echo __('Display Pick-up Date and Drop-off Date with Car', ST_TEXTDOMAIN); ?><br/>
                            3. <?php echo __('Display Departure date and Arrive date with Tour and Activity', ST_TEXTDOMAIN); ?>
                        </em>
                    </td>
                </tr>
                <tr>
                    <td><strong><?php echo __('Item Price', ST_TEXTDOMAIN); ?>:</strong></td>
                    <td>[st_email_booking_item_price]</td>
                    <td>
                        <em><?php echo __('Display item price (not included Tour and Activity)', ST_TEXTDOMAIN); ?></em>
                    </td>
                </tr>
                <tr>
                    <td><strong><?php echo __('Item Origin Price', ST_TEXTDOMAIN); ?>:</strong></td>
                    <td>[st_email_booking_origin_price]</td>
                    <td>
                        <em>
                            <?php echo __('Display original price of the item (not included custom price, sale price and tax)', ST_TEXTDOMAIN); ?>
                        </em>
                    </td>
                </tr>
                <tr>
                    <td><strong><?php echo __('Item Sale Price', ST_TEXTDOMAIN); ?>:</strong></td>
                    <td>[st_email_booking_sale_price]</td>
                    <td>
                        <em><?php echo __('Display the sale price.', ST_TEXTDOMAIN); ?></em>
                    </td>
                </tr>
                <tr>
                    <td><strong><?php echo __('Item Tax Price', ST_TEXTDOMAIN); ?>:</strong></td>
                    <td>[st_email_booking_price_with_tax]</td>
                    <td>
                        <em><?php echo __('Display the price with tax.', ST_TEXTDOMAIN); ?></em>
                    </td>
                </tr>
                <tr>
                    <td><strong><?php echo __('Item Deposit Price', ST_TEXTDOMAIN); ?>:</strong></td>
                    <td>[st_email_booking_deposit_price]</td>
                    <td>
                        <em><?php echo __('Display the deposit require. ', ST_TEXTDOMAIN); ?></em>
                    </td>
                </tr>
                <tr>
                    <td><strong><?php echo __('Item Total Price', ST_TEXTDOMAIN); ?>:</strong></td>
                    <td>[st_email_booking_total_price]</td>
                    <td>
                        <em><?php echo __('Display the total price (included sale price and tax).', ST_TEXTDOMAIN); ?></em>
                    </td>
                </tr>
                <tr>
                    <td><strong><?php echo __('Item Tax Percent', ST_TEXTDOMAIN); ?>:</strong></td>
                    <td>[st_email_booking_total_price]</td>
                    <td>
                        <em><?php echo __('Display the total amount payment.', ST_TEXTDOMAIN); ?></em>
                    </td>
                </tr>
            </table>    
        </li>
        <li>
            <h5><?php echo __('Use for Hotel', ST_TEXTDOMAIN); ?></h5>
            <table width="95%" style="margin-left: 20px;">
                <tr style="background: #CCC;">
                    <th align="center" width="33.3333%"><?php echo __('Name', ST_TEXTDOMAIN); ?></th>
                    <th align="center" width="33.3333%"><?php echo __('Code', ST_TEXTDOMAIN); ?></th>
                    <th align="center" width="33.3333%"><?php echo __('Description', ST_TEXTDOMAIN); ?></th>
                </tr>
                <tr>
                    <td><strong><?php echo __('Room Name', ST_TEXTDOMAIN); ?>:</strong></td>
                    <td>[st_email_booking_room_name]</td>
                    <td>
                        <em>
                            <?php echo __('Display the room name of hotel.', ST_TEXTDOMAIN); ?>
                            <br />
                            @param 'title' 'string'.<br />
                            <xmp>   Eg: title="Room Name"</xmp>
                        </em>
                    </td>
                </tr>
                <tr>
                    <td><strong><?php echo __('Extra Items', ST_TEXTDOMAIN); ?>:</strong></td>
                    <td>[st_email_booking_extra_items]</td>
                    <td>
                        <em><?php echo __('Display all service/facillities inside a room.', ST_TEXTDOMAIN); ?></em>
                    </td>
                </tr>
                <tr>
                    <td><strong><?php echo __('Extra Price', ST_TEXTDOMAIN); ?>:</strong></td>
                    <td>[st_email_booking_extra_price]</td>
                    <td>
                        <em><?php echo __('Display total price of service in room.', ST_TEXTDOMAIN); ?></em>
                    </td>
                </tr>
            </table>    
        </li>
        <li>
            <h5><?php echo __('Use for Car', ST_TEXTDOMAIN); ?></h5>
            <table width="95%" style="margin-left: 20px;">
                <tr style="background: #CCC;">
                    <th align="center" width="33.3333%"><?php echo __('Name', ST_TEXTDOMAIN); ?></th>
                    <th align="center" width="33.3333%"><?php echo __('Code', ST_TEXTDOMAIN); ?></th>
                    <th align="center" width="33.3333%"><?php echo __('Description', ST_TEXTDOMAIN); ?></th>
                </tr>
                <tr>
                    <td><strong><?php echo __('Car Time', ST_TEXTDOMAIN); ?>:</strong></td>
                    <td>[st_email_booking_check_in_out_time]</td>
                    <td>
                        <em>
                            <?php echo __('Display Pick up and Drop off time.', ST_TEXTDOMAIN); ?>
                        </em>
                    </td>
                </tr>
                <tr>
                    <td><strong><?php echo __('Car Equipments', ST_TEXTDOMAIN); ?>:</strong></td>
                    <td>[st_email_booking_equipments]</td>
                    <td>
                        <em>
                            <?php echo __('Display equipment list in a car.', ST_TEXTDOMAIN); ?>
                            </br />
                            @param 'tag' 'string'.<br />
                            <xmp>   Eg: tag="<h3>"</xmp>
                            <br />
                            @param 'title' 'string'.<br />
                            <xmp>   Eg: title="Equipments"</xmp>
                        </em>
                    </td>
                </tr>
                <tr>
                    <td><strong><?php echo __('Car Equipments Price', ST_TEXTDOMAIN); ?>:</strong></td>
                    <td>[st_email_booking_equipment_price]</td>
                    <td>
                        <em>
                            <?php echo __('Display total price of equipment in car.', ST_TEXTDOMAIN); ?>
                            <br />
                            @param 'title' 'string'.<br />
                            <xmp>   Eg: title="Equipments Price"</xmp>
                        </em>
                    </td>
                </tr>
            </table>    
        </li>
        <li>
            <h5><?php echo __('Use for Tour and Activity', ST_TEXTDOMAIN); ?></h5>
            <table width="95%" style="margin-left: 20px;">
                <tr style="background: #CCC;">
                    <th align="center" width="33.3333%"><?php echo __('Name', ST_TEXTDOMAIN); ?></th>
                    <th align="center" width="33.3333%"><?php echo __('Code', ST_TEXTDOMAIN); ?></th>
                    <th align="center" width="33.3333%"><?php echo __('Description', ST_TEXTDOMAIN); ?></th>
                </tr>
                <tr>
                    <td><strong><?php echo __('Adult Infomation', ST_TEXTDOMAIN); ?>:</strong></td>
                    <td>[st_email_booking_adult_info]</td>
                    <td>
                        <em>
                            <?php echo __('Display info of adult (number and price)', ST_TEXTDOMAIN); ?>
                            </br />
                            @param 'title' 'string'.<br />
                            <xmp>   Eg: title="No. Adults"</xmp>
                        </em>
                    </td>
                </tr>
                <tr>
                    <td><strong><?php echo __('Children Infomation', ST_TEXTDOMAIN); ?>:</strong></td>
                    <td>[st_email_booking_children_info]</td>
                    <td>
                        <em>
                            <?php echo __('Display info of adult (number and price)', ST_TEXTDOMAIN); ?>
                            </br />
                            @param 'title' 'string'.<br />
                            <xmp>   Eg: title="No. Children"</xmp>
                        </em>
                    </td>
                </tr>
                <tr>
                    <td><strong><?php echo __('Infant Infomation', ST_TEXTDOMAIN); ?>:</strong></td>
                    <td>[st_email_booking_infant_info]</td>
                    <td>
                        <em>
                            <?php echo __('Display info of infant  (number and price)', ST_TEXTDOMAIN); ?>
                            </br />
                            @param 'title' 'string'.<br />
                            <xmp>   Eg: title="No. Infant"</xmp>
                        </em>
                    </td>
                </tr>
                <tr>
                    <td><strong><?php echo __('Confirm Link', ST_TEXTDOMAIN); ?></strong></td>
                    <td>[st_email_confirm_link]</td>
                    <td><em><?php echo __('Get confirm email link', ST_TEXTDOMAIN); ?></em></td>
                </tr>
            </table>    
        </li>
    </ul>
<?php   
    echo '</div>';
      
    echo '</div>';
    
  }
