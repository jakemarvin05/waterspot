<div class="hr-line"></div>
<div class="clear"></div>
<div class="bredcrum"><a href="/">Home</a> &raquo; Reset Password</div>
<h2 class="page-title">Vendors</h2>
<div class="middle-area">
   <div class="login-middle"> <div class="login member-reg">
   <h6>Reset Password</h6>
	<?=$this->element('message');?>
	<?php echo $this->Form->create('Vendor', array('name' => 'user','url' => array('plugin'=>'vendor_manager','controller'=>'accounts','action'=>'passwordurl/'.$str),'class'=>'quick-contacts1','onSubmit'=>'//return validatefields()'));?>
            <div class="form-row"><label>New Password: <span style="color:#ff0000">*</span></label>
				<?=$this->Form->hidden('form-name',array('required'=>false,'value'=>'PasswordUrlForm')); ?>
              <?=$this->Form->password('password',array('required'=>false)); ?>
              
            </div>
            <div class="form-row"><label>Confirm Password: <span style="color:#ff0000">*</span></label>
              <?=$this->Form->password('password2',array('required'=>false)); ?>
            </div>
			<input type="submit" value="Reset Now" class="smt2">	
       <?php echo $this->Form->end();?>
       
       </div>
      </div>
    </div>
   </div>
    <div class="clear"></div> 
 </div>
 
<script type="text/javascript">
    <?php $path = $this->Html->webroot; ?>
    $(document).ready(function(){
		$('#VendorPasswordurlForm').submit(function(){
			var data = new FormData(this);
			var formData = $(this);
            var status = 0;
			$.each(this,function(i,v){
                $(v).removeClass('invalid form-error');
                });
            $('.error-message').remove();
            $('#VendorPasswordurlForm > span#for_owner_cms').show();
            $('#VendorPasswordurlForm > button[type=submit]').attr({'disabled':true});
           
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
               $('#VendorPasswordurlForm > button[type=submit]').attr({'disabled':false});
               $('#VendorPasswordurlForm > span#for_owner_cms').hide();
            }
           return (status===1)?true:false; 
        });
    });
</script>
