<div>
    <article>
            <header>
                    <h2>Add Coupon</h2>
            </header>
    </article>
    <?php echo $this->element('admin/message');?>
    <?php echo $this->Form->create('Coupon', array('name' => 'user','url' => array('controller'=>'admin','action'=>'coupon_add')));?>
    <fieldset>
        <?=$this->Form->hidden('form-name',array('required'=>false,'value'=>'AddCoupon')); ?>

        <dt><label>Code <span style="color:red;">*</span></label></dt>
        <dd>
            <?php echo $this->Form->text('code', array('class'=>'large','size' => 20,'required'=>false, 'id' => 'code')); ?>
            <button type="button" id="generate_code" style="position:absolute; margin-left:10px;">Generate Code</button>
        </dd>
        
        <dt></dt>
        <dd><span id="code_check_message" style="visibility:hidden;"></span></dd>

        <dt><label>Description <span style="color:red;">*</span></label></dt>
        <dd><?php echo $this->Form->textarea('description', array('class'=>'large','size' => 20,'required'=>false)); ?></dd>
        
        <dt><label>Discount (Please use whole number) <span style="color:red;">*</span></label></dt>
        <dd><?php echo $this->Form->text('discount', array('class'=>'large','size' => 20,'required'=>false)); ?></dd>

        <dt><label>Max Usage <span style="color:red;">*</span></label></dt>
        <dd><?php echo $this->Form->text('max_usage', array('class'=>'large','size' => 20,'required'=>false)); ?></dd>
    </fieldset>
    <button type="submit">Add Coupon</button>
    <?php echo $this->Form->end();?>
	
</div>

<div>
    <h3>Coupons</h3>
    <table>
        <tr>
            <th>Date</th>
            <th>Code</th>
            <th>Description</th>
            <th>Discount</th>
            <th>Max Usage</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

        <?php if(count($coupons)): ?>
            <?php foreach($coupons as $coupon): ?>
                <tr>
                    <td><?php echo date('Y-m-d h:ia', strtotime($coupon['Coupon']['created_date'])); ?></td>
                    <td><?php echo $coupon['Coupon']['code']; ?></td>
                    <td style="text-align:left;"><?php echo $coupon['Coupon']['description']; ?></td>
                    <td><?php echo $coupon['Coupon']['discount']*100 . '%'; ?></td>
                    <td><?php echo $coupon['Coupon']['max_usage']; ?></td>
                    <td><?php echo $coupon['Coupon']['is_active'] ? 'Open' : 'Closed'; ?></td>
                    <?php
                        if ($coupon['Coupon']['is_active']) {
                            echo '<td><a href="/admin/coupon_close/' . $coupon['Coupon']['id'] . '">close</a></td>';
                        } else {
                            echo '<td>' . date('Y-m-d h:ia', strtotime($coupon['Coupon']['close_date'])) . '</td>';
                        }
                    ?>
                    
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="8">No Coupons</td>
            </tr>
        <?php endif; ?>
    </table>
</div>
<script type="text/javascript">
    
    function check_code() {
        $.ajax({
            method: "post",
            url: "/admin/ajax_check_code",
            data: {code: $('#code').val()},
            success: function(data) {
                if (data == 'false') {
                    $('#code_check_message').css('color', '#556F22').css('visibility', 'visible').html('Code is available.');
                } else if (data == 'true') {
                    $('#code_check_message').css('color', '#943a28').css('visibility', 'visible').html('Code is already in use.');
                } else if (data == 'empty') {
                    $('#code_check_message').css('color', '#943a28').css('visibility', 'visible').html('Code is empty.');
                } else {
                    $('#code_check_message').css('color', '#943a28').css('visibility', 'visible').html('An error has occurred.');
                }
            }
        });
    }

    $('#code').on('change', function(){
        check_code();
    });

    $('#generate_code').on('click', function(){
        $('#code_check_message').css('visibility', 'hidden').html('');
        $.ajax({
            url: "/admin/ajax_generate_code",
            success: function(data) {
                $('#code').val(data);
            }
        });
    });

</script>

<script type="text/javascript">
	 <?php $path = $this->Html->webroot; ?>
    $(document).ready(function(){
		$('#PasswordChange').submit(function(){
			
			var data = $(this).serializeArray();
            var formData = $(this);
            var status = 0;
           
           $.each(this,function(i,v){
                $(v).removeClass('invalid form-error');
                });
            $('.error-message').remove();
            $('#PasswordChange > span#for_owner_cms').show();
            $('#PasswordChange > button[type=submit]').attr({'disabled':true});
           $.ajax({
                url: '<?=$path?>subadmin_manager/users/validation',
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
               $('#PasswordChange > button[type=submit]').attr({'disabled':false});
               $('#PasswordChange > span#for_owner_cms').hide();
            }
           
          
          
           return (status===1)?true:false; 
            
        });
        
        
    });
 </script>
