<script type="text/javascript">
	$(function()
	{
		$("#tabs").tabs();
	});
</script>
	<article class="activity-description">
		<header class="title-header">
			<ul id="tabs" class="tabs">
				<li class="active"><a href="#service-details" target="_self"><h3>Description</h3></a></li>
				<li class="active"><a href="#itinerary-details" target="_self"><h3>Itinerary</h3></a></li>
				
				<li><a href="#how-to-get-review" target="_self"><h3>How to get there</h3></a></li>
				  
				<li><a href="#reviews" target="_self"><h3>Reviews <sup id="reviews-count" class="reviews-count"><?php echo $review_count=(count($service_detail['Review'])>=99)?"99+":count($service_detail['Review'])>0?count($service_detail['Review']):''; ?></sup></h3></a></li>
			</ul>
		</header>
		<div class="content">
			<div class="tabs-content">
				<div id="tab-scrollable" style="max-height: 498px;">
					<div id="service-details" class="tabs-panel">
						<div class="tab-title"></div>
						<a href="#service-details" target="_self" class="tabs-panel-link"></a>
						<?=$service_detail['Service']['description'] ?>
					</div>
					
					<div id="itinerary-details" class="tabs-panel">
						<div class="tab-title"></div>
						<a href="#itinerary-details" target="_self" class="tabs-panel-link"></a>
						<?=$service_detail['Service']['itinerary'] ?>
					</div>
					<div id="how-to-get-review" class="tabs-panel">
						<div class="tab-title"></div>
						<a href="#how-to-get-review" target="_self" class="tabs-panel-link"></a>
						<?=$service_detail['Service']['how_get_review'] ?>
					</div>
					
					<div id="reviews" class="reviews tabs-panel">
						<div class="tab-title"></div>
						<a href="#reviews" target="_self" class="tabs-panel-link"></a>
						<?php  if(!empty($service_detail['Review'])){?>
							<ul>
								<?php $i = 0; ?>
								<?php foreach($service_detail['Review'] as $review){ ?>
									<li class="review-container">
										
										<div class="reviewer-name">
											<p class="r-username"><?php echo ucfirst($review['Member']['first_name'].' '.$review['Member']['last_name']); ?></p>
											<p class="r-time"><?=date(Configure::read('Calender_format_php'),strtotime($review['ServiceReview']['date'])); ?></p>
											
											<div class="r-rating">
												<?php $ratings = range(1,10); ?>
												<?php foreach($ratings as $rating){ ?>
													<input type="radio" value="<?php //echo $rating; ?>" name="test<?php echo $i; ?>" class="star {split:2}" disabled="disabled" <?php echo ($review['ServiceReview']['rating']==$rating)?'checked="checked"':'';?> />
												<?php } ?>
											</div>	
											
											
										</div>
										<div class="reviewer-message">
											<div class="review-message-pointer"></div>
											<p><?php echo ucfirst($review['ServiceReview']['message']); ?></p>
										</div>
										
										
									</li>
									<?php $i++; ?>
								<?php }?>
							</ul>
						<?php } else {?>
							<div class="no-review-found">No reviews yet</div>
						<?php }?>
					</div>
				</div>
			</div>
		</div>
	</article>

