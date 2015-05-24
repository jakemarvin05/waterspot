 
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
                          echo  __('Approve Vendor');
                    endif;
                ?>
            </h2>
        </header>
    </article>
	
    <?php echo $this->element('admin/message');?>
    <?php echo $this->Form->create('Vendor',array('name'=>'vendors','id'=>'VendorApprove','action'=>'approval' ,'onsubmit'=>'//return validatefields();','type'=>'file'))?>
    <?php echo $this->Form->input('id');?>
    
    <?=$this->Form->hidden('redirect', array('value' => $url)); ?>
    <?=$this->Form->hidden('form-name',array('class'=> 'small','size'=>'45','required'=>false,'value'=>'Admin-vendor-registration')); ?>
    <fieldset>
        <dl>
             <dt>
                <label>Business Name <span style="color:red;">*</span></label>
            </dt>
            
            <dd>
                <?=$this->Form->text('bname',array('class'=> 'small','size'=>'45','required'=>false,'disabled'=> 'disabled')); ?>
                  
            </dd>
            <dt>
                <label>First Name <span style="color:red;">*</span></label>
            </dt>
            
            <dd>
                <?=$this->Form->text('fname',array('class'=> 'small','size'=>'45','required'=>false,'disabled'=> 'disabled')); ?>
                  
            </dd>
             <dt>
                <label>Last Name <span style="color:red;">*</span></label>
            </dt>
            
            <dd>
                <?=$this->Form->text('lname',array('class'=> 'small','size'=>'45','required'=>false,'disabled'=> 'disabled')); ?>
                  
            </dd>
             <dt>
                <label> About Us </label>
            </dt>
            
            <dd>
                <?=$this->Form->textarea('about_us',array('class'=> 'small','size'=>'45','required'=>false,'disabled'=> 'disabled')); ?>
                  
            </dd>
             <dt>
                <label>E-Mail ID <span style="color:red;">*</span></label>
            </dt>
            
            <dd>
                <?=$this->Form->text('email',array('class'=> 'small','size'=>'45','required'=>false,'disabled'=> 'disabled')); ?>
                  
            </dd>
             <dt>
                <label>Contact No. <span style="color:red;">*</span></label>
            </dt>
            
            <dd>
                <?=$this->Form->text('phone',array('class'=> 'small','size'=>'45','required'=>false,'disabled'=> 'disabled')); ?>
                  
            </dd>
             <dt>
                <label>Account Type  <span style="color:red;">*</span></label>
            </dt>
            
             <dd>
				 <? $options = array('0' => '<span>Free</span>','1'=> '<span>Chargeable</span>');
					$attributes = array(
						'legend' => false,
						'label' => true,
						'onclick'=>'togelshow(this.id)'
					);
					echo $this->Form->radio('account_type',$options, $attributes);
				?>
				<?=$this->Form->error('account_type',null,array('wrap' => 'span', 'class' => 'error-message')); ?> 
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
                <label>Approve Now <span style="color:red;">*</span></label>
            </dt>
            
            <dd>
                <?=$this->Form->checkbox('approval',array('checked'=>'checked','required'=>false,'disabled'=> 'disabled')); ?>
                  
            </dd>
            
            
        </dl>
    </fieldset>
	<button type="submit">
            <?php 
                if (isset($this->request->data['Vendor']['id']) && $this->request->data['Vendor']['id']):
                    echo __('Approve Now');
                endif;								
            ?>
        </button> or 
        <?php echo $this->Html->link('Cancel', array('controller'=>'vendors', 'action' => 'index'));?>
                                
	<?php echo $this->Form->end();?>
</div>

 
<script type="text/javascript">
	 <?php $path = $this->Html->webroot; ?>
    $(document).ready(function(){
		//togelshow('VendorAccountType1);
		var account_type_id=$("input[type=radio]:checked").attr('id');
		togelshow(account_type_id);
		$('#VendorApprove').submit(function(){
			
			//var data = $(this).serializeArray();
			var data = new FormData(this);
			var formData = $(this);
            var status = 0;
           
           $.each(this,function(i,v){
                $(v).removeClass('invalid form-error');
                });
            $('.error-message').remove();
            $('#VendorApprove > span#for_owner_cms').show();
            $('#VendorApprove > button[type=submit]').attr({'disabled':true});
           
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
               $('#VendorApprove > button[type=submit]').attr({'disabled':false});
               $('#VendorApprove > span#for_owner_cms').hide();
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
 
