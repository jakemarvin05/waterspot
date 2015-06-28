 
<script language="javascript">
function saveform()
{
	document.getElementById('CityPublish').value=1;
	document.getElementById('CityCms').submit();
}
</script>
<div>
    <article>
        <header>
            <h2>
                <?php
                    if (isset($this->request->data['City']['id']) && $this->request->data['City']['id']):
                          echo  __('Update City');
                    else:
                          echo  __('Add City');
                    endif;
                ?>
            </h2>
        </header>
    </article>
	
    <?php echo $this->element('admin/message');?>
    <?php echo $this->Form->create('City',array('name'=>'cities','id'=>'CityCms','action'=>'add/'.$country_id,'onsubmit'=>'//return validatefields();','type'=>'file'))?>
    <?php echo $this->Form->input('id');?>
    <?php echo $this->Form->hidden('country_id', array('value'=>$country_id)); ?>
    <?php echo $this->Form->hidden('status'); ?>
    <?=$this->Form->hidden('redirect', array('value' => $url)); ?>
    
    <fieldset>
        <dl>
            <dt>
                <label>Country Name <span style="color:red;">*</span></label>
            </dt>
            <dd>
               <?=$this->Form->input('country_id',array('options'=>$countries,'type'=>'select', 'class'=> 'small', 'style'=>'width:304px','label'=>false,'div'=>false,'required'=>false)); ?>
                <?=$this->Form->error('country_id',null,array('wrap' => 'span', 'class' => 'error-message')); ?>
                  
            </dd>
            
            <dt>
                <label>City Name <span style="color:red;">*</span></label>
            </dt>
            
            <dd>
                <?=$this->Form->text('name',array('class'=> 'small','size'=>'45','required'=>false)); ?>
                <?=$this->Form->error('name',null,array('wrap' => 'span', 'class' => 'error-message')); ?>
                  
            </dd>
            
            
        </dl>
    </fieldset>
	<button type="submit">
            <?php 
                if (isset($this->request->data['City']['id']) && $this->request->data['City']['id']):
                    echo __('Update');
                else:
                    echo __('Add');
                endif;								
            ?>
        </button> or 
        <?php echo $this->Html->link('Cancel', array('controller'=>'cities', 'action' => 'index/'.$country_id));?>
                                
	<?php echo $this->Form->end();?>
</div>

 
<script type="text/javascript">
	<?php $path = $this->Html->webroot; ?>
    $(document).ready(function(){
		$('#CityCms').submit(function(){
			
			var data = $(this).serializeArray();
            var formData = $(this);
            var status = 0;
           
           $.each(this,function(i,v){
                $(v).removeClass('invalid form-error');
                });
            $('.error-message').remove();
            $('#CityCms > span#for_owner_cms').show();
            $('#CityCms > button[type=submit]').attr({'disabled':true});
           $.ajax({
                url: '<?=$path?>location_manager/cities/validation',
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
               $('#CityCms > button[type=submit]').attr({'disabled':false});
               $('#CityCms > span#for_owner_cms').hide();
            }
           
          
          
           return (status===1)?true:false; 
            
        });
        
        
    });
 </script>
