<div>
	<article>
		<header>
			<h2>Fee Amount For Vendor</h2>
		</header>
	</article>
	<?=$this->element('admin/message');?>
	<?=$this->Form->create('Setting',array('name'=>'setting','id'=>'SalesCommision','url'=>array('controller'=>'settings','action'=>'admin_fees'),'type' => 'file','novalidate'=>true ))?>
	<fieldset>
		<dl>
			<dt>
				<label>Vendor Sales Commission Amount ($)<span style="color:red;">*</span></label>
			</dt>
			<dd>
				<?=$this->Form->input('sales_commission_amount',array('div'=>false,'label'=>false));?>
			</dd>
		
		</dl>

    </fieldset>
    <button type="submit"><?=__('Save');?></button>
		 &nbsp;<?php echo $this->Html->link('Cancel', array('action' => 'home','plugin'=>false,'controller'=>false));?>
         <?=$this->Form->end();?>
    </div>

<script type="text/javascript">
	<?php $path = $this->Html->webroot; ?>
    $(document).ready(function(){
		$('#SalesCommision').submit(function(){
			
			var data = $(this).serializeArray();
            var formData = $(this);
            var status = 0;
           
           $.each(this,function(i,v){
                $(v).removeClass('invalid form-error');
                });
            $('.error-message').remove();
            $('#SalesCommision > span#for_owner_cms').show();
            $('#SalesCommision > button[type=submit]').attr({'disabled':true});
           $.ajax({
                url: '<?=$path?>vendor_manager/settings/validation',
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
               $('#SalesCommision > button[type=submit]').attr({'disabled':false});
               $('#SalesCommision > span#for_owner_cms').hide();
            }
          
           return (status===1)?true:false; 
            
        });
    });
 </script>
