<div class="container-fluid wrapper accounts-page">

    <header class="page-header row text-center">
        <p class="beforeHeader">Did You Forgot Your Password?</p>

        <h1 class=" headerAlt">Account Settings</h1>
    </header>

    <div class="middle-area row">
    <?=$this->element('message');?>
    <div class="registration-form-box col-sm-6 col-sm-offset-3">
	<?php echo $this->Form->create('Vendor', array('url'=>array('plugin'=>'vendor_manager','controller'=>'accounts','action'=>'reset_password'),'class'=>'registration-form','novalidate' => true,'type'=>'file'));?>
	    <div class="registration-form-row">
		<p class="text-center">Don't worry! Just fill in your email and we'll help you reset your password.</p>
	    </div>
	    <div class="registration-form-row form-row">
		<div class="fieldbox">
		    <?=$this->Form->hidden('form-name',array('required'=>false,'value'=>'ResetPasswordForm')); ?>
		    <?=$this->Form->email('email',array('required'=>false,'class'=>'form-control','placeholder'=>'Email Address:')); ?>
		</div>
	    </div>
	    <div class="registration-form-row">
		<input class="submit-button btn btnDefaults btnFillOrange registration_button" value="Submit" type="submit">
	    </div>
	<?php echo $this->Form->end();?>
    </div>
        <div class="spacer"></div>
</div>
</div>
<script type="text/javascript">
    <?php $path = $this->Html->webroot; ?>
    $(document).ready(function(){
		$('#VendorResetpasswordForm').submit(function(){
			var data = new FormData(this);
			var formData = $(this);
            var status = 0;
			$.each(this,function(i,v){
                $(v).removeClass('invalid form-error');
                });
            $('.error-message').remove();
            $('#VendorResetpasswordForm > span#for_owner_cms').show();
            $('#VendorResetpasswordForm > button[type=submit]').attr({'disabled':true});
           
			$.ajax({
                url: '<?=$path?>vendor_manager/accounts/validation',
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
               $('#VendorResetpasswordForm > button[type=submit]').attr({'disabled':false});
               $('#VendorResetpasswordForm > span#for_owner_cms').hide();
            }
           return (status===1)?true:false; 
        });
    });
</script>
