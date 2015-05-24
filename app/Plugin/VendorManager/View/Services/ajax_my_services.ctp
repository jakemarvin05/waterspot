
<?php
$i = $this->paginator->counter('{:start}');
foreach ($service_lists as $service) {
    ?>
    
	<tr>
						
		<td width="5%" class="border" valign='middle'><?php echo $i++; ?></td>
		<td width="30%" class="border" valign='middle'><?=ucfirst($service['service_title']); ?></td>
		<td width="15%" class="border" style="text-align: center;">
			 <? $imgArr = array('source_path'=>Configure::read('Image.SourcePath'),'img_name'=>$service['image'],'width'=>80,'height'=>80,'noimg'=>$setting['site']['site_noimage']);
				$resizedImg = $this->ImageResize->ResizeImage($imgArr);
				echo $this->Html->image($resizedImg,array('border'=>'0','alt'=>$service['service_title'])) ; ?> </td>
		<td width="15%" class="border" valign='middle'><?=ucfirst($service['location_details']); ?></td>
		<td width="9%" class="border" valign='middle'><?= number_format($service['service_price'],2) ?></td>
		<td width="30%" class="border action" valign='middle'>
			<?=$this->Html->link($this->Html->image('add.png',array('alt'=>'edit')),array('plugin'=>false,'controller'=>'services','action'=>'add_services',$service['id']),array('escape' => false,'class'=>'tooltip','title'=>'Edit Service'));?>
			<?=$this->Html->link($this->Html->image('view.png',array('alt'=>'Add/Update Slot')),array('plugin'=>false,'controller'=>'services','action'=>'add_slots',$service['id']),array('escape' => false,'class'=>'tooltip','title'=>'View Service'));?>
			
			<?=$this->Html->link($this->Html->image('slots.gif',array('alt'=>'Add/Update Slot')),array('plugin'=>false,'controller'=>'services','action'=>'add_service_slots',$service['id']),array('escape' => false,'class'=>'tooltip','title'=>'Add/Update Slots'));?>
			
			<?=$this->Html->link($this->Html->image('add-avail.png',array('alt'=>'Add/Update Availablity')),array('plugin'=>'vendor_manager','controller'=>'vendor_service_availabilities','action'=>'index',$service['id']),array('escape' => false,'class'=>'tooltip','title'=>'Manage Slot Availability'));?>
			
			<?=$this->Html->link($this->Html->image('service_review-icon.png',array('alt'=>'View Review')),array('plugin'=>'vendor_manager','controller'=>'service_reviews','action'=>'reviews',$service['id']),array('escape' => false,'class'=>'tooltip','title'=>'View Review'));?>
		</td>
	</tr>	

<?php } ?>
