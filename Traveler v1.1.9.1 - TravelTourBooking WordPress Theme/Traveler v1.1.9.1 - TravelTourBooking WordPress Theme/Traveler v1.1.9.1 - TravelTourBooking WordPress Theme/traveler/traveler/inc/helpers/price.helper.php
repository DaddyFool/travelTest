<?php
if(!class_exists('STPrice')){
    class STPrice{
        public function __construct(){

        }
        static function checkIncludeTax(){
            if(st()->get_option('tax_enable','off') == 'on' && st()->get_option('st_tax_include_enable', 'off') == 'off'){
                return false;
            }
            if(st()->get_option('tax_enable','off') == 'on' && st()->get_option('st_tax_include_enable', 'off') == 'on'){
                return true;
            }
            if(st()->get_option('tax_enable','off') == 'off'){
                return false;
            }
        }
        static function checkSale($post_id = ''){
            $post_id = intval($post_id);
            $is_sale_schedule = get_post_meta($post_id, 'is_sale_schedule', true);
            if($is_sale_schedule == false || empty($is_sale_schedule)) $is_sale_schedule = 'off';
            if($is_sale_schedule == 'on')
                return true;
            return false;
        }
        static function getTax(){
            if(st()->get_option('tax_enable','off') == 'on'){
                $tax = floatval(st()->get_option('tax_value',0));
                if($tax <= 0) $tax = 0;
                if($tax > 100) $tax = 100;
                return $tax;
            }
            return 0;
        }
        /**
         *@since 1.1.8
         *@param $price float
         **/
        static function getPriceWithTax($price = 0, $tax = false){
            $price = floatval($price);
            if($price < 0) $price = 0;
            if(!$tax){
                $tax = 0;
                if(st()->get_option('tax_enable','off') == 'on' && st()->get_option('st_tax_include_enable', 'off') == 'off'){

                    $tax = floatval(st()->get_option('tax_value',0));
                }
            }
            $price = $price + ($price / 100) * $tax;

            return $price;
        }

        /**
         *@since 1.1.8
         *	Only use for activity
         **/
        static function getPriceByPeople($activity_id = '', $check_in = '', $check_out = '', $adult_number = 0, $child_number = 0, $infant_number = 0){
            $total_price = 0;

            $activity_id = intval($activity_id);

            $groupday = STPrice::getGroupDay($check_in, $check_out);

            $adult_price = floatval(get_post_meta($activity_id, 'adult_price', true));
            if($adult_price < 0) $adult_price = 0;

            $child_price = floatval(get_post_meta($activity_id, 'child_price', true));
            if($child_price < 0) $child_price = 0;

            $infant_price = floatval(get_post_meta($activity_id, 'infant_price', true));
            if($infant_price < 0) $infant_price = 0;

            $discount_by_adult = get_post_meta($activity_id, 'discount_by_adult', true);
            $discount_by_child = get_post_meta($activity_id, 'discount_by_child', true);

            $total_adult_price = 0;
            if(is_array($discount_by_adult) && count($discount_by_adult)){
                $discount_by_adult = self::sortPricePeople($discount_by_adult);
                $people_ori = 0;
                foreach($discount_by_adult as $key => $val){
                    $people = intval($val['key']);
                    $price = floatval($val['value']);
                    while($adult_number - $people >= 0 && $people_ori != $people){
                        $adult_number -= $people;
                        $total_adult_price += ($adult_price - ($adult_price * ($price / 100))) * $people;
                    }
                    $people_ori = $people;
                }
            }
            if($adult_number > 0){
                for($i = 1; $i <= $adult_number; $i++)
                    $total_adult_price += $adult_price;
            }

            $total_child_price = 0;
            if(is_array($discount_by_child) && count($discount_by_child)){
                $discount_by_child = self::sortPricePeople($discount_by_child);
                $people_ori = 0;
                foreach($discount_by_child as $key => $val){
                    $people = intval($val['key']);
                    $price = floatval($val['value']);
                    while($child_number - $people >= 0 && $people_ori != $people){
                        $child_number -= $people;
                        $total_child_price += ($child_price - ($child_price * ($price / 100))) * $people;
                    }
                    $people_ori = $people;
                }
            }

            if($child_number > 0){
                for($i = 1; $i <= $child_number; $i++)
                    $total_child_price += $child_price;
            }
            $total_price = $total_adult_price + $total_child_price + ($infant_number * $infant_price);
            $data = array(
                'adult_price' => $total_adult_price,
                'child_price' => $total_child_price,
                'infant_price' => ($infant_number * $infant_price),
                'total_price' => $total_price
            );
            return $data;

        }
        /**
         *@since 1.1.9
         * use for tour
         **/
        static function getPeoplePrice($tour_id, $check_in, $check_out){
            global $wpdb;
            $data_price = array(
                'adult_price' => 0,
                'child_price' => 0,
                'infant_price' => 0
            );
            $type_tour = get_post_meta($tour_id, 'type_tour', true);

            $sql = "
				SELECT
					`adult_price`,
					`child_price`,
					`infant_price`,
					`status`
				FROM
					{$wpdb->prefix}st_availability
				WHERE
					post_id = '{$tour_id}'
				AND (
					UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME('{$check_in}'), '%Y-%m-%d')) = UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME(check_in), '%Y-%m-%d'))
					AND UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME('{$check_out}'), '%Y-%m-%d')) = UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME(check_out), '%Y-%m-%d'))
				)";

            $data = $wpdb->get_row($sql, ARRAY_A);
            if(is_array($data) && count($data)){
                if($data['status'] == 'available'){
                    $data_price['adult_price'] = $data['adult_price'];
                    $data_price['child_price'] = $data['child_price'];
                    $data_price['infant_price'] = $data['infant_price'];
                }
            }else{
                if($type_tour == 'daily_tour'){
                    $data_price['adult_price'] = floatval(get_post_meta($tour_id, 'adult_price', true));
                    $data_price['child_price'] = floatval(get_post_meta($tour_id, 'child_price', true));
                    $data_price['infant_price'] = floatval(get_post_meta($tour_id, 'infant_price', true));
                }
            }

            return $data_price;
        }
        static function getPriceByPeopleTour($tour_id = '', $check_in = '', $check_out = '', $adult_number = 0, $child_number = 0, $infant_number = 0){
            $total_price = 0;

            $tour_id = intval($tour_id);

            $people_price = self::getPeoplePrice($tour_id, $check_in, $check_out);

            $adult_price = $people_price['adult_price'];
            $child_price = $people_price['child_price'];
            $infant_price = $people_price['infant_price'];

            $groupday = STPrice::getGroupDay($check_in, $check_out);

            $discount_by_adult = get_post_meta($tour_id, 'discount_by_adult', true);
            $discount_by_child = get_post_meta($tour_id, 'discount_by_child', true);

            $total_adult_price = 0;
            if(is_array($discount_by_adult) && count($discount_by_adult)){
                $discount_by_adult = self::sortPricePeople($discount_by_adult);
                $people_ori = 0;
                foreach($discount_by_adult as $key => $val){
                    $people = intval($val['key']);
                    $price = floatval($val['value']);
                    while($adult_number - $people >= 0 && $people_ori != $people){
                        $adult_number -= $people;
                        $total_adult_price += ($adult_price - ($adult_price * ($price / 100))) * $people;
                    }
                    $people_ori = $people;
                }
            }
            if($adult_number > 0){
                for($i = 1; $i <= $adult_number; $i++)
                    $total_adult_price += $adult_price;
            }

            $total_child_price = 0;
            if(is_array($discount_by_child) && count($discount_by_child)){
                $discount_by_child = self::sortPricePeople($discount_by_child);
                $people_ori = 0;
                foreach($discount_by_child as $key => $val){
                    $people = intval($val['key']);
                    $price = floatval($val['value']);
                    while($child_number - $people >= 0 && $people_ori != $people){
                        $child_number -= $people;
                        $total_child_price += ($child_price - ($child_price * ($price / 100))) * $people;
                    }
                    $people_ori = $people;
                }
            }

            if($child_number > 0){
                for($i = 1; $i <= $child_number; $i++)
                    $total_child_price += $child_price;
            }
            $total_price = $total_adult_price + $total_child_price + ($infant_number * $infant_price);
            $data = array(
                'adult_price' => $total_adult_price,
                'child_price' => $total_child_price,
                'infant_price' => ($infant_number * $infant_price),
                'total_price' => $total_price
            );
            return $data;

        }
        static function sortPricePeople($data = array()){
            if(count($data)){
                for($i = 0; $i < count($data) - 1; $i++)
                    for($j = $i+1; $j < count($data); $j++)
                        if(intval($data[$i]['key']) < intval($data[$j]['key'])){
                            $tg = $data[$i];
                            $data[$i] = $data[$j];
                            $data[$j] = $tg;
                        }
            }

            return $data;
        }

        static function getSaleActivitySalePrice($post_id = '', $price = '', $tour_type = '', $check_in = ''){
            $total_price = 0;

            $price = floatval($price);
            if($tour_type == 'daily_tour' || $tour_type == 'daily_activity'){
                $check_in = $check_in;
            }else{
                $check_in = strtotime(date('Y-m-d'));
            }

            $discount_rate = floatval(get_post_meta($post_id,'discount',true));
            if($discount_rate < 0) $discount_rate = 0;
            if($discount_rate > 100) $discount_rate = 100;
            $is_sale_schedule = get_post_meta($post_id, 'is_sale_schedule', true);
            if($is_sale_schedule == false || empty($is_sale_schedule)) $is_sale_schedule = 'off';
            if($is_sale_schedule == 'on'){
                $sale_from = intval(strtotime(get_post_meta($post_id, 'sale_price_from',true)));
                $sale_to = intval(strtotime(get_post_meta($post_id, 'sale_price_to',true)));

                if($sale_from > 0 && $sale_to > 0 && $sale_from < $sale_to){
                    if($check_in >= $sale_from && $check_in <= $sale_to){
                        $total_price = $price - ($price * ($discount_rate / 100));
                    }else{
                        $total_price = $price;
                    }
                }
            }else{
                $total_price = $price - ($price * ($discount_rate / 100));
            }
            return $total_price;
        }
        static function get_discount_rate($post_id = '', $check_in = ''){

            $post_type = get_post_type($post_id);
            $discount_text = 'discount' ;
            if($post_type =='st_hotel' or $post_type =='st_rental') $discount_text = 'discount_rate';

            $discount_rate = floatval(get_post_meta($post_id,$discount_text,true));
            if($discount_rate < 0) $discount_rate = 0;
            if($discount_rate > 100) $discount_rate = 100;
            $is_sale_schedule = get_post_meta($post_id, 'is_sale_schedule', true);
            if($is_sale_schedule == false || empty($is_sale_schedule)) $is_sale_schedule = 'off';
            if($is_sale_schedule == 'on'){
                $sale_from = intval(strtotime(get_post_meta($post_id, 'sale_price_from',true)));
                $sale_to = intval(strtotime(get_post_meta($post_id, 'sale_price_to',true)));
                if($sale_from > 0 && $sale_to > 0 && $sale_from < $sale_to){
                    if($check_in >= $sale_from && $check_in <= $sale_to){
                        return $discount_rate ;
                    }else {
                        return 0 ;
                    }
                }
            }else{
                return $discount_rate;
            }
        }
        static function getSaleTourSalePrice($post_id = '', $price = '', $tour_type = '', $check_in = ''){
            $total_price = 0;

            $price = floatval($price);

            $discount_rate = floatval(get_post_meta($post_id,'discount',true));
            if($discount_rate < 0) $discount_rate = 0;
            if($discount_rate > 100) $discount_rate = 100;
            $is_sale_schedule = get_post_meta($post_id, 'is_sale_schedule', true);
            if($is_sale_schedule == false || empty($is_sale_schedule)) $is_sale_schedule = 'off';
            if($is_sale_schedule == 'on'){
                $sale_from = intval(strtotime(get_post_meta($post_id, 'sale_price_from',true)));
                $sale_to = intval(strtotime(get_post_meta($post_id, 'sale_price_to',true)));

                if($sale_from > 0 && $sale_to > 0 && $sale_from < $sale_to){
                    if($check_in >= $sale_from && $check_in <= $sale_to){
                        $total_price = $price - ($price * ($discount_rate / 100));
                    }else{
                        $total_price = $price;
                    }
                }
            }else{
                $total_price = $price - ($price * ($discount_rate / 100));
            }
            return $total_price;
        }

        static function getSaleCarPrice($post_id = '', $price = '', $check_in = '', $check_out = ''){
            $post_id = intval($post_id);
            $is_custom_price = get_post_meta($post_id,'is_custom_price',true);
            if(empty($is_custom_price))$is_custom_price = 'price_by_number';
            ///////////////////////////////////////
            /////////// Price By Number ///////////
            ///////////////////////////////////////
            if($is_custom_price == 'price_by_number'){
                if(!empty($check_in) and !empty($check_out)){
                    $price = self::get_car_price_by_number_of_day_or_hour($post_id,$price,$check_in,$check_out);
                }
                // price discount
                $total_price = 0;
                if(get_post_type($post_id) == 'st_cars'){
                    $discount_rate = floatval(get_post_meta($post_id,'discount',true));
                    if($discount_rate < 0) $discount_rate = 0;
                    if($discount_rate > 100) $discount_rate = 100;

                    $is_sale_schedule = get_post_meta($post_id, 'is_sale_schedule', true);
                    if($is_sale_schedule == false || empty($is_sale_schedule)) $is_sale_schedule = 'off';

                    $groupday = self::getGroupDayCar($check_in, $check_out);
                    $unit = st()->get_option('cars_price_unit', 'day');
                    $numberday = STCars::get_date_diff( $check_in , $check_out , $unit);

                    if($is_sale_schedule == 'on'){
                        $sale_from = intval(strtotime(get_post_meta($post_id, 'sale_price_from',true)));
                        $sale_to = intval(strtotime(get_post_meta($post_id, 'sale_price_to',true)));
                        $today=strtotime('today');

                        if($sale_from > 0 && $sale_to > 0 && $sale_from < $sale_to){

                            foreach($groupday as $key => $date){
                                if(self::checkBetween($date, $sale_from, $sale_to)){
                                    $total_price += $price - ($price * ($discount_rate / 100));
                                }else{

                                    $total_price += $price;
                                }
                            }


                        }else
                        {
                            $total_price = ($price) * $numberday;
                        }
                    }else{

                        $total_price = ($price - ($price * ($discount_rate / 100))) * $numberday;
                    }
                }
                return $total_price;
            }
            ///////////////////////////////////////
            /////////// Price By Date /////////////
            ///////////////////////////////////////
            if($is_custom_price = 'price_by_date'){
                $total = 0;
                if($check_in and $check_out){
                    $unit = st()->get_option('cars_price_unit', 'day');
                    if($unit == 'day'){
                        $one_day = (60 * 60 * 24);
                    }elseif($unit == 'hour'){
                        $one_day = (60 * 60 );
                    }

                    $number_days = STCars::get_date_diff($check_in,$check_out);
                    for($i=1;$i<=$number_days;$i++){
                        $data_date = date("Y-m-d",$check_in + ($one_day * $i) - $one_day );
                        //$tmp = date("Y-m-d H:i:s",$check_in + ($one_day * $i) - $one_day );
                        $price_tmp = TravelerObject::st_get_custom_price_by_date($post_id , $data_date);
                        if(empty($price_tmp)){
                            $price_tmp = $price;
                        }
                        $is_sale = self::_check_car_sale_schedule_by_date($post_id,$data_date);
                        if(!empty($is_sale)){
                            $price_tmp = $price_tmp - ($price_tmp * ($is_sale / 100));
                        }
                        $total += $price_tmp;
                    }
                }
                return $total;
            }
        }
        static  function _check_car_sale_schedule_by_date($post_id,$date){
            $discount         = get_post_meta( $post_id , 'discount' , true );
            $is_sale_schedule = get_post_meta( $post_id , 'is_sale_schedule' , true );
            if($is_sale_schedule == 'on') {
                $sale_from = get_post_meta( $post_id , 'sale_price_from' , true );
                $sale_to   = get_post_meta( $post_id , 'sale_price_to' , true );
                if($sale_from and $sale_from) {
                    $today     = $date;
                    $sale_from = date( 'Y-m-d' , strtotime( $sale_from ) );
                    $sale_to   = date( 'Y-m-d' , strtotime( $sale_to ) );
                    if(( $today >= $sale_from ) && ( $today <= $sale_to )) {
                        //$discount         = get_post_meta( $post_id , 'discount' , true );
                    } else {
                        $discount = 0;
                    }
                } else {
                    $discount = 0;
                }
            }
            return $discount;
        }
        static function get_car_price_by_number_of_day_or_hour( $post_id , $price , $date_start = false , $date_end=false   ){
            $date_driff = STCars::get_date_diff($date_start,$date_end);
            if(!$post_id)$post_id = get_the_ID();
            $price_by_number_of_day_hour = get_post_meta($post_id , 'price_by_number_of_day_hour',true);
            if(!empty($price_by_number_of_day_hour) and is_array($price_by_number_of_day_hour)){
                foreach($price_by_number_of_day_hour as $k=>$v){
                    if( $date_driff >= $v['number_start'] and  $date_driff <= $v['number_end'] ){
                        $price = $v['price'];
                    }
                }
            }
            return $price;
        }
        static function convert_array_date_custom_price_by_date( $list_price ){
            if(empty($list_price)) return false;
            $array_list = array();
            $price_tmp=0;
            $key=0;
            foreach($list_price as $k=>$v){
                $date_start = $v['start'];
                $date_end = $v['end'];
                $price = $v['price'];
                if($price_tmp != $price){
                    $array_list[$key] = array(
                        'start' => $date_start,
                        'end' => $date_end,
                        'price' => $price,
                    );
                    $price_tmp = $price;
                    $key++;
                }
                if($price_tmp == $price){
                    if(!empty($array_list[$key-1]['end'])){
                        $array_list[$key-1]['end'] = $date_end;
                    }
                }
            }
            return $array_list;
        }
        static function getSale($post_id, $check_in, $check_out){
            $sale = false;
            $groupday = STPrice::getGroupDay($check_in, $check_out);
            $discount_rate = floatval(get_post_meta($post_id,'discount',true));
            if($discount_rate < 0) $discount_rate = 0;
            if($discount_rate > 100) $discount_rate = 100;

            $is_sale_schedule = get_post_meta($post_id, 'is_sale_schedule', true);
            if($is_sale_schedule == false || empty($is_sale_schedule)) $is_sale_schedule = 'off';
            if($is_sale_schedule == 'on'){
                $sale_from = intval(strtotime(get_post_meta($post_id, 'sale_price_from',true)));
                $sale_to = intval(strtotime(get_post_meta($post_id, 'sale_price_to',true)));
                if($sale_from > 0 && $sale_to > 0 && $sale_from < $sale_to){
                    foreach($groupday as $key => $date){
                        if(self::checkBetween($date, $sale_from, $sale_to)){
                            $sale = $discount_rate;
                        }
                    }
                }
            }else{
                $sale = $discount_rate;
            }

            return $sale;
        }
        /**
         *@since 1.1.8
         *	Not use for hotel room. See 'getRoomPrice' function
         **/
        static function getSalePrice($post_id = '', $price = 0, $check_in = '', $check_out = ''){

            $total_price = 0;

            $price = floatval($price);

            $numberday = TravelHelper::dateDiff(date('Y-m-d', $check_in), date('Y-m-d', $check_out));
            $groupday = STPrice::getGroupDay($check_in, $check_out);

            $discount_rate = floatval(get_post_meta($post_id,'discount_rate',true));
            if($discount_rate < 0) $discount_rate = 0;
            if($discount_rate > 100) $discount_rate = 100;

            $is_sale_schedule = get_post_meta($post_id, 'is_sale_schedule', true);
            if($is_sale_schedule == false || empty($is_sale_schedule)) $is_sale_schedule = 'off';
            if($is_sale_schedule == 'on'){
                $sale_from = intval(strtotime(get_post_meta($post_id, 'sale_price_from',true)));
                $sale_to = intval(strtotime(get_post_meta($post_id, 'sale_price_to',true)));
                if($sale_from > 0 && $sale_to > 0 && $sale_from < $sale_to){
                    foreach($groupday as $key => $date){
                        if(self::checkBetween($date, $sale_from, $sale_to)){

                            $total_price += $price - ($price * ($discount_rate / 100));
                        }else{

                            $total_price += $price;
                        }
                    }
                }
            }else{
                $total_price = ($price - ($price * ($discount_rate / 100)));
            }

            return $total_price;
        }

        static function getPriceEuipmentCar($data = array(),$check_in_timestamp, $check_out_timestamp){
            $total_price = 0;
            if(is_array($data) && count($data)){
                foreach($data as $key => $val){
                    if(is_object($val)){
                        $price = floatval($val->price);
                        if($price < 0) $price = 0;
                        $time_number=STCars::get_date_diff($check_in_timestamp,$check_out_timestamp,$val->price_unit);
                        if(!empty($val->price_unit)){
                            $price = $price*$time_number;
                        }
                        if($price > $val->price_max and $val->price_max > 0){
                            $price = $val->price_max;
                        }
                        $total_price += $price;
                    }else{
                        $price = floatval($val['price']);
                        if($price < 0) $price = 0;
                        $time_number=STCars::get_date_diff($check_in_timestamp,$check_out_timestamp,$val['price_unit']);
                        if(!empty($val['price_unit'])){
                            $price = $price*$time_number;
                        }
                        if($price > $val['price_max'] and $val['price_max'] > 0){
                            $price = $val['price_max'];
                        }
                        $total_price += $price;
                    }
                }
            }
            return $total_price;
        }
        static function getPriceEuipmentCarAdmin($data = array()){
            $total_price = 0;
            if(is_array($data) && count($data)){
                foreach($data as $key => $val){
                    $price = preg_replace("/.*(--)/", "", $val);
                    if($price < 0) $price = 0;
                    $total_price += $price;
                }
            }
            return $total_price;
        }

        static function convertEquipmentToOject($selected_equipments = array()){
            $list = array();
            if(is_array($selected_equipments) && count($selected_equipments)){
                foreach($selected_equipments as $key => $val){
                    $arr = explode('--', $val);
                    $title = isset($arr[0]) ? trim($arr[0]) : '';
                    $price = isset($arr[1]) ? floatval($arr[1]) : 0;
                    $list[$key] = new stdClass();
                    $list[$key]->title = $title;
                    $list[$key]->price = $price;
                }
            }
            return $list;
        }
        static function getTotal($div_room = false){
            $cart = $_SESSION['st_cart'];

            $total = 0;

            if(is_array($cart) && count($cart)){
                foreach($cart as $key => $val){
                    $post_id = intval($key);
                    if(!isset($val['data']['deposit_money'])){
                        $val['data']['deposit_money'] = array();
                    }
                    if(get_post_type($post_id) == 'st_hotel'){
                        $room_id = intval($val['data']['room_id']);
                        $check_in = $val['data']['check_in'];
                        $check_out = $val['data']['check_out'];
                        $number_room = intval($val['number']);
                        $numberday = TravelHelper::dateDiff($check_in, $check_out);

                        $sale_price = STPrice::getRoomPrice($room_id, strtotime($check_in), strtotime($check_out), $number_room);
                        $extras = isset($val['data']['extras']) ? $val['data']['extras'] : array();
                        $extra_price = STPrice::getExtraPrice($extras, $number_room, $numberday);
                        $price_with_tax = STPrice::getPriceWithTax($sale_price + $extra_price);
                        $price_coupon = STPrice::getCouponPrice();
                        if(isset($val['data']['deposit_money'])){
                            $total = STPrice::getDepositPrice($val['data']['deposit_money'], $price_with_tax, $price_coupon);
                        }else{
                            $total = $price_with_tax - $price_coupon;
                        }
                        if($div_room){
                            $total /= $number_room;
                        }
                    }

                    if(get_post_type($post_id) == 'st_rental'){
                        $rental_id = intval($key);
                        $check_in = $val['data']['check_in'];
                        $check_out = $val['data']['check_out'];
                        $item_price = STPrice::getRentalPriceOnlyCustomPrice($rental_id, strtotime($check_in), strtotime($check_out));
                        $numberday = TravelHelper::dateDiff($check_in, $check_out);
                        $sale_price = self::getSalePrice($rental_id, $item_price, strtotime($check_in), strtotime($check_out));
                        $extras = isset($val['data']['extras']) ? $val['data']['extras'] : array();
                        $extra_price = STPrice::getExtraPrice($extras, 1, $numberday);
                        $price_with_tax = STPrice::getPriceWithTax($sale_price + $extra_price);
                        $price_coupon = STPrice::getCouponPrice();
                        if(isset($val['data']['deposit_money'])){
                            $total = STPrice::getDepositPrice($val['data']['deposit_money'], $price_with_tax, $price_coupon);
                        }else{
                            $total = $price_with_tax - $price_coupon;
                        }
                    }
                    if(get_post_type($post_id) == 'st_activity'){
                        $check_in = $val['data']['check_in'];
                        $check_out = $val['data']['check_out'];
                        $adult_number = intval($val['data']['adult_number']);
                        $child_number = intval($val['data']['child_number']);
                        $infant_number = intval($val['data']['infant_number']);
                        $data_prices = self::getPriceByPeople($post_id, strtotime($check_in), strtotime($check_out), $adult_number, $child_number, $infant_number);
                        $origin_price = floatval($data_prices['total_price']);

                        $activity_type = $val['data']['type_activity'];

                        $sale_price = STPrice::getSaleTourSalePrice($post_id, $origin_price, $activity_type, strtotime($check_in));
                        $price_with_tax = self::getPriceWithTax($sale_price);
                        $coupon_price = self::getCouponPrice();
                        $deposit_price = self::getDepositPrice($val['data']['deposit_money'], $price_with_tax, $coupon_price);
                        if(isset($val['data']['deposit_money'])){
                            $total = $deposit_price;
                        }else{
                            $total = $price_with_tax - $coupon_price;
                        }
                    }

                    if(get_post_type($post_id) == 'st_tours'){
                        $check_in = $val['data']['check_in'];
                        $check_out = $val['data']['check_out'];
                        $adult_number = intval($val['data']['adult_number']);
                        $child_number = intval($val['data']['child_number']);
                        $infant_number = intval($val['data']['infant_number']);
                        $data_prices = self::getPriceByPeopleTour($post_id, strtotime($check_in), strtotime($check_out), $adult_number, $child_number, $infant_number);
                        $origin_price = floatval($data_prices['total_price']);

                        $tour_type = $val['data']['type_tour'];

                        $sale_price = STPrice::getSaleTourSalePrice($post_id, $origin_price, $tour_type, strtotime($check_in));
                        $price_with_tax = self::getPriceWithTax($sale_price);
                        $coupon_price = self::getCouponPrice();
                        $deposit_price = self::getDepositPrice($val['data']['deposit_money'], $price_with_tax, $coupon_price);
                        if(isset($val['data']['deposit_money'])){
                            $total = $deposit_price;
                        }else{
                            $total = $price_with_tax - $coupon_price;
                        }
                    }
                    if(get_post_type($post_id) == 'st_cars'){
                        $car_id = intval($key);
                        $item_price = floatval(get_post_meta($car_id, 'cars_price', true));
                        $check_in_timestamp = $val['data']['check_in_timestamp'];
                        $check_out_timestamp = $val['data']['check_out_timestamp'];
                        $item_price = floatval($val['data']['item_price']);
                        $price_equipment = floatval($val['data']['price_equipment']);
                        $unit = st()->get_option('cars_price_unit', 'day');
                        /*if($unit == 'day'){
                            $numberday = ceil(($check_out_timestamp - $check_in_timestamp) / (60 * 60 * 24));
                        }elseif($unit == 'hour'){
                            $numberday = ceil(($check_out_timestamp - $check_in_timestamp) / (60 * 60));
                        }*/
                        $numberday = STCars::get_date_diff( $check_in_timestamp , $check_out_timestamp , $unit);
                        $origin_price = $item_price * $numberday;

                        $sale_price = STPrice::getSaleCarPrice($car_id, $item_price,  $check_in_timestamp, $check_out_timestamp);
                        $price_with_tax = STPrice::getPriceWithTax($sale_price + $price_equipment);
                        $coupon_price = self::getCouponPrice();
                        $deposit_price = self::getDepositPrice($val['data']['deposit_money'], $price_with_tax, $coupon_price);
                        if(isset($val['data']['deposit_money'])){
                            $total = $deposit_price;
                        }else{
                            $total = $price_with_tax - $coupon_price;
                        }
                    }
                }
            }
            return TravelHelper::convert_money($total);
        }

        static function getDataPrice(){
            $cart = $_SESSION['st_cart'];
            $data_price = array(
                'origin_price' => '',
                'sale_price' => '',
                'coupon_price' => '',
                'total_price' => '',
                'deposit_price' => ''
            );
            if(is_array($cart) && count($cart)){
                foreach($cart as $key => $val){
                    if(!isset($val['data']['deposit_money'])){
                        $val['data']['deposit_money'] = array();
                    }
                    if(get_post_type($key) == 'st_hotel'){
                        $room_id = intval($val['data']['room_id']);
                        $check_in = $val['data']['check_in'];
                        $check_out = $val['data']['check_out'];
                        $number_room = intval($val['number']);
                        $numberday = TravelHelper::dateDiff($check_in, $check_out);

                        $origin_price = self::getRoomPriceOnlyCustomPrice($room_id, strtotime($check_in), strtotime($check_out), $number_room);
                        $sale_price = self::getRoomPrice($room_id, strtotime($check_in), strtotime($check_out), $number_room);
                        $extras = isset($val['data']['extras']) ? $val['data']['extras'] : array();
                        $extra_price = STPrice::getExtraPrice($extras, $number_room, $numberday);
                        $coupon_price = self::getCouponPrice();
                        $price_with_tax = self::getPriceWithTax($sale_price + $extra_price);
                        $deposit_price = self::getDepositPrice($val['data']['deposit_money'], $price_with_tax, $coupon_price);
                        if(isset($val['data']['deposit_money'])){
                            $total_price = $deposit_price;
                        }else{
                            $total_price = $price_with_tax - $coupon_price;
                        }
                    }
                    if(get_post_type($key) == 'st_rental'){
                        $rental_id = intval($key);
                        $check_in = $val['data']['check_in'];
                        $check_out = $val['data']['check_out'];

                        $numberday = TravelHelper::dateDiff($check_in, $check_out);

                        $item_price = STPrice::getRentalPriceOnlyCustomPrice($rental_id, strtotime($check_in), strtotime($check_out));
                        $origin_price = $item_price;
                        $sale_price = self::getSalePrice($rental_id, $item_price, strtotime($check_in), strtotime($check_out));
                        $extras = isset($val['data']['extras']) ? $val['data']['extras'] : array();
                        $extra_price = STPrice::getExtraPrice($extras, 1, $numberday);
                        $coupon_price = self::getCouponPrice();
                        $price_with_tax = self::getPriceWithTax($sale_price + $extra_price);
                        $deposit_price = self::getDepositPrice($val['data']['deposit_money'], $price_with_tax, $coupon_price);
                        if(isset($val['data']['deposit_money'])){
                            $total_price = $deposit_price;
                        }else{
                            $total_price = $price_with_tax - $coupon_price;
                        }
                    }
                    if(get_post_type($key) == 'st_tours'){
                        $post_id = intval($key);
                        $check_in = $val['data']['check_in'];
                        $check_out = $val['data']['check_out'];
                        $adult_number = intval($val['data']['adult_number']);
                        $child_number = intval($val['data']['child_number']);
                        $infant_number = intval($val['data']['infant_number']);
                        $data_prices = self::getPriceByPeopleTour($post_id, strtotime($check_in), strtotime($check_out), $adult_number, $child_number, $infant_number);
                        $origin_price = floatval($data_prices['total_price']);
                        if(get_post_type($post_id) == 'st_tours'){
                            $tour_type = $val['data']['type_tour'];
                        }elseif(get_post_type($post_id) == 'st_activity'){
                            $tour_type = $val['data']['type_activity'];
                        }
                        $sale_price = STPrice::getSaleTourSalePrice($post_id, $origin_price, $tour_type, strtotime($check_in));
                        $price_with_tax = self::getPriceWithTax($sale_price);
                        $coupon_price = self::getCouponPrice();
                        $deposit_price = self::getDepositPrice($val['data']['deposit_money'], $price_with_tax, $coupon_price);
                        if(isset($val['data']['deposit_money'])){
                            $total_price = $deposit_price;
                        }else{
                            $total_price = $price_with_tax - $coupon_price;
                        }

                        $data_price['adult_price'] = $data_prices['adult_price'];
                        $data_price['child_price'] = $data_prices['child_price'];
                        $data_price['infant_price'] = $data_prices['infant_price'];
                    }

                    if(get_post_type($key) == 'st_activity'){
                        $post_id = intval($key);
                        $check_in = $val['data']['check_in'];
                        $check_out = $val['data']['check_out'];
                        $adult_number = intval($val['data']['adult_number']);
                        $child_number = intval($val['data']['child_number']);
                        $infant_number = intval($val['data']['infant_number']);
                        $data_prices = self::getPriceByPeople($post_id, strtotime($check_in), strtotime($check_out), $adult_number, $child_number, $infant_number);
                        $origin_price = floatval($data_prices['total_price']);
                        if(get_post_type($post_id) == 'st_tours'){
                            $tour_type = $val['data']['type_tour'];
                        }elseif(get_post_type($post_id) == 'st_activity'){
                            $tour_type = $val['data']['type_activity'];
                        }
                        $sale_price = STPrice::getSaleTourSalePrice($post_id, $origin_price, $tour_type, strtotime($check_in));
                        $price_with_tax = self::getPriceWithTax($sale_price);
                        $coupon_price = self::getCouponPrice();
                        $deposit_price = self::getDepositPrice($val['data']['deposit_money'], $price_with_tax, $coupon_price);
                        if(isset($val['data']['deposit_money'])){
                            $total_price = $deposit_price;
                        }else{
                            $total_price = $price_with_tax - $coupon_price;
                        }

                        $data_price['adult_price'] = $data_prices['adult_price'];
                        $data_price['child_price'] = $data_prices['child_price'];
                        $data_price['infant_price'] = $data_prices['infant_price'];
                    }

                    if(get_post_type($key) == 'st_cars'){
                        $post_id = intval($key);
                        $check_in_timestamp = $val['data']['check_in_timestamp'];
                        $check_out_timestamp = $val['data']['check_out_timestamp'];
                        $item_price = floatval($val['data']['item_price']);
                        $price_equipment = floatval($val['data']['price_equipment']);
                        $unit = st()->get_option('cars_price_unit', 'day');
                        $numberday = STCars::get_date_diff( $check_in_timestamp , $check_out_timestamp , $unit);
                        /*if($unit == 'day'){
                            $numberday = ceil(($check_out_timestamp - $check_in_timestamp) / (60 * 60 * 24));
                        }elseif($unit == 'hour'){
                            $numberday = ceil(($check_out_timestamp - $check_in_timestamp) / (60 * 60));
                        }*/
                        $origin_price = $item_price * $numberday;

                        $sale_price = STPrice::getSaleCarPrice($post_id, $item_price,  $check_in_timestamp, $check_out_timestamp);
                        $price_with_tax = STPrice::getPriceWithTax($sale_price + $price_equipment);
                        $coupon_price = self::getCouponPrice();
                        $deposit_price = self::getDepositPrice($val['data']['deposit_money'], $price_with_tax, $coupon_price);
                        if(isset($val['data']['deposit_money'])){
                            $total_price = $deposit_price;
                        }else{
                            $total_price = $price_with_tax - $coupon_price;
                        }
                        $data_price['price_equipment'] = $price_equipment;
                        $data_price['unit'] = $unit;
                    }
                }
            }

            $data_price['origin_price'] = $origin_price;
            $data_price['sale_price'] = $sale_price;
            $data_price['coupon_price'] = $coupon_price;
            $data_price['price_with_tax'] = $price_with_tax;
            $data_price['total_price'] = $total_price;
            $data_price['deposit_price'] = $deposit_price;

            return $data_price;
        }
        static function getDepositPrice($deposit = array(), $price_with_tax = '',  $price_coupon = ''){
            $price_with_tax = floatval($price_with_tax);
            $price_coupon = floatval($price_coupon);

            $deposit_price = $price_with_tax - $price_coupon;

            if(isset($deposit['type']) && isset($deposit['amount']) && floatval($deposit['amount']) > 0){
                if($deposit['type'] == 'percent'){
                    $de_price = floatval($deposit['amount']);
                    $deposit_price = $deposit_price * ($de_price / 100);
                }elseif($deposit['type'] == 'amount'){
                    $de_price = floatval($deposit['amount']);
                    $deposit_price = $de_price;
                }
            }

            return $deposit_price;
        }

        static function getDepositData($post_id = '', $cart_data = array()){
            $cart_data['data']['deposit_money'] = array(
                'type' => '',
                'amount' => ''
            );
            $post_id = intval($post_id);
            $status = get_post_meta( $post_id , 'deposit_payment_status' , true );
            if(!$status) $status = '';

            if(!empty($status)){
                $amount = floatval(get_post_meta($post_id , 'deposit_payment_amount' , true ));
                if($amount < 0) $amount = 0;

                $cart_data['data']['deposit_money'] = array(
                    'type' => $status,
                    'amount' => $amount
                );
            }

            return $cart_data;
        }

        static function getCouponPrice(){
            if(STCart::use_coupon()){
                $price_coupon = floatval(STCart::get_coupon_amount());
                if($price_coupon < 0) $price_coupon = 0;

                return $price_coupon;
            }
            return 0;
        }

        /**
         *@since 1.1.8
         *	Only use for hotel room
         **/
        static function getRoomPriceOnlyCustomPrice($room_id = '', $check_in = '', $check_out = '', $number_room = 1){
            $room_id = intval($room_id);
            $default_state = get_post_meta($room_id, 'default_state', true);
            if(!$default_state) $default_state = 'available';

            $hotel_id = get_post_meta($room_id, 'room_parent', true);
            $allow_full_day = get_post_meta($hotel_id, 'allow_full_day', true);
            if(!$allow_full_day) $allow_full_day = 'on';

            if(get_post_type($room_id) == 'hotel_room'){
                $price_ori = floatval(get_post_meta($room_id, 'price', true));
                if($price_ori < 0) $price_ori = 0;

                $total_price = 0;
                $custom_price = AvailabilityHelper::_getdataHotel($room_id, $check_in, $check_out);
                
                $price_key = 0;
                for($i = $check_in; $i <= $check_out; $i = strtotime('+1 day', $i)){
                    if(is_array($custom_price) && count($custom_price)){
                        $in_date = false;
                        $price = 0;
                        $status = 'available';
                        foreach($custom_price as $key => $val){
                            if($i >= $val->check_in && $i <= $val->check_out){
                                $status = $val->status;
                                $price = floatval($val->price);
                                if(!$in_date) $in_date = true;
                            }
                        }
                        if($in_date){
                            if($status == 'available'){

                                $price_key = floatval($price);
                            }
                        }else{
                            if($default_state == 'available'){
                                $price_key = $price_ori;
                            }
                        }
                    }else{
                        if($default_state == 'available'){
                            $price_key = $price_ori;
                        }
                    }
                    if($i < $check_out){
                        $total_price += $price_key;
                    }
                }
                return $total_price * $number_room;
            }
            return 0;
        }

        static function getRentalPriceOnlyCustomPrice($rental_id = '', $check_in = '', $check_out = ''){
            $rental_id = intval($rental_id);

            if(get_post_type($rental_id) == 'st_rental'){
                $price_ori = floatval(get_post_meta($rental_id, 'price', true));
                if($price_ori < 0) $price_ori = 0;

                $total_price = 0;
                $custom_price = AvailabilityHelper::_getdataRental($rental_id, $check_in, $check_out);
                
                $price_key = 0;
                for($i = $check_in; $i <= $check_out; $i = strtotime('+1 day', $i)){
                    if(is_array($custom_price) && count($custom_price)){
                        $in_date = false;
                        $price = 0;
                        $status = 'available';
                        foreach($custom_price as $key => $val){
                            if($i >= $val->check_in && $i <= $val->check_out){
                                $status = $val->status;
                                $price = floatval($val->price);
                                if(!$in_date) $in_date = true;
                            }
                        }
                        if($in_date){
                            if($status == 'available'){

                                $price_key = floatval($price);
                            }
                        }else{
                            $price_key = $price_ori;
                        }
                    }else{
                        $price_key = $price_ori;
                    }
                    if($i < $check_out){
                        $total_price += $price_key;
                    }
                }
                return $total_price;
            }
            return 0;
        }
        static function showRoomPriceInfo($room_id = '', $check_in = '', $check_out = ''){
            $room_id = intval($room_id);
            $price_ori = floatval(get_post_meta($room_id, 'price', true));
            if($price_ori < 0) $price_ori = 0;

            $default_state = get_post_meta($room_id, 'default_state', true);
            if(!$default_state) $default_state = 'available';

            $price_ori = floatval(get_post_meta($room_id, 'price', true));
            if($price_ori < 0) $price_ori = 0;
            $html = '<table width="100%">
				<tr>
					<th style="text-align: center" bgcolor="#CCC">'.__('From', ST_TEXTDOMAIN).' - '.__('To', ST_TEXTDOMAIN).'</th>
					<th style="text-align: center" bgcolor="#CCC">'.__('Price', ST_TEXTDOMAIN).'</th>
				</tr>
			';
            if(get_post_type($room_id) == 'hotel_room'){

                $custom_price = AvailabilityHelper::_getdataHotel($room_id, $check_in, $check_out);
                $groupday = STPrice::getGroupDay($check_in, $check_out);
                foreach($groupday as $key => $date){
                    $status = 'available';
                    $price = 0;
                    $in_date = false;
                    $price_tmp = 0;
                    if(is_array($custom_price) && count($custom_price)){
                        foreach($custom_price as $key => $val){
                            if($date[0] == $val->check_in){
                                $status = $val->status;
                                $price = floatval($val->price);
                                if(!$in_date) $in_date = true;
                            }
                            if($in_date){
                                if($status == 'available'){
                                    $price_tmp = $price;
                                }
                            }else{
                                if($default_state == 'available'){
                                    $price_tmp = $price_ori;
                                }
                            }
                        }
                    }else{
                        if($default_state == 'available'){
                            $price_tmp = $price_ori;
                        }
                    }

                    $html .= '
						<tr>
							<td width="60%" style="border-bottom: 1px dashed #CCC">'.
                        date(TravelHelper::getDateFormat(), $date[0]).' <i class="fa fa-arrow-right "></i> '.date(TravelHelper::getDateFormat(), $date[1]).
                        '</td>
							<td width="40%" style="border-bottom: 1px dashed #CCC; text-align: right">'.
                        TravelHelper::format_money($price_tmp)
                        .'</td>
						</tr>
					';
                }
            }
            $html .= '</table>';
            return $html;
        }
        /**
         *@since 1.1.8
         *@param $room_id int
         *@param $check_in timestamp
         *@param $check_out timestamp
         **/
        static function getRoomPrice($room_id = '', $check_in = '', $check_out = '', $number_room = 1){
            global $wpdb;

            $room_id = intval($room_id);
            $default_state = get_post_meta($room_id, 'default_state', true);
            if(!$default_state) $default_state = 'available';

            $total_price = 0;
            if(get_post_type($room_id) == 'hotel_room'){

                $price_ori = floatval(get_post_meta($room_id, 'price', true));

                if($price_ori < 0) $price_ori = 0;

                $discount_rate = floatval(get_post_meta($room_id,'discount_rate',true));

                if($discount_rate < 0) $discount_rate = 0;
                if($discount_rate > 100) $discount_rate = 100;

                $is_sale_schedule = get_post_meta($room_id, 'is_sale_schedule', true);
                if($is_sale_schedule == false || empty($is_sale_schedule)) $is_sale_schedule = 'off';
                // Price wiht custom price
                $custom_price = AvailabilityHelper::_getdataHotel($room_id, $check_in, $check_out);

                $groupday = STPrice::getGroupDay($check_in, $check_out);

                if(is_array($groupday) && count($groupday)){
                    foreach($groupday as $key => $date){
                        $price_tmp = 0;
                        $status = 'available';
                        $priority = 0;
                        $in_date = false;
                        foreach($custom_price as $key => $val){
                            if($date[0] >= $val->check_in && $date[0] <= $val->check_out){
                                $status = $val->status;
                                $price = floatval($val->price);
                                if(!$in_date) $in_date = true;
                            }
                        }

                        if($is_sale_schedule == 'on'){
                            $sale_from = strtotime(get_post_meta($room_id, 'sale_price_from',true));
                            $sale_to = strtotime(get_post_meta($room_id, 'sale_price_to',true));
                            if($sale_from > 0 && $sale_to > 0 && $sale_from <= $sale_to){
                                if(self::checkBetween($date, $sale_from, $sale_to)){
                                    $in_sale = true;
                                }else{
                                    $in_sale = false;
                                }
                            }
                        }else{
                            $in_sale = true;
                        }
                        if($in_date){
                            if($status = 'available'){
                                if($in_sale){
                                    $price_tmp = $price - ($price * ($discount_rate / 100));
                                }else{
                                    $price_tmp = $price;
                                }
                            }
                        }else{
                            if($default_state == 'available'){
                                if($in_sale){
                                    $price_tmp = $price_ori - ($price_ori * ($discount_rate / 100));
                                }else{
                                    $price_tmp = $price_ori;
                                }
                            }

                        }
                        $total_price += $price_tmp;

                    }
                }
                return $total_price * $number_room;
            }
            return 0;
        }

        static function getExtraPrice($extra_price = array(), $number_room = 0, $numberday = 0){
            $total_price = 0;
            if(isset($extra_price['value']) && is_array($extra_price['value']) && count($extra_price['value'])){
                foreach($extra_price['value'] as $name => $number){
                    $price_item = floatval($extra_price['price'][$name]);
                    if($price_item <= 0) $price_item = 0;
                    $number_item = intval($extra_price['value'][$name]);
                    if($number_item <= 0) $number_item = 0;
                    $total_price += $price_item * $number_item;
                }
            }
            return $total_price * $numberday * $number_room;
        }
        static function checkBetween($date = array(), $in = '', $out = ''){
            foreach($date as $val){
                if($val >= $in && $val <= $out){
                    return true;
                }
            }
            return false;
        }
        static function getGroupDay($start = '', $end = ''){
            $list = array();
            for($i = $start; $i <= $end; $i = strtotime('+1 day', $i)){
                $next = strtotime('+1 day', $i);
                if($next <= $end){
                    $list[] = array($i, $next);
                }
            }
            return $list;
        }

        static function getGroupDayCar($start = '', $end = ''){
            $list = array();
            $unit = st()->get_option('cars_price_unit', 'day');
            if($unit == 'day'){
                $numberday_ori = ($end - $start) / (60 * 60 * 24);
                $numberday = ceil(($end - $start) / (60 * 60 * 24));
            }elseif($unit == 'hour'){
                $numberday_ori = ($end - $start) / (60 * 60);
                $numberday = ceil(($end - $start) / (60 * 60));
            }
            //$numberday = STCars::get_date_diff( $check_in_timestamp , $check_out_timestamp , $unit);


            if($unit == 'day' && $numberday <= 0){
                $end = strtotime('+1 day', $start);
            }elseif($unit == 'hour' && $numberday <= 0){
                $end = strtotime('+1 hour', $start);
            }
            if($unit == 'day'){
                for($i = $start; $i <= $end; $i = strtotime('+1 day', $i)){
                    $next = strtotime('+1 day', $i);
                    if($i < $end){
                        $list[] = array($i, $next);
                    }
                    if($i == $end && $numberday > $numberday_ori){
                        $list[] = array($i, $next);
                    }
                }
                if(st()->get_option('booking_days_included')=='on')
                {
                    $list[]=array($start,$start);
                }
            }elseif($unit == 'hour'){

                for($i = $start; $i <= $end; $i = strtotime('+1 hour', $i)){
                    $next = strtotime('+1 hour', $i);
                    if($i < $end){
                        $list[] = array($i, $next);
                    }
                    if($i == $end && $numberday > $numberday_ori){
                        $list[] = array($i, $next);
                    }

                }
                if(st()->get_option('booking_days_included')=='on')
                {
                    $list[]=array($start,$start);
                }
            }
            return $list;
        }
        static function _getListRoomCustomPrice($room_id = '', $check_in = '', $check_out = ''){
            global $wpdb;

            $room_id = intval($room_id);

            if(get_post_type($room_id) == 'hotel_room'){
                $sql = "SELECT
					price,
					start_date AS check_in,
					end_date AS check_out,
					priority
				FROM
					{$wpdb->prefix}st_price
				WHERE
					post_id = '{$room_id}'
				AND (
					(
						'{$check_in}' < STR_TO_DATE(start_date, '%Y-%m-%d')
						AND '{$check_out}' > STR_TO_DATE(end_date, '%Y-%m-%d')
					)
					OR (
						'{$check_in}' BETWEEN STR_TO_DATE(start_date, '%Y-%m-%d')
						AND STR_TO_DATE(end_date, '%Y-%m-%d')
					)
					OR (
						'{$check_out}' BETWEEN STR_TO_DATE(start_date, '%Y-%m-%d')
						AND STR_TO_DATE(end_date, '%Y-%m-%d')
					)
				)";
                $results = $wpdb->get_results($sql, ARRAY_A);
                return $results;
            }else{
                return null;
            }
        }
    }
}
new STPrice();
?>