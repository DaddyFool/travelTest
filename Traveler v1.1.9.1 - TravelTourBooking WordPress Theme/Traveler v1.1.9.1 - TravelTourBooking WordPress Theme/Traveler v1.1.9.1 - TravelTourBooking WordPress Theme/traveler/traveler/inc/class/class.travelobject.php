<?php

/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Class TravelerObject
 *
 * Created by ShineTheme
 * @update 1.1.1
 *
 */
class TravelerObject
{
    public $min_price;

    protected $post_type = '';
    /**
     * @var string
     * @since 1.1.7
     */
    protected $template_folder="";

    protected $metabox = array();

    protected $orderby = array();


    function init()
    {
        if(!$this->template_folder)
        {
            $this->template_folder=$this->post_type;
        }
        //Add Stats display for posted review
        add_action('admin_init', array($this, 'do_init_metabox'));
        add_action('st_review_stats_' . $this->post_type . '_content', array(
            $this,
            'display_posted_review_stats'
        ));

        /**
         * @since 1.1.7
         */
        add_action('st_wc_show_order_item_meta_' . $this->post_type, array($this, '_wc_order_item_meta'), 10, 3);

        /**
         * @since 1.1.7
         */
        add_filter('st_post_type_'.$this->post_type.'_icon',array($this,'_get_post_type_icon'));

        add_filter('st_get_owner_email_'.$this->post_type,array($this,'get_owner_email'));

    }

    /**
     * @since 1.1.7
     * @param $type
     * @return string
     */
    function _get_post_type_icon($type)
    {
        $type= "fa fa-building-o";

        return $type;
    }
    /**
     * @param $item_id
     * @param $item
     * @param $order
     * @since 1.1.7
     */
    function _wc_order_item_meta($item_id, $item, $order)
    {
        echo $this->load_view('wc_order_item_meta',null,array(
            'item_id'   =>$item_id,
            'item'      =>$item,
            'order'     =>$order
        ));
        echo st()->load_template('woo/cart-meta-deposit',null,array(
            'item_id'   =>$item_id,
            'item'      =>$item,
            'order'     =>$order
        ));

    }

    function is_available()
    {
        return st_check_service_available($this->post_type);
    }

    /**
     *
     * @since 1.1.7
     *
     *
     */
    function load_view($view,$slug=false,$data=array())
    {
        $view=$this->template_folder.'/'.$view;
        return st()->load_template($view,$slug,$data);
    }

    /**
     *
     *
     * @update 1.1.4
     * */
    function  _class_init()
    {


        add_action( 'save_post' , array( $this , 'update_avg_rate' ) );

        add_filter( 'post_class' , array( $this , 'change_post_class' ) );

        add_filter( 'pre_get_posts' , array( $this , '_admin_posts_for_current_author' ) );

        add_action( 'wp_ajax_st_top_ajax_search' , array( $this , '_top_ajax_search' ) );
        add_action( 'wp_ajax_nopriv_st_top_ajax_search' , array( $this , '_top_ajax_search' ) );

        add_action( 'st_single_breadcrumb' , array( $this , 'add_breadcrumb' ) );

    }


    function _add_seach_filter($query)
    {
        if(STInput::get('item_name'))
        {
            $query->set('s',STInput::get('item_name'));
        }
        return $query;
    }

    /**
     *
     *
     * @since 1.1.3
     * */
    function add_breadcrumb( $sep )
    {  
        $bc_show_location_url = st()->get_option( 'bc_show_location_url' , 'on' );
        $location_id          = get_post_meta( get_the_ID() , 'id_location' , true );

        if(!$location_id) {
            $location_id = get_post_meta( get_the_ID() , 'location_id' , true );
        }
        if (!$location_id) {
            $location_string = get_post_meta( get_the_ID() , 'multi_location' , true );
            if(!is_array($location_string)){
                $location_array = explode(",",$location_string);
            }
            elseif(is_array($location_string)){
                $location_array = $location_string;
            }
            $location_id = array();
            if (is_array($location_array) and !empty($location_array)){
                foreach ($location_array as $key => $value) {
                    $var = str_replace("_", "", $value);
                    $location_id[] = $var;
                }
            }
            $location_id = $location_id[0];
            // from 1.1.7 default get first location item child    
        }   

        $array   = array();
        $parents = get_post_ancestors( $location_id );
        if(!empty( $parents ) and is_array( $parents )) {
            for($i=count($parents)-1;$i>=0;$i--){
                $value=$parents[$i];
                $link = get_home_url( '/' );
                if($bc_show_location_url == 'on') {

                    $post_type   = get_post_type();
                    $page_search = st_get_page_search_result( $post_type );

                    if(!empty( $page_search ) and get_post_type($page_search)=='page') {
                        $link = esc_url( add_query_arg( array(
                            'location_id'   => $value ,
                            'location_name' => get_the_title( $value ),
                        ) , get_permalink($page_search) ) );
                    } else {
                        $link = esc_url( add_query_arg( array(
                            'post_type'     => get_post_type() ,
                            's'             => '' ,
                            'location_id'   => $value ,
                            'location_name' => get_the_title( $value )
                        ) , $link ) );
                    }

                } else {
                    $link = get_permalink( $value );
                }
                echo '<li><a href="' . $link . '">' . get_the_title( $value ) . '</a></li>';
            }
        }

        if($location_id) {

            $link = get_home_url( '/' );

            if($bc_show_location_url == 'on') {
                $post_type   = get_post_type();
                $page_search = st_get_page_search_result( $post_type );
                if(!empty( $page_search ) and get_post_type($page_search)=='page') {
                    $link = esc_url( add_query_arg( array(
                        'page_id'       => $page_search ,
                        'location_id'   => $location_id ,
                        'location_name' => get_the_title( $location_id )
                    ) , $link ) );
                } else {
                    $link = esc_url( add_query_arg( array(
                        'post_type'     => get_post_type() ,
                        's'             => '' ,
                        'location_id'   => $location_id ,
                        'location_name' => get_the_title( $location_id )
                    ) , $link ) );
                }

            } else {
                $link = get_permalink( $location_id );
            }
            echo '<li><a href="' . $link . '">' . get_the_title( $location_id ) . '</a></li>';
        }


    }


    function _admin_posts_for_current_author( $query )
    {
        if($query->is_admin) {
            $post_type = $query->get( 'post_type' );

            if(!current_user_can( 'manage_options' ) and ( !is_string( $post_type ) or $post_type != 'location' )) {
                global $user_ID;
                $query->set( 'author' , $user_ID );
            }
        }
        return $query;
    }

    function _top_ajax_search()
    {
        //Small security
        check_ajax_referer( 'st_search_security' , 'security' );
        //$search_header_onof = st()->get_option('search_header_onoff', 'on');
        $search_header_orderby = st()->get_option( 'search_header_orderby' , 'none' );
        $search_header_list    = st()->get_option( 'search_header_list' , 'post' );
        $search_header_order   = st()->get_option( 'search_header_order' , 'ASC' );
        $s                     = STInput::get( 's' );
        $arg                   = array(
            //'post_type'=>array('post','st_hotel','st_rental','location','st_tours','st_cars','st_activity'),
            'post_type'        => $search_header_list ,
            'posts_per_page'   => 10 ,
            's'                => $s ,
            'suppress_filters' => false ,
            'order_by'         => $search_header_orderby ,
            'order'            => $search_header_order
        );

        global $sitepress;

        if(class_exists( 'SitePress' ) and STInput::get( 'lang' )) {
            $sitepress->switch_lang( STInput::get( 'lang' ) );
        }

        $query           = new WP_Query();
        $query->is_admin = false;
        $query->query( $arg );
        $r = array();
        $r['x']=$arg;

        while( $query->have_posts() ) {
            $query->the_post();
            $post_type = get_post_type( get_the_ID() );
            $obj       = get_post_type_object( $post_type );

            $item = array(
                'title' => html_entity_decode(get_the_title()) ,
                'id'    => get_the_ID() ,
                'type'  => $obj->labels->singular_name ,
                'url'   => get_permalink() ,
                'obj'   => $obj
            );

            if($post_type == 'location') {
                $item[ 'url' ] = home_url( esc_url_raw( '?s=&post_type=st_hotel&location_id=' . get_the_ID() ) );
            }

            $r[ 'data' ][ ] = $item;
        }

        wp_reset_query();
        echo json_encode( $r );

        die();
    }

    function change_post_class( $class )
    {
        return $class;
    }

    function update_avg_rate( $post_id )
    {
        $avg = STReview::get_avg_rate( $post_id );
        update_post_meta( $post_id , 'rate_review' , $avg );
    }


    /**
     *
     * $range in kilometer
     *
     *
     * */
    function get_near_by( $post_id = false , $range = 20 , $limit = 5 )
    {
        if(!$post_id)
            $post_id = get_the_ID();
        //if ( false !== ( $value = get_transient( 'st_items_nearby_'.$post_id ) ) )
        //  return $value;

        $map_lat   = (float)get_post_meta( $post_id , 'map_lat' , true );
        $map_lng   = (float)get_post_meta( $post_id , 'map_lng' , true );
        $post_type = get_post_type( $post_id );

        $location_key = 'id_location';
        if($post_type == 'st_rental') {
            $location_key = 'location_id';
        }
        $location_key = apply_filters( 'st_' . $post_type . '_location_id_metakey' , $location_key );

        //$location_id = get_post_meta( $post_id , $location_key , true );

        //Search by Kilometer :6371
        //Miles: 3959
        global $wpdb;
        $where  = " $wpdb->posts.ID = mt1.post_id
            and $wpdb->posts.ID=mt2.post_id
            AND mt1.meta_key = 'map_lat'
            and mt2.meta_key = 'map_lng'
            and $wpdb->posts.ID !=$post_id
            AND $wpdb->posts.post_status = 'publish'
            AND $wpdb->posts.post_type = '{$this->post_type}'
            AND $wpdb->posts.post_date < NOW()" ; 
        $where = TravelHelper::edit_where_wpml($where);
        $join  = "" ; 
        $join = TravelHelper::edit_join_wpml($join , $post_type) ;         
        $querystr = "
            SELECT $wpdb->posts.*,( 6371 * acos( cos( radians({$map_lat}) ) * cos( radians( mt1.meta_value ) ) *
cos( radians( mt2.meta_value ) - radians({$map_lng}) ) + sin( radians({$map_lat}) ) *
sin( radians( mt1.meta_value ) ) ) ) AS distance
            FROM $wpdb->posts {$join} , $wpdb->postmeta as mt1,$wpdb->postmeta as mt2
            WHERE (1=1) and {$where }
            GROUP BY $wpdb->posts.ID HAVING distance<{$range}
            ORDER BY distance ASC
            LIMIT 0,{$limit}
         ";
         //echo $querystr ; 
        $pageposts = $wpdb->get_results( $querystr , OBJECT );
        set_transient( 'st_items_nearby_' . $post_id , $pageposts , 5 * HOUR_IN_SECONDS );
        return $pageposts;

    }
    function get_near_by_lat_lng( $lat = false , $lng = false ,$post_type=array(), $range = 20 , $limit = 5 )
    {
        $map_lat   = (float)$lat;
        $map_lng   = (float)$lng;
        //Search by Kilometer :6371
        //Miles: 3959
        if(!empty($post_type) and is_array($post_type)){
            $data_post_type="";
            foreach($post_type as $k=>$v){
                $data_post_type .= "'".$v."',";
            }
            $data_post_type = substr($data_post_type,0,-1);
            global $wpdb;
            $where = "$wpdb->posts.ID = mt1.post_id
            and $wpdb->posts.ID=mt2.post_id
            AND mt1.meta_key = 'map_lat'
            and mt2.meta_key = 'map_lng'
            AND $wpdb->posts.post_status = 'publish'
            AND $wpdb->posts.post_type IN ({$data_post_type})
            AND $wpdb->posts.post_date < NOW()" ; 
            $where = TravelHelper::edit_where_wpml($where);
            $join  = "" ; 
            $join = TravelHelper::edit_join_wpml($join , $post_type) ;    
            $querystr = "
            SELECT $wpdb->posts.*,( 6371 * acos( cos( radians({$map_lat}) ) * cos( radians( mt1.meta_value ) ) *
cos( radians( mt2.meta_value ) - radians({$map_lng}) ) + sin( radians({$map_lat}) ) *
sin( radians( mt1.meta_value ) ) ) ) AS distance
            FROM $wpdb->posts {$join}, $wpdb->postmeta as mt1,$wpdb->postmeta as mt2
            WHERE (1=1) and {$where}
            GROUP BY $wpdb->posts.ID HAVING distance<{$range}
            ORDER BY distance ASC
            LIMIT 0,{$limit}
         ";
        $pageposts = $wpdb->get_results( $querystr , OBJECT );
        //set_transient( 'st_items_nearby_' . $post_id , $pageposts , 5 * HOUR_IN_SECONDS );
        return $pageposts;
        }
        return false;
    }



    function get_review_stats()
    {
        return array();
    }

    function display_posted_review_stats( $comment_id )
    {

        if(get_post_type() == $this->post_type) {
            $data = $this->get_review_stats();

            $output[ ] = '<ul class="list booking-item-raiting-summary-list mt20">';

            if(!empty( $data ) and is_array( $data )) {
                foreach( $data as $value ) {
                    $key = $value[ 'title' ];

                    $stat_value = get_comment_meta( $comment_id , 'st_stat_' . sanitize_title( $value[ 'title' ] ) , true );

                    $output[ ] = '
                    <li>
                        <div class="booking-item-raiting-list-title">' . $key . '</div>
                        <ul class="icon-group booking-item-rating-stars">';
                    for( $i = 1 ; $i <= 5 ; $i++ ) {
                        $class = '';
                        if($i > $stat_value)
                            $class = 'text-gray';
                        $output[ ] = '<li><i class="fa fa-smile-o ' . $class . '"></i>';
                    }

                    $output[ ] = '
                        </ul>
                    </li>';
                }
            }

            $output[ ] = '</ul>';


            echo implode( "\n" , $output );
        }
    }

    function getOrderby()
    {
        $this->orderby = array(
            'price_asc'  => array(
                'key'  => 'price_asc' ,
                'name' => __( 'Price (low to high)' , ST_TEXTDOMAIN )
            ) ,
            'price_desc' => array(
                'key'  => 'price_desc' ,
                'name' => __( 'Price (high to low)' , ST_TEXTDOMAIN )
            ) ,
            'avg_rate'   => array(
                'key'  => 'avg_rate' ,
                'name' => __( 'Review' , ST_TEXTDOMAIN )
            )
        );

        return $this->orderby;
    }


    public function do_init_metabox()
    {
        $custom_metabox = $this->metabox;
        /**
         * Register our meta boxes using the
         * ot_register_meta_box() function.
         */
        if(function_exists( 'ot_register_meta_box' )) {
            if(!empty( $custom_metabox )) {
                foreach( $custom_metabox as $value ) {
                    ot_register_meta_box( $value );
                }
            }
        }
    }

    //Helper class
    static function get_last_booking_string( $post_id = false )
    {
        if(!$post_id and !is_singular())
            return false;
        global $wpdb;

        $post_id = get_the_ID();
        $where   = '';
        $join    = '';

        $post_type = get_post_type( $post_id );

        switch( $post_type ) {


            default:
                $where .= "and meta_value in (
                    '{$post_id}'
                )";
                break;
        }


        $query = "SELECT * from " . $wpdb->postmeta . "
                {$join}
                where meta_key='item_id'
                {$where}

                order by meta_id desc
                limit 0,1";

        $data = $wpdb->get_results( $query , OBJECT );

        if(!empty( $data )) {
            foreach( $data as $key => $value ) {
                return human_time_diff( get_the_time( 'U' , $value->post_id ) , current_time( 'timestamp' ) );
            }
        }


    }

    static function get_card( $card_name )
    {
        $options = st()->get_option( 'booking_card_accepted' , array() );


        if(!empty( $options )) {
            foreach( $options as $key ) {
                if(sanitize_title_with_dashes( $key[ 'title' ] ) == $card_name)
                    return $key;
            }
        }
    }

    static function get_orgin_booking_id( $item_id )
    {
        if(get_post_type( $item_id ) == 'hotel_room') {
            if($hotel_id = get_post_meta( $item_id , 'room_parent' , true )) {
                $item_id = $hotel_id;
            }
        }
        return apply_filters( 'st_orgin_booking_item_id' , $item_id );
    }

    static function get_min_max_price( $post_type )
    {
        if(empty( $post_type ) || !TravelHelper::checkTableDuplicate($post_type)) {
            return array( 'price_min' => 0 , 'price_max' => 500 );
        }

        $meta_key = 'sale_price';
        if($post_type == 'st_hotel'){
            $meta_key = 'price_avg';
        }
        $location_text = "id_location";
        if ($post_type == 'st_rental') { 
            $location_text = 'location_id';}
        global $wpdb;
        $sql = "
            select 
                min(CAST({$meta_key} as DECIMAL)) as min,
                max(CAST({$meta_key} as DECIMAL)) as max";
        if ($post_type =='st_tours' || $post_type =='st_activity'){            
            /*$sql = "
            select 
                min(CAST(child_price as Decimal)) , 
                min(CAST(adult_price as Decimal)), 
                max(CAST(child_price as Decimal)) , 
                max(CAST(adult_price as Decimal)) 
            " ;*/
            $sql = "
            select 
                min(CAST(adult_price as Decimal)), 
                max(CAST(adult_price as Decimal)) 
            ";                        
        }
        $sql .=" from {$wpdb->prefix}{$post_type} " ; 
        $join  =  "" ; $where = "" ; 
        $join .= " join {$wpdb->posts} on {$wpdb->posts}.ID = {$wpdb->prefix}{$post_type}.post_id " ; 
        $join  = STLocation::edit_join_wpml($join,  $post_type) ; 
        $sql  .= $join  ; 
        $where = " where (1=1 ) " ; 
        $where .= "and 
                (
                    {$wpdb->posts}.post_status = 'publish'
                )
                    " ; 
        $where = STLocation::edit_where_wpml($where);
        $sql.= $where ; 
        //echo $sql;
        $results = $wpdb->get_results($sql, OBJECT);
        $array_price = array();
        if ($post_type =='st_tours' || $post_type =='st_activity'){     
            foreach ($results[0] as $key => $value) {
                 $array_price[] = $value;
             }                           
            $price_min = min($array_price);
            $price_max = max($array_price);     
              
            
        }else {

            $price_min = $results[0]->min;
            $price_max = $results[0]->max;
        }
        if (!$price_max) {$price_max = 500  ;} // default 0 500
        return array( 'price_min' => ceil( $price_min ) , 'price_max' => ceil( $price_max ) );
    }
    static function get_list_location(){
        // session have started in  travel-helper by Hai nt
        if(isset($_SESSION['list_location'])){
            return $_SESSION['list_location']  ; 
        }
    }

    static function get_search_result_link( $post_type = false )
    {
        $url = home_url( '/' );
        return apply_filters( 'st_' . $post_type . '_search_result_link' , $url );
    }

    


    static function st_get_custom_price_by_date( $post_id , $start_date = null , $price_type = 'default' )
    {
        global $wpdb;
        if(!$post_id)
            $post_id = get_the_ID();
        if(empty( $start_date ))
            $start_date = date( "Y-m-d" );
        $rs = $wpdb->get_results( "SELECT * FROM " . $wpdb->base_prefix . "st_price WHERE post_id=" . $post_id . " AND price_type='" . $price_type . "'  AND start_date <='" . $start_date . "' AND end_date >='" . $start_date . "' AND status=1 ORDER BY priority DESC LIMIT 1" );
        if(!empty( $rs )) {
            return $rs[ 0 ]->price;
        } else {
            return false;
        }
    }

    static function st_conver_info_price( $info_price )
    {
        $list_info_price = '';
        if(!empty( $info_price )) {
            $start = '';
            $end   = '';
            $price = "";
            foreach( $info_price as $k => $v ) {
                if(empty( $price )) {
                    $start                     = $v[ 'start' ];
                    $end                       = $v[ 'end' ];
                    $price                     = $v[ 'price' ];
                    $list_info_price[ $start ] = array(
                        'start' => $start ,
                        'end'   => $end ,
                        'price' => $price ,
                    );
                }
                if($price == $v[ 'price' ]) {
                    $end                       = $v[ 'end' ];
                    $list_info_price[ $start ] = array(
                        'start' => $start ,
                        'end'   => $end ,
                        'price' => $price ,
                    );
                }
                if($price != $v[ 'price' ]) {
                    $start = $v[ 'end' ];
                    $end   = $v[ 'end' ];
                    $price = $v[ 'price' ];

                    $list_info_price[ $start ] = array(
                        'start' => $start ,
                        'end'   => $end ,
                        'price' => $price ,
                    );

                }
            }
        }
        return $list_info_price;
    }

    /**
     * @since 1.1.1
     *
     *
     */
    static function get_book_btn()
    {
        $class_room_btn = "";
        if(get_post_type( get_the_ID() ) == "hotel_room") {
            $class_room_btn = "btn_hotel_booking";
        };
        ob_start();
        ?>
        <input type="submit" class=" btn btn-primary <?php echo esc_attr( $class_room_btn ); ?>"
               value="<?php st_the_language( 'book_now' ) ?>">
        <?php
        $book_now_btn = ob_get_clean();
        return $book_now_btn;
    }

    /**
     *
     *
     * @since 1.1.1
     * @param int $room_id of booking item
     * @return string type of reposit of booking item
     * */
    public function get_deposit_type( $booking_id = null )
    {
        if(!$booking_id) {
            $booking_id = get_the_ID();
        }

        return get_post_meta( $booking_id , 'deposit_payment_status' , true );

    }

    /**
     *
     *
     * @since 1.1.1
     * */
    public function get_deposit_amount( $booking_id = null )
    {
        if(!$booking_id) {
            $booking_id = get_the_ID();
        }

        return get_post_meta( $booking_id , 'deposit_payment_amount' , true );

    }

    /**
     *
     *
     * @since 1.1.1
     * @update 1.1.2
     * */
    public function get_deposit_money_amount( $room_money , $booking_id = false )
    {
        if($deposit_type = $this->get_deposit_type( $booking_id ) and $room_money) {
            $deposit_amount = $this->get_deposit_amount( $booking_id );

            if($deposit_amount) {
                switch( $deposit_type ) {
                    case "percent":
                        $room_money = ( $room_money / 100 ) * $deposit_amount;
                        break;

                    case 'amount':
                        $room_money = $deposit_amount;
                        break;

                }
            }

        }

        return $room_money;
    }

    /**
     *
     *
     * @since 1.1.1
     * */
    function _deposit_calculator( $cart_data , $item_id ){
        if($this->get_deposit_type( $item_id ) and $this->get_deposit_amount( $item_id )) {

            $old_price            = $cart_data[ 'price' ];

            $cart_data[ 'price' ] = $old_price;

            $cart_data[ 'data' ][ 'deposit_money' ] = array(
                'type'      => $this->get_deposit_type( $item_id ) ,
                'old_price' => $old_price ,
                'amount'    => $this->get_deposit_amount( $item_id )
            );
        }

        return $cart_data;
    }

    /**
     *
     *
     * @since 1.1.5
     * */
    static function _get_location_by_name( $location_name )
    {
        if(empty( $location_name ))
            return $location_name;

        $ids = array();
        global $wpdb; //OR (" . $wpdb->posts . ".post_content LIKE '%" . $location_name . "%')
        $query = "SELECT SQL_CALC_FOUND_ROWS  " . $wpdb->posts . ".ID
                    FROM " . $wpdb->posts . "
                    WHERE 1=1
                    AND (((" . $wpdb->posts . ".post_title LIKE '%" . $location_name . "%') ))
                    AND " . $wpdb->posts . ".post_type = 'location'
                    AND ((" . $wpdb->posts . ".post_status = 'publish' OR " . $wpdb->posts . ".post_status = 'pending'))
                    ORDER BY " . $wpdb->posts . ".post_title LIKE '%" . $location_name . "%' DESC, " . $wpdb->posts . ".post_date DESC LIMIT 0, 10";
        $data  = $wpdb->get_results( $query , OBJECT );
        if(!empty( $data )) {
            foreach( $data as $k => $v ) {
                $ids[ ] = $v->ID;
            }
        }
        return $ids;
    }
    /**
    * get current term by post id 
    *
    *
    */
    static function get_term_list_by_id($post_id = null){
        if(!$post_id){
            $post_id = get_the_ID(); 
        }
        $list_taxonomy = st_list_taxonomy( 'st_tours' );
        $array = array();
        if(!empty($list_taxonomy) and is_array($list_taxonomy)){
            foreach ($list_taxonomy as $key => $value) {
                $array[$value] = (wp_get_post_terms( $post_id, $value, array()));
            }
            
        }
        return $array; 
    }
    // from 1.1.9
    function _show_wc_cart_item_information_btn( $cart_item_key = array() )
    {            
        //print balancetags("<br><p class ='btn btn-primary' data-toggle='collapse' data-target='#st_cart_item".md5(json_encode($cart_item_key))."'>".__("Details" , ST_TEXTDOMAIN)."</p>");
        print balancetags('<br><span data-hide = "'.__("Less" , ST_TEXTDOMAIN).' <i class=&quot;fa fa-angle-up&quot;>" data-target= "#st_cart_item'.md5(json_encode($cart_item_key)).'" data-toggle="collapse" class="_show_wc_cart_item_information_btn text-color booking-item-review-expand-more">'.__("More", ST_TEXTDOMAIN).' <i class="fa fa-angle-down"></i></span>');
    }
}

$a = new TravelerObject();
$a->_class_init();
