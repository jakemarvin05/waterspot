<div class="wrapper">
	<div class="hr-line"></div>
	<div class="clear"></div>
	<?=$this->element('breadcrumbs');?>
	<div style="float: left; width: 100%;">
		<h2 class="page-title" style="float: left;">Activity <span style="color:#000;">
		<strong>Details</strong></span></h2>
		
		<div style="float: right;" class="social-share-box">
			<span class='st_facebook_large' displayText='Facebook'></span>
			<span class='st_twitter_large' displayText='Tweet'></span>
			<span class='st_linkedin_large' displayText='LinkedIn'></span>
			<span class='st_pinterest_large' displayText='Pinterest'></span>
			<span class='st_email_large' displayText='Email'></span>
		</div>
	</div>
		
		<? // if javascript disable then show these invite form  ?>
		
		<div class="activity-details">
			<section class="activity-left">
				<article class="gallery-box">
					<header class="title-header">
						<h2><?=ucfirst($service_detail['Service']['service_title']);?></h2>
						<h6><?=$service_detail['location_name'];?></h6>
					</header>
					<?=$this->element('activity/slider');?>
					 
				</article>
				<?=$this->element('activity/serviceDescriptiontabs');?>
			</section>

			<section class="activity-right">
			<aside class="cart-box">
				<header class="title-header">
					<div class="activity-price-info">Price<br /><span><?=Configure::read('currency');?><?=number_format($service_detail['Service']['service_price'],2);?></span></div>
					<!--
					<div class="per-slot-price">Per Slot Price<br /><span><?=Configure::read('currency');?><?=number_format($service_detail['Service']['service_price'],2);?></span></div>
                                        <div class="per-day-price">Per Day Price<br /><span><?=Configure::read('currency');?><?=number_format($service_detail['Service']['full_day_amount'],2);?></span></div>
					<div class="activity-price-note">The above prices are per person</div>
                                        -->
				</header>
				<div class="slot-booking-form">
					<?php echo $this->element('message');?>
					<?=$this->Form->create('Activity',array('url' => array('controller' => 'activity', 'action'=>'add_to_card'),'class'=>'quick-contacts5','id'=>'add_services','novalidate' => true));?>
					<?=$this->Form->text('service_id',array('type'=>'hidden','value'=>$service_detail['Service']['id'])); ?>
					<div class="start-date">Start Date<br /><?=$this->Form->text('start_date',array('class'=>'date-icon','autocomplete'=>'off'));?></div>
					<div class="end-date">End Date<br /><?=$this->Form->text('end_date',array('class'=>'date-icon','autocomplete'=>'off'));?></div>
					<div class="clear"></div>
					<div class="select-participant">
						<span class="select-participant-txt">Select No. of Participant(s)</span>
						<?
							$no_participants = array();
							foreach (range(1,$service_detail['Service']['no_person']) as $r){
								$no_participants[$r] = $r;
							}
						?>
						<?=$this->Form->input('no_participants',array('type' =>'select', 'options' => $no_participants,'empty' => 'Select','div'=>false,'label'=>false)); ?>
					</div>
					<div class="clear"></div> 
					<div id='loader_slots' class="ajax-loder" style="display:none">
						<?php echo $this->Html->image('loader-2.gif', array('alt' => 'loading..'));?>
					</div>
					<div id='slots_form' style="display:none"></div>
					<div class="cart-btn">
						<input type="submit" value="Add To Cart" class="addtocart-button" id="loginButton" />
					</div>
					<?=$this->Form->end(); ?>
				</div>
			</aside>
			<aside class="vendor-quick-info">
				<header class="title-header">
					<?php 
					/* Resize Image */
						if(isset($vendor_details['Vendor']['image'])) {
							$imgArr = array('source_path'=>Configure::read('VendorProfile.SourcePath'),'img_name'=>$vendor_details['Vendor']['image'],'width'=>325,'height'=>247,'noimg'=>$setting['site']['site_noimage']);
							$resizedImg = $this->ImageResize->ResizeImage($imgArr);
							echo $this->Html->image($resizedImg,array('border'=>'0','alt'=>(!empty($vendor_details['Vendor']['bname'])?$vendor_details['Vendor']['bname']:$vendor_details['Vendor']['fname']." ".$vendor_details['Vendor']['lname'])));
						}
					?>
				</header>
				<div class="content">
					<h3>
						<?=ucfirst(!empty($vendor_details['Vendor']['bname'])?$vendor_details['Vendor']['bname']:$vendor_details['Vendor']['fname']." ".$vendor_details['Vendor']['lname']); ?>
					</h3>
					<div class="rating-info">
						<span class="rating-txt">Ratings:</span>
						<div class="rating-stars">
						<? if(!empty($vendor_details['Vendor']['rating'])){ ?>
							<?php $ratings = range(1,10); ?>
								<?php $vratings = range(1,10); ?>
								<?php foreach($vratings as $vrating){ ?>
									<input type="radio" value="<?php //echo $vrating; ?>" name="test-vendor" class="star {split:2}" disabled="disabled" <?php echo ($vendor_details['Vendor']['rating']==$vrating)?'checked="checked"':'';?> />
								<?php } ?>
								<? }else{ ?>
									<div class="no-rating">No feedback yet</div>
								<? } ?>	
						</div>	
					</div>	
		<!--
					<div class="vendor-activity-tags">
						<? if(!empty($vendor_details['Service'])){
							foreach($vendor_details['Service'] as $key=>$service) {
							?>
								<span><?php echo $this->Html->link(ucfirst($service['Service']['service_title']),array('plugin'=>false,'controller'=>'activity','action'=>'index',$service['Service']['id']),array('escape'=>false));?></span>
							<? } ?>
						<? } ?>
					</div>
		-->
				</div>
			</aside>
			<?=$this->element('activity/similar-listings');?>
		</section>

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
   
      
    });
 
 </script>
 
 <script type="text/javascript">
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
</script>


<script type="text/javascript">
	$(function()
	{
		$('.scroll-pane').jScrollPane({
			horizontalBar: false,
			verticalBar: true
		});
	});
</script>
