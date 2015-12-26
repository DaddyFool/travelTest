<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Cars element search field pick up form
 *
 * Created by ShineTheme
 *
 */
$default=array(
    'title'       =>'',
    'is_required' =>'off',
    'placeholder' => ''
);
if(isset($data)){
    extract(wp_parse_args($data,$default));
}else{
    extract($default);
}

$pickup_title = '';
$dropoff_title = '';
$title = explode(',',$title);
if(isset($title) && !empty($title[1])){
    $pickup_title = $title[0];
    $dropoff_title = $title[1];
}
if(isset($placeholder) && !empty($placeholder)){
    $placeholder = explode(',', $placeholder);
}

if($is_required == 'on'){
    $is_required = 'required';
}
if(!isset( $field_size ))
    $field_size = 'md';

$location_id_pick_up = STInput::get('location_id_pick_up', '');
$location_country = get_post_meta($location_id_pick_up, 'location_country', true);

$location_id_drop_off = STInput::get('location_id_drop_off', '');
$pick_up = STInput::request('pick-up', '');
$drop_off = STInput::request('drop-off', '');
$required_dropoff = STInput::request('required_dropoff', 'off');
$hidden = '';
if($required_dropoff == 'on') $hidden = 'field-hidden';

$locations = TravelHelper::getListFullNameLocation();
?>
<div class="form-group form-group-<?php echo esc_attr($field_size)?> form-group-icon-left">
    
    <label for="field-car-dropoff"><?php echo $pickup_title; ?></label>
    <i class="fa fa-map-marker input-icon"></i>
    <div class="st-select-wrapper">
        <input data-children="location_id_drop_off" data-clear="clear" autocomplete="off" type="text" name="pick-up" value="<?php echo $pick_up; ?>" class="form-control st-location-name <?php echo esc_attr($is_required); ?>" placeholder="<?php if(isset($placeholder[0])) echo $placeholder[0]; ?>">
        <select id="field-car-dropoff" data-current-country="" name="location_id_pick_up" class="st-location-id st-hidden" tabindex="-1">
            <option value=""></option>
            <?php 
                if(is_array($locations) && count($locations)):
                    foreach($locations as $key => $value):
            ?>
            <option <?php selected($value->ID, $location_id_pick_up); ?> data-country="<?php echo $value->Country; ?>" value="<?php echo $value->ID; ?>"><?php echo TravelHelper::showNameLocation($value->ID); ?></option>
            <?php endforeach; endif; ?>
        </select>
        <div class="option-wrapper"></div>
    </div>
</div>
<div class="form-group form-group-<?php echo esc_attr($field_size)?> form-group-icon-left ml20">
    <label for="required_dropoff"><input <?php if($required_dropoff == 'on') echo 'checked'; ?> type="checkbox" name="required_dropoff" id="required_dropoff" class="required-field i-check"><?php echo __('Same location', ST_TEXTDOMAIN); ?></label>
</div>
<div class="form-drop-off <?php echo $hidden; ?>">
    <div class=" form-group form-group-<?php echo esc_attr($field_size)?> form-group-icon-left">
        
        <label for="field-car-pickup"><?php echo $dropoff_title; ?></label>
        <i class="fa fa-map-marker input-icon"></i>
        <div class="st-select-wrapper">
            <input data-parent="location_id_pick_up" data-clear="clear" autocomplete="off" type="text" name="drop-off" value="<?php echo $drop_off; ?>" class="form-control st-location-name" placeholder="<?php if(isset($placeholder[1])) echo $placeholder[1]; ?>" >
            <select id="field-car-pickup" data-current-country="<?php if($location_country) echo $location_country; ?>" name="location_id_drop_off" class="st-location-id st-hidden " tabindex="-1">
                <option value=""></option>
                <?php 
                    if(is_array($locations) && count($locations)):
                        foreach($locations as $key => $value):
                ?>
                <option <?php selected($value->ID, $location_id_drop_off); ?> data-country="<?php echo $value->Country; ?>" value="<?php echo $value->ID; ?>"><?php echo TravelHelper::showNameLocation($value->ID); ?></option>
                <?php endforeach; endif; ?>
            </select>
            <div class="option-wrapper"></div>
        </div>
    </div>
</div>    
