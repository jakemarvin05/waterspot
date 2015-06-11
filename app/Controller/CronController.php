<?php 
class CronController extends AppController {
	public function beforeFilter() {
		parent::beforeFilter();
		$this->layout=null;
	}
	public function clear_booking(){
		// it is used for cancel which booking is not booked
		$this->loadModel('BookingOrder');
		$this->loadModel('Booking');
		$end_time=date('Y-m-d H:i:s',strtotime(Configure::read('Booking.clearEndTime')));
		
		$start_time=date('Y-m-d H:i:s',strtotime(Configure::read('Booking.clearStartTime')));
		$this->BookingOrder->updateAll(
			array('BookingOrder.status' => 0),
			array('BookingOrder.status' => 4,'BookingOrder.time_stamp BETWEEN ? AND ?'=>array($start_time,$end_time))
		);
		$this->Booking->updateAll(
			array('Booking.status' => 0),
			array('Booking.status' => 4,'Booking.time_stamp BETWEEN ? AND ?'=>array($start_time,$end_time))
		);
		 
		return;
	}
}
?>
