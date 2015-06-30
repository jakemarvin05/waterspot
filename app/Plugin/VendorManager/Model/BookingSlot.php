<?php
Class BookingSlot extends VendorManagerAppModel {
	public $name = "BookingSlot";
	public $validate = array();

	//coded by po
	public function isSlotBooked($service_id, $date, $start_time, $end_time)
	{
		$bookings = $this->find('all', array(
			'conditions' => array(
				'BookingSlot.service_id'=>$service_id,
				'BookingSlot.start_time'=>"$date $start_time",
				'BookingSlot.start_time'=>"$date $end_time",
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
}
?>
