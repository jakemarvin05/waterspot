<div class="hr-line"></div>
<div class="clear"></div>
<div class="bredcrum"><a href="/">Home</a> &raquo; Reset Password</div>
<h2 class="page-title">Member</h2>

<div class="middle-area">
    <?=$this->element('message');?>
    <h6>Reset Password</h6>
    <div class="registration-form-box">
	<?php echo $this->Form->create('Member', array('name' => 'user','url' => array('plugin'=>'member_manager','controller'=>'members','action'=>'passwordurl/'.$str),'novalidate'=>true,'class'=>'registration-form'));?>
	  <div class="registration-form-row">
	    <div class="labelbox">
	      <label>New Password: <span style="color:#ff0000">*</span></label>
	    </div>
	    <div class="fieldbox">
              <?=$this->Form->password('password',array('required'=>false)); ?>
              <?=$this->Form->hidden('form-name',array('required'=>false,'value'=>'PasswordUrlForm')); ?>
	    </div>
	  </div>
	  <div class="registration-form-row">
	    <div class="labelbox">
	      <label>Confirm Password: <span style="color:#ff0000">*</span></label>
	    </div>
	    <div class="fieldbox">
              <?=$this->Form->password('password2',array('required'=>false)); ?>
	    </div>
	  </div>
	  <div class="registration-form-row" style="text-align: right;">
	    <input type="submit" value="Save" class="submit-button">
	  </div>
	<?php echo $this->Form->end();?>
      </div>
    </div>
</div>

<script type="text/javascript">
    <?php $path = $this->Html->webroot; ?>
    $(document).ready(function(){
		$('#MemberPasswordurlForm').submit(function(){
			var data = new FormData(this);
			var formData = $(this);
            var status = 0;
			$.each(this,function(i,v){
                $(v).removeClass('invalid form-error');
                });
            $('.error-message').remove();
            $('#MemberPasswordurlForm > span#for_owner_cms').show();
            $('#MemberPasswordurlForm > button[type=submit]').attr({'disabled':true});
           
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
               $('#MemberPasswordurlForm > button[type=submit]').attr({'disabled':false});
               $('#MemberPasswordurlForm > span#for_owner_cms').hide();
            }
           return (status===1)?true:false; 
        });
    });
</script>

 
