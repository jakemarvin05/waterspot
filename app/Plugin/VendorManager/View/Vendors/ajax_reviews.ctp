
<?php
$i = $this->paginator->counter('{:start}');?>

<? if(!empty($member_reviews)) {?>
	<? foreach($member_reviews as $key=>$member_review) {?>
		<tr>
			<td width="5%" class="border"><?=($i)?></td>
			<td width="50%" class="border"><?=$member_review['MemberReview']['member_message']?> </td>
			<td width="30%" class="border"><?=ucfirst($member_review['Member']['first_name']." ".$member_review['Member']['last_name'])?></td>
			<td width="15%" class="border">
						<?=date(Configure::read('Calender_format_php'),strtotime($member_review['MemberReview']['date'])); ?> 
			</td>
			<td width="5%" class="border">
				<?=$this->Html->link($this->Html->image('del.png'),array('plugin'=>'vendor_manager','controller'=>'vendors','action'=>'review_delete',$member_review['MemberReview']['id'],'reviews'),array('escape'=>false,"onclick"=>"return confirm('Are you want to delete this review?')")); ?>  
			</td>
			 
		</tr>
	<? } //end of foreach ?>
<? } //end of if ?>
