<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Class STLocation
 *
 * Created by ShineTheme
 *
 */
if (!class_exists('STLocation')) {
    class STLocation extends TravelerObject
    {
        public $post_type = 'location';
        function init()
        {
            
            parent::init();
            //$this->init_metabox();
            add_action('init', array(
                $this,
                'init_metabox'
            ), 9);
            
            add_action('wp_ajax_st_search_location', array(
                $this,
                'search_location'
            ));
            add_action('wp_ajax_nopriv_st_search_location', array(
                $this,
                'search_location'
            ));
            add_action('widgets_init', array(
                $this,
                'add_sidebar'
            ));
            add_action('wp_enqueue_scripts', array(
                $this,
                'add_script'
            ));
            add_action('wp_enqueue_scripts', array(
                __CLASS__,
                'get_list_post_type'
            ));
            add_action('admin_enqueue_scripts' , array(
                $this , 
                'admin_script'
            ));

            add_action('save_post', array($this, 'save_location'), 55);
            add_action('init', array($this , 'create_session'), 1);

            
        }
        static function create_session(){
            if(!session_id()) { 
                session_start();         
            }
            $arg = array(
                'post_type'      => 'location' ,
                'posts_per_page' => '-1' ,
                'order'          => 'ASC' ,
                'orderby'        => 'title' ,
                'post_parent'    => 0 ,
            );
            $array_list = array();
            query_posts( $arg );
            while( have_posts() ) {
                the_post();
                $array_list[ ]  = array(
                    'id'    => get_the_ID() ,
                    'title' => get_the_title()
                );
                $children_array = self::get_child_location( get_the_ID() , '-' );
                if(!empty( $children_array )) {
                    foreach( $children_array as $k => $v ) {
                        $array_list[ ]   = array(
                            'id'    => $v[ 'id' ] ,
                            'title' => $v[ 'title' ]
                        );
                        $children_array2 = self::get_child_location( $v[ 'id' ] , '--' );
                        if(!empty( $children_array2 )) {
                            foreach( $children_array2 as $k2 => $v2 ) {
                                $array_list[ ]   = array(
                                    'id'    => $v2[ 'id' ] ,
                                    'title' => $v2[ 'title' ]
                                );
                                $children_array3 = self::get_child_location( $v2[ 'id' ] , '---' );
                                if(!empty( $children_array3 )) {
                                    foreach( $children_array3 as $k3 => $v3 ) {
                                        $array_list[ ] = array(
                                            'id'    => $v3[ 'id' ] ,
                                            'title' => $v3[ 'title' ]
                                        );
                                    }
                                }
                            }
                        }
                    }
                }
            }
            wp_reset_query();
            if(!isset($_SESSION['list_location'])){$_SESSION['list_location'] = array() ; }
            $_SESSION['list_location'] = $array_list ;

        }
        public function save_location(){
            if(get_post_type() == 'location'){

                update_option('st_allow_save_location', 'allow' );

                update_option('st_allow_save_cache_location', 'allow');
            }
        }
        /**@from 1.1.9*/
        public function admin_script(){
            wp_enqueue_script( 'location-admin', get_template_directory_uri() . '/js/admin/location-admin.js' , array('jquery'));
        }
        public function add_script()
        {
            if(is_singular('location'))
            wp_enqueue_script('location_script', get_template_directory_uri() . '/js/location.js', array('jquery'), null, true);
            //wp_enqueue_script('location_script', get_template_directory_uri() . '/js/location2.js', array('jquery'), null, true);

        }
        static public function get_post_type_list_active()
        {
            $array = array();
            if (st_check_service_available('st_cars')) {
                $array[] = 'st_cars';
            }
            if (st_check_service_available('st_hotel')) {
                $array[] = 'st_hotel';
            }
            if (st_check_service_available('st_tours')) {
                $array[] = 'st_tours';
            }
            if (st_check_service_available('st_rental')) {
                $array[] = 'st_rental';
            }
            if (st_check_service_available('st_activity')) {
                $array[] = 'st_activity';
            }
            if (st_check_service_available('hotel_room')) {
                $array[] = 'hotel_room';
            }
            
            return $array;
        } 
    
        static function get_child_location( $id , $prent )
        {
            $args           = array(
                'post_parent'    => $id ,
                'post_type'      => 'location' ,
                'posts_per_page' => -1 ,
            );
            $children_array = get_children( $args );
            $array_list     = array();
            if(!empty( $children_array )) {
                foreach( $children_array as $k => $v ) {
                    $array_list[ ] = array(
                        'id'    => $v->ID ,
                        'title' => $prent . $v->post_title
                    );
                }
            }
            return $array_list;
        }

        /**
        * from 1.1.9
        */
        static function get_opt_list(){
            $array = 
            array(array(
                    'value' => 'st_map',
                    'label' => __("Map" , ST_TEXTDOMAIN)
                ));
            if (st_check_service_available('st_cars')) {
                $array[] = array(
                    'value'    => 'st_cars',
                    'label' => __('Car' , ST_TEXTDOMAIN ) 
                    );
            }
            if (st_check_service_available('st_hotel')) {
                $array[] = array(
                    'value'    => 'st_hotel',
                    'label' => __('Hotel' , ST_TEXTDOMAIN ) 
                    );
            }
            if (st_check_service_available('st_tours')) {
                $array[] = array(
                    'value'    => 'st_tours',
                    'label' => __('Tour' , ST_TEXTDOMAIN ) 
                    );
            }
            if (st_check_service_available('st_rental')) {
                $array[] = array(
                    'value'    => 'st_rental',
                    'label' => __('Rental' , ST_TEXTDOMAIN ) 
                    );
            }
            if (st_check_service_available('st_activity')) {
                $array[] = array(
                    'value'    => 'st_activity',
                    'label' => __('Activity' , ST_TEXTDOMAIN ) 
                    );
            }
            return $array;
        }
        /**
        * @since 1.1.9
        **/
        static function get_opt_list_std(){
            $array = array(
                array
                    (
                        'title' => __("Map" , ST_TEXTDOMAIN ) ,
                        'tab_icon_' => "fa fa-map-marker",
                        'tab_type' => "st_map",
                        'map_height' => "500",
                        'map_spots' => "500",
                        'map_location_style' => "normal",
                    )
                );
            if (st_check_service_available('st_cars')) {
                $array[] = array
                    (
                        'title' => __("Car" , ST_TEXTDOMAIN),
                        'tab_icon_' => "fa fa-car",
                        'tab_type' => "st_cars",
                    ); 
            }
            if (st_check_service_available('st_hotel')) {
                $array[] = array
                    (
                        'title' => __("Hotel" , ST_TEXTDOMAIN),
                        'tab_icon_' => "fa fa-building-o",
                        'tab_type' => "st_hotel",
                    );
            }
            if (st_check_service_available('st_tours')) {
                $array[] = array
                    (
                        'title' => __("Tour" , ST_TEXTDOMAIN),
                        'tab_icon_' => "fa fa-flag-o",
                        'tab_type' => "st_tours",
                    );
            }
            if (st_check_service_available('st_activity')) {
                $array[] = array
                    (
                        'title' => __("Activity" , ST_TEXTDOMAIN),
                        'tab_icon_' => "fa fa-bolt",
                        'tab_type' => "st_activity",
                    );
            }
            if (st_check_service_available('st_rental')) {
                $array[] = array
                    (
                        'title' => __("Rental" , ST_TEXTDOMAIN),
                        'tab_icon_' => "fa-home",
                        'tab_type' => "st_rental",
                    );
            }
            return $array ; 
        }
        function get_featured_ids($arg = array())
        {
            $default = array(
                'posts_per_page' => 10,
                'post_type' => 'location'
            );
            
            extract(wp_parse_args($arg, $default));
            
            $ids = array();
            
            $query = array(
                'posts_per_page' => $posts_per_page,
                'post_type' => $post_type,
                'meta_key' => 'is_featured',
                'meta_value' => 'on'
            );
            query_posts($query);
            
            while (have_posts()) {
                the_post();
                $ids[] = get_the_ID();
            }
            wp_reset_postdata();
            
            return $ids;
        }
        
        function search_location()
        {
            //Small security
            check_ajax_referer('st_search_security', 'security');
            
            $s   = STInput::get('s');
            $arg = array(
                'post_type' => 'location',
                'posts_per_page' => 10,
                's' => $s
            );
            
            if ($s) {
            }
            
            global $wp_query;
            
            query_posts($arg);
            $r = array();
            
            while (have_posts()) {
                the_post();
                
                $r['data'][] = array(
                    'title' => get_the_title(),
                    'id' => get_the_ID(),
                    'type' => __('Location', ST_TEXTDOMAIN)
                );
            }
            wp_reset_query();
            echo json_encode($r);
            
            die();
        }
        
        function init_metabox()
        {
            $metabox        = array(
                'id' => 'st_location',
                'title' => __('Location Setting', ST_TEXTDOMAIN),
                'pages' => array(
                    'location'
                ),
                'context' => 'normal',
                'priority' => 'high',
                'fields' => array(
                    array(
                        'label' => __('Location settings', ST_TEXTDOMAIN),
                        'id' => 'location_tab',
                        'type' => 'tab'
                    ),
                    array(
                        'label' => __('Logo', ST_TEXTDOMAIN),
                        'id' => 'logo',
                        'type' => 'upload',
                        'desc' => __('logo', ST_TEXTDOMAIN)
                    ),
                    array(
                        'label' => __('Set as Featured', ST_TEXTDOMAIN),
                        'id' => 'is_featured',
                        'type' => 'on-off',
                        'desc' => __('This location is set as featured', ST_TEXTDOMAIN),
                        'std' => 'off'
                    ),
                    array(
                        'label' => __('Country', ST_TEXTDOMAIN),
                        'id' => 'location_country',
                        'type' => 'select',
                        'choices' => TravelHelper::_get_location_country(),
                        'desc' => __('Country of this location', ST_TEXTDOMAIN)
                    ),
                    array(
                        'label' => __('Zip Code', ST_TEXTDOMAIN),
                        'id' => 'zipcode',
                        'type' => 'text',
                        'desc' => __('Zip code of this location', ST_TEXTDOMAIN)
                    ),
                    array(
                        'label' => __('Maps', ST_TEXTDOMAIN),
                        'id'    => 'st_google_map',
                        'type'  => 'bt_gmap',
                        'desc'  => __('Maps', ST_TEXTDOMAIN),
                    ),
                    array(
                        'label' => __('Use build layout' , ST_TEXTDOMAIN) ,
                        'id'    =>'st_location_use_build_layout' , 
                        'type'  =>'on-off',
                        'desc'  =>__('Turn it on to display layout' ),
                        'std'   => 'off'
                        ),
                    array(
                        'label' => __('Location gallery display', ST_TEXTDOMAIN),
                        'id' => 'location_gallery_setting',
                        'type' => 'tab'
                    ),
                    array(
                        'label' => __('Location gallery style', ST_TEXTDOMAIN),
                        'id' => 'location_gallery_style',
                        'type' => 'select',
                        'desc' => __('Select your location style', ST_TEXTDOMAIN),
                        'choices' => array(
                            array(
                                'value' => '',
                                'label' => __('--- Select ---', ST_TEXTDOMAIN)
                            ),
                            array(
                                'value' => '1',
                                'label' => __('Fotorama stage', ST_TEXTDOMAIN)
                            ),
                            array(
                                'value' => '2',
                                'label' => __('Fotorama stage without nav', ST_TEXTDOMAIN)
                            ),
                            array(
                                'value' => '3',
                                'label' => __('Light box gallery', ST_TEXTDOMAIN)
                            )
                        )
                    ),
                    array(
                        'label' => __('Images per row', ST_TEXTDOMAIN),
                        'id' => 'image_count',
                        'type' => 'select',
                        'desc' => __('Choose your count num', ST_TEXTDOMAIN)  ,
                        'condition' => 'location_gallery_style:is(3)',
                        'choices' => array(
                            array(
                                'value' => '',
                                'label' => __(' --- Select ---', ST_TEXTDOMAIN)
                            ),
                            array(
                                'value' => '3',
                                'label' => __('3 images', ST_TEXTDOMAIN)
                            ),
                            array(
                                'value' => '4',
                                'label' => __('4 images', ST_TEXTDOMAIN)
                            ),
                            array(
                                'value' => '6',
                                'label' => __('6 images', ST_TEXTDOMAIN)
                            ),
                            array(
                                'value' => '12',
                                'label' => __('12 images', ST_TEXTDOMAIN)
                            )
                        )
                    ),
                    array(
                        'label' => __('Location gallery' , ST_TEXTDOMAIN) , 
                        'id'    => 'st_gallery' , 
                        'type'  => 'gallery'
                        ),
                    array(
                        'label' => __('Location tab setting', ST_TEXTDOMAIN),
                        'id' => 'location_tab_setting',
                        'type' => 'tab'
                    ),
                    array(
                        'label' => __('Tab navigation position', ST_TEXTDOMAIN),
                        'id' => 'tab_position',
                        'type' => 'select',
                        'desc' => __('Choose your tab Navigation position', ST_TEXTDOMAIN)  ,
                        'choices' => array(
                            array(
                                'value' => '',
                                'label' => __(' --- Select ---', ST_TEXTDOMAIN)
                            ),
                            array(
                                'value' => 'top',
                                'label' => __('Top', ST_TEXTDOMAIN)
                            ),
                            array(
                                'value' => 'right',
                                'label' => __('Right', ST_TEXTDOMAIN)
                            ),
                            array(
                                'value' => 'left',
                                'label' => __('Left', ST_TEXTDOMAIN)
                            )
                        )
                    ),
                    array(
                        'label' => __("Information text title"  , ST_TEXTDOMAIN),
                        'id' => 'information_text_title',
                        'type' => 'text',
                        'desc' => __("Name of Information tab", ST_TEXTDOMAIN)  ,
                        'std' => __("Location Information" , ST_TEXTDOMAIN)
                    ),
                    array(
                        'label' => __("Information icon class" , ST_TEXTDOMAIN),
                        'id' => 'tab_icon_info_icon',
                        'type' => 'text',
                        'std' => __("fa fa-info" , ST_TEXTDOMAIN)
                    ),
                    array(
                        'label' => __('Show Information tab item', ST_TEXTDOMAIN),
                        'id' => 'info_location_tab_item_enable',
                        'type' => 'on-off',
                        'desc' => __('<b>Enable </b> it to add item into information tab, <b>Disable </b> to get content of Location', ST_TEXTDOMAIN)  ,
                        'std' => 'off'
                    ),
                    array(
                        'label' => __('Information tab item position', ST_TEXTDOMAIN),
                        'id' => 'info_location_tab_item_position',
                        'type' => 'select',
                        'choices' => array(
                            array(
                                'value' => '',
                                'label' => __(' --- Select ---', ST_TEXTDOMAIN)
                            ),
                            array(
                                'value' => 'top',
                                'label' => __('Top', ST_TEXTDOMAIN)
                            ),
                            array(
                                'value' => 'right',
                                'label' => __('Right', ST_TEXTDOMAIN)
                            ),
                            array(
                                'value' => 'left',
                                'label' => __('Left', ST_TEXTDOMAIN)
                            )
                        ),
                        'condition' => 'info_location_tab_item_enable:is(on)',
                        'std' => 'on'
                    ),                    
                    array(
                        'label' => __('Information tab Item', ST_TEXTDOMAIN),
                        'id' => 'info_tab_item',
                        'type' => 'list-item',
                        'desc' => __('Add new item for location information tab', ST_TEXTDOMAIN)  ,
                        'condition' => 'info_location_tab_item_enable:is(on)',
                        'settings' => array(
                            array(
                                'id' => 'post_info_select',
                                'label' => __('Post from', ST_TEXTDOMAIN),
                                'type' => 'post_select',      
                                'post_type' => 'post',
                                'placeholder' =>__('Select a post',ST_TEXTDOMAIN)   
                            )
                        ),
                        array(
                            'id' => 'post_info_icon',
                            'label' => __('icon ', ST_TEXTDOMAIN),
                            'type' => 'text'
                        )
                    ),
                    array(
                        'label' =>__("Tab item" , ST_TEXTDOMAIN) ,
                        'id'    => 'location_tab_item',
                        'type'  => 'list-item',
                        'desc'  => __('Add new item to create new tab', ST_TEXTDOMAIN)  ,
                        'condition' => 'info_location_tab_item_enable:is(on)',
                        'std'   => self::get_opt_list_std(),
                        'settings' => array
                        (
                            array(
                                'id' => 'tab_icon_',
                                'label' => __('Tab icon', ST_TEXTDOMAIN),
                                'type' => 'text',
                                'std'   => 'fa-star-o'
                            ),
                            array(
                                'id'    => 'tab_type',
                                'label' => __("Type" , ST_TEXTDOMAIN) , 
                                'type'  => 'select',
                                'choices'   => self::get_opt_list(),
                                'std'   => 'st_cars'
                            ),
                            array(
                                'id'    => 'map_height',
                                'label' => __('Map height' , ST_TEXTDOMAIN) , 
                                'type'  => 'text', 
                                'condition' => 'tab_type:is(st_map)',
                                'std'   => '500'
                                ),
                            array(
                                'id'    => 'map_spots',
                                'label' => __('Maxium number of spots' , ST_TEXTDOMAIN) , 
                                'type'  => 'text', 
                                'condition' => 'tab_type:is(st_map)',
                                'std'   => '500'
                                ),
                            array(
                                'label' => __('Map style', ST_TEXTDOMAIN),
                                'id' => 'map_location_style',
                                'type' => 'select',
                                'condition' => 'tab_type:is(st_map)',
                                'std'   => 'normal',
                                'choices'=>array(
                                    array(
                                        'value'=>'normal',
                                        'label'=>__('Normal',ST_TEXTDOMAIN)
                                    ),
                                    array(
                                        'value'=>'midnight',
                                        'label'=>__('Midnight',ST_TEXTDOMAIN)
                                    ),
                                    array(
                                        'value'=>'family_fest',
                                        'label'=>__('Family fest',ST_TEXTDOMAIN)
                                    ),
                                    array(
                                        'value'=>'open_dark',
                                        'label'=>__('Open dark',ST_TEXTDOMAIN)
                                    ),
                                    array(
                                        'value'=>'riverside',
                                        'label'=>__('River side',ST_TEXTDOMAIN)
                                    ),
                                    array(
                                        'value'=>'ozan',
                                        'label'=>__('Ozan',ST_TEXTDOMAIN)
                                    ),
                                )
                            ),
                        )
                        
                        
                        
                    ),                   

                )
            );
            
            $this->metabox[] = $metabox;
            
            
            
        }

        /**
         * @since 1.1.3
         * count post type in a location
         *
         */
        static function get_count_post_type($post_type, $location_id = null)
        {
            global $wpdb;
            $meta_key = "id_location";
            if ($post_type == "st_rental") {
                $meta_key = "location_id";
            }
            if (!$location_id) {
                $location_id = get_the_ID();
            }
            $join = self::edit_join_wpml(" join {$wpdb->postmeta} on {$wpdb->postmeta}.post_id = {$wpdb->posts}.ID  " , $post_type);
            $where = "{$wpdb->posts}.post_type = '{$post_type}'
                and {$wpdb->posts}.post_status = 'publish'
                and {$wpdb->postmeta}.meta_key = '{$meta_key}'
                and {$wpdb->postmeta}.meta_value = '{$location_id}'";
            $where = self::edit_where_wpml($where);

            $sql     = "
                SELECT {$wpdb->posts}.ID FROM `{$wpdb->posts}`   
                {$join}              
                where {$where}
                group by {$wpdb->posts}.ID;
                ";
            $results = $wpdb->get_results($sql, OBJECT);
            return ($wpdb->num_rows);
            
        }
        /**
         * @since 1.1.2
         * create new location static sidebar 
         * 
         *
         */
        function add_sidebar()
        {
            register_sidebar(array(
                'name' => __('Location sidebar ', ST_TEXTDOMAIN),
                'id' => 'location-sidebar',
                'description' => __('Widgets in this area will be show information in current Location', ST_TEXTDOMAIN),
                'before_title' => '<h4>',
                'after_title' => '</h4>',
                'before_widget' => '<div id="%1$s" class="sidebar-widget %2$s">',
                'after_widget' => '</div>'
            ));
        }
        static function get_info_by_post_type($id, $post_type = null, $list = NULL)
        {             
            if (!$post_type) {
                return;
            }
            if (!in_array($post_type , array('st_activity','st_hotel','st_rental' , 'st_cars' , 'st_tours' , 'hotel_room'))){return;}
            
            $location_meta_text = 'id_location';
            if ($post_type == 'st_rental') {
                $location_meta_text = 'location_id';
            }
            $where = " (1= 1 )" ; 
            $location_id = get_the_ID();

            if (!$list){
                $list = TravelHelper::getLocationByParent($location_id);
            }
            
            if (is_array($list) and !empty($list) ){
                $where .= " AND (";
                $where_tmp = "";

                foreach($list as $item){
                    if(empty($where_tmp)){
                        $where_tmp .= "tb.multi_location LIKE '%_{$item}_%'";
                    }else{
                        $where_tmp .= " OR tb.multi_location LIKE '%_{$item}_%'";
                    }
                }
                $list = implode(',', $list);
                $where_tmp .= " OR tb.{$location_meta_text} IN ({$list})";
                $where .= $where_tmp.")";

            }else {
                $where  = "(tb.multi_location LIKE '%_{$location_id}_%' 
                        OR tb.{$location_meta_text} IN ('{$location_id}')) " ;
            }            
            $results = array();   
            $num_rows  = 0;
            global $wpdb;
            $table = $wpdb->prefix.$post_type;
            $join  = " {$table} as tb ON  {$wpdb->posts}.ID = tb.post_id " ; 

            $join = self::edit_join_wpml($join, $post_type) ; 
            $where = self::edit_where_wpml($where, $post_type) ;

            if(TravelHelper::checkTableDuplicate($post_type)){
                $sql = "SELECT {$wpdb->posts}.ID
                    FROM {$wpdb->posts} 
                    join {$join}              
                    where {$where}
                    GROUP BY {$wpdb->posts}.ID";
                //echo $sql ; 
                $results     = $wpdb->get_results($sql, ARRAY_A);
                $num_rows    = $wpdb->num_rows;
                wp_reset_postdata();
            }
            // get review = count all comment number
            $comment_num = 0;
            
            if (is_array($results) and !empty($results)){
                foreach ($results as $row) {
                    $comment_num = $comment_num + STReview::count_all_comment($row['ID']);
                }
            }
            if ($num_rows > 1) {
                $name = self::get_post_type_name($post_type);
            } else {
                $name = self::get_post_type_name($post_type, true);
            }
            if ($list = TravelHelper::getLocationByParent(get_the_ID())){
                $min_max_price = self::get_min_max_price_location($post_type, $list) ; 
            }else {
                $min_max_price = self::get_min_max_price_location($post_type, $location_id) ; 
            }
            
            return array(
                'post_type' => $post_type,
                'post_type_name' => $name,
                'reviews' => $comment_num,
                'offers' => $num_rows,
                'min_max_price' => $min_max_price
            );
            
        }
        /**
         * @package Wordpress
         * @subpackage traveler
         * @since 1.1.3
         *
         */
        static function get_post_type_name($post_type, $is_single = NULL)
        {
            ob_start();
            
            if ($is_single) {
                if ($post_type == "st_cars") {
                    st_the_language("car");
                }
                if ($post_type == "st_tours") {
                    st_the_language("tour");
                }
                if ($post_type == "st_rental") {
                    st_the_language("rental");
                }
                if ($post_type == "st_activity") {
                    st_the_language("activity");
                }
                if ($post_type == "st_hotel") {
                    st_the_language("hotel");
                }
            } else {
                if ($post_type == "st_cars") {
                    st_the_language("cars");
                }
                if ($post_type == "st_tours") {
                    st_the_language("tours");
                }
                if ($post_type == "st_rental") {
                    st_the_language("rentals");
                }
                if ($post_type == "st_activity") {
                    st_the_language("activities");
                }
                if ($post_type == "st_hotel") {
                    st_the_language("hotels");
                }
            }
            $return = ob_get_clean();
            return $return;
        }
        // from 1.1.9
        static function get_location_terms(){         
            $return = array();    
            $array = (get_object_taxonomies( 'location', 'array' )); 
            if (!empty($array) and  is_array($array)) {
                foreach ($array as $tax => $value) {
                    $terms = get_terms( $tax, $args = array(
                      'hide_empty' => false,
                    )); 
                    if (!empty($terms) and is_array($terms)){
                        foreach ($terms as $key => $values) {
                            $return[] = $values ; 
                        }
                    }                    
                }
            }  
            return $return;
        }

        /**
         *
         * since 1.1.2
         * get single price
         *
         */
        public static function get_item_price($post_id)
        {
            if (!$post_id) {
                $post_id = get_the_ID();
            }
            $post_type = get_post_type($post_id);
            $discount  = get_post_meta($post_id, 'discount', true);
            if ($post_type == "st_rental" or $post_type == "hotel_room") {
                $discount = get_post_meta($post_id, 'discount_rate', true);
            }
            $is_sale_schedule = get_post_meta($post_id, 'is_sale_schedule', true);
            
            if ($post_type == "st_cars") {
                $price = get_post_meta($post_id, 'cars_price', true);
            } else {
                $price = get_post_meta($post_id, 'price', true);
            }
            if ($is_sale_schedule == 'on') {
                $sale_from = get_post_meta($post_id, 'sale_price_from', true);
                $sale_to   = get_post_meta($post_id, 'sale_price_to', true);
                if ($sale_from) {
                    
                    $today     = date('Y-m-d');
                    $sale_from = date('Y-m-d', strtotime($sale_from));
                    $sale_to   = date('Y-m-d', strtotime($sale_to));
                    if (($today >= $sale_from) && ($today <= $sale_to)) {
                        
                    } else {
                        
                        $discount = 0;
                    }
                    
                } else {
                    $discount = 0;
                }
            }
            if ($discount) {
                if ($discount > 100)
                    $discount = 100;
                $new_price = $price - ($price / 100) * $discount;
            } else {
                $new_price = $price;
            }
            return apply_filters('location_single_price', $new_price);
            
        }
        public static function get_min_max_price_location($post_type, $list)
        {            
            if (!in_array($post_type, array(
                'st_cars',
                'st_tours',
                'st_hotel',
                'st_activity',
                'st_rental'
            ))) {
                return;
            }
            $location_meta_text = 'id_location';
            if ($post_type == 'st_rental') {
                $location_meta_text = 'location_id';
            }

            global $wpdb;
            $join = " join {$wpdb->prefix}{$post_type} as tb on tb.post_id ={$wpdb->posts}.ID " ;
            $join = self::edit_join_wpml($join, $post_type) ; 

            $where = " where (1= 1 )" ; 
            $location_id = get_the_ID();
            
            if (is_array($list) and !empty($list) ){
                $where .= " AND (";
                $where_tmp = "";

                foreach($list as $item){
                    if(empty($where_tmp)){
                        $where_tmp .= "tb.multi_location LIKE '%_{$item}_%'";
                    }else{
                        $where_tmp .= " OR tb.multi_location LIKE '%_{$item}_%'";
                    }
                }
                $list = implode(',', $list);
                $where_tmp .= " OR tb.{$location_meta_text} IN ({$list})";
                $where .= $where_tmp.")";

            }else {
                $where  = "(tb.multi_location LIKE '%_{$location_id}_%' 
                        OR tb.{$location_meta_text} IN ('{$location_id}')) " ;
            }
            $where = self::edit_where_wpml($where);
            $sql = "
                select {$wpdb->posts}.ID from {$wpdb->posts} " ;
            $sql.=$join;
            $sql.= $where; 


            $results = $wpdb->get_results($sql, OBJECT);
            $min_price = 999999999999;
            $max_price = 0; // don't care this
            $detail    = array();
            
            if (!is_array($results) or empty($results)) {
                return;
            }
            if ($post_type == "st_hotel") {
                foreach ($results as $post_id) {
                    $post_id = $post_id->ID;
                    // call all room of current hotel
                    // coppy from hotel class and fixed . 
                    $query   = array(
                        'post_type' => 'hotel_room',
                        'meta_key' => 'room_parent',
                        'meta_value' => $post_id
                    );
                    $q       = new WP_Query($query);

                    wp_reset_postdata();

                    while ($q->have_posts()) {
                        $q->the_post();
                        $price = self::get_item_price(get_the_ID());
                        if ($price < $min_price) {
                            $min_price                    = $price;
                            $detail['item_has_min_price'] = get_the_ID();
                        }

                    }
                    if ($min_price == 999999999999){$min_price = 0;}  
                }
                
            } else {
                foreach ($results as $post_id) {
                    $post_id          = $post_id->ID;
                    $price_text_field = "price";

                    $price = self::get_item_price($post_id);

                    if ($post_type =='st_tours' || $post_type =='st_activity'){
                        $price = get_post_meta($post_id, 'child_price' , true);
                    }

                    if ($price < $min_price and $price) {
                        $min_price                    = $price;
                        $detail['item_has_min_price'] = $post_id;    
                    }
                    if ($price > $max_price) {
                        $max_price                    = $price;
                        $detail['item_has_max_price'] = $post_id;
                    }
                    
                }
                if ($min_price == 999999999999){$min_price = 0;}    
            }

            wp_reset_postdata();
            return array(
                'price_min' => $min_price,
                'price_max' => $max_price,
                'detail' => $detail
            );
        }
        static function scrop_thumb($image)
        {
            $return = '<img src="' . esc_url($image) . '" style="width: 100%" alt = "' . get_the_title() . '" >';
            return apply_filters('location_item_crop_thumb', $return);
        }
        
        /**
         * @since 1.1.3
         * get location information
         *
         */
        
        static function get_slider($gallery_array)
        {
            $return ="";
            $gallery_array = explode(',', $gallery_array);
            $return .= '<div class="fotorama" data-allowfullscreen="true" data-nav="thumbs">';
            if (is_array($gallery_array) and !empty($gallery_array)) {
                foreach ($gallery_array as $key => $value) {
                    $return .= wp_get_attachment_image($value, array(
                        800,
                        600,
                    ));
                }
            }
            $return .= '</div>';
            return $return;
        }
        /**
         * @since 1.1.3
         * static rate by location and rate
         * return array(1=>xx , 2=> xyz , 3=>sss  , 4=>ssss, 5+>ksfs)
         **/
        static function get_rate_count($star_array, $post_type_array)
        {
            global $wpdb;
            
            if (!$star_array) {
                $star_array = array(
                    5,
                    4,
                    3,
                    2,
                    1
                );
            }
            if (!$post_type_array) {
                $post_type_array = array(
                    'st_cars',
                    'st_hotel',
                    'st_rental',
                    'st_tours',
                    'st_activity'
                );
            }
            $post_type_list_sql ="";
            
            if (!empty($post_type_array) and is_array($post_type_array)) {
                $post_type_list_sql .= " and ( ";
                foreach ($post_type_array as $key => $value) {
                    if ($key == 0) {
                        $post_type_list_sql .= "soifjsf .post_type = '{$value}' ";
                    } else {
                        $post_type_list_sql .= " or soifjsf .post_type = '{$value}' ";
                    }
                }
                $post_type_list_sql .= " ) ";
            }
            
            
            $return      = array();
            $location_id = get_the_ID();
            $list = TravelHelper::getLocationByParent(get_the_ID());

            $where_cla ="" ; 
            if (is_array($list) and !empty($list)){
                foreach ($list as $key => $value) {
                    if ($key>0){$where_cla .= " or ";}
                    $where_cla .= " meta_value LIKE '%_{$value}_%' ";
                    $where_cla .= " or meta_value = {$value} " ;                      
                }
            }
            if (is_array($star_array) and !empty($star_array)) {
                foreach ($star_array as $key => $value) {
                    $star    = $value;
                    $sql     = "
                        SELECT wp_commentmeta.comment_id as count_rate FROM {$wpdb->commentmeta}  
                        join {$wpdb->comments}  on {$wpdb->commentmeta} .comment_id = {$wpdb->comments} .comment_ID
                        join {$wpdb->posts} as soifjsf on {$wpdb->comments} .comment_post_ID = soifjsf .ID
                        where {$wpdb->commentmeta} .meta_key = 'comment_rate' and {$wpdb->commentmeta} .meta_value = {$star}
                        and soifjsf .comment_status  = 'open'
                        and soifjsf .post_status = 'publish'
                            " . $post_type_list_sql . "

                        AND soifjsf.ID IN (
                            SELECT
                                DISTINCT ID
                            FROM
                                {$wpdb->posts}
                            JOIN {$wpdb->postmeta} ON {$wpdb->postmeta}.post_id = {$wpdb->posts}.ID
                            and 
                             (
                                (
                                    meta_key = 'id_location'
                                    OR meta_key = 'location_id'
                                    OR meta_key = 'multi_location'
                                )
                                AND (
                                    {$where_cla}
                                )
                            )
                            where (
                                {$wpdb->posts}.post_type = 'st_cars' 
                                or {$wpdb->posts}.post_type = 'st_tours'  
                                or {$wpdb->posts}.post_type = 'st_rental' 
                                or {$wpdb->posts}.post_type = 'st_hotel' 
                                or {$wpdb->posts}.post_type = 'st_activity' 
                                or {$wpdb->posts}.post_type = 'st_cars' 
                                )
                        )
                        and {$wpdb->comments} .comment_approved = 1
                        GROUP BY {$wpdb->commentmeta} .comment_id"; 

                    
                    $results = $wpdb->get_results($sql, OBJECT);
                    if ($results){
                        $return[$star] = count($results);
                    }
                    
                }
            }
            
            return $return;
            
        }
        /**
         * @package wordpress
         * @subpackage traveler 
         * @since 1.1.3
         * @descript get random post type to show widget 
         */
        public static function get_random_post_type($location_id, $post_type)
        {
            if (!$location_id) {
                $location_id = get_the_ID();
            }
            if (!$post_type) {
                $post_type = "st_cars";
            }
            $meta_key = "id_location";
            if ($post_type == 'st_rental') {
                $meta_key = 'location_id';
            }
            
            $query = array(
                'posts_per_page' => 1,
                'post_type' => $post_type,
                'orderby' => 'rand',
                'post_status' => 'publish',
                'meta_key' => $meta_key,
                'meta_value' => $location_id
            );
            query_posts($query);
            while (have_posts()) {
                the_post();
                $id = get_the_ID();
            }
            wp_reset_postdata();
            return $id;
        }
        /**
         * since 1.1.4
         */
        static function get_default_icon($value)
        {
            if (!$value) {
                return;
            }
            $icon;
            if ($value == 'st_cars')
                $icon = 'fa fa-car';
            if ($value == 'st_tours')
                $icon = 'fa fa-flag-o';
            if ($value == 'st_hotel')
                $icon = 'fa fa-building-o';
            if ($value == 'st_rental')
                $icon = 'fa fa-home';
            if ($value == 'st_activity')
                $icon = 'fa fa-bolt';
            if ($value == 'hotel_room')
                $icon = 'fa fa-home';
            return $icon;
        }
        /**
         * get list post for Location description hightlights
         * since 1.1.5
         *
         */
        static function get_list_post_location_info()
        {
            if(!isset($_GET['post']) || get_post_type($_GET['post']) != 'location'){
                return;
            }
            if (!isset($_GET['post'])) {
                return;
            }
            global $wpdb;
            $location_id = $_GET['post'];
            $sql         = "
                select {$wpdb->posts}.ID, post_title from {$wpdb->posts}
                join {$wpdb->postmeta} on {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id
                where {$wpdb->posts}.post_status = 'publish'
                and {$wpdb->posts}.post_type = 'post'
                and {$wpdb->postmeta}.meta_key = 'location_select'
                and {$wpdb->postmeta}.meta_value = '{$location_id}'
                group by {$wpdb->posts}.ID
                ";
            $results     = $wpdb->get_results($sql, OBJECT);
            $return = array();
            if (is_array($results) and !empty($results)) {
                foreach ($results as $key => $value) {
                    $return[] = array(
                        'value' => $value->ID,
                        'label' => $value->post_title
                    );
                }
            }
            return $return;
        }
        public function get_location_list()
        {
            $return = array();
            $arg    = array(
                'post_type' => 'location',
                'nopaging' => true,
                'orderby' => 'ID',
                'post_status' => 'publish'
            );
            foreach (query_posts($arg) as $key => $value) {
                $return[] = array(
                    'id' => $value->ID,
                    'title' => $value->post_title,
                    'post_parent' => $value->post_parent
                );
            }
            wp_reset_postdata();
            return $return;
            
        }
        /**
         * widget location statistical
         * since 1.1.5
         *
         */
        static function location_widget3($instance)
        {
            global $wpdb;
            $style = $instance['style'];
            if (!$style) {
                $add_sql = " order by {$wpdb->posts}.ID DESC";
            }
            if ($style == 'latest') {
                $add_sql = " order by {$wpdb->posts}.post_date_gmt DESC ";
            }
            if ($style == 'famous') {
                return self::get_famous_location($instance);
            }
            
            if (!$instance['location']) {
                $instance['location'] = get_the_ID();
            }
            $list = TravelHelper::getLocationByParent($instance['location']);
            $add_where = " ";
            if (is_array($list) and !empty($list)){
                foreach ($list as $key => $value) {
                    $add_where  .= " or ({$wpdb->postmeta}.meta_value LIKE '%_{$value}_%' and {$wpdb->postmeta}.meta_key = 'multi_location')";
                }
            }
            $join = " join {$wpdb->postmeta} on {$wpdb->postmeta}.post_id = {$wpdb->posts}.ID    "; 
            $join = self::edit_join_wpml($join , $instance['post_type']);
            $where = "
                ({$wpdb->postmeta}.meta_value  = {$instance['location']} and {$wpdb->postmeta}.meta_key = 'id_location')
                 or ({$wpdb->postmeta}.meta_value  = {$instance['location']} and {$wpdb->postmeta}.meta_key = 'location_id')
                    {$add_where}
                    ";
            $where = self::edit_where_wpml($where);
            $sql = "
                select {$wpdb->posts}.ID from {$wpdb->posts} 
                {$join}                
                where 
                   (
                    {$where}
                 )
                
                ";
            $sql .= "
                and {$wpdb->posts}.post_status = 'publish'
                and {$wpdb->posts}.post_type = '{$instance['post_type']}' 
                group by {$wpdb->posts}.ID ";
            $sql .= $add_sql;
            $sql .= " limit 0 ,{$instance['count']}
                ";
            
            $results = $wpdb->get_results($sql, OBJECT);
            return $results;
        }
        /**
         * @since 1.1.6
         * 
         *
         */
        static function get_famous_location($instance)
        {
            global $wpdb;
            $item_post_type = $instance['post_type'];
            $location_id    = $instance['location'];
            $count          = $instance['count'];
            $join = "
                INNER JOIN {$wpdb->postmeta} as mt1 ON mt1.post_id={$wpdb->posts}.ID AND mt1.meta_key='item_post_type' AND mt1.meta_value='{$item_post_type}' 
            ";
            $where = "
                {$wpdb->postmeta}.meta_value 
                            in (
                                select post_id 
                                from {$wpdb->postmeta} 
                                where ({$wpdb->postmeta}.meta_key = 'id_location' or {$wpdb->postmeta}.meta_key = 'location_id') 
                                and {$wpdb->postmeta}.meta_value= '{$location_id}') 
            ";
            $sql            = "
                 SELECT count({$wpdb->postmeta}.meta_value) as count , {$wpdb->postmeta}.meta_value as ID
                    FROM {$wpdb->posts} INNER JOIN {$wpdb->postmeta} ON {$wpdb->posts}.ID={$wpdb->postmeta}.post_id AND {$wpdb->postmeta}.meta_key='item_id'
                    {$join}
                    WHERE 
                        {$where}
                    and {$wpdb->posts}.post_status = 'publish' 
                    group by  {$wpdb->postmeta}.meta_value
                    order by count({$wpdb->postmeta}.meta_value) DESC
             
                ";
            $results        = $wpdb->get_results($sql, OBJECT);
            
            $return = array();
            if (!empty($results) and is_array($results)){
                foreach ($results as $key => $value) {
                    if ($key <= ($count - 1)) {
                        $return[] = $value;
                    }
                }
            }
            
            return $return;
        }
        /**
         * @since 1.1.6
         * 
         *
         */
        static function query_location_($post_parent)
        {
            global $wpdb;
            $query   = "select ID, post_parent, post_title from {$wpdb->posts} 
                where {$wpdb->posts}.post_status = 'publish'
                and {$wpdb->posts}.post_parent = {$post_parent}
                and {$wpdb->posts}.post_type = 'location'
                order by {$wpdb->posts}.post_parent";
            $results = $wpdb->get_results($query, OBJECT);
            return $wpdb->last_result;
            
        }
        /**
         * @since 1.1.6
         * 
         *
         */
        static function round_count_reviews($reviews_num)
        {
            if (!$reviews_num) {
                return;
            }
            if ($reviews_num >= 1000 and $reviews_num < 1000000) {
                $reviews_num = (round($reviews_num / 1000)) . "." . "000+ ";
            }
            if ($reviews_num >= 1000000) {
                $reviews_num = (round($reviews_num / 1000000)) . "." . "000.000+ ";
            }
            return $reviews_num;
        }
        /**
         * @since 1.1.6
         * create a list post type in this location . 
         * to gmap3
         */
        static function get_list_post_type()
        {
            
            if (!is_singular('location')) {
                return;
            }
            
            global $wpdb;
            $list_post_active = self::get_post_type_list_active();
            $post_type_sql = " and ( ";
            if(is_array($list_post_active) and !empty($list_post_active)){
                foreach ($list_post_active as $key => $value) {
                    if ($key !=0){
                        $post_type_sql .=" or {$wpdb->posts}.post_type = '{$value}' ";
                    }else{
                        $post_type_sql .=" {$wpdb->posts}.post_type = '{$value}' ";
                    }
                }
            }
            $post_type_sql .= " ) ";
            $location_id = get_the_ID();

            $sql = "
                select ID , post_title, post_type   from {$wpdb->posts} , {$wpdb->postmeta} 
                where {$wpdb->postmeta}.post_id = {$wpdb->posts}.ID 
                and {$wpdb->postmeta}.meta_key = 'id_location' 
                and {$wpdb->postmeta}.meta_value = '{$location_id}' 
                and {$wpdb->posts}.post_status = 'publish' 
                ";
            $sql .= $post_type_sql;
            $sql .= "
                group by ID";
            $results = $wpdb->get_results($sql, OBJECT);

            $list_post_type = array();
            if (is_array($results) and !empty($results)) {
                foreach ($results as $key => $value) {
                    $array_flag['ID']  = $value->ID;
                    $array_flag['lat'] = get_post_meta($value->ID, 'map_lat', true);
                    $array_flag['lng'] = get_post_meta($value->ID, 'map_lng', true);
                    $array_flag['link']  = get_permalink($value->ID);                    
                    $array_flag['post_type']= $value->post_type;                    
                    $list_post_type[]          = $array_flag;
                }
            }
            $data_zoom_l  = get_post_meta(get_the_ID(),'map_zoom', true);
            
            if (!$data_zoom_l){$data_zoom_l = 15;}
            $current_location = array(
                'title'=>get_the_title(),
                'map_lat'=>get_post_meta(get_the_ID(), 'map_lat', true),
                'map_lng'=>get_post_meta(get_the_ID(), 'map_lng', true),
                'location_map_zoom'=>$data_zoom_l
                );
            
            /*wp_localize_script('jquery','default_icon_gmap3_location' , array(
                'st_cars'=>st()->get_option('st_cars_icon_map_marker'),
                'st_tours'=>st()->get_option('st_tours_icon_map_marker'),
                'st_hotel'=>st()->get_option('st_hotel_icon_map_marker'),
                'st_rental'=>st()->get_option('st_rental_icon_map_marker'),
                'st_activity'=>st()->get_option('st_activity_icon_map_marker'),
                ));*/
            wp_localize_script('jquery','current_location' ,$current_location);
            //wp_localize_script('jquery', 'list_post_type', $list_post_type);
        }
        /**
        * from 1.1.8
        */
        static function edit_where_wpml($where ,$post_type = null){            
            if(defined('ICL_LANGUAGE_CODE')){
                global $wpdb;
                $current_language = ICL_LANGUAGE_CODE;                
                $where.= " AND t.language_code = '{$current_language}' " ;
            }
            return $where; 
        }
        /**
        * from 1.1.8
        */
        static function edit_join_wpml($join ,$post_type){            
            if(defined('ICL_LANGUAGE_CODE')){
                global $wpdb;
                $join.= "
                join {$wpdb->prefix}icl_translations as  t ON {$wpdb->posts}.ID = t.element_id
                AND t.element_type = 'post_{$post_type}'
                JOIN {$wpdb->prefix}icl_languages as  l ON t.language_code = l. CODE
                AND l.active = 1 " ; 
            }
            return $join; 
        }
        

    }
    
    $a = new STLocation();
    $a->init();
}

// for location single 
if(!class_exists('st_location')){
    class st_location{
        static function _get_query_join($join){
            $post_type = $_SESSION['el_post_type'];
            if(!TravelHelper::checkTableDuplicate($post_type)) return $join;
            global $wpdb;

            $table = $wpdb->prefix.$post_type;

            $join .= " INNER JOIN {$table} as tb ON {$wpdb->prefix}posts.ID = tb.post_id";

            return $join;
        }

        static function _get_query_where($where){
            $post_type = $_SESSION['el_post_type'];
            if(!TravelHelper::checkTableDuplicate($post_type)) return $where;
            global $wpdb;

            $location_field = 'id_location';
            if($post_type == 'st_rental') $location_field = 'location_id';
            $location_id = get_the_ID();
            $list = TravelHelper::getLocationByParent($location_id);
            if(is_array($list) && count($list)){
                $where .= " AND (";
                $where_tmp = "";
                foreach($list as $item){
                    if(empty($where_tmp)){
                        $where_tmp .= "tb.multi_location LIKE '%_{$item}_%'";
                    }else{
                        $where_tmp .= " OR tb.multi_location LIKE '%_{$item}_%'";
                    }
                }
                $list = implode(',', $list);
                $where_tmp .= " OR tb.{$location_field} IN ({$list})";
                $where .= $where_tmp.")";
            }else{
                $where .= " AND (tb.multi_location LIKE '%_{$location_id}_%' OR tb.{$location_field} IN ('{$location_id}')) ";
            }    
            return $where;
        }
    }
}