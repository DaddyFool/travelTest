<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.1.5
 *
 * Hotel search Map
 *
 * Created by ShineTheme
 *
 */
if(!class_exists( 'STTour' ))
    return false;
$class  = new STTour();
$fields = $class->get_search_fields();
if(!isset( $field_size ))
    $field_size = 'md';
?>

<form id="hotel_search_half_map" method="get" class="search filter_search_map" action="<?php echo home_url() ?>">
    <h2><?php echo esc_html( $title ) ?></h2>
    <input type="hidden" name="post_type" value="st_tours">
    <input type="hidden" name="action" value="st_search_list_half_map">
    <input type="hidden" name="zoom" value="<?php echo esc_html( $zoom ) ?>">
    <input type="hidden" name="style_map" value="<?php echo esc_html( $style_map ) ?>">
    <input type="hidden" name="number" value="<?php echo esc_html( $number ) ?>">
    <input type="hidden" name="is_search_map" value="true">

    <div class="row">
        <?php
        if(!empty( $fields )) {
            foreach( $fields as $key => $value ) {
                $default = array(
                    'placeholder' => ''
                );
                $value   = wp_parse_args( $value , $default );
                $name    = $value[ 'tours_field_search' ];
                $size    = '4';
                if($value[ 'layout_col' ]) {
                    $size = $value[ 'layout_col' ];
                }
                ?>
                <div class="col-md-<?php echo esc_attr( $size );
                ?>">
                    <?php echo st()->load_template( 'tours/elements/search/field' , $name , array( 'data'          => $value ,
                                                                                                   'field_size'    => $field_size ,
                                                                                                   'location_name' => 'location_name' ,
                                                                                                   'placeholder'   => $value[ 'placeholder' ]
                    ) ) ?>
                </div>
            <?php
            }
        } ?>
        <?php if($range_km == 'on'): ?>
            <div class="col-md-<?php echo esc_attr( $range_km_col ) ?>">
                <label><?php _e( "Range Kilometers" , ST_TEXTDOMAIN ) ?></label>
                <div class="form-group price_map">
                    <?php
                    $data_min_max['min'] = 0;
                    $data_min_max['max'] = $max_range_km;
                    echo '<input type="text" name="range" value="' . STInput::get( 'range' ) . '" class="range-slider" data-symbol="' . TravelHelper::get_current_currency( 'symbol' ) . '" data-min="' . $data_min_max[ 'min' ] . '" data-max="' . $data_min_max[ 'max' ] . '" data-step="1">';
                    ?>
                </div>
            </div>
        <?php endif ?>
    </div>
    <!--<label><?php /*_e( "Price range" , ST_TEXTDOMAIN ) */?></label>-->
    <!--<div class="form-group price_map">
        <?php
/*        $data_min_max = TravelerObject::get_min_max_price( 'st_tours' );
        echo '<input type="text" name="price_range" value="' . STInput::get( 'price_range' ) . '" class="price-slider" data-symbol="' . TravelHelper::get_current_currency( 'symbol' ) . '" data-min="' . $data_min_max[ 'price_min' ] . '" data-max="' . $data_min_max[ 'price_max' ] . '" data-step="' . st()->get_option( 'search_price_range_step' , 0 ) . '">';
        */?>
    </div>-->
    <button class="btn btn-primary btn_search" data-title="<?php st_the_language( 'search_for_tour' ) ?>"
            type="submit"><?php st_the_language( 'search_for_tour' ) ?></button>
    <hr>
</form>
