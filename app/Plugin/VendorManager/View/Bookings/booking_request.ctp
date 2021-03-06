<? $payment_status=Configure::read('payment_status');?>
<? // create array for searching list
//$search_type=array('ref_no'=>'Order id','transaction_id'=>'Transaction Id','phone'=>'Phone No.','email'=>'Email','fname'=>'First Name','lname'=>'Last Name');
//$search_by_date_type=array('booking_date'=>'Booking Date','start_date'=>'Booked Date');
?>

<div class="wrapper vendor-panel container-fluid">
	<br><br><br><br>
	<h2 class="page-title">Booking Request</h2>
	
	<?=$this->element('VendorManager.left-vendor-panel');?>
	<div class="right-area col-sm-9 col-xs-12">

		<div class="service">
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
				<td colspan="9">There are no booking requests now</td>
			</tr>
		<? }?>
       </table>

       <div class="loader_pagination" style="display:none;" ><?=$this->Html->image('admin/icons/ajax_loading_ladder.gif');?></div>
		<? //pagination 
		 if(!empty($booking_request)) {?>
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
		<? } ?>
       
   
  
   <div class="clear"></div>
  
  
 </div>

	</div>
<div class="clearfix"></div>


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
  
</script>

<script type='text/javascript'>
	$(document).ready(function () {
		sameHeight('left-area','right-area');
	});
</script>