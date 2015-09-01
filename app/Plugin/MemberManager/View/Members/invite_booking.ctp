<div class="container-fluid member-panel invite-booking">
<link rel="stylesheet" type="text/css" href="/css/fancybox/jquery.fancybox(new).css" />
<script type="text/javascript" src="/js/jquery.fancybox.js"></script>
<script type="text/javascript">
$( document ).ready(function() {
		 $( "#ActivityStartDate" ).click();
		});
		$(document).ready(function() {
			$('.fancybox').fancybox();
		});
</script>

<div class="hr-line"></div>
<div class="clear"></div>
<?=$this->element('breadcrumbs');?>
<h2 class="page-title">Invite <span style="color: #000;">Details</span></h2>

<?=$this->element('MemberManager.left-member-panel');?>
<div class="right-area  col-sm-9 col-xs-12">

<? if(!empty($customer_detail)) {?>
	<h3 class="dashboard-heading">Invited By</h3>
	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="dashboard-content">
		<tr>
			<th width="25%">First Name</th>
			<th width="25%">Last Name</th>
			<th width="25%">Email</th>
			<th width="24%">Phone</th>
		</tr>
		<tr>
			<td class="align-center"><?=$customer_detail['Booking']['fname']?></td>
			<td class="align-center"><?=$customer_detail['Booking']['lname']?></td>
			<td class="align-center"><a href="mailto:<?=$customer_detail['Booking']['email'];?>"><?=$customer_detail['Booking']['email'];?></a></td>
			<td class="align-center"><a href="callto:<?=$customer_detail['Booking']['phone'];?>"><?=$customer_detail['Booking']['phone'];?></a></td>
		</tr>
	</table>
<? }?>

<h3 class="dashboard-heading">Invite Details</h3>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="dashboard-content">
	<tr>
		<th width="5%">S.No.</th>
		<th width="15%">Service Name</th>
		<th width="20%">Location</th>
		<th width="10%">From</th>
		<th width="10%">To</th>
		<th width="17%">Invited Members</th>
		<th width="5%">Slots</th>
		<!--<th width="5%">VAS</th>-->
		<th width="13%">Price ($)</th>
	</tr>
	<? if(!empty($order_detail )){?>
		<tr>
			<td class="align-center">1.</td>
			<td><?=$order_detail['BookingOrder']['service_title']?></td>
			<td><?=$order_detail['BookingOrder']['location_name']?></td>
			<td class="align-center"><?=date(Configure::read('Calender_format_php'),strtotime($order_detail['BookingOrder']['start_date'])); ?></td>
			<td class="align-center"><?=date(Configure::read('Calender_format_php'),strtotime($order_detail['BookingOrder']['end_date'])); ?></td>
			<td class="align-center">
				   <?=$this->Html->link("<i class=\"fa fa-search\"></i>",array('plugin'=>'member_manager','controller'=>'bookings','action'=>'booking_member_invite_details',$order_detail['BookingOrder']['id']),array('escape' => false,'class'=>'fancybox fancybox.iframe'));?>
				   <span class="number">(<?=($order_detail['BookingOrder']['no_participants']-1)?>)</span>
			</td>
			<td class="align-center"><?=$this->Html->link("<i class=\"fa fa-search\"></i>",array('plugin'=>'member_manager','controller'=>'bookings','action'=>'booking_slot_details',$order_detail['BookingOrder']['id']),array('escape' => false,'class'=>'fancybox fancybox.iframe'));?></td>
<!--			<td class="align-center">--><?//=$this->Html->link("<i class=\"fa fa-search\"></i>",array('plugin'=>'member_manager','controller'=>'bookings','action'=>'booking_vas_details',$order_detail['BookingOrder']['id']),array('escape' => false,'class'=>'fancybox fancybox.iframe'));?><!--</td>-->
			<td class="align-right"><?=number_format(($order_detail['BookingOrder']['total_amount']),2); ?></td>
		</tr>
		<tr class="subtotal">
			<td colspan="7" class="align-right">Sub-Total</td>
			<td colspan="1" class="align-right">$<?=number_format(($order_detail['BookingOrder']['total_amount']),2); ?></td>
		</tr>
	<? }else{ ?>
		<tr class="no-details">
			<td colspan="8">No invitation details are available</td>
		</tr>
	<? }?>
</table>
	<?=$this->form->create('BookingParticipate',array('name'=>'booking','id'=>'booking_form','url' =>array('plugin'=>'payment_manager','controller'=>'payments','action'=>'invite_payment',$order_detail['BookingOrder']['id']),'novalidate'=>true,'class'=>'quick-contacts1'));?>

	<?=$this->Form->hidden('booking_order_id',array('value'=>$order_detail['BookingOrder']['id']))?>
	<input class="dashboard-buttons" value="Pay Now" type="submit">
<?=$this->form->end(); ?>

<div class="clear"></div>
</div>
</div>
<script type='text/javascript'>
	$(document).ready(function () {
		sameHeight('left-area','right-area');
	});
</script>