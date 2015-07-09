<h3>Available slots on chosen dates</h3>

<? if(!empty($service_slots)) {?>
	<?php foreach($service_slots as $service_slots) { ?>
		<div class="dt">
			<div class="dates"><?=$service_slots['start_date']; ?></div>
				<? if(!empty($service_slots['slotindex'] ))	{
					foreach($service_slots['slotindex'] as $slotkey=>$slot) { 
						//slot date,service_id,service_time
						$slotkey_value=strtotime($service_slots['start_date'])."_".$service_slots['service_id']."_".$slotkey."_".$slot->start_time."_".$slot->end_time;
						?>
						<div class="check"> <?=$this->Form->checkbox('Activity.slots.',array('value'=>$slotkey_value,'id'=>$slotkey,'class'=>'check-box','label'=>false,'div'=>false));?><label for="<?=$slotkey?>" class="checkbox-label"><?
						echo $this->Time->meridian_format($slot->start_time). " To ".$this->Time->end_meridian_format($slot->end_time);
						?></label></div>
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
