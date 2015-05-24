<div class="flexslider">
	<ul class="slides">
	<?php foreach($slides as $slide){ ?>
		<li><?
			$imgArr = array('source_path'=>Configure::read('Slide.SourcePath'),'img_name'=>$slide['Slide']['image'],'width'=>1456,'height'=>600);
			$resizedImg = $this->ImageResize->ResizeImage($imgArr);
			?>
			<?php echo $this->Html->image($resizedImg,array('class'=>'image','alt'=>''));?>
				<div class="wrapper">
					<div class="black">
						<? if(!empty($slide['Slide']['text1'])){ ?>
							<h3 class="banner-box"><?=$slide['Slide']['text1']?></h3>
							<div class="clear"></div>
						<? } ?>	
						
						<? if(!empty($slide['Slide']['text2'])){ ?>
							<h2 class="banners-box"><?=$slide['Slide']['text2']?></h2>
						<? } ?>
					</div>
				</div>
		</li>
	<?php } ?>
	</ul>
</div>
