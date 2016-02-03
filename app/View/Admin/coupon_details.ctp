<div>
    <article>
            <header>
                    <h2>Coupon Redemption History</h2>
            </header>
    </article>
    <?php echo $this->element('admin/message');?>
</div>

<div>
    <h3><?php echo $discount*100; ?>% Discount on activity</h3>
    <br/>
    <h3>Description:</h3>
    <br/>
    <p><?php echo nl2br($coupon['Coupon']['description']); ?></p>
    <br/><br/>
</div>

<div>
    <h3>Coupons</h3>
    <table>
        <tr>
            <th>Date</th>
            <th>Email</th>
            <th>Activity</th>
            <th>Amount</th>
            <th>Discount</th>
            <th>Grand Total</th>
        </tr>

        <?php if(count($bookings)): ?>
            <?php foreach($bookings as $booking): ?>
                <tr>
                    <td><?php echo date('Y-m-d h:ia', strtotime($booking['BookingOrder']['booking_date'])) ?></td>
                    <td><?php echo $booking['BookingOrder']['guest_email'] ?></td>
                    <td><?php echo $booking['BookingOrder']['service_title'] ?></td>
                    <td><?php echo '$'.number_format($booking[0]['total_price'], 2); ?></td>
                    <td><?php echo '$'.number_format($booking[0]['total_price'] * $discount, 2); ?></td>
                    <td><?php echo '$'.number_format($booking[0]['total_price'] * (1- $discount), 2); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">No Bookings</td>
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
