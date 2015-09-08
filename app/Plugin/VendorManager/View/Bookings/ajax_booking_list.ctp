<? $payment_status=Configure::read('payment_status');?>
<?php
$i = $this->paginator->counter('{:start}');?>

<? if(!empty($booking_details)) {?>
	<? foreach($booking_details as $booking_detail){ ?>
		<tr>
			<td class="align-center"><?=$i++;?></td>
			<td><?=$booking_detail['Booking']['ref_no']?></td>
			<td><?=$booking_detail['Booking']['fname']." ".$booking_detail['Booking']['lname']?></td>
			<td><?=$booking_detail['Booking']['email']?></td>
			<td><?=$booking_detail['Booking']['phone']?></td>
			<td><?=($payment_status[$booking_detail['Booking']['status']]);?></td>
			<td class="align-center">
				<?php if ($booking_detail['Booking']['status'] == 1 && $booking_detail['Booking']['vendor_confirm'] == 3): ?>
					<?=$this->Html->link("<i class=\"fa fa-check\"></i>",array('plugin'=>'vendor_manager','controller'=>'bookings','action'=>'accept_paid',$booking_detail['Booking']['id']),array('escape' => false,"class"=>"actions", 'style'=>'float:left;margin:2px 5px;'));?>
					<?=$this->Html->link("<i class=\"fa fa-remove\"></i>",array('plugin'=>'vendor_manager','controller'=>'bookings','action'=>'cancel_paid',$booking_detail['Booking']['id']),array('escape' => false,"class"=>"actions", 'style'=>'float:left;margin:2px 5px;'));?>
				<?php endif; ?>
				<?php if ($booking_detail['Booking']['status'] == 1 && $booking_detail['Booking']['vendor_confirm'] == 2): ?>
					<a href="#" class="actions" style="float:left;margin:2px;color:#D20000;"><i class="fa fa-remove"></i></a>
				<?php endif; ?>
				<?php if ($booking_detail['Booking']['status'] == 1 && $booking_detail['Booking']['vendor_confirm'] == 1): ?>
					<a href="#" class="actions" style="float:left;margin:2px;color:#00D21B;"><i class="fa fa-check"></i></a>
				<?php endif; ?>
				<?=$this->Html->link("<i class=\"fa fa-search\"></i>",array('plugin'=>'vendor_manager','controller'=>'bookings','action'=>'booking_details',$booking_detail['Booking']['ref_no']),array('escape' => false,"class"=>"actions",'style'=>'float:right;margin:2px 5px;'));?>
			</td>
			 
		</tr>
	<? }  //end of foreach ?>
<? } //end of if ?>
