
<?php
$i = $this->paginator->counter('{:start}');?>

<? if(!empty($booking_details)) {?>
	<? foreach($booking_details as $booking_detail){ ?>
		<tr>
			<td class="align-center"><?=$i++;?></td>
			<td class="align-center"><?=$booking_detail['Booking']['ref_no']?></td>
			<td><?=$booking_detail['Booking']['fname']." ".$booking_detail['Booking']['lname']?></td>
			<td><?=$booking_detail['Booking']['email']?></td>
			<td><?=$booking_detail['Booking']['phone']?></td>
			<td><?=($booking_detail['Booking']['status']==1)?'Completed':'Not Completed';?></td>
			<td class="align-center"><?=$this->Html->link('<i class="fa fa-search"></i>',array('plugin'=>'member_manager','controller'=>'bookings','action'=>'booking_details',$booking_detail['Booking']['ref_no']),array('escape' => false));?></td>
		</tr>
	<? }  //end of foreach ?>
<? } //end of if ?>
