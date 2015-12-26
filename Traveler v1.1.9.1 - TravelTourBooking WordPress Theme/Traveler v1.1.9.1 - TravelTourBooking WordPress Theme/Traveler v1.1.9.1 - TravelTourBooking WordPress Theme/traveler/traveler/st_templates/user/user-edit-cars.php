<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * User create cars
 *
 * Created by ShineTheme
 *
 */
if( STUser_f::st_check_edit_partner(STInput::request('id')) == false ){
    return false;
}
$post_id = STInput::request('id');
$post = get_post( $post_id );
$validator= STUser_f::$validator;
?>
<div class="st-create">
    <h2 class="pull-left"><?php _e("Edit Car",ST_TEXTDOMAIN) ?></h2>
    <a target="_blank" href="<?php echo get_the_permalink($post_id) ?>" class="btn btn-default pull-right"><?php _e("Preview",ST_TEXTDOMAIN) ?></a>
</div>
<div class="msg">
    <?php echo STTemplate::message() ?>
    <?php echo STUser_f::get_msg(); ?>
</div>
<form action="" method="post" enctype="multipart/form-data" id="st_form_add_partner">
    <?php wp_nonce_field( 'user_setting' , 'st_update_post_cars' ); ?>
    <div class="form-group form-group-icon-left">
        
        <label for="title" class="head_bol"><?php _e("Name Of Car",ST_TEXTDOMAIN) ?>:</label>
        <i class="fa  fa-file-text input-icon input-icon-hightlight"></i>
        <input id="title" name="title" type="text"
               placeholder="<?php _e("Name Of Car",ST_TEXTDOMAIN) ?>" class="form-control" value="<?php echo esc_html($post->post_title) ?>">
        <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('title'),'danger') ?></div>
    </div>
    <div  class="form-group form-group-icon-left hidden">
        <label for="st_content" class="head_bol"><?php st_the_language( 'user_create_car_content' ) ?>:</label>
        <?php wp_editor( $post->post_content ,'st_content'); ?>
        <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('st_content'),'danger') ?></div>
    </div>
    <div class="form-group">
        <label for="desc" class="head_bol"><?php _e("Car Description",ST_TEXTDOMAIN) ?>:</label>
        <textarea id="desc" rows="6" name="desc" class="form-control"><?php echo esc_html($post->post_excerpt) ?></textarea>
        <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('desc'),'danger') ?></div>
    </div>
    <div class="form-group form-group-icon-left">
        <label for="id_featured_image" class="head_bol"><?php _e("Featured Image",ST_TEXTDOMAIN) ?>:</label>
        <?php $id_img = get_post_thumbnail_id($post_id);
        $post_thumbnail_id = wp_get_attachment_image_src($id_img, 'full');
        ?>
        <div class="input-group">
                <span class="input-group-btn">
                    <span class="btn btn-primary btn-file">
                        <?php _e("Browse…",ST_TEXTDOMAIN) ?> <input name="featured-image"  type="file" >
                    </span>
                </span>
            <input type="text" readonly="" value="<?php echo esc_url($post_thumbnail_id['0']); ?>" class="form-control data_lable">
        </div>
        <input id="id_featured_image" name="id_featured_image" type="hidden" value="<?php echo esc_attr($id_img) ?>">
        <?php
        if(!empty($post_thumbnail_id)){
            echo '<div class="user-profile-avatar user_seting st_edit">
                        <div><img width="300" height="300" class="avatar avatar-300 photo img-thumbnail" src="'.$post_thumbnail_id['0'].'" alt=""></div>
                        <input name="" type="button"  class="btn btn-danger  btn_featured_image" value="'.st_get_language('user_delete').'">
                      </div>';
        }
        ?>
        <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('featured_image'),'danger') ?></div>
    </div>
    <div class="tabbable tabs_partner">
        <ul class="nav nav-tabs" id="">
            <li class="active"><a href="#tab-location-setting" data-toggle="tab"><?php _e("Location Settings",ST_TEXTDOMAIN) ?></a></li>
            <li><a href="#tab-car-details" data-toggle="tab"><?php _e("Car Details",ST_TEXTDOMAIN) ?></a></li>
            <li><a href="#tab-contact-details" data-toggle="tab"><?php _e("Contact Details",ST_TEXTDOMAIN) ?></a></li>
            <li><a href="#tab-price-setting" data-toggle="tab"><?php _e("Price Settings",ST_TEXTDOMAIN) ?></a></li>
            <li><a href="#tab-car-options" data-toggle="tab"><?php _e("Cars Options",ST_TEXTDOMAIN) ?></a></li>
            <?php $st_is_woocommerce_checkout=apply_filters('st_is_woocommerce_checkout',false);
            if(!$st_is_woocommerce_checkout):?>
                <li><a href="#tab-payment" data-toggle="tab"><?php _e("Payment Settings",ST_TEXTDOMAIN) ?></a></li>
            <?php endif ?>
            <?php $custom_field = st()->get_option( 'st_cars_unlimited_custom_field' );
            if(!empty( $custom_field ) and is_array( $custom_field )) { ?>
                <li><a href="#tab-custom-fields" data-toggle="tab"><?php _e("Custom Fields",ST_TEXTDOMAIN) ?></a></li>
            <?php } ?>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade in active" id="tab-location-setting">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group form-group-icon-left">
                            <label for="multi_location"><?php st_the_language( 'user_create_car_location' ) ?>:</label>
                            <div id="setting_multi_location" class="location-front">
                                <select placeholder="<?php echo __('Select location...',ST_TEXTDOMAIN); ?>" tabindex="-1" name="multi_location[]" id="multi_location" class="option-tree-ui-select list-item-post-type" data-post-type="location">
                                   <option value=""><?php echo __('Select a location', ST_TEXTDOMAIN); ?></option> 
                                   <?php
                                    $locations = TravelHelper::getLocationBySession();
                                    $html_location = TravelHelper::treeLocationHtml($locations, 0);

                                    if(is_array($html_location) && count($html_location)):
                                        foreach($html_location as $key => $value):
                                            $id = preg_replace("/(\_)/", "", $value['ID']);
                                    ?>      
                                        <option value="<?php echo $value['ID']; ?>"><?php echo $value['prefix'].get_the_title($id); ?></option>
                                    <?php
                                    endforeach; endif; ?>
                                </select>
                            </div>
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('multi_location'),'danger') ?></div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group form-group-icon-left">
                            
                            <label for="address"><?php st_the_language( 'user_create_car_address' ) ?>:</label>
                            <i class="fa fa-home input-icon input-icon-hightlight"></i>
                            <input id="address" name="address" type="text" placeholder="<?php st_the_language( 'user_create_car_address' ) ?>" class="form-control" value="<?php echo get_post_meta( $post_id , 'cars_address' , true); ?>">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('address'),'danger') ?></div>
                        </div>
                    </div>

                    <div class="col-md-12 partner_map">
                        <?php
                        if(class_exists('BTCustomOT')){
                            BTCustomOT::load_fields();
                            ot_type_bt_gmap_html();
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="tab-car-details">
                <div class="row">
                    <?php
                    $taxonomies = (get_object_taxonomies('st_cars'));
                    if (is_array($taxonomies) and !empty($taxonomies)){
                        foreach ($taxonomies as $key => $value) {
                            ?>
                            <div class="col-md-12">
                                <?php
                                $category = STUser_f::get_list_taxonomy($value);
                                $taxonomy_tmp = get_taxonomy( $value );
                                $taxonomy_label =  ($taxonomy_tmp->label );
                                $taxonomy_name =  ($taxonomy_tmp->name );
                                if(!empty($category)):
                                    ?>
                                    <div class="form-group form-group-icon-left">
                                        <label for="check_all"> <?php echo esc_html($taxonomy_label); ?>:</label>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="checkbox-inline checkbox-stroke">
                                                    <label for="check_all">
                                                        <i class="fa fa-cogs"></i>
                                                        <input name="check_all" class="i-check check_all" type="checkbox"  /><?php _e("All",ST_TEXTDOMAIN) ?>
                                                    </label>
                                                </div>
                                            </div>
                                            <?php foreach($category as $k=>$v):
                                                $icon = get_tax_meta($k,'st_icon');
                                                $icon = TravelHelper::handle_icon($icon);
                                                $check = '';
                                                if(STUser_f::st_check_post_term_partner( $post_id  ,$value , $k) == true ){
                                                    $check = 'checked';
                                                }
                                                ?>
                                                <div class="col-md-3">
                                                    <div class="checkbox-inline checkbox-stroke">
                                                        <label for="taxonomy">
                                                            <i class="<?php echo esc_html($icon) ?>"></i>
                                                            <input name="taxonomy[]" class="i-check item_tanoxomy" type="checkbox"  <?php echo esc_html($check) ?> value="<?php echo esc_attr($k.','.$taxonomy_name) ?>" /><?php echo esc_html($v) ?>
                                                        </label>
                                                    </div>
                                                </div>
                                            <?php endforeach ?>
                                        </div>
                                    </div>
                                <?php endif ?>
                            </div>
                        <?php
                        }
                    } else { ?>
                        <input name="no_taxonomy" type="hidden" value="no_taxonomy">
                    <?php } ?>
                    <div class="col-md-12">
                        <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('taxonomy[]'),'danger') ?></div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group form-group-icon-left">
                            <label for="create_car_equipment_price"><?php st_the_language( 'user_create_car_equipment_price_list' ) ?>:</label>
                        </div>
                    </div>
                    <div class="" id="data_equipment_item">
                        <?php $data =get_post_meta($post_id , 'cars_equipment_list',true); ?>
                        <?php
                        if(!empty($data)){
                            foreach($data as $k=>$v){?>
                                <div class="item">
                                    <div class="col-md-3">
                                        <div class="form-group ">
                                            <label for="equipment_item_title"><?php st_the_language( 'user_create_car_equipment_title' ) ?></label>
                                            <input id="title" name="equipment_item_title[]" type="text" class="form-control" value="<?php echo esc_html($v['title']) ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group ">
                                            <label for="equipment_item_price"><?php st_the_language( 'user_create_car_equipment_price' ) ?></label>
                                            <input id="price" name="equipment_item_price[]" type="text" class="form-control" value="<?php echo esc_html($v['cars_equipment_list_price']) ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group ">
                                            <label for="equipment_item_price_unit" ><?php _e("Price Unit",ST_TEXTDOMAIN) ?></label>
                                            <select class="form-control" id="equipment_item_price_unit" name="equipment_item_price_unit[]">
                                                <option value=""><?php _e("Fixed Price",ST_TEXTDOMAIN) ?></option>
                                                <option value="per_hour" <?php if($v['price_unit'] == 'per_hour') echo "selected" ?> ><?php _e("Price per Hour",ST_TEXTDOMAIN) ?></option>
                                                <option value="per_day" <?php if($v['price_unit'] == 'per_day') echo "selected" ?> ><?php _e("Price per Day",ST_TEXTDOMAIN) ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group ">
                                            <label for="price_max"><?php _e("Price Max",ST_TEXTDOMAIN) ?></label>
                                            <input id="price_max" name="equipment_item_price_max[]" type="text" class="form-control number" value="<?php echo esc_html($v['cars_equipment_list_price_max']) ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group form-group-icon-left">
                                            <div class="btn btn-danger btn_del_program" style="margin-top: 27px">
                                               X
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                        }
                        ?>
                    </div>
                    <div class="col-md-12 text-right">
                        <div class="form-group form-group-icon-left">
                            <button id="btn_add_equipment_item" type="button" class="btn btn-info btn-sm"><?php st_the_language( 'user_create_car_add_equipment' ) ?></button>
                            <br>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group form-group-icon-left">
                            <label for="create_car_features"><?php st_the_language( 'user_create_car_features' ) ?>:</label>
                        </div>
                    </div>
                    <?php $data =get_post_meta($post_id , 'cars_equipment_info',true); ?>
                    <div class="" id="data_features">
                        <?php
                        if(!empty($data)){
                            foreach($data as $key=>$value){?>
                                <?php $list = STUser_f::get_list_value_taxonomy( 'st_cars' ); ?>
                                <div class="item">
                                    <div class="col-md-4">
                                        <div class="form-group form-group-icon-left">
                                            
                                            <label for="features_taxonomy"><?php st_the_language( 'user_create_car_features_attributes' ) ?></label>
                                            <i class="fa fa-arrow-down input-icon input-icon-hightlight"></i>
                                            <?php
                                            if(!empty( $list )) {
                                                ?>
                                                <select name="features_taxonomy[]" class="form-control taxonomy_car">
                                                    <?php foreach( $list as $k => $v ) { ?>
                                                        <option  <?php if($v['value'] == $value['cars_equipment_taxonomy_id']) echo 'selected'; ?> data-icon="<?php echo esc_attr( $v[ 'icon' ] ) ?>" value="<?php echo esc_attr( $v[ 'value' ] . ',' . $v[ 'label' ] ) ?>"><?php echo esc_attr( $v[ 'label' ] ) ?></option>
                                                    <?php } ?>
                                                </select>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="form-group ">
                                            <label for="taxonomy_info"><?php st_the_language( 'user_create_car_features_attributes_info' ) ?></label>
                                            <input id="title" name="taxonomy_info[]" type="text" class="form-control" value="<?php echo esc_html($value['cars_equipment_taxonomy_info']) ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group form-group-icon-left">
                                            <div class="btn btn-danger btn_del_program" style="margin-top: 27px">
                                               X
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                        }
                        ?>
                    </div>
                    <div class="col-md-12 text-right">
                        <div class="form-group form-group-icon-left">
                            <button id="btn_add_features" type="button"
                                    class="btn btn-info btn-sm"><?php st_the_language( 'user_create_car_add_features' ) ?></button>
                            <br>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class='form-group form-group-icon-left'>
                            
                            <label for="st_custom_layout"><?php _e( "Detail Car Layout" , ST_TEXTDOMAIN ) ?>:</label>
                            <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                            <?php $layout = st_get_layout('st_cars');
                            if(!empty($layout) and is_array($layout)):
                                ?>
                                <select class='form-control' name='st_custom_layout' id="st_custom_layout">
                                    <?php
                                    $st_custom_layout = get_post_meta($post_id , 'st_custom_layout' , true);
                                    foreach($layout as $k=>$v):
                                        if($st_custom_layout == $v['value']) $check = "selected"; else $check = '';
                                        echo '<option '.$check.' value='.$v['value'].'>'.$v['label'].'</option>';
                                    endforeach;
                                    ?>
                                </select>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class='form-group form-group-icon-left'>
                            <?php if(st()->get_option( 'partner_set_feature' ) == "on") { ?>
                                
                                <label for="is_featured"><?php _e( "Set as Featured" , ST_TEXTDOMAIN ) ?>:</label>
                                <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                                <?php $is_featured = get_post_meta($post_id,'is_featured',true) ?>
                                <select class='form-control' name='is_featured' id="is_featured">
                                    <option value='off' <?php if($is_featured == 'off') echo 'selected'; ?> ><?php _e("No",ST_TEXTDOMAIN) ?></option>
                                    <option value='on'  <?php if($is_featured == 'on') echo 'selected'; ?> ><?php _e("Yes",ST_TEXTDOMAIN) ?></option>
                                </select>
                            <?php }; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-group-icon-left">
                            
                            <label for="video"><?php st_the_language( 'user_create_car_video' ) ?>:</label>
                            <i class="fa  fa-youtube-play input-icon input-icon-hightlight"></i>
                            <input id="video" name="video" type="text"
                                   placeholder="<?php _e("Enter Youtube or Vimeo video link (Eg: https://www.youtube.com/watch?v=JL-pGPVQ1a8)") ?>" class="form-control " value="<?php echo get_post_meta( $post_id , 'video' , true); ?>">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('video'),'danger') ?></div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group form-group-icon-left">
                            <label for="id_gallery"><?php _e( "Gallery" , ST_TEXTDOMAIN ) ?>:</label>
                            <?php $id_img = get_post_meta( $post_id , 'gallery' , true ); ?>
                            <div class="input-group">
                                <span class="input-group-btn">
                                    <span class="btn btn-primary btn-file multiple">
                                        <?php _e( "Browse…" , ST_TEXTDOMAIN ) ?> <input name="gallery[]" id="gallery" multiple type="file">
                                    </span>
                                </span>
                                <input type="text" readonly="" value="<?php echo esc_html( $id_img ) ?>"
                                       class="form-control data_lable">
                            </div>
                            <input id="id_gallery" name="id_gallery" type="hidden" value="<?php echo esc_attr( $id_img ) ?>">
                            <?php
                            if(!empty( $id_img )) {
                                echo '<div class="user-profile-avatar user_seting st_edit"><div>';
                                foreach( explode( ',' , $id_img ) as $k => $v ) {
                                    $post_thumbnail_id = wp_get_attachment_image_src( $v , 'full' );
                                    echo '<img width="300" height="300" class="avatar avatar-300 photo img-thumbnail" src="' . $post_thumbnail_id[ '0' ] . '" alt="">';
                                }
                                echo '</div><input name="" type="button"  class="btn btn-danger  btn_del_gallery" value="' . st_get_language( 'user_delete' ) . '"></div>';
                            }
                            ?>
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('gallery'),'danger') ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade  " id="tab-contact-details">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group form-group-icon-left">
                            <label for="id_logo"><?php _e("Logo",ST_TEXTDOMAIN) ?>:</label>
                            <?php $id_img = get_post_meta($post_id , 'cars_logo',true);
                            $post_thumbnail_id = $id_img;
                            ?>
                            <div class="input-group">
                            <span class="input-group-btn">
                                <span class="btn btn-primary btn-file">
                                    <?php _e("Browse…",ST_TEXTDOMAIN) ?> <input name="logo"  type="file" >
                                </span>
                            </span>
                                <input type="text" readonly="" value="<?php echo esc_url($post_thumbnail_id); ?>" class="form-control data_lable">
                            </div>
                            <input id="id_logo" name="id_logo" type="hidden" value="<?php echo esc_attr($id_img) ?>">
                            <?php
                            if(!empty($post_thumbnail_id)){
                                echo '<div class="user-profile-avatar user_seting st_edit">
                            <div><img width="300" height="300" class="avatar avatar-300 photo img-thumbnail" src="'.$post_thumbnail_id.'" alt=""></div>
                            <input name="" type="button"  class="btn btn-danger  btn_del_logo" value="'.st_get_language('user_delete').'">
                          </div>';
                            }
                            ?>
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('logo'),'danger') ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-group-icon-left">
                            
                            <label for="st_name"><?php _e("Car Manufacturer Name",ST_TEXTDOMAIN) ?>:</label>
                            <i class="fa  fa-star input-icon input-icon-hightlight"></i>
                            <input id="st_name" name="st_name" type="text"
                                   placeholder="<?php  _e("Car Manufacturer Name",ST_TEXTDOMAIN) ?>" class="form-control" value="<?php echo get_post_meta( $post_id , 'cars_name' , true); ?>">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('st_name'),'danger') ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-group-icon-left">
                            
                            <label for="email"><?php st_the_language( 'user_create_car_email' ) ?>:</label>
                            <i class="fa  fa-envelope-o input-icon input-icon-hightlight"></i>
                            <input id="email" name="email" type="text"
                                   placeholder="<?php st_the_language( 'user_create_car_email' ) ?>" class="form-control" value="<?php echo get_post_meta( $post_id , 'cars_email' , true); ?>">
                            <i class="placeholder"><?php _e("E-mail Car Agent, this address will received email when have new booking",ST_TEXTDOMAIN) ?></i>
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('email'),'danger') ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-group-icon-left">
                            
                            <label for="phone"><?php st_the_language( 'user_create_car_phone' ) ?>:</label>
                            <i class="fa  fa-phone input-icon input-icon-hightlight"></i>
                            <input id="phone" name="phone" type="text"
                                   placeholder="<?php st_the_language( 'user_create_car_phone' ) ?>" class="form-control" value="<?php echo get_post_meta( $post_id , 'cars_phone' , true); ?>">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('phone'),'danger') ?></div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="about"><?php st_the_language( 'user_create_car_about' ) ?>:</label>
                            <textarea name="about" rows="5" class="form-control"><?php echo get_post_meta( $post_id , 'cars_about' , true); ?></textarea>
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('about'),'danger') ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="tab-price-setting">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group form-group-icon-left">
                            
                            <label for="price"><?php st_the_language( 'user_create_car_price' ) ?>:</label>
                            <i class="fa fa-money input-icon input-icon-hightlight"></i>
                            <input id="price" name="price" type="text" placeholder="<?php st_the_language( 'user_create_car_price' ) ?>" class="form-control number" value="<?php echo get_post_meta( $post_id , 'cars_price' , true); ?>">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('price'),'danger') ?></div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group form-group-icon-left">
                            
                            <label for="is_custom_price"><?php _e("Custom Price",ST_TEXTDOMAIN) ?>:</label>
                            <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                            <?php $is_custom_price = get_post_meta($post_id,'is_custom_price',true);?>
                            <select class="form-control is_custom_price" name="is_custom_price">
                                <option value="price_by_date" <?php if($is_custom_price == 'price_by_date') echo 'selected'; ?>><?php _e("Price by Date",ST_TEXTDOMAIN) ?></option>
                                <option value="price_by_number" <?php if($is_custom_price == 'price_by_number') echo 'selected'; ?>><?php _e("Price by number of day/hour",ST_TEXTDOMAIN) ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="data_price_by_date">
                        <div class="col-md-12">
                            <div class="form-group form-group-icon-left">
                                <label for="custom_price"><?php _e("Price by Date",ST_TEXTDOMAIN) ?>:</label>
                            </div>
                        </div>
                        <?php $data_price = STAdmin::st_get_all_price($post_id);?>
                        <div class="content_data_price">
                            <?php if(!empty($data_price)){
                                foreach($data_price as $k=>$v){
                                    ?>
                                    <div class="item">
                                        <div class="col-md-4">
                                            <div class="form-group form-group-icon-left">
                                                
                                                <label for="st_start_date"><?php _e("Start Date",ST_TEXTDOMAIN) ?></label>
                                                <i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                                                <input id="st_start_date" data-date-format="yyyy-mm-dd" name="st_start_date[]" type="text" placeholder="<?php _e("Start Date",ST_TEXTDOMAIN) ?>" class="form-control date-pick" value="<?php echo esc_html($v->start_date) ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group form-group-icon-left">
                                                
                                                <label for="st_end_date"><?php _e("End Date",ST_TEXTDOMAIN) ?></label>
                                                <i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                                                <input id="st_end_date" data-date-format="yyyy-mm-dd" name="st_end_date[]" type="text" placeholder="<?php _e("End Date",ST_TEXTDOMAIN) ?>" class="form-control date-pick" value="<?php echo esc_html($v->end_date) ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group form-group-icon-left">
                                                
                                                <label for="st_price"><?php _e("Price",ST_TEXTDOMAIN) ?></label>
                                                <i class="fa fa-money input-icon input-icon-hightlight"></i>
                                                <input id="st_price" name="st_price[]" type="text" placeholder="<?php _e("Price",ST_TEXTDOMAIN) ?>" class="form-control number" value="<?php echo esc_html($v->price) ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <input name="st_priority[]" value="0" type="hidden" class="">
                                            <input name="st_price_type[]" value="default" type="hidden" class="">
                                            <input name="st_status[]" value="1" type="hidden" class="">
                                            <div class="btn btn-danger btn_del_price_custom" style="margin-top: 27px">-</div>
                                        </div>
                                    </div>
                                <?php
                                }
                            } ?>

                        </div>
                        <div class="col-md-12 div_btn_add_custom">
                            <div class="form-group form-group-icon-left">
                                <button id="btn_add_custom_price" class="btn btn-info" type="button"><?php _e("Add Price Custom",ST_TEXTDOMAIN) ?></button>
                                <br>
                            </div>
                        </div>
                    </div>

                    <div class="data_price_by_number">
                        <div class="col-md-12">
                            <div class="form-group form-group-icon-left">
                                <label for="st_price_by_number"><?php _e("Price by Number",ST_TEXTDOMAIN) ?>:</label>
                            </div>
                        </div>
                        <?php
                        $price_by_number_of_day_hour = get_post_meta($post_id,'price_by_number_of_day_hour',true);
                        ?>
                        <div class="content_data_price_by_number">
                            <?php if(!empty($price_by_number_of_day_hour)){
                                foreach($price_by_number_of_day_hour as $k=>$v){
                                        ?>
                                        <div class="item">
                                            <div class="col-md-3">
                                                <div class="form-group form-group-icon-left">
                                                    
                                                    <label for="st_title"><?php _e("Title",ST_TEXTDOMAIN) ?></label>
                                                    <i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                                                    <input id="st_title"  name="st_title[]" type="text" placeholder="<?php _e("Title",ST_TEXTDOMAIN) ?>" class="form-control" value="<?php echo esc_html($v['title']) ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group form-group-icon-left">
                                                    
                                                    <label for="st_start_date"><?php _e("Number Start",ST_TEXTDOMAIN) ?></label>
                                                    <i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                                                    <input id="st_start_date"  name="st_number_start[]" type="text" placeholder="<?php _e("Number Start",ST_TEXTDOMAIN) ?>" class="form-control number" value="<?php echo esc_html($v['number_start']) ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group form-group-icon-left">
                                                    
                                                    <label for="st_end_date"><?php _e("Number End",ST_TEXTDOMAIN) ?></label>
                                                    <i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                                                    <input id="st_end_date"  name="st_number_end[]" type="text" placeholder="<?php _e("Number End",ST_TEXTDOMAIN) ?>" class="form-control number" value="<?php echo esc_html($v['number_end']) ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group form-group-icon-left">
                                                    
                                                    <label for="st_price_by_number"><?php _e("Price",ST_TEXTDOMAIN) ?></label>
                                                    <i class="fa fa-money input-icon input-icon-hightlight"></i>
                                                    <input id="st_price_by_number" name="st_price_by_number[]" type="text" placeholder="<?php _e("Price",ST_TEXTDOMAIN) ?>" class="form-control number" value="<?php echo esc_html($v['price']) ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="btn btn-danger btn_del_price_custom" style="margin-top: 27px"> - </div>
                                            </div>
                                        </div>
                                    <?php
                                }
                            } ?>
                        </div>
                        <div class="col-md-12 div_btn_add_custom">
                            <div class="form-group form-group-icon-left">
                                <button id="btn_add_custom_price_by_number" class="btn btn-info" type="button"><?php _e("Add Price Custom",ST_TEXTDOMAIN) ?></button>
                                <br>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group form-group-icon-left">
                            
                            <label for="discount"><?php st_the_language( 'user_create_car_discount' ) ?>:</label>
                            <i class="fa fa-star  input-icon input-icon-hightlight"></i>
                            <input id="discount" name="discount" type="text" placeholder="<?php _e("Discount Rate (%)",ST_TEXTDOMAIN) ?>" class="form-control number" value="<?php echo get_post_meta( $post_id , 'discount' , true); ?>">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('discount'),'danger') ?></div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group form-group-icon-left">
                            
                            <label for="is_sale_schedule"><?php _e("Sale Schedule",ST_TEXTDOMAIN) ?>:</label>
                            <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                            <?php $is_sale_schedule = get_post_meta($post_id,'is_sale_schedule',true) ?>
                            <select class="form-control is_sale_schedule" name="is_sale_schedule" id="is_sale_schedule">
                                <option value="on" <?php if($is_sale_schedule == 'on') echo 'selected'; ?>><?php _e("Yes",ST_TEXTDOMAIN) ?></option>
                                <option value="off" <?php if($is_sale_schedule == 'off') echo 'selected'; ?>><?php _e("No",ST_TEXTDOMAIN) ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="data_is_sale_schedule input-daterange">
                        <div class="col-md-6">
                            <div class="form-group form-group-icon-left">
                                
                                <label for="sale_price_from"><?php _e("Sale Start Date",ST_TEXTDOMAIN) ?>:</label>
                                <i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                                <input name="sale_price_from" class="date-pick form-control st_date_start" id="sale_price_from"  data-date-format="yyyy-mm-dd" type="text" value="<?php echo get_post_meta( $post_id , 'sale_price_from' , true); ?>"/>
                                <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('sale_price_from'),'danger') ?></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-group-icon-left" >
                                
                                <label for="sale_price_to"><?php _e("Sale End Date",ST_TEXTDOMAIN) ?>:</label>
                                <i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                                <input name="sale_price_to" class="date-pick form-control st_date_end" id="sale_price_to" data-date-format="yyyy-mm-dd"  type="text" value="<?php echo get_post_meta( $post_id , 'sale_price_to' , true); ?>" />
                                <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('sale_price_to'),'danger') ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-group-icon-left">
                            
                            <label for="number_car"><?php _e("Number of cars for Rent",ST_TEXTDOMAIN) ?>:</label>
                            <i class="fa fa-cogs  input-icon input-icon-hightlight"></i>
                            <input id="number_car" name="number_car" type="text" placeholder="<?php _e("Number of cars for Rent",ST_TEXTDOMAIN) ?>" class="form-control number" value="<?php echo get_post_meta( $post_id , 'number_car' , true); ?>">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('number_car'),'danger') ?></div>
                        </div>
                    </div>
                    <div class="col-md-6 clear">
                        <div class="form-group form-group-icon-left">
                            
                            <label for="deposit_payment_status"><?php _e("Deposit Payment Options",ST_TEXTDOMAIN) ?>:</label>
                            <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                            <?php $deposit_payment_status = get_post_meta($post_id ,'deposit_payment_status',true) ?>
                            <select class="form-control deposit_payment_status" name="deposit_payment_status" id="deposit_payment_status">
                                <option value=""><?php _e("Disallow Deposit",ST_TEXTDOMAIN) ?></option>
                                <option value="percent" <?php if($deposit_payment_status == 'percent') echo 'selected' ?>><?php _e("Deposit By Percent",ST_TEXTDOMAIN) ?></option>
                                <option value="amount" <?php if($deposit_payment_status == 'amount') echo 'selected' ?>><?php _e("Deposit By Amount",ST_TEXTDOMAIN) ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 data_deposit_payment_status">
                        <div class="form-group form-group-icon-left">
                            
                            <label id="deposit_payment_amount"><?php _e("Deposit Payment Amount",ST_TEXTDOMAIN) ?>:</label>
                            <i class="fa fa-cogs  input-icon input-icon-hightlight"></i>
                            <input id="deposit_payment_amount" name="deposit_payment_amount" type="text" placeholder="<?php _e("Deposit payment amount",ST_TEXTDOMAIN) ?>" class="form-control" value="<?php echo get_post_meta($post_id ,'deposit_payment_amount',true) ?>">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('deposit_payment_amount'),'danger') ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="tab-car-options">
                <div class="row">
                    <div class='col-md-12'>
                        <div class="form-group">
                            <label for="cars_booking_period"><?php _e("Booking Period",ST_TEXTDOMAIN) ?>:</label>
                            <input id="cars_booking_period" name="cars_booking_period" type="text" min="0"  placeholder="<?php _e("Booking Period (day)",ST_TEXTDOMAIN) ?>" class="form-control number" value="<?php echo get_post_meta($post_id ,'cars_booking_period',true) ?>">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('cars_booking_period'),'danger') ?></div>
                        </div>
                    </div>
                    <?php $booking_unit = st()->get_option('cars_price_unit' ,'day') ; ?>
                    <?php if($booking_unit =='day') {?>
                    <div class='col-md-12'>
                        <div class="form-group">
                            <label for="cars_booking_min_day"><?php _e('Minimum days to book', ST_TEXTDOMAIN) ?>:</label>
                            <input id="cars_booking_min_day" name="cars_booking_min_day" type="text" min="0" placeholder="<?php __('Minimum days to book', ST_TEXTDOMAIN) ?>" class="form-control number" value="<?php echo STInput::request('cars_booking_min_day') ?>">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('cars_booking_min_day'),'danger') ?></div>
                        </div>
                    </div>
                    <?php }else{?>
                    <div class='col-md-12'>
                        <div class="form-group">
                            <label for="cars_booking_min_hour"><?php _e('Minimum hours to book', ST_TEXTDOMAIN) ?>:</label>
                            <input id="cars_booking_min_hour" name="cars_booking_min_hour" type="text" min="0" placeholder="<?php __('Minimum hours to book', ST_TEXTDOMAIN) ?>" class="form-control number" value="<?php echo STInput::request('cars_booking_min_hour') ?>">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('cars_booking_min_hour'),'danger') ?></div>
                        </div>
                    </div>
                    <?php }?>
                    
                    <div class="col-md-6">
                        <div class="form-group form-group-icon-left">
                            
                            <label for="st_car_external_booking"><?php _e("Car External Booking",ST_TEXTDOMAIN) ?>:</label>
                            <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                            <?php $st_car_external_booking = get_post_meta($post_id , 'st_car_external_booking' , true) ?>
                            <select class="form-control st_car_external_booking" name="st_car_external_booking">
                                <option value="off" <?php if($st_car_external_booking == 'off') echo 'selected'; ?>><?php _e("No",ST_TEXTDOMAIN) ?></option>
                                <option value="on" <?php if($st_car_external_booking == 'on') echo 'selected'; ?>><?php _e("Yes",ST_TEXTDOMAIN) ?></option>
                            </select>
                        </div>
                    </div>
                    <div class='col-md-6 data_st_car_external_booking'>
                        <div class="form-group form-group-icon-left">
                            
                            <label for="st_car_external_booking_link"><?php _e("Car External Booking URL",ST_TEXTDOMAIN) ?>:</label>
                            <i class="fa fa-link  input-icon input-icon-hightlight"></i>
                            <input id="st_car_external_booking_link" name="st_car_external_booking_link" type="text" placeholder="<?php _e("Booking Period (day)",ST_TEXTDOMAIN) ?>" class="form-control" value="<?php echo get_post_meta($post_id , 'st_car_external_booking_link' , true) ?>">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('st_car_external_booking_link'),'danger') ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="tab-payment">
                <?php
                $data_paypment = STPaymentGateways::$_payment_gateways;
                if (!empty($data_paypment) and is_array($data_paypment)) {
                    foreach( $data_paypment as $k => $v ) {?>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group form-group-icon-left">
                                    
                                    <label for="is_meta_payment_gateway_<?php echo esc_attr($k) ?>"><?php echo esc_html($v->get_name()) ?>:</label>
                                    <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                                    <?php $is_pay = get_post_meta($post_id , 'is_meta_payment_gateway_'.$k , true) ?>
                                    <select class="form-control" name="is_meta_payment_gateway_<?php echo esc_attr($k) ?>" id="is_meta_payment_gateway_<?php echo esc_attr($k) ?>">
                                        <option value="on" <?php if($is_pay == 'on') echo 'selected' ?>><?php _e( "Yes" , ST_TEXTDOMAIN ) ?></option>
                                        <option value="off" <?php if($is_pay == 'off') echo 'selected' ?>><?php _e( "No" , ST_TEXTDOMAIN ) ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                }
                ?>
            </div>
            <div class="tab-pane fade" id="tab-custom-fields">
                <?php
                $custom_field = st()->get_option( 'st_cars_unlimited_custom_field' );
                if(!empty( $custom_field ) and is_array( $custom_field )) {
                    ?>
                    <div class="row">
                        <?php
                        foreach( $custom_field as $k => $v ) {
                            $key   = str_ireplace( '-' , '_' , 'st_custom_' . sanitize_title( $v[ 'title' ] ) );
                            $class = 'col-md-12';
                            if($v[ 'type_field' ] == "date-picker") {
                                $class = 'col-md-4';
                            }
                            ?>
                            <div class="<?php echo esc_attr( $class ) ?>">
                                <div class="form-group form-group-icon-left">
                                    <label for="<?php echo esc_attr( $key ) ?>"><?php echo esc_html($v[ 'title' ]) ?>:</label>
                                    <?php if($v[ 'type_field' ] == "text") { ?>
                                        <input id="<?php echo esc_attr( $key ) ?>" name="<?php echo esc_attr( $key ) ?>" type="text" placeholder="<?php echo esc_html($v[ 'title' ]) ?>" class="form-control" value="<?php echo get_post_meta( $post_id , $key , true); ?>">
                                    <?php } ?>
                                    <?php if($v[ 'type_field' ] == "date-picker") { ?>
                                        <input id="<?php echo esc_attr( $key ) ?>" name="<?php echo esc_attr( $key ) ?>" type="text" placeholder="<?php echo esc_html($v[ 'title' ]) ?>" class="date-pick form-control" value="<?php echo get_post_meta( $post_id , $key , true); ?>">
                                    <?php } ?>
                                    <?php if($v[ 'type_field' ] == "textarea") { ?>
                                        <textarea id="<?php echo esc_attr( $key ) ?>" name="<?php echo esc_attr( $key ) ?>" class="form-control" ><?php echo get_post_meta( $post_id , $key , true); ?></textarea>
                                    <?php } ?>

                                </div>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="text-center div_btn_submit">
        <input type="button" id="btn_check_insert_cars" class="btn btn-primary btn-lg" value="<?php _e("UPDATE CAR",ST_TEXTDOMAIN) ?>">
        <input name="btn_update_post_type_cars" id="btn_insert_post_type_cars" type="submit" class="btn btn-primary hidden" value="SUBMIT">
    </div>
</form>

<div id="html_equipment_item" style="display: none">
    <div class="item">
        <div class="col-md-3">
            <div class="form-group ">
                <label for="equipment_item_title"><?php st_the_language( 'user_create_car_equipment_title' ) ?></label>
                <input id="title" name="equipment_item_title[]" type="text" class="form-control">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group ">
                <label for="equipment_item_price"><?php st_the_language( 'user_create_car_equipment_price' ) ?></label>
                <input id="price" name="equipment_item_price[]" type="text" class="form-control number">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group ">
                <label for="equipment_item_price_unit"><?php _e("Price Unit",ST_TEXTDOMAIN) ?></label>
                <select class="form-control" id="equipment_item_price_unit" name="equipment_item_price_unit[]">
                    <option value=""><?php _e("Fixed Price",ST_TEXTDOMAIN) ?></option>
                    <option value="per_hour"><?php _e("Price per Hour",ST_TEXTDOMAIN) ?></option>
                    <option value="per_day"><?php _e("Price per Day",ST_TEXTDOMAIN) ?></option>
                </select>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group ">
                <label for="equipment_item_price_max"><?php _e("Price Max",ST_TEXTDOMAIN) ?></label>
                <input id="price_max" name="equipment_item_price_max[]" type="text" class="form-control number">
            </div>
        </div>
        <div class="col-md-1">
            <div class="form-group form-group-icon-left">
                <div class="btn btn-danger btn_del_program" style="margin-top: 27px">
                    X
                </div>
            </div>
        </div>
    </div>
</div>


<div id="html_features" style="display: none">
    <?php $list = STUser_f::get_list_value_taxonomy( 'st_cars' ); ?>
    <?php if(!empty( $list )) { ?>
        <div class="item">
            <div class="col-md-4">
                <div class="form-group form-group-icon-left">
                    
                    <label for="features_taxonomy"><?php st_the_language( 'user_create_car_features_attributes' ) ?></label>
                    <i class="fa fa-arrow-down input-icon input-icon-hightlight"></i>
                    <?php
                    if(!empty( $list )) {
                        ?>
                        <select name="features_taxonomy[]" class="form-control taxonomy_car">
                            <?php foreach( $list as $k => $v ) { ?>
                                <option data-icon="<?php //echo esc_attr( $v[ 'icon' ] ) ?>"
                                        value="<?php echo esc_attr( $v[ 'value' ] . ',' . $v[ 'taxonomy' ] ) ?>"><?php echo esc_attr( $v[ 'label' ] ) ?></option>
                            <?php } ?>
                        </select>
                    <?php } ?>
                </div>
            </div>
            <div class="col-md-7">
                <div class="form-group">
                    <label for="taxonomy_info"><?php st_the_language( 'user_create_car_features_attributes_info' ) ?></label>
                    <input id="title" name="taxonomy_info[]" type="text" class="form-control">
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group form-group-icon-left">
                    <div class="btn btn-danger btn_del_program" style="margin-top: 27px">
                       X
                    </div>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <div class="col-md-12">
            <label for="create_car_no_data"><?php st_the_language( 'user_create_car_no_data' ) ?></label>
        </div>
    <?php } ?>
</div>

<div class="data_price_html" style="display: none">
    <div class="item">
        <div class="col-md-4">
            <div class="form-group form-group-icon-left">
                
                <label for="st_start_date"><?php _e("Start Date",ST_TEXTDOMAIN) ?></label>
                <i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                <input id="st_start_date" data-date-format="yyyy-mm-dd" name="st_start_date[]" type="text" placeholder="<?php _e("Start Date",ST_TEXTDOMAIN) ?>" class="form-control date-pick">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group form-group-icon-left">
                
                <label for="st_end_date"><?php _e("End Date",ST_TEXTDOMAIN) ?></label>
                <i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                <input id="st_end_date" data-date-format="yyyy-mm-dd" name="st_end_date[]" type="text" placeholder="<?php _e("End Date",ST_TEXTDOMAIN) ?>" class="form-control date-pick">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group form-group-icon-left">
                
                <label for="st_price"><?php _e("Price",ST_TEXTDOMAIN) ?></label>
                <i class="fa fa-money input-icon input-icon-hightlight"></i>
                <input id="st_price" name="st_price[]" type="text" placeholder="<?php _e("Price",ST_TEXTDOMAIN) ?>" class="number form-control number">
            </div>
        </div>
        <div class="col-md-1">
            <input name="st_priority[]" value="0" type="hidden" class="">
            <input name="st_price_type[]" value="default" type="hidden" class="">
            <input name="st_status[]" value="1" type="hidden" class="">
            <div class="btn btn-danger btn_del_price_custom" style="margin-top: 27px"> - </div>
        </div>
    </div>
</div>

<div class="data_price_by_number_html" style="display: none">
    <div class="item">
        <div class="col-md-3">
            <div class="form-group form-group-icon-left">
                
                <label for="st_title"><?php _e("Title",ST_TEXTDOMAIN) ?></label>
                <i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                <input id="st_title"  name="st_title[]" type="text" placeholder="<?php _e("Title",ST_TEXTDOMAIN) ?>" class="form-control">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group form-group-icon-left">
                
                <label for="st_start_date"><?php _e("Number Start",ST_TEXTDOMAIN) ?></label>
                <i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                <input id="st_start_date"  name="st_number_start[]" type="text" placeholder="<?php _e("Number Start",ST_TEXTDOMAIN) ?>" class="form-control number">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group form-group-icon-left">
                
                <label for="st_end_date"><?php _e("Number End",ST_TEXTDOMAIN) ?></label>
                <i class="fa fa-calendar input-icon input-icon-hightlight"></i>
                <input id="st_end_date"  name="st_number_end[]" type="text" placeholder="<?php _e("Number End",ST_TEXTDOMAIN) ?>" class="form-control number">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group form-group-icon-left">
                
                <label for="st_price_by_number"><?php _e("Price",ST_TEXTDOMAIN) ?></label>
                <i class="fa fa-money input-icon input-icon-hightlight"></i>
                <input id="st_price_by_number" name="st_price_by_number[]" type="text" placeholder="<?php _e("Price",ST_TEXTDOMAIN) ?>" class="form-control number">
            </div>
        </div>
        <div class="col-md-1">
            <div class="btn btn-danger btn_del_price_custom" style="margin-top: 27px"> - </div>
        </div>
    </div>
</div>