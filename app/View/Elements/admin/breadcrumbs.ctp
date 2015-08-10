<?php if(!empty($breadcrumbs)){?>
<ul id="breadcrumbs">
<?php foreach($breadcrumbs as $key => $breadcrumb){ ?>
	<li id="ctl00_ContentPlaceHolder1_Breadcrum1_lifour">
		<?php if(count($breadcrumbs)==($key+1)){ ?>
			<?=$breadcrumb['name']?>
		<?php }else { ?>
			<a href="<?=$breadcrumb['url']?>"><?=$breadcrumb['name']?></a>
		<?php } ?>
		
	</li>
	<!--<li id="ctl00_ContentPlaceHolder1_Breadcrum1_lifour">Settings</li>-->
<?php } ?>
</ul>
<?php } ?>
