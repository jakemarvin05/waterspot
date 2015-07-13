<div class="container-fluid vendor-panel">

	<br><br><br>
<div class="clear"></div>
<div class="bredcrum"><?=$this->element('breadcrumbs');?></div>
<h2 class="page-title">Vendor</h2>
<?=$this->element('VendorManager.left-vendor-panel');?>

<div class="right-area col-sm-9 col-xs-12">
      <h3 class="dashboard-heading">Edit Profile</h3>
      <?=$this->element('message');?>

      <?php echo $this->Form->create('Vendor', array('id'=>'VendorRegistration','url'=>array('plugin'=>'vendor_manager','controller'=>'accounts','action'=>'editProfile'),'class'=>'dashboard-edit-form','novalidate' => true,'type'=>'file'));?>
	    <?=$this->Form->hidden('form-name',array('required'=>false,'value'=>'ChangeProfile')); ?>
	<div class="row"><div class="col-sm-9">
	     <div class="dashboard-form-row row">
			<div class="labelbox">
				<label>Business Name : <span style="color:#ff4142;">*</span></label>
			</div>
			<div class="fieldbox">
				<?=$this->Form->text('bname'); ?>
				<?=$this->Form->error('bname',null,array('wrap' => 'div', 'class' => 'error-message')); ?>
			</div>
	    </div>
	    
	    <div class="dashboard-form-row row">
			<div class="col-sm-6 col-xs-12"><div class="labelbox">
					<label>First Name : <span style="color:#ff4142;">*</span></label>
				</div>
				<div class="fieldbox">
					<?=$this->Form->text('fname'); ?>
					<?=$this->Form->error('fname',null,array('wrap' => 'div', 'class' => 'error-message')); ?>
				</div></div>
			<div class="col-sm-6 col-xs-12">
				<div class="labelbox">
					<label>Last Name : <span style="color:#ff4142;">*</span></label>
				</div>
				<div class="fieldbox">
					<?=$this->Form->text('lname'); ?>
					<?=$this->Form->error('lname',null,array('wrap' => 'div', 'class' => 'error-message')); ?>
				</div>
			</div>

	    </div>

	    	    
	    <div class="dashboard-form-row row">
			<div class="col-sm-6 col-xs-12">
				<div class="labelbox">
					<label>Email : <span style="color:#ff4142;">*</span></label>
				</div>
				<div class="fieldbox">
					<?=$this->Form->text('email',array('readonly'=>true)); ?>
					<?=$this->Form->error('email',null,array('wrap' => 'div', 'class' => 'error-message')); ?>
				</div></div>
			<div class="col-sm-6 col-xs-12">
				<div class="labelbox">
					<label>Phone : <span style="color:#ff4142;"> *</span></label>
				</div>
				<div class="fieldbox">
					<?=$this->Form->text('phone'); ?>
					<?=$this->Form->error('phone',null,array('wrap' => 'div', 'class' => 'error-message')); ?>
				</div>
			</div>

	    </div>
	    

   	    <div class="dashboard-form-row row">
			<div class="labelbox">
				<label>About us : </label>
			</div>
			<div class="fieldbox about_fck">
				<?=$this->Form->textarea('about_us'); ?>
				<?=$this->Form->error('about_us',null,array('wrap' => 'div', 'class' => 'error-message')); ?>
			</div>
	    </div>

	    <div class="dashboard-form-row">
		  <input class="dashboard-buttons" value="Update Profile" type="submit">
	    </div>
		</div>
		<div class="col-sm-3">
			<div class="dashboard-file-uploader">
				<div class="dashboard-image-uploading-status" id="change_profile" style="display:none;"><?=$this->Html->image('uploading.gif');?> </div>
				<?php
				/* Resize Image */
				if(isset($this->data['Vendor']['image'])) {
					$imgArr = array('source_path'=>Configure::read('VendorProfile.SourcePath'),'img_name'=>$this->data['Vendor']['image'],'width'=>150,'height'=>150,'noimg'=>$setting['site']['site_noimage']);
					$resizedImg = $this->ImageResize->ResizeImage($imgArr);
					echo $this->Html->image($resizedImg,array('border'=>'0','id'=>'profileImage', 'class'=>'profile-img'));
				}
				?>
				<div class="dashboard-upload-link-div">
					<a class="dashboard-upload-image-link btn btn-primary" href="#" id="uploadFile" title="Upload">Change Photo</a>
				</div>

				<div id="messageBox" class="dashboard-file-uploader-msg"></div>
			</div>
		</div>
		<div class="clearfix"></div>
      <?php echo $this->Form->end();?>

	</div>
      <div class="Registration-key"></div>
	


	<div class="clearfix"></div>

</div>
	</div>

  <script type="text/javascript">
	<?php $path = $this->Html->webroot; ?>
    $(document).ready(function(){
	   $('#VendorRegistration').submit(function(){
		
		//var data = $(this).serializeArray();
		var data = new FormData(this);
		var formData = $(this);
		var status = 0;
           
		$.each(this,function(i,v){
			$(v).removeClass('invalid form-error');
			});
		$('.error-message').remove();
		$('#VendorRegistration > span#for_owner_cms').show();
		$('#VendorRegistration > button[type=submit]').attr({'disabled':true});
           
           $.ajax({
                url: '<?=$path?>vendor_manager/accounts/validation',
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
							$('#'+i).addClass("invalid form-error").after('<div class="error-message">'+v+'</div>');
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
               $('#VendorRegistration > button[type=submit]').attr({'disabled':false});
               $('#VendorRegistration > span#for_owner_cms').hide();
            }
           return (status===1)?true:false; 
            
        });
        
       
    });
 </script>
 <?php $path = $this->Html->webroot; ?>
   
 <script type="text/javascript"> 
	      var uploadURL = "<?=$path?>vendor_manager/accounts/images_handle"
 	      $(document).ready(function(){
                    $('a#uploadFile').file();
                    $('a#delete').click(function(){
						
						$('input#profileImageFile').val("");
						$('img#profileImage').attr("src","<?=$path?>img/profileBlank.jpg");
						$('div#messageBox').html("Image deleted !");
						$('div#messageBox').attr("class","success");
                        $('a#delete').hide();
                    });
                    $('input#uploadFile').file().choose(function(e, input) {
						$('#change_profile').show();
                        input.upload(uploadURL, function(res) {
							
                            if (res=="invalid"){
                                $('div#messageBox').attr("class","error");
                                $('div#messageBox').html("Invalid extension !");
                            }else{
								$('#change_profile').hide();
                                //$('div#messageBox').attr("class","success");
                                $('div#messageBox').html("Profile picture has been updated!");
                                $('img#profileImage').attr("src",res);
                                $('input#profileImageFile').val(res);
                                $('a#delete').show();
                                $(this).remove();
                            }
                        }, '');                  
              });
               });

	</script>
<script>
	 CKEDITOR.replace('VendorAboutUs', {
		 removePlugins:'bidi,div,font,forms,flash,horizontalrule,iframe,justify,table,tabletools,smiley',
			removeButtons: 'Anchor,Underline,Strike,Subscript,Superscript,Image',
			format_tags: 'p;h1;h2;h3;pre;address'
		} );
		 
	</script>


<script type="text/javascript">
	$(document).ready(function(){
		//sameHeight('right-area','left-area');

		//$('.left-area').height($('.right-area').height());
	});
</script>
