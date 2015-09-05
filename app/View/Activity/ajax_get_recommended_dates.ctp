	<?php if ($recommendedActivities): ?>

	<div class="container" style="position: relative; overflow: visible; box-sizing: border-box; min-height: 1px;">
		
		<div class="theiaStickySidebar" style="padding-top: 0px; padding-bottom: 1px; position: static;"><div class="suggestion-holder row">
			<h2 class="suggestion-title">Check other slots</h2>
			<div id="tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
				<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all" role="tablist">
					<?php
					$n = 1;
					foreach($recommendedActivities as $recommend){
						echo '<li class="ui-state-default ui-corner-top" role="tab" tabindex="-1" aria-controls="fragment-' . $n . '" aria-labelledby="ui-id-' . $n . '" aria-selected="false"><a href="#fragment-' . $n . '" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-1"><span>'.date("M j, Y", strtotime($recommend['date'])).'</span></a></li>';
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
					<div class="col-sm-5"><h3>Time Slot</h3></div>
					<div class="col-sm-3"><h3>Price</h3></div>
					<div class="col-sm-4"><h3>Status</h3></div>
				<!--	<div class="col-sm-2"><h3><input type="submit" form="add_services" class="btn btnDefaults btnFillOrange" value="book now"></h3></div> -->
				</div>
				<div class="record-holder">

				<?php

				foreach($slots as $slot){
				?>
				<div class="row">
					<div class="time col-sm-5"><?php echo $slot->start_time; ?> - <?php echo $slot->end_time; ?></div>
					<div class="price col-sm-3">SGD<?php echo $slot->price; ?></div>
					<div class="status col-sm-4">
						<?php if($slot->status=="available"){?>
						<span class="status av"><i class="fa fa-check-square-o"></i> Available</span></div>
						<?php } else { ?>
						<span class="status notav"><i class="fa fa-times"></i>Not Available</span></div>
						<?php } ?>
				<!--	<div class="book col-sm-2"><button class="book-now btn btnDefaults btnFillOrange"> add </button></div> -->
				</div>
			<?php
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