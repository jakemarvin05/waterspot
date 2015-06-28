<?php
$i = $this->paginator->counter('{:start}');?>

<? if(!empty($vendor_services)){ ?>	
		<? foreach($vendor_services as $key=>$vendor_service) { ?>
			<div class="vendor-listing">
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
						</div>
						<div class="tile-info"> 
							<h4>
								<?=$this->Format->Headingsubstring((!empty($vendor_service['Vendor']['bname'])?$vendor_service['Vendor']['bname']:$vendor_service['Vendor']['name']),27);?>
							</h4>
							<div class="vendor-rating-wrapper">
							<? if(!empty($vendor_service['Vendor']['rating'])){ ?>
						
								<?php $ratings = range(1,10); ?>
								<?php foreach($ratings as $rating){ ?>
									<input type="radio" value="<?php echo $rating; ?>" name="test-4-rating-<?php echo $i; ?>" class="star {split:2}" disabled="disabled" <?php echo (round($vendor_service['Vendor']['rating'])==$rating)?'checked="checked"':'';?> />
								<?php } ?>
							<? }else{ ?>
								<div class="no-rating">No feedback yet</div>
							<? } ?>
							</div>	
						</div>
				</div>
				<div class="contenthover">
					<div class="activity-tags">
					<? foreach($vendor_service['ServicesType'] as $key=>$service_type) {?>
						<span>
							<?php echo $this->Html->link($service_type['ServiceType']['name'],array('plugin'=>false,'controller'=>'activity','action'=>'activities',$vendor_service['Vendor']['id'],$service_type['ServiceType']['id']));?>
						</span>
					<? } ?>
					</div>
					<?php echo $this->Html->link('View all activities',array('plugin'=>'vendor_manager','controller'=>'vendors','action'=>'activities',$vendor_service['Vendor']['id']),array('class'=>'view-all-tags'));?> 
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
