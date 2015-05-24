<script language="javascript">
function validatefields(val)
{
	if(document.getElementById('GalleryName').value==''){
		alert("Please Enter The Title");
		document.getElementById('GalleryName').focus();
		return false;
	}
	if(document.getElementById('GalleryGalleryTitle').value==''){
		alert("Please Enter The Gallery Title");
		document.getElementById('GalleryGalleryTitle').focus();
		return false;
	}
	
}
function saveform()
{
	document.getElementById('GalleryPublish').value=1;
	document.getElementById('GalleryCms').submit();
}
</script>

<div>
     <article>
        <header>
          <h2>
            <?php if (isset($this->data['Gallery']['id'])):
			echo ('Update Gallery');
		else:
			echo ('Add Gallery');	    
		endif;
	     ?>
           </h2>
         </header>
      </article>
	    <?php 
              if(!empty($this->request->data) && isset($this->request->data['Gallery']['id']))
              {
			  $act='edit';
		  }
              else
              {
			   $act='add';
		   }
              
	     ?>
	     
	    <?php 
			echo $this->Form->create('Gallery',array('name'=>'gallerys','type'=>'file','id'=>'GalleryCms','action'=>$act.'/'.$page_id.'/'.$parent_id,'onsubmit'=>'//return validatefields();')); ?>
	    <?php echo $this->Form->input('id');?>
	    <?php echo $this->Form->hidden('page_id',array('value'=>$page['Page']['id']));?>
	    
		<!-- Inputs -->
		<!-- Use class .small, .medium or .large for predefined size -->
	<fieldset>
		<dl>
			<dt>
				<label>Title<span style="color:red;">*</span></label>
			</dt>
			<?php $error_class= ($this->Form->isFieldError('name'))?'invalid':''; ?>
			<dd>
				<?php echo $this->Form->text('name',array('class'=> 'small '.$error_class,'size'=>'45')); ?>
				<?php if($this->Form->isFieldError('name')): ?>
					<span class="error-message"><?php echo __($this->Form->error('name',null,array('wrap'=>false))); ?></span>
				<?php endif; ?>
			</dd>
			<dt>
				<label>Image File<span style="color:red;">*</span></label>
			</dt>
			<?php $error_class= ($this->Form->isFieldError('image'))?'invalid':''; ?>
			<dd>
			<?php echo $this->Form->file('image',array('class'=> 'fileupload customfile-input'.$error_class)); ?>
			<?php if($this->Form->isFieldError('image')): ?>
					<span class="error-message"><?php echo __($this->Form->error('image',null,array('wrap'=>false))); ?></span>
				<?php endif; ?>
			<br><label>(Only png, gif, jpg, jpeg types are allowed. Max Image Size is 150KB )( 824 X 284 px)</label>
			<?php if(isset($this->request->data['Gallery']['thumb_image'])){
			echo $this->Html->image($this->request->data['Gallery']['thumb_image'],array('border'=>'0'));}?>
			</dd>
<!--
			<dt>
				<label>Address</label>
			</dt>			
			<dd>
				<?php //echo $this->Form->textarea('address',array('class'=> 'small','size'=>'45')); ?>
			</dd>
			<dt>
				<label>Discription</label>
			</dt>			
			<dd>
				<?php //echo $this->Form->textarea('discription',array('class'=> 'small','size'=>'45')); ?>
			</dd>
                        
                        <dt>
				<label>Sub Gallery</label>
			</dt>
			<dd>
				<?php //echo $this->Form->checkbox('sub_gallery');?>
			</dd>
-->
		</dl>
        </fieldset>
                <?php echo $this->Form->hidden('status',array('value'=>'1')); ?>
		<?php //e($form->hidden('publish',array('value'=>'0'))); ?>
		<button type="submit">
			<?php 
				if(isset($this->data['Gallery']['id'])):
					echo ('Update');
				else:
					echo ('Add');
				endif;
		       ?>
		</button> or 
                   <?php echo $this->Html->link('Cancel', array('controller'=>'galleries', 'action' => 'index/'.$page_id));?>
                    <?php $this->Form->end();?>
</div>




<script type="text/javascript">
    $(document).ready(function() {
		<?php $path = $this->Html->webroot; ?>
        $('#GalleryCms').submit(function() {
             var form_obj = this;
             var data = new FormData(this);
             var status = 0;
             $.each(this, function(i, v) {
                $(v).removeClass('invalid form-error');

            });
            
           
            $('.error-message , .notification').remove();
            $(form_obj).find('span#for_gallery_cms').show();
            $(form_obj).find('button[type=submit]').attr({'disabled': true});
		
            $.ajax({
                url: '<?=$path?>content_manager/galleries/ajax_check_validation',
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
                $(form_obj).find('span#for_gallery_cms').hide();
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
            $('#GalleryCms').before('<div class="notification error"><a class="close-notification" rel="tooltip" title="Hide Notification" href="#">x</a><p></p><div id="errorMessage" class="message">' + data.message + '</div><p></p></div>');
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
 
