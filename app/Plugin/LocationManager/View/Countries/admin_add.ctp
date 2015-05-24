 
<script language="javascript">
function saveform()
{
	document.getElementById('CountryPublish').value=1;
	document.getElementById('CountryCms').submit();
}
</script>

<div>
    <article>
        <header>
            <h2>
                <?php
                    if (isset($this->request->data['Country']['id']) && $this->request->data['Country']['id']):
                          echo  __('Update Country');
                    else:
                          echo  __('Add Country');
                    endif;
                ?>
            </h2>
        </header>
    </article>
	
    <?php echo $this->element('admin/message');?>
    <?php echo $this->Form->create('Country',array('name'=>'counteries','id'=>'CountryCms','action'=>'add/','onsubmit'=>'//return validatefields();','type'=>'file','novalidate'=>true))?>
    <?php echo $this->Form->hidden('id');?>
    <?php echo $this->Form->hidden('status');?>
    <?=$this->Form->hidden('redirect', array('value' => $url)); ?>
    
    <fieldset>
        <dl>
            <dt>
                <label>Country Name <span style="color:red;">*</span></label>
            </dt>
            <dd>
                <?=$this->Form->text('name',array('class'=> 'small','size'=>'45')); ?>
                <?=$this->Form->error('name',null,array('wrap' => 'span', 'class' => 'error-message')); ?>
                  
            </dd>
            
            <dt>
                <label>Country Code<span style="color:red;">*</span></label>
            </dt>
            
            <dd>
                <?=$this->Form->text('alpha_2',array('class'=> 'small','size'=>'45', 'style'=>'text-transform: uppercase')); ?>
                <?=$this->Form->error('alpha_2',null,array('wrap' => 'span', 'class' => 'error-message')); ?>
                  
            </dd>
            
        </dl>
    </fieldset>
	<button type="submit">
            <?php 
                if (isset($this->request->data['Country']['id']) && $this->request->data['Country']['id']):
                    echo __('Update');
                else:
                    echo __('Add');
                endif;								
            ?>
        </button> or 
        <?php echo $this->Html->link('Cancel', array('controller'=>'countries', 'action' => 'index'));?>
                                
	<?php echo $this->Form->end();?>
</div>

 
<script type="text/javascript">
	
	<?php $path = $this->Html->webroot; ?>
     
    $(document).ready(function(){
		$('#CountryCms').submit(function(){
			
			var data = $(this).serializeArray();
            var formData = $(this);
            var status = 0;
           
           $.each(this,function(i,v){
                $(v).removeClass('form-error');
                });
            $('.error-message').remove();
            $('#CountryCms > span#for_owner_cms').show();
            $('#CountryCms > button[type=submit]').attr({'disabled':true});
           
           $.ajax({
                url: '<?=$path?>location_manager/countries/validation',
                async: false,
				data: data,
                dataType:'json', 
                type:'post',
                success: function(data) {
					 
                    if(data.error==1){
						 
                        $.each(data.errors,function(i,v){
							
							$('#'+i).addClass("form-error").after('<span class="error-message">'+v+'</span>');
                            
                        });
                       
                    }else{
                        status = 1;
                    }
                   
                   }


            });
            if(status==0){
               $("html, body").animate({ scrollTop: 0 }, "slow");
               $('#CountryCms > button[type=submit]').attr({'disabled':false});
               $('#CountryCms > span#for_owner_cms').hide();
            }
           
          
          
           return (status===1)?true:false; 
            
        });
        
        
    });
 </script>
