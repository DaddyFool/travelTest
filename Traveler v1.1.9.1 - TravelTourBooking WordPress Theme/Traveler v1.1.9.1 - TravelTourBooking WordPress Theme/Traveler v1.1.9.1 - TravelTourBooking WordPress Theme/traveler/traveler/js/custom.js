jQuery(document).ready(function ($) {

    "use strict";

    var $title_menu = $('ul.slimmenu').data('title');
    $('ul.slimmenu').slimmenu({
        resizeWidth: '992',
        collapserTitle: $title_menu,
        animSpeed: 250,
        indentChildren: true,
        childrenIndenter: ''
    });


    // Countdown
    $('.countdown').each(function () {
        var count = $(this);
        $(this).countdown({
            zeroCallback: function (options) {
                var newDate = new Date(),
                    newDate = newDate.setHours(newDate.getHours() + 130);

                $(count).attr("data-countdown", newDate);
                $(count).countdown({
                    unixFormat: true
                });
            }
        });
    });


    $('.btn').button();

    $("[rel='tooltip']").tooltip();

    $('.form-group').each(function () {
        var self = $(this),
            input = self.find('input');

        input.focus(function () {
            self.addClass('form-group-focus');
        })

        input.blur(function () {
            if (input.val()) {
                self.addClass('form-group-filled');
            } else {
                self.removeClass('form-group-filled');
            }
            self.removeClass('form-group-focus');
        });
    });

    var st_country_drop_off_address = '';
    $('.typeahead_drop_off_address').typeahead({
        hint: true,
        highlight: true,
        minLength: 3,
        limit: 8
    }, {
        source: function (q, cb) {
            console.log(st_country_drop_off_address);
            if (st_country_drop_off_address.length > 0) {
                return $.ajax({
                    dataType: 'json',
                    type: 'get',
                    url: 'http://gd.geobytes.com/AutoCompleteCity?callback=?&filter=' + st_country_drop_off_address + '&q=' + q,
                    chache: false,
                    success: function (data) {
                        var result = [];
                        $.each(data, function (index, val) {
                            result.push({
                                value: val
                            });
                        });
                        cb(result);
                    }
                });
            }
        }
    });
    $('.typeahead_pick_up_address').keyup(function () {
        $(".typeahead_drop_off_address").each(function () {
            $(this).attr('disabled', "disabled");
            $(this).css('background', "#eee");
            $(this).val("");
        });
    });

    $('.typeahead_pick_up_address').typeahead({
        hint: true,
        highlight: true,
        minLength: 3,
        limit: 8
    }, {
        source: function (q, cb) {
            return $.ajax({
                dataType: 'json',
                type: 'get',
                url: 'http://gd.geobytes.com/AutoCompleteCity?callback=?&q=' + q,
                chache: false,
                success: function (data) {
                    var result = [];
                    $.each(data, function (index, val) {
                        result.push({
                            value: val
                        });
                    });
                    cb(result);
                }
            });
        }
    });
    $('.typeahead_pick_up_address').bind('typeahead:selected', function (obj, datum, name) {
        var cityfqcn = $(this).val();
        var $this = $(this);
        jQuery.getJSON(
            "http://gd.geobytes.com/GetCityDetails?callback=?&fqcn=" + cityfqcn,
            function (data) {
                $this.attr('data-country', data.geobytesinternet);
                st_country_drop_off_address = data.geobytesinternet;
                console.log(st_country_drop_off_address);
                $(".typeahead_drop_off_address").each(function () {
                    $(this).removeAttr('disabled');
                    $(this).css('background', "#fff");
                });
            }
        );
    });

    $('.typeahead_pick_up_address').each(function () {
        var cityfqcn = $(this).val();
        var $this = $(this);
        if (cityfqcn.length > 0) {
            jQuery.getJSON(
                "http://gd.geobytes.com/GetCityDetails?callback=?&fqcn=" + cityfqcn,
                function (data) {
                    $this.attr('data-country', data.geobytesinternet);
                    st_country_drop_off_address = data.geobytesinternet;
                    console.log(st_country_drop_off_address);
                }
            );
        }
    });
    $('.county_pick_up').each(function () {
        var cityfqcn = $(this).data("address");
        var $this = $(this);
        if (cityfqcn.length > 0) {
            jQuery.getJSON(
                "http://gd.geobytes.com/GetCityDetails?callback=?&fqcn=" + cityfqcn,
                function (data) {
                    $this.val(data.geobytesinternet);
                }
            );
        }
    });
    $('.county_drop_off').each(function () {
        var cityfqcn = $(this).data("address");
        var $this = $(this);
        if (cityfqcn.length > 0) {
            jQuery.getJSON(
                "http://gd.geobytes.com/GetCityDetails?callback=?&fqcn=" + cityfqcn,
                function (data) {
                    $this.val(data.geobytesinternet);
                }
            );
        }
    });


    $('.typeahead_address').typeahead({
        hint: true,
        highlight: true,
        minLength: 3,
        limit: 8
    }, {
        source: function (q, cb) {
            return $.ajax({
                dataType: 'json',
                type: 'get',
                url: 'http://gd.geobytes.com/AutoCompleteCity?callback=?&q=' + q,
                chache: false,
                success: function (data) {
                    var result = [];
                    $.each(data, function (index, val) {
                        result.push({
                            value: val
                        });
                    });
                    cb(result);
                }
            });
        }
    });


    $('.typeahead').typeahead({
        hint: true,
        highlight: true,
        minLength: 3,
        limit: 8
    }, {
        source: function (q, cb) {
            return $.ajax({
                dataType: 'json',
                type: 'get',
                url: 'http://gd.geobytes.com/AutoCompleteCity?callback=?&q=' + q,
                chache: false,
                success: function (data) {
                    var result = [];
                    $.each(data, function (index, val) {
                        result.push({
                            value: val
                        });
                    });
                    cb(result);
                }
            });
        }
    });

    $('.typeahead_location').typeahead({
            hint: true,
            highlight: true,
            minLength: 3,
            limit: 8
        },
        {
            source: function (q, cb) {
                return $.ajax({
                    dataType: 'json',
                    type: 'get',
                    url: st_params.ajax_url,
                    data: {
                        security: st_params.st_search_nonce,
                        action: 'st_search_location',
                        s: q
                    },
                    cache: true,
                    success: function (data) {
                        var result = [];
                        if (data.data) {
                            $.each(data.data, function (index, val) {
                                result.push({
                                    value: val.title,
                                    location_id: val.id,
                                    type_color: 'success',
                                    type: val.type
                                });
                            });
                            cb(result);
                        }

                    }
                });
            },
            templates: {
                suggestion: Handlebars.compile('<p><label class="label label-{{type_color}}">{{type}}</label><strong> {{value}}</strong></p>')
            }
        });
    $('.typeahead_location').bind('typeahead:selected', function (obj, datum, name) {
        var parent = $(this).parents('.form-group');
        parent.find('.location_id').val(datum.location_id);
    });
    $('.typeahead_location').keyup(function () {
        var parent = $(this).parents('.form-group');
        parent.find('.location_id').val('');
    });

    $('input.date-pick, .date-pick-inline').datepicker({
        todayHighlight: true,
        weekStart: 1,
    }).on('changeDate', function (ev) {
        $(this).datepicker('hide');
    });


    $('.input-daterange input[name="start"]').each(function () {

        var form = $(this).closest('form');

        var me = $(this);

        $(this).datepicker({
            language:st_params.locale,
            autoclose: true,
            todayHighlight: true,
            startDate: 'today',
            format: $('[data-date-format]').data('date-format'),
            weekStart: 1,
        }).on('changeDate', function (e) {

                var new_date = e.date;
                new_date.setDate(new_date.getDate() + 1);
                $('.input-daterange input[name="end"]', form).datepicker('setDates', new_date);
                $('.input-daterange input[name="end"]', form).datepicker('setStartDate', new_date);
            }
        );

        $('.input-daterange input[name="end"]', form).datepicker({
            language:st_params.locale,
            startDate: '+1d',
            format: $('[data-date-format]').data('date-format'),
            autoclose: true,
            todayHighlight: true,
            weekStart: 1
        });
    })

    $('.pick-up-date').each(function () {
        var form = $(this).closest('form');
        var me = $(this);
        $(this).datepicker({
            language:st_params.locale,
            startDate: 'today',
            format: $('[data-date-format]').data('date-format'),
            todayHighlight: true,
            autoclose: true,
            weekStart: 1
        });
        $(this).on('changeDate', function (e) {
                var new_date = e.date;
                new_date.setDate(new_date.getDate());
                $('.drop-off-date', form).datepicker('setDates', new_date);
                $('.drop-off-date', form).datepicker('setStartDate', new_date);
            }
        );

        $('.drop-off-date', form).datepicker({
            language:st_params.locale,
            startDate: 'today',
            todayHighlight: true,
            autoclose: true,
            format: $('[data-date-format]').data('date-format'),
            weekStart: 1
        });
    })

    if($('.tour_book_date').length >0 && $('.tour_book_date').val().length > 0){
        $('.tour_book_date').datepicker(
            'setStartDate', 'today'
        );
        $('.tour_book_date').datepicker(
            'setDates', $('.tour_book_date').val()
        );
    }else{
        $('.tour_book_date').datepicker(
            'setStartDate', 'today'
        );
        $('.tour_book_date').datepicker(
            'setDates', 'today'
        );
    }

    var time_picker_arg={
        minuteStep: 15,
        showInpunts: false,
        defaultTime:false
    };
    if(st_params.time_format == '12h'){
        time_picker_arg.showMeridian = true;
    }else{
        time_picker_arg.showMeridian=false;
    }

    $('input.time-pick').timepicker(time_picker_arg);

    $('input.date-pick-years').datepicker({
        startView: 2,
        weekStart: 1
    });


    $('.booking-item-price-calc .checkbox label').click(function () {
        var checkbox = $(this).find('input'),
        // checked = $(checkboxDiv).hasClass('checked'),
            checked = $(checkbox).prop('checked'),
            price = parseInt($(this).find('span.pull-right').html().replace('$', '')),
            eqPrice = $('#car-equipment-total'),
            tPrice = $('#car-total'),
            eqPriceInt = parseInt(eqPrice.attr('data-value')),
            tPriceInt = parseInt(tPrice.attr('data-value')),
            value,
            animateInt = function (val, el, plus) {
                value = function () {
                    if (plus) {
                        return el.attr('data-value', val + price);
                    } else {
                        return el.attr('data-value', val - price);
                    }
                };
                return $({
                    val: val
                }).animate({
                    val: parseInt(value().attr('data-value'))
                }, {
                    duration: 500,
                    easing: 'swing',
                    step: function () {
                        if (plus) {
                            el.text(Math.ceil(this.val));
                        } else {
                            el.text(Math.floor(this.val));
                        }
                    }
                });
            };
        if (!checked) {
            animateInt(eqPriceInt, eqPrice, true);
            animateInt(tPriceInt, tPrice, true);
        } else {
            animateInt(eqPriceInt, eqPrice, false);
            animateInt(tPriceInt, tPrice, false);
        }
    });


    $('div.bg-parallax').each(function () {
        var $obj = $(this);
        if ($(window).width() > 992) {
            $(window).scroll(function () {
                var animSpeed;
                if ($obj.hasClass('bg-blur')) {
                    animSpeed = 10;
                } else {
                    animSpeed = 15;
                }
                var yPos = -($(window).scrollTop() / animSpeed);
                var bgpos = '50% ' + yPos + 'px';
                $obj.css('background-position', bgpos);

            });
        }
    });


    $(document).ready(
        function () {
            // Owl Carousel
            var owlCarousel = $('#owl-carousel'),
                owlItems = owlCarousel.attr('data-items'),
                owlCarouselSlider = $('#owl-carousel-slider, .owl-carousel-slider'),
                owlCarouselEffect = $('#owl-carousel-slider, .owl-carousel-slider').data('effect'),
                owlNav = owlCarouselSlider.attr('data-nav');
            // owlSliderPagination = owlCarouselSlider.attr('data-pagination');

            owlCarousel.owlCarousel({
                items: owlItems,
                navigation: true,
                navigationText: ['', '']
            });

            owlCarouselSlider.owlCarousel({
                slideSpeed: 300,
                paginationSpeed: 400,
                // pagination: owlSliderPagination,
                singleItem: true,
                navigation: true,
                pagination: false,
                navigationText: ['', ''],
                transitionStyle: owlCarouselEffect,
                autoPlay: 4500
            });             
            
            if($('#main-footer').length){
                // footer always on bottom
                var docHeight = $(window).height();
                var footerHeight = $('#main-footer').height();
                var footerTop = $('#main-footer').position().top + footerHeight;

                if (footerTop < docHeight) {
                    $('#main-footer').css('margin-top', (docHeight - footerTop) + 'px');
                }
            }

        }
    );
    fix_slider_height();
    fix_slider_height_testimonial();

    var flag_resize;
    $(window).resize(function(){
        clearTimeout(flag_resize);
        flag_resize = setTimeout(function(){
            fix_slider_height();
            fix_slider_height_testimonial();
        }, 500);

    }).resize();
    function fix_slider_height(){
        if ($("#owl-carousel-slider").length ==0) {return ; }
        // if search tab => full height
        // if text center => 1. full height 2. half height
        if ($(".bg-front .search-tabs").length !=0){
            var need_height = $(".bg-front .search-tabs").outerHeight(true) + 20;
            //console.log(need_height);
            if($('#tagline').length !=0){
                need_height += $('#tagline').outerHeight(true) + ($('#tagline').outerHeight(true) - $('#tagline').outerHeight());
            }
            console.log($('#tagline').outerHeight());
            $(".top-area").height(need_height);
        }else {
            var elem_height = $(window).height() - $("#st_header_wrap").height() ;
            var elem_height_2=  0.5*$(window).height();
            if ($(".top-area").length !=0) {
                $(".top-area").height(elem_height);
            }
            if ($(".special-area").length !=0) {
                $(".special-area").height(elem_height_2);
            }
        }
        console.log(need_height);
    }
    function fix_slider_height_testimonial(){        
        if ($(".top-area.is_form #slide-testimonial").length !=0){
            var s_h = $(".search-tabs").height() + parseInt($(".search-tabs").css("top") ,10) + 20 + 35;   
            $(".top-area.is_form").height(s_h);
        }
    }
    $("#myTab a[data-toggle='tab']").on('shown.bs.tab', function (e) {            
            e.target;            
            if ($(".st-slider-location").length >0) {
                var s_h = $(".search-tabs").outerHeight(true) + 20;
                $(".top-area").height(s_h);
            }
            if ($("#slide-testimonial").length >0) { 
                var s_h = $(".search-tabs").height() + parseInt($(".search-tabs").css("top") ,10) + 20; 
                $(".top-area").height(s_h);
            }
            fix_slider_height();
            
     });
    $(document).ready(function () {
        $('#slide-testimonial').each(function () {
            var $this = $(this);
            $this.owlCarousel({
                slideSpeed: $(this).attr('data-speed'),
                paginationSpeed: 400,
                pagination : false,
                itemsCustom: [[0, 1], [400, 1] ,[768,1], [1024 , 1]],
                navigation: $(this).data('data-navigation'),
                navigationText: ['', ''],
                transitionStyle: $(this).data('effect'),
                autoPlay: $this.attr('data-play')
            });
        })
    });


    $('.nav-drop').click(function () {
        if ($(this).hasClass('active-drop')) {
            $(this).removeClass('active-drop');
        } else {
            $('.nav-drop').removeClass('active-drop');
            $(this).addClass('active-drop');

        }
    });


    $(document).mouseup(function (e) {
        var container = $(".nav-drop");

        if (!container.is(e.target) // if the target of the click isn't the container...
            && container.has(e.target).length === 0) // ... nor a descendant of the container
        {
            $('.nav-drop').removeClass('active-drop');
        }
    });
    $(".range-slider").each(function () {
        var min = $(this).data('min');
        var max = $(this).data('max');
        var step = $(this).data('step');
        $(this).ionRangeSlider({
            min: min,
            max: max,
            from: max,
            step: step,
            grid: false,
            grid_snap: true,
            postfix:" km"
        });

    });
    $(".price-slider").each(function () {
        var min = $(this).data('min');
        var max = $(this).data('max');
        var step = $(this).data('step');

        var value = $(this).val();

        var from = value.split(';');

        var prefix_symbol = $(this).data('symbol');

        var to = from[1]
        from = from[0];

        var arg = {
            min: min,
            max: max,
            type: 'double',
            prefix: prefix_symbol,
            // maxPostfix: "+",
            prettify: false,
            grid: true,
            step: step,
            grid_snap: true,
            onFinish: function (data) {
                console.log(data);
                set_price_range_val(data, $('input[name="price_range"]'));
                //console.log(data);
                //console.log(window.location.href);
            },
            from: from,
            to: to
        };

        //postfix
        if(st_params.currency_rtl_support=='on')
        {
            delete arg.prefix;
            arg.postfix=prefix_symbol;
        }

        if (!step) {
            delete arg.step;
            delete arg.grid_snap;
        }

        //console.log(arg);

        //console.log(min);
        $(this).ionRangeSlider(arg);
    });
    function set_price_range_val(data , element){
        var exchange = 1;
        var from = Math.round(parseInt(data.from)/exchange) ;
        var to = Math.round(parseInt(data.to)/exchange) ;
        var text = from + ";" + to;

        element.val(text);
    }
    $("#price-slider").ionRangeSlider({
        min: 130,
        max: 575,
        type: 'double',
        prefix: "$",
        // maxPostfix: "+",
        prettify: false,
        grid: true
    });

    $('.i-check, .i-radio').iCheck({
        checkboxClass: 'i-check',
        radioClass: 'i-radio'
    });


    $('.booking-item-review-expand').click(function (event) {
        var parent = $(this).parent('.booking-item-review-content');
        if (parent.hasClass('expanded')) {
            parent.removeClass('expanded');
        } else {
            parent.addClass('expanded');
        }
    });
    $('.expand_search_box').click(function (event) {
        var parent = $(this).parent('.search_advance');
        if (parent.hasClass('expanded')) {
            parent.removeClass('expanded');
        } else {
            parent.addClass('expanded');
        }
    });


    $('.stats-list-select > li > .booking-item-rating-stars > li').each(function () {
        var list = $(this).parent(),
            listItems = list.children(),
            itemIndex = $(this).index(),
            parentItem = list.parent();

        $(this).hover(function () {
            for (var i = 0; i < listItems.length; i++) {
                if (i <= itemIndex) {
                    $(listItems[i]).addClass('hovered');
                } else {
                    break;
                }
            }
            ;
            $(this).click(function () {
                for (var i = 0; i < listItems.length; i++) {
                    if (i <= itemIndex) {
                        $(listItems[i]).addClass('selected');
                    } else {
                        $(listItems[i]).removeClass('selected');
                    }
                }
                ;

                parentItem.children('.st_review_stats').val(itemIndex + 1);

            });
        }, function () {
            listItems.removeClass('hovered');
        });
    });


    $('.booking-item-container').children('.booking-item').click(function (event) {
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
            $(this).parent().removeClass('active');
        } else {
            $(this).addClass('active');
            $(this).parent().addClass('active');
            $(this).delay(1500).queue(function () {
                $(this).addClass('viewed')
            });
        }
    });


    //$('.form-group-cc-number input').payment('formatCardNumber');
    //$('.form-group-cc-date input').payment('formatCardExpiry');
    //$('.form-group-cc-cvc input').payment('formatCardCVC');


    if ($('#map-canvas').length) {
        var map,
            service;
        var default_lat = 40.7564971; 
        var default_long = -73.9743277 ; 
        if ($("#google-map-tab").attr('data-lat') && $("#google-map-tab").attr('data-long')){
            default_lat = ($("#google-map-tab").attr('data-lat'));
            default_long = ($("#google-map-tab").attr('data-long'));
        }
        jQuery(function ($) {
            $(document).ready(function () {
                var latlng = new google.maps.LatLng(default_lat, default_long);
                var myOptions = {
                    zoom: 16,
                    center: latlng,
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    scrollwheel: false
                };

                map = new google.maps.Map(document.getElementById("map-canvas"), myOptions);


                var marker = new google.maps.Marker({
                    position: latlng,
                    map: map
                });
                marker.setMap(map);


                $('a[href="#google-map-tab"]').on('shown.bs.tab', function (e) {
                    google.maps.event.trigger(map, 'resize');
                    map.setCenter(latlng);
                });
            });
        });
    }


    $('.card-select > li').click(function () {
        var self = this;
        $(self).addClass('card-item-selected');
        $(self).siblings('li').removeClass('card-item-selected');
        $('.form-group-cc-number input').click(function () {
            $(self).removeClass('card-item-selected');
        });
    });
    // Lighbox gallery
    $('#popup-gallery').each(function () {
        $(this).magnificPopup({
            delegate: 'a.popup-gallery-image',
            type: 'image',
            gallery: {
                enabled: true
            }
        });
    });

    $('.st-popup-gallery').each(function () {
        $(this).magnificPopup({
            delegate: '.st-gp-item',
            type: 'image',
            gallery: {
                enabled: true
            }
        });
    });

    // Lighbox image
    $('.popup-image').magnificPopup({
        type: 'image'
    });

    // Lighbox text
    $('.popup-text').magnificPopup({
        removalDelay: 500,
        closeBtnInside: true,
        callbacks: {
            beforeOpen: function () {
                this.st.mainClass = this.st.el.attr('data-effect');
            }
        },
        midClick: true
    });

    // Lightbox iframe
    $('.popup-iframe').magnificPopup({
        dispableOn: 700,
        type: 'iframe',
        removalDelay: 160,
        mainClass: 'mfp-fade',
        preloader: false
    });


    $('.form-group-select-plus').each(function () {
        var self = $(this),
            btnGroup = self.find('.btn-group').first(),
            select = self.find('select');
        btnGroup.children('label').last().click(function () {
            btnGroup.addClass('hidden');
            select.removeClass('hidden');
        });
        btnGroup.children('label').click(function () {
            var c = $(this);
            select.find('option[value=' + c.children('input').val() + ']').prop('selected', 'selected');
            if (!c.hasClass('active'))
                select.trigger('change');
        });
    });
    // Responsive videos
    $(document).ready(function () {
        //$("body").fitVids();
    });

    //$(function($) {
    //    $("#twitter").tweet({
    //        username: "remtsoy", //!paste here your twitter username!
    //        count: 3
    //    });
    //});

    //$(function($) {
    //    $("#twitter-ticker").tweet({
    //        username: "remtsoy", //!paste here your twitter username!
    //        page: 1,
    //        count: 20
    //    });
    //});

    $(document).ready(function () {
        var ul = $('#twitter-ticker').find(".tweet-list");
        var ticker = function () {
            setTimeout(function () {
                ul.find('li:first').animate({
                    marginTop: '-4.7em'
                }, 850, function () {
                    $(this).detach().appendTo(ul).removeAttr('style');
                });
                ticker();
            }, 5000);
        };
        ticker();
    });
    $(function () {

        $('.ri-grid').each(function () {
            var $girl_ri = $(this);
            if ($.fn.gridrotator !== undefined) {
                $girl_ri.gridrotator({
                    rows: $girl_ri.attr('data-row'),
                    columns: $girl_ri.attr('data-col'),
                    animType: 'random',
                    animSpeed: 1200,
                    interval: $girl_ri.attr('data-speed'),
                    step: 'random',
                    preventClick: false,
                    maxStep: 2,
                    w992: {
                        rows: 5,
                        columns: 4
                    },
                    w768: {
                        rows: 6,
                        columns: 3
                    },
                    w480: {
                        rows: 8,
                        columns: 3
                    },
                    w320: {
                        rows: 8,
                        columns: 2
                    },
                    w240: {
                        rows: 6,
                        columns: 4
                    }
                });
            }
        });
    });


    $(function () {
        if ($.fn.gridrotator !== undefined) {
            $('#ri-grid-no-animation').gridrotator({
                rows: 4,
                columns: 8,
                slideshow: false,
                w1024: {
                    rows: 4,
                    columns: 6
                },
                w768: {
                    rows: 3,
                    columns: 3
                },
                w480: {
                    rows: 4,
                    columns: 4
                },
                w320: {
                    rows: 5,
                    columns: 4
                },
                w240: {
                    rows: 6,
                    columns: 4
                }
            });
        }

    });

    var tid = setInterval(tagline_vertical_slide, 2500);

    // vertical slide
    function tagline_vertical_slide() {
        $('.div_tagline').each(function () {
            var curr = $(this).find(".tagline ul li.active");
            curr.removeClass("active").addClass("vs-out");
            setTimeout(function () {
                curr.removeClass("vs-out");
            }, 500);

            var nextTag = curr.next('li');
            if (!nextTag.length) {
                nextTag = $(this).find(".tagline ul li").first();
            }
            nextTag.addClass("active");
        });

    }

    function abortTimer() { // to be called when you want to stop the timer
        clearInterval(tid);
    }

    $('#submit').addClass('btn btn-primary');


    //Button Like Review
    $('.st-like-review').click(function (e) {

        e.preventDefault();

        var me = $(this);


        if (!me.hasClass('loading')) {
            var comment_id = me.data('id');
            var loading = $('<i class="loading_icon fa fa-spinner fa-spin"></i>');

            me.addClass('loading');
            me.before(loading);

            $.ajax({

                url: st_params.ajax_url,
                type: 'post',
                dataType: 'json',
                data: {
                    action: 'like_review',
                    comment_ID: comment_id
                },
                success: function (res) {
                    if (res.status) {
                        if (res.data.like_status) {
                            me.addClass('fa-thumbs-o-down').removeClass('fa-thumbs-o-up');
                        } else {
                            me.addClass('fa-thumbs-o-up').removeClass('fa-thumbs-o-down');
                        }

                        if (typeof res.data.like_count != undefined) {
                            res.data.like_count = parseInt(res.data.like_count);
                            me.parent().find('.text-color .number').html(' ' + res.data.like_count);
                        }
                    } else {
                        if (res.error.error_message) {
                            alert(res.error.error_message);
                        }
                    }
                    me.removeClass('loading');
                    loading.remove();
                },
                error: function (res) {
                    console.log(res);
                    alert('Ajax Faild');
                    me.removeClass('loading');
                    loading.remove();
                }
            });
        }


    });

    //Button Like Review
    $('.st-like-comment').click(function (e) {

        e.preventDefault();

        var me = $(this);


        if (!me.hasClass('loading')) {
            var comment_id = me.data('id');
            var loading = $('<i class="loading_icon fa fa-spinner fa-spin"></i>');

            me.addClass('loading');
            me.before(loading);

            $.ajax({

                url: st_params.ajax_url,
                type: 'post',
                dataType: 'json',
                data: {
                    action: 'like_review',
                    comment_ID: comment_id
                },
                success: function (res) {
                    console.log(res);
                    if (res.status) {
                        if (res.data.like_status) {
                            me.addClass('fa-heart').removeClass('fa-heart-o');
                        } else {
                            me.addClass('fa-heart-o').removeClass('fa-heart');
                        }

                        if (typeof res.data.like_count != undefined) {
                            res.data.like_count = parseInt(res.data.like_count);
                            me.next('.text-color').html(' ' + res.data.like_count);
                        }
                    } else {
                        if (res.error.error_message) {
                            alert(res.error.error_message);
                        }
                    }
                    me.removeClass('loading');
                    loading.remove();
                },
                error: function (res) {
                    console.log(res);
                    alert('Ajax Faild');
                    me.removeClass('loading');
                    loading.remove();
                }
            });
        }


    });


    // vc-element cars
    $('.booking-item-price-calc .equipment').on('ifChanged', function (event) {
        var price_total_item = 0;
        var person_ob = new Object();
        var list_selected_equipment = [];
        var $total_price_equipment = 0;

        var $start_timestamp = $('.car_booking_form [name=check_in_timestamp]').val();
        var $end_timestamp = $('.car_booking_form [name=check_out_timestamp]').val();

        $('.singe_cars').find('.equipment').each(function (event) {
            if ($(this)[0].checked == true) {
                var price = str2num($(this).attr('data-price'));
                var price_max = str2num($(this).attr('data-price-max'));
                console.log(price);
                console.log(price_max);

                person_ob[$(this).attr('data-title')] = str2num($(this).attr('data-price'));
                price_total_item = price_total_item + str2num($(this).attr('data-price'));
                list_selected_equipment.push({
                    title: $(this).attr('data-title'),
                    price: str2num($(this).attr('data-price')),
                    price_unit: $(this).data('price-unit'),
                    price_max: $(this).data('price-max')
                });
                var item_price = get_amount_by_unit(str2num($(this).attr('data-price')), $(this).data('price-unit'), $start_timestamp, $end_timestamp);
                if(item_price > price_max && price_max > 0){
                    item_price = price_max;
                }
                console.log(item_price);
                console.log(list_selected_equipment);
                $total_price_equipment += item_price;
            }
        });
        //console.log($total_price_equipment);
        $('.data_price_items').val(JSON.stringify(person_ob));
        $('.st_selected_equipments').val(JSON.stringify(list_selected_equipment));

        var price_total = price_total_item + str2num($('.st_cars_price').attr('data-value'));



        var regular_price = $('.car_booking_form [name=price]').val();
        var price_time = $('.car_booking_form [name=time]').val();
        var price_unit = $('.car_booking_form [name=price_unit]').val();
        var price_rate = $('.car_booking_form [name=price_rate]').val();
        //console.log(price_unit);
        regular_price = parseFloat(regular_price);
        price_time=parseFloat(price_time);

        //var sub_total = get_amount_by_unit(regular_price, price_unit, $start_timestamp, $end_timestamp);
        var sub_total = $('.car_booking_form .st_cars_price').data('value');
       // console.log(sub_total);

        $('.st_data_car_equipment_total').html(format_money($total_price_equipment * price_rate));
        $('.st_data_car_total').html(format_money(($total_price_equipment + sub_total) * price_rate ));
        $('.data_price_total').val($total_price_equipment + sub_total);

    });

    function get_amount_by_unit($amount, $unit, $start_timestamp, $end_timestamp) {
        var time_diff,$hour_diff;
        var hour=time_diff = $end_timestamp - $start_timestamp;
        if (hour <= 0) {
            hour= 0;
        }else
        hour = Math.ceil(hour / 3600/24);

        if(st_single_car.check_booking_days_included){
            hour++;
        }
        switch ($unit) {
            case "day":
            case "per_day":
                $amount *= (hour);
                break;
            case "hour":
            case "per_hour":
                $hour_diff=Math.ceil(time_diff/3600)
                if(st_single_car.check_booking_days_included){
                    $hour_diff++;
                }

                $amount *=$hour_diff;
                break;
        }
        return $amount;
    }

    function format_money($money) {

        if (!$money) {
            return st_params.free_text;
        }
        //if (typeof st_params.booking_currency_precision && st_params.booking_currency_precision) {
        //    $money = Math.round($money).toFixed(st_params.booking_currency_precision);
        //}

        $money = st_number_format($money, st_params.booking_currency_precision,st_params.decimal_separator, st_params.thousand_separator );
        var $symbol = st_params.currency_symbol;
        var $money_string = '';

        switch (st_params.currency_position) {
            case "right":
                $money_string = $money + $symbol;
                break;
            case "left_space":
                $money_string = $symbol + " " + $money;
                break;

            case "right_space":
                $money_string = $money + " " + $symbol;
                break;
            case "left":
            default:
                $money_string = $symbol + $money;
                break;
        }

        return $money_string;
    }

    function st_number_format(number, decimals, dec_point, thousands_sep) {


        number = (number + '')
            .replace(/[^0-9+\-Ee.]/g, '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function (n, prec) {
                var k = Math.pow(10, prec);
                return '' + (Math.round(n * k) / k)
                        .toFixed(prec);
            };
        // Fix for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n))
            .split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '')
                .length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1)
                .join('0');
        }
        return s.join(dec);
    }

    function str2num(val) {
        val = '0' + val;
        val = parseFloat(val);
        return val;
    }

    $('.share li>a').click(function () {
        var href = $(this).attr('href');
        if (href && $(this).hasClass('no-open') == false) {


            popupwindow(href, '', 600, 600);
            return false;
        }
    });

    function popupwindow(url, title, w, h) {
        var left = (screen.width / 2) - (w / 2);
        var top = (screen.height / 2) - (h / 2);
        return window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
    }

    $('.social_login_nav_drop .login_social_link').click(function () {
            var href = $(this).attr('href');

            popupwindow(href, '', 600, 450);
            return false;
        }
    );

    $('.btn_show_year').click(function () {
        $('.head_control a').removeClass('active');
        $(this).addClass("active");
        $(".st_reports").show(1000);
    });
    if ($('.btn_show_year').hasClass('active')) {
        $(".st_reports").show(1000);
    }
    ;

    var activity_booking_form = $('.activity_booking_form');
    var message_box = $('.activity_booking_form .message_box');

    $('.activity_booking_form input[type=submit]').click(function () {
        if (validate_activity_booking()) {
            activity_booking_form.submit();
        } else {
            return false;
        }
    });
    activity_booking_form.find('.check_in').each(function(){
        $(this).datepicker(
            'setDates', 'today'
        );
    });
    function validate_activity_booking() {
        var form_validate = true;
        message_box.html('');
        message_box.removeClass('alert');
        var check_in = activity_booking_form.find('.check_in').val();
        var check_out = activity_booking_form.find('.check_out').val();
        try {
            if(check_in.length > 0 && check_out.length > 0 ){
                form_validate = true;
            }else{
                form_validate = false;
                message_box.html('<div class="alert alert-danger">'+st_hotel_localize.is_not_select_date+'</div>');
            }

        } catch (e) {
            console.log(e);
        }
        return form_validate;
    }

    //$('.bg-video').hide();
    setTimeout(function(){
        $('.bg-video').show().css('display','block');
    },2000)
    $(window).load(function(){
        $('.bg-video').show().css('display','block');
    });

});

// VC element filter
jQuery(document).ready(function ($) {
    $('.st-elements-filters input[type=checkbox]').on('ifClicked', function (event) {
        var url = $(this).data('url');
        if (url) {
            window.location.href = url;
        }
    });

    $('.form-custom-taxonomy .item_tanoxomy').on('ifClicked', function (event) {
        var $this = $(this);
        var $value = '';
        $this.parent().parent().parent().parent().find('.item_tanoxomy').each(function () {
            var $this2 = $(this);
            setTimeout(function () {
                if ($this2.prop('checked')) {
                    $value += $this2.val()+",";
                }
            }, 100)
        });
        setTimeout(function () {
            $this.parent().parent().parent().parent().find('.data_taxonomy').val($value);
            //$('.form-custom-taxonomy .data_taxonomy').val($value);
        }, 200)

    });


});

//List rental room
jQuery(document).ready(function ($) {
    $('.st_list_rental_room').owlCarousel({
        items: 4,
        navigation: true,
        navigationText: ['', ''],
        slideSpeed: 1000
    });
});
jQuery(window).load(function(){
        // fix safari video display
        window.setTimeout(function(){ 
            jQuery('.bg-video').css("display","table");
         }, 2000);
});
jQuery(function($){
    //.owl_carousel_style2 , .owl_carousel_style2 * {height: 100%;}
    if ($(".owl_carousel_style2").length >0){
        
        var h = $(window).height();
        if ($(".room_bgr_with_form").height()>0){
            h = $(".room_bgr_with_form").height();
        }
        var pos = $(".owl_carousel_style2").css("position")  ; 
        if (pos === "absolute"){
            h += $("#menu2").height();
        }
        
        $(".owl_carousel_style2").height(h);
    }
    if ($(window).width() > 1024 ){      
        var sheight = ($(window).height() - $(".form_bottom").height());
        //var sheight = $(window).height();
        $(".top-are-fix").height(sheight);
    }
    
});
/* woocommerce cart */
jQuery(document).ready(function ($) {
    $(document).on('click', '._show_wc_cart_item_information_btn', function(event) {
        event.preventDefault();
        var hide_content   = ($(this).attr('data-hide'));
        var content = $(this).html();
        $(this).attr({'data-hide': content});
        $(this).html(hide_content);
    });
});