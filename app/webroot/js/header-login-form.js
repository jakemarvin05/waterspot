 $(function() {
	  $( ".parent-link a" ).click(function() {
			
			$( ".parent-link > ul" ).slideToggle( "slow");
			
			var check_class = $(".parent-link").hasClass("active");
			if(check_class){
				$(".parent-link").removeClass("active");
			}else{
				$(".parent-link").addClass("parent-link active");
			}
			
	  });
	
	  $( ".close-login" ).click(function() {
		   $(".parent-link").removeClass("active");
		   $( ".parent-link > ul" ).slideUp("slow" );
		   
		   
	  });
 });
