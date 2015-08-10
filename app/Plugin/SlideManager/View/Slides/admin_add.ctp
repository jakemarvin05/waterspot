<script language="javascript">
function saveform() {
	document.getElementById('SlidePublish').value=1;
	document.getElementById('Slide').submit();
}
</script>
<div>
    <article>
        <header>
            <h2>
                <?php
                    if (isset($this->request->data['Slide']['id']) && $this->request->data['Slide']['id']):
                          echo  __('Update Slide');
                    else:
                          echo  __('Add Slide');
                    endif;
                ?>
            </h2>
        </header>
    </article>
	
    <?php echo $this->element('admin/message');?>
    <?php echo $this->Form->create('Slide',array('name'=>'slides','id'=>'Slide','action'=>'add' ,'onsubmit'=>'//return validatefields();','type'=>'file'))?>
    <?php echo $this->Form->input('id');?>
	<?php echo $this->Form->hidden('status');?>    
    <?=$this->Form->hidden('redirect', array('value' => $url)); ?>
    <fieldset>
        <dl>
            
            
            <dt>
                <label>Slide Name <span style="color:red;">*</span></label>
            </dt>
            
            <dd>
                <?=$this->Form->text('name',array('class'=> 'small','size'=>'45','required'=>false)); ?>
                  
            </dd>
             <dt>
                <label>Image <span style="color:red;">*</span></label>
            </dt>
            
            <dd>
                <?php echo $this->Form->file('image', array('class'=> 'fileupload customfile-input')); ?>
				 <p style="padding-bottom:15px;">(Only png, gif, jpg, jpeg types are allowed. Dimensions should be 1456 X 600)</p>
				 <span id="image_error"></span>
				<?php 
				/* Resize Image */
					if(isset($this->data['Slide']['image'])) {
						$imgArr = array('source_path'=>Configure::read('Slide.SourcePath'),'img_name'=>$this->data['Slide']['image'],'width'=>80,'height'=>80,'noimg'=>$setting['site']['site_noimage']);
						$resizedImg = $this->ImageResize->ResizeImage($imgArr);
						echo $this->Html->image($resizedImg,array('border'=>'0'));
					}
					?>
			 </dd>
            <dt>
                <label>Text 1 </label>
            </dt>
            
            <dd>
                <?=$this->Form->text('text1',array('class'=> 'small','size'=>'45','required'=>false)); ?>
                  
            </dd>
            <dt>
                <label>Text 2 </label>
            </dt>
            
            <dd>
                <?=$this->Form->text('text2',array('class'=> 'small','size'=>'45','required'=>false)); ?>
                  
            </dd>
             
            
            
            
        </dl>
    </fieldset>
	<button type="submit">
            <?php 
                if (isset($this->request->data['Slide']['id']) && $this->request->data['Slide']['id']):
                    echo __('Update');
                else:
                    echo __('Add');
                endif;								
            ?>
        </button> or 
        <?php echo $this->Html->link('Cancel', array('controller'=>'slides', 'action' => 'index'));?>
                                
	<?php echo $this->Form->end();?>
</div>
<script type="text/javascript">
	 <?php $path = $this->Html->webroot; ?>
    $(document).ready(function(){
		$('#Slide').submit(function(){
			
			//var data = $(this).serializeArray();
			var data = new FormData(this);
			var formData = $(this);
            var status = 0;
           
           $.each(this,function(i,v){
                $(v).removeClass('invalid form-error');
                });
            $('.error-message').remove();
            $('#Slide > span#for_owner_cms').show();
            $('#Slide > button[type=submit]').attr({'disabled':true});
           
           $.ajax({
                url: '<?=$path?>slide_manager/slides/validation',
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
							if(i=="SlideImage"){
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
               $('#Slide > button[type=submit]').attr({'disabled':false});
               $('#Slide > span#for_owner_cms').hide();
            }
            return (status===1)?true:false; 
            
        });
    });
 </script>
