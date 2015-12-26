<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Cars modal booking
 *
 * Created by ShineTheme
 *
 */
//Logged in User Info
global $firstname , $user_email;
get_currentuserinfo();

?>
<h3><?php printf(st_get_language('you_are_booking_for_s'),get_the_title())?></h3>

<form id="booking_modal_<?php echo get_the_ID() ?>" class="booking_modal_form" action="" method="post" onsubmit="return false">
    <div>
        <input type="hidden" name="action" value="booking_form_submit">
        <?php echo st()->load_template('check_out/check_out',null,array('post_id'=>get_the_ID()))?>
    </div>
</form>