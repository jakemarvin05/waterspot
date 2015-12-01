<?php
class MembersController extends MemberManagerAppController{
	public $name = 'Members';
	public $helpers = array('Html', 'Form');
	public $components = array('Email','VendorManager.VendorAuth','Cookie');
	public $uses = array('MemberManager.Member'); 
	public $paginate = array();
	
	function registration($email=null) {
        array_push(self::$css_for_layout,'vendor/registration.css');
		$member_id = $this->MemberAuth->id();
		if(isset($_POST['facebook_login'])) {
			$criteria['conditions'] = array('Member.fb_id'=>$this->request->data['Member']['fb_id']);
			$member_details =  $this->Member->find('first', $criteria);
			if ($member_details){
				$this->MemberAuth->login_facebook();
			}
			return;
		}
		if($member_id) {
			$this->redirect(array('controller'=>'members','action'=>'dashboard','plugin'=>'member_manager'));
		} 
		if(!empty($_REQUEST['redirect_url'])) {
			$this->MemberAuth->loginRedirect =$_REQUEST['redirect_url'];
		}
		
		if(!empty($this->request->data) && $this->validation()) {
			if($this->request->data['Member']['form-name']=='LoginForm'){
				self::login();
			}
			if($this->request->data['Member']['form-name']=='RegistrationForm'){
				$realpassword = $this->request->data['Member']['password'];
				$this->request->data['Member']['password'] = Security::hash(Configure::read('Security.salt').$this->request->data['Member']['password']);
				$this->request->data['Member']['created_at']=date('Y-m-d H:i:s');
				$this->request->data['Member']['active']='1';
				$this->Member->create();
				if($this->Member->save($this->request->data,array('validate'=>false))){
					$this->__mail_send(11,$this->request->data,$realpassword);

					// subscribe the new user
					$apikey = '08c19e41483c616d5fd3ec14df89e2bc-us11';
					$list_id = 0;
					$list_name = 'Waterspot Users List';
					$email = $this->request->data['Member']['email_id'];

					$ch = curl_init('https://us11.api.mailchimp.com/2.0/lists/list.json');
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
					curl_setopt($ch, CURLOPT_POSTFIELDS, '{"apikey": "'.$apikey.'"}');
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array(
						'Content-Type: application/json',
						'Content-Length: ' . strlen('{"apikey": "'.$apikey.'"}'))
					);

					$results = json_decode(curl_exec($ch));
					
					foreach ($results->data as $result) {
						if ($list_name == $result->name) {
							$list_id = $result->id;
							break;
						}
					}
					if ($list_id) {
						$data = '{
						    "apikey": "'.$apikey.'",
						    "id": "'.$list_id.'",
						    "batch": [
						        {
						            "email": {
						                "email": "'.$email.'"
						            }
						        }
						    ]
						}';
						$ch = curl_init('https://us11.api.mailchimp.com/2.0/lists/batch-subscribe.json');
						curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
						curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch, CURLOPT_HTTPHEADER, array(
							'Content-Type: application/json',
							'Content-Length: ' . strlen($data))
						);

						$results = json_decode(curl_exec($ch));
					}

					$this->request->data['Member']['password'] = $realpassword;//To Login member with Un-encrypted password
					$this->MemberAuth->login();
				}else {
					$this->request->data['Member']['password'] = $realpassword;
				}
			}
		}
		$this->request->data['Member']['email_id']=$this->Cookie->read('Member.email_id');
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/'),
			'name'=>'Home'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url(array('controller'=>'members','action'=>'registration')),
			'name'=>'Member Registration'
		);
		// if invite friend auto email id insert in form
		if(!empty($email)){
			$this->Cookie->write('Member.email_id', urldecode($email),false, 60);
			$this->request->data['Member']['email_id']=urldecode($email);
		}
	}

	public function log_in() {
        array_push(self::$css_for_layout,'vendor/registration.css');
		$member_id = $this->MemberAuth->id();
		if(isset($_POST['facebook_login'])) {
			$criteria['conditions'] = array('Member.fb_id'=>$this->request->data['Member']['fb_id']);
			$member_details =  $this->Member->find('first', $criteria);
			if ($member_details){
				$this->MemberAuth->login_facebook();
			}
			return;
		}
		if($member_id) {
			$this->redirect(array('controller'=>'members','action'=>'dashboard','plugin'=>'member_manager'));
		} 
	}


	
	private function login($email=null) {
		$member_id = $this->MemberAuth->id();
		if(!empty($_REQUEST['redirect_url'])) {
			$this->MemberAuth->loginRedirect=$_REQUEST['redirect_url'];
		}
		if($member_id) {
			$this->redirect($this->referer());
		}
		if(!empty($this->request->data)) {
			$this->MemberAuth->login();
			unset($this->request->data['Member']['password']);
		}
	}
	
	function logout() {
		$this->loadModel('Cart');
		$this->autoRender=false;
		// delete all cart when logout
		$ses_id=$this->Session->id();
		$this->Cart->deleteAll(array('Cart.session_id'=>$ses_id, 'Cart.vendor_confirm'=>0));
		$this->MemberAuth->logout(array('plugin'=>false,'controller'=>'pages','action'=>'home'));
	}
	
	function resetpassword() {
		if(!empty($this->request->data)) {
			$member = $this->Member->find('first',array('conditions'=>array('Member.email_id'=>$this->request->data['Member']['email_id'])));
			if(!empty($member['Member']['id'])) {
				unset($member['Member']['password']);
				$member['Member']['passwordurl'] = Security::hash(Configure::read('Security.salt').$this->RandomString());				
				$this->__resetpassword_mail(12,$member);
				$this->Member->create();
				$this->Member->save($member,array('validate'=>false));
				$this->Session->setFlash('Mail with password reset link will be sent to '.$member['Member']['email_id'].'. Please follow the instructions to reset your password');
				$this->redirect(array('controller'=>'members','action'=>'resetpassword'));
			}
			else {
				$this->Session->setFlash('The email address you entered is not registered with us. Please try again using a different email address.','default','','error');
			}
		}
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/'),
			'name'=>'Home'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/member_manager/members/resetpassword'),
			'name'=>'Reset Password '
		);
	}
	
	function RandomString() {
		$characters = '$&@!0123456789abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randstring = '';
		for ($i = 0; $i < 15; $i++) {
			$arr1 = str_split($characters);
			$randstring .= $arr1[rand(0, $i)];
		}
		return $randstring;
	}
	
	private function __resetpassword_mail($mail_id=null,$member=null) {
		$this->loadModel('MailManager.Mail');
		$mail=$this->Mail->read(null,$mail_id);
		$heading=$mail['Mail']['heading'];
		$linkmerge=$this->setting['site']['site_url'].'/member_manager/members/passwordurl/'.$member['Member']['passwordurl'];
		$body=str_replace('{NAME}',$member['Member']['first_name'],$mail['Mail']['mail_body']);
		$body=str_replace('{url}',$linkmerge,$body);
		$body=str_replace('{EMAIL}',$member['Member']['email_id'],$body);
		$email = new CakeEmail();

		$email->to($member['Member']['email_id']);
		$email->subject($mail['Mail']['mail_subject']);
		$email->from($this->setting['site']['site_contact_email']);
		$email->emailFormat('html');
		$email->template('default');
		$email->viewVars(array('data'=>$body,'logo'=>$this->setting['site']['logo'],'url'=>$this->setting['site']['site_url']));
		$email->send();
	}
	
	function passwordurl($str=null) {
		$member_check = $this->Member->find('first',array('conditions'=>array('Member.passwordurl'=>$str)));
	 	if(!empty($member_check)) {
			if(!empty($this->request->data) && $this->validation()) {
				if($this->request->data['Member']['password']==$this->request->data['Member']['password2']) {
					$this->request->data['Member']['id']=$member_check['Member']['id'];
					$this->request->data['Member']['passwordurl']='';
					$this->request->data['Member']['password']=Security::hash(Configure::read('Security.salt').$this->request->data['Member']['password']);
					// Save here member update details 
					$this->Member->create();
					$this->Member->save($this->request->data,array('validate'=>false));
					$this->Session->setFlash('Password has been changed succesfully');
					$this->redirect(array('controller'=>'members','action'=>'passwordurl'));
				}else {
					$this->Session->setFlash('New password and confirm password does not match., try again','default','msg','error');
				}
			}
			$this->set('str',$str);
		}
		else {
			$this->Session->setFlash('Invalid link or password, try again','default','msg','error');
			$this->redirect(array('controller'=>'members','action'=>'registration'));
		}
	}
	
	function changepassword(){
            array_push(self::$css_for_layout,'member/member-panel.css');
		$id = $this->MemberAuth->id;
		if(!empty($this->request->data) && $this->validation()){
			$data['Member']['password'] = Security::hash(Configure::read('Security.salt').$this->request->data['Member']['password']);
			$this->Member->id = $id;
			$this->Member->saveField('password',$data['Member']['password']);
			$this->Session->setFlash('Your password changed successfully.');
			$this->redirect(array('controller'=>'members','action'=>'changepassword'));
		}
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/'),
			'name'=>'Home'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/members/dashboard/'),
			'name'=>'Dashboard'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/member_manager/members/changepassword'),
			'name'=>'Change Password '
		);
	}
	
	function change_email(){
            array_push(self::$css_for_layout,'member/member-panel.css');
		$id = $this->MemberAuth->id;
		if(!empty($this->request->data) && $this->validation()){
			$this->Member->id = $id;
			$this->Member->saveField('email_id',$this->request->data['Member']['email_id']);
			$this->Session->setFlash('Your email id has been changed successfully.');
			$this->redirect(array('controller'=>'members','action'=>'change_email'));
		}
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/'),
			'name'=>'Home'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/members/dashboard/'),
			'name'=>'Dashboard'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/member_manager/members/change_email'),
			'name'=>'Change Email'
		);
	}
	
	function edit_profile() {
        
        array_push(self::$css_for_layout,'member/member-panel.css');
		$id = $this->MemberAuth->id;
		if(!empty($this->request->data) && $this->validation()){
			$this->request->data['Member']['id'] = $id;
			$this->request->data['Member']['updated_at']=date('Y-m-d H:i:s');
			unset($this->request->data['Member']['email_id']);
			$this->Member->create();
			if($this->Member->save($this->request->data)) {
				$members = $this->Member->read(null,$id);
				$this->MemberAuth->updateMemberSession($members['Member']);	
				$this->Session->setFlash(__('Member has been updated successfully'));
				$this->redirect(array('controller'=>'members','action'=>'edit_profile'));
			}else {
				unset($this->request->data['Member']['id']);
			}
		}else{
			if($id!=null){
				$this->request->data = $this->Member->read(null,$id);
				unset($this->request->data['Member']['id']);
			}else{
				$this->request->data = array();
			}
		}
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/'),
			'name'=>'Home'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/members/dashboard/'),
			'name'=>'Dashboard'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/member_manager/members/edit_profile'),
			'name'=>'Edit Profile'
		);
	}
	
	function admin_index($search=null) {
		$this->paginate = array();
		$condition = null;
		$this->paginate['limit']=20;
		if($this->request->is('post')){
			$this->redirect(array('plugin'=>'member_manager','controller'=>'members','action'=>'index' ,$this->request->data['search']));
		}
		$this->paginate['order']=array('Member.reorder'=>'ASC','Member.id'=>'DESC');		
		
		if($search!=null){
			$search = urldecode($search);
			$condition['OR']['Member.first_name like'] = $search.'%';
			$condition['OR']['Member.last_name like'] = $search.'%';
			$condition['OR']['Member.email_id like'] = $search.'%';
		}
		$members=$this->paginate("Member", $condition);	
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/home'),
			'name'=>'Home'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/member_manager/members'),
			'name'=>'Manage Member'
		);
		$this->set('members', $members);
		$this->set('search',$search);
		$this->set('url','/'.$this->params->url);
		if($this->request->is('ajax')){
			$this->layout = '';
			$this -> Render('ajax_admin_index');
		}
	}

	function admin_add($id=null){
		$this->breadcrumbs[] = array(
		'url'=>Router::url('/admin/home'),
		'name'=>'Home');
		$this->breadcrumbs[] = array(
				'url'=>Router::url('/admin/member_manager/members'),
				'name'=>'Manage Member'
		);
		$this->breadcrumbs[] = array(
				'url'=>Router::url('/admin/member_manager/members/add'),
				'name'=>($id==null)?'Add Member':'Update Member'
		);
		if(!empty($this->request->data) && $this->validation()){
			if(empty($id)){
				$this->request->data['Member']['created_at']=date('Y-m-d H:i:s');
				$this->request->data['Member']['active'] =1;
				$memberPass = trim($this->RandomString());
				$this->request->data['Member']['password'] = Security::hash(Configure::read('Security.salt').$memberPass);
			}else{
				$this->request->data['Member']['updated_at']=date('Y-m-d H:i:s');
			}
			$this->Member->create();
			$this->Member->save($this->request->data);
			if(!empty($this->Member->id) && (empty($id))){
				$this->__mail_send(11,$this->request->data,$memberPass);	
			}
			if ($this->request->data['Member']['id']) {
				$this->Session->setFlash(__('Member has been updated successfully'));
			} 
			else {
				$this->Session->setFlash(__('Member has been added successfully'));
			}
			$this->redirect($this->request->data['Member']['redirect']);
			}
			else{
				if($id!=null){
					$this->request->data = $this->Member->read(null,$id);
				}else{
					$this->request->data = array();
				}
			} 
		$this->set('url',Controller::referer());
	}
	
	function admin_delete($id=null){
		$this->autoRender = false;
	 	$data=$this->request->data['Member']['id'];
		$action = $this->request->data['Member']['action'];
		$ans="0";
		foreach($data as $value){
			if($value!='0'){
				if($action=='Activate'){
					$member['Member']['id'] = $value;
					$member['Member']['active']=1;
					$this->Member->create();
					$this->Member->save($member);
					$ans="1";
				}
				if($action=='Deactivate'){
					$member['Member']['id'] = $value;
					$member['Member']['active']=0;
					$this->Member->create();
					$this->Member->save($member);
					$ans="1";
				}
				if($action=='Delete'){
					$this->Member->delete($value);
					$ans="2";
				}
			}
		}
		if($ans=="1"){
			$this->Session->setFlash(__('Member has been '.$this->data['Member']['action'].'d successfully', true));
		}
		else if($ans=="2"){
			$this->Session->setFlash(__('Member has been '.$this->data['Member']['action'].'d successfully', true));
		}else{
			$this->Session->setFlash(__('Please Select any Member', true),'default','','error');
		}
		$this->redirect($this->request->data['Member']['redirect']);
	}
	
	function admin_view($id = null) {
		$this->layout = '';
		$criteria = array();
		$criteria['conditions'] = array('Member.id'=>$id);
		$member_details =  $this->Member->find('first', $criteria);
		$this->set('member', $member_details);
	}
  
	function ajax_sort(){
		$this->autoRender = false;
		foreach($_POST['sort'] as $order => $id){
			$member= array();
			$member['Member']['id'] = $id;
			$member['Member']['reorder'] = $order;
			$this->Member->create();
			$this->Member->save($member);
		}
	}
	
	function validation($action=null) {
		if (!isset($_POST['facebook_login'])) {
			if($this->request->data['Member']['form-name']=='LoginForm'){
				$this->Member->setValidation('Login');
			}else if($this->request->data['Member']['form-name']=='change_email'){
				$this->Member->setValidation('change_email');
			}else if($this->request->data['Member']['form-name']=='RegistrationForm'){
				//$this->request->data['Member']['id'] = $this->MemberAuth->id;
				$this->Member->setValidation('Register');
			}
			else if($this->request->data['Member']['form-name']=='EditProfileForm'){
				//$this->request->data['Member']['id'] = $this->MemberAuth->id;
				unset($this->request->data['Member']['email_id']);
				$this->Member->setValidation('Register');
			}
			else if($this->request->data['Member']['form-name']=='Admin-member-registration'){			
				$this->Member->setValidation('Register');
			}
			else if($this->request->data['Member']['form-name']=='Change-Password'){
				$this->Member->setValidation('Changepassword');
			}
			else if($this->request->data['Member']['form-name']=='ForgotForm'){
				$this->Member->setValidation('Forgot');
			}
			else if($this->request->data['Member']['form-name']=='PasswordUrlForm'){	
				$this->Member->setValidation('PasswordUrl');
			} 
			$this->Member->set($this->request->data);
			$result = array();
			if ($this->Member->validates()) {
				$result['error'] = 0;
			}else{
				$result['error'] = 1;
			}
		}
		if($this->request->is('ajax')) {
			$this->autoRender = false;
			$result['errors'] = $this->Member->validationErrors;
			$errors = array();
			foreach($result['errors'] as $field => $data){
				$errors['Member'.Inflector::camelize($field)] = array_pop($data);
			}
			$result['errors'] = $errors;
			echo json_encode($result);
			return;
		}
		return (int)($result['error'])?0:1;
	}
	
	private function __mail_send($mail_id=null,$mail_data,$password=null){
		$this->loadModel('MailManager.Mail');
		$mail=$this->Mail->read(null,$mail_id);

		$key = 'RcGToklPpGQ56uCAkEpY5A';
		$from = $this->setting['site']['site_contact_email'];
		$from_name = $mail['Mail']['mail_from'];
		$subject = 'Thank you for registration with us';
		$to = $mail_data['Member']['email_id'];
		$to_name = $mail_data['Member']['first_name'];
		$template_name = 'user_sign_up';

		$global_merge_vars = '[';
        $global_merge_vars .= '{"name": "NAME", "content": "'.$mail_data['Member']['first_name'].'"},';
        $global_merge_vars .= '{"name": "EMAIL", "content": "'.$mail_data['Member']['email_id'].'"},';
        $global_merge_vars .= '{"name": "PHONE", "content": "'.$mail_data['Member']['phone'].'"},';
        $global_merge_vars .= '{"name": "PASSWORD", "content": "'.$password.'"}';
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
                        "from_name": "'.$from_name.'",
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
	}
	
	function dashboard() { 
		// load model
            array_push(self::$css_for_layout,'member/member-panel.css');
		$this->loadModel('BookingParticipate');
		$this->loadModel('Booking');
		$this->loadModel('VendorManager.BookingOrder');
		
		$email=$this->Session->read('MemberAuth.MemberAuth.email_id');
		$invite_detail=array();
		$conditions=array();
		$member_id = $this->MemberAuth->id;
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
		$criteria['fields'] = array('BookingParticipate.*','BookingOrder.service_title','BookingOrder.ref_no');
		$criteria['conditions'] = array(
		'BookingParticipate.email'=>$email,
		'BookingOrder.status'=>1,
		'OR'=>array(
			array('BookingParticipate.status'=>array(0,2,4)),
            ),
        );
		$criteria['order'] =array('BookingParticipate.id'=>'DESC');
		$invite_details=$this->BookingParticipate->find('all',$criteria);
		// booking list 
		$this->paginate = array('joins'=>
			array(
				array(
					'table'=>'booking_orders',
					'alias'=>'BookingOrder',
					'type'=>'LEFT',
					'conditions'=>array('BookingOrder.ref_no = Booking.ref_no')
				)
	
			),
			'conditions'=>array('Booking.member_id'=>$member_id,'Booking.status'=>array(0,1)),
			'group'=>'BookingOrder.ref_no',
		    'fields'=>array('Booking.*'),
			'limit'=>5,
			'order'=>array('Booking.ref_no'=>'DESC')
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/'),
			'name'=>'Home'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/members/dashboard/'),
			'name'=>'Dashboard'
		);
		$booking_details=$this->paginate("Booking",$conditions);
		$this->set('booking_details',$booking_details); 
		$this->set('invite_details',$invite_details); 
	}
	
	function invite_booking($booking_order_id=null) {

		array_push(self::$css_for_layout,'member/member-panel.css');
		if(empty($booking_order_id)){
				 $this->redirect('/');
		} 
		$criteria = array();
		$this->loadModel('VendorManager.BookingOrder');
		$this->loadModel('LocationManager.City');
		$this->loadModel('Booking');
		$member_id=$this->MemberAuth->id;
		$criteria['conditions']=array('BookingOrder.id'=>$booking_order_id);
		$criteria['group']=array('BookingOrder.id');
		$criteria['fields']=array('BookingOrder.*');
		$criteria['order']=array('BookingOrder.id ASC');
		$order_detail=$this->BookingOrder->find('first',$criteria);
		$customer_detail=$this->Booking->find('first',array('conditions'=>array('Booking.ref_no'=>$order_detail['BookingOrder']['ref_no'])));
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/members/dashboard/'),
			'name'=>'Dashboard'
		    );
		$this->breadcrumbs[] = array(
                    'url'=>Router::url('/members/invite_booking/'.$booking_order_id),
                    'name'=>'Invite Service Details'
			);
		$order_detail['BookingOrder']['location_name']=(!empty($order_detail['BookingOrder']['location_id']))?$this->City->getLocationListCityID($order_detail['BookingOrder']['location_id']): "Location not available";
	 	$this->set('customer_detail',$customer_detail);
		$this->set('order_detail',$order_detail);
	}
	
	function vendor_member_login(){
		if(!empty($this->request->data)) {
			if($this->request->data['Login']['login_type']==0){
				unset($this->request->data['Login']['login_type']);
				$this->request->data['Member']=$this->request->data['Login'];
				unset($this->request->data['Login']);
				if(!empty($this->request->data['Member']['redirect_url'])) {
					$this->MemberAuth->loginRedirect = $this->request->data['Member']['redirect_url'];
				}
				$this->MemberAuth->login();
			}else{
				$this->request->data['Vendor']['email']=$this->request->data['Login']['email_id'];
				$this->request->data['Vendor']['password']=$this->request->data['Login']['password'];
				unset($this->request->data['Login']);
				$this->VendorAuth->messages = array('direct_access' =>'You are not authorized to access that location.', 'auth_fail' =>'Invalid email or password.','admin_approv'=>__('Sorry! Admin has not approved your registration.'));
				$this->VendorAuth->login(); 
				$this->redirect(array('plugin'=>'vendor_manager','controller'=>'vendors', 'action' => 'registration'));
			}
		}		
		$this->redirect(array('controller'=>'members','action'=>'registration'));
	}

	function vendor_member_signup(){
		
		if(!empty($this->request->data)) {
			if($this->request->data['Signup']['login_type']==1){
				$this->redirect(array('plugin'=>'vendor_manager','controller'=>'vendors', 'action' => 'registration'));
			}else{
				$this->redirect(array('controller'=>'members','action'=>'registration'));
			}
		}
	}

	public function fb_login()
	{
		$criteria = array();
		$fb_id = $_POST['fb_id'];
		$first_name = $_POST['first_name'];
		$last_name = $_POST['last_name'];
		$email = $_POST['email_id'];
		$phone = $_POST['phone'];
		$criteria['conditions'] = array('Member.fb_id'=>$fb_id);
		$member_details =  $this->Member->find('first', $criteria);
		//$this->request->data['Member']['password'] = $fb_id;
		$this->request->data['Member']['email_id'] = $email;
		//print_r($this->request->data);die();
		if (!$member_details){
			$this->request->data['Member']['fb_id']      = $fb_id;
			$this->request->data['Member']['first_name'] = $first_name;
			$this->request->data['Member']['last_name']  = $last_name;
			$this->request->data['Member']['created_at'] = date('Y-m-d H:i:s');
			$this->request->data['Member']['active']     = '1';
			
			$this->Member->save($this->request->data);
		}
		$this->MemberAuth->login_facebook();
	}

	function messages($vendor_id = null)
	{
		$this->loadModel('Message');
		$this->loadModel('VendorManager.Vendor');
		$this->loadModel('MemberManager.Member');
		array_push(self::$css_for_layout,'member/member-panel.css');
		// javascript set
		array_push(self::$script_for_layout,'http://code.jquery.com/jquery-1.9.1.js','http://code.jquery.com/ui/1.10.3/jquery-ui.js');
		array_push(self::$css_for_layout,'http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css');
		$member_id = $this->MemberAuth->id();
		$this->set('member_id', $member_id);
		if ($vendor_id == null) {
			$all_messages = $this->Message->getAllListById($member_id);
			$messages = [];
			foreach ($all_messages as $message) {
				$message['vendor_name'] = $this->Vendor->find('first', ['fields' => 'CONCAT(Vendor.fname, " ", Vendor.lname) as vendor_name', 'conditions' => ['Vendor.id' => $message['vendor_id']]])[0]['vendor_name'];
				$message['member_name'] = $this->Member->find('first', ['fields' => 'CONCAT(Member.first_name, " ", Member.last_name) as member_name', 'conditions' => ['Member.id' => $message['member_id']]])[0]['member_name'];
				$messages[] = $message;
			}
			// print_r($messages);die;
			$this->set('messages', $messages);
		} else {
			$this->set('vendor_id', $vendor_id);
			$all_conversations = $this->Message->getAllConversation($member_id, $vendor_id);
			$conversations = [];
			foreach ($all_conversations as $conversation) {
				$conversation['vendor_name'] = $this->Vendor->find('first', ['fields' => 'CONCAT(Vendor.fname, " ", Vendor.lname) as vendor_name', 'conditions' => ['Vendor.id' => $conversation['vendor_id']]])[0]['vendor_name'];
				$conversation['member_name'] = $this->Member->find('first', ['fields' => 'CONCAT(Member.first_name, " ", Member.last_name) as member_name', 'conditions' => ['Member.id' => $conversation['member_id']]])[0]['member_name'];
				$conversations[] = $conversation;
			}
			$this->set('messages', $conversations);
		}
	}
}
?>
