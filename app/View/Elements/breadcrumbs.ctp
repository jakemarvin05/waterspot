<?php if(!empty($breadcrumbs)){?>
<div class="bredcrum">
<?php foreach($breadcrumbs as $key => $breadcrumb){ ?>
	<?php if(count($breadcrumbs)==($key+1)){ ?>
		<?=$breadcrumb['name']?>
		<?php }else { ?>
			<a href="<?=$breadcrumb['url']?>"><?=$breadcrumb['name']?> » </a>
		<? }?>

	<? }?>
 
<? } ?>
</div>
