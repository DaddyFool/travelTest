<?php
    if(function_exists('vc_map')){
        vc_map( 
            array(
            "name" => __("ST Location", ST_TEXTDOMAIN),
            "base" => "st_location",
            "content_element" => true,
            "icon" => "icon-st",
            "category"=>"Shinetheme",
            "params"    =>array(
                array(
                    "type"  =>"dropdown",
                    "holder"=>"div",
                    "heading"=>__("Location item custom", ST_TEXTDOMAIN), // 
                    "param_name" => "st_location_custom_type",
                    "description" =>__("Select <b>Custom</b> if you want customize infomation text of Location<br> OR not we get all information of location ",ST_TEXTDOMAIN),
                    "value"     =>array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __('Normal', ST_TEXTDOMAIN) => 'normal',
                        __('Custom', ST_TEXTDOMAIN) => 'custom',
                        )
                    ),
                array(
                    "type"  =>"textfield",
                    "holder"=>"div",
                    "heading"=>__("From price custom", ST_TEXTDOMAIN), // 
                    "param_name" => "st_location_price_custom",
                    "description" =>__("Your custom from price ",ST_TEXTDOMAIN),
                    "dependency"    =>
                        array(
                            "element"   =>"st_location_custom_type",
                            "value"     =>"custom"
                        ),
                ),
                array(
                    "type"  =>"textfield",
                    "holder"=>"div",
                    "heading"=>__("Offers number", ST_TEXTDOMAIN), // 
                    "param_name" => "st_location_number_offers",
                    "description" =>__("Your custom offers number ",ST_TEXTDOMAIN),
                    "dependency"    =>
                        array(
                            "element"   =>"st_location_custom_type",
                            "value"     =>"custom"
                        ),
                ),
                array(
                    "type"  =>"textfield",
                    "holder"=>"div",
                    "heading"=>__("Reviews number", ST_TEXTDOMAIN), // 
                    "param_name" => "st_location_number_reviews",
                    "description" =>__("Your custom reviews number ",ST_TEXTDOMAIN),
                    "dependency"    =>
                        array(
                            "element"   =>"st_location_custom_type",
                            "value"     =>"custom"
                        ),
                ),
                
                array(
                    "type" => "dropdown",
                    "holder" => "div",
                    "heading" => __("Your post type", ST_TEXTDOMAIN),
                    "param_name" => "st_location_post_type",
                    "description" =>__("Your post type",ST_TEXTDOMAIN),
                    "value" => array(
                        __('--Select--',ST_TEXTDOMAIN)=>'',
                        __('Hotel', ST_TEXTDOMAIN) => 'st_hotel',
                        __('Car', ST_TEXTDOMAIN) => 'st_cars',
                        __('Rental', ST_TEXTDOMAIN) => 'st_rental',
                        __('Activity', ST_TEXTDOMAIN) => 'st_activity',
                        __('Tour', ST_TEXTDOMAIN) => 'st_tours',
                        __('Holiday', ST_TEXTDOMAIN) => 'st_holidays',
                    ),
                    "dependency"    =>
                        array(
                            "element"   =>"st_location_custom_type",
                            "value"     =>"normal"
                        ),
                ),
                array(
                    "type" => "attach_image",
                    "holder" => "div",
                    "heading" => __("Your post type thumbnail", ST_TEXTDOMAIN),
                    "param_name" => "st_location_post_type_thumb",
                    "description" =>__("Your post type thumbnail",ST_TEXTDOMAIN),
                ),
            )
            
        ) );
    }

    if (!function_exists('st_location_func')){
        function st_location_func($attr){
            return ;
            /*$data = shortcode_atts(
                array(          
                'st_location_custom_type'=>'normal',
                'st_location_post_type'=>'st_hotel',
                'st_location_number_offers'=>'',
                'st_location_number_reviews'=>'',
                'st_location_price_custom'=>'',
                'st_location_post_type_thumb'=>''
                ), $attr, 'st_location' );
            extract($data);
            
            if (!is_singular('location')){return ; }

            $post_type = $st_location_post_type ; 

            if ($st_location_custom_type =="custom"){
                $array = array(
                    'post_type'=>       $st_location_post_type,
                    'thumb'=>       $st_location_post_type_thumb ,
                    'post_type_name'=>      get_post_type_object($post_type)->labels->name,
                    'reviews'=>     $st_location_number_reviews,
                    'offers'=>      $st_location_number_offers,
                    'min_max_price'=>  array(
                        'price_min'=>$st_location_price_custom ,
                        )     
                    );
            }
            else {
                // get infomation from location ID
                $array = STLocation::get_info_by_post_type(get_the_ID(), $post_type);
                $array['thumb']= $attr['st_location_post_type_thumb'] ;
                $array['post_type']=$attr['st_location_post_type'];
            }
            return st()->load_template('location/location-content-item' , null, $array ) ;*/
            
        }
        st_reg_shortcode('st_location','st_location_func');
    }
    /**
    * @since 1.1.3
    * @Description build Location page header
    *
    */
    if (function_exists('vc_map')){
        
        vc_map( array(
            "name" => __("ST Location count rate ", ST_TEXTDOMAIN),
            "base" => "st_location_header_rate_count",
            "content_element" => true,
            "icon" => "icon-st",
            "params" => array(
                // add params same as with any other content element
                array(
                    "type"  =>"checkbox",
                    "holder"=>"div",
                    "heading"=>__("Post type select ?", ST_TEXTDOMAIN), // 
                    "param_name" => "post_type",
                    "description" =>__("Select your post types which you want ?",ST_TEXTDOMAIN),    
                    "value" => array(
                        __('--- All ---',ST_TEXTDOMAIN)=>'all',
                        __('Hotel', ST_TEXTDOMAIN) => 'st_hotel',
                        __('Car', ST_TEXTDOMAIN) => 'st_cars',
                        __('Rental', ST_TEXTDOMAIN) => 'st_rental',
                        __('Activity', ST_TEXTDOMAIN) => 'st_activity',
                        __('Tour', ST_TEXTDOMAIN) => 'st_tours',
                        __('Holiday', ST_TEXTDOMAIN) => 'st_holidays',
                    )            
                ),     
                          

            )
        ) );
        vc_map( array(
            "name" => __("ST Location statistical", ST_TEXTDOMAIN),
            "base" => "st_location_header_static",
            "content_element" => true,
            "icon" => "icon-st",
            "params" => array(
                // add params same as with any other content element
                array(
                    "type"  =>"checkbox",
                    "holder"=>"div",
                    "heading"=>__("Post type select ?", ST_TEXTDOMAIN), // 
                    "param_name" => "post_type",
                    "description" =>__("Select your post types",ST_TEXTDOMAIN),    
                    "value" => array(
                        __('--- All ---',ST_TEXTDOMAIN)=>'all',
                        __('Hotel', ST_TEXTDOMAIN) => 'st_hotel',
                        __('Car', ST_TEXTDOMAIN) => 'st_cars',
                        __('Rental', ST_TEXTDOMAIN) => 'st_rental',
                        __('Activity', ST_TEXTDOMAIN) => 'st_activity',
                        __('Tour', ST_TEXTDOMAIN) => 'st_tours',
                        __('Holiday', ST_TEXTDOMAIN) => 'st_holidays',
                    )            
                ),
                array(
                    "type"  =>"checkbox",
                    "holder"=>"div",
                    "heading"=>__("Select star list ", ST_TEXTDOMAIN), // 
                    "param_name" => "star_list",
                    "description" =>__("Select star list to static and show",ST_TEXTDOMAIN),    
                    "value" => array(
                        __('--- All ---<br>',ST_TEXTDOMAIN)=>'all',
                        __('<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i> (5)<br> ', ST_TEXTDOMAIN) => '5',
                        __('<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i> (4)<br> ', ST_TEXTDOMAIN) => '4',
                        __('<i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i> (3)<br> ', ST_TEXTDOMAIN) => '3',
                        __('<i class="fa fa-star"></i><i class="fa fa-star"></i> (2) <br> ', ST_TEXTDOMAIN) => '2',
                        __('<i class="fa fa-star"></i> (1)  ', ST_TEXTDOMAIN) => '1',
                    )            
                ),
            )
        ) );
        
    }
    
    if(!function_exists('st_location_header_rate_count')){
        function st_location_header_rate_count($arg){

            $defaults = array(
                'post_type'=>'all',
            );
            $arg = wp_parse_args( $arg, $defaults );
            
            return st()->load_template('vc-elements/st-location/location' , 'header-rate-count' , $arg); 
        }
        st_reg_shortcode('st_location_header_rate_count','st_location_header_rate_count' );
    }
    if(!function_exists('st_location_header_static')){
        function st_location_header_static($arg){
            $defaults = array(
                'post_type'=>'all',
                'star_list'=>'all'
            );
            $arg = wp_parse_args( $arg, $defaults );
            
            return st()->load_template('vc-elements/st-location/location' , 'header-static' , $arg); 
        }
        st_reg_shortcode('st_location_header_static','st_location_header_static' );
    }
    /**
    * @since 1.1.3
    * @Description build Location page content
    * create a vc_tab and get Shinetheme element into here 
    */
    if (function_exists('vc_map')){
        /**
        * @since 1.1.3
        * St location information slider 
        */
        vc_map(
            array(
            "name" => __("ST Location slider ", ST_TEXTDOMAIN),
            "base" => "st_location_slider",
            "content_element" => true,
            "icon" => "icon-st",
            "category"=>"Shinetheme",
            "params"    =>array(
                array(
                    "type"  =>"attach_images",
                    "holder"=>"div",
                    "heading"=>__("Gallery slider ", ST_TEXTDOMAIN), // 
                    "param_name" => "st_location_list_image"             
                    
                )
            )
        )
        );
        
        if (!function_exists('st_location_infomation_func')){
            function st_location_infomation_func($attr){
                return STLocation::get_slider($attr['st_location_list_image']);
            }
            st_reg_shortcode('st_location_slider','st_location_infomation_func' );

        };   
        $params = array(

            array(
                "type" => "dropdown",
                "holder" => "div",
                "heading" => __("Style", ST_TEXTDOMAIN),
                "param_name" => "st_location_style",
                "description" =>"Default style",
                'value'=> array(
                    __('--Select --',ST_TEXTDOMAIN)=>'',
                    __('List',ST_TEXTDOMAIN)=>'list',
                    __('Grid',ST_TEXTDOMAIN)=>'grid')
            ),
            array(
                "type" => "textfield",
                "holder" => "div",
                "heading" => __("No. items displayed", ST_TEXTDOMAIN),
                "param_name" => "st_location_num",
                "description" =>"Number of items shown",
                'value'=>4,
            ),
            array(
                "type" => "dropdown",
                "holder" => "div",
                "heading" => __("Order By", ST_TEXTDOMAIN),
                "param_name" => "st_location_orderby",
                "description" =>"",
                'value'=>st_get_list_order_by()
            ),
            array(
                "type" => "dropdown",
                "holder" => "div",
                "heading" => __("Order",ST_TEXTDOMAIN),
                "param_name" => "st_location_order",
                'value'=>array(
                    __('--Select--',ST_TEXTDOMAIN)=>'',
                    __('Asc',ST_TEXTDOMAIN)=>'asc',
                    __('Desc',ST_TEXTDOMAIN)=>'desc'
                ),
            )
        );
        vc_map(
            array(
                "name" => __("ST Location list car ", ST_TEXTDOMAIN),
                "base" => "st_location_list_car",
                "content_element" => true,
                "icon" => "icon-st",
                "category"=>"Shinetheme",
                "params"    =>$params
            ));
        vc_map(
            array(
                "name" => __("ST Location list hotel ", ST_TEXTDOMAIN),
                "base" => "st_location_list_hotel",
                "content_element" => true,
                "icon" => "icon-st",
                "category"=>"Shinetheme",
                "params"    =>$params
            ));
        vc_map(
            array(
                "name" => __("ST Location list rental ", ST_TEXTDOMAIN),
                "base" => "st_location_list_rental",
                "content_element" => true,
                "icon" => "icon-st",
                "category"=>"Shinetheme",
                "params"    =>$params
            ));
        vc_map(
            array(
                "name" => __("ST Location list activity ", ST_TEXTDOMAIN),
                "base" => "st_location_list_activity",
                "content_element" => true,
                "icon" => "icon-st",
                "category"=>"Shinetheme",
                "params"    =>$params
            ));
        vc_map(
            array(
                "name" => __("ST Location list tour ", ST_TEXTDOMAIN),
                "base" => "st_location_list_tour",
                "content_element" => true,
                "icon" => "icon-st",
                "category"=>"Shinetheme",
                "params"    =>$params
            )
        );
        vc_map(
            array(
                "name" => __("ST Location list holiday ", ST_TEXTDOMAIN),
                "base" => "st_location_list_holiday",
                "content_element" => true,
                "icon" => "icon-st",
                "category"=>"Shinetheme",
                "params"    =>$params
            )
        );
        vc_map(
            array(
                "name" => __("ST Location map", ST_TEXTDOMAIN),
                "base" => "st_location_map",
                "content_element" => true,
                "icon" => "icon-st",
                "category"=>"Shinetheme",   
                /*'params'=> array(
                    array(
                        "type" => "textfield",
                        "holder" => "div",
                        "heading" => __("Location ID", ST_TEXTDOMAIN),
                        "param_name" => "post_id",
                        "description" =>"Type location",
                    )             
                )*/
            )
        );
        if (!function_exists('st_location_list_car_func')){
            function st_location_list_car_func($attr){
                
                $data = shortcode_atts(
                array( 
                    'st_location_style'=>"",
                    'st_location_num'=>"",
                    'st_location_orderby'=>"",
                    'st_location_order'=>""
                ), $attr, 'st_location_list_car' );
                extract($data);
                $return =""; 

                $_SESSION['el_post_type'] = 'st_cars';
                $query=array(
                    'post_type' => 'st_cars',
                    'posts_per_page'=>$st_location_num,
                    'order'=>$st_location_order,
                    'orderby'=>$st_location_orderby,
                );
                if (STInput::request('style')){$st_location_style = STInput::request('style');};

                if ($st_location_style =="list"){
                    $return .='<ul class="booking-list loop-cars style_list">' ; 
                }else {
                    $return .='<div class="row row-wrap">';
                }
                $st_location = new st_location();
                add_filter('posts_where', array($st_location ,'_get_query_where'));
                add_filter('posts_join', array($st_location ,'_get_query_join'));

                query_posts($query);

                unset($_SESSION['el_post_type']);
                remove_filter('posts_where', array($st_location ,'_get_query_where'));
                remove_filter('posts_join', array($st_location ,'_get_query_join'));

                while(have_posts()){
                    the_post();
                    if ($st_location_style =="list"){
                            $return .=st()->load_template('cars/elements/loop/loop-1');
                        }else {
                            $return .=st()->load_template('cars/elements/loop/loop-2');
                        }
                }

                wp_reset_query();

                if ($st_location_style =="list"){
                    $return .='</ul>' ; 
                }else {
                    $return .="</div>";
                }          

                return $return ;
            }
            st_reg_shortcode('st_location_list_car','st_location_list_car_func' );
        };
        if (!function_exists('st_location_list_hotel_func')){
            function st_location_list_hotel_func($attr){
                
                $data = shortcode_atts(
                array( 
                    'st_location_style'=>"",
                    'st_location_num'=>"",
                    'st_location_orderby'=>"",
                    'st_location_order'=>""
                ), $attr, 'st_location_list_car' );
                extract($data);

                if (STInput::request('style')){$st_location_style = STInput::request('style');};

                $return = '' ;
                $_SESSION['el_post_type'] = 'st_hotel';
                $st_location = new st_location();
                add_filter('posts_where', array($st_location ,'_get_query_where'));
                add_filter('posts_join', array($st_location ,'_get_query_join'));
                $query=array(
                    'post_type' => 'st_hotel',
                    'posts_per_page'=>$st_location_num,
                    'order'=>$st_location_order,
                    'orderby'=>$st_location_orderby,
                    'post_status'=>'publish',
                );
                $data['query'] = $query; 
                $data['style'] =$st_location_style; 
                $query = new WP_Query($query);
                //echo $query->request;
                if ( $query->have_posts() ) : 
                    while ( $query->have_posts() ) : $query->the_post();
                        $return .=st()->load_template('vc-elements/st-location/location','list-hotel',$data); 
                    endwhile;
                endif;                
                unset($_SESSION['el_post_type']);
                remove_filter('posts_where', array($st_location ,'_get_query_where'));
                remove_filter('posts_join', array($st_location ,'_get_query_join'));
                wp_reset_query();


                return $return; 

            }
            st_reg_shortcode('st_location_list_hotel','st_location_list_hotel_func' );
        };
        if (!function_exists('st_location_list_tour_func')){
            function st_location_list_tour_func($attr){
                
                $data = shortcode_atts(
                array( 
                    'st_location_style'=>"",
                    'st_location_num'=>"",
                    'st_location_orderby'=>"",
                    'st_location_order'=>""
                ), $attr, 'st_location_list_car' );
                extract($data);
                $return = '' ; 
                $_SESSION['el_post_type'] = 'st_tours';
                $st_location = new st_location();
                add_filter('posts_where', array($st_location ,'_get_query_where'));
                add_filter('posts_join', array($st_location ,'_get_query_join'));
                $query=array(
                    'post_type' => 'st_tours',
                    'posts_per_page'=>$st_location_num,
                    'order'=>$st_location_order,
                    'orderby'=>$st_location_orderby,
                    'post_status'=>'publish',
                );

                if (STInput::request('style')){$st_location_style = STInput::request('style');};

                if($st_location_style == 'list'){
                    $return .="<ul class='booking-list loop-tours style_list loop-tours-location'>";
                }
                else{
                    $return .='<div class="row row-wrap grid-tour-location">';
                }
                $query = new Wp_Query($query);

                unset($_SESSION['el_post_type']);
                remove_filter('posts_where', array($st_location ,'_get_query_where'));
                remove_filter('posts_join', array($st_location ,'_get_query_join'));

                while($query->have_posts()){
                    $query->the_post();
                    if($st_location_style == 'list'){
                        $return .=st()->load_template('tours/elements/loop/loop-1',null , array('tour_id'=>get_the_ID()));
                    }
                    else{
                        $return .=  st()->load_template('tours/elements/loop/loop-2',null, array('tour_id'=>get_the_ID()));
                    }
                }
                wp_reset_query();
                
                if($st_location_style == 'list'){
                    $return .="</ul>";
                }
                else{
                    $return .="</div>";
                }
                return $return ;

            }
            st_reg_shortcode('st_location_list_tour','st_location_list_tour_func' );
        };
        if (!function_exists('st_location_list_holiday_func')){
            function st_location_list_holiday_func($attr){
                
                $data = shortcode_atts(
                array( 
                    'st_location_style'=>"",
                    'st_location_num'=>"",
                    'st_location_orderby'=>"",
                    'st_location_order'=>""
                ), $attr, 'st_location_list_car' );
                extract($data);
                $return = '' ; 
                $_SESSION['el_post_type'] = 'st_holidays';
                $st_location = new st_location();
                add_filter('posts_where', array($st_location ,'_get_query_where'));
                add_filter('posts_join', array($st_location ,'_get_query_join'));
                $query=array(
                    'post_type' => 'st_holidays',
                    'posts_per_page'=>$st_location_num,
                    'order'=>$st_location_order,
                    'orderby'=>$st_location_orderby,
                    'post_status'=>'publish',
                );

                if (STInput::request('style')){$st_location_style = STInput::request('style');};

                if($st_location_style == 'list'){
                    $return .="<ul class='booking-list loop-holidays style_list loop-holidays-location'>";
                }
                else{
                    $return .='<div class="row row-wrap grid-holiday-location">';
                }
                $query = new Wp_Query($query);

                unset($_SESSION['el_post_type']);
                remove_filter('posts_where', array($st_location ,'_get_query_where'));
                remove_filter('posts_join', array($st_location ,'_get_query_join'));

                while($query->have_posts()){
                    $query->the_post();
                    if($st_location_style == 'list'){
                        $return .=st()->load_template('holidays/elements/loop/loop-1',null , array('holiday_id'=>get_the_ID()));
                    }
                    else{
                        $return .=  st()->load_template('holidays/elements/loop/loop-2',null, array('holiday_id'=>get_the_ID()));
                    }
                }
                wp_reset_query();
                
                if($st_location_style == 'list'){
                    $return .="</ul>";
                }
                else{
                    $return .="</div>";
                }
                return $return ;

            }
            st_reg_shortcode('st_location_list_holiday','st_location_list_holiday_func' );
        };
        if (!function_exists('st_location_list_rental_func')){
            function st_location_list_rental_func($attr){     
                     
                $data = shortcode_atts(
                array( 
                    'st_location_style'=>"",
                    'st_location_num'=>"",
                    'st_location_orderby'=>"",
                    'st_location_order'=>""
                ), $attr, 'st_location_list_car' );
                extract($data);
                $return = '' ; 
                $_SESSION['el_post_type'] = 'st_rental';
                $st_location = new st_location();
                add_filter('posts_where', array($st_location ,'_get_query_where'));
                add_filter('posts_join', array($st_location ,'_get_query_join'));
                $query=array(
                    'post_type' => 'st_rental',
                    'posts_per_page'=>$st_location_num,
                    'order'=>$st_location_order,
                    'orderby'=>$st_location_orderby,
                    'post_status'=>'publish',
                );

                $query = new WP_Query($query);
                //echo $query->request;
                $data['style'] = $st_location_style ; 
                if ( $query->have_posts() ) : 
                    while ( $query->have_posts() ) : $query->the_post();
                        $return .=st()->load_template('vc-elements/st-location/location-list' , 'rental', $data);
                    endwhile;
                endif;                
                unset($_SESSION['el_post_type']);
                remove_filter('posts_where', array($st_location ,'_get_query_where'));
                remove_filter('posts_join', array($st_location ,'_get_query_join'));
                wp_reset_query();   
                return $return ;
            }
            st_reg_shortcode('st_location_list_rental','st_location_list_rental_func' );
        };
        if (!function_exists('st_location_list_activity_func')){
            function st_location_list_activity_func($attr){
                
                $data = shortcode_atts(
                array( 
                    'st_location_style'=>"",
                    'st_location_num'=>"",
                    'st_location_orderby'=>"",
                    'st_location_order'=>""
                ), $attr, 'st_location_list_car' );
                extract($data);
                $return = '' ; 
                $_SESSION['el_post_type'] = 'st_activity';
                $query=array(
                    'post_type' => 'st_activity',
                    'posts_per_page'=>$st_location_num,
                    'order'=>$st_location_order,
                    'orderby'=>$st_location_orderby,
                    'post_status'=>'publish',
                );
                if (STInput::request('style')){$st_location_style = STInput::request('style');};

                if($st_location_style == 'list'){
                    $return .="<ul class='booking-list loop-tours style_list loop-activity-location'>";
                }
                else{
                    $return .='<div class="row row-wrap grid-activity-location">';
                }
                query_posts($query);

                unset($_SESSION['el_post_type']);
                $st_location  = new st_location();

                remove_filter('posts_where', array($st_location ,'_get_query_where'));
                remove_filter('posts_join', array($st_location ,'_get_query_join'));
                while(have_posts()){
                    the_post();
                    if($st_location_style == 'list'){
                        $return .=st()->load_template('activity/elements/loop/loop-1' ,null , array('is_location'=>true) );
                    }
                    else{
                        $return .=st()->load_template('activity/elements/loop/loop-2' ,null , array('is_location'=>true) );
                    }
                }
                wp_reset_query();

                if($st_location_style == 'list'){
                    $return .="</ul>";
                }
                else{
                    $return .='</div>';
                }
                return $return ;

            }
            st_reg_shortcode('st_location_list_activity','st_location_list_activity_func' );
        };
        if (!function_exists('st_location_map')){
            function st_location_map($attr){
                $return = "<div style='position: relative' id='google-map-tab' data-lat= '".get_post_meta(get_the_ID() , 'map_lat' , true) ."' data-long = '".get_post_meta(get_the_ID() , 'map_lng' , true) ."' >";
                $st_type ; 
               $number = get_post_meta(get_the_ID(),'max_post_type_num',true);
               if (!$number){$number = 36;} 

               $zoom = get_post_meta(get_the_ID(),'map_zoom' , true);
               if (!$zoom){$zoom = 15;}

               $map_height = get_post_meta(get_the_ID(),'map_height' , true);
               if (!$map_height){$map_height = 500;}               

               $map_location_style = get_post_meta(get_the_ID(),'map_location_style',true);
               if (!$map_location_style){$map_location_style = 'normal';}
               
               $list_post_type = STLocation::get_post_type_list_active();
               $shortcode_string = "";
               if (is_array($list_post_type) and !empty($list_post_type)) {
                foreach ($list_post_type as $key => $value) {
                     if(get_post_meta(get_the_ID(),'tab_enable_'.$value , true) =='on'){
                        $flag = $value; 
                     }
                     if ($key != 0){
                        $shortcode_string .= ",".$value;
                     }else {
                        $shortcode_string .= $value;
                     }
                  }
               };
               $show_data_list_map = apply_filters('show_data_list_map' , 'no');
               
               $return .= do_shortcode('[st_list_map 
                  st_type= "'.$shortcode_string.'" 
                  number="'.$number.'" 
                  zoom="'.$zoom.'" 
                  height="'.$map_height.'" 
                  style_map="'.$map_location_style.'" 
                  st_list_location="'.get_the_ID().'" 
                  show_data_list_map = "'.$show_data_list_map.'" 
                  show_search_box = "no"]');
               
                $return .= "</div>";
                return $return ; 
            }
            st_reg_shortcode('st_location_map' , 'st_location_map');
        }
                
    }
?>
