<div style="background:#f6f6f6; padding:8PX; max-height:550px; overflow-y:auto;" id="view2">
	
	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>Title</b></div>
		<div align="justify;" style="float:left; width:400px;"><?=$srevices['ServiceType']['title']?></div>
		<div style="clear:both;"></div>
	</div>
	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>Name</b></div>
		<div align="justify;" style="float:left; width:400px;"><?=$srevices['ServiceType']['name']?></div>
		<div style="clear:both;"></div>
	</div>
	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>Image</b></div>
		<div align="justify;" style="float:left; width:400px;"><?php 
		/* Resize Image */
			if(isset($srevices['ServiceType']['image'])) {
				$imgArr = array('source_path'=>Configure::read('Image.SourcePath'),'img_name'=>$srevices['ServiceType']['image'],'width'=>110,'height'=>80,'noimg'=>$setting['site']['site_noimage']);
				
				$resizedImg = $this->ImageResize->ResizeImage($imgArr);
				echo $this->Html->image($resizedImg,array('border'=>'0'));
			}
			?>
		</div>
		<div style="clear:both;"></div>
	</div>
	
	
	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>Seo Keyword</b></div>
		<div align="justify;" style="float:left; width:400px;"><?=$srevices['ServiceType']['seo_keyword']?></div>
		<div style="clear:both;"></div>
	</div>
	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>Meta Keyword</b></div>
		<div align="justify;" style="float:left; width:400px;"><?=$srevices['ServiceType']['meta_keyword']?></div>
		<div style="clear:both;"></div>
	</div>
	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>Meta Description</b></div>
		<div align="justify;" style="float:left; width:400px;"><?=$srevices['ServiceType']['meta_description']?></div>
		<div style="clear:both;"></div>
	</div>
	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>Short Description</b></div>
		<div align="justify;" style="float:left; width:400px;"><?=$srevices['ServiceType']['short_description']?></div>
		<div style="clear:both;"></div>
	</div>
	
	
	
	
	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>Description</b></b></div>
		<div align="justify" style="float:left; width:400px;"><?=$srevices['ServiceType']['description']?></div>
		<div style="clear:both;"></div>
	</div>
	
	
