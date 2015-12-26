jQuery(document).ready(function($) {
	if($('.st_timepicker').length){
		$('.st_timepicker').timepicker({
			timeFormat: "hh:mm tt"
		});
	}
	if(typeof myfunc == 'timepicker'){
    	$('.st_timepicker').timepicker({
			timeFormat: "hh:mm tt"
		});
	}/*else{
	    alert("not exist");
	}*/
});