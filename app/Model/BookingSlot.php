<?php
App::uses('BookingParticipate', 'Model');
App::uses('BookingOrder', 'Model');
App::uses('Booking', 'Model');
Class BookingSlot extends AppModel {
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

	public function usedSlotCount($service_id, $date, $start_time, $end_time)
	{
		$end_time = date('H:i:s', strtotime($end_time) + 1);
		$end_date = strtotime($start_time) > strtotime($end_time) ? date('Y-m-d', strtotime($date) + 60*60*24) : $date;
		$count = 0;
		$booking_slots = $this->find('all', array(
			'conditions' => array(
				'BookingSlot.service_id'=>$service_id,
				'BookingSlot.start_time'=>"$date $start_time",
				'BookingSlot.end_time'=>"$end_date $end_time",
				),
			'fields' => array(
				'SUM(BookingSlot.no_participants) as count',
				'BookingSlot.ref_no'
				),
			'group' => 'BookingSlot.ref_no'
			)
		);
		$count = $booking_slots[0][0]['count'];
		if ($count == 0) return 0;
		$count = 0;
		$booking_participate = new BookingParticipate();
		foreach ($booking_slots as $booking_slot) {
			$participants = $booking_participate->find('all' , [
				'conditions' => ['ref_no' => $booking_slot['BookingSlot']['ref_no']],
	 			'fields' => ['status',]
	 		]);
	 		$bo = new BookingOrder();
			$bo = $bo->find('first', [
				'conditions' => ['ref_no' => $booking_slot['BookingSlot']['ref_no']],
				'fields' => ['booking_date', 'no_participants', 'invite_friend_email']
			]);
			$bo = array_pop($bo);
			$booking_date = $bo['booking_date'];
			$invited = 0;

			if ($bo['invite_friend_email']) {
				$invited = count($bo['invite_friend_email']);
			}
			if (strtotime($booking_date)+60*60*24 > time()) {
				$count += $bo['no_participants'];
			} else {
				$count += $bo['no_participants'] - $invited;
			}

			if (count($participants) > 0) {
				foreach ($participants as $p) {
					if ($p['booking_participates']['status'] == 1) {
						$count++;
					}
				}
			}
		}
		return (int) $count;
	}

	public function isSlotFull($service_id, $date, $start_time, $end_time, $max_slot)
	{
		return $this->usedSlotCount($service_id, $date, $start_time, $end_time) == $max_slot;
	}


	public function paidSlotCount($service_id, $start_time, $end_time)
	{
		$count = 0;
		$booking_slots = $this->find('all', array(
			'conditions' => array(
				'BookingSlot.service_id'=>$service_id,
				'BookingSlot.start_time'=>"$start_time",
				'BookingSlot.end_time'=>"$end_time",
				),
			'fields' => array(
				'SUM(BookingSlot.no_participants) as count',
				'ref_no'
				),
			'group' => 'BookingSlot.ref_no'
			)
		);
		$count = $booking_slots[0][0]['count'];
		if ($count == 0) return 0;
		$count = 0;

		$booking_participate = new BookingParticipate();
		foreach ($booking_slots as $booking_slot) {
	 		
	 		$participants = $booking_participate->find('all' , [
				'conditions' => ['ref_no' => $booking_slot['BookingSlot']['ref_no']],
	 			'fields' => ['status']
	 		]);
	 		$bo = new BookingOrder();
	 		$bo = $bo->find('first', [
				'conditions' => ['ref_no' => $booking_slot['BookingSlot']['ref_no']],
				'fields' => ['booking_date', 'no_participants', 'invite_friend_email']
			]);
			$bo = array_pop($bo);
			$booking_date = $bo['booking_date'];
			$invited = 0;
			if ($bo['invite_friend_email']) {
				$invited = count($bo['invite_friend_email']);
			}
			$count += $bo['no_participants'] - $invited;
			
			if (count($participants) > 0) {
				foreach ($participants as $p) {
					if ($p['booking_participates']['status'] == 1) {
						$count++;
					}
				}
			}
		}
		return (int) $count;
	}
}
?>
