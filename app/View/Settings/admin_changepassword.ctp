<div>
    <article>
            <header>
                    <h2>Change Password</h2>
            </header>
    </article>
    <?php echo $this->element('admin/message');?>
    <?php echo $this->Form->create('User', array('name' => 'user','url' => array('controller'=>'settings','action'=>'admin_changepassword'),'id'=>'PasswordChange','onSubmit'=>'//return validatefields()'));?>
    <fieldset>
        <dt><label>Current Password <span style="color:red;">*</span></label></dt>
        <?=$this->Form->hidden('form-name',array('required'=>false,'value'=>'PasswordChange')); ?>
        <dd><?php echo $this->Form->password('oldpassword', array('class'=>'small','size' => 20,'required'=>false)); ?></dd>
        <dt><label>New Password <span style="color:red;">*</span></label></dt>
        <dd><?php echo $this->Form->password('password', array('class'=>'small','size' => 20,'required'=>false)); ?></dd>
        <dt><label>Confirm Password <span style="color:red;">*</span></label></dt>
        <dd><?php echo $this->Form->password('password2', array('class'=>'small','size' => 20,'required'=>false)); ?></dd>
    </fieldset>
    <button type="submit">Change Password</button>
    <?php echo $this->Form->end();?>
	
</div>
<script type="text/javascript">
	 <?php $path = $this->Html->webroot; ?>
    $(document).ready(function(){
		$('#PasswordChange').submit(function(){
			
			var data = $(this).serializeArray();
            var formData = $(this);
            var status = 0;
           
           $.each(this,function(i,v){
                $(v).removeClass('invalid form-error');
                });
            $('.error-message').remove();
            $('#PasswordChange > span#for_owner_cms').show();
            $('#PasswordChange > button[type=submit]').attr({'disabled':true});
           $.ajax({
                url: '<?=$path?>subadmin_manager/users/validation',
                async: false,
				data: data,
                dataType:'json', 
                type:'post',
                success: function(data) {
					 
                    if(data.error==1){
						 
                        $.each(data.errors,function(i,v){
							
							$('#'+i).addClass("invalid form-error").after('<span class="error-message">'+v+'</span>');
                            
                        });
                       
                    }else{
                        status = 1;
                    }
                   
                   }


            });
            if(status==0){
               $("html, body").animate({ scrollTop: 0 }, "slow");
               $('#PasswordChange > button[type=submit]').attr({'disabled':false});
               $('#PasswordChange > span#for_owner_cms').hide();
            }
           
          
          
           return (status===1)?true:false; 
            
        });
        
        
    });
 </script>
