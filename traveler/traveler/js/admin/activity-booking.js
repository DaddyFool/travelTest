jQuery(document).ready(function($) {
	
	$('#form-booking-admin input[name="item_id"]').change(function(event) {

		var item_id = $(this).val();
		getActivityInfo(item_id);
	});
	
	if($('#form-booking-admin input[name="item_id"]').val() != "" && parseInt($('#form-booking-admin input[name="item_id"]').val()) > 0){
		var item_id = $('#form-booking-admin input[name="item_id"]').val();
		getActivityInfo(item_id);
	}
	function getActivityInfo(item_id){
		$parent = $('#form-booking-admin');
		$('span.spinner', $parent).addClass('is-active');
		if(typeof item_id != 'undefined' && parseInt(item_id) > 0){
			data = {
				action: 'st_getInfoActivity',
				activity_id: item_id
			};
			$.post(ajaxurl, data, function(respon, textStatus, xhr) {
				$('span.spinner', $parent).removeClass('is-active');
				if(typeof respon == 'object'){
					$('#activity-type-wrapper', $parent).html(respon.type_activity);
					if(respon.activity_text == 'daily_activity'){
						$('input#check_out', $parent).attr('data-duration', respon.duration);
						$('input#check_in', $parent).datepicker({
				            minDate: 0,
				            dateFormat : 'mm/dd/yy'
				        });
						$('#form-booking-admin').on('change', 'input#check_in', function(event) {
							event.preventDefault();
							var date = $(this).val();
							var duration = $('input#check_out', $parent).data('duration');
							if(typeof duration == 'undefined' || duration == '') duration = 0;
							var new_date = new Date(date);
							new_date.setDate(new_date.getDate() + parseInt(duration));
							var  d = new_date.getDate();
							d = (d.toString().length < 2) ? '0'+d : d; 
							var m = new_date.getMonth() + 1;
							m = (m.toString().length < 2) ? '0'+m : m;
							var y = new_date.getFullYear();
							$('input#check_out', $parent).val(m+'/'+d+'/'+y);
						});
					}else{
						$('input#check_in', $parent).val(respon.check_in);
						$('input#check_out', $parent).val(respon.check_out);
					}
					$('input#adult_price', $parent).val(respon.adult_price);
					$('input#child_price', $parent).val(respon.child_price);
					$('input#infant_price', $parent).val(respon.infant_price);
					$('input#max_people', $parent).val(respon.max_people);
				}
			}, 'json');
		}
	}
});