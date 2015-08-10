<div style="background:#f6f6f6; padding:8PX; max-height:550px; overflow-y:auto;" id="view2">
	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>Slide Name</b></div>
		<div align="justify;" style="float:left; width:400px;"><?=$slide['Slide']['name']?></div>
		<div style="clear:both;"></div>
	</div>
	
	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>Image</b></div>
		<div align="justify;" style="float:left; width:400px;">
	<?php 
		/* Resize Image */
		if(isset($slide['Slide']['image'])) {
			$imgArr = array('source_path'=>Configure::read('Slide.SourcePath'),'img_name'=>$slide['Slide']['image'],'width'=>80,'height'=>80,'noimg'=>$setting['site']['site_noimage']);
			$resizedImg = $this->ImageResize->ResizeImage($imgArr);
			echo $this->Html->image($resizedImg,array('border'=>'0'));
		}
		?>
	</div>
	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>Text 1</b></div>
		<div align="justify;" style="float:left; width:400px;"><?=$slide['Slide']['text1']?></div>
		<div style="clear:both;"></div>
	</div>
	<div style="padding:10px 0;">
		<div style="float:left; width:110px;"><b>Text 2</b></div>
		<div align="justify;" style="float:left; width:400px;"><?=$slide['Slide']['text2']?></div>
		<div style="clear:both;"></div>
	</div>	
	
	
	</div>
