<article class="activity-description">
<h3>Description</h3>
<?=$service_detail['Service']['description'] ?>
<h3>Itenerary</h3>
<?=$service_detail['Service']['itinerary'] ?>
<h3>How to get There</h3>
<?=$service_detail['Service']['how_get_review'] ?>
<h3>Review</h3>
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
	</article>