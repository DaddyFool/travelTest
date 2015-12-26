<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Activity field taxonomy
 *
 * Created by ShineTheme
 *
 */
$default=array(
    'title'=>'',
    'taxonomy'=>'',
    'is_required'=>'on',
    'type_show_taxonomy_activity'=>'checkbox'
);

if(isset($data)){
    extract(wp_parse_args($data,$default));
}else{
    extract($default);
}

if(!isset($field_size)) $field_size='lg';

$terms = get_terms($taxonomy);

if($is_required == 'on'){
    $is_required = 'required';
}

if(!empty($terms)):
    if($type_show_taxonomy_activity == "select"){
        ?>
        <div class="form-group form-group-<?php echo esc_attr($field_size)?>" taxonomy="<?php echo esc_html($taxonomy) ?>">
            <label for="field-taxonomy-<?php echo esc_html($taxonomy) ?>"><?php echo esc_html( $title)?></label>
            <select id="field-taxonomy-<?php echo esc_html($taxonomy) ?>" class="form-control" name="taxonomy[<?php echo esc_html($taxonomy) ?>]" <?php echo esc_attr($is_required) ?>>
                <option value=""><?php _e('-- Select --',ST_TEXTDOMAIN) ?></option>
                <?php if(is_array($terms)){ ?>
                    <?php foreach($terms as $k=>$v){ ?>
                        <?php $is_taxonomy = STInput::request('taxonomy'); ?>
                        <option <?php if(!empty($is_taxonomy[$taxonomy]) and $is_taxonomy[$taxonomy] == $v->term_id) echo 'selected'; ?>  value="<?php echo esc_attr($v->term_id) ?>">
                            <?php echo esc_html($v->name) ?>
                        </option>
                    <?php } ?>
                <?php } ?>
            </select>
        </div>
    <?php }else{ ?>
        <div class="form-custom-taxonomy form-group form-group-<?php echo esc_attr($field_size)?>" taxonomy="<?php echo esc_html($taxonomy) ?>">
            <label for="field-taxonomy-<?php echo esc_html($taxonomy) ?>"><?php echo esc_html( $title)?></label>
            <?php if(is_array($terms)){ ?>
                <?php foreach($terms as $k=>$v){ ?>
                    <?php $is_taxonomy = STInput::request('taxonomy');
                    $is_check = '';
                    if(!empty($is_taxonomy[$taxonomy])){
                        $data = explode(',',$is_taxonomy[$taxonomy]);
                        if(in_array($v->term_id,$data)){
                            $is_check = 'checked';
                        }
                    }
                    ?>
                    <div class="checkbox">
                        <label>
                            <input class="i-check item_tanoxomy" <?php echo esc_html($is_check) ?>  type="checkbox" value="<?php echo esc_attr($v->term_id) ?>" /><?php echo esc_html($v->name) ?></label>
                    </div>
                <?php } ?>
            <?php } ?>
            <input type="hidden" class="data_taxonomy" name="taxonomy[<?php echo esc_html($taxonomy) ?>]" value="">
        </div>
    <?php } ?>
<?php endif ?>
