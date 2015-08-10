<?php

class WarnUnpaidUsersTask extends Shell {
	public $uses = array('Scheduler');
    public function execute() {
    	$servername = "localhost";
		$username = "water";
		$password = "asdfasdf";
		$dbname = "waterspot_local";
		$time = date('Y-m-d H:i:s', time() + 60*60*12); // make it 12 hrs ahead
		echo $time . "\n\n"; 
		$conn = new mysqli($servername, $username, $password, $dbname);
		$query = "SELECT bp.*, MIN(bs.start_time) as date_time
		FROM booking_participates as bp, booking_slots as bs 
		WHERE bp.booking_order_id = bs.booking_order_id
		AND bp.status = 0
		GROUP BY bs.booking_order_id
		HAVING MIN(bs.start_time) < '$time'
		";

		$result = $conn->query($query);
		$users = [];
		$count = $result->num_rows;
	    while($row = $result->fetch_assoc()) {
	        $users[] = $row;
	    }
		$conn->close();
		foreach ($users as $user) {
	        App::uses('CakeEmail', 'Network/Email');
	        $email = new CakeEmail();
$email->config('gmail');
	        
	        $email->from(array('admin@waterspot.com.sg' => 'Waterspot'));
	        $email->to($user['email']); // don't use the members email yet to avoid spamming
	        $email->subject('test email, you forgot to pay your reserved booking at waterspot');
	        $email->send('please to continue pay the booking you have made');
		}
		return "Warned $count unpaid members with bookings\n";
    }
}
