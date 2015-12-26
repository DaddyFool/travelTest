<?php 
$list_item = (get_post_meta(get_the_ID() , 'info_tab_item' , true));
   if (($nav_position)  =="top"){
   }
?>
<div class='location_list_item_content col-md-9 col-xs-9 '>
	<div class='tab-content'>
	<?php 
	$list_item = get_post_meta(get_the_ID() , 'info_tab_item' , true );
	if(!empty($list_item) and is_array($list_item)){
		foreach ($list_item as $key => $value) {
			$post_id = $value['post_info_select']; 
			$title = $value['title'];
			?>
				<div class='tab-pane  fade <?php if ($key == 0) echo " in active " ; ?>' id='<?php echo esc_attr("tab_".$post_id); ?>'>
					<?php 
						$the_post_content = get_post($post_id);
						$content = $the_post_content->post_content;
						$content = apply_filters('the_content', $content);
						$content = str_replace(']]>', ']]>', $content);

						// Print Content data or further use it as required
						echo $content;
					?>
				</div>
			<?php 
		}
	}
?>	
	</div>
</div>


