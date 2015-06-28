
<?php $paypalmode=array('0'=>'No','1'=>'Yes');?>
<div>
    <article>
        <header>
            <h2>
                <?php
                    if (isset($this->request->data['Setting']['id']) && $this->request->data['Setting']['id']):
                          echo  __('Update Paypal Setting');
                    else:
                          echo  __('Add Paypal Setting');
                    endif;
                ?>
            </h2>
        </header>
    </article>
	
    <?php echo $this->element('admin/message');?>
    <?=$this->Form->create('Setting',array('name'=>'setting','id'=>'PaypalSetting','url'=>array('controller'=>'settings','action'=>'paypalsetting'),'type' => 'file','novalidate'=>true))?>
    <fieldset>
        <dl>
            <dt>
                <label>E-Mail Associated (Business) With Paypal <span style="color:red;">*</span></label>
            </dt>
            <dd>
                <?=$this->Form->text('business_email_paypal',array('class'=> 'small','size'=>'70','required'=>false)); ?>
                <?=$this->Form->error('business_email_paypal',null,array('wrap' => 'span', 'class' => 'error-message')); ?>  
            </dd>

            <dt>
                <label>Test Mode</label>
            </dt>
                
            <dd>
				<?=$this->Form->input('sandbox_mode',array('class'=>'smail','options'=>$paypalmode,'label'=>false));?>
            </dd>
            
        </dl>
    </fieldset>
	<button type="submit"><?=__('Save');?></button>
	 or 
        <?php echo $this->Html->link('Cancel', array('action' => 'home','plugin'=>false,'controller'=>false));?>
                                
	<?php echo $this->Form->end();?>
</div>

<script type="text/javascript">
	<?php $path = $this->Html->webroot; ?>
    $(document).ready(function(){
		$('#PaypalSetting').submit(function(){
			var data = $(this).serializeArray();
            var formData = $(this);
            var status = 0;
			$.each(this,function(i,v){
                $(v).removeClass('invalid form-error');
                });
            $('.error-message').remove();
            $('#PaypalSetting > span#for_owner_cms').show();
            $('#PaypalSetting > button[type=submit]').attr({'disabled':true});
			$.ajax({
                url: '<?=$path?>settings/paypal_validation',
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
               $('#PaypalSetting > button[type=submit]').attr({'disabled':false});
               $('#PaypalSetting > span#for_owner_cms').hide();
            }
           return (status===1)?true:false; 
            
        });
        
        
    });
 </script>


 
