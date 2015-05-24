    <?php
                 $i = $this->paginator->counter('{:start}');
                    //$i = 0;
                    foreach ($vendors as $vendor) {
                ?>
				<li id="sort_<?=$vendor['Vendor']['id'];?>"  style="cursor:move" >
					<table width="100%">
						<tr>
							<td width="5%"><?php echo $this->Form->checkbox('Vendor.id.'.$i, array('value' => $vendor['Vendor']['id'])); ?></td>
							<td width="5%"><?php echo $i++; ?></td>
							<td width="12%"><?=$vendor['Vendor']['bname'];?></td>
							<td width="12%"><?=$vendor['Vendor']['fname'];?></td>
							<td width="12%"><?=$vendor['Vendor']['lname'];?></td>
							<td width="21%"><?=$vendor['Vendor']['email'];?></td>
							<td width="10%"><?=$vendor['Vendor']['phone'];?></td>
							<td width="10%">
							<?php if($vendor['Vendor']['active']=='1') 
									echo $this->Html->image('admin/icons/icon_success.png', array());
                                else
									echo $this->Html->image('admin/icons/icon_error.png', array());
							?>
							<?php if($vendor['Vendor']['approval']!='1'){ ?>
							<br>
								<?php echo $this->Html->link('Not Approved', array('controller'=>'vendors', 'action' => 'approval',$vendor['Vendor']['id']), array('escape' => false,'class'=>'button-link','title'=>'Click to Approve','rel'=>'modal'));?>
							<?php } ?>
							</td>
							<td width="15%">
								<ul class="actions">
									<li><?php echo $this->Html->link('edit', array('controller' => 'vendors', 'action' => 'add', $vendor['Vendor']['id']), array('escape' => false, 'class' => 'edit', 'title' => 'Edit Vendor', 'rel' => 'tooltip')); ?></li>
									<li>
									<?=$this->Html->link('view', array('controller' => 'vendors', 'action' => 'view', $vendor['Vendor']['id']), array('escape' => false,'class'=>'view fancybox','title'=> __('View'),'rel'=>'tooltip'))?>
									
									</li>
									<li>
									<?=$this->Html->link('Service List', array('controller' => 'services', 'action' => 'servicelist', $vendor['Vendor']['id']), array('escape' => false,'class'=>'view-services','title'=> __('View Service List'),'rel'=>'tooltip'))?>
									
									</li>
								</ul >
							</td> 
						</tr>
					</table>
				</li>
				<?php } ?>
