
<?=$this->element('breadcrumbs');?>
<h2 class="page-title">Vendor <span style="color:#000;">Login</span></h2>

<div class="middle-area">
	 
	 <?php if ($this->Session->check('Message.login_error')): ?>
	 <div class="notification error">
		<p><?=$this->Session->flash('login_error'); ?></p>
	 </div>
      <?php endif;?>
   
   <div class="registration-form-box">
		<?php echo $this->Form->create('Vendor',array('name'=>'vendors','id'=>'VendorLogin','controller'=>'vendors' ,'type'=>'file','novalidate' => true, 'class'=>'registration-form'));?>
		<?=$this->Form->hidden('form-name',array('required'=>false,'value'=>'LoginForm')); ?>
	 <div class="registration-form-row">
	    <div class="labelbox">
	       <label>Email Address : <span style="color:#ff0000">*</span></label>
	    </div>
	    <div class="fieldbox">
	       <?=$this->Form->email('emailid',array('required'=>false)); ?>
	       <?=$this->Form->error('emailid',null,array('wrap' => 'div', 'class' => 'error-message')); ?>
	    </div>
	 </div>
	 <div class="registration-form-row">
	    <div class="labelbox">
	       <label>Password : <span style="color:#ff0000">*</span></label>
	    </div>
	    <div class="fieldbox">
	       <?=$this->Form->password('pass',array('required'=>false)); ?>
	       <?=$this->Form->error('pass',null,array('wrap' => 'div', 'class' => 'error-message')); ?> 
	    </div>
	 </div>
	 <div class="registration-form-row" style="text-align: center;">
	    <?=$this->Form->checkbox('keep_me_login',array('required'=>false)); ?> Keep me logged in
	 </div>
	 <div class="registration-form-row" style="text-align: right;">
	    <input class="submit-button" value="Login" type="submit">
	 </div>
      <?php echo $this->Form->end();?>
      <p style="margin-left: 200px;">Forgot Password? <?=$this->Html->link('Click here',array('controller'=>'accounts','action'=>'resetpassword'))?></p>
      <p style="margin-left: 200px;">Not Registered? <?php echo $this->Html->link('Register now!',array('controller'=>'vendors','action'=>'registration','plugin'=>'vendor_manager'),array('escape'=>false));?></p>
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


