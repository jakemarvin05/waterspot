<div class="container-fluid activities-page topResponsivePadding">

	<section class="row">


		<div class="search-listing-header">
		</div>
		<div style="margin-top: 10px;" class="search-listing">
			<header class="page-header">
				<p class="beforeHeader">Ready to enjoy exciting adventures?</p>
				<h1 class=" headerAlt" style="float: left;"> Select Activities</h1>
				<div class="filter">
					<? $action=(implode('/',$this->params->pass));?>
					<?=$this->Form->create('Search',array('url'=>array('plugin'=>false,'controller'=>'search','action'=>'index/'.$action),'novalidate' => true,'class'=>'sl'));?>
					<label class="filter-label">Filter by:</label>
					<?=$this->Form->hidden('vendor_list');?>
					<?  //=$this->Form->input('vendor_list',array('options'=>$vendor_list,'empty'=>'Select any Vendor','label'=>false,'div'=>false,'required'=>false));?>
					<?=$this->Form->input('service_type_list',array('options'=>$service_type_list,'empty'=>'Select service type','label'=>false,'div'=>false,'required'=>false));?>
					<?=$this->Form->input('sort_price',array('options'=>Configure::read('price_range'),'empty'=>'Select price range','label'=>false,'class'=>'last','div'=>false,'required'=>false));?>
					<!--<?=$this->Form->input('sort_review',array('options'=>Configure::read('review'),'empty'=>'Sort by review ratings','label'=>false,'class'=>'last','div'=>false,'required'=>false));?>-->
					<?=$this->Form->end();?>

					<script>
						// init the selectpicker
						$('#SearchServiceTypeList').selectpicker();

						// bind selection to toggling of text colors on the select
						// so that the placeholder color is maintained
						$(function() {
							var $filterOption = $('#activityListWrap .filter-option');
							var selectPlaceholder = $('#activityListWrap select').attr('title');

							$('ul.dropdown-menu>li>a').on('click', function() {
								setTimeout(function() {
									if ($filterOption.html() === selectPlaceholder) $filterOption.css('color', '#ccc');
									else $filterOption.css('color', '#606060');
								},0);
							});

						});
						$('#SearchSortPrice').selectpicker();

						// bind selection to toggling of text colors on the select
						// so that the placeholder color is maintained
						$(function() {
							var $filterOption = $('#activityListWrap .filter-option');
							var selectPlaceholder = $('#activityListWrap select').attr('title');

							$('ul.dropdown-menu>li>a').on('click', function() {
								setTimeout(function() {
									if ($filterOption.html() === selectPlaceholder) $filterOption.css('color', '#ccc');
									else $filterOption.css('color', '#606060');
								},0);
							});

						});
						$('#SearchSortReview').selectpicker();

						// bind selection to toggling of text colors on the select
						// so that the placeholder color is maintained
						$(function() {
							var $filterOption = $('#activityListWrap .filter-option');
							var selectPlaceholder = $('#activityListWrap select').attr('title');

							$('ul.dropdown-menu>li>a').on('click', function() {
								setTimeout(function() {
									if ($filterOption.html() === selectPlaceholder) $filterOption.css('color', '#ccc');
									else $filterOption.css('color', '#606060');
								},0);
							});

						});
					</script>
				</div>
			</header>

			<div class="filtered-listing">
			
				<div class="activity-filter">

				</div>
			</div>

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
		         
			

			<div class="clear"></div>
		</div>

		<div class="clear"></div>

		<script type="text/javascript">
		    $(document).ready(function(){
				$('#SearchSortPrice,#SearchVendorList,#SearchServiceTypeList,#SearchSortReview').bind("change",function(){
					$('#sort_by_price').show();
					var SearchVendorList = ($("#SearchVendorList").val()=='')?'vendor_id':$("#SearchVendorList").val();
				 	var SearchServiceTypeList = ($("#SearchServiceTypeList").val()=='')?'service_type':$("#SearchServiceTypeList").val();
				 	var SearchSortPrice = ($("#SearchSortPrice").val()=='')?'sortbyprice':$("#SearchSortPrice").val();
				 	var SearchSortReview = ($("#SearchSortReview").val()=='')?'sortbyreview':$("#SearchSortReview").val();

					$.ajax({
					    url : "<?=$this->webroot;?>activity/activities/"+SearchVendorList+"/"+SearchServiceTypeList+"/"+SearchSortPrice+"/"+SearchSortReview,
					    success: function(res) {

						   $( ".sun-text" ).remove();
						   $("div.activities-listing").remove();
						   $(".activities").html('<div class="row">'+res+'</div>');
						   $('#sort_by_price').hide();

						   $('.tile').contenthover({
							   overlay_background:'#000',
							   overlay_opacity:1
						   });
					  
						}           
					});
				});
				 
			});
		</script>
		<script type='text/javascript'>
			var loading_start = 0;
			$(document).ready(function(){
				
				if(page >= pages) {
				  $("#loader_pagination").attr("disabled", true);
				  $('#loader_pagination').addClass('no-more-activities').html('No more results');
				
				} else {
					$("#loader_pagination").attr("disabled", false);
					$('#loader_pagination').removeClass('no-more-activities').html('Load more results');
				}

				$('#loader_pagination').bind('click',function(){
					$('#loader-image').show();
					var SearchVendorList = ($("#SearchVendorList").val()=='')?'vendor_id':$("#SearchVendorList").val();
				 	var SearchServiceTypeList = ($("#SearchServiceTypeList").val()=='')?'service_type':$("#SearchServiceTypeList").val();
				 	var SearchSortPrice = ($("#SearchSortPrice").val()=='')?'sortbyprice':$("#SearchSortPrice").val();
				 	var SearchSortReview = ($("#SearchSortReview").val()=='')?'sortbyreview':$("#SearchSortReview").val();
					
					if(pages >= (page+1)){
						 if(loading_start===0){
		                    loading_start = 1;
		                    page++;
		                    $.ajax({url:'<?=$this->webroot;?>activity/activities/'+SearchVendorList+"/"+SearchServiceTypeList+"/"+SearchSortPrice+"/"+SearchSortReview+'/page:'+page,
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
									if(textStatus == 'timeout') {     
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
	</section>
	<div class="clearfix"></div>
</div>