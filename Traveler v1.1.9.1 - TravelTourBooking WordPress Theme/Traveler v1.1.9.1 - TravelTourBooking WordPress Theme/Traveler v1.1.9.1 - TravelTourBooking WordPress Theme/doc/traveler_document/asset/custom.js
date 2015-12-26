jQuery(document).ready(function($){
	var afix_timeout;
	var sidebar=$('#sidebar-menu');
	var main_sidebar=$('#main-sidebar-menu');
	var offset_top=sidebar.offset().top;
	
	$(window).scroll(function(){
		var current_top=$(window).scrollTop();
		if(current_top>sidebar.offset().top)
		{
			main_sidebar.addClass('affix').removeClass('affix-top');
		}else
		{
			main_sidebar.addClass('affix-top').removeClass('affix');
		}
	});
	$(window).trigger('scroll');

	
    
	/* ==============================================
	Active Menu Item on Page Scroll
	=============================================== */   
	    
	var  nav = $('div.navbar-sidebar')
	  , nav_height = nav.outerHeight(),
	    find_rs,admin_bar_height;

	   admin_bar_height=0;

	  if($('body').hasClass('page_boxed'))
	  {
	      var sections=$('body .main_wraper>section');
	  }else{
	      var sections = $('body .content');
	  }

	nav_height+=admin_bar_height;
 
	    $(window).on('scroll', function () {
	      var cur_pos = $(this).scrollTop();
	     
	      sections.each(function() {
	        var top = $(this).offset().top - nav_height,
	            bottom = top + $(this).outerHeight();
	     
	        if (cur_pos >= top && cur_pos <= bottom) {
	          nav.find('a').removeClass('active');
	          sections.removeClass('active');
	          $(this).addClass('active');
	         
	            find_rs=nav.find('a[href$="#'+$(this).attr('id')+'"]');
	            if(find_rs.length)
	            {
	                find_rs.addClass('active');
	            }
	        }
	      });
	    });
 
});
