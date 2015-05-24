<script language="javascript">
function saveform()
{
	document.getElementById('ServiceReviewPublish').value=1;
	document.getElementById('ServiceReview').submit();
}
</script>

	<div>
    <article>
        <header>
            <h2>
                <?php
                    if (isset($this->request->data['ServiceReview']['id']) && $this->request->data['ServiceReview']['id']):
                          echo  __('Update ServiceReview');
                    else:
                          echo  __('Add ServiceReview');
                    endif;
                ?>
            </h2>
        </header>
    </article>
	
    <?php echo $this->element('admin/message');?>
    <?php echo $this->Form->create('ServiceReview',array('name'=>'vendors','id'=>'ServiceReview','action'=>'add','onsubmit'=>'//return validatefields();','type'=>'file'))?>
    <?php echo $this->Form->input('id');?>
    <?php echo $this->Form->hidden('vendor_id');?>
    <?php echo $this->Form->hidden('service_id');?>
    
    <?=$this->Form->hidden('redirect', array('value' => $url)); ?>
    
    <fieldset>
        <dl>
			    
            <dt>
                <label>Service Name <span style="color:red;">*</span></label>
            </dt>
            
            <dd>
                 <?=$this->request->data['Service']['service_title']?>
                  
            </dd>
             <dt>
                <label>Member Name <span style="color:red;">*</span></label>
            </dt>
            
            <dd>
               <?php echo ucfirst($this->request->data['Member']['first_name']." ".$this->request->data['Member']['last_name']); ?>
                  
            </dd>
             <dt>
                <label>Vendor Name <span style="color:red;">*</span></label>
            </dt>
            
            <dd>
              <?php echo ucfirst($this->request->data['Vendor']['fname']." ".$this->request->data['Vendor']['lname']); ?>
                  
            </dd>
             <dt>
                <label>Message. <span style="color:red;">*</span></label>
            </dt>
            
            <dd>
                <?=$this->Form->textarea('message',array('required'=>false,"style"=>"height:100px;width:300px")); ?>
                <?=$this->Form->error('message',null,array('cols'=>'40','rows'=>'3','wrap' => 'span', 'class' => 'error-message')); ?> 
                   
            </dd>
              
            
            
        </dl>
    </fieldset>
	<button type="submit">
            <?php 
                if (isset($this->request->data['ServiceReview']['id']) && $this->request->data['ServiceReview']['id']):
                    echo __('Update');
                else:
                    echo __('Add');
                endif;								
            ?>
        </button> or <a href="<?php echo $url;?>">Cancel</a>
        
                               
	<?php echo $this->Form->end();?>
</div>
<script type="text/javascript">
	 <?php $path = $this->Html->webroot; ?>
    $(document).ready(function(){
		$('#ServiceReview').submit(function(){
			
			//var data = $(this).serializeArray();
			var data = new FormData(this);
			var formData = $(this);
            var status = 0;
           
           $.each(this,function(i,v){
                $(v).removeClass('invalid form-error');
                });
            $('.error-message').remove();
            $('#ServiceReview > span#for_owner_cms').show();
            $('#ServiceReview > button[type=submit]').attr({'disabled':true});
           
           $.ajax({
                url: '<?=$path?>vendor_manager/service_reviews/validation',
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
               $('#ServiceReview > button[type=submit]').attr({'disabled':false});
               $('#ServiceReview > span#for_owner_cms').hide();
            }
           
          
			
           return (status===1)?true:false; 
            
        });
        
        
    });
 </script>
