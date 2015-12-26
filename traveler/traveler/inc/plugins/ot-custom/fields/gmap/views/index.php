<?php
/**
 * Created by PhpStorm.
 * User: me664
 * Date: 1/20/15
 * Time: 3:23 PM
 */
$default = array(
    'field_desc'  => '',
    'field_name'  => '',
    'field_value' => array(),
    'meta'        => '',
    'field_id'    => '',
    'type'        => ''
);


$args = wp_parse_args($args, $default);


extract($args);

if($field_value == 'off'){
    $zoom = get_post_meta( STInput::request('post'),'map_zoom',true);
    if(empty($zoom))$zoom='1';
    $field_value  =array(
        'lat'=>get_post_meta( STInput::request('post'),'map_lat',true),
        'lng'=>get_post_meta( STInput::request('post'),'map_lng',true),
        'zoom'=>$zoom,
    );
}
if(is_page_template('template-user.php')){
    $zoom = get_post_meta( STInput::request('id'),'map_zoom',true);
    if(empty($zoom))$zoom='1';
    $field_value  =array(
        'lat'=>get_post_meta( STInput::request('id'),'map_lat',true),
        'lng'=>get_post_meta( STInput::request('id'),'map_lng',true),
        'zoom'=>$zoom,
    );
}
if(!empty($field_post_type)){
    $post_type = $field_post_type;
}


/* verify a description */
$has_desc = $field_desc ? TRUE : FALSE;
echo '<div class="format-setting type-post_select_ajax ' . ($has_desc ? 'has-desc' : 'no-desc') . '">';
/* description */
echo $has_desc ? '<div class="description">' . htmlspecialchars_decode($field_desc) . '</div>' : '';
/* format setting inner wrapper */
echo '<div class="format-setting-inner">';
/* allow fields to be filtered */
echo '<div class="option-tree-ui-' . $type . '-input-wrap">';
?>
    <div class="bt_ot_gmap_wrap">
        <input type="text" placeholder="<?php _e('Search by name...', 'bravo-toolkit') ?>" class="bt_ot_searchbox"/>

        <div class="bt_ot_gmap">

        </div>
        <?php $validator= STUser_f::$validator; ?>
        <div class="bt_ot_map_field">
            <label for=""><span class="title"><?php _e('Latitude:', ST_TEXTDOMAIN) ?></span>
                <?php
                $value ='';
                if(!empty($field_value['lat'])){
                    $value = $field_value['lat'];
                }else{
                    $tmp = STInput::request('gmap');
                    $value = $tmp['lat'];
                } ?>
                <input type="text" placeholder="<?php _e('Latitude', ST_TEXTDOMAIN) ?>" id="bt_ot_gmap_input_lat" class="number bt_ot_gmap_input_lat form-control"
                       value="<?php echo esc_html($value) ?>"
                       name="<?php echo esc_attr($field_name) ?>[lat]"/>
                <div class="st_msg">
                    <?php echo STUser_f::get_msg_html($validator->error('gmap[lat]'),'danger') ?>
                </div>
            </label>

            <label for="">
                <span class="title"><?php _e('Longitude:', ST_TEXTDOMAIN) ?></span>
                <?php
                $value ='';
                if(!empty($field_value['lng'])){
                    $value = $field_value['lng'];
                }else{
                    $tmp = STInput::request('gmap');
                    $value = $tmp['lng'];
                } ?>
                <input value="<?php echo esc_html($value) ?>" type="text"
                       placeholder="<?php _e('Longitude', ST_TEXTDOMAIN) ?>" id="bt_ot_gmap_input_lng" class="number bt_ot_gmap_input_lng form-control"
                       name="<?php echo esc_attr($field_name) ?>[lng]"/>
                <div class="st_msg ">
                    <?php echo STUser_f::get_msg_html($validator->error('gmap[lng]'),'danger') ?>
                </div>
            </label>
            <label for=""><span class="title"><?php _e('Zoom:', ST_TEXTDOMAIN) ?></span>
                <?php
                $value ='';
                $tmp = STInput::request('gmap');
                if(!empty($tmp)){
                    $value = $tmp['zoom'];
                }elseif(!empty($field_value['zoom'])){
                    $value = $field_value['zoom'];
                } ?>
                <input value="<?php echo esc_html($value) ?>" type="text"
                       placeholder="<?php _e('Zoom Level', ST_TEXTDOMAIN) ?>" id="bt_ot_gmap_input_zoom" class="number bt_ot_gmap_input_zoom form-control"
                       name="<?php echo esc_attr($field_name) ?>[zoom]"/>
                <div class="st_msg">
                    <?php echo STUser_f::get_msg_html($validator->error('gmap[zoom]'),'danger') ?>
                </div>
            </label>
            <input type="hidden" class="bt_ot_gmap_input_type"
                   name="<?php echo esc_attr($field_name) ?>[map_type]"/>
        </div>
    </div>
<?php
echo '</div>';
echo '</div>';
echo '</div>';