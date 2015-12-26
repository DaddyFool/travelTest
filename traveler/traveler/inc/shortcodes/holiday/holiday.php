<?php
/**
 * Created by PhpStorm.
 * User: me664
 * Date: 12/15/14
 * Time: 9:44 AM
 */
if(!st_check_service_available( 'st_holidays' )) {
    return;
}
/**
 * ST Thumbnail Holiday
 * @since 1.1.0
 **/
if(function_exists( 'vc_map' )) {
    vc_map(
        array(
            'name'                    => __( "ST Holiday Thumbnail" , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_thumbnail_holidays' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Holiday' ,
            'show_settings_on_create' => false ,
            'params'                  => array()
        )
    );
}

if(!function_exists( 'st_thumbnail_holidays_func' )) {
    function st_thumbnail_holidays_func()
    {
        if(is_singular( 'st_holidays' )) {
            return st()->load_template( 'holidays/elements/image' , 'featured' );
        }
    }

    st_reg_shortcode( 'st_thumbnail_holidays' , 'st_thumbnail_holidays_func' );
}

/**
 * ST Excerpt Holiday
 * @since 1.1.0
 **/
if(function_exists( 'vc_map' )) {
    vc_map(
        array(
            'name'                    => __( "ST Holiday Excerpt" , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_excerpt_holiday' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Holiday' ,
            'show_settings_on_create' => true ,
            'params'                  => array(
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
                    "type"             => "dropdown" ,
                    "holder"           => "div" ,
                    "heading"          => __( "Font Size" , ST_TEXTDOMAIN ) ,
                    "param_name"       => "font_size" ,
                    "description"      => "" ,
                    "value"            => array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __( "H1" , ST_TEXTDOMAIN ) => '1' ,
                        __( "H2" , ST_TEXTDOMAIN ) => '2' ,
                        __( "H3" , ST_TEXTDOMAIN ) => '3' ,
                        __( "H4" , ST_TEXTDOMAIN ) => '4' ,
                        __( "H5" , ST_TEXTDOMAIN ) => '5' ,
                    ) ,
                    'edit_field_class' => 'vc_col-sm-6' ,
                ) ,
            )
        )
    );
}
if(!function_exists( 'st_excerpt_holidays_func' )) {
    function st_excerpt_holidays_func( $attr = array() )
    {
        if(is_singular( 'st_holidays' )) {
            $default = array(
                'title'     => '' ,
                'font_size' => '3' ,
            );
            extract( wp_parse_args( $attr , $default ) );
            while(have_posts())
            {
                the_post();
                $html = '<blockquote class="center">' . get_the_excerpt() . "</blockquote>";
                if(!empty( $title ) and !empty( $html )) {
                    $html = '<h' . $font_size . '>' . $title . '</h' . $font_size . '>' . $html;
                }
            }
            return $html;
        }
    }

    st_reg_shortcode( 'st_excerpt_holiday' , 'st_excerpt_holidays_func' );
}


/**
 * ST Holiday Content
 * @since 1.1.0
 **/
if(function_exists( 'vc_map' )) {
    vc_map(
        array(
            'name'                    => __( "ST Holiday Content" , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_holiday_content' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Holiday' ,
            'show_settings_on_create' => true ,
            'params'                  => array(
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
                    "type"             => "dropdown" ,
                    "holder"           => "div" ,
                    "heading"          => __( "Font Size" , ST_TEXTDOMAIN ) ,
                    "param_name"       => "font_size" ,
                    "description"      => "" ,
                    "value"            => array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __( "H1" , ST_TEXTDOMAIN ) => '1' ,
                        __( "H2" , ST_TEXTDOMAIN ) => '2' ,
                        __( "H3" , ST_TEXTDOMAIN ) => '3' ,
                        __( "H4" , ST_TEXTDOMAIN ) => '4' ,
                        __( "H5" , ST_TEXTDOMAIN ) => '5' ,
                    ) ,
                    'edit_field_class' => 'vc_col-sm-6' ,
                ) ,
            )
        )
    );
}
if(!function_exists( 'st_holiday_content_func' )) {
    function st_holiday_content_func( $attr = array() )
    {
        if(is_singular( 'st_holidays' )) {
            $default = array(
                'title'     => '' ,
                'font_size' => 1 ,
            );
            extract( wp_parse_args( $attr , $default ) );
            $html = st()->load_template( 'holidays/elements/content' , 'holidays' );
            if(!empty( $title ) and !empty( $html )) {
                $html = '<h' . $font_size . '>' . $title . '</h' . $font_size . '>' . $html;
            }
            return $html;
        }
    }

    st_reg_shortcode( 'st_holiday_content' , 'st_holiday_content_func' );
}

/**
 * ST Info Holiday
 * @since 1.1.0
 **/
if(function_exists( 'vc_map' )) {
    vc_map(
        array(
            'name'                    => __( "ST Holiday Info" , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_info_holidays' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Holiday' ,
            'show_settings_on_create' => false ,
            'params'                  => array()
        )
    );
}
if(!function_exists( 'st_info_holidays_func' )) {
    function st_info_holidays_func()
    {
        if(is_singular( 'st_holidays' )) {
            return st()->load_template( 'holidays/elements/info' , 'holidays' );
        }
    }

    st_reg_shortcode( 'st_info_holidays' , 'st_info_holidays_func' );
}

/**
 * ST Holiday Detail Map
 * @since 1.1.0
 **/
if(function_exists( 'vc_map' )) {
    vc_map(
        array(
            'name'                    => __( "ST Detailed Holiday Map" , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_holiday_detail_map' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Holiday' ,
            'show_settings_on_create' => true ,
            'params'                  => array(
                array(
                    "type"        => "textfield" ,
                    "holder"      => "div" ,
                    "heading"     => __( "Range" , ST_TEXTDOMAIN ) ,
                    "param_name"  => "range" ,
                    "description" => "Km" ,
                    "value"       => "20" ,
                ) ,
                array(
                    "type"        => "textfield" ,
                    "holder"      => "div" ,
                    "heading"     => __( "Number" , ST_TEXTDOMAIN ) ,
                    "param_name"  => "number" ,
                    "description" => "" ,
                    "value"       => "12" ,
                ) ,
                array(
                    "type"        => "dropdown" ,
                    "holder"      => "div" ,
                    "heading"     => __( "Show Circle" , ST_TEXTDOMAIN ) ,
                    "param_name"  => "show_circle" ,
                    "description" => "" ,
                    "value"       => array(
                        __( "No" , ST_TEXTDOMAIN )  => "no" ,
                        __( "Yes" , ST_TEXTDOMAIN ) => "yes"
                    ) ,
                )
            )
        )
    );
}

if(!function_exists( 'st_holiday_detail_map' )) {
    function st_holiday_detail_map($attr)
    {
        if(is_singular( 'st_holidays' )) {

            $default = array(
                'number'      => '12' ,
                'range'       => '20' ,
                'show_circle' => 'no' ,
            );
            extract( $dump = wp_parse_args( $attr , $default ) );
            $lat   = get_post_meta( get_the_ID() , 'map_lat' , true );
            $lng   = get_post_meta( get_the_ID() , 'map_lng' , true );
            $zoom  = get_post_meta( get_the_ID() , 'map_zoom' , true );
            $class = new STHoliday();
            $data  = $class->get_near_by( get_the_ID() , $range , $number );
            $location_center                     = '[' . $lat . ',' . $lng . ']';
            $data_map                            = array();
            $data_map[ 0 ][ 'id' ]               = get_the_ID();
            $data_map[ 0 ][ 'name' ]             = get_the_title();
            $data_map[ 0 ][ 'post_type' ]        = get_post_type();
            $data_map[ 0 ][ 'lat' ]              = $lat;
            $data_map[ 0 ][ 'lng' ]              = $lng;
            $data_map[ 0 ][ 'icon_mk' ]          = get_template_directory_uri() . '/img/mk-single.png';
            $data_map[ 0 ][ 'content_html' ]     = preg_replace( '/^\s+|\n|\r|\s+$/m' , '' , st()->load_template( 'vc-elements/st-list-map/loop/holiday' , false , array( 'post_type' => '' ) ) );
            $data_map[ 0 ][ 'content_adv_html' ] = preg_replace( '/^\s+|\n|\r|\s+$/m' , '' , st()->load_template( 'vc-elements/st-list-map/loop-adv/holiday' , false , array( 'post_type' => '' ) ) );
            $stt                                 = 1;
            global $post;
            if(!empty( $data )) {
                foreach( $data as $post ) :
                    setup_postdata( $post );
                    $map_lat = get_post_meta( get_the_ID() , 'map_lat' , true );
                    $map_lng = get_post_meta( get_the_ID() , 'map_lng' , true );
                    if(!empty( $map_lat ) and !empty( $map_lng ) and is_numeric( $map_lat ) and is_numeric( $map_lng )) {
                        $post_type                              = get_post_type();
                        $data_map[ $stt ][ 'id' ]               = get_the_ID();
                        $data_map[ $stt ][ 'name' ]             = get_the_title();
                        $data_map[ $stt ][ 'post_type' ]        = $post_type;
                        $data_map[ $stt ][ 'lat' ]              = $map_lat;
                        $data_map[ $stt ][ 'lng' ]              = $map_lng;
                        $data_map[ $stt ][ 'icon_mk' ]          = st()->get_option( 'st_holidays_icon_map_marker' , 'http://maps.google.com/mapfiles/marker_yellow.png' );
                        $data_map[ $stt ][ 'content_html' ]     = preg_replace( '/^\s+|\n|\r|\s+$/m' , '' , st()->load_template( 'vc-elements/st-list-map/loop/holiday' , false , array( 'post_type' => '' ) ) );
                        $data_map[ $stt ][ 'content_adv_html' ] = preg_replace( '/^\s+|\n|\r|\s+$/m' , '' , st()->load_template( 'vc-elements/st-list-map/loop-adv/holiday' , false , array( 'post_type' => '' ) ) );
                        $stt++;
                    }
                endforeach;
                wp_reset_postdata();
            }
            if($location_center == '[,]')
                $location_center = '[0,0]';
            if($show_circle == 'no') {
                $range = 0;
            }
            $data_tmp               = array(
                'location_center' => $location_center ,
                'zoom'            => $zoom ,
                'data_map'        => $data_map ,
                'height'          => 500 ,
                'style_map'       => 'normal' ,
                'number'          => $number ,
                'range'           => $range ,
            );
            $data_tmp[ 'data_tmp' ] = $data_tmp;
            $html                   = '<div class="map_single">'.st()->load_template( 'hotel/elements/detail' , 'map' , $data_tmp ).'</div>';
            return $html;
        }
    }
}

/**
 * ST Holiday Detail Review Summary
 * @since 1.1.0
 **/
if(function_exists( 'vc_map' )) {
    vc_map(
        array(
            'name'                    => __( "ST Holiday Review Summary" , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_holiday_detail_review_summary' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Holiday' ,
            'show_settings_on_create' => false ,
            'params'                  => array()
        )
    );
}
if(!function_exists( 'st_holiday_detail_review_summary' )) {
    function st_holiday_detail_review_summary()
    {

        if(is_singular( 'st_holidays' )) {
            return st()->load_template( 'holidays/elements/review_summary' );
        }
    }
}

/**
 * ST Holiday Detail Review Detail
 * @since 1.1.0
 **/
if(function_exists( 'vc_map' )) {
    vc_map(
        array(
            'name'                    => __( "ST Detailed Holiday Review" , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_holiday_detail_review_detail' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Holiday' ,
            'show_settings_on_create' => false ,
            'params'                  => array()
        )
    );
}
if(!function_exists( 'st_holiday_detail_review_detail' )) {
    function st_holiday_detail_review_detail()
    {
        if(is_singular( 'st_holidays' )) {
            return st()->load_template( 'holidays/elements/review_detail' );
        }
    }
}


/**
 * ST Holiday Program
 * @since 1.1.0
 **/
if(function_exists( 'vc_map' )) {
    vc_map(
        array(
            'name'                    => __( "ST Holiday Program" , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_holiday_program' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Holiday' ,
            'show_settings_on_create' => true ,
            'params'                  => array(
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
                    "type"             => "dropdown" ,
                    "holder"           => "div" ,
                    "heading"          => __( "Font Size" , ST_TEXTDOMAIN ) ,
                    "param_name"       => "font_size" ,
                    "description"      => "" ,
                    "value"            => array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __( "H1" , ST_TEXTDOMAIN ) => '1' ,
                        __( "H2" , ST_TEXTDOMAIN ) => '2' ,
                        __( "H3" , ST_TEXTDOMAIN ) => '3' ,
                        __( "H4" , ST_TEXTDOMAIN ) => '4' ,
                        __( "H5" , ST_TEXTDOMAIN ) => '5' ,
                    ) ,
                    'edit_field_class' => 'vc_col-sm-6' ,
                ) ,
            )
        )
    );
}
if(!function_exists( 'st_holiday_program' )) {
    function st_holiday_program( $attr = array() )
    {
        if(is_singular( 'st_holidays' )) {
            $default = array(
                'title'     => '' ,
                'font_size' => '3' ,
            );
            extract( wp_parse_args( $attr , $default ) );
            $html = st()->load_template( 'holidays/elements/program' );
            if(!empty( $title ) and !empty( $html )) {
                $html = '<h' . $font_size . '>' . $title . '</h' . $font_size . '>' . $html;
            }
            return $html;

        }
    }
}
st_reg_shortcode( 'st_holiday_program' , 'st_holiday_program' );

/**
 * ST Holiday Share
 * @since 1.1.0
 **/
if(function_exists( 'vc_map' )) {
    vc_map(
        array(
            'name'                    => __( "ST Holiday Share" , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_holiday_share' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Holiday' ,
            'show_settings_on_create' => false ,
            'params'                  => array()
        )
    );
}
if(!function_exists( 'st_holiday_share' )) {
    function st_holiday_share()
    {
        if(is_singular( 'st_holidays' )) {
            return '<div class="package-info holiday_share" style="clear: both;text-align: right">
                    ' . st()->load_template( 'hotel/share' ) . '
                </div>';
        }
    }
}
st_reg_shortcode( 'st_holiday_share' , 'st_holiday_share' );


/**
 * ST Holiday Review
 * @since 1.1.0
 **/
if(function_exists( 'vc_map' )) {
    vc_map(
        array(
            'name'                    => __( "ST Holiday Review" , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_holiday_review' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Holiday' ,
            'show_settings_on_create' => true ,
            'params'                  => array(
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
                    "type"             => "dropdown" ,
                    "holder"           => "div" ,
                    "heading"          => __( "Font Size" , ST_TEXTDOMAIN ) ,
                    "param_name"       => "font_size" ,
                    "description"      => "" ,
                    "value"            => array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __( "H1" , ST_TEXTDOMAIN ) => '1' ,
                        __( "H2" , ST_TEXTDOMAIN ) => '2' ,
                        __( "H3" , ST_TEXTDOMAIN ) => '3' ,
                        __( "H4" , ST_TEXTDOMAIN ) => '4' ,
                        __( "H5" , ST_TEXTDOMAIN ) => '5' ,
                    ) ,
                    'edit_field_class' => 'vc_col-sm-6' ,
                ) ,
            )
        )
    );
}

if(!function_exists( 'st_holiday_review' )) {
    function st_holiday_review( $attr = array() )
    {
        if(is_singular( 'st_holidays' )) {
            $default = array(
                'title'     => '' ,
                'font_size' => '3' ,
            );
            extract( wp_parse_args( $attr , $default ) );
            if(comments_open() and st()->get_option( 'activity_holiday_review' ) != 'off') {
                ob_start();
                comments_template( '/reviews/reviews.php' );
                $html = @ob_get_clean();
                if(!empty( $title ) and !empty( $html )) {
                    $html = '<h' . $font_size . '>' . $title . '</h' . $font_size . '>' . $html;
                }
                return $html;
            }

        }
    }
}


/**
 * ST Holiday Price
 * @since 1.1.0
 **/
if(function_exists( 'vc_map' )) {
    vc_map(
        array(
            'name'                    => __( "ST Holiday Price" , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_holiday_price' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Holiday' ,
            'show_settings_on_create' => false ,
            'params'                  => array()
        )
    );
}

if(!function_exists( 'st_holiday_price' )) {

    function st_holiday_price( $attr = array() )
    {
        if(is_singular( 'st_holidays' )) {
            return st()->load_template( 'holidays/elements/price' );
        }
    }
}


/**
 * ST Holiday Video
 * @since 1.1.0
 **/
if(function_exists( 'vc_map' )) {
    vc_map(
        array(
            'name'                    => __( "ST Holiday Video" , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_holiday_video' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Holiday' ,
            'show_settings_on_create' => false ,
            'params'                  => array()
        )
    );
}
if(!function_exists( 'st_holiday_video' )) {
    function st_holiday_video( $attr = array() )
    {
        if(is_singular( 'st_holidays' )) {
            if($video = get_post_meta( get_the_ID() , 'video' , true )) {
                return "<div class='media-responsive'>" . wp_oembed_get( $video ) . "</div>";
            }
        }
    }
}

/**
 * ST Holiday Nearby
 * @since 1.1.0
 **/
if(function_exists( 'vc_map' )) {
    vc_map(
        array(
            'name'                    => __( "ST Holiday Nearby" , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_holiday_nearby' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Holiday' ,
            'show_settings_on_create' => true ,
            'params'                  => array(
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
                    "type"             => "dropdown" ,
                    "holder"           => "div" ,
                    "heading"          => __( "Font Size" , ST_TEXTDOMAIN ) ,
                    "param_name"       => "font_size" ,
                    "description"      => "" ,
                    "value"            => array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __( "H1" , ST_TEXTDOMAIN ) => '1' ,
                        __( "H2" , ST_TEXTDOMAIN ) => '2' ,
                        __( "H3" , ST_TEXTDOMAIN ) => '3' ,
                        __( "H4" , ST_TEXTDOMAIN ) => '4' ,
                        __( "H5" , ST_TEXTDOMAIN ) => '5' ,
                    ) ,
                    'edit_field_class' => 'vc_col-sm-6' ,
                ) ,
            )
        )
    );
}
if(!function_exists( 'st_holiday_nearby' )) {
    function st_holiday_nearby( $arg = array() )
    {
        if(is_singular( 'st_holidays' )) {
            $default = array(
                'title'     => '' ,
                'font_size' => '3' ,
            );
            extract( $data = wp_parse_args( $arg , $default ) );
            return st()->load_template( 'holidays/elements/nearby' , '' , $data );
        }
    }
}


st_reg_shortcode( 'st_holiday_nearby' , 'st_holiday_nearby' );


st_reg_shortcode( 'st_holiday_video' , 'st_holiday_video' );

st_reg_shortcode( 'st_holiday_price' , 'st_holiday_price' );


st_reg_shortcode( 'st_holiday_review' , 'st_holiday_review' );

st_reg_shortcode( 'st_holiday_detail_list_schedules' , 'st_holiday_detail_list_schedules' );


st_reg_shortcode( 'st_holiday_detail_review_detail' , 'st_holiday_detail_review_detail' );
st_reg_shortcode( 'st_holiday_detail_review_summary' , 'st_holiday_detail_review_summary' );

st_reg_shortcode( 'st_holiday_detail_map' , 'st_holiday_detail_map' );

/**
 * ST holidays show discount
 * @since 1.1.9
 **/
if(function_exists( 'vc_map' )) {
    vc_map(
        array(
            'name'                    => __( 'ST Holiday Show Discount' , ST_TEXTDOMAIN ) ,
            'base'                    => 'st_holiday_show_discount' ,
            'content_element'         => true ,
            'icon'                    => 'icon-st' ,
            'category'                => 'Holiday' ,
            'show_settings_on_create' => false ,
            'params'                  => array()
        )
    );
}


if(!function_exists( 'st_holiday_show_discount' )) {
    function st_holiday_show_discount()
    {
        if(is_singular( 'st_holidays' )) {
            return st()->load_template( 'holidays/elements/holiday_show_info_discount' );
        }
    }
}
st_reg_shortcode( 'st_holiday_show_discount' , 'st_holiday_show_discount' );