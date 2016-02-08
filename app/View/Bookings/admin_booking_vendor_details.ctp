<h3 class="small">Vendor Details</h3>
<table width="100%" cellpadding="0" cellspacing="0" border="0" class="admin-content">
	<tr>
		<th width="30%">Name</th>
		<th width="30%">Email</th>
		<th width="26%">Phone</th>
		<th width="10%">More Details</th>
	</tr>
	<? if(!empty($vendor_details)){?>
		<tr>
			<td class="align-center"><?=$vendor_details['BookingOrder']['vendor_name'];?></td>
			<td class="align-center"><a href="mailto:<?=$vendor_details['BookingOrder']['vendor_email']?>"><?=$vendor_details['BookingOrder']['vendor_email']?></a></td>
			<td class="align-center"><?=$vendor_details['BookingOrder']['vendor_phone'];?></td>
			<td class="align-center"><?=$this->Html->link('view', array('plugin'=>'vendor_manager','controller' => 'vendors', 'action' => 'index', $vendor_details['BookingOrder']['vendor_email']), array('escape' => false,'title'=> __('View'),'target'=>'_blank'))?></td>
		</tr>
	<? } else { ?>
		<tr class="no-details">
			<td class="align-center" colspan="4">No friends are invited.</td>
		</tr>
	<? } ?>		 
</table>