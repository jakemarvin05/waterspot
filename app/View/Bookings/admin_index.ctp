
<?
// create array for searching list
$search_type=array('ref_no'=>'Order id','transaction_id'=>'Transaction Id','phone'=>'Phone No.','email'=>'Email','fname'=>'First Name','lname'=>'Last Name');
$search_by_date_type=array('booking_date'=>'Booking Date','start_date'=>'Booked Date');

?>

<link rel="stylesheet" href="https://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
 <script type="text/javascript">

$(function() {
	$( "#BookingSearchbydate" ).datepicker({
	dateFormat: "dd-mm-yy",
	changeMonth: false
	})
});
</script>
 

<style type="text/css">
    ul.Main {
        list-style-type: none;
    }
    .ui-state-highlight { height: 30px; line-height: 25px; }
    /*
    ul.Main {
        list-style-type: none;
        margin-left:-40px;
        margin-top:-1px;
    }
    ul li.Main2 {
        color:#000000;
        border: 1px solid #cccccc;
        cursor: move;
        margin-bottom: 0px;
        background:  #FFFFFF;
        border: 1px solid #efefef;
        /*width: 763px; *
        text-align: left;
        font-family:Verdana, Arial, Helvetica, sans-serif; font-size:11px;
    }	
    ul li.Main3 {
        color:#000000;
        border: 1px solid  #FFE8E8;
        cursor: move;
        margin-bottom: 0px;
        background: #FFE8E8;
        border: 1px solid #efefef;
        width: 763px;
        text-align: left;
        font-family:Verdana, Arial, Helvetica, sans-serif; font-size:11px;

    }
.ui-state-highlight { height: 1.5em; line-height: 1.2em; }
*/
</style>

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
		    
//alert(sta+'--'+st);
            
          //Determines up-or-down scrolling
          //alert(doc_height+'--'+(st+win_height));
          //if((st > lastScroll) && ((doc_height-100) < (st+win_height)) ){
        
            if((scrollBottom <= scroll_value) && (pages >= (page+1))){
                if(loading_start===0){
                    loading_start = 1;
                    page++;
                    
                    $('#loader_pagination').show(); 
                    $.ajax({
                        url:'<?=Router::url(array('plugin'=>false,'controller'=>'bookings','action'=>'index',$vendor_id,$search,$searchtext,$search_by_date,$searchbydate,'page:'));?>'+page,
                        async:false,
                        success:function(data){
                            $('ul.Main').append(data);
                            loading_start = 0;
                            $('#loader_pagination').hide();
                        }
                    });
                }
            }
        
            
          lastScroll = st;
          
          
      });
      
    });
</script>
<article>
        <header>
            <h2>Booking Lists</h2>
        </header>
    </article> 

   <?=$this->Form->create('Booking',array('class'=>'query-from','id'=>'Booking','action'=>'admin_index','novalidate' => true));
	?>
    <div class="input text" style="overflow:hidden;">
	BOOKING:
		<?=$this->Form->input('vendor_id',array('type' =>'select', 'options' => $vendorlist,'empty' => 'Search by vendor','div'=>false,'label'=>false));?>
		<?=$this->Form->input('search',array('type' =>'select', 'options' => $search_type,'div'=>false,'label'=>false));?>
			
		<?=$this->Form->input('searchtext',array('div'=>false,'label'=>false)); ?>
		<?=$this->Form->input('search_by_date',array('type' =>'select', 'options' => $search_by_date_type,'div'=>false,'label'=>false));?>    
		<?=$this->Form->input('searchbydate',array('div'=>false,'label'=>false)); ?>
	<button type="submit" style="margin-top:10px; margin-left:10px" >search</button>
    </div>
    </form>
    <?php echo $this->element('admin/message'); ?>
    <?=$this->Form->create('Booking', array('name' => 'booking', 'action' => 'delete/' , 'id' => 'BookingDeleteForm', 'onSubmit' => 'return validate(this)', 'class' => 'table-form')); ?>
    <?=$this->Form->hidden('action', array('id' => 'action', 'value' => '')); ?>
    <?=$this->Form->hidden('redirect', array('value' => $url)); ?>

    <table width="100%">
        <tr>
            
            <th width="5%">SNo.</th>
			<th width="10%">Order No.</th>
			<th width="15%">Name</th>
			<th width="15%">Email</th>
			<th width="10%">Phone</th>
			<th width="15%">Transaction Id</th>
			<th width="10%">Status</th>
			<th width="10%">Action</th>
        </tr>
         
        <tr>
            <td colspan="9">
                <ul class="Main">
                <?php
                 $i = $this->paginator->counter('{:start}');
                    //$i = 0;
                if(!empty($booking_details)) {
                    foreach ($booking_details as $booking_detail) {
                ?>
				<li>
					<table width="100%">
						<tr>
							
							
							<td width="5%"><?php echo $i++; ?></td>
							<td width="10%"><?=$booking_detail['Booking']['ref_no']?></td>
							<td width="15%"><?=$booking_detail['Booking']['fname']." ".$booking_detail['Booking']['lname']?></td>
							<td width="15%"><?=$booking_detail['Booking']['email']?></td>
							<td width="10%"><?=$booking_detail['Booking']['phone']?></td>
							<td width="15%"><?=$booking_detail['Booking']['transaction_id']?></td>
							<td width="10%">
							<?php if($booking_detail['Booking']['status']=='1') {
								echo $this->Html->image('admin/icons/icon_success.png', array('alt'=>"Completed",'title'=>"Completed"));
							}else {
								echo $this->Html->image('admin/icons/icon_error.png', array('alt'=>"Not completed",'title'=>"Not completed"));
							}?>
							</td>
							<td width="10%">
                                <?php if ($booking_detail['Booking']['status'] == 1 && $booking_detail['Booking']['vendor_confirm'] == 3): ?>
                                    <?=$this->Html->link('<img src="/img/admin/icons/icon_success.png" alt="Confirm Booking" title="Confirm Booking">',array('plugin'=>'vendor_manager','controller'=>'bookings','action'=>'accept_paid',$booking_detail['Booking']['id']),array('escape' => false));?>
                                    <?=$this->Html->link('<img src="/img/admin/icons/icon_error.png" alt="Reject Booking" title="Reject Booking">',array('plugin'=>'vendor_manager','controller'=>'bookings','action'=>'cancel_paid',$booking_detail['Booking']['id']),array('escape' => false));?>
                                <?php endif; ?>

                                <?php if ($booking_detail['Booking']['status'] == 1 && $booking_detail['Booking']['vendor_confirm'] == 1): ?>
                                    <img src="/img/admin/icons/icon_success.png" alt="Confirmed Booking" title="Confirmed Booking">
                                <?php endif; ?>

                                <?php if ($booking_detail['Booking']['status'] == 1 && $booking_detail['Booking']['vendor_confirm'] == 2): ?>
                                    <img src="/img/admin/icons/icon_error.png" alt="Rejected Booking" title="Rejected Booking">
                                <?php endif; ?>


								<?=$this->Html->link($this->Html->image('cemera-icon.png',array('alt'=>'View Detail','title'=>'View Detail')),array('plugin'=>false,'controller'=>'bookings','action'=>'booking_details',$booking_detail['Booking']['ref_no']),array('escape' => false));?>
							</td> 
						</tr>
					</table>
				</li>
				<?php } } ?>
                </ul>
            </td>
        </tr>
    <tr>
        <td colspan="9" id="loader_pagination" style="display:none;"><div><?=$this->Html->image('admin/icons/ajax_loading_ladder.gif');?></div></td>
    </tr>
        <tfoot>
        <tr>
                <td colspan="9">
                    <?php if (!$booking_details) { ?>
                    <div style='color:#FF0000'>No Record Found</div>
                     <?php } else { ?>
                            <ul class="pagination">
                             <?php if($this->Paginator->first()){?>
                            <li><?php echo $this->Paginator->first('« First'); ?></li>
                            <?php } ?>
                            
                            <?php if($this->Paginator->hasPrev()){?>
                            <li><?php echo $this->Paginator->prev('< Previous',null, null, array('class'=>'disabled'));?>&nbsp;... &nbsp;</li>
                            <?php } ?>
                            
                            <?=$this->Paginator->numbers(array('modulus'=>6,'tag'=>'li','class'=>'','separator'=>'')); ?>
                            <?php if($this->Paginator->hasNext()){?>
                            <li><?php echo $this->Paginator->next('Next >');?></li>
                            <?php } ?>
                            <?php if($this->Paginator->last()){?>
                            <li><?php echo $this->Paginator->last('Last »'); ?></li>
                            <?php } ?>
                             </ul>
					<?php } ?>

                </td>
            </tr>
        </tfoot>

    </table>
</form>
