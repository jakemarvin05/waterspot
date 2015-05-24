<?php
 $i = $this->paginator->counter('{:start}');
	//$i = 0;
	foreach ($vendor_payments as $payment) {
?>
<li id="sort_<?=$payment['Vendor']['id'];?>"  style="cursor:move" >
	<table width="100%">
		<tr>
			<td width="5%"><?php echo $i++; ?></td>
			<td width="12%"><?=$payment['Vendor']['bname'];?></td>
			<td width="12%"><?=$payment['Vendor']['fname'];?></td>
			<td width="12%"><?=$payment['Vendor']['lname'];?></td>
			<td width="16%"><?=$payment['Vendor']['email'];?></td>
			<td width="10%">$<?=$payment['Payment']['payment_amount'];?></td>
			<td width="20%">
			<?php if($payment['Payment']['status']=='0') {
					echo '<b class=button-link>Not Completed</b>';
				}elseif($payment['Payment']['status']=='1'){
					echo '<span class="tag green">Completed</span>';
				}else{
					echo '<b class=button-link>Pending</b>';
				}
			?>
			</td>
			<td width="15%">
				<ul class="actions">
					<li><?=$this->Html->link('View', array('controller' => 'vendors', 'action' => 'paymentstatus', $payment['Vendor']['id']), array('escape' => false,'class'=>'view fancybox','title'=> __('View Payment Status'),'rel'=>'tooltip'))?></li>
				</ul >
			</td> 
		</tr>
	</table>
</li>
<?php } ?>
