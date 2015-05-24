<div class="hr-line"></div>
<div class="clear"></div>
<div class="bredcrum"> <?=$this->element('breadcrumbs');?></div>
<h2 class="page-title">Dashboard</h2>

<?=$this->element('MemberManager.left-member-panel');?>
<div class="right-area">
	<div class="service">
		<?php if(!empty($invite_details)){ ?>
		<h3 class="dashboard-heading">Pending Invites</h3>
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="dashboard-content">
			<tr>
				<th width="5%">S.No.</th>
				<th width="28%">Inviter Email ID</th>
				<th width="38%">Service Title</th>
				<th width="10%">Price ($)</th>
				<th width="13%">Payment</th>
			</tr>
			
			<?php 
			$i=1;
			foreach($invite_details as $invite_detail){ ?>
			<tr>
				<td class="align-center"><?=$i; ?></td>
				<td><?=$invite_detail['BookingParticipate']['invite_email']; ?></td>
				<td><?=$invite_detail['BookingOrder']['service_title']; ?></td>
				<td class="align-right"><?=number_format($invite_detail['BookingParticipate']['amount'],2); ?></td>
				<td class="align-center"><?=$this->Html->link('Book Now',array('plugin'=>false,'controller'=>'members','action'=>'invite_booking',$invite_detail['BookingParticipate']['booking_order_id']),array('escape' => false));?></td>
			</tr>
			<?php $i++;?>
			<? } ?>
			 <? $total_booking=$this->Paginator->counter(array('format' => '{:count}'));?>
			<? } ?>
		</table>
		
		<div class="clear"></div>
		
		<h3 class="dashboard-heading">My Bookings</h3>
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="dashboard-content">
			<tr>
				<th width="5%">S.No.</th>
				<th width="10%">Order No.</th>
				<th width="20%">Name</th>
				<th width="25%">Email</th>
				<th width="12%">Phone</th>
				<th width="12%">Status</th>
				<th width="10%">View</th>
			</tr>
			<? if(!empty($booking_details)){ ?>
				<? foreach($booking_details as $key=>$booking_detail){ ?>
				<tr>
					<td class="align-center"><?=$key+1;?></td>
					<td><?=$booking_detail['Booking']['ref_no']?></td>
					<td><?=$booking_detail['Booking']['fname']." ".$booking_detail['Booking']['lname']?></td>
					<td><?=$booking_detail['Booking']['email']?></td>
					<td><?=$booking_detail['Booking']['phone']?></td>
					<td><?=($booking_detail['Booking']['status']==1)?'Completed':'Pending';?></td>
					<td class="align-center"><?=$this->Html->link($this->Html->image('view.png',array('alt'=>'View Detail','title'=>'View Detail')),array('plugin'=>'member_manager','controller'=>'bookings','action'=>'booking_details',$booking_detail['Booking']['ref_no']),array('escape' => false));?></td>
				</tr>
				<? } ?>
				<? $total_booking=$this->Paginator->counter(array('format' => '{:count}'));?>
				<? if($total_booking>=5){ ?>
					<tr class="view-all">
						<td colspan="7"><?=$this->Html->link('View All',array('plugin'=>'member_manager','controller'=>'bookings','action'=>'booking_list'),array('class'=>'dashboard-buttons'));?></td>
					</tr>
				<? } ?>	
			<? } else {?>
				<tr class="no-details">
					<td colspan='7'>There are no booking details</td>
				</tr>
			<? }?>
		</table>
	</div>
	<div class="clear"></div>
</div>
