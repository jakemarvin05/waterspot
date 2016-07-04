<script type="text/javascript">
    function formsubmit(action) {
        var flag = true;
		//	if(action=='Delete')
        //flag=confirm('Are You Sure, You want to Delete this Page(s)!');
        if (flag)
        {
            document.getElementById('action').value = action;
            if (validate())
                document.getElementById('ServiceDeleteForm').submit();
        }
    }

    function validate() {
        var ans = "0";
        for (i = 0; i < document.service.elements.length; i++) {
            if (document.service.elements[i].type == "checkbox") {
                if (document.service.elements[i].checked) {
                    ans = "1";
                    break;
                }
            }
        }
        if (ans == "0") {
            alert("Please select service(s) to " + document.getElementById('action').value);
            return false;
        } else {
            var answer = confirm('Are you sure you want to ' + document.getElementById('action').value + ' Service(s)');
            if (!answer)
                return false;
        }
        return true;
    }


    function CheckAll(chk)
    {
        var fmobj = document.getElementById('ServiceDeleteForm');
        for (var i = 0; i < fmobj.elements.length; i++)
        {
            var e = fmobj.elements[i];
            if (e.type == 'checkbox')
                fmobj.elements[i].checked = document.getElementById('ServiceCheck').checked;
        }

    }
    
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
            if((scrollBottom <= scroll_value) && (pages >= (page+1))){
                if(loading_start===0){
                    loading_start = 1;
                    page++;
                    $('#loader_pagination').show();
                    $.ajax({
                        url:'<?=Router::url(array('plugin'=>'vendor_manager','controller'=>'services','action'=>'servicelist',$vendor_id,$search,'page:'));?>'+page,
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
            <h2>Vendor Service List</h2>
            <div style="float:right;">

                <a href="javascript:" onClick="return formsubmit('Activate');" class="button">Activate</a>
                <a href="javascript:" onClick="return formsubmit('Deactivate');" class="button">Deactivate</a>

                <a href="javascript:" onClick="return formsubmit('Delete');" class="button">Delete</a>

                <?php echo $this->Html->link('New', array('plugin'=>'vendor_manager','controller' => 'services', 'action' => 'add_services',$vendor_id), array('escape' => false, 'class' => 'button')); ?>
           </div>
        </header>
    </article>
 
     <form method="post">
    <div class="input text" style="overflow:hidden;">
	Search:
	<input id="search" type="text" style="margin-left:10px; margin-right:10px;" name="search" value="<?=$search?>" />
	<button type="submit" style="margin-top:10px; margin-left:10px" >search</button>
    </div>
    </form>
    <?php echo $this->element('admin/message'); ?>
    <?=$this->Form->create('Service', array('name' => 'service', 'action' => 'delete/' , 'id' => 'ServiceDeleteForm', 'onSubmit' => 'return validate(this)', 'class' => 'table-form')); ?>
    <?=$this->Form->hidden('action', array('id' => 'action', 'value' => '')); ?>
    <?=$this->Form->hidden('redirect', array('value' => $url)); ?>
	<?=$this->Form->hidden('vendor_id', array('value' => $vendor_id)); ?>
    
    <table width="100%">
        <tr>
            <th width="5%"><?= $this->Form->checkbox('check', array('value' => 1, 'onchange' => "CheckAll(this.value)", 'class' => 'check-all')); ?></th>
            <th width="5%">SNo.</th>
			<th width="25%">Title</th>
			<th width="25%">Service Type</th>
			<th width="11%">Price</th>
			<th width="5%">Status</th>
			<th width="25%">Action</th>
        </tr>
        <tr>
            <td colspan="9">
                <ul class="Main">
                <?php
					$i = $this->paginator->counter('{:start}');
                    //$i = 0;
                    foreach ($vendor_services as $vendor_service) {
                ?>
				<li id="sort_<?=$vendor_service['Service']['id'];?>"  style="cursor:move" >
					<table width="100%">
						<tr>
							<td width="5%"><?php echo $this->Form->checkbox('Service.id.'.$i, array('value' => $vendor_service['Service']['id'])); ?></td>
							<td width="5%"><?php echo $i++; ?></td>
							<td width="25%"><?=$vendor_service['Service']['service_title'];?></td>
							<td width="25%"><?=$vendor_service['ServiceType']['name'];?></td>
							<td width="11%"><?=$vendor_service['Service']['service_price'];?></td>
							<td width="5%">
							<?php if($vendor_service['Service']['status']=='1') 
									echo $this->Html->image('admin/icons/icon_success.png', array());
                                else
									echo $this->Html->image('admin/icons/icon_error.png', array());
							?>
						
							</td>

							<td width="25%">
								<ul class="actions">
									 <li><?php echo $this->Html->link('edit', array('controller' => 'services', 'action' => 'add_services',$vendor_service['Service']['vendor_id'],$vendor_service['Service']['id']), array('escape' => false, 'class' => 'edit', 'title' => 'Edit Service', 'rel' => 'tooltip')); ?></li>

									<li>
                                        <a href="/activity/index/<?=$vendor_service['Service']['id'] ?>" target="_blank" class="view fancybox" title="View" rel="tooltip">view</a>
                                  	<li><?php echo $this->Html->link('Add Price Rules', array('controller' => 'services', 'action' => 'add_price_rules',$vendor_service['Service']['vendor_id'],$vendor_service['Service']['id'] ), array('escape' => false, 'class' => 'add-price-rule', 'title' => 'Add Price Rules', 'rel' => 'tooltip')); ?></li>
                                  	<li><?php echo $this->Html->link('Add Slot', array('controller' => 'services', 'action' => 'add_service_slots',$vendor_service['Service']['vendor_id'],$vendor_service['Service']['id'] ), array('escape' => false, 'class' => 'add-slot', 'title' => 'Edit Service Slot', 'rel' => 'tooltip')); ?></li>
									<li><?php echo $this->Html->link('Service Availability', array('controller' => 'vendor_service_availabilities', 'action' => 'index',$vendor_service['Service']['vendor_id'],$vendor_service['Service']['id']), array('escape' => false, 'class' => 'add-avail', 'title' => 'Edit Service Availability', 'rel' => 'tooltip')); ?></li>
									<li><?php echo $this->Html->link('Book Slots', array('controller' => 'services', 'action' => 'book_slots',$vendor_service['Service']['vendor_id'],$vendor_service['Service']['id']), array('escape' => false, 'class' => 'book-slots', 'title' => 'Manually Book Slot', 'rel' => 'tooltip')); ?></li>

									<li><?php echo $this->Html->link('Service Review', array('controller' => 'service_reviews', 'action' => 'index',$vendor_service['Service']['vendor_id'],$vendor_service['Service']['id']), array('escape' => false, 'class' => 'add-review', 'title' => 'Edit Service Review', 'rel' => 'tooltip')); ?></li>

                                    <li><?php echo $this->Html->link("Manage Attributes",array('plugin'=>'vendor_manager','controller'=>'service_attributes','action'=>'index',$vendor_service['Service']['id']),array('escape' => false,'class'=>'view-services','title'=>'Manage Attributes'));?></li>

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
                    <?php if (!$vendor_services) { ?>
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
                    url: "<?php echo Router::url(array('plugin'=>'vendor_manager','controller'=>'services','action'=>'ajax_sort','admin'=>false)); ?>",
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
