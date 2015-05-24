 
<script language="javascript">
function saveform()
{
	document.getElementById('MemberPublish').value=1;
	document.getElementById('Member').submit();
}
</script>

	<div>
    <article>
        <header>
            <h2>
                <?php
                    if (isset($this->request->data['Member']['id']) && $this->request->data['Member']['id']):
                          echo  __('Update Member');
                    else:
                          echo  __('Add Member');
                    endif;
                ?>
            </h2>
        </header>
    </article>
	
    <?php echo $this->element('admin/message');?>
    <?php echo $this->Form->create('Member',array('name'=>'members','id'=>'Member','action'=>'add'))?>
    <?php echo $this->Form->input('id');?>
    <?php echo $this->Form->hidden('active');?>
    <?=$this->Form->hidden('form-name',array('class'=> 'small','size'=>'45','required'=>false,'value'=>'Admin-member-registration')); ?>
    
    <?=$this->Form->hidden('redirect', array('value' => $url)); ?>
    
    <fieldset>
        <dl>
            <dt>
                <label>First Name <span style="color:red;">*</span></label>
            </dt>
            
            <dd>
                <?=$this->Form->text('first_name',array('class'=> 'small','size'=>'45','required'=>false)); ?>
                <?=$this->Form->error('first_name',null,array('wrap' => 'span', 'class' => 'error-message')); ?>
                  
            </dd>
             <dt>
                <label>Last Name <span style="color:red;">*</span></label>
            </dt>
            
            <dd>
                <?=$this->Form->text('last_name',array('class'=> 'small','size'=>'45','required'=>false)); ?>
                <?=$this->Form->error('last_name',null,array('wrap' => 'span', 'class' => 'error-message')); ?>
                  
            </dd>
             <dt>
                <label>E-Mail ID <span style="color:red;">*</span></label>
            </dt>
            
            <dd>
                <?=$this->Form->text('email_id',array('class'=> 'small','size'=>'45','required'=>false)); ?>
                <?=$this->Form->error('email_id',null,array('wrap' => 'span', 'class' => 'error-message')); ?>
                  
            </dd>
             <dt>
                <label>Contact No. <span style="color:red;">*</span></label>
            </dt>
            
            <dd>
                <?=$this->Form->text('phone',array('class'=> 'small','size'=>'45','required'=>false)); ?>
                <?=$this->Form->error('phone',null,array('wrap' => 'span', 'class' => 'error-message')); ?> 
                  
            </dd>
            
            
        </dl>
    </fieldset>
	<button type="submit">
            <?php 
                if (isset($this->request->data['Member']['id']) && $this->request->data['Member']['id']):
                    echo __('Update');
                else:
                    echo __('Add');
                endif;								
            ?>
        </button> or 
        <?php echo $this->Html->link('Cancel', array('controller'=>'members', 'action' => 'index'));?>
                                
	<?php echo $this->Form->end();?>
</div>
<script type="text/javascript">
	 <?php $path = $this->Html->webroot; ?>
	$(document).ready(function(){
		$('#Member').submit(function(){
			
			//var data = $(this).serializeArray();
			var data = new FormData(this);
			var formData = $(this);
            var status = 0;
           
           $.each(this,function(i,v){
                $(v).removeClass('invalid form-error');
                });
            $('.error-message').remove();
            $('#Member > span#for_owner_cms').show();
            $('#Member > button[type=submit]').attr({'disabled':true});
           
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
							$('#'+i).addClass("invalid form-error").after('<span class="error-message">'+v+'</span>');
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
               $('#Member > button[type=submit]').attr({'disabled':false});
               $('#Member > span#for_owner_cms').hide();
            }
           
          
			
           return (status===1)?true:false; 
            
        });
        
        
    });
 </script>
