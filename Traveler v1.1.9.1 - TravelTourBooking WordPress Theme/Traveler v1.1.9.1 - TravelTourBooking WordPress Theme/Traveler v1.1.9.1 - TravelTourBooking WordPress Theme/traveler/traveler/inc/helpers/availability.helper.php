<?php 
/**
*@since 1.1.9
**/
if(!class_exists('AvailabilityHelper')){
	class AvailabilityHelper{
		public function __construct(){
			if(is_admin()){
				add_action('wp_ajax_st_get_availability_hotel', array(&$this, '_get_availability_hotel'));
				add_action('wp_ajax_st_get_availability_rental', array(&$this, '_get_availability_rental'));
				
				add_action('wp_ajax_st_get_availability_tour', array(&$this, '_get_availability_tour'));
				add_action('wp_ajax_st_get_availability_tour_frontend', array(&$this, '_get_availability_tour_frontend'));
				add_action('wp_ajax_nopriv_st_get_availability_tour_frontend', array(&$this, '_get_availability_tour_frontend'));
				add_action('wp_ajax_st_add_custom_price', array(&$this, '_add_custom_price'));
				add_action('wp_ajax_st_add_custom_price_rental', array(&$this, '_add_custom_price_rental'));
				add_action('wp_ajax_st_add_custom_price_tour', array(&$this, '_add_custom_price_tour'));
			}
		}
		public function _get_availability_hotel(){
			$results = array();
			$post_id = STInput::request('post_id', '');
			$check_in = STInput::request('start', '');
			$check_out = STInput::request('end', '');
			$price_ori = floatval(get_post_meta($post_id, 'price', true));
			$default_state = get_post_meta($post_id, 'default_state', true);
			$number_room = intval(get_post_meta($post_id, 'number_room', true));

			if(get_post_type($post_id) == 'hotel_room'){
				$data = self::_getdataHotel($post_id, $check_in, $check_out);
				
				for($i = intval($check_in); $i <= intval($check_out); $i = strtotime('+1 day', $i)){
					$in_date = false;
					if(is_array($data) && count($data)){
						foreach($data as $key => $val){
							if($i == intval($val->check_in) && $i == intval($val->check_out)){
								$status = $val->status;
								if($status != 'unavailable'){
									$item = array(
										'price' => floatval($val->price),
										'start' => date('Y-m-d',$i),
										'title' => get_the_title($post_id),
										'item_id' => $val->id,
										'status' => $val->status,
									);
								}else{
									unset($item);
								}
								if(!$in_date)
									$in_date = true;
							}
						}
					}
					if(isset($item)){
						$results[] = $item;
						unset($item);
					}
					if(!$in_date && ($default_state == 'available' || !$default_state)){
						$item_ori = array(
							'price' => $price_ori,
							'start' => date('Y-m-d', $i),
							'title' => get_the_title($post_id),
							'number' => $number_room,
							'status' => 'available'
						);
						$results[] = $item_ori;
						unset($item_ori);
					}
				}
			}

			echo json_encode($results);
			die();
		}

		public function _get_availability_rental(){
			$results = array();
			$post_id = STInput::request('post_id', '');
			$check_in = STInput::request('start', '');
			$check_out = STInput::request('end', '');
			$price_ori = floatval(get_post_meta($post_id, 'price', true));
			$number_room = intval(get_post_meta($post_id, 'number_room', true));

			if(get_post_type($post_id) == 'st_rental'){
				$data = self::_getdataRental($post_id, $check_in, $check_out);
				
				for($i = intval($check_in); $i <= intval($check_out); $i = strtotime('+1 day', $i)){
					$in_date = false;
					if(is_array($data) && count($data)){
						foreach($data as $key => $val){
							if($i == intval($val->check_in) && $i == intval($val->check_out)){
								$status = $val->status;
								if($status != 'unavailable'){
									$item = array(
										'price' => floatval($val->price),
										'start' => date('Y-m-d',$i),
										'title' => get_the_title($post_id),
										'item_id' => $val->id,
										'status' => $val->status,
									);
								}else{
									unset($item);
								}
								if(!$in_date)
									$in_date = true;
							}
						}
					}
					if(isset($item)){
						$results[] = $item;
						unset($item);
					}
					if(!$in_date){
						$item_ori = array(
							'price' => $price_ori,
							'start' => date('Y-m-d', $i),
							'title' => get_the_title($post_id),
							'number' => $number_room,
							'status' => 'available'
						);
						$results[] = $item_ori;
						unset($item_ori);
					}
				}
			}

			echo json_encode($results);
			die();
		}


		public function _get_availability_tour(){
			$results = array();
			$tour_id = STInput::request('tour_id', '');
			$check_in = STInput::request('start', '');
			$check_out = STInput::request('end', '');
			if(get_post_type($tour_id) == 'st_tours'){
				$max_people = intval(get_post_meta($tour_id, 'max_people', true));
				$adult_price = floatval(get_post_meta($tour_id, 'adult_price', true));
				$child_price = floatval(get_post_meta($tour_id, 'child_price', true));
				$infant_price = floatval(get_post_meta($tour_id, 'infant_price', true));

				$type_tour = get_post_meta($tour_id, 'type_tour', true);

				$data_tour = self::_getdataTourEachDate($tour_id, $check_in, $check_out);
				if(is_array($data_tour) && count($data_tour)){
					foreach($data_tour as $key => $val){
						if($val->status == 'available'){
							if(intval($val->groupday) == 1){
								$results[] = array(
									'title' => get_the_title($tour_id),
									'start' => date('Y-m-d',$val->check_in),
									'end' => date('Y-m-d',strtotime('+1 day', $val->check_out)),
									'adult_price' => $val->adult_price,
									'child_price' => $val->child_price,
									'infant_price' => $val->infant_price,
									'status' => 'available'
								);
							}else{
								for($i = $val->check_in; $i <= $val->check_out; $i = strtotime('+1 day', $i)){
									$results[] = array(
										'title' => get_the_title($tour_id),
										'start' => date('Y-m-d',$i),
										'adult_price' => $val->adult_price,
										'child_price' => $val->child_price,
										'infant_price' => $val->infant_price,
										'status' => 'available',
									);
								}
							}
						}
					}

					if($type_tour == 'daily_tour'){
						for($i = $check_in; $i <= $check_out; $i = strtotime('+1 day', $i)){
							$in_item = false;
							foreach($data_tour as $key => $val){
								if($i >= $val->check_in && $i <= $val->check_out){
									$in_item = true; break;
								}
							}

							if(!$in_item){
								$results[] = array(
									'title' => get_the_title($tour_id),
									'start' => date('Y-m-d',$i),
									'adult_price' => $adult_price,
									'child_price' => $child_price,
									'infant_price' => $infant_price,
									'status' => 'available',
								);
							}
						}
					}

				}else{
					if($type_tour == 'daily_tour' || !$type_tour){
						for($i = $check_in; $i <= $check_out; $i = strtotime('+1 day', $i)){
							$results[] = array(
								'title' => get_the_title($tour_id),
								'start' => date('Y-m-d',$i),
								'adult_price' => $adult_price,
								'child_price' => $child_price,
								'infant_price' => $infant_price,
								'status' => 'available'
							);
						}
					}
				}
			}
			echo json_encode($results);
			die();
		}

		public function _get_availability_tour_frontend(){
			$results = array();
			$tour_id = STInput::request('tour_id', '');
			$check_in = STInput::request('start', '');
			$check_out = STInput::request('end', '');
			$today = strtotime(date('Y-m-d'));
			if(get_post_type($tour_id) == 'st_tours'){
				$adult_price = floatval(get_post_meta($tour_id, 'adult_price', true));
				$child_price = floatval(get_post_meta($tour_id, 'child_price', true));
				$infant_price = floatval(get_post_meta($tour_id, 'infant_price', true));

				$type_tour = get_post_meta($tour_id, 'type_tour', true);
				$booking_period = intval(get_post_meta($tour_id,'tours_booking_period', true));
				$data_tour = self::_getdataTourEachDate($tour_id, $check_in, $check_out);
				
				if(is_array($data_tour) && count($data_tour)){
					
					foreach($data_tour as $key => $val){
						if($val->status == 'available'){
							$cant_book = self::_get_tour_cant_order($tour_id, date('m/d/Y',$val->check_in));
							$period = TravelHelper::dateDiff(date('Y-m-d',$today), date('Y-m-d',$val->check_in));
							
							if(intval($val->groupday) == 1){
								if($val->check_in >= $today && count($cant_book) <= 0 && $period >= $booking_period){
									$results[] = array(
										'start' => date('Y-m-d',$val->check_in),
										'day'	=> date('d' ,$val->check_in),
										'date'	=> date('Y-m-d',$val->check_in),
										'end' => date('Y-m-d',strtotime('+1 day', $val->check_out)),
										'date_end' => date('d',$val->check_out),
										'adult_price' => ($val->adult_price) ?TravelHelper::format_money($val->adult_price) : 0,
										'child_price' => ($val->child_price) ?TravelHelper::format_money($val->child_price) : 0,
										'infant_price' => ($val->infant_price) ?TravelHelper::format_money($val->infant_price) : 0,
										'status' => 'available'
									);
								}
							}else{
								if($val->check_in >= $today  && count($cant_book) <= 0 && $period >= $booking_period){
									$results[] = array(
										'start' => date('Y-m-d',$val->check_in),
										'day'	=> date('d' ,$val->check_in),
										'date'	=> date('Y-m-d',$val->check_in),
										'adult_price' => ($val->adult_price) ?TravelHelper::format_money($val->adult_price) : 0,
										'child_price' => ($val->child_price) ?TravelHelper::format_money($val->child_price) : 0,
										'infant_price' => ($val->infant_price) ?TravelHelper::format_money($val->infant_price) : 0,
										'status' => 'available'
									);
								}
							}							
						}
					}

					if($type_tour == 'daily_tour'){
						
						for($i = $check_in; $i <= $check_out; $i = strtotime('+1 day', $i)){
							$in_item = false;
							$status = 'available';
							foreach($data_tour as $key => $val){
								if($i >= $val->check_in && $i <= $val->check_out){
									$in_item = true;
									$status = $val->status;
									break;
								}
							}

							if(!$in_item ){
								$cant_book = self::_get_tour_cant_order($tour_id, date('m/d/Y',$i));
								$period = TravelHelper::dateDiff(date('Y-m-d',$today), date('Y-m-d',$i));
								if($i >= $today && count($cant_book) <= 0 && $period >= $booking_period){
									$results[] = array(
										'start' => date('Y-m-d',$i),
										'day'	=> date('d' ,$i),
										'date'	=> date('Y-m-d',$i),
										'adult_price' => ($adult_price) ?TravelHelper::format_money($adult_price) : 0,
										'child_price' => ($child_price) ?TravelHelper::format_money($child_price) : 0,
										'infant_price' => ($infant_price) ?TravelHelper::format_money($infant_price) : 0,
										'status' => 'available'
									);
								}
								
							}
						}
					}

				}else{
					if($type_tour == 'daily_tour'){
						for($i = $check_in; $i <= $check_out; $i = strtotime('+1 day', $i)){
							$cant_book = self::_get_tour_cant_order($tour_id, date('m/d/Y',$i));
							$period = TravelHelper::dateDiff(date('Y-m-d',$today), date('Y-m-d',$i));
							if($i >= $today && count($cant_book) <= 0 && $period >= $booking_period){
								$results[] = array(
									'title' => get_the_title($tour_id),
									'start' => date('Y-m-d',$i),
									'day'	=> date('d' ,$i),
									'date'	=> date('Y-m-d',$i),
									'adult_price' => ($adult_price) ?TravelHelper::format_money($adult_price) : 0,
									'child_price' => ($child_price) ?TravelHelper::format_money($child_price) : 0,
									'infant_price' => ($infant_price) ?TravelHelper::format_money($infant_price) : 0,
									'status' => 'available'
								);
							}
						}
					}
				}
			}
			$st_tour_available = $results;
			$return = array();
			if(count($results)){
				for($i = $check_in; $i <= $check_out; $i = strtotime('+1 day', $i)){
					$in_date = false;
					foreach($results as $key => $val){
						$start = strtotime($val['start']);
						$end = (isset($val['end'])) ? strtotime('-1 day',strtotime($val['end'])) : strtotime($val['start']);
						if($i >= $start && $i <= $end){
							$in_date = true;
							break;
						}
					}

					if(!$in_date){
						$return[] = array(
							'start' => date('Y-m-d',$i),
							'day'	=> date('d' ,$i),
							'date' => date('Y-m-d',$i),
							'status' => 'not_available'
						);
					}
				}

				foreach($results as $key => $val){
					$return[] = $val;
				}
			}else{
				for($i = $check_in; $i <= $check_out; $i = strtotime('+1 day', $i)){
					$return[] = array(
						'start' => date('Y-m-d',$i),
						'day'	=> date('d' ,$i),
						'date' => date('Y-m-d',$i),
						'status' => 'not_available'
					);
				}
			}
			echo json_encode($return);
			die();
		}

		static function _get_list_availability_tour_frontend($tour_id, $check_in, $check_out){
			$results = array();
			$today = strtotime(date('Y-m-d'));
			if(get_post_type($tour_id) == 'st_tours'){
				$adult_price = floatval(get_post_meta($tour_id, 'adult_price', true));
				$child_price = floatval(get_post_meta($tour_id, 'child_price', true));
				$infant_price = floatval(get_post_meta($tour_id, 'infant_price', true));

				$type_tour = get_post_meta($tour_id, 'type_tour', true);
				$booking_period = intval(get_post_meta($tour_id,'tours_booking_period', true));
				$data_tour = self::_getdataTourEachDate($tour_id, $check_in, $check_out);
				
				if(is_array($data_tour) && count($data_tour)){
					
					foreach($data_tour as $key => $val){
						if($val->status == 'available'){
							$cant_book = self::_get_tour_cant_order($tour_id, date('m/d/Y',$val->check_in));
							
							$period = TravelHelper::dateDiff(date('Y-m-d',$today), date('Y-m-d',$val->check_in));
							
							if(intval($val->groupday) == 1){
								if($val->check_in >= $today && count($cant_book) <= 0 && $period >= $booking_period){
									$results[] = array(
										'start' => date('Y-m-d',$val->check_in),
										'end' => date('Y-m-d',$val->check_out),
										'adult_price' => TravelHelper::format_money($val->adult_price),
										'child_price' => TravelHelper::format_money($val->child_price),
										'infant_price' => TravelHelper::format_money($val->infant_price),
										'status' => 'available'
									);
								}
							}else{
								if($val->check_in >= $today  && count($cant_book) <= 0 && $period >= $booking_period){
									$results[] = array(
										'start' => date('Y-m-d',$val->check_in),
										'end' => date('Y-m-d',$val->check_in),
										'adult_price' => TravelHelper::format_money($val->adult_price),
										'child_price' => TravelHelper::format_money($val->child_price),
										'infant_price' => TravelHelper::format_money($val->infant_price),
										'status' => 'available'
									);
								}
							}
						}
					}

					if($type_tour == 'daily_tour'){
						
						for($i = $check_in; $i <= $check_out; $i = strtotime('+1 day', $i)){
							$in_item = false;
							$status = 'available';
							foreach($data_tour as $key => $val){
								if($i >= $val->check_in && $i <= $val->check_out){
									$in_item = true;
									$status = $val->status;
									break;
								}
							}

							if(!$in_item ){
								$cant_book = self::_get_tour_cant_order($tour_id, date('m/d/Y',$i));
								$period = TravelHelper::dateDiff(date('Y-m-d',$today), date('Y-m-d',$i));
								if($i >= $today && count($cant_book) <= 0 && $period >= $booking_period){
									$results[] = array(
										'start' => date('Y-m-d',$i),
										'end' => date('Y-m-d',$i),
										'adult_price' => TravelHelper::format_money($adult_price),
										'child_price' => TravelHelper::format_money($child_price),
										'infant_price' => TravelHelper::format_money($infant_price),
										'status' => 'available'
									);
								}
								
							}
						}
					}

				}else{
					if($type_tour == 'daily_tour'){
						for($i = $check_in; $i <= $check_out; $i = strtotime('+1 day', $i)){
							$cant_book = self::_get_tour_cant_order($tour_id, date('m/d/Y',$i));
							$period = TravelHelper::dateDiff(date('Y-m-d',$today), date('Y-m-d',$i));
							if($i >= $today && count($cant_book) <= 0 && $period >= $booking_period){
								$results[] = array(
									'title' => get_the_title($tour_id),
									'start' => date('Y-m-d',$i),
									'end' => date('Y-m-d',$i),
									'adult_price' => TravelHelper::format_money($adult_price),
									'child_price' => TravelHelper::format_money($child_price),
									'infant_price' => TravelHelper::format_money($infant_price),
									'status' => 'available'
								);
							}
						}
					}
				}
			}
			return $results;
			
		}
		static function _get_tour_cant_order($tour_id, $check_in){
			if(!TravelHelper::checkTableDuplicate('st_tours')) return '';
			global $wpdb;

			$sql = "SELECT
				st_booking_id AS tour_id,
				mt.meta_value AS max_people,
				mt.meta_value - SUM(
					DISTINCT (adult_number + child_number + infant_number)
				) AS free_people
			FROM
				{$wpdb->prefix}st_order_item_meta
			INNER JOIN {$wpdb->prefix}postmeta AS mt ON mt.post_id = st_booking_id
			AND mt.meta_key = 'max_people'
			WHERE
			st_booking_id = '{$tour_id}'
			AND
				st_booking_post_type = 'st_tours'
			AND (
				STR_TO_DATE('{$check_in}', '%m/%d/%Y') = STR_TO_DATE(check_in, '%m/%d/%Y')
			)
			AND status NOT IN ('trash', 'canceled')
			GROUP BY st_booking_id
			HAVING
			max_people - SUM(
					DISTINCT (adult_number + child_number + infant_number)
				) <= 0";

			$result = $wpdb->get_col($sql, 0);
			$list_date = array();
			if(is_array($result) && count($result)){
				$list_date = $result;
			}
			return $list_date;
		}

		static function _getdataHotel($post_id, $check_in, $check_out){
			global $wpdb;
			$sql = "
			SELECT
				`id`,
				`post_id`,
				`post_type`,
				`check_in`,
				`check_out`,
				`number`,
				`price`,
				`status`
			FROM
				{$wpdb->prefix}st_availability
			WHERE
			post_id = {$post_id} 
			AND post_type='hotel_room'
			AND
			(
				(
					UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME('{$check_in}'), '%Y-%m-%d')) < UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME(check_in), '%Y-%m-%d'))
					AND UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME('{$check_out}'), '%Y-%m-%d')) > UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME(check_out), '%Y-%m-%d'))
				)
				OR (
					UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME('{$check_in}'), '%Y-%m-%d')) BETWEEN UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME(check_in), '%Y-%m-%d'))
					AND UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME(check_out), '%Y-%m-%d'))
				)
				OR (
					UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME('{$check_out}'), '%Y-%m-%d')) BETWEEN UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME(check_in), '%Y-%m-%d'))
					AND UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME(check_out), '%Y-%m-%d'))
				)
			)";
			$results = $wpdb->get_results($sql);
			return $results;
		}
		static function _getdataRental($post_id, $check_in, $check_out){
			global $wpdb;
			$sql = "
			SELECT
				`id`,
				`post_id`,
				`post_type`,
				`check_in`,
				`check_out`,
				`number`,
				`price`,
				`status`
			FROM
				{$wpdb->prefix}st_availability
			WHERE
			post_id = {$post_id} 
			AND post_type='st_rental'
			AND
			(
				(
					UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME('{$check_in}'), '%Y-%m-%d')) < UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME(check_in), '%Y-%m-%d'))
					AND UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME('{$check_out}'), '%Y-%m-%d')) > UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME(check_out), '%Y-%m-%d'))
				)
				OR (
					UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME('{$check_in}'), '%Y-%m-%d')) BETWEEN UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME(check_in), '%Y-%m-%d'))
					AND UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME(check_out), '%Y-%m-%d'))
				)
				OR (
					UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME('{$check_out}'), '%Y-%m-%d')) BETWEEN UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME(check_in), '%Y-%m-%d'))
					AND UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME(check_out), '%Y-%m-%d'))
				)
			)";
			$results = $wpdb->get_results($sql);
			return $results;
		}

		static function _getdataTourEachDate($tour_id, $check_in, $check_out){
			global $wpdb;

			$sql = "
			SELECT
				`id`,
				`post_id`,
				`post_type`,
				`check_in`,
				`check_out`,
				`adult_price`,
				`child_price`,
				`infant_price`,
				`status`,
				`priority`,
				`groupday`
			FROM
				{$wpdb->prefix}st_availability
			WHERE
				post_id = '{$tour_id}'
			AND (
				(
					UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME('{$check_in}'), '%Y-%m-%d')) < UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME(check_in), '%Y-%m-%d'))
					AND UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME('{$check_out}'), '%Y-%m-%d')) > UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME(check_out), '%Y-%m-%d'))
				)
				OR (
					UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME('{$check_in}'), '%Y-%m-%d')) BETWEEN UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME(check_in), '%Y-%m-%d'))
					AND UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME(check_out), '%Y-%m-%d'))
				)
				OR (
					UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME('{$check_out}'), '%Y-%m-%d')) BETWEEN UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME(check_in), '%Y-%m-%d'))
					AND UNIX_TIMESTAMP(DATE_FORMAT(FROM_UNIXTIME(check_out), '%Y-%m-%d'))
				)
			)";
			$results = $wpdb->get_results($sql);
			return $results;
		}

		static function _getDisableCustomDate($room_id, $month, $month2, $year, $year2){
			$date1 = $year.'-'.$month.'-01';
			$date2 = strtotime($year2.'-'.$month2.'-01');
			$date2 = date('Y-m-t',$date2);
			global $wpdb;
			$sql = "
			SELECT
				`check_in`,
				`check_out`,
				`number`,
				`status`,
				`priority`
			FROM
				{$wpdb->prefix}st_availability
			WHERE
				post_id = {$room_id}

			AND (
				(
					'{$date1}' < DATE_FORMAT(FROM_UNIXTIME(check_in), '%Y-%m-%d')
					AND '{$date2}' > DATE_FORMAT(FROM_UNIXTIME(check_out), '%Y-%m-%d')
				)
				OR (
					'{$date1}' BETWEEN DATE_FORMAT(FROM_UNIXTIME(check_in), '%Y-%m-%d')
					AND DATE_FORMAT(FROM_UNIXTIME(check_out), '%Y-%m-%d')
				)
				OR (
					'$date2' BETWEEN DATE_FORMAT(FROM_UNIXTIME(check_in), '%Y-%m-%d')
					AND DATE_FORMAT(FROM_UNIXTIME(check_out), '%Y-%m-%d')
				)
			)";
			$results = $wpdb->get_results($sql);
			$default_state = get_post_meta($room_id, 'default_state', true);

			if(!$default_state) $default_state = 'available';
			$list_date = array();

			$start = strtotime($date1);
			$end = strtotime($date2);
			if(is_array($results) && count($results)){
				for($i = $start; $i <= $end; $i = strtotime('+1 day', $i)){
					$priority = 0;
					$in_date = false;
					foreach($results as $key => $val){
						$status = $val->status;
						if($i == $val->check_in && $i == $val->check_out){
							if($status == 'unavailable'){
								$date = $i;
							}else{
								unset($date);
							}
							if(!$in_date){
								$in_date = true;
							}
						}
					}

					if($in_date && isset($date)){
						$list_date[] = date(TravelHelper::getDateFormat(), $date);
						unset($date);
					}else{
						if(!$in_date && $default_state == 'not_available'){
							$list_date[] = date(TravelHelper::getDateFormat(), $i);
							unset($in_date);
						}
					}
				}
			}else{
				if($default_state == 'not_available'){
					for($i = $start; $i <= $end; $i = strtotime('+1 day', $i)){
						$list_date[] = date(TravelHelper::getDateFormat(), $i);
					}
				}	
			}
			return $list_date;
		}

		static function _getDisableCustomDateRental($rental_id, $month, $month2, $year, $year2){
			$date1 = $year.'-'.$month.'-01';
			$date2 = strtotime($year2.'-'.$month2.'-01');
			$date2 = date('Y-m-t',$date2);
			global $wpdb;
			$sql = "
			SELECT
				`check_in`,
				`check_out`,
				`number`,
				`status`
			FROM
				{$wpdb->prefix}st_availability
			WHERE
				post_id = {$rental_id}

			AND (
				(
					'{$date1}' < DATE_FORMAT(FROM_UNIXTIME(check_in), '%Y-%m-%d')
					AND '{$date2}' > DATE_FORMAT(FROM_UNIXTIME(check_out), '%Y-%m-%d')
				)
				OR (
					'{$date1}' BETWEEN DATE_FORMAT(FROM_UNIXTIME(check_in), '%Y-%m-%d')
					AND DATE_FORMAT(FROM_UNIXTIME(check_out), '%Y-%m-%d')
				)
				OR (
					'$date2' BETWEEN DATE_FORMAT(FROM_UNIXTIME(check_in), '%Y-%m-%d')
					AND DATE_FORMAT(FROM_UNIXTIME(check_out), '%Y-%m-%d')
				)
			)";
			$results = $wpdb->get_results($sql);

			$list_date = array();

			$start = strtotime($date1);
			$end = strtotime($date2);
			if(is_array($results) && count($results)){
				for($i = $start; $i <= $end; $i = strtotime('+1 day', $i)){
					$in_date = false;
					foreach($results as $key => $val){
						$status = $val->status;
						if($i == $val->check_in && $i == $val->check_out){
							if($status == 'unavailable'){
								$date = $i;
							}else{
								unset($date);
							}
							if(!$in_date){
								$in_date = true;
							}
						}
					}

					if($in_date && isset($date)){
						$list_date[] = date(TravelHelper::getDateFormat(), $date);
						unset($date);
					}
				}
			}
			return $list_date;
		}

		public function _add_custom_price(){
			global $wpdb;
			$check_in = STInput::request('calendar_check_in', '');
			$check_out = STInput::request('calendar_check_out', '');
			if(empty($check_in) || empty($check_out)){
				echo json_encode(array(
						'type' => 'error',
						'status' => 0,
						'message' => __('The check in or check out field is not empty.', ST_TEXTDOMAIN)
					));
				die();
			}
			$check_in = strtotime($check_in);
			$check_out = strtotime($check_out);
			if($check_in > $check_out){
				echo json_encode(array(
						'type' => 'error',
						'status' => 0,
						'message' => __('The check out is later than the check in field.', ST_TEXTDOMAIN)
					));
				die();
			}
			
			$status = STInput::request('calendar_status', 'available');
			if($status == 'available'){
				if(filter_var($_POST['calendar_price'], FILTER_VALIDATE_FLOAT) === false){
					echo json_encode(array(
							'type' => 'error',
							'status' => 0,
							'message' => __('The price field is not a number.', ST_TEXTDOMAIN)
						));
					die();
				}
			}
			$price = floatval(STInput::request('calendar_price', ''));
			$post_id = STInput::request('calendar_post_id', '');

			for($i = $check_in; $i <= $check_out; $i = strtotime('+1 day', $i)){
				$hotels = self::_getdataHotel($post_id, $i, $i);
				if(is_array($hotels) && count($hotels)){
					foreach($hotels as $key => $val){
						if($i == $val->check_in && $i == $val->check_out){
							$data = array(
								'price' => $price,
								'status' => $status,
							);
							$where = array(
								'id' => $val->id
							);
							self::_updateData($where, $data);
						}else{
							$data = array(
								'post_id' => $post_id,
								'post_type' => 'hotel_room',
								'check_in' => $i,
								'check_out' => $i,
								'price' => $price,
								'status' => $status,
							);
							self::_insertData($data);
						}
					}
				}else{
					$data = array(
						'post_id' => $post_id,
						'post_type' => 'hotel_room',
						'check_in' => $i,
						'check_out' => $i,
						'price' => $price,
						'status' => $status,
					);
					self::_insertData($data);
				}
			}

			for($i = $check_in; $i <= $check_out; $i = strtotime('+1 day', $i)){
				
			}
			echo json_encode(array(
				'type' => 'success',
				'status' => 1,
				'message' => __('Successfully', ST_TEXTDOMAIN)
			));
			die();
		}

		public function _add_custom_price_rental(){
			global $wpdb;
			$check_in = STInput::request('calendar_check_in', '');
			$check_out = STInput::request('calendar_check_out', '');
			if(empty($check_in) || empty($check_out)){
				echo json_encode(array(
						'type' => 'error',
						'status' => 0,
						'message' => __('The check in or check out field is not empty.', ST_TEXTDOMAIN)
					));
				die();
			}
			$check_in = strtotime($check_in);
			$check_out = strtotime($check_out);
			if($check_in > $check_out){
				echo json_encode(array(
						'type' => 'error',
						'status' => 0,
						'message' => __('The check out is later than the check in field.', ST_TEXTDOMAIN)
					));
				die();
			}
			
			$status = STInput::request('calendar_status', 'available');
			if($status == 'available'){
				if(filter_var($_POST['calendar_price'], FILTER_VALIDATE_FLOAT) === false){
					echo json_encode(array(
							'type' => 'error',
							'status' => 0,
							'message' => __('The price field is not a number.', ST_TEXTDOMAIN)
						));
					die();
				}
			}
			$price = floatval(STInput::request('calendar_price', ''));
			$post_id = STInput::request('calendar_post_id', '');

			for($i = $check_in; $i <= $check_out; $i = strtotime('+1 day', $i)){
				$hotels = self::_getdataRental($post_id, $i, $i);
				if(is_array($hotels) && count($hotels)){
					foreach($hotels as $key => $val){
						if($i == $val->check_in && $i == $val->check_out){
							$data = array(
								'price' => $price,
								'status' => $status,
							);
							$where = array(
								'id' => $val->id
							);
							self::_updateData($where, $data);
						}else{
							$data = array(
								'post_id' => $post_id,
								'post_type' => 'st_rental',
								'check_in' => $i,
								'check_out' => $i,
								'price' => $price,
								'status' => $status,
							);
							self::_insertData($data);
						}
					}
				}else{
					$data = array(
						'post_id' => $post_id,
						'post_type' => 'st_rental',
						'check_in' => $i,
						'check_out' => $i,
						'price' => $price,
						'status' => $status,
					);
					self::_insertData($data);
				}
			}

			for($i = $check_in; $i <= $check_out; $i = strtotime('+1 day', $i)){
				
			}
			echo json_encode(array(
				'type' => 'success',
				'status' => 1,
				'message' => __('Successfully', ST_TEXTDOMAIN)
			));
			die();
		}
		public function _add_custom_price_tour(){
			global $wpdb;
			$check_in = STInput::request('calendar_check_in', '');
			$check_out = STInput::request('calendar_check_out', '');
			if(empty($check_in) || empty($check_out)){
				echo json_encode(array(
						'type' => 'error',
						'status' => 0,
						'message' => __('The check in or check out field is not empty.', ST_TEXTDOMAIN)
					));
				die();
			}
			$check_in = strtotime($check_in);
			$check_out = strtotime($check_out);
			if($check_in > $check_out){
				echo json_encode(array(
						'type' => 'error',
						'status' => 0,
						'message' => __('The check out is later than the check in field.', ST_TEXTDOMAIN)
					));
				die();
			}

			$status = STInput::request('calendar_status', 'available');
			if($status == 'available'){
				if(filter_var($_POST['calendar_adult_price'], FILTER_VALIDATE_FLOAT) === false){
					echo json_encode(array(
							'type' => 'error',
							'status' => 0,
							'message' => __('The adult price field is not a number.', ST_TEXTDOMAIN)
						));
					die();
				}

				if(filter_var($_POST['calendar_child_price'], FILTER_VALIDATE_FLOAT) === false){
					echo json_encode(array(
							'type' => 'error',
							'status' => 0,
							'message' => __('The children price field is not a number.', ST_TEXTDOMAIN)
						));
					die();
				}

				if(filter_var($_POST['calendar_infant_price'], FILTER_VALIDATE_FLOAT) === false){
					echo json_encode(array(
							'type' => 'error',
							'status' => 0,
							'message' => __('The infant price field is not a number.', ST_TEXTDOMAIN)
						));
					die();
				}
			}

			$adult_price = floatval(STInput::request('calendar_adult_price', ''));
			$child_price = floatval(STInput::request('calendar_child_price', ''));
			$infant_price = floatval(STInput::request('calendar_infant_price', ''));
			$priority = floatval(STInput::request('calendar_priority', ''));
			$post_id = STInput::request('calendar_post_id', '');
			$groupday = STInput::request('calendar_groupday', '');
			for($i = $check_in; $i <= $check_out; $i = strtotime('+1 day', $i)){
				$list_tour = self::_getdataTourEachDate($post_id, $i, $i);
				if(is_array($list_tour) && count($list_tour)){
					foreach($list_tour as $key => $val){
						if($i == $val->check_in){
							if(intval($val->check_out) - intval($val->check_in) == 0){
								self::_deleteData($val->id);
							}else{
								$group_day = 1;
								if(strtotime('+1 day', $val->check_in) == $val->check_out){
									$group_day = 0;
								}
								$data = array(
									'check_in' => strtotime('+1 day', $val->check_in),
									'groupday' => $group_day
								);
								$where = array(
									'id' => $val->id
								);

								self::_updateData($where, $data);
							}
							
						}elseif($i == $val->check_out && $val->check_out != $val->check_in){
							$group_day = 1;
							if(strtotime('-1 day', $val->check_out) == $val->check_in){
								$group_day = 0;
							}
							$data = array(
								'check_out' => strtotime('-1 day', $val->check_out),
								'groupday' => $group_day
							);

							$where = array(
								'id' => $val->id
							);

							self::_updateData($where, $data);
						}elseif($i > $val->check_in && $i < $val->check_out){
							$group_day = 1;
							if($val->check_in == strtotime('-1 day', $i)){
								$group_day = 0;
							}
							$data = array(
								'check_out' => strtotime('-1 day', $i),
								'groupday' => $group_day
							);
							$where = array(
								'id' => $val->id
							);
							self::_updateData($where, $data);

							$group_day = 1;
							if(strtotime('+1 day', $i) == $val->check_out){
								$group_day = 0;
							}
							$data = array(
								'post_id' => $val->post_id,
								'post_type' => $val->post_type,
								'check_in' => strtotime('+1 day', $i),
								'check_out' => $val->check_out,
								'adult_price' => $val->adult_price,
								'child_price' => $val->child_price,
								'infant_price' => $val->infant_price,
								'groupday' => $group_day,
								'status' => $val->status,
							);
							self::_insertData($data);
						}
					}
				}
			}
			if(intval($groupday) == 1){
				$data = array(
					'post_id' => $post_id,
					'post_type' => 'st_tours',
					'check_in' => $check_in,
					'check_out' => $check_out,
					'adult_price' => $adult_price,
					'child_price' => $child_price,
					'infant_price' => $infant_price,
					'status' => $status,
					'groupday' => $groupday,
				);
				$table = $wpdb->prefix.'st_availability';
				$wpdb->insert($table, $data);
				if($wpdb->insert_id <= 0){
					echo json_encode(array(
						'type' => 'error',
						'status' => 0,
						'message' => $wpdb->show_errors()
					));
					die();
				}
			}else{
				for($i = $check_in; $i <= $check_out; $i = strtotime('+1 day', $i)){
					$data = array(
						'post_id' => $post_id,
						'post_type' => 'st_tours',
						'check_in' => $i,
						'check_out' => $i,
						'adult_price' => $adult_price,
						'child_price' => $child_price,
						'infant_price' => $infant_price,
						'status' => $status,
						'groupday' => $groupday,
					);
					$table = $wpdb->prefix.'st_availability';
					$wpdb->insert($table, $data);
					if($wpdb->insert_id <= 0){
						echo json_encode(array(
							'type' => 'error',
							'status' => 0,
							'message' => $wpdb->show_errors()
						));
						die();
					}
				}
			}
			echo json_encode(array(
				'type' => 'success',
				'status' => 1,
				'message' => __('Successfully', ST_TEXTDOMAIN)
			));
			die();
		}

		static function _insertData($data = array()){
			global $wpdb;
			$table = $wpdb->prefix.'st_availability';
			$wpdb->insert($table, $data);
		}

		static function _updateData($where = array(), $data = array()){
			global $wpdb;
			$table = $wpdb->prefix.'st_availability';
			$wpdb->update($table, $data, $where);
		}

		static function _deleteData($id){
			global $wpdb;
			$table = $wpdb->prefix.'st_availability';
			$where = array(
				'id' => $id
			);
			$wpdb->delete($table, $where);
		}

		/**
		*@param $data is array, has ('check_in', 'check_out') object field
		**/
		static function getMinMaxFromData($data = array()){
			$minmax = array(
				'min' => 0,
				'max' => 0
			);
			if(is_array($data) && count($data)){
				foreach($data as $key => $val){
					if($minmax['min'] == 0) $minmax['min']  = intval($val->$check_in);
					if($minmax['min'] > intval($val->check_in)) $minmax['min'] = intval($val->check_in);
					if($minmax['min'] > intval($val->check_out)) $minmax['min'] = intval($val->check_out);
					if($minmax['max'] == 0) $minmax['max']  = intval($val->$check_in);
					if($minmax['max'] < intval($val->check_in)) $minmax['max'] = intval($val->check_in);
					if($minmax['max'] < intval($val->check_out)) $minmax['max'] = intval($val->check_out);
				}
			}

			return $minmax;
		}
	}

	new AvailabilityHelper();
}