<div class="hr-line"></div>
<div class="clear"></div>
<div class="bredcrum"><?=$this->element('breadcrumbs');?></div>
<h2 class="page-title">Change <span style="color: #000;">Password</span></h2>

<?=$this->element('MemberManager.left-member-panel');?>

<div class="right-area">
   <h3 class="dashboard-heading">Change Password</h3>
   <?=$this->element('message');?>
   <?php echo $this->Form->create('Member', array('id'=>'ChangePassword','url'=>array('plugin'=>'member_manager','controller'=>'members','action'=>'changepassword'),'class'=>'dashboard-edit-form','novalidate'=>true));?>
      <?=$this->Form->hidden('form-name',array('required'=>false,'value'=>'Change-Password')); ?>
      <div class="dashboard-form-row">
	 <div class="labelbox">
	    <label>Current Password: <span style="color:#ff0000;">*</span></label>
	 </div>
	 <div class="fieldbox">
	    <?=$this->Form->password('current_password',array('required'=>false)); ?>
	 </div>
      </div>
      <div class="dashboard-form-row">
	 <div class="labelbox">
	    <label>New Password: <span style="color:#ff0000;">*</span></label>
	 </div>
	 <div class="fieldbox">
	    <?=$this->Form->password('password',array('required'=>false)); ?>
	 </div>
      </div>
      <div class="dashboard-form-row">
	 <div class="labelbox">
	    <label>Confirm Password: <span style="color:#ff0000;">*</span></label>
	 </div>
	 <div class="fieldbox">
	    <?=$this->Form->password('confirm_password',array('required'=>false)); ?>
	 </div>
      </div>
      <div class="dashboard-form-row">
	 <input class="dashboard-buttons" value="Update" type="submit">
      </div>
   <?php echo $this->Form->end();?>     
</div>

<script type="text/javascript">
   <?php $path = $this->Html->webroot; ?>
   $(document).ready(function(){
      $('#ChangePassword').submit(function(){		
	 var data = $(this).serializeArray();
	 var formData = $(this);
	 var status = 0;
	 $.each(this,function(i,v){
	     $(v).removeClass('invalid form-error');
	 });
	 $('.error-message').remove();
	 $('#ChangePassword > span#for_owner_cms').show();
	 $('#ChangePassword > button[type=submit]').attr({'disabled':true});
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
	    $('#ChangePassword > button[type=submit]').attr({'disabled':false});
	    $('#ChangePassword > span#for_owner_cms').hide();
	 }	     
	 return (status===1)?true:false;
      });
        
   });
 </script>
