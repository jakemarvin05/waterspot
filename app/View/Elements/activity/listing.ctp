<?php
$i = $this->paginator->counter('{:start}');
 foreach($search_service_lists as $search_service_list) { ?>
	<div class="activities-listing">
		<?php if(!empty($search_service_list['tag'])){ ?>
			<div class="strip_fullbooked"></div>
		<? } ?>

		<div class="contentvisible contentselector">
			<div class="tile">
				<? $path=WWW_ROOT.'img'.DS.'service_images'.DS;
				$imgArr = array('source_path'=>$path,'img_name'=>$search_service_list['image'],'width'=>293,'height'=>223,'noimg'=>$setting['site']['site_noimage']);
				$resizedImg = $this->ImageResize->ResizeImage($imgArr);
				echo $this->Html->image($resizedImg,array('border'=>'0','alt'=>$search_service_list['Service']['service_title'])) ; ?>
			</div>
			<div class="tile-info">
				<h4><?php echo $this->Format->Headingsubstring($search_service_list['Service']['service_title'],25);?></h4>
				<div class="activity-rating-wrapper">
					<? if(!empty($search_service_list['rating'])){ ?>
						<?php $ratings = range(1,10); ?>
						<?php foreach($ratings as $rating){ ?>
							 
							<input type="radio" value="<?php //echo $rating; ?>" name="test-4-rating-<?php echo $i; ?>" class="star {split:2}" disabled="disabled" <?php echo ($search_service_list['rating']==$rating)?'checked="checked"':'';?>
							/>
						<?php } ?>
					<? }else{ ?>
						<div class="no-rating">No feedback yet</div>
					<? } ?>	
				</div>	
				<div class="price-start"><span>from</span> <br/>$<?= number_format($search_service_list['Service']['service_price'],2)?></div>
			</div>
		</div>
		<div class="contenthover">
			<div class="short-desc"><?php echo $this->Format->Headingsubstring(strip_tags($search_service_list['Service']['description']),250);?></div>
			<a href="/activity/index/<?=$search_service_list['Service']['id']?>" class="view-description">Book A Spot</a>
		</div>
	</div>
				

<? $i++; } 
if(!empty($sort_by_price)){
	if(empty($search_service_lists)){
		echo '<div style="clear:both;"><div class="sun-text no-record"> There are no record found.</div></div>';
	}
}
?>
<script type="text/javascript">
	 var page = <?=$this->paginator->counter('{:page}')?>;
      var pages = <?=$this->paginator->counter('{:pages}')?>;
      if(page >= pages){
		  $("#loader_pagination").attr("disabled", true);
		  $('#loader_pagination').addClass('no-more-activities').html('No more results');
		
		}else{
			$("#loader_pagination").attr("disabled", false);
			$('#loader_pagination').removeClass('no-more-activities').html('Load more results');
		}
</script> 
