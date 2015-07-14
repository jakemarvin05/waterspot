<div class="container-fluid member-panel">
<div class="hr-line"></div>
<div class="clear" style="margin-top:80px;"></div>

<h2 class="page-title">Change Email</h2>

<?=$this->element('MemberManager.left-member-panel');?>

<div class="right-area col-sm-9 col-xs-12">
  <h3 class="dashboard-heading">Update Your Email</h3>
    <?=$this->element('message');?>
      <?php echo $this->Form->create('Member', array('id'=>'ChangeEmail','url'=>array('plugin'=>'member_manager','controller'=>'members','action'=>'change_email'),'class'=>'dashboard-edit-form','novalidate'=>true));?>
	<?=$this->Form->hidden('form-name',array('required'=>false,'value'=>'change_email')); ?>
        <div class="frame">
	<div class="dashboard-form-row editemail">
          <div class="labelbox">
	    <label>Current Password: <span style="color:#ff0000;">*</span></label>
          </div>
          <div class="fieldbox">
	    <?=$this->Form->password('current_password',array('required'=>false, 'class'=>'editemailinput')); ?>
          </div>
        </div>
	<div class="dashboard-form-row editemail">
          <div class="labelbox">
            <label>New Email: <span style="color:#ff0000;">*</span></label>
          </div>
          <div class="fieldbox">
	    <?=$this->Form->text('email_id',array('required'=>false, 'class'=>'editemailinput')); ?>
          </div>
        </div>
	<div class="dashboard-form-row editemail">
          <div class="labelbox">
	    <label>Confirm Email: <span style="color:#ff0000;">*</span></label>
          </div>
          <div class="fieldbox">
	    <?=$this->Form->text('confirm_email_id',array('required'=>false, 'class'=>'editemailinput')); ?>
          </div>
        </div>
	<div class="dashboard-form-row editemail">
          <input class="dashboard-buttons" value="Update Email" type="submit">
        </div>
      <?php echo $this->Form->end();?>
</div>    
</div>
</div>

<script type="text/javascript">
   <?php $path = $this->Html->webroot; ?>
   $(document).ready(function(){
      $('#ChangeEmail').submit(function(){		
	 var data = $(this).serializeArray();
	 var formData = $(this);
	 var status = 0;
	 $.each(this,function(i,v){
	     $(v).removeClass('invalid form-error');
	 });
	 $('.error-message').remove();
	 $('#ChangeEmail > span#for_owner_cms').show();
	 $('#ChangeEmail > button[type=submit]').attr({'disabled':true});
	 $.ajax({
	    url: '<?=$path?>member_manager/members/validation',
	    async: false,
	    data: data,
	    dataType:'json', 
	    type:'post',
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
	    $('#ChangeEmail > button[type=submit]').attr({'disabled':false});
	    $('#ChangeEmail > span#for_owner_cms').hide();
	 }	     
	 return (status===1)?true:false;
      });
        
   });
 </script>
