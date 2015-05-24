<?php $payment_status=array(2=>'Pending',1=>'Completed');?>
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
            if((scrollBottom <= scroll_value) && (pages >= (page+1))){
                if(loading_start===0){
                    loading_start = 1;
                    page++;
                    $('#loader_pagination').show();
                    $.ajax({
                        url:'<?=Router::url(array('plugin'=>'vendor_manager','controller'=>'vendors','action'=>'vendorpayment',$search,'page:'));?>'+page,
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

<div>
    <article>
        <header>
            <h2>Vendor Payment List</h2>
        </header>
    </article>
 
    <form method="post">
    <div class="input text" style="overflow:hidden;">
Search:
<input id="search" type="text" style="margin-left:10px; margin-right:10px;" name="search" value="<?=$search?>" placeholder="By Business name,name,email">
<?=$this->Form->input('status',array('options'=>$payment_status,'empty'=>'Select Payment Status','label'=>false,'div'=>false,'required'=>false,'value'=>$status,'selected'=>$status));?>
<button type="submit" style="margin-top:10px; margin-left:10px" >search</button>
    </div>
    </form>
    <?php echo $this->element('admin/message'); ?>
    <?=$this->Form->create('Payment', array('name' => 'payment', 'class' => 'table-form')); ?>
    <?=$this->Form->hidden('action', array('id' => 'action', 'value' => '')); ?>
    <?=$this->Form->hidden('redirect', array('value' => $url)); ?>

    <table width="100%">
        <tr>
            <th width="5%">SNo.</th>
            
			<th width="12%">Business Name</th>
			<th width="12%">First Name</th>
			<th width="12%">Last Name</th>
			<th width="26%">Email</th>
			<th width="10%">Amount</th>
			<th width="20%">Payment Status</th>
			<th width="15%">Actions</th>
        </tr>
        <tr>
            <td colspan="9">
                <ul class="Main">
                <?php
                 $i = $this->paginator->counter('{:start}');
                    //$i = 0;
                    foreach ($vendor_payments as $payment) {
                ?>
				<li id="sort_<?=$payment['Vendor']['id'];?>"  style="cursor:move" >
					<table width="100%">
						<tr>
							<td width="5%"><?php echo $i++; ?></td>
							<td width="12%"><?=$payment['Vendor']['bname'];?></td>
							<td width="12%"><?=$payment['Vendor']['fname'];?></td>
							<td width="12%"><?=$payment['Vendor']['lname'];?></td>
							<td width="15%"><?=$payment['Vendor']['email'];?></td>
							<td width="10%">$<?=$payment['Payment']['payment_amount'];?></td>
							<td width="20%">
							<?php if($payment['Payment']['status']=='0') {
									echo '<span class="tag red">Not Completed</span>';
                                }elseif($payment['Payment']['status']=='1'){
									echo '<span class="tag green">Completed</span>';
								}else{
									echo '<b class=button-link>Pending</b>';
								}
							?>
							</td>
							<td width="15%">
								<ul class="actions">
									<li><?=$this->Html->link('View', array('controller' => 'vendors', 'action' => 'paymentstatus', $payment['Vendor']['id']), array('escape' => false,'class'=>'view fancybox','title'=> __('View Payment Status'),'rel'=>'tooltip'))?></li>
								</ul >
							</td> 
						</tr>
					</table>
				</li>
				<?php } ?>
                </ul>
            </td>
        </tr>
    <tr>
        <td colspan="9" id="loader_pagination" style="display:none;"><div><?=$this->Html->image('admin/icons/ajax_loading_ladder.gif');?></div></td>
    </tr>
        <tfoot>
        <tr>
                <td colspan="9">
                    <?php if (!$vendor_payments) { ?>
                    <div style='color:#FF0000'>No Record Found</div>
                     <?php } else { ?>
							 <noscript>
                            <ul class="pagination">
                             <?php if($this->Paginator->first()){?>
							<li><?php echo $this->Paginator->first('« First',array('class'=>'button gray')); ?></li>
							<?php } ?>
							
							<?php if($this->Paginator->hasPrev()){?>
							<li><?php echo $this->Paginator->prev('< Previous',array('class'=>'button gray'), null, array('class'=>'disabled'));?>&nbsp;... &nbsp;</li>
							<?php } ?>
							
							<?=$this->Paginator->numbers(array('modulus'=>6,'tag'=>'li','class'=>'','separator'=>'')); ?>
                            <?php if($this->Paginator->hasNext()){?>
                            <li>&nbsp;... &nbsp;<?php echo $this->Paginator->next('Next >',array('class'=>'button gray'));?></li>
							<?php } ?>
							<?php if($this->Paginator->last()){?>
							<li><?php echo $this->Paginator->last('Last »',array('class'=>'button gray')); ?></li>
							<?php } ?>
                             </ul>
                             </noscript> 
					<?php } ?>

                </td>
            </tr>
        </tfoot>

    </table>
</form>

</div>
<script type="text/javascript">
	
$(document).ready(function(){
        $( ".Main" ).sortable({
			placeholder: "ui-state-highlight",
			opacity: 0.6,
            update: function(event, ui) {
                var info = $(this).sortable("serialize");
                $.ajax({
                    type: "POST",
                    url: "<?php echo Router::url(array('plugin'=>'vendor_manager','controller'=>'vendors','action'=>'ajax_vendorpayment_sort','admin'=>false)); ?>",
                    data: info,
                    context: document.body,
                    success: function(){
                        
                        // alert("cool");
                    }
              });
            }
        });
        $( ".Main" ).disableSelection();         
    });
</script>
