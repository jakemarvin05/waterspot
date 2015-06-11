<h3 class="small">VAS Details</h3>
<table width="100%" cellpadding="0" cellspacing="0" border="0" class="admin-content">
	<tr>
		<th width="10%">S.No.</th>
		<th width="60%">Value Added Service</th>
		<th width="25%">Price ($)</th>
	</tr>
	<? $total_amount=0;
	if(!empty($value_added_services)) {
		foreach($value_added_services as $key=>$value_added_services) { ?> 
			<tr>
				<td class="align-center"><?=($key+1) ?>.</td>
				<td class="align-center"><?=$value_added_services['value_added_name']; ?></td>
				<td class="align-center">$<?=number_format($value_added_services['value_added_price'],2); $total_amount+=$value_added_services['value_added_price'];?></td>
			</tr>
		<? } ?>
		<tr class="subtotal">
			<td colspan="2" class="align-right">Total</td>
			<td class="align-center">$<?=number_format($total_amount,2); ?></td>
		</tr> 
	<? } else { ?>
		<tr>
			<td colspan="3">There are no Value Added Services.</td>
		</tr>
	<? } ?>
</table>
