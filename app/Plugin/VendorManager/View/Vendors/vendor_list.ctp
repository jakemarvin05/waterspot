<script type="text/javascript">
	$(function(){
	/*	$('.tile').contenthover({
			//data_selector: '.contenthover',
			//effect:'slide',
			//slide_direction: 'left',
			//slide_speed:300,
			overlay_background:'#000',
			overlay_opacity:1
		});*/
	});
</script>

<div class="container-fluid wrapper vendors-page">

	<section class="row">
<div class="hr-line"></div>
<div class="clear"></div><header class="page-header">
			<p class="beforeHeader">See what we have for you?</p>
			<h1 class=" headerAlt">Select Vendors</h1>
		</header>
		<div class="clearfix"></div>
<div class="middle-area">
	<?php $i = $this->paginator->counter('{:start}'); ?>
	<? foreach($vendor_services as $key=>$vendor_service) { ?>
		<div class="vendor-listing col-sm-4 col-xs-6">
			<div class="contentvisible contentselector">
				<div class="tile">
					<?php 
					/* Resize Image */
					
						if(isset($vendor_service['Vendor']['image'])) {
							$imgArr = array('source_path'=>Configure::read('VendorProfile.SourcePath'),'img_name'=>$vendor_service['Vendor']['image'],'width'=>290,'height'=>220,'noimg'=>$setting['site']['site_noimage']);
							$resizedImg = $this->ImageResize->ResizeImage($imgArr);
							echo urldecode($this->Html->image($resizedImg,array('border'=>'0')));
						}
						
					?>
					<div class="contenthover">
						<div class="box-center">
							<div class="activity-tags">
								<? foreach($vendor_service['ServicesType'] as $key=>$service_type) {?>
									<span>
							<?php echo $this->Html->link($service_type['ServiceType']['name'],array('plugin'=>false,'controller'=>'activity','action'=>'activities',$vendor_service['Vendor']['id'],$service_type['ServiceType']['id']));?>
						</span>
								<? } ?>
							</div>
							<div class="clearfix"></div><br>
							<?php echo $this->Html->link('View all activities',array('plugin'=>'vendor_manager','controller'=>'vendors','action'=>'activities',$vendor_service['Vendor']['id']),array('class'=>'btn btnDefaults btnFillOrange'));?>
						</div>
					</div>
				</div>

				<div class="tile-info">
                                    
					<h4 class="h4 fix">
						<?=$this->Format->Headingsubstring((!empty($vendor_service['Vendor']['bname'])?$vendor_service['Vendor']['bname']:$vendor_service['Vendor']['name']),27);?>
					</h4>
                                    
					<div class="vendor-rating-wrapper">
					<? if(!empty($vendor_service['Vendor']['rating'])){ ?>
						
						<?php $ratings = range(1,10); ?>
						<?php foreach($ratings as $rating){ ?>
							<input type="radio" value="<?php echo $rating; ?>" name="test-4-rating-<?php echo $i; ?>" class="star {split:2}" disabled="disabled" <?php echo (round($vendor_service['Vendor']['rating'])==$rating)?'checked="checked"':'';?> />
						<?php } ?>
					<? } else {
						echo '<em style="display:block;color: #A1A1A1;">Unrated</em>';
						} ?>

						<span class="rating-label">Rating:</span>

						<?php
						//@todo convert Rating into float


						$rating = 0.0; // value is 0.0 to 1.0

						$ratingPerCent = $rating*5;
						$ratingMark = 0;

						if($ratingPerCent>1) {
							$ratingMark = round($ratingPerCent);
							$ratingMark = $ratingMark/5;
						}
						else{
							$ratingMark = 0;
						}

						?>

						<div class="rating" style="background-position: <?php echo -100+($ratingMark*100); ?>px 0px"></div>
						<div class="clearfix"></div>
					</div>

					<div class="clearfix"></div>
				</div>
			</div>

		</div>
	<? $i++; 
	}?> 
	 
	<div class="load-more-listings">
			<div class="load-more-row">
				<button class="load-more" id="loader_pagination">Load more results</button>
			</div>
			<div class="load-more-row">
				<?=$this->Html->image('loader-2.gif',array('style'=>'display:none;','alt'=>'Activity Loader','id'=>'loader-image'));?>
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
  </section></div>
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
			$('#loader_pagination').removeClass('no-more-activities').html('Load more results');
		}
		$('#loader_pagination').bind('click',function(){
			$('#loader-image').show();
			var SearchVendorList = ($("#SearchVendorList").val()=='')?'vendor_id':$("#SearchVendorList").val();
		 	var SearchServiceTypeList = ($("#SearchServiceTypeList").val()=='')?'service_type':$("#SearchServiceTypeList").val();
		 	var SearchSortPrice = ($("#SearchSortPrice").val()=='')?'sortbyprice':$("#SearchSortPrice").val();
			
			if(pages >= (page+1)){
				 if(loading_start===0){
                    loading_start = 1;
                    page++;
                    $.ajax({ 
						url:'<?=Router::url(array('plugin'=>'vendor_manager','controller'=>'vendors','action'=>'vendor_list','page:'));?>'+page,
                        async:false,
                        timeout:5,
                        success:function(data){
							loading_start = 0;
							$('#loader-image').hide();
							$('.vendor-listing:last').after(data );
							/*$('.tile').contenthover({
								//data_selector: '.contenthover',
								//effect:'slide',
								//slide_direction: 'left',
								//slide_speed:300,
								overlay_background:'#000',
								overlay_opacity:1
							});*/
							
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
						}
                    });
                }
            }
			
		});
	});
	
</script>	
