<?php
$i = $this->paginator->counter('{:start}');
if(!empty($booking_details)) {
    foreach ($booking_details as $booking_detail) { ?>
	<li>
		<table width="100%">
			<tr>
				
				
				<td width="5%"><?php echo $i++; ?></td>
				<td width="10%"><?=$booking_detail['Booking']['ref_no']?></td>
				<td width="15%"><?=$booking_detail['Booking']['fname']." ".$booking_detail['Booking']['lname']?></td>
				<td width="15%"><?=$booking_detail['Booking']['email']?></td>
				<td width="15%"><?=$booking_detail['Booking']['phone']?></td>
				<td width="15%"><?=$booking_detail['Booking']['transaction_id']?></td>
				<td width="10%">
				<?php if($booking_detail['Booking']['status']=='1') {
						echo $this->Html->image('admin/icons/icon_success.png', array('alt'=>"Completed"));
					}else {
						echo $this->Html->image('admin/icons/icon_error.png', array('alt'=>"Not completed"));
					}
				?>
				 
				
				</td>
				<td width="5%">
					<?=$this->Html->link($this->Html->image('cemera-icon.png',array('alt'=>'View Detail','title'=>'View Detail')),array('plugin'=>false,'controller'=>'bookings','action'=>'booking_details',$booking_detail['Booking']['ref_no']),array('escape' => false));?>
				</td> 
			</tr>
		</table>
	</li>
	<?php } } ?>
