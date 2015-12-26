<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Class STAdminHolidays
 *
 * Created by ShineTheme
 *
 */
$order_id = 0;
if(!class_exists('STAdminHolidays'))
{

    class STAdminHolidays extends STAdmin
    {

        static $booking_page;

        protected $post_type="st_holidays";

        /**
         *
         *
         * @update 1.1.3
         * */
        function __construct()
        {
            add_action('init',array($this,'_init_post_type'));
            if (!st_check_service_available($this->post_type)) return;

            add_action('init',array($this,'init_metabox'));

            /// add_action( 'save_post', array($this,'holidays_update_location') );
            add_action( 'save_post', array($this,'holidays_update_price_sale') );
            add_filter('manage_st_holidays_posts_columns', array($this,'add_col_header'), 10);
            add_action('manage_st_holidays_posts_custom_column', array($this,'add_col_content'), 10, 2);

            // ==========================================================================

            self::$booking_page=admin_url('edit.php?post_type=st_holidays&page=st_holidays_booking');
            add_action('admin_menu',array($this,'new_menu_page'));

            //Check booking edit and redirect
            if(self::is_booking_page())
            {
                add_action('admin_enqueue_scripts',array(__CLASS__,'add_edit_scripts'));
                add_action('admin_init',array($this,'_do_save_booking'));
                
            }

            if(isset($_GET['send_mail']) and $_GET['send_mail']=='success')
            {
                self::set_message(__('Email sent',ST_TEXTDOMAIN),'updated');
            }

            add_action('wp_ajax_st_room_select_ajax',array(__CLASS__,'st_room_select_ajax'));
            add_action( 'save_post', array($this,'meta_update_sale_price') ,10,4);
            parent::__construct();


            add_action('save_post', array($this, '_update_list_location'), 50, 2);
            add_action('save_post', array($this, '_update_duplicate_data'), 50, 2);
            add_action( 'before_delete_post', array($this, '_delete_data'), 50 );

            add_action('wp_ajax_st_getInfoHoliday', array(__CLASS__,'_getInfoHoliday'), 9999);
        }
        static function _getInfoHoliday(){
            $holiday_id = intval(STInput::request('holiday_id', ''));
            $result = array(
                'type_holiday' => '',
                'max_people' => 0,
                'adult_html' => '',
                'child_html' => '',
                'infant_html' => '',
            );
            $duration = 0;
            $duration_unit = '';
            if(get_post_type($holiday_id) == 'st_holidays'){
                $type_holiday = get_post_meta($holiday_id, 'type_holiday', true);
                $max_people = intval(get_post_meta($holiday_id, 'max_people', true));
                if($max_people <= 0) $max_people = 1;
                $adult_html = '<select name="adult_number" class="form-control" style="width: 100px">';
                $child_html = '<select name="child_number" class="form-control" style="width: 100px">';
                $infant_html = '<select name="infant_number" class="form-control" style="width: 100px">';
                for($i = 0; $i <= $max_people; $i++){
                    $adult_html .= '<option value="'.$i.'">'.$i.'</option>';
                    $child_html .= '<option value="'.$i.'">'.$i.'</option>';
                    $infant_html .= '<option value="'.$i.'">'.$i.'</option>';
                }
                $adult_html .= '</select>';
                $child_html .= '</select>';
                $child_html .= '</select>';

                if($type_holiday && $type_holiday == 'daily_holiday'){
                    $html = "<select name='type_holiday' class='form-control form-control-admin'>
                        <option value='daily_holiday'>".__('Daily Holiday', ST_TEXTDOMAIN)."</option>
                    </select>";
                    $result['type_holiday'] = $html;
                    $result['holiday_text'] = $type_holiday;
                    $duration = get_post_meta($holiday_id, 'duration_day', true);
                    $duration_unit = get_post_meta($holiday_id, 'duration_unit', true);
                }elseif($type_holiday && $type_holiday == 'specific_date'){
                    $html = "<select name='type_holiday' class='form-control form-control-admin'>
                        <option value='specific_date'>".__('Specific Date', ST_TEXTDOMAIN)."</option>
                    </select>";
                    $result['type_holiday'] = $html;
                    $result['holiday_text'] = $type_holiday;
                }
                $result['max_people'] = $max_people;
                $result['adult_html'] = $adult_html;
                $result['child_html'] = $child_html;
                $result['infant_html'] = $infant_html;
                $result['duration'] = $duration.' '.$duration_unit.'(s)';
            }

            echo json_encode($result);
            die();
        }
        function _do_save_booking()
        {
            $section = isset($_GET['section'])?$_GET['section']:false;
            switch($section){
                case "edit_order_item":
                    if($this->is_able_edit())
                    {
                        if(isset($_POST['submit']) and $_POST['submit']) $this->_save_booking(STInput::get('order_item_id'));
                    }
                    
                    break;
                case "add_booking":
                    if(isset($_POST['submit']) and $_POST['submit']) $this->_add_booking();
                    break;
                case 'resend_email_holidays':
                    $this->_resend_mail();
                    break;
            }
        }
        public function _delete_data($post_id){
            if(get_post_type($post_id) == 'st_holidays'){
                global $wpdb;
                $table = $wpdb->prefix.'st_holidays';
                $rs = TravelHelper::deleteDuplicateData($post_id, $table);
                if(!$rs)
                    return false;
                return true;
            }
        }
        function _update_duplicate_data($id, $data){

            if(!TravelHelper::checkTableDuplicate('st_holidays')) return;
            if(get_post_type($id) == 'st_holidays'){
                $num_rows = TravelHelper::checkIssetPost($id, 'st_holidays');

                $location_str = get_post_meta($id, 'multi_location', true);

                $location_id = ''; // location_id
                
                $address = get_post_meta($id, 'address', true); // address

                $max_people = get_post_meta($id, 'max_people', true); // maxpeople
                $check_in = get_post_meta($id, 'check_in', true); // check in
                $check_out = get_post_meta($id, 'check_out', true); // check out
                $type_holiday = get_post_meta($id, 'type_holiday', true); // check out
                $duration_day = get_post_meta($id, 'duration_day', true); // duration_day
                $holidays_booking_period = get_post_meta($id, 'holidays_booking_period', true); // holidays_booking_period

                $sale_price  = get_post_meta($id,'price',true); // sale_price
                
                $child_price = get_post_meta($id, 'child_price', true);
                $adult_price = get_post_meta($id, 'adult_price', true);  
                $infant_price = get_post_meta($id, 'infant_price', true);  
                

                $discount = get_post_meta($id, 'discount', true); 
                
                $is_sale_schedule=get_post_meta($id,'is_sale_schedule',true);
                if($is_sale_schedule=='on')
                {
                    $sale_from=get_post_meta($id,'sale_price_from',true);
                    $sale_to=get_post_meta($id,'sale_price_to',true);
                    if($sale_from and $sale_from){

                        $today=date('Y-m-d');
                        $sale_from = date('Y-m-d', strtotime($sale_from));
                        $sale_to = date('Y-m-d', strtotime($sale_to));
                        if (($today >= $sale_from) && ($today <= $sale_to))
                        {

                        }else{

                            $discount = 0;
                        }

                    }else{
                        $discount = 0;
                    }
                }
                if($discount){
                    $sale_price     = $sale_price - ($sale_price / 100)*$discount;
                    $child_price    = $child_price - ($child_price / 100)*$discount;
                    $adult_price    = $adult_price - ($adult_price / 100)*$discount;
                    $infant_price    = $infant_price - ($infant_price / 100)*$discount;
                }
                
                $rate_review = STReview::get_avg_rate($id); // rate review

                if($num_rows == 1){
                    $data = array(
                        'multi_location' => $location_str,
                        'id_location'   => $location_id,
                        'address'       => $address,
                        'type_holiday'     => $type_holiday,
                        'check_in'      => $check_in,
                        'check_out'     => $check_out,
                        'sale_price'    => $sale_price,
                        'child_price'   => $child_price,
                        'adult_price'   => $adult_price,
                        'infant_price'   => $infant_price,
                        'max_people'    => $max_people,
                        'rate_review'   => $rate_review,
                        'duration_day'  => $duration_day,
                        'holidays_booking_period' => $holidays_booking_period,
                    );
                    $where = array(
                        'post_id' => $id
                    );

                    TravelHelper::updateDuplicate('st_holidays', $data, $where);
                }elseif($num_rows == 0){
                    $data = array(
                        'post_id' => $id,
                        'multi_location' => $location_str,
                        'id_location' => $location_id,
                        'address' => $address,
                        'type_holiday' => $type_holiday,
                        'check_in' => $check_in,
                        'check_out' => $check_out,
                        'sale_price' => $sale_price,
                        'child_price'   => $child_price,
                        'adult_price'   => $adult_price,
                        'infant_price'   => $infant_price,
                        'max_people' => $max_people,
                        'rate_review' => $rate_review,
                        'duration_day' => $duration_day,
                        'holidays_booking_period' => $holidays_booking_period,
                    );

                    TravelHelper::insertDuplicate('st_holidays', $data);
                }
            }
        }
        /** 
        *@since 1.1.7
        **/
        function _update_list_location($id, $data){
            $location = STInput::request('multi_location', '');
            if(isset($_REQUEST['multi_location'])){
                if(is_array($location) && count($location)){
                    $location_str = '';
                    foreach($location as $item){
                        if(empty($location_str)){
                            $location_str.= $item;
                        }else{
                            $location_str.=','.$item;
                        }
                    }
                }else{
                    $location_str = '';
                }
                update_post_meta($id,'multi_location', $location_str);
                update_post_meta($id,'id_location', '');
            }
            
        }
        /**
         * Init the post type
         *
         * @since 1.1.3
         * */
        function _init_post_type()
        {
            if(!st_check_service_available($this->post_type))
            {
                return;
            }

            if(!function_exists('st_reg_post_type')) return;
            // Holidays ==============================================================
            $labels = array(
                'name'               => __( 'Holidays', ST_TEXTDOMAIN ),
                'singular_name'      => __( 'Holiday', ST_TEXTDOMAIN ),
                'menu_name'          => __( 'Holidays', ST_TEXTDOMAIN ),
                'name_admin_bar'     => __( 'Holiday', ST_TEXTDOMAIN ),
                'add_new'            => __( 'Add New', ST_TEXTDOMAIN ),
                'add_new_item'       => __( 'Add New Holiday', ST_TEXTDOMAIN ),
                'new_item'           => __( 'New Holiday', ST_TEXTDOMAIN ),
                'edit_item'          => __( 'Edit Holiday', ST_TEXTDOMAIN ),
                'view_item'          => __( 'View Holiday', ST_TEXTDOMAIN ),
                'all_items'          => __( 'All Holiday', ST_TEXTDOMAIN ),
                'search_items'       => __( 'Search Holiday', ST_TEXTDOMAIN ),
                'parent_item_colon'  => __( 'Parent Holiday:', ST_TEXTDOMAIN ),
                'not_found'          => __( 'No Holidays found.', ST_TEXTDOMAIN ),
                'not_found_in_trash' => __( 'No Holidays found in Trash.', ST_TEXTDOMAIN )
            );

            $args = array(
                'labels'             => $labels,
                'public'             => true,
                'publicly_queryable' => true,
                'show_ui'            => true,
                'query_var'          => true,
                'rewrite'            => array( 'slug' =>  get_option( 'holiday_permalink' ,'st_holiday' ) ),
                'capability_type'    => 'post',
                'hierarchical'       => false,
                //'menu_position'      => null,
                'supports'           => array( 'author','title','editor' , 'excerpt','thumbnail', 'comments' ),
                'menu_icon'          =>'dashicons-palmtree-st'
            );

            st_reg_post_type( 'st_holidays', $args );

            $labels = array(
                'name'                       => __( 'Holiday Categories', 'taxonomy general name', ST_TEXTDOMAIN ),
                'singular_name'              => __( 'Holiday Categories', 'taxonomy singular name', ST_TEXTDOMAIN ),
                'search_items'               => __( 'Search Holiday Categories' , ST_TEXTDOMAIN),
                'popular_items'              => __( 'Popular Holiday Categories' , ST_TEXTDOMAIN),
                'all_items'                  => __( 'All Holiday Categories', ST_TEXTDOMAIN ),
                'parent_item'                => null,
                'parent_item_colon'          => null,
                'edit_item'                  => __( 'Edit Holiday Categories' , ST_TEXTDOMAIN),
                'update_item'                => __( 'Update Holiday Categories' , ST_TEXTDOMAIN),
                'add_new_item'               => __( 'Add New Pickup Feature', ST_TEXTDOMAIN ),
                'new_item_name'              => __( 'New Holiday Type Name', ST_TEXTDOMAIN ),
                'separate_items_with_commas' => __( 'Separate Holiday Categories with commas' , ST_TEXTDOMAIN),
                'add_or_remove_items'        => __( 'Add or remove Holiday Categories', ST_TEXTDOMAIN ),
                'choose_from_most_used'      => __( 'Choose from the most used Holiday Categories', ST_TEXTDOMAIN ),
                'not_found'                  => __( 'No Pickup Holiday Categories.', ST_TEXTDOMAIN ),
                'menu_name'                  => __( 'Holiday Categories', ST_TEXTDOMAIN ),
            );
            $args = array(
                'hierarchical'          => true,
                'labels'                => $labels,
                'show_ui'               => true,
                'show_admin_column'     => true,
                'query_var'             => true,
                'rewrite'               => array( 'slug' => 'st_holiday_type' ),
            );

            st_reg_taxonomy( 'st_holiday_type', 'st_holidays', $args );
        }

        /**
         *
         *
         * @since 1.1.1
         * @update 1.1.2
         * */
        function init_metabox()
        {
            //Room
            $this->metabox[] = array(
                'id'          => 'room_metabox',
                'title'       => __( 'Holiday Setting', ST_TEXTDOMAIN),
                'desc'        => '',
                'pages'       => array( 'st_holidays' ),
                'context'     => 'normal',
                'priority'    => 'high',
                'fields'      => array(
                    array(
                        'label'       => __( 'Location', ST_TEXTDOMAIN),
                        'id'          => 'location_reneral_tab',
                        'type'        => 'tab'
                    ),
                    array(
                        'label'     => __('Location', ST_TEXTDOMAIN),
                        'id'        => 'multi_location', // id_location
                        'type'      => 'list_item_post_type',
                        'desc'        => __( 'Holiday Location', ST_TEXTDOMAIN),
                        'post_type'   =>'location'
                    ),/*
                    array(
                        'label'       => __( 'Location', ST_TEXTDOMAIN),
                        'id'          => 'id_location',
                        'type'        => 'post_select_ajax',
                        'post_type'   =>'location'
                    ),*/
                    array(
                        'label'       => __( 'Address', ST_TEXTDOMAIN),
                        'id'          => 'address',
                        'type'        => 'text',
                        'desc'        => __( 'Address', ST_TEXTDOMAIN),
                    ),
                    array(
                        'label' => __('Maps', ST_TEXTDOMAIN),
                        'id'    => 'st_google_map',
                        'type'  => 'bt_gmap',
                        'desc'  => __('Maps', ST_TEXTDOMAIN),
                        'std'   => 'off'
                    ),
                    array(
                        'label'       => __( 'General', ST_TEXTDOMAIN),
                        'id'          => 'room_reneral_tab',
                        'type'        => 'tab'
                    ),
                    array(
                        'label'       => __( 'Set as Featured', ST_TEXTDOMAIN),
                        'id'          => 'is_featured',
                        'type'        => 'on-off',
                        'desc'        => __( 'This is set as featured', ST_TEXTDOMAIN),
                        'std'         =>'off'
                    ),
                    array(
                        'label'       => __( 'Custom Layout', ST_TEXTDOMAIN),
                        'id'          => 'st_custom_layout',
                        'post_type'   =>'st_layouts',
                        'desc'        => __( 'Detail Holiday Layout', ST_TEXTDOMAIN),
                        'type'        => 'select',
                        'choices'     => st_get_layout('st_holidays')
                    ),

                    array(
                        'label'       => __( 'Gallery', ST_TEXTDOMAIN),
                        'desc'       => __( 'Select images for holiday', ST_TEXTDOMAIN),
                        'id'          => 'gallery',
                        'type'        => 'gallery',
                    ),
                    /*array(
                        'label'       => __( 'Gallery style', ST_TEXTDOMAIN),
                        'id'          => 'gallery_style',
                        'type'        => 'select',
                        'choices'   =>array(
                            array(
                                'value'=>'grid',
                                'label'=>__('Grid',ST_TEXTDOMAIN)
                            ),
                            array(
                                'value'=>'slider',
                                'label'=>__('Slider',ST_TEXTDOMAIN)
                            ),
                        )
                    ),*/
                    array(
                        'label'       => __( 'Contact email addresses', ST_TEXTDOMAIN),
                        'id'          => 'contact_email',
                        'type'        => 'text',
                        'desc'        => __( 'Contact email addresses', ST_TEXTDOMAIN),
                    ),
                    array(
                        'label'       => __( 'Video', ST_TEXTDOMAIN),
                        'id'          => 'video',
                        'type'        => 'text',
                        'desc'        => __('Please use youtube or vimeo video',ST_TEXTDOMAIN)
                    ),

                    array(
                        'label'       => __( 'Price setting', ST_TEXTDOMAIN),
                        'id'          => 'price_number_tab',
                        'type'        => 'tab'
                    ),/*
                    array(
                        'label'       => __( 'Price type', ST_TEXTDOMAIN),
                        'id'          => 'type_price',
                        'type'        => 'select',
                        'desc'        => __( 'Price type', ST_TEXTDOMAIN),
                        'choices'   =>array(
                            array(
                                'value'=>'holiday_price',
                                'label'=>__('Price / Holiday',ST_TEXTDOMAIN)
                            ),
                            array(
                                'value'=>'people_price',
                                'label'=>__('Price / Person',ST_TEXTDOMAIN)
                            ),
                        )
                    ),
                    array(
                        'label'       => __( 'Price', ST_TEXTDOMAIN),
                        'id'          => 'price',
                        'type'        => 'text',
                        'desc'        => __( 'Price of this holiday', ST_TEXTDOMAIN),
                        'std'         =>0,
                        'condition'   =>'type_price:is(holiday_price)'
                    ),*/
                    array(
                        'label'       => __( 'Adult Price', ST_TEXTDOMAIN),
                        'id'          => 'adult_price',
                        'type'        => 'text',
                        'desc'        => __( 'Price per Adult', ST_TEXTDOMAIN),
                        'std'         =>0,
                    ),
                    array(
                        'label'       => __( 'Fields list discount by Adult number booking', ST_TEXTDOMAIN),
                        'id'          => 'discount_by_adult',
                        'type'        => 'list-item',
                        'desc'        => __( 'Fields list discount by Adult number booking', ST_TEXTDOMAIN),
                        'std'         =>0,
                        'settings'    =>array(
                            array(
                                'id'=>'key',
                                'label'=>__('Number of Adult',ST_TEXTDOMAIN),
                                'type'=>'text',
                            ),
                            array(
                                'id'=>'value',
                                'label'=>__('Value percent of discount',ST_TEXTDOMAIN),
                                'type'        => 'numeric-slider',
                                'min_max_step'=> '0,100,0.5',
                            )
                        )
                    ),
                    array(
                        'label'       => __( 'Child Price', ST_TEXTDOMAIN),
                        'id'          => 'child_price',
                        'type'        => 'text',
                        'desc'        => __( 'Price per Child', ST_TEXTDOMAIN),
                        'std'         =>0,
                    ),
                    array(
                        'label'       => __( 'Fields list discount by Child number booking', ST_TEXTDOMAIN),
                        'id'          => 'discount_by_child',
                        'type'        => 'list-item',
                        'desc'        => __( 'Fields list discount by Child number booking', ST_TEXTDOMAIN),
                        'std'         =>0,
                        'settings'    =>array(
                            array(
                                'id'=>'key',
                                'label'=>__('Number of Children',ST_TEXTDOMAIN),
                                'type'=>'text',
                            ),
                            array(
                                'id'=>'value',
                                'label'=>__('Value percent of discount',ST_TEXTDOMAIN),
                                'type'        => 'numeric-slider',
                                'min_max_step'=> '0,100,0.5',
                            )
                        )
                    ),

                    array(
                        'label'       => __( 'Infant Price', ST_TEXTDOMAIN),
                        'id'          => 'infant_price',
                        'type'        => 'text',
                        'desc'        => __( 'Price per Infant', ST_TEXTDOMAIN),
                        'std'         =>0,
                    ),
                    array(
                        'label'       => __( 'Discount by percent', ST_TEXTDOMAIN),
                        'id'          => 'discount',
                        'type'        => 'numeric-slider',
                        'min_max_step'=> '0,100,1',
                        'desc'        => __( 'Discount of this holiday, by percent', ST_TEXTDOMAIN),
                        'std'         =>0
                    ),
                    array(
                        'label'       =>  __( 'Sale Schedule', ST_TEXTDOMAIN),
                        'id'          => 'is_sale_schedule',
                        'type'        => 'on-off',
                        'std'        => 'off',
                    ),
                    array(
                        'label'       =>  __( 'Sale Start Date', ST_TEXTDOMAIN),
                        'desc'       =>  __( 'Sale Start Date', ST_TEXTDOMAIN),
                        'id'          => 'sale_price_from',
                        'type'        => 'date-picker',
                        'condition'   =>'is_sale_schedule:is(on)'
                    ),

                    array(
                        'label'       =>  __( 'Sale End Date', ST_TEXTDOMAIN),
                        'desc'       =>  __( 'Sale End Date', ST_TEXTDOMAIN),
                        'id'          => 'sale_price_to',
                        'type'        => 'date-picker',
                        'condition'   =>'is_sale_schedule:is(on)'
                    ),
                    array(
                        'id'      => 'deposit_payment_status',
                        'label'   => __("Deposit payment options", ST_TEXTDOMAIN),
                        'desc'    => __('You can select <code>Disallow Deposit</code>, <code>Deposit by percent</code>, <code>Deposit by amount</code>'),
                        'type'    => 'select',
                        'choices' => array(
                            array(
                                'value' => '',
                                'label' => __('Disallow Deposit', ST_TEXTDOMAIN)
                            ),
                            array(
                                'value' => 'percent',
                                'label' => __('Deposit by percent', ST_TEXTDOMAIN)
                            ),
                            array(
                                'value' => 'amount',
                                'label' => __('Deposit by amount', ST_TEXTDOMAIN)
                            ),
                        )
                    ),
                    array(
                        'label'      => __('Deposit payment amount', ST_TEXTDOMAIN),
                        'desc'       => __('Leave empty for disallow deposit payment', ST_TEXTDOMAIN),
                        'id'         => 'deposit_payment_amount',
                        'type'       => 'text',
                        'condition' => 'deposit_payment_status:not()'
                    ),
                    array(
                        'label'       => __( 'Information', ST_TEXTDOMAIN),
                        'id'          => 'st_info_holidays_tab',
                        'type'        => 'tab'
                    ),
                    array(
                        'label'       => __( 'Holiday Type', ST_TEXTDOMAIN),
                        'id'          => 'type_holiday',
                        'type'        => 'select',
                        'desc'        =>__('Holiday Type',ST_TEXTDOMAIN),
                        'choices'   =>array(
                            array(
                                'value'=>'daily_holiday',
                                'label'=>__('Daily Holiday',ST_TEXTDOMAIN)
                            ),
                            array(
                                'value'=>'specific_date',
                                'label'=>__('Specific Date',ST_TEXTDOMAIN)
                            ),
                        )
                    ),/*
                    array(
                        'label'       => __( 'Departure date ', ST_TEXTDOMAIN),
                        'id'          => 'check_in',
                        'type'        => 'date_picker',
                        'condition'   =>'type_holiday:is(specific_date)',
                        'desc'        => __( 'Departure date ', ST_TEXTDOMAIN),
                    ),
                    array(
                        'label'       => __( 'Arrival date', ST_TEXTDOMAIN),
                        'id'          => 'check_out',
                        'type'        => 'date_picker',
                        'condition'   =>'type_holiday:is(specific_date)',
                        'desc'        => __( 'Arrival date', ST_TEXTDOMAIN)
                    ),*/
                    array(
                        'label'       => __( 'Duration', ST_TEXTDOMAIN),
                        'id'          => 'duration_day',
                        'type'        => 'text',
                        'desc'        => __( 'Duration', ST_TEXTDOMAIN),
                        'std'         => '1',
                        'condition'   =>'type_holiday:is(daily_holiday)'
                    ),
                    array(
                        'label'       => __( 'Duration unit', ST_TEXTDOMAIN),
                        'id'          => 'duration_unit',
                        'type'        => 'select',
                        'desc'        => __( 'Select your duration unit', ST_TEXTDOMAIN),
                        'std'         => __('day' , ST_TEXTDOMAIN),
                        'choices'     => array(
                            array(
                                'value' =>'day',
                                'label' =>__('Days' , ST_TEXTDOMAIN),
                                ),
                            array(
                                'value' =>'hour',
                                'label' =>__('Hours' , ST_TEXTDOMAIN),
                                ),
                            array(
                                'value' =>'week',
                                'label' =>__('Weeks' , ST_TEXTDOMAIN),
                                ),
                            array(
                                'value' =>'month',
                                'label' =>__('Months' , ST_TEXTDOMAIN),
                                ),
                            ),
                        'condition'   =>'type_holiday:is(daily_holiday)'
                    ),
                    array(
                        'label' => __('Minimum days to book before departure',ST_TEXTDOMAIN),
                        'desc' => __('Minimum days to book before departure',ST_TEXTDOMAIN),
                        'id' => 'holidays_booking_period',
                        'type'        => 'numeric-slider',
                        'min_max_step'=> '0,30,1',
                        'std' => 0,
                    ),
                    array(
                        'label' => __('Holiday external booking',ST_TEXTDOMAIN),
                        'id' => 'st_holiday_external_booking',
                        'type'        => 'on-off',
                        'std' => "off",
                    ),
                    array(
                        'label' => __('Holiday external booking link ',ST_TEXTDOMAIN),
                        'id' => 'st_holiday_external_booking_link',
                        'type'        => 'text',
                        'std' => "",
                        'condition'   =>'st_holiday_external_booking:is(on)',
                        'desc'=>"<em>".__('Notice: Must be http://...',ST_TEXTDOMAIN)."</em>",
                    ),
                    array(
                        'label'       => __( 'Max No. People', ST_TEXTDOMAIN),
                        'id'          => 'max_people',
                        'type'        => 'text',
                        'desc'        => __( 'Max No. People', ST_TEXTDOMAIN),
                        'std'         => '1',
                    ),
                    array(
                        'id'          => 'holidays_program',
                        'label'       => __( "Holiday program", ST_TEXTDOMAIN ),
                        'type'        => 'list-item',
                        'settings'    =>array(
                            array(
                                'id'=>'desc',
                                'label'=>__('Description',ST_TEXTDOMAIN),
                                'type'=>'textarea',
                                'rows'        => '5',
                            )
                        )
                    ),

                    array(
                        'label' => __('Availability', ST_TEXTDOMAIN),
                        'id' => 'availability_tab',
                        'type' => 'tab'
                    ),
                    array(
                        'label' => __('Holiday Calendar', ST_TEXTDOMAIN),
                        'id' => 'st_holiday_calendar',
                        'type' => 'st_holiday_calendar'
                    )
                )
            );
            $data_paypment = STPaymentGateways::get_payment_gateways();
            if(!empty($data_paypment) and is_array($data_paypment)){
                $this->metabox[0]['fields'][] = array(
                    'label'       => __( 'Payment', ST_TEXTDOMAIN),
                    'id'          => 'payment_detail_tab',
                    'type'        => 'tab'
                );
                foreach($data_paypment as $k=>$v){
                    $this->metabox[0]['fields'][] = array(
                        'label'       =>$v->get_name() ,
                        'id'          => 'is_meta_payment_gateway_'.$k,
                        'type'        => 'on-off',
                        'desc'        => $v->get_name(),
                        'std'         => 'on'
                    );
                }
            }
            $custom_field = st()->get_option('holidays_unlimited_custom_field');
            if(!empty($custom_field) and is_array($custom_field)){
                $this->metabox[0]['fields'][]=array(
                    'label'       => __( 'Custom fields', ST_TEXTDOMAIN),
                    'id'          => 'custom_field_tab',
                    'type'        => 'tab'
                );
                foreach($custom_field as $k => $v){
                    $key = str_ireplace('-','_','st_custom_'.sanitize_title($v['title']));
                    $this->metabox[0]['fields'][]=array(
                        'label'       => $v['title'],
                        'id'          => $key,
                        'type'        => $v['type_field'],
                        'desc'        => '<input value=\'[st_custom_meta key="'.$key.'"]\' type=text readonly />',
                        'std'         =>$v['default_field']
                    );
                }
            }


            parent::register_metabox($this->metabox);
        }


        function meta_update_sale_price($post_id)
        {
            if ( wp_is_post_revision( $post_id ) )
                return;
            $post_type=get_post_type($post_id);
            if($post_type=='st_holidays')
            {
                $sale_price=get_post_meta($post_id,'price',true);
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
                    $sale_price= $sale_price - ($sale_price/100)*$discount;
                }
                update_post_meta($post_id,'sale_price',$sale_price);
            }
        }
        function _resend_mail()
        {
            $order_item=isset($_GET['order_item_id'])?$_GET['order_item_id']:false;

            $test=isset($_GET['test'])?$_GET['test']:false;
            if($order_item){

                $order=$order_item;

                if($test){
                    global $order_id;
                    $order_id = $order_item;
                    $email_to_admin = st()->get_option('email_for_admin', '');
                    $email = st()->load_template('email/header');
                    $email .= do_shortcode($email_to_admin);
                    $email .= st()->load_template('email/footer');
                    echo($email);
                    die;
                }


                if($order){
                    STCart::send_mail_after_booking($order);
                }
            }

            wp_safe_redirect(self::$booking_page.'&send_mail=success');
        }
        static  function  st_room_select_ajax()
        {
            extract( wp_parse_args($_GET,array(
                'room_parent'=>'',
                'post_type'=>'',
                'q'=>''
            )));


            query_posts(array('post_type'=>$post_type,'posts_per_page'=>10,'s'=>$q,'meta_key'=>'room_parent','meta_value'=>$room_parent));

            $r=array(
                'items'=>array(),
            );
            while(have_posts())
            {
                the_post();
                $r['items'][]=array(
                    'id'=>get_the_ID(),
                    'name'=>get_the_title(),
                    'description'=>''
                );
            }

            wp_reset_query();

            echo json_encode($r);
            die;

        }
        static function  add_edit_scripts()
        {
            wp_enqueue_script('moment.js', get_template_directory_uri() . '/js/fullcalendar-2.4.0/lib/moment.min.js', array('jquery'), NULL, TRUE);
            wp_enqueue_script('fullcalendar', get_template_directory_uri() . '/js/fullcalendar-2.4.0/fullcalendar.min.js', array('jquery'), NULL, TRUE);

            wp_enqueue_style('fullcalendar', get_template_directory_uri() . '/js/fullcalendar-2.4.0/fullcalendar.min.css');
            wp_enqueue_style('availability_holiday', get_template_directory_uri() . '/css/availability_holiday.css');
            wp_enqueue_script('select2');
            wp_enqueue_script('st-edit-booking',get_template_directory_uri().'/js/admin/edit-booking.js',array('jquery'),null,true);
            wp_enqueue_script('st-qtip',get_template_directory_uri().'/js/jquery.qtip.js',array('jquery'),null,true);
            wp_enqueue_script('holiday-booking',get_template_directory_uri().'/js/admin/holiday-booking.js',array('jquery'),null,true);
            wp_enqueue_script('jquery-ui-datepicker');
            wp_enqueue_style('jjquery-ui.theme.min.css',get_template_directory_uri().'/css/admin/jquery-ui.min.css');
            wp_enqueue_style('jjquery-qtip',get_template_directory_uri().'/css/qtip.css');

            $locale=get_locale();
            if($locale and $locale!='en') {
                $locale_array=explode('_',$locale);
                if(!empty($locale_array) and $locale_array[0]){
                    $locale=$locale_array[0];
                }
            }
            wp_localize_script('jquery', 'st_params', array(

                'locale'            =>$locale,
                'text_refresh'=>__("Refresh",ST_TEXTDOMAIN)
            ));
        }
        static function is_booking_page()
        {
            if(is_admin()
                and isset($_GET['post_type'])
                and $_GET['post_type']=='st_holidays'
                and isset($_GET['page'])
                and $_GET['page']='st_holidays_booking'
            ) return true;
            return false;
        }

        function new_menu_page()
        {
            //Add booking page
            add_submenu_page('edit.php?post_type=st_holidays',__('Holiday Booking',ST_TEXTDOMAIN), __('Holiday Booking',ST_TEXTDOMAIN), 'manage_options', 'st_holidays_booking', array($this,'__holidays_booking_page'));
        }

        function  __holidays_booking_page(){

            $section=isset($_GET['section'])?$_GET['section']:false;

            if($section){
                switch($section)
                {
                    case "edit_order_item":
                    // show edit page
                        $this->edit_order_item();
                        break;
                    case 'add_booking':
                        $this->add_booking();
                        break;
                }
            }else{

                $action=isset($_POST['st_action'])?$_POST['st_action']:false;
                switch($action){
                    case "delete":
                        $this->_delete_items();
                        break;
                }
                echo balanceTags($this->load_view('holiday/booking_index',false));
            }

        }
        function add_booking()
        {

            echo balanceTags($this->load_view('holiday/booking_edit',false,array('page_title'=>__('Add new Holiday Booking',ST_TEXTDOMAIN))));
        }
        function _delete_items(){

            if ( empty( $_POST ) or  !check_admin_referer( 'shb_action', 'shb_field' ) ) {
                //// process form data, e.g. update fields
                return;
            }
            $ids=isset($_POST['post'])?$_POST['post']:array();
            if(!empty($ids))
            {
                foreach($ids as $id)
                    wp_delete_post($id,true);

            }

            STAdmin::set_message(__("Delete item(s) success",ST_TEXTDOMAIN),'updated');

        }

        function edit_order_item()
        {
            $item_id=isset($_GET['order_item_id'])?$_GET['order_item_id']:false;
            if(!$item_id or get_post_type($item_id)!='st_order')
            {
                return false;
            }

            

            echo balanceTags($this->load_view('holiday/booking_edit'));
        }
        function _add_booking(){

            if(!check_admin_referer( 'shb_action', 'shb_field' )) die;
            $data = $this->_check_validate();

            if(is_array($data) && count($data)){
                extract($data);
                $order = array(
                    'post_title'=>__('Order',ST_TEXTDOMAIN).' - '.date(get_option( 'date_format' )).' @ '.date(get_option('time_format')),
                    'post_type'=>'st_order',
                    'post_status'=>'publish'
                );
                $order_id = wp_insert_post($order);

                if($order_id){
                    $check_out_field = STCart::get_checkout_fields();

                    if(!empty($check_out_field)){
                        foreach($check_out_field as $field_name=>$field_desc){
                            update_post_meta($order_id,$field_name,STInput::post($field_name));
                        }
                    }

                    $id_user = get_current_user_id();
                    update_post_meta($order_id, 'id_user', $id_user);

                    update_post_meta($order_id,'payment_method','st_submit_form');

                    $data_price = STPrice::getPriceByPeopleHoliday($item_id, strtotime($check_in), strtotime($check_out),$adult_number, $child_number, $infant_number);
                    $sale_price = STPrice::getSaleHolidaySalePrice($item_id, $data_price['total_price'], $type_holiday, strtotime($check_in));
                    
                    $price_with_tax = STPrice::getPriceWithTax($sale_price);
                    
                    $deposit_money['data'] = array();
                            
                    $deposit_money = STPrice::getDepositData($item_id, $deposit_money);
                    $deposit_price = STPrice::getDepositPrice($deposit_money['data']['deposit_money'], $price_with_tax, 0);
                    if(isset($deposit_money['data']['deposit_money']) && $deposit_price > 0){
                        $total_price = $deposit_price;
                    }else{
                        $total_price = $price_with_tax - $coupon_price;
                    }
                    $data_prices = array(
                        'origin_price' => $data_price['total_price'],
                        'sale_price' => $sale_price,
                        'coupon_price' => 0,
                        'price_with_tax' => $price_with_tax,
                        'total_price' => $total_price,
                        'deposit_price' => $deposit_price
                    );
                    $item_data = array(
                        'item_number' => 1,
                        'item_id' => $item_id,
                        'check_in'    => date('Y-m-d',strtotime($check_in)),
                        'check_out'   => date('Y-m-d',strtotime($check_out)),
                        'type_holiday' => $type_holiday,
                        'duration' => $duration,
                        'adult_price' => $adult_price,
                        'child_price' => $child_price,
                        'infant_price' => $infant_price,
                        'adult_number' => $adult_number,
                        'child_number' => $child_number,
                        'infant_number' => $child_number,
                        'total_price' => $total_price,
                        'data_prices' => $data_prices,
                        'booking_by' => 'admin',
                        'st_tax' => STPrice::getTax(),
                        'st_tax_percent' => STPrice::getTax(),
                        'status' => $_POST['status'],
                        'deposit_money' => $deposit_money['data']['deposit_money'],
                        'currency'        => TravelHelper::get_current_currency('symbol'),
                        'currency_rate' => TravelHelper::get_current_currency('rate'),
                        'commission' => TravelHelper::get_commission()
                    );

                    foreach($item_data as $key => $value){
                        update_post_meta($order_id, $key , $value);
                    }

                    if(TravelHelper::checkTableDuplicate('st_holidays')){
                        global $wpdb;

                        $table = $wpdb->prefix.'st_order_item_meta';
                        $g_post = get_post($item_id);
                        $partner_id = $g_post ? $g_post->post_author : '';
                        global $sitepress;
                        if($sitepress){
                            $post_type = get_post_type($st_booking_id);
                            if($post_type == 'st_hotel'){
                                $post_type = 'hotel_room';
                                $id = $room_id;
                            }else{
                                $id = $st_booking_id;
                            }
                            $lang_code = $sitepress->get_default_language();
                            $origin_id = icl_object_id($id, $post_type, true, $lang_code);
                        }else{
                            $origin_id = $st_booking_id;
                        }
                        $data = array(
                            'order_item_id' => $order_id,
                            'type' => 'normal_booking',
                            'check_in' => $check_in,
                            'check_out' => $check_out,
                            'st_booking_post_type' => 'st_holidays',
                            'st_booking_id' => $item_id,
                            'adult_number' => $adult_number,
                            'child_number' => $child_number,
                            'infant_number' => $infant_number,
                            'check_in_timestamp' => strtotime($check_in),
                            'check_out_timestamp' => strtotime($check_out),
                            'duration' => $duration,
                            'user_id' => $id_user,
                            'status' => $_POST['status'],
                            'wc_order_id' => $order_id,
                            'partner_id' => $partner_id,
                            'created' => get_the_date('Y-m-d',$order_id),
                            'total_order'=>$total_price,
                            'commission' => TravelHelper::get_commission(),
                            'origin_id' => $origin_id
                        );
                        $wpdb->insert($table, $data);
                    }
                    //Check email
                    $user_name = STInput::post('st_email');
                    $user_id = username_exists( $user_name );
                    if( !$user_id and email_exists($user_name) == false ){
                        $random_password = wp_generate_password( $length = 12, $include_standard_special_chars = false );
                        $userdata = array(
                            'user_login'  =>  $user_name,
                            'user_pass'   =>  $random_password,
                            'user_email'  =>$user_name,
                            'first_name'  =>STInput::post('st_first_name'), // When creating an user, `user_pass` is expected.
                            'last_name'  =>STInput::post('st_last_name') // When creating an user, `user_pass` is expected.
                        );
                        $user_id = wp_insert_user( $userdata );

                        wp_new_user_notification($user_id);
                    }

                    STCart::send_mail_after_booking($order_id, true);
                    STCart::send_email_confirm($order_id);

                    wp_safe_redirect(self::$booking_page);

                    do_action('st_booking_success',$order_id);
                }
            }
        }

        function _save_booking($order_id){
            if(!check_admin_referer( 'shb_action', 'shb_field' )) die;
            $data = $this->_check_validate();
            if(is_array($data)){

                $check_out_field = STCart::get_checkout_fields();

                if(!empty($check_out_field)){
                    foreach($check_out_field as $field_name => $field_desc){
                        update_post_meta($order_id,$field_name,STInput::post($field_name));
                    }
                }
                
                $item_data = array(
                    'status' => $_POST['status'],
                );

                foreach($item_data as $key => $value){
                    update_post_meta($order_id, $key , $value);
                }

                if(TravelHelper::checkTableDuplicate('st_holidays')){
                    global $wpdb;

                    $table = $wpdb->prefix.'st_order_item_meta';
                    $data = array(
                        'status' => $_POST['status'],
                    );
                    $where = array(
                        'order_item_id' => $order_id
                    );
                    $wpdb->update($table, $data, $where);
                }
                
                STCart::send_mail_after_booking($order_id, true);
                STCart::send_email_confirm($order_id);

                wp_safe_redirect(self::$booking_page);
            }
        }

        function _check_validate(){
            $data = array();
            $order_item_id = STInput::request('order_item_id','');

            $st_first_name = STInput::request('st_first_name','');
            if(empty($st_first_name)){
                STAdmin::set_message(__('The firstname field is not empty.', ST_TEXTDOMAIN), 'danger');
                return false;
            }

            $st_last_name = STInput::request('st_last_name','');
            if(empty($st_last_name)){
                STAdmin::set_message(__('The lastname field is not empty.', ST_TEXTDOMAIN), 'danger');
                return false;
            }

            $st_email = STInput::request('st_email', '');
            if(empty($st_email)){
                STAdmin::set_message(__('The email field is not empty.', ST_TEXTDOMAIN), 'danger');
                return false;
            }

            $st_phone = STInput::request('st_phone', '');
            if(empty($st_phone)){
                STAdmin::set_message(__('The phone field is not empty.', ST_TEXTDOMAIN), 'danger');
                return false;
            }

            if(STInput::request('section', '') != 'edit_order_item'){
                $item_id = intval(STInput::request('item_id', ''));

                if($item_id <= 0 || get_post_type($item_id) != 'st_holidays'){
                    STAdmin::set_message(__('The holiday field is not empty.', ST_TEXTDOMAIN), 'danger');
                    return false;
                }

                $type_holiday = get_post_meta($item_id, 'type_holiday', true);

                $today = date('Y-m-d');
                $check_in = STInput::request('check_in', '');
                $check_out = STInput::request('check_out', '');

                if(!$check_in || !$check_out){
                    STAdmin::set_message(__( 'Select a holiday in the calendar above.' , ST_TEXTDOMAIN ) , 'danger' );
                    $pass_validate = FALSE;
                    return false;
                }
                $compare = TravelHelper::dateCompare($today, $check_in);
                if($compare < 0){
                    STAdmin::set_message( __( 'This holiday has expired' , ST_TEXTDOMAIN ) , 'danger' );
                    $pass_validate = false;
                    return false;
                }
                $duration = ($type_holiday = 'daily_holiday') ? get_post_meta($item_id, 'duration_day', true) : '';

                $booking_period = intval(get_post_meta($item_id, 'holidays_booking_period', true));
                $period = TravelHelper::dateDiff($today, $check_in);
                if($period < $booking_period){
                    STAdmin::set_message(sprintf(__('This holiday allow minimum booking is %d day(s)', ST_TEXTDOMAIN), $booking_period), 'danger');
                    $pass_validate = false;
                    return false;
                }

                $adult_number = intval(STInput::request('adult_number', 1));
                $child_number = intval(STInput::request('child_number', 0));
                $infant_number = intval(STInput::request('infant_number', 0));
                $max_number = intval(get_post_meta($item_id, 'max_people', true));

                if($adult_number + $child_number + $infant_number > $max_number){
                    STAdmin::set_message( sprintf(__( 'Max of people for this holiday is %d people' , ST_TEXTDOMAIN ), $max_number) , 'danger' );
                    return false;
                }
                
                $holiday_available = HolidayHelper::checkAvailableHoliday($item_id, strtotime($check_in), strtotime($check_out));
                if(!$holiday_available){
                    STAdmin::set_message(__('The check in, check out day is not invalid or this holiday not available.', ST_TEXTDOMAIN), 'danger');
                    $pass_validate = FALSE;
                    return false;
                }

                $free_people = intval(get_post_meta($item_id, 'max_people', true));
                $result = HolidayHelper::_get_free_peple($item_id, strtotime($check_in), strtotime($check_out), $order_item_id);
                if(is_array($result) && count($result)){
                    $free_people = intval($result['free_people']);
                }
                if($free_people > $max_number){
                    STAdmin::set_message(sprintf(__('This holiday only vacant %d people', ST_TEXTDOMAIN), $free_people), 'danger');
                    $pass_validate = FALSE;
                    return false;
                }

                $data['order_item_id']      = $order_item_id;
                $data['item_id']      = $item_id;
                $data['check_in']     = date('m/d/Y', strtotime($check_in));
                $data['check_out']    = date('m/d/Y',strtotime($check_out));
                $data['adult_number'] = $adult_number;
                $data['child_number'] = $child_number;
                $data['infant_number'] = $infant_number;
                $data['type_holiday'] = $type_holiday;
                $data['duration'] = $duration;
                $people_price = STPrice::getPeoplePrice($item_id, strtotime($check_in), strtotime($check_out));
                $data = wp_parse_args($data, $people_price);
            }
            
            return $data;
        }

        static function get_price_person($post_id = null){
            if(!$post_id) $post_id = get_the_ID();

            $adult_price = get_post_meta($post_id,'adult_price',true);
            $child_price = get_post_meta($post_id,'child_price',true);

            $adult_price = apply_filters('st_apply_tax_amount',$adult_price);
            $child_price = apply_filters('st_apply_tax_amount',$child_price);

            $discount = get_post_meta($post_id,'discount',true);
            $is_sale_schedule = get_post_meta($post_id,'is_sale_schedule',true);

            if($is_sale_schedule == 'on'){
                $sale_from=get_post_meta($post_id,'sale_price_from',true);
                $sale_to=get_post_meta($post_id,'sale_price_to',true);
                if($sale_from and $sale_from){

                    $today=date('Y-m-d');
                    $sale_from = date('Y-m-d', strtotime($sale_from));
                    $sale_to = date('Y-m-d', strtotime($sale_to));
                    if (($today >= $sale_from) && ($today <= $sale_to)){

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
                $data = array(
                    'adult'=>$adult_price,
                    'adult_new'=>$adult_price_new,
                    'child'=>$child_price,
                    'child_new'=>$child_price_new,
                    'discount'=>$discount,

                );
            }else{
                $data = array(
                    'adult_new'=>$adult_price,
                    'adult'    =>$adult_price,
                    'child'     =>$child_price,
                    'child_new'=>$child_price,
                    'discount'=>$discount,
                );
            }


            return $data;
        }

        function get_price_by_holiday($holiday_id){
            if(!empty($holiday_id) && get_post_type($holiday_id) == 'st_holidays'){
                $price = floatval(get_post_meta($holiday_id, 'price', true));
                $discount= get_post_meta($holiday_id,'discount',true);
                $is_sale_schedule=get_post_meta($holiday_id,'is_sale_schedule',true);

                if($is_sale_schedule=='on')
                {
                    $sale_from=get_post_meta($holiday_id,'sale_price_from',true);
                    $sale_to=get_post_meta($holiday_id,'sale_price_to',true);
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

                    $price_new = $price - ($price/100)*$discount;
                    return $price_new;
                }else{
                    return $price;
                }
            }
        }
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
        function is_able_edit()
        {
            $item_id=isset($_GET['order_item_id'])?$_GET['order_item_id']:false;
            if(!$item_id or get_post_type($item_id)!='st_order')
            {
                wp_safe_redirect(self::$booking_page); die;
            }
            return true;
        }


        /* Function  update ========================================================= */

        function holidays_update_location($post_id)
        {
            if ( wp_is_post_revision( $post_id ) )
                return;
            $post_type=get_post_type($post_id);

            if($post_type=='st_holidays')
            {
                $location_id = get_post_meta( $post_id ,'id_location',true);
                $ids_in=array();
                $parents = get_posts( array( 'numberposts' => -1, 'post_status' => 'publish', 'post_type' => 'location', 'post_parent' => $location_id ));

                $ids_in[]=$location_id;

                foreach( $parents as $child ){
                    $ids_in[]=$child->ID;
                }
                $arg = array(
                    'post_type'=>'st_holidays',
                    'meta_query' => array(
                        array(
                            'key'     => 'id_location',
                            'posts_per_page'=>'-1',
                            'value'   => $ids_in,
                            'compare' => 'IN',
                        ),
                    ),
                );
                $query=new WP_Query($arg);
                $offer_holidays = $query->post_count;

                // get total review
                $arg = array(
                    'post_type'=>'st_holidays',
                    'posts_per_page'=>'-1',
                    'meta_query' => array(
                        array(
                            'key'     => 'id_location',
                            'value'   => array( $location_id ),
                            'compare' => 'IN',
                        ),
                    ),
                );
                $query=new WP_Query($arg);
                $total=0;
                if($query->have_posts()) {
                    while($query->have_posts()){
                        $query->the_post();
                        $total +=get_comments_number();
                    }
                }
                // get car min price
                $arg = array(
                    'post_type'=>'st_holidays',
                    'posts_per_page'=>'1',
                    'order'=>'ASC',
                    'meta_key'=>'price',
                    'orderby'=>'meta_value_num',
                    'meta_query' => array(
                        array(
                            'key'     => 'id_location',
                            'value'   => array( $location_id ),
                            'compare' => 'IN',
                        ),
                    ),
                );
                $query=new WP_Query($arg);
                if($query->have_posts()) {
                    $query->the_post();
                    $price_min = get_post_meta(get_the_ID(),'price',true);
                    update_post_meta($location_id,'review_st_holidays',$total);
                    update_post_meta($location_id,'min_price_st_holidays',$price_min);
                    update_post_meta($location_id,'offer_st_holidays',$offer_holidays);
                }
                wp_reset_postdata();

            }
        }
        function holidays_update_price_sale($post_id)
        {
            if ( wp_is_post_revision( $post_id ) )
                return;
            $post_type=get_post_type($post_id);

            if($post_type=='st_holidays')
            {
                $discount = get_post_meta( $post_id ,'discount',true);
                $price = get_post_meta( $post_id ,'price',true);
                if(!empty($discount)){
                    $price_sale = $price - $price * ( $discount / 100 );
                    update_post_meta($post_id,'price_sale',$price_sale);
                }
            }
        }

        function add_col_header($defaults)
        {

            $this->array_splice_assoc($defaults,2,0,array(

                'holiday_date'=>__('Date',ST_TEXTDOMAIN),
                'price'=>__('Price',ST_TEXTDOMAIN),
                'holiday_layout'=>__('Layout',ST_TEXTDOMAIN),

            ));

            return $defaults;
        }
        function array_splice_assoc(&$input, $offset, $length = 0, $replacement = array()) {
            $tail = array_splice($input, $offset);
            $extracted = array_splice($tail, 0, $length);
            $input += $replacement + $tail;
            return $extracted;
        }
        function add_col_content($column_name, $post_ID)
        {
            if ($column_name == 'holiday_layout') {
                // show content of 'directors_name' column
                $parent = get_post_meta($post_ID, 'st_custom_layout', TRUE);

                if ($parent) {
                    echo "<a href='" . get_edit_post_link($parent) . "'>" . get_the_title($parent) . "</a>";
                } else {
                    echo __('Default', ST_TEXTDOMAIN);
                }
            }

            if ($column_name == 'holiday_date') {
                $check_in = get_post_meta($post_ID , 'check_in' ,true);
                $check_out = get_post_meta($post_ID , 'check_out' ,true);
                $date = mysql2date('d/m/Y',$check_in).' <i class="fa fa-long-arrow-right"></i> '.mysql2date('d/m/Y',$check_out);
                if(!empty($check_in) and !empty($check_out)){
                    echo balanceTags($date);
                }else{
                    _e('none',ST_TEXTDOMAIN);
                }
            }
            if ($column_name == 'price') {
                $discount=get_post_meta($post_ID,'discount',true);
                //$type_price = get_post_meta($post_ID,'type_price',true);

                $price_adult=get_post_meta($post_ID,'adult_price',true);
                $price_child=get_post_meta($post_ID,'child_price',true);
                if(!empty($discount)){
                    $is_sale_schedule=get_post_meta($post_ID,'is_sale_schedule',true);

                    $sale_adult = $price_adult - $price_adult * ( $discount / 100 );
                    $sale_child = $price_child - $price_child * ( $discount / 100 );
                    if($is_sale_schedule == "on"){
                        $sale_from=get_post_meta($post_ID,'sale_price_from',true);
                        $sale_from = mysql2date('d/m/Y',$sale_from);
                        $sale_to=get_post_meta($post_ID,'sale_price_to',true);
                        $sale_to = mysql2date('d/m/Y',$sale_to);
                        echo '<span> '.__("Adult Price",ST_TEXTDOMAIN).': '.TravelHelper::format_money($price_adult).'</span> <i class="fa fa-arrow-right"></i> <strong>'.TravelHelper::format_money($sale_adult).'</strong><br>';
                        echo '<span>'.__("Child Price",ST_TEXTDOMAIN).': '.TravelHelper::format_money($price_child).'</span> <i class="fa fa-arrow-right"></i> <strong>'.TravelHelper::format_money($sale_child).'</strong><br>';
                        echo '<span>'.__('Discount rate',ST_TEXTDOMAIN).' : '.$discount.'%</span><br>';
                        echo '<span> '.$sale_from.' <i class="fa fa-arrow-right"></i> '.$sale_to.'</span>';
                    }else{
                        echo '<span> '.__("Adult Price",ST_TEXTDOMAIN).': '.TravelHelper::format_money($price_adult).'</span> <i class="fa fa-arrow-right"></i> <strong>'.TravelHelper::format_money($sale_adult).'</strong><br>';
                        echo '<span>'.__("Child Price",ST_TEXTDOMAIN).': '.TravelHelper::format_money($price_child).'</span> <i class="fa fa-arrow-right"></i> <strong>'.TravelHelper::format_money($sale_child).'</strong><br>';
                        echo '<span>'.__('Discount rate',ST_TEXTDOMAIN).' : '.$discount.'%</span><br>';
                    }
                }
                else{
                    echo '<span> '.__("Adult Price",ST_TEXTDOMAIN).': '.TravelHelper::format_money($price_adult).'</span><br>';
                    echo '<span>'.__("Child Price",ST_TEXTDOMAIN).': '.TravelHelper::format_money($price_child).'</span>';
                }
            }
        }
    }
    new STAdminHolidays();
}