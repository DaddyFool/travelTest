<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Cars search form
 *
 * Created by ShineTheme
 *
 */
$cars=new STCars();
$fields=$cars->get_search_fields();
?>
<h3><?php st_the_language('search_for_cars') ?></h3>
<form method="get" action="<?php echo home_url( '/' ); ?>">
    <input type="hidden" name="post_type" value="st_cars">
    <input type="hidden" name="s" value="">
    <input type="hidden" name="layout" value="<?php echo STInput::get('layout') ?>">
    <input type="hidden" name="style" value="<?php echo STInput::get('style') ?>">
    <div class="row">
        <?php
        if(!empty($fields)){
            foreach($fields as $key=>$value){
                $name=$value['title'];
                $size=$value['layout_col_normal'];
                $size_class = " col-md-".$size." col-lg-".$size. " col-sm-12 col-xs-12 " ;
                ?>
                <div class="<?php echo esc_attr($size_class); ?>">
                    <?php echo st()->load_template('cars/elements/search/field-'.$value['field_atrribute'],false,array('data'=>$value)) ?>
                </div>
        <?php
            }
        }
        ?>
    </div>
    <input type="submit" class="btn btn-primary btn-lg" value="<?php st_the_language('search_for_cars') ?>">
 <!--   <button class="btn btn-primary btn-lg" type="submit"><?php /*st_the_language('search_for_cars') */?></button>-->
</form>