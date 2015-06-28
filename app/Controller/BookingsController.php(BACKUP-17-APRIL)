<?php 
Class BookingsController extends AppController{
	public $uses = array('Booking');
	public $helpers = array('Time');
	public $components = array('Email');
	public $paginate = array();
	public $id = null;
	
	function index(){
	}
	
	function admin_index($vendor_id=null,$search=null,$searchtext=null,$search_by_date=null,$searchbydate=null) {
		$this->loadModel('BookingSlot');
		$this->loadModel('VendorManager.Vendor');
		$conditions=null;
		$this->paginate = array();
		if($this->request->is('post')){
			$vendor_id=(!empty($this->request->data['Booking']['vendor_id']))?$this->request->data['Booking']['vendor_id']:'_blank';
			$search=(!empty($this->request->data['Booking']['search']))?$this->request->data['Booking']['search']:'_blank';
			$searchtext=(!empty($this->request->data['Booking']['searchtext']))?$this->request->data['Booking']['searchtext']:'_blank';
			$search_by_date=(!empty($this->request->data['Booking']['search_by_date']))?$this->request->data['Booking']['search_by_date']:'_blank';
			$searchbydate=(!empty($this->request->data['Booking']['search_by_date']))?$this->request->data['Booking']['searchbydate']:'_blank';
			$this->redirect(array('plugin'=>false,'controller'=>'bookings','action'=>'admin_index',$vendor_id,$search,$searchtext,$search_by_date,$searchbydate));
        }else{
			// conditions
			if(($vendor_id!=null && $vendor_id!='_blank')){
				
				$conditions['BookingOrder.vendor_id']=$vendor_id;
				//assign in variable
				$this->request->data['Booking']['vendor_id']=$vendor_id;
			}
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
			'group'=>'BookingOrder.ref_no',
		    'fields'=>array('Booking.*'),
			'limit'=>10,
			'order'=>array('Booking.ref_no'=>'DESC')
		);
		$booking_details=$this->paginate("Booking",$conditions);
		// get all vendor list
		$this->Vendor->getVirtualField('name');
		$this->set('vendorlist',$this->Vendor->vendorList());
		$this->set('booking_details',$booking_details);
		$this->set('url',Controller::referer());
		$this->set('vendor_id',$vendor_id);
		$this->set('search',$search);
		$this->set('searchtext',$searchtext);
		$this->set('search_by_date',$search_by_date);
		$this->set('searchbydate',$searchbydate);
		if($this->request->is('ajax')){
			$this->layout = '';
			$this->Render('ajax_admin_index');
        }
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/home'),
			'name'=>'Home'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/bookings/index'),
			'name'=>'Manage Booking'
		);
		if($this->request->is('ajax')){
			$this->layout = '';
			$this -> Render('ajax_admin_index');
		}
	}
	
	function payment_process($booking_id=null){
		ob_start();
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
			throw new NotFoundException('Could not find that booking id');
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
		$total_cart_price=0;
		foreach($cart_details as $cart_detail){
			$total_cart_price+=$cart_detail['Cart']['total_amount'];
			$cart_service_names[]=$cart_detail['Service']['service_title'];
		}
		// PAYPAL SEND CUSTOM VARIABLE
		$custom_variable="member_id=".$this->member_data['MemberAuth']['id'].'&booking_id='.$booking_id.'&session_id=' . $this->Session->id();
		// save data before payment
		self::_before_booking_data_save($custom_variable);
		$this->redirect(array('plugin'=>'payment_manager','controller'=>'payments','action'=>'index',$booking_id));
	}
	
	function payment_summary($ref_no=null){
		// load model
		$this->loadModel('Cart');
		$this->loadModel('VendorManager.ServiceImage');
		$this->loadModel('ServiceManager.ServiceType');
		$this->loadModel('BookingOrder');
		$service_row = '';
		$slot_row = '';
		$total_amount =0;
		$criteria = array();
		$criteria['fields']= array('BookingOrder.*');
		/*$criteria['joins'] = array(
            array(
                'table' => 'services',
                'alias' => 'Service',
                'type' => 'INNER',
                'conditions' => array('Service.id = BookingOrder.service_id')
            ) 
                
        );*/
	    $criteria['conditions'] =array('BookingOrder.ref_no'=>$ref_no);
        $criteria['order'] =array('BookingOrder.id DESC');
        $criteria['group'] =array('BookingOrder.id');
		$order_details=$this->BookingOrder->find('all', $criteria);
		$booking_detail=$this->Booking->getBookingDetailsByBooking_ref($ref_no);
		foreach($order_details as $key=>$order_detail) {
			$order_details[$key]['BookingOrder']['servicetype']=$this->ServiceType->getServiceTypeNameByServiceId($order_detail['BookingOrder']['service_id']);
		}	 
		// delete email id of guest
		$this->Session->delete('Guest_email');
		// set variable 
		$this->set('order_details',$order_details);
		$this->set('booking_detail',$booking_detail);
	}
	
	function invite_payment_summary($booking_order_id=null){
		if(empty($booking_order_id)){
			 $this->redirect('/');
		} 
		// load model
		$this->loadModel('VendorManager.ServiceImage');
		$this->loadModel('ServiceManager.ServiceType');
		$this->loadModel('BookingOrder');
		// delete session
		$service_row = '';
		$slot_row = '';
		$total_amount =0;
		$criteria = array();
		$criteria['fields']= array('BookingOrder.*');
		$criteria['conditions'] =array('BookingOrder.id'=>$booking_order_id);
        $criteria['order'] =array('BookingOrder.id DESC');
        $criteria['group'] =array('BookingOrder.service_id');
		$booking_order_detail=$this->BookingOrder->find('first', $criteria);
		$booking_detail=$this->Booking->getBookingDetailsByBooking_ref($booking_order_detail['BookingOrder']['ref_no']);
		$booking_order_detail['BookingOrder']['servicetype']=$this->ServiceType->getServiceTypeNameByServiceId($booking_order_detail['BookingOrder']['service_id']);
		$service_image=$this->ServiceImage->getOneimageServiceImageByservice_id($booking_order_detail['BookingOrder']['service_id']);
		$booking_order_detail['BookingOrder']['image']= $service_image;
		$this->set('booking_order_detail',$booking_order_detail);
		$this->set('booking_detail',$booking_detail);
	}
	
	private function check_paypal_refered_url($request){
		$paypal_url=($this->setting['paypal']['sandbox_mode']==1)?'https://www.sandbox.paypal.com':'https://www.paypal.com';
		if($paypal_url==$request){
			return true;
		}else{
			return false;
		}
	}
    /*
    function process_ipn(){
		// paypal notifiction valid ipn
		App::import('Vendor', 'paypal', array('file' => 'paypal' . DS . 'Paypal.php'));
		$siteurl=$this->setting['site']['site_url'];
		$myPaypal = new Paypal();
		if($this->setting['paypal']['sandbox_mode']==1){
			$myPaypal->enableTestMode();
		}
		if($myPaypal->validateIpn()){ 
			$this->loadModel('Cart');
			$this->loadModel('BookingOrder');
			$this->loadModel('BookingSlot');
			$this->loadModel('Booking');
			$this->loadModel('BookingParticipate');
			$this->loadModel('VendorManager.ServiceImage');
			$this->loadModel('ServiceManager.ServiceType');
			$custom_field = explode('&', $_REQUEST['custom']);
			$params = array();
			$booking_data = array();
			$service_row = '';
			$slot_row = '';
			foreach ($custom_field as $param) {
				$item = explode('=', $param);
				$custom_variable[$item[0]] = $item[1];
			}
			$criteria = array();
			$criteria['fields']= array('Cart.*','Service.service_title');
			$criteria['joins'] = array(
				array(
					'table' => 'services',
					'alias' => 'Service',
					'type' => 'INNER',
					'conditions' => array('Service.id = Cart.service_id')
				) 
					
			);
			$criteria['conditions'] =array('Cart.session_id'=>$custom_variable['session_id'],'Cart.status'=>1);
			$criteria['order'] =array('Cart.id ASC');
			$criteria['group'] =array('Cart.id');
			$cart_details=$this->Cart->find('all', $criteria);
			$row='';
			//update booking table
			 $booking_data['Booking']['id']=$custom_variable['booking_id'];
			 $booking_data['Booking']['member_id']=$custom_variable['member_id'];
			 $booking_data['Booking']['transaction_amount']=$_REQUEST['mc_gross'];
			 $booking_data['Booking']['status']=($_REQUEST['payment_status']=="Completed")?1:(($_REQUEST['payment_status']=="Pending")?2:0);
			 $booking_data['Booking']['transaction_id']=$_REQUEST['txn_id'];
			 $booking_data['Booking']['booking_date']=date('Y-m-d H:i:s');
			 $booking_data['Booking']['time_stamp']=date('Y-m-d H:i:s');
			//$this->Booking->create();
			$this->Booking->save($booking_data,array('validate' => false));
			$booking_detail=$this->Booking->getBookingDetailsByBooking_id($custom_variable['booking_id']);
			$this->BookingOrder->updateAll(
				array('BookingOrder.status' => $booking_detail['Booking']['status']),
				array('BookingOrder.ref_no =' => $booking_detail['Booking']['ref_no'])
			);
			$this->BookingParticipate->updateAll(
				array('BookingParticipate.status' => $booking_detail['Booking']['status']),
				array('BookingParticipate.ref_no =' => $booking_detail['Booking']['ref_no'])
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
					$body=str_replace('{PHONE}',$booking_detail['Booking']['phone'],$body);//$body=str_replace('{POST_CODE}',$booking_detail['Booking']['post_code'],$body);
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
					$body=str_replace('{PHONE}',$booking_detail['Booking']['phone'],$//$body=str_replace('{POST_CODE}',$booking_detail['Booking']['post_code'],$body);
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
					$this->Cart->deleteAll(array('Cart.session_id'=>$custom_variable['session_id']));
					// send to vendor mail
					self::vendor_mails($booking_detail['Booking']['ref_no']);
				}
				else{
					self::payment_failed_mail($booking_detail);
				}
			}
		}
    }*/
  
    function booking_thanks(){
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
				$booking_participates['BookingParticipate']['service_title']=$cart_detail['Service']['service_title'];
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
	/*
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
	*/
	// admin booking details
	function admin_booking_details($ref_no=null){
		if(empty($ref_no)){
			 $this->redirect('/');
		} 
		$criteria = array();
		$this->loadModel('BookingOrder');
		$this->loadModel('LocationManager.City');
		
		$criteria['conditions']=array('BookingOrder.ref_no'=>$ref_no);
		$criteria['group']=array('BookingOrder.id');
		$criteria['fields']=array('BookingOrder.*');
		$criteria['order']=array('BookingOrder.id ASC');
		
		$order_details=$this->BookingOrder->find('all',$criteria);
		$customer_detail=$this->Booking->find('first',array('conditions'=>array('Booking.ref_no'=>$ref_no)));
		
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/home'),
			'name'=>'Home'
		);
		
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/bookings/index'),
			'name'=>'Manage Booking Details'
		);
		foreach($order_details as $key=>$order_detail){
			$order_details[$key]['BookingOrder']['location_name']=(!empty($order_detail['BookingOrder']['location_id']))?$this->City->getLocationListCityID($order_detail['BookingOrder']['location_id']): "Location not available";
		}
		
		$this->set('customer_detail',$customer_detail);
		$this->set('order_details',$order_details);
	}
	
	function admin_booking_vas_details($booking_order_id=null){
		$this->layout='';
		$this->loadModel('BookingOrder');
		$value_added_services=$this->BookingOrder->find('first',array('conditions'=>array('BookingOrder.id'=>$booking_order_id),'fields'=>array('BookingOrder.value_added_services')));
		$this->set('value_added_services',json_decode($value_added_services['BookingOrder']['value_added_services'],true));
	}
	
	function admin_booking_member_invite_details($booking_order_id=null){
		$this->layout='';
		$this->loadModel('BookingParticipate');
		$invites_friend_details=$this->BookingParticipate->find('all',array('conditions'=>array('BookingParticipate.booking_order_id'=>$booking_order_id)));
		$this->set('invites_friend_details',$invites_friend_details);
	}
	
	function admin_booking_slot_details($booking_order_id=null){
		$this->layout='';
		$this->loadModel('BookingSlot');
		$booking_slots=$this->BookingSlot->find('all',array('conditions'=>array('BookingSlot.booking_order_id'=>$booking_order_id)));
		$this->set('booking_slots',$booking_slots);
	}
	
	function admin_booking_vendor_details($booking_order_id=null){
		$this->layout='';
		$this->loadModel('BookingOrder');
		$vendor_details=$this->BookingOrder->find('first',array('conditions'=>array('BookingOrder.id'=>$booking_order_id),'fields'=>array('BookingOrder.*')));
		$this->set('vendor_details',$vendor_details);
	}
	/*
	function invite_payment($booking_order_id=null){
		//$this->autoRender=false;
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
			$this->redirect('/');
		}
		$criteria=array();
		$criteria['joins']=array(
							array(
								'table'=>'booking_orders',
								'alias' => 'BookingOrder',
								'type' => 'INNER',
								//'foreignKey' => false,
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
		$booking_data['Booking']['status']=4;
		$this->Booking->create();
		$this->Booking->save($booking_data);
		if(empty($this->Booking->id)){
			$this->redirect(array('plugin'=>'member_manager','controller'=>'members','action'=>'dashboard'));
		}
		// create custom variable for paypal 
		$custom_variable="member_id=".$this->member_data['MemberAuth']['id'].'&booking_id='.$this->Booking->id.'&booking_order_id='.$this->request->data['booking_order_id'].'&session_id=' . $this->Session->id()."&amount=".$invite_details['BookingParticipate']['amount']."&ref_no=".$invite_details['BookingOrder']['ref_no']."&b_p_id=".$invite_details['BookingParticipate']['id'];// b_p_id bookingparticipate id
		App::import('Vendor', 'paypal', array('file' => 'paypal' . DS . 'Paypal.php'));
		$siteurl=$this->setting['site']['site_url'];
		$myPaypal = new Paypal();
		if($this->setting['paypal']['sandbox_mode']==1){
			$myPaypal->enableTestMode();
		}
		$paypal_email = trim($this->setting['paypal']['business_email_paypal']);
		$myPaypal->addField('business', $paypal_email);
		$myPaypal->addField('lc', 'US');
		$myPaypal->addField('cmd', '_xclick'); 
		$myPaypal->addField('item_name', $invite_details['BookingOrder']['service_title']);
		$myPaypal->addField('currency_code', 'USD');
		$myPaypal->addField('image_url', $siteurl.'/img/logo.png');
		$myPaypal->addField('notify_url', $siteurl . '/bookings/invite_process_ipn/');
		$myPaypal->addField('cancel_return', $siteurl . '/bookings/payment_cancel/'.$booking_order_id);
		$myPaypal->addField('return', $siteurl . '/bookings/invite_payment_summary/'.$booking_order_id);
		$myPaypal->addField('amount', number_format($invite_details['BookingParticipate']['amount'], 2));
		$myPaypal->addField('no_shipping', number_format('0'));
		$myPaypal->addField('custom', $custom_variable);
		if(empty($invite_details['BookingParticipate']['amount'])) {
			$this->redirect('/');
		}
		$html='';
		$html.= "<html>\n";
		$html.= "<head><title>Processing Payment...</title></head>\n";
		$html.= "<body onLoad=\"document.forms['gateway_form'].submit();\">\n";
		$html.= "<div class=\"wrapper\"><div class=\"span_12_of_12 margin-top relative\"><p align=\"center\">Please wait, your order is being processed";
		$html.="</p><div class=\"loader-class\"></div>";
		$html.= "</div></div>\n";
		$html.= "<form method=\"POST\" name=\"gateway_form\" ";
		$html.= "action=\"" . $myPaypal->gatewayUrl . "\">\n";
		foreach ($myPaypal->fields as $name => $value){
			 $html.= "<input type=\"hidden\" name=\"$name\" value=\"$value\"/>\n";
		}
		$html.= "</form>\n";
		$html.= "</body></html>\n";
		$this->set('html',$html);
	}
	*/
	/*function invite_process_ipn($booking_order_id=null){
		//echo "<pre>";print_r($_SERVER['HTTP_ORIGIN']);// https://www.sandbox.paypal.com
		// paypal notifiction valid ipn
		App::import('Vendor', 'paypal', array('file' => 'paypal' . DS . 'Paypal.php'));
		$siteurl=$this->setting['site']['site_url'];
		$myPaypal = new Paypal();
		if($this->setting['paypal']['sandbox_mode']==1){
			$myPaypal->enableTestMode();
		}
		if($myPaypal->validateIpn()){
			$this->loadModel('Cart');
			$this->loadModel('BookingOrder');
			$this->loadModel('BookingSlot');		
			$this->loadModel('BookingParticipate');

			$this->loadModel('VendorManager.ServiceImage');
			$custom_field = explode('&', $_REQUEST['custom']);
			$params = array();
			$booking_data = array();
			// custom vield 
			foreach ($custom_field as $param) {
				$item = explode('=', $param);
				$custom_variable[$item[0]] = $item[1];
			}
			$booking_data['Booking']['id']=$custom_variable['booking_id'];
			$booking_data['Booking']['transaction_amount']=$_REQUEST['mc_gross'];
			$booking_data['Booking']['status']=($_REQUEST['payment_status']=="Completed")?1:(($_REQUEST['payment_status']=="Pending")?2:0);
			$booking_data['Booking']['transaction_id']=$_REQUEST['txn_id'];
			$booking_data['Booking']['time_stamp']=date('Y-m-d H:i:s');
			$this->Booking->create();
			$this->Booking->save($booking_data,array('validate' => false));
			$data=array();
			$data['BookingParticipate']['id']=$custom_variable['b_p_id'];
			$data['BookingParticipate']['booking_member_id']=$custom_variable['member_id'];
			$data['BookingParticipate']['status']=($_REQUEST['payment_status']=="Completed")?1:(($_REQUEST['payment_status']=="Pending")?2:0);
			// upldating booking participate table
			$this->BookingParticipate->create();
			$this->BookingParticipate->save($data,array('validate' => false));
			// sending mail if payment status completed
			$booking_detail=$this->Booking->getBookingDetailsByBooking_id($custom_variable['booking_id']);
			$booking_order_detail=$this->BookingOrder->read(null,$custom_variable['booking_order_id']);
			// if booking is completed then mail
			if($data['BookingParticipate']['status']==1){
				self::invite_payment_mail($booking_detail,$booking_order_detail,$custom_variable['booking_order_id']);
			}else{
				self::payment_failed_mail($booking_detail);
			}
		}
		$this->autoRender=false;
	}
	*/
	/*
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
		$vendor_details=$this->Vendor->vendorNameEmailById($booking_order_detail['BookingOrder']['vendor_id']);
		$mail=$this->Mail->read(null,26);
		$body=str_replace('{ORDERNO}',$booking_detail['Booking']['ref_no'],$mail['Mail']['mail_body']);  
		$body=str_replace('{VENDER_NAME}',$vendor_details['Vendor']['fname'],$body);  
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
	}
	*//*
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
	*/
	private function _before_booking_data_save($booking_custom_data){
		if(!empty($booking_custom_data)){
			$this->loadModel('Cart');
			$this->loadModel('BookingOrder');
			$this->loadModel('BookingSlot');
			$this->loadModel('Booking');
			$this->loadModel('VendorManager.ServiceImage');
			$custom_field = explode('&', $booking_custom_data);
			$params = array();
			$booking_data = array();
			$service_row = '';
			$slot_row = '';
			foreach ($custom_field as $param) {
				$item = explode('=', $param);
				$custom_variable[$item[0]] = $item[1];
			}
			$criteria = array();
			$criteria['fields']= array('Cart.*','Service.service_title');
			$criteria['joins'] = array(
				array(
					'table' => 'services',
					'alias' => 'Service',
					'type' => 'INNER',
					'conditions' => array('Service.id = Cart.service_id')
				) 
			);
			$criteria['conditions'] =array('Cart.session_id'=>$custom_variable['session_id'],'Cart.status'=>1);
			$criteria['order'] =array('Cart.id DESC');
			$cart_details=$this->Cart->find('all', $criteria);
			$row='';
			$booking_detail=$this->Booking->getBookingDetailsByBooking_id($custom_variable['booking_id']);
			$service_slot_details='';
			$total_cart_price=0;
			$slots='';
			// update booking status processing 
			$booking_data['Booking']['id']=$custom_variable['booking_id'];
			$booking_data['Booking']['time_stamp']=date('Y-m-d H:i:s');
			$booking_data['Booking']['browser_name']=$_SERVER['HTTP_USER_AGENT'];
			$booking_data['Booking']['status']=4; // status 4 for processing 	
			$this->Booking->create();
			$this->Booking->save($booking_data,array('validate' => false));
			// check payment status
			if(!empty($cart_details)){
				foreach($cart_details as $cart_detail) {
					$total_cart_price=0; 
					unset($cart_detail['Cart']['id']);
					$newData['BookingOrder']=$cart_detail['Cart'];
					$newData['BookingOrder']['ref_no']=$booking_detail['Booking']['ref_no'];
					$newData['BookingOrder']['service_title']=$cart_detail['Service']['service_title'];
					$newData['BookingOrder']['status']=4;
					$this->BookingOrder->create();
					$this->BookingOrder->save($newData,array('validate' => false));
					$slots=json_decode($newData['BookingOrder']['slots'],true);
					$this->booking_order_id=$this->BookingOrder->id;
					//save booking slots
					self::before_saving_booking_slot($slots,$booking_detail['Booking']['ref_no'],$newData['BookingOrder']['service_id'],$newData['BookingOrder']['no_participants']);
					// save invite save data
					$total_cart_price=$cart_detail['Cart']['total_amount'];
					self::before_sent_invite_save($cart_detail,$total_cart_price,$booking_detail);
				}
			}
		}
		return true;
	}

	private function before_saving_booking_slot($slots=null,$ref_no=null,$service_id=null,$no_participants=null){
		if(!empty($slots['Slot'])){
			foreach($slots['Slot'] as $key=>$slot) {
				$data_booking_slot['BookingSlot']['booking_order_id']=$this->booking_order_id;
				$data_booking_slot['BookingSlot']['slot_id']=$slot['slot_id'];
				$data_booking_slot['BookingSlot']['service_id']=$service_id;
				$data_booking_slot['BookingSlot']['ref_no']=$ref_no;
				$data_booking_slot['BookingSlot']['no_participants']=$no_participants;
				$data_booking_slot['BookingSlot']['start_time']= DATE("Y-m-d H:i:s", STRTOTIME(date('Y-m-d',$slot['slot_date'])." ".$slot['start_time']));
				$data_booking_slot['BookingSlot']['end_time']= DATE("Y-m-d H:i:s", STRTOTIME(date('Y-m-d',$slot['slot_date'])." ".$slot['end_time'])+1);
				$this->BookingSlot->create();
				$this->BookingSlot->save($data_booking_slot,array('validate' => false));
			}
		}
	}

	private function before_sent_invite_save($cart_detail=null,$total_cart_price=null,$booking_detail=null) {
		$this->loadModel('BookingParticipate');
		$booking_participates=array();
		$booking_participates_mails=array();
		$emails=json_decode($cart_detail['Cart']['invite_friend_email'],true);
		if(!empty($emails)) {
			foreach($emails as $key=>$email) {
				$booking_participates['BookingParticipate']['id']='';	
				$booking_participates['BookingParticipate']['booking_order_id']=$this->booking_order_id;;	
				$booking_participates['BookingParticipate']['member_id']=$booking_detail['Booking']['member_id'];	
				$booking_participates['BookingParticipate']['ref_no']=$booking_detail['Booking']['ref_no'];	
				$booking_participates['BookingParticipate']['invite_email']=$booking_detail['Booking']['email'];	
				 $booking_participates['BookingParticipate']['email']=$email;	
				 $booking_participates['BookingParticipate']['amount']=$total_cart_price;
				 // status set if paymet pay by invitor
				 $booking_participates['BookingParticipate']['status']=($cart_detail['Cart']['invite_payment_status']==1)?5:4;
				 // save invite friends	
				 if(!empty($booking_participates)){
					$this->BookingParticipate->create();
					$this->BookingParticipate->save($booking_participates,array('validate' => false));
				}
				 $booking_participates['BookingParticipate']['id']=$this->BookingParticipate->id;
				 $booking_participates['BookingParticipate']['service_title']=$cart_detail['Service']['service_title'];
				 $booking_participates['BookingParticipate']['start_end_date']=date(Configure::read('Calender_format_php'),strtotime($cart_detail['Cart']['start_date']))." To ".date(Configure::read('Calender_format_php'),strtotime($cart_detail['Cart']['end_date']));
				 $booking_participates_mails[]=$booking_participates;
			}
		}
	}
	/*
	function payment_cancel(){
		$this->Booking->updateAll(array('Booking.status' => 0),array('Booking.session_id'=>$this->Session->id(),'Booking.status' =>4));
		//for cancel page
		$this->redirect(array('plugin'=>'content_manager','controller'=>'pages','action'=>'view',17));
	}*/
	/*
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
	} */

	/*private function getBookedParticipantEmail($participant_emails){
		$booked_participant_emails='';
		if(!empty($participant_emails)){
			$emails=json_decode($participant_emails,true);
			foreach($emails as $email){
				 $booked_participant_emails.=$email.'<br/>';
			}
		}
		return $booked_participant_emails;
	}*/
	/*
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
	}	*/
	/*
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
	}*/
}
?>
