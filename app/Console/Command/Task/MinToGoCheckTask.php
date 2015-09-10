<?php

class MinToGoCheckTask extends Shell {
	public $uses = array('Scheduler', 'VendorManager.BookingSlot', 'VendorManager.BookingOrder', 'Booking', 'Service');
    public function execute() {
    	
		$time = date('Y-m-d H:i:s', time() + 60*60*1);
		$now = date('Y-m-d H:i:s', time());

		$near_slots = $this->BookingSlot->find('all', ['conditions' => ["start_time BETWEEN '$now' AND '$time'"], 'group' => 'ref_no' ]);
		
		App::uses('CakeEmail', 'Network/Email');
		foreach ($near_slots as $bs) {
			$booking_slot  = $bs['BookingSlot'];
			$service = $this->Service->find('first', ['conditions' => ['id' => $booking_slot['service_id']] ]);

			if ($service['Service']['min_participants'] > 0) {
				$bookings = $this->Booking->find('all', ['conditions' => ['ref_no' => $booking_slot['ref_no'], 'status' => 1] ]);
				$count = $this->BookingSlot->paidSlotCount($service['Service']['id'], $booking_slot['start_time'], $booking_slot['end_time']); //count only those who are paid
				if ($count < $service['Service']['min_participants']) {
					// send email to vendor
					$booking_order = $this->BookingOrder->find('first', ['conditions' => ['ref_no' => $booking_slot['ref_no']] ]);
					$vendor_email = $booking_order['BookingOrder']['vendor_email'];
					$email = new CakeEmail();
					$email->config('gmail');
			        $email->from(array('admin@waterspot.com.sg' => 'Waterspot'));
			        $email->to($vendor_email);
			        $email->subject('Mimimum to go not reached');
			        $message = "Unfortunately the minimum to go has not been reached, \nFor the cancellation of booking, please contact the members that booked the service \n" . $service['Service']['service_title'] . " $booking_slot[start_time] to $booking_slot[end_time]";
			        $email->send($message);

			        foreach ($bookings as $booking) {
			        	// send email to booked & paid users
			        	$mail_to = $booking['Booking']['email'];
			        	$email = new CakeEmail();
						$email->config('gmail');
				        $email->from(array('admin@waterspot.com.sg' => 'Waterspot'));
				        $email->to($vendor_email);
				        $email->subject('Mimimum to go not reached');
				        $message = "Unfortunately the minimum to go has not been reached, \nFor the cancellation of booking, please contact the vendor \n\n Service Details: \n" . $service['Service']['service_title'] . " $booking_slot[start_time] to $booking_slot[end_time]";
				        $email->send($message);
			        }
				}
			}
		}
    }
}
