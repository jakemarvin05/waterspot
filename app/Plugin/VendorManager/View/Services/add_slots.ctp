<div class="hr-line"></div>
<div class="clear"></div>
<?=$this->element('breadcrumbs');?>
<h2 class="page-title">View Services</h2>

<?=$this->element('VendorManager.left-vendor-panel');?>

<div class="right-area">
	<div class="dashboard-view-services">
		<?=$this->element('message');?>
		<div class="dashboard-form-row">
			<div class="lablebox">Your Services:</div>
			<div class="fieldbox"> <?=ucfirst($service['ServiceType']['name']); ?></div>
		</div>
		<div class="dashboard-form-row">
			<div class="lablebox">Title:</div>
			<div class="fieldbox"><?=ucfirst($service['Service']['service_title']); ?></div>
		</div>
		<div class="dashboard-form-row">
			<div class="lablebox">Description:</div>
			<div class="fieldbox"><?=ucfirst($service['Service']['description']); ?></div>
		</div>
		<div class="dashboard-form-row">
			<div class="lablebox">Itinerary:</div>
			<div class="fieldbox"><?=ucfirst($service['Service']['itinerary']); ?></div>
		</div>
		<div class="dashboard-form-row">
			<div class="lablebox">How to get there reviews:</div>
			<div class="fieldbox"><?=ucfirst($service['Service']['how_get_review']); ?></div>
		</div>
		<div class="dashboard-form-row">
			<div class="lablebox">Price Per Slot:</div>
			<div class="fieldbox"><?=Configure::read('currency');?><?=number_format($service['Service']['service_price'],2); ?> Per Person</div>
		</div>
		<div class="dashboard-form-row">
			<div class="lablebox">Full Day Price:</div>
			<div class="fieldbox"><?=Configure::read('currency');?><?=number_format($service['Service']['full_day_amount'],2); ?> Per Person</div>
		</div>
		<div class="dashboard-form-row">
			<div class="lablebox">No of Person:</div>
			<div class="fieldbox"><?=$service['Service']['no_person']; ?> / Per Person</div>
		</div>
		<div class="dashboard-form-row">
			<div class="lablebox">Location:</div>
			<div class="fieldbox"><?=ucfirst($service['Service']['location_name']); ?></div>
		</div>
		<? if(!empty($service['ValueAddedService'])) {?>
			<div class="dashboard-form-row">
				<div class="lablebox">Value Added Services:</div>
				<div class="fieldbox">
					<table border="0" cellpadding="0" cellspacing="0" class="dashboard-content">
						<tr>
							<th>Name</th>
							<th>Price</th>
						</tr>
						<? foreach($service['ValueAddedService'] as $key=>$value_added_service){ ?>
							<td class="align-center"><?=$value_added_service['value_added_name']; ?></td>
							<td class="align-center"><?=Configure::read('currency');?><?=number_format($value_added_service['value_added_price'],2); ?></td>
						<? }?>
					</table>
				</div>
			</div>
		<? } ?>
		<div class="dashboard-form-row">
			<div class="lablebox">Slots:</div>
			<div class="fieldbox">
				<? if(!empty($service['ServiceSlot'])) {?>
					<? foreach($service['ServiceSlot'] as $key=>$slot){ ?>
						<div class="one-slot">
							<?=$this->Time->meridian_format($slot['start_time']). " To ".$this->Time->end_meridian_format($slot['end_time']);?>
						</div>
					<? }
				}?>
			</div>
		</div>
		<div class="dashboard-form-row">
			<?=$this->Html->link('Add Slots',array('plugin'=>false,'controller'=>'services','action'=>'add_service_slots',$service['Service']['id']),array('class'=>'dashboard-buttons'));?>
		</div>
	</div>
</div>

<script type="text/javascript">$('.fancybox').fancybox();</script>
