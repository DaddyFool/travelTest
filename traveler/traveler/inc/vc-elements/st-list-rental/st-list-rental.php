<?php
if(!st_check_service_available( 'st_rental' )) {
    return;
}
if(function_exists( 'vc_map' )) {


    $list_taxonomy = st_list_taxonomy( 'st_rental' );
    $list_taxonomy = array_merge( array( "---Select---" => "" ) , $list_taxonomy );

    $list_location                                              = TravelerObject::get_list_location();
    $list_location_data[ __( '-- Select --' , ST_TEXTDOMAIN ) ] = '';
    if(!empty( $list_location )) {
        foreach( $list_location as $k => $v ) {
            $list_location_data[ $v[ 'title' ] ] = $v[ 'id' ];
        }
    }

    $param = array(
        array(
            "type"        => "textfield" ,
            "holder"      => "div" ,
            "heading"     => __( "List ID in Rental" , ST_TEXTDOMAIN ) ,
            "param_name"  => "st_ids" ,
            "description" => __( "Ids separated by commas" , ST_TEXTDOMAIN ) ,
            'value'       => "" ,
        ) ,
        array(
            "type"        => "textfield" ,
            "holder"      => "div" ,
            "heading"     => __( "Number" , ST_TEXTDOMAIN ) ,
            "param_name"  => "number" ,
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
                    __( 'Sale' , ST_TEXTDOMAIN )     => 'sale' ,
                    __( 'Featured' , ST_TEXTDOMAIN ) => 'featured' ,
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
            "type"             => "dropdown" ,
            "holder"           => "div" ,
            "heading"          => __( "Number of rows" , ST_TEXTDOMAIN ) ,
            "param_name"       => "number_of_row" ,
            'edit_field_class' => 'vc_col-sm-12' ,
            "value"            => array(
                __( '--Select--' , ST_TEXTDOMAIN ) => '' ,
                __( 'Four' , ST_TEXTDOMAIN )       => '4' ,
                __( 'Three' , ST_TEXTDOMAIN )      => '3' ,
                __( 'Two' , ST_TEXTDOMAIN )        => '2' ,
            ) ,
        ) ,
        array(
            "type"        => "dropdown" ,
            "holder"      => "div" ,
            "heading"     => __( "Location" , ST_TEXTDOMAIN ) ,
            "param_name"  => "st_location" ,
            "description" => __( "Location" , ST_TEXTDOMAIN ) ,
            'value'       => $list_location_data ,
        ) ,
        array(
            "type"        => "dropdown" ,
            "holder"      => "div" ,
            "heading"     => __( "Sort By Taxonomy" , ST_TEXTDOMAIN ) ,
            "param_name"  => "sort_taxonomy" ,
            "description" => "" ,
            "value"       => $list_taxonomy ,
        ) ,
    );

    foreach( $list_taxonomy as $k => $v ) {
        $term = get_terms( $v );
        if(!is_wp_error( $term )) {
            if(!empty( $term ) and is_array( $term )) {
                foreach( $term as $key => $value ) {
                    $list_value[ $value->name ] = $value->term_id;
                }
                $param[ ] = array(
                    "type"       => "checkbox" ,
                    "holder"     => "div" ,
                    "heading"    => $k ,
                    "param_name" => "id_term_" . $v ,
                    "value"      => $list_value ,
                    'dependency' => array(
                        'element' => 'sort_taxonomy' ,
                        'value'   => array( $v )
                    ) ,
                );
            }
        }

    }

    vc_map( array(
        "name"            => __( "ST List of Rentals" , ST_TEXTDOMAIN ) ,
        "base"            => "st_list_rental" ,
        "content_element" => true ,
        "icon"            => "icon-st" ,
        "category"        => "Shinetheme" ,
        "params"          => $param
    ) );
}

if(!function_exists( 'st_vc_list_rental' )) {
    function st_vc_list_rental( $attr , $content = false )
    {
        $data = wp_parse_args( $attr ,
            array(
                'st_ids'        => '' ,
                'taxonomy'      => '' ,
                'number'        => 0 ,
                'st_order'      => '' ,
                'st_orderby'    => '' ,
                'number_of_row' => 4 ,
                'st_location'   => '' ,
                'sort_taxonomy' => ''
            ) );
        extract( $data );
        $query = array(
            'post_type'      => 'st_rental' ,
            'posts_per_page' => $number ,
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
        if($st_orderby == 'featured') {
            $query[ 'meta_key' ] = 'is_featured';
            $query[ 'orderby' ]  = 'meta_value';
        }
        $_SESSION[ 'el_location_id' ] = $st_location;

        $st_list_rental = new st_list_rental();
        if(!empty( $st_location )) {
            add_filter( 'posts_where' , array( $st_list_rental , '_get_query_where' ) );
            add_filter( 'posts_join' , array( $st_list_rental , '_get_query_join' ) );

        }

        if($sort_taxonomy) {
            if(isset( $data[ 'id_term_' . $sort_taxonomy ] ) and taxonomy_exists( $sort_taxonomy )) {
                $query[ 'tax_query' ][ ] = array(
                    'taxonomy' => $sort_taxonomy ,
                    'field'    => 'id' ,
                    'terms'    => explode( ',' , $data[ 'id_term_' . $sort_taxonomy ] )
                );
            }
        }

        $current_page = get_post_type( get_the_ID() ); // get current page .since 1.1.3
        query_posts( $query );

        remove_filter( 'posts_where' , array( $st_list_rental , '_get_query_where' ) );
        remove_filter( 'posts_join' , array( $st_list_rental , '_get_query_join' ) );
        unset( $_SESSION[ 'el_location_id' ] );

        $txt = '';
        while( have_posts() ) {
            the_post();
            $txt .= st()->load_template( 'vc-elements/st-list-rental/loop' , 'list' , array(
                'attr'         => $attr ,
                'data'         => $data ,
                'current_page' => $current_page
            ) );;
        }
        wp_reset_query();
        return '<div class="row row-wrap">' . $txt . '</div>';
    }
}

if(st_check_service_available( 'st_rental' )) {
    st_reg_shortcode( 'st_list_rental' , 'st_vc_list_rental' );
}
if(!class_exists( 'st_list_rental' )) {
    class st_list_rental
    {
        static function _get_query_where( $where )
        {
            if(!TravelHelper::checkTableDuplicate( 'st_rental' ))
                return $where;
            global $wpdb;
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
                $where_tmp .= " OR tb.location_id IN ({$list})";
                $where .= $where_tmp . ")";
            } else {
                $where .= " AND (tb.multi_location LIKE '%_{$location_id}_%' OR tb.location_id IN ('{$location_id}')) ";
            }
            return $where;
        }

        static function _get_query_join( $join )
        {
            if(!TravelHelper::checkTableDuplicate( 'st_rental' ))
                return $join;
            global $wpdb;

            $table = $wpdb->prefix . 'st_rental';

            $join .= " INNER JOIN {$table} as tb ON {$wpdb->prefix}posts.ID = tb.post_id";
            return $join;
        }
    }
}