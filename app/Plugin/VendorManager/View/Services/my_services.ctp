<div class="container-fluid vendor-panel">
	<br><br><br>
<script type="text/javascript">
	function formsubmit(action) {
		var flag = true;
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

<link rel="stylesheet" type="text/css" href="/css/tooltipster.css" />
<script type="text/javascript" src="/js/jquery.tooltipster.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		 $('.tooltip').tooltipster();
	});
</script>

<div class="hr-line"></div>
<div class="clear"></div>

<h2 class="page-title">My Services</h2>
<?=$this->element('VendorManager.left-vendor-panel');?>
	<div class="right-area col-sm-9 col-xs-12">

   
   <?=$this->element('message');?>
    <div class="service">
		<div class="dashboard-form-row" id="service_container">
			<h3 class="dashboard-heading" style="float: left;">Services</h3> 
			<?=$this->Html->link('Add New Service',array('plugin'=>'vendor_manager','controller'=>'services','action'=>'add_services'),array('class'=>'dashboard-buttons btn orange'));?>
		</div>
		<div class="dashboard-form-row" style="text-align: right; margin-bottom: 0; padding-bottom: 0;" id="servicebutton_container">
                        <div id="servicebuttons">
			<a href="javascript:" onClick="return formsubmit('Activate');" class="dashboard-quick-buttons btn orange" id="servicebuttons1">Activate</a>
			<a href="javascript:" onClick="return formsubmit('Deactivate');" class="dashboard-quick-buttons btn orange" id="servicebuttons2">Deactivate</a>
			<a href="javascript:" onClick="return formsubmit('Delete');" class="dashboard-quick-buttons btn orange" id="servicebuttons3">Delete</a>
                        </div>
		</div>
		<div class="clear"></div>
		<?=$this->Form->create('Service', array('name' => 'service', 'action' => 'delete/' , 'id' => 'ServiceDeleteForm', 'onSubmit' => 'return validate(this)', 'class' => 'table-form')); ?>
		<?=$this->Form->hidden('action', array('id' => 'action', 'value' => '')); ?>
		<?=$this->Form->hidden('redirect', array('value' => $url)); ?>
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="dashboard-content">
			<tr>
				<th width="5%"><?= $this->Form->checkbox('check', array('value' => 1, 'onchange' => "CheckAll(this.value)", 'class' => 'check-all')); ?></th>
				<th width="3%">S.No.</th>
				<th width="20%">Name</th>
				<th width="15%">Image</th>
				<th width="12%">Location</th>
				<th width="10%">Price ($)</th>
				<th width="25%">Actions</th>
				<th width="5%">Status</th>
			</tr>
		 
				<?php if(!empty($service_lists)){ ?>
		 
					 <?php $i = $this->paginator->counter('{:start}'); ?>
					<?php foreach($service_lists as $service){ ?>
					<tr>
						<td style="text-align: center;" valign="middle"><?php echo $this->Form->checkbox('Service.id.'.$i, array('value' => $service['id'])); ?></td>
						<td style="text-align: center;" valign="middle"><?php echo $i++; ?></td>
						<td valign="middle"><?=ucfirst($service['service_title']); ?></td>
						<td style="text-align: center;">
							 <? $imgArr = array('source_path'=>Configure::read('Image.SourcePath'),'img_name'=>$service['image'],'width'=>80,'height'=>80,'noimg'=>$setting['site']['site_noimage']);
								$resizedImg = $this->ImageResize->ResizeImage($imgArr);
								echo $this->Html->image($resizedImg,array('border'=>'0','alt'=>$service['service_title'])) ; ?> </td>
						<td valign="middle"><?=ucfirst($service['location_details']); ?></td>
						<td style="text-align: right;" valign="middle"><?= number_format($service['service_price'],2) ?></td>
						<td class="action" style="text-align: center;" valign="middle">
							<?=$this->Html->link($this->Html->image('add.png',array('alt'=>'edit')),array('plugin'=>false,'controller'=>'services','action'=>'add_services',$service['id']),array('escape' => false,'class'=>'tooltip','title'=>'Edit Service'));?>
							<?=$this->Html->link($this->Html->image('view.png',array('alt'=>'Add/Update Slot')),array('plugin'=>false,'controller'=>'services','action'=>'add_slots',$service['id']),array('escape' => false,'class'=>'tooltip','title'=>'View Service'));?>
							
							<?=$this->Html->link($this->Html->image('slots.gif',array('alt'=>'Add/Update Slot')),array('plugin'=>false,'controller'=>'services','action'=>'add_service_slots',$service['id']),array('escape' => false,'class'=>'tooltip','title'=>'Add/Update Slots'));?>
							
							<?=$this->Html->link($this->Html->image('add-avail.png',array('alt'=>'Add/Update Availablity')),array('plugin'=>'vendor_manager','controller'=>'vendor_service_availabilities','action'=>'index',$service['id']),array('escape' => false,'class'=>'tooltip','title'=>'Manage Slot Availability'));?>
							
							<?=$this->Html->link($this->Html->image('service_review-icon.png',array('alt'=>'View Review')),array('plugin'=>'vendor_manager','controller'=>'service_reviews','action'=>'reviews',$service['id']),array('escape' => false,'class'=>'tooltip','title'=>'View Review'));?>
						</td>
						<td style="text-align: center;">
							<?php if($service['status']=='1'){ 
								echo "<i class=\"success fa fa-check\"></i>"; //$this->Html->image('admin/icons/icon_success.png', array());
                                }else{
									"<i class=\"error fa fa-times\"></i>"; //echo $this->Html->image('admin/icons/icon_error.png', array());
								}
							?>
						</td>
					</tr>		
				<? }} ?>
				
			</table>
				
		</div>	
		<?php if(!empty($service_lists)){ ?>
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
		
		<? }else {  ?>
			<div class="no-details">You have not added any services yet</div>
		<? }?>
		<div class="loader_pagination" style="display:none;" ><?=$this->Html->image('admin/icons/ajax_loading_ladder.gif');?></div>
	</div>
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
                        url:'<?=Router::url(array('plugin'=>'vendor_manager','controller'=>'services','action'=>'my_services','page:'));?>'+page,
                        async:false,
                        success:function(data){
                            $('.dashboard-content').append(data);
                            loading_start = 0;
                            $('#loader_pagination').hide();
                        }
                    });
                }
            }
        
            
          lastScroll = st;
          
          
      });
      
    });

	sameHeight('left-area','right-area');
</script>
