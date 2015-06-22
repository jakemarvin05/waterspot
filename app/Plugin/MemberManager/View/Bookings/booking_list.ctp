<?
// create array for searching list
$search_type=array('ref_no'=>'Order id','transaction_id'=>'Transaction Id','phone'=>'Phone No.','email'=>'Email','fname'=>'First Name','lname'=>'Last Name');
$search_by_date_type=array('booking_date'=>'Booking Date','start_date'=>'Booked Date');
?>
<div class="wrapper">
	<div class="hr-line"></div>
	<div class="clear"></div>
	<?=$this->element('breadcrumbs');?>
	<h2 class="page-title">Booking <span style="color: #000;">List</span></h2>
	<?=$this->element('MemberManager.left-member-panel');?>

	<div class="right-area">
		<h3 class="dashboard-heading">My Bookings</h3>
		<div class="service booking">

			<?=$this->Form->create('Booking',array('class'=>'dashboard-form','id'=>'Booking','action'=>'booking_list','novalidate' => true));
			?>
				<div class="dashboard-form-row">
					<?=$this->Form->input('search',array('type' =>'select', 'options' => $search_type,'div'=>false,'label'=>false,'onChange'=>'AddPlace(this.id)'));?>
					<?=$this->Form->input('searchtext',array('div'=>false,'label'=>false,'Placeholder'=>'Type your Order id')); ?>
					<?=$this->Form->input('search_by_date',array('type' =>'select', 'options' => $search_by_date_type,'div'=>false,'label'=>false,'onChange'=>'AddPlace_date(this.id)'));?>    
					<?=$this->Form->input('searchbydate',array('div'=>false,'label'=>false,'Placeholder'=>'Select booking date','class'=>'form-last-field')); ?>
				</div>
				<div class="dashboard-form-row">
					<input type="submit" value="Search" class="dashboard-buttons">
				</div>
			<?=$this->Form->end();?>

			<div class="clear"></div>
       
			<table id="booking_list" width="100%" border="0" cellpadding="0" cellspacing="0" class="dashboard-content">
				<tr>
					<th width="5%">S.No.</th>
					<th width="12%">Order No.</th>
					<th width="20%">Name</th>
					<th width="20%">Email</th>
					<th width="16%">Phone</th>
					<th width="12%">Status</th>
					<th width="10%">View</th>
				 
				</tr>
				<? $i = $this->paginator->counter('{:start}'); ?>
				<? if(!empty($booking_details)){ ?>
				<? foreach($booking_details as $booking_detail){ ?>
					<tr>
						<td class="align-center"><?=$i++;?></td>
						<td class="align-center"><?=$booking_detail['Booking']['ref_no']?></td>
						<td><?=$booking_detail['Booking']['fname']." ".$booking_detail['Booking']['lname']?></td>
						<td><?=$booking_detail['Booking']['email']?></td>
						<td><?=$booking_detail['Booking']['phone']?></td>
						<td><?=($booking_detail['Booking']['status']==1)?'Completed':'Not Completed';?></td>
						<td class="align-center"><?=$this->Html->link($this->Html->image('view.png',array('alt'=>'View Detail','title'=>'View Detail','class'=>'dashboard-links')),array('plugin'=>'member_manager','controller'=>'bookings','action'=>'booking_details',$booking_detail['Booking']['ref_no']),array('escape' => false));?></td>
					</tr>
				<? } ?>
			<? } else {?>
				<tr>
					<td colspan='7'>There are no booking details</td>
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
</div>
<div class="clear"></div>

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
