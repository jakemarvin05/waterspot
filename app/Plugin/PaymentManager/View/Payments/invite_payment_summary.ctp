
<div class="container-fluid wrapper payment-page summary">

	<header class="page-header text-center">
		<p class="beforeHeader">Congratulation!</p>
		<h1 class=" headerAlt">Booking Summary</h1>
	</header>

<div class="middle-area">

	<div class="row text-center">
		<div class="col-sm-6 col-sm-offset-3">

	<div class="booking-status-message">
		<?=($booking_detail['Booking']['status']==1)?'Your booking has been completed successfully.<br />Please find the booking details below:':'Your booking was not completed successfully.<br />Please try again.';?>
	</div>

	<div class="transactionid-info">
		<strong>Transaction ID</strong> <?=$booking_detail['Booking']['transaction_id'];?>
	</div>
		</div>
	</div>
	<div class="row">

		<div class="col-sm-8 col-sm-offset-2">
	<h3 class="dashboard-heading">Personal Details</h3>
	<table width="100%" border="1" bordercolor="#ccc"  cellpadding="0" cellspacing="0" class="dashboard-content">
		<tr>
			<th width="32%">Name</th>
			<th width="32%">Email</th>
			<th width="32%">Phone</th>
		</tr>
		<tr>
			<td><?=$booking_detail['Booking']['fname']." ".$booking_detail['Booking']['lname'];?></td>
			<td><?=$booking_detail['Booking']['email'];?></td>
			<td><?=$booking_detail['Booking']['phone'];?></td>
		</tr>
	</table>
		</div>
	</div>
	<div class="row">

		<div class="col-sm-8 col-sm-offset-2">
	<h3 class="dashboard-heading">Booking Details</h3>
	<table width="100%"  border="1" bordercolor="#ccc" cellpadding="0" cellspacing="0" class="dashboard-content">
		<tr>
			<th class="align-left" width="10%">Vendor</th>
			<th width="10%">Service Name</th>
			<th width="15%">Activity</th>
			<th width="10%">Cost Sharing</th>
			<th width="11%">Date</th>
			<th width="11%">Time</th>
			<th width="9%">Participant(s)</th>
			<th width="8%">Price ($)</th>
		</tr>
		<? if(!empty($booking_order_detail)){
			$total_amount=0;	
			$slot_details=array();
			if(!empty($booking_order_detail['BookingOrder']['slots'])){ ?>
				<? $slots=json_decode($booking_order_detail['BookingOrder']['slots'],true);
				$slot_details=array();
				foreach($slots['Slot'] as $key=>$slot) {
					$slot_details[]=$this->Time->meridian_format($slot['start_time']). " To ".$this->Time->end_meridian_format($slot['end_time']);
				}
			}
			$total_amount+=$booking_order_detail['BookingOrder']['price']+$booking_order_detail['BookingOrder']['value_added_price'];
			$vas_details=json_decode($booking_order_detail['BookingOrder']['value_added_services'],true);
				$booked_vas_details='';
				if(!empty($vas_details)){
						$booked_vas_details='';
						foreach($vas_details as $key=>$vas){
							$booked_vas_details.=
							'<div>'.$vas['value_added_name'].'&nbsp;&nbsp;&nbsp;($'.$vas['value_added_price'].')'.'</div><br/>';
						}
				}
				$participant_emails='';
				if(!empty($booking_order_detail['BookingOrder']['invite_friend_email'])){
					$emails=json_decode($booking_order_detail['BookingOrder']['invite_friend_email'],true);
					foreach($emails as $email){
						 $participant_emails.=$email.'<br/>';
					}
					//$participant_emails=$implode('<br>',$participant_emails);
					//echo $participant_emails;
				}else{
					$participant_emails='No participant.';
				}
			?>
			<tr>
				<td><?=$booking_order_detail['BookingOrder']['vendor_name']; ?></td>
				<td><?=$booking_order_detail['BookingOrder']['servicetype']; ?></td>
				<td><?=$booking_order_detail['BookingOrder']['service_title']; ?></td>
				<td class="align-center">
					<?=date(Configure::read('Calender_format_php'),strtotime($booking_order_detail['BookingOrder']['start_date'])) ?> To <?=date(Configure::read('Calender_format_php'),strtotime($booking_order_detail['BookingOrder']['end_date'])) ?>
				</td>
				<td class="align-center">
					<? if(!empty($slot_details)){
							echo implode(',',$slot_details);
						}else{
							echo "Full Day";
						}
					?>
				</td>
				<td class="align-center"><?=$booked_vas_details?></td>
				<td class="align-center"><?=$participant_emails?></td>
				<td class="align-right">
					$<?=number_format(($booking_order_detail['BookingOrder']['total_amount']),2);?>
				</td>
			</tr>
		<? } // end of if ?>
	</table>
			<div class="spacer"></div>
		</div></div>
</div>
</div>