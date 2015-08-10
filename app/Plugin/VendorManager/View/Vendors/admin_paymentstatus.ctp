<div style="background:#f6f6f6; padding:8PX; max-height:550px; overflow-y:auto;" id="view2">
	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>Name</b></div>
		<div align="justify;" style="float:left; width:400px;"><?=$payment['Vendor']['fname'].' '.$payment['Vendor']['lname'];?></div>
		<div style="clear:both;"></div>
	</div>
	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>Contact No.</b></div>
		<div align="justify;" style="float:left; width:400px;"><?=$payment['Vendor']['phone']?></div>
		<div style="clear:both;"></div>
	</div>
	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>Email ID.</b></div>
		<div align="justify;" style="float:left; width:400px;"><?=$payment['Vendor']['email']?></div>
		<div style="clear:both;"></div>
	</div>
	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>Payment Type.</b></div>
		<div align="justify;" style="float:left; width:400px;"><?=$payment['Payment']['payment_mode']?></div>
		<div style="clear:both;"></div>
	</div>
	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>Total Amount.</b></div>
		<div align="justify;" style="float:left; width:400px;"><?=$payment['Payment']['total_amount']?></div>
		<div style="clear:both;"></div>
	</div>
	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>Paid Amount.</b></div>
		<div align="justify;" style="float:left; width:400px;"><?=$payment['Payment']['payment_amount']?></div>
		<div style="clear:both;"></div>
	</div>
	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>Transaction ID.</b></div>
		<div align="justify;" style="float:left; width:400px;"><?=$payment['Payment']['transaction_id']?></div>
		<div style="clear:both;"></div>
	</div>
	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>Payment Date.</b></div>
		<div align="justify;" style="float:left; width:400px;"><?=date('d M Y',strtotime($payment['Payment']['payment_date']));?></div>
		<div style="clear:both;"></div>
	</div>
	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>IP Address.</b></div>
		<div align="justify;" style="float:left; width:400px;"><?=$payment['Payment']['ip_address']?></div>
		<div style="clear:both;"></div>
	</div>
	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>Payment Status</b></div>
		<div align="justify;" style="float:left; width:400px;">
			<?php if($payment['Payment']['status']=='0') {
					echo 'Not Completed';
				}elseif($payment['Payment']['status']=='1'){
					echo 'Completed';
				}else{
					echo 'Pending';
				}
			?>
		</div>
		<div style="clear:both;"></div>
	</div>

</div>
