<div class="hr-line"></div>
<div class="clear"></div>


<h2 class="page-title">Member <span style="color:#000;">Registration</span></h2>

<div class="middle-area">
    <div class="registration-form-box">
	<?php if ($this->Session->check('Message.register_error')): ?>
	    <div class="notification error">
		<p><?=$this->Session->flash('register_error'); ?></p>
	    </div>
	<?php endif;?>
	<h6>Create New Account</h6>
	<?php echo $this->Form->create('Member',array('name'=>'members','id'=>'MemberRegistration','action'=>'registration' ,'type'=>'file','novalidate' => true, 'class'=>'registration-form'));?>
	    <?=$this->Form->hidden('form-name',array('required'=>false,'value'=>'RegistrationForm')); ?>
            <div class="registration-form-row">
               <div class="labelbox">
                <label>Name :  <span style="color:#ff4142;">*</span></label>
               </div>
               <div class="namefieldbox">
		    <label class="namefield">
			<?=$this->Form->text('first_name',array("placeholder"=>"First Name", 'value'=>(isset($_POST['first_name'])) ? $_POST['first_name'] : '', 'required'=>true,)); ?>
			<?=$this->Form->error('first_name',null,array('wrap' => 'div', 'class' => 'error-message')); ?>
                    </label>
                    <label class="namefield2">
			<?=$this->Form->text('last_name',array("placeholder"=>"Last Name", 'value'=>(isset($_POST['last_name'])) ? $_POST['last_name'] : '', 'required'=>true)); ?>
			<?=$this->Form->error('last_name',null,array('wrap' => 'div', 'class' => 'error-message')); ?>
                    </label>
               </div>
            </div>
                <br/>
            <div class="registration-form-row">
		<div class="labelbox">
		    <label>Email Address : <span style="color:#ff4142;">*</span></label>
		</div>
		<div class="fieldbox">
		    <?=$this->Form->text('email_id',array('value'=>(isset($_POST['email_id'])) ? $_POST['email_id'] : '', 'required'=>true)); ?>
		    <?=$this->Form->error('email_id',null,array('wrap' => 'div', 'class' => 'error-message')); ?>
		</div>
            </div>
                        <br/>
            <div class="registration-form-row">
		<div class="labelbox">
		    <label>Phone : <span style="color:#ff4142;"> *</span></label>  
		</div>
		<div class="fieldbox">
		    <?=$this->Form->text('phone',array('value'=>(isset($_POST['phone'])) ? $_POST['phone'] : '', 'required'=>false)); ?>
		    <?=$this->Form->error('phone',null,array('wrap' => 'div', 'class' => 'error-message')); ?>
		</div>
            </div>
                                <br/>
            <div class="registration-form-row">
		<div class="labelbox">
		    <label>Password : <span style="color:#ff4142;">*</span></label>
		</div>
		<div class="fieldbox">
		    <?=$this->Form->password('password',array('required'=>true)); ?>
		    <?=$this->Form->error('password',null,array('wrap' => 'div', 'class' => 'error-message')); ?>
		</div>
            </div>
                                        <br/>
            <div class="registration-form-row">
		<div class="labelbox">
		    <label>Confirm Password : <span style="color:#ff4142;">*</span></label>
		</div>
		<div class="fieldbox">
		    <?=$this->Form->password('confirm_password',array('required'=>true)); ?>
		    <?=$this->Form->error('confirm_password',null,array('wrap' => 'div', 'class' => 'error-message')); ?>
		</div>
            </div>
                                                <br/>
            <div class="registration-form-row">
		<input class="submit-button" value="Register Now" type="submit">
            </div>
                                                        <br/>
                                                                <br/>
    <?=$this->Form->hidden('fb_id',array('value'=>(isset($_POST['fb_id'])) ? $_POST['fb_id'] : '', 'required'=>false)); ?>
	<?php echo $this->Form->end();?>
    </div>
    </div>


 <script type="text/javascript">
    <?php $path = $this->Html->webroot; ?>
    $(document).ready(function(){
        $('#MemberRegistration').submit(function(){
	    //var data = $(this).serializeArray();
	    var data = new FormData(this);
	    var formData = $(this);
            var status = 0;
           
	    $.each(this,function(i,v){
                $(v).removeClass('invalid form-error');
                });
            $('.error-message').remove();
            $('#MemberRegistration > span#for_owner_cms').show();
            $('#MemberRegistration > button[type=submit]').attr({'disabled':true});
           
	    $.ajax({
                url: '<?=$path?>member_manager/members/validation',
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
               $('#MemberRegistration > button[type=submit]').attr({'disabled':false});
               $('#MemberRegistration > span#for_owner_cms').hide();
            }
	    return (status===1)?true:false; 
        });
    });
 </script>

<script type="text/javascript">
    <?php $path = $this->Html->webroot; ?>
    $(document).ready(function(){
		$('#MemberLogin').submit(function(){
			var data = new FormData(this);
			var formData = $(this);
            var status = 0;
           
           $.each(this,function(i,v){
                $(v).removeClass('invalid form-error');
                });
            $('.error-message').remove();
            $('#MemberLogin > span#for_owner_cms').show();
            $('#MemberLogin > button[type=submit]').attr({'disabled':true});
           
           $.ajax({
                url: '<?=$path?>member_manager/members/validation/login',
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
						$('#MemberLogin').find('#'+i).addClass("invalid form-error").after('<div class="error-message">'+v+'</div>');
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
				   $('#MemberLogin > button[type=submit]').attr({'disabled':false});
				   $('#MemberLogin > span#for_owner_cms').hide();
				}
			   return (status===1)?true:false; 
				
			});
		});
</script>

 

 
