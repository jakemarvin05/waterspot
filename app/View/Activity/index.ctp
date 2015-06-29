	<section id="splashVideoCont">
		<div id="splashVideoCropper">
			<video autoplay loop muted poster="/img/splash-statics/slide1.jpg">
				<source src="/media/watersports.mp4" type="video/mp4">
				<img src="/img/splash-statics/slide1.jpg">
			</video>
			<img src="/img/splash-statics/slide1.jpg">
		</div>
		<div id="videoOverlayWrapper">
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
			<div class="map-holder row">
				<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d127641.73203943127!2d103.85765580502138!3d1.291905694200164!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31da11238a8b9375%3A0x887869cf52abf5c4!2sSingapore!5e0!3m2!1sen!2sph!4v1434699748138" width="100%" height="530" frameborder="0" style="border:0"></iframe>
			</div>
		</section>
		<section id="stickysidebar" class="right-section col-sm-4 col-xs-12">
				<aside class="cart-box">
						<div class="activity-price-info"><span><?=Configure::read('currency');?><?=number_format($service_detail['Service']['service_price'],2);?></span> <span class="unit">PER PAX</span></div>
						<div id="rating" class="blocks">
							<h4>RATING:</h4>
							<div class="rating"></div>
							<div class="clearfix"></div>
							<p class="info">Event has a minimum-to-go of 30 pax.</p>
							<div class="completion">
								<div class="progressbar" style="width:40%;"></div>
							</div>
							<div class="progressinfo"><span class="current">12</span> out of 30</div>
							<div class="clearfix"></div>
						</div>
					<div class="blocks">
					<div class="slot-booking-form">
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

						<?=$this->Form->create('Activity',array('url' => array('controller' => 'activity', 'action'=>'add_to_card'),'class'=>'quick-contacts5','id'=>'add_services','novalidate' => true));?>
						<?=$this->Form->text('service_id',array('type'=>'hidden','value'=>$service_detail['Service']['id'])); ?>
						<br>
						<div class="startDate">
						<div class="start-date">
							<h4>2. Start Date</h4><br /><?=$this->Form->text('start_date',array('type'=>'hidden','class'=>'date-icon','autocomplete'=>'off'));?></div>
						<div id="startdatepicker"></div>
						<br>
						</div>

						
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
					<div class="fb-share">
						<div class="fb-share-button" data-href="<?php echo $web_url ? $web_url : 'http://128.199.214.85'; ?>" data-layout="box_count"></div>
					</div>
				</aside>

			</section>

		<div class="clear spacer"></div>

	</div>

	<div class="clear"></div>
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
	var enddate=$( "#ActivityEndDate" ).val();
	var no_participants=$( "#ActivityNoParticipants" ).val();
				
	if(startdate=='' || service_id=='' || no_participants<=0) {
		return;
	}
 
 
 
 $('#loader_slots').show();

 $.ajax({
		 url :'<?=$path?>activity/ajax_get_availbility_range',
		 type:'POST',
		 data:{'service_id':service_id,'start_date':startdate,'end_date':enddate,'no_participants':no_participants},
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
			var rightOffset = $(window).width() - ($('#stickysidebar').offset().left + $('#stickysidebar').width());
			var sidebarWidth = $('#stickysidebar').width();
			rightOffset = rightOffset - (rightOffset * .18);
			if ($(window).width() < 800) {
				rightOffset = rightOffset - (rightOffset * .205)
			}


			sidebarWidth = sidebarWidth + (sidebarWidth * .107);

			$(window).scroll(function () {

				if ($('#stickysidebar').parent('#stickysidebar-sticky-wrapper').hasClass('is-sticky')) {
					$('#stickysidebar').css({width: sidebarWidth + 'px', right: rightOffset + 'px', 'z-index': 99});
				}
				else {
					$('#stickysidebar').css({width: sidebarWidth + 'px', right: 0 + 'px'});
				}

			});
			if ($(window).width() < 800) {
				$("#stickysidebar").sticky({topSpacing: -170, bottomSpacing: 600});
			}
			else {
				$("#stickysidebar").sticky({topSpacing: -170, bottomSpacing: 500});
			}
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
