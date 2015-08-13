	<section id="splashVideoCont">
		<div id="splashVideoCropper">
			<video autoplay loop muted poster="/img/service_images/<?php echo $service_detail['Service']['panorama_image']; ?>">
				<!-- <source src="/media/watersports.mp4" type="video/mp4"> -->
				<img src="/img/service_images/<?php echo $service_detail['Service']['panorama_image']; ?>">
			</video>
			<img src="/img/service_images/<?php echo $service_detail['Service']['panorama_image']; ?>">
		</div>
		<div id="videoOverlayWrapper">
		</div>
		</div>

	</section>


<div class="wrapper">
	<div class="whitebg"></div>
	<div class="container">
		<section class="left-section col-sm-8">
			<h2 class="activity-title"><?=ucfirst($service_detail['Service']['service_title']);?></h2>
			<div class="slider-holder">
				<?=$this->element('activity/slider');?>
			</div>
			<?=$this->element('activity/serviceDescriptiontabs');?>
			<div id="map-canvas" style="height:400px; width:100%;"></div>
			<script src="https://maps.googleapis.com/maps/api/js"></script>
		    <script>
		      function initialize() {
		        geocoder = new google.maps.Geocoder();
			    var latlng = new google.maps.LatLng(-34.397, 150.644);
			    var mapOptions = {
			      zoom: 15,
			      center: latlng,
                              scrollwheel: false
			    }
			    map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
		      	geocoder.geocode( { 'address': "<?php echo str_replace(' ','+',($service_detail['Service']['location_string']?$service_detail['Service']['location_string']:$service_detail['location_name'])); ?>"}, function(results, status) {
			      if (status == google.maps.GeocoderStatus.OK) {
			        map.setCenter(results[0].geometry.location);
			        var marker = new google.maps.Marker({
			            map: map,
			            position: results[0].geometry.location
			        });
			      } else {
			        alert("Geocode was not successful for the following reason: " + status);
			      }
			    });
		      }
		      google.maps.event.addDomListener(window, 'load', initialize);
		    </script>

		</section>
		<section id="sidebar" class="right-section col-sm-4 col-xs-12">
				<aside class="cart-box">
						<div class="activity-price-info"><span><?=Configure::read('currency');?><?=number_format($service_detail['Service']['service_price'],2);?></span> <span class="unit">PER PAX</span></div>
						<div id="rating" class="blocks">
							<h4>RATING:</h4>
							<div class="rating" style="background:none;">
								<button class="rate" id="rate-1" data-rate="1" style="background:none"><img src="/img/social-feed-logo.jpg"></button>
								<button class="rate" id="rate-2" data-rate="2" style="background:none"><img src="/img/social-feed-logo.jpg"></button>
								<button class="rate" id="rate-3" data-rate="3" style="background:none"><img src="/img/social-feed-logo.jpg"></button>
								<button class="rate" id="rate-4" data-rate="4" style="background:none"><img src="/img/social-feed-logo.jpg"></button>
								<button class="rate" id="rate-5" data-rate="5" style="background:none"><img src="/img/social-feed-logo.jpg"></button>
							</div>
							<script type="text/javascript">
							$(document).ready(function(){
								var rate = <?php echo (isset($service_detail['Rating'])?$service_detail['Rating']:0); ?>;
								var crate = rate+1;
								while(crate <= 5) {
									$('#rate-' + crate).html('<img src="/img/social-feed-logo-bw.jpg">');
									crate++;
								}
							});
							</script>
							<div class="clearfix"></div>
						<?php 
							if($service_detail['Service']['min_participants'] > 0) { 
								$percent = round($booking_count * 100 / $service_detail['Service']['min_participants']) ;
						?>
							<p class="info">Event has a minimum-to-go of <?php echo $service_detail['Service']['min_participants']; ?> pax.</p>
							<div class="completion">
								<div class="progressbar" style="width:<?php echo $percent; ?>%;"></div>
							</div>
							<div class="progressinfo"><span class="current"><?php echo ($booking_count?$booking_count:"0"); ?></span> out of <?php echo $service_detail['Service']['min_participants']; ?></div>
							<div class="clearfix"></div>
						<?php } ?>
						</div>
					<div class="blocks">
					<div class="slot-booking-form">
					<?=$this->Form->create('Activity',array('url' => array('controller' => 'activity', 'action'=>'add_to_card'),'name'=>'add_services','class'=>'quick-contacts5','id'=>'add_services','novalidate' => true));?>
						<div class="select-participant">
							<h4 class="select-participant-txt">1. Select No. of Pax</h4>
							<?
							$no_participants = array();
							foreach (range(1,$service_detail['Service']['no_person']) as $r){
								$no_participants[$r] = $r;
							}
							?>
							<?=$this->Form->input('no_participants',array('type' =>'select', 'options' => $no_participants,'empty' => 'Select','div'=>false,'label'=>false)); ?>
						</div>
						<?php echo $this->element('message');?>

						
						<?=$this->Form->text('service_id',array('type'=>'hidden','value'=>$service_detail['Service']['id'])); ?>
						<br>
						<div class="startDate">
						<div class="start-date">
							<h4>2. Start Date</h4><br /><?=$this->Form->text('start_date',array('type'=>'hidden','class'=>'date-icon','autocomplete'=>'off'));?></div>
						<div id="startdatepicker"></div>
						<br>
						</div>

						<!--
						<div class="endDate">
						<div class="end-date"><h4>3. End Date</h4><br /><?=$this->Form->text('end_date',array('type'=>'hidden','class'=>'date-icon','autocomplete'=>'off'));?></div>
						<div id="enddatepicker"></div>
						</div>-->

						<div class="clear"></div>
						<div class="clear"></div>
						<div id='loader_slots' class="ajax-loder" style="display:none">
							<?php echo $this->Html->image('loader-2.gif', array('alt' => 'loading..'));?>
						</div>
						<div id='slots_form' style="display:none"></div>
						<div class="cart-btn">
							<input type="submit" value="Book Now" class="addtocart-button" id="loginButton" />
						</div>
						<?=$this->Form->end(); ?>
					</div>
					</div>
					<div id="share" class="blocks"><br><br>
						<h4>Share: </h4>
						<div class="socialicons">
							<a id="shareFB" href="https://www.facebook.com/sharer/sharer.php?app_id=381957422009700&sdk=joey&u=<?php echo  (isset($web_url) ? urlencode($web_url) : urlencode('http://128.199.214.85')); ?>&display=popup&ref=plugin&src=share_button" >facebook</a>
						</div>

					<div class="clearfix"></div>
					</div>

				</aside>

			</section>

		<div class="clear spacer"></div>

	</div>
	<div class="container-fluid suggestion" id="recommended_slots">

	</div>
</div>


<div class="clear"></div>

<!-- NEW DESIGN FOR CART MODEL BOX BEGINS -->
<? if(!empty($cart_id)) { ?>
	<?php echo $this->element('activity/cart_booking_invite'); ?>
<? }?>
<?php $path = $this->Html->webroot; ?>
<script type="text/javascript">
<? if(!empty($cart_id)) {  ?>	
		$(document).ready(function(){
			//$('#add_services input#loginButton').attr('disabled',true);
			
			$("#add_invite input[type=checkbox]").click(function(event) {
				updateTotal();
			});
			$("#add_invite input[type=radio]").click(function(event) {
				updateTotal();
			});
		});	

	 
		function updateTotal() {
			var value_added_service=0;
			var no_of_booking_msg='';
			var service_amount =parseFloat(<?=$cart_details['Cart']['price'];?>);
			var total=parseFloat(<?=$cart_details['Cart']['total_amount'];?>);
			// no of booking date or slot
			var no_of_interval=parseInt(<?=$no_of_booking_days;?>);
			if(no_of_interval == 1){
				// overight no_of_interval 
				var no_of_slots=no_of_interval=parseInt(<?=count($cart_details['Cart']['slots']); ?>);
				if(no_of_slots == 1){
					no_of_booking_msg=no_of_slots+ " Slot";
				}else{
					no_of_booking_msg=no_of_slots+ " Slots";
				}
				
			}else{
				no_of_booking_msg=no_of_interval+ " Days";
			}
			
			var invite_p_status=$(".cart-payment-method input:radio:checked").val();
			$("#add_invite input:checkbox:checked").each(function() {
				total += parseFloat(this.value);
				value_added_service+=parseFloat(this.value);
			});
			
			
			if(invite_p_status==1){
				var no_of_participant=$('#CartNoParticipants').val();
				total =total*no_of_participant;
				
			}else{
				var no_of_participant=1;
			}
			
			var value_added_total=(value_added_service*no_of_participant).toFixed(2);
			$('.subtotal').show();
			$('#Vas_detail').html("( $"+value_added_service.toFixed(2) +'x'+ no_of_participant+")");
			$('#Vas_total').html("$" + value_added_total);
			$('#no_of_booking_days').html(no_of_booking_msg);
			$('#Vas_total').html("$" + value_added_total);
			$('#total_amount').html("$" + (no_of_participant*service_amount).toFixed(2));
			$('#total_participate').html(no_of_participant);
			$('#total_participate_amount').html((no_of_participant*service_amount*no_of_interval).toFixed(2));
			$('#sub_total').html("$" + total.toFixed(2));
			 
		}
<? }?>
function get_service_availability()	{
 
	var service_id=$( "#ActivityServiceId" ).val();
	var startdate=$( "#ActivityStartDate" ).val();
	var no_participants=$( "#ActivityNoParticipants" ).val();
				
	if(startdate=='' || service_id=='' || no_participants<=0) {
		return;
	}
 
 
 
 $('#loader_slots').show();

 $.ajax({
		 url :'<?=$path?>activity/ajax_get_availbility_range',
		 type:'POST',
		 data:{'service_id':service_id,'start_date':startdate,'no_participants':no_participants},
		 success: function (result)
		 {
			 
			$('#slots_form').show();
			$('#loader_slots').hide();
			//$('#add_services input#loginButton').attr({'disabled':false});
			$("#slots_form").html(result);
			 

		 }
	}); 
//alert(service_id+startdate+enddate)
}

function get_recommended_dates() {
	var service_id = $("#ActivityServiceId").val();
	var startdate  = $("#ActivityStartDate").val();
	$.ajax({
		 url :'<?=$path?>activity/ajax_get_recommended_dates',
		 type:'POST',
		 data:{'service_id':service_id,'start_date':startdate},
		 success: function (result) {
			$("#recommended_slots").html(result);
		}
	});
}
</script>

<script>
<?php $path = $this->Html->webroot; ?>
    $(document).ready(function(){
		// for price update
		$('#add_services').submit(function(){
		var startdate=$( "#ActivityStartDate" ).val();
		var enddate=$( "#ActivityEndDate" ).val();	
		/*if(enddate==null || enddate==''){ 
			var data = $(this).serializeArray();
			if($('.check-box').length < 1){
				return false;
			}
		}*/
		var data = new FormData(this);
		var formData = $(this);
		var status = 0;
		$.each(this,function(i,v){
			$(v).removeClass('invalid form-error');
		});
               
        $('.error-message').remove();
        $('#add_services > span#for_owner_cms').show();
		//$('input[type="submit"]').attr({'disabled':true});
        
        $.ajax({
			url: '<?=$path?>activity/validation',
			async: false,
			data: data,
			dataType:'json', 
			type:'post',
			cache: false,
			contentType: false,
			processData: false,
			success: function(data) {
					 
				if(data.error==1){
					$.each(data.errors,function(i,v){
						$('#'+i).addClass("invalid form-error").after('<span class="error-message" style="width:200px; margin-left:0;">'+v+'</span>');
						$('#'+i).bind('submit',function(){
							$(this).removeClass('invalid form-error');
							$(this).next().remove();
							});
					});
				}else{
					status = 1;
				}
                   
			}
            });
            if(status==0){
               $("html, body").animate({ scrollTop: 0 }, "slow");
               $('input[type="submit"]').attr({'disabled':false});
               $('#add_services > span#for_owner_cms').hide();
            }
		     
           return (status===1)?true:false; 
            
        });
        
       // invite friends 
    
    $('#add_invite').submit(function(){
		
		//var data = $(this).serializeArray();
		var data = new FormData(this);
		var formData = $(this);
		
		var status = 0;
		$.each(this,function(i,v){
			$(v).removeClass('invalid form-error');
		});
               
        $('.error-message').remove();
        $('#add_invite > span#for_owner_cms').show();
		$('input[type="submit"]').attr({'disabled':true});
        
        $.ajax({
			url: '<?=$path?>carts/validation/cart',
			async: false,
			data: data,
			dataType:'json', 
			type:'post',
			cache: false,
			contentType: false,
			processData: false,
			success: function(data) {
					 
				if(data.error==1){
					$.each(data.errors,function(i,v){
						$('#'+i).addClass("invalid form-error").after('<div class="error-message">'+v+'</div>'); 
						$('#'+i).bind('submit',function(){
							$(this).removeClass('invalid form-error');
							$(this).next().remove();
							});
					});
				}else{
					status = 1;
				}
                   
			}
            });
            if(status==0){
               $("html, body").animate({ scrollTop: 0 }, "slow");
               $('input[type="submit"]').attr({'disabled':false});
               $('#add_invite > span#for_owner_cms').hide();
            }
		   
           return (status===1)?true:false; 
            
        });

		if($(window).width()>768) {
			$('.container, #sidebar').theiaStickySidebar({
				// Settings
				 additionalMarginTop: -60,
				// additionalMarginBottom: 50,
				scrollThrough: ['container']
			});
		}


      
    });
 
 </script>
 
<!-- <script type="text/javascript">
	$(function(){
	    $('#tab-scrollable').slimScroll({
		position: 'right',
		railVisible: true
	    });
	    $('#similar-listing-scrollable').slimScroll({
		position: 'right',
		railVisible: true
	    });
	    $('#cart-email-scrollable').slimScroll({
		position: 'right',
		railVisible: true
	    });
	    $('#cart-vas-scrollable').slimScroll({
		position: 'right',
		railVisible: true
	    });
	});
</script>-->

	<script type="text/javascript">
	$(function()
	{
		$('.scroll-pane').jScrollPane({
			horizontalBar: false,
			verticalBar: true
		});
	});
</script>
	<script language="javascript" type="text/javascript">

		function openInPopUp(url) {
			newwindow=window.open(url,'name','height=500,width=550');
			if (window.focus) {newwindow.focus()}
			return false;
		}

		$('#shareFB').click(function(e){
			e.preventDefault();
			openInPopUp($(this).attr("href"));

		});

	</script>
	<script type="text/javascript">
		$('#ActivityNoParticipants').selectpicker().hide();
	</script>
