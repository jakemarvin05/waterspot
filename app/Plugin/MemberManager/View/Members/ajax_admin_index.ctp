    <?php
                 $i = $this->paginator->counter('{:start}');
                    //$i = 0;
                    foreach ($members as $member) {
                ?>
				<li id="sort_<?=$member['Member']['id'];?>"  style="cursor:move" >
					<table width="100%">
						<tr>
							<td width="5%"><?php echo $this->Form->checkbox('Member.id.'.$i, array('value' => $member['Member']['id'])); ?></td>
							<td width="5%"><?php echo $i++; ?></td>
							<td width="12%"><?=$member['Member']['first_name'];?></td>
							<td width="12%"><?=$member['Member']['last_name'];?></td>
							<td width="21%"><?=$member['Member']['email_id'];?></td>
							<td width="20%"><?=$member['Member']['phone'];?></td>
							<td width="10%">
							<?php if($member['Member']['active']=='1') 
									echo $this->Html->image('admin/icons/icon_success.png', array());
                                else
									echo $this->Html->image('admin/icons/icon_error.png', array());
							?>
							</td>
							<td width="15%">
								<ul class="actions">
									<li><?php echo $this->Html->link('edit', array('controller' => 'members', 'action' => 'add', $member['Member']['id']), array('escape' => false, 'class' => 'edit', 'title' => 'Edit Member', 'rel' => 'tooltip')); ?></li>
									<li>
									<?=$this->Html->link('view', array('controller' => 'members', 'action' => 'view', $member['Member']['id']), array('escape' => false,'class'=>'view fancybox','title'=> __('View'),'rel'=>'tooltip'))?>
									
									</li>
								</ul >
							</td> 
						</tr>
					</table>
				</li>
				<?php } ?>
