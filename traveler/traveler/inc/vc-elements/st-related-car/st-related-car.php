<?php
if(!st_check_service_available( 'st_cars' )) {
    return;
}
if(function_exists( 'vc_map' )) {
    $list_taxonomy = st_list_taxonomy( 'st_cars' );
    //$list_taxonomy = array_merge( array( "---Select---" => "" ) , $list_taxonomy );

    $params  = array(
        array(
            "type"             => "textfield" ,
            "holder"           => "div" ,
            "heading"          => __( "Title" , ST_TEXTDOMAIN ) ,
            "param_name"       => "title" ,
            "description"      => "" ,
            "value"            => "" ,
            'edit_field_class' => 'vc_col-sm-6' ,
        ) ,
        array(
            "type"             => "textfield" ,
            "holder"           => "div" ,
            "heading"          => __( "Number of Posts" , ST_TEXTDOMAIN ) ,
            "param_name"       => "posts_per_page" ,
            "description"      => "" ,
            "value"            => "" ,
            'edit_field_class' => 'vc_col-sm-6' ,
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
    $data_vc = STCars::get_taxonomy_and_id_term_tour();
    $params  = array_merge( $params , $data_vc[ 'list_vc' ] );
    vc_map( array(
        "name"            => __( "ST List cars related" , ST_TEXTDOMAIN ) ,
        "base"            => "st_list_car_related" ,
        "content_element" => true ,
        "icon"            => "icon-st" ,
        "category"        => "Shinetheme" ,
        "params"          => $params
    ) );
}
if(!function_exists( 'st_list_car_related' )) {
    function st_list_car_related( $attr , $content = false )
    {

        $data_vc = STCars::get_taxonomy_and_id_term_tour();
        $param   = array(
            'title'          => '' ,
            'sort_taxonomy'  => '' ,
            'posts_per_page' => 3 ,
            'orderby'        => 'ID' ,
            'order'          => 'DESC' ,
            'font_size'      => '3' ,
            'number_of_row'  => 1
        );
        $param   = array_merge( $param , $data_vc[ 'list_id_vc' ] );
        $data    = shortcode_atts(
            $param , $attr , 'st_list_car_related' );
        extract( $data );
        $page = STInput::request( 'paged' );
        if(!$page) {
            $page = get_query_var( 'paged' );
        }
        $query = array(
            'post_type'      => 'st_cars' ,
            'posts_per_page' => $posts_per_page ,
            'post_status'    => 'publish' ,
            'paged'          => $page ,
            'order'          => $order ,
            'orderby'        => $orderby ,
            'post__not_in'   => array( get_the_ID() )
        );

        if(!empty( $sort_taxonomy )) {
            if(isset( $attr[ "id_term_" . $sort_taxonomy ] )) {
                $terms_post = ( wp_get_post_terms( get_the_ID() , $sort_taxonomy , array( 'fields' => 'ids' ) ) );
                $id_term    = $attr[ "id_term_" . $sort_taxonomy ];
                $id_term    = explode( ',' , $id_term );
                $terms      = array();
                foreach( $id_term as $key => $value ) {
                    if(in_array( $value , $terms_post )) {
                        $terms[ ] = $value;
                    }
                }

                if($terms) {
                    $query[ 'tax_query' ] = array(
                        array(
                            'taxonomy' => $sort_taxonomy ,
                            'field'    => 'id' ,
                            'terms'    => $terms
                        ) ,
                    );
                }
            }
        };
        query_posts( $query );

        $r = "<div class='list_car_related'>" . st()->load_template( 'vc-elements/st-list-cars/loop-list' , '2' , array() ) . "</div>";

        wp_reset_query();

        if(!empty( $title ) and !empty( $r )) {
            $r = '<h' . $font_size . '>' . $title . '</h' . $font_size . '>' . $r;
        }

        return $r;

    }
}
if(st_check_service_available( 'st_cars' )) {
    st_reg_shortcode( 'st_list_car_related' , 'st_list_car_related' );
}