jQuery(document).ready(function($) {
	booking_period = $('.booking-item-dates-change').data('booking-period');
	if(typeof booking_period != 'undefined' && parseInt(booking_period) > 0){
		var data = {
            booking_period : booking_period,
            action: 'st_getBookingPeriod'
        };
        $.post(st_params.ajax_url, data, function(respon) {
            if(respon != ''){
                $('input.checkin_hotel, input.checkout_hotel').datepicker('setRefresh',true);
                $('input.checkin_hotel, input.checkout_hotel').datepicker('setDatesDisabled',respon); 
            }    
        },'json');


        $( document ).ajaxStop(function() {
            $('.overlay-form').fadeOut(500); 
        });
	}else{
        $('.overlay-form').fadeOut(500);
    }

    $('ul.paged_room a.paged_room').each(function(){
        $(this).attr('data-page',$(this).html());
    });

    $(document).on('click','.paged_item_room',function(){
        var paged = $(this).data('page');
        $('.booking-item-dates-change .paged_room').val(paged);
        $('.btn-do-search-room').click();
    });


});