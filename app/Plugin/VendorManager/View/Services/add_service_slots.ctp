<div class="container-fluid vendor-panel">
<div class="hr-line"></div>
<div class="clear" style="margin-top:80px;"></div>

<h2 class="page-title">Slots - <?=$service_title?></h2>

<?=$this->element('VendorManager.left-vendor-panel');?>

<div class="right-area col-sm-9 col-xs-12">

	<?=$this->element('message');?>
	<h3 class="dashboard-heading">Our Available Slots</h3>
	<?php if(!empty($service_slots)) { ?>
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="dashboard-content">
			<tr>
				<th><a href="/services/add_service_slots/<?php echo $service_id; ?>?sort_by=start_time&order=<?php echo isset($_GET['order']) ? ($_GET['order'] == 'ASC' ? 'DESC' : 'ASC') : 'ASC'; ?>">Start Time</a></th>
				<th><a href="/services/add_service_slots/<?php echo $service_id; ?>?sort_by=end_time&order=<?php echo isset($_GET['order']) ? ($_GET['order'] == 'ASC' ? 'DESC' : 'ASC') : 'ASC'; ?>">End Time</a></th>
				<th><a href="/services/add_service_slots/<?php echo $service_id; ?>?sort_by=price&order=<?php echo isset($_GET['order']) ? ($_GET['order'] == 'ASC' ? 'DESC' : 'ASC') : 'ASC'; ?>">Price</a></th>
				<th>Fire Sales Price</th>
				<th>Fire Sales Margin</th>
				<th>Action</th>

			</tr>
			<?php foreach($service_slots as $service_slot){?>
				<tr> 
					<td class="align-center"><?=$this->Time->meridian_format($service_slot['start_time'])?></td>
					<td class="align-center"><?=$this->Time->end_meridian_format($service_slot['end_time'])?></td>
					<td class="align-center"><?=$service_slot['price']?></td>
					<td class="align-center"><?php echo $service_slot['fire_sales_price'] ? $service_slot['fire_sales_price'] : '<em>No fire sales</em>'; ?></td>
					<td class="align-center"><?php echo $service_slot['fire_sales_day_margin'] ? $service_slot['fire_sales_day_margin'] : '<em>No fire sales</em>'; ?></td>
					<td class="align-center action"><?=$this->Html->link("<i class=\"fa fa-times actions\"></i>",array('plugin'=>'vendor_manager','controller'=>'services','action'=>'slot_delete',$service_id,$service_slot['id']),array('escape'=>false,"onclick"=>"return confirm('Are you sure you wish to delete this slot?')")); ?>  </td>
				</tr> 
			<?php }?> 
		</table>
	<?php } else { ?>
		<div class="no-details">No slot is available here</div>
	<?php } ?>

	<h3 class="dashboard-heading">Add New Slot</h3>
        <div class="dashboard-form-row with-padding edit">
	<?=$this->Form->create('ServiceSlot',array('id'=>'add_slots','class'=>'add-slot-form','url'=>array('controller'=>'services','action'=>'add_service_slots',$service_id),'novalidate' => true)); ?>
		<?php echo $this->Form->hidden('id'); ?>
		<?php echo $this->Form->hidden('service_id',array('value'=>$service_id));?>
		<?=$this->Form->input('start_time',array('class'=>'selectpicker', 'type' =>'select', 'options' => $hours_format,'label'=>false,'div'=>false));?>
		<span class="txt edit">TO</span>
		<?=$this->Form->input('end_time',array('class'=>'selectpicker', 'type' =>'select', 'options' => $end_hours_format,'label'=>false,'div'=>false));?>
		<?=$this->Form->text('price',array('default'=>$default_service_price,'label'=>false,'div'=>false, 'placeholder'=>'price'));?>
		<?=$this->Form->text('fire_sales_price',array('default'=>'','label'=>false,'div'=>false, 'placeholder'=>'Fire Sales Price'));?>
		<?=$this->Form->text('fire_sales_day_margin',array('default'=>'','label'=>false,'div'=>false, 'placeholder'=>'Fire Sales Margin'));?>
		<input class="dashboard-buttons dashboard-buttons btn orange" type="submit" value="Add Slot" />
	<?php echo $this->Form->end();?>
        </div>
</div>
</div>

<script>
	/*
 $("#ServiceSlotStartTime").change(function () {
	$('#loader_slot').show();
	$("#ServiceSlotEndTime").attr('disabled',true);

    $.post('/vendor_manager/services/ajax_end_time/<?=$service_id?>/' + $(this).val(), function(data) {
		$('#loader_slot').hide();
		$("#ServiceSlotEndTime").attr('disabled',false);
        $("#ServiceSlotEndTime").empty().append(data);
		}, 'html'); 
     
 });
 */
</script>  
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
<script type='text/javascript'>
	$(document).ready(function () {
		sameHeight('left-area','right-area');
	});
	$("select.selectpicker").selectpicker();
</script>
