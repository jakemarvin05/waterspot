	<?php if ($recommendedActivities): ?>

	<div class="container" style="position: relative; overflow: visible; box-sizing: border-box; min-height: 1px;">
		
		<div class="theiaStickySidebar" style="padding-top: 0px; padding-bottom: 1px; position: static;"><div class="suggestion-holder row">
			<h2 class="suggestion-title">Check other slots</h2>
			<div id="tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
				<div id="mobileTabsContainer">

					<div class="mobileArrows" id="mobileArrowLeft">
						<div class="mobileArrowsInner"><span class="glyphicon glyphicon-triangle-left"></span></div>
					</div>
					<div id="mobileTabs">
							<div id="mobileDateStrip">
							<?php
							$n = 1;
							foreach($recommendedActivities as $recommend){
								echo '<div class="mobileTabsOuter" aria-date-index="'.$n.'"><div class="mobileTabsInner">'.date("M j", strtotime($recommend['date'])).'</div></div>';
								$n++;
							}
							?>
							</div>
						<div class="clearfix"></div>
					</div>
					<div class="mobileArrows" id="mobileArrowRight">
						<div class="mobileArrowsInner"><span class="glyphicon glyphicon-triangle-right"></span></div>
					</div>

					<div class="clearfix"></div>
				</div>
				<!-- script to control the mobile tabs behaviours -->
				<script>
				var CURRENT_CALENDAR_INDEX = 3;
				$(document).ready(function() {

					//bind the UI tabs to the mobile date strip;
					var arrayOfTabs = $('.ui-tabs-anchor');
					var mobileDateStrip = $('#mobileDateStrip');
					arrayOfTabs.each(function(i, el) {
						$(el).on('click', function() {
							var index = $(this).attr('aria-index')-1;
							var newMarginLeft = -(index)*200 + 'px';
							mobileDateStrip.css('margin-left', newMarginLeft);
							CURRENT_CALENDAR_INDEX = index;
						});
					});

					$('#mobileArrowLeft').on('click', function() {

						// check if tab exist
						if (arrayOfTabs[CURRENT_CALENDAR_INDEX - 1]) {
			
							// check if there is tab preceding this tab to be shown
							// if not, grey out the button
							if (!arrayOfTabs[CURRENT_CALENDAR_INDEX - 2]) {
								$(this).css('color', '#ccc');
							}

							// check if there is tab succeeding this tab
							// if yes, attempt to recover the active color.
							if (arrayOfTabs[CURRENT_CALENDAR_INDEX]) {
								$('#mobileArrowRight').css('color', '#606060');
							}

							// click the tab.
							arrayOfTabs[CURRENT_CALENDAR_INDEX - 1].click();

						} else {
							$(this).css('color', '#ccc');
						}
					});

					$('#mobileArrowRight').on('click', function() {
						if (arrayOfTabs[CURRENT_CALENDAR_INDEX + 1]) {

							if (!arrayOfTabs[CURRENT_CALENDAR_INDEX + 2]) {
								$(this).css('color', '#ccc');
							}
							if (arrayOfTabs[CURRENT_CALENDAR_INDEX]) {
								$('#mobileArrowLeft').css('color', '#606060');
							} 
							
							arrayOfTabs[CURRENT_CALENDAR_INDEX + 1].click();

						} else {
							$(this).css('color', '#ccc');
						}
					});



					// $('#mobileTabs>div').each(function(i, el) {
					// 	$(el).on('click', function(e) {
					// 		var index = $(this).attr('aria-date-index');
					// 		console.log($('a.ui-tabs-anchor[href="#fragment-' + index + '"]'));
					// 		$('a.ui-tabs-anchor[href="#fragment-' + index + '"]').click();
					// 	});

					// });
				});
				</script>

				<ul id="mobileRecommendDates" class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all" role="tablist">
					<?php
					$n = 1;
					foreach($recommendedActivities as $recommend){
						echo '<li style="padding:0;" class="ui-state-default ui-corner-top" role="tab" tabindex="-1" aria-controls="fragment-' . $n . '" aria-labelledby="ui-id-' . $n . '" aria-selected="false"><a href="#fragment-' . $n . '" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-1" aria-index='.$n.'><span>'.date("M j", strtotime($recommend['date'])).'</span></a></li>';
						$n++;
					}
					?>
				</ul>

				<?php
				$n = 1;
				foreach($recommendedActivities as $recommend){
					$slots = $recommend['slots'];
				?>
				<div id="fragment-<?php echo $n; ?>" aria-labelledby="ui-id-<?php echo $n; ?>" class="ui-tabs-panel ui-widget-content ui-corner-bottom" role="tabpanel" aria-expanded="false" aria-hidden="true" style="display: none;">
				<div class="row">
					<div class="col-xs-7"><h3>Time Slot</h3></div>
					<div class="col-xs-5"><h3>Price</h3></div>
				<!--	<div class="col-sm-2"><h3><input type="submit" form="add_services" class="btn btnDefaults btnFillOrange" value="book now"></h3></div> -->
				</div>
				<div class="record-holder">

				<?php
				if (count($slots) > 0) {
					foreach($slots as $slot){
				?>
				<div class="row">
					<div class="time col-xs-7"><?php echo date('h:ia', strtotime($slot->start_time)); ?> - <?php echo date('h:ia', strtotime($slot->end_time)); ?></div>
					<div class="price col-xs-5">SGD<?php echo $slot->price; ?></div>
				<!--	<div class="book col-sm-2"><button class="book-now btn btnDefaults btnFillOrange"> add </button></div> -->
				</div>
				<?php
					}
				} else {
					echo '<div class="row"><div class="col-xs-12"><strong>No Slots Available</strong></div></div>';
				}
				echo "</div></div>";
				$n++;
				}


				?>
		</div>
		<br><br>
	</div>


	<script>
		$("#tabs").tabs({
			active: <?=$currentDateIndex; ?>
		});
	</script>

	<?php else: ?>
		<h2 class="suggestion-title">Sorry, We do not have recommended dates</h2>
	<?php endif; ?>

				<script type="text/javascript">
						$( "button.book-now" ).click(function(e) {
							if($(this).text()=="Added") {
								$(this).text("Add");
								$(this).css("background-color", "#FDA018");
							}
							else{
								$(this).text("Added");
								$(this).css("background-color", "#1DBE12");

							}
						});

				</script>