<?php
/**
 * Created by PhpStorm.
 * User: me664
 * Date: 11/24/14
 * Time: 8:58 AM
 */
class ST_List_Item_Post_Type
{
    public  $url;
    public $dir;

    function __construct()
    {

        $this->dir=st()->dir('plugins/custom-option-tree');
        $this->url=st()->url('plugins/custom-option-tree');


        add_action('admin_enqueue_scripts',array($this,'add_scripts'));
        add_action('wp_enqueue_scripts',array($this,'add_scripts'));
    }
    function init()
    {


        if( !class_exists( 'OT_Loader' ) ) return false;


        //Default Fields
        add_filter( 'ot_post_select_ajax_unit_types', array($this,'ot_post_select_ajax_unit_types'), 10, 2 );


        add_filter( 'ot_option_types_array', array($this,'ot_add_custom_option_types') );
        add_action('wp_ajax_list_item_post_type',array($this,'list_item_post_type'));
        add_action('wp_ajax_nopriv_list_item_post_type',array($this,'list_item_post_type'));

        include_once $this->dir.'/custom-css-output.php';

    }

    function add_scripts(){
    	wp_enqueue_script('selectize',$this->url.'/js/selectize/selectize.min.js',array('jquery'),null,true);
        wp_enqueue_style('selectize-css',$this->url.'/js/selectize/selectize.css');
        wp_enqueue_style('selectize-bt3-css',$this->url.'/js/selectize/selectize.bootstrap3.css');
    }
    function list_item_post_type(){
    	$post_type = STInput::request('post_type', 'location');
        $query = array(
            'post_type' => $post_type,
            'posts_per_page' => -1
            );
        query_posts($query);
        $result = array();
        while(have_posts()): the_post();
            $result[] = array(
                'title' => get_the_title(),
                'id' => get_the_ID()
                );
        endwhile;
        wp_reset_query(); wp_reset_postdata();

        echo json_encode($result);
        die;

    }

    function ot_post_select_ajax_unit_types($array, $id )
    {
        return apply_filters( 'list_item_post_type', $array, $id );
    }

    function ot_add_custom_option_types( $types ) {
        $types['list_item_post_type']       = __('List Item Post Type',ST_TEXTDOMAIN);

        return $types;
    }

}


$a = new ST_List_Item_Post_Type();
$a->init();


if(!function_exists('ot_type_list_item_post_type')):
function ot_type_list_item_post_type($args = array())
{
    $st_custom_ot = new ST_List_Item_Post_Type();

    $url = $st_custom_ot->url;


    $default=array(

        'field_post_type'=>'location',
        'field_desc'=> 'Location'
    );



    $args = wp_parse_args($args,$default);


    extract($args);

    $post_type = $field_post_type;

    /* verify a description */
    $has_desc = $field_desc ? true : false;

    echo '<div class="format-setting type-post_select_ajax ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

    echo balanceTags($has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '');

        echo '<div class="format-setting-inner">';
        $pl_name='';
        $pl_desc='';
        if($field_value){
            $pl_name = get_the_title($field_value);
            $pl_desc="ID: ".get_the_ID($field_value);
        }

        $post_type_json = $post_type;
        $locations = TravelHelper::getLocationBySession();
        $html_location = TravelHelper::treeLocationHtml($locations, 0);

        echo '<select placeholder="'.__("Select item...", ST_TEXTDOMAIN).'" tabindex="-1" name="'.esc_attr( $field_name ).'[]" id="'.esc_attr( $field_id ).'" class="option-tree-ui-select list-item-post-type" data-post-type="'.$post_type_json.'">';
        
        if(is_array($html_location) && count($html_location)):
            foreach($html_location as $key => $value):
                $id = preg_replace("/(\_)/", "", $value['ID']);
        ?>      
            <option value="<?php echo $value['ID']; ?>"><?php echo $value['prefix'].get_the_title($id); ?></option>
        <?php
        endforeach; endif;
        echo '</select>';
        echo '</div>';
        echo '</div>';
}
endif;