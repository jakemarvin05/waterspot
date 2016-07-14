<?php

class MinToGoCheckTask extends Shell {
	public $uses = array('Scheduler', 'VendorManager.BookingSlot', 'VendorManager.BookingOrder', 'Booking', 'Service', 'MemberManager.Member');
    public function execute() {
    	
		$time = date('Y-m-d H:i:s', time() + 60*60*24*2);
		$now = date('Y-m-d H:i:s', time() + 60*60*24*2 - 60*65);

		$near_slots = $this->BookingSlot->find('all', ['conditions' => ["start_time BETWEEN '$now' AND '$time'"], 'group' => 'ref_no' ]);
		echo "Checking slots between $now - $time\n";
		echo "\n\n" . count($near_slots) . " activity found!\n\n";
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
					
					$slot_time = date('H:ia', strtotime($booking_slot['start_time'])) . ' - ' . date('H:ia', strtotime($booking_slot['end_time']));

					$global_merge_vars = '[';
			        $global_merge_vars .= '{"name": "USER_NAME", "content": "'.$booking_order['BookingOrder']['vendor_name'].'"},';
			        $global_merge_vars .= '{"name": "SERVICE_TITLE", "content": "'.$booking_order['BookingOrder']['service_title'].'"},';
			        $global_merge_vars .= '{"name": "PAX", "content": "'.$booking_order['BookingOrder']['no_participants'].'"},';
			        $global_merge_vars .= '{"name": "DATE", "content": "'.date('Y-m-d',strtotime($booking_order['BookingOrder']['booking_date'])).'"},';
			        $global_merge_vars .= '{"name": "SLOT_DATE", "content": "'.$slot_time.'"},';
			        $global_merge_vars .= '{"name": "VENDOR_NAME", "content": "'.$booking_order['BookingOrder']['vendor_name'].'"},';
			        $global_merge_vars .= '{"name": "PHONE", "content": "'.$booking_order['BookingOrder']['vendor_phone'].'"},';
			        $global_merge_vars .= '{"name": "MESSAGE", "content": "For the cancellation of booking, please contact the members that booked the service."}';
			        $global_merge_vars .= ']';

			        $data_string = '{
			                "key": "'.Configure::read('Mandrill.key').'",
			                "template_name": "vendor-min-to-go-not-reached",
			                "template_content": [
			                        {
			                                "name": "TITLE",
			                                "content": "Mimimum to go not reached"
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
			                        "merge_language": "handlebars",
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

			        	$memberinfo = $this->Member->read(null,$booking['Booking']['member_id']);
						if (!empty($memberinfo)) {
							$member_name = (strlen(trim($memberinfo['Member']['first_name'].' '.$memberinfo['Member']['last_name'])) > 0 ) ? $memberinfo['Member']['first_name'].' '.$memberinfo['Member']['last_name'] : 'Member';
						} else if(strlen(trim($booking['Booking']['fname']." ".$booking['Booking']['lname'])) > 0) {
							$member_name = $booking['Booking']['fname']." ".$booking['Booking']['lname'];
						} else {
							$member_name = 'Member';
						}

			        	// send email to booked & paid users
						$global_merge_vars = '[';
				        $global_merge_vars .= '{"name": "USER_NAME", "content": "'.$member_name.'"},';
				        $global_merge_vars .= '{"name": "SERVICE_TITLE", "content": "'.$booking_order['BookingOrder']['service_title'].'"},';
				        $global_merge_vars .= '{"name": "PAX", "content": "'.$booking_order['BookingOrder']['no_participants'].'"},';
				        $global_merge_vars .= '{"name": "DATE", "content": "'.date('Y-m-d',strtotime($booking_order['BookingOrder']['booking_date'])).'"},';
				        $global_merge_vars .= '{"name": "SLOT_DATE", "content": "'.$slot_time.'"},';
				        $global_merge_vars .= '{"name": "VENDOR_NAME", "content": "'.$booking_order['BookingOrder']['vendor_name'].'"},';
				        $global_merge_vars .= '{"name": "PHONE", "content": "'.$booking_order['BookingOrder']['vendor_phone'].'"},';
			        	$global_merge_vars .= '{"name": "MESSAGE", "content": "For the cancellation of booking, please contact the vendor."}';
				        $global_merge_vars .= ']';

				        $data_string = '{
				                "key": "'.Configure::read('Mandrill.key').'",
				                "template_name": "vendor-min-to-go-not-reached",
				                "template_content": [
				                        {
				                                "name": "TITLE",
				                                "content": "Mimimum to go not reached"
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
				                        "merge_language": "handlebars",
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
