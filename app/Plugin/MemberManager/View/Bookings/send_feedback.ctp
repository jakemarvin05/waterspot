<div class="container-fluid member-panel">
<div class="hr-line"></div>
<div class="clear"></div>
<?=$this->element('breadcrumbs');?>
<h2 class="page-title">Leave <span style="color: #000;">Feedback</span></h2>
<?=$this->element('MemberManager.left-member-panel');?>
<?php if($review_status==0){ ?>
	<div class="right-area feedback col-sm-9 col-xs-12">
		<?=$this->element('message');?>
		<?=$this->Form->create('ServiceReview',array('class'=>'dashboard-feedback-form','id'=>'ServiceReview','url'=>array('plugin'=>'member_manager','controller'=>'bookings',	'action'=>'send_feedback',$order_id),'novalidate' => true));?>
			<?php echo $this->Form->hidden('id');?>
			<?php echo $this->Form->hidden('ref_no');?>
			<?php echo $this->Form->hidden('service_id');?>
			<?php echo $this->Form->hidden('member_id');?>
			
			<div class="dashboard-form-row">
				<label>Leave Feedback: <span style="color:#F00">*</span></label>
				<?php echo $this->Form->textarea('message',array('required'=>false,'id'=>'Message','placeholder'=>'Please type your feedback here...')); ?>
				<?=$this->Form->error('confirm_password',null,array('wrap' => 'div', 'class' => 'error-message')); ?>
			</div>
			<div class="dashboard-form-row">
				<label>Service Rating: <span style="color:#F00">*</span></label>
				<div class="dashboard-service-rating">
					<? $ranges=range(1,10);?>
					<?php foreach($ranges as $range){?>
						<input name="data[ServiceReview][rating]" type="radio" value="<?=($range); ?>" class="star {split:2}"/>
					<? }?>
				</div>
				<div class="clearfix"></div>
			</div>
			<div><span id="ServiceReviewMessage"></span></div>
			<div class="dashboard-form-row">
				<?php echo $this->Html->link('Back', array('plugin'=>'member_manager','controller'=>'bookings', 'action' => 'booking_details',$this->request->data['ServiceReview']['ref_no']),array('escape'=>false,'class'=>' btnDefaults btnFillGrey'));?>
				<input type="submit" value="Submit Feedback" class="btnDefaults btnFillOrange" id="submitBtn" />
			</div>
		<?=$this->Form->end();?>
	</div>

<?php }else { ?>
	<div class="right-area feedback col-sm-9 col-xs-12">
		<p>You have already submitted reviews for this service.</p>
		<?php echo $this->Html->link('Back &raquo;', array('plugin'=>'member_manager','controller'=>'bookings', 'action' => 'booking_details',$this->request->data['ServiceReview']['ref_no']),array('escape'=>false));?>
	</div>
<? } ?>
<script type="text/javascript">
    <?php $path = $this->Html->webroot; ?>
	  var submitButton = $('#submitBtn');
	  submitButton.prop("disabled", true);
	  setTimeout(function () {
        submitButton.prop("disabled", false);
    },3000);
    
    $(document).ready(function(){
		//alert('etst');
		$('#ServiceReview').submit(function(){
			
			var data = $(this).serializeArray();
            var formData = $(this);
            var status = 0;
           
           
           
            $.each(this,function(i,v){
                $(v).removeClass('invalid form-error');
                });
            $('.error-message').remove();
            $('#ServiceReview > span#for_owner_cms').show();
            $('#ServiceReview > button[type=submit]').attr({'disabled':true});
            $.ajax({
                url: '<?=$path?>member_manager/bookings/validation',
                async: false,
				data: data,
                dataType:'json', 
                type:'post',
                success: function(data) {
                    if(data.error==1){
						 
                        $.each(data.errors,function(i,v){
							
							$('#'+i).addClass("invalid form-error").after('<span class="error-message" style="position:relative; top:-10px; font-size:13px; margin: 10px; width:300px;">'+v+'</span>');
							$('#'+i).bind('click',function(){
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
               $('#ServiceReview > button[type=submit]').attr({'disabled':false});
               $('#ServiceReview > span#for_owner_cms').hide();
            }
          
           return (status===1)?true:false; 
            
        });
    });
 </script>
</div>