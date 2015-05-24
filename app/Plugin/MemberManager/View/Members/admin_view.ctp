<div style="background:#f6f6f6; padding:8PX; max-height:550px; overflow-y:auto;" id="view2">
	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>Name</b></div>
		<div align="justify;" style="float:left; width:400px;"><?=$member['Member']['first_name'].' '.$member['Member']['last_name'];?></div>
		<div style="clear:both;"></div>
	</div>
		<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>E-Mail</b></div>
		<div align="justify;" style="float:left; width:400px;"><?=$member['Member']['email_id']?></div>
		<div style="clear:both;"></div>
	</div>
	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>Contact No.</b></div>
		<div align="justify;" style="float:left; width:400px;"><?=$member['Member']['phone']?></div>
		<div style="clear:both;"></div>
	</div>
	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>Active</b></div>
		<div align="justify;" style="float:left; width:400px;"><?php if($member['Member']['active']=='1') { echo 'Yes'; } else { echo 'No'; }?></div>
		<div style="clear:both;"></div>
	</div>	
</div>
