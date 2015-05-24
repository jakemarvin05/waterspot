
 
    $(document).ready(function(){
		$('#SearchSortPrice').change(function(){
			$('#sort_by_price').show();
			var SearchSortPrice = $("#SearchSortPrice").val();
			   $.ajax({
				   url : "<?=$this->webroot.$this->params->url;?>/"+SearchSortPrice,
				   success: function(res) {
				   
				   $( ".sun-text" ).remove();
				   $("div.vendor-tile").remove();
				   $(".middle-area").html(res);
				   $('#sort_by_price').hide();
				   
				   $(function(){
			  //Keep track of last scroll
			  var lastScroll = 0;
			  var loading_start = 0;
			  var page = <?=$this->paginator->counter('{:page}')?>;
			  var pages = <?=$this->paginator->counter('{:pages}')?>;
			  
			  $(window).bind('scroll',function(event){
				   //Sets the current scroll position
				  var st = $(this).scrollTop();
				  var win_height = $(this).height();
				  var doc_height = $(document).height();
				  var scrollBottom = doc_height - win_height - st;
				  var scroll_value=200;
				   if(navigator.userAgent.match(/(iPhone)/i)){
					  scroll_value=4500;
					 //alert(doc_height);
				  }
				  //Determines up-or-down scrolling
				  //alert(doc_height+'--'+(st+win_height));
				  //if((st > lastScroll) && ((doc_height-100) < (st+win_height)) ){
				
					if((scrollBottom <= scroll_value) && (pages >= (page+1))){
						if(loading_start===0){
							loading_start = 1;
							page++;
							 $.ajax({
								
								url:'<?=$this->webroot.$this->params->url;?>/'+SearchSortPrice+'/page:'+page,
								async:false,
								success:function(data){
									$( ".vendor-tile:last" ).after(data );
								   // $('.ajaxpagination').append(data);
									loading_start = 0;
								}
							});
						}
					}
				
					
				  lastScroll = st;
				  
				  
			  });
			  
			});
						   
						}           
					});
				});
				 
			});
		 </script>
 <script type='text/javascript'>
    $(function(){
	  //Keep track of last scroll
      var lastScroll = 0;
      var loading_start = 0;
      var page = <?=$this->paginator->counter('{:page}')?>;
      var pages = <?=$this->paginator->counter('{:pages}')?>;
      
      $(window).bind('scroll',function(event){
           //Sets the current scroll position
          var st = $(this).scrollTop();
          var win_height = $(this).height();
          var doc_height = $(document).height();
          var scrollBottom = doc_height - win_height - st;
          var scroll_value=200;
           if(navigator.userAgent.match(/(iPhone)/i)){
			  scroll_value=4500;
			 //alert(doc_height);
		  }
		  //Determines up-or-down scrolling
          //alert(doc_height+'--'+(st+win_height));
          //if((st > lastScroll) && ((doc_height-100) < (st+win_height)) ){
        
            if((scrollBottom <= scroll_value) && (pages >= (page+1))){
				if(loading_start===0){
				    loading_start = 1;
                    page++;
                    $('#loader_pagination').show();
                    $.ajax({
						
                        url:'<?=$this->webroot.$this->params->url;?>/sortbyprice/page:'+page,
                        async:false,
                        success:function(data){
							$( ".vendor-tile:last" ).after(data );
                           // $('.ajaxpagination').append(data);
                            loading_start = 0;
                            $('#loader_pagination').hide();
                        }
                    });
                }
            }
        
            
          lastScroll = st;
          
          
      });
      
    });


