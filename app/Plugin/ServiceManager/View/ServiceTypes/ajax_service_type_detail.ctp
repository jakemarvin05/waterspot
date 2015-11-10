<?php
$i = $this->paginator->counter('{:start}');
 foreach($activity_service_list as $service_list) { ?>
	<div class="vendorwise-listing">
		<div class="contentvisible contentselector">
			<div class="tile">
				<? $path=WWW_ROOT.'img'.DS.'service_images'.DS;
						$imgArr = array('source_path'=>$path,'img_name'=>$service_list['image'],'width'=>290,'height'=>220,'noimg'=>$setting['site']['site_noimage']);
						$resizedImg = $this->ImageResize->ResizeImage($imgArr);
						echo $this->Html->image($resizedImg,array('border'=>'0','alt'=>$service_list['Service']['service_title'])); ?>
			</div>
			<div class="tile-info"> 
				<h4><?=$this->Format->Headingsubstring($service_list['Service']['service_title'],24);?></h4>
				<div class="clearfix"></div>
			</div>

			<div class="activity-rating-wrapper">
				<? if(!empty($service_list['rating'])){ ?>
					<?php $ratings = range(1,10); ?>
					<?php foreach($ratings as $rating){ ?>
						<input type="radio" value="<?php echo $rating; ?>" name="test-4-rating-<?php echo $i; ?>" class="star {split:2}" disabled="disabled" <?php echo ($service_list['rating']==$rating)?'checked="checked"':'';?> />
					<?php } ?>
				<? } else{ ?>
				<div class="no-rating">No feedback yet</div>
				<? } ?>	
			</div>	
			<div class="price-start">
				<span>from</span> <br/>$<?= number_format($service_list['Service']['service_price'],2)?>
			</div>

		</div>
		<div class="contenthover">
			<div class="short-desc"> <?=$this->Format->Headingsubstring(strip_tags($service_list['Service']['description']),200);?></div>
			<a href="/activity/index/<?=$service_list['Service']['id']?>" class="view-description">Book A Spot</a>
		</div>
	</div>
<?php $i++; ?>
<? }  
	
if(!empty($sort_by_price)){
	if(empty($service_lists)){
		echo '<div class="sun-text no-record"> There are no record found.</div>';
	}
	
}
?>


<script type="text/javascript">
page = <?=$this->paginator->counter('{:page}')?>;
pages = <?=$this->paginator->counter('{:pages}')?>;
$(function(){ 
	 $('input.star').rating(); 
 });
</script>
