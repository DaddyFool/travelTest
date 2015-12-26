/**
 * Created by me664 on 1/13/15.
 */

jQuery(document).ready(function($){
    
    if(typeof $.fn.sticky!='undefined'){
        var topSpacing=0; 
        if($(window).width()>481 && $('body').hasClass('admin-bar') ){
            topSpacing=$('#wpadminbar').height();
        }
        //console.log(topSpacing);  
        set_sticky();   

    }
    function set_sticky(){

        var is_menu1 = $(".menu_style1").length ; 
        var is_menu2 = $(".menu_style2").length ; 
        var is_topbar = $("#top_toolbar").length ; 
        var sticky_topbar = $(".enable_sticky_topbar").length ; 
        var sticky_menu = $(".enable_sticky_menu").length ; 
        var sticky_header = $(".enable_sticky_header").length ; 

        if(sticky_header && is_menu1){
            $("#st_header_wrap_inner").sticky({topSpacing:topSpacing});
            return;
        }else {
            if (sticky_topbar && is_topbar) {
                $("#top_toolbar").sticky({topSpacing:topSpacing});                
            }            
            if (sticky_menu) {
                var topSpacing_topbar = topSpacing;
                if (is_topbar && sticky_topbar) {topSpacing_topbar  += $("#top_toolbar").height(); }
                console.log(topSpacing);
                console.log($("#top_toolbar").height());
                console.log(topSpacing_topbar);
                $(".menu_style1 #menu1").sticky({topSpacing:topSpacing_topbar});
                $(".menu_style2 #menu2").sticky({topSpacing:topSpacing_topbar});
                return;
            }
        }
         
    }
    if($('body').hasClass('search_enable_preload'))
    {
        window.setTimeout(function(){
            $('.full-page-absolute').fadeOut().addClass('.hidden');
        },1000);
    }
    /*Begin gotop*/
    $('#gotop').click(function(){
        $("body,html").animate({
            scrollTop:0
        },1000,function(){
            $('#gotop').fadeOut();
        });
    });

    $(window).scroll(function(){
        var scrolltop=$(window).scrollTop();

        if(scrolltop>200){
            $('#gotop').fadeIn();
        }else{
            $('#gotop').fadeOut();
        }        
        scroll_with_out_transparent();         
    });
    scroll_with_out_transparent();
    function scroll_with_out_transparent(){
        var sdlfkjsdflksd_scrolltop=$(window).scrollTop(); 
        var header_bgr_default = {'background-color' : ""};
        if ($("body").hasClass("menu_style2") && sdlfkjsdflksd_scrolltop !=0 && $('.enable_sticky_menu.header_transparent').length !==0){     

            $(".header-top").css(st_params.header_bgr);
            //$(".header-top").addClass('no_transparent');
             
        }else { 
            $(".header-top").css(header_bgr_default);
            //$(".header-top").removeClass('no_transparent');
             
        }
    }

    var top_ajax_search=$('.st-top-ajax-search');

    top_ajax_search.typeahead({
            hint: true,
            highlight: true,
            minLength: 3,
            limit: 8
        },
        {
            source: function(q, cb) {
                $('.st-top-ajax-search').parent().addClass('loading');
                return $.ajax({
                    dataType: 'json',
                    type: 'get',
                    url: st_params.ajax_url,
                    data:{
                        security:st_params.st_search_nonce,
                        action:'st_top_ajax_search',
                        s:q,
                        lang:top_ajax_search.data('lang')
                    },
                    cache:true,
                    success: function(data) {
                        $('.st-top-ajax-search').parent().removeClass('loading');
                        var result = [];
                        if(data.data){
                            $.each(data.data, function(index, val) {
                                result.push({
                                    value: val.title,
                                    location_id:val.id,
                                    type_color:'success',
                                    type:val.type,
                                    url:val.url
                                });
                            });
                            cb(result);
                            console.log(result);
                        }

                    },
                    error:function(e){
                        $('.st-top-ajax-search').parent().removeClass('loading');
                    }
                });
            },
            templates: {
                suggestion: Handlebars.compile('<p class="search-line-item"><label class="label label-{{type_color}}">{{type}}</label><strong> {{value}}</strong></p>')
            }
        });

    top_ajax_search.bind('typeahead:selected', function(obj, datum, name) {
        if(datum.url){
            window.location.href=datum.url;
        }
    });

    if($.fn.chosen){
        $(".chosen_select").chosen();
    }

    $('.woocommerce-ordering .posts_per_page').change(function(){
        $('.woocommerce-ordering').submit();
    });
    var product_timeout;
    $('.woocommerce li.product').hover(function(){
        var me=$(this);
        product_timeout=window.setTimeout(function(){
            me.find('.product-info-hide').slideDown('fast');
        },250);
    },function(){
        window.clearTimeout(product_timeout);
        var me=$(this);
        me.find('.product-info-hide').slideUp('fast');
    });

});
