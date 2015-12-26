<?php
/**
 * Created by PhpStorm.
 * User: MSI
 * Date: 03/06/2015
 * Time: 3:53 CH
 */
$div_id = "st_cart_item".md5(json_encode($st_booking_data['cart_item_key']));
?>
<p class="booking-item-description">
    <?php echo __( 'Duration:' , ST_TEXTDOMAIN ); ?>
    <?php echo date_i18n( get_option( 'date_format' ) , strtotime( $st_booking_data[ 'check_in' ] ) ) ?>
    <i class="fa fa-long-arrow-right"></i>
    <?php echo date_i18n( get_option( 'date_format' ) , strtotime( $st_booking_data[ 'check_out' ] ) ) ?>
</p>
<div id="<?php echo esc_attr($div_id);?>" class='<?php if (apply_filters('st_woo_cart_is_collapse' , false)) {echo esc_attr("collapse");}?>'>
    <p><small><?php echo __("Booking Details" , ST_TEXTDOMAIN) ; ?></small> </p>
    <div class='cart_border_bottom'></div>
    <div class="cart_item_group" style='margin-bottom: 10px'>
        <p class="booking-item-description"> 
            <?php if(!empty($st_booking_data['activity_time'])): ?>
            <?php echo __( 'Department Time:' , ST_TEXTDOMAIN ); ?>
            <?php echo $st_booking_data['activity_time'] ?>
            <?php endif; ?>
        </p>
    </div>
    <div class="cart_item_group" style='margin-bottom: 10px'>
        <?php 
            $data_price = $st_booking_data['data_price'];
            $adult_price = $data_price['adult_price'];
            $child_price = $data_price['child_price'];
            $infant_price = $data_price['infant_price'];
         ?>
        <p class="booking-item-description"> 
            <?php if($st_booking_data['adult_number']){?>
                <?php echo __( 'Adult Number:' , ST_TEXTDOMAIN ); ?>
                <?php echo $st_booking_data[ 'adult_number' ];?>
                <?php if(isset($st_booking_data['adult_price']) and $st_booking_data['adult_price']){?>
                x
                <?php echo TravelHelper::format_money($adult_price/$st_booking_data['adult_number']) ?>
                <i class="fa fa-long-arrow-right"></i>
                <?php echo TravelHelper::format_money($adult_price); ?>
                <?php }?>
            <?php }?>
        </p>
        <p class="booking-item-description"> 
            <?php if($st_booking_data['child_number']){?>
                <?php echo __( 'Children Number:' , ST_TEXTDOMAIN ); ?>
                <?php echo $st_booking_data[ 'child_number' ] ?>
                <?php if(isset($st_booking_data['child_price']) and $st_booking_data['child_price']){?>
                x
                <?php echo TravelHelper::format_money($child_price/$st_booking_data['child_number']) ?>
                <i class="fa fa-long-arrow-right"></i>
                <?php echo TravelHelper::format_money($child_price); ?>
                <?php }?>
            <?php }?>
        </p>
        <p class="booking-item-description"> 
            <?php if($st_booking_data['infant_number']){?>
                <?php echo __( 'Infant Number:' , ST_TEXTDOMAIN ); ?>
                <?php echo $st_booking_data[ 'infant_number' ]; ?>
                <?php if(isset($st_booking_data['infant_price']) and $st_booking_data['infant_price']): ?>
                x
                <?php echo TravelHelper::format_money($infant_price/$st_booking_data['infant_number']) ?>
                <i class="fa fa-long-arrow-right"></i>
                <?php echo TravelHelper::format_money($infant_price); ?>
                <?php endif; ?>
            <?php }?> 
        </p>
    </div>
    
    <div class="cart_item_group" style='margin-bottom: 10px'>
        <?php 
            $discount = $st_booking_data['discount_rate'];
            if (!empty($discount)){ ?>
                <b class='booking-cart-item-title'><?php echo __( "Discount", ST_TEXTDOMAIN); ?>: </b>
                <?php echo esc_attr($discount)."%" ?>
            <?php }            
        ?>        
    </div>
    <div class="cart_item_group" style='margin-bottom: 10px'>        
        <?php  if ( get_option( 'woocommerce_tax_total_display' ) == 'itemized' ) {
            $wp_cart = WC()->cart->cart_contents; 
            $item = $wp_cart[$st_booking_data['cart_item_key']];
            $tax = $item['line_tax']; 
            if (!empty($tax)) { ?>
                <b class='booking-cart-item-title'><?php echo __( "Tax", ST_TEXTDOMAIN); ?>: </b>
                <?php echo TravelHelper::format_money($tax);?>
            <?php }
        }else {$tax = 0 ;}
        ?>
    </div>
    <div class='cart_border_bottom'></div>
    <div class="cart_item_group" style='margin-bottom: 10px'>        
        <b class='booking-cart-item-title'><?php echo __("Total amount" , ST_TEXTDOMAIN) ;  ?>:</b>  
        <?php echo TravelHelper::format_money($st_booking_data['ori_price'] + $tax )?>
    </div>
</div>


