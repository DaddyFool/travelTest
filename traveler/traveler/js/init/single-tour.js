jQuery(document).ready(function($) {
    $('[data-toggle="tooltip"]').tooltip();
    var listDate = [];
    if($('input.tour_book_date').length > 0){
        $('input.tour_book_date').each(function(index, el) {
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
            tour_id = $(this).data('tour-id');
            ajaxGetRentalOrder($this, year_start, tour_id);
        });

        $('input.tour_book_date').on('changeYear', function(e) {
            var $this = $(this);
            year =  new Date(e.date).getFullYear();
            tour_id = $(this).data('tour-id');
            ajaxGetRentalOrder( $this, year, tour_id);
        });
        
    }else{
        $('.package-info-wrapper .overlay-form').fadeOut(500);
    }

    function ajaxGetRentalOrder(me, year, tour_id){
        var data = {
            tour_id: tour_id,
            year: year,
            action:'st_get_disable_date_tour',
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

    var TourCalendar = function(container){
        var self = this;
        this.container = container;
        this.calendar= null;
        this.form_container = null;

        this.init = function(){
            self.container = container;
            self.calendar = $('.calendar-content', self.container);
            self.form_container = $('.calendar-form', self.container);
            self.initCalendar();
        }

        this.initCalendar = function(){
            self.calendar.fullCalendar({ 
                firstDay: 1,
                lang:st_params.locale,
                customButtons: {
                    reloadButton: {
                        text: st_params.text_refresh,
                        click: function() {
                            self.calendar.fullCalendar( 'refetchEvents' );
                            // ty dat adult , child vao day
                        }
                    }
                },
                header : {
                    left:   'prev',
                    center: 'title',
                    right:  'next'
                },    
                contentHeight: 360,
                //dayNames: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
                //dayNamesShort: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
                select : function(start, end, jsEvent, view){
                    var start_date = new Date(start._d).toString("MM");
                    var end_date = new Date(end._d).toString("MM");
                    var today = new Date().toString("MM");
                    if(start_date < today || end_date < today){
                        self.calendar.fullCalendar('unselect');
                    }
                    
                },
                events:function(start, end, timezone, callback) {
                    $.ajax({
                        url: st_params.ajax_url,
                        dataType: 'json',
                        type:'post',
                        data: {
                            action: 'st_get_availability_tour_frontend',
                            tour_id: self.container.data('post-id'),
                            start: start.unix(),
                            end: end.unix()
                        },
                        success: function(doc){
                            if(typeof doc == 'object'){
                                callback(doc);
                            }
                        },
                        error:function(e)
                        {
                            alert('Can not get the availability slot. Lost connect with your sever');
                        }
                    });
                },
                eventClick: function(event, element, view){
                    /*var scroll = $('.package-info-wrapper').offset().top - $(window).height() / 2;
                    var animate = '';
                    clearTimeout(animate);
                    setTimeout(function(){
                        $('html, body').animate({scrollTop: scroll},0);
                    });*/
                },
                eventMouseover: function(event, jsEvent, view){
                    //$('.event-number-'+event.start.unix()).addClass('hover');
                },
                eventMouseout: function(event, jsEvent, view){
                    //$('.event-number-'+event.start.unix()).removeClass('hover');
                },                
                eventRender: function(event, element, view){                          
                    var html = event.day; 
                    var html_class = "none";
                    if(typeof event.date_end != 'undefined'){
                        html += ' - '+event.date_end;
                        html_class = "group";
                    }                    
                    var today_y_m_d = new Date().getFullYear() +"-"+(new Date().getMonth()+1)+"-"+new Date().getDate();                    
                    if(event.status == 'available'){
                        var title ="";
                        
                        if (event.adult_price != 0) {title += st_checkout_text.adult_price+': '+event.adult_price + " <br/>"; }
                        if (event.child_price != 0) {title += st_checkout_text.child_price+': '+event.child_price + " <br/>"; }
                        if (event.infant_price != 0) {title += st_checkout_text.infant_price+': '+event.infant_price ;  }
                        
                        html  = "<button data-placement='top' title  = '"+title+"' data-toggle='tooltip' class='"+html_class+" btn btn-available'>" + html;
                    }else {
                        html  = "<button disabled data-placement='top' title  = 'Disabled' data-toggle='tooltip' class='"+html_class+" btn btn-disabled'>" + html;
                    }         
                    if (today_y_m_d === event.date) {
                        html += "<span class='triangle'></span>";
                    }
                    html  += "</button>";
                    element.addClass('event-'+event.id)
                    element.addClass('event-number-'+event.start.unix());
                    $('.fc-content', element).html(html);

                    
                    element.bind('click', function(calEvent, jsEvent, view) {
                        if (!$(this).find("button").hasClass('btn-available')) return ; 
                        $('.fc-day-grid-event').removeClass('st-active');
                        $(this).addClass('st-active');
                        $('input#check_in').val(event.start._i);
                        if(typeof event.end != 'undefined' && event.end && typeof event.end._i != 'undefined'){
                                date = new Date(event.end._i);
                                date.setDate(date.getDate() - 1);
                                date = $.fullCalendar.moment(date).format("YYYY-MM-DD");
                            $('input#check_out').val(date).parent().show();
                        }else{
                            $('input#check_out').val(event.start._i).parent().hide();

                        }
                        $('input#adult_price').val(event.adult_price);
                        $('input#child_price').val(event.child_price);
                        $('input#infant_price').val(event.infant_price);
                        
                    }); 
                },
                eventAfterRender: function( event, element, view ) {
                    $('[data-toggle="tooltip"]').tooltip({html:true});
                },
                loading: function(isLoading, view){
                    if(isLoading){
                        $('.calendar-wrapper-inner .overlay-form').fadeIn();
                    }else{
                        $('.calendar-wrapper-inner .overlay-form').fadeOut();
                    }
                },

            });
        }
    };
    $('input#check_out').parent().hide();
    $('.calendar-wrapper').each(function(index, el) {
        var t = $(this);
        var tour = new TourCalendar(t);
        tour.init();
    });
    if($('#select-a-tour').length){
        $("#select-a-tour").fancybox({
            'transitionIn'      : 'none',
            'transitionOut'     : 'none',
        });
    }
    if($('#list_tour_item').length){
        $('#list_tour_item input').on('ifChecked', function(event){
            $('#check_in_2').val($(this).data('start')).parent().show();
            $('#check_out_2').val($(this).data('end'));
            if($('#list_tour_item').data('type-tour') != 'daily_tour'){
                $('#check_out_2').parent().show();
            }
            setTimeout(function(){
                $.fancybox.close();
            }, 500);
        });
        if($('#check_in_2').val() != '' && $('#check_out_2').val() != ''){
            $('#check_in_2').parent().show();
            if($('#list_tour_item').data('type-tour') != 'daily_tour'){
                $('#check_out_2').parent().show();
            }
        }else{
            $('#check_in_2').parent().hide();
            if($('#list_tour_item').data('type-tour') != 'daily_tour'){
                $('#check_out_2').parent().hide();
            }
        }
    }
    
});
