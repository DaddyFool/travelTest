<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Holidays search form
 *
 * Created by ShineTheme
 *
 */
$holidays=new STHoliday();
$fields=$holidays->get_search_fields();
?>
<h3><?php st_the_language('search_for_holiday') ?></h3>
<form role="search" method="get" class="search" action="<?php the_permalink() ?>">
    <?php
        if(!get_option('permalink_structure'))
        {
            echo '<input type="hidden" name="page_id"  value="'.STInput::request('page_id').'">';
        }
    ?>
    <input type="hidden" name="layout" value="<?php echo STInput::get('layout') ?>">
    <input type="hidden" name="style" value="<?php echo STInput::get('style') ?>">
    <div class="row">
        <?php
        if(!empty($fields))
        {
            foreach($fields as $key=>$value)
            {
                $name=$value['holidays_field_search'];
                $size=$value['layout_col'];

                ?>
                <div class="col-md-<?php echo esc_attr($size);
                ?>">
                    <?php echo st()->load_template('holidays/elements/search/field',$name,array('data'=>$value,'location_name'=>'location_name')) ?>
                </div>
            <?php
            }
        }?>
    </div>
    <button class="btn btn-primary btn-lg" type="submit"><?php st_the_language('search_for_holiday') ?></button>
</form>
