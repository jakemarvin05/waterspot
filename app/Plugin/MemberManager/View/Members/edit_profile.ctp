<div class="container-fluid member-panel">
<div class="hr-line"></div>
<div class="clear" style="margin-top:80px;"></div>


<h2 class="page-title">Member</h2>

<?=$this->element('MemberManager.left-member-panel');?>

<div class="right-area col-sm-9 col-xs-12">
      <h3 class="dashboard-heading">Update Profile</h3>
      <?=$this->element('message');?>
      <?php echo $this->Form->create('Member', array('id'=>'MemberRegistration','url'=>array('plugin'=>'member_manager','controller'=>'members','action'=>'edit_profile'),'class'=>'dashboard-edit-form','novalidate' => true));?>
	    <?=$this->Form->hidden('form-name',array('required'=>false,'value'=>'EditProfileForm')); ?> 
            <div class="dashboard-form-row row editprof">
                <div class="col-sm-6">
		  <div class="labelbox">
			<label>First name : <span style="color:#ff4142;">*</span></label>
		  </div>
		  <div class="fieldbox">
			<?=$this->Form->text('first_name', array('class' => 'editprofinput')); ?>
			<?=$this->Form->error('first_name',null,array('wrap' => 'div', 'class' => 'error-message')); ?>
		  </div>
                </div>
                <div class="col-sm-6">
		  <div class="labelbox">
			<label>Last name : <span style="color:#ff4142;">*</span></label>
		  </div>
		  <div class="fieldbox">
			<?=$this->Form->text('last_name', array('class' => 'editprofinput')); ?>
			<?=$this->Form->error('last_name',null,array('wrap' => 'div', 'class' => 'error-message')); ?>
		  </div>
                </div>
	    </div>
            <div class="dashboard-form-row editprof">
		  <div class="labelbox">
			<label>Email address : <span style="color:#ff4142;"></span></label>
		  </div>
		  <div class="fieldbox">
			<?=$this->Form->text('email_id',array('readonly'=>true, 'class' => 'editprofinput')); ?>
			<?=$this->Form->error('email_id',null,array('wrap' => 'div', 'class' => 'error-message')); ?> 
		  </div>
	    </div>
            <div class="dashboard-form-row editprof">
		  <div class="labelbox">
			<label>Phone : <span style="color:#ff4142;"> *</span></label>
		  </div>
		  <div class="fieldbox">
			<?=$this->Form->text('phone',array('readonly'=>true, 'class' => 'editprofinput')); ?>
			<?=$this->Form->error('phone',null,array('wrap' => 'div', 'class' => 'error-message')); ?>
		  </div>
	    </div>
            <div class="dashboard-form-row editprof">
		  <input class="dashboard-buttons" value="Update Profile" type="submit">
	    </div>
      <?php echo $this->Form->end();?>  
</div>
</div>

  <script type="text/javascript">
	<?php $path = $this->Html->webroot; ?>
    $(document).ready(function(){
	   $('#MemberRegistration').submit(function(){
		
		//var data = $(this).serializeArray();
		var data = new FormData(this);
		var formData = $(this);
		var status = 0;
           
		$.each(this,function(i,v){
			$(v).removeClass('invalid form-error');
			});
		$('.error-message').remove();
		$('#MemberRegistration > span#for_owner_cms').show();
		$('#MemberRegistration > button[type=submit]').attr({'disabled':true});
           
           $.ajax({
                url: '<?=$path?>member_manager/members/validation',
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
               $('#MemberRegistration > button[type=submit]').attr({'disabled':false});
               $('#MemberRegistration > span#for_owner_cms').hide();
            }
           return (status===1)?true:false; 
            
        });
        
       
    });
 </script>
<script type='text/javascript'>
 $(document).ready(function () {
 sameHeight('left-area','right-area');
 });
</script>