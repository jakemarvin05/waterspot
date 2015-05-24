<div class="hr-line"></div>
<div class="clear"></div>
<?=$this->element('breadcrumbs');?>
<h2 class="page-title">Member <span style="color:#000;">Login</span></h2>

<div class="middle-area">
		<?=$this->element('message');?>
   <div class="registration-form-box">
      
      <?php echo $this->Form->create('Member',array('name'=>'members','id'=>'MemberLogin','controller'=>'members','novalidate'=>true,'class'=>'registration-form'));?>
	 <?=$this->Form->hidden('form-name',array('required'=>false,'value'=>'LoginForm')); ?>
	 <div class="registration-form-row">
	    <div class="labelbox">
	       <label>Email Address : <span style="color:#ff0000">*</span></label>
	    </div>
	    <div class="fieldbox">
	       <?=$this->Form->email('email_id',array('required'=>false)); ?>
	       <?=$this->Form->error('email_id',null,array('wrap' => 'div', 'class' => 'error-message')); ?>
	    </div>
	 </div>
	 <div class="registration-form-row">
	    <div class="labelbox">
	       <label>Password : <span style="color:#ff0000">*</span></label>
	    </div>
	    <div class="fieldbox">
	       <?=$this->Form->password('password',array('required'=>false)); ?>
	       <?=$this->Form->error('password',null,array('wrap'=>'div','class'=>'error-message'));?>
	    </div>
	 </div>
	 <div class="registration-form-row" style="text-align: center;">
	    <?php echo $this->Form->checkbox('keep_me_login',array());?> Keep me logged in
	 </div>
	 <div class="registration-form-row" style="text-align: right;">
	    <input class="submit-button" value="Login" type="submit">
	 </div>
      <?php echo $this->Form->end();?>
      <p style="margin-left: 200px;">Forgot Password? <?php echo $this->Html->link('Click here',array('controller'=>'members','action'=>'resetpassword','plugin'=>'member_manager'),array('escape'=>false));?></p>
      <p style="margin-left: 200px;">Not Registered? <?php echo $this->Html->link('Register now!',array('controller'=>'members','action'=>'registration','plugin'=>'member_manager'),array('escape'=>false));?></p>
   </div>
</div>

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
               $('#MemberLogin > button[type=submit]').attr({'disabled':false});
               $('#MemberLogin > span#for_owner_cms').hide();
            }
           return (status===1)?true:false; 
            
        });
    });
</script>
