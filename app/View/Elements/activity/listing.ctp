<?php
$i = $this->paginator->counter('{:start}');
 foreach($search_service_lists as $search_service_list) { ?>
	<div class="activities-listing col-sm-4 col-xs-6">
		<?php if(!empty($search_service_list['tag'])){ ?>
			<div class="strip_fullbooked"></div>
		<? } ?>

		<div class="contentvisible contentselector">
			<div class="tile">
				<? $path=WWW_ROOT.'img'.DS.'service_images'.DS;
				$imgArr = array('source_path'=>$path,'alt'=>$search_service_list['Service']['service_title'],'img_name'=>$search_service_list['image'],'width'=>600,'height'=>450,'noimg'=>$setting['site']['site_noimage']);
				$resizedImg = $this->ImageResize->ResizeImage($imgArr);
				echo urldecode($this->Html->image($resizedImg,array('border'=>'0','alt'=>$search_service_list['Service']['service_title'])) ); ?>
				<div class="price">From $<?= number_format($search_service_list['Service']['service_price'],2)?></div>

				<div class="contenthover">
					<div class="box-center">
					<div class="short-desc"><?php echo $this->Format->Headingsubstring(strip_tags($search_service_list['Service']['description']),250);?></div>
					<a href="/activity/details/<?php echo ($search_service_list['slug']?$search_service_list['slug']:$search_service_list['Service']['id']);?>" class="btn btnDefaults btnFillOrange">Book A Spot</a>
				</div>
					</div>
			</div>

			<div class="tile-info">
				<h4><a href="/activity/details/<?php echo ($search_service_list['slug']?$search_service_list['slug']:$search_service_list['Service']['id']);?>"><?php echo $search_service_list['Service']['service_title'];?></a></h4>
				<div class="clearfix"></div>
				<div class="icons">
					<?php
					// Check if attributes is not empty then render
					if(!empty($search_service_list['attributes'])){
						// loop through each attributes
						foreach($search_service_list['attributes'] as $attribute){
							echo '<span class="attr-icon"><i class="'.$attribute['icon_class'].'" aria-hidden="true"></i> <strong>'.$attribute['name'].':</strong> '.$attribute['value'];
							echo '<br>';
						}
					}
					?>
					</div>
			</div>

			<div class="activity-rating-wrapper" style="display:none!important;">
				<? if(!empty($search_service_list['rating'])){ ?>
					<?php $ratings = range(1,10); ?>
					<?php foreach($ratings as $rating){ ?>
						 
						<input type="radio" value="<?php //echo $rating; ?>" name="test-4-rating-<?php echo $i; ?>" class="star {split:2}" disabled="disabled" <?php echo ($search_service_list['rating']==$rating)?'checked="checked"':'';?>
						/>
					<?php }} ?>
    
                    <span class="rating-label">Rating:</span>
				<?php
				//@todo convert Rating into float


				$rating = 0.0; // value is 0.0 to 1.0

				$ratingPerCent = $rating*5;
				$ratingMark = 0;

				if($ratingPerCent>1) {
					$ratingMark = round($ratingPerCent);
					$ratingMark = $ratingMark/5;
				}
				else{
					$ratingMark = 0;
				}

				
				if($ratingMark > 0):

				?>

				<div class="rating" style="background-position: <?php echo -100+($ratingMark*100); ?>px 0px"></div>

				<?php
				else:
				?>
				<span class="rating-label"> No ratings yet</span> 
				<?php
				endif;
				?>

			</div>
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
