<?php
Class Booking extends AppModel {
	public $name = "bookings";
	function getBookingDetailsByBooking_id($booking_id=null) {
		$booking_detail=$this->find('first',array('conditions'=>array('Booking.id'=>$booking_id)));
		return $booking_detail;
	}
	function getBookingRefenceByBooking_id($booking_id=null) {
		$booking_ref_no=$this->find('first',array('fields'=>array('Booking.ref_no'),'conditions'=>array('Booking.id'=>$booking_id)));
		return $booking_ref_no['Booking']['ref_no'];
	}
	function getBookingDetailsByBooking_ref($ref_no=null) {
		$booking_detail=$this->find('first',array('conditions'=>array('Booking.ref_no'=>$ref_no),'order'=>array('Booking.id DESC')));
		return $booking_detail;
	}
	function getBookingDetailsByPayment_ref($payment_ref=null) {
		$booking_detail=$this->find('first',array('conditions'=>array('Booking.payment_ref'=>$payment_ref),'order'=>array('Booking.id DESC')));
		return $booking_detail;
	}
	 
 }
?>
