<?php
$i = $this->paginator->counter('{:start}');
	foreach ($cities as $city) {
	?>
	<li id="sort_<?= $city['City']['id'] ?>"  style="cursor:move" >
		<table width="100%">
			<tr>
				<td width="5%"><?php echo $this->Form->checkbox('City.id.'.$i, array('value' => $city['City']['id'])); ?></td>
				<td width="5%"><?php echo $i++; ?></td>
				
				
				<td width="40%"><?php echo $city['City']['name']; ?></td>
				
				<td width="20%">
				<?php
				if ($city['City']['status'] == '1')
					echo $this->Html->image('admin/icons/icon_success.png', array());
				else
					echo $this->Html->image('admin/icons/icon_error.png', array());
				?>
				</td>
				<td width="60%">
				<ul class="actions">
					<li><?php echo $this->Html->link('edit', array('controller' => 'cities', 'action' => 'add',$city['City']['country_id'], $city['City']['id']), array('escape' => false, 'class' => 'edit', 'title' => 'Edit City', 'rel' => 'tooltip')); ?></li>
					<li>
					<?=$this->Html->link('view', array('controller' => 'cities', 'action' => 'view', $city['City']['id']), array('escape' => false,'class'=>'view fancybox','title'=> __('View'),'rel'=>'tooltip'))?>
					
					</li>
													
				</ul >
				</td> 
			</tr>
		</table>
	</li>
<?php } ?>
