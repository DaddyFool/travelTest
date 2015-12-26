<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Class STRoom
 *
 * Created by ShineTheme
 *
 */
if(!class_exists('STRoom'))
{
    class STRoom extends TravelerObject
    {

        function init()
        {
            if(!st_check_service_available('st_hotel')) return;

            parent::init();

            //add_filter('st_data_custom_price',array($this,'_st_data_custom_price'));

        }
        function _st_data_custom_price(){
            return array('title'=>__('Price Custom Settings',ST_TEXTDOMAIN),'post_type'=>'hotel_room');
        }

        function _alter_search_query($where)
        {
            if(is_admin()) return $where;
            global $wp_query,$wpdb;
            $post_type='';
            if(isset($wp_query->query_vars['post_type']) and is_string($wp_query->query_vars['post_type']))
            {
                $post_type= $wp_query->query_vars['post_type'];

            }

            if($post_type=='hotel_room')
            {
                // Check Woocommerce Booking
                $st_is_woocommerce_checkout=apply_filters('st_is_woocommerce_checkout',false);

                if(STInput::request('start') and STInput::request('end'))
                {

                    $check_in=strtotime(STInput::request('start'));
                    $check_out=strtotime(STInput::request('end'));

//                    $where_add=" AND {$wpdb->posts}.ID NOT IN (SELECT room_id FROM (
//                                SELECT count(st_meta6.meta_value) as total,
//                                    st_meta5.meta_value as total_room,st_meta6.meta_value as room_id ,st_meta2.meta_value as check_in,st_meta3.meta_value as check_out
//                                     FROM {$wpdb->posts}
//                                            JOIN {$wpdb->postmeta}  as st_meta2 on st_meta2.post_id={$wpdb->posts}.ID and st_meta2.meta_key='check_in'
//                                            JOIN {$wpdb->postmeta}  as st_meta3 on st_meta3.post_id={$wpdb->posts}.ID and st_meta3.meta_key='check_out'
//                                            JOIN {$wpdb->postmeta}  as st_meta6 on st_meta6.post_id={$wpdb->posts}.ID and st_meta6.meta_key='room_id'
//                                            JOIN {$wpdb->postmeta}  as st_meta5 on st_meta5.post_id=st_meta6.meta_value and st_meta5.meta_key='number_room'
//                                            WHERE {$wpdb->posts}.post_type='st_order'
//                                    GROUP BY st_meta6.meta_value HAVING total>=total_room AND (
//
//                                                ( CAST(st_meta2.meta_value AS DATE)<'{$check_in}' AND  CAST(st_meta3.meta_value AS DATE)>'{$check_in}' )
//                                                OR ( CAST(st_meta2.meta_value AS DATE)>='{$check_in}' AND  CAST(st_meta2.meta_value AS DATE)<='{$check_out}' )
//
//                                    )
//                            ) as room_booked)";
                    $table_name = $wpdb->prefix.'st_order_item_meta';

                    if($st_is_woocommerce_checkout){
                        $where_type=" AND type='woocommerce'";
                    }else{

                        $where_type=" AND type='normal_booking'";
                    }

                    $where_add=" AND {$wpdb->posts}.ID NOT IN (SELECT room_id FROM (
                                        SELECT
                                            room_id,SUM(room_num_search) as total_booked_number,{$wpdb->postmeta}.meta_value
                                        FROM
                                            {$table_name}
                                        JOIN {$wpdb->postmeta} ON {$wpdb->postmeta}.post_id = {$table_name}.room_id and {$wpdb->postmeta}.meta_key='number_room'
                                        AND st_booking_post_type = 'st_hotel'
                                        WHERE (check_in_timestamp<{$check_in} AND check_out_timestamp>{$check_in}) OR
                                        (check_in_timestamp>={$check_in} AND check_in_timestamp<={$check_out})
                                        {$where_type}
                                        GROUP BY room_id
                                         HAVING total_booked_number>={$wpdb->postmeta}.meta_value

                            ) as room_booked2 )";

                    // Woocommerce Booking
                    if($st_is_woocommerce_checkout)
                    {
                        // Woocommerce check Query
                        $where_add="";
                    }
                    $where.=$where_add;
                }
            }

            return $where;
        }

        static function get_room_price($room_id = false, $start_date, $end_date){           
            
            if(!$room_id) $room_id = get_the_ID();
            $list_price=array();

            $price = 0;
            $number_days=0;
            if($start_date and $end_date){
                $one_day = (60 * 60 * 24);
                $str_start_date = strtotime($start_date);
                $str_end_date = strtotime($end_date);
                $number_days = ( $str_end_date - $str_start_date )  /  $one_day;

                $total = 0;
                for($i=1;$i<=$number_days;$i++){
                    $data_date = date("Y-m-d",$str_start_date + ($one_day * $i) );
                    $date_tmp = date("Y-m-d",strtotime($data_date) - ($one_day) );
                    $data_price=get_post_meta($room_id,'price',true);
                    $price_custom = TravelerObject::st_get_custom_price_by_date($room_id , $data_date);
                    if($price_custom)$data_price = $price_custom;
                    $list_price[$data_date]= array(
                        'start'=>$date_tmp,
                        'end'=>$data_date,
                        'price'=>apply_filters('st_apply_tax_amount',$data_price)
                    );
                    $total += $data_price;
                }
                $price = $total;
            }


            /** get custom price by date **/


            /** get custom price by date **/

            $data_price = array(
                'discount'=>false,
                'price'=>apply_filters('st_apply_tax_amount',$price),
                'info_price'=>$list_price,
                'number_day'=>$number_days,
            );

            if($price>0){
                $discount_rate=get_post_meta($room_id,'discount_rate',true);
                $is_sale_schedule=get_post_meta($room_id,'is_sale_schedule',true);

                if($is_sale_schedule=='on')
                {
                    $sale_from = get_post_meta($room_id,'sale_price_from',true);
                    $sale_to = get_post_meta($room_id,'sale_price_to',true);

                    $str_sale_from   = strtotime($sale_from) ; 
                    $str_sale_to = strtotime($sale_to);

                    //$str_start_date
                    // discount = 0 
                    if (
                        ($str_sale_from and $str_start_date <$str_sale_from)
                        or ($str_sale_to and $str_start_date >$str_sale_to)   
                        or ($str_sale_to and $str_sale_from and $str_sale_from<$str_sale_to and $str_start_date <$str_sale_from and $str_start_date >$str_sale_to ) 
                        or ($str_sale_to and $str_sale_from and $str_sale_from>$str_sale_to)                    
                        ){
                        $discount_rate = 0; 
                    }
                }

                if($discount_rate>100){
                    $discount_rate=100;
                }
                
                if($discount_rate){
                    $data_price = array(
                        'discount'=>true,
                        'price'=>apply_filters('st_apply_tax_amount',$price - ($price/100)*$discount_rate),
                        'price_old'=>apply_filters('st_apply_tax_amount',$price),
                        'info_price'=>$list_price,
                        'number_day'=>$number_days,
                    );
                }
                
            }
            
            return $data_price;
        }
        public static function get_external_url($post_id = null){
            if (!$post_id) $post_id =get_the_ID();
            $is_external = get_post_meta($post_id, 'st_room_external_booking' , "off");
            $link = get_post_meta($post_id , 'st_room_external_booking_link' , true) ; 
            if ($is_external == 'on' and $link ){
                return $link;
            }
            return false ; 
        }
    }

    st()->hotel_room=new STRoom();
    st()->hotel_room->init();
}