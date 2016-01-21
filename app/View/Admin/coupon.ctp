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

        <dt><label>Title <span style="color:red;">*</span></label></dt>
        <dd><?php echo $this->Form->text('title', array('class'=>'large','size' => 20,'required'=>false)); ?></dd>
        
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
            <th>Title</th>
            <th>Description</th>
            <th>Discount</th>
            <th>Code</th>
            <th>Max Usage</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

        <?php if(count($coupons)): ?>
            <?php foreach($coupons as $coupon): ?>
                <tr>
                    <td><?php echo date('Y-m-d h:ia', strtotime($coupon['Coupon']['created_date'])); ?></td>
                    <td style="text-align:left;"><?php echo $coupon['Coupon']['title']; ?></td>
                    <td style="text-align:left;"><?php echo $coupon['Coupon']['description']; ?></td>
                    <td><?php echo $coupon['Coupon']['discount']*100 . '%'; ?></td>
                    <td><?php echo $coupon['Coupon']['code']; ?></td>
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
