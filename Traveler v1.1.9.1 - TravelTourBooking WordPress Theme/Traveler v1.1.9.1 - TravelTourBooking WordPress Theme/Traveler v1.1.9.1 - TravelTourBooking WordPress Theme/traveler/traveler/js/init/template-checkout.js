/**
 * Created by me664 on 12/19/14.
 */
jQuery(document).ready(function($){

    $('.i-check, .i-radio').iCheck({
        checkboxClass: 'i-check',
        radioClass: 'i-radio'
    });

    $('.payment_gateway_item .st_payment_gatewaw_submit').click(function(){
        var me=$('#cc-form');

        $('#cc-form').find('.form_alert').addClass('hidden');
        var data=me.serializeArray();
        var dataobj = {};

        var form_validate=true;

        for (var i = 0; i < data.length; ++i)
        {
            dataobj[data[i].name] = data[i].value;
        }


        $('#cc-form').find('input.required,select.required,textarea.required').removeClass('error');

        $('#cc-form').find('input.required,select.required,textarea.required').each(function(){
           if(!$(this).val())
           {
               $(this).addClass('error');
               form_validate=false;

           }
        });

        /* fix safari */
        //var input_payment  = $(this).val();
        //var input_name = $(this).attr("name");
        //$(this).removeAttr('name');
        //if (!$(this).hasClass('added')){
        //    me.append("<input type='hidden' name='"+input_name+"' value ='"+input_payment+"'/>");
        //}
        //$(this).addClass('added');
        // end fix safari
        
        if(form_validate==false){
            me.find('.form_alert').addClass('alert-danger').removeClass('hidden');
            me.find('.form_alert').html(st_checkout_text.validate_form);
            return false;
        }


        //term_condition
        if(!dataobj.term_condition && $('[name=term_condition]',me).length){
            me.find('.form_alert').addClass('alert-danger').removeClass('hidden');
            me.find('.form_alert').html(st_checkout_text.error_accept_term);
            return false;
        }

        //me.submit();

    });
});