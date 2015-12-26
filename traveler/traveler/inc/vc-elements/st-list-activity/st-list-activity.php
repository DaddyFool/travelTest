<?php
if(!st_check_service_available( 'st_activity' )) {
    return;
}
if(function_exists( 'vc_map' ) and class_exists( 'TravelerObject' )) {
    $list_taxonomy = st_list_taxonomy( 'st_activity' );
    $list_taxonomy = array_merge( array( "---Select---" => "" ) , $list_taxonomy );

    $list_location                                              = TravelerObject::get_list_location();
    $list_location_data[ __( '-- Select --' , ST_TEXTDOMAIN ) ] = '';
    if(!empty( $list_location )) {
        foreach( $list_location as $k => $v ) {
            $list_location_data[ $v[ 'title' ] ] = $v[ 'id' ];
        }
    }
    $params  = array(
        array(
            "type"        => "textfield" ,
            "holder"      => "div" ,
            "heading"     => __( "List ID in Activity" , ST_TEXTDOMAIN ) ,
            "param_name"  => "st_ids" ,
            "description" => __( "Ids separated by commas" , ST_TEXTDOMAIN ) ,
            'value'       => "" ,
        ) ,
        array(
            "type"             => "textfield" ,
            "holder"           => "div" ,
            "heading"          => __( "Number" , ST_TEXTDOMAIN ) ,
            "param_name"       => "st_number" ,
            "description"      => "" ,
            'value'            => 4 ,
            'edit_field_class' => 'vc_col-sm-3' ,
        ) ,
        array(
            "type"             => "dropdown" ,
            "holder"           => "div" ,
            "heading"          => __( "Order By" , ST_TEXTDOMAIN ) ,
            "param_name"       => "st_orderby" ,
            "description"      => "" ,
            'edit_field_class' => 'vc_col-sm-3' ,
            'value'            => function_exists( 'st_get_list_order_by' ) ? st_get_list_order_by(
                array(
                    __( 'Price' , ST_TEXTDOMAIN )         => 'sale' ,
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
            'edit_field_class' => 'vc_col-sm-3' ,
        ) ,
        array(
            "type"             => "dropdown" ,
            "holder"           => "div" ,
            "heading"          => __( "Number of row" , ST_TEXTDOMAIN ) ,
            "param_name"       => "st_of_row" ,
            'edit_field_class' => 'vc_col-sm-3' ,
            "value"            => array(
                __( '--Select--' , ST_TEXTDOMAIN ) => '' ,
                __( 'Four' , ST_TEXTDOMAIN )       => '4' ,
                __( 'Three' , ST_TEXTDOMAIN )      => '3' ,
                __( 'Two' , ST_TEXTDOMAIN )        => '2' ,
            ) ,
        ) ,
        array(
            "type"       => "dropdown" ,
            "holder"     => "div" ,
            "heading"    => __( "Only in Featured Location" , ST_TEXTDOMAIN ) ,
            "param_name" => "only_featured_location" ,
            "value"      => array(
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
        array(
            "type"        => "dropdown" ,
            "holder"      => "div" ,
            "heading"     => __( "Sort By Taxonomy" , ST_TEXTDOMAIN ) ,
            "param_name"  => "sort_taxonomy" ,
            "description" => "" ,
            "value"       => $list_taxonomy ,
        )
    );
    $data_vc = STActivity::get_taxonomy_and_id_term_activity();
    $params  = array_merge( $params , $data_vc[ 'list_vc' ] );
    vc_map( array(
        "name"            => __( "ST List of Activities" , ST_TEXTDOMAIN ) ,
        "base"            => "st_list_activity" ,
        "content_element" => true ,
        "icon"            => "icon-st" ,
        "category"        => "Shinetheme" ,
        "params"          => $params
    ) );
}

if(!function_exists( 'st_vc_list_activity' )) {
    function st_vc_list_activity( $attr , $content = false )
    {
        $data_vc = STActivity::get_taxonomy_and_id_term_activity();

        $param = array(
            'st_ids'                 => "" ,
            'st_number'              => 4 ,
            'st_order'               => '' ,
            'st_orderby'             => '' ,
            'st_of_row'              => 4 ,
            'only_featured_location' => 'no' ,
            'st_location'            => '' ,
            'sort_taxonomy'          => '' ,
        );
        $param = array_merge( $param , $data_vc[ 'list_id_vc' ] );
        $data  = shortcode_atts( $param , $attr , 'st_list_activity' );
        extract( $data );


        $page = STInput::request( 'paged' );
        if(!$page) {
            $page = get_query_var( 'paged' );
        }
        $query = array(
            'post_type'      => 'st_activity' ,
            'posts_per_page' => $st_number ,
            'paged'          => $page ,
            'order'          => $st_order ,
            'orderby'        => $st_orderby
        );

        if(!empty( $st_ids )) {
            $query[ 'post__in' ] = explode( ',' , $st_ids );
            $query[ 'orderby' ]  = 'post__in';
        }

        if($st_orderby == 'sale') {
            $query[ 'meta_key' ] = 'price';
            $query[ 'orderby' ]  = 'meta_value';
        }
        if($st_orderby == 'rate') {
            $query[ 'meta_key' ] = 'rate_review';
            $query[ 'orderby' ]  = 'meta_value';
        }
        if($st_orderby == 'discount') {
            $query[ 'meta_key' ] = 'discount';
            $query[ 'orderby' ]  = 'meta_value';
        }
        $_SESSION[ 'el_only_featured_location' ] = $only_featured_location;
        $_SESSION[ 'el_st_location' ]            = $st_location;
        $_SESSION[ 'el_featured' ]               = array();
        if($only_featured_location == 'yes') {
            $STLocation             = new STLocation();
            $featured               = $STLocation->get_featured_ids();
            $_SESSION[ 'featured' ] = $featured;
        }

        $st_list_activity = new st_list_activity();
        if($only_featured_location == 'yes' || !empty( $st_location )) {

            add_filter( 'posts_where' , array( $st_list_activity , '_get_query_where' ) );
            add_filter( 'posts_join' , array( $st_list_activity , '_get_query_join' ) );
        }

        if(!empty( $sort_taxonomy )) {
            if(isset( $attr[ "id_term_" . $sort_taxonomy ] )) {
                $id_term              = $attr[ "id_term_" . $sort_taxonomy ];
                $query[ 'tax_query' ] = array(
                    array(
                        'taxonomy' => $sort_taxonomy ,
                        'field'    => 'id' ,
                        'terms'    => explode( ',' , $id_term )
                    ) ,
                );
            }
        }
        query_posts( $query );

        remove_filter( 'posts_where' , array( $st_list_activity , '_get_query_where' ) );
        remove_filter( 'posts_join' , array( $st_list_activity , '_get_query_join' ) );
        unset( $_SESSION[ 'el_only_featured_location' ] );
        unset( $_SESSION[ 'el_st_location' ] );
        unset( $_SESSION[ 'el_featured' ] );

        $r = "<div class='list_tours'>" . st()->load_template( 'vc-elements/st-list-activity/loop' , '' , $data ) . "</div>";

        wp_reset_query();

        return $r;
    }
}
if(st_check_service_available( 'st_activity' )) {
    st_reg_shortcode( 'st_list_activity' , 'st_vc_list_activity' );
}


if(!class_exists( 'st_list_activity' )) {
    class st_list_activity
    {
        static function _get_query_join( $join )
        {
            if(!TravelHelper::checkTableDuplicate( 'st_activity' ))
                return $join;
            global $wpdb;

            $table = $wpdb->prefix . 'st_activity';

            $join .= " INNER JOIN {$table} as tb ON {$wpdb->prefix}posts.ID = tb.post_id";

            return $join;
        }

        static function _get_query_where( $where )
        {
            if(!TravelHelper::checkTableDuplicate( 'st_activity' ))
                return $where;
            global $wpdb;
            $only_featured_location = $_SESSION[ 'el_only_featured_location' ];
            $st_location            = $_SESSION[ 'el_st_location' ];
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
    }
}