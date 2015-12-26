<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Class STGatewaySubmitform
 *
 * Created by ShineTheme
 *
 */
if(!class_exists('STGatewaySubmitform'))
{
    class STGatewaySubmitform extends STAbstactPaymentGateway
    {

        function __construct()
        {
            add_filter('st_payment_gateway_st_submit_form_name',array($this,'get_name'));

        }

        function html()
        {
            echo st()->load_template('gateways/submit_form');
        }

        /**
         *
         *
         * @update 1.1.1
         * */
        function do_checkout($order_id)
        {
            update_post_meta($order_id,'status','pending');
            do_action('st_booking_change_status','pending',$order_id,'normal_booking');
            $order_token=get_post_meta($order_id,'order_token_code',true);

            //Destroy cart on success
            STCart::destroy_cart();

            $booking_success=STCart::get_success_link();
            //do_action('st_email_after_booking',$order_id);
            //do_action('st_booking_submit_form_success',$order_id);
            STCart::send_mail_after_booking($order_id, true);
            STCart::send_email_confirm($order_id);

            if($order_token){
                $array=array(
                    'order_token_code'=>$order_token
                );
            }else{
                $array=array(
                    'order_code'=>$order_id,

                );
            }

            return array(
                'status'=>true,
                'redirect'=>add_query_arg($array,$booking_success)
            );

        }

        /**
         * Validate if order is available to show booking infomation
         *
         * @since 1.0.8
         *
         * */
        function success_page_validate()
        {
            return true;
        }


        function get_name()
        {
            return __('Submit Form',ST_TEXTDOMAIN);
        }

        /**
         * Check payment method for all items or specific is enable
         *
         *
         * @update 1.1.7
         * @param bool $item_id
         * @return bool
         */
        function is_available($item_id=false)
        {
            $result=false;
            if(st()->get_option('pm_gway_st_submit_form_enable')=='on')
            {
                $result= true;
            }
            if($item_id)
            {
                $meta=get_post_meta($item_id,'is_meta_payment_gateway_st_submit_form',true);
                if($meta=='off'){
                    $result=false;
                }
            }

            return $result;
        }
        function _pre_checkout_validate()
        {
            return true;
        }

        function get_option_fields()
        {
            return array();
        }
        function get_default_status()
        {
            return true;
        }
    }
}
