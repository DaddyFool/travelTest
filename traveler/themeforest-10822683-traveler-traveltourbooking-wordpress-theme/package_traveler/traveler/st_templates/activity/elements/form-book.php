<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Activity element form book
 *
 * Created by ShineTheme
 *
 */

//check is booking with modal
$st_is_booking_modal = apply_filters( 'st_is_booking_modal' , false );
$type_activity       = get_post_meta( get_the_ID() , 'type_activity' , true );
?>
<?php echo STTemplate::message(); ?>

<div class="info-activity">

<?php
    $activity_time = get_post_meta( get_the_ID() , 'activity-time' , true );
    if(!empty( $activity_time ) and $type_activity != 'daily_activity'):
?>
        <div class="info">
            <span class="head"><i class="fa fa-clock-o"></i> <?php st_the_language( 'activity_time' ) ?> : </span>
            <span><?php echo esc_html( $activity_time ); ?> </span>
        </div>
<?php endif; ?>
<?php
    if($type_activity != 'daily_activity'):
        $check_in  = get_post_meta( get_the_ID() , 'check_in' , true );
        $check_out = get_post_meta( get_the_ID() , 'check_out' , true );
        if(!empty( $check_in ) and !empty( $check_out )):
            ?>
            <div class="info">
                <span class="head"><i class="fa fa-calendar"></i> <?php st_the_language( 'availability' ) ?> : </span>
            <span>
                <?php echo date_i18n( TravelHelper::getDateFormat() , strtotime( $check_in ) ) ?>
                <i class="fa fa-arrow-right"></i>
                <?php echo date_i18n( TravelHelper::getDateFormat() , strtotime( $check_out ) ) ?>
            </span>
            </div>
<?php endif; endif; ?>
<?php
    $facilities = get_post_meta( get_the_ID() , 'venue-facilities' , true );
    if(!empty( $facilities )):
?>
        <div class="info">
            <span class="head"><i class="fa fa-cogs"></i> <?php st_the_language( 'venue_facilities' ) ?> : </span>
            <span><?php echo esc_html( $facilities ); ?> </span>
        </div>
<?php endif; ?>
</div>
<div class="package-info-wrapper">
    <div class="overlay-form"><i class="fa fa-refresh text-color"></i></div>
    <form id="form-booking-inpage" method="post" action="" class="activity_booking_form booking_modal_form" data-activity-type="<?php echo esc_attr($type_activity); ?>">
        <div class="message_box"></div>
        <?php
        if(!get_option( 'permalink_structure' )) {
            echo '<input type="hidden" name="st_activity"  value="' . st_get_the_slug() . '">';
        }
        ?>
        <input type="hidden" name="action" value="activity_add_to_cart">
        <input type="hidden" name="item_id" value="<?php echo get_the_ID() ?>">
        <input name="type_activity" type="hidden" value="<?php echo $type_activity; ?>">
        <div class="book_form">
            <span style=" display: none;"><?php st_the_language( 'guests' ) ?> :</span>
            <?php
            if($type_activity == 'daily_activity'):
                $booking_period = get_post_meta(get_the_ID(), 'activity_booking_period', true);
                if(empty($booking_period) || $booking_period <= 0) $booking_period = 0;
            ?>
                <div class="input-daterange">
                    <div class="row">
                        <div class="col-md-6">
                            <span><?php _e( 'Check In' , ST_TEXTDOMAIN ) ?>: </span>
                            <?php 
                                $start = STInput::request('check_in','');
                                if(empty($start)){
                                    $start = STInput::request('start','');
                                }
                            ?>
                            <input data-booking-period="<?php echo $booking_period; ?>" data-date-format="<?php echo TravelHelper::getDateFormatJs(); ?>" data-activity-id="<?php echo get_the_ID(); ?>" class="activity_book_date form-control"
                                   placeholder="<?php echo TravelHelper::getDateFormatJs(); ?>" type="text"
                                   value="<?php echo $start; ?>" name="check_in">
                        </div>
                    </div>
                </div>
            <?php else: 
                $check_in = get_post_meta( get_the_ID() , 'check_in' , true );
                $check_in = $check_in ? date(TravelHelper::getDateFormat(), strtotime($check_in)) : '';

                $check_out = get_post_meta( get_the_ID(), 'check_out', true);

                $check_out = $check_out ? date(TravelHelper::getDateFormat(), strtotime($check_out)) : '';
            ?>
                <input name="check_in" type="hidden" class="activity_check_in"
                       value="<?php echo $check_in; ?>">
                <input name="check_out" type="hidden" class="activity_check_out"
                       value="<?php echo $check_out; ?>">

            <?php endif; ?>

            <div class="row line_ald">
                <div class="col-md-4">
                    <span><?php _e( 'Adults' , ST_TEXTDOMAIN ) ?>: </span>
                    <select class="form-control st_tour_adult" name="adult_number" required>
                        <?php 
                        $adult_number = intval(STInput::request('adult_number', 1));
                        for( $i = 1 ; $i <= 20 ; $i++ ) {
                            echo "<option ".selected($adult_number, $i)." value='{$i}'>{$i}</option>";
                        } ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <span><?php _e( 'Children' , ST_TEXTDOMAIN ) ?>: </span>
                    <select class="form-control st_tour_children" name="child_number" required>
                        <?php 
                        $child_number = intval(STInput::request('child_number', 0));
                        for( $i = 0 ; $i <= 20 ; $i++ ) {
                            echo "<option ".selected($child_number, $i)." value='{$i}'>{$i}</option>";
                        } ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <span><?php _e( 'Infant' , ST_TEXTDOMAIN ) ?>: </span>
                    <select class="form-control st_tour_infant" name="infant_number" required>
                        <?php 
                        $infant_number = intval(STInput::request('infant_number', 0));
                        for( $i = 0 ; $i <= 20 ; $i++ ) {
                            echo "<option ".selected($infant_number, $i)." value='{$i}'>{$i}</option>";
                        } ?>
                    </select>
                </div>
            </div>
            <br>
            <?php if($st_is_booking_modal) { ?>
                <a href="#activity_booking_<?php the_ID() ?>" class="btn btn-primary popup-text btn_activity"
                   data-effect="mfp-zoom-out"><?php st_the_language( 'book_now' ) ?></a>
            <?php } else { ?>

                <?php echo STActivity::activity_external_booking_submit(); ?>

            <?php } ?>
            <?php
            $best_price = get_post_meta( get_the_ID() , 'best-price-guarantee' , true );
            if($best_price == 'on') {
                ?>
                <div class="btn btn-ghost btn-info tooltip_2 tooltip_2-effect-1 activity" style="font-size: 16px">
                <span class="">
                    <?php st_the_language( 'best_price_guarantee' ) ?>
                    <i class="fa fa-check-square-o fa-lg primary"></i>
                </span>
                <span class="tooltip_2-content clearfix title">
                    <?php echo get_post_meta( get_the_ID() , 'best-price-guarantee-text' , true ) ?>
                </span>
                </div>
            <?php } ?>
        </div>

    </form>
</div>    
<?php
if($st_is_booking_modal) {
    ?>
    <div class="mfp-with-anim mfp-dialog mfp-search-dialog mfp-hide" id="activity_booking_<?php the_ID() ?>">
        <?php echo st()->load_template( 'activity/modal_booking' ); ?>
    </div>

<?php } ?>