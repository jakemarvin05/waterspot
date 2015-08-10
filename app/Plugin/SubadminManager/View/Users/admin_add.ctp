<script language="javascript">
function saveform()
{
	document.getElementById('UserAddForm').submit();
}
</script>
<div>
        <article>
		<header>
				<h2>
			   <?php
                    if (isset($this->request->data['User']['id']) && $this->request->data['User']['id']):
                          echo  __('Update Sub Admin');
                    else:
                          echo  __('Add Sub Admin');
                    endif;
                ?>
				</h2>
                </header>
        </article>
	<?php echo $this->element('admin/message');?>
    <?php echo $this->Form->create('User',array('name'=>'users','id'=>'UserAddForm','action'=>'add','type'=>'file'))?>
    <?php echo $this->Form->input('id');?>
    <?//=$this->Form->hidden('redirect', array('value' => $url)); ?>
        <fieldset>
		<dl>
			<dt>
				<label>First Name <span style="color:red;">*</span></label>
			</dt>
            <dd>
				<?=$this->Form->hidden('form-name',array('class'=> 'small','size'=>'45','value'=>'NewUserForm','required'=>false)); ?>
				<?=$this->Form->text('name',array('class'=> 'small','size'=>'45','required'=>false)); ?>
                <?=$this->Form->error('name',null,array('wrap' => 'span', 'class' => 'error-message')); ?>
			</dd>
			
			<dt>
				<label>Last Name <span style="color:red;">*</span></label>
			</dt>
             <dd>
				<?=$this->Form->text('lname',array('class'=> 'small','size'=>'45','required'=>false)); ?>
                <?=$this->Form->error('lname',null,array('wrap' => 'span', 'class' => 'error-message')); ?>
 			</dd>

             <dt>
				<label>Email <span style="color:red;">*</span></label>
			</dt>
            <dd>
				<?=$this->Form->text('email',array('class'=> 'small','size'=>'45','required'=>false)); ?>
                <?=$this->Form->error('email',null,array('wrap' => 'span', 'class' => 'error-message')); ?>
			</dd>
                        
            <dt>
				<label>Username <span style="color:red;">*</span></label>
			</dt>
            <dd>
				<?=$this->Form->text('username',array('class'=> 'small','size'=>'45','required'=>false)); ?>
                <?=$this->Form->error('username',null,array('wrap' => 'span', 'class' => 'error-message')); ?>
			</dd>
		</dl>
        </fieldset>
        <button type="submit">
			<?php 
                if (isset($this->request->data['User']['id']) && $this->request->data['User']['id']):
                    echo __('Update');
                else:
                    echo __('Add');
                endif;								
            ?>
	</button> or 
	<?php echo $this->Html->link('Cancel', array('controller'=>'users', 'action' => 'index'));?>
	
	<?php echo  $this->Form->end();?>
</div>

<script type="text/javascript">
	<?php $path = $this->Html->webroot; ?>
    $(document).ready(function(){
		$('#UserAddForm').submit(function(){
			
			var data = $(this).serializeArray();
            var formData = $(this);
            var status = 0;
           
            $.each(this,function(i,v){
                $(v).removeClass('form-error');
                });
            $('.error-message').remove();
            $('#UserAddForm > span#for_owner_cms').show();
            $('#UserAddForm > button[type=submit]').attr({'disabled':true});
           
			$.ajax({
                url: '<?=$path?>subadmin_manager/users/validation',
                async: false,
				data: data,
                dataType:'json', 
                type:'post',
                success: function(data) {
                    if(data.error==1){
						 
                        $.each(data.errors,function(i,v){
							
							$('#'+i).addClass("form-error").after('<span class="error-message">'+v+'</span>');
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
               $('#UserAddForm > button[type=submit]').attr({'disabled':false});
               $('#UserAddForm > span#for_owner_cms').hide();
            }
           return (status===1)?true:false; 
            
        });
        
        
    });
 </script>
