<?php

class FreeExpiredSlotsTask extends Shell {
	public $uses = array('Scheduler');
    public function execute() {
		$servername = "localhost";
		$username = "water";
		$password = "asdfasdf";
		$dbname = "waterspot_local";
		$time = date('Y-m-d H:i:s');

		$conn = new mysqli($servername, $username, $password, $dbname);
		$query = "SELECT bp.*, MIN(bs.start_time) as date_time
		FROM booking_participates as bp, booking_slots as bs 
		WHERE bp.booking_order_id = bs.booking_order_id
		AND bp.status = 0
		GROUP BY bs.booking_order_id
		HAVING MIN(bs.start_time) < '$time'
		";

		$result = $conn->query($query);
		$count = $result->num_rows;
	    while($row = $result->fetch_assoc()) {
	        $booking_slots = "DELETE FROM booking_slots WHERE booking_order_id='$row[booking_order_id]'";
	        $booking_participates = "DELETE FROM booking_participates WHERE booking_order_id='$row[booking_order_id]'";
	        $booking_orders = "DELETE FROM booking_orders WHERE id='$row[booking_order_id]'";

	        $conn->query($booking_slots);
	        $conn->query($booking_participates);
	        $conn->query($booking_participates);
	    }
		$conn->close();
		return "Freed $count expired booking slots\n";
    }
}
