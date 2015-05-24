

<div class="contact-form">
	<?=$this->element('message');?>
	<?php echo $this->Form->create('Page', array('name'=>'pages','id'=>'Contact','url'=>array('controller'=>'pages','plugin'=>'content_manager','action'=>'contactus'),'novalidate' => true)); ?>
	
    <?=$this->Form->hidden('form_name',array('required'=>false,'value'=>'ContactForm')); ?>
    <div class="form-row">
    	<label>Name: <span style="color:#ff0000;">*</span></label> 
    	<?php echo $this->Form->text('name',array('required'=>false)); ?>
    </div>
    <div class="form-row">
    	<label>Email Address: <span style="color:#ff0000;">*</span></label>
    	<?php echo $this->Form->email('email',array('required'=>false)); ?>
    </div>
    <div class="form-row">
    	<label>Phone Number: <span style="color:#ff0000;">*</span></label>
    	<?php echo $this->Form->tel('phone',array('required'=>false)); ?>
    </div>
    <div class="form-row">
    	<label>Message: <span style="color:#ff0000;">*</span></label>
    	<?php echo $this->Form->textarea('message',array('required'=>false)); ?>
    </div>
    <input class="submit-button" style="margin:20px 0 0 365px;" type="submit" value="Submit" id="submitBtn" />
</div>
<?php echo $this->Form->end(); ?> 
<div class="map">
	<? 	$map=str_ireplace('<p>','',$page['Page']['page_longdescription']);
		echo $map=str_ireplace('</p>','',$map);
	?>
</div>


<script type="text/javascript">
    <?php $path = $this->Html->webroot; ?>
	  var submitButton = $('#submitBtn');
	  submitButton.prop("disabled", true);
	  setTimeout(function () {
        submitButton.prop("disabled", false);
    },3000);
    
    $(document).ready(function(){
		//alert('etst');
		$('#Contact').submit(function(){
			
			var data = $(this).serializeArray();
			//var data = new FormData(this);
            var formData = $(this);
            var status = 0;
           
            $.each(this,function(i,v){
                $(v).removeClass('invalid form-error');
                });
            $('.error-message').remove();
            $('#Contact > span#for_owner_cms').show();
            $('#Contact > button[type=submit]').attr({'disabled':true});
            $.ajax({
                url: '<?=$path?>content_manager/pages/validation',
				async: false,
				data: data,
                dataType:'json', 
                type:'post',
                success: function(data) {
                    if(data.error==1){
						 
                        $.each(data.errors,function(i,v){
							$('#'+i).addClass("invalid form-error").after('<span class="error-message">'+v+'</span>');
							//$('#'+i).bind('click',function(){
								//$(this).removeClass('invalid form-error');
								//$(this).next().remove();
								//});
                            
                        });
                       
                    }else{
                        status = 1;
                    }
                   
                   }

            });
            if(status==0){
               $("html, body").animate({ scrollTop: 0 }, "slow");
               $('#Contact > button[type=submit]').attr({'disabled':false});
               $('#Contact > span#for_owner_cms').hide();
            }
			//return false; 
			return (status===1)?true:false; 
            
        });
    });
 </script>
