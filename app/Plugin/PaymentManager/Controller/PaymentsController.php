<?php
class PaymentsController extends PaymentManagerAppController{
	public $uses = array('PaymentManager.Payment');
	public $components = array('Email','SmoovPay');
	public $paginate = array();
	public $id = null;
	
	// This is used to payment by booking id  
	function index($booking_id=null){
		$this->loadModel('BookingSlot');
		$this->layout='';
		App::uses('MemberAuthComponent', 'MemberManager.Controller/Component');
		$this->sessionKey = MemberAuthComponent::$sessionKey;		
		$this->member_data = $this->Session->read($this->sessionKey);
		//check guest email or login 
		$guest_email=$this->Session->read('Guest_email');
		if(!empty($guest_email) || !empty($this->member_data['MemberAuth']['id'])){
		}else{
			$this->Session->setFlash('Please login/email to book activity.','default','','error');
			$this->redirect(array('plugin'=>'member_manager','controller'=>'members','action'=>'registration'));
		}	
		if(empty($booking_id)) {
			throw new NotFoundException('Could not find that booking id.');
		}		
		$this->loadModel('Cart');
		$this->loadModel('Booking');
		$criteria = array();
		$criteria['fields']= array('Cart.price','Cart.value_added_price','Cart.no_participants','Cart.start_date','Cart.end_date','Service.service_title','Service.description','Cart.total_amount','Cart.slots','Cart.invite_friend_email');
		$criteria['joins'] = array(
			array(
				'table' => 'services',
				'alias' => 'Service',
				'type' => 'INNER',
				'conditions' => array('Service.id = Cart.service_id')
			) 
				
		);
		$criteria['conditions'] =array('Cart.session_id'=>$this->Session->id(),'Cart.status'=>1);
		$criteria['order'] =array('Cart.id DESC');
		$cart_details=$this->Cart->find('all', $criteria);
		//pr($cart_details);die;
		$booking_ref_no=$this->Booking->getBookingRefenceByBooking_id($booking_id);

		// Get total cart price 
		$total_cart_price=0;
		foreach($cart_details as $cart_detail){
			$total_cart_price+=$cart_detail['Cart']['total_amount'];
			$cart_service_names[]=$cart_detail['Service']['service_title'];
		 }
		$siteurl=$this->setting['site']['site_url'];
		$payment_data=array();
		$memberid = $this->member_data['MemberAuth']['id'];
		$payment_ref = time().$booking_id;
		
		$payment_data['amount']=$total_cart_price;

		$payment_data['orderRef'] = $payment_ref;

		$payment_data['successUrl']=$siteurl.Router::url(array('plugin'=>'payment_manager','controller'=>'payments','action'=>'payment_summary/'.$payment_ref));
		$payment_data['strUrl']=$siteurl.Router::url(array('plugin'=>'payment_manager','controller'=>'payments','action'=>'paypal_ipn_simple/'.$payment_ref));
		$payment_data['cancelUrl']=$siteurl.Router::url(array('plugin'=>'payment_manager','controller'=>'payments','action'=>'cancelled_url'));
		
		self::_save_payment_ref($booking_id,$payment_ref,$memberid);
		// $formData = self::_paypal_form($payment_data,$cart_details);

		// $this->breadcrumbs[] = array(
		// 	'url'=>Router::url('/'),
		// 	'name'=>'Home'
		// );
		// $this->breadcrumbs[] = array(
		// 	'url'=>Router::url('/'),
		// 	'name'=>'Payment'
		// );
		
		// $this->set('formData',$formData);

		// new secured checkout
		$this->autoRender = false;
		$urldata = self::_paypal_url_data($payment_data,$cart_details);
		$this->redirect($urldata);
	}
	
	
	private function _save_payment_ref($bookingid=null,$payment_ref=null,$memberId=null){
		$bookingData = array();
		$bookingData['Booking']['id'] = $bookingid;
		$bookingData['Booking']['payment_ref'] = $payment_ref;
		$booking_data['Booking']['member_id']=(!empty($memberId))?$memberId:null;
		$this->Booking->create();
		$this->Booking->save($bookingData,array('validate'=>false)); 
	}
	
	function smoovPay_success($payment_ref = null)
	{
		if ($payment_ref) {
			$this->loadModel('Cart');
			$this->loadModel('VendorManager.ServiceImage');
			$this->loadModel('ServiceManager.ServiceType');
			$this->loadModel('BookingOrder');
			$this->loadModel('Booking');
			$booking = $this->Booking->find('first', ['conditions' => ['payment_ref' => $payment_ref]]);
			// remove the cart
			$this->Cart->deleteAll(['Cart.session_id'=>$this->Session->id(),'Cart.status'=>1]);
			// update booking orders
			$query = "UPDATE booking_orders SET payment_ref = $payment_ref WHERE ref_no=" . $booking['Booking']['ref_no'];
            $this->BookingOrder->query($query);
            // redirect to payment summary
			$this->redirect(array('plugin'=>'payment_manager','controller'=>'payments','action'=>'payment_summary/'.$payment_ref));
		}
		$this->redirect(array('plugin'=>false,'controller'=>'pages','action'=>'home'));
	}

	private function _smoovPay($payment_data=null, $cartData=array()){
		if(Configure::read('Payment.sandbox_mode')==1){
			$url = Configure::read('Payment.test_url');
		}
		else{
			$url = Configure::read('Payment.live_url');
		}
		$secret_key = Configure::read('Payment.secret_key');
		$merchant =Configure::read('Payment.merchant'); 
		$action = 'pay';
		$total_amount = $payment_data['amount'];
		$currency ="SGD"; 
		$ref_id = $payment_data['orderRef'];
		$dataToBeHashed = $secret_key. $merchant. $action. $ref_id. $total_amount. $currency;
		$get_signature = $this->SmoovPay->encryption($dataToBeHashed);
		$html = '';
		$i = 1;
		if(!empty($payment_data['amount'])){
			$html .= "<form name='payFormCcard' id='payFormCcard' method='post' action='$url'>";
			$html .= "<input type='hidden' name='version' value='2.0' />";
			$html .= "<input type='hidden' name='action' value='$action' />";
			$html .= "<input type='hidden' name='merchant' value='$merchant' />";
			$html .= "<input type='hidden' name='ref_id' value='$ref_id' />";
			if(!empty($cartData)){
				foreach($cartData as $cart_detail){
					$diff = abs(strtotime($cart_detail['Cart']['end_date']) - strtotime($cart_detail['Cart']['start_date']));
					$years = floor($diff / (365*60*60*24));
					$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
					$no_of_booking_days =(floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24)))+1;
					$itemname = $cart_detail['Service']['service_title'];
					// $desc = empty($cart_detail['Service']['description'])? 'NA':strip_tags($cart_detail['Service']['description']);
					$participants = ($cart_detail['Cart']['no_participants']+$no_of_booking_days);
					//$participants = $no_of_booking_days;
					$itemprice = $cart_detail['Cart']['price'];

					$slots = json_decode($cart_detail['Cart']['slots'],true);
					foreach ($slots['Slot'] as $slot_key=>$slot_time) {
						$desc = date(Configure::read('Calender_format_php'),strtotime($cart_detail['Cart']['start_date'])) . ', Slot ' . date('H:ia', strtotime($slot_time['start_time'])) . ' to ' . date('H:ia', strtotime($slot_time['end_time']));
						$participants = $cart_detail['Cart']['no_participants'] - count(json_decode($cart_detail['Cart']['invite_friend_email']));
						$itemprice = $slot_time['price'];
						$html .= "<input type='hidden' name='item_name_$i' value='$itemname'/>";
						$html .="<input type='hidden' name='item_description_$i' value='$desc' />";
						$html .="<input type='hidden' name='item_quantity_$i' value='$participants'/>";
						$html .="<input type='hidden' name='item_amount_$i' value='$itemprice' />";
						$i++;
					}
				}
			} 
			
			$html .= "<input type='hidden' name='currency' value='SGD' />";
			$html .= "<input type='hidden' name='total_amount' value='$payment_data[amount]'/>";
			$html .= "<input type='hidden' name='success_url' value='$payment_data[successUrl]' />";
			$html .= "<input type='hidden' name='cancel_url' value='$payment_data[cancelUrl]' />";
			$html .= "<input type='hidden' name='str_url' value='$payment_data[strUrl]' />";
			$html .= "<input type='hidden' name='signature' value='$get_signature' />";
			$html .= "<input type='hidden' name='signature_algorithm' value='sha1' />";
			$html .="</form>";
		}
		return $html;
	}
	
	// this is used to after payment successful 
	function notify_url() {
		$this->loadModel('Cart');
		$this->loadModel('BookingOrder');
		$this->loadModel('BookingSlot');
		$this->loadModel('Booking');
		$this->loadModel('BookingParticipate');
		$this->loadModel('VendorManager.ServiceImage');
		$this->loadModel('ServiceManager.ServiceType');
		
		if(!empty($_POST)){
			// Here this code is used for payment verification 
			$src = $_POST['src'];														//host status code (secondary).
			$prc = $_POST['prc'];														//Return bank host status code (primary).
			$successcode = $_POST['successcode'];							
			//0- succeeded, 1- failure, Others - error
			$ref = $_POST['Ref'];														//Merchant‘s Order Reference Number
			$payRef = $_POST['PayRef'];												//PayDollar Payment Reference Number
			$amt = $_POST['Amt'];														//Transaction Amount
			$cur = $_POST['Cur'];															//Transaction Currency
			$payerAuth = $_POST['payerAuth'];									
			//Payer Authentication Status
			
			$ord = $_POST['Ord'];															//Bank Reference – Order id
			$holder = $_POST['Holder'];												
			//The Holder Name of the Payment Account
			$remark = $_POST['remark'];												
			//A remark field for you to store additional data that will 
			$authId = $_POST['AuthId'];												//Approval Code
			$eci = $_POST['eci'];															//ECI value (for 3D enabled Merchants)
			$sourceIp = $_POST['sourceIp'];											
			//IP address of payer
			$ipCountry = $_POST['ipCountry'];									//Country of payer ( e.g. HK) - if country is on high risk country list, an asterisk will be shown (e.g. MY*)
			 
			$payMethod = $_POST['payMethod'];								//Payment method (e.g. VISA, Master, Diners, JCB, AMEX)
			$secureHash = $_POST['secureHash'];
			$secureHashSecret = Configure::read('AsiaPay.secureHashSecret');
			//offered by paydollar
		
			App::import('Vendor', 'assiapay', array('file' => 'assiapay' . DS . 'SHAPaydollarSecure.php'));
			// load vendor of aisapay
			$isSecureHash=true;
			if($isSecureHash){
				$secureHashs=explode(',', $secureHash);
				$paydollarSecure=new SHAPaydollarSecure();
				 $verifyResult =false;
				while(list($key,$value)=each($secureHashs)){
					$verifyResult = $paydollarSecure->verifyPaymentDatafeed($src,$prc, $successcode, $ref, $payRef, $cur, $amt, $payerAuth,$secureHashSecret, $value);
					if (!$verifyResult) {
						//echo 'Verify Fail';
						//TODO Verify Fail
						return;
					}else{
						echo 'True';
					}
				}
				// Here type 1- for simple activity bookin, 2- for invited activity booking,3- for vendor payment 
				if($_POST['type']==1){
					self::simple_payment_ipn($_POST);
				}else
				if($_POST['type']==2){
					self :: invite_process_ipn($_POST);
				}else
				if($_POST['type']==3){
					self :: vendor_process_ipn($_POST);
				}
			}
		}
	}
	public function simple_payment_ipn(){
		$this->loadModel('Booking');
		$this->loadModel('BookingOrder');
		$this->loadModel('Cart');
		$this->loadModel('BookingSlot');
		$this->loadModel('ServiceManager.ServiceType');
		if ($_SERVER["REQUEST_METHOD"]=="POST") {
			$flag = $this->SmoovPay->validateIpn($_POST);
			if($flag==1){
				$booking = $this->Booking->find('first',array('conditions'=>array('Booking.payment_ref'=>$_POST['ref_id'])));
				$booking_id = $booking['Booking']['id'];
				$sessionId = $booking['Booking']['session_id'];
				$booking_ref_no = $this->Booking->getBookingRefenceByBooking_id($booking['Booking']['id']);
				
				$criteria = array();
				$criteria['fields']= array('Cart.*');
				$criteria['conditions'] =array('Cart.session_id'=>$sessionId,'Cart.status'=>1);
				$criteria['order'] =array('Cart.id ASC');
				$criteria['group'] =array('Cart.id');
				$cart_details=$this->Cart->find('all', $criteria);
				
				//update booking table
				$booking_data['Booking']['id']= $booking_id;
				$booking_data['Booking']['transaction_amount']=$_POST['total_amount'];
				$booking_data['Booking']['status']=1;
				$booking_data['Booking']['transaction_id'] = $_POST['reference_code'];
				$booking_data['Booking']['booking_date'] = date('Y-m-d H:i:s');
				$booking_data['Booking']['time_stamp'] = date('Y-m-d H:i:s');
				$booking_data['Booking']['secureHash'] = $_POST['signature'];
				$booking_data['Booking']['payment_ref'] = $_POST['ref_id'];
				$booking_data['Booking']['payment_log'] = json_encode($_POST);
				$booking_data['Booking']['currency_code'] = $_POST['currency'];
				//$booking_data['Booking']['card_holder']=$post_data['Holder'];
				//$booking_data['Booking']['authid']=$post_data['AuthId'];
				$booking_data['Booking']['merchantId'] = $_POST['merchant'];
				$booking_data['Booking']['price'] = $_POST['total_amount'];
				$this->Booking->create();
				$this->Booking->save($booking_data,array('validate' => false));
				$booking_detail=$this->Booking->getBookingDetailsByBooking_id($booking_id);
				$this->BookingOrder->updateAll(
					array('BookingOrder.status' =>1,'BookingOrder.payment_ref' => $_POST['ref_id']),
					array('BookingOrder.ref_no =' => $booking_ref_no)
				);
				$this->BookingSlot->updateAll(
					array('BookingSlot.status' => 1),
					array('BookingSlot.ref_no =' => $booking_ref_no)
				); 

				$service_slot_details='';
				$total_cart_price=0;
				// check payment status
				if(!empty($cart_details)){
					if($booking_data['Booking']['status']==1){
						foreach($cart_details as $cart_detail) {
							$slot_details=array();
							unset($cart_detail['Cart']['id']);
							$newData['BookingOrder']=$cart_detail['Cart'];
							$newData['BookingOrder']['ref_no']=$booking_detail['Booking']['ref_no'];
							// get serviceType name 
							$newData['BookingOrder']['serviceTypeName']=$this->ServiceType->getServiceTypeNameByServiceId($newData['BookingOrder']['service_id']);
							$service_slot_details.=self::getBookedServices($newData); 	
							$total_cart_price+=$cart_detail['Cart']['total_amount'];
							//echo $service_slot_details;die;
							self::sent_invite_mail($cart_detail,number_format($total_cart_price,2),$booking_detail);
							// end of booking slot
							 
							 
						}
						
						// send to user mail

						$key = 'RcGToklPpGQ56uCAkEpY5A';
						$from = $this->setting['site']['site_contact_email'];
						$subject = 'Thank you for booking with us';
						$to = $booking_detail['Booking']['email'];
						$template_name = 'user_pending_booking_confirmation';

						$global_merge_vars = '[';
				        $global_merge_vars .= '{"name": "NAME", "content": "'.$booking_detail['Booking']['fname']." ".$booking_detail['Booking']['lname'].'"},';
				        $global_merge_vars .= '{"name": "EMAIL", "content": "'.$booking_detail['Booking']['email'].'"},';
				        $global_merge_vars .= '{"name": "PHONE", "content": "'.$booking_detail['Booking']['phone'].'"},';
				        $global_merge_vars .= '{"name": "BOOKING_DETAIL", "content": "'.str_replace(['"', "\n", "\t"],['\'', "", ""],$service_slot_details).'"}';
				        $global_merge_vars .= ']';

				        $data_string = '{
				                "key": "'.$key.'",
				                "template_name": "'.$template_name.'",
				                "template_content": [
				                        {
				                                "name": "TITLE",
				                                "content": "test test test"
				                        }
				                ],
				                "message": {
				                        "subject": "'.$subject.'",
				                        "from_email": "'.$from.'",
				                        "to": [
				                                {
				                                        "email": "'.$to.'",
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
						// cart empty 
						$this->Cart->deleteAll(array('Cart.session_id'=>$sessionId));
						
						// send to vendor mail
						self::vendor_mails($booking_detail['Booking']['ref_no']);
						
					}
					else{
						//self::payment_failed_mail($booking_detail);
					}
				}
			}
			else{
				self::payment_failed_mail($booking_detail);
			}
		} 
	}
	// This function is used for vendor mail
	private function vendor_mails($booking_ref_no=null) {
	//$this->autoRender=false;
	$criteria = array();
	$this->loadModel('VendorManager.Vendor');
	$this->loadModel('BookingOrder');
	$this->loadModel('LocationManager.City');
	$this->loadModel('VendorManager.ServiceImage');
	$this->loadModel('MailManager.Mail');
	$this->loadModel('ServiceManager.ServiceType');
	$this->loadModel('Coupon');
	$customer_detail=$this->Booking->find('first',array('conditions'=>array('Booking.ref_no'=>$booking_ref_no)));
	$criteria['conditions']=array('BookingOrder.ref_no'=>$booking_ref_no);
	$criteria['fields']=array('BookingOrder.vendor_id');
	$criteria['group']=array('BookingOrder.vendor_id');
	$criteria['order']=array('BookingOrder.id ASC');
	$order_details=$this->BookingOrder->find('all',$criteria);	
	foreach($order_details as $key=>$order_detail) {
		$booking_content = '
			<tr><th style="min-width:200px"><span style="font-size:14px">Vendor</span></th></tr>
			<tr><th><span style="font-size:14px">Service</span></th></tr>
			<tr><th style="min-width:200px"><span style="font-size:14px">Activity</span></th></tr>
			<tr><th><span style="font-size:14px">Date</span></th></tr>
			<tr><th style="min-width:200px"><span style="font-size:14px">Booking Time</span></th></tr>
			<tr><th><span style="font-size:14px">Participant(s)</span></th></tr>
			<tr><th style="min-width:200px"><span style="font-size:14px">Price ($)</span></th></tr>
			<tr><th><span style="font-size:14px">Min. to go status</span></th></tr>';
		$total_cart_price=0;
		$criteria['conditions']=array('BookingOrder.ref_no'=>$booking_ref_no,'BookingOrder.vendor_id'=>$order_detail['BookingOrder']['vendor_id']);
		$criteria['fields']=array('BookingOrder.*');
		$criteria['group']=array('BookingOrder.id');
		$order_detail1=$this->BookingOrder->find('all',$criteria);	
		
		
		$vendor_details=$this->Vendor->vendorNameEmailById($order_detail['BookingOrder']['vendor_id']);
			if(!empty($vendor_details['Vendor']['email'])){
				  
				foreach($order_detail1 as $key=>$order){ 
					 // find the vendor details 
					$slot_details=array();
					$slot_details=self::getBookingSlot($order['BookingOrder']['slots']);
					
					//get service image
					$order['BookingOrder']['serviceTypeName']=$this->ServiceType->getServiceTypeNameByServiceId($order['BookingOrder']['service_id']);

					if (!is_null($order['BookingOrder']['coupon_id'])) {
						$discount = $this->Coupon->find('first', ['conditions' => ['coupon_id' => $order['BookingOrder']['coupon_id']]]);
						$order['BookingOrder']['discount'] = $discount['Coupon']['discount'];
					} else {
						$order['BookingOrder']['discount'] = 0;
					}
					$booking_content = self::getBookedServicesVertical($order, $booking_content);
					$total_cart_price+=$order['BookingOrder']['total_amount'];
				}
				
				$this->loadModel('MailManager.Mail');
				$mail=$this->Mail->read(null,16);

				

				$global_merge_vars = '[';
		        $global_merge_vars .= '{"name": "ORDERNO", "content": "'.$customer_detail['Booking']['ref_no'].'"},';
		        $global_merge_vars .= '{"name": "VENDOR", "content": "'.$vendor_details['Vendor']['fname'].'"},';
		        if (strlen(trim($customer_detail['Booking']['fname']." ".$customer_detail['Booking']['lname'])) > 0) {
		        	$global_merge_vars .= '{"name": "NAME", "content": "'.$customer_detail['Booking']['fname']." ".$customer_detail['Booking']['lname'].'"},';
		    	} else {
		    		$global_merge_vars .= '{"name": "NAME", "content": "N/A"},';
		    	}
		        $global_merge_vars .= '{"name": "EMAIL", "content": "'.$customer_detail['Booking']['email'].'"},';
		        $global_merge_vars .= '{"name": "PHONE", "content": "'.$customer_detail['Booking']['phone'].'"},';
		        $global_merge_vars .= '{"name": "ORDER_COMMENT", "content": "'.(!empty($customer_detail['Booking']['order_message']))?$customer_detail['Booking']['order_message']:'There are no comments.'.'"},';
		        $global_merge_vars .= '{"name": "TOTAL", "content": "'.number_format($total_cart_price,2).'"},';
		        $global_merge_vars .= '{"name": "CONFIRM_LINK", "content": "'.Router::url('/vendor/booking_list').'"},';
		        $global_merge_vars .= '{"name": "BOOKING_DETAIL", "content": "'.str_replace(['"', "\n", "\t"],['\'', "", ""],$booking_content).'"}';
		        $global_merge_vars .= ']';


		        $key = 'RcGToklPpGQ56uCAkEpY5A';
				$from = $customer_detail['Booking']['email'];
				$from_name = $customer_detail['Booking']['fname']." ".$customer_detail['Booking']['lname'];
				$subject = 'Booking has been received';
				$to = $vendor_details['Vendor']['email'];
				$to_name = $mail['Mail']['mail_from'];
				$template_name = 'vendor_request_booking_confirmation';


		        $data_string = '{
		                "key": "'.$key.'",
		                "template_name": "'.$template_name.'",
		                "template_content": [
		                        {
		                                "name": "TITLE",
		                                "content": "test test test"
		                        }
		                ],
		                "message": {
		                        "subject": "'.$subject.'",
		                        "from_email": "'.$from.'",
		                        "from_name": "'.$from_name.'",
		                        "to": [
		                                {
		                                        "email": "'.$to.'",
		                                        "type": "to"
		                                }
		                        ],
		                        "global_merge_vars": '.$global_merge_vars.',
		                        "bcc_address": "'.$this->setting['site']['site_contact_email'].'"
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
				curl_close($ch);
			}
		}
		return true;
	} 
	// This function is used for payment failed mail when this function return from payment gateway
	private function payment_failed_mail($booking_detail=null){
		 
		// send to user mail
		$this->loadModel('MailManager.Mail');
		$mail=$this->Mail->read(null,19);
		$body=str_replace('{ORDERNO}',$booking_detail['Booking']['ref_no'],$mail['Mail']['mail_body']);  
		$body=str_replace('{NAME}',$booking_detail['Booking']['fname']." ".$booking_detail['Booking']['lname'],$body);  
		$body=str_replace('{EMAIL}',$booking_detail['Booking']['email'],$body);
		$body=str_replace('{PHONE}',$booking_detail['Booking']['phone'],$body);
		//$body=str_replace('{POST_CODE}',$booking_detail['Booking']['post_code'],$body);
		$body=str_replace('{ORDER_COMMENT}',(!empty($booking_detail['Booking']['order_message']))?$booking_detail['Booking']['order_message']:'There are no comments.',$body);
		$body=str_replace('{TOTAL}',number_format($booking_detail['Booking']['transaction_amount'],2),$body);
		$body=str_replace('{TXN_ID}',$_REQUEST['txn_id'],$body);  
		$body=str_replace('{PAYMENT_STATUS}',$_REQUEST['payment_status'],$body);  
		$email = new CakeEmail();

		
		$email->to($booking_detail['Booking']['email'],$mail['Mail']['mail_from']);
		$email->subject($mail['Mail']['mail_subject']);
		$email->from($this->setting['site']['site_contact_email']);
		$email->emailFormat('html');
		$email->template('default');
		$email->viewVars(array('data'=>$body,'logo'=>$this->setting['site']['logo'],'url'=>$this->setting['site']['site_url']));
		$email->send();
	}
	// get function is used for get booking detail which is used
	private function getBookedServices($orderBooked=array()){
		
		$this->loadModel('Service');
		$this->loadModel('VendorManager.BookingSlot');

		$service = $this->Service->find('first', ['conditions' => ['id' => $orderBooked['BookingOrder']['service_id']] ]);
		$service = array_pop($service);
		$slot_string = '';
		if ($service['min_participants'] != 0) {
			$slots = json_decode($orderBooked['BookingOrder']['slots']);
			foreach ($slots as $slot_data) {
				foreach ($slot_data as $slot) {
					if ($slot_string !== '') $slot_string .= '<br>';
					$date = date('Y-m-d', (int) $slot->slot_date);
					$start_time = $date . ' ' . $slot->start_time;
					$end_time = $date . ' ' . date('H:i:s', strtotime($slot->end_time) + 1);
					$paid_count = $this->BookingSlot->paidSlotCount($orderBooked['BookingOrder']['service_id'], $start_time, $end_time);
					$slot_string .= $paid_count . " out of " . $service['min_participants'] . " booked";
				}
			}
		}
		if ($slot_string === '') {
			$slot_string = 'N/A';
		}

		$slot_details=self::getBookingSlot($orderBooked['BookingOrder']['slots']);
		$booked_slot_details=(!empty($slot_details))? implode('<br>',$slot_details):'Full day';
		// $participant_emails=self::getBookedParticipantEmail($orderBooked['BookingOrder']['invite_friend_email']); // will not be used
		$participant_emails = $orderBooked['BookingOrder']['no_participants'];
		// get booked vas service;
		$booked_vas_details=self::getBookedVas($orderBooked['BookingOrder']['value_added_services']);

		$booking_content='<tr>	
			<td>'.ucfirst($orderBooked['BookingOrder']['vendor_name']).'</td> 
			<td>'.ucfirst($orderBooked['BookingOrder']['serviceTypeName']).'</td> 
			<td>'.ucfirst($orderBooked['BookingOrder']['service_title']).'</td> 
			<td>'.date(Configure::read('Calender_format_php'),strtotime($orderBooked['BookingOrder']['start_date'])).' To '.date(Configure::read('Calender_format_php'),strtotime($orderBooked['BookingOrder']['end_date'])).'</td> 
			<td>'.$booked_slot_details.'</td> 
			<td>'.$participant_emails.'</td>
			<td>'.number_format(($orderBooked['BookingOrder']['total_amount']),2).'</td>
			<td>'.$slot_string.'</td>
		</tr>';
		 
		return $booking_content;
	}

	private function getBookedServicesVertical($orderBooked=array(), $booking_content = '') {
		
		$this->loadModel('Service');
		$this->loadModel('VendorManager.BookingSlot');

		$service = $this->Service->find('first', ['conditions' => ['id' => $orderBooked['BookingOrder']['service_id']] ]);
		$service = array_pop($service);
		$slot_string = '';
		if ($service['min_participants'] != 0) {
			$slots = json_decode($orderBooked['BookingOrder']['slots']);
			foreach ($slots as $slot_data) {
				foreach ($slot_data as $slot) {
					if ($slot_string !== '') $slot_string .= '<br>';
					$date = date('Y-m-d', (int) $slot->slot_date);
					$start_time = $date . ' ' . $slot->start_time;
					$end_time = $date . ' ' . date('H:i:s', strtotime($slot->end_time) + 1);
					$paid_count = $this->BookingSlot->paidSlotCount($orderBooked['BookingOrder']['service_id'], $start_time, $end_time);
					$slot_string .= $paid_count . " out of " . $service['min_participants'] . " booked";
				}
			}
		}
		if ($slot_string === '') {
			$slot_string = 'N/A';
		}

		$slot_details=self::getBookingSlot($orderBooked['BookingOrder']['slots']);
		$booked_slot_details=(!empty($slot_details))? implode('<br>',$slot_details):'Full day';
		// $participant_emails=self::getBookedParticipantEmail($orderBooked['BookingOrder']['invite_friend_email']); // will not be used
		$participant_emails = $orderBooked['BookingOrder']['no_participants'];
		// get booked vas service;
		$booked_vas_details=self::getBookedVas($orderBooked['BookingOrder']['value_added_services']);

		$paid_by_user = $orderBooked['BookingOrder']['no_participants'] - count($orderBooked['BookingOrder']['invite_friend_email']);
		$price = $orderBooked['BookingOrder']['no_participants'] > 1 ? (($paid_by_user) * $orderBooked['BookingOrder']['total_amount'] )  : $orderBooked['BookingOrder']['total_amount'];
		if ($orderBooked['BookingOrder']['no_participants'] > 1 && count($orderBooked['BookingOrder']['invite_friend_email']) > 0) {
			$price_str = $orderBooked['BookingOrder']['total_amount'] . 'x' . $paid_by_user . ' = ' . number_format($price,2);
		} else {
			$price_str = number_format($orderBooked['BookingOrder']['total_amount'], 2);
		}
		// :/var/www/waterspot/app/Plugin/PaymentManager/Controller
		if (strlen($booking_content) > 0) {
			$frag = explode('</tr>', $booking_content);
			$frag[0] .= '<td style="min-width:250px"><span style="font-size:14px">'.ucfirst($orderBooked['BookingOrder']['vendor_name']).'</span></td>';
			$frag[1] .= '<td><span style="font-size:14px">'.ucfirst($orderBooked['BookingOrder']['serviceTypeName']).'</span></td>';
			$frag[2] .= '<td style="min-width:250px"><span style="font-size:14px">'.ucfirst($orderBooked['BookingOrder']['service_title']).'</span></td>';
			$frag[3] .= '<td style="min-width:250px"><span style="font-size:14px">'.date(Configure::read('Calender_format_php'),strtotime($orderBooked['BookingOrder']['start_date'])).' To '.date(Configure::read('Calender_format_php'),strtotime($orderBooked['BookingOrder']['end_date'])).'</span></td>';
			$frag[4] .= '<td><span style="font-size:14px">'.$booked_slot_details.'</span></td>';
			$frag[5] .= '<td><span style="font-size:14px">'.$participant_emails.'</span></td>';
			$frag[6] .= '<td style="min-width:250px"><span style="font-size:14px">' . $price_str . '</span></td>';
			$frag[7] .= '<td><span style="font-size:14px">'.$slot_string.'</span></td>';
			$booking_content = implode('</tr>', $frag) . '</tr>';
		}
		
		return $booking_content;
	}
	// Get booking slots
	private function getBookingSlot($slotDetails){
		$slot_details=array();
		if(!empty($slotDetails)){
			$slots=json_decode($slotDetails,true);
			foreach($slots['Slot'] as $key=>$slot) {
				if($slot['end_time']=="23:59:59"){
					$slot_details[]=DATE("g:i A", STRTOTIME($slot['start_time']))." To ".DATE("g:i A", STRTOTIME($slot['end_time']));
				}else{
					$slot_details[]=DATE("g:i A", STRTOTIME($slot['start_time']))." To ".DATE("g:i A", STRTOTIME($slot['end_time'])+1);
				}
					
				
			}
			
		}
		return $slot_details;
		
	} 
	// Get booking participant emails
	
	private function getBookedParticipantEmail($participant_emails){
		$booked_participant_emails='';
		if(!empty($participant_emails)){
			$emails=json_decode($participant_emails,true);
			foreach($emails as $email){
				 $booked_participant_emails.=$email.'<br/>';
			}
		}
		return $booked_participant_emails == '' ? 'None' : $booked_participant_emails;
		
	}
	// Get booking VAS
	private function getBookedVas($vas_services){
		$booked_vas_details='';
		if(!empty($vas_services)){
			$vas_details=json_decode($vas_services,true);
			if(!empty($vas_details)){
				$booked_vas_details='';
				foreach($vas_details as $key=>$vas){
					$booked_vas_details.=
					'<div>'.$vas['value_added_name'].' ($'.$vas['value_added_price'].')'.'</div><br/>';
				}
			}
		}
		return $booked_vas_details == '' ? 'N/A' : $booked_vas_details;
	}
	private function sent_invite_mail($cart_detail=null,$total_cart_price=null,$booking_detail=null){
		$this->loadModel('BookingParticipate');
		$this->loadModel('MailManager.Mail');
		$booking_participates=array();
		$booking_participates_mails=array();
		$emails=json_decode($cart_detail['Cart']['invite_friend_email'],true);
		if(!empty($emails)) {
		 
			foreach($emails as $key=>$email) {
				$booking_participates['BookingParticipate']['id']='';	
				$booking_participates['BookingParticipate']['booking_order_id']=$this->booking_order_id;;	
				$booking_participates['BookingParticipate']['member_id']=$booking_detail['Booking']['member_id'];	
				$booking_participates['BookingParticipate']['ref_no']=$booking_detail['Booking']['ref_no'];	
				$booking_participates['BookingParticipate']['email']=$email;	
				$booking_participates['BookingParticipate']['amount']=$total_cart_price;
				 
				 // save invite friends	
				/* if(!empty($booking_participates)){
					$this->BookingParticipate->create();
					$this->BookingParticipate->save($booking_participates,array('validate' => false));
				}*/
				  
				$booking_participates['BookingParticipate']['id']=$this->BookingParticipate->id;
				$booking_participates['BookingParticipate']['service_title']=$cart_detail['Cart']['service_title'];
				$booking_participates['BookingParticipate']['invite_payment_status']=$cart_detail['Cart']['invite_payment_status'];
				$booking_participates['BookingParticipate']['start_end_date']=date(Configure::read('Calender_format_php'),strtotime($cart_detail['Cart']['start_date']))." To ".date(Configure::read('Calender_format_php'),strtotime($cart_detail['Cart']['end_date']));
				$booking_participates_mails[]=$booking_participates;
				 
			}
					
					 
				 
			// SEND MAIL INVITE FRIEND 
			// send to user mail
			
			foreach($booking_participates_mails as $booking_participates_mail) {
				// send mail different content if paymet pay by invitor
				$mail_id=($booking_participates_mail['BookingParticipate']['invite_payment_status']==1)?25:15;
				$mail=$this->Mail->read(null,$mail_id);

				$booking_participates_id = $this->BookingParticipate->find('first', ['conditions' => ['BookingParticipate.email' => $booking_participates_mail['BookingParticipate']['email'], 'BookingParticipate.ref_no' => $booking_participates_mail['BookingParticipate']['ref_no']]]);
				$booking_participates_id = $booking_participates_id['BookingParticipate']['id'];

				$global_merge_vars = '[';
		        $global_merge_vars .= '{"name": "FRIEND_NAME", "content": "'.$booking_detail['Booking']['fname']." ".$booking_detail['Booking']['lname'].'"},';
		        $global_merge_vars .= '{"name": "ACTIVITY_NAME", "content": "'.$booking_participates_mail['BookingParticipate']['service_title'].'"},';
		        $global_merge_vars .= '{"name": "ACTIVITY_DATE", "content": "'.$booking_participates_mail['BookingParticipate']['start_end_date'].'"},';
		        $global_merge_vars .= '{"name": "ACTIVITY_AMOUNT", "content": "'.$booking_participates_mail['BookingParticipate']['amount'].'"},';
		        $global_merge_vars .= '{"name": "URL", "content": "'.$this->setting['site']['site_url'].Router::url(array('plugin'=>'payment_manager','controller'=>'payments','action'=>'invite_payment_paypal/',$booking_participates_id)).'"}';
		        $global_merge_vars .= ']';


		        $key = 'RcGToklPpGQ56uCAkEpY5A';
				$template_name = 'user_invite_friend';


		        $data_string = '{
		                "key": "'.$key.'",
		                "template_name": "'.$template_name.'",
		                "template_content": [
		                        {
		                                "name": "TITLE",
		                                "content": "test test test"
		                        }
		                ],
		                "message": {
		                        "subject": "'.trim($mail['Mail']['mail_subject'])." ".$booking_detail['Booking']['fname'].'",
		                        "from_email": "'.$booking_detail['Booking']['email'].'",
		                        "from_name": "'.$mail['Mail']['mail_from'].'",
		                        "to": [
		                                {
		                                        "email": "'.$booking_participates_mail['BookingParticipate']['email'].'",
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
				curl_close($ch);
			}	
		}
	}
	// this is used to after payment not successful 
	function failled_url(){
		$this->loadModel('Booking');
		$this->Booking->updateAll(array('Booking.status' => 5),array('Booking.session_id'=>$this->Session->id(),'Booking.status' =>4));
		//for cancel page
		$this->redirect(array('plugin'=>'content_manager','controller'=>'pages','action'=>'view',20));
	}
	// this is used to after payment cancelled 
	public function cancelled_url(){
		$this->loadModel('Booking');
		$this->Booking->updateAll(array('Booking.status' => 5),array('Booking.session_id'=>$this->Session->id(),'Booking.status' =>4));
		//for cancel page
		$this->redirect(array('plugin'=>'content_manager','controller'=>'pages','action'=>'view',17));
	}
	function payment_summary($payment_ref=null){

		array_push(self::$css_for_layout,'payment/payment.css');
		if(empty($payment_ref)) {
			throw new NotFoundException('Could not find that payment reference');
		}
		// load model
		$this->loadModel('Cart');
		$this->loadModel('VendorManager.ServiceImage');
		$this->loadModel('ServiceManager.ServiceType');
		$this->loadModel('BookingOrder');
		$this->loadModel('Booking');
		$total_amount =0;
		$criteria = array();
		$booking_detail=$this->Booking->getBookingDetailsByPayment_ref($payment_ref);
		$criteria['fields']= array('BookingOrder.*');
		$criteria['conditions'] =array('BookingOrder.ref_no'=>$booking_detail['Booking']['ref_no']);
		$criteria['order'] =array('BookingOrder.id DESC');
		$criteria['group'] =array('BookingOrder.id');
		$order_details=$this->BookingOrder->find('all', $criteria);
		if(empty($order_details)) {
			throw new NotFoundException('Could not find that booking id');
		}
		foreach($order_details as $key=>$order_detail) {
			$order_details[$key]['BookingOrder']['servicetype']=$this->ServiceType->getServiceTypeNameByServiceId($order_detail['BookingOrder']['service_id']);
			
			if (is_null($order_detail['BookingOrder']['coupon_id'])) {
				$order_details[$key]['BookingOrder']['discount'] = 0;
			} else {
				$this->loadModel('Coupon');
				$coupon = $this->Coupon->find('first', ['conditions' => ['id' => $order_detail['BookingOrder']['coupon_id']]]);
				$order_details[$key]['BookingOrder']['discount'] = $coupon['Coupon']['discount'] * $order_detail['BookingOrder']['total_amount'];
			}
		}	 
		// delete email id of guest
		$this->Session->delete('Guest_email');
		// set variable 
		$this->set('order_details',$order_details);
		$this->set('booking_detail',$booking_detail);
				
	}
	
	// payment invite email
	
	function invite_payment($booking_order_id=null){
		//$this->autoRender=false;
		$this->loadModel('Booking');
		$this->loadModel('BookingOrder');
		$this->loadModel('BookingParticipate');
		App::uses('MemberAuthComponent', 'MemberManager.Controller/Component');
		$this->sessionKey = MemberAuthComponent::$sessionKey;
		$this->member_data = $this->Session->read($this->sessionKey);
		if(empty($this->member_data['MemberAuth']['id'])) {
			$this->Session->setFlash('Please login for payment.','default','','error');
			$this->redirect(array('controller'=>'members','action'=>'registration','plugin'=>'member_manager'));
		} 
		if($booking_order_id!=$this->request->data['booking_order_id']) {
			throw new NotFoundException('Could not find that booking id');
		}
		$criteria=array(); 
		$criteria['joins']=array(
							array(
								'table'=>'booking_orders',
								'alias' => 'BookingOrder',
								'type' => 'INNER',
								/*'foreignKey' => false,*/
								'conditions'=> array('BookingOrder.id=BookingParticipate.booking_order_id')
								),
							);
		$criteria['fields'] = array('BookingParticipate.*','BookingOrder.*');
		$criteria['conditions'] =array('BookingParticipate.booking_order_id'=>$booking_order_id,'BookingParticipate.email'=>$this->member_data['MemberAuth']['email_id']);
		 
		$criteria['order'] =array('BookingParticipate.id'=>'DESC');
		
		$invite_details=$this->BookingParticipate->find('first',$criteria);
		// check payment status of invite participate
		if($invite_details['BookingParticipate']['status']==1 || $invite_details['BookingOrder']['status']==3){
			$this->Session->setFlash('Payment already payments or cancelled.','default');
			$this->redirect(array('plugin'=>'member_manager','controller'=>'members','action'=>'dashboard'));
		}
		
		// save booking 
		$booking_data=array();
		$booking_data['Booking']['ref_no']=$invite_details['BookingOrder']['ref_no'];
		$booking_data['Booking']['member_id']=$this->member_data['MemberAuth']['id'];
		$booking_data['Booking']['email']=$this->member_data['MemberAuth']['email_id'];
		$booking_data['Booking']['fname']=$this->member_data['MemberAuth']['first_name'];
		$booking_data['Booking']['lname']=$this->member_data['MemberAuth']['last_name'];
		$booking_data['Booking']['phone']=$this->member_data['MemberAuth']['phone'];
		$booking_data['Booking']['booking_date']=date('Y-m-d H:i:s');
		$booking_data['Booking']['time_stamp']=date('Y-m-d H:i:s');
		$booking_data['Booking']['price']=$invite_details['BookingParticipate']['amount'];
		$booking_data['Booking']['session_id']=$this->Session->id();
		$booking_data['Booking']['ip_address']=$_SERVER['REMOTE_ADDR'];
		$booking_data['Booking']['browser_name']=$_SERVER['HTTP_USER_AGENT'];
		$booking_data['Booking']['status']=4;
		
		$this->Booking->create();
		$this->Booking->save($booking_data);
		$booking_id = $this->Booking->id;
		if(empty($this->Booking->id)){
			$this->redirect(array('plugin'=>'member_manager','controller'=>'members','action'=>'dashboard'));
		}
		$siteurl=$this->setting['site']['site_url'];
		$payment_data=array();
		$booking_participate_id = $invite_details['BookingParticipate']['id'];
		$payment_data['amount']=$invite_details['BookingParticipate']['amount'];
		$memberid = $this->member_data['MemberAuth']['id'];
		$payment_ref = time().$booking_id;
		$bookingData = Array();
		$payment_data['orderRef'] = $payment_ref;

		$payment_data['successUrl']=$siteurl.Router::url(array('plugin'=>'payment_manager','controller'=>'payments','action'=>'invite_payment_summary/'.$payment_ref.'/'.$booking_order_id));
		$payment_data['strUrl']=$siteurl.Router::url(array('plugin'=>'payment_manager','controller'=>'payments','action'=>'invite_process_ipn/'.$booking_participate_id.'/'.$booking_order_id));
		$payment_data['cancelUrl']=$siteurl.Router::url(array('plugin'=>'payment_manager','controller'=>'payments','action'=>'cancelled_url'));
		
		self::_save_payment_ref($booking_id,$payment_ref,$memberid);
		$formData = self::_smoovPay($payment_data,$bookingData);
		
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/'),
			'name'=>'Home'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/'),
			'name'=>'Invite Payment'
		);
		$this->set('formData',$formData);
		$this->render('index');
	} 

	function invite_payment_new($booking_participate_id = null)
	{
		//$this->autoRender=false;
		$this->loadModel('Booking');
		$this->loadModel('BookingOrder');
		$this->loadModel('BookingParticipate');
		App::uses('MemberAuthComponent', 'MemberManager.Controller/Component');
		$this->sessionKey = MemberAuthComponent::$sessionKey;
		$this->member_data = $this->Session->read($this->sessionKey);
		if(empty($this->member_data['MemberAuth']['id'])) {
			$this->Session->setFlash('Please Register&login and click the link again for payment.','default','','error');
			$this->redirect(array('controller'=>'members','action'=>'registration','plugin'=>'member_manager'));
		} 

		if($booking_participate_id == null) {
			throw new NotFoundException('Could not find that booking id');
		}

		$criteria=array(); 
		$criteria['joins']=array(
							array(
								'table'=>'booking_orders',
								'alias' => 'BookingOrder',
								'type' => 'INNER',
								/*'foreignKey' => false,*/
								'conditions'=> array('BookingOrder.id=BookingParticipate.booking_order_id')
								),
							);
		$criteria['fields'] = array('BookingParticipate.*','BookingOrder.*');
		$criteria['conditions'] =array('BookingParticipate.id'=>$booking_participate_id);
		 
		$criteria['order'] =array('BookingParticipate.id'=>'DESC');
		
		$invite_details=$this->BookingParticipate->find('first',$criteria);
		$booking_order_id = $invite_details['BookingParticipate']['booking_order_id'];
		// print_r($invite_details['BookingParticipate']['email']);die;
		if (empty($invite_details)) {
			throw new NotFoundException('Could not find that booking id');
		}

		// check payment status of invite participate
		if($invite_details['BookingParticipate']['status']==1 || $invite_details['BookingOrder']['status']==3){
			$this->Session->setFlash('Payment already payments or cancelled.','default');
			$this->redirect(array('plugin'=>'member_manager','controller'=>'members','action'=>'dashboard'));
		}
		
		// save booking 
		$booking_data=array();
		$booking_data['Booking']['ref_no']=$invite_details['BookingOrder']['ref_no'];
		$booking_data['Booking']['member_id']=$this->member_data['MemberAuth']['id'];
		$booking_data['Booking']['email']=$invite_details['BookingParticipate']['email'];
		$booking_data['Booking']['fname']=$this->member_data['MemberAuth']['first_name'];
		$booking_data['Booking']['lname']=$this->member_data['MemberAuth']['last_name'];
		$booking_data['Booking']['phone']=$this->member_data['MemberAuth']['phone'];
		$booking_data['Booking']['booking_date']=date('Y-m-d H:i:s');
		$booking_data['Booking']['time_stamp']=date('Y-m-d H:i:s');
		$booking_data['Booking']['price']=$invite_details['BookingParticipate']['amount'];
		$booking_data['Booking']['session_id']=$this->Session->id();
		$booking_data['Booking']['ip_address']=$_SERVER['REMOTE_ADDR'];
		$booking_data['Booking']['browser_name']=$_SERVER['HTTP_USER_AGENT'];
		$booking_data['Booking']['status']=4;
		
		$this->Booking->create();
		$this->Booking->save($booking_data);
		$booking_id = $this->Booking->id;
		if(empty($this->Booking->id)){
			$this->redirect(array('plugin'=>'member_manager','controller'=>'members','action'=>'dashboard'));
		}
		$siteurl=$this->setting['site']['site_url'];
		$payment_data=array();
		$booking_participate_id = $invite_details['BookingParticipate']['id'];
		$payment_data['amount']=$invite_details['BookingParticipate']['amount'];
		$memberid = $this->member_data['MemberAuth']['id'];
		$payment_ref = time().$booking_id;
		$bookingData[0]['Cart'] = $invite_details['BookingOrder'];
		$bookingData[0]['Service']['service_title'] = $bookingData[0]['Cart']['service_title'];
		$payment_data['orderRef'] = $payment_ref;

		$payment_data['successUrl']=$siteurl.Router::url(array('plugin'=>'payment_manager','controller'=>'payments','action'=>'invite_payment_summary/'.$payment_ref.'/'.$booking_order_id));
		$payment_data['strUrl']=$siteurl.Router::url(array('plugin'=>'payment_manager','controller'=>'payments','action'=>'invite_process_ipn/'.$booking_participate_id.'/'.$booking_order_id));
		$payment_data['cancelUrl']=$siteurl.Router::url(array('plugin'=>'payment_manager','controller'=>'payments','action'=>'cancelled_url'));
		self::_save_payment_ref($booking_id,$payment_ref,$memberid);
		$formData = self::_paypal_form($payment_data,$bookingData);
		
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/'),
			'name'=>'Home'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/'),
			'name'=>'Invite Payment'
		);
		$this->set('formData',$formData);
		$this->render('index');
	}
	
	function invite_payment_summary($payment_ref=null,$booking_order_id) {
		// load model
		$this->loadModel('VendorManager.ServiceImage');
		$this->loadModel('ServiceManager.ServiceType');
		$this->loadModel('BookingOrder');
		$this->loadModel('Booking');
		array_push(self::$css_for_layout,'payment/payment.css');

		if(empty($payment_ref)) {
			throw new NotFoundException('Could not find that payment reference');
		}
		$booking_detail=$this->Booking->getBookingDetailsByPayment_ref($payment_ref);
		
		if(empty($booking_detail)) {
			throw new NotFoundException('Could not find that payment details');
		}
		
		//get Booking order id
		$data=json_decode($booking_detail['Booking']['payment_log'],true);

		// delete session
		$total_amount =0;
		$criteria = array();
		$criteria['fields']= array('BookingOrder.*');
		$criteria['conditions'] =array('BookingOrder.id'=>$booking_order_id);
		$criteria['order'] =array('BookingOrder.id DESC');
		$criteria['group'] =array('BookingOrder.service_id');
		$booking_order_detail=$this->BookingOrder->find('first', $criteria);
	
		$booking_order_detail['BookingOrder']['servicetype']=$this->ServiceType->getServiceTypeNameByServiceId($booking_order_detail['BookingOrder']['service_id']);
		$this->set('booking_order_detail',$booking_order_detail);
		$this->set('booking_detail',$booking_detail);
	
	}
	
	public function invite_process_ipn($b_p_id=null,$booking_order_id=null){
		 // load model
		$this->loadModel('Cart');
		$this->loadModel('Booking');
		$this->loadModel('BookingOrder');
		$this->loadModel('BookingSlot');
		$this->loadModel('BookingParticipate');
		$this->loadModel('VendorManager.ServiceImage');
		App::uses('MemberAuthComponent', 'MemberManager.Controller/Component');
		$this->sessionKey = MemberAuthComponent::$sessionKey;
		$this->member_data = $this->Session->read($this->sessionKey);
		if ($_SERVER["REQUEST_METHOD"]=="POST") {
			$flag = $this->SmoovPay->validateIpn($_POST);
			if($flag==1){

				$booking = $this->Booking->find('first',array('conditions'=>array('Booking.payment_ref'=>$_POST['ref_id'])));
				$booking_id = $booking['Booking']['id'];
				$sessionId = $booking['Booking']['session_id'];
				$booking_data = array();
				$booking_data['Booking']['id'] = $booking_id;
				$booking_data['Booking']['transaction_amount']=$_POST['total_amount'];
				$booking_data['Booking']['status']=1;
				$booking_data['Booking']['transaction_id'] = $_POST['reference_code'];
				$booking_data['Booking']['booking_date'] = date('Y-m-d H:i:s');
				$booking_data['Booking']['time_stamp'] = date('Y-m-d H:i:s');
				$booking_data['Booking']['secureHash'] = $_POST['signature'];
				$booking_data['Booking']['payment_ref'] = $_POST['ref_id'];
				$booking_data['Booking']['payment_log'] = json_encode($_POST);
				$booking_data['Booking']['currency_code'] = $_POST['currency'];
				$booking_data['Booking']['merchantId'] = $_POST['merchant'];
				$booking_data['Booking']['price'] = $_POST['total_amount'];
				$this->Booking->create();
				$this->Booking->save($booking_data,array('validate' => false));
				$data=array();
				$data['BookingParticipate']['id'] = $b_p_id;
				$data['BookingParticipate']['booking_member_id']=$this->member_data['MemberAuth']['id'];
				$data['BookingParticipate']['status']= 1;
				// updating booking participate table
				$this->BookingParticipate->create();
				$this->BookingParticipate->save($data,array('validate' => false));
				// sending mail if payment status completed
				$booking_detail=$this->Booking->getBookingDetailsByBooking_id($booking_id);
				$booking_order_detail=$this->BookingOrder->read(null,$booking_order_id);
				// if booking is completed then mail
					if($data['BookingParticipate']['status']==1){
						self::invite_payment_mail($booking_detail,$booking_order_detail,$booking_or_id);
					}else{
						//self::payment_failed_mail($booking_detail);
					}
			}
			else{
				self::payment_failed_mail($booking_detail);
			}
		}
		$this->autoRender=false;
		return true;
	}
	private function invite_payment_mail($booking_detail=null,$booking_order_detail,$booking_order_id=null){
		$this->loadModel('VendorManager.Vendor');
		$this->loadModel('MailManager.Mail');
		$this->loadModel('ServiceManager.ServiceType');
		
		$service_slot_details = '';
		$total_cart_price=0;
		// get service type 
		$booking_order_detail['BookingOrder']['serviceTypeName']=$this->ServiceType->getServiceTypeNameByServiceId($booking_order_detail['BookingOrder']['service_id']);
		
		$siteurl=$this->setting['site']['site_url'];
		//get Booked content
		$booking_content = '
			<tr><th style="min-width:200px"><span style="font-size:14px">Vendor</span></th></tr>
			<tr><th><span style="font-size:14px">Service</span></th></tr>
			<tr><th style="min-width:200px"><span style="font-size:14px">Activity</span></th></tr>
			<tr><th><span style="font-size:14px">Date</span></th></tr>
			<tr><th style="min-width:200px"><span style="font-size:14px">Booking Time</span></th></tr>
			<tr><th><span style="font-size:14px">Participant(s)</span></th></tr>
			<tr><th style="min-width:200px"><span style="font-size:14px">Price ($)</span></th></tr>
			<tr><th><span style="font-size:14px">Min. to go status</span></th></tr>';
		$this->loadModel('Coupon');
		if (!is_null($booking_order_detail['BookingOrder']['coupon_id'])) {
			$discount = $this->Coupon->find('first', ['conditions' => ['coupon_id' => $booking_order_detail['BookingOrder']['coupon_id']]]);
			$booking_order_detail['BookingOrder']['discount'] = $discount['Coupon']['discount'];
		} else {
			$booking_order_detail['BookingOrder']['discount'] = 0;
		}
		$booking_content = self::getBookedServicesVertical($booking_order_detail, $booking_content);
		$total_cart_price += $booking_order_detail['BookingOrder']['total_amount'];
		
		$mail=$this->Mail->read(null,20);
		$this->loadModel('MemberManager.Member');
		$this->loadModel('VendorManager.Vendor');
		$this->loadModel('Service');

		$key = 'RcGToklPpGQ56uCAkEpY5A';
		$from = $this->setting['site']['site_contact_email'];
		$subject = 'Thank you for booking with us';
		$to = $booking_detail['Booking']['email'];
		$template_name = 'user_pending_booking_confirmation';
		$memberinfo = $this->Member->read(null,$booking_detail['Booking']['member_id']);
		if (!empty($memberinfo)) {
			$member_name = (strlen(trim($memberinfo['Member']['first_name'].' '.$memberinfo['Member']['last_name'])) > 0 ) ? $memberinfo['Member']['first_name'].' '.$memberinfo['Member']['last_name'] : 'Member';
		} else if(strlen(trim($booking_detail['Booking']['fname']." ".$booking_detail['Booking']['lname'])) > 0) {
			$member_name = $booking_detail['Booking']['fname']." ".$booking_detail['Booking']['lname'];
		} else {
			$member_name = 'Member';
		}


		$global_merge_vars = '[';
    	$global_merge_vars .= '{"name": "NAME", "content": "'.$member_name.'"},';
        $global_merge_vars .= '{"name": "EMAIL", "content": "'.$booking_detail['Booking']['email'].'"},';
        $global_merge_vars .= '{"name": "PHONE", "content": "'.$booking_detail['Booking']['phone'].'"},';
        $global_merge_vars .= '{"name": "BOOKING_DETAIL", "content": "'.str_replace(['"', "\n", "\t"],['\'', "", ""],$booking_content).'"}';
        $global_merge_vars .= ']';

        $data_string = '{
                "key": "'.$key.'",
                "template_name": "'.$template_name.'",
                "template_content": [
                        {
                                "name": "TITLE",
                                "content": "test test test"
                        }
                ],
                "message": {
                        "subject": "'.$mail['Mail']['mail_subject'].'",
                        "from_email": "'.$from.'",
                        "to": [
                                {
                                        "email": "'.$to.'",
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
		curl_close($ch);

		// send to vendor 
		self::vendor_mails($booking_detail['Booking']['ref_no']);

		// send to Admin mail
		// $mail=$this->Mail->read(null,18);
		// $body=str_replace('{ORDERNO}',$booking_detail['Booking']['ref_no'],$mail['Mail']['mail_body']);  
		// $body=str_replace('{ADMIN-NAME}','Admin',$body); 
		// $body=str_replace('{NAME}',$booking_detail['Booking']['fname']." ".$booking_detail['Booking']['lname'],$body);  
		// $body=str_replace('{EMAIL}',$booking_detail['Booking']['email'],$body);
		// $body=str_replace('{PHONE}',$booking_detail['Booking']['phone'],$body);
		// $body=str_replace('{ORDER_COMMENT}',(!empty($booking_detail['Booking']['order_message']))?$booking_detail['Booking']['order_message']:'There are no comments.',$body);
		// $body=str_replace('{TOTAL}',number_format($booking_detail['Booking']['transaction_amount']),$body);
		// $body=str_replace('{BOOKING_DETAIL}',$booking_content,$body);  
		// $email = new CakeEmail();

		
		// $email->to($this->setting['site']['site_contact_email'],$mail['Mail']['mail_from']);
		// $email->subject($mail['Mail']['mail_subject']);
		// $email->from($booking_detail['Booking']['email']);
		// $email->emailFormat('html');
		// $email->template('default');
		// $email->viewVars(array('data'=>$body,'logo'=>$this->setting['site']['logo'],'url'=>$this->setting['site']['site_url']));
		// $email->send();
		 
		// send to user mail


		
		

		// $vendor_details=array();
		// $vendor_details=$this->Vendor->vendorNameEmailById($booking_order_detail['BookingOrder']['vendor_id']);
		// $mail=$this->Mail->read(null,26);
		// $body=str_replace('{ORDERNO}',$booking_detail['Booking']['ref_no'],$mail['Mail']['mail_body']);  
		// $body=str_replace('{VENDER_NAME}',@$vendor_details['Vendor']['fname'],$body);  
		// $body=str_replace('{NAME}',$booking_detail['Booking']['fname']." ".$booking_detail['Booking']['lname'],$body);  
		// $body=str_replace('{EMAIL}',$booking_detail['Booking']['email'],$body);
		// $body=str_replace('{PHONE}',$booking_detail['Booking']['phone'],$body);
		// //$body=str_replace('{POST_CODE}',$booking_detail['Booking']['post_code'],$body);
		// $body=str_replace('{ORDER_COMMENT}',(!empty($booking_detail['Booking']['order_message']))?$booking_detail['Booking']['order_message']:'There are no comments.',$body);
		// $body=str_replace('{TOTAL}',number_format($booking_detail['Booking']['transaction_amount'],2),$body);
		// $body=str_replace('{BOOKING_DETAIL}',$booking_content,$body); 
		
		// $email = new CakeEmail();

		
		// if(!empty($vendor_details['Vendor']['email'])) {
		// 	$email->to($vendor_details['Vendor']['email'],$mail['Mail']['mail_from']);
		// }else{
		// 	$email->to($this->setting['site']['site_contact_email'],$mail['Mail']['mail_from']);
		// }
		
		// $email->subject($mail['Mail']['mail_subject']);
		// $email->from($booking_detail['Booking']['email']);
		// $email->emailFormat('html');
		// $email->template('default');
		// $email->viewVars(array('data'=>$body,'logo'=>$this->setting['site']['logo'],'url'=>$this->setting['site']['site_url']));
		// $email->send();
		return true;
	} 
	
	private function vendor_process_ipn($post_data=array()){
		
		$this->loadModel('VendorManager.Vendor');
		$successcode = $post_data['successcode'];							
		if ('0'==$successcode) {
			//payment successfull
			$payment_status=1;
		}else {
			//payment successfull
			$payment_status=0;
		}
		$vendor_id=$post_data['vendor_id'];
		$vendorinfo=$this->Vendor->read(null,$vendor_id);
		
		$data['Payment']['vendor_id']=$post_data['vendor_id'];
		$data['Payment']['payment_mode']="AsiaPay";
		$data['Payment']['total_amount']=$post_data['amount'];
		$data['Payment']['payment_amount']=$post_data['Amt'];
		$data['Payment']['payment_ref']=$post_data['Ref'];
		$data['Payment']['ip_address']=$_SERVER['REMOTE_ADDR'];
		$data['Payment']['transaction_id']=$post_data['PayRef'];
		$data['Payment']['email']=$vendorinfo['Vendor']['email'];
		$data['Payment']['payment_date']=date('Y-m-d H:i:s');
		$data['Payment']['status']=intval($payment_status);
		$this->Payment->create();				 
		$this->Payment->save($data);
		
		////********ending of saving records in database******/
		
		///********updating vendor status for active ********/
		$this->Vendor->updateAll(array('Vendor.active' => '1','Vendor.payment_status' => '1','Vendor.payment_date' => "'" . date('Y-m-d H:i:s') . "'"),array('Vendor.id'=> $vendor_id));
		
		//$vendors = $this->Vendor->read(null,$vendor_id); 
		///********ending of saving records in database******/		
		if($payment_status==1){
			$this->loadModel('MailManager.Mail');
			$this->loadModel('User');
			$mail=$this->Mail->read(null,9);

			$body=str_replace('{NAME}',$vendorinfo['Vendor']['fname'].' '.$vendorinfo['Vendor']['lname'],$mail['Mail']['mail_body']);
			$body=str_replace('{EMAIL}',$vendorinfo['Vendor']['email'],$body);
			$body=str_replace('{AMOUNT}',$post_data['Amt'],$body);
			///******Mail to Admin******/
			
			$email = new CakeEmail();

			
			//$admindetails = $this->User->read(null,$this->Session->read('Auth.User.id'));
			$email->to($this->setting['site']['site_contact_email']);
			$email->subject($mail['Mail']['mail_subject']);
			$email->from($vendorinfo['Vendor']['email']);
			//$email->from($this->site_setting['site_contact_email'],$mail['Mail']['mail_from']);
			$email->emailFormat('html');
			$email->template('default');
			$email->viewVars(array('data'=>$body,'logo'=>$this->setting['site']['logo'],'url'=>$this->setting['site']['site_url']));
			$email->send();

			///******Mail to User******/ 
			$mail=$this->Mail->read(null,8);
			$body=str_replace('{NAME}',$vendorinfo['Vendor']['fname'].' '.$vendorinfo['Vendor']['lname'],$mail['Mail']['mail_body']);
			$body=str_replace('{AMOUNT}','$'.$post_data['Amt'],$body);
			
			$email = new CakeEmail();

			

			$email->to($vendorinfo['Vendor']['email']);
			$email->subject($mail['Mail']['mail_subject']);
			$email->from($this->setting['site']['site_contact_email']);
			$email->emailFormat('html');
			$email->template('default');
			$email->viewVars(array('data'=>$body,'logo'=>$this->setting['site']['logo'],'url'=>$this->setting['site']['site_url']));
			$email->send();
		}
		return true; 
	}
	// this function is set asiapy varible set
	private function asiapay($payment_data){
		App::import('Vendor', 'assiapay', array('file' => 'assiapay' . DS . 'SHAPaydollarSecure.php'));
		$assiapay = new SHAPaydollarSecure();
		$payment_data['merchantId']=Configure::read('AsiaPay.merchantId');
		$payment_data['orderRef']=$payment_data['orderRef'];
		//$payment_data['orderRef']=time();
		$payment_data['currCode']=Configure::read('AsiaPay.currCode');
		//“344” – HKD  “840” – USD “702” – SGD “156” – CNY (RMB) “392” – JPY “901” – TWD “036” – AUD “978” – EUR “826” – GBP “124” – CAD
		$payment_data['amount']=$payment_data['amount'];
		$payment_data['payType']='N';
		$payment_data['mpsMode']="NIL";
		$payment_data['payMethod']="ALL";
		$payment_data['remark']=$payment_data['remark'];
		$payment_data['lang']=Configure::read('AsiaPay.lang');
		$payment_data['successUrl']=$payment_data['successUrl'];
		$payment_data['failUrl']=$payment_data['failUrl'];
		$payment_data['cancelUrl']=$payment_data['cancelUrl'];
		//Optional Parameter for connect to our payment page
		//$payment_data['remark']="";  
		$payment_data['redirect']="";
		$payment_data['oriCountry']="";
		$payment_data['destCountry']=""; 
		$payment_data['secureHash']=$assiapay->generatePaymentSecureHash($payment_data['merchantId'], $payment_data['orderRef'], $payment_data['currCode'], $payment_data['amount'], $payment_data['payType'], Configure::read('AsiaPay.secureHashSecret'));
		$this->set('payment_action',Configure::read('AsiaPay.payment_action'));
		$this->set('payment_data',$payment_data);
	}


	function _paypal_form($payment_data=null, $cartData=array(), $is_participant = false)
	{
		if(Configure::read('Payment.sandbox_mode')==1){
			$url = Configure::read('Payment.test_url');
		}
		else{
			$url = Configure::read('Payment.live_url');
		}
		$secret_key = Configure::read('Payment.secret_key');
		$merchant =Configure::read('Payment.merchant');
		$action = 'pay';
		$total_amount = $payment_data['amount'];
		$currency ="SGD"; 
		$ref_id = $payment_data['orderRef'];
		$dataToBeHashed = $secret_key. $merchant. $action. $ref_id. $total_amount. $currency;
		$get_signature = $this->SmoovPay->encryption($dataToBeHashed);
		$paypal_email = Configure::read('Paypal.email');
		$paypal_url = Configure::read('Paypal.url');
		if (Configure::read('Paypal.sandbox_mode')) {
			$paypal_email = Configure::read('Paypal.test_email');
			$paypal_url = Configure::read('Paypal.test_url');
		}
		
		$html = '';
		$i = 1;
		if(!empty($payment_data['amount'])){
			$html .= "<form action='$paypal_url' method='post' id='paypal_form'>";
			$html .= '<input type="hidden" name="cmd" value="_cart" />';
			$html .= '<input type="hidden" name="upload" value="1" />';
			$html .= "<input type='hidden' name='business' value='$paypal_email' />";
			$html .= '<input type="hidden" name="currency_code" value="SGD" />';
			if(!empty($cartData)){
				foreach($cartData as $cart_detail){
					$diff = abs(strtotime($cart_detail['Cart']['end_date']) - strtotime($cart_detail['Cart']['start_date']));
					$years = floor($diff / (365*60*60*24));
					$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
					$no_of_booking_days =(floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24)))+1;
					$itemname = $cart_detail['Service']['service_title'];
					// $desc = empty($cart_detail['Service']['description'])? 'NA':strip_tags($cart_detail['Service']['description']);
					$participants = ($cart_detail['Cart']['no_participants']+$no_of_booking_days);
					//$participants = $no_of_booking_days;
					$itemprice = $cart_detail['Cart']['price'];

					$slots = json_decode($cart_detail['Cart']['slots'],true);
					foreach ($slots['Slot'] as $slot_key=>$slot_time) {
						$desc = date('M d',strtotime($cart_detail['Cart']['start_date'])) . ',' . date('H:ia', strtotime($slot_time['start_time'])) . '-' . date('H:ia', strtotime($slot_time['end_time']));
						$participants = $cart_detail['Cart']['no_participants'] - count(json_decode($cart_detail['Cart']['invite_friend_email']));
						$itemprice = $slot_time['price'];
						if ($is_participant) {
							$participants = 1;
						}
						$html .= "<input type='hidden' name='item_name_$i' value='$itemname ($desc)' />";
						$html .= "<input type='hidden' name='quantity_$i' value='$participants' />";
						$html .= "<input type='hidden' name='amount_$i' value='$itemprice' />";
						$i++;
					}
				}
			}

			if ($this->Session->check('coupon_id')) {
				$this->loadModel('Coupon');
				$coupon = $this->Coupon->find('first', ['conditions' => ['id' => $this->Session->read('coupon_id')]]);
				$discount_amount = $total_amount * $coupon['Coupon']['discount'];
				$html .= "<input type='hidden' name='discount_amount_cart' value='$discount_amount' />";
				$this->Session->delete('coupon_id');
			}
			
			
			$html .= "<input type='hidden' name='cancel_return' value='$payment_data[cancelUrl]'/>";
			$html .= "<input type='hidden' name='return' value='$payment_data[successUrl]' />";
			$html .= "<input type='hidden' name='notify_url' value='$payment_data[strUrl]' />";
			$html .="</form>";
		}
		return $html;
	}

	function _paypal_url_data($payment_data=null, $cartData=array(), $is_participant = false)
	{
		if(Configure::read('Payment.sandbox_mode')==1){
			$url = Configure::read('Payment.test_url');
		}
		else{
			$url = Configure::read('Payment.live_url');
		}
		$secret_key = Configure::read('Payment.secret_key');
		$merchant =Configure::read('Payment.merchant');
		$action = 'pay';
		$total_amount = $payment_data['amount'];
		$currency ="SGD"; 
		$ref_id = $payment_data['orderRef'];
		$dataToBeHashed = $secret_key. $merchant. $action. $ref_id. $total_amount. $currency;
		$get_signature = $this->SmoovPay->encryption($dataToBeHashed);
		$paypal_email = Configure::read('Paypal.email');
		$paypal_url = Configure::read('Paypal.url');
		if (Configure::read('Paypal.sandbox_mode')) {
			$paypal_email = Configure::read('Paypal.test_email');
			$paypal_url = Configure::read('Paypal.test_url');
		}
		
		$html = '';
		$i = 1;
		$ud = [];
		if(!empty($payment_data['amount'])){
			$ud['cmd'] = '_cart';
			$ud['upload'] = 1;
			$ud['business'] = $paypal_email;
			$ud['currency_code'] = 'SGD';
			$html .= 'cmd=_cart&upload=1&business=' . $paypal_email . '&currency_code=SGD';
			if(!empty($cartData)){
				foreach($cartData as $cart_detail){
					$diff = abs(strtotime($cart_detail['Cart']['end_date']) - strtotime($cart_detail['Cart']['start_date']));
					$years = floor($diff / (365*60*60*24));
					$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
					$no_of_booking_days =(floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24)))+1;
					$itemname = $cart_detail['Service']['service_title'];
					// $desc = empty($cart_detail['Service']['description'])? 'NA':strip_tags($cart_detail['Service']['description']);
					$participants = ($cart_detail['Cart']['no_participants']+$no_of_booking_days);
					//$participants = $no_of_booking_days;
					$itemprice = $cart_detail['Cart']['price'];

					$slots = json_decode($cart_detail['Cart']['slots'],true);
					foreach ($slots['Slot'] as $slot_key=>$slot_time) {
						$desc = date('M d',strtotime($cart_detail['Cart']['start_date'])) . ',' . date('H:ia', strtotime($slot_time['start_time'])) . '-' . date('H:ia', strtotime($slot_time['end_time']));
						$participants = $cart_detail['Cart']['no_participants'] - count(json_decode($cart_detail['Cart']['invite_friend_email']));
						$itemprice = $slot_time['price'];
						if ($is_participant) {
							$participants = 1;
						}
						$ud["item_name_$i"] = $itemname . ' (' . $desc . ')';
						$ud["quantity_$i"] = $participants;
						$ud["amount_$i"] = $itemprice;
						$html .= "&item_name_{$i}={$itemname}%20($desc)&quantity_{$i}=$participants&amount_{$i}=$itemprice";
						$i++;
					}
				}
			}

			if ($this->Session->check('coupon_id')) {
				$this->loadModel('Coupon');
				$coupon = $this->Coupon->find('first', ['conditions' => ['id' => $this->Session->read('coupon_id')]]);
				$discount_amount = $total_amount * $coupon['Coupon']['discount'];
				$ud['discount_amount_cart'] = $discount_amount;
				$this->Session->delete('coupon_id');
			}
			
			$ud['cancel_url'] = $payment_data['cancelUrl'];
			$ud['return'] = $payment_data['successUrl'];
			$ud['notify_url'] = $payment_data['strUrl'];
		}
		$udx = '';
		foreach($ud as $k => $v) {
			$udx .= !empty($udx) ? '&' : '';
			$udx .= $k . '=' . urlencode($v);
		}
		return $paypal_url . "?" . $udx;
	}

	function paypal_ipn_simple($payment_ref = null)
	{
		// $v = '';
		// foreach ($_POST as $k => $va) {
		// 	$v .= "$k = $va\n";
		// }
		$this->layout='';
		$this->loadModel('Booking');
		$this->loadModel('BookingOrder');
		$this->loadModel('Cart');
		$this->loadModel('BookingSlot');
		$this->loadModel('ServiceManager.ServiceType');
		$this->loadModel('BookingCoupon');
		$this->loadModel('Coupon');
		if ($_SERVER["REQUEST_METHOD"]=="POST") {
			$status_num = 5;
			// $v .= "YES IT IS POST\n";
			if ($_POST['payment_status'] == 'Completed') {
				$status_num = 1;
				// $v .= "status_num = 1\n";
			} else if ( $_POST['payment_status'] == 'Pending') {
				$status_num = 4;
				// $v .= "status_num = 4\n";
			}
			if($payment_ref != null && ($status_num == 1 || $status_num == 4)){
				// $v .= "passed = $payment_ref \n";
				$booking = $this->Booking->find('first',array('conditions'=>array('Booking.payment_ref'=>$payment_ref)));
				$booking_id = $booking['Booking']['id'];
				$sessionId = $booking['Booking']['session_id'];
				$booking_ref_no = $this->Booking->getBookingRefenceByBooking_id($booking['Booking']['id']);
				
				$criteria = array();
				$criteria['fields']= array('Cart.*');
				$criteria['conditions'] =array('Cart.session_id'=>$sessionId,'Cart.status'=>1);
				$criteria['order'] =array('Cart.id ASC');
				$criteria['group'] =array('Cart.id');
				$cart_details=$this->Cart->find('all', $criteria);
				
				//update booking table
				$booking_data['Booking']['id']= $booking_id;
				$booking_data['Booking']['transaction_amount']=$_POST['mc_gross'];
				$booking_data['Booking']['status']=$status_num;
				$booking_data['Booking']['transaction_id'] = $_POST['txn_id'];
				$booking_data['Booking']['booking_date'] = date('Y-m-d H:i:s');
				$booking_data['Booking']['time_stamp'] = date('Y-m-d H:i:s');
				$booking_data['Booking']['secureHash'] = $_POST['verify_sign'];
				$booking_data['Booking']['payment_ref'] = $payment_ref;
				$booking_data['Booking']['payment_log'] = json_encode($_POST);
				$booking_data['Booking']['currency_code'] = $_POST['mc_currency'];
				//$booking_data['Booking']['card_holder']=$post_data['Holder'];
				//$booking_data['Booking']['authid']=$post_data['AuthId'];
				$booking_data['Booking']['merchantId'] = $_POST['receiver_id'];
				$booking_data['Booking']['price'] = $_POST['mc_gross'];
				$this->Booking->create();
				$this->Booking->save($booking_data,array('validate' => false));
				$booking_detail=$this->Booking->getBookingDetailsByBooking_id($booking_id);
				$this->BookingOrder->updateAll(
					array('BookingOrder.status' =>$status_num,'BookingOrder.payment_ref' => $payment_ref),
					array('BookingOrder.ref_no =' => $booking_ref_no)
				);
				$this->BookingSlot->updateAll(
					array('BookingSlot.status' => $status_num),
					array('BookingSlot.ref_no =' => $booking_ref_no)
				); 

				$service_slot_details='
				<tr><th style="min-width:200px"><span style="font-size:14px">Vendor</span></th></tr>
				<tr><th><span style="font-size:14px">Service</span></th></tr>
				<tr><th style="min-width:200px"><span style="font-size:14px">Activity</span></th></tr>
				<tr><th><span style="font-size:14px">Date</span></th></tr>
				<tr><th style="min-width:200px"><span style="font-size:14px">Booking Time</span></th></tr>
				<tr><th><span style="font-size:14px">Participant(s)</span></th></tr>
				<tr><th style="min-width:200px"><span style="font-size:14px">Price ($)</span></th></tr>
				<tr><th><span style="font-size:14px">Min. to go status</span></th></tr>';
				$total_cart_price=0;
				// check payment status
				if(!empty($cart_details)){
					// $v .= "with cart\n";
					if($booking_data['Booking']['status']==1 || $booking_data['Booking']['status']==4){
						foreach($cart_details as $cart_detail) {
							$slot_details=array();
							unset($cart_detail['Cart']['id']);
							$newData['BookingOrder']=$cart_detail['Cart'];
							$newData['BookingOrder']['ref_no']=$booking_detail['Booking']['ref_no'];
							// get serviceType name 
							$newData['BookingOrder']['serviceTypeName']=$this->ServiceType->getServiceTypeNameByServiceId($newData['BookingOrder']['service_id']);
							$coupon = $this->BookingCoupon->find('first', ['conditions' => ['booking_id' => $booking_detail['Booking']['id']]]);
							if (!empty($coupon)) {
								$discount = $this->Coupon->find('first', ['conditions' => ['coupon_id' => $coupon['BookingCoupon']['coupon_id']]]);
								$newData['BookingOrder']['discount'] = $discount['Coupon']['discount'];
							} else {
								$newData['BookingOrder']['discount'] = 0;
							}
							$service_slot_details=self::getBookedServicesVertical($newData,$service_slot_details); 	
							$total_cart_price+=$cart_detail['Cart']['total_amount'];
							//echo $service_slot_details;die;
							self::sent_invite_mail($cart_detail,number_format($total_cart_price,2),$booking_detail);
							// end of booking slot
							 
							 
						}
						
						//  // send to Admin mail
						// $this->loadModel('MailManager.Mail');
						// $maill=$this->Mail->read(null,13);
						// $body=str_replace('{ORDERNO}',$booking_detail['Booking']['ref_no'],$maill['Mail']['mail_body']);  
						// $body=str_replace('{ADMIN_NAME}','Admin',$body);  
						// $body=str_replace('{NAME}',$booking_detail['Booking']['fname']." ".$booking_detail['Booking']['lname'],$body);  
						// $body=str_replace('{EMAIL}',$booking_detail['Booking']['email'],$body);
						// $body=str_replace('{PHONE}',$booking_detail['Booking']['phone'],$body);
						// //$body=str_replace('{POST_CODE}',$booking_detail['Booking']['post_code'],$body);
						
						// $body=str_replace('{ORDER_COMMENT}',(!empty($booking_detail['Booking']['order_message']))?$booking_detail['Booking']['order_message']:'There are no comments.',$body);
						// $body=str_replace('{TOTAL}',number_format($total_cart_price,2),$body);
						// $body=str_replace('{BOOKING_DETAIL}',$service_slot_details,$body);  
						
						// $emaill = new CakeEmail();
						
						// $emaill->to($this->setting['site']['site_contact_email'],$maill['Mail']['mail_from']);
						// $emaill->subject($maill['Mail']['mail_subject']);
						// $emaill->from($booking_detail['Booking']['email']);
						// $emaill->emailFormat('html');
						// $emaill->template('default');
						// $emaill->viewVars(array('data'=>$body,'logo'=>$this->setting['site']['logo'],'url'=>$this->setting['site']['site_url']));
						// $emaill->send();

						// send to user mail

						$this->loadModel('MemberManager.Member');
						$this->loadModel('VendorManager.Vendor');
						$this->loadModel('Service');

						$key = 'RcGToklPpGQ56uCAkEpY5A';
						$from = $this->setting['site']['site_contact_email'];
						$subject = 'Thank you for booking with us';
						$to = $booking_detail['Booking']['email'];
						$template_name = 'user_pending_booking_confirmation';
						$memberinfo = $this->Member->read(null,$booking_detail['Booking']['member_id']);
						if (!empty($memberinfo)) {
							$member_name = (strlen(trim($memberinfo['Member']['first_name'].' '.$memberinfo['Member']['last_name'])) > 0 ) ? $memberinfo['Member']['first_name'].' '.$memberinfo['Member']['last_name'] : 'Member';
						} else if(strlen(trim($booking_detail['Booking']['fname']." ".$booking_detail['Booking']['lname'])) > 0) {
							$member_name = $booking_detail['Booking']['fname']." ".$booking_detail['Booking']['lname'];
						} else {
							$member_name = 'Member';
						}


						$global_merge_vars = '[';
				    	$global_merge_vars .= '{"name": "NAME", "content": "'.$member_name.'"},';
				        $global_merge_vars .= '{"name": "EMAIL", "content": "'.$booking_detail['Booking']['email'].'"},';
				        $global_merge_vars .= '{"name": "PHONE", "content": "'.$booking_detail['Booking']['phone'].'"},';
				        $global_merge_vars .= '{"name": "BOOKING_DETAIL", "content": "'.str_replace(['"', "\n", "\t"],['\'', "", ""],$service_slot_details).'"}';
				        $global_merge_vars .= ']';

				        $data_string = '{
				                "key": "'.$key.'",
				                "template_name": "'.$template_name.'",
				                "template_content": [
				                        {
				                                "name": "TITLE",
				                                "content": "test test test"
				                        }
				                ],
				                "message": {
				                        "subject": "'.$subject.'",
				                        "from_email": "'.$from.'",
				                        "to": [
				                                {
				                                        "email": "'.$to.'",
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
						

						// cart empty 
						$this->Cart->deleteAll(array('Cart.session_id'=>$sessionId));

						// send to vendor mail
						self::vendor_mails($booking_detail['Booking']['ref_no']);
						
					}
					else{
						//self::payment_failed_mail($booking_detail);
					}
				}
				// check min-to-go
				$this->loadModel('Service');
				$this->loadModel('VendorManager.BookingSlot');

				$booking_slots = $this->BookingSlot->find('all', ['conditions' => ['ref_no' => $booking_ref_no]]);
				foreach ($booking_slots as $booking_slot) {
					$bs = array_pop($booking_slot);
					$service = $this->Service->find('first', ['conditions' => ['id' => $bs['service_id']] ]);
					$service = array_pop($service);
					if ($service['min_participants'] == 0) continue;
					$paid_count = $this->BookingSlot->paidSlotCount($bs['service_id'], $bs['start_time'], $bs['end_time']);
					if ($paid_count < $service['min_participants']) continue;
					$booking_order = $this->BookingOrder->find('first', ['conditions' => ['ref_no' => $bs['ref_no']] ]);
					$booking_order = array_pop($booking_order);
					$vendor_email = $booking_order['vendor_email'];

					$slot_time = date('H:ia', strtotime($bs['start_time'])) . ' - ' . date('H:ia', strtotime($bs['end_time']));

					$global_merge_vars = '[';
			        $global_merge_vars .= '{"name": "USER_NAME", "content": "'.$booking_order['vendor_name'].'"},';
			        $global_merge_vars .= '{"name": "SERVICE_TITLE", "content": "'.$booking_order['service_title'].'"},';
			        $global_merge_vars .= '{"name": "PAX", "content": "'.$booking_order['no_participants'].'"},';
			        $global_merge_vars .= '{"name": "DATE", "content": "'.date('Y-m-d',strtotime($booking_order['booking_date'])).'"},';
			        $global_merge_vars .= '{"name": "SLOT_DATE", "content": "'.$slot_time.'"},';
			        $global_merge_vars .= '{"name": "VENDOR_NAME", "content": "'.$booking_order['vendor_name'].'"},';
			        $global_merge_vars .= '{"name": "TOTAL_PRICE", "content": "'.$booking_order['total_amount'].'"},';
		        	$global_merge_vars .= '{"name": "CONFIRM_LINK", "content": "'.$this->setting['site']['site_url'] . '/vendor/booking_list'.'"},';
			        $global_merge_vars .= '{"name": "PHONE", "content": "'.$booking_order['vendor_phone'].'"}';
			        $global_merge_vars .= ']';

			        $data_string = '{
			                "key": "RcGToklPpGQ56uCAkEpY5A",
			                "template_name": "minimum_to_go_reached",
			                "template_content": [
			                        {
			                                "name": "TITLE",
			                                "content": "test test test"
			                        }
			                ],
			                "message": {
			                        "subject": "Mimimum to go reached",
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
				}

			}
			else{
				// self::payment_failed_mail($booking_detail);
			}
		}

		// $f = fopen('ipn.txt', 'w');
		// fwrite($f, $v);
		// fclose($f);

		$this->autoRender=false;
		echo 'success';

	}

	public function paypal_ipn_invite($b_p_id=null,$booking_order_id=null,$payment_ref=null){
		 // load model
		$this->loadModel('Cart');
		$this->loadModel('Booking');
		$this->loadModel('BookingOrder');
		$this->loadModel('BookingSlot');
		$this->loadModel('BookingParticipate');
		$this->loadModel('VendorManager.ServiceImage');
		App::uses('MemberAuthComponent', 'MemberManager.Controller/Component');
		$this->sessionKey = MemberAuthComponent::$sessionKey;
		$this->member_data = $this->Session->read($this->sessionKey);
		if ($_SERVER["REQUEST_METHOD"]=="POST") {
			$status_num = 5;
			// $v .= "YES IT IS POST\n";
			if ($_POST['payment_status'] == 'Completed') {
				$status_num = 1;
				// $v .= "status_num = 1\n";
			} else if ( $_POST['payment_status'] == 'Pending') {
				$status_num = 4;
				// $v .= "status_num = 4\n";
			}
			if($payment_ref != null && ($status_num == 1 || $status_num == 4)){

				$booking = $this->Booking->find('first',array('conditions'=>array('Booking.payment_ref'=>$payment_ref)));
				$booking_id = $booking['Booking']['id'];
				$sessionId = $booking['Booking']['session_id'];
				$booking_data = array();

				$booking_data['Booking']['id'] = $booking_id;
				$booking_data['Booking']['transaction_amount']=$_POST['mc_gross'];
				$booking_data['Booking']['status'] = $status_num;
				$booking_data['Booking']['transaction_id'] = $_POST['txn_id'];
				$booking_data['Booking']['booking_date'] = date('Y-m-d H:i:s');
				$booking_data['Booking']['time_stamp'] = date('Y-m-d H:i:s');
				$booking_data['Booking']['secureHash'] = $_POST['verify_sign'];
				$booking_data['Booking']['payment_ref'] = $payment_ref;
				$booking_data['Booking']['payment_log'] = json_encode($_POST);
				$booking_data['Booking']['currency_code'] = $_POST['Holder'];
				$booking_data['Booking']['merchantId'] = $_POST['receiver_id'];
				$booking_data['Booking']['price'] = $_POST['mc_gross'];
				$this->Booking->create();
				$this->Booking->save($booking_data,array('validate' => false));
				$data=array();
				$data['BookingParticipate']['id'] = $b_p_id;
				$data['BookingParticipate']['booking_member_id']=$this->member_data['MemberAuth']['id'];
				$data['BookingParticipate']['status'] = $status_num;
				// updating booking participate table
				$this->BookingParticipate->create();
				$this->BookingParticipate->save($data,array('validate' => false));
				// sending mail if payment status completed
				$booking_detail=$this->Booking->getBookingDetailsByBooking_id($booking_id);
				$booking_order_detail=$this->BookingOrder->read(null,$booking_order_id);
				// if booking is completed then mail
					if($data['BookingParticipate']['status']==1 || $data['BookingParticipate']['status']==4){
						self::invite_payment_mail($booking_detail,$booking_order_detail,$booking_or_id);
					}else{
						//self::payment_failed_mail($booking_detail);
					}
				$this->autoRender=false;

				// check min-to-go
				$this->loadModel('Service');
				$this->loadModel('VendorManager.BookingSlot');

				$booking_slots = $this->BookingSlot->find('all', ['conditions' => ['ref_no' => $booking_ref_no]]);
				foreach ($booking_slots as $booking_slot) {
					$bs = array_pop($booking_slot);
					$service = $this->Service->find('first', ['conditions' => ['id' => $bs['service_id']] ]);
					$service = array_pop($service);
					if ($service['min_participants'] == 0) continue;
					$paid_count = $this->BookingSlot->paidSlotCount($bs['service_id'], $bs['start_time'], $bs['end_time']);
					if ($paid_count < $service['min_participants']) continue;
					$booking_order = $this->BookingOrder->find('first', ['conditions' => ['ref_no' => $bs['ref_no']] ]);
					$booking_order = array_pop($booking_order);
					$vendor_email = $booking_order['vendor_email'];

					$slot_time = date('H:ia', strtotime($bs['start_time'])) . ' - ' . date('H:ia', strtotime($bs['end_time']));

					$global_merge_vars = '[';
			        $global_merge_vars .= '{"name": "USER_NAME", "content": "'.$booking_order['vendor_name'].'"},';
			        $global_merge_vars .= '{"name": "SERVICE_TITLE", "content": "'.$booking_order['service_title'].'"},';
			        $global_merge_vars .= '{"name": "PAX", "content": "'.$booking_order['no_participants'].'"},';
			        $global_merge_vars .= '{"name": "DATE", "content": "'.date('Y-m-d',strtotime($booking_order['booking_date'])).'"},';
			        $global_merge_vars .= '{"name": "SLOT_DATE", "content": "'.$slot_time.'"},';
			        $global_merge_vars .= '{"name": "VENDOR_NAME", "content": "'.$booking_order['vendor_name'].'"},';
			        $global_merge_vars .= '{"name": "TOTAL_PRICE", "content": "'.$booking_order['total_amount'].'"},';
		        	$global_merge_vars .= '{"name": "CONFIRM_LINK", "content": "'.$this->setting['site']['site_url'] . '/vendor/booking_list'.'"},';
			        $global_merge_vars .= '{"name": "PHONE", "content": "'.$booking_order['vendor_phone'].'"}';
			        $global_merge_vars .= ']';

			        $data_string = '{
			                "key": "RcGToklPpGQ56uCAkEpY5A",
			                "template_name": "minimum_to_go_reached",
			                "template_content": [
			                        {
			                                "name": "TITLE",
			                                "content": "test test test"
			                        }
			                ],
			                "message": {
			                        "subject": "Mimimum to go reached",
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
				}
			}
			else{
				// self::payment_failed_mail($booking_detail);
			}
		}
		$this->autoRender=false;
		echo 'success';
	}

	function invite_payment_paypal($booking_participate_id = null)
	{
		//$this->autoRender=false;
		$this->layout = '';
		$this->loadModel('Booking');
		$this->loadModel('BookingOrder');
		App::uses('MemberAuthComponent', 'MemberManager.Controller/Component');
		$this->sessionKey = MemberAuthComponent::$sessionKey;
		$this->member_data = $this->Session->read($this->sessionKey);
		if(!isset($this->member_data['MemberAuth']['id'])) {
			$this->Session->setFlash('Please Register & login and click the link again for payment.','default','','error');
			$this->redirect(array('controller'=>'members','action'=>'log_in','plugin'=>'member_manager'));
		}
		
		$this->loadModel('BookingParticipate');

		if($booking_participate_id == null) {
			throw new NotFoundException('Could not find that booking id');
		}

		$criteria=array(); 
		$criteria['joins']=array(
							array(
								'table'=>'booking_orders',
								'alias' => 'BookingOrder',
								'type' => 'INNER',
								/*'foreignKey' => false,*/
								'conditions'=> array('BookingOrder.id=BookingParticipate.booking_order_id')
								),
							);
		$criteria['fields'] = array('BookingParticipate.*','BookingOrder.*');
		$criteria['conditions'] =array('BookingParticipate.id'=>$booking_participate_id);
		 
		$criteria['order'] =array('BookingParticipate.id'=>'DESC');
		
		$invite_details=$this->BookingParticipate->find('first',$criteria);
		$booking_order_id = $invite_details['BookingParticipate']['booking_order_id'];
		if (empty($invite_details)) {
			throw new NotFoundException('Could not find that booking id');
		}

		// check payment status of invite participate
		if($invite_details['BookingParticipate']['status']==1 || $invite_details['BookingOrder']['status']==3){
			$this->Session->setFlash('Payment already payments or cancelled.','default');
			$this->redirect(array('plugin'=>'member_manager','controller'=>'members','action'=>'dashboard'));
		}
		
		// save booking 
		$booking_data=array();
		$booking_data['Booking']['ref_no']=$invite_details['BookingOrder']['ref_no'];
		$booking_data['Booking']['member_id']=$this->member_data['MemberAuth']['id'];
		$booking_data['Booking']['email']=$invite_details['BookingParticipate']['email'];
		$booking_data['Booking']['fname']=$this->member_data['MemberAuth']['first_name'];
		$booking_data['Booking']['lname']=$this->member_data['MemberAuth']['last_name'];
		$booking_data['Booking']['phone']=$this->member_data['MemberAuth']['phone'];
		$booking_data['Booking']['booking_date']=date('Y-m-d H:i:s');
		$booking_data['Booking']['time_stamp']=date('Y-m-d H:i:s');
		$booking_data['Booking']['price']=$invite_details['BookingParticipate']['amount'];
		$booking_data['Booking']['session_id']=$this->Session->id();
		$booking_data['Booking']['ip_address']=$_SERVER['REMOTE_ADDR'];
		$booking_data['Booking']['browser_name']=$_SERVER['HTTP_USER_AGENT'];
		$booking_data['Booking']['status']=4;
		
		$this->Booking->create();
		$this->Booking->save($booking_data);
		$booking_id = $this->Booking->id;
		if(empty($this->Booking->id)){
			$this->redirect(array('plugin'=>'member_manager','controller'=>'members','action'=>'dashboard'));
		}
		$siteurl=$this->setting['site']['site_url'];
		$payment_data=array();
		$booking_participate_id = $invite_details['BookingParticipate']['id'];
		$payment_data['amount']=$invite_details['BookingParticipate']['amount'];
		$memberid = $this->member_data['MemberAuth']['id'];
		$payment_ref = time().$booking_id;
		$bookingData[0]['Cart'] = $invite_details['BookingOrder'];
		$bookingData[0]['Service']['service_title'] = $bookingData[0]['Cart']['service_title'];
		$payment_data['orderRef'] = $payment_ref;

		$payment_data['successUrl']=$siteurl.Router::url(array('plugin'=>'payment_manager','controller'=>'payments','action'=>'invite_payment_summary/'.$payment_ref.'/'.$booking_order_id));
		$payment_data['strUrl']=$siteurl.Router::url(array('plugin'=>'payment_manager','controller'=>'payments','action'=>'paypal_ipn_invite/'.$booking_participate_id.'/'.$booking_order_id.'/'.$payment_ref));
		$payment_data['cancelUrl']=$siteurl.Router::url(array('plugin'=>'payment_manager','controller'=>'payments','action'=>'cancelled_url'));
		self::_save_payment_ref($booking_id,$payment_ref,$memberid);
		// $formData = self::_paypal_form($payment_data, $bookingData, true);
		
		// $this->breadcrumbs[] = array(
		// 	'url'=>Router::url('/'),
		// 	'name'=>'Home'
		// );
		// $this->breadcrumbs[] = array(
		// 	'url'=>Router::url('/'),
		// 	'name'=>'Invite Payment'
		// );
		// $this->set('formData',$formData);
		// $this->render('index');

		// new secured checkout
		$this->autoRender = false;
		$urldata = self::_paypal_url_data($payment_data, $bookingData, true);
		$this->redirect($urldata);
	}
}
?>
