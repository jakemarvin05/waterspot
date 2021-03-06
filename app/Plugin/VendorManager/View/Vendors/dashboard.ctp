<div class="container-fluid vendor-panel">
<? $payment_status=Configure::read('payment_status');?>
<? $total_service=$this->Paginator->counter(array('format' => '{:count}'));?>
<div class="hr-line"></div>
<div class="clear" style="margin-top:80px;"></div>

<h2 class="page-title">Dashboard</h2>

<?=$this->element('VendorManager.left-vendor-panel');?>

<div class="right-area col-sm-9 col-xs-12">
    <div class="service">
                    
		<div class="dashboard-form-row special">
                        <h3 class="dashboard-heading special" style="float: left;">My Services</h3>
			<?=$this->Html->link('Add New Service',array('plugin'=>'vendor_manager','controller'=>'services','action'=>'add_services'),array('class'=>'btn btn-primary','style'=>'margin-bottom:20px; float:right;'));?>
		</div>
		<div class="clear"></div>
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="table table-striped table-bordered">
			<tr>
				<th width="15%">Type</th>
				<th width="20%">Title</th>
				<th width="15%">Image</th>
				<th width="15%">Location</th>
				<th width="10%">Price (<?=Configure::read('currency'); ?>)</th>
				<th width="20%" class="text-center">Action</th>
			</tr>
			<?php if(!empty($services)){ ?>
			<?php foreach($services as $service){ ?>
				<tr>
					<td><?=ucfirst($service['service_name']); ?></td>
					<td><?=$service['service_title']; ?></td>
					<td class="align-center">
						<? $imgArr = array('source_path'=>Configure::read('Image.SourcePath'),'img_name'=>$service['image'],'width'=>80,'height'=>80,'noimg'=>$setting['site']['site_noimage']);
						       $resizedImg = $this->ImageResize->ResizeImage($imgArr);
						       echo $this->Html->image($resizedImg,array('border'=>'0','alt'=>$service['service_title'])) ; ?> </td>
					<td><?=ucfirst($service['location_details']); ?></td>
					<td class="align-right">$<?=$service['service_price']; ?></td>
					<td class="align-center" style="text-align: center;" valign="middle">
						<?=$this->Html->link("<i class=\"fa fa-pencil-square-o\"></i>",array('plugin'=>false,'controller'=>'services','action'=>'add_services',$service['id']),array('escape' => false,'class'=>'actions','title'=>'Edit Service'));?>
						<?=$this->Html->link("<i class=\"fa fa-search\"></i>",array('plugin'=>false,'controller'=>'activity','action'=>'index',$service['id']),array('escape' => false,'class'=>'actions','title'=>'View Service'));?>
						<?=$this->Html->link("<i class=\"fa fa-search\"></i>",array('plugin'=>false,'controller'=>'services','action'=>'add_slots',$service['id']),array('escape' => false,'class'=>'tooltip','actions'=>'View Service'));?>
						<?=$this->Html->link("<i class=\"fa fa-calendar\"></i>",array('plugin'=>false,'controller'=>'services','action'=>'add_service_slots',$service['id']),array('escape' => false,'class'=>'actions','title'=>'Add/Update Slots'));?>
						<?=$this->Html->link("<i class=\"fa fa-sitemap\"></i>",array('plugin'=>'vendor_manager','controller'=>'vendor_service_availabilities','action'=>'index',$service['id']),array('escape' => false,'class'=>'actions','title'=>'Manage Slot Availability'));?>
						<?=$this->Html->link("<i class=\"fa fa-comments\"></i>",array('plugin'=>'vendor_manager','controller'=>'service_reviews','action'=>'reviews',$service['id']),array('escape' => false,'class'=>'actions','title'=>'View Review'));?>
						<?=$this->Html->link("<i class=\"fa fa-list\"></i>",array('plugin'=>'vendor_manager','controller'=>'service_attributes','action'=>'index',$service['id']),array('escape' => false,'class'=>'actions','title'=>'Manage Attributes'));?>
					</td>
				</tr>
			<?php }
			} ?>
			<?
			// view all service show after more than 5 records 
			if($total_service>5){ ?>
				<tr class="view-all">
					<td colspan="6"><?=$this->Html->link('View All',array('plugin'=>'vendor_manager','controller'=>'services','action'=>'my_services'),array('class'=>'dashboard-buttons'));?></td>
				</tr>
			<?php } ?>
		</table>
	 
	<?php if(empty($services)){ ?>
		<div class="no-details">You have not added any services yet.</div>
	<?php } ?>
		
	<div class="clear"></div>
		
	<h3 class="dashboard-heading">My Booking</h3>
	<?=$this->element('message');?>
	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="table table-striped table-bordered">
		<tr>
			<th width="5%">S.No.</th>
			<th width="12%">Order No.</th>
			<th width="23%">Name</th>
			<th width="15%">Email</th>
			<th width="15%">Phone</th>
			<th width="15%">Status</th>
			<th width="10%">Action</th>
			 
		</tr>
		<? if(!empty($booking_details)){ ?>
			<? foreach($booking_details as $key=>$booking_detail){ ?>
				<tr>
					<td class="align-center"><?=$key+1;?></td>
					<td><?=$booking_detail['Booking']['ref_no']?></td>
					<td><?=$booking_detail['Booking']['fname']." ".$booking_detail['Booking']['lname']?></td>
					<td><?=$booking_detail['Booking']['email']?></td>
					<td><?=$booking_detail['Booking']['phone']?></td>
					<td><?=($payment_status[$booking_detail['Booking']['status']]); ?></td>
					<td class="align-center" style="text-align: center;">
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

					<?=$this->Html->link("<i class=\"fa fa-search\"></i>",array('plugin'=>'vendor_manager','controller'=>'bookings','action'=>'booking_details',$booking_detail['Booking']['ref_no']),array('escape' => false,"class"=>"actions"));?>

					</td>
					 
				</tr>
			<? } ?>
			<? if($count_booking_list>5){ ?>
				<tr class="view-all">
					<td colspan="8"><?=$this->Html->link('View All',array('plugin'=>'vendor_manager','controller'=>'bookings','action'=>'booking_list'),array('class'=>'dashboard-buttons'));?></td>
				</tr>
			<? } ?>	
		<? } else {?>
			<tr class="no-details">
				<td colspan="8">There are no booking details</td>
			</tr>
		<? }?>
	</table>
	
	<div class="clear"></div>
    </div>
</div>
	</div>

<script type='text/javascript'>
	$(document).ready(function () {
		sameHeight('left-area','right-area');
	});
</script>