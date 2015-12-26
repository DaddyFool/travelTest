<?php 
$format=TravelHelper::getDateFormat();
$div_id = "st_cart_item".md5(json_encode($st_booking_data['cart_item_key']));
$data = $st_booking_data; 
?>
<?php if(isset($data['type_tour'])): ?>
<p class="booking-item-description"><span><?php echo __('Type tour', ST_TEXTDOMAIN); ?>: </span><?php echo $data['type_tour']; ?></p>
<?php endif; ?>

<?php if(isset($data['type_tour']) && $data['type_tour'] == 'daily_tour'): ?>
<p class="booking-item-description"><span><?php echo __('Departure date', ST_TEXTDOMAIN); ?>: </span><?php echo $data['check_in']; ?></p>
<p class="booking-item-description"><span><?php echo __('Duration', ST_TEXTDOMAIN); ?>: </span><?php echo $data['duration']; ?></p>
<?php endif; ?>

<?php if(isset($data['type_tour']) && $data['type_tour'] == 'specific_date'): ?>
<p class="booking-item-description"><span><?php echo __('Departure date', ST_TEXTDOMAIN); ?>: </span><?php echo $data['check_in']; ?></p>
<p class="booking-item-description"><span><?php echo __('Arrive date', ST_TEXTDOMAIN); ?>: </span><?php echo $data['check_out']; ?></p>
<?php endif; ?>

<div id="<?php echo esc_attr($div_id);?>" class='<?php if (apply_filters('st_woo_cart_is_collapse' , false)) {echo esc_attr("collapse");}?>'>
	<p><small><?php echo __("Booking Details" , ST_TEXTDOMAIN) ; ?></small> </p>
	<div class='cart_border_bottom'></div>
	<div class="cart_item_group" style='margin-bottom: 10px'>
        <div class="booking-item-description">   
            <?php 
                $data_price = $st_booking_data['data_price'];                
                $adult_price = $data_price['adult_price'];
                $child_price = $data_price['child_price'];
                $infant_price = $data_price['infant_price'];
             ?>        	
			<p class="booking-item-description"> 
			     <?php if (!empty($data['adult_number'])) :?>
                     <span><?php echo __('Adult number', ST_TEXTDOMAIN); ?>: </span><?php echo $data['adult_number']; ?> 
                    x 
                    <?php if (!empty($data['adult_price'])){                         
                        echo TravelHelper::format_money($adult_price/$data['adult_number']);
                        echo ' <i class="fa fa-long-arrow-right"></i> ';
                        echo TravelHelper::format_money($adult_price);
                        }
                    ?>
                    <br>
                <?php endif ; ?>
                <?php if (!empty($data['child_number'])) :?>
                    <span><?php echo __('Children number', ST_TEXTDOMAIN); ?>: </span><?php echo $data['child_number']; ?>
                    x 
                    <?php if (!empty($data['child_price'])){                         
                        echo TravelHelper::format_money($child_price/$data['child_number']);
                        echo ' <i class="fa fa-long-arrow-right"></i> ';
                        echo TravelHelper::format_money($child_price);
                        }
                    ?>
                    <br>
                <?php endif ; ?>
                <?php if (!empty($data['infant_number'])) :?>
                    <span><?php echo __('Infant number', ST_TEXTDOMAIN); ?>: </span><?php echo $data['infant_number']; ?>
                    x 
                    <?php if (!empty($data['infant_price'])){                         
                        echo TravelHelper::format_money($infant_price/$data['infant_number']);
                        echo ' <i class="fa fa-long-arrow-right"></i> ';
                        echo TravelHelper::format_money($infant_price);
                        }
                    ?>
                    <br>
                <?php endif ; ?>
			</p>
        </div>
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
        }else{$tax = 0;}
        ?>
    </div>
    <div class='cart_border_bottom'></div>
    <div class="cart_item_group" style='margin-bottom: 10px'>        
        <b class='booking-cart-item-title'><?php echo __("Total amount" , ST_TEXTDOMAIN) ;  ?>:</b>  
        <?php echo TravelHelper::format_money($st_booking_data['ori_price'] + $tax )?>
    </div>   
</div>