jQuery(document).ready(function ($) {
    $(document).on('click', '.btn_add_wishlist', function (event) {
        var $this = $(this)
        $.ajax({
            url: st_params.ajax_url,
            type: "POST",
            data: {
                action: "st_add_wishlist",
                data_id: $(this).data('id'),
                data_type: $(this).data('type')
            },
            dataType: "json",
            beforeSend: function () {

            }
        }).done(function (html) {
            $this.html(html.icon).attr("data-original-title", html.title);
        });
    });

    $(document).on('click', '.btn_remove_wishlist', function (event) {
        var $this = $(this);
        $.ajax({
            url: st_params.ajax_url,
            type: "POST",
            data: {
                action: "st_remove_wishlist",
                data_id: $(this).data('id'),
                data_type: $(this).data('type')
            },
            dataType: "json",
            beforeSend: function () {
                $('.post-' + $this.attr('data-id') + ' .user_img_loading').show();
            }
        }).done(function (html) {
            if (html.status == 'true') {
                $('.post-' + html.msg).html(console_msg(html.type, html.content)).attr("data-original-title", html.title);
                ;
            } else {
                $('.post-' + html.msg).append(console_msg(html.type, html.content)).attr("data-original-title", html.title);
                ;
            }
        });
    });

    $('.btn_load_more_wishlist').click(function () {
        var $this = $(this);
        var txt_me = $this.html();
        $.ajax({
            url: st_params.ajax_url,
            type: "GET",
            data: {
                action: "st_load_more_wishlist",
                data_per: $('.btn_load_more_wishlist').attr('data-per'),
                data_next: $('.btn_load_more_wishlist').attr('data-next')
            },
            dataType: "json",
            beforeSend: function () {
                $this.html('Loading...');
            }
        }).done(function (html) {
            $this.html(txt_me);
            $('#data_whislist').append(html.msg);
            if (html.status == 'true') {
                console.log(html);
                $('.btn_load_more_wishlist').attr('data-per', html.data_per);
            } else {
                $('.btn_load_more_wishlist').attr('disabled', 'disabled');
                $('.btn_load_more_wishlist').html('No More');
            }

        });
    });

    $('#btn_add_media').click(function () {
        $('#my_image_upload').click();
    });
    $('#my_image_upload').change(function () {
        $('#submit_my_image_upload').click();
    });

    $('.btn_remove_post_type').click(function () {
        var $this = $(this);
        $.ajax({
            url: st_params.ajax_url,
            type: "POST",
            data: {
                action: "st_remove_post_type",
                data_id: $(this).attr('data-id'),
                data_id_user: $(this).attr('data-id-user')
            },
            dataType: "json",
            beforeSend: function () {
                $('.post-' + $this.attr('data-id') + ' .user_img_loading').show();
            }
        }).done(function (html) {
            console.log(html);
            if (html.status == 'true') {
                $('.post-' + html.msg).html(console_msg(html.type, html.content));
            } else {
                $('.post-' + html.msg).append(console_msg(html.type, html.content));
            }

        });
    });
    function console_msg(type, content) {
        var txt = '<div class="alert alert-' + type + '"> <button data-dismiss="alert" type="button" class="close"><span aria-hidden="true">�</span> </button> <p class="text-small">' + content + '</p> </div>';
        return txt;
    }

    $('#btn_check_insert_post_type_hotel').click(function () {
        var dk = true;
        if (dk == true) {
            console.log('Submit create hotel !');
            $('#btn_insert_post_type_hotel').click();
        }

    });
    /* Room */
    $('#btn_check_insert_post_type_room').click(function () {
        var dk = true;
        if (kt_rong('title', 'Warning : Room Name could not left empty') != true) {
            dk = false;
        }
        if (kt_chieudai('title', 'Warning : Room Name no shorter than 4 characters', 4) != true) {
            dk = false;
        }

        if (dk == true) {
            console.log('Submit create hotel !');
            $('#btn_insert_post_type_room').click();
        }
    });
    $(document).on('click', '.btn_del_price_custom', function () {
        $(this).parent().parent().remove();
    });

    $('#btn_add_custom_price').click(function () {
        var $item = $('.data_price_html').html();
        $('.content_data_price').append($item);
        $('input.date-pick, .input-daterange, .date-pick-inline').datepicker({
            todayHighlight: true
        });
    });
    $('#btn_add_custom_price_by_number').click(function () {
        var $item = $('.data_price_by_number_html').html();
        $('.content_data_price_by_number').append($item);
    });

    $('#btn_add_extra_price').click(function (event) {
        var $item = $('.data-extra-price-html').html();
        $('.content_extra_price').append($item);
    });
    $(document).on('click', '.btn_del_extra_price', function () {
        $(this).parents('.item').remove();
    });
    /* Tours */

    $('#btn_check_insert_post_type_tours').click(function () {
        var dk = true;
        if (dk == true) {
            console.log('Submit create Tours !');
            $('#btn_insert_post_type_tours').click();
        }
    });

    /* activity */
    $('#btn_check_insert_activity').click(function () {
        var dk = true;
        if (dk == true) {
            console.log('Submit create Activity !');
            $('#btn_insert_post_type_activity').click();
        }
    });

    /* Cars */
    $('#btn_check_insert_cars').click(function () {
        var dk = true;
        /*if(kt_rong('title','Warning : Title could not left empty')!=true){
         dk = false;
         }
         if(kt_chieudai('title','Warning : Title no shorter than 4 characters',4)!=true){
         dk = false;
         }*/
        if (dk == true) {
            console.log('Submit create Cars !');
            $('#btn_insert_post_type_cars').click();
        }
    });

    /* Rental */
    $('#btn_check_insert_post_type_rental').click(function () {
        var dk = true;
        /*if(kt_rong('title','Warning : Title could not left empty')!=true){
         dk = false;
         }
         if(kt_chieudai('title','Warning : Title no shorter than 4 characters',4)!=true){
         dk = false;
         }*/
        if (dk == true) {
            console.log('Submit create Rental !');
            $('#btn_insert_post_type_rental').click();
        }
    });

    /* Cruise */
    $('#btn_check_insert_post_type_cruise').click(function () {
        var dk = true;
        /*if(kt_rong('title','Warning : Title could not left empty')!=true){
         dk = false;
         }
         if(kt_chieudai('title','Warning : Title no shorter than 4 characters',4)!=true){
         dk = false;
         }*/
        if (dk == true) {
            console.log('Submit create cruise !');
            $('#btn_insert_post_type_cruise').click();
        }
    });

    /* Cruise Cabin */
    $('#btn_check_insert_cruise_cabin').click(function () {
        var dk = true;
        /* if(kt_rong('title','Warning : Title could not left empty')!=true){
         dk = false;
         }
         if(kt_chieudai('title','Warning : Title no shorter than 4 characters',4)!=true){
         dk = false;
         }*/
        if (dk == true) {
            console.log('Submit create cruise !');
            $('#btn_insert_cruise_cabin').click();
        }
    });

    /* Location */
    $('#btn_check_insert_post_type_location').click(function () {
        var dk = true;
        /*if(kt_rong('title','Warning : Title could not left empty')!=true){
         dk = false;
         }
         if(kt_chieudai('title','Warning : Title no shorter than 4 characters',4)!=true){
         dk = false;
         }*/
        if (dk == true) {
            console.log('Submit create location !');
            $('#btn_insert_post_type_location').click();
        }
    });

    function validate_fileupload(fileName, msg) {
        var allowed_extensions = new Array("jpg", "png", "gif");
        var file_extension = fileName.split('.').pop(); // split function will split the filename by dot(.), and pop function will pop the last element from the array which will give you the extension as well. If there will be no extension then it will return the filename.

        for (var i = 0; i <= allowed_extensions.length; i++) {
            if (allowed_extensions[i] == file_extension) {
                $('.msg').html('');
                return true; // valid file extension
            }
        }
        $('.msg').html('<div class="alert alert-danger msg_image"> <button aria-label="" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">�</span></button> <p>' + msg + '</p> </div>');
        return false;
    }

    function checkLinkUrl(div, thongbao) {
        var str = $('#' + div).val();
        var pattern = new RegExp('^(https?:\/\/)?' + // protocol
        '((([a-z\d]([a-z\d-]*[a-z\d])*)\.)+[a-z]{2,}|' + // domain name
        '((\d{1,3}\.){3}\d{1,3}))' + // OR ip (v4) address
        '(\:\d+)?(\/[-a-z\d%_.~+]*)*' + // port and path
        '(\?[;&a-z\d%_.~+=-]*)?' + // query string
        '(\#[-a-z\d_]*)?$', 'i'); // fragment locater
        if (!pattern.test(str)) {
            $('.console_msg_' + div).html(console_msg('danger', thongbao));
            $('#' + div).css('borderColor', "red");
            return false;
        } else {
            $('.console_msg_' + div).html('');
            $('#' + div).css('borderColor', "#C6DBE0");
            return true;
        }
    }

    function kt_rong(div, thongbao) {
        var value = $('#' + div).val();
        if (value == "" || value == null) {
            $('.console_msg_' + div).html(console_msg('danger', thongbao));
            $('#' + div).css('borderColor', "red");
            return false;
        } else {
            $('.console_msg_' + div).html('');
            $('#' + div).css('borderColor', "#C6DBE0");
            return true;
        }
    }

    function kt_chieudai(div, thongbao, chieudai) {
        var value = $('#' + div).val();
        if (value.length == chieudai || value.length < chieudai) {
            $('.console_msg_' + div).html(console_msg('danger', thongbao));
            $('#' + div).css('borderColor', "red");
            return false;
        } else {
            $('.console_msg_' + div).html('');
            $('#' + div).css('borderColor', "#C6DBE0")
            ;
            return true;
        }
    }

    function kt_so(div, thongbao) {
        var value = $('#' + div).val();
        if (isNaN(value) == true) {
            $('.console_msg_' + div).html(console_msg('danger', thongbao));
            $('#' + div).css('borderColor', "red");
            return false;
        } else {
            $('.console_msg_' + div).html('');
            $('#' + div).css('borderColor', "#C6DBE0");
            return true;
        }
    }

    function checkEmail(div, thongbao) {
        var value = $('#' + div).val();
        if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(value)) {
            $('.console_msg_' + div).html('');
            $('#' + div).css('borderColor', "#C6DBE0");
            return true;
        } else {
            $('.console_msg_' + div).html(console_msg('danger', thongbao));
            $('#' + div).css('borderColor', "red");
            return false;
        }
    }

    $(document).on('change', '.btn-file :file', function () {
        var input = $(this),
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.parent().parent().parent().find(".data_lable").val(label);

    });

    $(document).on('change', '.btn-file.multiple :file', function () {
        var $this = $(this);
        var files = $this[0].files;
        var txt = '';
        for (var i = 0; i < files.length; i++) {
            txt += files[i].name + " , ";
        }
        $this.parent().parent().parent().find(".data_lable").val(txt);
    });

    $('.btn_del_avatar').click(function () {
        $('#id_avatar_user_setting').val('');
        $('.data_lable').val('');
        $(this).parent().remove();
    });

    function str2num(val) {
        val = '0' + val;
        val = parseFloat(val);
        return val;
    }


    /* load more histtory book */
    $('.btn_load_his_book').click(function () {
        var $this = $(this);
        var txt_me = $this.html();
        $.ajax({
            url: st_params.ajax_url,
            type: "GET",
            data: {
                action: "st_load_more_history_book",
                paged: $this.attr('data-per'),
                show: "json",
                data_type: $this.attr('data-type')
            },
            dataType: "json",
            beforeSend: function () {
                $this.html('Loading...');
            }
        }).done(function (html) {
            $this.html(txt_me);

            if (html.status == 'true') {
                console.log(html);
                $this.attr('data-per', html.data_per);
                $this.parent().find('#data_history_book').append(html.html);
                //$('#data_history_book').append(html.html);
            } else {
                $this.attr('disabled', 'disabled');
                $this.html('No More');
            }

        });
    });

    $('#btn_add_program').click(function () {
        var html = $('#html_program').html();
        console.log(html);
        $('#data_program').append(html);
    });
    $('#btn_add_equipment_item').click(function () {
        var html = $('#html_equipment_item').html();
        console.log(html);
        $('#data_equipment_item').append(html);
    });

    $('#btn_add_features').click(function () {
        var html = $('#html_features').html();
        console.log(html);
        $('#data_features').append(html);
        /*$('.taxonomy_car').each(function() {
         var $this = $(this);
         var $select_index = $this.context.options.selectedIndex;
         var $icon = $($this.context.options[$select_index]).attr('data-icon');
         $this.parent().find('i').removeAttr('class').attr('class',$icon+' input-icon input-icon-hightlight');
         });*/

    });

    $('#btn_add_features_rental').click(function () {
        var html = $('#html_features_rental').html();
        console.log(html);
        $('#data_features_rental').append(html);
    });

    $(document).on('click', '.btn_del_program', function () {
        $(this).parent().parent().parent().remove();
    });

    /*$(document).on('change', '.taxonomy_car', function() {
     var $this = $(this);
     var $select_index = $this.context.options.selectedIndex;
     var $icon = $($this.context.options[$select_index]).attr('data-icon');
     $this.parent().find('i').removeAttr('class').attr('class',$icon+' input-icon input-icon-hightlight');
     });*/

    $('li.menu_partner a').click(function () {
        var type = $(this).next('.sub_partner').css('display');
        console.log(type);
        if (type == "none") {
            $(this).next('.sub_partner').slideDown(500);
            $('.icon_partner', this).removeClass("fa-angle-left").addClass("fa-angle-down");
        } else {
            $(this).next('.sub_partner').slideUp(500);
            $('.icon_partner', this).removeClass("fa-angle-down").addClass("fa-angle-left");
        }
    });

    /*if($('#sub_partner').find('.active').length > 0){
     $('.icon_partner').removeClass("fa-angle-left").addClass("fa-angle-down");
     $('#sub_partner').parent().addClass('active');
     $('#sub_partner').css('display','block');
     }*/

    $('.btn_on_off_post_type_partner').click(function () {
        var $this = $(this);
        $.ajax({
            url: st_params.ajax_url,
            type: "POST",
            data: {
                action: "st_change_status_post_type",
                data_id: $(this).attr('data-id'),
                data_id_user: $(this).attr('data-id-user'),
                status: $(this).attr('data-status')
            },
            dataType: "json",
            beforeSend: function () {
                $('.post-' + $this.attr('data-id') + ' .user_img_loading').show();
            }
        }).done(function (html) {
            console.log(html);
            $('.post-' + $this.attr('data-id') + ' .user_img_loading').hide();
            if (html.status == 'true') {


                if ($this.attr('data-status') == 'on') {
                    $this.attr('data-status', 'off');
                    $this.removeClass('fa-eye-slash').addClass('fa-eye');
                } else {
                    $this.attr('data-status', 'on');
                    $this.removeClass('fa-eye').addClass('fa-eye-slash');
                }
            } else {

            }

        });
    });


    // Create room rental
    $('#add-new-facility').click(function (event) {
        var html = $('#template').html();
        $('#facility-wrapper').append(html).find('.facility-item').show();
        event.preventDefault();
    });
    $('#facility-wrapper').on('click', '.btn_del_facility', function (event) {
        $(this).closest('.facility-item').remove();
    });


    // edit

    $('.btn_featured_image').click(function () {
        var $this = $(this);
        $this.parent().parent().find('#id_featured_image').val('');
        $this.parent().parent().find('.data_lable').val('');
        $this.parent().remove();
    });

    $('.btn_del_logo').click(function () {
        var $this = $(this);
        $this.parent().parent().find('#id_logo').val('');
        $this.parent().parent().find('.data_lable').val('');
        $this.parent().remove();
    });

    $('.btn_del_gallery').click(function () {
        var $this = $(this);
        $this.parent().parent().find('#id_gallery').val('');
        $this.parent().parent().find('.data_lable').val('');
        $this.parent().remove();
    });


    $('#btn_add_custom_paid_options').click(function () {
        var html = $('.paid_options_html').html();
        console.log(html);
        $('.content_data_paid_options').append(html);
    });
    $('#btn_add_custom_add_new_facility').click(function () {
        var html = $('.add_new_facility_html').html();
        console.log(html);
        $('.content_data_add_new_facility').append(html);
        $('.st_icon').each(function(){
            $(this).iconpicker({
                icons : st_icon_picker.icon_list,
                iconClassPrefix: ''
            });
        });
    });

    $(document).on('click', '.btn_del_custom_partner', function () {
        $(this).parent().parent().parent().remove();
    });

    $('#btn_discount_by_adult').click(function () {
        var html = $('#html_discount_by_adult').html();
        console.log(html);
        $('#data_discount_by_adult').append(html);
    });
    $('#btn_discount_by_child').click(function () {
        var html = $('#html_discount_by_child').html();
        console.log(html);
        $('#data_discount_by_child').append(html);
    });
    $("#btn_hotel_policy").on('click', function () {
        var html = $("#html_hotel_policy").html();
        console.log(html);
        $("#data_hotel_policy").append(html);
    });
    function fix_user_menu(){
        setTimeout(function () {
        var height_conent = $('.row_content_partner').height();
        var content_width = $('body').width();
        if (height_conent > 0 && content_width > 960) {
            $('.user-left-menu>.st-page-sidebar-new').css("min-height", height_conent);
        }

    }, 1500);
    } 
    jQuery(window).bind("load", function($) {
        fix_user_menu();
    }   
    );
    
    jQuery(window).resize(function($){
        fix_user_menu();
    })

    $('#st_form_add_partner .number').each(function () {
        var $this = $(this);
        $this.change(function () {
            var number = $(this).val();
            number = parseFloat(number);
            if (isNaN(number)) {
                number = 0;
            }
            $(this).val(number);
        });
    });


    $('#st_form_add_partner input.date-pick').each(function () {
        var form = $(this).closest('form');
        $(this, form).datepicker('setStartDate', 'today');
    });


    /*$('.check_all').each(function(){
     var $this = $(this);
     $this.click(function(){
     console.log($(this).val())
     });
     });*/

    /*$('.check_all').iCheck({
     checkboxClass: 'i-check',
     radioClass: 'i-radio',
     increaseArea: '20%' // optional
     });*/


    $('.check_all').on('ifClicked', function (event) {
        var $this = $(this);
        if ($this.prop('checked')) {
            $this.parent().parent().parent().parent().parent().find('.item_tanoxomy').iCheck('uncheck');
        } else {
            $this.parent().parent().parent().parent().parent().find('.item_tanoxomy').iCheck('check');
        }
    });
    $('.item_tanoxomy').on('ifClicked', function (event) {
        var $this = $(this);
        var is_check = true;
        $this.parent().parent().parent().parent().parent().find('.item_tanoxomy').each(function () {
            var $this2 = $(this);
            setTimeout(function () {
                if ($this2.prop('checked') == "") {
                    is_check = false;
                }
            }, 100)
        });
        setTimeout(function () {
            if (is_check == true) {
                $this.parent().parent().parent().parent().parent().find('.check_all').iCheck('check');
            } else {
                $this.parent().parent().parent().parent().parent().find('.check_all').iCheck('uncheck');
            }
        }, 200)

    });

    check_show_hiden('is_sale_schedule', 'data_is_sale_schedule');
    check_show_hiden('st_tour_external_booking', 'data_st_tour_external_booking');
    check_show_hiden('st_rental_external_booking', 'data_st_rental_external_booking');
    check_show_hiden('st_activity_external_booking', 'data_st_activity_external_booking');
    check_show_hiden('st_room_external_booking', 'data_st_room_external_booking');
    check_show_hiden('st_car_external_booking', 'data_st_car_external_booking');
    check_show_hiden('best-price-guarantee', 'data_best-price-guarantee');

    function check_show_hiden(div, div_data) {
        //console.log(div);
        //console.log($("."+div).val());
        //console.log($("."+div_data));
        if ($("." + div).val() == 'on') {
            $('.' + div_data).fadeIn(500);
        } else {
            $('.' + div_data).fadeOut(500);
        }
        $('.' + div).change(function () {
            if ($(this).val() == 'on') {
                $('.' + div_data).fadeIn(500);
            } else {
                $('.' + div_data).fadeOut(500);
            }
        });
    }

    if ($(".deposit_payment_status").val() != '') {
        $('.data_deposit_payment_status').fadeIn(500);
    } else {
        $('.data_deposit_payment_status').fadeOut(500);
    }
    $('.deposit_payment_status').change(function () {
        if ($(this).val() != '') {
            $('.data_deposit_payment_status').fadeIn(500);
        } else {
            $('.data_deposit_payment_status').fadeOut(500);
        }
    });

    if ($(".is_auto_caculate").val() == 'off') {
        $('.data_is_auto_caculate').fadeIn(500);
    } else {
        $('.data_is_auto_caculate').fadeOut(500);
    }
    $('.is_auto_caculate').change(function () {
        if ($(this).val() == 'off') {
            $('.data_is_auto_caculate').fadeIn(500);
        } else {
            $('.data_is_auto_caculate').fadeOut(500);
        }
    });

    if ($(".is_custom_price").val() == 'price_by_date') {
        $('.data_price_by_date').fadeIn(500);
        $('.data_price_by_number').fadeOut(0);
    } else {
        $('.data_price_by_date').fadeOut(0);
        $('.data_price_by_number').fadeIn(500);
    }
    $('.is_custom_price').change(function () {
        if ($(this).val() == 'price_by_date') {
            $('.data_price_by_date').fadeIn(500);
            $('.data_price_by_number').fadeOut(0);
        } else {
            $('.data_price_by_date').fadeOut(0);
            $('.data_price_by_number').fadeIn(500);
        }
    });


    setTimeout(function () {
        $('.div_btn_submit input[type=submit]').removeAttr('disabled');
    }, 5000);

});
jQuery(function ($) {
    if ($("#st_form_add_partner").hasClass('success') == true) {
        console.log('Reset');
        $("#st_form_add_partner input[type=text]").val('');
        $("#st_form_add_partner input[type=email]").val('');
        $("#st_form_add_partner input[type=number]").val('0');
        $("#st_form_add_partner .st_content").val('');
        $("#st_form_add_partner textarea").html('');
        $("#st_form_add_partner .user-profile-avatar").html('');
        $("#st_form_add_partner .id_featured_image").val('');
        $("#st_form_add_partner .id_logo").val('');
        $("#st_form_add_partner .data_lable").val('');
        $("#st_form_add_partner .content_data_add_new_facility").html('');
        $("#st_form_add_partner .content_data_paid_options").html('');
        $("#st_form_add_partner .content_data_price").html('');
        $("#st_form_add_partner .selectize-input").html('');

        $('#st_form_add_partner select').prop('selectedIndex', 0);
        $("#st_form_add_partner").find('.item_tanoxomy').iCheck('uncheck');
    }


    $('.input-daterange input.st_date_start').each(function () {

        var form = $(this).closest('form');

        var me = $(this);

        $(this).datepicker({
            language: st_params.locale,
            autoclose: true,
            todayHighlight: true,
            startDate: 'today',
            format: $('[data-date-format]').data('date-format')
        }).on('changeDate', function (e) {

                var new_date = e.date;
                new_date.setDate(new_date.getDate() + 1);
                $('.input-daterange input.st_date_end', form).datepicker('setDates', new_date);
                $('.input-daterange input.st_date_end', form).datepicker('setStartDate', new_date);
            }
        );

        $('.input-daterange input.st_date_end', form).datepicker({
            language: st_params.locale,
            startDate: '+1d',
            format: $('[data-date-format]').data('date-format'),
            autoclose: true,
            todayHighlight: true
        });
    })
});
///////////////////////////////////////
/////// Menu new partner///////////////
///////////////////////////////////////
jQuery(function ($) {

    $(document).on('click', '.st_menu_new li.item', function () {
        var content = $(this).parent();
        var $this = $(this);
        if ($this.hasClass('active') == false) {
            content.find('li.item').removeClass("active").find('.sub-menu').css('display', 'none');
            $this.find('.sub-menu').fadeIn(500);
            $this.addClass("active");
        }
    });



   /* $('.custom-date-start').datepicker({
        todayHighlight: true,
        setDates:"today",
    }).on('changeDate', function (e) {
        var $date = e.date;
        var formatted = $date.getDate() + "-" + ($date.getMonth() + 1) + "-" + $date.getFullYear();
        $('.input-date-start').val(formatted);
    });



    $('.custom-date-end').datepicker({
        todayHighlight: true
    }).on('changeDate', function (e) {
        var $date = e.date;
        var formatted = $date.getDate() + "-" + ($date.getMonth() + 1) + "-" + $date.getFullYear();
        $('.input-date-end').val(formatted);
    });
    ;*/


    $('.input-date-start').each(function () {
        var form = $(this).closest('form');
        var me = $(this);
        $(this).datepicker({
            language:st_params.locale,
            autoclose: true,
            todayHighlight: true,
            //startDate: 'today',
            todayBtn:true,
            format: $(this).data('date-format')
        }).on('changeDate', function (e) {

                var new_date = e.date;
                new_date.setDate(new_date.getDate() + 1);
                $('.input-date-end', form).datepicker('setDates', new_date);
                //$('.input-date-end', form).datepicker('setStartDate', new_date);
            }
        );
        $('.input-date-end', form).datepicker({
            language:st_params.locale,
            //startDate: '+1d',
            format: $(this).data('date-format'),
            autoclose: true,
            todayBtn:true,
            todayHighlight: true
        });
    })




    $(document).on('click', '.btn_show_custom_date', function () {
        var $this = $(this);
        console.log($this.hasClass('open'));
        if ($this.hasClass('open') == true) {
            $(".div-custom-date").fadeOut();
            $this.removeClass('open');
        } else {
            $(".div-custom-date").fadeIn();
            $this.addClass('open');
        }

    });
    $(document).on('click', '.btn_cancel', function () {
        $(".div-custom-date").fadeOut();
        $('.btn_show_custom_date').removeClass('open');
    });

    if ($('.custom_select_date').val() == 'custom_date||') {
        $('.data_custom_date').fadeIn();
    } else {
        $('.data_custom_date').fadeOut();
    }
    $(document).on('change', '.custom_select_date', function () {
        var type = $(this).val();
        if (type == 'custom_date||') {
            $('.data_custom_date').fadeIn();
        } else {
            $('.data_custom_date').fadeOut();
        }
    });
    ////////////////////////////////////////////////////
    ////////////  //////////////////////////////
    ////////////////////////////////////////////////////
    $(document).on('click', '.btn_show_month_by_year', function () {
        var $content = $(this).parent().parent().parent();
        $content.find('tr').removeClass('active');
        $(this).parent().parent().addClass('active');
        var $this = $(this);
        var $post_type = $this.data('post-type');
        var $year = $this.data('year');
        $.ajax({
            url: st_params.ajax_url,
            type: "POST",
            data: {
                action: "st_load_month_by_year_partner",
                data_year: $year,
                data_post_type: $post_type
            },
            dataType: "json",
            beforeSend: function () {

                $content.find('.active a.btn_show_month_by_year').html($this.data('loading'));
            }
        }).done(function (html) {
            $('.div_single_month .data_month').html(html.html);
            $('.div_single_month .bc_single').html(html.bc_title);
            $content.find('.active a.btn_show_month_by_year').html($this.data('title'));
            /// RESET
            $('.div_single_year').hide();
            $('.div_single_day').hide();
            $('.div_single_month').fadeIn();

            $('.div_single_custom').hide();
            //INIT CANVAS
            init_canvas_detail_post_type('st_div_item_canvas_month',html.id_rand,$post_type,html.js.lable,html.js.data)
        }).error(function(html){
            console.log(html);
        });

    });

    $(document).on('click', '.btn_show_day_by_month_year_partner', function () {
        var $content = $(this).parent().parent().parent();
        $content.find('tr').removeClass('active');
        $(this).parent().parent().addClass('active');
        var $this = $(this);
        var $post_type = $this.data('post-type');
        var $year = $this.data('year');
        var $month = $this.data('month');
        $.ajax({
            url: st_params.ajax_url,
            type: "POST",
            data: {
                action: "st_load_day_by_month_and_year_partner",
                data_year: $year,
                data_month: $month,
                data_post_type: $post_type
            },
            dataType: "json",
            beforeSend: function () {
                $content.find('.active a.btn_show_day_by_month_year_partner').html($this.data('loading'));
            }
        }).done(function (html) {

            $('.div_single_day .data_day').html(html.html);
            $('.div_single_day .bc_single').html(html.bc_title);
            $content.find('.active a.btn_show_day_by_month_year_partner').html($this.data('title'));
            /// RESET
            $('.div_single_year').hide();
            $('.div_single_month').hide();
            $('.div_single_day').fadeIn();
            //INIT CANVAS
            init_canvas_detail_post_type('st_div_item_canvas_day',html.id_rand,$post_type,html.js.lable,html.js.data);
        }).error(function(html){
            console.log(html);
        });

    });
    $(document).on('click', '.btn_single_all_time', function () {
        $('.div_single_year').fadeIn();
        $('.div_single_month').hide();
        $('.div_single_day').hide();
    });
    $(document).on('click', '.btn_single_year', function () {
        $('.div_single_year').hide();
        $('.div_single_month').fadeIn();
        $('.div_single_day').hide();
    });
    ////////////////////////////////////////////////////
    //////////// ALL TIME //////////////////////////////
    ////////////////////////////////////////////////////
    $(document).on('click', '.btn_all_time_show_month_by_year', function () {
        var $content = $(this).parent().parent().parent();
        $content.find('tr').removeClass('active');
        $(this).parent().parent().addClass('active');
        var $this = $(this);
        var $year = $this.data('year');
        $.ajax({
            url: st_params.ajax_url,
            type: "POST",
            data: {
                action: "st_load_month_all_time_by_year_partner",
                data_year: $year
            },
            dataType: "json",
            beforeSend: function () {
                $content.find('.active a.btn_all_time_show_month_by_year').html($this.data('loading'));
            }
        }).done(function (html) {
            $('.div_all_time_month .data_all_time_month').html(html.html);
            $('.div_all_time_month .bc_all_time').html(html.bc_title);
            $content.find('.active a.btn_all_time_show_month_by_year').html($this.data('title'));
            /// reset
            $('.div_all_time_year').hide();
            $('.div_all_time_day').hide();
            $('.div_all_time_month').fadeIn();
            $('.div_custom_month').hide();
            //INIT CANVAS
            init_canvas_detail_post_type('st_div_item_all_time_canvas_month',html.id_rand,'st_hotel',html.js.lable,html.js.data)
        }).error(function(html){
            console.log(html);
        });
    });

    $(document).on('click', '.btn_all_time_show_day_by_month_year_partner', function () {
        var $content = $(this).parent().parent().parent();
        $content.find('tr').removeClass('active');
        $(this).parent().parent().addClass('active');
        var $this = $(this);
        var $year = $this.data('year');
        var $month = $this.data('month');
        $.ajax({
            url: st_params.ajax_url,
            type: "POST",
            data: {
                action: "st_load_day_all_time_by_month_and_year_partner",
                data_year: $year,
                data_month: $month
            },
            dataType: "json",
            beforeSend: function () {
                $content.find('.active a.btn_all_time_show_day_by_month_year_partner').html($this.data('loading'));
            }
        }).done(function (html) {
            $('.div_all_time_day .data_all_time_day').html(html.html);
            $('.div_all_time_day .bc_all_time').html(html.bc_title);
            $content.find('.active a.btn_all_time_show_day_by_month_year_partner').html($this.data('title'));
            /// reset
            $('.div_all_time_year').hide();
            $('.div_all_time_month').hide();
            $('.div_all_time_day').fadeIn();
            //INIT CANVAS
            init_canvas_detail_post_type('st_div_item_all_time_canvas_day',html.id_rand,'st_hotel',html.js.lable,html.js.data);
        }).error(function(html){
            console.log(html);
        });
    });

    $(document).on('click', '.btn_all_time', function () {
        $('.div_all_time_year').fadeIn();
        $('.div_all_time_month').hide();
        $('.div_all_time_day').hide();
    });
    $(document).on('click', '.btn_all_time_year', function () {
        $('.div_all_time_year').hide();
        $('.div_all_time_month').fadeIn();
        $('.div_all_time_day').hide();
    });

    /////////////////////////////////////////////////
    ///////////// INIT CANVAS ///////////////////////
    /////////////////////////////////////////////////
    function init_canvas_detail_post_type(div_content,id_rand,post_type,lable,data_item){
        var id_div = 'canvas_detail_post_type_'+id_rand;
        var $content = $("."+div_content);
        $content.html('<canvas id="'+id_div+'" height="150"></canvas>');
        lable = eval(lable);
        data_item = eval(data_item);
        var color = '237,​ 131,​ 35';
        switch (post_type){
            case "st_hotel":
                color = '87, 142, 190';
                break;
            case "st_rental":
                color = '227, 91, 90';
                break;
            case "st_cars":
                color = '68, 182, 174';
                break;
            case "st_tours":
                color = '135, 117, 167';
                break;
            case "st_activity":
                color = '39, 174, 96';
                break;
        }
        var lineChartData = {
            labels : lable,
            datasets : [
                {
                    label: "My First",
                    fillColor : "rgba("+color+", 0.8)",
                    strokeColor : "rgba("+color+", 1)",
                    pointColor : "rgba("+color+", 1)",
                    pointStrokeColor : "#fff",
                    pointHighlightFill : "#fff",
                    pointHighlightStroke : "rgba("+color+", 1)",
                    data : data_item,
                }
            ]
        }
        var ctx = document.getElementById(id_div).getContext("2d");
        new Chart(ctx).Line(lineChartData, {
            responsive: true,
            animationEasing: "easeOutBounce",
        });
    }


    if($('.st_timepicker').length){
        $('.st_timepicker').timepicker({
            timeFormat: "hh:mm tt"
        });
    }

    $('.st_icon').each(function () {
        $(this).iconpicker({
            icons: st_icon_picker.icon_list,
            iconClassPrefix: ''
        });
    });

});