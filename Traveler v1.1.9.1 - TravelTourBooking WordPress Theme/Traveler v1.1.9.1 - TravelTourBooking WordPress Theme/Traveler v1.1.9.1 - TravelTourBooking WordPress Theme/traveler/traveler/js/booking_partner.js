jQuery(document).ready(function($){
    var parent = $('.form-add-booking-partner');
    var nowTemp = new Date();
    var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

    $('.st_timepicker').timepicker({
        timeFormat: "hh:mm tt"
    });

    var check_in = $('input#check_in', parent).datepicker({
        format: $(this).data('date-format'),
        startDate: "today",
        todayBtn: "linked",
        todayHighlight: true,
        autoclose: true,
        format: $('[data-date-format]').data('date-format')
    });

    var check_out = $('input#check_out', parent).datepicker({
        format: $(this).data('date-format'),
        startDate: "today",
        todayBtn: "linked",
        todayHighlight: true,
        autoclose: true,
        format: $('[data-date-format]').data('date-format')
    });

    check_in.on('changeDate', function (e) {
        var new_date = e.date;
        new_date.setDate(new_date.getDate() + 1);
        $('input#check_out', parent).datepicker('setDates', new_date);
        $('input#check_out', parent).datepicker('setStartDate', new_date);
    });


    var check_in_car = $('input#check_in_car', parent).datepicker({
        format: $(this).data('date-format'),
        startDate: "today",
        todayBtn: "linked",
        todayHighlight: true,
        autoclose: true,
        format: $('[data-date-format]').data('date-format')
    });

    var check_out_car = $('input#check_out_car', parent).datepicker({
        format: $(this).data('date-format'),
        startDate: "today",
        todayBtn: "linked",
        todayHighlight: true,
        autoclose: true,
        format: $('[data-date-format]').data('date-format')
    });

    check_in_car.on('changeDate', function (e) {
        var new_date = e.date;
        new_date.setDate(new_date.getDate());
        $('input#check_out_car', parent).datepicker('setDates', new_date);
        $('input#check_out_car', parent).datepicker('setStartDate', new_date);
    });

    $('.st_post_select_ajax', parent).each(function(){
        var me = $(this);
        $(this).select2({
            placeholder: me.data('placeholder'),
            minimumInputLength:2,
            allowClear: true,
            ajax: { // instead of writing the function to execute the request we use Select2's convenient helper
                url: st_params.ajax_url,
                dataType: 'json',
                quietMillis: 250,
                data: function (term, page) {
                    return {
                        q: term,
                        action: 'st_post_select_ajax',
                        post_type: me.data('post-type'),
                        user_id: me.data('user-id')
                    };
                },
                results: function (data, page) {
                    return { results: data.items };
                },
                cache: true
            },
            initSelection: function(element, callback) {
                var id = $(element).val();
                if (id !== "") {
                    var data={
                        id:id,
                        name:$(element).data('pl-name'),
                        description:$(element).data('pl-desc')
                    };
                    callback(data);
                }
            },
            formatResult: function(state){
                if (!state.id) return state.name; // optgroup
                return state.name+'<p><em>'+state.description+'</em></p>';
            },
            formatSelection: function(state){
                if (!state.id) return state.name; // optgroup
                return state.name+'<p><em>'+state.description+'</em></p>';
            },
            escapeMarkup: function(m) { return m; }
        });
    });
	
	$('input#hotel_id', parent).change(function(event) {
		var hotel_id = $(this).val();
		var user_id = $(this).data('user-id');
		var data = {
			action: 'st_partnerGetListRoom',
			hotel_id : hotel_id,
			user_id : user_id
		};
        $('#overlay', parent).addClass('active');
		$.post(st_params.ajax_url, data, function(respon, textStatus, xhr) {
            $('#overlay', parent).removeClass('active');
			if(typeof respon == 'object'){
				$('input#room_id', parent).select2({
					data: respon
				});
			}
		}, 'json');
	});
    $('input#room_id', parent).change(function(event) {
        $('input#item_price', parent).val('');
        $('#extra-price-wrapper', parent).html('');
        var room_id = $(this).val();
        if(typeof room_id != 'undefined' && parseInt(room_id) > 0){
            $('#overlay', parent).addClass('active');
            data = {
                action: 'st_getRoomHotelInfo',
                room_id: room_id
            };
            $.post(st_params.ajax_url, data, function(respon, textStatus, xhr) {
               $('#overlay', parent).removeClass('active');
                if(typeof respon == 'object'){
                    $('input#item_price', parent).val(respon.price);
                    $('#extra-price-wrapper', parent).html(respon.extras);
                    $(parent).iCheck({
                        checkboxClass: 'i-check',
                        radioClass: 'i-radio'
                    });
                    $('#adult-wrapper', parent).html(respon.adult_html);
                    $('#child-wrapper', parent).html(respon.child_html);
                    $('#room-wrapper', parent).html(respon.room_html);
                }
            }, 'json');
        }
    });
    
    $('input#rental_id', parent).change(function(event) {
        $('input#item_price', parent).val('');
        $('#extra-price-wrapper', parent).html('');
        var rental_id = $(this).val();
        if(typeof rental_id != 'undefined' && parseInt(rental_id) > 0){
            $('#overlay', parent).addClass('active');
            data = {
                action: 'st_getRentalInfo',
                rental_id: rental_id
            };
            $.post(st_params.ajax_url, data, function(respon, textStatus, xhr) {
                $('#overlay', parent).removeClass('active');
                if(typeof respon == 'object'){
                    $('input#item_price', parent).val(respon.price);
                    $('#extra-price-wrapper', parent).html(respon.extras);
                    $(parent).iCheck({
                        checkboxClass: 'i-check',
                        radioClass: 'i-radio'
                    });
                    $('#adult-wrapper', parent).html(respon.adult_html);
                    $('#child-wrapper', parent).html(respon.child_html);
                }
            }, 'json');
        }
    });
    
    $('input#tour_id', parent).change(function(event) {
        var tour_id = $(this).val();
        if(typeof tour_id != 'undefined' && parseInt(tour_id) > 0){
            $('#overlay', parent).addClass('active');
            data = {
                action: 'st_getInfoTour',
                tour_id: tour_id
            };
            $.post(st_params.ajax_url, data, function(respon, textStatus, xhr) {
                $('#overlay', parent).removeClass('active');
                if(typeof respon == 'object'){
                    $('#type-tour-wrapper', parent).html(respon.type_tour);
                    $('input#max_people', parent).val(respon.max_people);
                    $('input#adult_price', parent).val(respon.adult_price);
                    $('input#child_price', parent).val(respon.child_price);
                    $('input#infant_price', parent).val(respon.infant_price);

                    $('#adult-wrapper', parent).html(respon.adult_html);
                    $('#child-wrapper', parent).html(respon.child_html);
                    $('#infant-wrapper', parent).html(respon.infant_html);
                }
            }, 'json');
        }

    });

    $('input#activity_id', parent).change(function(event) {
        var activity_id = $(this).val();
        if(typeof activity_id != 'undefined' && parseInt(activity_id) > 0){
            $('#overlay', parent).addClass('active');
            data = {
                action: 'st_getInfoActivity',
                activity_id: activity_id
            };
            $.post(st_params.ajax_url, data, function(respon, textStatus, xhr) {
                $('#overlay', parent).removeClass('active');
                if(typeof respon == 'object'){
                    if(respon.activity_text == 'daily_activity'){
                        $('input#check_out_activity', parent).attr('data-duration', respon.duration);
                        check_in.on('changeDate', function (e) {
                            var new_date = e.date;
                            new_date.setDate(new_date.getDate() + parseInt(respon.duration));
                            check_out.datepicker('setDates', new_date);
                        });
                        check_out.on('show', function (e) {
                            check_out.datepicker('hide');
                        });
                    }else{
                        var date_in = new Date(respon.check_in);
                        var date_out = new Date(respon.check_out);
                        check_in.datepicker('setDates', date_in);
                        check_out.datepicker('setDates', date_out);
                        check_in.on('show', function (e) {
                            check_in.datepicker('hide');
                        });
                        check_out.on('show', function (e) {
                            check_out.datepicker('hide');
                        });
                    }

                    $('#type-activity-wrapper', parent).html(respon.type_activity);
                    $('input#max_people', parent).val(respon.max_people);
                    $('input#adult_price', parent).val(respon.adult_price);
                    $('input#child_price', parent).val(respon.child_price);
                    $('input#infant_price', parent).val(respon.infant_price);

                    $('#adult-wrapper', parent).html(respon.adult_html);
                    $('#child-wrapper', parent).html(respon.child_html);
                    $('#infant-wrapper', parent).html(respon.infant_html);

                }
            }, 'json');
        }

    });

    var list_selected_equipment = [];
    $('input#car_id', parent).change(function(event) {
        var car_id = $(this).val();
        if(typeof car_id != 'undefined' && parseInt(car_id) > 0){
            $('#overlay', parent).addClass('active');
            data = {
                action: 'st_getInfoCarPartner',
                car_id: car_id
            };
            $.post(st_params.ajax_url, data, function(respon, textStatus, xhr) {
                $('#overlay', parent).removeClass('active');
                if(typeof respon == 'object'){
                    $('input#item_price', parent).val(respon.price);
                    $('#equipments-price-wrapper', parent).html(respon.item_equipment);
                    $(parent).iCheck({
                        checkboxClass: 'i-check',
                        radioClass: 'i-radio'
                    });
                    $('input.list_equipment', parent).on('ifChanged', function (event) {
                        if($(this).prop('checked') == true){
                            list_selected_equipment.push({
                                title: $(this).attr('data-title'),
                                price: str2num($(this).attr('data-price')),
                                price_unit: $(this).data('price-unit'),
                                price_max: $(this).data('price-max')
                            });
                        }
                        $('input#selected_equipments',parent).val(JSON.stringify(list_selected_equipment));
                    });
                    
                }
            }, 'json');
        }    
    });
    
    function str2num(val) {
        val = '0' + val;
        val = parseFloat(val);
        return val;
    }

    // Booking button
    var flag = false;
    var form_validate = true;
    $('#partner-booking-button', parent).click(function(event) {

        event.preventDefault();
        $('input.required,select.required,textarea.required', parent).removeClass('error');

        $('input.required,select.required,textarea.required', parent).each(function(){
           if(!$(this).val()){
               $(this).addClass('error');
               form_validate = false;
           }
        });
        
        if(typeof form_validate == 'undefined' || form_validate == false){
            $('.form_alert', parent).addClass('alert-danger').removeClass('hidden');
            $('.form_alert', parent).html(st_checkout_text.validate_form);
            return false;
        }
        
        var data = parent.serializeArray();
        $('.form_alert', parent).removeClass('alert-danger').addClass('hidden').html('');
        if(typeof data == 'object'){
            if(flag) return false; flag = true;
            $('#overlay', parent).addClass('active');
            $.ajax({
                url: st_params.ajax_url,
                type: 'POST',
                data: data,
                dataType : 'json'
            })
            .done(function(respon) {
                if(respon.message){
                    $('.form_alert', parent).addClass('alert-danger').removeClass('hidden');
                    $('.form_alert', parent).html(respon.message);
                }

                if(respon.redirect){
                    window.location.href = respon.redirect;
                }
            })
            .fail(function() {
                console.log("error");
            })
            .always(function() {
                $('#overlay', parent).removeClass('active');
                console.log("complete");
                flag = false;
            });
            
        }
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
                        }
                    }
                },
                header : {
                    left:   'today,reloadButton',
                    center: 'title',
                    right:  'prev, next'
                },
                selectable: true,
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
                            tour_id: $('input#tour_id').val(),
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
                    
                },
                eventMouseover: function(event, jsEvent, view){
                    $('.event-number-'+event.start.unix()).addClass('hover');
                },
                eventMouseout: function(event, jsEvent, view){
                    $('.event-number-'+event.start.unix()).removeClass('hover');
                },
                eventRender: function(event, element, view){
                    var html = '';
                    if(event.status == 'available'){
                        html += '<div class="price">Adult: '+event.adult_price+'</div>';
                        html += '<div class="price">Child: '+event.child_price+'</div>';
                        html += '<div class="price">Infant: '+event.infant_price+'</div>';
                        $(element).addClass('bgr-main');
                    }
                    element.addClass('event-'+event.id)
                    $('.fc-content', element).html(html);

                    element.addClass('event-number-'+event.start.unix());
                    element.bind('dblclick', function() {
                        $('input#check_in_tour').val(event.start._i);
                        if(typeof event.end != 'undefined' && event.end && typeof event.end._i != 'undefined'){
                                date = new Date(event.end._i);
                                date.setDate(date.getDate() - 1);
                                date = $.fullCalendar.moment(date).format("YYYY-MM-DD");
                            $('input#check_out_tour').val(date).parents('.form-group').show();
                        }else{
                            $('input#check_out_tour').val(event.start._i).parents('.form-group').hide();
                        }
                        $('input#adult_price').val(event.adult_price);
                        $('input#child_price').val(event.child_price);
                        $('input#infant_price').val(event.infant_price);
                        $('#tour_time').qtip('hide');
                    });
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
    $('input#check_out_tour').parents('.form-group').hide();
    
    if($('#tour_time').length){
        $('#tour_time').qtip({
            content: {
                text: $('#tour_time_content').html()
            },
            show: { 
                when: 'click', 
                solo: true // Only show one tooltip at a time
            },
            hide: 'unfocus',
            api :{
                onShow : function(){
                    $('.calendar-wrapper').each(function(index, el) {
                        var t = $(this);
                        var tour = new TourCalendar(t);
                        tour.init();
                    });
                }    
            }
        });
    }
    
});