<link rel="stylesheet" href="https://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

<div class="container-fluid vendor-panel">
<div class="hr-line"></div>
<div class="clear" style="margin-top:80px;"></div>

<h2 class="page-title">Service Availability</h2>

<?=$this->element('VendorManager.left-vendor-panel');?>

<div class="right-area col-sm-9 col-xs-12">
	<?=$this->element('message');?>


    <div class="dashboard-form-row indexedit edit">
        <h3 class="dashboard-heading">Recent Service Slot Availability</h3>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="dashboard-content">

        <? if (count($service_availabity_details) == 0) { ?>
            <th>No availabilities created yet.</th>

        <? } else { ?>

            <th>Availability duration</th>
            <th>Timeslots</th>
            <th>Edit</th>
            <th>Delete</th>

            <? foreach($service_availabity_details as $service_availabity_detail) { ?>
            <tr>
                <td>
                    <? if(!empty($service_availabity_detail['VendorServiceAvailability']['p_date'])) {
                             echo date(Configure::read('Calender_format_php'),strtotime($service_availabity_detail['VendorServiceAvailability']['p_date'])); 
                    } else {
                        echo date(Configure::read('Calender_format_php'),strtotime($service_availabity_detail['VendorServiceAvailability']['start_date']))." To ".date(Configure::read('Calender_format_php'),strtotime($service_availabity_detail['VendorServiceAvailability']['end_date']));
                    } ?>
                </td>
                <td>
                    <?php
                    // TODO: remove this function once the square brackets are fixed?
                    //echo $service_availabity_detail['VendorServiceAvailability']['slots'];
//                    function jsonDecode($json) {
//                        if ($json[0] == "[") {
//                            $newString = substr($json, 1, strlen($json)-2);
//                            $json = '{'.$newString.'}';
//                        }
//                        //print_r(json_decode($json));
//                        return json_decode($json);
//                    }


                    $slotsJSONString = $service_availabity_detail['VendorServiceAvailability']['slots'];
					if ($slotsJSONString[0] == "[") {
						$newString = substr($slotsJSONString, 1, strlen($slotsJSONString)-2);
						$json = '{'.$newString.'}';
					}
					$slots = json_decode($json);

                    foreach($slots as $slot) { 
                        echo $this->Time->meridian_format($slot->start_time). " to ".$this->Time->end_meridian_format($slot->end_time)."</br>";
                    } 
                    ?>

                </td>

                <td class="align-center">
                <?=$this->Html->link("<i class=\"fa fa-pencil-square-o\"></i> Edit",array('plugin'=>'vendor_manager','controller'=>'vendor_service_availabilities','action'=>'index',$service_availabity_detail['VendorServiceAvailability']['service_id'],$service_availabity_detail['VendorServiceAvailability']['id']),array('escape' => false));?>
                </td>
                <td class="align-center">
                <?=$this->Html->link("<i class=\"fa fa-times\"></i>",array('plugin'=>'vendor_manager','controller'=>'vendor_service_availabilities','action'=>'availability_del',$service_availabity_detail['VendorServiceAvailability']['service_id'],$service_availabity_detail['VendorServiceAvailability']['id']),array('escape' => false,"onclick"=>"return confirm('Are you want to delete availability slots?')"));?>
                </td>
            </tr>
            <? } ?>
        <? } ?>
        </table>
        <br>
    </div>


	<div class="clear"></div>

	<h3 class="dashboard-heading">Add Availability of Slots</h3>
	<?=$this->Form->create('VendorServiceAvailability',array('id'=>'service_availability','url'=>array('controller'=>'vendor_service_availabilities','action'=>'index',$service_id),'novalidate' => true));
		echo $this->Form->hidden('id'); 
		echo $this->Form->hidden('service_id',array('value'=>$service_id));?>
		<?=$this->Form->hidden('form-name',array('required'=>false,'value'=>'date_range')); ?>
		<div class="dashboard-form-row indexedit">
                    <div class="cont">
			<div class="dashboard-slot-date">
                            <div class="labelbox">
                                <label>
				Select Date(s): <span style="color:#ff0000;">*</span>
                                </label>
                            </div>
				<?=$this->Form->text('start_date',array('label'=>false,'div'=>false));?>
				<?=$this->Form->error('start_date',null,array('wrap' => 'div', 'class' => 'error-message')); ?>
				<span class="txt"> to </span>
				<?=$this->Form->text('end_date',array('label'=>false,'div'=>false));?>
				<?=$this->Form->error('end_date',null,array('wrap' => 'div', 'class' => 'error-message')); ?>
			</div>
                        <br>
                        <br>
			<div class="dashboard-slot-time">
                            <div class="labelbox">
                                <label>
				Select Slot(s): <span style="color:#ff0000;">*</span>
                                </label>
                            </div>
				<span id="VendorServiceAvailabilitySlots"></span>
				<?=$this->Form->error('slots',null,array('wrap' => 'div', 'class' => 'error-message')); ?>
				<? if(!empty($service_slots['service_slots_index'])){
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
						<span class="inedxedit">
							<? // check filter on edit case
								$checkstaus='';
								if(!empty($serviceAvailabilitySlots)) {
									$checkstaus=in_array($slot, $serviceAvailabilitySlots)?'checked':'';
								}
							 ?>
							<?=$this->Form->checkbox('slots.',array('value'=>$slot,'id'=>$key,'class'=>'check-box','label'=>false,'div'=>false,$checkstatus));?>
							<label for="<?=$key?>" class="checkbox-label"><? $slot_time=explode('_',$slot);
								echo $this->Time->meridian_format($slot_time[0]). " - ".$this->Time->end_meridian_format($slot_time[1]).", Price:".$slot_time[2];?></label>
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
					 <div class="no-details">
						There are no service slot available. Please 
						<?=$this->Html->link('add slot',array('controller'=>'services','action'=>'add_service_slots',$service_id)) ?>
					</div>
				<? }?>
			</div>
                        <br>
			<?php if (!empty($serviceAvailabilitySlots)): ?>
				<input type="submit" class="dashboard-buttons btn" value="Amend Availability" />
			<?php else: ?>
				<input type="submit" class="dashboard-buttons btn" value="Add Availability" />
			<?php endif; ?>

                </div>
		</div>
	<?php echo $this->Form->end();?>
</div>
</div>

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
      changeMonth: true
      
      
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
<script type='text/javascript'>
	$(document).ready(function () {
		sameHeight('left-area','right-area');
	});
</script>
