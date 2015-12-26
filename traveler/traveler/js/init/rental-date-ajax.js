jQuery(document).ready(function($) {
    var listDate = [];
    $('input.checkin_rental, input.checkout_rental').each(function() {
        $(this).datepicker({
            language:st_params.locale,
            format: $('[data-date-format]').data('date-format'),
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
        month_start = date_start.getMonth() + 1;

        ajaxGetRentalOrder(month_start, year_start, $this);
    });
    

    $('input.checkin_rental').on('changeMonth', function(e) {
        var $this = $(this);
        year =  new Date(e.date).getFullYear();
        month =  new Date(e.date).getMonth() + 1;
        ajaxGetRentalOrder(month, year, $this);
    });

    $('input.checkin_rental').on('changeDate',function(e){
        $('input.checkout_rental').datepicker('setDates', '');
    });

    $('input.checkout_rental').on('changeMonth', function(e) {
        var $this = $(this);
        year =  new Date(e.date).getFullYear();
        month =  new Date(e.date).getMonth() + 1;
        ajaxGetRentalOrder(month, year, $this);
    });

    function ajaxGetRentalOrder(month, year, me){
        post_id = $('.booking-item-dates-change').data('post-id');
        $('.date-overlay').addClass('open');
        if(!typeof post_id != 'undefined' || parseInt(post_id) > 0){
            var data = {
                rental_id : post_id,
                month: month,
                year: year,
                security:st_params.st_search_nonce,
                action:'st_get_disable_date_rental',
            };
            
            $.post(st_params.ajax_url, data, function(respon) {
                if(respon != ''){
                    listDate = respon;
                }
                booking_period = $('.booking-item-dates-change').data('booking-period');
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
                        $('.date-overlay').removeClass('open');
                    },'json'); 
                }else{
                    me.datepicker('setRefresh',true);
                    me.datepicker('setDatesDisabled',listDate);
                    $('.date-overlay').removeClass('open');
                }
            },'json');

        }else{
            $('.date-overlay').removeClass('open');
        }
    } 
});