<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Class Traver Helper
 *
 * Created by ShineTheme
 *
 */
if(!class_exists('TravelHelper'))
{

    class TravelHelper{
        public static $all_currency;
        static function st_admin_notice_post_draft_fc(){
            if(current_user_can('manage_options')){
                add_action( 'admin_notices', array(__CLASS__, 'st_admin_notice_post_draft' ), 99999); 
            }
        }
        static function  init()
        { 
            add_filter('st_get_post_id_origin', array(__CLASS__, 'getPostIdOrigin'));
            add_action( 'init',array(__CLASS__,'location_session'),1 );
            add_action('init',array(__CLASS__,'change_current_currency'));

            add_action('init',array(__CLASS__,'setLocationBySession'), 100);
            add_action('init',array(__CLASS__,'getListLocation'), 51);

            add_action('init',array(__CLASS__,'setListFullNameLocation'), 100);

            add_action('wp_ajax_st_format_money',array(__CLASS__,'_format_money'));
            add_action('wp_ajax_nopriv_st_format_money',array(__CLASS__,'_format_money'));
            add_action('wp_ajax_st_getBookingPeriod', array(__CLASS__,'getBookingPeriod'), 9999);
            add_action('wp_ajax_nopriv_st_getBookingPeriod', array(__CLASS__,'getBookingPeriod'), 9999);

            self::$all_currency=array (
                'ALL' => 'Albania Lek',
                'AFN' => 'Afghanistan Afghani',
                'ARS' => 'Argentina Peso',
                'AWG' => 'Aruba Guilder',
                'AUD' => 'Australia Dollar',
                'AZN' => 'Azerbaijan New Manat',
                'BSD' => 'Bahamas Dollar',
                'BBD' => 'Barbados Dollar',
                'BDT' => 'Bangladeshi taka',
                'BYR' => 'Belarus Ruble',
                'BZD' => 'Belize Dollar',
                'BMD' => 'Bermuda Dollar',
                'BOB' => 'Bolivia Boliviano',
                'BAM' => 'Bosnia and Herzegovina Convertible Marka',
                'BWP' => 'Botswana Pula',
                'BGN' => 'Bulgaria Lev',
                'BRL' => 'Brazil Real',
                'BND' => 'Brunei Darussalam Dollar',
                'KHR' => 'Cambodia Riel',
                'CAD' => 'Canada Dollar',
                'KYD' => 'Cayman Islands Dollar',
                'CLP' => 'Chile Peso',
                'CNY' => 'China Yuan Renminbi',
                'COP' => 'Colombia Peso',
                'CRC' => 'Costa Rica Colon',
                'HRK' => 'Croatia Kuna',
                'CUP' => 'Cuba Peso',
                'CZK' => 'Czech Republic Koruna',
                'DKK' => 'Denmark Krone',
                'DOP' => 'Dominican Republic Peso',
                'XCD' => 'East Caribbean Dollar',
                'EGP' => 'Egypt Pound',
                'SVC' => 'El Salvador Colon',
                'EEK' => 'Estonia Kroon',
                'EUR' => 'Euro Member Countries',
                'FKP' => 'Falkland Islands (Malvinas) Pound',
                'FJD' => 'Fiji Dollar',
                'GHC' => 'Ghana Cedis',
                'GIP' => 'Gibraltar Pound',
                'GTQ' => 'Guatemala Quetzal',
                'GGP' => 'Guernsey Pound',
                'GYD' => 'Guyana Dollar',
                'HNL' => 'Honduras Lempira',
                'HKD' => 'Hong Kong Dollar',
                'HUF' => 'Hungary Forint',
                'ISK' => 'Iceland Krona',
                'INR' => 'India Rupee',
                'IDR' => 'Indonesia Rupiah',
                'IRR' => 'Iran Rial',
                'IMP' => 'Isle of Man Pound',
                'ILS' => 'Israel Shekel',
                'JMD' => 'Jamaica Dollar',
                'JPY' => 'Japan Yen',
                'JEP' => 'Jersey Pound',
                'KZT' => 'Kazakhstan Tenge',
                'KPW' => 'Korea (North) Won',
                'KRW' => 'Korea (South) Won',
                'KGS' => 'Kyrgyzstan Som',
                'LAK' => 'Laos Kip',
                'LVL' => 'Latvia Lat',
                'LBP' => 'Lebanon Pound',
                'LRD' => 'Liberia Dollar',
                'LTL' => 'Lithuania Litas',
                'MKD' => 'Macedonia Denar',
                'MYR' => 'Malaysia Ringgit',
                'MUR' => 'Mauritius Rupee',
                'MXN' => 'Mexico Peso',
                'MNT' => 'Mongolia Tughrik',
                'MAD' => 'Morocco Dirhams',
                'MZN' => 'Mozambique Metical',
                'NAD' => 'Namibia Dollar',
                'NPR' => 'Nepal Rupee',
                'ANG' => 'Netherlands Antilles Guilder',
                'NZD' => 'New Zealand Dollar',
                'NIO' => 'Nicaragua Cordoba',
                'NGN' => 'Nigeria Naira',
                'NOK' => 'Norway Krone',
                'OMR' => 'Oman Rial',
                'PKR' => 'Pakistan Rupee',
                'PAB' => 'Panama Balboa',
                'PYG' => 'Paraguay Guarani',
                'PEN' => 'Peru Nuevo Sol',
                'PHP' => 'Philippines Peso',
                'PLN' => 'Poland Zloty',
                'QAR' => 'Qatar Riyal',
                'RON' => 'Romania New Leu',
                'RUB' => 'Russia Ruble',
                'SHP' => 'Saint Helena Pound',
                'SAR' => 'Saudi Arabia Riyal',
                'RSD' => 'Serbia Dinar',
                'SCR' => 'Seychelles Rupee',
                'SGD' => 'Singapore Dollar',
                'SBD' => 'Solomon Islands Dollar',
                'SOS' => 'Somalia Shilling',
                'ZAR' => 'South Africa Rand',
                'LKR' => 'Sri Lanka Rupee',
                'SEK' => 'Sweden Krona',
                'CHF' => 'Switzerland Franc',
                'SRD' => 'Suriname Dollar',
                'SYP' => 'Syria Pound',
                'TWD' => 'Taiwan New Dollar',
                'THB' => 'Thailand Baht',
                'TTD' => 'Trinidad and Tobago Dollar',
                'TRY' => 'Turkey Lira',
                'TRL' => 'Turkey Lira',
                'TVD' => 'Tuvalu Dollar',
                'UAH' => 'Ukraine Hryvna',
                'AED' => 'United Arab Emirates',
                'GBP' => 'United Kingdom Pound',
                'USD' => 'United States Dollar',
                'UYU' => 'Uruguay Peso',
                'UZS' => 'Uzbekistan Som',
                'VEF' => 'Venezuela Bolivar',
                'VND' => 'Viet Nam Dong',
                'YER' => 'Yemen Rial',
                'ZWD' => 'Zimbabwe Dollar'
            );
        }
        /**
         *
         *
         * @since 1.1.3
         * */
        static function _format_money()
        {
            $data=STInput::post('money_data',array());

            if(!empty($data))
            {
                foreach($data as $key=>$value){
                    $data[$key]=TravelHelper::format_money($value);
                }
            }

            echo json_encode(
                array(
                    'status'=>1,
                    'money_data'=>$data
                )
            );
            die;
        }


        static function ot_all_currency()
        {
            $a=array();

            foreach(self::$all_currency as $key=>$value)
            {
                $a[]=array(
                    'value'=>$key,
                    'label'=>$value.'('.$key.' )'
                );
            }

            return $a;
        }

        /**
         * @todo Setup Session
         *
         *
         * */
        static function location_session () {
            if(!session_id()) {
                session_start();
            }
        }


        /**
         * Return All Currencies
         *
         *
         * */
        static function get_currency($theme_option=false)
        {

            $all= apply_filters('st_booking_currency', st()->get_option('booking_currency'));

            //return array for theme options choise
            if($theme_option){
                $choice=array();

                if(!empty($all) and is_array($all))
                {


                    foreach($all as $key=>$value)
                    {
                        $choice[]=array(

                            'label'=>$value['title'],
                            'value'=>$value['name']
                        );
                    }

                }
                return $choice;
            }
            return $all;
        }




        /**
         * return Default Currency
         *
         *
         * */
        static function get_default_currency($need=false)
        {

            $primary = st()->get_option('booking_primary_currency');

            $primary_obj=self::find_currency($primary);

            if($primary_obj )
            {
                if($need and isset($primary_obj[$need])) return $primary_obj[$need];
                return $primary_obj;
            }else{
                //If user dont set the primary currency, we take the first of list all currency
                $all_currency=self::get_currency();



                if(isset($all_currency[0])){
                    if($need and isset($all_currency[0][$need])) return $all_currency[0][$need];
                    return $all_currency[0];
                }
            }


        }

        /**
         * return Current Currency
         *
         *
         * */
        static function get_current_currency($need=false)
        {

            //Check session of user first
            
            if(isset($_SESSION['currency']['name']))
            {
                $name=$_SESSION['currency']['name'];

                if($session_currency=self::find_currency($name))
                {
                    if($need and isset($session_currency[$need])) return $session_currency[$need];
                    return $session_currency;
                }
            }

            return self::get_default_currency($need);
        }


        /**
         * @todo Find currency by name, return false if not found
         *
         *
         * */
        static  function find_currency($currency_name,$compare_key='name')
        {
            $currency_name=esc_attr($currency_name);

            $all_currency=self::get_currency();

            if(!empty($all_currency)){
                foreach($all_currency as $key)
                {
                    if($key[$compare_key]==$currency_name)
                    {
                        return $key;
                    }
                }
            }
            return false;
        }

        /**
         * Change Default Currency
         * @param currency_name
         *
         * */
        /**
         * Change Default Currency
         * @param bool $currency_name
         */
        static function  change_current_currency($currency_name=false)
        {

            if(isset($_GET['currency']) and $_GET['currency'] and $new_currency=self::find_currency($_GET['currency']))
            {
                $_SESSION['currency']=$new_currency;
            }

            if($currency_name and $new_currency=self::find_currency($currency_name))
            {
                $_SESSION['currency']=$new_currency;
            }
        }

        /**
         *
         * Conver money from default currency to current currency
         *
         *
         *
         * */
        static function convert_money($money = false, $rate = false)
        {
            if(!$money) $money=0;
            if(!$rate){
                $current_rate = self::get_current_currency('rate');
                $current      = self::get_current_currency('name');
            
                $default      = self::get_default_currency('name');

                if($current != $default)
                    return $money * $current_rate;
            }else{
                $current_rate = $rate;
                return $money * $current_rate;
            }
            return $money;
        }

        /**
         *
         * Conver money from current currency to default currency
         *
         *
         *
         * */
        static function convert_money_to_default($money=false)
        {
            if(!is_numeric($money)) $money=0;
            if(!$money) $money=0;
            $current_rate=self::get_current_currency('rate');
            $current=self::get_current_currency('name');

            $default=self::get_default_currency('name');

            if($current!=$default)
                return $money/$current_rate;
            return $money;
        }

        /**
         *
         * Format Money
         *@since 1.1.1
         *
         *
         * */
        static function format_money($money=false,$need_convert=true,$precision=0)
        {
            $money=(float)$money;
            $symbol                 =   self::get_current_currency('symbol');
            $precision              =   self::get_current_currency('booking_currency_precision',2);
            $thousand_separator     =   self::get_current_currency('thousand_separator',',');
            $decimal_separator      =   self::get_current_currency('decimal_separator','.');

            if($money == 0 && st()->get_option('show_price_free')=='on'){
                return __("Free",ST_TEXTDOMAIN);
            }

            if($need_convert){
                $money=self::convert_money($money);
            }

            if(is_array($precision)){
                $precision=st()->get_option('booking_currency_precision',2);
            }

            if($precision){
                $money = round($money,2);
            }
            
            $symbol=self::get_current_currency('symbol');

            $template = self::get_current_currency('booking_currency_pos');

            if(!$template)
            {
                $template='left';
            }
            if(is_array($decimal_separator))
            {
                $decimal_separator=st()->get_option('decimal_separator','.');
            }
            if(is_array($thousand_separator))
            {
                $thousand_separator=st()->get_option('thousand_separator',',');
            }
            $money = number_format((float)$money,$precision,$decimal_separator,$thousand_separator);
            
            switch($template)
            {


                case "right":
                    $money_string= $money.$symbol;
                    break;
                case "left_space":
                    $money_string=$symbol." ".$money;
                    break;

                case "right_space":
                    $money_string=$money." ".$symbol;
                    break;
                case "left":
                default:
                    $money_string= $symbol.$money;
                    break;


            }

            return $money_string;

        }

        static function format_money_raw($money = '', $symbol = false ,$precision = 2 , $template =null)
        {
            if($money == 0){
                return __("Free",ST_TEXTDOMAIN);
            }

            if(!$symbol){
                $symbol=self::get_current_currency('symbol');
            }

            if($precision){
                $money = round($money,$precision);
            }
            if (!$template) $template = self::get_current_currency('booking_currency_pos');
            
            if(!$template)
            {
                $template='left';
            }

            $money = number_format((float)$money, $precision);

            switch($template)
            {


                case "right":
                    $money_string= $money.$symbol;
                    break;
                case "left_space":
                    $money_string=$symbol." ".$money;
                    break;

                case "right_space":
                    $money_string=$money." ".$symbol;
                    break;
                case "left":
                default:
                    $money_string= $symbol.$money;
                    break;

            }

            return $money_string;
        }


        static function format_money_from_db($money = '', $symbol = false , $rate = 0, $precision = 2)
        {
            if($money == 0){
                return __("Free",ST_TEXTDOMAIN);
            }

            $money = self::convert_money($money, $rate);

            if(!$symbol){
                $symbol=self::get_current_currency('symbol');
            }

            if($precision){
                $money = round($money,$precision);
            }

            $template = self::get_current_currency('booking_currency_pos');

            if(!$template)
            {
                $template='left';
            }

            $money = number_format((float)$money, $precision);

            switch($template)
            {


                case "right":
                    $money_string= $money.$symbol;
                    break;
                case "left_space":
                    $money_string=$symbol." ".$money;
                    break;

                case "right_space":
                    $money_string=$money." ".$symbol;
                    break;
                case "left":
                default:
                    $money_string= $symbol.$money;
                    break;


            }

            return $money_string;
        }


        static function build_url($name,$value){
            $all=$_GET;
            $current_url=self::current_url();
            $all[$name]=$value;
            return esc_url($current_url.'?'.http_build_query ($all));
        }
        static function build_url_array($key,$name,$value,$add=true){
            $all=$_GET;

            $val=isset($all[$key][$name])?$all[$key][$name]:'';

            if($add)
            {
                if($val)
                    $value_array=explode(',',$val);
                else
                    $value_array=array();
                $value_array[]=$value;

            }else{

                $value_array=explode(',',$val);
                unset($value_array[$value]);
                if(!empty($value_array))
                {
                    foreach($value_array as $k=>$v){
                        if($v==$value) unset( $value_array[$k]);
                    }
                }

            }
            $all[$key][$name]=implode(',',$value_array);

            return esc_html(add_query_arg($all));
        }
        static function build_url_auto_key($key,$value,$add=true){
            global $st_search_page_id;
            $all=$_GET;

            if($st_search_page_id)
            {
                $current_url=get_permalink($st_search_page_id);
            }elseif(is_page()) {
                $current_url=get_permalink();
            }else{
                    $current_url=home_url('/');
                    $current_url=add_query_arg('s',STInput::get('s'),$current_url);
                }

            $val=isset($all[$key])?$all[$key]:'';
            $value_array=array();
            $url=$current_url;

            if($add){

                if($val){
                    $value_array=explode(',',$val);
                }
                $value_array[]=$value;

            }else{

                $value_array=explode(',',$val);
                if(!empty($value_array))
                {
                    foreach($value_array as $k=>$v){
                        if($v==$value) unset( $value_array[$k]);
                    }

                }

            }

            $new_val=implode(',',$value_array);
            if($new_val){
                $all[$key]=$new_val;
            }else{
                $all[$key]='';
            }

            if(STInput::get('paged'))
            {
                $all['paged']='';
            }
            $url= esc_url(add_query_arg($all,$url));

            return $url;
        }

        static function checked_array($key,$need)
        {
            $found=false;
            if(!empty($key))
            {
                foreach($key as $k=>$v){
                    if($need==$v){
                        return true;
                    }
                }
            }

            return $found;
        }

        static function get_time_format(){
            $format = st()->get_option('time_format','true');
            
            return $format;
        }

        /**
        * @since 1.1.1
        **/
        static function convertDateFormat($date){
            
            $format = self::getDateFormat();
            if(!empty($date)){
                $myDateTime = DateTime::createFromFormat($format, $date);
                if($myDateTime)
                return $myDateTime->format('m/d/Y');
            }
            return '';
        }

        /**
        * @since 1.1.1
        **/
        static function getDateFormat(){
            $format = st()->get_option('datetime_format','{mm}/{dd}/{yyyy}');

            $ori_format = array(
                '{d}' => 'j',
                '{dd}' => 'd',
                '{D}' => 'D',
                '{DD}' => 'l',
                '{m}' => 'n',
                '{mm}' => 'm',
                '{M}' => 'M',
                '{MM}' => 'F',
                '{yy}' => 'y',
                '{yyyy}' => 'Y'
            );
            preg_match_all("/({)[a-zA-Z]+(})/", $format, $out);

            $out = $out[0];
            foreach($out as $key => $val){
                foreach($ori_format as $ori_key => $ori_val){
                    if($val == $ori_key){
                        $format = str_replace($val, $ori_val, $format);
                    }
                }
            }
            
            return $format;
        }

        /**
        * @since 1.1.1
        **/
        static function getDateFormatJs(){
            $format = st()->get_option('datetime_format','{mm}/{dd}/{yyyy}');
            $format_js = str_replace(array('{', '}'), '', $format);
            return $format_js;
        }
        static function build_url_muti_array($key,$name,$name_2,$value){
            $all=$_GET;
            $all[$key][$name][$name_2]=$value;
            return add_query_arg($all);
        }

        static function current_url()
        {

            $pageURL = 'http';
            if (isset($_SERVER['HTTPS']) and $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
            $pageURL .= "://";
            if ($_SERVER["SERVER_PORT"] != "80") {
                $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["SCRIPT_NAME"];
            } else {
                $pageURL .= $_SERVER["SERVER_NAME"].parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            }
            $pageURL=rtrim($pageURL,'index.php');
            return $pageURL;
        }

        static function paging($query=false)
        {
            global $wp_query,$st_search_query;
            if($st_search_query){
                $query=$st_search_query;
            }else $query=$wp_query;

            // Don't print empty markup if there's only one page.
            if ( $query->max_num_pages < 2 ) {
                return;
            }

            $paged        = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
            $pagenum_link = html_entity_decode( get_pagenum_link() );
            $query_args   = array();
            $url_parts    = explode( '?', $pagenum_link );

            if ( isset( $url_parts[1] ) ) {
                wp_parse_str( $url_parts[1], $query_args );
            }

            $pagenum_link = esc_url(remove_query_arg( array_keys( $query_args ), $pagenum_link ));
            $pagenum_link = trailingslashit( $pagenum_link ) . '%_%';

            $format  = $GLOBALS['wp_rewrite']->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
            $format .= $GLOBALS['wp_rewrite']->using_permalinks() ? user_trailingslashit( 'page/%#%', 'paged' ) : '?paged=%#%';

            // Set up paginated links.
            $links = paginate_links( array(
                'base'     => $pagenum_link,
                'format'   => $format,
                'total'    => $query->max_num_pages,
                'current'  => $paged,
                'mid_size' => 1,
                // 'add_args' => array_map( 'urlencode', $query_args ),
                'add_args' =>$query_args,
                'prev_text' => __( 'Previous Page', ST_TEXTDOMAIN ),
                'next_text' => __( 'Next Page', ST_TEXTDOMAIN ),
                'type'      =>'list'
            ) );


            if ( $links ) :
                $links=str_replace('page-numbers','pagination', balanceTags ($links));
                $links=str_replace('<span','<a',$links);
                $links=str_replace('</span>','</a>',$links);
                ?>
                <?php echo str_replace('page-numbers','pagination', balanceTags ($links));//do not use esc_html() with  paginate_links() result ?>
            <?php
            endif;
        }

        static function paging_room($query=false)
        {
            global $wp_query,$st_search_query;
            if($st_search_query){
                $query=$st_search_query;
            }else $query=$wp_query;

            if ( $query->max_num_pages < 2 ) {
                return;
            }
            $paged        = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
            $max = $query->found_posts;
            $posts_per_page = $query->query['posts_per_page'];

            $number = ceil($max / $posts_per_page);

            $html = ' <ul class="pagination paged_room">';

            if($paged > 1){
                $html.= ' <li><a class="pagination paged_item_room" data-page="'.($paged-1).'">'.__('Previous',ST_TEXTDOMAIN).'</a></li>';
            }
            for($i=1 ; $i<= $number  ; $i++){
                if($i == $paged){
                    $html.= ' <li><a class="pagination paged_item_room current" data-page="'.$i.'">'.$i.'</a></li>';
                }else{
                    $html.= '<li><a class="pagination paged_item_room" data-page="'.$i.'">'.$i.'</a></li>';
                }
            }
            if($paged < $i-1){
                $html.= ' <li><a class="pagination paged_item_room" data-page="'.($paged+1).'">'.__( 'Next', ST_TEXTDOMAIN ).'</a></li>';
            }

            $html . '</ul>';
            return $html;
        }
        static function comments_paging()
        {
            ob_start();

            paginate_comments_links(
                array('type'=>'list',
                    'prev_text' => __( 'Previous Page', ST_TEXTDOMAIN ),
                    'next_text' => __( 'Next Page', ST_TEXTDOMAIN ),));

            $links=@ob_get_clean();


            if ( $links ) :
                $links=str_replace('page-numbers','pagination pull-right', balanceTags ($links));
                $links=str_replace('<span','<a',$links);
                $links=str_replace('</span>','</a>',$links);
                ?>
                <?php echo str_replace('page-numbers','pagination', balanceTags ($links));//do not use esc_html() with  paginate_links() result ?>
            <?php
            endif;
        }

        static function comments_list($comment, $args, $depth )
        {
            //get_template_part('single/comment','list');

            $file=locate_template('single/comment-list.php');

            if(is_file($file))

                include($file);
        }

        static function cutnchar($str,$n)
        {
            if(strlen($str)<$n) return $str;
            $html = substr($str,0,$n);
            $html = substr($html,0,strrpos($html,' '));
            return $html.'...';
        }

        static function  get_orderby_list()
        {
            return array(
                'none'=>'None',
                'ID'=>"ID",
                'author'=>'Author',
                'title'=>'Title',
                'name'=>"Name",
                'date'=>"Date",
                'modified'=>'Modified Date',
                'parent'=>'Parent',
                'rand'=>'Random',
                'comment_count'=>'Comment Count',

            );

        }

        static function reviewlist()
        { 
            $file=locate_template('reviews/review-list.php');

            if(is_file($file))

                include($file);
        }

        static function rate_to_string($star,$max=5)
        {
            $html='';

            if($star>$max) $star=$max;

            $moc1=(int)$star;

            for($i=1;$i<=$moc1;$i++ )
            {
                $html.='<li><i class="fa  fa-star"></i></li>';
            }

            $new=$max-$star;

            $du=$star-$moc1;
            if($du>=0.2 and $du<=0.9){
                $html.='<li><i class="fa  fa-star-half-o"></i></li>';
            }elseif($du){
                $html.='<li><i class="fa  fa-star-o"></i></li>';
            }

            for($i=1;$i<=$new;$i++ )
            {
                $html.='<li><i class="fa  fa-star-o"></i></li>';
            }

            return apply_filters('st_rate_to_string',$html);

        }

        static function add_read_more($content,$max_string=200)
        {
            $all=strlen($content);

            if(strlen($content)<$max_string) return $content;
            $html = substr($content,0,$max_string);
            $html = substr($html,0,strrpos($html,' '));


            return $html.'<span class="booking-item-review-more">'.substr($content,-($all-strrpos($html,' '))).'</span>';


        }

        static function  cal_rate($number,$total)
        {
            if(!$total) return 0;
            return round(($number/$total)*100);
        }

        static function handle_icon($string)
        {
            if(strpos($string,'im-')===0)
            {
                $icon= "im ".$string;
            }elseif(strpos($string,'fa-')===0)
            {
                $icon= "fa ".$string;
            }elseif(strpos($string,'ion-')===0)
            {
                $icon= "ion ".$string;
            }
            else{
                $icon=$string;
            }

            //return "<i class=''>"
            return $icon;
        }

        static function find_in_array($item=array(),$item_key=false,$item_value=false,$need=false){
            if(!empty($item)){
                foreach($item as $key=>$value)
                {
                    if($item_value==$value[$item_key]){
                        if($need and isset($value[$need])) return $value[$need];
                        return $value;
                    }
                }
            }
        }

        static function get_location_temp($post_id=false)
        {
            if(!$post_id) $post_id=get_the_ID();
            $lat=get_post_meta($post_id,'map_lat',true);
            $lng=get_post_meta($post_id,'map_lng',true);

            if(!$lat and !$lng) return false;

            $dataWeather=self::_get_location_weather($post_id);

            $c=0;
            if(isset($dataWeather->main->temp)){
                $k=$dataWeather->main->temp;
                $temp_format = st()->get_option('st_weather_temp_unit','c');
                $c = self::_change_temp($k,$temp_format);
            }
            $icon='';
            if(!empty($dataWeather->weather[0]->icon)){
                $icon = self::get_weather_icons($dataWeather->weather[0]->icon);
            }
            return array(
                'temp'=>$c,
                'icon'=>$icon
            );
        }
        static function _change_temp($value,$type='k'){
            if($type == 'c'){
                $value=$value-273.15;
            }
            if($type == 'f'){
                $c = $value-273.15;
                $value = $c * 1.8 + 32 ;
            }
            $value = number_format((float)$value,1);
            return $value;
        }
        static function get_weather_icons($id_icon=null){
            // API http://openweathermap.org/weather-conditions
            switch($id_icon){
                case "01d":
                    return '<i class="wi wi-solar-eclipse loc-info-weather-icon"></i>';
                    break;
                case "02d":
                    return '<i class="wi wi-day-cloudy loc-info-weather-icon"></i>';
                    break;
                case "03d":
                    return '<i class="wi wi-cloud loc-info-weather-icon"></i>';
                    break;
                case "04d":
                    return '<i class="wi wi-cloudy loc-info-weather-icon"></i>';
                    break;
                case "09d":
                    return '<i class="wi wi-snow-wind loc-info-weather-icon"></i>';
                    break;
                case "10d":
                    return '<i class="wi wi-day-rain-mix loc-info-weather-icon"></i>';
                    break;
                case "11d":
                    return '<i class="wi wi-day-storm-showers loc-info-weather-icon"></i>';
                    break;
                case "13d":
                    return '<i class="wi wi-showers loc-info-weather-icon"></i>';
                    break;
                case "50d":
                    return '<i class="wi wi-windy loc-info-weather-icon"></i>';
                    break;
                case "01n":
                    return '<i class="wi wi-night-clear loc-info-weather-icon"></i>';
                    break;
                case "02n":
                    return '<i class="wi wi-night-cloudy loc-info-weather-icon"></i>';
                    break;
                case "03n":
                    return '<i class="wi wi-cloud loc-info-weather-icon"></i>';
                    break;
                case "04n":
                    return '<i class="wi wi-cloudy loc-info-weather-icon"></i>';
                    break;
                case "09n":
                    return '<i class="wi wi-snow-wind loc-info-weather-icon"></i>';
                    break;
                case "10n":
                    return '<i class="wi wi-night-alt-rain-mix loc-info-weather-icon"></i>';
                    break;
                case "11n":
                    return '<i class="wi wi-day-storm-showers loc-info-weather-icon"></i>';
                    break;
                case "13n":
                    return '<i class="wi wi-showers loc-info-weather-icon"></i>';
                    break;
                case "50n":
                    return '<i class="wi wi-windy loc-info-weather-icon"></i>';
                    break;
            }

        }

        private static function _get_location_weather($post_id=false)
        {
            if(!$post_id) $post_id=get_the_ID();

            $lat=get_post_meta($post_id,'map_lat',true);
            $lng=get_post_meta($post_id,'map_lng',true);


            if($lat and $lng){
                $url="http://api.openweathermap.org/data/2.5/weather?APPID=e7dcb4987e15f50e8915dcf365aed0ad&lat=".$lat.'&lon='.$lng;
            }else{
                $url="http://api.openweathermap.org/data/2.5/weather?APPID=e7dcb4987e15f50e8915dcf365aed0ad&q=".get_the_title($post_id);
            }

            // fix multilanguage whene translate new location 

            $post_data = get_post($post_id, ARRAY_A);
            $slug = $post_data['post_name'];

            $cache=get_transient('st_weather_location_'.$slug);
            $hour = intval(st()->get_option('update_weather_by', 1));

            $dataWeather=null;

            if($cache===false){
                $raw_geocode = wp_remote_get($url);

                $body=wp_remote_retrieve_body($raw_geocode);
                $body=json_decode($body);
                if(isset($body->main->temp))
                    set_transient( 'st_weather_location_'.$post_id, $body, 60*60*$hour );
                $dataWeather=$body;
            }else{
                $dataWeather=$cache;
            }
            return $dataWeather;
        }

        private static function _change_weather_icon($icon_old,$icon_new){

            if(strpos($icon_old,'d')!==FALSE)
            {
                return str_replace('-night-','-day-',$icon_new);
            }else{
                return str_replace('-day-','-night-',$icon_new);
            }
        }


        static function get_weather_icon($location_id=fasle)
        {
            if(!$location_id) $location_id=get_the_ID();

            $dataWeather=self::_get_location_weather($location_id);

            $c=0;
            if(isset($dataWeather->weather->id)){
                $w_id=$dataWeather->weather->id;
                $old_icon=$dataWeather->weather->id;

                switch($w_id){
                    case 200:
                        //$c=self::_change_weather_icon('')
                }
            }

            return $c;
        }
        /**
        * @since 1.1.0
        * @param string post type
        * @param string type (null or option_tree)
        **/
        static function st_get_field_search($post_type, $type = '')
        {
            $list_field=array();
            if(!empty($post_type)){
                switch($post_type){
                    case "st_hotel":
                        $data_field = STHotel::get_search_fields_name();
                        break;
                    case "st_rental":
                        $data_field = STRental::get_search_fields_name();
                        break;
                    case "st_cars":
                        $data_field = STCars::get_search_fields_name();
                        break;
                    case "st_tours":
                        $data_field = STTour::get_search_fields_name();
                        break;
                    case "st_activity":
                        $data_field = STActivity::get_search_fields_name();
                        break;
                    case "st_rental":
                        $data_field = STRental::get_search_fields_name();
                        break;
                    default:
                        $data_field=apply_filters('st_search_fields_name',array(),$post_type);
                        break;
                }
                $list_field[__('--Select--',ST_TEXTDOMAIN)]='';
                if(!empty($data_field) and is_array($data_field) and $type==''){
                    foreach($data_field as $k => $v){
                        $list_field[$v['label']] =  $v['value'] ;
                    }
                    return $list_field;
                }
                if(!empty($data_field) and is_array($data_field) and $type=='option_tree'){
                    foreach($data_field as $k => $v){
                        $list_field[] = array(
                            'label' => $v['label'],
                            'value' => $v['value']
                            );
                    }
                    return $list_field;
                }
            }else{
                return false;
            }
        }




        /**
        *@since 1.1.7
        **/
        static function getBookingPeriod(){
            $booking_period = STInput::request('booking_period', 0);
            $list_date = array();
            if($booking_period > 0){
                for($i = 0; $i< $booking_period; $i++){
                    if($i <= 1){
                        $date = date(TravelHelper::getDateFormat(), strtotime("+".$i." day"));
                    }elseif($i > 1){
                        $date = date(TravelHelper::getDateFormat(), strtotime("+".$i." days"));
                    }
                    $list_date[] = $date;
                }
            }
            echo json_encode($list_date);
            die();
        }

        static function getListDate($start, $end){

            $start = new DateTime($start);
            $end = new DateTime($end . ' +1 day'); 
            $list = array();
            foreach (new DatePeriod($start, new DateInterval('P1D'), $end) as $day) {
                    $list[] = $day->format(TravelHelper::getDateFormat());
            }
            return $list;
        }

        static function substr($str, $length, $minword = 3)
        {
            $sub = '';
            $len = 0;
            foreach (explode(' ', $str) as $word)
            {
                $part = (($sub != '') ? ' ' : '') . $word;
                $sub .= $part;
                $len += strlen($part);
                if (strlen($word) > $minword && strlen($sub) >= $length)
                {
                  break;
                }
             }
                return $sub . (($len < strlen($str)) ? '...' : '');
        }

        static function getVersion(){
            $ver = wp_get_theme()->get('Version');
            $ver = preg_replace("/(\.)/", "", $ver);
            return intval($ver);
        }

        static function dateDiff($start, $end){
            $start = strtotime($start);
            $end = strtotime($end);
            return ($end - $start) / (60 * 60 * 24);
        }
        static function dateCompare($start, $end){
            $start_ts = strtotime($start);
            $end_ts = strtotime($end);

            return $end_ts - $start_ts;
        }

        /**
        *@since 1.1.7
        **/
        static function getLocationBySession(){
            if(isset($_SESSION['st_location'])){
                $result = stripslashes($_SESSION['st_location']);
                return json_decode($result);
            }else{
                return '';
            }

        }

        /**
        *@since 1.1.7
        **/
        static function setLocationBySession(){

            $current_language = '';
            if(defined('ICL_LANGUAGE_CODE')){
                $current_language = ICL_LANGUAGE_CODE;
            }elseif(function_exists('qtrans_getLanguage')){
                $current_language = qtrans_getLanguage();
            }

            if(!isset($_SESSION['st_location']) || empty($_SESSION['st_location']) || get_option('st_allow_save_location') == false || get_option('st_allow_save_location') == 'allow' || !isset($_SESSION['st_current_language_1']) || $current_language != $_SESSION['st_current_language_1']){
                $locations = array();
                
                $query = array(
                    'post_type' => 'location',
                    'posts_per_page' => -1,
                    'post_status' => 'publish'
                    );
                query_posts( $query );
                while(have_posts()): the_post();
                    $locations[] = array(
                        'ID' => '_'.get_the_ID().'_',
                        'parent' => wp_get_post_parent_id(get_the_ID())
                        );
                endwhile;   
                wp_reset_postdata(); wp_reset_query();
                
                $_SESSION['st_location'] = json_encode($locations);

                $_SESSION['st_current_language_1'] = $current_language;
                update_option('st_allow_save_location', 'not_allow');
            }  
        }
        static function _loop_location($locations = array(), $parent = 0, $l_tmp = array()){
            $location_tmp = array();
            foreach($locations as $key => $val){
                if(intval($val['parent']) == intval($parent)){
                    $location_tmp[] = $val;
                    unset($locations[$key]);
                }
            }
            if(count($location_tmp)){
                foreach($location_tmp as $item){
                    $l_tmp[] = $item;
                    self::_loop_location($locations, $item['parent'], $l_tmp);
                }
            }
            if(count($locations) == 0)
                return $l_tmp;
        }
        /**
        *@since 1.1.7
        **/
        static function getListLocation(){
            if(!is_admin()){
                $post_id = STInput::request('id');
            }else{
                $post_id = STInput::request('post');
            }
            $muti_location=STInput::request('multi_location');
            if(empty($post_id) || !get_post_status($post_id) and empty($muti_location)  ){
                $list_location = json_encode("");
            }else{
                $list_location = get_post_meta($post_id, 'multi_location', true);
                
                if(empty($list_location) and !empty($muti_location)){
                    if(STUser_f::get_status_msg() != 'success' ){
                        $list_location = implode(',',$muti_location);
                    }
                }
                if(!empty($list_location)){
                    if(is_array($list_location)){
                        foreach($list_location as $key => $val){
                            $list_location[$key] = preg_replace("/(\_)/", "", $list_location[$key]);
                        }
                    }else{
                        $list_location = preg_replace("/(\_)/", "", $list_location);
                        $list_location = explode(",",$list_location);
                    }
                    $list_location = json_encode($list_location);
                }else{
                    $list_location = get_post_meta($post_id, 'id_location', true);
                    if(!empty($list_location)){
                        $arr = array($list_location);
                        $list_location = json_encode($arr);  
                    }else{
                        $list_location = get_post_meta($post_id, 'location_id', true);
                        if(!empty($list_location)){
                            $arr = array($list_location);
                            $list_location = json_encode($arr);
                        }else{
                            $list_location = json_encode("") ;
                        }
                    }
                }
            }
            wp_localize_script('jquery','list_location',array(
                'list'=> $list_location
            ));
        }

        /**
        *@since 1.1.7
        **/
        static function locationHtml($post_id = ''){
            $result = '';
            
            if(empty($post_id)){
                $post_id = get_the_ID();
            }

            $list_location = get_post_meta($post_id, 'multi_location', true);
            if($list_location && !empty($list_location)){
                if(is_array($list_location)){
                    foreach($list_location as $key => $val){
                        $list_location[$key] = preg_replace("/(\_)/", "", $list_location[$key]);
                    }
                }else{
                    $list_location = preg_replace("/(\_)/", "", $list_location);
                    $list_location = explode(",",$list_location);
                }
                foreach($list_location as $item){
                    if(empty($result)){
                        $result .= get_the_title($item);
                    }else{
                        $result .= ', '.get_the_title($item);
                    }
                }
            }else{
                $list_location = get_post_meta($post_id, 'location_id', true);
                if($list_location && !empty($list_location)){
                    $result = get_the_title($list_location);
                }else{
                    $list_location = get_post_meta($post_id, 'id_location', true);
                    if($list_location && !empty($list_location)){
                        $result = get_the_title($list_location);
                    }
                }
            }
            
            return $result;
        }

        /** 
        *@since 1.1.7
        **/
        static function setListFullNameLocation(){
            $current_language = '';
            if(defined('ICL_LANGUAGE_CODE')){
                $current_language = ICL_LANGUAGE_CODE;
            }elseif(function_exists('qtrans_getLanguage')){
                $current_language = qtrans_getLanguage();
            }
            if(!is_admin() && (!isset($_SESSION['st_current_language']) || ($current_language != $_SESSION['st_current_language']) || !isset($_SESSION['st_cache_location']) || get_option('st_allow_save_cache_location') == 'allow' || get_option('st_allow_save_cache_location') == false)){
                $query = array(
                    'post_type' => 'location',
                    'posts_per_page' => -1,
                    'post_status' => 'publish'
                    );

                $result = array();

                query_posts($query);
                while(have_posts()) : the_post();
                    $country = get_post_meta(get_the_ID(),'location_country', true);
                    if(!$country) $country = '';
                    $result[] = array(
                        'ID' => get_the_ID(),
                        'Country' => $country
                    );
                endwhile;
                wp_reset_query(); wp_reset_postdata();

                $_SESSION['st_cache_location'] = json_encode($result);
                update_option('st_allow_save_cache_location', 'notallow');
                $_SESSION['st_current_language'] = $current_language;
            }    
        }
        static function showNameLocation($post_id = ''){
            $list = '';
            return get_the_title($post_id).self::getRelationPost($list, $post_id).self::getZipCodeHtml($post_id);
        }
        /**
        *@since 1.1.7
        **/
        static function getZipCodeHtml($post_id){
            $zipcode = get_post_meta($post_id, 'zipcode', true);
            if($zipcode && !empty($zipcode)){
                return '||'.$zipcode;
            }else{
                return '';
            }
        }

        /**
        *@since 1.1.7
        **/
        static function getListFullNameLocation(){
            if(isset($_SESSION['st_cache_location'])){
                $cache_location = $_SESSION['st_cache_location'];
                $cache_location = stripslashes($cache_location);
                return json_decode($cache_location);
            }else{
                return '';
            }
        }
        /**
        *@since 1.1.7   
        **/
        static function getRelationPost($list = '', $id = ''){
            $parent = wp_get_post_parent_id($id);
            if($parent > 0){
                return $list.= ', '.get_the_title($parent);
                self::getRelationPost($parent);
            }else{
                return $list;
            }
        }

        /**
        *@since 1.1.8
        **/
        static function checkIssetPost($post_id = '', $post_type = ''){
            global $wpdb;
            if(intval($post_id) && !empty($post_type)){
                $table = $wpdb->prefix.$post_type;
                $sql = "SELECT post_id FROM {$table} WHERE post_id = '{$post_id}'";

                $wpdb->query($sql);

                $num_rows = $wpdb->num_rows;
                return $num_rows;
            }else{
                return 0;
            }

        }

        /**
        *@since 1.1.8
        **/
        static function insertDuplicate($post_type = 'st_hotel', $data = array()){
            global $wpdb;
            $table = $wpdb->prefix.$post_type;

            $wpdb->insert( $table, $data);
        }

        static function deleteDuplicateData($post_id, $table){
            global $wpdb;

            $sql = "DELETE FROM {$table} WHERE post_id = '{$post_id}'";

            $rs = $wpdb->query($sql);

            return $rs;
        }

        /**
        *@since 1.1.8
        **/
        static function updateDuplicate($post_type = 'st_hotel', $data = array(), $where = array()){
            global $wpdb;
            $table = $wpdb->prefix.$post_type;
            $wpdb->update( $table, $data, $where, $format = null, $where_format = null );
        }

        /**
        *@since 1.1.8
        **/

        static function checkTableDuplicate($post_types = array()){
            global $wpdb;

            if(is_array($post_types) && count($post_types)){
                foreach($post_types as $post_type){
                    $table = $wpdb->prefix.$post_type;
                    if($wpdb->get_var("SHOW TABLES LIKE '{$table}'") !== $table)
                        return false;
                }
                return true;
            }else{
                $table = $wpdb->prefix.$post_types;
                if($wpdb->get_var("SHOW TABLES LIKE '{$table}'") !== $table)
                    return false;
                return true;
            }
        }

        /**
        *@since 1.1.8
        **/
        static function getLocationByParent($post_id){
            $list = array();
            $list[] = $post_id;
            global $wpdb;
            $sql = "SELECT ID FROM {$wpdb->prefix}posts WHERE post_parent = {$post_id} AND post_type = 'location'";
            $result = $wpdb->get_results($sql);
            
            if(is_array($result) && count($result)){
                foreach($result as $item){
                    $list[] = $item->ID;
                }
            }
            return $list;
        }

        /**
        *@since 1.1.8
        **/
        static function infoItemInLocation($post_id, $post_type = 'st_hotel'){
            global $wpdb;
            $table = $wpdb->prefix.$post_type;
            $price_field = 'price';
            $location_field = 'id_location';

            if($post_type == 'st_cars'){
                $price_field = 'cars_price';
            }elseif($post_type == 'st_hotel'){
                $price_field = 'price_avg';
            }elseif($post_type == 'st_tours' || $post_type == 'st_activity'){
                $price_field = 'sale_price';
            }elseif($post_type = 'st_rental'){
                $price_field = 'sale_price';
                $location_field = 'location_id';
            }
            $sql = "SELECT COUNT(post_id) as numbers, MIN($price_field) as froms FROM {$table} WHERE (multi_location LIKE '%_{$post_id}_%' OR {$location_field} IN ({$post_id}))";
            $results = $wpdb->get_row($sql, ARRAY_A);
            return $results;
        }

        /**
        *@since 1.1.8
        **/

        static function treeLocationHtml($list_location, $parent = 0, $prefix = ''){
            static $l_location = array();

            if(count($list_location)){
                $location_tmp = array();
                foreach($list_location as $key => $val){
                    if(intval($parent) == intval($val->parent)){
                        $location_tmp[] = $val;
                        unset($list_location[$key]);
                    }
                }

                if($parent != 0)
                    $prefix .= '--';
                if(is_array($location_tmp) && count($location_tmp)){
                    foreach($location_tmp as $item){
                        $l_location[] = array(
                            'ID' => $item->ID,
                            'parent' => isset($item->parent) ? $item->parent : 0,
                            'prefix' => $prefix
                        );

                        $p = intval(preg_replace("/(\_)/", "", $item->ID));
                        self::treeLocationHtml($list_location, $p, $prefix);
                    }
                }
            }
            return $l_location;
            
        }
        /** FROM 1.1.9 */
        static function get_duration_text($value, $number= null){
            // get text by value 
            if ($number <=0 ) return ; 
            if (!$number or $number ==1 ){
                switch ($value) {
                    case 'month':
                        return __("month" , ST_TEXTDOMAIN);
                        break;
                    case 'week':
                        return __("week" , ST_TEXTDOMAIN);
                        break;
                    case 'hour':
                        return __("hour" , ST_TEXTDOMAIN);
                        break;
                    default:
                        return __("day" , ST_TEXTDOMAIN);
                        break;
                }
            }else{
                switch ($value) {
                    case 'month':
                        return __("months" , ST_TEXTDOMAIN);
                        break;
                    case 'week':
                        return __("weeks" , ST_TEXTDOMAIN);
                        break;
                    case 'hour':
                        return __("hours" , ST_TEXTDOMAIN);
                        break;
                    default:
                        return __("days" , ST_TEXTDOMAIN);
                        break;
                }
            }
            
        }

        /**
        *@since 1.1.8
        **/

        static function _get_location_country(){
            $countries = array(
                '' => '----Select----',
                'AF' => 'Afghanistan',
                'AX' => 'Aland Islands',
                'AL' => 'Albania',
                'DZ' => 'Algeria',
                'AS' => 'American Samoa',
                'AD' => 'Andorra',
                'AO' => 'Angola',
                'AI' => 'Anguilla',
                'AQ' => 'Antarctica',
                'AG' => 'Antigua And Barbuda',
                'AR' => 'Argentina',
                'AM' => 'Armenia',
                'AW' => 'Aruba',
                'AU' => 'Australia',
                'AT' => 'Austria',
                'AZ' => 'Azerbaijan',
                'BS' => 'Bahamas',
                'BH' => 'Bahrain',
                'BD' => 'Bangladesh',
                'BB' => 'Barbados',
                'BY' => 'Belarus',
                'BE' => 'Belgium',
                'BZ' => 'Belize',
                'BJ' => 'Benin',
                'BM' => 'Bermuda',
                'BT' => 'Bhutan',
                'BO' => 'Bolivia',
                'BA' => 'Bosnia And Herzegovina',
                'BW' => 'Botswana',
                'BV' => 'Bouvet Island',
                'BR' => 'Brazil',
                'IO' => 'British Indian Ocean Territory',
                'BN' => 'Brunei Darussalam',
                'BG' => 'Bulgaria',
                'BF' => 'Burkina Faso',
                'BI' => 'Burundi',
                'KH' => 'Cambodia',
                'CM' => 'Cameroon',
                'CA' => 'Canada',
                'CV' => 'Cape Verde',
                'KY' => 'Cayman Islands',
                'CF' => 'Central African Republic',
                'TD' => 'Chad',
                'CL' => 'Chile',
                'CN' => 'China',
                'CX' => 'Christmas Island',
                'CC' => 'Cocos (Keeling) Islands',
                'CO' => 'Colombia',
                'KM' => 'Comoros',
                'CG' => 'Congo',
                'CD' => 'Congo, Democratic Republic',
                'CK' => 'Cook Islands',
                'CR' => 'Costa Rica',
                'CI' => 'Cote D\'Ivoire',
                'HR' => 'Croatia',
                'CU' => 'Cuba',
                'CY' => 'Cyprus',
                'CZ' => 'Czech Republic',
                'DK' => 'Denmark',
                'DJ' => 'Djibouti',
                'DM' => 'Dominica',
                'DO' => 'Dominican Republic',
                'EC' => 'Ecuador',
                'EG' => 'Egypt',
                'SV' => 'El Salvador',
                'GQ' => 'Equatorial Guinea',
                'ER' => 'Eritrea',
                'EE' => 'Estonia',
                'ET' => 'Ethiopia',
                'FK' => 'Falkland Islands (Malvinas)',
                'FO' => 'Faroe Islands',
                'FJ' => 'Fiji',
                'FI' => 'Finland',
                'FR' => 'France',
                'GF' => 'French Guiana',
                'PF' => 'French Polynesia',
                'TF' => 'French Southern Territories',
                'GA' => 'Gabon',
                'GM' => 'Gambia',
                'GE' => 'Georgia',
                'DE' => 'Germany',
                'GH' => 'Ghana',
                'GI' => 'Gibraltar',
                'GR' => 'Greece',
                'GL' => 'Greenland',
                'GD' => 'Grenada',
                'GP' => 'Guadeloupe',
                'GU' => 'Guam',
                'GT' => 'Guatemala',
                'GG' => 'Guernsey',
                'GN' => 'Guinea',
                'GW' => 'Guinea-Bissau',
                'GY' => 'Guyana',
                'HT' => 'Haiti',
                'HM' => 'Heard Island & Mcdonald Islands',
                'VA' => 'Holy See (Vatican City State)',
                'HN' => 'Honduras',
                'HK' => 'Hong Kong',
                'HU' => 'Hungary',
                'IS' => 'Iceland',
                'IN' => 'India',
                'ID' => 'Indonesia',
                'IR' => 'Iran, Islamic Republic Of',
                'IQ' => 'Iraq',
                'IE' => 'Ireland',
                'IM' => 'Isle Of Man',
                'IL' => 'Israel',
                'IT' => 'Italy',
                'JM' => 'Jamaica',
                'JP' => 'Japan',
                'JE' => 'Jersey',
                'JO' => 'Jordan',
                'KZ' => 'Kazakhstan',
                'KE' => 'Kenya',
                'KI' => 'Kiribati',
                'KR' => 'Korea',
                'KW' => 'Kuwait',
                'KG' => 'Kyrgyzstan',
                'LA' => 'Lao People\'s Democratic Republic',
                'LV' => 'Latvia',
                'LB' => 'Lebanon',
                'LS' => 'Lesotho',
                'LR' => 'Liberia',
                'LY' => 'Libyan Arab Jamahiriya',
                'LI' => 'Liechtenstein',
                'LT' => 'Lithuania',
                'LU' => 'Luxembourg',
                'MO' => 'Macao',
                'MK' => 'Macedonia',
                'MG' => 'Madagascar',
                'MW' => 'Malawi',
                'MY' => 'Malaysia',
                'MV' => 'Maldives',
                'ML' => 'Mali',
                'MT' => 'Malta',
                'MH' => 'Marshall Islands',
                'MQ' => 'Martinique',
                'MR' => 'Mauritania',
                'MU' => 'Mauritius',
                'YT' => 'Mayotte',
                'MX' => 'Mexico',
                'FM' => 'Micronesia, Federated States Of',
                'MD' => 'Moldova',
                'MC' => 'Monaco',
                'MN' => 'Mongolia',
                'ME' => 'Montenegro',
                'MS' => 'Montserrat',
                'MA' => 'Morocco',
                'MZ' => 'Mozambique',
                'MM' => 'Myanmar',
                'NA' => 'Namibia',
                'NR' => 'Nauru',
                'NP' => 'Nepal',
                'NL' => 'Netherlands',
                'AN' => 'Netherlands Antilles',
                'NC' => 'New Caledonia',
                'NZ' => 'New Zealand',
                'NI' => 'Nicaragua',
                'NE' => 'Niger',
                'NG' => 'Nigeria',
                'NU' => 'Niue',
                'NF' => 'Norfolk Island',
                'MP' => 'Northern Mariana Islands',
                'NO' => 'Norway',
                'OM' => 'Oman',
                'PK' => 'Pakistan',
                'PW' => 'Palau',
                'PS' => 'Palestinian Territory, Occupied',
                'PA' => 'Panama',
                'PG' => 'Papua New Guinea',
                'PY' => 'Paraguay',
                'PE' => 'Peru',
                'PH' => 'Philippines',
                'PN' => 'Pitcairn',
                'PL' => 'Poland',
                'PT' => 'Portugal',
                'PR' => 'Puerto Rico',
                'QA' => 'Qatar',
                'RE' => 'Reunion',
                'RO' => 'Romania',
                'RU' => 'Russian Federation',
                'RW' => 'Rwanda',
                'BL' => 'Saint Barthelemy',
                'SH' => 'Saint Helena',
                'KN' => 'Saint Kitts And Nevis',
                'LC' => 'Saint Lucia',
                'MF' => 'Saint Martin',
                'PM' => 'Saint Pierre And Miquelon',
                'VC' => 'Saint Vincent And Grenadines',
                'WS' => 'Samoa',
                'SM' => 'San Marino',
                'ST' => 'Sao Tome And Principe',
                'SA' => 'Saudi Arabia',
                'SN' => 'Senegal',
                'RS' => 'Serbia',
                'SC' => 'Seychelles',
                'SL' => 'Sierra Leone',
                'SG' => 'Singapore',
                'SK' => 'Slovakia',
                'SI' => 'Slovenia',
                'SB' => 'Solomon Islands',
                'SO' => 'Somalia',
                'ZA' => 'South Africa',
                'GS' => 'South Georgia And Sandwich Isl.',
                'ES' => 'Spain',
                'LK' => 'Sri Lanka',
                'SD' => 'Sudan',
                'SR' => 'Suriname',
                'SJ' => 'Svalbard And Jan Mayen',
                'SZ' => 'Swaziland',
                'SE' => 'Sweden',
                'CH' => 'Switzerland',
                'SY' => 'Syrian Arab Republic',
                'TW' => 'Taiwan',
                'TJ' => 'Tajikistan',
                'TZ' => 'Tanzania',
                'TH' => 'Thailand',
                'TL' => 'Timor-Leste',
                'TG' => 'Togo',
                'TK' => 'Tokelau',
                'TO' => 'Tonga',
                'TT' => 'Trinidad And Tobago',
                'TN' => 'Tunisia',
                'TR' => 'Turkey',
                'TM' => 'Turkmenistan',
                'TC' => 'Turks And Caicos Islands',
                'TV' => 'Tuvalu',
                'UG' => 'Uganda',
                'UA' => 'Ukraine',
                'AE' => 'United Arab Emirates',
                'GB' => 'United Kingdom',
                'US' => 'United States',
                'UM' => 'United States Outlying Islands',
                'UY' => 'Uruguay',
                'UZ' => 'Uzbekistan',
                'VU' => 'Vanuatu',
                'VE' => 'Venezuela',
                'VN' => 'Viet Nam',
                'VG' => 'Virgin Islands, British',
                'VI' => 'Virgin Islands, U.S.',
                'WF' => 'Wallis And Futuna',
                'EH' => 'Western Sahara',
                'YE' => 'Yemen',
                'ZM' => 'Zambia',
                'ZW' => 'Zimbabwe',
            );
            $list_country = array();
            foreach($countries as $key => $val){
                $list_country [] = array(
                    'value' => $key,
                    'label' => $val
                );
            }

            return $list_country;
        }
        // from .1.1.9
        static function get_list_name($post_type = "st_hotel" , $max_num = null){ 

            /*$array = array() ; 
            $arg = array(
                'post_type' => $post_type,
                'posts_per_page' =>$max_num,
                'order' =>"DESC",
                'orderby'   =>'title'
                );

            $query = new WP_Query( $arg); 
            if($query->have_posts()){
                while ($query->have_posts()) {
                    $query->the_post(); 
                    $array[] = 
                    array(
                        'id' => get_the_ID() , 
                        'title'=> get_the_title()
                        );              
                }
            }
            wp_reset_postdata();
            return $array;*/

            global $wpdb ;  $table = $wpdb->posts ; 
            $join = "";
            $join = self::edit_join_wpml($join , $post_type) ;
            $where = " post_type = '{$post_type}' and post_status = 'publish' " ; 
            $where = self::edit_where_wpml($where) ; 
            $sql = "select {$table}.ID as id , {$table}.post_title as title from {$table} {$join} where 1=1 and $where order by {$table}.post_title " ; 
            //echo $sql ; 
            $result  = $wpdb->get_results($sql , ARRAY_A); 
            return $result  ; 
        }
        // from 1.2.0
        static function get_all_post_type(){
            $post_type = array();
            if (st_check_service_available('st_hotel')) { $post_type[] = "st_hotel"; }
            if (st_check_service_available('st_tours')) { $post_type[] = "st_tours"; }
            if (st_check_service_available('st_rental')) { $post_type[] = "st_rental"; }
            if (st_check_service_available('st_cars')) { $post_type[] = "st_cars"; }
            if (st_check_service_available('st_activity')) { $post_type[] = "st_activity"; }
            return $post_type;

        }
        // from 1.2.0
        static function get_all_post_type_not_in(){
            // for not in sql
            $post_type = array();
            if (!st_check_service_available('st_hotel')) { $post_type[] = "'st_hotel'"; }
            if (!st_check_service_available('st_tours')) { $post_type[] = "'st_tours'"; }
            if (!st_check_service_available('st_rental')) { $post_type[] = "'st_rental'"; }
            if (!st_check_service_available('st_cars')) { $post_type[] = "'st_cars'"; }
            if (!st_check_service_available('st_activity')) { $post_type[] = "'st_activity'"; }
            if (empty($post_type)) {
                return "('/')";
            }else {
                return "(".implode(',', $post_type).")";
            }
        }
        static function is_https(){
            return ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443);
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
                    $price_custom = self::st_get_custom_price_by_date($room_id , $data_date);
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
                    $sale_from=get_post_meta($room_id,'sale_price_from',true);
                    $sale_to=get_post_meta($room_id,'sale_price_to',true);

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
        /* from 1.1.8 
        * [SEO ] set static size for image 
        */
        static function get_attchment_size($image_url , $link = true){
            if (!$image_url) {return ; }
            global $wpdb ;       
            if ($link )   {
                $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url ));
                if ($attachment){
                   $info =  wp_get_attachment_image_src( $attachment[0], 'full' );
                    return array(
                        'id' => $attachment[0],
                        'width'=>$info[1],
                        'height'=>$info[2]
                        ); 
                }                
            }           

        }
        /** from 1.1.9 . get list menus*/
        static function get_opt_menus(){ 

            $menus=wp_get_nav_menus();
            if (empty($menus) or !is_array(wp_get_nav_menus())) return ;
            $menus = wp_get_nav_menus();
            $return = array();
            foreach ( $menus as $key => $value) {
                $return [] = array(
                    'label' => $value->name , 
                    'value' => $value->slug
                    );
            }
            return $return ; 
        }
        
         
        static function get_location(){
            if (is_search() || is_page()) return ; 
            $post_type = get_post_type(get_the_ID());
            $array  = array('st_hotel', 'st_activity' , 'st_rental' , 'st_tours');
            if (!in_array($post_type, $array)) return ; 
            if (!st_check_service_available($post_type)) {return ; }
            $location = get_post_meta(get_the_ID() , 'multi_location'  , true) ; 
             
            if (!empty($location)){
                $location = explode(',', $location) ; 
                $location = $location[0];
                $location = explode("_", $location) ; 
                $location = $location[1]; 
            }                        
            if(!$location){
                $location = get_post_meta(get_the_ID() , 'location_id' , true) ; 
            }
            if (!$location){
                $location = get_post_meta(get_the_ID() , 'id_location' , true) ; 
            }
            if (!$location ) return ; 
            return $location ; 
        }
        // from 1.1.9 get location and weather
        static function get_location_weather(){

            $location = self::get_location();
            if (!$location) return ; 
            $c = self::get_location_temp();
            $text = "" ;  
            $text .='<span>'.get_the_title($location).'</span>
                <span class="loc-info-weather-num">'.$c['temp'].'</span>
                 '.$c['icon'].' </p>' ;
            return $text ;  
        }
        /**
        * from 1.1.8
        */
        static function edit_where_wpml($where ,$post_type = null){            
            if(defined('ICL_LANGUAGE_CODE')){
                global $wpdb;
                $current_language = ICL_LANGUAGE_CODE;                
                $where.= " AND t.language_code = '{$current_language}' " ;
            }
            return $where; 
        }
        /**
        * from 1.1.8
        */
        static function edit_join_wpml($join ,$post_type){            
            if(defined('ICL_LANGUAGE_CODE')){
                global $wpdb;
                $join.= "
                join {$wpdb->prefix}icl_translations as  t ON {$wpdb->posts}.ID = t.element_id
                AND t.element_type = 'post_{$post_type}'
                JOIN {$wpdb->prefix}icl_languages as  l ON t.language_code = l. CODE
                AND l.active = 1 " ; 
            }
            return $join; 
        }

        static function isset_table($table_name){
            global $wpdb;
            $table = $wpdb->prefix.$table_name;
            if($wpdb->get_var("SHOW TABLES LIKE '{$table}'") != $table){
                return false;
            }
            return true;
        }

        static function get_commission(){
            $commission = floatval(st()->get_option('partner_commission'));
            return $commission;
        }

        static function st_admin_notice_post_draft(){
            $query = array(
                'post_status' => 'draft',
                'posts_per_page' => -1,
                'post_type' => array('st_hotel', 'st_rental', 'st_cars', 'st_tours', 'st_activity', 'hotel_room', 'rental_room')
            );
            $return = array();
            $posts = get_posts($query);
            if(count($posts)){
                foreach($posts as $post){
                    $return[$post->post_type][] = get_the_ID();
                }
            }
            if(count($return)){
                echo '<div class="updated" style="padding: 15px 10px 5px 10px !important;">';
                $name = '';
                foreach($return as $key => $item){
                    if($key == 'st_hotel'){
                        $name = count($item) > 1 ? __('Hotels', ST_TEXTDOMAIN) : __('Hotel', ST_TEXTDOMAIN); 
                    }
                    if($key == 'st_rental'){
                        $name = count($item) > 1 ? __('Rentals', ST_TEXTDOMAIN) : __('Rental', ST_TEXTDOMAIN); 
                    }
                    if($key == 'st_cars'){
                        $name = count($item) > 1 ? __('Cars', ST_TEXTDOMAIN) : __('Car', ST_TEXTDOMAIN); 
                    }
                    if($key == 'st_tours'){
                        $name = count($item) > 1 ? __('Tours', ST_TEXTDOMAIN) : __('Tour', ST_TEXTDOMAIN); 
                    }
                    if($key == 'st_activity'){
                        $name = count($item) > 1 ? __('Activities', ST_TEXTDOMAIN) : __('Activity', ST_TEXTDOMAIN); 
                    }
                    if($key == 'hotel_room'){
                        $name = count($item) > 1 ? __('Hotel rooms', ST_TEXTDOMAIN) : __('Hotel room', ST_TEXTDOMAIN); 
                    }
                    if($key == 'rental_room'){
                        $name = count($item) > 1 ? __('Rental rooms', ST_TEXTDOMAIN) : __('Rental room', ST_TEXTDOMAIN); 
                    }
                    echo '<div style="margin-bottom: 5px;">';
                    echo sprintf(__('Have %d new %s need check for approved.', ST_TEXTDOMAIN), count($item), $name);
                    echo '<a style="margin-left: 5px;" href="'.admin_url('edit.php?post_status=draft&post_type='.$key).'" target="_blank">'.__('Click Here', ST_TEXTDOMAIN).'!</a>';
                    echo '</div>';
                }
                echo '</div>';
            }
        }
        static function getPostIdOrigin($post_id){
            global $sitepress;
            if($sitepress){
                $lang_code = $sitepress->get_default_language();
                if($lang_code){
                    $post_type = get_post_type($post_id);
                    $origin_id = icl_object_id($post_id, $post_type, true, $lang_code);
                }else{
                    $origin_id = $post_id;
                }
            }else{
                $origin_id = $post_id;
            }
            return $origin_id;
        }
        static function st_welcome_user($user_id, $deprecated = null, $notify = '') {
            global $wpdb, $wp_hasher;
            $user = get_userdata( $user_id );
         
            $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
         
            $message  = sprintf(__('New user registration on your site %s:'), $blogname) . "\r\n\r\n";
            $message .= sprintf(__('Username: %s'), $user->user_login) . "\r\n\r\n";
            $message .= sprintf(__('E-mail: %s'), $user->user_email) . "\r\n";
         
            @wp_mail(get_option('admin_email'), sprintf(__('[%s] New User Registration'), $blogname), $message);
         
            if ( 'admin' === $notify || empty( $notify ) ) {
                return;
            }
         
            $key = wp_generate_password( 20, false );
         
            if ( empty( $wp_hasher ) ) {
                require_once ABSPATH . WPINC . '/class-phpass.php';
                $wp_hasher = new PasswordHash( 8, true );
            }
            $hashed = time() . ':' . $wp_hasher->HashPassword( $key );
            $wpdb->update( $wpdb->users, array( 'user_activation_key' => $hashed ), array( 'user_login' => $user->user_login ) );
         
            $message = sprintf(__('Username: %s'), $user->user_login) . "\r\n\r\n";
            $message .= __('To set your password, visit the following address:') . "\r\n\r\n";
            $message .= '<' . network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user->user_login), 'login') . ">\r\n\r\n";
         
            $message .= wp_login_url() . "\r\n";
         
            @wp_mail($user->user_email, sprintf(__('[%s] Your username and password info'), $blogname), $message);
        }
    }

    TravelHelper::init();
    TravelHelper::st_admin_notice_post_draft_fc();

}