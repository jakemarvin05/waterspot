 
<script language="javascript">
function saveform()
{
	document.getElementById('SocialPublish').value=1;
	document.getElementById('Socialmedia').submit();
}
</script>

<div>
    <article>
        <header>
            <h2>
                <?php
                    if (isset($this->request->data['Setting']['id']) && $this->request->data['Setting']['id']):
                          echo  __('Update Link');
                    else:
                          echo  __('Add Link');
                    endif;
                ?>
            </h2>
        </header>
    </article>
	
    <?php echo $this->element('admin/message');?>
    <?=$this->Form->create('Setting',array('name'=>'siteconfig','url'=>array('controller'=>'settings','action'=>'social'),'type' => 'file','onsubmit'=>'return validate();' ))?>
    <fieldset>
        <dl>
            <dt>
                <label>Facebook <span style="color:red;">*</span></label>
            </dt>
            <dd>
                <?=$this->Form->text('facebook',array('class'=> 'small','size'=>'70','required'=>false)); ?>
                  
            </dd>
                      
             <dt>
                <label>Twitter <span style="color:red;">*</span></label>
            </dt>
            <dd>
                <?=$this->Form->text('twitter',array('class'=> 'small','size'=>'70','required'=>false)); ?>
                  
            </dd>
                    
            <dt>
                <label>Google Plus <span style="color:red;">*</span></label>
            </dt>
            <dd>
                <?=$this->Form->text('google_plus',array('class'=> 'small','size'=>'70','required'=>false)); ?>
                  
            </dd>
            <dt>
                <label>Linkedin <span style="color:red;">*</span></label>
            </dt>
            <dd>
                <?=$this->Form->text('linkedin',array('class'=> 'small','size'=>'70','required'=>false)); ?>
                  
            </dd>
<!--
            <dt>
                <label>Pinterest <span style="color:red;">*</span></label>
            </dt>
            <dd>
                <?//=$this->Form->text('pinterest',array('class'=> 'small','size'=>'70','required'=>false)); ?>
                  
            </dd>
-->
                    
            
        </dl>
    </fieldset>
	<button type="submit"><?=__('Save');?></button>
	 
        <?php //echo $this->Html->link('Cancel', array('controller'=>'settings', 'action' => 'social'));?>
                                
	<?php echo $this->Form->end();?>
</div>

 
<script type="text/javascript">
    $(document).ready(function(){
		$('#Socialmedia').submit(function(){
			
			var data = $(this).serializeArray();
            var formData = $(this);
            var status = 0;
           
           $.each(this,function(i,v){
                $(v).removeClass('invalid form-error');
                });
            $('.error-message').remove();
            $('#Socialmedia > span#for_owner_cms').show();
            $('#Socialmedia > button[type=submit]').attr({'disabled':true});
           
           $.ajax({
                url: '<?=$path?>settings/validation',
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
               $('#Socialmedia > button[type=submit]').attr({'disabled':false});
               $('#Socialmedia > span#for_owner_cms').hide();
            }
           
          
          
           return (status===1)?true:false; 
            
        });
        
        
    });
 </script>
