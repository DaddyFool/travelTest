<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Class STRental
 *
 * Created by ShineTheme
 *
 */
if(!class_exists( 'STRental' )) {
    class STRental extends TravelerObject
    {
        protected $post_type = 'st_rental';
        /**
         * @var string
         * @since 1.1.7
         */
        protected $template_folder='rental';

        function __construct()
        {
            $this->orderby = array(
                'ID'         => array(
                    'key'  => 'ID' ,
                    'name' => __( 'Date' , ST_TEXTDOMAIN )
                ) ,
                'price_asc'  => array(
                    'key'  => 'price_asc' ,
                    'name' => __( 'Price (low to high)' , ST_TEXTDOMAIN )
                ) ,
                'price_desc' => array(
                    'key'  => 'price_desc' ,
                    'name' => __( 'Price (hight to low)' , ST_TEXTDOMAIN )
                ) ,
                'name_asc'   => array(
                    'key'  => 'name_asc' ,
                    'name' => __( 'Name (A-Z)' , ST_TEXTDOMAIN )
                ) ,
                'name_desc'  => array(
                    'key'  => 'name_desc' ,
                    'name' => __( 'Name (Z-A)' , ST_TEXTDOMAIN )
                ) ,
            );
        }

        /**
         * @return array
         */
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
            return  "fa fa-home";
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


            //Filter change layout of rental detail if choose in metabox
            add_filter( 'rental_single_layout' , array( $this , 'custom_rental_layout' ) );

            add_filter( 'template_include' , array( $this , 'choose_search_template' ) );

            //add Widget Area
            add_action( 'widgets_init' , array( $this , 'add_sidebar' ) );

            //Sidebar Pos for SEARCH
            add_filter( 'st_rental_sidebar' , array( $this , 'change_sidebar' ) );

            //Filter the search hotel

            //add_action('pre_get_posts',array($this,'change_search_arg'));

            add_action( 'save_post' , array( $this , 'update_sale_price' ) );

            add_action( 'wp_loaded' , array( $this , 'add_to_cart' ) , 20 );

            add_filter( 'st_search_preload_page' , array( $this , '_change_preload_search_title' ) );

            add_action( 'wp_enqueue_scripts' , array( $this , '_add_script' ) );


            //Save Rental Review Stats
            add_action( 'comment_post' , array( $this , 'save_review_stats' ) );

            //        Change rental review arg
            add_filter( 'st_rental_wp_review_form_args' , array( $this , 'comment_args' ) , 10 , 2 );

            // Woocommerce cart item information
            add_action( 'st_wc_cart_item_information_st_rental' , array( $this , '_show_wc_cart_item_information' ) );
            add_action( 'st_wc_cart_item_information_btn_st_rental' , array( $this , '_show_wc_cart_item_information_btn' ) );
            add_action( 'st_before_cart_item_st_rental' , array( $this , '_show_wc_cart_post_type_icon' ) );


            add_filter( 'st_add_to_cart_item_st_rental' , array( $this , '_deposit_calculator' ) , 10 , 2 );

            // add_filter('st_data_custom_price',array($this,'_st_data_custom_price'));

        }


        function _st_data_custom_price()
        {
            return array( 'title' => 'Price Custom Settings' , 'post_type' => 'st_rental' );
        }

        /**
         *
         *
         *
         * @since 1.1.1
         * */

        function _show_wc_cart_item_information( $st_booking_data = array() )
        {
            echo st()->load_template( 'rental/wc_cart_item_information' , false , array( 'st_booking_data' => $st_booking_data ) );
        }
        /**
         *
         *
         *
         * @since 1.1.1
         * */

        function _show_wc_cart_post_type_icon()
        {
            echo '<span class="booking-item-wishlist-title"><i class="fa fa-home"></i> ' . __( 'rental' , ST_TEXTDOMAIN ) . ' <span></span></span>';
        }

        function comment_args($comment_form,$post_id=false)
        {
            if(!$post_id)
                $post_id = get_the_ID();
            if(get_post_type( $post_id ) == 'st_rental') {
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

        function save_review_stats( $comment_id )
        {

            $comemntObj = get_comment( $comment_id );
            $post_id    = $comemntObj->comment_post_ID;

            if(get_post_type( $post_id ) == 'st_rental') {
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


            }
            //Class hotel do the rest
        }

        function  _alter_search_query( $where )
        {
            global $wp_query;
            if(is_search()) {
                $post_type = $wp_query->query_vars[ 'post_type' ];

                if($post_type == 'st_rental') {
                    //Alter From NOW
                    global $wpdb;

                    $check_in  = TravelHelper::convertDateFormat( STInput::get( 'start' ) );
                    $check_out = TravelHelper::convertDateFormat( STInput::get( 'end' ) );


                    //Alter WHERE for check in and check out
                    if($check_in and $check_out) {
                        $check_in  = @date( 'Y-m-d H:i:s' , strtotime( $check_in ) );
                        $check_out = @date( 'Y-m-d H:i:s' , strtotime( $check_out ) );

                        $check_in  = esc_sql( $check_in );
                        $check_out = esc_sql( $check_out );


//                        $where .= " AND $wpdb->posts.ID NOT IN
//                            (
//                                SELECT booked_id FROM (
//                                    SELECT count(st_meta6.meta_value) as total_booked, st_meta5.meta_value as total,st_meta6.meta_value as booked_id ,st_meta2.meta_value as check_in,st_meta3.meta_value as check_out
//                                         FROM {$wpdb->posts}
//                                                JOIN {$wpdb->postmeta}  as st_meta2 on st_meta2.post_id={$wpdb->posts}.ID and st_meta2.meta_key='check_in'
//                                                JOIN {$wpdb->postmeta}  as st_meta3 on st_meta3.post_id={$wpdb->posts}.ID and st_meta3.meta_key='check_out'
//                                                JOIN {$wpdb->postmeta}  as st_meta6 on st_meta6.post_id={$wpdb->posts}.ID and st_meta6.meta_key='item_id'
//                                                JOIN {$wpdb->postmeta}  as st_meta5 on st_meta5.post_id=st_meta6.meta_value and st_meta5.meta_key='rental_number'
//                                                WHERE {$wpdb->posts}.post_type='st_order'
//                                        GROUP BY st_meta6.meta_value HAVING total<=total_booked AND (
//
//                                                    ( CAST(st_meta2.meta_value AS DATE)<'{$check_in}' AND  CAST(st_meta3.meta_value AS DATE)>'{$check_in}' )
//                                                    OR ( CAST(st_meta2.meta_value AS DATE)>='{$check_in}' AND  CAST(st_meta2.meta_value AS DATE)<='{$check_out}' )
//
//                                        )
//                                ) as item_booked
//                            )
//
//                    ";
                    }
                }
            }
            return $where;
        }


        function _add_script()
        {
            if(is_singular( 'st_rental' )) {
                wp_enqueue_script( 'single-rental' , get_template_directory_uri() . '/js/init/single-rental.js' );
            }
        }


        function _change_preload_search_title( $return )
        {
            if(get_query_var( 'post_type' ) == 'st_rental') {
                $return = __( " Rentals in %s" , ST_TEXTDOMAIN );

                if(STInput::get( 'location_id' )) {
                    $return = sprintf( $return , get_the_title( STInput::get( 'location_id' ) ) );
                } elseif(STInput::get( 'location_name' )) {
                    $return = sprintf( $return , STInput::get( 'location_name' ) );
                } elseif(STInput::get( 'address' )) {
                    $return = sprintf( $return , STInput::get( 'address' ) );
                } else {
                    $return = __( " Rentals" , ST_TEXTDOMAIN );
                }

                $return .= '...';
            }


            return $return;
        }

        function get_cart_item_html( $item_id )
        {
            return st()->load_template( 'rental/cart_item_html' , null , array( 'item_id' => $item_id ) );
        }

        /**
         * @since 1.1.0
         **/
        function add_to_cart()
        {
            if(STInput::request( 'action' ) == 'rental_add_cart') {
                if($this->do_add_to_cart()){
                    $link = STCart::get_cart_link();
                    wp_safe_redirect($link);
                    die;
                }
            }
        }

        function do_add_to_cart()
        {
            $form_validate = TRUE;
            
            $item_id = intval(STInput::request('item_id',''));
            if($item_id <=0 || get_post_type($item_id) != 'st_rental'){
                STTemplate::set_message(__('This rental is not available.', ST_TEXTDOMAIN), 'danger');
                $form_validate = FALSE;
                return false;
            }

            $check_in = STInput::request('start','');
            if(empty($check_in)){
                STTemplate::set_message(__('The check in field is not empty.', ST_TEXTDOMAIN), 'danger');
                $form_validate = FALSE;
                return false;
            }
            $check_in = TravelHelper::convertDateFormat($check_in);

            $check_out = STInput::request('end','');
            if(empty($check_out)){
                STTemplate::set_message(__('The check out field is not empty.', ST_TEXTDOMAIN), 'danger');
                $form_validate = FALSE;
                return false;
            }
            $check_out = TravelHelper::convertDateFormat($check_out);

            $today = date( 'm/d/Y' );

            $booking_period = get_post_meta( $item_id , 'rentals_booking_period' , true );
            if(empty($booking_period) || $booking_period <= 0) $booking_period = 0;
            
            $period = TravelHelper::dateDiff($today, $check_in);
            $compare = TravelHelper::dateCompare($today, $check_in);
            
            if($compare < 0){
                STTemplate::set_message( __( 'You can not set check-in date in the past' , ST_TEXTDOMAIN ) , 'danger' );
                $form_validate = FALSE;
                return false;
            }

            if($period < $booking_period) {
                STTemplate::set_message( sprintf( __( 'This rental allow minimum booking is %d day(s)' , ST_TEXTDOMAIN ) , $booking_period ) , 'danger' );
                $form_validate = FALSE;
                return false;
            }

            $adult_number = intval(STInput::request('adult_number',''));
            $child_number = intval(STInput::request('child_number',''));

            $max_adult = intval( get_post_meta( $item_id , 'rental_max_adult' , true ) );
            $max_children = intval( get_post_meta( $item_id , 'rental_max_children' , true ) );

            if($adult_number > $max_adult) {
                STTemplate::set_message( sprintf( __( 'A maximum number of adult(s): %d' , ST_TEXTDOMAIN ) , $max_adult ) , 'danger' );
                $form_validate = FALSE;
                return false;
            }

            if($child_number > $max_children) {
                STTemplate::set_message( sprintf( __( 'A maximum number of children: %d' , ST_TEXTDOMAIN ) , $max_children ) , 'danger' );
                $form_validate = FALSE;
                return false;
            }

            $number_room = intval(get_post_meta($item_id, 'rental_number', true));

            $check_in_tmp = date('Y-m-d', strtotime($check_in));
            $check_out_tmp = date('Y-m-d', strtotime($check_out));

            if(!RentalHelper::check_day_cant_order($item_id, $check_in_tmp, $check_out_tmp, 1)){
                STTemplate::set_message(sprintf(__('This rental is not available from %s to %s.', ST_TEXTDOMAIN), $check_in_tmp, $check_out_tmp), 'danger');
                $pass_validate = FALSE;
                return false;
            }

            if(!RentalHelper::_check_room_available($item_id, $check_in_tmp, $check_out_tmp, 1)){
                STTemplate::set_message(__('This rental is not available.', ST_TEXTDOMAIN), 'danger');
                $pass_validate = FALSE;
                return false;
            }
            
            $item_price = STPrice::getRentalPriceOnlyCustomPrice($item_id, strtotime($check_in), strtotime($check_out));
            $extras = STInput::request('extra_price', array());
            $numberday = TravelHelper::dateDiff($check_in, $check_out);
            $extra_price = STPrice::getExtraPrice($extras, 1, $numberday);
            $price_sale = STPrice::getSalePrice($item_id,$item_price, strtotime($check_in), strtotime($check_out));
            $discount_rate = STPrice::get_discount_rate($item_id, strtotime($check_in));
            $data = array(
                'item_price'   => $item_price,
                'ori_price'    => $price_sale + $extra_price,
                'check_in'     => $check_in,
                'check_out'    => $check_out,
                'adult_number' => $adult_number,
                'child_number' => $child_number,
                'extras' => $extras,
                'extra_price' => $extra_price,
                'commission' => TravelHelper::get_commission(),
                'discount_rate' => $discount_rate
            );
            if($form_validate)
                $form_validate = apply_filters( 'st_rental_add_cart_validate' , $form_validate );

            if($form_validate) {
                STCart::add_cart( $item_id , 1 , $item_price , $data );
            }
            return $form_validate;

        }

        function _add_cart_check_available( $post_id = false , $data = array() )
        {
            if(!$post_id or get_post_status( $post_id ) != 'publish') {
                STTemplate::set_message( __( 'Rental doese not exists' , ST_TEXTDOMAIN ) , 'danger' );
                return false;
            }


            $validator = new STValidate();

            $validator->set_rules( 'start' , __( 'Check in' , ST_TEXTDOMAIN ) , 'required' );
            $validator->set_rules( 'end' , __( 'Check out' , ST_TEXTDOMAIN ) , 'required' );

            if(!$validator->run()) {
                STTemplate::set_message( $validator->error_string() , 'danger' );
                return false;
            }

            $check_in  = date( 'Y-m-d H:i:s' , strtotime( STInput::post( 'start' ) ) );
            $check_out = date( 'Y-m-d H:i:s' , strtotime( STInput::post( 'end' ) ) );


            return true;

        }


        function update_sale_price( $post_id )
        {
            if(get_post_type( $post_id ) == $this->post_type) {
                $price = STRental::get_price( $post_id );
                update_post_meta( $post_id , 'sale_price' , $price );
            }
        }

        function get_search_fields()
        {
            $fields = st()->get_option( 'rental_search_fields' );

            return $fields;
        }

        function get_search_adv_fields()
        {
            $fields = st()->get_option( 'rental_search_advance' );

            return $fields;
        }

        /**
         *
         *
         * @update 1.1.1
         * */
        static function get_search_fields_name()
        {
            return array(
                'location'      => array(
                    'value' => 'location' ,
                    'label' => __( 'Location' , ST_TEXTDOMAIN )
                ) ,
                'list_location' => array(
                    'value' => 'list_location' ,
                    'label' => __( 'Location List' , ST_TEXTDOMAIN )
                ) ,/*
                'address'       => array(
                    'value' => 'address' ,
                    'label' => __( 'Address (geobytes.com)' , ST_TEXTDOMAIN )
                ) ,*/
                'checkin'       => array(
                    'value' => 'checkin' ,
                    'label' => __( 'Check in' , ST_TEXTDOMAIN )
                ) ,
                'checkout'      => array(
                    'value' => 'checkout' ,
                    'label' => __( 'Check out' , ST_TEXTDOMAIN )
                ) ,
                'room_num'  => array(
                    'value' => 'room_num', 
                    'label' => __('Room(s)' , ST_TEXTDOMAIN)
                    ),
                'adult'         => array(
                    'value' => 'adult' ,
                    'label' => __( 'Adult' , ST_TEXTDOMAIN )
                ) ,
                'children'      => array(
                    'value' => 'children' ,
                    'label' => __( 'Children' , ST_TEXTDOMAIN )
                ) ,
                'taxonomy'      => array(
                    'value' => 'taxonomy' ,
                    'label' => __( 'Taxonomy' , ST_TEXTDOMAIN )
                ) ,
                'item_name'     => array(
                    'value' => 'item_name' ,
                    'label' => __( 'Rental Name' , ST_TEXTDOMAIN )
                ),
                'list_name'     => array(
                    'value' => 'list_name' ,
                    'label' => __( 'List Name' , ST_TEXTDOMAIN )
                ),
                'price_slider'=>array(
                    'value'=>'price_slider',
                    'label'=>__('Price slider ',ST_TEXTDOMAIN)
                )

            );
        }

        function _get_join_query($join)
        {
            if(!TravelHelper::checkTableDuplicate('st_rental')) return $join;
            
            global $wpdb;

            $table = $wpdb->prefix.'st_rental';

            $join .= " INNER JOIN {$table} as tb ON {$wpdb->prefix}posts.ID = tb.post_id";

            return $join;
        }
        /**
        * @update 1.1.8
        *
        */
        function _get_where_query_tab_location($where){
            $location_id = get_the_ID();
            if(!TravelHelper::checkTableDuplicate('st_rental')) return $where;
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
                    $where_tmp .= " OR tb.location_id IN ({$list})";
                    $where .= $where_tmp.")";
                }else{
                    $where .= " AND (tb.multi_location LIKE '%_{$location_id}_%' OR tb.location_id IN ('{$location_id}')) ";
                }
            return $where;            
        }
        function _get_where_query($where)
        {
            if(!TravelHelper::checkTableDuplicate('st_rental')) return $where;

            global $wpdb;

            if(
                ( $location_id = STInput::get('location_id') and STInput::get('location_id'))
                    or (get_post_type(get_the_ID()) == 'location')
                    )
                {
                if (!$location_id ){$location_id = (get_the_ID()) ; }

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
                    $where_tmp .= " OR tb.location_id IN ({$list})";
                    $where .= $where_tmp.")";
                }else{
                    $where .= " AND (tb.multi_location LIKE '%_{$location_id}_%' OR tb.location_id IN ('{$location_id}')) ";
                }

            }elseif($location_name = STInput::request( 'location_name' )){
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
                if(!empty( $ids_location )) {
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

                    $where_tmp .= " OR (tb.location_id IN ({$ids_location})";
                    $where .= $where_tmp.")";
                    $where .= " OR (tb.address LIKE '%{$location_name}%'";
                    $where .= " OR {$wpdb->prefix}posts.post_title LIKE '%{$location_name}%')))";

                }else if(!empty($_REQUEST['search_all'])){
                    $where .= " AND (tb.address LIKE '%{$location_name}%'";
                    $where .= " OR {$wpdb->prefix}posts.post_title LIKE '%{$location_name}%')";
                }
            }elseif($address = STInput::request( 'address' , '')){
                $value = STInput::request( 'address' );
                $value = explode( "," , $value );
                if(!empty( $value[ 0 ] ) and !empty( $value[ 2 ] )) {
                    $where .= " AND (tb.address LIKE '%{$value[0]}%' OR tb.address LIKE '%{$value[2]}%')";
                }else{
                    $where .= " AND (tb.address LIKE '%{$address}%')";
                }
            }

            if(isset($_GET['start']) && isset($_GET['end']) and !empty($_GET['start']) and !empty($_GET['end'])){
                $check_in = date('Y-m-d', strtotime(TravelHelper::convertDateFormat($_GET['start'])));
                $check_out = date('Y-m-d', strtotime(TravelHelper::convertDateFormat($_GET['end'])));

                $today = date('m/d/Y');

                $period = TravelHelper::dateDiff($today, $check_in);

                $adult_number = STInput::get('adult_number', 0);
                if(intval($adult_number) < 0) $adult_number = 0;

                $children_number = STInput::get('children_num', 0);
                if(intval($children_number) < 0) $children_number = 0;

                $list_rental = RentalHelper::_rentalValidate($check_in, $check_out, $adult_number, $children_number, 1);
                
                if(!is_array($list_rental) || count($list_rental) <= 0){
                    $list_rental = "''";
                }else{
                    $list_rental = implode(',', $list_rental);
                }
                $where .= " AND {$wpdb->posts}.ID NOT IN ({$list_rental}) AND CAST(tb.rentals_booking_period AS UNSIGNED) <= {$period}";

            }
            if($star = STInput::get( 'star_rate' )) {
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

            if($price = STInput::get( 'price_range' )) {
                $priceobj      = explode( ';' , $price );

                $priceobj[0]=TravelHelper::convert_money_to_default($priceobj[0]);
                $where .= " AND (tb.sale_price >= {$priceobj[0]})";
                if(isset( $priceobj[ 1 ] )) {

                    $priceobj[1]=TravelHelper::convert_money_to_default($priceobj[1]);
                    $where .= " AND (tb.sale_price <= {$priceobj[1]})";
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

            if(isset($_REQUEST['item_id']) and  !empty($_REQUEST['item_id'])){
                    $item_id = STInput::request('item_id', '');
                    $where .= " AND ({$wpdb->prefix}posts.ID = '{$item_id}')";
                } 
            if (!empty($_REQUEST['room_num_search'])){

                $room_num_search =$_REQUEST['room_num_search'];
                $list_not_in = RentalHelper::get_list_not_in($room_num_search);
                $where .= " AND {$wpdb->prefix}posts.ID NOT IN ({$list_not_in})";
            }
            return $where;

        }
        function alter_search_query(){
            add_action('pre_get_posts',array($this,'change_search_arg'));
            add_filter('posts_where', array($this, '_get_where_query'));
            add_filter('posts_join', array($this, '_get_join_query'));
            add_filter('posts_orderby', array($this , '_get_order_by_query'));
        }

        function remove_alter_search_query()
        {
            remove_action('pre_get_posts',array($this,'change_search_arg'));
            remove_filter('posts_where', array($this, '_get_where_query'));
            remove_filter('posts_join', array($this, '_get_join_query'));
            remove_filter('posts_orderby', array($this , '_get_order_by_query'));
        }
        /**
         * Add query meta max adult, children
         * @since 1.1.0
         **/
        function change_search_arg( $query )
        {
            if (is_admin() and empty( $_REQUEST['is_search_map'] )) return $query;

            $post_type = get_query_var( 'post_type' );

            $meta_query = array();

            if($query->is_search && $post_type == 'st_rental') {

                add_filter( 'posts_where' , array( $this , '_alter_search_query' ) );
                add_filter('posts_where', array($this, '_get_where_query'));
                add_filter('posts_join', array($this, '_get_join_query'));


                if(STInput::get('item_name'))
                {
                    $query->set('s',STInput::get('item_name'));
                }

                $tax = STInput::get( 'taxonomy' );

                if(!empty( $tax ) and is_array( $tax )) {
                    $tax_query = array();
                    foreach( $tax as $key => $value ) {
                        if($value) {
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

                    $query->set( 'tax_query' , $tax_query );
                }

                $is_featured = st()->get_option( 'is_featured_search_rental' , 'off' );
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
                            $query->set( 'order' , 'asc' );


                            break;
                        case "price_desc":
                            $query->set( 'meta_key' , 'sale_price' );
                            $query->set( 'orderby' , 'meta_value_num' );
                            $query->set( 'order' , 'desc' );

                            break;
                        case "avg_rate":
                            $query->set( 'meta_key' , 'rate_review' );
                            $query->set( 'orderby' , 'meta_value' );
                            $query->set( 'order' , 'desc' );

                            break;
                        case "name_asc":
                            $query->set( 'orderby' , 'title' );
                            $query->set( 'order' , 'asc' );

                            break;
                        case "name_desc":
                            $query->set( 'orderby' , 'title' );
                            $query->set( 'order' , 'desc' );

                            break;
                    }
                }

                if(!empty( $meta_query )) {
                    $query->set( 'meta_query' , $meta_query );
                }
            } else {
                remove_filter( 'posts_where' , array( $this , '_alter_search_query' ) );
                remove_filter('posts_where', array($this, '_get_where_query'));
                //remove_filter('posts_join', array($this, '_get_join_query'));
            }

        }

        function change_sidebar()
        {
            return st()->get_option( 'rental_sidebar_pos' , 'left' );
        }

        function choose_search_template( $template )
        {
            global $wp_query;
            $post_type = get_query_var( 'post_type' );
            if($wp_query->is_search && $post_type == 'st_rental') {
                return locate_template( 'search-rental.php' );  //  redirect to archive-search.php
            }
            return $template;
        }

        function add_sidebar()
        {
            register_sidebar( array(
                'name'          => __( 'Rental Search Sidebar 1' , ST_TEXTDOMAIN ) ,
                'id'            => 'rental-sidebar' ,
                'description'   => __( 'Widgets in this area will be shown on Rental' , ST_TEXTDOMAIN ) ,
                'before_title'  => '<h4>' ,
                'after_title'   => '</h4>' ,
                'before_widget' => '<div id="%1$s" class="sidebar-widget %2$s">' ,
                'after_widget'  => '</div>' ,
            ) );

            register_sidebar( array(
                'name'          => __( 'Rental Search Sidebar 2' , ST_TEXTDOMAIN ) ,
                'id'            => 'rental-sidebar-2' ,
                'description'   => __( 'Widgets in this area will be shown on Rental' , ST_TEXTDOMAIN ) ,
                'before_title'  => '<h4>' ,
                'after_title'   => '</h4>' ,
                'before_widget' => '<div id="%1$s" class="sidebar-widget %2$s">' ,
                'after_widget'  => '</div>' ,
            ) );


        }


        function  get_result_string()
        {
            global $wp_query, $st_search_query;
            if ($st_search_query) {
                $query = $st_search_query;
            } else $query = $wp_query;
            $result_string = '';
            if($wp_query->found_posts) {
                if($wp_query->found_posts>1){
                    $result_string.=sprintf(__('%s vacation rentals ',ST_TEXTDOMAIN),$wp_query->found_posts);
                }else{
                    $result_string.=sprintf(__('%s vacation rental ',ST_TEXTDOMAIN),$wp_query->found_posts);
                }
            } else {
                $result_string = __( 'No rental found' , ST_TEXTDOMAIN );
            }

            $location_id = STInput::get( 'location_id' );

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


        function custom_rental_layout( $old_layout_id = false )
        {
            if(is_singular( 'st_rental' )) {
                $meta = get_post_meta( get_the_ID() , 'custom_layout' , true );

                if($meta) {
                    return $meta;
                }
            }
            return $old_layout_id;
        }

        function get_near_by( $post_id = false , $range = 20 , $limit = 5 )
        {
            $this->post_type = 'st_rental';
            return parent::get_near_by( $post_id , $range , $limit );
        }

        function get_review_stats()
        {
            $review_stat = st()->get_option( 'rental_review_stats' );

            return $review_stat;
        }

        function get_custom_fields()
        {
            return st()->get_option( 'rental_custom_fields' , array() );
        }


        static function get_price( $post_id = false )
        {
            if(!$post_id)
                $post_id = get_the_ID();

            $price     = get_post_meta( $post_id , 'price' , true );
            $price     = apply_filters( 'st_apply_tax_amount' , $price );
            $new_price = 0;

            $discount         = get_post_meta( $post_id , 'discount_rate' , true );
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
            } else {
                $new_price = $price;
            }

            return $new_price;

        }

        static function get_orgin_price( $post_id = false )
        {
            if(!$post_id)
                $post_id = get_the_ID();

            $price = get_post_meta( $post_id , 'price' , true );


            return $price = apply_filters( 'st_apply_tax_amount' , $price );


        }

        static function is_sale( $post_id = false )
        {
            if(!$post_id)
                $post_id = get_the_ID();
            $discount         = get_post_meta( $post_id , 'discount_rate' , true );
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
                return true;
            }
            return false;
        }

        function change_post_class( $class )
        {
            if(self::is_sale()) {
                $class[ ] = 'is_sale';
            }

            return $class;
        }


        static function get_owner_email( $item_id )
        {
            return get_post_meta( $item_id , 'agent_email' , true );
        }

        /**
         *
         *
         * @since 1.0.9
         * */
        function is_available()
        {
            return true;
        }

        public static function rental_external_booking_submit()
        {
            /*
             * since 1.1.1 
             * filter hook rental_external_booking_submit
            */
            $post_id = get_the_ID();
            if(STInput::request( 'post_id' )) {
                $post_id = STInput::request( 'post_id' );
            }

            $rental_external_booking      = get_post_meta( $post_id , 'st_rental_external_booking' , "off" );
            $rental_external_booking_link = get_post_meta( $post_id , 'st_rental_external_booking_link' , true );
            
            if($rental_external_booking == "on" && $rental_external_booking_link !== "") {
                if(get_post_meta( $post_id , 'st_rental_external_booking_link' , true )) {
                    ob_start();
                    ?>
    <a class='btn btn-primary' href='<?php echo get_post_meta( $post_id , 'st_rental_external_booking_link' , true ) ?>'>
        <?php st_the_language( 'rental_book_now' ) ?>
    </a>
    <?php
                    $return = ob_get_clean();
                }
            } else {
                $return = TravelerObject::get_book_btn();
            }
            return apply_filters( 'rental_external_booking_submit' , $return );
        }

        static function listTaxonomy(){
            $terms = get_object_taxonomies('rental_room', 'objects');
            if(!is_wp_error($terms ) and !empty($terms))
            foreach($terms as $key => $val){
                $listTaxonomy[$val->labels->name]= $key;
            }
            return $listTaxonomy;
        }
        /** from 1.1.7*/
        static function get_taxonomy_and_id_term_tour(){
            $list_taxonomy = st_list_taxonomy( 'st_rental' );
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

    $rental = new STRental();
    $rental->init();
}
