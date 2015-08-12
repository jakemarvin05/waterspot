<? if(!empty($service_detail['image'])) { ?>
	<? if(count($service_detail['image'])>1){?>
		<div class="fotorama" data-nav="thumbs" data-width="730" data-height="468">
		<? }else {?>
			<div data-width="730" data-height="468">
		<? }?>
			
			<?php if (isset($service_detail['Service']['youtube_url'])): ?>
				<?php foreach (unserialize($service_detail['Service']['youtube_url']) as $youtube_url): ?>
					<a href="<?php echo $youtube_url; ?>"></a>
				<?php endforeach; ?>
			<?php endif; ?>
			<? foreach($service_detail['image'] as $key=>$image){ ?>
				<? $imgArr = array('source_path'=>Configure::read('Image.SourcePath'),'img_name'=>$image['image'],'width'=>600,'height'=>400,'noimg'=>$setting['site']['site_noimage']);
				$resizedImg = $this->ImageResize->ResizeImage($imgArr);
				echo $this->Html->image($resizedImg,array('border'=>'0','alt'=>$service_detail['Service']['service_title'])) ; ?>
				
			<? }?>
		</div>
<? } else if ($service_detail['Service']['youtube_url']) { ?>
	<div class="fotorama" data-nav="thumbs" data-width="730" data-height="468">
		<a href="<?php echo $service_detail['Service']['youtube_url']; ?>"></a>
	</div>
<?php } else {?> 
	<div>
		<? $imgArr = array('source_path'=>Configure::read('Image.SourcePath'),'img_name'=>'','width'=>600,'height'=>400,'noimg'=>$setting['site']['site_noimage']);
		$resizedImg = $this->ImageResize->ResizeImage($imgArr);
		echo $this->Html->image($resizedImg,array('border'=>'0','alt'=>$service_detail['Service']['service_title'])) ; ?>
	</div>
<? }?>
