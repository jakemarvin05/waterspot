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
                          echo  __('Update time slots');
                    else:
                          echo  __('Add time slots');
                           $this->request->data['ServiceSlot']['status']=1;
                    endif;
                ?>
                [<?=$service_title?>]
            </h2>
           
        </header>
    </article> 
    <?php echo $this->element('admin/message');?>

	    <?php if(!empty($booked_slots)) {
	    	$table = '';
	    ?>

    	<?php foreach($booked_slots as $book_slot) {

    		$table_str = '<tr>';
    		$table_str .= '<td colspan="3">'.$book_slot['start_time'].'</td>';
    		$table_str .= '<td colspan="2">'.$book_slot['end_time'].'</td>';
    		$table_str .= '<td colspan="2">'.($book_slot['status']==2?'Affected slot':$book_slot['remarks']).'</td>';
			$table_str .= '<td>'.$this->Html->link($this->Html->image('del.png'),array('plugin'=>'vendor_manager','controller'=>'services','action'=>'booking_slot_delete',$vendor_id,$service_id,$book_slot['id']),array('class'=>($book_slot['status']==2?'disabled':''),'escape'=>false,"onclick"=>"return confirm('Are you sure you wish to delete this slot?')")).'</td>';
    		$table_str .= '</tr>';
			$table .= $table_str;
		}
		?>

    	<h4>Booked Slots</h4>
		<table width="100%" style="margin-bottom:10px;">
			<tr>
				<td colspan="3"><strong>Start Time</strong></td>
				<td colspan="2"><strong>End Time</strong></td>
				<td colspan="2"><strong>Remarks</strong></td>
				<td><strong>Cancel</strong></td>
			</tr>
			<?php
				if (strlen($table)) {
					echo $table;
				} else {
					echo '<tr><td colspan="8">No slots defined</td></tr>';
				}
			?>
		</table>
	<?php }else{ ?>
		<div class="no-record">No slot is available here</div>
	<?php } ?>

	<h2 style="font-size: 155%; margin-top: 15px;">Book a Slot</h2>

	<?php echo $this->Form->create('BookingSlot',array('name'=>'slot','id'=>'book_slot','url'=>array('plugin'=>'vendor_manager','controller'=>'services','action'=>'book_slots',$vendor_id,$service_id),'onsubmit'=>'//return validatefields();','type'=>'file','novalidate' => true));?>
	<?php echo $this->Form->hidden('service_id',array('value'=>$service_id));?>
	<?php echo $this->Form->hidden('status',array('value'=>3)); ?>
	<?php echo $this->Form->hidden('no_participants',array('value'=>1)); ?>

	<div class="book-slot-forms">
		<div class="col-3">
			<h3>Select Date</h3>
			<?= $this->Form->text('start_date', array('type' => 'hidden', 'class' => 'date-icon', 'autocomplete' => 'off')); ?>
			<div id="date-picker"></div>
		</div>
		<div class="col-3">
			<h3>Select Slot</h3>
			<div id="slots_form"></div>
		</div>
		<div class="col-3">
			<h3>Remarks</h3>
			<?= $this->Form->textarea('remarks', array('class' => 'date-icon', 'autocomplete' => 'off', 'maxlength' => 50)); ?>

			<br>
			<br>
			<button type="submit">
				<?php
				echo __('Book Slot');
				?>
			</button> or
			<?php echo $this->Html->link('Cancel', array('controller'=>'services', 'action' => 'servicelist',$vendor_id));?>

		</div>
	</div>



		<?php echo $this->Form->end();?>
</div>
<script type="text/javascript">
<?php $path = $this->Html->webroot; ?>
    $(document).ready(function(){

		$('#BookingSlotStartDate').val('<?php echo date("Y-m-d"); ?>');

		get_service_availability();

    });
 </script>

<link rel="stylesheet" href="https://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script type="text/javascript">
	$( "#date-picker" ).datepicker({
			dateFormat: "<?php echo Configure::read('Calender_format'); ?>",
			minDate: 0,
			onSelect:function(selectedDate){
				console.log(selectedDate);
				$("#BookingSlotStartDate" ).val(selectedDate);
				get_service_availability();
				$(this).change();
			}
		}
	);

	function get_service_availability() {

		var service_id = $("#BookingSlotServiceId").val();
		var startdate = $("#BookingSlotStartDate").val();
		var no_participants = 1;


		if (startdate == '' || service_id == '' || no_participants <= 0) {
			return;
		}

		$.ajax({
			url: '<?=$path?>activity/ajax_get_availbility_range',
			type: 'POST',
			data: {'service_id': service_id, 'start_date': startdate, 'no_participants': no_participants},
			success: function (result) {

				$('#slots_form').show();
				$('#loader_slots').hide();
				$("#slots_form").html(result);


			}
		});
	}

	function get_recommended_dates() {
		var service_id = $("#BookingSlotServiceId").val();
		var startdate = $("#BookingSlotStartDate").val();
		$.ajax({
			url: '<?=$path?>activity/ajax_get_recommended_dates',
			type: 'POST',
			data: {'service_id': service_id, 'start_date': startdate},
			success: function (result) {
				$("#recommended_slots").html(result);
			}
		});
	}



</script>
