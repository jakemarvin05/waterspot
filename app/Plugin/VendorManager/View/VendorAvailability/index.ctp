<div class="hr-line"></div>
<div class="clear"></div>
<?=$this->element('breadcrumbs');?>
<h2 class="page-title">Service <span style="color:#000;">Availability</span></h2>
<div class="middle-area service-avail">
	<?=$this->element('message');?>
		<div class="first-box">
			<h2>Date In Range</h2>
		<table border="0" cellpadding="10" cellspacing="0" style="border-collapse:collapse;" class="range-table">
			<tr align="center" style="border-bottom:1px solid #9b9b9b;">
				<td width="20%">2-12-2013 to 5-12-2013</td>
				<td align="left" width="50%">4pm to 5pm, 5pm to 6pm, 6pm to 7pm, 9pm to 10pm</td>
				<td align="right" width="15%">
					<?=$this->Html->link($this->Html->image('edit-icon.png',array('alt'=>'Add/Update Slot')),array('plugin'=>false,'controller'=>'services','action'=>'add_slots',1),array('escape' => false));?>
				</td>
				<td align="right" width="15%">	
					<?=$this->Html->link($this->Html->image('del.png',array('alt'=>'Delete')),array('plugin'=>false,'controller'=>'vendor_availability','action'=>'index',1),array('escape' => false));?>
				</td>
			</tr>
			<tr align="center" style="border-bottom:1px solid #9b9b9b;">
				<td width="20%">2-12-2013 to 5-12-2013</td>
				<td align="left" width="50%">4pm to 5pm, 5pm to 6pm, 6pm to 7pm, 9pm to 10pm</td>
				<td align="right" width="15%">
					<?=$this->Html->link($this->Html->image('edit-icon.png',array('alt'=>'Add/Update Slot')),array('plugin'=>false,'controller'=>'services','action'=>'add_slots',1),array('escape' => false));?>
				</td>
				<td align="right" width="15%">	
					<?=$this->Html->link($this->Html->image('del.png',array('alt'=>'Delete')),array('plugin'=>false,'controller'=>'vendor_availability','action'=>'index',1),array('escape' => false));?>
				</td>
			</tr>
			<tr align="center" style="border-bottom:1px solid #9b9b9b;">
				<td width="20%">2-12-2013 to 5-12-2013</td>
				<td align="left" width="50%">4pm to 5pm, 5pm to 6pm, 6pm to 7pm, 9pm to 10pm</td>
				<td align="right" width="15%">
					<?=$this->Html->link($this->Html->image('edit-icon.png',array('alt'=>'Add/Update Slot')),array('plugin'=>false,'controller'=>'services','action'=>'add_slots',1),array('escape' => false));?>
				</td>
				<td align="right" width="15%">	
					<?=$this->Html->link($this->Html->image('del.png',array('alt'=>'Delete')),array('plugin'=>false,'controller'=>'vendor_availability','action'=>'index',1),array('escape' => false));?>
				</td>
			</tr>
		</table>
		<h3>Add</h3>
		<div class="select-slot">
			<input type="text" /> 
			<div class="mid-to">to</div> 
			<input type="text" />
		</div>
		<div class="add-slots">
			<span><input type="checkbox" /> 10am to 11am</span>
			<span><input type="checkbox" /> 11am to 12pm</span>
			<span><input type="checkbox" /> 12pm to 01pm</span>
			<span><input type="checkbox" /> 1pm to 2pm</span>
			<div class="grey-box">
				<span><input type="checkbox" /> 12pm to 01pm</span>
				<span><input type="checkbox" /> 1pm to 2pm</span>
				<span><input type="checkbox" /> 12pm to 01pm</span>
				<span><input type="checkbox" /> 1pm to 2pm</span>
			</div>
			<span><input type="checkbox" /> 2pm to 3pm</span>
			<span><input type="checkbox" /> 3pm to 4pm</span>
			<span><input type="checkbox" /> 12pm to 01pm</span>
			<span><input type="checkbox" /> 1pm to 2pm</span>
			<div class="grey-box">
				<span><input type="checkbox" /> 4pm to 5pm</span>
				<span><input type="checkbox" /> 5pm to 6pm</span>
				<span><input type="checkbox" /> 12pm to 01pm</span>
				<span><input type="checkbox" /> 1pm to 2pm</span>
			</div>
			<span><input type="checkbox" /> 6pm to 7pm</span>
			<span><input type="checkbox" /> 7pm to 8pm</span>
			<span><input type="checkbox" /> 12pm to 01pm</span>
			<span><input type="checkbox" /> 1pm to 2pm</span>
			<div class="grey-box">
				<span><input type="checkbox" /> 8pm to 9pm</span>
				<span><input type="checkbox" /> 9pm to 10pm</span>
				<span><input type="checkbox" /> 12pm to 01pm</span>
				<span><input type="checkbox" /> 1pm to 2pm</span>
			</div>
		</div>
		<input type="button" value="Add" />
		</div>
		<div class="clear"></div>
		
		<div class="first-box">
			<h2>Particular Date</h2>
			<table border="0" cellpadding="10" cellspacing="0" style="border-collapse:collapse;" class="range-table">
				<tr align="center" style="border-bottom:1px solid #9b9b9b;">
					<td width="20%">2-12-2013</td>
					<td align="left" width="50%">4pm to 5pm, 5pm to 6pm, 6pm to 7pm, 9pm to 10pm</td>
					<td align="right" width="15%">
						<?=$this->Html->link($this->Html->image('edit-icon.png',array('alt'=>'Add/Update Slot')),array('plugin'=>false,'controller'=>'services','action'=>'add_slots',1),array('escape' => false));?>
					</td>
				<td align="right" width="15%">	
					<?=$this->Html->link($this->Html->image('del.png',array('alt'=>'Delete')),array('plugin'=>false,'controller'=>'vendor_availability','action'=>'index',1),array('escape' => false));?>
				</td>
				</tr>
				<tr align="center" style="border-bottom:1px solid #9b9b9b;">
					<td width="20%">2-12-2013</td>
					<td align="left" width="50%">4pm to 5pm, 5pm to 6pm, 6pm to 7pm, 9pm to 10pm</td>
					<td align="right" width="15%">
						<?=$this->Html->link($this->Html->image('edit-icon.png',array('alt'=>'Add/Update Slot')),array('plugin'=>false,'controller'=>'services','action'=>'add_slots',1),array('escape' => false));?>
					</td>
					<td align="right" width="15%">	
						<?=$this->Html->link($this->Html->image('del.png',array('alt'=>'Delete')),array('plugin'=>false,'controller'=>'vendor_availability','action'=>'index',1),array('escape' => false));?>
					</td>
				
				</tr>
				<tr align="center" style="border-bottom:1px solid #9b9b9b;">
					<td width="20%">2-12-2013</td>
					<td align="left" width="50%">4pm to 5pm, 5pm to 6pm, 6pm to 7pm, 9pm to 10pm</td>
					<td align="right" width="15%">
						<?=$this->Html->link($this->Html->image('edit-icon.png',array('alt'=>'Add/Update Slot')),array('plugin'=>false,'controller'=>'services','action'=>'add_slots',1),array('escape' => false));?>
					</td>
					<td align="right" width="15%">	
						<?=$this->Html->link($this->Html->image('del.png',array('alt'=>'Delete')),array('plugin'=>false,'controller'=>'vendor_availability','action'=>'index',1),array('escape' => false));?>
					</td>
				</tr>
			</table>
			<h3>Add</h3>
			<div class="select-slot">
				<input type="text" /> 
			</div>
			<div class="add-slots">
				<span><input type="checkbox" /> 10am to 11am</span>
				<span><input type="checkbox" /> 11am to 12pm</span>
				<span><input type="checkbox" /> 12pm to 01pm</span>
				<span><input type="checkbox" /> 1pm to 2pm</span>
				<div class="grey-box">
					<span><input type="checkbox" /> 12pm to 01pm</span>
					<span><input type="checkbox" /> 1pm to 2pm</span>
					<span><input type="checkbox" /> 12pm to 01pm</span>
					<span><input type="checkbox" /> 1pm to 2pm</span>
				</div>
				<span><input type="checkbox" /> 2pm to 3pm</span>
				<span><input type="checkbox" /> 3pm to 4pm</span>
				<span><input type="checkbox" /> 12pm to 01pm</span>
				<span><input type="checkbox" /> 1pm to 2pm</span>
				<div class="grey-box">
					<span><input type="checkbox" /> 4pm to 5pm</span>
					<span><input type="checkbox" /> 5pm to 6pm</span>
					<span><input type="checkbox" /> 12pm to 01pm</span>
					<span><input type="checkbox" /> 1pm to 2pm</span>
				</div>
				<span><input type="checkbox" /> 6pm to 7pm</span>
				<span><input type="checkbox" /> 7pm to 8pm</span>
				<span><input type="checkbox" /> 12pm to 01pm</span>
				<span><input type="checkbox" /> 1pm to 2pm</span>
				<div class="grey-box">
					<span><input type="checkbox" /> 8pm to 9pm</span>
					<span><input type="checkbox" /> 9pm to 10pm</span>
					<span><input type="checkbox" /> 12pm to 01pm</span>
					<span><input type="checkbox" /> 1pm to 2pm</span>
				</div>
			</div>
			<input type="button" value="Add" />
			</div>
	<div class="clear"></div>
</div>
