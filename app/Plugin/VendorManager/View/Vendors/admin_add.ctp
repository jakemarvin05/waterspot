<script language="javascript">
function saveform()
{
	document.getElementById('VendorPublish').value=1;
	document.getElementById('Vendor').submit();
}
</script>

	<div>
    <article>
        <header>
            <h2>
                <?php
                    if (isset($this->request->data['Vendor']['id']) && $this->request->data['Vendor']['id']):
                          echo  __('Update Vendor');
                    else:
                          echo  __('Add Vendor');
                    endif;
                ?>
            </h2>
        </header>
    </article>
	
    <?php echo $this->element('admin/message');?>
    <?php echo $this->Form->create('Vendor',array('name'=>'vendors','id'=>'Vendor','action'=>'add' ,'onsubmit'=>'//return validatefields();','type'=>'file','validation'=>'false'))?>
    <?php echo $this->Form->input('id');?>
    
    <?=$this->Form->hidden('redirect', array('value' => $url)); ?>
    
    <fieldset>
        <dl>
			<?=$this->Form->hidden('form-name',array('class'=> 'small','size'=>'45','required'=>false,'value'=>'Admin-vendor-registration')); ?>
             
             
            <dt>
                <label>Business Name <span style="color:red;">*</span></label>
            </dt>
            
            <dd>
                <?=$this->Form->text('bname',array('class'=> 'small','size'=>'45','required'=>false)); ?>
                <?=$this->Form->error('bname',null,array('wrap' => 'span', 'class' => 'error-message')); ?>
                  
            </dd>
                
            <dt>
                <label>First Name <span style="color:red;">*</span></label>
            </dt>
            
            <dd>
                <?=$this->Form->text('fname',array('class'=> 'small','size'=>'45','required'=>false)); ?>
                <?=$this->Form->error('fname',null,array('wrap' => 'span', 'class' => 'error-message')); ?>
                  
            </dd>
            <dt>
                <label>Last Name <span style="color:red;">*</span></label>
            </dt>
            <dd>
                <?=$this->Form->text('lname',array('class'=> 'small','size'=>'45','required'=>false)); ?>
                <?=$this->Form->error('lname',null,array('wrap' => 'span', 'class' => 'error-message')); ?>
                  
            </dd>
            <dt>
                <label>About Us</label>
            </dt>
            <dd>
                <?=$this->Form->textarea('about_us',array('size'=>'45','required'=>false)); ?>
                <?=$this->Form->error('about_us',null,array('wrap' => 'span', 'class' => 'error-message')); ?>
                <div class="float_left">
					<a href="Javascript:void(0);" onclick="removeeditor(2)">hide editor</a> |
				<a href="Javascript:void(0);" onclick="addeditor(1,'VendorAboutUs')">show editor</a>
					 
                </div>
            </dd>
            
             <dt>
                <label>E-Mail ID <span style="color:red;">*</span></label>
            </dt>
            
            <dd>
                <?=$this->Form->text('email',array('class'=> 'small','size'=>'45','required'=>false)); ?>
                <?=$this->Form->error('email',null,array('wrap' => 'span', 'class' => 'error-message')); ?>
                  
            </dd>
             <dt>
                <label>Contact No. <span style="color:red;">*</span></label>
            </dt>
            
            <dd>
                <?=$this->Form->text('phone',array('class'=> 'small','size'=>'45','required'=>false)); ?>
                <?=$this->Form->error('phone',null,array('wrap' => 'span', 'class' => 'error-message')); ?> 
                  
            </dd>
             <dt>
                <label>Account Type  <span style="color:red;">*</span></label>
            </dt>
            
             <dd>
				 <? $options = array('0' => '<span>Free</span>','1'=> '<span>Chargeable</span>');
					$attributes = array(
						'legend' => false,
						'label' => true,
						'id' => 'VendorAccountType',
						'onclick'=>'togelshow(this.id)'
					);
					echo $this->Form->radio('account_type',$options, $attributes);
				?>
				<span id="VendorAccountType"></span>
			</dd>
            <div id="show_payment_amount" style="display:none;">
				<dt>
					<label>Payment Amount<span style="color:red;">*</span></label>
				</dt>
				
				<dd>
					<?=$this->Form->text('payment_amount',array('class'=> 'small','size'=>'45','required'=>false)); ?>
					<?=$this->Form->error('payment_amount',null,array('wrap' => 'span', 'class' => 'error-message')); ?> 
					  
				</dd>
            </div>
            <dt>
				<label>Commission(%)</label>
			</dt>
				
			<dd>
				<?=$this->Form->text('commission',array('class'=> 'small','size'=>'45','required'=>false)); ?>
				<?=$this->Form->error('commission',null,array('wrap' => 'span', 'class' => 'error-message')); ?> 
					  
			</dd>
            <dt>
                <label>Profile Image</label>
            </dt>
            
            <dd>
               <?=$this->Form->file('image', array('class'=> 'fileupload customfile-input')); ?>
               <p style="padding-bottom:15px;">(Only png, gif, jpg, jpeg types are allowed. Profile pic should be 290x230 resolution)</p>
				 <span id="image_error"></span> 
				<?php 
				/* Resize Image */
					if(isset($this->data['Vendor']['image'])) {
						$imgArr = array('source_path'=>Configure::read('VendorProfile.SourcePath'),'img_name'=>$this->data['Vendor']['image'],'width'=>80,'height'=>80,'noimg'=>$setting['site']['site_noimage']);
						$resizedImg = $this->ImageResize->ResizeImage($imgArr);
						echo $this->Html->image($resizedImg,array('border'=>'0'));
					}
					?>
                  
            </dd>
             
            
            
        </dl>
    </fieldset>
	<button type="submit">
            <?php 
                if (isset($this->request->data['Vendor']['id']) && $this->request->data['Vendor']['id']):
                    echo __('Update');
                else:
                    echo __('Add');
                endif;								
            ?>
        </button> or 
        <?php echo $this->Html->link('Cancel', array('controller'=>'vendors', 'action' => 'index'));?>
                                
	<?php echo $this->Form->end();?>
</div>
<script type="text/javascript">
	 <?php $path = $this->Html->webroot; ?>
    $(document).ready(function(){
		var account_type_id=$("input[type=radio]:checked").attr('id');
		togelshow(account_type_id);
		$('#Vendor').submit(function(){
			
			//var data = $(this).serializeArray();
			var data = new FormData(this);
			var formData = $(this);
            var status = 0;
           
           $.each(this,function(i,v){
                $(v).removeClass('invalid form-error');
                });
            $('.error-message').remove();
            $('#Vendor > span#for_owner_cms').show();
            $('#Vendor > button[type=submit]').attr({'disabled':true});
           
           $.ajax({
                url: '<?=$path?>vendor_manager/vendors/validation',
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
               $('#Vendor > button[type=submit]').attr({'disabled':false});
               $('#Vendor > span#for_owner_cms').hide();
            }
           
          
			
           return (status===1)?true:false; 
            
        });
        
        
    });
    
    function togelshow(id){
		if(id=='VendorAccountType1'){
			$("#show_payment_amount").show();
		}else{
			$("#show_payment_amount").hide();
		}
	}
 </script>
 
 <script type="text/javascript">
     <?php $path = $this->Html->webroot; ?>
     var fckeditor = new Array;
	  addeditor(0,'VendorAboutUs'); 
      
     
     function removeeditor(id){
         fckeditor[id].destroy();
     }
      
     function addeditor(id,name){
         fckeditor[id] = CKEDITOR.replace(name,{
                                language : 'eng',
                                uiColor : '#e6e6e6',
                                toolbar : 'Basic',
                                customConfig : '',
                                filebrowserBrowseUrl : '<?=$path?>js/ckfinderckfinder.html',
                                filebrowserImageBrowseUrl : '<?=$path?>js/ckfinder/ckfinder.html',
                                filebrowserUploadUrl : '<?=$path?>js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                                filebrowserImageUploadUrl : '<?=$path?>js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files'
                        });
     }
     
     
     
    </script>
