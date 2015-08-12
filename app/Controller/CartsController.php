<?php
Class CartsController extends AppController{
	public $uses = array('Cart');
	public $components = array('MemberManager.MemberAuth','Email');
	public $helpers = array('VendorManager.Time');
	public $paginate = array();
	public $id = null;
	
	function index(){
	}
	function check_out(){
		// load model
		$check_guest_status=0;
		$this->loadModel('VendorManager.Service');
		$this->loadModel('VendorManager.ServiceImage');
		$this->loadModel('CantentManager.Page'); 
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/'),
            'name'=>'Home'
        );
        $this->breadcrumbs[] = array(
            'url'=>Router::url('/carts/check_out'),
            'name'=>'Check Out'
            );
        // load MemberAuth component
		App::uses('MemberAuthComponent', 'MemberManager.Controller/Component');
		array_push(self::$css_for_layout,'pages.css');

		$this->sessionKey = MemberAuthComponent::$sessionKey;
		$this->member_data = $this->Session->read($this->sessionKey);
		// member empty then redirect login page
		$redirect_login='';
		// assign email in checkout page
		$guest_email=$this->Session->read('Guest_email');
		if(!empty($guest_email)) { 
			$this->request->data['Cart']['email']=$guest_email;
			$check_guest_status=1;
		}
		if(empty($this->member_data['MemberAuth']['id'])) {
			//$redirect_login=parent::curPageURL();
		}else{
			$check_guest_status=1;
			// Assign cart details if member is login
			$this->request->data['Cart']['fname']=$this->member_data['MemberAuth']['first_name'];
			$this->request->data['Cart']['lname']=$this->member_data['MemberAuth']['last_name'];
			$this->request->data['Cart']['email']=$this->member_data['MemberAuth']['email_id'];
			$this->request->data['Cart']['phone']=$this->member_data['MemberAuth']['phone'];
		}
		// update guest email id 
		if(!empty($guest_email)){
				// convert to string
				$email='\''.$guest_email.'\'';
				$this->Cart->updateAll(array('Cart.guest_email'=>$email), array('Cart.session_id'=>$this->Session->id()));
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
        $criteria['conditions'] =array('Cart.session_id'=>$this->Session->id(),'Cart.status'=>1);
        $criteria['order'] =array('Cart.id DESC');
		$cart_details=$this->Cart->find('all', $criteria);
		foreach($cart_details as $key=>$cart_detail) {
			$cart_details[$key]['image']=$this->ServiceImage->getOneimageServiceImageByservice_id($cart_detail['Cart']['service_id']);
			$cart_slots=json_decode($cart_detail['Cart']['slots'],true);
			$cart_details[$key]['Cart']['slots']=$cart_slots['Slot'];
			// get no of days
			$diff = abs(strtotime($cart_detail['Cart']['end_date']) - strtotime($cart_detail['Cart']['start_date']));
				$years = floor($diff / (365*60*60*24));
				$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
				$no_of_booking_days =(floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24)))+1;
			$cart_details[$key]['Cart']['no_of_booking_days']=$no_of_booking_days;
		}
		// if cart is empty then guest pop is not dispay 
		if(empty($cart_details)){
			$check_guest_status=1;
		}

		$cart_page=$this->Page->find('first',array('conditions'=>array('Page.id'=>18),'fields'=>array('Page.*')));
		$this->title_for_layout = $cart_page['Page']['page_title'];
		$this->metakeyword = $cart_page['Page']['page_metakeyword'];
		$this->metadescription = $cart_page['Page']['page_metadescription'];
		$this->set('cart_page',$cart_page);
		$this->set('check_guest_status',$check_guest_status);
		$this->set('cart_details',$cart_details);
		$this->set('redirect_login',$redirect_login);
		$this->set('guest_email',$this->Session->read('Guest_email'));
	}

	function booking_success($booking_id=null){
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/'),
			'name'=>'Home'
		);
		$this->breadcrumbs[] = array(
		'url'=>Router::url('/carts/booking_success'),
		'name'=>'Booking Success'
		);
		array_push(self::$css_for_layout,'pages.css');

	}
	
	function booking_request(){ 
		//$this->autoRender = false;
		if(!empty($this->request->data)){
			$this->loadModel('Cart');
			App::uses('MemberAuthComponent', 'MemberManager.Controller/Component');
			$this->sessionKey = MemberAuthComponent::$sessionKey;
			$this->member_data = $this->Session->read($this->sessionKey);

			if ($this->Session->read($this->sessionKey)) {
				$query = "UPDATE carts SET status = 0, vendor_confirm = 3 WHERE session_id='".$this->Session->id()."' AND member_id='".$this->member_data['MemberAuth']['id']."'";
				$this->Cart->query($query);
			} else {
				$query = "UPDATE carts SET status = 0, vendor_confirm = 3 WHERE session_id='".$this->Session->id()."'";
				$this->Cart->query($query);
			}
			
			if ($this->Session->read($this->sessionKey)) {
				$cart_value = $this->Cart->find('all', array('conditions' => array('session_id' => $this->Session->id(), 'member_id' => $this->member_data['MemberAuth']['id'])));
			} else {
				$cart_value = $this->Cart->find('all', array('conditions' => array('session_id' => $this->Session->id()), 'order' => 'time_stamp DESC'));
			}
			//Send mail to vender for booking request
			// send to Admin mail
			//pr($this->request->data);die;
			$data = $this->request->data;
			$this->loadModel('MailManager.Mail');
			if(!empty($cart_value)){
				foreach($cart_value as $val){
					$mail=$this->Mail->read(null,27);
					$activity = ($val['Cart']['full_day_status'] == 0)?'Slote':'Full Day';
					$vas = ($val['Cart']['full_day_status'] == '[]')?'yes':'No';
					$body=str_replace('{VENDOR}',$val['Cart']['vendor_name'],$mail['Mail']['mail_body']);  
					$body=str_replace('{NAME}',$data['Cart']['fname']." ".$data['Cart']['lname'],$body);  
					$body=str_replace('{EMAIL}',$data['Cart']['email'],$body);
					$body=str_replace('{PHONE}',$data['Cart']['phone'],$body);
					$body=str_replace('{ORDER_COMMENT}',(!empty($data['Cart']['order_message']))?$data['Cart']['order_message']:'There are no comments.',$body);
					$body=str_replace('{SERVICE}',$val['Cart']['service_title'],$body);
					$body=str_replace('{ACTIVITY}',$activity,$body);
					$body=str_replace('{DATE}',date('Y-m-d',strtotime($val['Cart']['booking_date'])),$body);
					$body=str_replace('{STARTDATE}',date('Y-m-d',strtotime($val['Cart']['start_date'])),$body);
					$body=str_replace('{ENDDATE}',date('Y-m-d',strtotime($val['Cart']['end_date'])),$body);
					$body=str_replace('{PARTICIPANT}',$val['Cart']['no_participants'],$body);
					$body=str_replace('{VAS}',$vas,$body);
					$body=str_replace('{PRICE}',$val['Cart']['total_amount'],$body);
					
					$email = new CakeEmail();
$email->config('gmail');
					
					$email->to($val['Cart']['vendor_email'],$mail['Mail']['mail_from']);
					$email->subject($mail['Mail']['mail_subject']);
					$email->from($data['Cart']['email']);
					$email->emailFormat('html');
					$email->template('default');
					$email->viewVars(array('data'=>$body,'logo'=>$this->setting['site']['logo'],'url'=>$this->setting['site']['site_url']));
					// $email->send();
					// do not send!
					
				}
				
				$this->redirect(array('plugin'=>'vendor_manager','controller'=>'bookings','action'=>'accept_request',$cart_value[0]['Cart']['id']));
			}
			
		}
		$this->Session->setFlash('Sorry! Please fill data to proceed','default','','error');
		$this->redirect(array('plugin'=>false,'controller'=>'carts','action'=>'check_out'));
	}
	
	function add_order($id=null, $service_id=null){
		App::uses('MemberAuthComponent', 'MemberManager.Controller/Component');
		$this->sessionKey = MemberAuthComponent::$sessionKey;
		$this->member_data = $this->Session->read($this->sessionKey);
		$cart_data = $this->Cart->find('first', array('conditions' => array('Cart.id'=>$id, 'Cart.member_id'=>$this->member_data['MemberAuth']['id'], 'Cart.service_id'=> $service_id, 'Cart.status'=> 0, 'Cart.vendor_confirm'=> 1)));
		//pr($this->Session->id()); die;
		//pr($cart_data);exit;
		if(!empty($cart_data)){
			
			$this->loadModel('Booking');
			
			$booking_ref_no=$this->Booking->find('first',array('fields'=>array('ref_no'),'order'=>array('Booking.ref_no'=>'Desc')));
			$ref_no=(empty($booking_ref_no['Booking']['ref_no']))?1000:($booking_ref_no['Booking']['ref_no']+1);
			
			$data['Booking']['member_id']=$this->member_data['MemberAuth']['id'];
			$data['Booking']['session_id']=$this->Session->id();
			$data['Booking']['ref_no']=$ref_no;
			$data['Booking']['time_stamp']=date('Y-m-d H:i:s');
			$data['Booking']['ip_address']=$_SERVER['REMOTE_ADDR'];
			
			$data['Booking']['fname']=$this->member_data['MemberAuth']['first_name'];
			$data['Booking']['lname']=$this->member_data['MemberAuth']['last_name'];
			$data['Booking']['email']=$this->member_data['MemberAuth']['email_id'];
			$data['Booking']['phone']=$this->member_data['MemberAuth']['phone'];
			
			$this->Booking->create();
			$this->Booking->save($data);
			
			$cart['Cart']['id'] = $cart_data['Cart']['id'];
			$cart['Cart']['status'] = 1;
			$cart['Cart']['session_id'] = $this->Session->id();
			$this->Cart->create();
			$this->Cart->save($cart);
			
			if(!empty($this->Booking->id)){
				$this->redirect(array('plugin'=>false,'controller'=>'bookings','action'=>'payment_process',$this->Booking->id));
				//$this->redirect(array('plugin'=>false,'controller'=>'carts','action'=>'booking_success',$this->Booking->id));
			}
			else {
				$this->redirect(array('controller'=>'members','action'=>'dashboard','plugin'=>false));
			}
		}
		$this->redirect(array('controller'=>'members','action'=>'dashboard','plugin'=>false));
		
	}
	
	function validation($action=null){
        if($action=='cart') {
			$this->Cart->setValidation('cart');
		}else {
			$this->Cart->setValidation('check_out');
		}
	    $this->Cart->set($this->request->data);
		$result = array();
		if ($this->Cart->validates()) {
		  $result['error'] = 0;
		}else{
		  $result['error'] = 1;
		}
		if($this->request->is('ajax')) {
			$this->autoRender = false;
			$result['errors'] = $this->Cart->validationErrors;
			$errors = array();
			foreach($result['errors'] as $field => $data){
				$errors['Cart'.Inflector::camelize($field)] = array_pop($data);
			}
			$result['errors'] = $errors;
			echo json_encode($result);
			return;
		}
		return (int)($result['error'])?0:1;
	}
	
	function add_invite($service_id=null,$cart_id=null){
		$this->autoRender=false;
		$total_value_added_amount=$total_amount=0;
		$data['Cart']['id']=$cart_id;
		$value_added_details=array();
		$service_price=$this->Cart->GetCartServiceAmountById($cart_id);
		if(!empty($this->request->data['Cart']['value_added_services'])) {
			foreach($this->request->data['Cart']['value_added_services'] as $key=>$price){
				$value_added_list=explode("@_",$price);
				$value_added_details[$key]['service_id']=$value_added_list[1];
				$value_added_details[$key]['value_added_price']=$value_added_list[0];
				$value_added_details[$key]['value_added_name']=$value_added_list[2];
				$total_value_added_amount+=$value_added_list[0];
			}
		}
		$data['Cart']['value_added_price']=$total_value_added_amount;
		//for Want your friends to pay their individual share
		if($this->request->data['Cart']['invite_payment_status']==1){
			if($this->request->data['Cart']['no_participants']>0){
				$total_amount=($service_price['Cart']['total_amount']*$service_price['Cart']['no_participants'])+($total_value_added_amount*$service_price['Cart']['no_participants']);
			}
		}else{
			$total_amount=($service_price['Cart']['total_amount']+$total_value_added_amount);
		}
		$data['Cart']['invite_payment_status']=$this->request->data['Cart']['invite_payment_status'];
		$data['Cart']['total_amount']=$total_amount;
		$data['Cart']['additional_service']=1;
		if(!empty($this->request->data['Cart']['email'])){
			$data['Cart']['invite_friend_email']=json_encode($this->request->data['Cart']['email']);
		}
		$data['Cart']['value_added_services']=json_encode($value_added_details);
		$data['Cart']['time_stamp']=date('Y-m-d H:i:s');
		$data['Cart']['status']=1;
		$this->Cart->Create();
		$this->Cart->save($data,array('validate' => false));
		$this->redirect(array('plugin'=>false,'controller'=>'carts', 'action' => 'check_out'));
	}
	
	function delete_cart($cart_id=null){
		$this->Cart->DeleteCartByCartId($cart_id);
		$this->redirect(array('plugin'=>false,'controller'=>'carts', 'action' => 'check_out'));
	}
	
	function cancel_cart($service_id=null,$cart_id=null){
		$this->Cart->DeleteCartByCartId($cart_id);
		$this->redirect(array('plugin'=>false,'controller'=>'activity', 'action' => 'index',$service_id));
	}
	
	function admin_abandon_cart() {
		$conditions=null;
		$this->loadModel('MemberManager.Member');
		$this->paginate = array();
		$this->paginate['limit']=20;
		//$this->paginate['group']=array('Cart.session_id');
		$this->paginate['fields']=array('Cart.*');
		$this->paginate['order']=array('Cart.id'=>'DESC');
		$abandon_carts=$this->paginate("Cart",$conditions);
		$abandon_cart_details=array();
		foreach($abandon_carts as $abandon_cart)
		{	if(empty($abandon_cart['Cart']['guest_email'])){
				$abandon_cart['Cart']['guest_email']=$this->Member->GetMemberEmailByid($abandon_cart['Cart']['member_id']);
				$abandon_cart_details[]=$abandon_cart;
			}else{
				$abandon_cart_details[]=$abandon_cart;
			}
		}
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/home'),
			'name'=>'Home'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/abandon/index'),
			'name'=>'Manage Abandon'
		);
		$this->set('abandon_carts',$abandon_cart_details);
		if($this->request->is('ajax')){
                $this->layout = '';
                $this->Render('ajax_admin_abandon_cart');
        }
	}
	
	function guest_login(){
		if(!empty($this->request->data['GuestLogin']['email_id'])){
				$this->Session->write('Guest_email',$this->request->data['GuestLogin']['email_id']);
				$email='\''.$this->request->data['GuestLogin']['email_id'].'\'';
				$this->Cart->updateAll(array("Cart.guest_email"=>$email), array('Cart.session_id'=>$this->Session->id()));
		}
		if(@$this->request->data['GuestLogin']['GuestLogin']==1){
			
			$this->request->data['Member']=$this->request->data['GuestLogin'];
			unset($this->request->data['GuestLogin']);
			$this->userScope=array('Member.active' => '1');
			$this->Session->write('redirect_url',$this->referer());
			$this->MemberAuth->login();
		
		}else{
			$this->redirect($this->referer());
		}
	}
	
	// validation of guest check out
	function guest_validation(){
		$this->loadModel('GuestLogin');
		$this->GuestLogin->set($this->request->data);
		$result = array();
		if ($this->GuestLogin->validates()) {
		  $result['error'] = 0;
		}else{
		  $result['error'] = 1;
		}
		if($this->request->is('ajax')) {
			$this->autoRender = false;
			$result['errors'] = $this->GuestLogin->validationErrors;
			$errors = array();
			foreach($result['errors'] as $field => $data){
				$errors['GuestLogin'.Inflector::camelize($field)] = array_pop($data);
			}
			$result['errors'] = $errors;
			echo json_encode($result);
			return;
		  }
			return (int)($result['error'])?0:1;
	}
}
?>
