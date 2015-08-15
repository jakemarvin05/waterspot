<div class="container-fluid topResponsivePadding">

<div class="middle-area">
    <div class="registration-form-box">
	<?php if ($this->Session->check('Message.register_error')): ?>
	    <div class="notification error">
		<p><?=$this->Session->flash('register_error'); ?></p>
	    </div>
	<?php endif;?>
    <?php if ($this->Session->check('Message.login_error')): ?>
        <div class="error-message">
        <p><?=$this->Session->flash('login_error'); ?></p>
        </div>
    <?php endif;?>
        <br/>
	<p class="beforeHeader">Register and Start selling Services</p>
        <h1 class="headerAlt">Create a New Vendor account</h1>
        <br/>
	<?php echo $this->Form->create('Vendor',array('name'=>'vendors','id'=>'VendorRegistration','action'=>'registration' ,'type'=>'file','novalidate' => true, 'class'=>'registration-form'));?>
	    <?=$this->Form->hidden('form-name',array('required'=>false,'value'=>'RegistrationForm')); ?>
        <div class="registration-form-row">
			<div class="fieldbox">
				<? // b_name is used for Business name?>
				<?=$this->Form->text('bname',array("placeholder"=>"Your Business Name Here :" ,'required'=>false, 'class'=>'registration_inputbox')); ?>
				<?=$this->Form->error('bname',null,array('wrap' => 'div', 'class' => 'error-message')); ?>  
			</div>
        </div>
        

		
            <div class="registration-form-row">
            </div>
        <br/>
            <div class="registration-form-row">
		<div class="fieldbox">
		    <?=$this->Form->email('email',array("placeholder"=>"Email :", 'class'=>'registration_inputbox', 'required'=>false)); ?>
		    <?=$this->Form->error('email',null,array('wrap' => 'div', 'class' => 'error-message')); ?>
		</div>
            </div>
        <br/>
            <div class="registration-form-row">
		<div class="fieldbox">
		    <?=$this->Form->tel('phone',array("placeholder"=>"Phone Number :", 'class'=>'registration_inputbox', 'required'=>false)); ?>
		    <?=$this->Form->error('phone',null,array('wrap' => 'div', 'class' => 'error-message')); ?>
		</div>
            </div>
        <br/>
            <div class="registration-form-row">
		<div class="fieldbox">
		    <?=$this->Form->password('password',array("placeholder"=>"Password :", 'class'=>'registration_inputbox', 'required'=>false)); ?>
		    <?=$this->Form->error('password',null,array('wrap' => 'div', 'class' => 'error-message')); ?>
		</div>
            </div>
        <br/>
            <div class="registration-form-row">
		<div class="fieldbox">
		    <?=$this->Form->password('confirm_password',array("placeholder"=>"Confirm Password :", 'class'=>'registration_inputbox', 'required'=>false)); ?>
		    <?=$this->Form->error('confirm_password',null,array('wrap' => 'div', 'class' => 'error-message')); ?>
		</div>
            </div>
        <br/>
            <div class="registration-form-row">
		<input class="submit-button btn btnDefaults btnFillOrange registration_button" value="Register Now" type="submit">
            </div>
                <br/>
                        <br/>
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
</div>

