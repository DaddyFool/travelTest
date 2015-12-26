<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Activity field list location
 *
 * Created by ShineTheme
 *
 */
$default=array(
    'title'=>'',
    'is_required'=>'on',
    'is_required'=>'off',
);
if(isset($data)){
    extract(wp_parse_args($data,$default));
}else{
    extract($default);
}
if($is_required == 'on'){
    $is_required = 'required';
}
if(!isset($field_size)) $field_size='lg';
$old_location=STInput::get('location_id');
$list_location = TravelerObject::get_list_location();
if($is_required == 'on'){
    $is_required = 'required';
}
?>
<?php if(!empty($list_location) and is_array($list_location)): ?>
<div class="form-group form-group-<?php echo esc_attr($field_size)?> form-group-icon-left">
    
    <label for="field-list-location"><?php echo esc_html( $title)?></label>
    <i class="fa fa-map-marker input-icon"></i>
   <select id="field-list-location" name="location_id" class="form-control" <?php echo esc_attr($is_required) ?>>
       <option value=""><?php _e('-- Select --',ST_TEXTDOMAIN) ?></option>
       <?php foreach($list_location as $k=>$v): ?>
            <option <?php if($old_location == $v['id'] ) echo 'selected' ?> value="<?php echo esc_html($v['id']) ?>">
                <?php echo esc_html($v['title']) ?>
            </option>
       <?php endforeach; ?>
   </select>
</div>
<?php endif ?>