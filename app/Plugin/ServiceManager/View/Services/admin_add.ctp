 
<script language="javascript">
function saveform()
{
	document.getElementById('ServicePublish').value=1;
	document.getElementById('Service').submit();
}
</script>

<div>
    <article>
        <header>
            <h2>
                <?php
                    if (isset($this->request->data['Service']['id']) && $this->request->data['Service']['id']):
                          echo  __('Update Service');
                    else:
                          echo  __('Add Service');
                    endif;
                ?>
            </h2>
        </header>
    </article>
	
    <?php echo $this->element('admin/message');?>
    <?php echo $this->Form->create('Service',array('name'=>'services','id'=>'Service','action'=>'add','onsubmit'=>'//return validatefields();','type'=>'file'))?>
    <?php echo $this->Form->input('id');?>
    <?=$this->Form->hidden('redirect', array('value' => $url)); ?>
    
    <fieldset>
        <dl>
            <dt>
                <label>Title<span style="color:red;">*</span></label>
            </dt>
            <dd>
                <?=$this->Form->text('title',array('class'=> 'small','size'=>'45','required'=>false)); ?>
                  
            </dd>
            
            <dt>
                <label>Seo Keyword<span style="color:red;">*</span></label>
            </dt>
            <dd>
                <?=$this->Form->text('seo_keyword',array('class'=> 'small','size'=>'45','required'=>false)); ?>
                  
            </dd>
            
            <dt>
                <label>Meta Keyword <span style="color:red;">*</span></label>
            </dt>
            
            <dd>
                <?=$this->Form->text('meta_keyword',array('class'=> 'small','size'=>'45','required'=>false)); ?>
                  
            </dd>
            <dt>
                <label>Meta Description<span style="color:red;">*</span></label>
            </dt>
            
            <dd>
                <?=$this->Form->textarea('meta_description',array('cols' => '40', 'rows' => '3','required'=>false)); ?>
                  
            </dd>
             <dt>
                <label>Service Description<span style="color:red;">*</span></label>
            </dt>
            
            <dd>
                 <?php 	
                    echo $this->Form->textarea('description', array('cols' => '40', 'rows' => '3','required'=>false, 'id'=>'ServiceServiceBody'));
                ?>
                <div class="float_left"><a href="Javascript:void(0);" onclick="removeeditor(1)">hide editor</a> |
                <a href="Javascript:void(0);" onclick="addeditor(1,'ServiceServiceBody')">show editor</a>
                </div>  
            </dd>
           
                     
            
        </dl>
    </fieldset>
	<button type="submit">
            <?php 
                if (isset($this->request->data['Service']['id']) && $this->request->data['Service']['id']):
                    echo __('Update');
                else:
                    echo __('Add');
                endif;								
            ?>
        </button> or 
        <?php echo $this->Html->link('Cancel', array('controller'=>'services', 'action' => 'index'));?>
                                
	<?php echo $this->Form->end();?>
</div>

 


<script type="text/javascript">
	<?php $path = $this->Html->webroot; ?>
    $(document).ready(function(){
		$('#Service').submit(function(){
			
			var data = $(this).serializeArray();
            var formData = $(this);
            var status = 0;
           
           $.each(this,function(i,v){
                $(v).removeClass('invalid form-error');
                });
            $('.error-message').remove();
            $('#Service > span#for_owner_cms').show();
            $('#Service > button[type=submit]').attr({'disabled':true});
           
           $.ajax({
                url: '<?=$path?>service_manager/services/validation',
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
               $('#Service > button[type=submit]').attr({'disabled':false});
               $('#Service > span#for_owner_cms').hide();
            }
           
          
         
           return (status===1)?true:false; 
            
        });
        
        
    });
 </script>
 
  <script type="text/javascript">
	 var fckeditor = new Array;
         
     
     function removeeditor(id){
         fckeditor[id].destroy();
     }
     
     function addeditor(id,name){
         fckeditor[id] = CKEDITOR.replace(name,{
                                language : 'eng',
                                uiColor : '#e6e6e6',
                                toolbar : 'Basic',
                                customConfig : '../editor.js',
                                filebrowserBrowseUrl : '<?=$path?>js/ckfinderckfinder.html',
                                filebrowserImageBrowseUrl : '<?=$path?>js/ckfinder/ckfinder.html',
                                filebrowserUploadUrl : '<?=$path?>js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                                filebrowserImageUploadUrl : '<?=$path?>js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files'
                        });
     }
     
     
    </script>

