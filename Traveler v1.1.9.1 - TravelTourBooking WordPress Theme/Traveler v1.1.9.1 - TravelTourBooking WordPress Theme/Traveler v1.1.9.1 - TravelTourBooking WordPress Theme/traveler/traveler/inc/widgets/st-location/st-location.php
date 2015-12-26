<?php

/**
* @package  Wordpress 
* @subpackage shinetheme
* @since 1.1.3
*/

/**@update 1.1.5*/
class st_location_widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'st_location_widget', 
			__('ST Location list', ST_TEXTDOMAIN), 
			array( 'description' => __( 'Show list post type by criteria', ST_TEXTDOMAIN ), ) 
		);
		add_action('admin_enqueue_scripts',array($this,'add_scripts'));

	    
	}
	public function add_scripts(){
		$screen=get_current_screen();

        if($screen->base=='widgets'){
        	wp_enqueue_style('jquery-ui',get_template_directory_uri().'/css/admin/jquery-ui.min.css');
            wp_enqueue_script('location_widget',get_template_directory_uri().'/js/admin/widgets/location_widget.js',array('jquery','jquery-ui-sortable'),null,true);            
        }
	}

	public function widget( $args, $instance ) {
		$instance=wp_parse_args($instance,array(
				'location'=>'',
				'style'=>'',
				'post_type'=>'',
				'count'=>5,
				'layout'=>''
			) );
		$title                 = apply_filters( 'widget_title', $instance['title'] );	
		$instance['title']     = $title; 
		$args['after_title']   = apply_filters('after_title_widget_location' , $args['after_title'] );	
		$args['before_title']  = apply_filters('before_title_widget_location' , $args['before_title'] );	
		$args['before_widget'] = apply_filters('before_widget_location' , $args['before_widget'] ); 
		$args['after_widget']  = apply_filters('after_widget_location' , $args['after_widget'] ); 

		if (!$instance['count']){$instance['count']         =  5 ; }
		if (!$instance['post_type']){$instance['post_type'] =  'st_cars' ; }
		if (!$instance['location']){$instance['location']   =  get_the_ID() ; }
		if (!$instance['style']){$instance['style']         =  'latest' ; }


		echo st()->load_template('location/widget/list_widget' , NULL,array('instance'=>$instance));
		
	}
		
	public function form( $instance ) {
		$instance=wp_parse_args($instance,array(
				'location'=>'',
				'style'=>'',
				'post_type'=>'',
				'count'=>5,
				'layout'=>''
			) );
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'Title', ST_TEXTDOMAIN );
		}
		if ( isset( $instance[ 'style' ] ) ) {
			$style = $instance[ 'style' ];
		}
		if ( isset( $instance[ 'layout' ] ) ) {
			$layout = $instance[ 'layout' ];
		}
		if ( isset( $instance[ 'post_type' ] ) ) {
			$post_type = $instance[ 'post_type' ];
		}
		else {
			$post_type = __( 'Post type', ST_TEXTDOMAIN );
		}
		if ( isset( $instance[ 'count' ] ) ) {
			$count = $instance[ 'count' ];
		}
		else {
			$count = 5;
		}
		?>

		<div class='location_widget_item'>
			<p>
				<label> <?php _e( 'Title', ST_TEXTDOMAIN ); ?></label> 
				<input class="widefat" id="<?php echo balancetags( $this->get_field_id( 'title' )); ?>" name="<?php echo balancetags($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>
			
			<p>
				<label><?php _e( 'Select post type'  , ST_TEXTDOMAIN ); ?></label> 
				<select name="<?php echo balancetags($this->get_field_name( 'post_type' )); ?>" >
					<option <?php if ($post_type =="st_cars") echo balancetags("selected") ; ?>  value='st_cars'>Car</option>
					<option <?php if ($post_type =="st_hotel") echo balancetags("selected") ; ?>  value='st_hotel'>Hotel</option>
					<option <?php if ($post_type =="st_rental") echo balancetags("selected") ; ?>  value='st_rental'>Rental</option>
					<option <?php if ($post_type =="st_tours") echo balancetags("selected") ; ?>  value='st_tours'>Tour</option>
					<option <?php if ($post_type =="st_activity") echo balancetags("selected") ; ?>  value='st_activity'>Activity</option>
				</select>
			</p>
			<p>
				<label> <?php echo __( 'Style',ST_TEXTDOMAIN ); ?></label>
				
				<select name='<?php echo balancetags($this->get_field_name('style')); ?>'>
					<option value=''> -- Select -- </option>
					<option <?php if ($style =="latest") echo esc_attr("selected") ;?> value='latest'><?php echo __("Latest" , ST_TEXTDOMAIN) ; ?></option>
					<option <?php if ($style =="famous") echo esc_attr("selected") ;?> value='famous'><?php echo __("Famous" , ST_TEXTDOMAIN) ; ?></option>
					<!--<option <?php if ($style =="cheapest") echo esc_attr("selected") ;?> value='cheapest'><?php echo __("Cheapest" , ST_TEXTDOMAIN) ; ?></option>-->
				</select>
			</p>
			<p>
				<label for=""><?php echo __('Layout', ST_TEXTDOMAIN) ?></label>
				<select name='<?php echo balancetags($this->get_field_name('layout')); ?>'>
					<option <?php if ($layout =="layout 1") echo esc_attr("selected") ;?> value='layout1'><?php echo __("Layout1" , ST_TEXTDOMAIN) ; ?></option>
					<option <?php if ($layout =="layout2") echo esc_attr("selected") ;?> value='layout2'><?php echo __("Layout2" , ST_TEXTDOMAIN) ; ?></option>
				</select>
			</p>
			<p>
				<label><?php echo __("Count num",ST_TEXTDOMAIN);?> </label>
				<input type='number' 
				name='<?php echo balancetags($this->get_field_name('count'))  ; ?>' 
				value='<?php echo balancetags($count);?>' />
			</p>
			<p>
				<label><?php echo __("Location select" , ST_TEXTDOMAIN) ; ?></label>				
				<?php 
				$list_location = TravelerObject::get_list_location();
				$old_location = $instance['location'];
				?>
				<select name="<?php echo balancetags($this->get_field_name('location'));?>" class="form-control">
			       <option value=""><?php _e('-- Select --',ST_TEXTDOMAIN) ?></option>
			       <?php foreach($list_location as $k=>$v): ?>
			            <option <?php if($old_location == $v['id'] ) echo 'selected' ?> value="<?php echo esc_html($v['id']) ?>">
			                <?php echo esc_html($v['title']) ?>
			            </option>
			       <?php endforeach; ?>
			   </select>
			</p>

		</div>
		<?php 
	}
		
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {  
		$instance              = array();
		$instance['title']     = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';		
		$instance['style']     = ( ! empty( $new_instance['style'] ) ) ? strip_tags( $new_instance['style'] ) : '';		
		$instance['layout']    = ( ! empty( $new_instance['layout'] ) ) ? strip_tags( $new_instance['layout'] ) : '';		
		$instance['post_type'] = ( ! empty( $new_instance['post_type'] ) ) ? strip_tags( $new_instance['post_type'] ) : '';
		$instance['count']     = ( ! empty( $new_instance['count'] ) ) ? strip_tags( $new_instance['count'] ) : '';		
		$instance['location']  = ( ! empty( $new_instance['location'] ) ) ? strip_tags( $new_instance['location'] ) : '';		
		
		return $instance;
	}
} // Class st_location_widget ends here

// Register and load the widget
function wpb_load_widget() {
	register_widget( 'st_location_widget' );
}
add_action( 'widgets_init', 'wpb_load_widget' );


//2nd widget
/**@update 1.1.5*/
class st_location_largest_selection extends WP_Widget{
	function __construct() {
		parent::__construct(
			'st_location_largest_selection', 
			__('ST Location selection ', ST_TEXTDOMAIN), 
			array( 'description' => __( 'Show Largest Location information', ST_TEXTDOMAIN ), ) 
		);
		add_action('admin_enqueue_scripts',array($this,'add_scripts'));
	}
	function add_scripts(){
		$screen=get_current_screen();

        if($screen->base=='widgets'){
        	wp_enqueue_style('jquery-ui',get_template_directory_uri().'/css/admin/jquery-ui.min.css');
            wp_enqueue_script('location_widget',get_template_directory_uri().'/js/admin/widgets/location_widget.js',array('jquery','jquery-ui-sortable'),null,true);            
        }
	}
	public function widget( $args, $instance ) {

		$title = apply_filters( 'widget_title', $instance['title'] );	
		$instance['title'] = $title ;
		$args['after_title'] = apply_filters('after_title_widget_location' , $args['after_title'] );	
		$args['before_title'] = apply_filters('before_title_widget_location' , $args['before_title'] );	
		$args['before_widget'] = apply_filters('before_widget_location' , $args['before_widget'] ); 
		$args['after_widget'] = apply_filters('after_widget_location' , $args['after_widget'] ); 

		$default = array(
            'location'=>get_the_ID(),
            'post_type'=>STLocation::get_post_type_list_active() , 
            'count_review'=> 'no'
        );
        extract( wp_parse_args( $instance , $default ) );
        
		echo st()->load_template('location/widget/largest_section_location' , NULL,array('instance'=>$instance));

	}
	public function form( $instance ) {
		// title
		// list post type checkbox 
		// review checkbox
		// location select

		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'Title', ST_TEXTDOMAIN );
		}
		if ( isset( $instance[ 'post_type' ] ) ) {
			$post_type = $instance[ 'post_type' ];
		}
		if ( isset( $instance[ 'count_review' ] ) ) {
			$count_review = $instance[ 'count_review' ];
		}
		
		?>
		<div class='location_widget_item'>
			<p>
				<label ><?php echo balancetags("Title" , ST_TEXTDOMAIN); ?></label>
				<input value='<?php echo esc_attr($title)  ; ?>' type='text' name='<?php echo $this->get_field_name('title');?>'/>
			</p>
			<p>
				<label><?php echo balancetags("Post type select " , ST_TEXTDOMAIN);?></label>
				<?php
                $get_post_type_list_active = STLocation::get_post_type_list_active();
				if (!empty($get_post_type_list_active) and is_array($get_post_type_list_active)){
					foreach(STLocation::get_post_type_list_active() as $key=>$value){
						?>
						<br>
						<input <?php if(!empty($post_type) and $post_type and in_array($value, $post_type) ){echo esc_attr('checked');};?> 
						id ='<?php echo esc_attr("st_w_".$value); ?>'  
						value = '<?php echo esc_attr($value); ?>' 
						type='checkbox' 
						name='<?php echo balancetags($this->get_field_name('post_type'))?>[]'/> 
						<label ><?php echo esc_attr(STLocation::get_post_type_name($value,true));?></label>
						<?php
					};
				};				
				 ?>
			</p>
			<p>
				<label><?php echo balancetags("Count Review" , ST_TEXTDOMAIN); ?></label>
				<input <?php if (!empty($count_review) and  $count_review =='on'){echo "checked"; } ?> type='checkbox' name='<?php echo balancetags($this->get_field_name('count_review'));?>'/>
			</p>
			<p>
				<label><?php echo esc_attr("Location select",ST_TEXTDOMAIN) ;?></label>
				<?php 
				$list_location = TravelerObject::get_list_location();
				if (!empty($instance['location'])){
					$old_location = $instance['location'];
				}
				//$old_location = $instance['location'];
				?>
				<select name="<?php echo balancetags($this->get_field_name('location'));?>" class="form-control">
			       <option value=""><?php _e('-- Select --',ST_TEXTDOMAIN) ?></option>
			       <?php foreach($list_location as $k=>$v): ?>
			            <option <?php if(!empty($old_location) and $old_location == $v['id'] ) echo 'selected' ?> value="<?php echo esc_html($v['id']) ?>">
			                <?php echo esc_html($v['title']) ?>
			            </option>
			       <?php endforeach; ?>
			   </select>
		   </p>
		</div>
		<?php

	}
	public function update( $new_instance, $old_instance ) {
		/*$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';				
		$instance['post_type'] = ( ! empty( $new_instance['post_type'] ) ) ? strip_tags( $new_instance['post_type'] ) : $new_instance['post_type'];
		$instance['count_review'] = ( ! empty( $new_instance['count'] ) ) ? strip_tags( $new_instance['count'] ) : '';		
		$instance['location'] = ( ! empty( $new_instance['location'] ) ) ? strip_tags( $new_instance['location'] ) : '';		
		*/
		return $new_instance;
	}
} 
// class Largest Selection location
// register 
function st_location_largest_selection_func(){
	register_widget('st_location_largest_selection');
}
add_action('widgets_init' , 'st_location_largest_selection_func');