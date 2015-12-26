<?php global $post; ?>

<div class="row calendar-wrapper" data-post-id="<?php echo $post->ID; ?>">
    <div class="overlay">
        <span class="spinner is-active"></span>
    </div>
    <div class="col-xs-12 col-lg-4">
        <div class="calendar-form">
            <div class="form-group">
                <label for="calendar_check_in"><strong><?php echo __('Check In', ST_TEXTDOMAIN); ?></strong></label>
                <input readonly="readonly" type="text" class="widefat option-tree-ui-input date-picker" name="calendar_check_in" id="calendar_check_in">
            </div> 
            <div class="form-group">
                <label for="calendar_check_out"><strong><?php echo __('Check Out', ST_TEXTDOMAIN); ?></strong></label>
                <input readonly="readonly" type="text" class="widefat option-tree-ui-input date-picker" name="calendar_check_out" id="calendar_check_out">
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="form-group">
                        <label><strong><?php echo __('Price ($)', ST_TEXTDOMAIN); ?></strong></label>
                    </div>
                </div>
                <div class="col-xs-12 col-md-6 col-lg-4">
                    <div class="form-group">
                        <label for="calendar_adult_price"><?php echo __('Adult', ST_TEXTDOMAIN); ?></label>
                        <input type="text" name="calendar_adult_price" id="calendar_adult_price" class="form-control">
                    </div>
                </div>
                <div class="col-xs-12 col-md-6 col-lg-4">
                    <div class="form-group">
                        <label for="calendar_child_price"><?php echo __('Children', ST_TEXTDOMAIN); ?></label>
                        <input type="text" name="calendar_child_price" id="calendar_child_price" class="form-control">
                    </div>
                </div>
                <div class="col-xs-12 col-md-6 col-lg-4">
                    <div class="form-group">
                        <label for="calendar_infant_price"><?php echo __('Infant', ST_TEXTDOMAIN); ?></label>
                        <input type="text" name="calendar_infant_price" id="calendar_infant_price" class="form-control">
                    </div>
                </div>
            </div><!-- 
            <div class="form-group">
                <label for="calendar_number"><?php echo __('Number of People', ST_TEXTDOMAIN); ?></label>
                <input type="text" name="calendar_number" id="calendar_number" class="form-control">
            </div> -->
            <div class="form-group">
                <label for="calendar_status"><?php echo __('Status', ST_TEXTDOMAIN); ?></label>
                <select name="calendar_status" id="calendar_status">
                    <option value="available"><?php echo __('Available', ST_TEXTDOMAIN); ?></option>
                    <option value="unavailable"><?php echo __('Unavailble', ST_TEXTDOMAIN); ?></option>
                </select>
            </div>
            <div class="form-group">
                <input type="checkbox" name="calendar_groupday" id="calendar_groupday" value="1"><span class="ml5"><?php echo __('Group day', ST_TEXTDOMAIN); ?></span>
            </div>
            <div class="form-group">
                <div class="form-message">
                    <p></p>
                </div>
            </div>
            <div class="form-group">
                <input type="hidden" name="calendar_post_id" value="<?php echo $post->ID; ?>">
                <input type="submit" id="calendar_submit" class="button button-primary" name="calendar_submit" value="<?php echo __('Update', ST_TEXTDOMAIN); ?>">
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-lg-8">
        <div class="calendar-content">
            
        </div>
    </div>
</div>