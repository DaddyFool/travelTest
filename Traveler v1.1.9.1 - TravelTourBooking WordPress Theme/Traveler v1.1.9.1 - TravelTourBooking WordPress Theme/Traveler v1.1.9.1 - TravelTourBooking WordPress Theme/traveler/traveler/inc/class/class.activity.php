<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Class STActivity
 *
 * Created by ShineTheme
 *
 */
if(!class_exists( 'STActivity' )) {
    class STActivity extends TravelerObject
    {
        protected $orderby;
        protected $post_type = "st_activity";
        protected $template_folder='activity';

        function __construct( $tours_id = false )
        {

            $this->orderby = array(
                'new'        => array(
                    'key'  => 'new' ,
                    'name' => __( 'New' , ST_TEXTDOMAIN )
                ) ,
                'price_asc'  => array(
                    'key'  => 'price_asc' ,
                    'name' => __( 'Price (low to high)' , ST_TEXTDOMAIN )
                ) ,
                'price_desc' => array(
                    'key'  => 'price_desc' ,
                    'name' => __( 'Price (high to low)' , ST_TEXTDOMAIN )
                ) ,
                'name_a_z'   => array(
                    'key'  => 'name_a_z' ,
                    'name' => __( 'Activity Name (A-Z)' , ST_TEXTDOMAIN )
                ) ,
                'name_z_a'   => array(
                    'key'  => 'name_z_a' ,
                    'name' => __( 'Activity Name (Z-A)' , ST_TEXTDOMAIN )
                ) ,

            );

        }

        public function getOrderby()
        {
            return $this->orderby;
        }

        /**
         * @since 1.1.7
         * @param $type
         * @return string
         */
        function _get_post_type_icon($type)
        {
            return  "fa fa-bolt";
        }

        /**
         *
         *
         * @update 1.1.3
         * */
        function init()
        {

            if(!$this->is_available())
                return;

            parent::init();
            add_filter( 'st_activity_detail_layout' , array( $this , 'custom_activity_layout' ) );

            // add to cart
            add_action( 'wp_loaded' , array( $this , 'activity_add_to_cart' ) , 20 );

            //custom search Activity template
            add_filter( 'template_include' , array( $this , 'choose_search_template' ) );

            //Sidebar Pos for SEARCH
            add_filter('st_activity_sidebar', array($this, 'change_sidebar'));

            //Filter the search Activity
            //add_action('pre_get_posts',array($this,'change_search_activity_arg'));

            //add Widget Area
            add_action( 'widgets_init' , array( $this , 'add_sidebar' ) );

            //Save car Review Stats
            add_action( 'comment_post' , array( $this , '_save_review_stats' ) );


            // Change cars review arg
            add_filter( 'st_activity_wp_review_form_args' , array( $this , 'comment_args' ) , 10 , 2 );

            add_filter( 'st_search_preload_page' , array( $this , '_change_preload_search_title' ) );

            // Woocommerce cart item information
            add_action( 'st_wc_cart_item_information_st_activity' , array( $this , '_show_wc_cart_item_information' ) );
            add_action( 'st_wc_cart_item_information_btn_st_activity' , array( $this , '_show_wc_cart_item_information_btn' ) );
            add_action( 'st_before_cart_item_st_activity' , array( $this , '_show_wc_cart_post_type_icon' ) );

            add_filter( 'st_add_to_cart_item_st_activity' , array( $this , '_deposit_calculator' ) , 10 , 2 );

        }
        /**
         *
         *
         * @since 1.1.9 
         * */
        function change_sidebar($sidebar = FALSE)
        {
            return st()->get_option('activity_sidebar_pos', 'left');
        }
        /**
         * @since 1.1.9
         * @param $comment_id
         */
        function _save_review_stats($comment_id){
            $comemntObj = get_comment( $comment_id );
            $post_id    = $comemntObj->comment_post_ID;

            if(get_post_type( $post_id ) == 'st_activity') {
                $all_stats       = $this->get_review_stats();
                $st_review_stats = STInput::post( 'st_review_stats' );

                if(!empty( $all_stats ) and is_array( $all_stats )) {
                    $total_point = 0;
                    foreach( $all_stats as $key => $value ) {
                        if(isset( $st_review_stats[ $value[ 'title' ] ] )) {
                            $total_point += $st_review_stats[ $value[ 'title' ] ];
                            //Now Update the Each Stat Value
                            update_comment_meta( $comment_id , 'st_stat_' . sanitize_title( $value[ 'title' ] ) , $st_review_stats[ $value[ 'title' ] ] );
                        }
                    }

                    $avg = round( $total_point / count( $all_stats ) , 1 );

                    //Update comment rate with avg point
                    $rate = wp_filter_nohtml_kses( $avg );
                    if($rate > 5) {
                        //Max rate is 5
                        $rate = 5;
                    }
                    update_comment_meta( $comment_id , 'comment_rate' , $rate );
                    //Now Update the Stats Value
                    update_comment_meta( $comment_id , 'st_review_stats' , $st_review_stats );
                }

                if(STInput::post( 'comment_rate' )) {
                    update_comment_meta( $comment_id , 'comment_rate' , STInput::post( 'comment_rate' ) );

                }
                //review_stats
                $avg = STReview::get_avg_rate( $post_id );

                update_post_meta( $post_id , 'rate_review' , $avg );

            }

        }

        /**
         * @since 1.1.9
         * @return bool
         */
        function get_review_stats()
        {
            $review_stat = st()->get_option( 'activity_review_stats' );

            return $review_stat;
        }

        /**
         * @since 1.1.9
         * @param $comment_form
         * @param bool $post_id
         * @return mixed
         */
        function comment_args( $comment_form , $post_id = false )
        {
            /*since 1.1.0*/

            if(!$post_id)
                $post_id = get_the_ID();
            if(get_post_type( $post_id ) == 'st_activity') {
                $stats = $this->get_review_stats();

                if($stats and is_array( $stats )) {
                    $stat_html = '<ul class="list booking-item-raiting-summary-list stats-list-select">';

                    foreach( $stats as $key => $value ) {
                        $stat_html .= '<li class=""><div class="booking-item-raiting-list-title">' . $value[ 'title' ] . '</div>
                                                    <ul class="icon-group booking-item-rating-stars">
                                                    <li class=""><i class="fa fa-smile-o"></i>
                                                    </li>
                                                    <li class=""><i class="fa fa-smile-o"></i>
                                                    </li>
                                                    <li class=""><i class="fa fa-smile-o"></i>
                                                    </li>
                                                    <li class=""><i class="fa fa-smile-o"></i>
                                                    </li>
                                                    <li><i class="fa fa-smile-o"></i>
                                                    </li>
                                                </ul>
                                                <input type="hidden" class="st_review_stats" value="0" name="st_review_stats[' . $value[ 'title' ] . ']">
                                                    </li>';
                    }
                    $stat_html .= '</ul>';


                    $comment_form[ 'comment_field' ] = "
                        <div class='row'>
                            <div class=\"col-sm-8\">
                    ";
                    $comment_form[ 'comment_field' ] .= '<div class="form-group">
                                            <label>' . __( 'Review Title' , ST_TEXTDOMAIN ) . '</label>
                                            <input class="form-control" type="text" name="comment_title">
                                        </div>';

                    $comment_form[ 'comment_field' ] .= '<div class="form-group">
                                            <label>' . __( 'Review Text',ST_TEXTDOMAIN ) . '</label>
                                            <textarea name="comment" id="comment" class="form-control" rows="6"></textarea>
                                        </div>
                                        </div><!--End col-sm-8-->
                                        ';

                    $comment_form[ 'comment_field' ] .= '<div class="col-sm-4">' . $stat_html . '</div></div><!--End Row-->';
                }
            }

            return $comment_form;
        }

        /**
         *
         *
         * @since 1.1.1
         * */

        function _show_wc_cart_post_type_icon()
        {
            echo '<span class="booking-item-wishlist-title"><i class="fa fa-bolt"></i> ' . __( 'activity' , ST_TEXTDOMAIN ) . ' <span></span></span>';
        }

        /**
         *
         *
         * @since 1.1.1
         * */

        function _show_wc_cart_item_information( $st_booking_data = array() )
        {
            echo st()->load_template( 'activity/wc_cart_item_information' , false , array( 'st_booking_data' => $st_booking_data ) );
        } 
        /**
         *
         *
         * @update 1.1.1
         * */
        static function get_search_fields_name()
        {
            return array(
                'address'       => array(
                    'value' => 'address' ,
                    'label' => __( 'Location' , ST_TEXTDOMAIN )
                ) ,/*
                'address-2'     => array(
                    'value' => 'address-2' ,
                    'label' => __( 'Address (geobytes.com)' , ST_TEXTDOMAIN )
                ) ,*/
                'list_location' => array(
                    'value' => 'list_location' ,
                    'label' => __( 'Location List' , ST_TEXTDOMAIN )
                ) ,
                array(
                    'value' => 'check_in' ,
                    'label' => __( 'Check In' , ST_TEXTDOMAIN )
                ) ,
                array(
                    'value' => 'check_out' ,
                    'label' => __( 'Check Out' , ST_TEXTDOMAIN )
                ) ,
                'taxonomy'      => array(
                    'value' => 'taxonomy' ,
                    'label' => __( 'Taxonomy' , ST_TEXTDOMAIN )
                ) ,
                'item_name'     => array(
                    'value' => 'item_name' ,
                    'label' => __( 'Activity Name' , ST_TEXTDOMAIN )
                ),
                'list_name'=>array(
                    'value'=>'list_name',
                    'label'=>__('List Name',ST_TEXTDOMAIN)
                ),
                'duration-dropdown'=>array(
                    'value'=>'duration-dropdown',
                    'label'=>__('Duration Dropdown',ST_TEXTDOMAIN)
                ),
                'people'=>array(
                    'value'=>'people',
                    'label'=>__('People',ST_TEXTDOMAIN)
                ),
                'price_slider'=>array(
                    'value'=>'price_slider',
                    'label'=>__('Price slider',ST_TEXTDOMAIN)
                ),
            );
        }

        function _change_preload_search_title( $return )
        {
            if(get_query_var( 'post_type' ) == 'st_activity') {
                $return = __( " Activities in %s" , ST_TEXTDOMAIN );

                if(STInput::request( 'location_id' )) {
                    $return = sprintf( $return , get_the_title( STInput::request( 'location_id' ) ) );
                } elseif(STInput::request( 'location_name' )) {
                    $return = sprintf( $return , STInput::request( 'location_name' ) );
                } elseif(STInput::request( 'address' )) {
                    $return = sprintf( $return , STInput::request( 'address' ) );
                } else {
                    $return = __( " Activities" , ST_TEXTDOMAIN );
                }

                $return .= '...';
            }


            return $return;
        }

        function add_sidebar()
        {
            register_sidebar( array(
                'name'          => __( 'Activity Search Sidebar 1' , ST_TEXTDOMAIN ) ,
                'id'            => 'activity-sidebar' ,
                'description'   => __( 'Widgets in this area will be shown on Activity' , ST_TEXTDOMAIN ) ,
                'before_title'  => '<h4>' ,
                'after_title'   => '</h4>' ,
                'before_widget' => '<div id="%1$s" class="sidebar-widget %2$s">' ,
                'after_widget'  => '</div>' ,
            ) );

            register_sidebar( array(
                'name'          => __( 'Activity Search Sidebar 2' , ST_TEXTDOMAIN ) ,
                'id'            => 'activity-sidebar-2' ,
                'description'   => __( 'Widgets in this area will be shown on Activity' , ST_TEXTDOMAIN ) ,
                'before_title'  => '<h4>' ,
                'after_title'   => '</h4>' ,
                'before_widget' => '<div id="%1$s" class="sidebar-widget %2$s">' ,
                'after_widget'  => '</div>' ,
            ) );

        }

        function _get_join_query($join)
        {
            if(!TravelHelper::checkTableDuplicate('st_activity')) return $join;
            global $wpdb;

            $table = $wpdb->prefix.'st_activity';

            $join .= " INNER JOIN {$table} as tb ON {$wpdb->prefix}posts.ID = tb.post_id";

            return $join;
        }

        function _get_where_query($where)
        {
            if(!TravelHelper::checkTableDuplicate('st_activity')) return $where;
            global $wpdb;

            if(isset($_REQUEST['location_id']) && !empty($_REQUEST['location_id'])){
                $location_id = STInput::get('location_id');
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
                    $where_tmp .= " OR tb.id_location IN ({$list})";
                    $where .= $where_tmp.")";
                }else{
                    $where .= " AND (tb.multi_location LIKE '%_{$location_id}_%' OR tb.id_location IN ('{$location_id}')) ";
                }
            }elseif(isset($_REQUEST['location_name']) && !empty($_REQUEST['location_name'])){
                $location_name = STInput::request( 'location_name', '');
                $ids_location = TravelerObject::_get_location_by_name($location_name);
                if(is_array($ids_location) && count($ids_location)){
                    $ids_location_tmp = array();
                    foreach($ids_location as $item){
                        $list = TravelHelper::getLocationByParent($item);
                        if(is_array($list) && count($list)){
                            foreach($list as $item){
                                $ids_location_tmp[] = $item;
                            }
                        }
                    }
                    if(count($ids_location_tmp)){
                        $ids_location = $ids_location_tmp;
                    }
                }
                if(is_array($ids_location) && count($ids_location)){
                    $where .= " AND ((";
                    $where_tmp = "";
                    foreach($ids_location as $id){
                        if(empty($where_tmp)){
                            $where_tmp .= " tb.multi_location LIKE '%_{$id}_%' ";
                        }else{
                            $where_tmp .= " OR tb.multi_location LIKE '%_{$id}_%' ";
                        }

                    }
                    $ids_location = implode(',', $ids_location);

                    $where_tmp .= " OR (tb.id_location IN ({$ids_location})";
                    $where .= $where_tmp.")";
                    $where .= " OR (tb.address LIKE '%{$location_name}%'";
                    $where .= " OR {$wpdb->prefix}posts.post_title LIKE '%{$location_name}%')))";

                }else if(!empty($_REQUEST['search_all'])){
                    $where .= " AND (tb.address LIKE '%{$location_name}%'";
                    $where .= " OR {$wpdb->prefix}posts.post_title LIKE '%{$location_name}%')";
                }
            }elseif(isset($_REQUEST['address']) && !empty($_REQUEST['address'])){
                $address = STInput::request('address');
                $address = explode(",", $address);
                if(!empty($address[0]) and !empty($address[2])){
                    $where .= " AND ( tb.address LIKE '%{$address[0]}%' OR tb.address LIKE '%{$address[2]}%')";
                }else{
                    $where .= " AND ( tb.address LIKE '%{$address}%')";
                }
            }
            if(isset($_REQUEST['people'])){
                $people = intval(STInput::get('people',0));
                $where .= " AND (tb.max_people >= {$people})";
            }

            if(isset($_REQUEST['duration'])){   
                $duration = intval(STInput::get('duration',0));
                $today = time();
                $where .= "AND (
                    (
                        tb.duration >= {$duration}
                        AND tb.type_activity= 'daily_tour'
                    )
                    OR (
                        (
                           (UNIX_TIMESTAMP(STR_TO_DATE(tb.check_out, '%Y-%m-%d')) - UNIX_TIMESTAMP(STR_TO_DATE(tb.check_in, '%Y-%m-%d'))) / (60*60*24) + 1
                        ) >= {$duration}
                    )
                )";
            }
            if(isset($_REQUEST['price_range']) && !empty($_REQUEST['price_range'])) {
                $price = STInput::get( 'price_range' ,'0;0');
                $priceobj      = explode( ';' , $price );

                // convert to default money
                $priceobj[0]=TravelHelper::convert_money_to_default($priceobj[0]);
                $priceobj[1]=TravelHelper::convert_money_to_default($priceobj[1]);

                $min_range = $priceobj[0] ; 
                $max_range = $priceobj[1] ;  
                /*$where .= " AND (
                        (CAST(tb.child_price AS DECIMAL) >= {$min_range} and CAST(tb.child_price AS DECIMAL) <= {$max_range})
                        OR (CAST(tb.adult_price AS DECIMAL) >= {$min_range} and CAST(tb.adult_price AS DECIMAL) <= {$max_range})
                    ) ";*/
                $where .= " AND (
                        (CAST(tb.adult_price AS DECIMAL) >= {$min_range} and CAST(tb.adult_price AS DECIMAL) <= {$max_range})
                    ) ";
            }
            $start = STInput::request("start") ; 
            $end = STInput::request("end");

            if( !empty($start) && !empty($end)){

                $today = date('Y-m-d');
                $check_in = date('Y-m-d',strtotime(TravelHelper::convertDateFormat(STInput::request("start"))));
                $period = TravelHelper::dateDiff($today, $check_in);
                if($period<0)$period=0;
                $check_out = date('Y-m-d',strtotime(TravelHelper::convertDateFormat(STInput::request("end"))));
                $list_date = ActivityHelper::_get_activity_cant_order($check_in, $check_out);
                if(is_array($list_date) && count($list_date)){
                    $list = implode(',', $list_date);
                }else{
                    $list = "''";
                }

                $where .= " AND {$wpdb->posts}.ID NOT IN ({$list})";
                $where .= " AND (
                            (
                                tb.type_activity = 'specific_date'
                                AND (
                                    UNIX_TIMESTAMP(
                                        STR_TO_DATE(tb.check_in, '%Y-%m-%d')
                                    ) - UNIX_TIMESTAMP(
                                        STR_TO_DATE('{$check_in}', '%Y-%m-%d')
                                    ) <= 0
                                )
                                AND (
                                    UNIX_TIMESTAMP(
                                        STR_TO_DATE(tb.check_out, '%Y-%m-%d')
                                    ) - UNIX_TIMESTAMP(
                                        STR_TO_DATE('{$check_out}', '%Y-%m-%d')
                                    ) >= 0
                                )
                            )
                            OR (
                                tb.type_activity = 'daily_activity'
                                AND (tb.activity_booking_period <= {$period})
                            ))";
            }

            if(isset($_REQUEST['star_rate']) && !empty($_REQUEST['star_rate'])){
                $stars = STInput::get('star_rate', 1);
                $stars = explode(',', $stars);
                $all_star = array();
                if (!empty($stars) && is_array($stars)) {
                    foreach ($stars as $val) {
                        for($i = $val; $i < $val + 0.9; $i += 0.1){
                            if ($i){
                                $all_star[] = $i;
                            }                                
                        }
                    }
                }
                
                $list_star = implode(',', $all_star);
                if ($list_star) {
                    $where .= " AND (tb.rate_review IN ({$list_star}))";
                }
            }
            if(isset($_REQUEST['item_id']) and  !empty($_REQUEST['item_id'])){
                    $item_id = STInput::request('item_id', '');
                    $where .= " AND ({$wpdb->prefix}posts.ID = '{$item_id}')";
                } 
            if( isset($_REQUEST['range']) and isset($_REQUEST['location_id']) ){
                $range = STInput::request('range', '5');
                $location_id = STInput::request('location_id');
                $post_type = get_query_var( 'post_type' );
                $map_lat   = (float)get_post_meta( $location_id , 'map_lat' , true );
                $map_lng   = (float)get_post_meta( $location_id , 'map_lng' , true );
                global $wpdb;
                $where .= "
                AND $wpdb->posts.ID IN (
                        SELECT ID FROM (
                            SELECT $wpdb->posts.*,( 6371 * acos( cos( radians({$map_lat}) ) * cos( radians( mt1.meta_value ) ) *
                                            cos( radians( mt2.meta_value ) - radians({$map_lng}) ) + sin( radians({$map_lat}) ) *
                                            sin( radians( mt1.meta_value ) ) ) ) AS distance
                                                FROM $wpdb->posts, $wpdb->postmeta as mt1,$wpdb->postmeta as mt2
                                                WHERE $wpdb->posts.ID = mt1.post_id
                                                and $wpdb->posts.ID=mt2.post_id
                                                AND mt1.meta_key = 'map_lat'
                                                and mt2.meta_key = 'map_lng'
                                                AND $wpdb->posts.post_status = 'publish'
                                                AND $wpdb->posts.post_type = '{$post_type}'
                                                AND $wpdb->posts.post_date < NOW()
                                                GROUP BY $wpdb->posts.ID HAVING distance<{$range}
                                                ORDER BY distance ASC
                        ) as st_data
	            )";
            }
            return $where;
        }
        /**
        * @update 1.1.8
        */
        function _get_where_query_tab_location($where){
            // if in location tab location id = get_the_ID();
            $location_id = get_the_ID();
            if(!TravelHelper::checkTableDuplicate('st_activity')) return $where;
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
                    $where_tmp .= " OR tb.id_location IN ({$list})";
                    $where .= $where_tmp.")";
                }else{
                    $where .= " AND (tb.multi_location LIKE '%_{$location_id}_%' OR tb.id_location IN ('{$location_id}')) ";
                }
            return $where;
        }

        function alter_search_query(){
            add_action('pre_get_posts',array($this,'change_search_activity_arg'));
            add_filter('posts_where', array($this, '_get_where_query'));
            add_filter('posts_join', array($this, '_get_join_query'));
            add_filter('posts_orderby', array($this , '_get_order_by_query'));
        }

        function remove_alter_search_query()
        {
            remove_action('pre_get_posts',array($this,'change_search_activity_arg'));
            remove_filter('posts_where', array($this, '_get_where_query'));
            remove_filter('posts_join', array($this, '_get_join_query'));
            remove_filter('posts_orderby', array($this , '_get_order_by_query'));
        }

        function change_search_activity_arg( $query )
        {
            if (is_admin() and empty( $_REQUEST['is_search_map'] )) return $query;

            $post_type = get_query_var( 'post_type' );
            if($query->is_search && $post_type == 'st_activity') {
                add_filter('posts_where', array($this, '_get_where_query'));
                add_filter('posts_join', array($this, '_get_join_query'));
                add_filter('posts_orderby', array($this , '_get_order_by_query'));

                if(STInput::get( 'item_name' )) {
                    $query->set( 's' , STInput::get( 'item_name' ) );
                }
                
                $tax = STInput::get('taxonomy');

                if(!empty($tax) and is_array($tax))
                {
                    $tax_query=array();
                    foreach($tax as $key=>$value)
                    {
                        if($value)
                        {
                            $value = explode(',',$value);
                            if(!empty($value) and is_array($value)){
                                foreach($value as $k=>$v) {
                                    if(!empty($v)){
                                        $ids[] = $v;
                                    }
                                }
                            }
                            if(!empty($ids)){
                                $tax_query[]=array(
                                    'taxonomy'=>$key,
                                    'terms'=>$ids,
                                    //'COMPARE'=>"IN",
                                    'operator' => 'AND',
                                );
                            }
                            $ids = array();
                        }
                    }

                    $query->set('tax_query',$tax_query);
                }

                $is_featured = st()->get_option( 'is_featured_search_activity' , 'off' );
                if(!empty( $is_featured ) and $is_featured == 'on') {
                    $query->set( 'meta_key' , 'is_featured' );
                    $query->set( 'orderby' , 'meta_value' );
                    $query->set( 'order' , 'DESC' );
                }
                if($orderby = STInput::get( 'orderby' )) {
                    switch( $orderby ) {
                        case "price_asc":
                            $query->set( 'meta_key' , 'sale_price' );
                            $query->set( 'orderby' , 'meta_value_num' );
                            $query->set( 'order' , 'ASC' );

                            break;
                        case "price_desc":
                            $query->set( 'meta_key' , 'sale_price' );
                            $query->set( 'orderby' , 'meta_value_num' );
                            $query->set( 'order' , 'DESC' );
                            break;
                        case "name_a_z":
                            $query->set( 'orderby' , 'name' );
                            $query->set( 'order' , 'asc' );
                            break;
                        case "name_z_a":
                            $query->set( 'orderby' , 'name' );
                            $query->set( 'order' , 'desc' );
                            break;
                    }
                }
                
                if(!empty( $meta_query )) {
                    $query->set( 'meta_query' , $meta_query );
                }
            }else{
                remove_filter('posts_where', array($this, '_get_where_query'));
                remove_filter('posts_orderby', array($this , '_get_order_by_query'));
                //remove_filter('posts_join', array($this, '_get_join_query'));
            }
        }
        /**
        *  since 1.1.8
        */
        static function _get_order_by_query($orderby){
            if (STInput::request('orderby') =='price_desc'){
                $orderby = ' CAST(tb.adult_price as DECIMAL)   desc , CAST(tb.child_price as DECIMAL) desc ';
            }
            if (STInput::request('orderby') =='price_asc'){
                $orderby = ' CAST(tb.child_price as DECIMAL)   asc , CAST(tb.adult_price as DECIMAL) asc  '; 
            }               
            return $orderby ;
        }
        function choose_search_template( $template )
        {
            global $wp_query;
            $post_type = get_query_var( 'post_type' );
            if($wp_query->is_search && $post_type == 'st_activity') {
                return locate_template( 'search-activity.php' );  //  redirect to archive-search.php
            }
            return $template;
        }

        function get_result_string()
        {
            global $wp_query , $st_search_query;
            if($st_search_query) {
                $query = $st_search_query;
            } else $query = $wp_query;
            
            $result_string = '';

            if ($query->found_posts) {
                if($query->found_posts > 1) {
                    $result_string .= esc_html( $query->found_posts ) . __( ' activities ' , ST_TEXTDOMAIN );
                } else {
                    $result_string .= esc_html( $query->found_posts ) . __( ' activity ' , ST_TEXTDOMAIN );
                }
            } else {
                $result_string .= __('No activity found', ST_TEXTDOMAIN);
            }


            $location_id = STInput::get( 'location_id' );
            if (!$location_id){
                $location_id = STInput::get('location_id_pick_up');
            }
            if($location_id and $location = get_post( $location_id )) {
                $result_string .= sprintf( __( ' in %s' , ST_TEXTDOMAIN ) , get_the_title( $location_id ) );
            } elseif(STInput::request( 'location_name' )) {
                $result_string .= sprintf( __( ' in %s' , ST_TEXTDOMAIN ) , STInput::request( 'location_name' ) );
            } elseif(STInput::request( 'address' )) {
                $result_string .= sprintf( __( ' in %s' , ST_TEXTDOMAIN ) , STInput::request( 'address' ) );
            }

            $start = TravelHelper::convertDateFormat(STInput::get('start'));
            $end = TravelHelper::convertDateFormat(STInput::get('end'));

            $start = strtotime( $start );

            $end = strtotime( $end );

            if($start and $end) {
                $result_string .= __( ' on ' , ST_TEXTDOMAIN ) . date_i18n( 'M d' , $start ) . ' - ' . date_i18n( 'M d' , $end );
            }

            if($adult_number = STInput::get( 'adult_number' )) {
                if($adult_number > 1) {
                    $result_string .= sprintf( __( ' for %s adults' , ST_TEXTDOMAIN ) , $adult_number );
                } else {

                    $result_string .= sprintf( __( ' for %s adult' , ST_TEXTDOMAIN ) , $adult_number );
                }

            }

            return esc_html($result_string);

        }

        /**
         * @since 1.0.9
         **/
        function activity_add_to_cart() {
            if(STInput::request('action') == 'activity_add_to_cart') {
                if($this->do_add_to_cart()){
                    $link = STCart::get_cart_link();
                    wp_safe_redirect( $link );
                    die;
                }
            }
        }

        function do_add_to_cart(){
            $pass_validate = true;

            $item_id = intval(STInput::request('item_id', ''));
            if($item_id <= 0 || get_post_type($item_id) != 'st_activity'){
                STTemplate::set_message( __( 'This activity is not available..' , ST_TEXTDOMAIN ) , 'danger' );
                $pass_validate = false;
                return false;
            }

            $number  = 1;

            $type_activity = STInput::request('type_activity', 'specific_date');

            $adult_number = intval(STInput::request('adult_number', 1));
            $child_number = intval(STInput::request('child_number', 0));
            $infant_number = intval(STInput::request('infant_number',0));

            $max_number = intval(get_post_meta($item_id, 'max_people', true));

            $today = date('Y-m-d');
            if($adult_number + $child_number + $infant_number > $max_number){
                STTemplate::set_message( sprintf(__( 'Max of people for this activity is %d people' , ST_TEXTDOMAIN ), $max_number) , 'danger' );
                $pass_validate = FALSE;
                return false;
            }

            if($type_activity == 'specific_date') {

                $check_in = TravelHelper::convertDateFormat(STInput::request('check_in',''));
                
                $check_out = TravelHelper::convertDateFormat(STInput::request('check_out',''));
                
                if(empty($check_in)){
                    STTemplate::set_message( __( 'The check in field is not empty.' , ST_TEXTDOMAIN ) , 'danger' );
                    $pass_validate = false;
                    return false;
                }

                if(empty($check_out)){
                    STTemplate::set_message( __( 'The check out field is not empty.' , ST_TEXTDOMAIN ) , 'danger' );
                    $pass_validate = false;
                    return false;
                }

                $compare = TravelHelper::dateCompare($today, $check_in);

                if($compare < 0){
                    STTemplate::set_message( __( 'This activity has expired' , ST_TEXTDOMAIN ) , 'danger' );
                    $pass_validate = false;
                    return false;
                }

                $people = $adult_number + $child_number + $child_number;
                
                $result = ActivityHelper::_get_free_peple_special($item_id, $check_in, $check_out);

                $free_people = intval(get_post_meta($item_id, 'max_people', true));
                
                if(is_array($result) && count($result))
                    $free_people = intval($result['free_people']);
                
                if($free_people < $people){
                    STTemplate::set_message(sprintf(__('This activity only vacant %d people', ST_TEXTDOMAIN), $free_people), 'danger');
                    $pass_validate = false;
                    return false;
                }

                $data['check_in'] = date('m/d/Y',strtotime($check_in));

                $data['check_out'] = date('m/d/Y',strtotime($check_out));

            }elseif($type_activity == 'daily_activity'){
                $check_in = STInput::request('check_in', '');
                if(empty($check_in)){
                    STTemplate::set_message( __( 'The check in field is not empty.' , ST_TEXTDOMAIN ) , 'danger' );
                    $pass_validate = false;
                    return false;
                }
                $check_in = TravelHelper::convertDateFormat($check_in);

                $check_in = date('Y-m-d', strtotime($check_in));

                $duration = intval(get_post_meta($item_id, 'duration', true));

                $check_out = ($duration >= 2)? strtotime('+ '.$duration.' days', strtotime($check_in)) : strtotime('+ '.$duration.' day', strtotime($check_in));
                $check_out = date('Y-m-d', $check_out);
                
                $booking_period = intval(get_post_meta($item_id, 'activity_booking_period', true));

                $period = TravelHelper::dateDiff($today, $check_in);

                $compare = TravelHelper::dateCompare($today, $check_in);

                if($compare < 0){
                    STTemplate::set_message( __( 'You can not set check-in date in the past' , ST_TEXTDOMAIN ) , 'danger' );
                    $pass_validate = false;
                    return false;
                }

                if($booking_period && $booking_period > $period){
                    STTemplate::set_message(sprintf(__('This activity allow minimum booking is %d day(s)', ST_TEXTDOMAIN), $booking_period), 'danger');
                    $pass_validate = false;
                    return false;
                }

                $people = $adult_number + $child_number + $child_number;

                $result = ActivityHelper::_get_free_peple_daily($item_id, $check_in);
                
                $free_people = intval(get_post_meta($item_id, 'max_people', true));

                if(is_array($result) && count($result))
                    $free_people = intval($result['free_people']);
                
                if($free_people < $people){
                    STTemplate::set_message(sprintf(__('This activity only vacant %d people', ST_TEXTDOMAIN), $free_people), 'danger');
                    $pass_validate = false;
                    return false;
                }

                $data['duration'] = $duration;
            }
            $data['adult_number'] = $adult_number;
            $data['child_number'] = $child_number;
            $data['infant_number'] = $infant_number;
            $data['check_in'] = date('m/d/Y',strtotime($check_in));
            $data['check_out'] = date('m/d/Y',strtotime($check_out));
            $data['type_activity'] = $type_activity;
            
            $data_price = STPrice::getPriceByPeople($item_id, strtotime($check_in), strtotime($check_out), $adult_number, $child_number, $infant_number);
            
            $total_price = $data_price['total_price'];
            $sale_price = STPrice::getSaleTourSalePrice($item_id, $total_price, $type_activity,  strtotime($check_in));
            $discount_rate = STPrice::get_discount_rate($item_id,strtotime($check_in));
            $data['adult_price'] = floatval(get_post_meta($item_id, 'adult_price', true));
            $data['child_price'] = floatval(get_post_meta($item_id, 'child_price', true));
            $data['infant_price'] = floatval(get_post_meta($item_id, 'infant_price', true));
            $data['ori_price'] = $sale_price;
            $data['currency']     = TravelHelper::get_current_currency('symbol');
            $data['currency_rate'] = TravelHelper::get_current_currency('rate');
            $data['currency_pos'] = TravelHelper::get_current_currency('booking_currency_pos');
            $data['commission'] = TravelHelper::get_commission();
            $data['data_price'] = $data_price;
            $data['discount_rate'] = $discount_rate ; 

            if($pass_validate)
                $pass_validate = apply_filters('st_activity_add_cart_validate' , $pass_validate);

            if($pass_validate) {
                STCart::add_cart( $item_id , $number , $sale_price , $data );
            }

            return $pass_validate;
        }

        function get_cart_item_html( $item_id = false )
        {
            return st()->load_template( 'activity/cart_item_html' , null , array( 'item_id' => $item_id ) );
        }

        function custom_activity_layout( $old_layout_id )
        {
            if(is_singular( 'st_activity' )) {
                $meta = get_post_meta( get_the_ID() , 'st_custom_layout' , true );

                if($meta) {
                    return $meta;
                }
            }
            return $old_layout_id;
        }

        function get_search_fields()
        {
            $fields = st()->get_option( 'activity_search_fields' );
            return $fields;
        }

        static function get_info_price( $post_id = null )
        {

            if(!$post_id)
                $post_id = get_the_ID();
            $price     = get_post_meta( $post_id , 'price' , true );
            $new_price = 0;

            $discount         = get_post_meta( $post_id , 'discount' , true );
            $is_sale_schedule = get_post_meta( $post_id , 'is_sale_schedule' , true );

            if($is_sale_schedule == 'on') {
                $sale_from = get_post_meta( $post_id , 'sale_price_from' , true );
                $sale_to   = get_post_meta( $post_id , 'sale_price_to' , true );
                if($sale_from and $sale_from) {

                    $today     = date( 'Y-m-d' );
                    $sale_from = date( 'Y-m-d' , strtotime( $sale_from ) );
                    $sale_to   = date( 'Y-m-d' , strtotime( $sale_to ) );
                    if(( $today >= $sale_from ) && ( $today <= $sale_to )) {

                    } else {

                        $discount = 0;
                    }

                } else {
                    $discount = 0;
                }
            }
            if($discount) {
                if($discount > 100)
                    $discount = 100;

                $new_price = $price - ( $price / 100 ) * $discount;
                $data      = array(
                    'price'     => apply_filters( 'st_apply_tax_amount' , $new_price ) ,
                    'price_old' => apply_filters( 'st_apply_tax_amount' , $price ) ,
                    'discount'  => $discount ,

                );
            } else {
                $new_price = $price;
                $data      = array(
                    'price'    => apply_filters( 'st_apply_tax_amount' , $new_price ) ,
                    'discount' => $discount ,
                );
            }

            return $data;
        }

        function get_near_by( $post_id = false , $range = 20 , $limit = 5 )
        {

            $this->post_type = 'st_activity';
            return parent::get_near_by( $post_id , $range , $limit = 5 );
        }

        static function get_owner_email( $item_id )
        {
            return get_post_meta( $item_id , 'contact_email' , true );
        }

        public static function activity_external_booking_submit()
        {
            /*
             * since 1.1.1 
             * filter hook activity_external_booking_submit
            */
            $post_id = get_the_ID();
            if(STInput::request( 'post_id' )) {
                $post_id = STInput::request( 'post_id' );
            }

            $activity_external_booking      = get_post_meta( $post_id , 'st_activity_external_booking' , "off" );
            $activity_external_booking_link = get_post_meta( $post_id , 'st_activity_external_booking_link' , true );
            if($activity_external_booking == "on" and $activity_external_booking_link !== "") {
                if(get_post_meta( $post_id , 'st_activity_external_booking_link' , true )) {
                    ob_start();
                    ?>
                    <a class='btn btn-primary'
                       href='<?php echo get_post_meta( $post_id , 'st_activity_external_booking_link' , true ) ?>'> <?php st_the_language( 'book_now' ) ?></a>
                    <?php
                    $return = ob_get_clean();
                }
            } else {
                $return = TravelerObject::get_book_btn();
            }
            return apply_filters( 'activity_external_booking_submit' , $return );
        }

        static function get_price_html( $post_id = false , $get = false , $st_mid = '' , $class = '' )
        {
            /*
             * since 1.1.3
             * filter hook get_price_html
            */
            if(!$post_id)
                $post_id = get_the_ID();

            $html = '';

            $prices = self::get_price_person( $post_id );

            $adult_html = '';

            $adult_new_html = '<span class="text-lg lh1em item ">' . TravelHelper::format_money( $prices[ 'adult_new' ] ) . '</span>';

            // Check on sale
            if(isset( $prices[ 'adult' ] ) and $prices[ 'adult' ]) {

                if(  $prices[ 'discount' ])
                {
                    $adult_html = '<span class="text-small lh1em  onsale">' . TravelHelper::format_money( $prices[ 'adult' ] ) . '</span>&nbsp;&nbsp;';

                    $html .= sprintf( __( 'Adult: %s %s' , ST_TEXTDOMAIN ) , $adult_html , $adult_new_html );
                }
                else{
                    $html .= sprintf( __( 'Adult: %s' , ST_TEXTDOMAIN ) , $adult_new_html );
                }
            }

            /*$child_new_html = '<span class="text-lg lh1em item ">' . TravelHelper::format_money( $prices[ 'child_new' ] ) . '</span>';


            // Price for child
            if($prices[ 'child_new' ]) {
                $html .= ' ' . $st_mid . ' ';

                // Check on sale
                if(isset( $prices[ 'child' ] ) and $prices[ 'child' ] and $prices[ 'discount' ]) {
                    $child_html = '<span class="text-small lh1em  onsale">' . TravelHelper::format_money( $prices[ 'child' ] ) . '</span>&nbsp;&nbsp;';

                    $html .= sprintf( __( 'Children: %s %s' , ST_TEXTDOMAIN ) , $child_html , $child_new_html );
                } else {
                    $html .= sprintf( __( 'Children: %s' , ST_TEXTDOMAIN ) , $child_new_html );
                }

            }

            $infant_new_html = '<span class="text-lg lh1em item ">' . TravelHelper::format_money( $prices[ 'infant_new' ] ) . '</span>';


            // Price for child
            if($prices[ 'infant_new' ]) {
                $html .= ' ' . $st_mid . ' ';

                // Check on sale
                if(isset( $prices[ 'infant' ] ) and $prices[ 'infant' ] and $prices[ 'discount' ]) {
                    $infant_html = '<span class="text-small lh1em  onsale">' . TravelHelper::format_money( $prices[ 'infant' ] ) . '</span>&nbsp;&nbsp;';

                    $html .= sprintf( __( 'Infant: %s %s' , ST_TEXTDOMAIN ) , $infant_html , $infant_new_html );
                } else {
                    $html .= sprintf( __( 'Infant: %s' , ST_TEXTDOMAIN ) , $infant_new_html );
                }

            }*/

            return apply_filters( 'st_get_tour_price_html' , $html );
        }

        static function get_price_person( $post_id = null )
        {
            /* @since 1.1.3 */
            if(!$post_id)
                $post_id = get_the_ID();
            $adult_price = get_post_meta( $post_id , 'adult_price' , true );
            $child_price = get_post_meta( $post_id , 'child_price' , true );
            $infant_price = get_post_meta( $post_id , 'infant_price' , true );

            $adult_price = apply_filters( 'st_apply_tax_amount' , $adult_price );
            $child_price = apply_filters( 'st_apply_tax_amount' , $child_price );

            $discount         = get_post_meta( $post_id , 'discount' , true );
            $is_sale_schedule = get_post_meta( $post_id , 'is_sale_schedule' , true );

            if($is_sale_schedule == 'on') {
                $sale_from = get_post_meta( $post_id , 'sale_price_from' , true );
                $sale_to   = get_post_meta( $post_id , 'sale_price_to' , true );
                if($sale_from and $sale_from) {

                    $today     = date( 'Y-m-d' );
                    $sale_from = date( 'Y-m-d' , strtotime( $sale_from ) );
                    $sale_to   = date( 'Y-m-d' , strtotime( $sale_to ) );
                    if(( $today >= $sale_from ) && ( $today <= $sale_to )) {

                    } else {

                        $discount = 0;
                    }

                } else {
                    $discount = 0;
                }
            }
            if($discount) {
                if($discount > 100)
                    $discount = 100;

                $adult_price_new = $adult_price - ( $adult_price / 100 ) * $discount;
                $child_price_new = $child_price - ( $child_price / 100 ) * $discount;
                $infant_price_new = $infant_price - ( $infant_price / 100 ) * $discount;
                $data            = array(
                    'adult'     => $adult_price ,
                    'adult_new' => $adult_price_new ,
                    'child'     => $child_price ,
                    'child_new' => $child_price_new ,
                    'infant'     => $infant_price ,
                    'infant_new' => $infant_price_new ,
                    'discount'  => $discount ,

                );
            } else {
                $data = array(
                    'adult_new' => $adult_price ,
                    'adult'     => $adult_price ,
                    'child'     => $child_price ,
                    'child_new' => $child_price ,
                    'infant'     => $infant_price ,
                    'infant_new' => $infant_price ,
                    'discount'  => $discount ,
                );
            }

            return $data;
        }

        static function get_cart_item_total( $item_id , $item )
        {
            /* @since 1.1.3 */
            $count_sale = 0;
            $price_sale = $item[ 'price' ];
            if(!empty( $item[ 'data' ][ 'discount' ] )) {
                $count_sale = $item[ 'data' ][ 'discount' ];
                $price_sale = $item[ 'data' ][ 'price_sale' ] * $item[ 'number' ];
            }

            $adult_number   = $item[ 'data' ][ 'adult_number' ];
            $child_number   = $item[ 'data' ][ 'child_number' ];
            $adult_price = $item[ 'data' ][ 'adult_price' ];
            $child_price = $item[ 'data' ][ 'child_price' ];

            if($get_array_discount_by_person_num = self::get_array_discount_by_person_num( $item_id )) {
                if($array_adult = $get_array_discount_by_person_num[ 'adult' ]) {
                    if(is_array( $array_adult ) and !empty( $array_adult )) {
                        foreach( $array_adult as $key => $value ) {
                            if($adult_number >= (int)$key) {
                                $adult_price2 = $adult_price * $value;
                            }
                        }
                        $adult_price -= $adult_price2;
                    }
                };
                if($array_child = $get_array_discount_by_person_num[ 'child' ]) {
                    if(is_array( $array_child ) and !empty( $array_child )) {
                        foreach( $array_child as $key => $value ) {
                            if($child_number >= (int)$key) {
                                $child_price2 = $child_price * $value;
                            }
                        }
                        $child_price -= $child_price2;
                    }
                };
            }

            $adult_price = round( $adult_price );
            $child_price = round( $child_price );
            $total_price = $adult_number * st_get_discount_value( $adult_price , $count_sale , false );
            $total_price += $child_number * st_get_discount_value( $child_price , $count_sale , false );

            return $total_price;

        }

        static function get_array_discount_by_person_num( $item_id = false )
        {
            /* @since 1.1.3 */
            $return = array();

            $discount_by_adult = get_post_meta( $item_id , 'discount_by_adult' , true );
            $discount_by_child = get_post_meta( $item_id , 'discount_by_child' , true );

            if(!$discount_by_adult and !$discount_by_child) {
                return false;
            }
            if(is_array( $discount_by_adult ) and !empty( $discount_by_adult )) {
                foreach( $discount_by_adult as $row ) {
                    $key                       = (int)$row[ 'key' ];
                    $value                     = (int)$row[ 'value' ] / 100;
                    $return[ 'adult' ][ $key ] = $value;
                }
            }
            if(is_array( $discount_by_child ) and !empty( $discount_by_child )) {
                foreach( $discount_by_child as $row ) {
                    $key                       = (int)$row[ 'key' ];
                    $value                     = (int)$row[ 'value' ] / 100;
                    $return[ 'child' ][ $key ] = $value;
                }
            }

            return $return;
        }

        /* @since 1.1.3 */
        static function get_taxonomy_and_id_term_activity()
        {
            $list_taxonomy = st_list_taxonomy( 'st_activity' );
            $list_id_vc    = array();
            $param         = array();
            foreach( $list_taxonomy as $k => $v ) {
                $term = get_terms( $v );
                if(!empty( $term ) and is_array( $term )) {
                    foreach( $term as $key => $value ) {
                        $list_value[ $value->name ] = $value->term_id;
                    }
                    $param[ ]                      = array(
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
                    $list_value                    = "";
                    $list_id_vc[ "id_term_" . $v ] = "";
                }
            }

            return array(
                "list_vc"    => $param ,
                'list_id_vc' => $list_id_vc
            );
        }

        /**
         *
         * @since 1.1.3
         * */
        function is_available()
        {

            return st_check_service_available( 'st_activity' );

        }
        /** from 1.1.7*/
        static function get_taxonomy_and_id_term_tour(){
            $list_taxonomy = st_list_taxonomy( 'st_activity' );
            $list_id_vc    = array();
            $param         = array();
            foreach( $list_taxonomy as $k => $v ) {
                $term = get_terms( $v );
                if(!empty( $term ) and is_array( $term )) {
                    foreach( $term as $key => $value ) {
                        $list_value[ $value->name ] = $value->term_id;
                    }
                    $param[ ]                      = array(
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
                    $list_value                    = "";
                    $list_id_vc[ "id_term_" . $v ] = "";
                }
            }

            return array(
                "list_vc"    => $param ,
                'list_id_vc' => $list_id_vc
            );
        }
    }

    $a = new STActivity();
    $a->init();
}
