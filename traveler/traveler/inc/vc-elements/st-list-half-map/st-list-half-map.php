<?php
/**
 *
 *
 * @since 1.1.5
 * */
if(function_exists( 'vc_map' ) and class_exists( 'TravelerObject' )) {
    $list_location                                              = TravelerObject::get_list_location();
    $list_location_data[ __( '-- Select --' , ST_TEXTDOMAIN ) ] = '';
    if(!empty( $list_location )) {
        foreach( $list_location as $k => $v ) {
            $list_location_data[ $v[ 'title' ] ] = $v[ 'id' ];
        }
    }
    vc_map( array(
        "name"     => __( "ST List Half Map" , ST_TEXTDOMAIN ) ,
        "base"     => "st_list_half_map" ,
        "class"    => "" ,
        "icon"     => "icon-st" ,
        "category" => "Shinetheme" ,
        "params"   => array(
            array(
                "type"       => "textfield" ,
                "holder"     => "div" ,
                "class"      => "" ,
                "heading"    => __( "Title" , ST_TEXTDOMAIN ) ,
                "param_name" => "title" ,
                "value"      => '' ,
            ) ,
            array(
                "type"       => "dropdown" ,
                "holder"     => "div" ,
                "class"      => "" ,
                "heading"    => __( "Type" , ST_TEXTDOMAIN ) ,
                "param_name" => "type" ,
                "value"      => array(
                    __( "Normal" , ST_TEXTDOMAIN )      => 'normal' ,
                    __( "Page Search" , ST_TEXTDOMAIN ) => 'page_search'
                ) ,
            ) ,
            array(
                "type"        => "dropdown" ,
                "holder"      => "div" ,
                "heading"     => __( "Select Location" , ST_TEXTDOMAIN ) ,
                "param_name"  => "st_list_location" ,
                "description" => "" ,
                "value"       => $list_location_data ,
                "dependency"  =>
                    array(
                        "element" => "type" ,
                        "value"   => "normal"
                    ) ,
            ) ,
            array(
                "type"             => "dropdown" ,
                "holder"           => "div" ,
                "heading"          => __( "Type" , ST_TEXTDOMAIN ) ,
                "param_name"       => "st_type" ,
                "description"      => "" ,
                'value'            => array(
                    __( '--Select--' , ST_TEXTDOMAIN ) => '' ,
                    __( 'Hotel' , ST_TEXTDOMAIN )      => 'st_hotel' ,
                    __( 'Car' , ST_TEXTDOMAIN )        => 'st_cars' ,
                    __( 'Tour' , ST_TEXTDOMAIN )       => 'st_tours' ,
                    __( 'Holiday' , ST_TEXTDOMAIN )       => 'st_holidays' ,
                    __( 'Rental' , ST_TEXTDOMAIN )     => 'st_rental' ,
                    __( 'Activities' , ST_TEXTDOMAIN ) => 'st_activity' ,
                ) ,
                'edit_field_class' => 'vc_col-sm-6' ,
            ) ,
            array(
                "type"             => "dropdown" ,
                "holder"           => "div" ,
                "heading"          => __( "Show Search Box" , ST_TEXTDOMAIN ) ,
                "param_name"       => "show_search_box" ,
                "description"      => "" ,
                'value'            => array(
                    __( 'Yes' , ST_TEXTDOMAIN ) => 'yes' ,
                    __( 'No' , ST_TEXTDOMAIN )  => 'no' ,
                ) ,
                'edit_field_class' => 'vc_col-sm-6' ,
            ) ,
            array(
                "type"             => "textfield" ,
                "holder"           => "div" ,
                "class"            => "" ,
                "heading"          => __( "Number" , ST_TEXTDOMAIN ) ,
                "param_name"       => "number" ,
                "value"            => 12 ,
                'edit_field_class' => 'vc_col-sm-3' ,
            ) ,
            array(
                "type"             => "textfield" ,
                "holder"           => "div" ,
                "class"            => "" ,
                "heading"          => __( "Zoom" , ST_TEXTDOMAIN ) ,
                "param_name"       => "zoom" ,
                "value"            => 13 ,
                'edit_field_class' => 'vc_col-sm-3' ,
            ) ,
            array(
                "type"             => "dropdown" ,
                "holder"           => "div" ,
                "class"            => "" ,
                "heading"          => __( "Map Position" , ST_TEXTDOMAIN ) ,
                "param_name"       => "map_position" ,
                "description"      => "Map Position" ,
                "value"            => array(
                    __( '--Select--' , ST_TEXTDOMAIN ) => '' ,
                    __( 'Left' , ST_TEXTDOMAIN )       => "left" ,
                    __( 'Right' , ST_TEXTDOMAIN )      => "right" ,
                ) ,
                'edit_field_class' => 'vc_col-sm-3' ,
            ) ,
            array(
                "type"             => "textfield" ,
                "holder"           => "div" ,
                "class"            => "" ,
                "heading"          => __( "Map Height" , ST_TEXTDOMAIN ) ,
                "param_name"       => "height" ,
                "description"      => "pixels" ,
                "value"            => 500 ,
                'edit_field_class' => 'vc_col-sm-3' ,
            ) ,
            array(
                "type"             => "dropdown" ,
                "holder"           => "div" ,
                "class"            => "" ,
                "heading"          => __( "Fit Bounds" , ST_TEXTDOMAIN ) ,
                "param_name"       => "fit_bounds" ,
                "description"      => "on|off" ,
                'value'            => array(
                    __( 'Off' , ST_TEXTDOMAIN ) => 'off' ,
                    __( 'On' , ST_TEXTDOMAIN )  => 'on' ,
                ) ,
                'edit_field_class' => 'vc_col-sm-12' ,
            ) ,
            array(
                "type"             => "dropdown" ,
                "holder"           => "div" ,
                "class"            => "" ,
                "heading"          => __( "Range Kilometer" , ST_TEXTDOMAIN ) ,
                "param_name"       => "range_km" ,
                "description"      => __( "On|Off" , ST_TEXTDOMAIN ) ,
                "value"            => array(
                    __( "Off" , ST_TEXTDOMAIN ) => 'off' ,
                    __( "On" , ST_TEXTDOMAIN )  => 'on' ,
                ) ,
                'edit_field_class' => 'vc_col-sm-4' ,
            ) ,
            array(
                "type"             => "textfield" ,
                "holder"           => "div" ,
                "class"            => "" ,
                "heading"          => __( "Max Range Kilometers" , ST_TEXTDOMAIN ) ,
                "param_name"       => "max_range_km" ,
                "description"      => __( "Kilometer" , ST_TEXTDOMAIN ) ,
                "value"            => 20 ,
                'edit_field_class' => 'vc_col-sm-4' ,
                "dependency"       =>
                    array(
                        "element" => "range_km" ,
                        "value"   => "on"
                    ) ,
            ) ,
            array(
                "type"             => "textfield" ,
                "holder"           => "div" ,
                "class"            => "" ,
                "heading"          => __( "Range Layout Col" , ST_TEXTDOMAIN ) ,
                "param_name"       => "range_km_col" ,
                "description"      => __( "1-12" , ST_TEXTDOMAIN ) ,
                "value"            => 6 ,
                'edit_field_class' => 'vc_col-sm-4' ,
                "dependency"       =>
                    array(
                        "element" => "range_km" ,
                        "value"   => "on"
                    ) ,
            ) ,
            //Range Kilometers
            array(
                "type"             => "dropdown" ,
                "holder"           => "div" ,
                "heading"          => __( "Style Map" , ST_TEXTDOMAIN ) ,
                "param_name"       => "style_map" ,
                "description"      => "" ,
                'value'            => array(
                    __( '--Select--' , ST_TEXTDOMAIN )  => '' ,
                    __( 'Normal' , ST_TEXTDOMAIN )      => 'normal' ,
                    __( 'Midnight' , ST_TEXTDOMAIN )    => 'midnight' ,
                    __( 'Family Fest' , ST_TEXTDOMAIN ) => 'family_fest' ,
                    __( 'Open Dark' , ST_TEXTDOMAIN )   => 'open_dark' ,
                    __( 'Riverside' , ST_TEXTDOMAIN )   => 'riverside' ,
                    __( 'Ozan' , ST_TEXTDOMAIN )        => 'ozan' ,
                ) ,
                'edit_field_class' => 'vc_col-sm-12' ,
            ) ,
        )
    ) );
}

if(!function_exists( 'st_list_half_map' )) {
    function st_list_half_map( $attr , $content = false )
    {
        $data = shortcode_atts(
            array(
                'title'             => '' ,
                'type'              => 'normal' ,
                'st_list_location'  => '' ,
                'st_type'           => 'st_hotel' ,
                'zoom'              => '13' ,
                'height'            => '500' ,
                'number'            => '12' ,
                'fit_bounds'        => 'off' ,
                'map_position'      => 'left' ,
                'style_map'         => 'normal' ,
                'custom_code_style' => '' ,
                'show_search_box'   => 'yes' ,
                'range_km'          => 'no' ,
                'max_range_km'      => '20' ,
                'range_km_col'       => '6' ,
            ) , $attr , 'st_list_map' );
        extract( $data );

        $html = '';
        if($type == "normal") {
            $st_list_half_map             = new st_list_half_map();
            $ids                          = $st_list_location;
            $query                        = array(
                'post_type'      => $st_type ,
                'posts_per_page' => $number ,
                'post_status'    => 'publish' ,
            );
            $_SESSION[ 'el_st_type' ]     = $st_type;
            $_SESSION[ 'el_location_id' ] = $st_list_location;
            add_filter( 'posts_where' , array( $st_list_half_map , '_get_query_where' ) );
            add_filter( 'posts_join' , array( $st_list_half_map , '_get_query_join' ) );

            $map_lat         = get_post_meta( $ids , 'map_lat' , true );
            $map_lng         = get_post_meta( $ids , 'map_lng' , true );
            $location_center = '[' . $map_lat . ',' . $map_lng . ']';

            $data_map = array();
            query_posts( $query );

            remove_filter( 'posts_where' , array( $st_list_half_map , '_get_query_where' ) );
            remove_filter( 'posts_join' , array( $st_list_half_map , '_get_query_join' ) );
            unset( $_SESSION[ 'el_st_type' ] );
            unset( $_SESSION[ 'el_location_id' ] );
        }
        if($type == "page_search") {
            $map_lat_center  = 0;
            $map_lng_center  = 0;
            $location_center = '[0,0]';
            $address_center  = '';
            if(STInput::request( 'pick-up' )) {
                $ids_location = TravelerObject::_get_location_by_name( STInput::get( 'pick-up' ) );
                if(!empty( $ids_location )) {
                    $_REQUEST[ 'pick-up' ] = implode( ',' , $ids_location );
                    $map_lat_center        = get_post_meta( $ids_location[ 0 ] , 'map_lat' , true );
                    $map_lng_center        = get_post_meta( $ids_location[ 0 ] , 'map_lng' , true );
                    $location_center       = '[' . $map_lat_center . ',' . $map_lng_center . ']';
                    $address_center        = get_the_title( $ids_location[ 0 ] );
                }
            }
            if(STInput::request( 'location_id' )) {
                $map_lat_center  = get_post_meta( STInput::request( 'location_id' ) , 'map_lat' , true );
                $map_lng_center  = get_post_meta( STInput::request( 'location_id' ) , 'map_lng' , true );
                $location_center = '[' . $map_lat_center . ',' . $map_lng_center . ']';
                $address_center  = get_the_title( STInput::request( 'location_id' ) );
            }
            if(STInput::request( 'location_id_pick_up' )) {
                $map_lat_center  = get_post_meta( STInput::request( 'location_id_pick_up' ) , 'map_lat' , true );
                $map_lng_center  = get_post_meta( STInput::request( 'location_id_pick_up' ) , 'map_lng' , true );
                $location_center = '[' . $map_lat_center . ',' . $map_lng_center . ']';
                $address_center  = get_the_title( STInput::request( 'location_id_pick_up' ) );
            }

            global $wp_query , $st_search_query;
            switch( $st_type ) {
                case"st_hotel":
                    $hotel = new STHotel();
                    add_action( 'pre_get_posts' , array( $hotel , 'change_search_hotel_arg' ) );
                    break;
                case"st_rental":
                    $rental = new STRental();
                    add_action( 'pre_get_posts' , array( $rental , 'change_search_arg' ) );
                    break;
                case"st_cars":
                    $cars = new STCars();
                    add_action( 'pre_get_posts' , array( $cars , 'change_search_cars_arg' ) );
                    break;
                case"st_tours":
                    $tour = new STTour();
                    //add_action( 'pre_get_posts' , array( $tour , 'change_search_tour_arg' ) );
                    st()->tour->alter_search_query();
                    break;
                case"st_holidays":
                    $holiday = new STHoliday();
                    //add_action( 'pre_get_posts' , array( $holiday , 'change_search_holiday_arg' ) );
                    st()->holiday->alter_search_query();
                    break;
                case"st_activity":
                    $activity = new STActivity();
                    add_action( 'pre_get_posts' , array( $activity , 'change_search_activity_arg' ) );
                    break;
            }

            $query = array(
                'post_type'      => $st_type ,
                'posts_per_page' => $number ,
                'post_status'    => 'publish' ,
                's'              => '' ,
            );
            query_posts( $query );
        }

        $stt = 0;
        while( have_posts() ) {
            the_post();
            $map_lat = get_post_meta( get_the_ID() , 'map_lat' , true );
            $map_lng = get_post_meta( get_the_ID() , 'map_lng' , true );
            if(!empty( $map_lat ) and !empty( $map_lng )) {
                $post_type                       = get_post_type();
                $data_map[ $stt ][ 'id' ]        = get_the_ID();
                $data_map[ $stt ][ 'name' ]      = get_the_title();
                $data_map[ $stt ][ 'post_type' ] = $post_type;
                $data_map[ $stt ][ 'lat' ]       = $map_lat;
                $data_map[ $stt ][ 'lng' ]       = $map_lng;
                $post_type_name                  = get_post_type_object( $post_type );
                $post_type_name->label;
                switch( $post_type ) {
                    case"st_hotel";
                        $data_map[ $stt ][ 'icon_mk' ]          = st()->get_option( 'st_hotel_icon_map_marker' , 'http://maps.google.com/mapfiles/marker_black.png' );
                        $data_map[ $stt ][ 'content_html' ]     = preg_replace( '/^\s+|\n|\r|\s+$/m' , '' , st()->load_template( 'vc-elements/st-list-map/loop/hotel' , false , array( 'post_type' => $post_type_name->label ) ) );
                        $data_map[ $stt ][ 'content_adv_html' ] = preg_replace( '/^\s+|\n|\r|\s+$/m' , '' , st()->load_template( 'vc-elements/st-list-map/loop-adv/hotel' , false , array( 'post_type' => $post_type_name->label ) ) );
                        break;
                    case"st_rental";
                        $data_map[ $stt ][ 'icon_mk' ]          = st()->get_option( 'st_rental_icon_map_marker' , 'http://maps.google.com/mapfiles/marker_brown.png' );
                        $data_map[ $stt ][ 'content_html' ]     = preg_replace( '/^\s+|\n|\r|\s+$/m' , '' , st()->load_template( 'vc-elements/st-list-map/loop/rental' , false , array( 'post_type' => $post_type_name->label ) ) );
                        $data_map[ $stt ][ 'content_adv_html' ] = preg_replace( '/^\s+|\n|\r|\s+$/m' , '' , st()->load_template( 'vc-elements/st-list-map/loop-adv/rental' , false , array( 'post_type' => $post_type_name->label ) ) );
                        break;
                    case"st_cars";
                        $data_map[ $stt ][ 'icon_mk' ]          = st()->get_option( 'st_cars_icon_map_marker' , 'http://maps.google.com/mapfiles/marker_green.png' );
                        $data_map[ $stt ][ 'content_html' ]     = preg_replace( '/^\s+|\n|\r|\s+$/m' , '' , st()->load_template( 'vc-elements/st-list-map/loop/car' , false , array( 'post_type' => $post_type_name->label ) ) );
                        $data_map[ $stt ][ 'content_adv_html' ] = preg_replace( '/^\s+|\n|\r|\s+$/m' , '' , st()->load_template( 'vc-elements/st-list-map/loop-adv/car' , false , array( 'post_type' => $post_type_name->label ) ) );
                        break;
                    case"st_tours";
                        $data_map[ $stt ][ 'icon_mk' ]          = st()->get_option( 'st_tours_icon_map_marker' , 'http://maps.google.com/mapfiles/marker_purple.png' );
                        $data_map[ $stt ][ 'content_html' ]     = preg_replace( '/^\s+|\n|\r|\s+$/m' , '' , st()->load_template( 'vc-elements/st-list-map/loop/tour' , false , array( 'post_type' => $post_type_name->label ) ) );
                        $data_map[ $stt ][ 'content_adv_html' ] = preg_replace( '/^\s+|\n|\r|\s+$/m' , '' , st()->load_template( 'vc-elements/st-list-map/loop-adv/tour' , false , array( 'post_type' => $post_type_name->label ) ) );
                        break;
                    case"st_holidays";
                        $data_map[ $stt ][ 'icon_mk' ]          = st()->get_option( 'st_holidays_icon_map_marker' , 'http://maps.google.com/mapfiles/marker_purple.png' );
                        $data_map[ $stt ][ 'content_html' ]     = preg_replace( '/^\s+|\n|\r|\s+$/m' , '' , st()->load_template( 'vc-elements/st-list-map/loop/holiday' , false , array( 'post_type' => $post_type_name->label ) ) );
                        $data_map[ $stt ][ 'content_adv_html' ] = preg_replace( '/^\s+|\n|\r|\s+$/m' , '' , st()->load_template( 'vc-elements/st-list-map/loop-adv/holiday' , false , array( 'post_type' => $post_type_name->label ) ) );
                        break;
                    case"st_activity";
                        $data_map[ $stt ][ 'icon_mk' ]          = st()->get_option( 'st_activity_icon_map_marker' , 'http://maps.google.com/mapfiles/marker_yellow.png' );
                        $data_map[ $stt ][ 'content_html' ]     = preg_replace( '/^\s+|\n|\r|\s+$/m' , '' , st()->load_template( 'vc-elements/st-list-map/loop/activity' , false , array( 'post_type' => $post_type_name->label ) ) );
                        $data_map[ $stt ][ 'content_adv_html' ] = preg_replace( '/^\s+|\n|\r|\s+$/m' , '' , st()->load_template( 'vc-elements/st-list-map/loop-adv/activity' , false , array( 'post_type' => $post_type_name->label ) ) );
                        break;
                }
                $stt++;
            }

        }
        if($type == "page_search") {
            $st_search_query = $wp_query;
            switch( $post_type ) {
                case"st_hotel":
                    $hotel->remove_alter_search_query();
                    break;
                case"st_rental":
                    $rental->remove_alter_search_query();
                    break;
                case"st_cars":
                    $cars->remove_alter_search_query();
                    break;
                case"st_tours":
                    //remove_action( 'pre_get_posts' , array( $tour , 'change_search_tour_arg' ) );
                    st()->tour->remove_alter_search_query();
                    break;
                case"st_holidays":
                    //remove_action( 'pre_get_posts' , array( $holiday , 'change_search_holiday_arg' ) );
                    st()->holiday->remove_alter_search_query();
                    break;
                case"st_activity":
                    $activity->remove_alter_search_query();
                    break;
            }
        }
        wp_reset_query();
        if(empty( $location_center ) or $location_center == '[,]')
            $location_center = '[0,0]';
        $data_tmp               = array(
            'st_list_location' => $st_list_location ,
            'location_center'  => $location_center ,
            'zoom'             => $zoom ,
            'data_map'         => $data_map ,
            'height'           => $height ,
            'map_position'     => $map_position ,
            'style_map'        => $style_map ,
            'st_type'          => $st_type ,
            'number'           => $number ,
            'fit_bounds'       => $fit_bounds ,
            'title'            => $title ,
            'show_search_box'  => $show_search_box ,
            'range_km'         => $range_km ,
            'max_range_km'     => $max_range_km ,
            'range_km_col'       => $range_km_col ,
        );
        $data_tmp[ 'data_tmp' ] = $data_tmp;
        $html                   = st()->load_template( 'vc-elements/st-list-half-map/html' , '' , $data_tmp );

        return $html;
    }
}

st_reg_shortcode( 'st_list_half_map' , 'st_list_half_map' );


if(!function_exists( 'st_search_list_half_map' )) {
    function st_search_list_half_map( $attr , $content = false )
    {

        $post_type = STInput::request( 'post_type' );
        $zoom      = STInput::request( 'zoom' );
        $number    = STInput::request( 'number' , 8 );
        $style_map = STInput::request( 'style_map' );

        $query           = array(
            'post_type'      => $post_type ,
            'posts_per_page' => $number ,
            'post_status'    => 'publish' ,
            's'              => '' ,
        );
        $map_lat_center  = 0;
        $map_lng_center  = 0;
        $location_center = '[0,0]';
        $address_center  = '';
        /*if(STInput::request( 'location_name' )) {
            $ids_location = TravelerObject::_get_location_by_name( STInput::get( 'location_name' ) );
            if(!empty( $ids_location )) {
                $_REQUEST['location_name'] = implode(',',$ids_location);
                $map_lat_center  = get_post_meta( $ids_location[ 0 ] , 'map_lat' , true );
                $map_lng_center  = get_post_meta( $ids_location[ 0 ] , 'map_lng' , true );
                $location_center = '[' . $map_lat_center . ',' . $map_lng_center . ']';
                $address_center  = get_the_title( $ids_location[ 0 ] );
            }
        }*/
        if(STInput::request( 'pick-up' )) {
            $ids_location = TravelerObject::_get_location_by_name( STInput::get( 'pick-up' ) );
            if(!empty( $ids_location )) {
                $_REQUEST[ 'pick-up' ] = implode( ',' , $ids_location );
                $map_lat_center        = get_post_meta( $ids_location[ 0 ] , 'map_lat' , true );
                $map_lng_center        = get_post_meta( $ids_location[ 0 ] , 'map_lng' , true );
                $location_center       = '[' . $map_lat_center . ',' . $map_lng_center . ']';
                $address_center        = get_the_title( $ids_location[ 0 ] );
            }
        }
        if(STInput::request( 'location_id' )) {
            $map_lat_center  = get_post_meta( STInput::request( 'location_id' ) , 'map_lat' , true );
            $map_lng_center  = get_post_meta( STInput::request( 'location_id' ) , 'map_lng' , true );
            $location_center = '[' . $map_lat_center . ',' . $map_lng_center . ']';
            $address_center  = get_the_title( STInput::request( 'location_id' ) );
        }
        if(STInput::request( 'location_id_pick_up' )) {
            $map_lat_center  = get_post_meta( STInput::request( 'location_id_pick_up' ) , 'map_lat' , true );
            $map_lng_center  = get_post_meta( STInput::request( 'location_id_pick_up' ) , 'map_lng' , true );
            $location_center = '[' . $map_lat_center . ',' . $map_lng_center . ']';
            $address_center  = get_the_title( STInput::request( 'location_id_pick_up' ) );
        }
        $data_map = array();
        global $wp_query , $st_search_query;
        switch( $post_type ) {
            case"st_hotel":
                $hotel = new STHotel();
                add_action( 'pre_get_posts' , array( $hotel , 'change_search_hotel_arg' ) );
                break;
            case"st_rental":
                $rental = new STRental();
                add_action( 'pre_get_posts' , array( $rental , 'change_search_arg' ) );
                break;
            case"st_cars":
                $cars = new STCars();
                add_action( 'pre_get_posts' , array( $cars , 'change_search_cars_arg' ) );
                break;
            case"st_tours":
                $tour = new STTour();
                //add_action( 'pre_get_posts' , array( $tour , 'change_search_tour_arg' ) );
                st()->tour->alter_search_query();
                break;
            case"st_holidays":
                $holiday = new STHoliday();
                //add_action( 'pre_get_posts' , array( $holiday , 'change_search_holiday_arg' ) );
                st()->holiday->alter_search_query();
                break;
            case"st_activity":
                $activity = new STActivity();
                add_action( 'pre_get_posts' , array( $activity , 'change_search_activity_arg' ) );
                break;
        }
        query_posts( $query );
        $stt = 0;
        while( have_posts() ) {
            the_post();

            $map_lat = get_post_meta( get_the_ID() , 'map_lat' , true );
            $map_lng = get_post_meta( get_the_ID() , 'map_lng' , true );
            if(!empty( $map_lat ) and !empty( $map_lng )) {
                $post_type                       = get_post_type();
                $data_map[ $stt ][ 'id' ]        = get_the_ID();
                $data_map[ $stt ][ 'name' ]      = get_the_title();
                $data_map[ $stt ][ 'post_type' ] = $post_type;
                $data_map[ $stt ][ 'lat' ]       = $map_lat;
                $data_map[ $stt ][ 'lng' ]       = $map_lng;
                $post_type_name                  = get_post_type_object( $post_type );
                $post_type_name->label;
                switch( $post_type ) {
                    case"st_hotel";
                        $data_map[ $stt ][ 'icon_mk' ]          = st()->get_option( 'st_hotel_icon_map_marker' , 'http://maps.google.com/mapfiles/marker_black.png' );
                        $data_map[ $stt ][ 'content_html' ]     = preg_replace( '/^\s+|\n|\r|\s+$/m' , '' , st()->load_template( 'vc-elements/st-list-map/loop/hotel' , false , array( 'post_type' => $post_type_name->label ) ) );
                        $data_map[ $stt ][ 'content_adv_html' ] = preg_replace( '/^\s+|\n|\r|\s+$/m' , '' , st()->load_template( 'vc-elements/st-list-map/loop-adv/hotel' , false , array( 'post_type' => $post_type_name->label ) ) );
                        break;
                    case"st_rental";
                        $data_map[ $stt ][ 'icon_mk' ]          = st()->get_option( 'st_rental_icon_map_marker' , 'http://maps.google.com/mapfiles/marker_brown.png' );
                        $data_map[ $stt ][ 'content_html' ]     = preg_replace( '/^\s+|\n|\r|\s+$/m' , '' , st()->load_template( 'vc-elements/st-list-map/loop/rental' , false , array( 'post_type' => $post_type_name->label ) ) );
                        $data_map[ $stt ][ 'content_adv_html' ] = preg_replace( '/^\s+|\n|\r|\s+$/m' , '' , st()->load_template( 'vc-elements/st-list-map/loop-adv/rental' , false , array( 'post_type' => $post_type_name->label ) ) );
                        break;
                    case"st_cars";
                        $data_map[ $stt ][ 'icon_mk' ]          = st()->get_option( 'st_cars_icon_map_marker' , 'http://maps.google.com/mapfiles/marker_green.png' );
                        $data_map[ $stt ][ 'content_html' ]     = preg_replace( '/^\s+|\n|\r|\s+$/m' , '' , st()->load_template( 'vc-elements/st-list-map/loop/car' , false , array( 'post_type' => $post_type_name->label ) ) );
                        $data_map[ $stt ][ 'content_adv_html' ] = preg_replace( '/^\s+|\n|\r|\s+$/m' , '' , st()->load_template( 'vc-elements/st-list-map/loop-adv/car' , false , array( 'post_type' => $post_type_name->label ) ) );
                        break;
                    case"st_tours";
                        $data_map[ $stt ][ 'icon_mk' ]          = st()->get_option( 'st_tours_icon_map_marker' , 'http://maps.google.com/mapfiles/marker_purple.png' );
                        $data_map[ $stt ][ 'content_html' ]     = preg_replace( '/^\s+|\n|\r|\s+$/m' , '' , st()->load_template( 'vc-elements/st-list-map/loop/tour' , false , array( 'post_type' => $post_type_name->label ) ) );
                        $data_map[ $stt ][ 'content_adv_html' ] = preg_replace( '/^\s+|\n|\r|\s+$/m' , '' , st()->load_template( 'vc-elements/st-list-map/loop-adv/tour' , false , array( 'post_type' => $post_type_name->label ) ) );
                        break;
                    case"st_holidays";
                        $data_map[ $stt ][ 'icon_mk' ]          = st()->get_option( 'st_holidays_icon_map_marker' , 'http://maps.google.com/mapfiles/marker_purple.png' );
                        $data_map[ $stt ][ 'content_html' ]     = preg_replace( '/^\s+|\n|\r|\s+$/m' , '' , st()->load_template( 'vc-elements/st-list-map/loop/holiday' , false , array( 'post_type' => $post_type_name->label ) ) );
                        $data_map[ $stt ][ 'content_adv_html' ] = preg_replace( '/^\s+|\n|\r|\s+$/m' , '' , st()->load_template( 'vc-elements/st-list-map/loop-adv/holiday' , false , array( 'post_type' => $post_type_name->label ) ) );
                        break;
                    case"st_activity";
                        $data_map[ $stt ][ 'icon_mk' ]          = st()->get_option( 'st_activity_icon_map_marker' , 'http://maps.google.com/mapfiles/marker_yellow.png' );
                        $data_map[ $stt ][ 'content_html' ]     = preg_replace( '/^\s+|\n|\r|\s+$/m' , '' , st()->load_template( 'vc-elements/st-list-map/loop/activity' , false , array( 'post_type' => $post_type_name->label ) ) );
                        $data_map[ $stt ][ 'content_adv_html' ] = preg_replace( '/^\s+|\n|\r|\s+$/m' , '' , st()->load_template( 'vc-elements/st-list-map/loop-adv/activity' , false , array( 'post_type' => $post_type_name->label ) ) );
                        break;
                }
                $stt++;
            }
        }
        $st_search_query = $wp_query;
        switch( $post_type ) {
            case"st_hotel":
                $hotel->remove_alter_search_query();
                break;
            case"st_rental":
                $rental->remove_alter_search_query();
                break;
            case"st_cars":
                $cars->remove_alter_search_query();
                break;
            case"st_tours":
                //remove_action( 'pre_get_posts' , array( $tour , 'change_search_tour_arg' ) );
                st()->tour->remove_alter_search_query();
                break;
            case"st_holidays":
                //remove_action( 'pre_get_posts' , array( $holiday , 'change_search_holiday_arg' ) );
                st()->holiday->remove_alter_search_query();
                break;
            case"st_activity":
                $activity->remove_alter_search_query();
                break;
        }
        if(!empty( $_REQUEST[ 'st_test' ] )) {
        }
        wp_reset_query();
        if($location_center == '[,]' or $location_center == '[0,0]') {
            $location_center = '[21.289374,15.644531]';
            $data_map        = "";
            $zoom            = "3";
        }
        $data_tmp = array(
            'location_center' => $location_center ,
            'zoom'            => $zoom ,
            'data_map'        => $data_map ,
            'style_map'       => $style_map ,
            'number'          => $number ,
            'address_center'  => $address_center ,
            'map_lat_center'  => $map_lat_center ,
            'map_lng_center'  => $map_lng_center ,
        );


        echo json_encode( $data_tmp );

        die();
    }
}
add_action( 'wp_ajax_st_search_list_half_map' , 'st_search_list_half_map' );
add_action( 'wp_ajax_nopriv_st_search_list_half_map' , 'st_search_list_half_map' );
if(!class_exists( 'st_list_half_map' )) {
    class st_list_half_map
    {
        static function _get_query_where( $where )
        {
            $post_type = $_SESSION[ 'el_st_type' ];
            if(!TravelHelper::checkTableDuplicate( $post_type ))
                return $where;
            global $wpdb;
            $location_field = 'id_location';
            if($post_type == 'st_rental')
                $location_field = 'location_id';
            $location_id = $_SESSION[ 'el_location_id' ];
            $list        = TravelHelper::getLocationByParent( $location_id );
            if(is_array( $list ) && count( $list )) {
                $where .= " AND (";
                $where_tmp = "";
                foreach( $list as $item ) {
                    if(empty( $where_tmp )) {
                        $where_tmp .= "tb.multi_location LIKE '%_{$item}_%'";
                    } else {
                        $where_tmp .= " OR tb.multi_location LIKE '%_{$item}_%'";
                    }
                }
                $list = implode( ',' , $list );
                $where_tmp .= " OR tb.{$location_field} IN ({$list})";
                $where .= $where_tmp . ")";
            } else {
                $where .= " AND (tb.multi_location LIKE '%_{$location_id}_%' OR tb.{$location_field} IN ('{$location_id}')) ";
            }
            return $where;
        }

        static function _get_query_join( $join )
        {
            $post_type = $_SESSION[ 'el_st_type' ];
            if(!TravelHelper::checkTableDuplicate( $post_type ))
                return $join;
            global $wpdb;

            $table = $wpdb->prefix . $post_type;

            $join .= " INNER JOIN {$table} as tb ON {$wpdb->prefix}posts.ID = tb.post_id";
            return $join;
        }
    }
}