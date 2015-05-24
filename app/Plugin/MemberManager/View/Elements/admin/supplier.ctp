<?=$this->element('admin/breadcrumbs');?>

<div>
        <article>
		<header>
                        <h2>
                        <?php if (isset($this->request->data['Supplier']['id'])):
					echo 'Update Supplier';
				else:
					echo 'Add Supplier';
				endif;
			?>
                        </h2>
                </header>
        </article>
	<?php echo $this->element('admin/message'); ?>
	
	<?php 
		if(isset($this->request->data['Supplier']['id']))$act='edit';
		else $act='add';
		$act=$act.'/'.$id;
	?>
		<?php echo $this->Form->create('Supplier',array('name'=>'suppliers','type'=>'file','id'=>'SupplierCms','action'=>$act));?>
		<?php echo $this->Form->hidden('id');?>
		<?php 	if(isset($this->request->data['Supplier']['profile_complete']))
				echo $this->Form->hidden('profile_complete');
			else
				echo $this->Form->hidden('profile_complete',array('value'=>'0'));
		?>
		
		<?php 	if(isset($this->request->data['Supplier']['active']))
				echo $this->Form->hidden('active');
			else
				echo $this->Form->hidden('active',array('value'=>'1'));
		?>
								
		<fieldset>
			<dl>
				<dt>
					<label>First Name <span style="color:red;">*</span></label>
				</dt>
				<?php $error_class= ($this->Form->isFieldError('first_name'))?'invalid':''; ?>
				<dd>
					<?php echo $this->Form->text('first_name',array('class'=> 'small '.$error_class,'size'=>'45')); ?>
					<?php if($this->Form->isFieldError('first_name')): ?>
						<span class="error-message"><?php echo __($this->Form->error('first_name',null,array('wrap'=>false))); ?></span>
					<?php endif; ?>
				</dd>
				
				<dt>
					<label>Last Name <span style="color:red;">*</span></label>
				</dt>
				<?php $error_class= ($this->Form->isFieldError('last_name'))?'invalid':''; ?>
				<dd>
					<?php echo $this->Form->text('last_name',array('class'=> 'small '.$error_class,'size'=>'45')); ?>
					<?php if($this->Form->isFieldError('last_name')): ?>
						<span class="error-message"><?php echo __($this->Form->error('last_name',null,array('wrap'=>false))); ?></span>
					<?php endif; ?>
				</dd>
				
				<dt>
					<label>Email Address <span style="color:red;">*</span></label>
				</dt>
				<?php $error_class= ($this->Form->isFieldError('email'))?'invalid':''; ?>
				<dd>
					<?php echo $this->Form->text('email',array('class'=> 'small '.$error_class,'size'=>'45')); ?>
					<?php if($this->Form->isFieldError('email')): ?>
						<span class="error-message"><?php echo __($this->Form->error('email',null,array('wrap'=>false))); ?></span>
				<?php endif; ?>
				</dd>
				
				<?php /*//if(!isset($this->request->data['Supplier']['id']) or !$this->request->data['Supplier']['id']){ ?>
				<dt >
					<label>Password <span style="color:red;">*</span></label>
				</dt>
				<?php $error_class= ($this->Form->isFieldError('password'))?'invalid':''; ?>
				<dd >
					<?php echo $this->Form->password('password',array('class'=> 'small '.$error_class,'size'=>'45','readonly'=>true)); ?>
					<?php if($this->Form->isFieldError('password')): ?>
						<span class="error-message"><?php echo __($this->Form->error('password',null,array('wrap'=>false))); ?></span>
				<?php endif; ?>
				</dd>
				
				<dt >
					<label>Confirm Password <span style="color:red;">*</span></label>
				</dt>
				<?php $error_class= ($this->Form->isFieldError('confirm_password'))?'invalid':''; ?>
				<dd >
					<?php echo $this->Form->password('confirm_password',array('class'=> 'small '.$error_class,'size'=>'45','readonly'=>true)); ?>
					<?php if($this->Form->isFieldError('confirm_password')): ?>
						<span class="error-message"><?php echo __($this->Form->error('confirm_password',null,array('wrap'=>false))); ?></span>
				<?php endif; ?>
				</dd>
				<?php //} */?>
				
				<dt>
					<label>Company Name <span style="color:red;">*</span></label>
				</dt>
				<?php $error_class= ($this->Form->isFieldError('company_name'))?'invalid':''; ?>
				<dd>
					<?php echo $this->Form->text('company_name',array('class'=> 'small '.$error_class,'size'=>'45')); ?>
					<?php if($this->Form->isFieldError('company_name')): ?>
						<span class="error-message"><?php echo __($this->Form->error('company_name',null,array('wrap'=>false))); ?></span>
				<?php endif; ?>
				</dd>
				
				<dt>
					<label>ABN <span style="color:red;">*</span></label>
				</dt>
				<?php $error_class= ($this->Form->isFieldError('abn'))?'invalid':''; ?>
				<dd>
					<?php echo $this->Form->text('abn',array('class'=> 'small '.$error_class,'size'=>'45')); ?>
					<?php if($this->Form->isFieldError('abn')): ?>
						<span class="error-message"><?php echo __($this->Form->error('abn',null,array('wrap'=>false))); ?></span>
				<?php endif; ?>
				</dd>
				
				<?php /*?><dt>
					<label>License Number <span style="color:red;">*</span></label>
				</dt>
				<?php $error_class= ($this->Form->isFieldError('license_no'))?'invalid':''; ?>
				<dd>
					<?php echo $this->Form->text('license_no',array('class'=> 'small '.$error_class,'size'=>'45')); ?>
					<?php if($this->Form->isFieldError('license_no')): ?>
						<span class="error-message"><?php echo __($this->Form->error('license_no',null,array('wrap'=>false))); ?></span>
				<?php endif; ?>
				</dd><?php */?>
				
				<dt>
					<label>Website <span style="color:red;"></span></label>
				</dt>
				<?php $error_class= ($this->Form->isFieldError('website'))?'invalid':''; ?>
				<dd>
					<?php echo $this->Form->text('website',array('class'=> 'small '.$error_class,'size'=>'45')); ?>
					<?php if($this->Form->isFieldError('website')): ?>
						<span class="error-message"><?php echo __($this->Form->error('website',null,array('wrap'=>false))); ?></span>
				<?php endif; ?>
				</dd>
				
				<dt>
					<label>Address <span style="color:red;">*</span></label>
				</dt>
				<?php $error_class= ($this->Form->isFieldError('address'))?'invalid':''; ?>
				<dd>
					<?php echo $this->Form->textarea('address',array('class'=>'small '.$error_class,'style'=>'height:100px;width:300px')); ?>
					<?php if($this->Form->isFieldError('address')): ?>
						<span class="error-message"><?php echo __($this->Form->error('address',null,array('wrap'=>false))); ?></span>
				<?php endif; ?>
				</dd>
				
				<dt>
					<label>Suburb <span style="color:red;">*</span></label>
				</dt>
				<?php $error_class= ($this->Form->isFieldError('suburb'))?'invalid':''; ?>
				<dd>
					<?php echo $this->Form->text('suburb',array('class'=> 'small '.$error_class,'size'=>'45')); ?>
					<?php if($this->Form->isFieldError('suburb')): ?>
						<span class="error-message"><?php echo __($this->Form->error('suburb',null,array('wrap'=>false))); ?></span>
				<?php endif; ?>
				</dd>
				
				<dt>
					<label>City <span style="color:red;">*</span></label>
				</dt>
				<?php $error_class= ($this->Form->isFieldError('city'))?'invalid':''; ?>
				<dd>
					<?php echo $this->Form->text('city',array('class'=> 'small '.$error_class,'size'=>'45')); ?>
					<?php if($this->Form->isFieldError('city')): ?>
						<span class="error-message"><?php echo __($this->Form->error('city',null,array('wrap'=>false))); ?></span>
				<?php endif; ?>
				</dd>
				
				<dt>
					<label>State <span style="color:red;">*</span></label>
				</dt>
				<?php $error_class= ($this->Form->isFieldError('state'))?'invalid':''; ?>
				<dd>
					<?php echo $this->Form->text('state',array('class'=> 'small '.$error_class,'size'=>'45')); ?>
					<?php if($this->Form->isFieldError('state')): ?>
						<span class="error-message"><?php echo __($this->Form->error('state',null,array('wrap'=>false))); ?></span>
				<?php endif; ?>
				</dd>
				
				<dt>
					<label>Post Code <span style="color:red;">*</span></label>
				</dt>
				<?php $error_class= ($this->Form->isFieldError('postcode'))?'invalid':''; ?>
				<dd>
					<?php echo $this->Form->text('postcode',array('class'=> 'small '.$error_class,'size'=>'45')); ?>
					<?php if($this->Form->isFieldError('postcode')): ?>
						<span class="error-message"><?php echo __($this->Form->error('postcode',null,array('wrap'=>false))); ?></span>
				<?php endif; ?>
				</dd>
				
				<dt>
					<label>Phone Number <span style="color:red;">*</span></label>
				</dt>
				<?php $error_class= ($this->Form->isFieldError('phone_no'))?'invalid':''; ?>
				<dd>
					<?php echo $this->Form->text('phone_no',array('class'=> 'small '.$error_class,'size'=>'45')); ?>
					<?php if($this->Form->isFieldError('phone_no')): ?>
						<span class="error-message"><?php echo __($this->Form->error('phone_no',null,array('wrap'=>false))); ?></span>
				<?php endif; ?>
				</dd>
				
				<?php if($status!='1'){ ?>
				<dt>
					<label>Approve </label>
				</dt>
				<dd>
					<?php echo $this->Form->checkbox('approve_status',array('class'=> '','size'=>'45')); ?>
					<?php //echo $this->Form->hidden('approve_status',array('value'=>$status)); ?>
				</dd>
				<?php } else { ?>
				<?php echo $this->Form->hidden('status'); ?>
				<?php } ?>
				
			</dl>
                                   
		</fieldset>
							
				<button type="submit">
                                <?php 			
					if (isset($this->request->data['Supplier']['id'])):
						echo 'Update';
					else:
						echo 'Add';
					endif;
				?>
                                </button> or 
                <?php echo $this->Html->link('Cancel', array('action' => 'index'));?>
                                
		<?php echo $this->Form->end();?>
</div>
 
 <script type="text/javascript">
    $(document).ready(function() {
        $('#SupplierCms').submit(function() {
             var form_obj = this;
             var data = new FormData(this);
             var status = 0;
             $.each(this, function(i, v) {
                $(v).removeClass('invalid form-error');

            });
            
           
            $('.error-message , .notification').remove();
            $(form_obj).find('span#for_supplier_cms').show();
            $(form_obj).find('button[type=submit]').attr({'disabled': true});
		
            $.ajax({
                url: '<?=Configure::read('HTTP_PATH');?>supplier_manager/suppliers/ajax_check_validation',
                async: false,
                processData: false,
                contentType: false,
                data: data,
                dataType: 'json',
                type: 'post',
                success: function(data) {
			
                    if (data.error==1) {
                        onError(data);
                    } else {
                        status = 1;
                    }


                    //alert(data.error);
                }


            });
            if (status == 0) {
                $("html, body").animate({scrollTop: 0}, "slow");
                $(form_obj).find('span#for_page_cms').hide();
                $(form_obj).find('button[type=submit]').attr({'disabled': false});
            }
            //return true;
            return (status === 1) ? true : false;

        });
            function camelize(string) {
                var a = string.split('_'), i;
                //var model = "Owner";
                s = [];
                for (i = 0; i < a.length; i++) {
                    s.push(a[i].charAt(0).toUpperCase() + a[i].substring(1));
                }
                s = s.join('');

                return s;
            }
        function onError(data) {
            //flashMessage(data.message);
            $.each(data.errors, function(model, errors) {

                for (fieldName in this) {

                    $('#' + camelize(model + '_' + fieldName)).addClass("invalid form-error").after('<span class="error-message">' + this[fieldName] + '</span>');

                }
                //_loadingDiv.hide();

            });
            $('#SupplierCms').before('<div class="notification error"><a class="close-notification" rel="tooltip" title="Hide Notification" href="#">x</a><p></p><div id="errorMessage" class="message">' + data.message + '</div><p></p></div>');
            $('.close-notification').click(
                    function() {
                        $(this).parent().fadeTo(350, 0, function() {
                            $(this).slideUp(600);
                        });
                        return false;
                    }
            );
        }


    });
</script>
