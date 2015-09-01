<script type="text/javascript">
	$(function(){
		$('.tile').contenthover({
			//data_selector: '.contenthover',
			//effect:'slide',
			//slide_direction: 'left',
			//slide_speed:300,
			overlay_background:'#000',
			overlay_opacity:1

		});
	});
</script>

<div class="middle-area search-page container-fluid topResponsivePadding">
	<div class="row">

		<header class="page-header text-center">
			<p class="beforeHeader">What are you game for?</p>
			<h1 class="headerAlt">SEARCH RESULTS</h1>
			<br/>
			<div class="activity-filter">
				<div class="filter">
					<? $action=(implode('/',$this->params->pass));?>
					<?=$this->Form->create('Search',array('url'=>array('plugin'=>false,'controller'=>'search','action'=>'index/'.$action),'novalidate' => true,'class'=>'sl'));?>
					<label class="filter-label">Filter by:</label>
					<?=$this->Form->input('service_type_list',array('options'=>$service_type_list,'empty'=>'Select service type','label'=>false,'div'=>false,'required'=>false));?>
					<?=$this->Form->input('sort_price',array('options'=>Configure::read('price_range'),'empty'=>'Sort by price range','label'=>false,'class'=>'last','div'=>false,'required'=>false));?>
					<?=$this->Form->input('location_list',array('options'=>$location_list,'empty'=>'Sort by location','label'=>false,'div'=>false,'required'=>false));?>
					<?=$this->Form->input('sort_review',array('options'=>Configure::read('review'),'empty'=>'Sort by review ratings','label'=>false,'class'=>'last','div'=>false,'required'=>false));?>



					<?=$this->Form->end();?>
				</div>
			</div>

		</header>

	<!-- <div class="search"><span> Search</span><input type="search"></div>-->
	<div id='sort_by_price' class="ajax-loder" style="display:none">
		<?php echo $this->Html->image('loader-2.gif', array('alt' => 'loading..'));?>
	</div>
	<div class="activities">
		<? if(!empty($search_service_lists)) { ?>
			<?php echo $this->element('activity/listing'); ?>
			 
		<? } else { ?>
			<div class="sun-text no-record"> There are no record found.</div>
		<? } ?>
	</div>
			<div class="load-more-listings">
			<div class="load-more-row">
				<button class="load-more" id="loader_pagination">Load more results</button>
			</div>
			<div class="load-more-row">
				<?=$this->Html->image('loader-2.gif',array('style'=>'display:none;','alt'=>'Activity Loader','id'=>'loader-image'));?>
			</div>
		</div>
</div>
</div>
<noscript>
	<div class='pag-box'>
		<ul class="pagination">
			<?php if($this->Paginator->first()){?>
				<li><?php echo $this->Paginator->first('<< First',array('class'=>'button gray')); ?></li>
			<?php } ?>
			<?php if($this->Paginator->hasPrev()){?>
				<li><?php echo $this->Paginator->prev('< Previous',array('class'=>'button gray'), null, array('class'=>'disabled'));?></li>
			<?php } ?>
			<?=$this->Paginator->numbers(array('modulus'=>7,'tag'=>'li','class'=>'','separator'=>'')); ?>
			<?php if($this->Paginator->hasNext()){?>
				<li><?php echo $this->Paginator->next('Next >',array('class'=>'button gray'));?></li>
			<?php } ?>
			<?php if($this->Paginator->last()){?>
				<li><?php echo $this->Paginator->last('Last >>',array('class'=>'button gray')); ?></li>
			<?php } ?>
		</ul>
	</div>
</noscript>	

<script type="text/javascript">
	
    $(document).ready(function(){
		var page = <?=$this->paginator->counter('{:page}')?>;
		var pages = <?=$this->paginator->counter('{:pages}')?>;
		
		if(page >= pages){
		  $("#loader_pagination").attr("disabled", true);
		  $('#loader_pagination').addClass('no-more-activities').html('No more activities');
		
		}else{
			$("#loader_pagination").attr("disabled", false);
			$('#loader_pagination').removeClass('no-more-activities').html('Load more results');
		}
		$('#SearchSortPrice,#SearchServiceTypeList,#SearchSortReview,#SearchLocationList').bind("change",function(){
			$('#sort_by_price').show();
			
		 	var SearchServiceTypeList = $("#SearchServiceTypeList").val();
		 	
		 	var SearchSortPrice = ($("#SearchSortPrice").val()=='')?'sortbyprice':$("#SearchSortPrice").val();
		 	
		 	var SearchSortReview = ($("#SearchSortReview").val()=='')?'sortbyreview':$("#SearchSortReview").val();

		 	var SearchSortByLocation = ($("#SearchLocationList").val()=='')?'sortbylocation':$("#SearchLocationList").val();

			if(SearchServiceTypeList=='' || SearchServiceTypeList==null){
				$('#sort_by_price').hide();
				alert('Please select service type');
				return false;	
			}
			 
			$.ajax({
			   url : "<?=Router::url(array('controller'=>'search'))?>/index/"+SearchServiceTypeList+"/<?=$start_date."/".$start_date."/1"?>/"+SearchSortPrice+"/"+SearchSortByLocation+"/"+SearchSortReview,
				   success: function(res) {
				   
					   $( ".sun-text" ).remove();
					   $("div.activities-listing").remove();
					   $(".activities").html(res);
					   $('#loader-image').hide();
					   $('.tile').contenthover({
						   //data_selector: '.contenthover',
						   //effect:'slide',
						   //slide_direction: 'left',
						   //slide_speed:300,
						   overlay_background:'#000',
						   overlay_opacity:1

					   });
					   $('#sort_by_price').hide();
				 	}           
					});
				});
				 
			});
		 </script>
 <script type='text/javascript'>
	var loading_start = 0;
	$(document).ready(function(){
		$('#loader_pagination').bind('click',function(){
			$('#loader-image').show();
			var SearchServiceTypeList = $("#SearchServiceTypeList").val();
		 	var SearchSortPrice = $("#SearchSortPrice").val();
			var SearchSortReview = ($("#SearchSortReview").val()=='')?'sortbyreview':$("#SearchSortReview").val();
			var SearchServiceTypeList = $("#SearchServiceTypeList").val();
		 	
			if(SearchSortPrice==null ||SearchSortPrice==''){
				SearchSortPrice='0-100000';
			} 
			if(SearchServiceTypeList=='' || SearchServiceTypeList==null){
				$('#sort_by_price').hide();
				alert('Please select service type');
				return false;	
			}
			 
			if(pages >= (page+1)){
				 if(loading_start===0){
                    loading_start = 1;
                    page++;
                    $.ajax({
						url:"<?=Router::url(array('controller'=>'search'))?>/index/"+SearchServiceTypeList+"/<?=$start_date."/".$start_date."/1"?>/"+SearchSortPrice+"/"+SearchSortReview+"/page:"+page,
						async:false,
                        timeout:5,
                        success:function(data){
							loading_start = 0;
							$('#loader-image').hide();
							$('.activities-listing:last').after(data );
							$('.tile').contenthover({
								//data_selector: '.contenthover',
								//effect:'slide',
								//slide_direction: 'left',
								//slide_speed:300,
								overlay_background:'#000',
								overlay_opacity:1

							});
							
                             
							
                            
                        },
                        error: function(jqXHR, textStatus){
							if(textStatus == 'timeout')
							{     
								 alert('Failed from timeout');         
								//do something. Try again perhaps?
							}
						}
                    });
                }
            }
			
		});
		
		
		
		});
	
</script>
<script type="application/javascript">
	$('#SearchIndexForm select').selectpicker();
</script>
