<div class="container-fluid vendor-panel">
<? $payment_status=Configure::read('payment_status');?>
<? // create array for searching list
$search_type=array('ref_no'=>'Order id','transaction_id'=>'Transaction Id','phone'=>'Phone No.','email'=>'Email','fname'=>'First Name','lname'=>'Last Name');
$search_by_date_type=array('booking_date'=>'Booking Date','start_date'=>'Booked Date');
?>
<br><br><br>

<div class="wrapper">
	<div class="hr-line"></div>
	<div class="clear"></div>
	<h2 class="page-title">Booking List</h2>
    <?php if ($this->Session->check('Message.error')): ?>
        <div class="error-message">
        <p><?=$this->Session->flash('error'); ?></p>
        </div>
    <?php endif;?>
    <?php if ($this->Session->check('Message.message')): ?>
        <div class="notification error">
        <p><?=$this->Session->flash('message'); ?></p>
        </div>
    <?php endif;?>
	
	<?=$this->element('VendorManager.left-vendor-panel');?>
	<div class="right-area col-sm-9 col-xs-12">

		<div class="service">
			<h3 class="dashboard-heading">Bookings</h3>
			<?=$this->element('message');?>
			<?=$this->Form->create('Booking',array('class'=>'dashboard-form','id'=>'Booking','action'=>'booking_list','novalidate' => true)); ?>
				<div class="dashboard-form-row with-padding">
					<?=$this->Form->input('search',array('type' =>'select','class'=>'selectpicker special', 'options' => $search_type,'div'=>false,'label'=>false,'onChange'=>'AddPlace(this.id)'));?>
					<?=$this->Form->input('searchtext',array('div'=>false,'label'=>false,'Placeholder'=>'Type your Order id')); ?>
					<?=$this->Form->input('search_by_date',array('type' =>'select','class'=>'selectpicker', 'options' => $search_by_date_type,'div'=>false,'label'=>false,'onChange'=>'AddPlace_date(this.id)'));?>
					<?=$this->Form->input('searchbydate',array('div'=>false,'label'=>false,'Placeholder'=>'Select booking date','class'=>'form-last-field')); ?>
					<input type="submit" value="Search" class="dashboard-buttons btn orange">
				</div>
		       <?=$this->Form->end();?>
		       
		<div class="clear"></div>
		
		<table id="booking_list" width="100%" border="0" cellpadding="0" cellspacing="0" class="dashboard-content">
		    <tr>
			<th width="5%">S.No.</th>
			<th width="13%">Order No.</th>
			<th width="20%">Name</th>
			<th width="16%">Email</th>
			<th width="15%">Phone</th>
			<th width="12%">Status</th>
			<th width="13%" class="text-center">Action</th>
			 
		    </tr>
		    <? $i = $this->Paginator->counter('{:start}'); ?>
		    <? if(!empty($booking_details)){ 
				//echo "<pre>";print_r($booking_details);die;
				?>
			<? foreach($booking_details as $booking_detail){ ?>
				<tr>
					<td class="align-center"><?=$i++;?></td>
					<td><?=$booking_detail['Booking']['ref_no']?></td>
					<td><?=$booking_detail['Booking']['fname']." ".$booking_detail['Booking']['lname']?></td>
					<td><?=$booking_detail['Booking']['email']?></td>
					<td><?=$booking_detail['Booking']['phone']?></td>
					<td>
						<?=($payment_status[$booking_detail["BookingOrder"]["status"]]);?></td>
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
			<? } ?>
		<? } else {?>
			<tr class="no-details">
				<td colspan="7">There are no booking details</td>
			</tr>
		<? }?>
       </table>



			<h3 class="dashboard-heading">My Booking Requests</h3>
			<?=$this->element('message');?>

			<div class="clear"></div>

			<table id="booking_list" width="100%" border="0" cellpadding="0" cellspacing="0" class="dashboard-content">
				<tr>
					<th width="5%">S.No.</th>
					<th width="13%">Booking No.</th>
					<th width="27%">Name</th>
					<th width="16%">Email</th>
					<th width="15%">Phone</th>
					<th width="12%">Service</th>
					<th width="12%">Start Date</th>
					<th width="12%">End Date</th>
					<th width="6%">Accept/Decline</th>

				</tr>
				<? $i = $this->Paginator->counter('{:start}'); ?>
				<? if(!empty($booking_requests)){
					//echo "<pre>";print_r($booking_details);die;
					?>
					<? foreach($booking_requests as $booking_request){
						//pr($booking_request);
						?>
						<tr>
							<td class="align-center"><?=$i++;?></td>
							<td><?=$booking_request['Cart']['id']?></td>
							<td><?=$booking_request['Member']['first_name']." ".$booking_request['Member']['last_name']?></td>
							<td style="word-break: break-all"><?=$booking_request['Member']['email_id']?></td>
							<td><?=$booking_request['Member']['phone']?></td>
							<td><?=$booking_request['Cart']['service_title']?></td>
							<td><?=date('Y-m-d',strtotime($booking_request['Cart']['start_date']))?></td>
							<td><?=date('Y-m-d',strtotime($booking_request['Cart']['end_date']))?></td>
							<td class="align-center">
								<?=$this->Html->link(
									"<i 'onclick'=>'return confirm(\"Are you sure want to confirm this request\")' class=\"fa fa-check\"></i>",
									array('plugin'=>'vendor_manager','controller'=>'bookings','action'=>'accept_request',$booking_request['Cart']['id']),
									array('escape' => false, "class"=>"actions"));?>
								<?=$this->Html->link("<i 'onclick'=>'return confirm(\"Are you sure want to decline this request\")' class=\"fa fa-times\"></i>",
									array('plugin'=>'vendor_manager','controller'=>'bookings','action'=>'cancel_request',$booking_request['Cart']['id']),
									array('escape' => false, "class"=>"actions"));?></td>

						</tr>
					<? } ?>
				<? } else {?>
					<tr class="no-details">
						<td colspan="9">There are no booking requests</td>
					</tr>
				<? }?>
			</table>



			<div class="loader_pagination" style="display:none;" ><?=$this->Html->image('admin/icons/ajax_loading_ladder.gif');?></div>
		<? //pagination 
		 if(!empty($booking_details)) {?>
			<noscript>
				<div class='pag-box'>
					<ul class="pagination">
						<?php if($this->Paginator->first()){?>
							<li><?php echo $this->Paginator->first('<< First',array('class'=>'button gray')); ?></li>
						<?php } ?>
									
						<?php if($this->Paginator->hasPrev()){?>
							<li><?php echo $this->Paginator->prev('< Previous',array('class'=>'button gray'), null, array('class'=>'disabled'));?></li>
						<?php } ?>
						<?=$this->Paginator->numbers(array('modulus'=>7,'tag'=>'li','class'=>'','separator'=>'')); ?>
						<?php if($this->Paginator->hasNext()){?>
							<li><?php echo $this->Paginator->next('Next >',array('class'=>'button gray'));?></li>
						<?php } ?>
						<?php if($this->Paginator->last()){?>
							<li><?php echo $this->Paginator->last('Last >>',array('class'=>'button gray')); ?></li>
							<?php } ?>			  
					</ul>
				</div>
			</noscript>	
		<? }?>
       
   
  
   <div class="clear"></div>
  
  
 </div>

	</div>
<div class="clear"></div>

</div>
	</div>

<script type='text/javascript'>
    $(function(){
	  //Keep track of last scroll
      var lastScroll = 0;
      var loading_start = 0;
      var page = <?=$this->paginator->counter('{:page}')?>;
      var pages = <?=$this->paginator->counter('{:pages}')?>;
      
      $(window).bind('scroll',function(event){
           //Sets the current scroll position
          var st = $(this).scrollTop();
          var win_height = $(this).height();
          var doc_height = $(document).height();
          var scrollBottom = doc_height - win_height - st;
          var scroll_value=200;
           if(navigator.userAgent.match(/(iPhone)/i)){
			  scroll_value=4500;
			 //alert(doc_height);
		  }
		  //Determines up-or-down scrolling
          //alert(doc_height+'--'+(st+win_height));
          //if((st > lastScroll) && ((doc_height-100) < (st+win_height)) ){
        
            if((scrollBottom <= scroll_value) && (pages >= (page+1))){
                if(loading_start===0){
                    loading_start = 1;
                    page++;
                    $('#loader_pagination').show();
                    $.ajax({
                        url:'<?=Router::url(array('plugin'=>'vendor_manager','controller'=>'bookings','action'=>'booking_list','page:'));?>'+page,
                        async:false,
                        success:function(data){
                            //$('.ajaxpagination').append(data);
                            $('#booking_list').append(data);
                            loading_start = 0;
                            $('#loader_pagination').hide();
                        }
                    });
                }
            }
        
            
          lastScroll = st;
          
          
      });
      
    });
    
    
    function AddPlace(id) {
		var selected = $("#"+id+" :selected").text();
		$("#BookingSearchtext").attr("placeholder", "Type your "+selected);
	}
  
    function AddPlace_date(id) {
		var selected = $("#"+id+" :selected").text();
		$("#BookingSearchbydate").attr("placeholder", "Select "+selected);
	}


	sameHeight('left-area','right-area');




</script>
<script type='text/javascript'>
	$(document).ready(function () {
		sameHeight('left-area','right-area');
	});
</script>