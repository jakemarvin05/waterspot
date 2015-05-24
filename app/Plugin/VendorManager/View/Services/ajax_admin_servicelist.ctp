 <?php
					$i = $this->paginator->counter('{:start}');
                    //$i = 0;
                    foreach ($vendor_services as $vendor_service) {
                ?>
				<li id="sort_<?=$vendor_service['Service']['id'];?>"  style="cursor:move" >
					<table width="100%">
						<tr>
							<td width="5%"><?php echo $this->Form->checkbox('Service.id.'.$i, array('value' => $vendor_service['Service']['id'])); ?></td>
							<td width="5%"><?php echo $i++; ?></td>
							<td width="25%"><?=$vendor_service['Service']['service_title'];?></td>
							<td width="25%"><?=$vendor_service['ServiceType']['name'];?></td>
							<td width="11%"><?=$vendor_service['Service']['service_price'];?></td>
							<td width="10%">
							<?php if($vendor_service['Service']['status']=='1') 
									echo $this->Html->image('admin/icons/icon_success.png', array());
                                else
									echo $this->Html->image('admin/icons/icon_error.png', array());
							?>
						
							</td>
							<td width="20%">
								<ul class="actions">
									 <li><?php echo $this->Html->link('edit', array('controller' => 'services', 'action' => 'add_services',$vendor_service['Service']['vendor_id'],$vendor_service['Service']['id']), array('escape' => false, 'class' => 'edit', 'title' => 'Edit Service', 'rel' => 'tooltip')); ?></li>

									<li><?=$this->Html->link('view', array('controller' => 'services', 'action' => 'view_service',$vendor_service['Service']['id']), array('escape' => false,'class'=>'view fancybox','title'=> __('View'),'rel'=>'tooltip'))?></li>
									<li><?php echo $this->Html->link('Add Slot', array('controller' => 'services', 'action' => 'add_service_slots',$vendor_service['Service']['vendor_id'],$vendor_service['Service']['id'] ), array('escape' => false, 'class' => 'add-slot', 'title' => 'Edit Service Slot', 'rel' => 'tooltip')); ?></li>
									<li><?php echo $this->Html->link('Service Availability', array('controller' => 'vendor_service_availabilities', 'action' => 'index',$vendor_service['Service']['vendor_id'],$vendor_service['Service']['id']), array('escape' => false, 'class' => 'add-avail', 'title' => 'Edit Service Availability', 'rel' => 'tooltip')); ?></li>
									
									<li><?php echo $this->Html->link('Service Review', array('controller' => 'service_reviews', 'action' => 'index',$vendor_service['Service']['vendor_id'],$vendor_service['Service']['id']), array('escape' => false, 'class' => 'add-review', 'title' => 'Edit Service Review', 'rel' => 'tooltip')); ?></li>

								</ul >
							</td>					
						</tr>
					</table>
				</li>
				<?php } ?>
 
