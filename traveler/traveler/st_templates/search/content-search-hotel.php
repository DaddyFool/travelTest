<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Content search hotel
 *
 * Created by ShineTheme
 *
 */
if(!class_exists('STHotel')) return false;

$hotel=new STHotel();
$fields=$hotel->get_search_fields();


if(!isset($field_size)) $field_size='';

?>
    <h2><?php echo esc_html($st_title_search) ?></h2>
    <?php $id_page = st()->get_option('hotel_search_result_page');
          if(!empty($id_page)){
              $link_action = get_the_permalink($id_page);
          }else{
              $link_action = home_url( '/' );
          }
    ?>
    <form role="search" method="get" class="search" action="<?php echo esc_url($link_action) ?>">
        <?php if(empty($id_page)): ?>
        <input type="hidden" name="post_type" value="st_hotel">
        <input type="hidden" name="s" value="">
        <?php endif ?>
        <?php if(STInput::get('layout')):?>
        <input type="hidden" name="layout" value="<?php echo STInput::get('layout') ?>">
        <?php endif;?>
        <div class="<?php  if($st_direction=='horizontal') echo 'row';?>">

            <?php

            if(!empty($fields))
            {
                foreach($fields as $key=>$value)
                {
                    $default=array(
                        'placeholder'=>''
                    );
                    $value = wp_parse_args($value,$default);
                    $name = $value['name'];
                    $size = '4';
                        if($st_style_search=="style_1")
                    {
                        $size=$value['layout_col'];
                    }else
                    {
                        if(!empty($value['layout2_col']))
                        {
                            $size=$value['layout2_col'];
                        }
                    }

                    if($st_direction!='horizontal'){
                        $size='x';
                    }
                    $size_class = " col-md-".$size." col-lg-".$size. " col-sm-12 col-xs-12 " ;
                    ?>
                    <div class="<?php echo esc_attr($size_class); ?>">
                        <?php echo st()->load_template('hotel/elements/search/field_'.$name,false,array('data'=>$value,'field_size'=>$field_size,'location_name'=>'location_name','placeholder'=>$value['placeholder'])) ?>
                    </div>
                    <?php
                }
            }?>

        </div>

        <?php if(st()->get_option('hotel_allow_search_advance')=='on'):?>
        <!--Search Advande-->
        <div class="search_advance">
            <div class="view_more_content_box">
                <div class="<?php  if($st_direction=='horizontal') echo 'row';?>">

                    <?php

                    $fields=$hotel->get_search_adv_fields();
                    if(!empty($fields))
                    {
                        foreach($fields as $key=>$value)
                        {
                            $name=$value['name'];
                            $size='4';
                            if($st_style_search=="style_1")
                            {
                                $size=$value['layout_col'];
                            }else
                            {
                                if($value['layout2_col'])
                                {
                                    $size=$value['layout2_col'];
                                }
                            }
                            if($st_direction!='horizontal'){
                                $size='x';
                            }

                            ?>
                            <div class="col-md-<?php echo esc_attr($size);  ?>">
                                <?php echo st()->load_template('hotel/elements/search/field_'.$name,false,array('data'=>$value,'field_size'=>$field_size,'location_name'=>'location_name')) ?>
                            </div>
                        <?php
                        }
                    }?>

                </div>
            </div>
            <div class="expand_search_box">
                <span class="expand_search_box-more"><?php st_the_language('hotel_advance_search')?> <i class="fa fa-angle-down"></i></span>
                <span class="expand_search_box-less"><?php st_the_language('hotel_less')?> <i class="fa fa-angle-up"></i></span>
            </div>
        </div>
        <!--End search Advance-->
        <?php endif;?>

        <button class="btn btn-primary btn-lg" type="submit"><?php st_the_language('search_for_hotel')?></button>
    </form>
