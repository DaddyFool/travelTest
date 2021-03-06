<?php
    /**
     * @package WordPress
     * @subpackage Traveler
     * @since 1.0
     *
     * Holidays field duration
     *
     * Created by ShineTheme
     *
     */
    $default=array(
        'title'=>'',
        'is_required'=>'on',
    );

    if(isset($data)){
        extract(wp_parse_args($data,$default));
    }else{
        extract($default);
    }
    if($is_required == 'on'){
        $is_required = 'required';
    }
?>
<div class="form-group form-group-lg form-group-icon-left">
    
    <label for="field-holiday-duration"><?php echo esc_html($title)?></label>
    <i class="fa fa-calendar input-icon input-icon-highlight"></i>
    <input id="field-holiday-duration" name="duration" <?php echo esc_attr($is_required) ?> value="<?php echo STInput::get('duration') ?>" class="typeahead_location form-control <?php echo esc_attr($is_required) ?>" placeholder="<?php st_the_language('holiday_duration')?>" type="text" />
</div>