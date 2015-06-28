<div style="background:#f6f6f6; padding:8PX; max-height:550px; overflow-y:auto;" id="view2">
	
	
	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>Business Name</b></div>
		<div align="justify;" style="float:left; width:400px;"><?=$vendor['Vendor']['bname'];?></div>
		<div style="clear:both;"></div>
	</div>
	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>Name</b></div>
		<div align="justify;" style="float:left; width:400px;"><?=$vendor['Vendor']['fname'].' '.$vendor['Vendor']['lname'];?></div>
		<div style="clear:both;"></div>
	</div>
	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>About us</b></div>
		<div align="justify;" style="float:left; width:400px;"><?=$vendor['Vendor']['about_us'];?></div>
		<div style="clear:both;"></div>
	</div>

	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>E-Mail</b></div>
		<div align="justify;" style="float:left; width:400px;"><?=$vendor['Vendor']['email']?></div>
		<div style="clear:both;"></div>
	</div>
	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>Contact No.</b></div>
		<div align="justify;" style="float:left; width:400px;"><?=$vendor['Vendor']['phone']?></div>
		<div style="clear:both;"></div>
	</div>
	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>Active</b></div>
		<div align="justify;" style="float:left; width:400px;"><?php if($vendor['Vendor']['active']=='1') { echo 'Yes'; } else { echo 'No'; }?></div>
		<div style="clear:both;"></div>
	</div>
	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>Approval</b></div>
		<div align="justify;" style="float:left; width:400px;"><?php if($vendor['Vendor']['approval']=='1') { echo 'Yes'; } else { echo 'No'; }?></div>
		<div style="clear:both;"></div>
	</div>
	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>Payment Amount</b></div>
		<div align="justify;" style="float:left; width:400px;"><?=Configure::read('currency');?> <?php  echo number_format($vendor['Vendor']['payment_amount'],2);?></div>
		<div style="clear:both;"></div>
	</div>
	
	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>Commission Amount</b></div>
		<div align="justify;" style="float:left; width:400px;"><?=Configure::read('currency');?> <?php  echo number_format($vendor['Vendor']['commission'],2);?></div>
		<div style="clear:both;"></div>
	</div>
	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>Profile Image</b></div>
		<div align="justify;" style="float:left; width:400px;">
			<?php 
				/* Resize Image */
				if(isset($vendor['Vendor']['image'])) {
					$imgArr = array('source_path'=>Configure::read('VendorProfile.SourcePath'),'img_name'=>$vendor['Vendor']['image'],'width'=>80,'height'=>80,'noimg'=>$setting['site']['site_noimage']);
					$resizedImg = $this->ImageResize->ResizeImage($imgArr);
					echo $this->Html->image($resizedImg,array('border'=>'0'));
				}
			?>
		</div>
		<div style="clear:both;"></div>
	</div>
</div>
