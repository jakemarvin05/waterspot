<?=$this->Html->css('fancybox-inner.css');?>

<h3>Invited Member Details</h3>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<th width="15%">S. No.</th>
		<th width="50%">Email</th>
		<th width="30%">Payment Status</th>
	</tr>
	<? if(!empty($invites_friend_details)) {?>
		<? foreach($invites_friend_details as $key=>$invites_friend_detail) {?>
			<tr>
				<td><?=($key+1); ?>.</td>
				<td><a href="mailto:<?=$invites_friend_detail['BookingParticipate']['email']?>"><?=$invites_friend_detail['BookingParticipate']['email']?></a></td>
				<td><? echo ($invites_friend_detail['BookingParticipate']['status']==1)?'Completed':'Pending'?></td>
			</tr>
		<? } ?>
	<? } else { ?>
	<tr class="no-details">
		<td colspan="3">There are no invites friends details</td>
	</tr>
<? } ?>		 
</table>