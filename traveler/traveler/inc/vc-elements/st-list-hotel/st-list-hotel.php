<?php
if(!st_check_service_available( 'st_hotel' )) {
    return;
}
if(function_exists( 'vc_map' ) and class_exists( 'TravelerObject' )) {
    $list_location                                              = TravelerObject::get_list_location();
    $list_location_data[ __( '-- Select --' , ST_TEXTDOMAIN ) ] = '';
    if(!empty( $list_location )) {
        foreach( $list_location as $k => $v ) {
            $list_location_data[ $v[ 'title' ] ] = $v[ 'id' ];
        }
    }
    vc_map( array(
        "name"            => __( "ST List of Hotels" , ST_TEXTDOMAIN ) ,
        "base"            => "st_list_hotel" ,
        "content_element" => true ,
        "icon"            => "icon-st" ,
        "category"        => "Shinetheme" ,
        "params"          => array(
            array(
                "type"        => "textfield" ,
                "holder"      => "div" ,
                "heading"     => __( "List ID in Hotel" , ST_TEXTDOMAIN ) ,
                "param_name"  => "st_ids" ,
                "description" => __( "Ids separated by commas" , ST_TEXTDOMAIN ) ,
                'value'       => "" ,
            ) ,
            array(
                "type"        => "textfield" ,
                "holder"      => "div" ,
                "heading"     => __( "Number hotel" , ST_TEXTDOMAIN ) ,
                "param_name"  => "st_number_ht" ,
                "description" => "" ,
                'value'       => 4 ,
            ) ,
            array(
                "type"             => "dropdown" ,
                "holder"           => "div" ,
                "heading"          => __( "Order By" , ST_TEXTDOMAIN ) ,
                "param_name"       => "st_orderby" ,
                "description"      => "" ,
                'edit_field_class' => 'vc_col-sm-6' ,
                'value'            => function_exists( 'st_get_list_order_by' ) ? st_get_list_order_by(
                    array(
                        __( 'Sale' , ST_TEXTDOMAIN )          => 'sale' ,
                        __( 'Rate' , ST_TEXTDOMAIN )          => 'rate' ,
                        __( 'Discount rate' , ST_TEXTDOMAIN ) => 'discount'
                    )
                ) : array() ,
            ) ,
            array(
                "type"             => "dropdown" ,
                "holder"           => "div" ,
                "heading"          => __( "Order" , ST_TEXTDOMAIN ) ,
                "param_name"       => "st_order" ,
                'value'            => array(
                    __( '--Select--' , ST_TEXTDOMAIN ) => '' ,
                    __( 'Asc' , ST_TEXTDOMAIN )        => 'asc' ,
                    __( 'Desc' , ST_TEXTDOMAIN )       => 'desc'
                ) ,
                'edit_field_class' => 'vc_col-sm-6' ,
            ) ,
            array(
                "type"        => "dropdown" ,
                "holder"      => "div" ,
                "heading"     => __( "Style hotel" , ST_TEXTDOMAIN ) ,
                "param_name"  => "st_style_ht" ,
                "description" => "" ,
                'value'       => array(
                    __( '--Select--' , ST_TEXTDOMAIN )          => '' ,
                    __( 'BG Last Minute Deal' , ST_TEXTDOMAIN ) => 'bg_last_minute_deal' ,
                    __( 'Last Minute Deals' , ST_TEXTDOMAIN )   => 'last_minute_deals' ,
                    __( 'Hot Deals' , ST_TEXTDOMAIN )           => 'hot-deals' ,
                    __( 'Grid' , ST_TEXTDOMAIN )                => 'grid' ,
                    __( 'Grid Style 2' , ST_TEXTDOMAIN )        => 'grid2' ,
                ) ,
            ) ,
            array(
                "type"             => "dropdown" ,
                "holder"           => "div" ,
                "heading"          => __( "Items per row" , ST_TEXTDOMAIN ) ,
                "param_name"       => "st_ht_of_row" ,
                'edit_field_class' => 'vc_col-sm-12' ,
                "description"      => __( 'Noticed: the field "Items per row" only applicable to "Last Minute Deal" style' , ST_TEXTDOMAIN ) ,
                "value"            => array(
                    __( '--Select--' , ST_TEXTDOMAIN ) => '' ,
                    __( 'Four' , ST_TEXTDOMAIN )       => 4 ,
                    __( 'Three' , ST_TEXTDOMAIN )      => 3 ,
                    __( 'Two' , ST_TEXTDOMAIN )        => 2 ,
                ) ,
            ) ,
            array(
                "type"             => "dropdown" ,
                "holder"           => "div" ,
                "heading"          => __( "Only in Featured Location" , ST_TEXTDOMAIN ) ,
                "param_name"       => "only_featured_location" ,
                'edit_field_class' => 'vc_col-sm-12' ,
                "value"            => array(
                    __( '--Select--' , ST_TEXTDOMAIN ) => '' ,
                    __( 'No' , ST_TEXTDOMAIN )         => 'no' ,
                    __( 'Yes' , ST_TEXTDOMAIN )        => 'yes' ,
                ) ,
            ) ,
            array(
                "type"        => "dropdown" ,
                "holder"      => "div" ,
                "heading"     => __( "Location" , ST_TEXTDOMAIN ) ,
                "param_name"  => "st_location" ,
                "description" => __( "Location" , ST_TEXTDOMAIN ) ,
                'value'       => $list_location_data ,
                "dependency"  =>
                    array(
                        "element" => "only_featured_location" ,
                        "value"   => "no"
                    ) ,
            ) ,
        )
    ) );
}

if(!function_exists( 'st_hotel_last_minute_deal_join' )) {
    function st_hotel_last_minute_deal_join( $join )
    {
        global $wpdb;

        $join .= " LEFT JOIN {$wpdb->postmeta} as st_meta1 on st_meta1.post_id={$wpdb->posts}.ID and st_meta1.meta_key='room_parent' ";


        return $join;
    }
}
if(!function_exists( 'st_hotel_last_minute_deal_filter' )) {
    function st_hotel_last_minute_deal_filter( $where )
    {
        global $wpdb;

        $where .= " AND st_meta1.meta_value IS NOT NULL and st_meta1.meta_value!=''";

        return $where;
    }
}
if(!function_exists( 'st_vc_list_hotel' )) {
    function st_vc_list_hotel( $attr , $content = false )
    {
        global $wp_query;
        $data = shortcode_atts(
            array(
                'st_ids'                 => "" ,
                'st_number_ht'           => 4 ,
                'st_order'               => '' ,
                'st_orderby'             => '' ,
                'st_ht_of_row'           => 4 ,
                'st_style_ht'            => 'bg_last_minute_deal' ,
                'only_featured_location' => 'no' ,
                'st_location'            => '' ,
            ) , $attr , 'st_list_hotel' );
        extract( $data );

        $query = array(
            'post_type'      => 'st_hotel' ,
            'posts_per_page' => $st_number_ht ,
            'order'          => $st_order ,
            'orderby'        => $st_orderby
        );
        if(!empty( $st_ids )) {
            $query[ 'post__in' ] = explode( ',' , $st_ids );
            $query[ 'orderby' ]  = 'post__in';
        }
        if($st_style_ht == 'bg_last_minute_deal') {
            $query = array(
                'post_type'      => 'hotel_room' ,
                'posts_per_page' => 1 ,
                'meta_key'       => 'sale_price_from' ,
                'order'          => 'desc' ,
                'orderby'        => 'meta_value'
            );
            add_filter( 'posts_where' , 'st_hotel_last_minute_deal_filter' );
            add_filter( 'posts_join' , 'st_hotel_last_minute_deal_join' );

            $post = query_posts( $query );

            remove_filter( 'posts_where' , 'st_hotel_last_minute_deal_filter' );
            remove_filter( 'posts_join' , 'st_hotel_last_minute_deal_join' );

            $data[ 'info_room' ] = $post;
            wp_reset_query();
            if(!empty( $post )) {
                $id_hotel = get_post_meta( $post[ 0 ]->ID , "room_parent" , true );
                $query    = array(
                    'post_type'      => 'st_hotel' ,
                    'posts_per_page' => 1 ,
                    'post__in'       => array( $id_hotel ) ,
                );
            } else {
                $query = array(
                    'post_type'      => 'hotel_room' ,
                    'posts_per_page' => 1 ,
                    'meta_key'       => 'discount_rate' ,
                    'order'          => 'ASC' ,
                    'orderby'        => 'meta_value' ,
                );
                add_filter( 'posts_where' , 'st_hotel_last_minute_deal_filter' );
                add_filter( 'posts_join' , 'st_hotel_last_minute_deal_join' );

                $post = query_posts( $query );

                remove_filter( 'posts_where' , 'st_hotel_last_minute_deal_filter' );
                remove_filter( 'posts_join' , 'st_hotel_last_minute_deal_join' );

                $data[ 'info_room' ] = $post;
                wp_reset_query();
                if(!empty( $post )) {
                    $id_hotel = get_post_meta( $post[ 0 ]->ID , "room_parent" , true );
                    $query    = array(
                        'post_type'      => 'st_hotel' ,
                        'posts_per_page' => 1 ,
                        'post__in'       => array( $id_hotel ) ,
                    );
                }
            }

        }
        if($st_orderby == 'sale') {
            $query[ 'meta_key' ] = 'total_sale_number';
            $query[ 'orderby' ]  = 'meta_value';
        }
        if($st_orderby == 'rate') {
            $query[ 'meta_key' ] = 'rate_review';
            $query[ 'orderby' ]  = 'meta_value';
        }
        if($st_orderby == 'discount') {
            $query[ 'meta_key' ] = 'discount_rate';
            $query[ 'orderby' ]  = 'meta_value';
        }
        $_SESSION[ 'el_only_featured_location' ] = $only_featured_location;
        $_SESSION[ 'st_st_location' ]            = $st_location;
        $_SESSION[ 'el_featured' ]               = array();
        if($only_featured_location == 'yes') {

            $STLocation                = new STLocation();
            $featured                  = $STLocation->get_featured_ids();
            $_SESSION[ 'el_featured' ] = $featured;
        }
        $st_list_hotel = new st_list_hotel();
        if($only_featured_location == 'yes' || !empty( $st_location )) {

            add_filter( 'posts_where' , array( $st_list_hotel , '_get_query_where' ) );
            add_filter( 'posts_join' , array( $st_list_hotel , '_get_query_join' ) );
        }
        $query = new WP_Query( $query );

        remove_filter( 'posts_where' , array( $st_list_hotel , '_get_query_where' ) );
        remove_filter( 'posts_join' , array( $st_list_hotel , '_get_query_join' ) );
        unset( $_SESSION[ 'el_only_featured_location' ] );
        unset( $_SESSION[ 'st_st_location' ] );
        unset( $_SESSION[ 'el_featured' ] );

        $data[ 'query' ] = $query;


        $r = st()->load_template( 'vc-elements/st-list-hotel/loop' , $st_style_ht , $data );

        wp_reset_query();

        return $r;
    }


}
if(st_check_service_available( 'st_hotel' )) {
    st_reg_shortcode( 'st_list_hotel' , 'st_vc_list_hotel' );
}


if(!class_exists( 'st_list_hotel' )) {
    class st_list_hotel
    {
        static function _get_query_where( $where )
        {
            if(!TravelHelper::checkTableDuplicate( 'st_hotel' ))
                return $where;
            global $wpdb;
            $only_featured_location = $_SESSION[ 'el_only_featured_location' ];
            $st_location            = $_SESSION[ 'st_st_location' ];
            $featured               = $_SESSION[ 'el_featured' ];
            if($only_featured_location == 'yes') {
                if(is_array( $featured ) && count( $featured )) {
                    $where .= " AND (";
                    $where_tmp = "";
                    foreach( $featured as $item ) {
                        if(empty( $where_tmp )) {
                            $where_tmp .= " tb.multi_location LIKE '%_{$item}_%'";
                        } else {
                            $where_tmp .= " OR tb.multi_location LIKE '%_{$item}_%'";
                        }
                    }
                    $featured = implode( ',' , $featured );
                    $where_tmp .= " OR tb.id_location IN ({$featured})";
                    $where .= $where_tmp . ")";
                }
            } else {
                if(!empty( $st_location )) {
                    $list = TravelHelper::getLocationByParent( $st_location );
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
                        $where_tmp .= " OR tb.id_location IN ({$list})";
                        $where .= $where_tmp . ")";
                    } else {
                        $where .= " AND (tb.multi_location LIKE '%_{$st_location}_%' OR tb.id_location IN ('{$st_location}')) ";
                    }
                }
            }
            return $where;
        }

        static function _get_query_join( $join )
        {
            if(!TravelHelper::checkTableDuplicate( 'st_hotel' ))
                return $join;
            global $wpdb;

            $table = $wpdb->prefix . 'st_hotel';

            $join .= " INNER JOIN {$table} as tb ON {$wpdb->prefix}posts.ID = tb.post_id";

            return $join;
        }
    }
}