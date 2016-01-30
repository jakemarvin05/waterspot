<aside class="similar-listings">
	<header class="title-header">
		<h3>Other Activities by <?=ucfirst(!empty($vendor_details['Vendor']['bname'])?$vendor_details['Vendor']['bname']:$vendor_details['Vendor']['fname']." ".$vendor_details['Vendor']['lname']) ?></h3>
	</header>
	<div class="similar-listing-content">
		<?php  if(!empty($service_detail['VendorService'])){?>
			<div id="similar-listing-scrollable" style="max-height: 395px;">
				<ul>
					<?php foreach($service_detail['VendorService'] as $related_service){ ?>
						<li>
							<? $path=WWW_ROOT.'img'.DS.'service_images'.DS;
							 $imgArr = array('source_path'=>$path,'img_name'=>$related_service['Service']['image'],'width'=>600,'height'=>400,'noimg'=>$setting['site']['site_noimage']);
							 $resizedImg = $this->ImageResize->ResizeImage($imgArr);
							 echo $this->Html->link($this->Html->image($resizedImg,array('border'=>'0','alt'=>$related_service['Service']['service_title'],'title'=>$related_service['Service']['service_title'],'class'=>'listing-img')),array('plugin'=>false,'controller'=>'activity','action'=>'index',$related_service['Service']['id']),array('escape'=>false)) ; ?>
							 
							<div class="listing-info">
								<h4>
									<?=$this->Html->link($this->Format->Headingsubstring($related_service['Service']['service_title'],40),array('plugin'=>false,'controller'=>'activity','action'=>'index',$related_service['Service']['id']),array('escape'=>false)) ; ?>
								</h4>
								<h6>Price $<?php echo number_format($related_service['Service']['service_price'],2);?></h6>
							</div>
						</li>
					<? }?>
				</ul>
			</div>
		<? }else{?>
			<div class="no-records-found">No more activities found.</div>
		<? } ?>
	</div>
</aside>
