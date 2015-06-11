<h3 class="small">Slots Details</h3>
<table width="100%" cellpadding="0" cellspacing="0" border="0" class="admin-content">
	<tr>
		<th width="10%">S.No.</th>
		<th width="30%">Date</th>
		<th width="55%">Slot Timings</th>
	</tr>
	<? if(!empty($booking_slots)){?>
		<? foreach($booking_slots as $key=>$booking_slot) {?>
			<tr>
				<td class="align-center"><?=($key+1); ?>.</td>
				<td class="align-center"><?=date(Configure::read('Calender_format_php'),strtotime($booking_slot['BookingSlot']['start_time'])); ?></td>
				<td class="align-center"><? echo $this->Time->meridian_format($booking_slot['BookingSlot']['start_time']);?> To <?=$this->Time->end_meridian_format($booking_slot['BookingSlot']['end_time']);?></td>
			</tr>
		<? } // end of foreach ?>	
	<? } else { ?>
		<tr class="no-details">
			<td class="align-center" colspan="3">There are no booking slots.</td>
		</tr>
	<? } ?>	
</table>