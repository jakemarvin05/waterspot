
<?php
$i = $this->paginator->counter('{:start}');?>

<? if(!empty($service_reviews)) { 
	foreach($service_reviews as $review){ ?>
		<tr>
			<td class="align-center"><?php echo $i++; ?></td>
			<td><?php echo $review['Service']['service_title']; ?></td>
			<td><?php echo ucfirst($review['Member']['first_name']." ".$review['Member']['last_name']); ?></td>
			<td><?=date(Configure::read('Calender_format_php'),strtotime($review['ServiceReview']['date'])); ?></td>
			<td class="align-center">
				<?php if($review['ServiceReview']['status']=='1') 
					echo $this->Html->image('admin/icons/icon_success.png', array());
					else 
						echo $this->Html->image('admin/icons/icon_error.png', array());
				?>
			</td>
			<td class="align-center"><?=$this->Html->link('View', array('controller' => 'service_reviews', 'action' => 'view', $review['ServiceReview']['id']), array('escape' => false,'class'=>'dashboard-links fancybox fancybox.iframe','title'=> __('View'),'rel'=>'tooltip'))?></td>
		</tr>
	<? } ?>  
<? } //end of if ?>
