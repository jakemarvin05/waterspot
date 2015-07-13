<script type="text/javascript">
	$(function(){
		$('.tile').contenthover({
			//data_selector: '.contenthover',
			//effect:'slide',
			//slide_direction: 'left',
			//slide_speed:300,
			overlay_opacity: 1,
			overlay_background: '#000'
		});
	});
</script>
<div class="container-fluid vendors-page">
	<br><br><br><br><br>
	<header class="page-header">
		<p class="beforeHeader">See what they offer</p>
		<h1 class=" headerAlt">Vendor Activities</h1>
	</header>

<div class="search-listing">
	<!-- <div class="search"><span> Search</span><input type="search"></div>-->
	<div class="row">

		<div id='sort_by_price' class="ajax-loder" style="display:none">
			<?php echo $this->Html->image('loader-2.gif', array('alt' => 'loading..'));?>
		</div>

		<div class="listing-boxes row">
			<? if(!empty($vendor_service_list)) { ?>
				<?php $i = $this->paginator->counter('{:start}'); ?>
				<? foreach($vendor_service_list as $service_list) { ?>
					<div class="vendorwise-listing col-sm-4 col-xs-12">
						<div class="contentvisible contentselector">
							<div class="tile">
								<? $path=WWW_ROOT.'img'.DS.'service_images'.DS;
										$imgArr = array('source_path'=>$path,'img_name'=>$service_list['image'],'width'=>290,'height'=>220,'noimg'=>$setting['site']['site_noimage']);
										$resizedImg = $this->ImageResize->ResizeImage($imgArr);
										echo $this->Html->image($resizedImg,array('border'=>'0','alt'=>$service_list['Service']['service_title'])); ?>
								<div class="price">$<?= number_format($service_list['Service']['service_price'],2)?></div>
								</div>
                                                        <div class="contenthover">
                                                        <div class="short-desc"> <?=$this->Format->Headingsubstring(strip_tags($service_list['Service']['description']),200);?></div>
                                                        <a href="/activity/index/<?=$service_list['Service']['id']?>" class="view-description">Book A Spot</a>
                                                        </div>
							<div class="tile-info"> 
								<h4><?=$this->Format->Headingsubstring($service_list['Service']['service_title'],24);?></h4>
								 <div class="activity-rating-wrapper">
									<? if(!empty($service_list['rating'])){ ?>
										<?php $ratings = range(1,10); ?>
										<?php foreach($ratings as $rating){ ?>
											<input type="radio" value="<?php echo $rating; ?>" name="test-4-rating-<?php echo $i; ?>" class="star {split:2}" disabled="disabled" <?php echo ($service_list['rating']==$rating)?'checked="checked"':'';?> />
										<?php } ?>
									<? }else{ ?>

											<? if(!empty($search_service_list['rating'])){ ?>
												<?php $ratings = range(1,10); ?>
												<?php foreach($ratings as $rating){ ?>

													<input type="radio" value="<?php //echo $rating; ?>" name="test-4-rating-<?php echo $i; ?>" class="star {split:2}" disabled="disabled" <?php echo ($search_service_list['rating']==$rating)?'checked="checked"':'';?>
														/>
												<?php }} ?>
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


								<? } ?>	
								</div>	

							</div>
						</div>
						
					</div>
				<?php $i++; ?>
				<? } ?>
			<? } else { ?>
				<div class="sun-text no-record"> There are no record found.</div>
			<? } ?>
		</div>
		
		<div class="load-more-listings">
			<div class="load-more-row">
				<button class="load-more" id="loader_pagination">Load More results</button>
			</div>
			<div class="load-more-row">
				<?=$this->Html->image('loader-2.gif',array('style'=>'display:none;','alt'=>'Activity Loader','id'=>'loader-image'));?>
			</div>
		</div>

	</div>
	<div class="vendor-area row">

		<div class="row">
			<div class="col-sm-12 col-xs-12"> <h4>About the Vendor</h4>
			<br>
			</div>

		<div class="col-sm-3 col-xs-12 img-holder">
			<?php
				/* Resize Image */
					if(isset($vendor_detail['Vendor']['image'])) {
						$imgArr = array('source_path'=>Configure::read('VendorProfile.SourcePath'),'img_name'=>$vendor_detail['Vendor']['image'],'width'=>290,'height'=>220,'noimg'=>$setting['site']['site_noimage']);
						$resizedImg = $this->ImageResize->ResizeImage($imgArr);
						echo $this->Html->image($resizedImg,array('border'=>'0',  'alt'=>(!empty($vendor_detail['Vendor']['bname'])?$vendor_detail['Vendor']['bname']:$vendor_detail['Vendor']['fname']." ".$vendor_detail['Vendor']['lname'])));
					}
		?></div>
			<div class="col-sm-9 col-xs-12">

			<h5 class="vendor-name"><?=ucfirst(!empty($vendor_detail['Vendor']['bname'])?$vendor_detail['Vendor']['bname']:$vendor_detail['Vendor']['fname']." ".$vendor_detail['Vendor']['lname']); ?></h5>


		<div class="vendorlisting-vendordesc"><?=$vendor_detail['Vendor']['about_us']?></div>
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
         
	</div>

	<div class="clear"></div>
</div>

<div class="clear"></div>

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
			var SearchVendorList = ($("#SearchVendorList").val()=='')?'vendor_id':$("#SearchVendorList").val();
		 	var SearchServiceTypeList = ($("#SearchServiceTypeList").val()=='')?'service_type':$("#SearchServiceTypeList").val();
		 	var SearchSortPrice = ($("#SearchSortPrice").val()=='')?'sortbyprice':$("#SearchSortPrice").val();
			
			if(pages >= (page+1)){
				 if(loading_start===0){
                    loading_start = 1;
                    page++;
                    $.ajax({ 
						url:'<?=Router::url(array('controller' => 'vendors', 'action' => 'activities','plugin'=>'vendor_manager',$vendor_id));?>/'+'/page:'+page,
                        async:false,
                        timeout:5,
                        success:function(data){
							loading_start = 0;
							$('#loader-image').hide();
							$('.vendorwise-listing:last').after(data );
							$('.tile').contenthover({
								//data_selector: '.contenthover',
								//effect:'slide',
								//slide_direction: 'left',
								//slide_speed:300,
								overlay_opacity: 1,
								overlay_background: '#000'
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
