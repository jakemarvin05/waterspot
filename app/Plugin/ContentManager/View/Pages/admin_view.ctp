<div style="background:#f6f6f6; padding:8PX; max-height:550px; overflow-y:auto;" id="view2">
	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>Page name</b></div>
		<div align="justify;" style="float:left; width:400px;"><?=$page['Page']['name']?></div>
		<div style="clear:both;"></div>
	</div>
	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>Page Title</b></div>
		<div align="justify;" style="float:left; width:400px;"><?=$page['Page']['page_title']?></div>
		<div style="clear:both;"></div>
	</div>
	
	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>SEO Keyword</b></div>
		<div align="justify;" style="float:left; width:400px;"><?=$page['Page']['url_key']?></div>
		<div style="clear:both;"></div>
	</div>
	
	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>Top Menu Active</b></div>
		<div align="justify;" style="float:left; width:400px;">
			<?php if($page['Page']['show_top_menu']=='1') { echo 'Yes'; } else { echo 'No'; }  ?>
		</div>
		<div style="clear:both;"></div>
	</div>
	
	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>Footer Menu Active</b></div>
		<div align="justify;" style="float:left; width:400px;">
			<?php if($page['Page']['show_footer_menu']=='1') { echo 'Yes'; } else { echo 'No'; }  ?>
		</div>
		<div style="clear:both;"></div>
	</div>
	
	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>Meta Keyword</b></div>
		<div align="justify" style="float:left; width:400px;"><?=$page['Page']['page_metakeyword']?></div>
		<div style="clear:both;"></div>
	</div>
	
	<div style="padding:10px 0;"> 
		<div style="float:left; width:110px;"><b>Meta Description</b></div>
		<div align="justify" style="float:left; width:400px;"><?=$page['Page']['page_metadescription']?></div>
		<div style="clear:both;"></div>
	</div>
	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>Short Description</b></div>
		<div align="justify" style="float:left; width:400px;"><?=$page['Page']['page_shortdescription']?></div>
		<div style="clear:both;"></div>
	</div>
 
	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>Long Description</b></div>
		<div align="justify" style="float:left; width:400px;"><?=$page['Page']['page_longdescription']?></div>
		<div style="clear:both;"></div>
	</div>
</div>
