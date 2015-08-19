<?
// create array for searching list
$search_type=array('ref_no'=>'Order id','transaction_id'=>'Transaction Id','phone'=>'Phone No.','email'=>'Email','fname'=>'First Name','lname'=>'Last Name');
$search_by_date_type=array('booking_date'=>'Booking Date','start_date'=>'Booked Date');
?>

        <div class="container-fluid member-panel">
        <div class="hr-line"></div>
        <div class="clear" style="margin-top:80px;"></div>

	<h2 class="page-title">Booking List</h2>
	<?=$this->element('MemberManager.left-member-panel');?>

	<div class="right-area  col-sm-9 col-xs-12">
		<h3 class="dashboard-heading">My Bookings Request</h3>
		<div class="service booking">

			<div class="clear"></div>
       
			<table id="booking_list" width="100%" border="0" cellpadding="0" cellspacing="0" class="dashboard-content">
				<tr>
					<th width="5%">S.No.</th>
					<th width="20%">Game Name</th>
					<th width="20%">Booking Date</th>
					<th width="16%">Start Date</th>
					<th width="12%">End Date</th>
					<th width="12%">Status</th>
					<th width="12%">Amount</th>
					<th width="10%">Pay</th>
				 
				</tr>
				<? $i = $this->paginator->counter('{:start}'); ?>
				<? if(!empty($booking_details)){ ?>
				<? foreach($booking_details as $booking_detail){ ?>
					<tr>
						
						<td class="align-center"><?=$i++;?></td>
						<td><?=$booking_detail['Cart']['service_title']?></td>
						<td><?php echo date('Y-m-d',strtotime($booking_detail['Cart']['booking_date'])); ?></td>
						<td><?php echo date('Y-m-d',strtotime($booking_detail['Cart']['start_date'])); ?></td>
						<td><?php echo date('Y-m-d',strtotime($booking_detail['Cart']['end_date'])); ?></td>
						<td><?=($booking_detail['Cart']['vendor_confirm']==3)?'Processing':'Confirm';?></td>
						<td><?=$booking_detail['Cart']['total_amount']?></td>
						<td>
							<?php if($booking_detail['Cart']['vendor_confirm']==1){ 
								echo $this->Html->link('Pay Now',array('plugin'=>false,'controller'=>'carts','action'=>'add_order',$booking_detail['Cart']['id'],$booking_detail['Cart']['service_id']),array('escape' => false));
							}else{
								echo "-";
							} ?>
						</td>
						
					</tr>
				<? } ?>
			<? } else {?>
				<tr>
					<td colspan='8'>There are no booking details</td>
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
                        url:'<?=Router::url(array('plugin'=>'member_manager','controller'=>'bookings','action'=>'booking_list','page:'));?>'+page,
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
		$("#BookingSearchbydate").attr("placeholder", "Please select "+selected);
	}
</script>
<script type='text/javascript'>
 $(document).ready(function () {
 sameHeight('left-area','right-area');
 });
</script>
