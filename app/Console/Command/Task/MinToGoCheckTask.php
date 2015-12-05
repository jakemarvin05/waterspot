<?php

class MinToGoCheckTask extends Shell {
	public $uses = array('Scheduler', 'VendorManager.BookingSlot', 'VendorManager.BookingOrder', 'Booking', 'Service');
    public function execute() {
    	
		$time = date('Y-m-d H:i:s', time() + 60*60*1);
		$now = date('Y-m-d H:i:s', time());

		$near_slots = $this->BookingSlot->find('all', ['conditions' => ["start_time BETWEEN '$now' AND '$time'"], 'group' => 'ref_no' ]);
		echo count($near_slots) . "\n";
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

					$global_merge_vars = '[';
			        $global_merge_vars .= '{"name": "USER_NAME", "content": "'.$booking_order['BookingOrder']['vendor_name'].'"},';
			        $global_merge_vars .= '{"name": "ORDERNO", "content": "'.$booking_order['BookingOrder']['id'].'"},';
			        $global_merge_vars .= '{"name": "SERVICE_TITLE", "content": "'.$booking_order['BookingOrder']['service_title'].'"},';
			        $global_merge_vars .= '{"name": "PAX", "content": "'.$booking_order['BookingOrder']['no_participants'].'"},';
			        $global_merge_vars .= '{"name": "DATE", "content": "'.date('Y-m-d',strtotime($booking_order['BookingOrder']['booking_date'])).'"},';
			        $global_merge_vars .= '{"name": "SLOT_DATE", "content": "'.date('Y-m-d',strtotime($booking_order['BookingOrder']['start_date'])).' - '.date('Y-m-d',strtotime($booking_order['BookingOrder']['end_date'])).'"},';
			        $global_merge_vars .= '{"name": "VENDOR_NAME", "content": "'.$booking_order['BookingOrder']['vendor_name'].'"},';
			        $global_merge_vars .= '{"name": "PHONE", "content": "'.$booking_order['BookingOrder']['vendor_phone'].'"},';
			        $global_merge_vars .= '{"name": "TOTAL_PRICE", "content": "'.$booking_order['BookingOrder']['total_amount'].'"},';
			        $global_merge_vars .= '{"name": "MESSAGE", "content": "For the cancellation of booking, please contact the members that booked the service."},';
			        $global_merge_vars .= '{"name": "VENDORADDRESS", "content": "'.$booking_order['BookingOrder']['vendor_email'].'"}';
			        $global_merge_vars .= ']';

			        $data_string = '{
			                "key": "RcGToklPpGQ56uCAkEpY5A",
			                "template_name": "minimum_to_go_not_reached",
			                "template_content": [
			                        {
			                                "name": "TITLE",
			                                "content": "test test test"
			                        }
			                ],
			                "message": {
			                        "subject": "Mimimum to go not reached",
			                        "from_email": "admin@waterspot.com.sg",
			                        "from_name": "Waterspot Admin",
			                        "to": [
			                                {
			                                        "email": "'.$vendor_email.'",
			                                        "type": "to"
			                                }
			                        ],
			                        "global_merge_vars": '.$global_merge_vars.'
			                }
			        }';

			        $ch = curl_init('https://mandrillapp.com/api/1.0/messages/send-template.json');                                                                      
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
					curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
					curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
					    'Content-Type: application/json',                                                                                
					    'Content-Length: ' . strlen($data_string))                                                                       
					);                                                                                                                   
					                                                                                                                     
					$result = curl_exec($ch);

			        foreach ($bookings as $booking) {
			        	$mail_to = $booking['Booking']['email'];


			        	// send email to booked & paid users
						$global_merge_vars = '[';
				        $global_merge_vars .= '{"name": "USER_NAME", "content": "'.$booking_order['BookingOrder']['vendor_name'].'"},';
				        $global_merge_vars .= '{"name": "ORDERNO", "content": "'.$booking_order['BookingOrder']['id'].'"},';
				        $global_merge_vars .= '{"name": "SERVICE_TITLE", "content": "'.$booking_order['BookingOrder']['service_title'].'"},';
				        $global_merge_vars .= '{"name": "PAX", "content": "'.$booking_order['BookingOrder']['no_participants'].'"},';
				        $global_merge_vars .= '{"name": "DATE", "content": "'.date('Y-m-d',strtotime($booking_order['BookingOrder']['booking_date'])).'"},';
				        $global_merge_vars .= '{"name": "SLOT_DATE", "content": "'.date('Y-m-d',strtotime($booking_order['BookingOrder']['start_date'])).' - '.date('Y-m-d',strtotime($booking_order['BookingOrder']['end_date'])).'"},';
				        $global_merge_vars .= '{"name": "VENDOR_NAME", "content": "'.$booking_order['BookingOrder']['vendor_name'].'"},';
				        $global_merge_vars .= '{"name": "PHONE", "content": "'.$booking_order['BookingOrder']['vendor_phone'].'"},';
				        $global_merge_vars .= '{"name": "TOTAL_PRICE", "content": "'.$booking_order['BookingOrder']['total_amount'].'"},';
			        	$global_merge_vars .= '{"name": "MESSAGE", "content": "For the cancellation of booking, please contact the vendor."},';
				        $global_merge_vars .= '{"name": "VENDORADDRESS", "content": "'.$booking_order['BookingOrder']['vendor_email'].'"}';
				        $global_merge_vars .= ']';

				        $data_string = '{
				                "key": "RcGToklPpGQ56uCAkEpY5A",
				                "template_name": "minimum_to_go_not_reached",
				                "template_content": [
				                        {
				                                "name": "TITLE",
				                                "content": "test test test"
				                        }
				                ],
				                "message": {
				                        "subject": "Mimimum to go not reached",
				                        "from_email": "admin@waterspot.com.sg",
				                        "from_name": "Waterspot Admin",
				                        "to": [
				                                {
				                                        "email": "'.$mail_to.'",
				                                        "type": "to"
				                                }
				                        ],
				                        "global_merge_vars": '.$global_merge_vars.'
				                }
				        }';

				        $ch = curl_init('https://mandrillapp.com/api/1.0/messages/send-template.json');                                                                      
						curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
						curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
						curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
						    'Content-Type: application/json',                                                                                
						    'Content-Length: ' . strlen($data_string))                                                                       
						);                                                                                                                   
						                                                                                                                     
						$result = curl_exec($ch);

			        }
				}
			}
		}
    }
}
