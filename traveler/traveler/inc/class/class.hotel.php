<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Class STHotel
 *
 * Created by ShineTheme
 *
 */
if (!class_exists('STHotel')) {
    class STHotel extends TravelerObject
    {
        //Current Hotel ID
        private $hotel_id;

        protected $orderby;

        protected $post_type = 'st_hotel';
        
        protected $template_folder='hotel';

        function __construct($hotel_id = FALSE)
        {
            $this->hotel_id = $hotel_id;
            $this->orderby = array(
                'ID'         => array(
                    'key'  => 'ID',
                    'name' => __('Date', ST_TEXTDOMAIN)
                ),
                'price_asc'  => array(
                    'key'  => 'price_asc',
                    'name' => __('Price (low to high)', ST_TEXTDOMAIN)
                ),
                'price_desc' => array(
                    'key'  => 'price_desc',
                    'name' => __('Price (high to low)', ST_TEXTDOMAIN)
                ),
                'name_asc'   => array(
                    'key'  => 'name_asc',
                    'name' => __('Name (A-Z)', ST_TEXTDOMAIN)
                ),
                'name_desc'  => array(
                    'key'  => 'name_desc',
                    'name' => __('Name (Z-A)', ST_TEXTDOMAIN)
                ),

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
         *
         *
         * @update 1.1.3
         * */
        function init()
        {
            if(!$this->is_available()) return;

            parent::init();


            add_action('template_redirect', array($this, 'ajax_search_room'), 1);

            //Filter the search hotel
            //add_action('pre_get_posts',array($this,'change_search_hotel_arg'));


            //custom search hotel template
            add_filter('template_include', array($this, 'choose_search_template'));


            //Sidebar Pos for SEARCH
            add_filter('st_hotel_sidebar', array($this, 'change_sidebar'));

            //add Widget Area
            add_action('widgets_init', array($this, 'add_sidebar'));

            //Create Hotel Booking Link
            add_action('wp_loaded', array($this, 'hotel_add_to_cart'),20);

            // Change hotel review arg
            add_filter('st_hotel_wp_review_form_args', array($this, 'comment_args'), 10, 2);

            //Save Hotel Review Stats
            add_action('comment_post', array($this, 'save_review_stats'));

            //Reduce total stats of posts after comment_delete
            add_action('delete_comment', array($this, 'save_post_review_stats'));


            //Filter change layout of hotel detail if choose in metabox
            add_filter('st_hotel_detail_layout', array($this, 'custom_hotel_layout'));

            add_action('wp_enqueue_scripts', array($this, 'add_localize'));

            add_action('wp_ajax_ajax_search_room', array($this, 'ajax_search_room'));
            add_action('wp_ajax_nopriv_ajax_search_room', array($this, 'ajax_search_room'));



            add_filter('st_real_comment_post_id', array($this, '_change_comment_post_id'));

            add_filter('st_search_preload_page', array($this, '_change_preload_search_title'));

            //add_action('st_after_checkout_fields', array($this, '_add_room_number_field'));

            add_filter('st_checkout_form_validate', array($this, '_check_booking_period'));

            add_filter('st_st_hotel_search_result_link', array($this, '_change_search_result_link'), 10, 2);

            // Woocommerce cart item information
            add_action('st_wc_cart_item_information_st_hotel', array($this, '_show_wc_cart_item_information'));
            add_action('st_wc_cart_item_information_btn_st_hotel', array($this, '_show_wc_cart_item_information_btn'));

            add_action('st_before_cart_item_st_hotel', array($this, '_show_wc_cart_post_type_icon'));

            //add_filter('st_add_to_cart_item_st_hotel', array($this, '_deposit_calculator'), 10, 2);



        }


        /**
         *
         *
         * @since 1.1.1
         * */
        function _deposit_calculator($cart_data,$item_id){
            $room_id = isset($cart_data['data']['room_id']) ? $cart_data['data']['room_id'] : false;
            if($room_id){
                $cart_data = parent::_deposit_calculator($cart_data, $room_id);
            }
            return $cart_data;
        }

        /**
         *
         *
         * @since 1.1.1
         * */
        function _show_wc_cart_post_type_icon()
        {
            echo '<span class="booking-item-wishlist-title"><i class="fa fa-building-o"></i> ' . __('hotel', ST_TEXTDOMAIN) . ' <span></span></span>';
        }

        /**
         *
         * Show cart item information for hotel booking
         *
         * @since 1.1.1
         * */

        function _show_wc_cart_item_information($st_booking_data = array())
        {
            echo st()->load_template('hotel/wc_cart_item_information', FALSE, array('st_booking_data' => $st_booking_data));
        }

        function _add_room_number_field($post_type = FALSE)
        {

            if ($post_type == 'hotel_room') {
                echo st()->load_template('hotel/checkout_fields', NULL, array('key' => get_the_ID()));

                return;
            }

        }

        function _is_hotel_booking()
        {
            $items = STCart::get_items();
            if (!empty($items)) {
                foreach ($items as $key => $value) {
                    if (get_post_type($key) == 'st_hotel') return TRUE;
                }
            }
        }


        /**
         *
         *
         *
         * @since 1.0.9
         *
         * */
        function _check_booking_period($validate)
        {

            $cart = STCart::get_items();

            $hotel_id = '';

            $today = strtotime(date('m/d/Y'));

            $check_in = $today;

            foreach ($cart as $key => $val) {

                $hotel_id = $key;

                $check_in = strtotime($val['data']['check_in']);
            }

            $booking_period = intval(get_post_meta($hotel_id, 'hotel_booking_period', TRUE));

            $period = STDate::date_diff($today, $check_in);

            if ($booking_period && $period < $booking_period) {
                STTemplate::set_message(sprintf(__('This hotel allow minimum booking is %d day(s)', ST_TEXTDOMAIN), $booking_period), 'danger');
                $validate = FALSE;
            }

            return $validate;

        }

        function _add_validate_fields($validate)
        {
            $items = STCart::get_items();

            if (!empty($items)) {
                foreach ($items as $key => $value) {
                    if (get_post_type($key) == 'st_hotel') {

                        // validate

                        $default = array(
                            'number' => 1
                        );

                        $value = wp_parse_args($value, $default);

                        $room_num = $value['number'];

                        $room_data = STInput::request('room_data', array());

                        if ($room_num > 1) {

                            if (!is_array($room_data) or empty($room_data)) {
                                STTemplate::set_message(__('Room infomation is required', ST_TEXTDOMAIN), 'danger');
                                $validate = FALSE;
                            } else {

                                for ($k = 1; $k <= $room_num; $k++) {
                                    $valid = TRUE;
                                    if (!isset($room_data[ $k ]['adult_number']) or !$room_data[ $k ]['adult_number']) {
                                        STTemplate::set_message(__('Adult number in room is required!', ST_TEXTDOMAIN), 'danger');
                                        $valid = FALSE;
                                    }
                                    if (!isset($room_data[ $k ]['host_name']) or !$room_data[ $k ]['host_name']) {
                                        STTemplate::set_message(__('Room Host Name is required!', ST_TEXTDOMAIN), 'danger');
                                        $valid = FALSE;
                                    }

                                    if (isset($room_data[ $k ]['child_number'])) {
                                        $child_number = (int)$room_data[ $k ]['child_number'];

                                        if ($child_number > 0) {
                                            if (!isset($room_data[ $k ]['age_of_children']) or !is_array($room_data[ $k ]['age_of_children']) or empty($room_data[ $k ]['age_of_children'])) {
                                                STTemplate::set_message(__('Ages of Children is required!', ST_TEXTDOMAIN), 'danger');
                                                $valid = FALSE;
                                            } else {
                                                foreach ($room_data[ $k ]['age_of_children'] as $k2 => $v2) {
                                                    if (!$v2) {
                                                        STTemplate::set_message(__('Ages of Children is required!', ST_TEXTDOMAIN), 'danger');
                                                        $valid = FALSE;
                                                        break;
                                                    }
                                                }
                                            }
                                        }
                                    }

                                    if (!$valid) {
                                        $validate = FALSE;
                                        break;
                                    }


                                }
                            }

                        }


                    }
                }
            }

            return $validate;
        }

        function _change_preload_search_title($return)
        {

            if (get_query_var('post_type') == 'st_hotel') {
                $return = __(" Hotels in %s", ST_TEXTDOMAIN);

                if (STInput::get('location_id')) {
                    $return = sprintf($return, get_the_title(STInput::get('location_id')));
                } elseif (STInput::get('location_name')) {
                    $return = sprintf($return, STInput::get('location_name'));
                } elseif (STInput::get('address')) {
                    $return = sprintf($return, STInput::get('address'));
                } else {
                    $return = __(" Hotels", ST_TEXTDOMAIN);
                }


                $return .= '...';
            }

            return $return;
        }

        function _change_comment_post_id($id_item)
        {


            return $id_item;
        }



        function add_localize()
        {
            wp_localize_script('jquery', 'st_hotel_localize', array(
                'booking_required_adult'          => __('Please select adult number', ST_TEXTDOMAIN),
                'booking_required_children'       => __('Please select children number', ST_TEXTDOMAIN),
                'booking_required_adult_children' => __('Please select Adult and  Children number', ST_TEXTDOMAIN),
                'room'                            => __('Room', ST_TEXTDOMAIN),
                'is_aoc_fail'                     => __('Please select the ages of children', ST_TEXTDOMAIN),
                'is_not_select_date'              => __('Please select Check-in and Check-out date', ST_TEXTDOMAIN),
                'is_not_select_check_in_date'     => __('Please select Check-in date', ST_TEXTDOMAIN),
                'is_not_select_check_out_date'    => __('Please select Check-out date', ST_TEXTDOMAIN),
                'is_host_name_fail'               => __('Please provide Host Name(s)', ST_TEXTDOMAIN)
            ));
        }


        /**
         *
         *
         *
         *
         * @update 1.1.1
         * */
        static function get_search_fields_name()
        {
            return array(
                'location'      => array(
                    'value' => 'location',
                    'label' => __('Location', ST_TEXTDOMAIN)
                ),
                'list_location' => array(
                    'value' => 'list_location',
                    'label' => __('Location List', ST_TEXTDOMAIN)
                ),
                'checkin'       => array(
                    'value' => 'checkin',
                    'label' => __('Check in', ST_TEXTDOMAIN)
                ),
                'checkout'      => array(
                    'value' => 'checkout',
                    'label' => __('Check out', ST_TEXTDOMAIN)
                ),
                'adult'         => array(
                    'value' => 'adult',
                    'label' => __('Adult', ST_TEXTDOMAIN)
                ),
                'children'      => array(
                    'value' => 'children',
                    'label' => __('Children', ST_TEXTDOMAIN)
                ),  
                'taxonomy'      => array(
                    'value' => 'taxonomy',
                    'label' => __('Taxonomy', ST_TEXTDOMAIN)
                ),  
                'item_name'     => array(
                    'value' => 'item_name' ,
                    'label' => __( 'Hotel Name' , ST_TEXTDOMAIN )
                ),
                'list_name'     => array(
                    'value' => 'list_name' ,
                    'label' => __( 'List Name' , ST_TEXTDOMAIN )
                ),            
                'price_slider'      => array(
                    'value' => 'price_slider',
                    'label' => __('Price slider', ST_TEXTDOMAIN)
                ),
                'room_num'=>array(
                    'value'=>'room_num',
                    'label'=>__('Room(s)',ST_TEXTDOMAIN)
                ),
                'taxonomy_room'=>array(
                    'value'=>'taxonomy_room',
                    'label'=>__('Taxonomy Room',ST_TEXTDOMAIN)
                ),
            );
        }

        function count_offers($post_id = FALSE)
        {
            if (!$post_id) $post_id = $this->hotel_id;
            //Count Rooms
            global $wpdb;
            $query_count = $wpdb->get_results("
                select DISTINCT ID from {$wpdb->posts}
                join {$wpdb->postmeta} 
                on {$wpdb->postmeta} .post_id = {$wpdb->posts}.ID
                and {$wpdb->postmeta} .meta_key = 'room_parent' and {$wpdb->postmeta} .meta_value =  {$post_id}
                and {$wpdb->posts}.post_status = 'publish'
            ");

            return (count($query_count));

        }

        function get_search_fields()
        {
            $fields = st()->get_option('hotel_search_fields');

            return $fields;
        }

        function get_search_adv_fields()
        {
            $fields = st()->get_option('hotel_search_advance');

            return $fields;
        }

        function custom_hotel_layout($old_layout_id)
        {
            if (is_singular($this->post_type)) {
                $meta = get_post_meta(get_the_ID(), 'st_custom_layout', TRUE);

                if ($meta and get_post_type($meta)=='st_layouts') {
                    return $meta;
                }
            }

            return $old_layout_id;
        }


        function save_review_stats($comment_id)
        {
            $comemntObj = get_comment($comment_id);
            $post_id = $comemntObj->comment_post_ID;

            if (get_post_type($post_id) == 'st_hotel') {
                $all_stats = $this->get_review_stats();
                $st_review_stats = STInput::post('st_review_stats');

                if (!empty($all_stats) and is_array($all_stats)) {
                    $total_point = 0;
                    foreach ($all_stats as $key => $value) {
                        if (isset($st_review_stats[ $value['title'] ])) {
                            $total_point += $st_review_stats[ $value['title'] ];
                            //Now Update the Each Stat Value
                            update_comment_meta($comment_id, 'st_stat_' . sanitize_title($value['title']), $st_review_stats[ $value['title'] ]);
                        }
                    }

                    $avg = round($total_point / count($all_stats), 1);

                    //Update comment rate with avg point
                    $rate = wp_filter_nohtml_kses($avg);
                    if ($rate > 5) {
                        //Max rate is 5
                        $rate = 5;
                    }
                    update_comment_meta($comment_id, 'comment_rate', $rate);
                    //Now Update the Stats Value
                    update_comment_meta($comment_id, 'st_review_stats', $st_review_stats);
                }


            }


            if (STInput::post('comment_rate')) {
                update_comment_meta($comment_id, 'comment_rate', STInput::post('comment_rate'));

            }
            //review_stats
            $avg = STReview::get_avg_rate($post_id);

            update_post_meta($post_id, 'rate_review', $avg);
        }

        function save_post_review_stats($comment_id)
        {
            $comemntObj = get_comment($comment_id);
            $post_id = $comemntObj->comment_post_ID;

            $avg = STReview::get_avg_rate($post_id);

            update_post_meta($post_id, 'rate_review', $avg);
        }


        function get_review_stats()
        {
            $review_stat = st()->get_option('hotel_review_stats');

            return $review_stat;
        }

        function get_review_stats_metabox()
        {
            $review_stat = st()->get_option('hotel_review_stats');

            $result = array();

            if (!empty($review_stat)) {
                foreach ($review_stat as $key => $value) {
                    $result[] = array(
                        'label' => $value['title'],
                        'value' => sanitize_title($value['title'])
                    );
                }

            }

            return $result;
        }

        function comment_args($comment_form, $post_id = FALSE)
        {

            if (!$post_id) $post_id = get_the_ID();
            if (get_post_type($post_id) == 'st_hotel') {
                $stats = $this->get_review_stats();

                if ($stats and is_array($stats)) {
                    $stat_html = '<ul class="list booking-item-raiting-summary-list stats-list-select">';

                    foreach ($stats as $key => $value) {
                        $stat_html .= '<li class=""><div class="booking-item-raiting-list-title">' . $value['title'] . '</div>
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
                                                <input type="hidden" class="st_review_stats" value="0" name="st_review_stats[' . $value['title'] . ']">
                                                    </li>';
                    }
                    $stat_html .= '</ul>';


                    $comment_form['comment_field'] = "
                        <div class='row'>
                            <div class=\"col-sm-8\">
                    ";
                    $comment_form['comment_field'] .= '<div class="form-group">
                                            <label>' . __('Review Title', ST_TEXTDOMAIN) . '</label>
                                            <input class="form-control" type="text" name="comment_title">
                                        </div>';

                    $comment_form['comment_field'] .= '<div class="form-group">
                                            <label>' . __('Review Text',ST_TEXTDOMAIN) . '</label>
                                            <textarea name="comment" id="comment" class="form-control" rows="6"></textarea>
                                        </div>
                                        </div><!--End col-sm-8-->
                                        ';

                    $comment_form['comment_field'] .= '<div class="col-sm-4">' . $stat_html . '</div></div><!--End Row-->';
                }
            }

            return $comment_form;
        }

        function hotel_add_to_cart()
        {
            if (STInput::request('action') == 'hotel_add_to_cart') {

                if ($this->do_add_to_cart()){
                    $link = STCart::get_cart_link();
                    wp_safe_redirect($link);
                    die;
                }
            }

        }
        
        function do_add_to_cart(){
            $pass_validate = TRUE;

            $item_id = intval(STInput::request('item_id', ''));
            if($item_id <= 0 || get_post_type($item_id) != 'st_hotel'){
                STTemplate::set_message(__('This hotel is not available.', ST_TEXTDOMAIN), 'danger');
                $pass_validate = FALSE;
                return false;
            }


            $room_id = intval(STInput::request('room_id', ''));
            if($room_id <= 0 || get_post_type($room_id) != 'hotel_room'){
                STTemplate::set_message(__('This room is not available.', ST_TEXTDOMAIN), 'danger');
                $pass_validate = FALSE;
                return false;
            }

            $check_in = STInput::request('check_in', '');
            if(empty($check_in)){
                STTemplate::set_message(__('Date is invalid', ST_TEXTDOMAIN), 'danger');
                $pass_validate = FALSE;
                return false;
            }
            $check_in = TravelHelper::convertDateFormat($check_in);

            $check_out = STInput::request('check_out', '');
            if(empty($check_out)){
                STTemplate::set_message(__('Date is invalid', ST_TEXTDOMAIN), 'danger');
                $pass_validate = FALSE;
                return false;
            }
            $check_out = TravelHelper::convertDateFormat($check_out);
            $room_num_search = intval(STInput::request('room_num_search', ''));
            if($room_num_search <= 0) $room_num_search = 1;

            $adult_number = intval(STInput::request('adult_number', ''));
            if($adult_number <= 0) $adult_number = 1;

            $child_number = intval(STInput::request('child_number', ''));
            if($child_number <= 0) $child_number = 0;

            $checkin_ymd = date('Y-m-d', strtotime($check_in));
            $checkout_ymd = date('Y-m-d', strtotime($check_out));
            if(!HotelHelper::check_day_cant_order($room_id, $checkin_ymd, $checkout_ymd, $room_num_search)){
                STTemplate::set_message(sprintf(__('This room is not available from %s to %s.', ST_TEXTDOMAIN), $checkin_ymd, $checkout_ymd), 'danger');
                $pass_validate = FALSE;
                return false;
            }

            if(!HotelHelper::_check_room_available($room_id, $checkin_ymd, $checkout_ymd, $room_num_search)){
                STTemplate::set_message(__('This room is not available.', ST_TEXTDOMAIN), 'danger');
                $pass_validate = FALSE;
                return false;
            }

            if(strtotime($check_out) - strtotime($check_in) <= 0){
                STTemplate::set_message(__('The check-out is later than the check-in.', ST_TEXTDOMAIN), 'danger');
                $pass_validate = FALSE;
                return false;
            }

            $num_room = intval(get_post_meta($room_id, 'number_room', true));
            $adult = intval(get_post_meta($room_id, 'adult_number', true));
            $children = intval(get_post_meta($room_id, 'children_number', true));

            if($room_num_search > $num_room){
                STTemplate::set_message(__('Max of rooms are incorrect.', ST_TEXTDOMAIN), 'danger');
                $pass_validate = FALSE;
                return false;
            }
            if($adult_number > $adult){
                STTemplate::set_message(sprintf(__('Max of adults is %d people.', ST_TEXTDOMAIN), $adult), 'danger');
                $pass_validate = FALSE;
                return false;
            }
            if($child_number > $children){
                STTemplate::set_message(__('Number of children in the room are incorrect.', ST_TEXTDOMAIN), 'danger');
                $pass_validate = FALSE;
                return false;
            }
            $today = date('m/d/Y');

            $period = TravelHelper::dateDiff($today, $check_in);
            $booking_min_day = intval(get_post_meta($item_id , 'min_book_room' , true ) );
            $compare = TravelHelper::dateCompare($today, $check_in);
            
            $booking_period = get_post_meta($item_id,'hotel_booking_period', true);
            if(empty($booking_period) || $booking_period <=0) $booking_period = 0;            
            
            if($compare < 0){
                STTemplate::set_message( __( 'You can not set check-in date in the past' , ST_TEXTDOMAIN ) , 'danger' );
                $pass_validate = FALSE;
                return false;
            }
            if ($period < $booking_period) {
                STTemplate::set_message(sprintf(__('This hotel allow minimum booking is %d day(s)', ST_TEXTDOMAIN), $booking_period), 'danger');
                $pass_validate = FALSE;
                return false;
            }
            
            if ($booking_min_day and $booking_min_day > TravelHelper::dateDiff($check_in,$check_out)) {
                STTemplate::set_message( sprintf(__( 'Please booking at least %d day(s)' , ST_TEXTDOMAIN ), $booking_min_day) , 'danger' );
                $pass_validate = false;
                return false;
            }

            $item_price = floatval(get_post_meta($room_id, 'price', true));
            // Extra price added in the new version 1.1.9
            $extras = STInput::request('extra_price', array());
            $numberday = TravelHelper::dateDiff($check_in, $check_out);
            $extra_price = STPrice::getExtraPrice($extras, $room_num_search, $numberday);
            $sale_price = STPrice::getRoomPrice($room_id, strtotime($check_in), strtotime($check_out), $room_num_search);
            $discount_rate = STPrice::get_discount_rate($room_id, strtotime($check_in));
            $data = array(
                'item_price' => $item_price,
                'ori_price' => $sale_price + $extra_price,
                'check_in'        => $check_in,
                'check_out'       => $check_out,
                'room_num_search' => $room_num_search,
                'room_id'         => $room_id,
                'adult_number'    => $adult_number,
                'child_number'    => $child_number,
                'extras' => $extras,
                'extra_price' => $extra_price,
                'commission' => TravelHelper::get_commission(),
                'discount_rate' => $discount_rate
            );
            if($pass_validate) {
                $pass_validate = apply_filters('st_hotel_add_cart_validate', $pass_validate, $data);
            }

            if($pass_validate){
                STCart::add_cart($item_id, $room_num_search, $sale_price + $extra_price, $data);
            }
            return $pass_validate;

        }

        function is_booking_period($item_id = '', $t = '', $c = '')
        {

            $today = strtotime($t);

            $check_in = strtotime($c);

            $booking_period = intval(get_post_meta($item_id, 'hotel_booking_period', TRUE));

            $period = STDate::date_diff($today, $check_in);

            if ($period < $booking_period) {

                return $booking_period;
            }

            return FALSE;

        }


        function get_cart_item_html($item_id = FALSE)
        {
            return st()->load_template('hotel/cart_item_html', NULL, array('item_id' => $item_id));
        }

        function add_sidebar()
        {
            register_sidebar(array(
                'name'          => __('Hotel Search Sidebar 1', ST_TEXTDOMAIN),
                'id'            => 'hotel-sidebar',
                'description'   => __('Widgets in this area will be shown on Hotel', ST_TEXTDOMAIN),
                'before_title'  => '<h4>',
                'after_title'   => '</h4>',
                'before_widget' => '<div id="%1$s" class="sidebar-widget %2$s">',
                'after_widget'  => '</div>',
            ));

            register_sidebar(array(
                'name'          => __('Hotel Search Sidebar 2', ST_TEXTDOMAIN),
                'id'            => 'hotel-sidebar-2',
                'description'   => __('Widgets in this area will be shown on Hotel', ST_TEXTDOMAIN),
                'before_title'  => '<h4>',
                'after_title'   => '</h4>',
                'before_widget' => '<div id="%1$s" class="sidebar-widget %2$s">',
                'after_widget'  => '</div>',
            ));


        }

        /**
         *
         *
         * @since 1.0.1
         * @update 1.0.9
         * */
        function change_sidebar($sidebar = FALSE)
        {
            return st()->get_option('hotel_sidebar_pos', 'left');
        }

        function get_result_string()
        {
            global $wp_query, $st_search_query;
            if ($st_search_query) {
                $query = $st_search_query;
            } else $query = $wp_query;
            $result_string = $p1=$p2=$p3=$p4='';



            if ($query->found_posts) {
                if($query->found_posts>1){
                    $p1=sprintf(__('%s hotels',ST_TEXTDOMAIN),$query->found_posts);
                }else{
                    $p1=sprintf(__('%s hotel',ST_TEXTDOMAIN),$query->found_posts);
                }
            } else {
                $p1= __('No hotel found', ST_TEXTDOMAIN);
            }

            $location_id = STInput::get('location_id');
            if ($location_id and $location = get_post($location_id)) {
                $p2= sprintf(__('in %s', ST_TEXTDOMAIN), get_the_title($location_id));
            } elseif (STInput::request('location_name')) {
                $p2= sprintf(__('in %s', ST_TEXTDOMAIN), STInput::request('location_name'));
            } elseif (STInput::request('address')) {
                $p2= sprintf(__('in %s', ST_TEXTDOMAIN), STInput::request('address'));
            }

            $start = TravelHelper::convertDateFormat(STInput::get('start'));
            $end = TravelHelper::convertDateFormat(STInput::get('end'));

            $start = strtotime($start);

            $end = strtotime($end);

            if ($start and $end) {
                $p3= sprintf(__('on %s', ST_TEXTDOMAIN) , date_i18n('M d', $start) . ' - ' . date_i18n('M d', $end));
            }

            if ($adult_num = STInput::get('adult_num')) {
                if ($adult_num > 1) {
                    $p4= sprintf(__('for %s adults', ST_TEXTDOMAIN), $adult_num);
                } else {

                    $p4= sprintf(__('for %s adult', ST_TEXTDOMAIN), $adult_num);
                }

            }

            // check Right to left
            if(st()->get_option('right_to_left') == 'on')
            {

                return $p4.' '.$p3.' '.$p2.' '.$p1;
            }


            return esc_html($p1.' '.$p2.' '.$p3.' '.$p4);

        }


        function ajax_search_room()
        {
            if (st_is_ajax() and STInput::post('room_search')) {
                if (!wp_verify_nonce(STInput::post('room_search'), 'room_search')) {
                    $result = array(
                        'status' => 0,
                        'data'   => "",
                    );

                    echo json_encode($result);
                    die;
                }


                $result = array(
                    'status' => 1,
                    'data'   => "",
                );

                $hotel_id = get_the_ID();
                $post = STInput::request();
                $post['room_parent'] = $hotel_id;

                //Check Date
                $today = date('m/d/Y');

                $check_in = TravelHelper::convertDateFormat($post['start']);

                $check_out = TravelHelper::convertDateFormat($post['end']);

                $date_diff = TravelHelper::dateDiff($check_in, $check_out);

                $booking_period = intval(get_post_meta($hotel_id, 'hotel_booking_period', TRUE));

                $period = TravelHelper::dateDiff($today, $check_in);

                if ($booking_period && $period < $booking_period) {
                    $result = array(
                        'status'  => 0,
                        'data'    => st()->load_template('hotel/elements/loop-room-none'),
                        'message' => sprintf(__('This hotel allow minimum booking is %d day(s)', ST_TEXTDOMAIN), $booking_period)
                    );
                    echo json_encode($result);
                    die;
                }
                if ($date_diff < 1) {
                    $result = array(
                        'status'    => 0,
                        'data'      => "",
                        'message'   => __('Make sure your check-out date is at least 1 day after check-in.', ST_TEXTDOMAIN),
                        'more-data' => $date_diff
                    );

                    echo json_encode($result);
                    die;
                }
                add_filter('posts_where',array($this,'_alter_search_query_ajax'));

                $page = STInput::request('paged_room');
                if (!$page) {
                    $page = get_query_var('paged_room');
                }

                $arg = array(
                    'post_type'      => 'hotel_room',
                    'posts_per_page' => '10',
                    'paged'          => $page,
                    'meta_query' => array(
                        array(
                            'key'     => 'room_parent',
                            'value'   => $hotel_id,
                            'compare' => 'IN',
                        ),
                    )
                );
                query_posts($arg);

                remove_filter('posts_where',array($this,'_alter_search_query_ajax'));

                global $wp_query;

                if (have_posts()) {
                    while (have_posts()) {
                        the_post();

                       $result['data'] .= st()->load_template('hotel/elements/loop-room-item');

                    }

                } else {
                    $result['data'] .= st()->load_template('hotel/elements/loop-room-none');

                }
                $result['paging'] = TravelHelper::paging_room();

                wp_reset_query();

                echo json_encode($result);

                die();
            }
        }

        function get_search_arg($param)
        {
            $default = array(
                's' => FALSE
            );

            extract(wp_parse_args($param, $default));

            $arg = array();

            return $arg;

        }

        function choose_search_template($template)
        {
            global $wp_query;
            $post_type = get_query_var('post_type');
            if ($wp_query->is_search && $post_type == 'st_hotel') {
                return locate_template('search-hotel.php');  //  redirect to archive-search.php
            }

            return $template;
        }

        function  _alter_search_query($where)
        {
            if (is_admin()) return $where;
            global $wp_query;
            if (is_search()) {
                $post_type = $wp_query->query_vars['post_type'];

                if ($post_type == 'st_hotel' and is_search()) {
                    //Alter From NOW
                    global $wpdb;

                    $check_in = STInput::get('start');
                    $check_out = STInput::get('end');


                    //Alter WHERE for check in and check out
                    if ($check_in and $check_out) {
                        $check_in = @date('Y-m-d', strtotime(TravelHelper::convertDateFormat($check_in)));
                        $check_out = @date('Y-m-d', strtotime(TravelHelper::convertDateFormat($check_out)));

                        $check_in = esc_sql($check_in);
                        $check_out = esc_sql($check_out);

                        $where .= " AND $wpdb->posts.ID in ((SELECT {$wpdb->postmeta}.meta_value
                        FROM {$wpdb->postmeta}
                        WHERE {$wpdb->postmeta}.meta_key='room_parent'
                        AND  {$wpdb->postmeta}.post_id NOT IN(
                            SELECT room_id FROM (
                                SELECT count(st_meta6.meta_value) as total,
                                    st_meta5.meta_value as total_room,st_meta6.meta_value as room_id ,st_meta2.meta_value as check_in,st_meta3.meta_value as check_out
                                     FROM {$wpdb->posts}
                                            JOIN {$wpdb->postmeta}  as st_meta2 on st_meta2.post_id={$wpdb->posts}.ID and st_meta2.meta_key='check_in'
                                            JOIN {$wpdb->postmeta}  as st_meta3 on st_meta3.post_id={$wpdb->posts}.ID and st_meta3.meta_key='check_out'
                                            JOIN {$wpdb->postmeta}  as st_meta6 on st_meta6.post_id={$wpdb->posts}.ID and st_meta6.meta_key='room_id'
                                            JOIN {$wpdb->postmeta}  as st_meta5 on st_meta5.post_id=st_meta6.meta_value and st_meta5.meta_key='number_room'
                                            WHERE {$wpdb->posts}.post_type='st_order'
                                    GROUP BY st_meta6.meta_value HAVING total>=total_room AND (

                                                ( CAST(st_meta2.meta_value AS DATE)<'{$check_in}' AND  CAST(st_meta3.meta_value AS DATE)>'{$check_in}' )
                                                OR ( CAST(st_meta2.meta_value AS DATE)>='{$check_in}' AND  CAST(st_meta2.meta_value AS DATE)<='{$check_out}' )

                                    )
                            ) as room_booked
                        )
                    ))";


                    }


                    if ($price_range = STInput::request('price_range_')) { 
                        // price_range_ ???

                        $price_obj = explode(';', $price_range);

                        // convert to default money
                        $price_obj[0]=TravelHelper::convert_money_to_default($price_obj[0]);
                        $price_obj[1]=TravelHelper::convert_money_to_default($price_obj[1]);

                        if (!isset($price_obj[1])) {
                            $price_from = 0;
                            $price_to = $price_obj[0];
                        } else {
                            $price_from = $price_obj[0];
                            $price_to = $price_obj[1];
                        }

                        global $wpdb;

                        $query = " AND {$wpdb->posts}.ID IN (

                                SELECT ID FROM
                                (
                                    SELECT ID, MIN(min_price) as min_price_new FROM
                                    (
                                    select {$wpdb->posts}.ID,
                                    IF(
                                        st_meta3.meta_value is not NULL,
                                        IF((st_meta2.meta_value = 'on' and CAST(st_meta5.meta_value as DATE)<=NOW() and CAST(st_meta4.meta_value as DATE)>=NOW()) or
                                        st_meta2.meta_value='off'
                                        ,
                                        st_meta1.meta_value-(st_meta1.meta_value/100)*st_meta3.meta_value,
                                        CAST(st_meta1.meta_value as DECIMAL)
                                        ),
                                        CAST(st_meta1.meta_value as DECIMAL)
                                    ) as min_price

                                    from {$wpdb->posts}
                                    JOIN {$wpdb->postmeta} on {$wpdb->postmeta}.meta_value={$wpdb->posts}.ID and {$wpdb->postmeta}.meta_key='room_parent'
                                    JOIN {$wpdb->postmeta} as st_meta1 on st_meta1.post_id={$wpdb->postmeta}.post_id AND st_meta1.meta_key='price'
                                    LEFT JOIN {$wpdb->postmeta} as st_meta2 on st_meta2.post_id={$wpdb->postmeta}.post_id AND st_meta2.meta_key='is_sale_schedule'
                                    LEFT JOIN {$wpdb->postmeta} as st_meta3 on st_meta3.post_id={$wpdb->postmeta}.post_id AND st_meta3.meta_key='discount_rate'
                                    LEFT JOIN {$wpdb->postmeta} as st_meta4 on st_meta4.post_id={$wpdb->postmeta}.post_id AND st_meta4.meta_key='sale_price_to'
                                    LEFT JOIN {$wpdb->postmeta} as st_meta5 on st_meta5.post_id={$wpdb->postmeta}.post_id AND st_meta5.meta_key='sale_price_from'

                                     )as min_price_table
                                    group by ID Having  min_price_new>=%d and min_price_new<=%d ) as min_price_table_new
                                ) ";

                        $query = $wpdb->prepare($query, $price_from, $price_to);

                        $where .= $query;

                    }
                }
            }
            return $where;
        }
        function _get_join_query($join)
        {
            if(!TravelHelper::checkTableDuplicate('st_hotel')) return $join;
            global $wpdb;

            $table = $wpdb->prefix.'st_hotel';

            $join .= " INNER JOIN {$table} as tb ON {$wpdb->prefix}posts.ID = tb.post_id";
            return $join;
        }
        function _get_where_query($where)
        {
            if(!TravelHelper::checkTableDuplicate('st_hotel')) return $where;

            global $wpdb;

            $where .= "";

            if(isset($_REQUEST['location_id']) && !empty($_REQUEST['location_id'])){
                $location_id = STInput::request('location_id','');
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
            }else{
                if (!empty($_REQUEST['location_name'])) {
                    $location_name = STInput::request('location_name','');
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

                } elseif (isset($_REQUEST['address']) && !empty($_REQUEST['address'])) {
                    $address = STInput::request('address','');
                    $value = STInput::request('address');
                    $value = explode(",", $value);
                    if (!empty($value[0]) and !empty($value[2])) {
                        $where .= " AND ( tb.address LIKE '%{$value[0]}%' OR tb.address LIKE '%{$value[2]}%')";
                    } else {
                        $where .= " AND ( tb.address LIKE '%{$address}%')";
                    }
                }
                
            }

            if(isset($_GET['start']) && !empty($_GET['start']) && isset($_GET['end']) && !empty($_GET['end'])){
                $check_in = date('Y-m-d', strtotime(TravelHelper::convertDateFormat($_GET['start'])));
                $check_out = date('Y-m-d', strtotime(TravelHelper::convertDateFormat($_GET['end'])));
                
                $today = date('m/d/Y');

                $period = TravelHelper::dateDiff($today, $check_in);
                
                $adult_number = STInput::get('adult_number', 0);
                if(intval($adult_number) < 0) $adult_number = 0;

                $children_number = STInput::get('children_num', 0);
                if(intval($children_number) < 0) $children_number = 0;

                $number_room = STInput::get('room_num_search', 0);
                if(intval($number_room) < 0) $number_room = 0;

                $list_hotel = HotelHelper::_hotelValidate($check_in, $check_out, $adult_number, $children_number, $number_room);

                if(!is_array($list_hotel) || count($list_hotel) <= 0){
                    $list_hotel = "''";
                }else{
                    $list_hotel = implode(',', $list_hotel);
                }

                $where .= " AND {$wpdb->prefix}posts.ID NOT IN ({$list_hotel}) AND CAST(tb.hotel_booking_period AS UNSIGNED) <= {$period}";

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

            if (isset($_REQUEST['hotel_rate']) && !empty($_REQUEST['hotel_rate'])) {
                $hotel_rate = STInput::get('hotel_rate', '');
                $where .= " AND (tb.hotel_star IN ({$hotel_rate}))";
            }

            if (isset($_REQUEST['price_range']) && !empty($_REQUEST['price_range'])){
                $price = STInput::get('price_range', '0;0');
                $priceobj = explode(';', $price);

                // convert to default money
                $priceobj[0]=TravelHelper::convert_money_to_default($priceobj[0]);
                $priceobj[1]=TravelHelper::convert_money_to_default($priceobj[1]);

                $where .= " AND (tb.price_avg >= {$priceobj[0]})";

                if(isset($priceobj[1])){

                    $priceobj[1]=TravelHelper::convert_money_to_default($priceobj[1]);
                    $where .= " AND (tb.price_avg <= {$priceobj[1]})";
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
            if( !empty($_REQUEST['taxonomy_hotel_room'])) {
                $tax = STInput::request( 'taxonomy_hotel_room' );
                if(!empty( $tax ) and is_array( $tax )) {
                    $tax_query = array();
                    foreach( $tax as $key => $value ) {
                        if($value) {
                            $ids ="";
                            $ids_tmp = explode(',', $value);
                            if(!empty($ids_tmp)){
                                foreach($ids_tmp as $k=>$v){
                                    if(!empty($v)){
                                        $ids[]=$v;
                                    }
                                }
                            }
                            if(!empty( $ids )) {
                                $tax_query[ ] = array(
                                    'taxonomy' => $key ,
                                    'terms'    => $ids
                                );
                            }
                        }
                    }
                    $where_room = '';
                    if(!empty($tax_query)){
                        $where_room =' AND (';
                        foreach($tax_query as $k=>$v){
                            $ids = implode(',',$v['terms']);
                            if($k>0){
                                $where_room .= " AND ";
                            }
                            $where_room .= "  (
                                                    SELECT COUNT(1)
                                                    FROM wp_term_relationships
                                                    WHERE term_taxonomy_id IN ({$ids})
                                                    AND object_id = wp_posts.ID
                                                  ) = ".count($v['terms'])."  ";
                        }
                        $where_room .= " ) ";
                    }

                    $where .= " AND $wpdb->posts.ID IN
                        (
                           SELECT ID FROM
                           (
                              SELECT meta1.meta_value as ID
                                    FROM {$wpdb->prefix}posts

                                    INNER JOIN {$wpdb->prefix}postmeta as meta1 ON wp_posts.ID = meta1.post_id and meta1.meta_key='room_parent'
                                    WHERE 1=1
                                    {$where_room}
                                    AND {$wpdb->prefix}posts.post_type = 'hotel_room'
                                    GROUP BY meta1.meta_value
                           ) as ids
                        ) ";
                }
            }
            if(isset($_REQUEST['item_id']) and  !empty($_REQUEST['item_id'])){

                    $item_id = STInput::request('item_id', '');
                    $where .= " AND ({$wpdb->prefix}posts.ID = '{$item_id}')";
                } 
            
            return $where;
        }
        /**
        * @update 1.1.8
        */
        function _get_where_query_tab_location($where){
            $location_id = get_the_ID();
            if(!TravelHelper::checkTableDuplicate('st_hotel')) return $where;
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
        public function _alter_search_query_ajax($where){
            global $wpdb;
            $hotel_id = get_the_ID();

            $check_in = date('Y-m-d',strtotime(TravelHelper::convertDateFormat(STInput::request('start'))));

            $check_out = date('Y-m-d',strtotime(TravelHelper::convertDateFormat(STInput::request('end'))));

            $adult_num = STInput::request('adult_number', 0);
            $child_num = STInput::request('child_number', 0);
            $number_room = STInput::request('room_num_search', 0);

            $list = HotelHelper::_hotelValidateByID($hotel_id, strtotime($check_in), strtotime($check_out), $adult_num, $child_num, $number_room);
            if(!is_array($list) || count($list) <= 0){
                    $list = "''";
                }else{
                    $list = implode(',', $list);
                }
            $where .= " AND {$wpdb->prefix}posts.ID NOT IN ({$list})";
            return $where;
            
        }

        function alter_search_query(){
            add_action('pre_get_posts',array($this,'change_search_hotel_arg'));
            add_filter('posts_where', array($this, '_get_where_query'));
            add_filter('posts_join', array($this, '_get_join_query'));
            add_filter('posts_orderby', array($this , '_get_order_by_query'));
        }

        function remove_alter_search_query()
        {
            remove_action('pre_get_posts',array($this,'change_search_hotel_arg'));
            remove_filter('posts_where', array($this, '_get_where_query'));
            remove_filter('posts_join', array($this, '_get_join_query'));
            remove_filter('posts_orderby', array($this , '_get_order_by_query'));
        }
        function change_search_hotel_arg($query)
        {
            if (is_admin() and empty( $_REQUEST['is_search_map'] )) return $query;

            $post_type = get_query_var('post_type');
            $posts_per_page = st()->get_option('hotel_posts_per_page', 12);

            if ($query->is_search && $post_type == 'st_hotel') {
                add_filter('posts_join', array($this, '_get_join_query'));

                add_filter('posts_where', array($this, '_get_where_query'));

                if(STInput::get('item_name'))
                {
                    $query->set('s',STInput::get('item_name'));
                }

                if(empty($_REQUEST['s'])){
                    $query->set('posts_per_page', $posts_per_page);
                }

                $tax = STInput::request( 'taxonomy' );

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
                $is_featured = st()->get_option('is_featured_search_hotel', 'off');
                if (!empty($is_featured) and $is_featured == 'on') {
                    $query->set('meta_key', 'is_featured');
                    $query->set('orderby', 'meta_value');
                    $query->set('order', 'DESC');
                }

                if ($orderby = STInput::get('orderby')) {
                    $hotels = HotelHelper::_getAllHotelID();
                    if(count($hotels)){
                        foreach($hotels as $hotel){
                            $price = HotelHelper::get_minimum_price_hotel($hotel);
                            update_post_meta($hotel, 'st_price_now', $price);
                        }
                    }
                    switch ($orderby) {
                        case "price_asc":
                            $query->set('meta_key', 'st_price_now');
                            $query->set('orderby', 'meta_value_num');
                            $query->set('order', 'asc');

                            break;
                        case "price_desc":
                            $query->set('meta_key', 'st_price_now');
                            $query->set('orderby', 'meta_value_num');
                            $query->set('order', 'desc');

                            break;
                        case "avg_rate":
                            $query->set('meta_key', 'rate_review');
                            $query->set('orderby', 'meta_value_num');
                            $query->set('order', 'desc');
                            break;

                        case "name_asc":
                            $query->set('orderby', 'title');
                            $query->set('order', 'asc');

                            break;
                        case "name_desc":
                            $query->set('orderby', 'title');
                            $query->set('order', 'desc');

                            break;
                    }
                } else {
                    //Default Sorting

                    $query->set('orderby', 'modified');
                    $query->set('order', 'desc');
                }

                
                if (!empty($meta_query)) {
                    $query->set('meta_query', $meta_query);
                }
            } else {
                remove_filter('posts_where', array($this, '_get_where_query'));
            }


            return $query;
        }


        function search_room($param = array())
        {
            $default = array(
                'room_parent'     => FALSE,
                'adult_number'       => FALSE,
                'child_number'       => FALSE,
                'room_type'       => 0,
                'room_num_search' => 1,
                'room_num_config' => array()
            );

            $page = STInput::request('paged');
            if (!$page) {
                $page = get_query_var('paged');
            }

            extract(wp_parse_args($param, $default));

            $arg = array(
                'post_type'      => 'hotel_room',
                'posts_per_page' => '10',
                'paged'          => $page,
            );


            $max_adult = 1;
            $max_child = 0;
            if ($room_num_search == 1) {
                $max_adult = $adult_number;
                $max_child = $child_number;
            }

            if ($room_num_search) {
                $arg['meta_query'][] = array(
                    'key'     => 'number_room',
                    'compare' => '>=',
                    'value'   => $room_num_search,
                    'type'  => 'DECIMAL'
                );
            }

            if ($room_num_search > 1) {
                if (!empty($room_num_config) and is_array($room_num_config)) {

                    foreach ($room_num_config as $key => $value) {
                        if ($value['adults'] > $max_adult) {
                            $max_adult = $value['adults'];
                        }
                        if ($value['children'] > $max_child) {
                            $max_child = $value['children'];
                        }
                    }
                }
            }
            if (st_is_ajax()){ // for ajax
               $arg['meta_query'][] = array(

                'key'     => 'children_number',
                'compare' => '>=',
                'value'   => $child_number,
                'type'=>'NUMERIC'
                );
                $arg['meta_query'][] = array(

                    'key'     => 'adult_number',
                    'compare' => '>=',
                    'value'   => $adult_number,
                    'type'=>'NUMERIC'
                ); 
            }else{
                $arg['meta_query'][] = array(

                'key'     => 'children_number',
                'compare' => '>=',
                'value'   => $max_child, // can't understand
                'type'=>'NUMERIC'
                );
                $arg['meta_query'][] = array(

                    'key'     => 'adult_number',
                    'compare' => '>=',
                    'value'   => $max_adult, // can't understand
                    'type'=>'NUMERIC'
                ); 
            }
            


            if ($room_parent) {
                $arg['meta_key'] = 'room_parent';
                $arg['meta_value'] = $room_parent;
            }

            if ($room_type) {
                $arg['tax_query'][] = array(
                    'taxonomy' => 'room_type',
                    'terms'    => $room_type
                );
            }


            return $arg;
        }



        //Helper class
        function get_last_booking()
        {
            if ($this->hotel_id == FALSE) {
                $this->hotel_id = get_the_ID();
            }
            global $wpdb;


            $query = "SELECT * from " . $wpdb->postmeta . "
                where meta_key='item_id'
                and meta_value in (
                    SELECT ID from {$wpdb->posts}
                    join " . $wpdb->postmeta . " on " . $wpdb->posts . ".ID=" . $wpdb->postmeta . ".post_id and " . $wpdb->postmeta . ".meta_key='room_parent'
                    where post_type='hotel_room'
                    and " . $wpdb->postmeta . ".meta_value='" . $this->hotel_id . "'

                )

                order by meta_id
                limit 0,1";

            $data = $wpdb->get_results($query, OBJECT);

            if (!empty($data)) {
                foreach ($data as $key => $value) {
                    return human_time_diff(get_the_time('U', $value->post_id), current_time('timestamp')) . __(' ago', ST_TEXTDOMAIN);
                }
            }


        }

        static function count_meta_key($key, $value, $post_type = 'st_hotel', $location_key = 'multi_location')
        {

            $arg = array(
                'post_type'      => $post_type,
                'posts_per_page' => 1,


            );

            if (STInput::request('location_id')) {
                $arg['meta_query'][] = array(
                    'key'   => $location_key,
                    'value' => STInput::request('location_id')
                );
            }

            if ($key == 'rate_review') {

                $arg['meta_query'][] = array(
                    'key'     => $key,
                    'value'   => $value,
                    'type'    => 'DECIMAL',
                    'compare' => '>='
                );
            } else {
                $arg['meta_key'] = $key;
                $arg['meta_value'] = $value;
            }

            $query = new WP_Query(
                $arg
            );
            $count = $query->found_posts;
            wp_reset_query();

            return $count;
        }

        static function get_avg_price($post_id = FALSE)
        {
            if (!$post_id) {
                $post_id = get_the_ID();
            }
            $price = get_post_meta($post_id, 'price_avg', TRUE);
            $price = apply_filters('st_apply_tax_amount', $price);

            return $price;
        }

        /**
         * Get Hotel price for listing and single page
         *
         * @since 1.1.1
         * */
        static function get_price($hotel_id = FALSE)
        {
            if (!$hotel_id) $hotel_id = get_the_ID();
            if (self::is_show_min_price()) {
                $min_price = HotelHelper::get_minimum_price_hotel($hotel_id);
                $min_price = apply_filters('st_apply_tax_amount', $min_price);
                return $min_price;

            } else {
                return HotelHelper::get_avg_price_hotel($hotel_id);
            }

        }

        /**
         * Check if Traveler Setting show min price instead avg price
         *
         * @since 1.1.1
         * */
        static function is_show_min_price()
        {
            $show_min_or_avg = st()->get_option('hotel_show_min_price', 'avg_price');

            if ($show_min_or_avg == 'min_price') return TRUE;

            return FALSE;
        }

        /**
         *
         * Base on all room price
         *
         * @deprecate this function is no longer work
         *
         *
         * */
        static function get_min_price($post_id = FALSE)
        {
            if (!$post_id) {
                $post_id = get_the_ID();
            }
            $query = array(
                'post_type'      => 'hotel_room',
                'posts_per_page' => 100,
                'meta_key'       => 'room_parent',
                'meta_value'     => $post_id
            );

            $q = new WP_Query($query);

            $min_price = 0;
            $i = 1;
            while ($q->have_posts()) {
                $q->the_post();

                $price = get_post_meta(get_the_ID(), 'price', TRUE);

                if ($i == 1) {
                    $min_price = $price;
                } else {
                    if ($price < $min_price) {
                        $min_price = $price;
                    }
                }


                $i++;
            }


            wp_reset_postdata();

            return apply_filters('st_apply_tax_amount', $min_price);
        }

        function _change_search_result_link($url)
        {
            $page_id = st()->get_option('hotel_search_result_page');
            if ($page_id) {
                $url = get_permalink($page_id);
            }

            return $url;
        }

        static function get_min_max_price($post_type = 'st_hotel')
        {
            if (empty($post_type) || !TravelHelper::checkTableDuplicate($post_type)) {
                return array('price_min' => 0, 'price_max' => 500);
            }            

            global $wpdb;

            $sql = "
                select 
                    min(CAST(price_avg as DECIMAL)) as min,
                    max(CAST(price_avg as DECIMAL)) as max
                from {$wpdb->prefix}st_hotel";

            $results = $wpdb->get_results($sql, OBJECT);

            $price_min = $results[0]->min;
            $price_max = $results[0]->max;

            if (empty($price_min)) $price_min = 0;
            if (empty($price_max)) $price_max = 500;

            return array('min' => ceil($price_min), 'max' => ceil($price_max));
        }

        static function get_price_slider()
        {
            global $wpdb;
            $query = "SELECT min(orgin_price) as min_price,MAX(orgin_price) as max_price from
                (SELECT
                 IF( st_meta3.meta_value is not NULL,
                    IF((st_meta2.meta_value = 'on' and CAST(st_meta5.meta_value as DATE)<=NOW() and CAST(st_meta4.meta_value as DATE)>=NOW())
                      or st_meta2.meta_value='off' ,
                      {$wpdb->postmeta}.meta_value-({$wpdb->postmeta}.meta_value/100)*st_meta3.meta_value,
                      CAST({$wpdb->postmeta}.meta_value as DECIMAL) ),
                  CAST({$wpdb->postmeta}.meta_value as DECIMAL) ) as orgin_price
                  FROM {$wpdb->postmeta}
                  JOIN {$wpdb->postmeta} as st_meta1 on st_meta1.post_id={$wpdb->postmeta}.post_id
                  LEFT JOIN {$wpdb->postmeta} as st_meta2 on st_meta2.post_id={$wpdb->postmeta}.post_id AND st_meta2.meta_key='is_sale_schedule'
                  LEFT JOIN {$wpdb->postmeta} as st_meta3 on st_meta3.post_id={$wpdb->postmeta}.post_id AND st_meta3.meta_key='discount_rate'
                  LEFT JOIN {$wpdb->postmeta} as st_meta4 on st_meta4.post_id={$wpdb->postmeta}.post_id AND st_meta4.meta_key='sale_price_to'
                  LEFT JOIN {$wpdb->postmeta} as st_meta5 on st_meta5.post_id={$wpdb->postmeta}.post_id AND st_meta5.meta_key='sale_price_from'
                  WHERE st_meta1.meta_key='room_parent' AND {$wpdb->postmeta}.meta_key='price')
        as orgin_price_table";

            $data = $wpdb->get_row($query);

            $min = apply_filters('st_apply_tax_amount', $data->min_price);
            $max = apply_filters('st_apply_tax_amount', $data->max_price);

            return array('min' => floor($min), 'max' => ceil($max));
        }

        static function get_owner_email($hotel_id = FALSE)
        {
            return get_post_meta($hotel_id, 'email', TRUE);
        }

        /**
         * @since 1.1.0
         **/
        static function getStar($post_id = FALSE)
        {

            if (!$post_id) {

                $post_id = get_the_ID();
            }

            return intval(get_post_meta($post_id, 'hotel_star', TRUE));
        }

        static function listTaxonomy(){
            $terms = get_object_taxonomies('hotel_room', 'objects');
            $listTaxonomy = array();
            if(!is_wp_error($terms ) and !empty($terms))
            foreach($terms as $key => $val){
                $listTaxonomy[$val->labels->name]= $key;
            }
            return $listTaxonomy;
        }
        /** from 1.1.7*/
        static function get_taxonomy_and_id_term_tour(){
            $list_taxonomy = st_list_taxonomy( 'st_hotel' );
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

    $a = new STHotel();

    $a->init();
}
