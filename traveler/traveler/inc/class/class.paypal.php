<?php
    /**
     * @package WordPress
     * @subpackage Traveler
     * @since 1.0
     *
     * Class STPaypal
     *
     * Created by ShineTheme
     *
     */
    use Omnipay\Omnipay;

    if (class_exists('STTravelCode') and !class_exists('STPaypal')) {
        class STPaypal
        {
            protected $clientId = "AcYQMRB0jNn39D-u39i-p2LukpPAkgw19FtK1jIdb_jj7l99OFCncBJa3KLQ";
            protected $clientSecret = "EAEO1xC3u-V9eZWTutjmu4QLpFPMlo3KWdaF2LrcDc2-13B8PXafeT4vXjW9";
            protected $apiUserName = 'dungdt-facilitator_api1.shinetheme.com';
            protected $apiPass = 'RBKANUDGJN6KY7WQ';
            protected $apiSignature = 'AfVov5Hs6Z8rseCCA0HGxV1Ckbn2A6QjaKYGlaqCMlAelXy7AjQDTQZR';


            public $debug = false;

            function __construct()
            {
                $this->apiUserName=st()->get_option('paypal_api_username');
                $this->apiPass=st()->get_option('paypal_api_password');
                $this->apiSignature=st()->get_option('paypal_api_signature');
                $this->liveurl = 'https://www.paypal.com/cgi-bin/webscr';
                $this->testurl = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
            }

            function test_authorize()
            {
                //Check cart is not empty

                if (STCart::check_cart() and STPrice::getTotal()) {
                    $gateway = Omnipay::create('PayPal_Express');
                    $gateway->setUsername(st()->get_option('paypal_api_username'));
                    $gateway->setPassword(st()->get_option('paypal_api_password'));
                    $gateway->setSignature(st()->get_option('paypal_api_signature'));
                    if (st()->get_option('paypal_enable_sandbox', 'on') == 'on') {
                        $gateway->setTestMode(true);
                    }

                    $purchase = array(
                        'amount'      => $this->getPriceByLineItems(),
                        'currency'    => TravelHelper::get_current_currency('name'),
                        'description' => sprintf(__('%s Booking', ST_TEXTDOMAIN),get_bloginfo('title')),
                        'returnUrl'   => add_query_arg(array(
                            'gateway_name' => 'st_paypal',
                            'status'          => 'success'
                        ), STCart::get_success_link()),
                        'cancelUrl'   => add_query_arg(array(
                            'gateway_name' => 'st_paypal',
                            'status'          => 'error'
                        ), STCart::get_success_link()),
                        'items'       => $this->get_line_items(),
                        'taxAmount'   => 0
                    );
                    
                    $response = $gateway->purchase(
                        $purchase
                    )->send();

                    if ($response->isSuccessful()) {

                        return array('status' => true);

                    } elseif ($response->isRedirect()) {

                        return array('status' => false, 'redirect_url' => $response->getRedirectUrl(), '');
                    } else {
                        return array('status' => false, 'message' => $response->getMessage(), 'data' => $purchase);

                    }
                } else {

                    return array('status' => false, 'message' => __('Your cart is currently empty', ST_TEXTDOMAIN));
                }
            }

            function get_authorize_url($order_id = false){
                if (STCart::check_cart() and STPrice::getTotal()) {
                    $gateway = Omnipay::create('PayPal_Express');
                    $gateway->setUsername(st()->get_option('paypal_api_username'));
                    $gateway->setPassword(st()->get_option('paypal_api_password'));
                    $gateway->setSignature(st()->get_option('paypal_api_signature'));
                    if (st()->get_option('paypal_enable_sandbox', 'on') == 'on') {
                        $gateway->setTestMode(true);
                    }

                    $order_token_code=get_post_meta($order_id,'order_token_code',true);

                    if(!$order_token_code)
                    {
                        $array=array(
                            'gateway_name' => 'st_paypal',
                            'order_code'          => $order_id,
                            'status'=>'success'
                        );
                        $array_error=array(
                            'gateway_name' => 'st_paypal',
                            'order_code'          => $order_id,
                            'status'=>'error'
                        );
                    }else{
                        $array=array(
                            'gateway_name' => 'st_paypal',
                            'order_token_code'          => $order_token_code,
                            'status'=>'success'
                        );

                        $array_error=array(
                            'gateway_name' => 'st_paypal',
                            'order_token_code'          => $order_token_code,
                            'status'=>'error'
                        );
                    }


                    $purchase = array(
                        'amount'      => $this->getPriceByLineItems(),
                        'currency'    => TravelHelper::get_current_currency('name'),
                        'description' => __('Traveler Booking',ST_TEXTDOMAIN),
                        'returnUrl'   => add_query_arg($array, STCart::get_success_link()),
                        'cancelUrl'   => add_query_arg($array_error, STCart::get_success_link()),
                        'items'       => $this->get_line_items($order_id),
                        'taxAmount'   => 0
                    );

                    $response = $gateway->purchase(
                        $purchase
                    )->send();

                    if ($response->isSuccessful()) {

                        return array('status' => true);

                    } elseif ($response->isRedirect()) {
                        return array('status' => false, 'redirect_url' => $response->getRedirectUrl(), '');
                    } else {
                        return array('status' => false, 'message' => $response->getMessage(), 'data' => $purchase);

                    }
                } else {
                    return array('status' => false, 'message' => __('Your cart is currently empty', ST_TEXTDOMAIN));
                }

            }


            function get_paypal_url($order)
            {
                $paypal_args = $this->get_paypal_args($order);
                $paypal_args = http_build_query($paypal_args, '', '&');

                if ('yes' == $this->testmode) {
                    $paypal_adr = $this->testurl . '?test_ipn=1&';
                } else {
                    $paypal_adr = $this->liveurl . '?';
                }

                return $paypal_adr . $paypal_args;
            }

            function getItems($order_id)
            {
                $all_items = array();

                if (!$line_items = $this->get_line_items()) {
                    //If can not show line itemss
                    //Show all
                    $args[] = array(
                        'name'     => __('Travel Order', ST_TEXTDOMAIN),
                        'quantity' => 1,
                        'price'    => STPrice::getTotal()
                    );

                    $all_items = $args;

                } else {
                    $all_items = $line_items;
                }
                return $all_items;
            }


            function get_paypal_args($order)
            {

                $order_id = $order['id'];

                $paypal_args = array_merge(
                    array(
                        'cmd'           => '_cart',
                        'business'      => st()->get_option('paypal_email'),
                        'no_note'       => 1,
                        'currency_code' => TravelHelper::get_current_currency('name'),
                        'charset'       => 'utf-8',
                        'rm'            => is_ssl() ? 2 : 1,
                        'upload'        => 1,
                        'return'        => add_query_arg(array(
                            'gateway_name' => 'st_paypal',
                            'order_code'          => $order_id,
                            'status'=>'success'
                        ), STCart::get_success_link()),
                        'cancel_return' => add_query_arg(array(
                            'gateway_name' => 'st_paypal',
                            'order_code'          => $order_id,
                            'status'=>'error'
                        ), STCart::get_success_link()),
                        'page_style'    => $this->page_style,
                        'paymentaction' => 'sale',
                        'bn'            => 'ShinethemeCart',


                        'invoice'       => 'ST_' . $order_id,

                    )
                );


                $paypal_args['no_shipping'] = 1;

                if (!$line_items = $this->get_line_items($order)) {
                    //If can not show line itemss
                    //Show all
                    $args[] = array(
                        'name'     => __('Travel Order', ST_TEXTDOMAIN),
                        'quantity' => 1,
                        'price'    => STPrice::getTotal()
                    );

                } else {
                    $paypal_args = array_merge($paypal_args, $line_items);
                }

                return $paypal_args;
            }
            function getPriceByLineItems(){
                $lines = $this->get_line_items();
                $total = 0;
                if(is_array($lines) && count($lines)){
                    foreach($lines as $item){
                        $number = intval($item['quantity']);
                        $price = floatval($item['price']);
                        $total += ($number * $price);
                    }
                }

                return $total;
            }

            function get_line_items()
            {

                // Do not send lines when  too many line items in the order.
                $count = STCart::count();
                if ($count > 9 or !$count) return false;

                $args = array();
                $item_loop = 0;

                if (STCart::check_cart()) {
                    $cart = STCart::get_carts();

                    if (!empty($cart)) {
                        foreach ($cart as $key => $value){

                            $args[] =
                                array(
                                    'name'     => $this->paypal_item_name(get_the_title($key)),
                                    'quantity' => intval($value['number']),
                                    'price'    => round(STPrice::getTotal(true), 2)
                                );
                        }

                        /*if (STCart::use_coupon()) {
                            $args[] = array(
                                'name'     => sprintf(st_get_language('coupon_key'), STCart::get_coupon_code()),
                                'quantity' => 1,
                                'price'    => -STCart::get_coupon_amount()
                            );
                        }*/
                    }


                }
                return $args;
            }


            public function paypal_item_name($item_name)
            {
                if (strlen($item_name) > 127) {
                    $item_name = substr($item_name, 0, 124) . '...';
                }

                return html_entity_decode($item_name, ENT_NOQUOTES, 'UTF-8');
            }

            function check_completePurchase($order_id = false){

                //Check cart is not empty
                if ($order_id and false !== get_post_status($order_id)) {
                    $total = get_post_meta($order_id, 'total_price', true);

                    $gateway = Omnipay::create('PayPal_Express');
                    $gateway->setUsername($this->apiUserName);
                    $gateway->setPassword($this->apiPass);
                    $gateway->setSignature($this->apiSignature);
                    if (st()->get_option('paypal_enable_sandbox', 'on') == 'on') {
                        $gateway->setTestMode(true);
                    }

                    $amount = TravelHelper::convert_money($total);

                    $order_token_code=get_post_meta($order_id,'order_token_code',true);

                    if(!$order_token_code)
                    {
                        $array=array(
                            'gateway_name' => 'st_paypal',
                            'order_code'          => $order_id,
                            'status'=>'success'
                        );
                        $array_error=array(
                            'gateway_name' => 'st_paypal',
                            'order_code'          => $order_id,
                            'status'=>'error'
                        );
                    }else{
                        $array=array(
                            'gateway_name' => 'st_paypal',
                            'order_token_code'          => $order_token_code,
                            'status'=>'success'
                        );

                        $array_error=array(
                            'gateway_name' => 'st_paypal',
                            'order_token_code'          => $order_token_code,
                            'status'=>'error'
                        );
                    }

                    $response = $gateway->completePurchase(
                        array(
                            'amount'      => (float)$amount,
                            'currency'    => TravelHelper::get_current_currency('name'),
                            'description' => __('Traveler Booking', ST_TEXTDOMAIN),
                            'returnUrl'   => add_query_arg($array, STCart::get_success_link()),
                            'cancelUrl'   =>add_query_arg($array_error, STCart::get_success_link())
                        )
                    )->send();


                    if ($response->isSuccessful()) {

                        $data = $response->getData();
                        $data2 = $gateway->fetchCheckout(array('transactionReference' => $data['TOKEN']))->send();
                        $transaction_data = $data2->getData();

                        //Try to create user and create new orders with paypal transaction detail
                        return STGatewayPaypal::paypal_checkout($transaction_data,$order_id);
                        //return true;

                    } elseif ($response->isRedirect()) {
                        //$response->redirect(); // this will automatically forward the customer
                        return array('status' => false, 'redirect_url' => $response->getRedirectUrl(), 'func' => 'check_completePurchase');
//                    return ;
                    } else {
                        // not successful
                        return array('status' => false, 'message' => $response->getMessage());

                    }

                } else {
                    // not successful
                    return array('status' => false, 'message' => __('Order Code is not exists', ST_TEXTDOMAIN));

                }

            }


            function handle_link($link1, $link2)
            {
                global $wp_rewrite;
                if ($wp_rewrite->permalink_structure == '') {
                    return $link1 . '&' . $link2;
                } else {
                    return $link1 . '?' . $link2;
                }
            }

        }

    }