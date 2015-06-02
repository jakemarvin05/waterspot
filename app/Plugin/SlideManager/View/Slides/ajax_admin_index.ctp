<?php
			 $i = $this->paginator->counter('{:start}');
				//$i = 0;
				foreach ($slides as $slide) {
			?>
				<li id="sort_<?= $slide['Slide']['id'] ?>"  style="cursor:move" >
					<table width="100%">
						<tr>
							<td width="5%"><?php echo $this->Form->checkbox('Slide.id.'.$i, array('value' => $slide['Slide']['id'])); ?></td>
							<td width="6%"><?php echo $i++; ?></td>
							<td width="10%">
							 <?php 
							/* Resize Image */
							if(isset($slide['Slide']['image'])) {
								$imgArr = array('source_path'=>'slide','img_name'=>$slide['Slide']['image'],'width'=>Configure::read('AdminConfig.image_list_width'),'height'=>Configure::read('AdminConfig.image_list_height'),'noimg'=>$setting['site']['site_noimage']);
								$resizedImg = $this->ImageResize->ResizeImage($imgArr);
								echo $this->Html->image($resizedImg,array('border'=>'0'));
							}
							?>
						
						
							</td>
							
							<td width="60%"><?php echo $slide['Slide']['name']; ?></td>
							<td width="10%">
							<?php
							if ($slide['Slide']['status'] == '1')
								echo $this->Html->image('admin/icons/icon_success.png', array());
							else
								echo $this->Html->image('admin/icons/icon_error.png', array());
							?>
							</td>
							<td width="45%">
								<ul class="actions">
									<li><?php echo $this->Html->link('edit', array('controller' => 'slides', 'action' => 'add', $slide['Slide']['id']), array('escape' => false, 'class' => 'edit', 'title' => 'Edit Slide', 'rel' => 'tooltip')); ?></li>
									<li>
									<?=$this->Html->link('view', array('controller' => 'slides', 'action' => 'view', $slide['Slide']['id']), array('escape' => false,'class'=>'view fancybox','title'=> __('View'),'rel'=>'tooltip'))?>
									
									</li>
																	
								</ul >


							</td> 
						</tr>
					</table>
				</li>
				<?php } ?>
