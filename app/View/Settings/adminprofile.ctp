
<div>
	<article>
		<header>
			<h2>Administrator</h2>
		</header>
	</article>
	<?=$this->element('admin/message');?>
	<?=$this->Form->create('User',array('name'=>'user','url'=>array('controller'=>'settings','action'=>'adminprofile'),'novalidate'=>true,'onsubmit'=>'//return validate();' ))?>
	 <?php echo $this->Form->input('id');?>
	<fieldset>
		<dl>
			<dt>
				<label>Name <span style="color:red;">*</span></label>
			</dt>
			<dd>
				<?=$this->Form->hidden('form-name',array('required'=>false,'value'=>'UserProfileUpdate')); ?>
				<?php //echo $this->Form->hidden('id'); ?>
				<?=$this->Form->input('name',array('class'=> 
				'medium','size'=>'60','div'=>false,'label'=>false)); ?>
			</dd>
			
			<dt>
				<label>Last Name <span style="color:red;">*</span></label>
			</dt>
			<dd>
				<?=$this->Form->input('lname',array('class'=> 
				'medium','size'=>'60','div'=>false,'label'=>false)); ?>
			</dd>
		
			<dt>
				<label>Admin Email <span style="color:red;">*</span></label>
			</dt>
			<dd>
				<?=$this->Form->text('email',array('class'=> 
				'medium','size'=>'60','div'=>false,'label'=>false)); ?>
			</dd>
		
		</dl>

                    
                 
    </fieldset>
    <button type="submit"><?=__('Save');?></button>
         <?=$this->Form->end();?>
        </div>
        
        
        <script type="text/javascript">
	 <?php $path = $this->Html->webroot; ?>
    $(document).ready(function(){
		$('#UserAdminprofileForm').submit(function(){
			
			var data = $(this).serializeArray();
            var formData = $(this);
            var status = 0;
           
			$.each(this,function(i,v){
                $(v).removeClass('invalid form-error');
                });
            $('.error-message').remove();
            $('#UserAdminprofileForm > span#for_owner_cms').show();
            $('#UserAdminprofileForm > button[type=submit]').attr({'disabled':true});
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
               $('#UserAdminprofileForm > button[type=submit]').attr({'disabled':false});
               $('#UserAdminprofileForm > span#for_owner_cms').hide();
            }
           return (status===1)?true:false; 
            
        });
        
        
    });
 </script>

