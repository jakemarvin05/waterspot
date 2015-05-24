<div class="wrapper">
	<div class="contant-right-top">
		<div class="container1">
			 <div class="jcarousel-wrapper">
				<div class="jcarousel">
					<ul >
						<? foreach($slide_booking_details as $slide_booking) {?>			
						<li> 
						<? 
							$imgArr = array('source_path'=>Configure::read('Image.SourcePath'),'img_name'=>$slide_booking['ServiceType']['image'],'width'=>294,'height'=>186);
							$resizedImg = $this->ImageResize->ResizeImage($imgArr);
							echo $this->Html->image($resizedImg,array('border'=>'0','class'=>'main'));
							?>
								
							<div class="boxx">
								<? // help for substing eg Headingsubstring(string,Number);?>
								<h1><?=$this->Format->Headingsubstring($slide_booking['ServiceType']['name'],13); ?></h1>
								<div class="txt">
									<?=strip_tags($slide_booking['ServiceType']['short_description']);?>
								</div>
								<div class="more">
									<?=$this->Html->link("Details<span class='arrow'></span>",array('plugin'=>'service_manager','controller'=>'service_types','action'=>'service_type_detail',$slide_booking['ServiceType']['id']),array('escape'=>false));?>
									<img alt="" class="mr5" src="/img/shadow.png">
								</div>
							</div>					
						</li>
						<? } ?>
						 
						
					</ul>
				</div>          	
				 <a href="#" class="jcarousel-control-prev"></a>
				<a href="#" class="jcarousel-control-next"></a>
			</div>   
		 </div>
		 <!--
		<div class="fb-like-button">
			<iframe src="//www.facebook.com/plugins/like.php?href=<?=urlencode($setting['site']['site_url']);?>&amp;width&amp;layout=button_count&amp;action=like&amp;show_faces=true&amp;share=false&amp;height=21&amp;appId=688928254482792" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:21px;" allowTransparency="true"></iframe>
		</div>
		-->
	</div>
</div>
