<script type="text/javascript">
		$(document).ready(function(){
			$('.fancybox').fancybox();
		});
</script>
<?php
$i = $this->paginator->counter('{:start}');
	//$i = 0;
	  foreach ($service_reviews as $review) {?>
		<li id="sort_<?= $review['ServiceReview']['id'] ?>">
			<table width="100%">
				<tr>
					<td width="5%"><?php echo $this->Form->checkbox('ServiceReview.id.'.$i, array('value' => $review['ServiceReview']['id'])); ?></td>
					<td width="1%">&nbsp;</td>
					<td width="4%"><?php echo $i++; ?></td>
					<td width="20%"><?php echo $review['Service']['service_title']; ?></td>
					<td width="20%"><?php echo ucfirst($review['Member']['first_name']." ".$review['Member']['last_name']); ?></td>
					<td width="20%"><?php echo ucfirst($review['Vendor']['fname']." ".$review['Vendor']['lname']); ?></td>
					<td width="15%">
						<?=date(Configure::read('Calender_format_php'),strtotime($review['ServiceReview']['date'])); ?>
						 
					</td>
					<td width="5%">
						<?php if($review['ServiceReview']['status']=='1') 
						echo $this->Html->image('admin/icons/icon_success.png', array());
						else
							echo $this->Html->image('admin/icons/icon_error.png', array());
						?>
					</td>
					<td width="15%">
						<ul class="actions">
							<li><?php echo $this->Html->link('edit', array('controller' => 'service_reviews', 'action' => 'add', $review['ServiceReview']['id']), array('escape' => false, 'class' => 'edit', 'title' => 'Edit Review', 'rel' => 'tooltip')); ?></li>
							<li>
							<li>
							<?=$this->Html->link('view', array('controller' => 'service_reviews', 'action' => 'view', $review['ServiceReview']['id']), array('escape' => false,'class'=>'view fancybox','title'=> __('View'),'rel'=>'tooltip'))?>
							
							</li>
														
						</ul >


					</td> 
				</tr>
			</table>
		</li>
	 <?php } ?>

