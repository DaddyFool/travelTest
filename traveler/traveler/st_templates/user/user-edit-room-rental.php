<?php
if( STUser_f::st_check_edit_partner(STInput::request('id')) == false ){
    return false;
}
$post_id = STInput::request('id');
$post = get_post( $post_id );
?>
<?php $validator= STUser_f::$validator; ?>
<div class="st-create">
    <h2 class="pull-left"><?php echo __('Edit rental room', ST_TEXTDOMAIN); ?></h2>
    <a target="_blank" href="<?php echo get_the_permalink($post_id) ?>" class="btn btn-default pull-right"><?php _e("Preview",ST_TEXTDOMAIN) ?></a>
</div>
<div class="msg">
    <?php echo STTemplate::message() ?>
    <?php echo STUser_f::get_msg(); ?>
</div>
<form action="" method="post" enctype="multipart/form-data" id="st_form_add_partner">
    <?php wp_nonce_field('user_setting','st_update_rental_room'); ?>
    <div class="form-group form-group-icon-left">
        
        <label for="title" class="head_bol"><?php echo __('Room Name', ST_TEXTDOMAIN); ?>:</label>
        <i class="fa  fa-file-text input-icon input-icon-hightlight"></i>
        <input id="title" name="title" type="text" placeholder="<?php st_the_language('user_create_room_title') ?>" class="form-control" value="<?php echo esc_html($post->post_title) ?>">
        <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('title'),'danger') ?></div>
    </div>
    <div class="form-group form-group-icon-left">
        <label for="st_content" class="head_bol"><?php  st_the_language('user_create_room_content') ?>:</label>
        <?php wp_editor( $post->post_content ,'st_content'); ?>
        <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('st_content'),'danger') ?></div>
    </div>
    <div class="form-group">
        <label for="desc" class="head_bol"><?php _e("Room Description",ST_TEXTDOMAIN) ?>:</label>
        <textarea id="desc" rows="6" name="desc" class="form-control"><?php echo esc_html($post->post_excerpt) ?></textarea>
        <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('desc'),'danger') ?></div>
    </div>
    <div class="form-group form-group-icon-left">
        <label for="id_featured_image" class="head_bol"><<?php _e("Featured Image",ST_TEXTDOMAIN) ?>:</label>
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
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php _e("General Settings",ST_TEXTDOMAIN) ?></a></li>
            <li><a href="#tab-room-facility" data-toggle="tab"><?php _e("Room Facility",ST_TEXTDOMAIN) ?></a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade in active" id="tab-general">

                <div class="row">
                    <?php
                    $taxonomies = (get_object_taxonomies('rental_room'));
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
                                                            <input name="taxonomy[]" class="i-check item_tanoxomy" type="checkbox" <?php echo esc_html($check) ?> value="<?php echo esc_attr($k.','.$taxonomy_name) ?>" /><?php echo esc_html($v) ?>
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
                </div>
                <div class="col-md-12">
                    <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('taxonomy[]'),'danger') ?></div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group form-group-icon-left">
                            <label for="room_parent"><?php echo __('Select Rental', ST_TEXTDOMAIN); ?>:</label>
                            <?php $room_parent = get_post_meta($post_id , 'room_parent' ,true); ?>
                            <input type="text" name="room_parent" placeholder="<?php st_the_language('user_create_room_search') ?>" id="room_parent" data-pl-name="<?php echo get_the_title($room_parent) ?>" data-pl-desc="" value="<?php echo esc_html($room_parent) ?>" class="st_post_select" data-author="<?php echo esc_attr($data->ID)?>" data-post-type="st_rental" style="width: 100%">
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('room_parent'),'danger') ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class='form-group form-group-icon-left'>
                            
                            <label for="st_custom_layout"><?php _e( "Detail Room Rental Layout" , ST_TEXTDOMAIN ) ?>:</label>
                            <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                            <?php $layout = st_get_layout('rental_room');
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
                    <div class="col-md-12">
                        <div class="form-group form-group-icon-left">
                            <label for="id_gallery"><?php _e("Gallery",ST_TEXTDOMAIN) ?>:</label>
                            <?php $id_img = get_post_meta($post_id , 'gallery',true);?>
                            <div class="input-group">
                            <span class="input-group-btn">
                                <span class="btn btn-primary btn-file">
                                    <?php _e("Browse…",ST_TEXTDOMAIN) ?> <input name="gallery[]" id="gallery" multiple  type="file" >
                                </span>
                            </span>
                                <input type="text" readonly="" value="<?php echo esc_html($id_img) ?>" class="form-control data_lable">
                            </div>
                            <input id="id_gallery" name="id_gallery" type="hidden" value="<?php echo esc_attr($id_img) ?>">
                            <?php
                            if(!empty($id_img)){
                                echo '<div class="user-profile-avatar user_seting st_edit"><div>';
                                foreach(explode(',',$id_img) as $k=>$v){
                                    $post_thumbnail_id = wp_get_attachment_image_src($v, 'full');
                                    echo '<img width="300" height="300" class="avatar avatar-300 photo img-thumbnail" src="'.$post_thumbnail_id['0'].'" alt="">';
                                }
                                echo '</div><input name="" type="button"  class="btn btn-danger  btn_del_gallery" value="'.st_get_language('user_delete').'">
                      </div>';
                            }
                            ?>
                        </div>
                        <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('gallery'),'danger') ?></div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="tab-room-facility">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group form-group-icon-left">
                            
                            <label for="adult_number"><?php _e("Adults Number",ST_TEXTDOMAIN) ?>:</label>
                            <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                            <input id="adult_number" name="adult_number" type="text" placeholder="<?php _e("Adults Number",ST_TEXTDOMAIN) ?>" class="form-control" value="<?php echo get_post_meta( $post_id , 'adult_number' , true); ?>" >
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('adult_number'),'danger') ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-group-icon-left">
                            
                            <label for="children_number"><?php _e("Children Number",ST_TEXTDOMAIN) ?>:</label>
                            <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                            <input id="children_number" name="children_number" type="text" placeholder="<?php _e("Children Number",ST_TEXTDOMAIN) ?>" class="form-control" value="<?php echo get_post_meta( $post_id , 'children_number' , true); ?>" >
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('children_number'),'danger') ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-group-icon-left">
                            
                            <label for="bed_number"><?php _e("Beds Number",ST_TEXTDOMAIN) ?>:</label>
                            <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                            <input id="bed_number" name="bed_number" type="text" placeholder="<?php _e("Beds Number",ST_TEXTDOMAIN) ?>" class="form-control" value="<?php echo get_post_meta( $post_id , 'bed_number' , true); ?>" >
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('bed_number'),'danger') ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-group-icon-left">
                            
                            <label for="room_footage"><?php _e("Room Footage (square feet)",ST_TEXTDOMAIN) ?>:</label>
                            <i class="fa fa-cogs input-icon input-icon-hightlight"></i>
                            <input id="room_footage" name="room_footage" type="text" placeholder="<?php _e("Room footage (square feet)",ST_TEXTDOMAIN) ?>" class="form-control" value="<?php echo get_post_meta( $post_id , 'room_footage' , true); ?>" >
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('room_footage'),'danger') ?></div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group form-group-icon-left">
                            <label for="add_facility"><?php _e("Add a Facility",ST_TEXTDOMAIN) ?>:</label>
                        </div>
                    </div>
                    <div class="content_data_add_new_facility">
                        <?php $add_new_facility = get_post_meta($post_id,'add_new_facility',true);
                        if(!empty($add_new_facility) and is_array($add_new_facility)):
                            foreach($add_new_facility as $k=>$v){

                                ?>
                                <div class="add_new_facility_item">
                                    <div class="col-md-3">
                                        <label for="add_new_facility_title"><?php _e( "Title" , ST_TEXTDOMAIN ) ?></label>
                                        <input type="text" name="add_new_facility_title[]" class="form-control" value="<?php echo esc_html($v['title']) ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="add_new_facility_value"><?php _e( "Value" , ST_TEXTDOMAIN ) ?></label>
                                        <input type="text" name="add_new_facility_value[]" class="form-control" value="<?php echo esc_html($v['value']) ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="add_new_facility_icon"><?php _e( "Icon" , ST_TEXTDOMAIN ) ?></label>
                                        <input type="text" name="add_new_facility_icon[]" class="form-control" value="<?php if(!empty($v['facility_icon'])) echo esc_html($v['facility_icon']) ?>">
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group form-group-icon-left">
                                            <div class="btn btn-danger btn_del_facility btn_del_custom_partner" style="margin-top: 27px">
                                                X
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php }
                        endif; ?>
                    </div>
                    <div class="col-md-12 div_btn_add_custom">
                        <div class="form-group form-group-icon-left">
                            <button id="btn_add_custom_add_new_facility" class="btn btn-info" type="button"><?php _e("Add New",ST_TEXTDOMAIN) ?></button>
                            <br>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="room_description"><?php _e("Description",ST_TEXTDOMAIN) ?>:</label>
                            <textarea id="room_description" rows="6" name="room_description" class="form-control"><?php echo get_post_meta( $post_id , 'room_description' , true); ?></textarea>
                            <div class="st_msg"><?php echo STUser_f::get_msg_html($validator->error('room_description'),'danger') ?></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="text-center div_btn_submit">
        <input name="btn_update_post_type_rental_room" id="btn_insert_rental_room" type="submit"  class="btn btn-primary btn-lg" value="<?php _e("UPDATE ROOM RENTAL",ST_TEXTDOMAIN) ?>">
    </div>
</form><!-- Template -->
<div class="add_new_facility_html">
    <div class="add_new_facility_item" style="display: none;">
        <div class="col-md-3">
            <label for="add_new_facility_title"><?php _e( "Title" , ST_TEXTDOMAIN ) ?></label>
            <input type="text" name="add_new_facility_title[]" class="form-control">
        </div>
        <div class="col-md-4">
            <label for="add_new_facility_value"><?php _e( "Value" , ST_TEXTDOMAIN ) ?></label>
            <input type="text" name="add_new_facility_value[]" class="form-control">
        </div>
        <div class="col-md-3">
            <label for="add_new_facility_icon"><?php _e( "Icon" , ST_TEXTDOMAIN ) ?></label>
            <input type="text" name="add_new_facility_icon[]" placeholder="<?php _e("(eg: fa-facebook)",ST_TEXTDOMAIN) ?>" class="form-control">
        </div>
        <div class="col-md-1">
            <div class="form-group form-group-icon-left">
                <div class="btn btn-danger btn_del_facility btn_del_custom_partner" style="margin-top: 27px">
                    X
                </div>
            </div>
        </div>
    </div>
</div>
