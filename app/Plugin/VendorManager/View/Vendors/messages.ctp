<? $payment_status=Configure::read('payment_status');?>
<? // create array for searching list
//$search_type=array('ref_no'=>'Order id','transaction_id'=>'Transaction Id','phone'=>'Phone No.','email'=>'Email','fname'=>'First Name','lname'=>'Last Name');
//$search_by_date_type=array('booking_date'=>'Booking Date','start_date'=>'Booked Date');
?>

<div class="wrapper vendor-panel container-fluid">
	<br><br><br><br>
	<h2 class="page-title">Messages</h2>
	
	<?=$this->element('VendorManager.left-vendor-panel');?>
	<div class="right-area col-sm-9 col-xs-12">

		<div class="service">
			<h3 class="dashboard-heading">My Messages</h3>
		<div class="clear"></div>
          <div class="message-list">
            <ul style="padding:0; margin:0; list-style:none;">

              <?php
                if (isset($member_id)) {
                  foreach ($messages as $message) {
                    if ($message['sent_by'] == 'vendor') {
                      echo '<li style="border-bottom:1px solid #CCC; padding: 20px; text-align: right;">';
                      echo '<h5 class="message-vendor">' . $message['vendor_name'] . '</h5>';
                    } else {
                      echo '<li style="border-bottom:1px solid #CCC; padding: 20px; text-align: left;">';
                      echo '<h5 class="message-vendor">' . $message['member_name'] . '</h5>';
                    }
                    echo '<p class="short-message">' . $message['message'] . '</p>';
                    echo '<span class="pull-right">' . $message['sent_at'] . '</span>';
                    echo '</li>';
                  }
                } else {
                  foreach ($messages as $message) {
                    echo '<li style="border-bottom:1px solid #CCC; padding: 20px;">';
                    echo '<h5 class="message-vendor">' . $message['member_name'] . '</h5>';
                    echo '<p class="short-message">' . $message['message'] . '</p>';
                    echo '<span class="pull-right">' . $message['sent_at'] . '</span>';
                    echo '</li>';
                  }
                }
              ?>
            </ul>
          </div>
   
  
   		<div class="clear"></div>
 		</div>

	</div>
<div class="clearfix"></div>


</div>
<script type='text/javascript'>
	$(document).ready(function () {
		sameHeight('left-area','right-area');
	});
</script>