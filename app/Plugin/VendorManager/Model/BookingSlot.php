<?php
App::uses('BookingParticipate', 'Model');
App::uses('BookingOrder', 'Model');
Class BookingSlot extends VendorManagerAppModel {
	public $name = "BookingSlot";
	public $validate = array();


	//coded by po
	public function isSlotBooked($service_id, $date, $start_time, $end_time)
	{
		$end_time = date('H:i:s', strtotime($end_time) + 1);
		$bookings = null;
		$bookings = $this->find('all', array(
			'conditions' => array(
				'BookingSlot.service_id'=>$service_id,
				'BookingSlot.start_time'=>"$date $start_time",
				'BookingSlot.end_time'=>"$date $end_time",
				)
			)
		);
		if (!empty($bookings)) {
			$data = new stdClass();
			$data->price = $bookings[0]['BookingSlot']['price'];
			$data->date = $date;
			return $data;
		}
		return false;
	}

	public function usedSlotCount ($service_id, $date, $start_time, $end_time)
	{
		$end_time = date('H:i:s', strtotime($end_time) + 1);
		$count = 0;
		$booking = $this->find('all', array(
			'conditions' => array(
				'BookingSlot.service_id'=>$service_id,
				'BookingSlot.start_time'=>"$date $start_time",
				'BookingSlot.end_time'=>"$date $end_time",
				),
			'fields' => array(
				'COUNT(BookingSlot.no_participants) as count', //'SUM(BookingSlot.no_participants) as count',
				'ref_no'
				),
			)
		)[0];
		$count = $booking[0]['count'];
		if ($count == 0) {
			return (int) $count;
		}
		$booking_participate = new BookingParticipate();
		$participants = $booking_participate->find('all' , [
			'conditions' => 
			[
				'ref_no' => $booking['BookingSlot']['ref_no']
				],
 			'fields' => [
 				'status',
 				]
 			]
		);


		$bo = new BookingOrder();
		$bo = $bo->find('first', [
			'conditions' => [
				'ref_no' => $booking['BookingSlot']['ref_no'],
				],
			'fields' => ['booking_date', 'no_participants', 'invite_friend_email']
			]
		);
		$booking_date = $bo['booking_orders']['booking_date'];

		if (strtotime($booking_date)+60*60*24 > time()) {
			$count = $bo['booking_orders']['no_participants'];
		} else {
			$count = $bo['booking_orders']['no_participants'] - count($bo['booking_orders']['invite_friend_email']);
		}

		if (count($participants) > 0) {
			foreach ($participants as $p) {
				if ($p['booking_participates']['status'] == 1) {
					$count++;
				}
			}
		}
		
		return (int) $count;
	}

	public function isSlotFull($service_id, $date, $start_time, $end_time, $max_slot)
	{
		return $this->usedSlotCount($service_id, $date, $start_time, $end_time) == $max_slot;
	}
}
?>
