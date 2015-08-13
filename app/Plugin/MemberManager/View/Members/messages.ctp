<?
// create array for searching list
$search_type=array('ref_no'=>'Order id','transaction_id'=>'Transaction Id','phone'=>'Phone No.','email'=>'Email','fname'=>'First Name','lname'=>'Last Name');
$search_by_date_type=array('booking_date'=>'Booking Date','start_date'=>'Booked Date');
?>
<br><br><br>

<div class="container-fluid member-panel">
<div class="hr-line"></div>
<div class="clear" style="margin-top:80px;"></div>
	<h2 class="page-title">Booking List</h2>
	<?=$this->element('MemberManager.left-member-panel');?>

	<div class="right-area  col-sm-9 col-xs-12">
		<h3 class="dashboard-heading">Messages</h3>
		<div class="service booking">

			<div class="clear"></div>
		   		<div class="message-list">
		   			<ul style="padding:0; margin:0; list-style:none;">

		   				<?php
		   					if (isset($vendor_id)) {
			   					foreach ($messages as $message) {
			   						if ($message['sent_by'] == 'member') {
			   							echo '<li style="border-bottom:1px solid #CCC; padding: 20px; text-align: right;">';
			   							echo '<h5 class="message-vendor">' . $message['member_name'] . '</h5>';
			   						} else {
			   							echo '<li style="border-bottom:1px solid #CCC; padding: 20px; text-align: left;">';
			   							echo '<h5 class="message-vendor">' . $message['vendor_name'] . '</h5>';
			   						}
			   						echo '<p class="short-message">' . $message['message'] . '</p>';
			   						echo '<span class="pull-right">' . $message['sent_at'] . '</span>';
			   						echo '</li>';
			   					}
		   					} else {
			   					foreach ($messages as $message) {
		   							echo '<li style="border-bottom:1px solid #CCC; padding: 20px;">';
			   						echo '<h5 class="message-vendor"><a href="/members/messages/'.$message['vendor_id'].'">' . $message['vendor_name'] . '</a></h5>';
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
</div>
<div class="clearfix"></div>



<script type='text/javascript'>
 $(document).ready(function () {
 sameHeight('left-area','right-area');
 });
</script>
