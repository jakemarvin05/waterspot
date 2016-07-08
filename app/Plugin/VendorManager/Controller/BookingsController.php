<?php
Class BookingsController extends VendorManagerAppController{
	public $uses = array('Booking','Cart');
	public $components = array('Email');
	public $paginate = array();
	public $ajax_session_name= "Ajax_Files";
	public $id = null;
	
	function bookingNotification() {
		$vendor_id=$this->VendorAuth->id();
		if(empty($vendor_id)) {
				$this->redirect($this->VendorAuth->loginRedirect);
		}
		$this->loadModel('Cart');
		$this->loadModel('MailManager.Mail');		
		
		$curr_date = date('Y-m-d h:m:s', time());
		$criteria = array();
		$criteria['conditions']= array('Cart.vendor_id'=>$vendor_id,'Cart.end_date <' =>$curr_date, 'vendor_confirm' => array(1,3));	
		$criteria['fields']=array('Cart.*');		
		$cartData=$this->Cart->find('all',$criteria);
		$mail=$this->Mail->read(null,29);		
		$this->loadModel('MemberManager.Member');
		
		$detail_row='';
		if(!empty($cartData)){
			foreach($cartData as $data){				
				$memberinfo=$this->Member->read(null,$data['Cart']['member_id']);				
				$detail_row .= '<tr>
					<td>'.$memberinfo["Member"]["first_name"].' '.$memberinfo["Member"]["last_name"].'</td>
					<td>'.$memberinfo["Member"]["phone"].'</td>
					<td>'.$data["Cart"]["service_title"].'</td>
					<td>'.date('Y-m-d',strtotime($data["Cart"]["booking_date"])).'</td>
					<td>'.date('Y-m-d',strtotime($data["Cart"]["start_date"])).'</td>
					<td>'.date('Y-m-d',strtotime($data["Cart"]["end_date"])).'</td>
					<td style="text-align: center;">'.$data["Cart"]["no_participants"].'</td>					
					<td>'.$data["Cart"]["total_amount"].'</td>
					</tr>';
			}
			$BookingDetailTxt = '<table border="0" cellpadding="0" cellspacing="0" height="71" style="padding: 0px; margin: 0px 0px 15px; border: 0px none; border-collapse: collapse; background: none repeat scroll 0% 0% rgb(252, 252, 252); color: rgb(120, 120, 120); font-family: &quot;Arial&quot;,Helvetica,sans-serif; font-size: 13px; font-weight: normal;" width="521">
			<tbody>
				<tr>
					<th style="background:#CECECE;border:solid 1px #E2E2E2;color:#212121;padding:3px 5px;text-align:left;font-weight:500;" width="12%">{USER-COLUMN}</th>
					<th style="background:#CECECE;border:solid 1px #E2E2E2;color:#212121;padding:3px 5px;text-align:left;font-weight:500;" width="16%">Phone</th>
					<th style="background:#CECECE;border:solid 1px #E2E2E2;color:#212121;padding:3px 5px;text-align:left;font-weight:500;" width="16%">Service</th>
					<th style="background:#CECECE;border:solid 1px #E2E2E2;color:#212121;padding:3px 5px;text-align:left;font-weight:500;" width="10%">Booked Date</th>
					<th style="background:#CECECE;border:solid 1px #E2E2E2;color:#212121;padding:3px 5px;text-align:left;font-weight:500;" width="10%">Start Date</th>
					<th style="background:#CECECE;border:solid 1px #E2E2E2;color:#212121;padding:3px 5px;text-align:left;font-weight:500;" width="10%">End Date</th>
					<th style="background:#CECECE;border:solid 1px #E2E2E2;color:#212121;padding:3px 5px;text-align:left;font-weight:500;" width="15%">Participant(s)</th>
					<th style="background:#CECECE;border:solid 1px #E2E2E2;color:#212121;padding:3px 5px;text-align:left;font-weight:500;" width="10%">Total Amount($)</th>
				</tr>{DETAILROW}
			</tbody>
			</table>';
			
			//Notify mail Variables for Vendor
			$DETAILS_vendor=str_replace('{DETAILROW}',$detail_row,$BookingDetailTxt);
			$DETAILS_vendor=str_replace('{USER-COLUMN}','Member Name',$DETAILS_vendor);
			
			$body=str_replace('{RECEIVER-NAME}',$this->VendorAuth->results['VendorAuth']['fname'].' '.$this->VendorAuth->results['VendorAuth']['lname'],$mail['Mail']['mail_body']);
			$body=str_replace('{BOOKINDETAILS}',$DETAILS_vendor,$body);
			
			//Notify mail for Vendor	
			$email = new CakeEmail();

			
			//$email->to('shivram.yadav@newmediaguru.org');
			$email->to($this->VendorAuth->results['VendorAuth']['email']);
			$email->subject($mail['Mail']['mail_subject']);
			$email->from($this->setting['site']['site_contact_email']);
	
			$email->emailFormat('html');
			$email->template('default');
			$email->viewVars(array('data'=>$body,'logo'=>$this->setting['site']['logo'],'url'=>$this->setting['site']['site_url']));
			$email->send();
			
			//Notify mail Variables for Members			
			foreach($cartData as $data1){				
				$memberinfo=$this->Member->read(null,$data1['Cart']['member_id']);				
				$detail_rows ='';
				$detail_rows .= '<tr>
					<td>'.$data1['Cart']['vendor_name'].'</td>
					<td>'.$data1['Cart']['vendor_phone'].'</td>
					<td>'.$data1["Cart"]["service_title"].'</td>
					<td>'.date('Y-m-d',strtotime($data1["Cart"]["booking_date"])).'</td>
					<td>'.date('Y-m-d',strtotime($data1["Cart"]["start_date"])).'</td>
					<td>'.date('Y-m-d',strtotime($data1["Cart"]["end_date"])).'</td>
					<td style="text-align: center;">'.$data1["Cart"]["no_participants"].'</td>					
					<td>'.$data1["Cart"]["total_amount"].'</td>
					</tr>';
				$DETAILS=str_replace('{DETAILROW}',$detail_rows,$BookingDetailTxt);
				$DETAILS=str_replace('{USER-COLUMN}','Vendor Name',$DETAILS);
				$bodyMember=str_replace('{RECEIVER-NAME}',$memberinfo["Member"]["first_name"].' '.$memberinfo["Member"]["last_name"],$mail['Mail']['mail_body']);
				$bodyMember=str_replace('{BOOKINDETAILS}',$DETAILS,$bodyMember);
				//pr($bodyMember);
				
				//Notify mail for Members	
				$email = new CakeEmail();

				
				//$email->to('shivram.yadav@newmediaguru.org');
				$email->to($memberinfo["Member"]["email_id"]);
				$email->subject($mail['Mail']['mail_subject']);
				$email->from($this->setting['site']['site_contact_email']);
		
				$email->emailFormat('html');
				$email->template('default');
				$email->viewVars(array('data'=>$body,'logo'=>$this->setting['site']['logo'],'url'=>$this->setting['site']['site_url']));
				$email->send();
				
				$this->Cart->delete($data['Cart']['id']);
			}			
			
			//return;
		}
	}	
	
	
	function booking_request() {
	//	$this->bookingNotification();
		array_push(self::$css_for_layout,'vendor/vendor-panel.css');
		$this->paginate = array();
		$this->loadModel('Cart');
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/'),
			'name'=>'Home'
		    );
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/vendor/dashboard/'),
			'name'=>'Dashboard'
		);
		$this->breadcrumbs[] = array(
                    'url'=>Router::url('/vendor/booking_request'),
                    'name'=>'Booking Requests'
			);
		
		$vendor_id=$this->VendorAuth->id();
			
		$this->paginate = array('joins'=>
			array(
				array(
					'table'=>'members',
					'alias'=>'Member',
					'type'=>'LEFT',
					'conditions'=>array('Member.id = Cart.member_id')
				)
	
			),
			'conditions' => array('Cart.vendor_id'=>$vendor_id,'vendor_confirm'=>3,'status'=>0),			
			'fields'=>array('Cart.*','Member.*'),
			'limit'=>20,
			'order'=>array('Cart.id'=>'DESC')
		);
		$data=$this->paginate("Cart");		
		$this->set('booking_requests',$data);
	}
	
	function accept_request($cart_id=null){

		$this->loadModel('Cart');
		$this->loadModel('MailManager.Mail');
		$cart = $this->Cart->find('first', array('conditions' => array('Cart.id' =>$cart_id,'Cart.status' =>1,'Cart.vendor_confirm' =>3)));
		
		if(!empty($cart)){
			$update_cart['Cart']['id'] = $cart['Cart']['id'];
			$update_cart['Cart']['vendor_confirm'] = 1;
			$this->Cart->save($update_cart);
			
			//send mail to the member
			$this->loadModel('MemberManager.Member');
			$memberinfo=$this->Member->read(null,$cart['Cart']['member_id']);		
			$mail=$this->Mail->read(null,28);
			//create eamil for Member
			$thanksTxt = 'Thank you for showing interest in '.$cart['Cart']['service_title'];
			$body=str_replace('{USER-NAME}',$memberinfo['Member']['first_name'].' '.$memberinfo['Member']['last_name'],$mail['Mail']['mail_body']);
			$body=str_replace('{EMAIL}',$memberinfo['Member']['email_id'],$body);		
			$body=str_replace('{PHONE}',$memberinfo['Member']['phone'],$body);
			$body=str_replace('{THANKSTXT}',$thanksTxt,$body);
			$body=str_replace('{RESPONSE}','ACCEPTED',$body);
			$body=str_replace('{NAME}',$memberinfo['Member']['first_name'].' '.$memberinfo['Member']['last_name'],$body);
			$body=str_replace('{VENDOR}',$cart['Cart']['vendor_name'],$body);
			$body=str_replace('{SERVICE}',$cart['Cart']['service_title'],$body);
			//$body=str_replace('{ACTIVITY}',$cart['Cart']['service_title'],$body);
			$body=str_replace('{DATE}',date('Y-m-d',strtotime($cart['Cart']['booking_date'])),$body);
			$body=str_replace('{STARTDATE}',date('Y-m-d',strtotime($cart['Cart']['start_date'])),$body);
			$body=str_replace('{ENDDATE}',date('Y-m-d',strtotime($cart['Cart']['end_date'])),$body);
			$body=str_replace('{PARTICIPANT}',$cart['Cart']['no_participants'],$body);
			$body=str_replace('{VAS}',$cart['Cart']['service_title'],$body);
			$body=str_replace('{PRICE}',$cart['Cart']['total_amount'],$body);
			//pr($body);die;
			$email = new CakeEmail();

			
			$email->to($memberinfo['Member']['email_id']);
			//$email->to($this->setting['site']['site_contact_email']);
			$email->subject($mail['Mail']['mail_subject']);
			$email->from($cart['Cart']['vendor_email']);
	
			$email->emailFormat('html');
			$email->template('default');
			$email->viewVars(array('data'=>$body,'logo'=>$this->setting['site']['logo'],'url'=>$this->setting['site']['site_url']));
			// $email->send();
			// do not send!

			// $this->Session->setFlash(__('Booking has been accepeted successfully.'));
			$this->redirect(array('plugin'=>false,'controller'=>'carts','action'=>'add_order', $cart_id, $cart['Cart']['service_id']));
		}else{
			$this->Session->setFlash('Sorry! Booking id does not found.','','error');
			$this->redirect(array('plugin'=>'vendor_manager','controller'=>'bookings','action'=>'booking_request'));
		}
		
	}

	function accept_paid($booking_id)
	{
		$this->loadModel('VendorManager.Booking');
		$this->loadModel('VendorManager.BookingOrder');
		$this->loadModel('MailManager.Mail');
		$booking = $this->Booking->find('first', array('conditions' => array('Booking.id' =>$booking_id,'Booking.status' => 1,'Booking.vendor_confirm' =>3)));

		if(!empty($booking)){
			$update_booking['Booking']['id'] = $booking['Booking']['id'];
			$update_booking['Booking']['vendor_confirm'] = 1;
			$this->Booking->save($update_booking);
			
			//send mail to the member
			$this->loadModel('MemberManager.Member');
			$this->loadModel('VendorManager.Vendor');
			$memberinfo = $this->Member->read(null,$booking['Booking']['member_id']);
			$booking_order = $this->BookingOrder->find('first', ['conditions' => ['ref_no' => $booking['Booking']['ref_no']]]);

			$full_name = (strlen(trim($memberinfo['Member']['first_name'].' '.$memberinfo['Member']['last_name'])) > 0 ) ? $memberinfo['Member']['first_name'].' '.$memberinfo['Member']['last_name'] : $booking['Booking']['fname'];

			$slots = json_decode($booking_order['BookingOrder']['slots']);
			$slot_string = '';
			foreach ($slots as $slot_data) {
				foreach ($slot_data as $slot) {
					if ($slot_string !== '') $slot_string .= ', ';
					$slot_string .= date('Y-m-d', $slot->slot_date)
								 . ' (' . date('h:ia', strtotime($slot->start_time))
								 . ' - ' . date('h:ia', strtotime($slot->end_time))
								 . ')';
				}
			}
			if ($slot_string === '') {
				$slot_string = 'None';
			}

			$this->loadModel('Coupon');
			$discount = 0;
			$price_str = '$'.number_format($booking_order['BookingOrder']['total_amount'], 2);
			if ($booking_order['BookingOrder']['coupon_id']) {
				$coupon = $this->Coupon->find('first', ['conditions' => ['id' => $booking_order['BookingOrder']['coupon_id']]]);
				$discount = $coupon['Coupon']['discount'];
				$price_str = '<span style="text-decoration:line-through; color:#F00;">'.$price_str.'</span>$'. number_format($booking_order['BookingOrder']['total_amount'] * (1 - $discount), 2);
			}

			$to = $memberinfo ? $memberinfo['Member']['email_id'] : $booking_order['BookingOrder']['guest_email'];

			$value_added_services_array = [];

			if($booking_order['BookingOrder']['value_added_services']){
				foreach($booking_order['BookingOrder']['value_added_services'] as $service){
					$value_added_services_array[] = $service;
				}
			}
			$value_added_services = '';
			$value_added_services .= implode(',', $value_added_services_array);


			$global_merge_vars = '[';
	        $global_merge_vars .= '{"name": "NAME", "content": "'.$full_name.'"},';
	        $global_merge_vars .= '{"name": "ORDERNO", "content": "'.$booking_order['BookingOrder']['ref_no'].'"},';
	        $global_merge_vars .= '{"name": "SERVICE_TITLE", "content": "'.$booking_order['BookingOrder']['service_title'].'"},';
	        $global_merge_vars .= '{"name": "PAX", "content": "'.$booking_order['BookingOrder']['no_participants'].'"},';
	        $global_merge_vars .= '{"name": "DATE", "content": "'.date('Y-m-d',strtotime($booking_order['BookingOrder']['booking_date'])).'"},';
	        $global_merge_vars .= '{"name": "VENDOR_NAME", "content": "'.$booking_order['BookingOrder']['vendor_name'].'"},';
	        $global_merge_vars .= '{"name": "PARTICIPANTS", "content": "'.$booking_order['BookingOrder']['participants'].'"},';
	        $global_merge_vars .= '{"name": "SLOTS", "content": "'.$slot_string.'"},';
	        $global_merge_vars .= '{"name": "VAS", "content": "'.$value_added_services.'"},';
	        $global_merge_vars .= '{"name": "PHONE", "content": "'.$booking['Booking']['phone'].'"},';
	        $global_merge_vars .= '{"name": "EMAIL", "content": "'.$to.'"},';
	        $global_merge_vars .= '{"name": "TOTAL", "content": "'.str_replace(['"', "\n", "\t"],['\'', "", ""],$price_str).'"},';
	        $global_merge_vars .= '{"name": "VENDORADDRESS", "content": "'.$booking_order['BookingOrder']['vendor_email'].'"}';
	        $global_merge_vars .= ']';


	        $data_string = '{
	                "key": '.Configure::read('Mandrill.key').',
	                "template_name": "user-booking-confirmation-1",
	                "template_content": [
	                        {
	                                "name": "TITLE",
	                                "content": "Booking Confirmation"
	                        }
	                ],
	                "message": {
	                        "subject": "Booking Confirmation",
	                        "from_email": "'.$booking_order['BookingOrder']['vendor_email'].'",
	                        "from_name": "'.$booking_order['BookingOrder']['vendor_name'].'",
	                        "to": [
	                                {
	                                        "email": "'.$to.'",
	                                        "name": "'.$full_name.'",
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

			$this->Session->setFlash('Booking has been accepeted successfully.','','message');
		}else{
			$this->Session->setFlash('Sorry! Booking id was not found.','','error');
		}
		$this->redirect(array('plugin'=>'vendor_manager','controller'=>'bookings','action'=>'booking_list'));
	}

	function cancel_paid($booking_id)
	{
		$this->loadModel('VendorManager.Booking');
		$this->loadModel('VendorManager.BookingOrder');
		$this->loadModel('MailManager.Mail');
		$booking = $this->Booking->find('first', array('conditions' => array('Booking.id' => $booking_id,'Booking.status' => 1,'Booking.vendor_confirm' =>3)));
		
		if(!empty($booking)){
			$update_booking['Booking']['id'] = $booking['Booking']['id'];
			$update_booking['Booking']['vendor_confirm'] = 2;
			$this->Booking->save($update_booking);
			
			//send mail to the member
			$this->loadModel('MemberManager.Member');
			$this->loadModel('VendorManager.Vendor');
			$memberinfo = $this->Member->read(null,$booking['Booking']['member_id']);
			$booking_order = $this->BookingOrder->find('first', ['conditions' => ['ref_no' => $booking['Booking']['ref_no']]]);

			$full_name = (strlen(trim($memberinfo['Member']['first_name'].' '.$memberinfo['Member']['last_name'])) > 0 ) ? $memberinfo['Member']['first_name'].' '.$memberinfo['Member']['last_name'] : $booking['Booking']['fname'];

			$slots = json_decode($booking_order['BookingOrder']['slots']);
			$slot_string = '';
			foreach ($slots as $slot_data) {
				foreach ($slot_data as $slot) {
					if ($slot_string !== '') $slot_string .= ', ';
					$slot_string .= date('Y-m-d', $slot->slot_date)
								 . ' (' . date('h:ia', strtotime($slot->start_time))
								 . ' - ' . date('h:ia', strtotime($slot->end_time))
								 . ')';
				}
			}
			if ($slot_string === '') {
				$slot_string = 'None';
			}

			$this->loadModel('Coupon');
			$discount = 0;
			$price_str = '$'.number_format($booking_order['BookingOrder']['total_amount'], 2);
			if ($booking_order['BookingOrder']['coupon_id']) {
				$coupon = $this->Coupon->find('first', ['conditions' => ['id' => $booking_order['BookingOrder']['coupon_id']]]);
				$discount = $coupon['Coupon']['discount'];
				$price_str = '<span style="text-decoration:line-through; color:#F00;">'.$price_str.'</span>$'. number_format($booking_order['BookingOrder']['total_amount'] * (1 - $discount), 2);
			}

			$to = $memberinfo ? $memberinfo['Member']['email_id'] : $booking_order['BookingOrder']['guest_email'];
			$payment_status = ['Not completed','Completed','Processing','Cancelled'];

			$value_added_services_array = [];

			if($booking_order['BookingOrder']['value_added_services']){
				foreach($booking_order['BookingOrder']['value_added_services'] as $service){
					$value_added_services_array[] = $service;
				}
			}

			$value_added_services = '';
			$value_added_services .= implode(',', $value_added_services_array);

			$global_merge_vars = '[';
	        $global_merge_vars .= '{"name": "NAME", "content": "'.$full_name.'"},';
	        $global_merge_vars .= '{"name": "EMAIL", "content": "'.$to.'"},';
	        $global_merge_vars .= '{"name": "ORDERNO", "content": "'.$booking_order['BookingOrder']['ref_no'].'"},';
	        $global_merge_vars .= '{"name": "PAYMENT_STATUS", "content": "'.$payment_status[$booking['Booking']['status']].'"},';
	        $global_merge_vars .= '{"name": "TXN_ID", "content": "'.$booking['Booking']['transaction_id'].'"},';
	        $global_merge_vars .= '{"name": "SERVICE_TITLE", "content": "'.$booking_order['BookingOrder']['service_title'].'"},';
	        $global_merge_vars .= '{"name": "PAX", "content": "'.$booking_order['BookingOrder']['no_participants'].'"},';
	        $global_merge_vars .= '{"name": "DATE", "content": "'.date('Y-m-d',strtotime($booking_order['BookingOrder']['booking_date'])).'"},';
	        $global_merge_vars .= '{"name": "SLOT_DATE", "content": "'.$slot_string.'"},';
	        $global_merge_vars .= '{"name": "SLOT_DATE", "content": "'.$value_added_services.'"},';
	        $global_merge_vars .= '{"name": "VENDOR_NAME", "content": "'.$booking_order['BookingOrder']['vendor_name'].'"},';
	        $global_merge_vars .= '{"name": "PHONE", "content": "'.$booking['Booking']['phone'].'"},';
	        $global_merge_vars .= '{"name": "TOTAL_PRICE", "content": "'.str_replace(['"', "\n", "\t"],['\'', "", ""],$price_str).'"},';
	        $global_merge_vars .= '{"name": "VENDORADDRESS", "content": "'.$booking_order['BookingOrder']['vendor_email'].'"}';
	        $global_merge_vars .= ']';


	        $data_string = '{
	                "key": '.Configure::read('Mandrill.key').',
	                "template_name": "user-booking-failed",
	                "template_content": [
	                        {
	                                "name": "TITLE",
	                                "content": "Booking Request Declined"
	                        }
	                ],
	                "message": {
	                        "subject": "Booking Declined",
	                        "from_email": "'.$booking_order['BookingOrder']['vendor_email'].'",
	                        "from_name": "'.$booking_order['BookingOrder']['vendor_name'].'",
	                        "to": [
	                                {
	                                        "email": "'.$to.'",
	                                        "name": "'.$full_name.'",
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
			
			$this->Session->setFlash('Booking has been decline successfully.','','message');
		}else{
			$this->Session->setFlash('Sorry! Booking id does not found.','','error');
		}
		$this->redirect(array('plugin'=>'vendor_manager','controller'=>'bookings','action'=>'booking_list'));
		
	}

	function admin_accept_paid($booking_id)
	{
		$this->loadModel('VendorManager.Booking');
		$this->loadModel('VendorManager.BookingOrder');
		$this->loadModel('MailManager.Mail');
		$booking = $this->Booking->find('first', array('conditions' => array('Booking.id' =>$booking_id,'Booking.status' => 1,'Booking.vendor_confirm' =>3)));

		if(!empty($booking)){
			$update_booking['Booking']['id'] = $booking['Booking']['id'];
			$update_booking['Booking']['vendor_confirm'] = 1;
			$this->Booking->save($update_booking);
			
			//send mail to the member
			$this->loadModel('MemberManager.Member');
			$this->loadModel('VendorManager.Vendor');
			$memberinfo = $this->Member->read(null,$booking['Booking']['member_id']);
			$booking_order = $this->BookingOrder->find('first', ['conditions' => ['ref_no' => $booking['Booking']['ref_no']]]);

			$full_name = (strlen(trim($memberinfo['Member']['first_name'].' '.$memberinfo['Member']['last_name'])) > 0 ) ? $memberinfo['Member']['first_name'].' '.$memberinfo['Member']['last_name'] : $booking['Booking']['fname'];

			$slots = json_decode($booking_order['BookingOrder']['slots']);
			$slot_string = '';
			foreach ($slots as $slot_data) {
				foreach ($slot_data as $slot) {
					if ($slot_string !== '') $slot_string .= ', ';
					$slot_string .= date('Y-m-d', $slot->slot_date)
								 . ' (' . date('h:ia', strtotime($slot->start_time))
								 . ' - ' . date('h:ia', strtotime($slot->end_time))
								 . ')';
				}
			}
			if ($slot_string === '') {
				$slot_string = 'None';
			}

			$this->loadModel('Coupon');
			$discount = 0;
			$price_str = '$'.number_format($booking_order['BookingOrder']['total_amount'], 2);
			if ($booking_order['BookingOrder']['coupon_id']) {
				$coupon = $this->Coupon->find('first', ['conditions' => ['id' => $booking_order['BookingOrder']['coupon_id']]]);
				$discount = $coupon['Coupon']['discount'];
				$price_str = '<span style="text-decoration:line-through; color:#F00;">'.$price_str.'</span>$'. number_format($booking_order['BookingOrder']['total_amount'] * (1 - $discount), 2);
			}

			$to = $memberinfo ? $memberinfo['Member']['email_id'] : $booking_order['BookingOrder']['guest_email'];

			$value_added_services_array = [];

			if($booking_order['BookingOrder']['value_added_services']){
				foreach($booking_order['BookingOrder']['value_added_services'] as $service){
					$value_added_services_array[] = $service;
				}
			}
			$value_added_services = '';
			$value_added_services .= implode(',', $value_added_services_array);


			$global_merge_vars = '[';
			$global_merge_vars .= '{"name": "USER_NAME", "content": "'.$full_name.'"},';
			$global_merge_vars .= '{"name": "ORDERNO", "content": "'.$booking_order['BookingOrder']['ref_no'].'"},';
			$global_merge_vars .= '{"name": "SERVICE_TITLE", "content": "'.$booking_order['BookingOrder']['service_title'].'"},';
			$global_merge_vars .= '{"name": "PAX", "content": "'.$booking_order['BookingOrder']['no_participants'].'"},';
			$global_merge_vars .= '{"name": "DATE", "content": "'.date('Y-m-d',strtotime($booking_order['BookingOrder']['booking_date'])).'"},';
			$global_merge_vars .= '{"name": "VENDOR_NAME", "content": "'.$booking_order['BookingOrder']['vendor_name'].'"},';
			$global_merge_vars .= '{"name": "PARTICIPANTS", "content": "'.$booking_order['BookingOrder']['participants'].'"},';
			$global_merge_vars .= '{"name": "PRICE", "content": "'.$booking_order['BookingOrder']['price'].'"},';
			$global_merge_vars .= '{"name": "TOTAL", "content": "'.$booking_order['BookingOrder']['total_amount'].'"},';
			$global_merge_vars .= '{"name": "SLOTS", "content": "'.$slot_string.'"},';
			$global_merge_vars .= '{"name": "VAS", "content": "'.$value_added_services.'"},';
			$global_merge_vars .= '{"name": "PHONE", "content": "'.$booking['Booking']['phone'].'"},';
			$global_merge_vars .= '{"name": "EMAIL", "content": "'.$to.'"},';
			$global_merge_vars .= '{"name": "TOTAL_PRICE", "content": "'.str_replace(['"', "\n", "\t"],['\'', "", ""],$price_str).'"},';
			$global_merge_vars .= '{"name": "VENDORADDRESS", "content": "'.$booking_order['BookingOrder']['vendor_email'].'"}';
			$global_merge_vars .= ']';

	        $data_string = '{
	                "key": '.Configure::read('Mandrill.key').',
	                "template_name": "user-booking-confirmation-1",
	                "template_content": [
	                        {
	                                "name": "TITLE",
	                                "content": "User Booking Confirmation"
	                        }
	                ],
	                "message": {
	                        "subject": "Booking Confirmation",
	                        "from_email": "'.$booking_order['BookingOrder']['vendor_email'].'",
	                        "from_name": "'.$booking_order['BookingOrder']['vendor_name'].'",
	                        "to": [
	                                {
	                                        "email": "'.$to.'",
	                                        "name": "'.$full_name.'",
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

			$this->Session->setFlash('Booking has been accepeted successfully.','','message');
		}else{
			$this->Session->setFlash('Sorry! Booking id was not found.','','error');
		}
		$ref = Controller::referer();
		if ($ref != '/') {
			$this->redirect($ref);
		} else {
			$this->redirect(array('plugin'=>null,'controller'=>'bookings','action'=>'index'));
		}
	}

	function admin_cancel_paid($booking_id)
	{
		$this->loadModel('VendorManager.Booking');
		$this->loadModel('VendorManager.BookingOrder');
		$this->loadModel('MailManager.Mail');
		$booking = $this->Booking->find('first', array('conditions' => array('Booking.id' => $booking_id,'Booking.status' => 1,'Booking.vendor_confirm' =>3)));
		
		if(!empty($booking)){
			$update_booking['Booking']['id'] = $booking['Booking']['id'];
			$update_booking['Booking']['vendor_confirm'] = 2;
			$this->Booking->save($update_booking);
			
			//send mail to the member
			$this->loadModel('MemberManager.Member');
			$this->loadModel('VendorManager.Vendor');
			$memberinfo = $this->Member->read(null,$booking['Booking']['member_id']);
			$booking_order = $this->BookingOrder->find('first', ['conditions' => ['ref_no' => $booking['Booking']['ref_no']]]);

			$full_name = (strlen(trim($memberinfo['Member']['first_name'].' '.$memberinfo['Member']['last_name'])) > 0 ) ? $memberinfo['Member']['first_name'].' '.$memberinfo['Member']['last_name'] : $booking['Booking']['fname'];

			$slots = json_decode($booking_order['BookingOrder']['slots']);
			$slot_string = '';
			foreach ($slots as $slot_data) {
				foreach ($slot_data as $slot) {
					if ($slot_string !== '') $slot_string .= ', ';
					$slot_string .= date('Y-m-d', $slot->slot_date)
								 . ' (' . date('h:ia', strtotime($slot->start_time))
								 . ' - ' . date('h:ia', strtotime($slot->end_time))
								 . ')';
				}
			}
			if ($slot_string === '') {
				$slot_string = 'None';
			}

			$this->loadModel('Coupon');
			$discount = 0;
			$price_str = '$'.number_format($booking_order['BookingOrder']['total_amount'], 2);
			if ($booking_order['BookingOrder']['coupon_id']) {
				$coupon = $this->Coupon->find('first', ['conditions' => ['id' => $booking_order['BookingOrder']['coupon_id']]]);
				$discount = $coupon['Coupon']['discount'];
				$price_str = '<span style="text-decoration:line-through; color:#F00;">'.$price_str.'</span>$'. number_format($booking_order['BookingOrder']['total_amount'] * (1 - $discount), 2);
			}

			$to = $memberinfo ? $memberinfo['Member']['email_id'] : $booking_order['BookingOrder']['guest_email'];
			$payment_status = ['Not completed','Completed','Processing','Cancelled'];

			$value_added_services_array = [];

			if($booking_order['BookingOrder']['value_added_services']){
				foreach($booking_order['BookingOrder']['value_added_services'] as $service){
					$value_added_services_array[] = $service;
				}
			}

			$value_added_services = '';
			$value_added_services .= implode(',', $value_added_services_array);

			$global_merge_vars = '[';
			$global_merge_vars .= '{"name": "USER_NAME", "content": "'.$full_name.'"},';
			$global_merge_vars .= '{"name": "EMAIL", "content": "'.$to.'"},';
			$global_merge_vars .= '{"name": "ORDERNO", "content": "'.$booking_order['BookingOrder']['ref_no'].'"},';
			$global_merge_vars .= '{"name": "PAYMENT_STATUS", "content": "'.$payment_status[$booking['Booking']['status']].'"},';
			$global_merge_vars .= '{"name": "TXN_ID", "content": "'.$booking['Booking']['transaction_id'].'"},';
			$global_merge_vars .= '{"name": "SERVICE_TITLE", "content": "'.$booking_order['BookingOrder']['service_title'].'"},';
			$global_merge_vars .= '{"name": "PAX", "content": "'.$booking_order['BookingOrder']['no_participants'].'"},';
			$global_merge_vars .= '{"name": "VAS", "content": "'.$value_added_services.'"},';
			$global_merge_vars .= '{"name": "DATE", "content": "'.date('Y-m-d',strtotime($booking_order['BookingOrder']['booking_date'])).'"},';
			$global_merge_vars .= '{"name": "SLOT_DATE", "content": "'.$slot_string.'"},';
			$global_merge_vars .= '{"name": "VENDOR_NAME", "content": "'.$booking_order['BookingOrder']['vendor_name'].'"},';
			$global_merge_vars .= '{"name": "PHONE", "content": "'.$booking['Booking']['phone'].'"},';
			$global_merge_vars .= '{"name": "TOTAL_PRICE", "content": "'.str_replace(['"', "\n", "\t"],['\'', "", ""],$price_str).'"},';
			$global_merge_vars .= '{"name": "VENDORADDRESS", "content": "'.$booking_order['BookingOrder']['vendor_email'].'"}';
			$global_merge_vars .= ']';

			$data_string = '{
	                "key": '.Configure::read('Mandrill.key').',
	                "template_name": "user-booking-failed",
	                "template_content": [
	                        {
	                                "name": "TITLE",
	                                "content": "Booking Request Declined"
	                        }
	                ],
	                "message": {
	                        "subject": "Booking Declined",
	                        "from_email": "'.$booking_order['BookingOrder']['vendor_email'].'",
	                        "from_name": "'.$booking_order['BookingOrder']['vendor_name'].'",
	                        "to": [
	                                {
	                                        "email": "'.$to.'",
	                                        "name": "'.$full_name.'",
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
			
			$this->Session->setFlash('Booking has been decline successfully.','','message');
		}else{
			$this->Session->setFlash('Sorry! Booking id does not found.','','error');
		}
		$ref = Controller::referer();
		if ($ref != '/') {
			$this->redirect($ref);
		} else {
			$this->redirect(array('plugin'=>null,'controller'=>'bookings','action'=>'index'));
		}
		
	}
	
	function cancel_request($cart_id=null){
		$this->loadModel('Cart');
		$this->loadModel('MailManager.Mail');
		$cart = $this->Cart->find('first', array('conditions' => array('Cart.id' =>$cart_id,'Cart.status' =>0,'Cart.vendor_confirm' =>3, 'Cart.vendor_id' =>$this->VendorAuth->id())));
		if(!empty($cart)){
			
			//send mail to the member
			$this->loadModel('MemberManager.Member');
			$memberinfo=$this->Member->read(null,$cart['Cart']['member_id']);		
			$mail=$this->Mail->read(null,30);
			//create eamil for Member
			$body=str_replace('{USER-NAME}',$memberinfo['Member']['first_name'].' '.$memberinfo['Member']['last_name'],$mail['Mail']['mail_body']);
			$body=str_replace('{EMAIL}',$memberinfo['Member']['email_id'],$body);		
			$body=str_replace('{PHONE}',$memberinfo['Member']['phone'],$body);
			$body=str_replace('{RESPONSE}','DECLINED',$body);
			$body=str_replace('{NAME}',$memberinfo['Member']['first_name'].' '.$memberinfo['Member']['last_name'],$body);
			$body=str_replace('{VENDOR}',$cart['Cart']['vendor_name'],$body);
			$body=str_replace('{SERVICE}',$cart['Cart']['service_title'],$body);
			//$body=str_replace('{ACTIVITY}',$cart['Cart']['service_title'],$body);
			$body=str_replace('{DATE}',date('Y-m-d',strtotime($cart['Cart']['booking_date'])),$body);
			$body=str_replace('{STARTDATE}',date('Y-m-d',strtotime($cart['Cart']['start_date'])),$body);
			$body=str_replace('{ENDDATE}',date('Y-m-d',strtotime($cart['Cart']['end_date'])),$body);
			$body=str_replace('{PARTICIPANT}',$cart['Cart']['no_participants'],$body);
			$body=str_replace('{VAS}',$cart['Cart']['service_title'],$body);
			$body=str_replace('{PRICE}',$cart['Cart']['total_amount'],$body);
			//pr($body);die;
			$email = new CakeEmail();

			
			$email->to($memberinfo['Member']['email_id']);
			//$email->to($this->setting['site']['site_contact_email']);
			$email->subject($mail['Mail']['mail_subject']);
			$email->from($cart['Cart']['vendor_email']);
	
			$email->emailFormat('html');
			$email->template('default');
			$email->viewVars(array('data'=>$body,'logo'=>$this->setting['site']['logo'],'url'=>$this->setting['site']['site_url']));
			$email->send();
			
			$this->Cart->delete($cart_id);
			$this->Session->setFlash(__('Booking has been decline successfully.'));
			$this->redirect(array('plugin'=>'vendor_manager','controller'=>'bookings','action'=>'booking_request'));
		}else{
			$this->Session->setFlash('Sorry! Booking id does not found.','','error');
			$this->redirect(array('plugin'=>'vendor_manager','controller'=>'bookings','action'=>'booking_request'));
		}
		
	}
	
	function booking_list($search=null,$searchtext=null,$search_by_date=null,$searchbydate=null) {
		array_push(self::$css_for_layout,'vendor/vendor-panel.css');
		$this->loadModel('BookingSlot');
		$this->loadModel('Cart');
		$conditions=null;
		$this->paginate = array();
		$vendor_id=$this->VendorAuth->id();
		if($this->request->is('post')){
			$search=(!empty($this->request->data['Booking']['search']))?$this->request->data['Booking']['search']:'_blank';
			$searchtext=(!empty($this->request->data['Booking']['searchtext']))?$this->request->data['Booking']['searchtext']:'_blank';
			$search_by_date=(!empty($this->request->data['Booking']['search_by_date']))?$this->request->data['Booking']['search_by_date']:'_blank';
			$searchbydate=(!empty($this->request->data['Booking']['search_by_date']))?$this->request->data['Booking']['searchbydate']:'_blank';
			$this->redirect(array('plugin'=>'vendor_manager','controller'=>'bookings','action'=>'booking_list',$search,$searchtext,$search_by_date,$searchbydate));
        }else{
			// conditions
			if(($search!=null && $search!='_blank' && $searchtext!=null && $searchtext!='_blank')){
		        $searchtext = urldecode($searchtext);
                if($search=='fname' || $search=='lname') {
					$conditions['Booking.'.$search.' like'] = $searchtext.'%';
				}else {
					$conditions['Booking.'.$search] = $searchtext;
				}
				//assign in variable
				$this->request->data['Booking']['search']=$search;
				$this->request->data['Booking']['searchtext']=$searchtext;
		    }
            if(($search_by_date!=null && $search_by_date!='_blank' && $searchbydate!=null && $searchbydate!='_blank')){
				$search = urldecode($search);
				if($search_by_date=='booking_date'){
					$conditions['CAST(Booking.booking_date as DATE)'] = date('Y-m-d',strtotime($searchbydate));
				}
				if($search_by_date=='start_date'){
					$conditions['BookingOrder.start_date'] = date('Y-m-d',strtotime($searchbydate));
				}
				//assign in variable
				$this->request->data['Booking']['search_by_date']=$search_by_date;
				$this->request->data['Booking']['searchbydate']=$searchbydate;
            }
		}
		$this->paginate = array('joins'=>
			array(
				array(
					'table'=>'booking_orders',
					'alias'=>'BookingOrder',
					'type'=>'LEFT',
					'conditions'=>array('BookingOrder.ref_no = Booking.ref_no')
				)
	
			),
			'conditions'=>array('BookingOrder.vendor_id'=>$vendor_id),
			'group'=>'BookingOrder.ref_no',
		    'fields'=>array('Booking.*','BookingOrder.status'),
			'limit'=>20,
			'order'=>array('Booking.ref_no'=>'DESC')

		);
		$booking_details=$this->paginate("Booking",$conditions);
		// javascript set
		array_push(self::$script_for_layout,'https://code.jquery.com/jquery-1.9.1.js','https://code.jquery.com/ui/1.10.3/jquery-ui.js');
		array_push(self::$css_for_layout,'https://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css');
		self::$scriptBlocks[]='$(function() {
		$( "#BookingSearchbydate" ).datepicker({
		  dateFormat: "'.Configure::read('Calender_format').'",
		  changeMonth: false
		  })
		});' ;
		$this->set('booking_details',$booking_details);
		if($this->request->is('ajax')){
			$this->layout = '';
            $this->Render('ajax_booking_list');
        }
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/'),
			'name'=>'Home'
		    );
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/vendor/dashboard/'),
			'name'=>'Dashboard'
		);
		$this->breadcrumbs[] = array(
                    'url'=>Router::url('/vendor/booking_list'),
                    'name'=>'Booking List'
			);

		$bookings=array('joins'=>
			array(
				array(
					'table'=>'members',
					'alias'=>'Member',
					'type'=>'LEFT',
					'conditions'=>array('Member.id = Cart.member_id')
				)

			),
			'conditions' => array('Cart.vendor_id'=>$vendor_id,'vendor_confirm'=>3,'status'=>0),
			'fields'=>array('Cart.*','Member.*'),
			'limit'=>20,
			'order'=>array('Cart.id'=>'DESC')
		);
		$bookingRequest = $this->Cart->find('all',$bookings);
		$this->set('booking_request',$bookingRequest);

	}
	
	function booking_details($ref_no=null){
		array_push(self::$css_for_layout,'vendor/vendor-panel.css');
		if(empty($ref_no)){
			 $this->redirect('/');
		} 
		$criteria = array();
		$this->loadModel('BookingOrder');
		$this->loadModel('LocationManager.City');
		$vendor_id=$this->VendorAuth->id();
		$criteria['conditions']=array('BookingOrder.vendor_id'=>$vendor_id,'BookingOrder.ref_no'=>$ref_no);
		$criteria['group']=array('BookingOrder.id');
		$criteria['fields']=array('BookingOrder.*');
		$criteria['order']=array('BookingOrder.id ASC');
		//echo "<pre>";print_r($criteria);die;
		$order_details=$this->BookingOrder->find('all',$criteria);
		$customer_detail=$this->Booking->find('first',array('conditions'=>array('Booking.ref_no'=>$ref_no)));
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/'),
			'name'=>'Home'
		    );
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/vendor/dashboard/'),
			'name'=>'Dashboard'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/vendor/booking_details/'.$ref_no),
			'name'=>'Booking Details'
		);
		foreach($order_details as $key=>$order_detail){
			$order_details[$key]['BookingOrder']['location_name']=(!empty($order_detail['BookingOrder']['location_id']))?$this->City->getLocationListCityID($order_detail['BookingOrder']['location_id']): "Location not available";
			
			if (is_null($order_detail['BookingOrder']['coupon_id'])) {
				$order_details[$key]['BookingOrder']['discount'] = 0;
			} else {
				$this->loadModel('Coupon');
				$coupon = $this->Coupon->find('first', ['conditions' => ['id' => $order_detail['BookingOrder']['coupon_id']]]);
				$order_details[$key]['BookingOrder']['discount'] = $coupon['Coupon']['discount'] * $order_detail['BookingOrder']['total_amount'];
			}
		}
		$this->set('customer_detail',$customer_detail);
		$this->set('order_details',$order_details);
	}
	
	function booking_vas_details($booking_order_id=null){
		$this->layout='';
		$this->loadModel('BookingOrder');
		$value_added_services=$this->BookingOrder->find('first',array('conditions'=>array('BookingOrder.id'=>$booking_order_id),'fields'=>array('BookingOrder.value_added_services')));
		$this->set('value_added_services',json_decode($value_added_services['BookingOrder']['value_added_services'],true));
	}
	
	function booking_member_invite_details($booking_order_id=null){
		$this->layout='';
		$this->loadModel('BookingParticipate');
		$invites_friend_details=$this->BookingParticipate->find('all',array('conditions'=>array('BookingParticipate.booking_order_id'=>$booking_order_id)));
		$this->set('invites_friend_details',$invites_friend_details);
	}
	
	function booking_slot_details($booking_order_id=null){
		$this->layout='';
		$this->loadModel('BookingSlot');
		$booking_slots=$this->BookingSlot->find('all',array('conditions'=>array('BookingSlot.booking_order_id'=>$booking_order_id)));
		$this->set('booking_slots',$booking_slots);
	}
	
	function cancel_booking($ref_no=null){
		$this->autoRender=false;
		$this->loadModel('BookingOrder');
		$criteria = array();
			$criteria['joins'] = array(
				array(
					'table' => 'bookings',
					'alias' => 'Booking',
					'type' => 'INNER',
					'conditions' => array('Booking.ref_no =BookingOrder.ref_no','Booking.status' => 1)
				) 
    		);
			$criteria['conditions'] =array('BookingOrder.ref_no'=>$ref_no,'BookingOrder.status' => 1,'Booking.status' => 1,'BookingOrder.vendor_id' =>$this->VendorAuth->id());
			$criteria['group'] =array('BookingOrder.id');
			$booking_count_status=$this->BookingOrder->find('count',$criteria);
		
		if($booking_count_status>=1){
			self::__cancel_booking_mail_to_vendor($ref_no);
			$this->BookingOrder->updateAll(array('status'=>3), array('AND' => array('BookingOrder.ref_no'=>$ref_no,'BookingOrder.status' => 1,'BookingOrder.vendor_id' =>$this->VendorAuth->id())));
			$this->Session->setFlash(__('Booking has been cancelled successfully.'));
		}else{
			$this->Session->setFlash('Booking is already cancelled or not completed.','','error');
		}
		$this->redirect(Controller::referer());
	}
	// this function is not used.
	private function __cancel_booking_mail_to_vendor($ref_no=null){
		$criteria = array();
		$total_booking_price=0;
		$service_slot_details='';
		$vendor_details=($this->Session->read('VendorAuth.VendorAuth'));
		$this->loadModel('BookingOrder');
		$this->loadModel('Booking');
		$this->loadModel('VendorManager.ServiceImage');
		// find email whose booked status =1 
		$booking_emails=$this->Booking->find('list',array('conditions'=>array('Booking.ref_no'=>$ref_no,'Booking.status' => 1),'fields'=>('Booking.email'),'group'=>('Booking.email')));
		$booking_details=$this->BookingOrder->find('all',array('conditions'=>array('BookingOrder.ref_no'=>$ref_no,'BookingOrder.status' =>1,'BookingOrder.vendor_id'=>$this->VendorAuth->id())));
		if(!empty($booking_details)){
			foreach($booking_details as $booking_detail) {
				$slots=json_decode($booking_detail['BookingOrder']['slots'],true);
				
				// clear slot_detials in loop
				$slot_details=array();
				if(!empty($slots['Slot'])){
					foreach($slots['Slot'] as $key=>$slot) {
						$slot_details[]=DATE("g:i A", STRTOTIME($slot['start_time']))." To ".DATE("g:i A", STRTOTIME($slot['end_time'])+1);
					}
				}
				//get service image
				$service_image=$this->ServiceImage->getOneimageServiceImageByservice_id($booking_detail['BookingOrder']['service_id']);
				// load helper for image
				App::uses('ImageResizeHelper', 'View/Helper');
				$ImageComponent = new ImageResizeHelper(new View());
				 $path=WWW_ROOT.'img'.DS.'service_images'.DS;
				$siteurl=$this->setting['site']['site_url'];
				$imgArr = array('source_path'=>$path,'img_name'=>$service_image,'width'=>80,'height'=>80);
				$image_name = $siteurl."/img/".$ImageComponent->ResizeImage($imgArr);$service_slot_details.=' <tr>
					<td width="1%">&nbsp;</td>
					<td border-bottom: 1px solid #DFDFDF; color: #787878;  font-family:arial;  font-size: 12px;  padding: 10px 0;"> <strong>'.$booking_detail['BookingOrder']['service_title'].'</strong>		<table width="90%" cellspacing="0" cellpadding="0" border="0">
							<tr>
								<td width="50%">Start Date</td>
								<td width="50%">'.date(Configure::read('Calender_format_php'),strtotime($booking_detail['BookingOrder']['start_date'])).'</td>
									</tr>
									<tr>
										<td>End Date</td>
										<td>'.date(Configure::read('Calender_format_php'),strtotime($booking_detail['BookingOrder']['end_date'])).'</td>
									</tr>
									<tr>
										<td>Slot Time</td>
										<td>'.implode('<br>',$slot_details).'</td>
									</tr>
								</table>
							</td>
						  <td style="border-bottom: 1px solid #DFDFDF; color: #787878;  font-family:arial;  font-size: 12px;  padding: 10px 0;">
								 <img src="'.$image_name.'" alt="'.$booking_detail['BookingOrder']['service_title'].'"/>			
							</td>
						  <td align="center"  style="border-bottom: 1px solid #DFDFDF; color: #787878;  font-family:arial;  font-size: 12px;  padding: 10px 0;">$'.number_format(($booking_detail['BookingOrder']['price']+$booking_detail['BookingOrder']['value_added_price']),2).'</td></tr>';
				
				$total_booking_price+=$booking_detail['BookingOrder']['price']+$booking_detail['BookingOrder']['value_added_price'];
			
			}
			// send to Admin mail
			$this->loadModel('MailManager.Mail');
			$mail=$this->Mail->read(null,22);
			$body=str_replace('{ORDERNO}',$booking_detail['BookingOrder']['ref_no'],$mail['Mail']['mail_body']);  
			$body=str_replace('{TOTAL}',number_format($total_booking_price,2),$body);
			$body=str_replace('{BOOKING_DETAIL}',$service_slot_details,$body);  
			$email = new CakeEmail();

			
			$email->to(array($this->setting['site']['site_contact_email'],$vendor_details['email']),$mail['Mail']['mail_from']);
			$email->subject($mail['Mail']['mail_subject']);
			$email->from($vendor_details['email']);
			$email->emailFormat('html');
			$email->template('default');
			$email->viewVars(array('data'=>$body,'logo'=>$this->setting['site']['logo'],'url'=>$this->setting['site']['site_url']));
			$email->send();
			// send to user mail
			$mail=$this->Mail->read(null,21);
			$body=str_replace('{ORDERNO}',$booking_detail['BookingOrder']['ref_no'],$mail['Mail']['mail_body']);  
			$body=str_replace('{TOTAL}',number_format($total_booking_price,2),$body);
			$body=str_replace('{BOOKING_DETAIL}',$service_slot_details,$body);  
			
			$email = new CakeEmail();

			
			$email->from($this->setting['site']['site_contact_email'],$mail['Mail']['mail_from']);
			$email->subject($mail['Mail']['mail_subject']);
			$email->to($booking_emails);
			$email->emailFormat('html');
			$email->template('default');
			$email->viewVars(array('data'=>$body,'logo'=>$this->setting['site']['logo'],'url'=>$this->setting['site']['site_url']));
			$email->send();
		}		
	} 
	
	function cancel_booking_by_id($order_id=null,$ref_no=null){
		$this->autoRender=false;
		$this->loadModel('BookingOrder');
		$criteria = array();
		//$criteria['fields']= array('Booking.status','BookingOrder.status');
			$criteria['joins'] = array(
				array(
					'table' => 'bookings',
					'alias' => 'Booking',
					'type' => 'INNER',
					'conditions' => array('Booking.ref_no =BookingOrder.ref_no','Booking.status' => 1)
				) 
                
			);
		
			$criteria['conditions'] =array('BookingOrder.id'=>$order_id,'BookingOrder.status' => 1,'Booking.status' => 1,'BookingOrder.vendor_id' =>$this->VendorAuth->id());
			$criteria['group'] =array('BookingOrder.id');
		
		$booking_count_status=$this->BookingOrder->find('count',$criteria);
		if($booking_count_status>=1){
			self::__cancel_booking_by_id_mail_to_vendor($order_id,$ref_no);
			//$this->BookingOrder->updateAll(array('status'=>3), array('AND' => array('BookingOrder.id'=>$order_id,'BookingOrder.status' => 1,'BookingOrder.vendor_id' =>$this->VendorAuth->id())));
			$this->Session->setFlash(__('Booking has been cancelled successfully.'));
		}else{
			$this->Session->setFlash('Booking is already cancelled or not completed.','','error');
		}
		$this->redirect(Controller::referer());
	}
	
	private function __cancel_booking_by_id_mail_to_vendor($order_id=null,$ref_no=null){
		$criteria = array();
		$total_booking_price=0;
		$service_slot_details='';
		$inviter_email=$booking_emails=array();
		$vendor_details=($this->Session->read('VendorAuth.VendorAuth'));
		$this->loadModel('BookingOrder');
		$this->loadModel('BookingParticipate');
		$this->loadModel('VendorManager.ServiceImage');
		// find email whose booked status =1 
		$booking_emails=$this->BookingParticipate->find('all',array('conditions'=>array('BookingParticipate.booking_order_id'=>$order_id,'BookingParticipate.status' => 1),'fields'=>array('BookingParticipate.member_id','BookingParticipate.email'),'group'=>('BookingParticipate.email')));
		//find inviter email id whose invited 
		if(!empty($booking_emails)){
			foreach($booking_emails as $booking_email){
				$emails[]=$booking_email['BookingParticipate']['email'];
			}
			$inviter_email=$this->Booking->find('first',array('conditions'=>array('Booking.member_id'=>$booking_emails[0]['BookingParticipate']['member_id'],'Booking.status' => 1),'fields'=>array('Booking.email')));
		}else{
			$inviter_email=$this->Booking->find('first',array('conditions'=>array('Booking.ref_no'=>$ref_no,'Booking.status' => 1),'fields'=>array('Booking.email')));
		}
		if(!empty($inviter_email['Booking']['email'])){
			$emails[]=$inviter_email['Booking']['email'];
		}
		$booking_detail=$this->BookingOrder->find('first',array('conditions'=>array('BookingOrder.id'=>$order_id,'BookingOrder.vendor_id'=>$this->VendorAuth->id())));$slots=json_decode($booking_detail['BookingOrder']['slots'],true);
		foreach($slots['Slot'] as $key=>$slot) {
			$slot_details[]=DATE("g:i A", STRTOTIME($slot['start_time']))." To ".DATE("g:i A", STRTOTIME($slot['end_time'])+1);
		}
		//get service image
		$service_image=$this->ServiceImage->getOneimageServiceImageByservice_id($booking_detail['BookingOrder']['service_id']);
		// load helper for image
		App::uses('ImageResizeHelper', 'View/Helper');
		$ImageComponent = new ImageResizeHelper(new View());
		 $path=WWW_ROOT.'img'.DS.'service_images'.DS;
		$siteurl=$this->setting['site']['site_url'];
		$imgArr = array('source_path'=>$path,'img_name'=>$service_image,'width'=>80,'height'=>80);
		$image_name = $siteurl."/img/".$ImageComponent->ResizeImage($imgArr);
		$service_slot_details.=' <tr>
			<td width="1%">&nbsp;</td>
			<td border-bottom: 1px solid #DFDFDF; color: #787878;  font-family:arial;  font-size: 12px;  padding: 10px 0;"> <strong>'.$booking_detail['BookingOrder']['service_title'].'</strong>		<table width="90%" cellspacing="0" cellpadding="0" border="0">
					<tr>
						<td width="50%">Start Date</td>
						<td width="50%">'.date(Configure::read('Calender_format_php'),strtotime($booking_detail['BookingOrder']['start_date'])).'</td>
							</tr>
							<tr>
								<td>End Date</td>
								<td>'.date(Configure::read('Calender_format_php'),strtotime($booking_detail['BookingOrder']['end_date'])).'</td>
							</tr>
							<tr>
								<td>Slot Time</td>
								<td>'.implode('<br>',$slot_details).'</td>
							</tr>
						</table>
					</td>
				  <td style="border-bottom: 1px solid #DFDFDF; color: #787878;  font-family:arial;  font-size: 12px;  padding: 10px 0;">
						 <img src="'.$image_name.'" alt="'.$booking_detail['BookingOrder']['service_title'].'"/>			
					</td>
				  <td align="center"  style="border-bottom: 1px solid #DFDFDF; color: #787878;  font-family:arial;  font-size: 12px;  padding: 10px 0;">$'.number_format(($booking_detail['BookingOrder']['price']+$booking_detail['BookingOrder']['value_added_price']),2).'</td></tr>';
		$total_booking_price+=$booking_detail['BookingOrder']['price']+$booking_detail['BookingOrder']['value_added_price'];
		// send to Admin mail
		$this->loadModel('MailManager.Mail');
		$mail=$this->Mail->read(null,22);
		$body=str_replace('{ORDERNO}',$booking_detail['BookingOrder']['ref_no'],$mail['Mail']['mail_body']);  
		$body=str_replace('{TOTAL}',number_format($total_booking_price,2),$body);
		$body=str_replace('{BOOKING_DETAIL}',$service_slot_details,$body);  
		$email = new CakeEmail();

		
		$email->to(array($this->setting['site']['site_contact_email'],$vendor_details['email']),$mail['Mail']['mail_from']);
		$email->subject($mail['Mail']['mail_subject']);
		$email->from($vendor_details['email']);
		$email->emailFormat('html');
		$email->template('default');
		$email->viewVars(array('data'=>$body,'logo'=>$this->setting['site']['logo'],'url'=>$this->setting['site']['site_url']));
		$email->send();
		// send to user mail
		$mail=$this->Mail->read(null,21);
		$body=str_replace('{ORDERNO}',$booking_detail['BookingOrder']['ref_no'],$mail['Mail']['mail_body']);  
		$body=str_replace('{TOTAL}',number_format($total_booking_price,2),$body);
		$body=str_replace('{BOOKING_DETAIL}',$service_slot_details,$body);  
		$email = new CakeEmail();

		
		$email->from($vendor_details['email'],$mail['Mail']['mail_from']);
		$email->subject($mail['Mail']['mail_subject']);
		$email->to($emails);
		$email->emailFormat('html');
		$email->template('default');
		$email->viewVars(array('data'=>$body,'logo'=>$this->setting['site']['logo'],'url'=>$this->setting['site']['site_url']));
		$email->send();
	} 
}
?>
