jQuery(document).ready(function($) {
    var listDate = [];
    if($('input.activity_book_date').length > 0){
        $('input.activity_book_date').each(function(index, el) {
            $(this).datepicker({
                language:st_params.locale,
                format: $(this).data('date-format'),
                todayHighlight: true,
                autoclose: true,
                startDate: 'today',
            });
            date_start = $(this).datepicker('getDate');
            $(this).datepicker('addNewClass','booked');
            var $this = $(this);
            if(date_start == null)
                date_start = new Date();

            year_start = date_start.getFullYear();
            activity_id = $(this).data('activity-id');
            ajaxGetRentalOrder($this, year_start, activity_id);
        });

        $('input.activity_book_date').on('changeYear', function(e) {
            var $this = $(this);
            year =  new Date(e.date).getFullYear();
            activity_id = $(this).data('activity-id');
            ajaxGetRentalOrder( $this, year, activity_id);
        });
        
    }else{
        $('.overlay-form').fadeOut(500);
    }

    function ajaxGetRentalOrder(me, year, activity_id){
        var data = {
            activity_id: activity_id,
            year: year,
            action:'st_get_disable_date_activity',
        };
        $.post(st_params.ajax_url, data, function(respon) {
            if(respon!= ''){
                listDate = respon;
            }
            booking_period = me.data('booking-period');
            if(typeof booking_period != 'undefined' && parseInt(booking_period) > 0){
                var data = {
                    booking_period : booking_period,
                    action: 'st_getBookingPeriod'
                };
                $.post(st_params.ajax_url, data, function(respon1) {
                    if(respon1 != ''){
                        listDate = listDate.concat(respon1);
                        me.datepicker('setRefresh',true);
                        me.datepicker('setDatesDisabled',listDate);    
                    } 
                },'json');
            }else{
                me.datepicker('setRefresh',true);
                me.datepicker('setDatesDisabled',listDate);
                $('.overlay-form').fadeOut(500); 
            }
        },'json');
    }

    $( document ).ajaxStop(function() {
        $('.overlay-form').fadeOut(500); 
    });
});