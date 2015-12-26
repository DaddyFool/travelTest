<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Hotel nearby
 *
 * Created by ShineTheme
 *
 */
$hotel=new STHotel();
$nearby_posts=$hotel->get_near_by();

if($nearby_posts and !empty($nearby_posts))
{
    if(empty($attr['font_size'])) $attr['font_size']='3';
    if(!empty($attr['title'])){
        ?>
        <h<?php echo esc_attr($attr['font_size']) ?>><?php echo esc_html($attr['title']); ?>
            <span class="title_bol"><?php echo the_title(); ?></span>
        </h<?php echo esc_attr($attr['font_size']) ?>>
    <?php
    }
    global $post;
    echo "<ul class='booking-list'>";
    foreach($nearby_posts as $key=>$post)
    {
        setup_postdata($post);
        ?>
        <li <?php post_class('item-nearby')?>>
            <div class="bookinst_save_attributeg-item booking-item booking-item-small">
                <div class="row">
                    <div class="col-xs-4">
                        <a href="<?php the_permalink()?>">
                        <?php the_post_thumbnail()?>
                        </a>
                    </div>
                    <div class="col-xs-5">
                        <h5 class="booking-item-title"><a href="<?php the_permalink()?>"><?php the_title()?></a> </h5>

                        <?php
                        $view_star_review = st()->get_option('view_star_review', 'review');
                        if($view_star_review == 'review') :
                            ?>
                            <ul class="icon-group booking-item-rating-stars">
                                <?php
                                $avg = STReview::get_avg_rate();
                                echo TravelHelper::rate_to_string($avg);
                                ?>
                            </ul>
                        <?php elseif($view_star_review == 'star'): ?>
                            <ul class="icon-group booking-item-rating-stars hotel-star">
                                <?php
                                $star = STHotel::getStar();
                                echo  TravelHelper::rate_to_string($star);
                                ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                    <div class="col-xs-3"><span class="booking-item-price-from"><?php _e("avg",ST_TEXTDOMAIN) ?></span><span class="booking-item-price">
                            <?php
                                $min_price=STHotel::get_avg_price(get_the_ID());
                            echo TravelHelper::format_money($min_price);?>
                            </span>
                    </div>
                </div>
            </div>
        </li>
        <?php
    }
    echo "</ul>";
    wp_reset_query();
    wp_reset_postdata();
}
