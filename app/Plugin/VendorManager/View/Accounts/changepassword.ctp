<div class="container-fluid vendor-panel">
    <br><br><br>
<h2 class="page-title">Change Password</h2>

<?=$this->element('VendorManager.left-vendor-panel');?>

<div class="right-area col-sm-9 col-xs-12">
   <h3 class="dashboard-heading">Change your Password</h3>
   <?=$this->element('message');?>
   
   <?php echo $this->Form->create('Vendor', array('id'=>'ChangePassword','url'=>array('plugin'=>'vendor_manager','controller'=>'accounts','action'=>'changepassword'),'class'=>'dashboard-edit-form','novalidate' => true,'type'=>'file'));?>
     <div class="frame">
      <?=$this->Form->hidden('form-name',array('required'=>false,'value'=>'Change-Password')); ?>
      <div class="dashboard-form-row row">
	 <div class="labelbox">
	    <label>Current Password: <span style="color:#ff0000;">*</span></label>
	 </div>
	 <div class="fieldbox">
	    <?=$this->Form->password('old_password',array('required'=>false)); ?>
	 </div>
      </div>
      <div class="dashboard-form-row row">
	 <div class="labelbox">
	    <label>New Password: <span style="color:#ff0000;">*</span></label>
	 </div>
	 <div class="fieldbox">
	    <?=$this->Form->password('new_password',array('required'=>false)); ?>
	 </div>
      </div>
      <div class="dashboard-form-row row">
	 <div class="labelbox">
	    <label>Confirm Password: <span style="color:#ff0000;">*</span></label>
	 </div>
	 <div class="fieldbox">
	    <?=$this->Form->password('confirm_password1',array('required'=>false)); ?>
	 </div>
      </div>
      <div class="dashboard-form-row row">
	 <input class="dashboard-buttons" value="Update Password" type="submit">
      </div>
     </div>
   <?php echo $this->Form->end();?>     

</div>

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
                url: '<?=$path?>vendor_manager/accounts/validation',
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
<script type='text/javascript'>
    $(document).ready(function () {
        sameHeight('left-area','right-area');
    });
</script>