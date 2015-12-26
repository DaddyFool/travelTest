<?php
    /**
     * @package WordPress
     * @subpackage Traveler
     * @since 1.0
     *
     * Class STTour
     *
     * Created by ShineTheme
     *
     */
    if(!class_exists('STTour'))
    {
        class STTour extends TravelerObject
        {
            protected $post_type="st_tours";
            protected $orderby;
            /**
             * @var string
             * @since 1.1.7
             */
            protected $template_folder='tours';
            function __construct($tours_id=false)
            {
                $this->orderby=array(
                    'new'=>array(
                        'key'=>'new',
                        'name'=>__('New',ST_TEXTDOMAIN)
                    ),
                    'price_asc'=>array(
                        'key'=>'price_asc',
                        'name'=>__('Price (low to high)',ST_TEXTDOMAIN)
                    ),
                    'price_desc'=>array(
                        'key'=>'price_desc',
                        'name'=>__('Price (high to low)',ST_TEXTDOMAIN)
                    ),
                    'name_a_z'=>array(
                        'key'=>'name_a_z',
                        'name'=>__('Tours Name (A-Z)',ST_TEXTDOMAIN)
                    ),
                    'name_z_a'=>array(
                        'key'=>'name_z_a',
                        'name'=>__('Tours Name (Z-A)',ST_TEXTDOMAIN)
                    )
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
                return  "fa fa-flag-o";
            }

            /**
             *
             *
             * @update 1.1.3
             * */

            function init()
            {
               if(!$this->is_available()) return;
                parent::init();

                add_filter('st_tours_detail_layout',array($this,'custom_tour_layout'));

                // add to cart
                add_action('wp_loaded',array($this,'tours_add_to_cart'),20);

                //custom search cars template
                add_filter('template_include', array($this,'choose_search_template'));

                //Sidebar Pos for SEARCH
                add_filter('st_tours_sidebar', array($this, 'change_sidebar'));

                //Save car Review Stats
                add_action( 'comment_post' , array( $this , '_save_review_stats' ) );

                // Change cars review arg
                add_filter( 'st_tours_wp_review_form_args' , array( $this , 'comment_args' ) , 10 , 2 );

                //Filter the search tour
               //add_action('pre_get_posts',array($this,'change_search_tour_arg'));

                //add Widget Area
                add_action('widgets_init',array($this,'add_sidebar'));
                add_filter('st_search_preload_page',array($this,'_change_preload_search_title'));

                //add_filter('st_data_custom_price',array($this,'_st_data_custom_price'));


                // Woocommerce cart item information
                add_action('st_wc_cart_item_information_st_tours',array($this,'_show_wc_cart_item_information'));
                add_action( 'st_wc_cart_item_information_btn_st_tours' , array( $this , '_show_wc_cart_item_information_btn' ) );
                add_action('st_before_cart_item_st_tours',array($this,'_show_wc_cart_post_type_icon'));


                add_filter('st_add_to_cart_item_st_tours', array($this, '_deposit_calculator'), 10, 2);
                if(is_singular('st_tours')){
                    add_action('wp_enqueue_scripts',array($this,'add_scripts'));
                }

            }

            /**
             * @since 1.1.9
             * @param $comment_id
             */
            function _save_review_stats($comment_id){
                $comemntObj = get_comment( $comment_id );
                $post_id    = $comemntObj->comment_post_ID;

                if(get_post_type( $post_id ) == 'st_tours') {
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
             *
             *
             * @since 1.1.9 
             * */
            function change_sidebar($sidebar = FALSE)
            {
                return st()->get_option('tour_sidebar_pos', 'left');
            }
            /**
             * @since 1.1.9
             * @return bool
             */
            function get_review_stats()
            {
                $review_stat = st()->get_option( 'tour_review_stats' );

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
                if(get_post_type( $post_id ) == 'st_tours') {
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
            function _show_wc_cart_item_information($st_booking_data=array())
            {
                echo st()->load_template('tours/wc_cart_item_information',false,array('st_booking_data'=>$st_booking_data));
            } 
            /**
             *
             *
             * @since 1.1.1
             * */
            function _show_wc_cart_post_type_icon()
            {
                echo '<span class="booking-item-wishlist-title"><i class="fa fa-flag-o"></i> '.__('tour',ST_TEXTDOMAIN).' <span></span></span>';
            }


            function _st_data_custom_price(){
                return array('title'=>'Price Custom Settings','post_type'=>'st_tours');
            }

            /**
             *
             *
             * @update 1.1.1
             * */
            static function get_search_fields_name()
            {
                return array(
                    'address'=>array(
                        'value'=>'address',
                        'label'=>__('Location',ST_TEXTDOMAIN)
                    ),/*
                    'address-2'=>array(
                        'value'=>'address-2',
                        'label'=>__('Address (geobytes.com)',ST_TEXTDOMAIN)
                    ),*/
                    'people'=>array(
                        'value'=>'people',
                        'label'=>__('People',ST_TEXTDOMAIN)
                    ),
                    'check_in'=>array(
                        'value'=>'check_in',
                        'label'=>__('Departure date',ST_TEXTDOMAIN)
                    ),
                    'check_out'=>array(
                        'value'=>'check_out',
                        'label'=>__('Arrival Date',ST_TEXTDOMAIN)
                    ),
                    'taxonomy'=>array(
                        'value'=>'taxonomy',
                        'label'=>__('Taxonomy',ST_TEXTDOMAIN)
                    ),
                    'list_location'=>array(
                        'value'=>'list_location',
                        'label'=>__('Location List',ST_TEXTDOMAIN)
                    ),
                    'duration'=>array(
                        'value'=>'duration',
                        'label'=>__('Duration',ST_TEXTDOMAIN)
                    ),
                    'duration-dropdown'=>array(
                        'value'=>'duration-dropdown',
                        'label'=>__('Duration Dropdown',ST_TEXTDOMAIN)
                    ),
                    'item_name'=>array(
                        'value'=>'item_name',
                        'label'=>__('Tour Name',ST_TEXTDOMAIN)
                    ),
                    'list_name'=>array(
                        'value'=>'list_name',
                        'label'=>__('List Name',ST_TEXTDOMAIN)
                    ),
                    'price_slider'=>array(
                        'value'=>'price_slider',
                        'label'=>__('Price slider ',ST_TEXTDOMAIN)
                    )

                );
            }
            function _change_preload_search_title($return)
            {
                if( get_query_var('post_type')=='st_tours')
                {
                    $return=__(" Tours in %s",ST_TEXTDOMAIN);

                    if(STInput::get('location_id'))
                    {
                        $return=sprintf($return,get_the_title(STInput::get('location_id')));
                    }elseif(STInput::get('location_name')){
                        $return=sprintf($return,STInput::get('location_name'));
                    }elseif(STInput::get('address')){
                        $return=sprintf($return,STInput::get('address'));
                    }else {
                        $return=__(" Tours",ST_TEXTDOMAIN);
                    }

                    $return.='...';
                }





                return $return;
            }

            function add_sidebar()
            {
                register_sidebar( array(
                    'name' => __( 'Tours Search Sidebar 1', ST_TEXTDOMAIN ),
                    'id' => 'tours-sidebar',
                    'description' => __( 'Widgets in this area will be shown on Tours', ST_TEXTDOMAIN),
                    'before_title' => '<h4>',
                    'after_title' => '</h4>',
                    'before_widget' => '<div id="%1$s" class="sidebar-widget %2$s">',
                    'after_widget'  => '</div>',
                ) );


                register_sidebar( array(
                    'name' => __( 'Tour Single Sidebar', ST_TEXTDOMAIN ),
                    'id' => 'tour-single-sidebar',
                    'description' => __( 'Widgets in this area will be shown on all tour.', ST_TEXTDOMAIN),
                    'before_title' => '<h4>',
                    'after_title' => '</h4>',
                    'before_widget' => '<div id="%1$s" class="sidebar-widget %2$s">',
                    'after_widget'  => '</div>',
                ) );
            }

            /**
            *@since 1.1.7
            **/
            function search_distinct() {
                return "DISTINCT";
            }
            /**
             *
             *
             *@since 1.1.3
             *
             * */
            function _alter_search_query($where)
            {
                global $wpdb;

                // only daily tour
                // check if tour search result
//                $where.=" AND {$wpdb->posts}.ID in
//                            (SELECT post_id
//                                from $wpdb->postmeta
//                                join $wpdb->posts as mt10 on  {$wpdb->postmeta}.post_id  = mt10.ID
//                                where meta_key='type_tour' and meta_value='daily_tour'
//                                and mt10.post_type = 'st_tours'
//                                )";

                return $where;
            }

            /**
             * @since 1.1.7
             * @param $JOIN
             * @return string
             */
            function _alter_join_query($JOIN)
            {
                return $JOIN;
                global $wpdb;
                //$JOIN.="LEFT JOIN $wpdb->postmeta as st_meta1 on st_meta1.post_id=$wpdb->posts.ID and st_meta1.meta_key='type_tour' and st_meta1.meta_key='daily_tour'";
                return $JOIN;
            }

            function _get_join_query($join)
            {
                if(!TravelHelper::checkTableDuplicate('st_tours')) return $join;
                
                global $wpdb;

                $table = $wpdb->prefix.'st_tours';

                $join .= " INNER JOIN {$table} as tb ON {$wpdb->prefix}posts.ID = tb.post_id";

                return $join;
            }
            /**
            * @update 1.1.8
            *
            */
            function _get_where_query_tab_location($where){
                $location_id = get_the_ID();
                if(!TravelHelper::checkTableDuplicate('st_tours')) return $where;
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
            function _get_where_query($where)
            {
                if(!TravelHelper::checkTableDuplicate('st_tours')) return $where;

                global $wpdb;
                if(isset($_REQUEST['location_id']) && !empty($_REQUEST['location_id'])){
                    $location_id = STInput::get('location_id','');
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
                    $location_name = STInput::get('location_name','');
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
                        foreach($ids_location as $item){
                            if(empty($where_tmp)){
                                $where_tmp .= "tb.multi_location LIKE '%_{$item}_%'";
                            }else{
                                $where_tmp .= " OR tb.multi_location LIKE '%_{$item}_%'";
                            }
                        }
                        $ids_location = implode(',', $ids_location);
                        $where_tmp .= " OR tb.id_location IN ({$ids_location})";

                        $where .= $where_tmp.")";
                        $where .= " OR (tb.address LIKE '%{$location_name}%'";
                        $where .= " OR {$wpdb->prefix}posts.post_title LIKE '%{$location_name}%'))";
                        
                    }else if(!empty($_REQUEST['search_all'])){
                        $where .= " AND (tb.address LIKE '%{$location_name}%'";
                        $where .= " OR {$wpdb->prefix}posts.post_title LIKE '%{$location_name}%')";
                    }
                }elseif(isset($_REQUEST['address']) && !empty($_REQUEST['address'])){
                    $address = STInput::request('address','');
                    $value = STInput::request('address');
                    $value = explode(",", $value);
                    if(!empty($value[0]) and !empty($value[2])){
                        $where .= " AND ( tb.address LIKE '%{$value[0]}%' OR tb.address LIKE '%{$value[2]}%')";
                    }else{
                        $where .= " AND ( tb.address LIKE '%{$address}%')";
                    }
                }
                if(isset($_REQUEST['item_id']) and  !empty($_REQUEST['item_id'])){
                    $item_id = STInput::request('item_id', '');
                    $where .= " AND ({$wpdb->prefix}posts.ID = '{$item_id}')";
                } 
                if(isset($_REQUEST['item_name']) and  !empty($_REQUEST['item_name'])){
                    $item_name = STInput::request('item_name', '');
                    $where .= " AND ({$wpdb->prefix}posts.post_title LIKE '%{$item_name}%')";
                }
                if(isset($_REQUEST['people']) && !empty($_REQUEST['people'])){
                    $people = STInput::get('people', 1);
                    $where .= " AND (tb.max_people >= {$people})";
                }

                if(isset($_REQUEST['duration']) && !empty($_REQUEST['duration'])){
                    $duration = intval(STInput::get('duration', 1));
                    $today = time();

                    $where .= "AND (
                        (
                            tb.duration_day = {$duration}
                            AND tb.type_tour = 'daily_tour'
                        )
                    )";

                    /*$where .= "AND (
                        (
                            tb.duration_day >= {$duration}
                            AND tb.type_tour = 'daily_tour'
                        )
                        OR (
                            (
                               (UNIX_TIMESTAMP(STR_TO_DATE(tb.check_out, '%Y-%m-%d')) - UNIX_TIMESTAMP(STR_TO_DATE(tb.check_in, '%Y-%m-%d'))) / (60*60*24) + 1
                            ) >= {$duration}
                        )
                    )";*/
                }

                if(isset($_REQUEST['price_range']) ) {

                    $price = STInput::get( 'price_range' , '0;0');
                    $priceobj      = explode( ';' , $price );

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
                $end = STInput::request("end") ; 
                if( !empty($start) &&  !empty($end)) {
                    $today = date('Y-m-d');
                    $check_in = date('Y-m-d',strtotime(TravelHelper::convertDateFormat(STInput::request("start"))));
                    $period = TravelHelper::dateDiff($today, $check_in);
                    if($period<0)$period=0;
                    $check_out = date('Y-m-d',strtotime(TravelHelper::convertDateFormat(STInput::request("end"))));
                    $list_date = TourHelper::_tourValidate($check_in);
                    if(is_array($list_date) && count($list_date)){
                        $list = implode(',', $list_date);
                    }else{
                        $list = "''";
                    }

                    $where .= " AND {$wpdb->posts}.ID NOT IN ({$list})";
                   /* $where .= " AND (
                                (
                                    tb.type_tour = 'specific_date'
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
                                    tb.type_tour = 'daily_tour'
                                    AND (tb.tours_booking_period <= {$period})
                                ))";*/
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
            function alter_search_query(){
                add_action('pre_get_posts',array($this,'change_search_tour_arg'));
                add_filter('posts_where', array($this, '_get_where_query'));
                add_filter('posts_join', array($this, '_get_join_query'));
                add_filter('posts_orderby', array($this , '_get_order_by_query'));
            }

            function remove_alter_search_query()
            {
                remove_action('pre_get_posts',array($this,'change_search_tour_arg'));
                remove_filter('posts_where', array($this, '_get_where_query'));
                remove_filter('posts_join', array($this, '_get_join_query'));
                remove_filter('posts_orderby', array($this , '_get_order_by_query'));
            }

            /**
             *
             *
             * @update 1.1.3
             * */
            function change_search_tour_arg($query)
            {

                if (is_admin() and empty( $_REQUEST['is_search_map'] )) return $query;

                $post_type = get_query_var('post_type');

                if($query->is_search && $post_type == 'st_tours')
                {
                    if(STInput::get('item_name'))
                    {
                        $query->set('s',STInput::get('item_name'));
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


                    $is_featured = st()->get_option('is_featured_search_tour','off');
                    if(!empty($is_featured) and $is_featured =='on'){
                        $query->set('meta_key','is_featured');
                        $query->set('orderby','meta_value');
                        $query->set('order','DESC');
                    }

                    if($orderby=STInput::get('orderby'))
                    {
                        switch($orderby){
                            case "price_asc":
                                $query->set('meta_key','sale_price');
                                $query->set('orderby','meta_value_num');
                                $query->set('order','ASC');
                                break;
                            case "price_desc":
                                $query->set('meta_key','sale_price');
                                $query->set('orderby','meta_value_num');
                                $query->set('order','DESC');
                                break;
                            case "name_a_z":
                                $query->set('orderby','name');
                                $query->set('order','asc');
                                break;
                            case "name_z_a":
                                $query->set('orderby','name');
                                $query->set('order','desc');
                                break;
                        }
                    } 

                    if(!empty($meta_query)){
                        $query->set('meta_query',$meta_query);
                    }
                }else{
                    remove_filter('posts_where', array($this, '_get_where_query'));
                }
            }
            function choose_search_template($template)
            {
                global $wp_query;
                $post_type = get_query_var('post_type');
                if( $wp_query->is_search && $post_type == 'st_tours' )
                {
                    return locate_template('search-tour.php');  //  redirect to archive-search.php
                }
                return $template;
            }

            function get_result_string()
            {
                global $wp_query,$st_search_query;
                if($st_search_query){
                    $query=$st_search_query;
                }else $query=$wp_query;

                $result_string='';

                if ($query->found_posts) {
                    if($query->found_posts > 1){
                        $result_string.=esc_html( $query->found_posts).__(' tours ',ST_TEXTDOMAIN);
                    }else{
                        $result_string.=esc_html( $query->found_posts).__(' tour ',ST_TEXTDOMAIN);
                    }
                } else {
                    $result_string = __('No tour found', ST_TEXTDOMAIN);
                }


                $location_id=STInput::get('location_id');
                if (!$location_id){
                    $location_id = STInput::get('location_id_pick_up') ; 
                }
                if($location_id and $location=get_post($location_id))
                {
                    $result_string.=sprintf(__(' in %s',ST_TEXTDOMAIN),get_the_title($location_id));
                }elseif(STInput::request('location_name')){
                    $result_string.=sprintf(__(' in %s',ST_TEXTDOMAIN), STInput::request('location_name'));
                }elseif(STInput::request('address')){
                    $result_string.=sprintf(__(' in %s',ST_TEXTDOMAIN), STInput::request('address'));
                }

                $start=TravelHelper::convertDateFormat(STInput::get('start'));
                $end=TravelHelper::convertDateFormat(STInput::get('end'));

                $start=strtotime($start);

                $end=strtotime($end);

                if($start and $end)
                {
                    $result_string.=__(' on ',ST_TEXTDOMAIN).date_i18n('M d',$start).' - '.date_i18n('M d',$end);
                }

                if($adult_number=STInput::get('adult_number')){
                    if($adult_number>1){
                        $result_string.=sprintf(__(' for %s adults',ST_TEXTDOMAIN),$adult_number);
                    }else{

                        $result_string.=sprintf(__(' for %s adult',ST_TEXTDOMAIN),$adult_number);
                    }

                }

                return esc_html($result_string);

            }
            static function get_count_book($post_id=null){
                if(!$post_id) $post_id=get_the_ID();
                //  $post_type = get_post_type($id_post);
                $query = array(
                    'post_type'=>'st_order',
                    'post_per_page'=>'-1',
                    'meta_query'=>array(
                        array(
                            'key'=>'item_id',
                            'value'=>$post_id,
                            'compare'=>"="
                        )
                    ),
                );

                $query = new WP_Query( $query );
                $count= $query->post_count;

                wp_reset_postdata();
                return $count;
            }
            static function get_count_user_book($post_id=null){
                if(!$post_id) $post_id=get_the_ID();
                $count = 0;
                if(st()->get_option('use_woocommerce_for_booking','off') == 'on'){
                    global $wpdb;

                    $query = "  SELECT ".$wpdb->prefix."woocommerce_order_items.*,".$wpdb->prefix."woocommerce_order_itemmeta.meta_value,st_meta1.meta_value FROM ".$wpdb->prefix."woocommerce_order_items
                                INNER JOIN ".$wpdb->prefix."woocommerce_order_itemmeta  ON ".$wpdb->prefix."woocommerce_order_itemmeta.order_item_id = ".$wpdb->prefix."woocommerce_order_items.order_item_id and ".$wpdb->prefix."woocommerce_order_itemmeta.meta_key='_st_st_booking_id'
                                INNER JOIN ".$wpdb->prefix."woocommerce_order_itemmeta as st_meta1  ON st_meta1.order_item_id = ".$wpdb->prefix."woocommerce_order_items.order_item_id and st_meta1.meta_key='_st_number_book'
                                WHERE 1=1
                                AND ".$wpdb->prefix."woocommerce_order_itemmeta.meta_value = ".get_the_ID();
                    $rs = $wpdb->get_results($query,OBJECT);
                    if(!empty($rs)){
                        foreach($rs as $k=>$v){
                            $count  = $count + $v->meta_value;
                        }
                    }
                }else{
                    $query = array(
                        'post_type'=>'st_order',
                        'post_per_page'=>'-1',
                        'meta_query'=>array(
                            array(
                                'key'=>'item_id',
                                'value'=>$post_id,
                                'compare'=>"="
                            )
                        ),
                    );
                    $type_tour = get_post_meta($post_id , 'type_tour' , true);
                    if($type_tour == 'daily_tour') {
                        $query['date_query'] =  array(
                            array(
                                'after'     => date("Y-m-d") ,
                                'before'    => date("Y-m-d") ,
                                'inclusive' => true ,
                            ) ,
                        ) ;
                    }

                    $query = new WP_Query( $query );
                    while($query->have_posts()){
                        $query->the_post();

                        $count = $count +  get_post_meta(get_the_ID() , 'adult_number' , true);
                        $count = $count +  get_post_meta(get_the_ID() , 'child_number' , true);

                    }
                    wp_reset_postdata();
                }

                return $count;
            }
            function tours_add_to_cart()
            {
                if(STInput::request('action') == 'tours_add_to_cart')
                {
                    if(self::do_add_to_cart()){
                        $link=STCart::get_cart_link();
                        wp_safe_redirect($link);
                        die;
                    }

                }

            }
            /**
            * from 1.1.7 fix price child adult by person booking
            */
            function filter_price_by_person($price_old, $number ,  $key = 1 ){

                $discount_by_adult = (get_post_meta(STInput::request('item_id') , 'discount_by_adult' , true));
                $discount_by_child = (get_post_meta(STInput::request('item_id') , 'discount_by_child' , true));

                if ($key == 1 and is_array($discount_by_adult) and !empty($discount_by_adult)){
                    foreach ($discount_by_adult as $key => $value) {
                        if ($number>=$value['key'])
                        {
                            $flag_return =  (1-$value['value']/100)*$price_old ;

                        }
                        if (!$flag_return){
                            $flag_return = $price_old;
                        }
                    }
                    return $flag_return ;
                }
                if ($key == 2 and is_array($discount_by_child) and !empty($discount_by_child)){

                    foreach ($discount_by_child as $key => $value) {
                        if ($number>=$value['key'])
                        {
                            $flag_return =  (1-$value['value']/100)*$price_old ;
                        }
                        if (!$flag_return){
                            $flag_return = $price_old;
                        }
                    }
                    return $flag_return;
                }
                return $price_old ;
            }
            function do_add_to_cart(){

                $pass_validate = true;
                
                $item_id       = STInput::request('item_id','');
                if($item_id <= 0 || get_post_type($item_id) != 'st_tours'){
                    STTemplate::set_message( __( 'This tour is not available..' , ST_TEXTDOMAIN ) , 'danger' );
                    $pass_validate = false;
                    return false;
                }
                
                $number        = 1;
                
                $adult_number = intval(STInput::request('adult_number',1));
                $child_number = intval(STInput::request('child_number',0));
                $infant_number = intval(STInput::request('infant_number',0));


                $data['adult_number'] = $adult_number;
                $data['child_number'] = $child_number;
                $data['infant_number'] = $infant_number;

                $max_number = intval(get_post_meta($item_id, 'max_people', true));


                $type_tour  = get_post_meta($item_id, 'type_tour', true);

                $data['type_tour']    = $type_tour;
                
                $today = date('Y-m-d');
                $check_in = STInput::request('check_in', '');
                $check_out = STInput::request('check_out', '');

                if(!$check_in || !$check_out){
                    STTemplate::set_message(__( 'Select a tour in the calendar above.' , ST_TEXTDOMAIN ) , 'danger' );
                    $pass_validate = FALSE;
                    return false;
                }

                $compare = TravelHelper::dateCompare($today, $check_in);
                if($compare < 0){
                    STTemplate::set_message( __( 'This tour has expired' , ST_TEXTDOMAIN ) , 'danger' );
                    $pass_validate = false;
                    return false;
                }

                $booking_period = intval(get_post_meta($item_id, 'tours_booking_period', true));
                $period = TravelHelper::dateDiff($today, $check_in);
                if($period < $booking_period){
                    STTemplate::set_message(sprintf(__('This tour allow minimum booking is %d day(s)', ST_TEXTDOMAIN), $booking_period), 'danger');
                    $pass_validate = false;
                    return false;
                }
                if($adult_number + $child_number + $infant_number > $max_number){
                    STTemplate::set_message( sprintf(__( 'Max of people for this tour is %d people' , ST_TEXTDOMAIN ), $max_number) , 'danger' );
                    $pass_validate = FALSE;
                    return false;
                }
                $tour_available = TourHelper::checkAvailableTour($item_id, strtotime($check_in), strtotime($check_out));
                if(!$tour_available){
                    STTemplate::set_message(__('The check in, check out day is not invalid or this tour not available.', ST_TEXTDOMAIN), 'danger');
                    $pass_validate = FALSE;
                    return false;
                }
                $free_people = intval(get_post_meta($item_id, 'max_people', true));
                $result = TourHelper::_get_free_peple($item_id, strtotime($check_in), strtotime($check_out));

                if(is_array($result) && count($result)){
                    $free_people = intval($result['free_people']);
                }
                if($free_people < ($adult_number + $child_number + $infant_number)){
                    STTemplate::set_message(sprintf(__('This tour only vacant %d people', ST_TEXTDOMAIN), $free_people), 'danger');
                    $pass_validate = FALSE;
                    return false;
                }

                $data_price = STPrice::getPriceByPeopleTour($item_id, strtotime($check_in), strtotime($check_out),$adult_number, $child_number, $infant_number);
                $total_price = $data_price['total_price'];
                $sale_price = STPrice::getSaleTourSalePrice($item_id, $total_price, $type_tour, strtotime($check_in));
                $data['check_in'] = date('m/d/Y',strtotime($check_in));
                $data['check_out'] = date('m/d/Y',strtotime($check_out));

                $people_price = STPrice::getPeoplePrice($item_id, strtotime($check_in), strtotime($check_out));
                
                $data = wp_parse_args($data, $people_price);
                $data['ori_price'] = $sale_price;

                $data['currency']     = TravelHelper::get_current_currency('symbol');
                $data['currency_rate'] = TravelHelper::get_current_currency('rate');
                $data['currency_pos'] = TravelHelper::get_current_currency('booking_currency_pos');
                $data['commission'] = TravelHelper::get_commission();
                $data['data_price'] = $data_price;
                $data['discount_rate']  = STPrice::get_discount_rate($item_id, strtotime($check_in)); 
                
                if($pass_validate) {
                    $data['duration'] = ($type_tour == 'daily_tour') ? floatval(get_post_meta($item_id, 'duration_day', true)) : '';
                    if ($pass_validate) {
                        STCart::add_cart($item_id, $number, $sale_price, $data);
                    }
                }
                return $pass_validate;
            }
            function get_cart_item_html($item_id = false){
                return st()->load_template('tours/cart_item_html',null,array('item_id'=>$item_id));
            }

            function custom_tour_layout($old_layout_id)
            {
                if(is_singular('st_tours'))
                {
                    $meta=get_post_meta(get_the_ID(),'st_custom_layout',true);

                    if($meta)
                    {
                        return $meta;
                    }
                }
                return $old_layout_id;
            }

            function get_search_fields()
            {
                $fields=st()->get_option('activity_tour_search_fields');
                return $fields;
            }

            static function get_info_price($post_id=null){

                if(!$post_id) $post_id=get_the_ID();
                $price=get_post_meta($post_id,'price',true);
                $new_price=0;

                $discount=get_post_meta($post_id,'discount',true);
                $is_sale_schedule=get_post_meta($post_id,'is_sale_schedule',true);

                if($is_sale_schedule=='on')
                {
                    $sale_from=get_post_meta($post_id,'sale_price_from',true);
                    $sale_to=get_post_meta($post_id,'sale_price_to',true);
                    if($sale_from and $sale_from){

                        $today=date('Y-m-d');
                        $sale_from = date('Y-m-d', strtotime($sale_from));
                        $sale_to = date('Y-m-d', strtotime($sale_to));
                        if (($today >= $sale_from) && ($today <= $sale_to))
                        {

                        }else{

                            $discount=0;
                        }

                    }else{
                        $discount=0;
                    }
                }
                if($discount){
                    if($discount>100) $discount=100;

                    $new_price=$price-($price/100)*$discount;
                    $data = array(
                        'price'=>apply_filters('st_apply_tax_amount',$new_price),
                        'price_old'=>apply_filters('st_apply_tax_amount',$price),
                        'discount'=>$discount,

                    );
                }else{
                    $new_price=$price;
                    $data = array(
                        'price'=>apply_filters('st_apply_tax_amount',$new_price),
                        'discount'=>$discount,
                    );
                }

                return $data;
            }

            static function get_price_person($post_id=null)
            {
                if(!$post_id) $post_id=get_the_ID();
                $adult_price=get_post_meta($post_id,'adult_price',true);
                $child_price=get_post_meta($post_id,'child_price',true);
                $infant_price=get_post_meta($post_id,'infant_price',true);

                $adult_price = apply_filters('st_apply_tax_amount',$adult_price);
                $child_price = apply_filters('st_apply_tax_amount',$child_price);

                $discount=get_post_meta($post_id,'discount',true);
                $is_sale_schedule=get_post_meta($post_id,'is_sale_schedule',true);

                if($is_sale_schedule=='on')
                {
                    $sale_from=get_post_meta($post_id,'sale_price_from',true);
                    $sale_to=get_post_meta($post_id,'sale_price_to',true);
                    if($sale_from and $sale_from){

                        $today=date('Y-m-d');
                        $sale_from = date('Y-m-d', strtotime($sale_from));
                        $sale_to = date('Y-m-d', strtotime($sale_to));
                        if (($today >= $sale_from) && ($today <= $sale_to))
                        {

                        }else{

                            $discount=0;
                        }

                    }else{
                        $discount=0;
                    }
                }

                if($discount){
                    if($discount>100) $discount=100;

                    $adult_price_new=$adult_price-($adult_price/100)*$discount;
                    $child_price_new=$child_price-($child_price/100)*$discount;
                    $infant_price_new=$infant_price-($infant_price/100)*$discount;
                    $data = array(
                        'adult'=>$adult_price,
                        'adult_new'=>$adult_price_new,
                        'child'=>$child_price,
                        'child_new'=>$child_price_new,
                        'infant'=>$infant_price,
                        'infant_new'=>$infant_price_new,
                        'discount'=>$discount,

                    );
                }else{
                    $data = array(
                        'adult_new'=>$adult_price,
                        'adult'    =>$adult_price,
                        'child'     =>$child_price,
                        'child_new'=>$child_price,
                        'infant'     =>$infant_price,
                        'infant_new'=>$infant_price,
                        'discount'=>$discount,
                    );
                }

                return $data;
            }

            static function get_price_html($post_id = false,$get = false,$st_mid='',$class='')
            {
                if(!$post_id) $post_id=get_the_ID();

                $html='';

                $show_price_free=st()->get_option('show_price_free','on');

                $prices = self::get_price_person($post_id);
                $adult_html = '';
                $adult_new_html = '<span class="text-lg lh1em  ">'.TravelHelper::format_money($prices['adult_new']).'</span>';

                // Check on sale
                if(isset($prices['adult']) and $prices['adult'] and $prices['discount'])
                {
                    if($show_price_free=='on' or $adult_new_html) {

                        $adult_html='<span class="text-small lh1em  onsale">'.TravelHelper::format_money($prices['adult']).'</span>&nbsp;&nbsp;';

                        $html.=sprintf(__('Adult: %s %s',ST_TEXTDOMAIN),$adult_html,$adult_new_html);
                    }

                }elseif(!empty($prices['adult_new'])){
                    if($show_price_free=='on' or $adult_new_html) {
                        $html.=sprintf(__('Adult: %s',ST_TEXTDOMAIN),$adult_new_html);
                    }
                }

                $child_new_html='<span class="text-lg lh1em  ">'.TravelHelper::format_money($prices['child_new']).'</span>';

                /*// Price for child
                if($prices['child_new'])
                {
                    $html.=' '.$st_mid.' ';

                    // Check on sale
                    if(isset($prices['child']) and $prices['child'] and $prices['discount'])
                    {
                        if($show_price_free=='on' or $child_new_html) {

                            $child_html = '<span class="text-small lh1em  onsale">' . TravelHelper::format_money($prices['child']) . '</span>&nbsp;&nbsp;';

                            $html .= sprintf(__('Children: %s %s', ST_TEXTDOMAIN), $child_html, $child_new_html);
                        }
                    }else{
                        if($show_price_free=='on' or $child_new_html) {
                            $html.=sprintf(__('Children: %s',ST_TEXTDOMAIN),$child_new_html);
                        }
                    }

                }
                $infant_html = '';
                $infant_new_html='<span class="text-lg lh1em  ">'.TravelHelper::format_money($prices['infant_new']).'</span>';
                // Price for infant
                
                if($prices['infant_new'])
                {
                    $html.=' '.$st_mid.' ';

                    // Check on sale
                    if(isset($prices['infant']) and $prices['infant'] and $prices['discount'])
                    {
                        if($show_price_free=='on' or $infant_new_html) {

                            $infant_html = '<span class="text-small lh1em  onsale">' . TravelHelper::format_money($prices['infant']) . '</span>&nbsp;&nbsp;';

                            $html .= sprintf(__('Infant: %s %s', ST_TEXTDOMAIN), $infant_html, $infant_new_html);
                        }
                    }else{
                        if($show_price_free=='on' or $infant_new_html) {
                            $html.=sprintf(__('Infant: %s',ST_TEXTDOMAIN),$infant_new_html);
                        }
                    }

                }*/

                return apply_filters('st_get_tour_price_html',$html);
            }
            static function get_array_discount_by_person_num($item_id = false){
                /* @since 1.1.1 */
                $return = array();

                $discount_by_adult = get_post_meta($item_id, 'discount_by_adult' , true) ;
                $discount_by_child = get_post_meta($item_id, 'discount_by_child' , true) ;

                if (!$discount_by_adult and !$discount_by_child) { return false; }
                if (is_array($discount_by_adult) and !empty($discount_by_adult)){
                    foreach ($discount_by_adult as $row) {
                        $key = (int)$row['key']  ;
                        $value = (int)$row['value']/100;
                        $return['adult'][$key]= $value;
                    }
                }else
                {
                    $return['adult'] = array();
                }
                if (is_array($discount_by_child) and !empty($discount_by_child)){
                    foreach ($discount_by_child as $row) {
                        $key = (int)$row['key']  ;
                        $value = (int)$row['value']/100;
                        $return['child'][$key]= $value;
                    }
                }else {
                    $return['child'] = array();
                }

                return $return ;
            }
            static function get_cart_item_total($item_id,$item)
            {
                $count_sale=0;
                $price_sale = $item['price'];

                if(!empty($item['data']['discount'])){
                    $count_sale = $item['data']['discount'];
                    $price_sale = $item['data']['price_sale'] * $item['number'];
                }

                $adult_number=$item['data']['adult_number'];
                $child_number=$item['data']['child_number'];
                $adult_price=$item['data']['adult_price'];
                $child_price=$item['data']['child_price'];

                if ($get_array_discount_by_person_num = self::get_array_discount_by_person_num($item_id)){
                    if ($array_adult = $get_array_discount_by_person_num['adult']){
                        if (is_array($array_adult) and  !empty($array_adult)){
                            foreach ($array_adult as $key => $value) {
                                if ($adult_number>=(int)$key ){
                                    $adult_price2 = $adult_price*$value;
                                }
                            }
                            if (!empty($adult_price2)){
                                $adult_price -=$adult_price2;
                            }

                        }
                    };
                    if ($array_child = $get_array_discount_by_person_num['child']){
                        if (is_array($array_child) and  !empty($array_child)){
                            foreach ($array_child as $key => $value) {
                                if ($child_number>=(int)$key ){
                                    $child_price2 = $child_price*$value;
                                }
                            }
                            if (!empty($child_price2)){
                                $child_price -=$child_price2;
                            }

                        }
                    };
                }

                $adult_price = round($adult_price);
                $child_price = round($child_price);
                $total_price=$adult_number*st_get_discount_value($adult_price,$count_sale,false);
                $total_price+=$child_number*st_get_discount_value($child_price,$count_sale,false);

                return $total_price;

            }


            function get_near_by($post_id=false,$range=20, $limit = 5)
            {
                $this->post_type='st_tours';
                //$limit = st()->get_option('tours_similar_tour',5);

                return parent::get_near_by($post_id,$range, $limit);

            }

            static function get_owner_email($item_id)
            {
                return get_post_meta($item_id,'contact_email',true);
            }

            public static function tour_external_booking_submit(){
                /*
                 * since 1.1.1
                 * filter hook tour_external_booking_submit
                */
                $post_id = get_the_ID();
                if (STInput::request('post_id')) {$post_id = STInput::request('post_id') ; }

                $tour_external_booking = get_post_meta($post_id, 'st_tour_external_booking' , "off");
                $tour_external_booking_link = get_post_meta($post_id , 'st_tour_external_booking_link' ,true) ;
                if ($tour_external_booking =="on" and $tour_external_booking_link!==""){
                    if (get_post_meta($post_id , 'st_tour_external_booking_link' , true)){
                        ob_start();
                        ?>
                            <a class='btn btn-primary' href='<?php echo get_post_meta($post_id , 'st_tour_external_booking_link' , true) ?>'> <?php st_the_language('book_now')  ?></a>
                        <?php
                    $return  =  ob_get_clean();
                    }
                }
                    else
                {
                    $return  =  TravelerObject::get_book_btn();
                }
                return apply_filters('tour_external_booking_submit' , $return ) ;
            }

            /* @since 1.1.3 */
            static function get_taxonomy_and_id_term_tour()
            {
                $list_taxonomy = st_list_taxonomy( 'st_tours' );
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
            * from 1.1.7
            */
            static function get_duration_unit($post_id = null ){
                // day , hours
                if (!$post_id){
                    $post_id = get_the_ID() ;
                }
                $duration = get_post_meta($post_id  , 'duration_day' , true) ;
                $duration_unit  = get_post_meta($post_id,'duration_unit' , true );

                if (!$duration_unit){$duration = 'day' ; }

                $html = "";
                $html_unit = "" ;

                if (!is_int($duration)) {$html =  $duration ; }

                if ($duration >1 ){
                    if ($duration_unit == 'day'){
                        $html_unit =  __('days' , ST_TEXTDOMAIN) ;
                    }
                    if ($duration_unit == 'hour'){
                        $html_unit =  __('hours' , ST_TEXTDOMAIN) ;
                    }
                    if ($duration_unit == 'week'){
                        $html_unit =  __('weeks' , ST_TEXTDOMAIN) ;
                    }
                    if ($duration_unit == 'month'){
                        $html_unit =  __('months' , ST_TEXTDOMAIN) ;
                    }
                }
                if ($duration == 1) {
                    if ($duration_unit == 'day'){
                        $html_unit =  __('day' , ST_TEXTDOMAIN) ;
                    }
                    if ($duration_unit == 'hour'){
                        $html_unit =  __('hour' , ST_TEXTDOMAIN) ;
                    }
                    if ($duration_unit == 'week'){
                        $html_unit =  __('week' , ST_TEXTDOMAIN) ;
                    }
                    if ($duration_unit == 'month'){
                        $html_unit =  __('month' , ST_TEXTDOMAIN) ;
                    }
                }

                return $html ." ".$html_unit;
            }
            
        }
        st()->tour=new STTour();
        st()->tour->init();
    }
