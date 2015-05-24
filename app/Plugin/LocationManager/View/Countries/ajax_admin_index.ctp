  <?php
		 $i = $this->paginator->counter('{:start}');
			//$i = 0;
			foreach ($countries as $country) {
		?>
			<li id="sort_<?= $country['Country']['id'] ?>"  style="cursor:move" >
				<table width="100%">
					<tr>
						<td width="5%"><?php echo $this->Form->checkbox('Country.id.'.$i, array('value' => $country['Country']['id'])); ?></td>
						<td width="6%"><?php echo $i++; ?></td>
						<td width="65%"><?php echo $country['Country']['name']; ?></td>
						<td width="10%">
						<?php
						if ($country['Country']['status'] == '1')
							echo $this->Html->image('admin/icons/icon_success.png', array());
						else
							echo $this->Html->image('admin/icons/icon_error.png', array());
						?>
						</td>
						<td width="50%">
							<ul class="actions">
								<li><?php echo $this->Html->link('edit', array('controller' => 'countries', 'action' => 'add',$country['Country']['id']), array('escape' => false, 'class' => 'edit', 'title' => 'Edit Country', 'rel' => 'tooltip')); ?></li>
									
								<li><?php echo $this->Html->link('Manage City', array('controller' => 'cities', 'action' => 'index', $country['Country']['id']), array('escape' => false, 'class' => 'subcontent', 'title' => 'Manage City', 'rel' => 'tooltip')); ?>
								</li>	
							</ul >
						</td> 
					</tr>
				</table>
			</li>
	<?php } ?>
