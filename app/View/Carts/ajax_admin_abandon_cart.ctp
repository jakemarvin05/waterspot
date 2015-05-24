<?php
$i = $this->paginator->counter('{:start}');
if(!empty($abandon_carts)) {
	foreach ($abandon_carts as $abandon_cart) {?>
		<li>
			<table width="100%">
				<tr>
					<td width="5%"><?php echo $i++; ?></td>
					<td width="20%"><a href="mailto:<?=$abandon_cart['Cart']['guest_email']?>"><?=$abandon_cart['Cart']['guest_email']?> </a></td>
					<td width="20%"><?= ucfirst($abandon_cart['Cart']['vendor_name'])?></td>
					<td width="20%"><?=$abandon_cart['Cart']['service_title']?></td>
					<td width="15%"><?=date(Configure::read('Calender_format_php'),strtotime($abandon_cart['Cart']['time_stamp'])); ?>
					</td>
				</tr>
			</table>
		</li>
	<?php }
 } ?>
