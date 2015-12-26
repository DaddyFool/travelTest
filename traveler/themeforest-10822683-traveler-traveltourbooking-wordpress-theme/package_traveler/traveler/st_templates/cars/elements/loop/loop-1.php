<?php
    /**
     * @package WordPress
     * @subpackage Traveler
     * @since 1.0
     *
     * Cars element loop 1
     *
     * Created by ShineTheme
     *
     */
    $link=st_get_link_with_search(get_permalink(),array('pick-up','location_id_pick_up','drop-off','location_id_drop_off','drop-off-time','pick-up-time','drop-off-date','pick-up-date'),$_GET);
    if(!empty($_REQUEST['location_id']) OR !empty($_REQUEST['location_name']) ){
        $link = add_query_arg(array('pick-up'=>STInput::request('location_name'),'location_id_pick_up'=>STInput::request('location_id')) , $link);
    }
?>
<li <?php post_class('booking-item')  ?>>
    <?php echo STFeatured::get_featured(); ?>
    <a class="" href="<?php echo esc_url($link)?>">
    <div class="row">
        <div class="col-md-3">
            <div class="booking-item-car-img">
                <?php
                    if(has_post_thumbnail() and get_the_post_thumbnail()){
                        the_post_thumbnail(array(800,400,'bfi_thumb'=>true));
                    }else{
                        echo st_get_default_image();
                    }
                ?>
                <p class="booking-item-car-title"><?php the_title() ?></p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-8">
                    <?php echo st()->load_template('/cars/elements/attribute-list'); ?>
                </div>
                <div class="col-md-4">
                    <ul class="booking-item-features booking-item-features-dark">
                        <?php $data_terms = get_the_terms(get_the_ID(),'st_cars_pickup_features');
                            if(!empty($data_terms)){
                                foreach($data_terms as $k=>$v){
                                    $icon = get_tax_meta($v->term_id ,'st_icon',true);
                                    if(!empty($icon)){
                                        echo '<li title="" data-placement="top" rel="tooltip" data-original-title="'.$v->name.'">
                                                           <i class="'.TravelHelper::handle_icon( $icon ).'"></i>
                                                         </li>';
                                    }
                                }
                            }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-3">
                <span class="booking-item-price">
                    <?php
                    $info_price = STCars::get_info_price();
                    $cars_price = $info_price['price'];
                    $count_sale = $info_price['discount'];
                    $price_origin = $info_price['price_origin'];
                    ?>
                    <?php if($cars_price != $price_origin){ ?>
                        <span class="text-lg lh1em sale_block onsale">
							<?php echo TravelHelper::format_money( $price_origin ) ;?>
                        </span>
                    <?php } ?>
                    <?php echo TravelHelper::format_money($cars_price) ?>
                </span>
            <span class="booking-item-price-unit">/<?php echo strtolower(STCars::get_price_unit('label')) ?></span>
            <?php $category = get_the_terms(get_the_ID() ,'st_category_cars') ?>
            <?php
                $txt ='';
                if(!empty($category))
                {
                    foreach($category as $k=>$v){
                        $txt .= $v->name.' ,';
                    }
                }
                $txt = substr($txt,0,-1);
            ?>
            <p class="booking-item-flight-class"><?php echo esc_html($txt); ?></p>
                <span class="btn btn-primary "><?php st_the_language('car_select')?></span>
            <?php if(!empty($count_sale)){ ?>
                <span class="box_sale sale_small btn-primary"> <?php echo esc_html($count_sale) ?>% </span>
            <?php } ?>

        </div>
    </div>
    </a>
</li>
