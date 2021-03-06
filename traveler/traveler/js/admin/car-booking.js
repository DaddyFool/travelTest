jQuery(document).ready(function($) {
	$('.st_timepicker').timepicker({
		timeFormat: "hh:mm tt"
	});
	var $parent = $('#form-booking-admin');
	$('input[name="item_id"]', $parent).change(function(event) {
		var car_id = parseInt($(this).val());
		getInfoCar(car_id);
	});

	function getInfoCar(car_id){
		$('#item-equipment-wrapper').html('');
		$('#item-price-wrapper').html('');
		$('span.spinner').addClass('is-active');
		if(typeof car_id != 'undefined' && car_id > 0){
			data = {
				action: 'st_getInfoCar',
				car_id: car_id
			};
			$.post(ajaxurl, data, function(respon, textStatus, xhr) {
				$('span.spinner').removeClass('is-active');
				$('#item-equipment-wrapper').html(respon.item_equipment);
				$('#item-price-wrapper').html(respon.price);
			},'json');
		}
	}	
});