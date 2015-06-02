<div class="hr-line"></div>
<div class="clear"></div>
<?=$this->element('breadcrumbs');?>
<h2 class="page-title">Reset <span style="color:#000;">Password</span></h2>

<div class="middle-area">
    <h6>Forgot your Password?</h6>
    <?=$this->element('message');?>
    <div class="registration-form-box">
	<?php echo $this->Form->create('Vendor', array('url'=>array('plugin'=>'vendor_manager','controller'=>'accounts','action'=>'reset_password'),'class'=>'registration-form','novalidate' => true,'type'=>'file'));?>
	    <div class="registration-form-row">
		<p>Don't worry! Just fill in your email and we'll help you reset your password.</p>
	    </div>
	    <div class="registration-form-row">
		<div class="labelbox">
		    <label>Email Address: <span style="color:#ff0000">*</span></label>
		</div>
		<div class="fieldbox">
		    <?=$this->Form->hidden('form-name',array('required'=>false,'value'=>'ResetPasswordForm')); ?>
		    <?=$this->Form->email('email',array('required'=>false)); ?>
		</div>
	    </div>
	    <div class="registration-form-row" style="text-align: right;">
		<input class="submit-button" value="Submit" type="submit">
	    </div>
	<?php echo $this->Form->end();?>
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
