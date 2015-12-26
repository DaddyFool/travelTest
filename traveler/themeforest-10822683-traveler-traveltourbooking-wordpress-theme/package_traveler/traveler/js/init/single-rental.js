/**
 * Created by me664 on 3/3/15.
 */
jQuery(document).ready(function($){

    $('.btn_booking_modal').click(function(){
       var form=$(this).closest('form');
       $('.alert',form).remove();
        var validate_form=true;
        var data=[];

        $('input.required,textarea.required,select.required',form).each(function(){

            $(this).removeClass('error');
            if(!$(this).val()){
                validate_form=false;
                $(this).addClass('error');
            }

            if($(this).attr('name')){
                data.push({
                    'value':$(this).val(),
                    'name':$(this).attr('name')
                });
            }

        });

        if(!validate_form)
        {
            form.prepend('<div class="alert alert-danger">'+st_checkout_text.validate_form+'</div>');
            return false;
        }else
        {
            var tar_get=$(this).data('target');

            for(i=0;i<data.length;i++)
            {
                var val=data[i];
                $(tar_get).find('.booking_modal_form').prepend('<input type="hidden" name="'+val.name+'" value="'+val.value+'">');
            }

            $.magnificPopup.open({
                items: {
                    type: 'inline',
                    src: tar_get
                }

            });
        }
    });

    var RentalCalendar = function(container){
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
                    left:   'prev',
                    center: 'title',
                    right:  'next, '
                },
                contentHeight: 360,
                events:function(start, end, timezone, callback) {
                    $.ajax({
                        url: st_params.ajax_url,
                        dataType: 'json',
                        type:'post',
                        data: {
                            action:'st_get_availability_rental_single',
                            post_id:self.container.data('post-id'),
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
                    /*$('#calendar_price', self.form_container).val(event.price);
                    $('#calendar_number', self.form_container).val(event.number);
                    $('#calendar_status option[value='+event.date+']', self.form_container).prop('selected');*/
                },
                eventRender: function(event, element, view){ 
                    var html = "";
                    var title = "";
                    var html_class = "btn-disabled";   
                    var is_disabled = "disabled";
                    var today_y_m_d = new Date().getFullYear() +"-"+(new Date().getMonth()+1)+"-"+new Date().getDate();

                    if(event.status == 'booked'){ }
                    if(event.status == 'past'){ }
                    if(event.status == 'disabled'){ }

                    if(event.status == 'avalable'){
                        html_class = "btn-available";
                        is_disabled = "";
                        title = st_checkout_text.origin_price + ": "+event.price;
                    }                    
                    html += "<button  "+is_disabled+" data-toggle='tooltip' data-placement='top' class= '"+html_class+" btn' title ='"+title+"''>"+event.day;  
                    if (today_y_m_d === event.date) {
                        html += "<span class='triangle'></span>";
                    }        
                    html+="</button>";
                    $('.fc-content', element).html(html);
                                       

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
    if($('.calendar-wrapper').length){
        $('.calendar-wrapper').each(function(index, el) {
            var t = $(this);
            var rental = new RentalCalendar(t);
            rental.init();
        });
    }
});
