<?php 
/**
*@since 1.1.8
**/
if(!class_exists('STDuplicateData')){
  class STDuplicateData extends STAdmin{

    public function __construct(){
      add_action('admin_menu', array($this,'_register_duplicate_submenu_page'), 50);
      add_action('admin_enqueue_scripts', array($this,'_add_scripts'));

      add_action('wp_ajax_st_duplicate_ajax',array($this,'_duplicate_ajax'));
      add_action('init', array($this, '_create_security'));
      add_action('after_setup_theme', array($this, '_create_table'));
    }
    public function _create_table(){
      global $wpdb;
      $post_types = array('st_hotel', 'st_rental', 'st_cars', 'st_tours', 'st_activity');
      foreach($post_types as $post_type){
        if(!$this->isset_table($post_type)){
          $this->_stCreateTable($post_type);
        }
      }
    }
    public function isset_table($table_name = ''){
      global $wpdb;
      $table = $wpdb->prefix . $table_name;
      if($wpdb->get_var("SHOW TABLES LIKE '{$table}'") != $table){
        return false;
      }
      return true;
    }
    public function _add_scripts(){
      wp_enqueue_script('st-duplicate', get_template_directory_uri().'/js/st-duplicate.js', array('jquery'), '1.1.8', true);
    }

    public function _create_security(){
      $ajax_nonce = wp_create_nonce( "st_duplicate_string" );
      wp_localize_script('jquery','st_duplicate_string',array(
              'string' => $ajax_nonce
          ));
    }
    public function _register_duplicate_submenu_page(){
      add_submenu_page(apply_filters('ot_theme_options_menu_slug','st_traveler_options'), __('Upgrade Data', ST_TEXTDOMAIN), __('Upgrade Data', ST_TEXTDOMAIN), 'manage_options', 'st-upgrade-data', array($this,'_st_duplicate_data_content' ));
    }

    public function _st_duplicate_data_content(){
      echo balanceTags($this->load_view('duplicate_data/index', false)); 
    }

    public function _duplicate_ajax($oneclick = false){
      if((isset($_POST['name']) && $_POST['name'] == 'st_allow_duplicate' && check_ajax_referer( 'st_duplicate_string', 'security' ) == 1) || $oneclick){
        // Delete table if exist
        if($this->stDeleteTable()){ // if success
          // Create table
          if($this->stCreateTable()){
            if($this->stDuplicateData()){
              update_option('st_duplicated_data', 'duplicated');
              // Allow other plugin can do when our theme update
              do_action('st_traveler_do_upgrade_table');

              $result = array(
                'status' => 1,
                'msg' => 'Finished successfully!'
              );
              echo json_encode($result);
            }else{
              $result = array(
                'status' => 0,
                'msg' => 'An error has occurred during process (update new data). Please try again!'
              );
              echo json_encode($result);
            }
          }else{
            $result = array(
              'status' => 0,
              'msg' => 'An error has occurred during process (create new table). Please try again!'
            );
            echo json_encode($result);
          }
        }else{
          $result = array(
            'status' => 0,
            'msg' => 'An error has occurred during process (delete draft data). Please try again!'
          );
          echo json_encode($result);
        }
      }
      if(!$oneclick){
        die();
      }
    }
    protected function stDeleteTable(){
      $post_types = array('st_hotel', 'st_rental', 'st_cars', 'st_tours', 'st_activity');
      $result = true;
      foreach($post_types as $post_type){
        $result = $this->__stDeleteTable($post_type);
        if(false === $result) return $result;
      }
      return $result;
    }
    protected function __stDeleteTable($post_type){
      global $wpdb;
      $table = $wpdb->prefix.$post_type;
      $sql = "DROP TABLE IF EXISTS {$table}";
      $result = $wpdb->query($sql);
      return $result;
    }

    protected function stCreateTable(){
            $post_type = array(
                'st_hotel', 'st_rental', 'st_cars', 'st_tours', 'st_activity' 
            );
            $result  = true;
            foreach($post_type as $item){
                $result = $this->_stCreateTable($item);
                if($result == false) return $result;
            }
            return $result;
        }

        protected function _stCreateTable($post_type = 'st_hotel'){
            global $wpdb;
            $charset_collate = $wpdb->get_charset_collate();

            $table_name = $wpdb->prefix . $post_type; 

            if($post_type == 'st_hotel'){
                $sql = "
                    CREATE TABLE {$table_name} (
                      post_id bigint(20) NOT NULL,
                      multi_location longtext ,
                      id_location longtext,
                      address longtext,
                      allow_full_day longtext,
                      rate_review longtext,
                      hotel_star longtext,
                      price_avg longtext,
                      hotel_booking_period longtext,
                      map_lat longtext,
                      map_lng longtext,
                      UNIQUE KEY post_id (post_id)
                    ) $charset_collate;
                ";
            }elseif($post_type == 'st_rental'){
                $sql = "
                    CREATE TABLE {$table_name} (
                      post_id bigint(20) NOT NULL,
                      multi_location longtext ,
                      location_id longtext,
                      address longtext,
                      rental_max_adult longtext,
                      rental_max_children longtext,
                      rate_review longtext,
                      sale_price longtext,
                      rentals_booking_period longtext,
                      UNIQUE KEY post_id (post_id)
                    ) $charset_collate;
                ";
            }elseif($post_type == 'st_cars'){
                $sql = "
                    CREATE TABLE {$table_name} (
                      post_id bigint(20) NOT NULL,
                      multi_location longtext ,
                      id_location longtext,
                      cars_address longtext,
                      cars_price longtext,
                      sale_price longtext,
                      number_car longtext,
                      cars_booking_period longtext,
                      UNIQUE KEY post_id (post_id)
                    ) $charset_collate;
                ";
            }elseif($post_type == 'st_tours'){
              $sql = "
                    CREATE TABLE {$table_name} (
                      post_id bigint(20) NOT NULL,
                      multi_location longtext ,
                      id_location longtext,
                      address longtext,
                      price longtext,
                      sale_price longtext,
                      child_price longtext,
                      adult_price longtext,
                      infant_price longtext,
                      max_people longtext,
                      type_tour longtext,
                      check_in longtext,
                      check_out longtext,
                      rate_review longtext,
                      duration_day longtext,
                      tours_booking_period longtext,
                      UNIQUE KEY post_id (post_id)
                    ) $charset_collate;
                ";
            }elseif($post_type == 'st_activity'){
                $sql = "
                    CREATE TABLE {$table_name} (
                      post_id bigint(20) NOT NULL,
                      multi_location longtext ,
                      id_location longtext,
                      address longtext,
                      price longtext,
                      sale_price longtext,
                      child_price longtext,
                      adult_price longtext,
                      infant_price longtext,
                      type_activity longtext,
                      check_in longtext,
                      check_out longtext,
                      rate_review longtext,
                      activity_booking_period longtext,
                      max_people longtext,
                      duration longtext,
                      UNIQUE KEY post_id (post_id)
                    ) $charset_collate;
                ";
            }
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            return dbDelta( $sql );
        }

        protected function stDuplicateData(){
            $post_type = array(
                'st_hotel', 'st_rental', 'st_cars', 'st_tours', 'st_activity'
            );
            $result = true;
            foreach($post_type as $item){
                $result = $this->_stDuplicateData($item);
                if($result == false) return $result;
            }
            return $result;
        }


        protected function _stDuplicateData($post_type = 'st_hotel'){
          global $wpdb;
            $sql_count = "
              SELECT ID FROM {$wpdb->prefix}posts WHERE post_type='{$post_type}' GROUP BY ID
            ";
            if($post_type == 'st_hotel'){
                $sql ="
                    SELECT {$wpdb->prefix}postmeta.post_id, {$wpdb->prefix}postmeta.meta_key, {$wpdb->prefix}postmeta.meta_value from {$wpdb->prefix}postmeta, {$wpdb->prefix}posts
                        WHERE {$wpdb->prefix}postmeta.post_id = {$wpdb->prefix}posts.ID
                        and {$wpdb->prefix}posts.post_type='{$post_type}'
                        and {$wpdb->prefix}posts.post_status='publish'
                        and (
                        meta_key='multi_location'
                        or meta_key='id_location'
                        or meta_key='address'
                        or meta_key='allow_full_day'
                        or meta_key='rate_review'
                        or meta_key='hotel_star'
                        or meta_key='price_avg'
                        or meta_key='hotel_booking_period'
                        or meta_key='map_lat'
                        or meta_key='map_lng'
                        )
                ";

                $fields = array(
                    'post_id',
                    'multi_location', 
                    'id_location', 
                    'address', 
                    'allow_full_day', 
                    'rate_review', 
                    'hotel_star', 
                    'price_avg',
                    'hotel_booking_period',
                    'map_lat',
                    'map_lng',
                );
            }elseif($post_type == 'st_rental'){
                $sql ="
                SELECT {$wpdb->prefix}postmeta.post_id, {$wpdb->prefix}postmeta.meta_key, {$wpdb->prefix}postmeta.meta_value from {$wpdb->prefix}postmeta, {$wpdb->prefix}posts
                    WHERE {$wpdb->prefix}postmeta.post_id = {$wpdb->prefix}posts.ID
                        and {$wpdb->prefix}posts.post_type='{$post_type}'
                        and {$wpdb->prefix}posts.post_status='publish'
                        and (
                        meta_key='multi_location'
                        or meta_key='location_id'
                        or meta_key='address'
                        or meta_key='rental_max_adult'
                        or meta_key='rental_max_children'
                        or meta_key='rate_review' 
                        or meta_key='sale_price'
                        or meta_key='rentals_booking_period'
                        )
                ";

                $fields = array(
                    'post_id',
                    'multi_location', 
                    'location_id', 
                    'address', 
                    'rental_max_adult', 
                    'rental_max_children', 
                    'rate_review',
                    'sale_price',
                    'rentals_booking_period',
                ); 
            }elseif($post_type == 'st_cars'){
                $sql ="
                SELECT {$wpdb->prefix}postmeta.post_id, {$wpdb->prefix}postmeta.meta_key, {$wpdb->prefix}postmeta.meta_value from {$wpdb->prefix}postmeta, {$wpdb->prefix}posts
                    WHERE {$wpdb->prefix}postmeta.post_id = {$wpdb->prefix}posts.ID
                        and {$wpdb->prefix}posts.post_type='{$post_type}'
                        and {$wpdb->prefix}posts.post_status='publish'
                        and (
                        meta_key='multi_location'
                        or meta_key='id_location'
                        or meta_key='cars_address'
                        or meta_key='cars_price'
                        or meta_key='sale_price'
                        or meta_key='number_car'
                        or meta_key='cars_booking_period'
                        )
                ";

                $fields = array(
                    'post_id',
                    'multi_location', 
                    'id_location', 
                    'cars_address', 
                    'cars_price',
                    'sale_price',
                    'number_car',
                    'cars_booking_period'
                );  
            }elseif($post_type == 'st_tours'){
                $sql ="
                SELECT {$wpdb->prefix}postmeta.post_id, {$wpdb->prefix}postmeta.meta_key, {$wpdb->prefix}postmeta.meta_value from {$wpdb->prefix}postmeta, {$wpdb->prefix}posts
                    WHERE {$wpdb->prefix}postmeta.post_id = {$wpdb->prefix}posts.ID
                        and {$wpdb->prefix}posts.post_type='{$post_type}'
                        and {$wpdb->prefix}posts.post_status='publish'
                        and (
                        meta_key='multi_location'
                        or meta_key='id_location'
                        or meta_key='address'
                        or meta_key='price'
                        or meta_key='sale_price'
                        or meta_key='child_price'
                        or meta_key='adult_price'
                        or meta_key='infant_price'
                        or meta_key='max_people'
                        or meta_key='type_tour'
                        or meta_key='check_in'
                        or meta_key='check_out'
                        or meta_key='rate_review'
                        or meta_key='duration_day'
                        or meta_key='tours_booking_period'
                        )
                ";

                $fields = array(
                    'post_id',
                    'multi_location', 
                    'id_location', 
                    'address',
                    'price',
                    'sale_price',
                    'adult_price',
                    'child_price',
                    'infant_price',
                    'max_people',
                    'type_tour',
                    'check_in',
                    'check_out',
                    'rate_review',
                    'duration_day',
                    'tours_booking_period'
                );
            }elseif($post_type == 'st_activity'){
                $sql ="
                SELECT {$wpdb->prefix}postmeta.post_id, {$wpdb->prefix}postmeta.meta_key, {$wpdb->prefix}postmeta.meta_value from {$wpdb->prefix}postmeta, {$wpdb->prefix}posts
                    WHERE {$wpdb->prefix}postmeta.post_id = {$wpdb->prefix}posts.ID
                        and {$wpdb->prefix}posts.post_type='{$post_type}'
                        and {$wpdb->prefix}posts.post_status='publish'
                        and (
                        meta_key='multi_location'
                        or meta_key='id_location'
                        or meta_key='address'
                        or meta_key='price'
                        or meta_key='sale_price'
                        or meta_key='child_price'
                        or meta_key='adult_price'
                        or meta_key='infant_price'
                        or meta_key='type_activity'
                        or meta_key='check_in'
                        or meta_key='check_out'
                        or meta_key='rate_review'
                        or meta_key='activity_booking_period'
                        or meta_key='max_people'
                        or meta_key='duration'
                        )
                ";

                $fields = array(
                    'post_id',
                    'multi_location', 
                    'id_location', 
                    'address',
                    'price',
                    'sale_price',
                    'child_price',
                    'adult_price',
                    'infant_price',
                    'type_activity',
                    'check_in',
                    'check_out',
                    'rate_review',
                    'activity_booking_period',
                    'max_people',
                    'duration'
                );
            }

            $number = 1000;
            $id = $wpdb->get_col($sql_count);
            $count = count($id);
            if($count > 0){
              $i = 0;
              while($i <= $count){
                $now = ($i + $number);
                if($now >= $count) $now = $count;
                $in = "";
                for($j = $i; $j < $now; $j++){
                  if(empty($in)){
                    $in .= "'".$id[$j]."'";
                  }else{
                    $in .= ",'".$id[$j]."'";
                  }
                }
                $limit = " AND ID IN ({$in})  ORDER BY ID";
                $q = $sql.$limit;
                $result = $wpdb->get_results($q);
                $list_value = array();
                if(is_array($result) && count($result)){
                  foreach($result as $val){
                      $list_value[$val->post_id][$val->meta_key]  = $val->meta_value;
                  }
                }
                $this->_stSaveData($post_type, $fields, $list_value);

                $i += $number ;
              }
            }
            
            return true;
        }

        protected function _stSaveData($post_type = '', $fields = array(), $data = array()){
            global $wpdb;
            $table = $wpdb->prefix.$post_type;

            $field = implode(',', $fields);
            $field = '('.$field.')';
            
            $values = array();
            foreach($data as $key => $value){
                $values[] = $this->_stGetStringInsert($fields, $key, $value);
            }
            if(is_array($values) && count($values)){
              $sql = "INSERT INTO {$table} {$field} VALUES ".implode(',',$values)."";
              $wpdb->query($sql);
            }
        }

        protected function _stGetStringInsert($fields, $key, $data){
            $string = array();
            $string[0] = "'".$key."'";
            for($i = 1; $i < count($fields); $i++){
                $v = esc_sql($this->_getKeyArray($fields[$i], $data));
                $string[$i] = "'".$v."'";
            }
            $return = '('.implode(',',$string).')';
            return $return;
        }
         
        protected function _getKeyArray($key, $array){
            if(array_key_exists($key, $array)){
                return $array[$key];
            }else{
                return "";
            }
        }
  }
}
$st_duplicate_data = new STDuplicateData();
?>