<script language="javascript">
function saveform(){
	document.getElementById('ServiceSlotPublish').value=1;
	document.getElementById('ServiceSlot').submit();
}
</script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<?=$this->Html->script('admin/ajax_upload.js');?>
	 
<div>
    <article>
        <header>
            <h2>
                <?php
                    if (isset($this->request->data['ServiceSlot']['id']) && $this->request->data['ServiceSlot']['id']):
                          echo  __('Update Service');
                    else:
                          echo  __('Add Service ');
                           $this->request->data['ServiceSlot']['status']=1;
                    endif;
                ?>
                [<?=$service_title?>]
            </h2>
           
        </header>
    </article> 
    <?php echo $this->element('admin/message');?>
    <?php if(!empty($service_slots)) { ?>
		<table width="100%" style="margin-bottom:10px;">
			<tr>
				<td colspan="3"><strong>Start Time</strong></td>
				<td colspan="2"><strong>End Time</strong></td>
				<td colspan="2"><strong>Price</strong></td>
				<td><strong>Cancel</strong></td>
			</tr>
			
				<?php foreach($service_slots as $service_slot){?>
				<tr> 
					<td  colspan="3"><?=$this->Time->meridian_format($service_slot['start_time'])?></td>
					<td colspan="2"><?=$this->Time->end_meridian_format($service_slot['end_time'])?></td>
					<td colspan="2"><?=$service_slot['price']?></td>
					<td><?=$this->Html->link($this->Html->image('del.png'),array('plugin'=>'vendor_manager','controller'=>'services','action'=>'slot_delete',$service_id,$service_slot['id']),array('escape'=>false,"onclick"=>"return confirm('Are you sure you wish to delete this slot?')")); ?>  </td>
				</tr> 
				<?php }?> 
		</table>
	<?php }else{ ?>
		<div class="no-record">No slot is available here</div>
	<?php } ?>

	<h2 style="font-size: 155%; margin-top: 15px;">Add New Slot</h2>
	<?php echo $this->Form->create('ServiceSlot',array('name'=>'servicetype','id'=>'add_slots','url'=>array('plugin'=>'vendor_manager','controller'=>'services','action'=>'add_service_slots',$vendor_id,$service_id),'onsubmit'=>'//return validatefields();','type'=>'file','novalidate' => true));?>
		<?php echo $this->Form->input('id');?>
		<?php echo $this->Form->hidden('service_id',array('value'=>$service_id));?>
		 <?=$this->Form->hidden('status'); ?>
		<table border="0" cellspacing="0" cellpadding="10" width="100%">
			<tr>
				<td align="left" style="text-align: left; padding-left: 15px;" width="27%">
					<?=$this->Form->input('start_time',array('type' =>'select', 'options' => $hours_format,'label'=>false,'div'=>false));?>
				<?=$this->Form->error('start_time',null,array('wrap' => 'div', 'class' => 'error-message')); ?>
				</td>
				<td align="left" style="text-align: left;">
					<?=$this->Form->input('end_time',array('type' =>'select', 'options' => $end_hours_format,'label'=>false,'div'=>false));?>
				<?=$this->Form->error('end_time',null,array('wrap' => 'div', 'class' => 'error-message')); ?>
				</td>
				<td align="left" style="text-align: left;">
				Price: 
					<?=$this->Form->input('price',array('type' =>'text','label'=>false,'div'=>false, 'value'=>$default_service_price));?>
				<?=$this->Form->error('price',null,array('wrap' => 'div', 'class' => 'error-message')); ?>
				</td>
			</tr>
		</table><br /><br />
		<button type="submit">
		    <?php 
			if (isset($this->request->data['ServiceSlot']['id']) && $this->request->data['ServiceSlot']['id']):
			    echo __('Update Slot');
			else:
			    echo __('Add Slot');
			endif;								
		    ?>
		</button> or 
		<?php echo $this->Html->link('Cancel', array('controller'=>'services', 'action' => 'servicelist',$vendor_id));?>
	<?php echo $this->Form->end();?>
</div>
<script type="text/javascript">
<?php $path = $this->Html->webroot; ?>
    $(document).ready(function(){
		  $('#add_slots').submit(function(){
			//var data = $(this).serializeArray();
			var data = new FormData(this);
			var formData = $(this);
            var status = 0;
           
           $.each(this,function(i,v){
                $(v).removeClass('invalid form-error');
                });
            $('.error-message').remove();
            $('#add_slots > span#for_owner_cms').show();
            $('#add_slots > button[type=submit]').attr({'disabled':true});
           
           $.ajax({
                url: '<?=$path?>vendor_manager/services/slot_validation',
                async: false,
				data: data,
                dataType:'json', 
                type:'post',
                cache: false,
				contentType: false,
				processData: false,
                success: function(data) {
					if(data.error==1){
                        $.each(data.errors,function(i,v){
							$('#'+i).addClass("invalid form-error").after();
							$('#'+i).bind('click',function(){
								$(this).removeClass('invalid form-error');
								//$(this).next().remove();
								});
							$('#add_slots').after('<div class="error-message" style="width:450px;">'+v+'</div>');
                        });
                    }else{
                        status = 1;
                    }
                   
                 }
            });
            if(status==0){
               $("html, body").animate({ scrollTop: 0 }, "slow");
               $('#add_slots > button[type=submit]').attr({'disabled':false});
               $('#add_slots > span#for_owner_cms').hide();
            }
           return (status===1)?true:false; 
            
        });
 
    });
 </script>

