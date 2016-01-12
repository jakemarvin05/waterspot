<script language="javascript">
function saveform(){
	document.getElementById('VendorServiceAvailabilityPublish').value=1;
	document.getElementById('VendorServiceAvailability').submit();
}
</script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
 
 <div>
    <article>
        <header>
            <h2>
                <?php
                    if (isset($this->request->data['VendorServiceAvailability']['id']) && $this->request->data['VendorServiceAvailability']['id']):
                          echo  __('Update Vendor Service Availability');
                    else:
                          echo  __('Add Vendor Service Availability ');
                           $this->request->data['VendorServiceAvailability']['status']=1;
                    endif;
                ?>
                
            </h2>
           
        </header>
    </article>
    <?php echo $this->element('admin/message');?>
		<?php
		$weekday_count = 0;
		$weekend_count = 0;
		$special_count = 0;
		?>
		<div class="recent-service-slot-availability">
		<? if(!empty($service_availabity_details)){ ?>
			<h2> Recent Service Slot Availability</h2>

			<!-- for weekdays -->
			<h4>Weekday</h4>
			<table border="0" cellpadding="10" cellspacing="0" style="border-collapse:collapse; margin-bottom:20px;" class="range-table">
		
			<?  foreach($service_availabity_details as $service_availabity_detail) {
				if ($service_availabity_detail['VendorServiceAvailability']['slot_type'] != 1) continue;
				$weekday_count++;
				?>
				<tr align="center" style="border-bottom:1px solid #9b9b9b;">
				<td width="30%">
					<? if(!empty($service_availabity_detail['VendorServiceAvailability']['p_date'])) {
								echo date(Configure::read('Calender_format_php'),strtotime($service_availabity_detail['VendorServiceAvailability']['p_date'])); 
					 }else {
						 echo date(Configure::read('Calender_format_php'),strtotime($service_availabity_detail['VendorServiceAvailability']['start_date']))." To ".date(Configure::read('Calender_format_php'),strtotime($service_availabity_detail['VendorServiceAvailability']['end_date']));


					}?>
				</td>
				<td align="left" width="40%"><?php
					$slotsJSONString = $service_availabity_detail['VendorServiceAvailability']['slots'];
					$slots = json_decode($slotsJSONString);

					foreach($slots as $slot) {
						echo $this->Time->meridian_format($slot->start_time). " to ".$this->Time->end_meridian_format($slot->end_time)."</br>";
					}

					?></td>
				<td align="right" width="15%">
					<?=$this->Html->link($this->Html->image('editprofile-icon.png'),array('plugin'=>'vendor_manager','controller'=>'vendor_service_availabilities','action'=>'admin_index',$vendor_id,$service_availabity_detail['VendorServiceAvailability']['service_id'],$service_availabity_detail['VendorServiceAvailability']['id']),array('escape' => false));?>
				</td>
				<td align="right" width="15%">	
					<?=$this->Html->link($this->Html->image('del.png'),array('plugin'=>'vendor_manager','controller'=>'vendor_service_availabilities','action'=>'availability_del',$vendor_id,$service_availabity_detail['VendorServiceAvailability']['service_id'],$service_availabity_detail['VendorServiceAvailability']['id']),array('escape' => false,"onclick"=>"return confirm('Are you sure you want to delete availability slots?')"));?>
				</td>
			</tr>
			<? } ?>
			<?php 
				if ($weekday_count == 0) {
					echo '<tr><td colspan="4">No slots defined</td></tr>';
				}
			?>
			</table>

			<!-- for weekends -->
			<h4>Weekend</h4>
			<table border="0" cellpadding="10" cellspacing="0" style="border-collapse:collapse; margin-bottom:20px;" class="range-table">
		
			<?  foreach($service_availabity_details as $service_availabity_detail) {
				if ($service_availabity_detail['VendorServiceAvailability']['slot_type'] != 2) continue;
				$weekend_count++;
				?>
				<tr align="center" style="border-bottom:1px solid #9b9b9b;">
				<td width="30%">
					<? if(!empty($service_availabity_detail['VendorServiceAvailability']['p_date'])) {
								echo date(Configure::read('Calender_format_php'),strtotime($service_availabity_detail['VendorServiceAvailability']['p_date'])); 
					 }else {
						 echo date(Configure::read('Calender_format_php'),strtotime($service_availabity_detail['VendorServiceAvailability']['start_date']))." To ".date(Configure::read('Calender_format_php'),strtotime($service_availabity_detail['VendorServiceAvailability']['end_date']));


					}?>
				</td>
				<td align="left" width="40%"><?php
					$slotsJSONString = $service_availabity_detail['VendorServiceAvailability']['slots'];
					$slots = json_decode($slotsJSONString);

					foreach($slots as $slot) {
						echo $this->Time->meridian_format($slot->start_time). " to ".$this->Time->end_meridian_format($slot->end_time)."</br>";
					}

					?></td>
				<td align="right" width="15%">
					<?=$this->Html->link($this->Html->image('editprofile-icon.png'),array('plugin'=>'vendor_manager','controller'=>'vendor_service_availabilities','action'=>'admin_index',$vendor_id,$service_availabity_detail['VendorServiceAvailability']['service_id'],$service_availabity_detail['VendorServiceAvailability']['id']),array('escape' => false));?>
				</td>
				<td align="right" width="15%">	
					<?=$this->Html->link($this->Html->image('del.png'),array('plugin'=>'vendor_manager','controller'=>'vendor_service_availabilities','action'=>'availability_del',$vendor_id,$service_availabity_detail['VendorServiceAvailability']['service_id'],$service_availabity_detail['VendorServiceAvailability']['id']),array('escape' => false,"onclick"=>"return confirm('Are you sure you want to delete availability slots?')"));?>
				</td>
			</tr>
			<? } ?>
			<?php 
				if ($weekend_count == 0) {
					echo '<tr><td colspan="4">No slots defined</td></tr>';
				}
			?>
			</table>


			<!-- for special -->
			<h4>Special</h4>
			<table border="0" cellpadding="10" cellspacing="0" style="border-collapse:collapse; margin-bottom:20px;" class="range-table">
		
			<?  foreach($service_availabity_details as $service_availabity_detail) {
				if ($service_availabity_detail['VendorServiceAvailability']['slot_type'] != 3) continue;
				$special_count++;
				?>
				<tr align="center" style="border-bottom:1px solid #9b9b9b;">
				<td width="30%">
					<? if(!empty($service_availabity_detail['VendorServiceAvailability']['p_date'])) {
								echo date(Configure::read('Calender_format_php'),strtotime($service_availabity_detail['VendorServiceAvailability']['p_date'])); 
					 }else {
						 echo date(Configure::read('Calender_format_php'),strtotime($service_availabity_detail['VendorServiceAvailability']['start_date']))." To ".date(Configure::read('Calender_format_php'),strtotime($service_availabity_detail['VendorServiceAvailability']['end_date']));


					}?>
				</td>
				<td align="left" width="40%"><?php
					$slotsJSONString = $service_availabity_detail['VendorServiceAvailability']['slots'];
					$slots = json_decode($slotsJSONString);

					foreach($slots as $slot) {
						echo $this->Time->meridian_format($slot->start_time). " to ".$this->Time->end_meridian_format($slot->end_time)."</br>";
					}

					?></td>
				<td align="right" width="15%">
					<?=$this->Html->link($this->Html->image('editprofile-icon.png'),array('plugin'=>'vendor_manager','controller'=>'vendor_service_availabilities','action'=>'admin_index',$vendor_id,$service_availabity_detail['VendorServiceAvailability']['service_id'],$service_availabity_detail['VendorServiceAvailability']['id']),array('escape' => false));?>
				</td>
				<td align="right" width="15%">	
					<?=$this->Html->link($this->Html->image('del.png'),array('plugin'=>'vendor_manager','controller'=>'vendor_service_availabilities','action'=>'availability_del',$vendor_id,$service_availabity_detail['VendorServiceAvailability']['service_id'],$service_availabity_detail['VendorServiceAvailability']['id']),array('escape' => false,"onclick"=>"return confirm('Are you sure you want to delete availability slots?')"));?>
				</td>
			</tr>
			<? } ?>
			<?php 
				if ($special_count == 0) {
					echo '<tr><td colspan="4">No slots defined</td></tr>';
				}
			?>
			</table>


		<? }?>	
		</div>	
		
		
		<div class="first-box">
			<h3>Add</h3>
		<?=$this->Form->create('VendorServiceAvailability',array('id'=>'service_availability','url'=>array('controller'=>'vendor_service_availabilities','action'=>'index',$vendor_id,$service_id),'novalidate' => true));
		echo $this->Form->hidden('id');
		echo $this->Form->hidden('service_id',array('value'=>$service_id));?>
                <div style="margin: 15px 0 20px 0;"></div>
		<?=$this->Form->hidden('form-name',array('required'=>false,'value'=>'date_range')); ?>
			<div class="select-slot">
				<?=$this->Form->text('start_date',array('label'=>false,'div'=>false));?>
				<?=$this->Form->error('start_date',null,array('wrap' => 'div', 'class' => 'error-message')); ?>
				<div class="mid-to">to</div> 
				<?=$this->Form->text('end_date',array('label'=>false,'div'=>false));?>
				
				<?=$this->Form->error('end_date',null,array('wrap' => 'div', 'class' => 'error-message')); ?>

			</div>
			<?php
			if(!empty($this->request->data['VendorServiceAvailability']['slots'])) {
				$slotsx = $this->request->data['VendorServiceAvailability']['slots'];
				$checked_slots = [];
				foreach($slotsx as $slotx) {
					$checked_slots[] = $slotx->start_time . '_' . $slotx->end_time . '_' . $slotx->price;
				}
			}
			?>
			<div class="add-slots">
				Type: 
					<?=$this->Form->input('slot_type',array('type' =>'select','label'=>false,'div'=>false, 'options'=>$service_slot_types));?>
				<?=$this->Form->error('slot_type',null,array('wrap' => 'div', 'class' => 'error-message')); ?>
				<br><br>
				<button type="button" onclick="$('.check-slot').prop('checked', !$('.check-slot').prop('checked'));">Select all</button><br><br>
				<div id="slots_content"></div>
				<? if(!empty($service_slots['service_slots_index'])){
					$checkbox_slots = [];
					$i=0;$flag="grey-box";
					$count=count($service_slots['service_slots_index']);
					 
					foreach($service_slots['service_slots_index'] as $key=>$slot) {
						if($i%4==0 and $i!=0){ ?>
							</div>
						<? }
						
						if($i%4==0){
						?>
							<div class="<?=$flag;?>">
						<?	 
							$flag=($flag=="grey-box")?"white-box":"grey-box";
							
						}?>
						<span>
							<? // check filter on edit case
								$checkstaus='';
								if(!empty($this->request->data['VendorServiceAvailability'])) {
									$checkstaus=in_array($slot,$checked_slots)?'checked':'';
								}
							 
							 ?>

							<?php
								$slot_time=explode('_',$slot);
								$checkbox_slots[$service_slots['slot_types'][$key]][] = $this->Form->checkbox('slots.',array('value'=>$slot,'id'=>$key,'class'=>'check-box check-slot','label'=>false,'div'=>false,$checkstaus)) . '<label for="'.$key.'" class="checkbox-label">' . $this->Time->meridian_format($slot_time[0]). " To ".$this->Time->end_meridian_format($slot_time[1]).", Price:".$slot_time[2] . '</label><br>';
							?>
							</span>
						
						<? $i++;
					}
					if($count%4!=0){
					?>
						</div>
					<? }
					if($count%4==0){ ?>
						</div>
					<? }	
				} else {?> 
				
					 <div class="grey-box">
						<script type="text/javascript">
							 $(document).ready(function(){
								 $('#service_availability > button[type=submit]').attr({'disabled':true});
								});
						 </script>
						There are no service slot available. Please 
						 <?=$this->Html->link('add slot',array('controller'=>'services','action'=>'admin_add_service_slots',$vendor_id,$service_id)) ?>
					
					</div>
				 
				<? }?>
				<span id="VendorServiceAvailabilitySlots"></span>
				<?=$this->Form->error('slots',null,array('wrap' => 'div', 'class' => 'error-message')); ?>
			</div>
			<button type="submit">
				<?php 
                if (isset($this->request->data['VendorServiceAvailability']['id']) && $this->request->data['VendorServiceAvailability']['id']):
                    echo __('Update Vendor Service Availability');
                else:
                    echo __('Add Vendor Service Availability');
                endif;								
            ?>
        </button> or 
        <?php echo $this->Html->link('Cancel', array('controller'=>'services', 'action' => 'servicelist',$vendor_id));?>
                                
	<?php echo $this->Form->end();?>
		</div>
		<div class="clear"></div>
	 
			 
</div>
<?php
	$current_slot_type = $this->request->data['VendorServiceAvailability']['slot_type'] ? $this->request->data['VendorServiceAvailability']['slot_type'] : 1;
?>
<script type="text/javascript">
	var weekdays = '';
	var weekends = '';
	var special  = '';
	<?php
		foreach ($checkbox_slots[1] as $slot) {
			?>
				weekdays += '<?php echo $slot; ?>';
			<?php
		}
		foreach ($checkbox_slots[2] as $slot) {
			?>
				weekends += '<?php echo $slot; ?>';
			<?php
		}
		foreach ($checkbox_slots[3] as $slot) {
			?>
				special += '<?php echo $slot; ?>';
			<?php
		}
		?>
			var def = <?php echo $current_slot_type; ?>;
		<?php
	?>
	if (def == 1) {
		if (weekdays != '') {
			$('#slots_content').html(weekdays);
		} else {
			$('#slots_content').html('no slots to show');
		}
	} else if (def == 2) {
		if (weekends != '') {
			$('#slots_content').html(weekends);
		} else {
			$('#slots_content').html('no slots to show');
		}
	} else {
		if (special != '') {
			$('#slots_content').html(special);
		} else {
			$('#slots_content').html('no slots to show');
		}
	}
	$('#VendorServiceAvailabilitySlotType').on('change', function(){
		var val = parseInt($(this).val());
		if (val == 1) {
			if (weekdays != '') {
				$('#slots_content').html(weekdays);
			} else {
				$('#slots_content').html('no slots to show');
			}
		} else if (val == 2) {
			if (weekends != '') {
				$('#slots_content').html(weekends);
			} else {
				$('#slots_content').html('no slots to show');
			}
		} else {
			if (special != '') {
				$('#slots_content').html(special);
			} else {
				$('#slots_content').html('no slots to show');
			}
		}
	});

</script>
<script type="text/javascript">
$(function() {
   $( "#VendorServiceAvailabilityStartDate" ).datepicker({
      defaultDate: "+0d",
      dateFormat: "<?=Configure::read('Calender_format');?>",
      minDate: 0,
      dateFormat: "<?=Configure::read('Calender_format');?>",
      changeMonth: true,
      onClose: function( selectedDate ) {
        $( "#VendorServiceAvailabilityEndDate" ).datepicker( "option", "minDate", selectedDate );
      }
    });
    $( "#VendorServiceAvailabilityEndDate" ).datepicker({
      defaultDate: "+0d",
      dateFormat: "<?=Configure::read('Calender_format');?>",
      changeMonth: true,
      onClose: function( selectedDate ) {
        $( "#VendorServiceAvailabilityStartDate" ).datepicker( "option", "maxDate", selectedDate );
      }
    });
    // particular date picker
    $( "#p_date" ).datepicker({
      defaultDate: "+0d",
      dateFormat: "<?=Configure::read('Calender_format');?>",
      minDate: 0,
      changeMonth: true,
       
    });
  }); 
</script>
 <?php $path = $this->Html->webroot; ?>
 <script type="text/javascript">
	 $(document).ready(function(){
		 $('#service_availability').submit(function(){
			//var data = $(this).serializeArray();
			var data = new FormData(this);
			var formData = $(this);
			var status = 0;
		   
		    $.each(this,function(i,v){
				$(v).removeClass('invalid form-error');
				});
			$('.error-message').remove();
			$('#service_availability > span#for_owner_cms').show();
			$('#service_availability > button[type=submit]').attr({'disabled':true});
			   
		   $.ajax({
				url: '<?=$path?>vendor_manager/vendor_service_availabilities/validation/date_range',
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
							$('#'+i).addClass("invalid form-error").after('<span class="error-message">'+v+'</span>');
							$('#'+i).bind('click',function(){
								$(this).removeClass('invalid form-error');
								$(this).next('.error-message').remove();
								});
						});
					}else{
						status = 1;
					}
				   
				 }
			});
			if(status==0){
			   $("html, body").animate({ scrollTop: 0 }, "slow");
			   $('#service_availability > button[type=submit]').attr({'disabled':false});
			   $('#service_availability > span#for_owner_cms').hide();
			}
		    return (status===1)?true:false;
		   // return true; 
		});
	});
</script>
<script type="text/javascript">
	/*
$(function($) {
	$("#VendorServiceAvailabilityStartDate").change(function(){
		var service_id=$( "#VendorServiceAvailabilityServiceId" ).val();
		var startdate=$( "#VendorServiceAvailabilityStartDate" ).val();
		var enddate=$( "#VendorServiceAvailabilityEndDate" ).val();
		if(startdate!='' && enddate!='' && service_id!='') {
			get_service_availability(service_id,startdate,enddate);
		}
			
	});
	$("#VendorServiceAvailabilityEndDate").change(function(){
		var service_id=$( "#VendorServiceAvailabilityServiceId" ).val();
		var startdate=$( "#VendorServiceAvailabilityStartDate" ).val();
		var enddate=$( "#VendorServiceAvailabilityEndDate" ).val();
		if(startdate!='' && enddate!='' && service_id!='') {
			get_service_availability(service_id,startdate,enddate);
		}
		
	});
});
function get_service_availability(service_id,startdate,enddate)	{
 $.ajax({
		 url :'<?=$path?>vendor_manager/vendor_service_availabilities/ajax_get_availbility_range',
		 type:'POST',
		 data:{'service_id':service_id,'start_date':startdate,'end_date':enddate},
		 success: function (result)
		 {
			 alert(result);
			 

		 }
	}); 
//alert(service_id+startdate+enddate)
}*/
</script>
