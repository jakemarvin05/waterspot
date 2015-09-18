 
<script language="javascript">
function saveform()
{
	document.getElementById('ServiceTypePublish').value=1;
	document.getElementById('ServiceType').submit();
}
</script>

<div>
    <article>
        <header>
            <h2>
                <?php
                    if (isset($this->request->data['ServiceType']['id']) && $this->request->data['ServiceType']['id']):
                          echo  __('Update Service Type');
                    else:
                          echo  __('Add Service Type');
                           $this->request->data['ServiceType']['status']=1;
                    endif;
                ?>
            </h2>
        </header>
    </article>
	
    <?php echo $this->element('admin/message');?>
    <?php echo $this->Form->create('ServiceType',array('name'=>'servicetype','id'=>'ServiceType','action'=>'add','onsubmit'=>'//return validatefields();','type'=>'file'))?>
    <?php echo $this->Form->input('id');?>
    <?=$this->Form->hidden('redirect', array('value' => $url)); ?>
     <?=$this->Form->hidden('status'); ?>
    <fieldset>
        <dl>
			 
            <dt>
                <label>Name<span style="color:red;">*</span></label>
            </dt>
            <dd>
				<?=$this->Form->text('name',array('class'=> 'small','size'=>'45','required'=>false)); ?>                 
            </dd>
           
            <dt>
                <label>Image <span style="color:red;">*</span></label>
            </dt>
            
            <dd>
                <?php echo $this->Form->file('image', array('class'=> 'fileupload customfile-input')); ?>
				 <p style="padding-bottom:15px;">(Only png, gif, jpg, jpeg types are allowed. Dimensions should be 294 X 186)</p>
				 <span id="image_error"></span>
				<?php 
				/* Resize Image */
					if(isset($this->data['ServiceType']['image'])) {
						$imgArr = array('source_path'=>Configure::read('Image.SourcePath'),'img_name'=>$this->data['ServiceType']['image'],'width'=>110,'height'=>80,'noimg'=>$setting['site']['site_noimage']);
						
						$resizedImg = $this->ImageResize->ResizeImage($imgArr);
						echo $this->Html->image($resizedImg,array('border'=>'0'));
					}
					?>
			 </dd>

            <!--  <dt>
                <label>Youtube Url </label>
            </dt>
            <dd>
                <?=$this->Form->text('youtube_url',array('class'=> 'small','size'=>'45','required'=>false)); ?>                 
            </dd> -->
            
<!--
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
-->
            
             <dt>
                <label> Short Description 
                
                </label>
            </dt>
            
            <dd>
                 <?php echo $this->Form->textarea('short_description', array('cols' => '40', 'rows' => '3','required'=>false));?>
            </dd>
             <dt>
                <label> Description</label>
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
                if (isset($this->request->data['ServiceType']['id']) && $this->request->data['ServiceType']['id']):
                    echo __('Update');
                else:
                    echo __('Add');
                endif;								
            ?>
        </button> or 
        <?php echo $this->Html->link('Cancel', array('controller'=>'service_types', 'action' => 'index'));?>
                                
	<?php echo $this->Form->end();?>
</div>

 


<script type="text/javascript">
	<?php $path = $this->Html->webroot; ?>
    $(document).ready(function(){
		$('#ServiceType').submit(function(){
			
			//var data = $(this).serializeArray();
			var data = new FormData(this);
            var formData = $(this);
            var status = 0;
           
           $.each(this,function(i,v){
                $(v).removeClass('invalid form-error');
                });
            $('.error-message').remove();
            $('#ServiceType > span#for_owner_cms').show();
            $('#ServiceType > button[type=submit]').attr({'disabled':true});
           
           $.ajax({
                url: '<?=$path?>service_manager/service_types/validation',
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
							if(i=="ServiceTypeImage"){
								$('#image_error').html('<span class="error-message">'+v+'</span>');
							}else{
								$('#'+i).addClass("invalid form-error").after('<span class="error-message">'+v+'</span>');
							}
                        });
                       
                    }else{
                        status = 1;
                    }
                   
                   }


            });
            if(status==0){
               $("html, body").animate({ scrollTop: 0 }, "slow");
               $('#ServiceType > button[type=submit]').attr({'disabled':false});
               $('#ServiceType > span#for_owner_cms').hide();
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

