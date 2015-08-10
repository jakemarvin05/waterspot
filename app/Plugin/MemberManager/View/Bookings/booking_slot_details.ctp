<?=$this->Html->css('fancybox-inner.css');?>

<h3>Slot Details</h3>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<th width="15%">S. No.</th>
		<th width="30%">Date</th>
		<th width="50%">Slot Timings</th>
	</tr>
	<? if(!empty($booking_slots)) {?>
		<? foreach($booking_slots as $key=>$booking_slot) {?>
			<tr>
				<td><?=($key+1); ?>.</td>
				<td><?=date(Configure::read('Calender_format_php'),strtotime($booking_slot['BookingSlot']['start_time'])); ?></td>
				<td><? echo $this->Time->meridian_format($booking_slot['BookingSlot']['start_time']);?> To <?=$this->Time->end_meridian_format($booking_slot['BookingSlot']['end_time']);?></td>
			</tr>
		<? } ?>
	<? } else { ?>
		<tr class="no-details">
			<td colspan="3">There are no booking slots</td>
		</tr>
	<? } ?>
</table>