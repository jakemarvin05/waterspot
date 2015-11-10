<?php
$i = $this->paginator->counter('{:start}');?>

<? if(!empty($vendor_services)){ ?>	
		<? foreach($vendor_services as $key=>$vendor_service) { ?>
			<div class="vendor-listing col-sm-4 col-xs-6">
				<div class="contentvisible contentselector">
					<div class="tile">
						<?php 
						/* Resize Image */
							if(isset($vendor_service['Vendor']['image'])) {
								$imgArr = array('source_path'=>Configure::read('VendorProfile.SourcePath'),'img_name'=>$vendor_service['Vendor']['image'],'width'=>290,'height'=>220,'noimg'=>$setting['site']['site_noimage']);
								$resizedImg = $this->ImageResize->ResizeImage($imgArr);
								echo $this->Html->image($resizedImg,array('border'=>'0'));
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
							<h4>
								<?=$this->Format->Headingsubstring((!empty($vendor_service['Vendor']['bname'])?$vendor_service['Vendor']['bname']:$vendor_service['Vendor']['name']),27);?>
							</h4>
					</div>

					<div class="vendor-rating-wrapper">

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

						
						if($ratingMark > 0):

						?>

						<div class="rating" style="background-position: <?php echo -100+($ratingMark*100); ?>px 0px"></div>

						<?php
						else:
						?>
						<span class="rating-label"> No ratings yet</span> 
						<?php
						endif;
						?>
						
						<div class="clearfix"></div>
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
		<? $i++;  ?>	
	<? }?> 
	
<? } //end of if ?>
<script type="text/javascript">
$(function(){ 
	 $('input.star').rating(); 
 });
</script>
