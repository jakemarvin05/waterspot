<h3>Available slots on chosen dates</h3>
<?php
function hoursToSeconds($hour) { // $hour must be a string type: "HH:mm:ss"
	$parse = array();
	if (!preg_match ('#^(?<hours>[\d]{2}):(?<mins>[\d]{2}):(?<secs>[\d]{2})$#',$hour,$parse)) {
		// Throw error, exception, etc
		throw new RuntimeException ("Hour Format not valid");
	}
	return (int) $parse['hours'] * 3600 + (int) $parse['mins'] * 60 + (int) $parse['secs'];
}
?>

<? if(!empty($service_slots)) {?>

	<?php foreach($service_slots as $service_slots) { ?>
		<div class="dt">
			<div class="dates"><?= date("F j, Y", strtotime($service_slots['start_date'])); ; ?></div>
				<? if(!empty($service_slots['slotindex'] ))	{
					foreach($service_slots['slotindex'] as $slotkey=>$slot) {
						//slot date,service_id,service_time
						if ($slot->fire_sales_day_margin && $slot->fire_sales_price) {
							$slot_price = (strtotime($service_slots['start_date']) > (time() + ((60*60*24)*$slot->fire_sales_day_margin))) ? $slot->price : $slot->fire_sales_price;
						} else {
							$slot_price = $slot->price;
						}
						$slotkey_value=strtotime($service_slots['start_date'])."_".$service_slots['service_id']."_".$slotkey."_".$slot->start_time."_".$slot->end_time."_".$slot_price;
						?>
						<div class="check <?php echo ($slot->booked?"booked":"") ?>"> <?=$this->Form->checkbox('Activity.slots.',array('value'=>$slotkey_value,'data-slot'=>$slot->slot_type, 'disabled'=>($slot->booked?'disabled':'false'),'id'=>$slotkey,'class'=>'check-box','data-price'=>$slot_price,'label'=>false,'div'=>false));?><label for="<?=$slotkey?>" class="checkbox-label"><?
								// Calculate the hour duration base on the slot schedule
								$end_time_in_sec = hoursToSeconds($slot->end_time)+1;
								$start_time_in_sec = hoursToSeconds($slot->start_time);
								$duration = ($end_time_in_sec - $start_time_in_sec)/3600;

								echo "<span ".($slot->booked?"class='strike'":"").">".$this->Time->meridian_format($slot->start_time). " To ".$this->Time->end_meridian_format($slot->end_time)."</span>";
						?></label>
						<?php echo '<br/>Price : $'.$slot_price . '&nbsp;&nbsp;&nbsp;';
							if ($service_details['no_person'] > 1) {
								echo "(" . $slot->available_count . " slots remaining)";
							}
							if ($service_details['is_private'] && $service_details['num_pax_included'] > 0 && $service_details['duration'] > 0 ){
								echo "<br><br><span style='font-size: 12px;'>(Price includes " . $service_details['num_pax_included'] . " pax for a duration of ". $duration ." hrs)</span>";
							}
						?>
						<?php if ($service_details['min_participants'] > 1): ?>
						<div class="progressinfo" style="color:#777;width:100%;">
							<span class="current" style="color:#FEAB32"><?php echo ($service_details['min_participants'] - $slot->current_booked_count) < 0 ? 0 : ($service_details['min_participants'] - $slot->current_booked_count) ; ?></span> out of <?php echo $service_details['min_participants']; ?> Participants to go
						</div>
						<div class="completion" style="margin:5px 0 15px 0; height:5px; width:100%;">
                            <div class="progressbar" style="width:<?php echo ($slot->current_booked_count/$service_details['min_participants'])*100 > 100 ? 100 : ($slot->current_booked_count/$service_details['min_participants'])*100 ?>%;"></div>
                        </div>
                        <div class="clearfix"></div>
						<?php endif; ?>
						</div>
				<? } } // end if 
					else {?>
					<?=$this->Form->hidden('Activity.slots.',array('value'=>'','type'=>'checkbox','class'=>'check-box','label'=>false,'div'=>false));?>	
					<div class="check"> There are no slots </div>
				<? } ?>
				

			<div class="clear"></div>
		</div>
		<?php } ?>
		<span id="ActivitySlots" style="width:100%;"></span>
	<?  
}
else{ 	?>
	<span id="ActivitySlots" style="width:100%;"></span>

	<?=$this->Form->hidden('Activity.slots.',array('value'=>'','type'=>'checkbox','class'=>'check-box','label'=>false,'div'=>false));?>	
	<div class="check"> There are no slots </div>
	<?php if (isset($recommended_dates)) { ?>
		<h5>Recommended Dates:</h5>
		<ul>
		<?php foreach($recommended_dates as $date) {
			echo "<li>$date</li>";
		} ?>
		</ul>
	<?php } ?>
<? }?>

<script type="text/javascript">
	get_recommended_dates();
</script>
