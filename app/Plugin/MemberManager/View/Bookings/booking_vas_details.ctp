<?=$this->Html->css('fancybox-inner.css');?>

<h3>Value Added Services Details</h3>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<th width="15%">S. No.</th>
		<th width="50%">Value Added Service Name</th>
		<th width="30%">Price ($)</th>
	</tr>
	<? $total_amount=0;
	if(!empty($value_added_services)) {
		foreach($value_added_services as $key=>$value_added_services) { ?>
		<tr>
			<td><?=($key+1) ?>.</td>
			<td><?=$value_added_services['value_added_name']; ?></td>
			<td>$<?=number_format($value_added_services['value_added_price'],2); $total_amount+=$value_added_services['value_added_price'];?></td>
		</tr>
	<? } ?>
	<tr>
		<td class="subtotal" colspan="2">Total</td>
		<td class="align-left">$<?=number_format($total_amount,2); ?></td>
	</tr>
	<? } else { ?>
		<tr class="no-details">
			<td colspan="5">There are no VAS</td>
		</tr>
	<? } ?>
</table>