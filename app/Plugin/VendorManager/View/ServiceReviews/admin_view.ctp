<?=$this->Html->css('fancybox-inner.css');?>

<table id="view2" class="fancybox-popup-table" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<th>Service Name</th>
		<td><?=$service_review['Service']['service_title']?></td>
	</tr>
	<tr>
		<th>Member Name</th>
		<td><?php echo ucfirst($service_review['Member']['first_name']." ".$service_review['Member']['last_name']); ?></td>
	</tr>
	<tr>
		<th>Vendor Name</th>
		<td><?php echo ucfirst($service_review['Vendor']['fname']." ".$service_review['Vendor']['lname']); ?></td>
	</tr>
	<tr>
		<th>Message</th>
		<td><?php echo $service_review['ServiceReview']['message']; ?></td>
	</tr>
	<tr>
		<th>Status</th>
		<td><?php echo ($service_review['ServiceReview']['status']=='1')?'Active':'Deactive'; ?></td>
	</tr>
	<tr>
		<th>Date</th>
		<td><?=date(Configure::read('Calender_format_php'),strtotime($service_review['ServiceReview']['date'])); ?></td>
	</tr>
</table>