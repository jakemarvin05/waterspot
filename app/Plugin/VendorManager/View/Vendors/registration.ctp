<div class="hr-line"></div>
<div class="clear"></div>
<?=$this->element('breadcrumbs');?>
<h2 class="page-title">Vendor <span style="color:#000;">Registration</span></h2>

<div class="middle-area">
    <div class="registration-form-box">
	<?php if ($this->Session->check('Message.register_error')): ?>
	    <div class="notification error">
		<p><?=$this->Session->flash('register_error'); ?></p>
	    </div>
	<?php endif;?>
	<h6 style="border: 0; text-align: center; padding: 0; margin: 0 0 25px 0;">Create New Account</h6>
	<?php echo $this->Form->create('Vendor',array('name'=>'vendors','id'=>'VendorRegistration','action'=>'registration' ,'type'=>'file','novalidate' => true, 'class'=>'registration-form'));?>
	    <?=$this->Form->hidden('form-name',array('required'=>false,'value'=>'RegistrationForm')); ?>
        <div class="registration-form-row">
			<div class="labelbox">
				<label>Business Name : <span style="color:#ff4142;">*</span></label>
			</div>
			<div class="fieldbox">
				<? // b_name is used for Business name?>
				<?=$this->Form->text('bname',array('required'=>false)); ?>
				<?=$this->Form->error('bname',null,array('wrap' => 'div', 'class' => 'error-message')); ?>  
			</div>
        </div>
		
        <div class="registration-form-row">
			<div class="labelbox">
				<label>First Name : <span style="color:#ff4142;">*</span></label>
			</div>
			<div class="fieldbox">
				<?=$this->Form->text('fname',array('required'=>false)); ?>
				<?=$this->Form->error('fname',null,array('wrap' => 'div', 'class' => 'error-message')); ?>  
			</div>
        </div>
        <div class="registration-form-row">
		<div class="labelbox">
		    <label>Last Name : <span style="color:#ff4142;">*</span></label>
		</div>
		<div class="fieldbox">
		    <?=$this->Form->text('lname',array('required'=>false)); ?>
		    <?=$this->Form->error('lname',null,array('wrap' => 'div', 'class' => 'error-message')); ?>
		</div>
            </div>
            <div class="registration-form-row">
		<div class="labelbox">
		    <label>Email Address : <span style="color:#ff4142;">*</span></label>
		</div>
		<div class="fieldbox">
		    <?=$this->Form->email('email',array('required'=>false)); ?>
		    <?=$this->Form->error('email',null,array('wrap' => 'div', 'class' => 'error-message')); ?>
		</div>
            </div>
            <div class="registration-form-row">
		<div class="labelbox">
		    <label>Phone : <span style="color:#ff4142;"> *</span></label>  
		</div>
		<div class="fieldbox">
		    <?=$this->Form->tel('phone',array('required'=>false)); ?>
		    <?=$this->Form->error('phone',null,array('wrap' => 'div', 'class' => 'error-message')); ?>
		</div>
            </div>
            <div class="registration-form-row">
		<div class="labelbox">
		    <label>Password : <span style="color:#ff4142;">*</span></label>
		</div>
		<div class="fieldbox">
		    <?=$this->Form->password('password',array('required'=>false)); ?>
		    <?=$this->Form->error('password',null,array('wrap' => 'div', 'class' => 'error-message')); ?>
		</div>
            </div>
            <div class="registration-form-row">
		<div class="labelbox">
		    <label>Confirm Password : <span style="color:#ff4142;">*</span></label>
		</div>
		<div class="fieldbox">
		    <?=$this->Form->password('confirm_password',array('required'=>false)); ?>
		    <?=$this->Form->error('confirm_password',null,array('wrap' => 'div', 'class' => 'error-message')); ?>
		</div>
            </div>
            <div class="registration-form-row" style="text-align: right;">
		<input class="submit-button" value="Register Now" type="submit">
            </div>
	<?php echo $this->Form->end();?>
    </div>
    <div class="login-form-box">
	<?php if ($this->Session->check('Message.login_error')): ?>
	    <div class="notification error">
		<a rel="tooltip" title="Hide Notification" href="#" class="close-notification close">X</a> 
		<div class="message" id="errorMessage"><?=$this->Session->flash('login_error'); ?></div> 
	    </div>
	<?php endif;?>
	<h6 style="border: 0; text-align: center; padding: 0; margin: 0 0 25px 0;">Login to your account</h6>
	<?php echo $this->Form->create('Vendor',array('name'=>'vendors','id'=>'VendorLogin','controller'=>'vendors' ,'type'=>'file','novalidate' => true, 'class'=>'login-form'));?>
	    <?=$this->Form->hidden('form-name',array('required'=>false,'value'=>'LoginForm')); ?>
	    <div class="login-form-row">
		   <div class="labelbox">
		      <label>Email Address : <span style="color:#ff0000">*</span></label>
		   </div>
		   <div class="fieldbox">
		      <?=$this->Form->email('emailid',array('required'=>false)); ?>
		      <?=$this->Form->error('emailid',null,array('wrap' => 'div', 'class' => 'error-message')); ?>
		   </div>
	    </div>
	    <div class="login-form-row">
		   <div class="labelbox">
		      <label>Password : <span style="color:#ff0000">*</span></label>
		   </div>
		   <div class="fieldbox">
		      <?=$this->Form->password('pass',array('required'=>false)); ?>
		      <?=$this->Form->error('pass',null,array('wrap' => 'div', 'class' => 'error-message')); ?> 
		   </div>
	    </div>
	    <div class="login-form-row keep-me-login" style="text-align: left;">
		   <p style="margin-left: 200px;">
			<?php echo $this->Form->input('keep_me_login',array('type'=>'checkbox','label' => __('Keep me logged in', true)));?>
		   </p>
		   <p style="margin-left: 200px;">Forgot Password? <?=$this->Html->link('Click here',array('controller'=>'accounts','action'=>'resetpassword'))?></p>
	    </div>
	    <div class="login-form-row" style="text-align: right;">
		   <input class="submit-button" value="Login" type="submit">
	    </div>
	<?php echo $this->Form->end();?>
    </div>
     
</div>
 
 
	 
 <script type="text/javascript">
	 <?php $path = $this->Html->webroot; ?>
    $(document).ready(function(){
		$('#VendorLogin').submit(function(){
			
			//var data = $(this).serializeArray();
			var data = new FormData(this);
			var formData = $(this);
            var status = 0;
           
           $.each(this,function(i,v){
                $(v).removeClass('invalid form-error');
                });
            $('.error-message').remove();
            $('#VendorLogin > span#for_owner_cms').show();
            $('#VendorLogin > button[type=submit]').attr({'disabled':true});
           
           $.ajax({
                url: '<?=$path?>vendor_manager/vendors/validation/login',
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
               $('#VendorLogin > button[type=submit]').attr({'disabled':false});
               $('#VendorLogin > span#for_owner_cms').hide();
            }
            
           return (status===1)?true:false; 
            
        });
        
        $('#VendorRegistration').submit(function(){
			
			//var data = $(this).serializeArray();
			var data = new FormData(this);
			var formData = $(this);
            var status = 0;
           
           $.each(this,function(i,v){
                $(v).removeClass('invalid form-error');
                });
            $('.error-message').remove();
            $('#VendorRegistration > span#for_owner_cms').show();
            $('#VendorRegistration > button[type=submit]').attr({'disabled':true});
           
           $.ajax({
                url: '<?=$path?>vendor_manager/vendors/validation',
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
               $('#VendorRegistration > button[type=submit]').attr({'disabled':false});
               $('#VendorRegistration > span#for_owner_cms').hide();
            }
           return (status===1)?true:false; 
            
        });
        
        
        
        
    });
 </script>


