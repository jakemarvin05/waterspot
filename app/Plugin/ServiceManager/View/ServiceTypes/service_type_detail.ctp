<script type="text/javascript">
	$(function(){
		/*$('.contentselector').contenthover({
			data_selector: '.contenthover',
			effect:'slide',
			slide_direction: 'left',
			slide_speed:300,
			overlay_opacity: 1
		});*/
	});
</script>
<div class="container-fluid wrapper services-page activities-page">
<br><br>
	<section class="row">
<div class="search-listing-header">
	<header class="page-header">
		<p class="beforeHeader">Ready to enjoy exciting adventures?</p>
		<h1 class=" headerAlt" style="float: left;"> <?=$service_type_details['ServiceType']['page-title'] ?></h1>
		<br>
	</header>

</div>


<div class="search-listing">

	<div class="middle-area listing-body">
	<div id='sort_by_price' class="ajax-loder" style="float:left; width: 100%; text-align: center; padding: 20px 0; display:none">
		<?php echo $this->Html->image('loader-2.gif', array('alt' => 'loading..'));?>
	</div>
	<div class="activities">
		<div class="row">
		<? if(!empty($activity_service_list)) { ?>
			<?php echo $this->element('activity/listing',array('search_service_lists'=>$activity_service_list)); ?>
		<? } else { ?>
			<script type="text/javascript">
				var page = <?=$this->paginator->counter('{:page}')?>;
				var pages = <?=$this->paginator->counter('{:pages}')?>;
			</script>
			<div class="sun-text no-record"> There are no record found.</div>
		<? } ?>
			<div class="clearfix"></div>
			</div>

	</div>
	<div class="load-more-listings">
		<div class="load-more-row">
			<button class="btn btnDefaults btnFillOrange" id="loader_pagination">Load more results</button>
		</div>
		<div>
			<?=$this->Html->image('loader-2.gif',array('style'=>'display:none;','alt'=>'Activity Loader','id'=>'loader-image'));?>
		</div>
	</div>
	<div class="clear"></div>
</div>

<div class="clear"></div>
</section>
</div>
 
<script type='text/javascript'>
	var loading_start = 0;
	$(document).ready(function(){
		var page = <?=$this->paginator->counter('{:page}')?>;
		var pages = <?=$this->paginator->counter('{:pages}')?>;
      
		if(page >= pages){
		  $("#loader_pagination").attr("disabled", true);
		  $('#loader_pagination').addClass('no-more-activities').html('No more results');
		
		}else{
			$("#loader_pagination").attr("disabled", false);
			$('#loader_pagination').removeClass('no-more-activities').html('Load More results');
		}
		$('#loader_pagination').bind('click',function(){
			$('#loader-image').show();
			if(pages >= (page+1)){
				 if(loading_start===0){
                    loading_start = 1;
                    page++;
						$.ajax({url:'<?=Router::url(array('plugin'=>'service_manager','controller'=>'service_types','action'=>'service_type_detail',$service_type_id));?>'+'/page:'+page,
                        async:false,
                        timeout:5,
                        success:function(data){
							loading_start = 0;
							$('#loader-image').hide();
							$('.activities-listing:last').after(data );
							$('.tile').contenthover({
								overlay_background:'#000',
								overlay_opacity:1
							});
							
                            if(page >= pages){
								$("#loader_pagination").attr("disabled", true);
								$('#loader_pagination').addClass('no-more-activities').html('No more results');
							}
                            
                        },
                        error: function(jqXHR, textStatus){
							if(textStatus == 'timeout')
							{     
								 alert('Failed from timeout');         
								//do something. Try again perhaps?
							}
						},
                    });
                }
            }
			
		});
	});
	
</script>