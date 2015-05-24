<?php
class PaymentsController extends PaymentManagerAppController{
	public $uses = array('PaymentManager.Payment');
	public $components = array('Email');
	public $paginate = array();
	public $id = null;
	
	// This is used to payment by booking id  
	function index($booking_id=null){
		$this->loadModel('BookingSlot');
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
		$criteria['fields']= array('Cart.price','Cart.value_added_price','Service.service_title','Cart.total_amount');
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
		$booking_ref_no=$this->Booking->getBookingRefenceByBooking_id($booking_id);
		// Get total cart price 
		$total_cart_price=0;
		foreach($cart_details as $cart_detail){
			$total_cart_price+=$cart_detail['Cart']['total_amount'];
			$cart_service_names[]=$cart_detail['Service']['service_title'];
		 }
		$siteurl=$this->setting['site']['site_url'];
		$payment_data=array();
		$payment_data['amount']=$total_cart_price;
		$payment_data['orderRef']=$booking_id.$booking_ref_no;
		$payment_data['successUrl']=$siteurl.Router::url(array('plugin'=>'payment_manager','controller'=>'payments','action'=>'payment_summary'));
		$payment_data['failUrl']=$siteurl.Router::url(array('plugin'=>'payment_manager','controller'=>'payments','action'=>'failled_url'));
		$payment_data['cancelUrl']=$siteurl.Router::url(array('plugin'=>'payment_manager','controller'=>'payments','action'=>'cancelled_url'));
		// 1 for simple user booking
		// 2 for user inviter booking
		$payment_data['remark']="&member_id=".$this->member_data['MemberAuth']['id'].'&booking_id='.$booking_id.'&session_id=' . $this->Session->id()."&amount=".$total_cart_price."&email=".$guest_email."&ref_no=".$booking_ref_no."&type=1";
		self :: asiapay($payment_data);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/'),
			'name'=>'Home'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/'),
			'name'=>'Payment'
		);
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
	private function simple_payment_ipn($post_data){
		
		//Return bank host status code (primary).
		$successcode = $post_data['successcode'];							
		//0- succeeded, 1- failure, Others - error
		if ('0'==$successcode) {
			//payment successfull
			$payment_status=1;
		}else {
			//payment successfull
			$payment_status=0;
		}
				
		$criteria = array();
		$criteria['fields']= array('Cart.*');
		$criteria['conditions'] =array('Cart.session_id'=>$post_data['session_id'],'Cart.status'=>1);
		$criteria['order'] =array('Cart.id ASC');
		$criteria['group'] =array('Cart.id');
		$cart_details=$this->Cart->find('all', $criteria);
		//update booking table
		$booking_data['Booking']['id']=$post_data['booking_id'];
		$booking_data['Booking']['member_id']=(!empty($post_data['member_id']))?$post_data['member_id']:null;
		$booking_data['Booking']['transaction_amount']=$post_data['Amt'];
		$booking_data['Booking']['status']=$payment_status;
		$booking_data['Booking']['transaction_id']=$post_data['PayRef'];
		$booking_data['Booking']['booking_date']=date('Y-m-d H:i:s');
		$booking_data['Booking']['time_stamp']=date('Y-m-d H:i:s');
		$booking_data['Booking']['secureHash']=$secureHashSecret;
		$booking_data['Booking']['payment_ref']=$post_data['Ref'];
		$booking_data['Booking']['payment_log']=json_encode($post_data);
		$booking_data['Booking']['currency_code']=$post_data['Cur'];
		$booking_data['Booking']['card_holder']=$post_data['Holder'];
		$booking_data['Booking']['authid']=$post_data['AuthId'];
		$booking_data['Booking']['merchantId']=$post_data['MerchantId'];
		$booking_data['Booking']['price']=$post_data['amount'];
		//$this->Booking->create();
		$this->Booking->save($booking_data,array('validate' => false));
		$booking_detail=$this->Booking->getBookingDetailsByBooking_id($post_data['booking_id']);
		$this->BookingOrder->updateAll(
			array('BookingOrder.status' =>$payment_status,'BookingOrder.payment_ref' => $post_data['Ref']),
			array('BookingOrder.ref_no =' => $post_data['ref_no'])
		);
		$this->BookingSlot->updateAll(
			array('BookingSlot.status' => $payment_status),
			array('BookingSlot.ref_no =' =>  $post_data['ref_no'])
		); 
		/*$this->BookingParticipate->updateAll(
			array('BookingParticipate.status' => $payment_status),
			array('BookingParticipate.ref_no =' =>  $post_data['ref_no'])
		); */
		
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
				 // send to Admin mail
				$this->loadModel('MailManager.Mail');
				$mail=$this->Mail->read(null,13);
				$body=str_replace('{ORDERNO}',$booking_detail['Booking']['ref_no'],$mail['Mail']['mail_body']);  
				$body=str_replace('{ADMIN_NAME}','Admin',$body);  
				$body=str_replace('{NAME}',$booking_detail['Booking']['fname']." ".$booking_detail['Booking']['lname'],$body);  
				$body=str_replace('{EMAIL}',$booking_detail['Booking']['email'],$body);
				$body=str_replace('{PHONE}',$booking_detail['Booking']['phone'],$body);
				//$body=str_replace('{POST_CODE}',$booking_detail['Booking']['post_code'],$body);
				
				$body=str_replace('{ORDER_COMMENT}',(!empty($booking_detail['Booking']['order_message']))?$booking_detail['Booking']['order_message']:'There are no comments.',$body);
				$body=str_replace('{TOTAL}',number_format($total_cart_price,2),$body);
				$body=str_replace('{BOOKING_DETAIL}',$service_slot_details,$body);  
				
				$email = new CakeEmail();
				$email->to($this->setting['site']['site_contact_email'],$mail['Mail']['mail_from']);
				$email->subject($mail['Mail']['mail_subject']);
				$email->from($booking_detail['Booking']['email']);
				$email->emailFormat('html');
				$email->template('default');
				$email->viewVars(array('data'=>$body,'logo'=>$this->setting['site']['logo'],'url'=>$this->setting['site']['site_url']));
				$email->send();
				
		
				// send to user mail
				$mail=$this->Mail->read(null,14);
				$body=str_replace('{ORDERNO}',$booking_detail['Booking']['ref_no'],$mail['Mail']['mail_body']);  
				$body=str_replace('{NAME}',$booking_detail['Booking']['fname']." ".$booking_detail['Booking']['lname'],$body);  
				$body=str_replace('{EMAIL}',$booking_detail['Booking']['email'],$body);
				$body=str_replace('{PHONE}',$booking_detail['Booking']['phone'],$body);
				//$body=str_replace('{POST_CODE}',$booking_detail['Booking']['post_code'],$body);
				$body=str_replace('{ORDER_COMMENT}',(!empty($booking_detail['Booking']['order_message']))?$booking_detail['Booking']['order_message']:'There are no comments.',$body);
				$body=str_replace('{TOTAL}',number_format($total_cart_price,2),$body);
				$body=str_replace('{BOOKING_DETAIL}',$service_slot_details,$body); 
				
				$email = new CakeEmail();
				$email->from($this->setting['site']['site_contact_email'],$mail['Mail']['mail_from']);
				$email->subject($mail['Mail']['mail_subject']);
				$email->to($booking_detail['Booking']['email']);
				$email->emailFormat('html');
				$email->template('default');
				$email->viewVars(array('data'=>$body,'logo'=>$this->setting['site']['logo'],'url'=>$this->setting['site']['site_url']));
				$email->send();
				
				// cart empty 
				$this->Cart->deleteAll(array('Cart.session_id'=>$post_data['session_id']));
				
				// send to vendor mail
				self::vendor_mails($booking_detail['Booking']['ref_no']);
				
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
	$customer_detail=$this->Booking->find('first',array('conditions'=>array('Booking.ref_no'=>$booking_ref_no)));
	$criteria['conditions']=array('BookingOrder.ref_no'=>$booking_ref_no);
	$criteria['fields']=array('BookingOrder.vendor_id');
	$criteria['group']=array('BookingOrder.vendor_id');
	$criteria['order']=array('BookingOrder.id ASC');
	$order_details=$this->BookingOrder->find('all',$criteria);	
	foreach($order_details as $key=>$order_detail) {
		$booking_content='';
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
					 
					$booking_content.=self::getBookedServices($order);		
					$total_cart_price+=$order['BookingOrder']['total_amount'];
				}
		
				$this->loadModel('MailManager.Mail');
				$mail=$this->Mail->read(null,16);
				$body=str_replace('{ORDERNO}',$customer_detail['Booking']['ref_no'],$mail['Mail']['mail_body']);  
				$body=str_replace('{VENDOR_NAME}',$vendor_details['Vendor']['fname'],$body);  
				$body=str_replace('{NAME}',$customer_detail['Booking']['fname']." ".$customer_detail['Booking']['lname'],$body);  
				$body=str_replace('{EMAIL}',$customer_detail['Booking']['email'],$body);
				$body=str_replace('{PHONE}',$customer_detail['Booking']['phone'],$body);
				//$body=str_replace('{POST_CODE}',$customer_detail['Booking']['post_code'],$body);
				
				$body=str_replace('{ORDER_COMMENT}',(!empty($booking_detail['Booking']['order_message']))?$booking_detail['Booking']['order_message']:'There are no comments.',$body);
				$body=str_replace('{TOTAL}',number_format($total_cart_price,2),$body);
				$body=str_replace('{BOOKING_DETAIL}',$booking_content,$body);  
				$email = new CakeEmail();
				$email->to($vendor_details['Vendor']['email'],$mail['Mail']['mail_from']);
				$email->subject($mail['Mail']['mail_subject']);
				$email->from($customer_detail['Booking']['email']);
				$email->emailFormat('html');
				$email->template('default');
				$email->viewVars(array('data'=>$body,'logo'=>$this->setting['site']['logo'],'url'=>$this->setting['site']['site_url']));
				$email->send();
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
		$slot_details=self::getBookingSlot($orderBooked['BookingOrder']['slots']);
		$booked_slot_details=(!empty($slot_details))? implode('<br>',$slot_details):'Full day';
		$participant_emails=self::getBookedParticipantEmail($orderBooked['BookingOrder']['invite_friend_email']);
		// get booked vas service;
		$booked_vas_details=self::getBookedVas($orderBooked['BookingOrder']['value_added_services']);
		
		$booking_content='<tr>	
			<td style="border:solid 1px #E2E2E2;padding:2px 5px;">'.ucfirst($orderBooked['BookingOrder']['vendor_name']).'</td> 
			<td style="border:solid 1px #E2E2E2;padding:2px 5px;">'.ucfirst($orderBooked['BookingOrder']['serviceTypeName']).'</td> 
			<td style="border:solid 1px #E2E2E2;padding:2px 5px;">'.ucfirst($orderBooked['BookingOrder']['service_title']).'</td> 
			<td style="border:solid 1px #E2E2E2;padding:2px 5px;">'.date(Configure::read('Calender_format_php'),strtotime($orderBooked['BookingOrder']['start_date'])).' To '.date(Configure::read('Calender_format_php'),strtotime($orderBooked['BookingOrder']['end_date'])).'</td> 
			<td style="border:solid 1px #E2E2E2;padding:2px 5px;">'.$booked_slot_details.'</td> 
			<td style="border:solid 1px #E2E2E2;padding:2px 5px;">'.$participant_emails.'</td>
			<td style="border:solid 1px #E2E2E2;padding:2px 5px;">'.$booked_vas_details.'</td>
			<td style="border:solid 1px #E2E2E2;padding:2px 5px;">'.
				number_format(($orderBooked['BookingOrder']['total_amount']),2).'
			</td>
			  
		</tr>';
		 
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
		return $booked_participant_emails;
		
	}
	// Get booking VAS
	private function getBookedVas($vas_services){
		$booked_vas_details='&nbsp;';
		if(!empty($vas_services)){
			$vas_details=json_decode($vas_services,true);
			if(!empty($vas_details)){
				$booked_vas_details='';
				foreach($vas_details as $key=>$vas){
					$booked_vas_details.=
					'<div>'.$vas['value_added_name'].'&nbsp;&nbsp;&nbsp;($'.$vas['value_added_price'].')'.'</div><br/>';
				}
			}
		}
		return $booked_vas_details;
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
				$body=str_replace('{FRIEND_NAME}',$booking_detail['Booking']['fname']." ".$booking_detail['Booking']['lname'],$mail['Mail']['mail_body']);  
				$body=str_replace('{ACTIVITY_NAME}',$booking_participates_mail['BookingParticipate']['service_title'],$body);  
				$body=str_replace('{ACTIVITY_NAME}',$booking_participates_mail['BookingParticipate']['service_title'],$body);  
				$body=str_replace('{ACTIVITY_DATE}',$booking_participates_mail['BookingParticipate']['start_end_date'],$body);  
				$body=str_replace('{ACTIVITY_AMOUNT}',$booking_participates_mail['BookingParticipate']['amount'],$body);  
				$body=str_replace('{URL}',$this->setting['site']['site_url'].Router::url(array('plugin'=>'member_manager','controller'=>'members','action'=>'registration',$booking_participates_mail['BookingParticipate']['email'])),$body);  
				
				$email = new CakeEmail();
				$email->from($booking_detail['Booking']['email'],$mail['Mail']['mail_from']);
				$email->subject(trim($mail['Mail']['mail_subject'])." ".$booking_detail['Booking']['fname']);
				$email->to($booking_participates_mail['BookingParticipate']['email']);
				$email->emailFormat('html');
				$email->template('default');
				$email->viewVars(array('data'=>$body,'logo'=>$this->setting['site']['logo'],'url'=>$this->setting['site']['site_url'])); 
				$email->send();
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
	function cancelled_url(){
		$this->loadModel('Booking');
		$this->Booking->updateAll(array('Booking.status' => 5),array('Booking.session_id'=>$this->Session->id(),'Booking.status' =>4));
		//for cancel page
		$this->redirect(array('plugin'=>'content_manager','controller'=>'pages','action'=>'view',17));
	}
	function payment_summary(){
		$payment_ref=$this->params->query['Ref'];
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
		$criteria['fields']= array('BookingOrder.*');
		$criteria['conditions'] =array('BookingOrder.payment_ref'=>$payment_ref);
		$criteria['order'] =array('BookingOrder.id DESC');
		$criteria['group'] =array('BookingOrder.id');
		$order_details=$this->BookingOrder->find('all', $criteria);
		$booking_detail=$this->Booking->getBookingDetailsByPayment_ref($payment_ref);
		if(empty($order_details)) {
			throw new NotFoundException('Could not find that booking id');
		}
		foreach($order_details as $key=>$order_detail) {
			$order_details[$key]['BookingOrder']['servicetype']=$this->ServiceType->getServiceTypeNameByServiceId($order_detail['BookingOrder']['service_id']);
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
		if(empty($this->Booking->id)){
			$this->redirect(array('plugin'=>'member_manager','controller'=>'members','action'=>'dashboard'));
		}
		$siteurl=$this->setting['site']['site_url'];
		$payment_data=array();
		$payment_data['amount']=$invite_details['BookingParticipate']['amount'];//$payment_data['orderRef']=$booking_order_id.$invite_details['BookingOrder']['ref_no'];
		$payment_data['orderRef']=time();
		$payment_data['successUrl']=$siteurl.Router::url(array('plugin'=>'payment_manager','controller'=>'payments','action'=>'invite_payment_summary'));
		$payment_data['failUrl']=$siteurl.Router::url(array('plugin'=>'payment_manager','controller'=>'payments','action'=>'failled_url'));
		$payment_data['cancelUrl']=$siteurl.Router::url(array('plugin'=>'payment_manager','controller'=>'payments','action'=>'cancelled_url'));
		// 1 for simple user booking
		// 2 for user inviter booking
		$payment_data['remark']="&member_id=".$this->member_data['MemberAuth']['id'].'&booking_id='.$this->Booking->id.'&booking_order_id='.$this->request->data['booking_order_id'].'&session_id=' . $this->Session->id()."&amount=".$invite_details['BookingParticipate']['amount']."&ref_no=".$invite_details['BookingOrder']['ref_no']."&b_p_id=".$invite_details['BookingParticipate']['id']."&type=2";
		self :: asiapay($payment_data);
		
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/'),
			'name'=>'Home'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/'),
			'name'=>'Invite Payment'
		);
		$this->render('index');
	} 	
	
	function invite_payment_summary() {
		// load model
		$this->loadModel('VendorManager.ServiceImage');
		$this->loadModel('ServiceManager.ServiceType');
		$this->loadModel('BookingOrder');
		$this->loadModel('Booking');
		// get payment ref 
		$payment_ref=$this->params->query['Ref'];
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
		$criteria['conditions'] =array('BookingOrder.id'=>$data['booking_order_id']);
		$criteria['order'] =array('BookingOrder.id DESC');
		$criteria['group'] =array('BookingOrder.service_id');
		$booking_order_detail=$this->BookingOrder->find('first', $criteria);
	
		$booking_order_detail['BookingOrder']['servicetype']=$this->ServiceType->getServiceTypeNameByServiceId($booking_order_detail['BookingOrder']['service_id']);
		$this->set('booking_order_detail',$booking_order_detail);
		$this->set('booking_detail',$booking_detail);
	
	}
	
	private function invite_process_ipn($post_data){
		 // load model
		$this->loadModel('Cart');
		$this->loadModel('Booking');
		$this->loadModel('BookingOrder');
		$this->loadModel('BookingSlot');		
		$this->loadModel('BookingParticipate');
		$this->loadModel('VendorManager.ServiceImage');
		$successcode = $post_data['successcode'];							
		if ('0'==$successcode) {
			//payment successfull
			$payment_status=1;
		}else {
			//payment successfull
			$payment_status=5;
		}
		$booking_data = array();
		$booking_data['Booking']['id']=$post_data['booking_id'];
		$booking_data['Booking']['transaction_amount']=$post_data['Amt'];
		$booking_data['Booking']['status']=$payment_status;
		$booking_data['Booking']['transaction_id']=$post_data['PayRef']; //here payment ref is used for transction_id
		$booking_data['Booking']['time_stamp']=date('Y-m-d H:i:s');
		$booking_data['Booking']['booking_date']=date('Y-m-d H:i:s');
		$booking_data['Booking']['secureHash']=$secureHashSecret;
		$booking_data['Booking']['payment_ref']=$post_data['Ref'];
		$booking_data['Booking']['payment_log']=json_encode($post_data);
		$booking_data['Booking']['currency_code']=$post_data['Cur'];
		$booking_data['Booking']['card_holder']=$post_data['Holder'];
		$booking_data['Booking']['authid']=$post_data['AuthId'];
		$booking_data['Booking']['merchantId']=$post_data['MerchantId'];
		$booking_data['Booking']['price']=$post_data['amount'];
		$this->Booking->create();
		$this->Booking->save($booking_data,array('validate' => false));
		$data=array();
		$data['BookingParticipate']['id']=$post_data['b_p_id'];
		$data['BookingParticipate']['booking_member_id']=$post_data['member_id'];
		$data['BookingParticipate']['status']=$payment_status;
		// upldating booking participate table
		$this->BookingParticipate->create();
		$this->BookingParticipate->save($data,array('validate' => false));
		// sending mail if payment status completed
		$booking_detail=$this->Booking->getBookingDetailsByBooking_id($post_data['booking_id']);
		$booking_order_detail=$this->BookingOrder->read(null,$post_data['booking_order_id']);
		// if booking is completed then mail
		if($data['BookingParticipate']['status']==1){
			self::invite_payment_mail($booking_detail,$booking_order_detail,$post_data['booking_order_id']);
		}else{
			self::payment_failed_mail($booking_detail);
		}
		$this->autoRender=false;
	}
	private function invite_payment_mail($booking_detail=null,$booking_order_detail,$booking_order_id=null){
		$this->loadModel('VendorManager.Vendor');
		$this->loadModel('MailManager.Mail');
		$this->loadModel('ServiceManager.ServiceType');
		
		$service_slot_details=$booking_content='';
		$total_cart_price=0;
		// get service type 
		$booking_order_detail['BookingOrder']['serviceTypeName']=$this->ServiceType->getServiceTypeNameByServiceId($booking_order_detail['BookingOrder']['service_id']);
		
		$siteurl=$this->setting['site']['site_url'];
		//get Booked content
		$booking_content.=self::getBookedServices($booking_order_detail);
		$total_cart_price+=$booking_order_detail['BookingOrder']['total_amount'];
		
		// send to Admin mail
		$mail=$this->Mail->read(null,18);
		$body=str_replace('{ORDERNO}',$booking_detail['Booking']['ref_no'],$mail['Mail']['mail_body']);  
		$body=str_replace('{ADMIN-NAME}','Admin',$body); 
		$body=str_replace('{NAME}',$booking_detail['Booking']['fname']." ".$booking_detail['Booking']['lname'],$body);  
		$body=str_replace('{EMAIL}',$booking_detail['Booking']['email'],$body);
		$body=str_replace('{PHONE}',$booking_detail['Booking']['phone'],$body);
		$body=str_replace('{ORDER_COMMENT}',(!empty($booking_detail['Booking']['order_message']))?$booking_detail['Booking']['order_message']:'There are no comments.',$body);
		$body=str_replace('{TOTAL}',number_format($booking_detail['Booking']['transaction_amount']),$body);
		$body=str_replace('{BOOKING_DETAIL}',$booking_content,$body);  
		$email = new CakeEmail();
		$email->to($this->setting['site']['site_contact_email'],$mail['Mail']['mail_from']);
		$email->subject($mail['Mail']['mail_subject']);
		$email->from($booking_detail['Booking']['email']);
		$email->emailFormat('html');
		$email->template('default');
		$email->viewVars(array('data'=>$body,'logo'=>$this->setting['site']['logo'],'url'=>$this->setting['site']['site_url']));
		$email->send();
		 
		// send to user mail
		$mail=$this->Mail->read(null,20);
		$body=str_replace('{ORDERNO}',$booking_detail['Booking']['ref_no'],$mail['Mail']['mail_body']);  
		
		$body=str_replace('{NAME}',$booking_detail['Booking']['fname']." ".$booking_detail['Booking']['lname'],$body);  
		$body=str_replace('{USER-NAME}',$booking_detail['Booking']['fname']." ".$booking_detail['Booking']['lname'],$body); 
		$body=str_replace('{EMAIL}',$booking_detail['Booking']['email'],$body);
		$body=str_replace('{PHONE}',$booking_detail['Booking']['phone'],$body);
		//$body=str_replace('{POST_CODE}',$booking_detail['Booking']['post_code'],$body);
		
		$body=str_replace('{ORDER_COMMENT}',(!empty($booking_detail['Booking']['order_message']))?$booking_detail['Booking']['order_message']:'There are no comments.',$body);
		$body=str_replace('{TOTAL}',number_format($booking_detail['Booking']['transaction_amount'],2),$body);
		$body=str_replace('{BOOKING_DETAIL}',$booking_content,$body);  
		
		$email = new CakeEmail();
		$email->to($booking_detail['Booking']['email']);
		$email->subject($mail['Mail']['mail_subject']);
		$email->from($this->setting['site']['site_contact_email'],$mail['Mail']['mail_from']);
		$email->emailFormat('html');
		$email->template('default');
		$email->viewVars(array('data'=>$body,'logo'=>$this->setting['site']['logo'],'url'=>$this->setting['site']['site_url']));
		$email->send();
		
		// send to vendor 
		$vendor_details=array();
		$vendor_details=$this->Vendor->vendorNameEmailById($booking_order_detail['BookingOrder']['vendor_id']);
		$mail=$this->Mail->read(null,26);
		$body=str_replace('{ORDERNO}',$booking_detail['Booking']['ref_no'],$mail['Mail']['mail_body']);  
		$body=str_replace('{VENDER_NAME}',@$vendor_details['Vendor']['fname'],$body);  
		$body=str_replace('{NAME}',$booking_detail['Booking']['fname']." ".$booking_detail['Booking']['lname'],$body);  
		$body=str_replace('{EMAIL}',$booking_detail['Booking']['email'],$body);
		$body=str_replace('{PHONE}',$booking_detail['Booking']['phone'],$body);
		//$body=str_replace('{POST_CODE}',$booking_detail['Booking']['post_code'],$body);
		$body=str_replace('{ORDER_COMMENT}',(!empty($booking_detail['Booking']['order_message']))?$booking_detail['Booking']['order_message']:'There are no comments.',$body);
		$body=str_replace('{TOTAL}',number_format($booking_detail['Booking']['transaction_amount'],2),$body);
		$body=str_replace('{BOOKING_DETAIL}',$booking_content,$body); 
		
		$email = new CakeEmail();
		if(!empty($vendor_details['Vendor']['email'])) {
			$email->to($vendor_details['Vendor']['email'],$mail['Mail']['mail_from']);
		}else{
			$email->to($this->setting['site']['site_contact_email'],$mail['Mail']['mail_from']);
		}
		
		$email->subject($mail['Mail']['mail_subject']);
		$email->from($booking_detail['Booking']['email']);
		$email->emailFormat('html');
		$email->template('default');
		$email->viewVars(array('data'=>$body,'logo'=>$this->setting['site']['logo'],'url'=>$this->setting['site']['site_url']));
		$email->send();
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
}
?>
