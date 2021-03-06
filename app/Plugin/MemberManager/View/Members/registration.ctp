<div class="container-fluid vendor-panel">

<br/><br<br/><br/><br/><br><br>

<div class="middle-area">
    <div class="registration-form-box">
	<?php if ($this->Session->check('Message.register_error')): ?>
	    <div class="notification error">
		<p><?=$this->Session->flash('register_error'); ?></p>
	    </div>
	<?php endif;?>
    <?php if ($this->Session->check('Message.error')): ?>
        <div class="error-message">
        <p><?=$this->Session->flash('error'); ?></p>
        </div>
    <?php endif;?>
        <br/>
	<p class="beforeHeader">Register and be a Member</p>
        <h1 class="headerAlt">CREATE A MEMBER ACCOUNT</h1>
        <br/>
	<?php echo $this->Form->create('Member',array('name'=>'members','id'=>'MemberRegistration','action'=>'registration' ,'type'=>'file','novalidate' => true, 'class'=>'registration-form'));?>
	    <?=$this->Form->hidden('form-name',array('required'=>false,'value'=>'RegistrationForm')); ?>
            <div class="registration-form-row">
                <div class="fieldbox">
                    <?=$this->Form->text('first_name',array("placeholder"=>"Name :",'class'=>'registration_inputbox','required'=>false)); ?>
                    <?=$this->Form->error('first_name',null,array('wrap' => 'div', 'class' => 'error-message')); ?>
                </div>
            </div>
                <br/>
            <div class="registration-form-row">
		<div class="fieldbox">
		    <?=$this->Form->text('email_id',array("placeholder"=>"Email :", 'class'=>'registration_inputbox', 'value'=>(isset($_POST['email_id'])) ? $_POST['email_id'] : '', 'required'=>true)); ?>
		    <?=$this->Form->error('email_id',null,array('wrap' => 'div', 'class' => 'error-message')); ?>
		</div>
            </div>
                        <br/>
            <div class="registration-form-row">
		<div class="fieldbox">
		    <?=$this->Form->text('phone',array("placeholder"=>"Phone Number :", 'class'=>'registration_inputbox', 'value'=>(isset($_POST['phone'])) ? $_POST['phone'] : '', 'required'=>false)); ?>
		    <?=$this->Form->error('phone',null,array('wrap' => 'div', 'class' => 'error-message')); ?>
		</div>
            </div>
                                <br/>
            <div class="registration-form-row">
		<div class="fieldbox">
		    <?=$this->Form->password('password',array("placeholder"=>"Password :", 'class'=>'registration_inputbox', 'required'=>true)); ?>
		    <?=$this->Form->error('password',null,array('wrap' => 'div', 'class' => 'error-message')); ?>
		</div>
            </div>
                                        <br/>
            <div class="registration-form-row">
		<div class="fieldbox">
		    <?=$this->Form->password('confirm_password',array("placeholder"=>"Confirm Password :", 'class'=>'registration_inputbox', 'required'=>true)); ?>
		    <?=$this->Form->error('confirm_password',null,array('wrap' => 'div', 'class' => 'error-message')); ?>
		</div>
            </div>
                                                <br/>
            <div class="registration-form-row">
		<input class="submit-button btn btnDefaults btnFillOrange registration_button" value="Register Now" type="submit">
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
</div>

 

 
