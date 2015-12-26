<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.1.3
 *
 * Filter Taxonomy
 *
 * Created by ShineTheme
 *
 */
if(empty( $taxonomy )) return;

$terms = get_terms( $taxonomy );
$key   = $taxonomy;
foreach( $terms as $key2 => $value2 ) {

    if($post_type == 'hotel_room'){
        $name_field = "taxonomy_hotel_room";
    }else{
        $name_field = "taxonomy";
    }

    $current = STInput::get( $name_field );

    if(isset( $current[ $key ] ))
        $current = $current[ $key ];
    else $current = '';

    $checked = TravelHelper::checked_array( explode( ',' , $current ) , $value2->term_id );

    if($checked) {
        $link = TravelHelper::build_url_array( $name_field , $key , $value2->term_id , false );
    } else {
        $link = TravelHelper::build_url_array( $name_field , $key , $value2->term_id );
    }


    ?>
    <div class="checkbox">
        <label>
            <input <?php if($checked) echo "checked" ?> value="<?php echo esc_attr( $value2->term_id )?>" name="" data-url="<?php echo esc_url( $link ) ?>" class="i-check" type="checkbox"/> <?php echo esc_html( $value2->name )?>
        </label>
    </div>
<?php
}