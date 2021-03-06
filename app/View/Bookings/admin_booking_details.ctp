<? $payment_status=Configure::read('payment_status'); ?>
<article>
    <header>
	<h2>Order Details</h2>
    </header>
</article>  
<div class="hr-line"></div>
<div class="clear"></div>

<div class="middle-area">
    <p class="details" style="float:left; padding:0 15px 20px 0;">
	<strong>Order Number:</strong> 
	<?if(!empty($customer_detail['Booking']['ref_no'])){
	    echo $customer_detail['Booking']['ref_no'];
	} ?>
    </p>
    <p class="details" style="float:left; padding:0 15px 20px 0;">
	<strong>Order Status:</strong>
	<? echo ($payment_status[$customer_detail['Booking']['status']]); ?>
    </p>
    <p class="details" style="float:left; padding:0 15px 20px 0;">
	<strong>Transction ID:</strong>
	<?if(!empty($customer_detail['Booking']['transaction_id'])){
	    echo $customer_detail['Booking']['transaction_id'];
	} ?>
    </p>
    <table width="100%" cellpadding="0" cellspacing="0" border="0" class="admin-content">
	<tr>
	    <th width="5%">S.No.</th>
	    <th width="20%">Service Name</th>
	    <th width="15%">Location</th>
	    <th width="10%">From</th>
	    <th width="10%">To</th>
	    <th width="10%">Vendor Details</th>
	    <th width="10%">Invited Members</th>
	    <th width="5%">Price ($)</th>
	    <th width="5%">Slots</th>
	    <th width="5%">VAS</th>
	</tr>
	<? if(!empty($order_details )){?>
	    <? foreach($order_details as $key=>$order_detail) { ?>
		<tr>
		    <td><?=$key+1; ?>.</td>
		    <td><?=$order_detail['BookingOrder']['service_title']?></td>
		    <td><?=$order_detail['BookingOrder']['location_name']?></td>
		    <td><?=date(Configure::read('Calender_format_php'),strtotime($order_detail['BookingOrder']['start_date'])); ?></td>
		    <td><?=date(Configure::read('Calender_format_php'),strtotime($order_detail['BookingOrder']['end_date'])); ?></td>
		    <td>
			<?=$this->Html->link($this->Html->image('view.png',array('alt'=>'','title'=>'View Vendor Details')),array('plugin'=>false,'controller'=>'bookings','action'=>'booking_vendor_details',$order_detail['BookingOrder']['id']),array('escape' => false,'class'=>'view fancybox admin-view-pop','title'=> __('View'),'rel'=>'tooltip'));?>
		    </td>
		    <td>
			<? if($order_detail['BookingOrder']['no_participants']!=1){ ?>
			    <?=$this->Html->link($this->Html->image('view.png',array('alt'=>'','title'=>'View Member Details')),array('plugin'=>false,'controller'=>'bookings','action'=>'booking_member_invite_details',$order_detail['BookingOrder']['id']),array('escape' => false,'class'=>'view fancybox admin-view-pop','title'=> __('View'),'rel'=>'tooltip'));?>
			    <span class="number">(<?=($order_detail['BookingOrder']['no_participants']-1) ?>)</span>
			<?} else {
			    echo "No Invited.";
			}?>
		    </td>
		    <td><?=number_format(($order_detail['BookingOrder']['total_amount']),2); ?></td>
		    <td>
			<?=$this->Html->link($this->Html->image('view.png',array('alt'=>'','title'=>'View Slots Details')),array('plugin'=>false,'controller'=>'bookings','action'=>'booking_slot_details',$order_detail['BookingOrder']['id']),array('escape' => false,'class'=>'view fancybox admin-view-pop','title'=> __('View'),'rel'=>'tooltip'));?>
		    </td>
		    <td>
			<?=$this->Html->link($this->Html->image('view.png',array('alt'=>'View Slots Details','title'=>'View Slots Details')), array('plugin'=>false,'controller'=>'bookings','action'=>'booking_vas_details',$order_detail['BookingOrder']['id']), array('escape' => false,'class'=>'view fancybox admin-view-pop','title'=> __('View'),'rel'=>'tooltip'))?>
		    </td>
		</tr>
	    <? } ?>
	<? } else { ?>
	    <td colspan="11">There are no booking</td>
	<? } ?>
    </table>
    <? if(!empty($order_details)) {?>
	<h3 class="small" style="margin:20px 0 10px;">Customer Information</h3>
	<table width="100%" cellpadding="0" cellspacing="0" border="0" class="admin-content">
	    <tr>
		<th width="24%">First Name</th>
		<th width="25%">Last Name</th>
		<th width="25%">Email</th>
		<th width="25%">Phone</th>
	    </tr>
	    <tr>
		<td><?=$customer_detail['Booking']['fname']?></td>
		<td><?=$customer_detail['Booking']['lname']?></td>
		<td><a href="mailto:<?=$customer_detail['Booking']['email'];?>"><?=$customer_detail['Booking']['email'];?></a></td>
		<td><a href="callto:<?=$customer_detail['Booking']['phone'];?>"><?=$customer_detail['Booking']['phone'];?></a></td>
	    </tr>
	</table>
    <? }?>
    <? if(!empty($order_details)) { ?>
	<h3 class="small" style="margin:20px 0 10px;">Comment</h3>
	<div><? echo (!empty($customer_detail['Booking']['order_message']))? $customer_detail['Booking']['order_message']:'There are no order message';?></div>
    <? }?>
</div>
