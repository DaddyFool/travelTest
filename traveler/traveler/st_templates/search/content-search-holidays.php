<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Content search holidays
 *
 * Created by ShineTheme
 *
 */
$holidays=new STHoliday();
$fields=$holidays->get_search_fields();

?>

<h2><?php echo esc_html($st_title_search) ?></h2>
<?php $id_page = st()->get_option('holidays_search_result_page');
if(!empty($id_page)){
    $link_action = get_the_permalink($id_page);
}else{
    $link_action = home_url( '/' );
}
?>
    <form role="search" method="get" class="search" action="<?php echo esc_url($link_action) ?>">
        <?php if(empty($id_page)): ?>
        <input type="hidden" name="post_type" value="st_holidays">
        <input type="hidden" name="s" value="">
        <?php endif ?>
        <div class="<?php  if($st_direction=='horizontal') echo 'row';?>">
            <?php
            if(!empty($fields))
            {
                foreach($fields as $key=>$value)
                {
                    $default=array(
                        'placeholder'=>''
                    );
                    $value=wp_parse_args($value,$default);
                    $name=$value['holidays_field_search'];
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
                    $size_class = " col-md-".$size." col-lg-".$size. " col-sm-12 col-xs-12 " ;
                    ?>
                    <div class="<?php echo esc_attr($size_class); ?>">
                    
                        <?php echo st()->load_template('holidays/elements/search/field',$name,array('data'=>$value,'field_size'=>$field_size,'location_name'=>'location_name','placeholder'=>$value['placeholder'])) ?>
                    </div>
                <?php
                }
            }?>
        </div>
        <button class="btn btn-primary btn-lg" type="submit"><?php st_the_language('search_for_holiday')?></button>
    </form>
