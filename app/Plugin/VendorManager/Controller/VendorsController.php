<?php 
Class VendorsController extends VendorManagerAppController{
	public $uses = array('VendorManager.Vendor');
	public $components = array('Email','MemberManager.MemberAuth');
	public $paginate = array();
	public $id = null;
	
	function admin_index($search=null){
		$this->paginate = array();
		$condition = null;
		$this->paginate['limit']=20;
		if($this->request->is('post')){
			$this->redirect(array('plugin'=>'vendor_manager','controller'=>'vendors','action'=>'index' ,$this->request->data['search']));
		}
		$this->paginate['order']=array('Vendor.reorder'=>'ASC','Vendor.id'=>'DESC');		
		if($search!=null){
			$search = urldecode($search);
			$condition['OR']['Vendor.bname like'] =urldecode($search).'%';
			$condition['OR']['Vendor.fname like'] =urldecode($search).'%';
			$condition['OR']['Vendor.lname like'] =urldecode($search).'%';
			$condition['OR']['Vendor.email like'] =urldecode($search).'%';
		}
		$vendors=$this->paginate("Vendor", $condition);	
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/home'),
			'name'=>'Home'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/vendor_manager/vendors'),
			'name'=>'Manage Vendor'
		);
		$this->set('vendors', $vendors);
		$this->set('search',$search);
		$this->set('url','/'.$this->params->url);
		if($this->request->is('ajax')){
			$this->layout = '';
			$this -> Render('ajax_admin_index');
		}
	}
	
	function admin_vendorpayment($search=null,$status=null,$limit=20){
		$this->loadModel('VendorManager.Payment');
		$this->paginate = array();
		if($this->request->is('post')){
				$search = ($this->request->data['search']=='')?'_blank':$this->request->data['search'];
				$status = ($this->request->data['status']=='')?'_blank':$this->request->data['status'];
				$this->redirect(array('plugin'=>'vendor_manager','controller'=>'vendors','action'=>'vendorpayment' ,urlencode($search),$status));
		}
		$this->paginate = array('joins'=>
			array(
				array(
					'table'=>'vendors',
					'alias'=>'Vendor',
					'type'=>'LEFT',
					'conditions'=>array('Vendor.id = Payment.vendor_id')
				)
			),
			'conditions'=>array('Vendor.id=Payment.vendor_id'),
			'group'=>'Vendor.id',
		    'fields'=>array('Vendor.*','Payment.id','Payment.payment_amount','Payment.status'),
			'limit'=>$limit,
			'order'=>array('Vendor.id'=>'DESC')
		);
		$conditions=null;
		$this->paginate['order']=array('Payment.id'=>'DESC');
		if($search!=NULL && $search!="_blank"){
			$this->paginate['conditions']['OR'] = array(
													array('Vendor.bname like'=>($search).'%'),
													array('Vendor.fname like'=>urldecode($search).'%'),
													array('Vendor.lname like'=>urldecode($search).'%'),
													array('Vendor.email like'=>urldecode($search).'%'),
												);	
		}else{
			$search = '';
		}
		if($status!=NULL && $status!="_blank"){
			$this->paginate['conditions']['OR'] = array('Payment.status like'=>urldecode($status).'%');
		}
		if(($search!=NULL && $search!="_blank") && ($status!=NULL && $status!="_blank")){
			$this->paginate['conditions']['AND'] = array('Payment.status like'=>urldecode($status).'%');
			$this->paginate['conditions']['AND'] = array(
														'OR'=>array(
															array('Vendor.fname like'=>urldecode($search).'%'),
															array('Vendor.lname like'=>urldecode($search).'%'),
															array('Vendor.email like'=>urldecode($search).'%'),
														)
													);
		} 
		$payments=$this->paginate("Payment",$conditions);
		$this->breadcrumbs[] = array(
		'url'=>Router::url('/admin/home'),
		'name'=>'Home'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/vendor_manager/vendors'),
			'name'=>'Manage Vendor'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/vendor_manager/vendors/vendorpayment'),
			'name'=>'Payment List'
		);
		$this->set('vendor_payments', $payments);
		$this->set('search',$search);
		$this->set('status',$status);
		$this->set('url','/'.$this->params->url);
		if($this->request->is('ajax')){
			$this->layout = '';
			$this -> Render('ajax_admin_vendorpayment');
		}
	}
	function admin_paymentstatus($id=null){
		$this->layout = '';
		$this->loadModel('VendorManager.Payment');
		$payment=$this->Vendor->find("first", array(
		   "joins" => array(
            array(
                "table" => "payments",
                "alias" => "Payment",
                "type" => "LEFT",
                "conditions" => array(
                "Vendor.id = Payment.vendor_id"
                )
            )
        ),
        'conditions' => array('Vendor.id' => $id),
        'fields'=>array('Vendor.*','Payment.*'),
		));
		$this->set('payment',$payment);
	}
	
	function vendor_list(){
		// load model
		$this->loadModel('VendorManager.Service');
		$this->loadModel('ServiceManager.ServiceType');
		$this->loadModel('VendorManager.Vendor');
		$this->loadModel('MemberManager.Member');
		// load script and css 
		array_push(self::$script_for_layout,'animatedcollapse.js','jquery.contenthover.min.js','jquery.mousewheel.js');
		array_push(self::$script_for_layout,array('jquery.contenthover.min.js',$this->setting['site']['jquery_plugin_url'].'ratings/jquery.rating.js'));
		array_push(self::$css_for_layout,array($this->setting['site']['jquery_plugin_url'].'ratings/jquery.rating.css'));
		array_push(self::$css_for_layout,'pages.css');
		$vendor_services=$condition = array();
		$this->paginate = array();
		$this->paginate['limit']=Configure::read('Activiy.Limit');
		$this->paginate['fields']= array('Vendor.id','Vendor.bname','Vendor.fname','Vendor.lname','Vendor.image','AVG(`ServiceReview`.`rating`) as rating');
		$this->paginate['joins'] = array(
            array(
                'table' => 'services',
                'alias' => 'Service',
                'type' => 'INNER',
                'conditions' =>array('AND'=>array('Service.vendor_id = Vendor.id','Service.status = 1','Vendor.active = 1'),'OR'=>array('Vendor.payment_status'=>1 ,'Vendor.account_type'=>0))
            ),
            array(
                'table' => 'service_reviews',
                'alias' => 'ServiceReview',
                'type' => 'LEFT',
                'conditions' => array('ServiceReview.service_id = Service.id')
            )
                
        ); 
        //$condition['Vendor.active'] = 1;
		$this->paginate['group'] = array('Vendor.id');
		$this->paginate['order'] = '`rating` DESC,Service.id';
		$vendor_services=$this->paginate("Vendor",$condition);
		//get services,images and create new array of vendoer service list
		$new_vendor_services=array();
		foreach($vendor_services as $key=>$vendor_service){
			$new_vendor_services[$key]['Vendor']['id']=$vendor_service['Vendor']['id'];
			$new_vendor_services[$key]['Vendor']['bname']=$vendor_service['Vendor']['bname'];
			$new_vendor_services[$key]['Vendor']['name']=$vendor_service['Vendor']['fname']." ".$vendor_service['Vendor']['lname'];
			$new_vendor_services[$key]['Vendor']['image']=$vendor_service['Vendor']['image'];
			$new_vendor_services[$key]['Vendor']['rating']=round($vendor_service[0]['rating']);
			$new_vendor_services[$key]['ServicesType']=$this->ServiceType->service_type_list($vendor_service['Vendor']['id']);
		}
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/'),
			'name'=>'Home'
		    );
		$this->breadcrumbs[] = array(
                    'url'=>Router::url('/vendor_manager/vendors/vendor_list'),
                    'name'=>'Vendors'
		    );
		$this->set('vendor_services',$new_vendor_services);	 
		$isMemberlogin=$this->MemberAuth->id;
		$isVendorlogin=$this->VendorAuth->id;
		$this->set('isMemberlogin',$isMemberlogin);
		$this->set('isVendorlogin',$isVendorlogin);
		$this->loadModel('ContentManager.Page');    
		$page=$this->Page->read(null,5);
		if(!empty($page['Page']['page_title'])){
			$this->title_for_layout .= ": ". $page['Page']['page_title'];
		}
		if(!empty($page['Page']['page_metakeyword'])){
			$this->metakeyword = $page['Page']['page_metakeyword'];
		}
		if(!empty($page['Page']['page_metadescription'])){
			$this->metadescription = $page['Page']['page_metadescription'];
		}
		if($this->request->is('ajax')){
                $this->layout = '';
                $this->Render('ajax_vendor_list');
		}
	}
	
	function admin_add($id=null){
		$this->breadcrumbs[] = array(
		'url'=>Router::url('/admin/home'),
		'name'=>'Home');
		$this->breadcrumbs[] = array(
				'url'=>Router::url('/admin/vendor_manager/vendors'),
				'name'=>'Manage Vendor'
		);
		$this->breadcrumbs[] = array(
				'url'=>Router::url('/admin/vendor_manager/vendors/add'),
				'name'=>($id==null)?'Add Vendor':'Update Vendor'
		);
		if(!empty($this->request->data) && $this->validation()){
			if($this->request->data['Vendor']['account_type']==0){
				$this->request->data['Vendor']['payment_amount']=0;
			}
			if(empty($id)){
				$this->request->data['Vendor']['created_at']=date('Y-m-d H:i:s');
				$this->request->data['Vendor']['approval']=1;
				$this->request->data['Vendor']['active'] =1;
				$this->request->data['Vendor']['payment_status'] =0;
				if(empty($this->request->data['Vendor']['commission'])){
					$this->request->data['Vendor']['commission']=0;
				} 
				$vendorPass = trim($this->RandomString());
				$this->request->data['Vendor']['password'] = Security::hash(Configure::read('Security.salt').$vendorPass);
				
				if($this->request->data['Vendor']['image']['error'] < 1){
					$image_name =self::_manage_image($this->request->data['Vendor']['image'],$this->Vendor->id);
					$this->request->data['Vendor']['image'] = $image_name;		
				}
				else{
						unset($this->request->data['Vendor']['image']);
				}
			}else{
					if($this->request->data['Vendor']['image']['error'] < 1) {
						$profile_image = $this->Vendor->find('first',array('fields'=>array('Vendor.image'),'conditions'=>array('Vendor.id'=>$id)));
						$image_name =self::_manage_image($this->request->data['Vendor']['image'],$id);
						$destination = Configure::read('VendorProfile.SourcePath');
						if(!empty($profile_image['Vendor']['image'])){
							unlink($destination.$profile_image['Vendor']['image']);
						}
						$this->request->data['Vendor']['image'] = $image_name;	 
					}else{
						unset($this->request->data['Vendor']['image']);
					}
				 
					$this->request->data['Vendor']['updated_at']=date('Y-m-d H:i:s');
				}
				$this->Vendor->create();
				$this->Vendor->save($this->request->data);
				if(!empty($this->Vendor->id) && (empty($id))){
					$this->__mail_send(5,$this->request->data,$vendorPass);	
				}
				if ($this->request->data['Vendor']['id']) {
					$this->Session->setFlash(__('Vendor has been updated successfully'));
					} 
					else {
						$this->Session->setFlash(__('Vendor has been added successfully'));
					}
				$this->redirect($this->request->data['Vendor']['redirect']);
			}
			else{
				if($id!=null){
					$this->request->data = $this->Vendor->read(null,$id);
				}else{
					$this->request->data = array();
				}
			} 
			$redirect_url=(Controller::referer()=="/")? Router::url('/admin/vendor_manager/vendors') :Controller::referer();
            $this->set('url',$redirect_url);
	}
	
	function registration(){
		array_push(self::$css_for_layout,'vendor/registration.css');

		if($this->VendorAuth->id()){
			$this->redirect($this->VendorAuth->loginRedirect);
		}
		if(!empty($this->request->data) && $this->validation()){
			if($this->request->data['Vendor']['form-name']=='RegistrationForm'){
				self::_vendor_registration();
			}
			if($this->request->data['Vendor']['form-name']=='LoginForm'){
				self::login();
			}
		}
		$this->breadcrumbs[] = array(
				'url'=>Router::url('/'),
				'name'=>'Home'
				);
		$this->breadcrumbs[] = array(
			'url'=>Router::url(array('controller'=>'vendors','action'=>'registration')),
			'name'=>'Vendor Registration'
		);
		$this->title_for_layout = 'Vendor Registration';
		$this->metakeyword = 'Vendor Registration';
		$this->metadescription = 'Vendor Registration';
	}

	function log_in()
	{
		array_push(self::$css_for_layout,'vendor/registration.css');

		if($this->VendorAuth->id()){
			$this->redirect($this->VendorAuth->loginRedirect);
		}
	}
	function login(){
		if($this->VendorAuth->id()){
			$this->redirect($this->VendorAuth->loginRedirect);
		}
		if(!empty($this->request->data) && $this->validation()){
			if($this->request->data['Vendor']['form-name']=='LoginForm'){
				$this->request->data['Vendor']['email'] = $this->request->data['Vendor']['emailid']; 
				$this->request->data['Vendor']['password'] = $this->request->data['Vendor']['pass'];
				$this->VendorAuth->login();
				unset($this->request->data['Vendor']['email']); 
				unset($this->request->data['Vendor']['password']); 
			}
		}
		$this->title_for_layout = 'Login';
		$this->metakeyword = 'Vendor Login';
		$this->metadescription = 'Vendor login';
	}

	function dashboard(){
		array_push(self::$css_for_layout,'vendor/vendor-panel.css');
		$this->loadModel('VendorManager.Service');
		$this->loadModel('MemberManager.Member');
		$this->loadModel('LocationManager.City');
		$this->loadModel('VendorManager.ServiceImage');
		$this->loadModel('VendorManager.Booking');
		$vendor_id=$this->VendorAuth->id();
		if(empty($vendor_id)) {
				$this->redirect($this->VendorAuth->loginRedirect);
		}
		$this->loadModel('LocationManager.City');
		$condition = null;
		$criteria = array();
		$this->paginate = array();
		$this->paginate['limit']=5;
		$this->paginate['fields']= array('*');
		$this->paginate['joins'] = array(
            array(
                'table' => 'service_types',
                'alias' => 'ServiceType',
                'type' => 'LEFT',
                'conditions' => array('ServiceType.id = Service.service_type_id')
            )
        );
		$condition['Service.vendor_id'] = $this->VendorAuth->id();
		$this->paginate['group'] = array('Service.id');
		$my_services=$this->paginate("Service",$condition);
		$service_id_list=array();
		$service_lists_filter=array();
		$service_lists_filter1=array();
		foreach($my_services as $service) {
			$service_id_list[]=$service['Service']['location_id'];
		}
		$location_lists=$this->City->getLocationListByID($service_id_list);
		foreach($my_services as $service) {
			$service_id_list[]=$service['Service']['location_id'];
			$service_lists_filter1['id']=$service['Service']['id'];
			$service_lists_filter1['service_name']=$service['ServiceType']['name'];
			$service_lists_filter1['service_title']=$service['Service']['service_title'];
			$service_lists_filter1['location_id']=$service['Service']['location_id'];
			$service_lists_filter1['location_details']=(!empty($service['Service']['location_string']))?$service['Service']['location_string']:((!empty($location_lists[$service['Service']['location_id']]))?$location_lists[$service['Service']['location_id']]:"Location Not availble");
			$service_lists_filter1['service_price']=$service['Service']['service_price'];
			$service_lists_filter1['description']=$service['Service']['description'];
			$service_lists_filter1['image']=$this->ServiceImage->getOneimageServiceImageByservice_id($service['Service']['id']);
			
			$service_lists_filter[]=$service_lists_filter1;
		}
		//particular vendor booking details
		$criteria=array();
		$criteria['joins']=array(
								array(
								'table'=>'booking_orders',
								'alias'=>'BookingOrder',
								'type'=>'LEFT',
								'conditions'=>array('BookingOrder.ref_no = Booking.ref_no')
								),
							);
		$criteria['fields'] = array('Booking.*','BookingOrder.status',);
		$criteria['conditions'] =array('BookingOrder.vendor_id'=>$vendor_id);
		$criteria['order'] =array('Booking.ref_no'=>'DESC');
		$criteria['group'] =array('BookingOrder.ref_no');
		$criteria['limit'] =5;
		$booking_details=$this->Booking->find('all',$criteria);
		// count for no of booking in particular vendor 
		$criteria['limit'] =0;
		$count_booking_list=$this->Booking->find('count',$criteria);
		 
		//review list in dashboard
		$this->set('booking_details',$booking_details);
		$this->set('count_booking_list',$count_booking_list);
		$this->set('services',$service_lists_filter);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/'),
			'name'=>'Home'
		    );
		$this->breadcrumbs[] = array(
                    'url'=>Router::url(array('plugin'=>'vendor_manager','controller'=>'vendors', 'action' => 'dashboard')),
                    'name'=>'Dashboard'
			);
	}
	private function _login (){
		$email=trim($this->request->data['Vendor']['emailid']);
		$password=$this->request->data['Vendor']['pass'];
		$encrypPass=trim( Security::hash(Configure::read('Security.salt').$password));
		$vendorinfo=$this->Vendor->find('first',array('conditions'=>array('Vendor.email'=>$email,'Vendor.password'=>$encrypPass)));
		if(!empty($vendorinfo)){
			/**check for vendor approval, active, and payment details block**/
			if($vendorinfo['Vendor']['approval']=='1' && $vendorinfo['Vendor']['active']=='1'  && ($vendorinfo['Vendor']['payment_status']=='1' || $results['Vendor']['account_type']=='0' )){
				$this->redirect(array('plugin'=>'vendor_manager','controller'=>'vendors', 'action' => 'dashboard'));
			}
			
			/**end for vendor approval, active, and payment details block**/
			
			/**check for vendor approval, and payment details block, if payment not done redirect to payment gateway**/
						
			else if($vendorinfo['Vendor']['approval']=='1' && $vendorinfo['Vendor']['payment_status']!='1'){
				$this->redirect(array('plugin'=>'vendor_manager','controller'=>'payments', 'action' => 'paynow'));	
			}
			
			/**end of above else if statement**/
			
			/**below code is used to display error message if vendor not approved**/
			
			else{
				$this->Session->setFlash(__('Sorry! Admin has not approved your registration yet.'),'default',array(),'login_error');
				//$this->redirect(array('controller'=>'vendors', 'action' => 'registration'));
			
			}	/**end of else statement**/

		}  /**end of parent if statement**/
		/**below code is used to display error message if vendor login details are incorrect approved**/
		else{
			$this->Session->setFlash(__('Invalid email or password.Please try again'),'default',array(),'login_error');
			//$this->redirect(array('controller'=>'vendors', 'action' => 'registration'));
		}	/**end of else statement**/
	}
	private function _vendor_registration(){
		$this->request->data['Vendor']['created_at']=date('Y-m-d H:i:s');
		$realpassword = trim($this->request->data['Vendor']['password']);
		$this->request->data['Vendor']['password'] = Security::hash(Configure::read('Security.salt').trim($this->request->data['Vendor']['password']));
		if(empty($this->request->data['Vendor']['commission'])){
			$this->request->data['Vendor']['commission']=0;
		}
		$this->request->data['Vendor']['active'] = 0;
		$this->request->data['Vendor']['approval'] = 0;
		$this->request->data['Vendor']['payment_status'] = 0;
		$name=$this->request->data['Vendor']['fname'];
		$this->Vendor->create();
		$this->Vendor->save($this->request->data,array('validate'=>false));
		if(!empty($this->Vendor->id) && (empty($id))){

			$time = time();
			$hash = md5($this->request->data['Vendor']['email'] . $time);
			$confirm_url = $this->setting['site']['site_url'] . '/vendor_manager/vendors/confirm_registration/' . $this->Vendor->id . '/' . $hash . '/' . $time;

			// send confirm email

			$this->loadModel('MailManager.Mail');
			$mail=$this->Mail->read(null,$mail_id);

			$key = 'RcGToklPpGQ56uCAkEpY5A';
			$from = $this->setting['site']['site_contact_email'];
			$from_name = $mail['Mail']['mail_from'];
			$subject = 'Thank you for registering with us';
			$to = $this->request->data['Vendor']['email'];
			$to_name = $this->request->data['Vendor']['fname'];
			$template_name = 'vendor_confirm_sign_up';

			$global_merge_vars = '[';
	        $global_merge_vars .= '{"name": "NAME", "content": "'.$this->request->data['Vendor']['fname'].'"},';
	        $global_merge_vars .= '{"name": "BNAME", "content": "'.$this->request->data['Vendor']['bname'].'"},';
	        $global_merge_vars .= '{"name": "EMAIL", "content": "'.$this->request->data['Vendor']['email'].'"},';
	        $global_merge_vars .= '{"name": "PHONE", "content": "'.$this->request->data['Vendor']['phone'].'"},';
	        $global_merge_vars .= '{"name": "CONFIRM_LINK", "content": "'.$confirm_url.'"},';
	        $global_merge_vars .= '{"name": "PASSWORD", "content": "'.$realpassword.'"}';
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
		$this->redirect(array('plugin'=>'vendor_manager','controller'=>'vendors', 'action' => 'confirm_message'));
	}

	public function confirm_registration($vendor_id = NULL, $hash = NULL, $time = NULL)
	{
		if ($vendor_id == NULL || $hash == NULL || $time == NULL) {
			$this->Session->setFlash(__('You seems to be accessing pages that you\'re not allowed, please register.'),'default',array(),'login_error');
			$this->redirect(array('plugin'=>'vendor_manager','controller'=>'vendors', 'action' => 'registration'));	
		}

		$vendor = $this->Vendor->read(NULL, $vendor_id);
		
		// check if a vendor is found
		if (count($vendor) == 0) {
			$this->Session->setFlash(__('Sorry! Your link seems to be broken, we cannot find your record.'),'default',array(),'login_error');
			$this->redirect(array('plugin'=>'vendor_manager','controller'=>'vendors', 'action' => 'registration'));	
		}

		$vendor = array_pop($vendor);

		// check if the account is already active
		if ($vendor['active'] == 1) {
			$this->Session->setFlash(__('Your account has already been activated, please continue to login.'),'default',array(),'login_error');
			$this->redirect(array('plugin'=>'vendor_manager','controller'=>'vendors', 'action' => 'registration'));
		}

		// checks validity of the link, checks for attackers
		$hash_check = ($hash == md5($vendor['email'] . $time));
		if (!$hash_check) {
			$this->Session->setFlash(__('Sorry! Your link seems to be broken, or your link is invalid.'),'default',array(),'login_error');
			$this->redirect(array('plugin'=>'vendor_manager','controller'=>'vendors', 'action' => 'registration'));
		}

		// check time, uses 60 days only
		$current_time = time();
		$lifetime = 60*60*24*60;
		if (($current_time - $time) > $lifetime) {
			$this->Session->setFlash(__('Sorry! Your link has already expired, the link lifetime is only for 60 days.'),'default',array(),'login_error');
			$this->redirect(array('plugin'=>'vendor_manager','controller'=>'vendors', 'action' => 'registration'));
		}

		// confirm the vendor
		$this->Vendor->id = $vendor_id;
		$this->Vendor->saveField('active', 1);

		// send welcome email
		$this->__mail_send(5,['Vendor' => $vendor],$realpassword);

		// subscribe the new user
		$apikey    = '08c19e41483c616d5fd3ec14df89e2bc-us11';
		$list_id   = 0;
		$list_name = 'Waterspot Vendor List';
		$email     = $vendor['email'];
		$name      = $vendor['fname'];

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
			    "email": {
			    	"email": "'.$email.'"
			    },
			    "double_optin": false
			}';
			$ch = curl_init('https://us11.api.mailchimp.com/2.0/lists/subscribe.json');
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'Content-Length: ' . strlen($data))
			);

			$results = json_decode(curl_exec($ch));

			
		}

		// redirect to thank you
		$this->redirect(array('plugin'=>'vendor_manager','controller'=>'vendors', 'action' => 'thankyou'));
	}
	
	// This function is used to than after vendor registration
	public function thankyou(){
            array_push(self::$css_for_layout,'pages.css');
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/'),
			'name'=>'Home'
		    );
		$this->breadcrumbs[] = array(
                    'url'=>Router::url('/vendor_manager/vendors/thankyou'),
                    'name'=>'Thankyou'
		    );
	}

	public function confirm_message(){
        array_push(self::$css_for_layout,'pages.css');
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/'),
			'name'=>'Home'
		    );
		$this->breadcrumbs[] = array(
                    'url'=>Router::url('/vendor_manager/vendors/confirm_message'),
                    'name'=>'Confirm Registration'
		    );
	}
	
	function admin_approval($id=null){
		$this->breadcrumbs[] = array(
		'url'=>Router::url('/admin/home'),
		'name'=>'Home');
		$this->breadcrumbs[] = array(
				'url'=>Router::url('/admin/vendor_manager/vendors'),
				'name'=>'Manage Vendor'
		);
		$this->breadcrumbs[] = array(
				'url'=>Router::url('/admin/vendor_manager/vendors/approve'),
				'name'=>($id==null)?'Approve Vendor':' '
		);
		if(!empty($this->request->data)){
				$this->request->data['Vendor']['approval']=1;
				$this->request->data['Vendor']['updated_at']=date('Y-m-d H:i:s');
				if($this->request->data['Vendor']['account_type']==0){
					$this->request->data['Vendor']['payment_amount']=0;
				}
				// save here vendor details
				$this->Vendor->create();
				$this->Vendor->save($this->request->data);
			if(!empty($this->Vendor->id)){
				
				$this->__mail_approve_send(7,$this->request->data);	
			}
			if ($this->request->data['Vendor']['id']) {
				$this->Session->setFlash(__('Vendor has been approved successfully'));
				} 
			$this->redirect($this->request->data['Vendor']['redirect']);
			}
		else{
			if($id!=null){
				$this->request->data = $this->Vendor->read(null,$id);
			}else{
				$this->request->data = array();
			}
		} 
		$this->set('url',Controller::referer());
	}
	
	private function __mail_send($mail_id=null,$mail_data,$password=null) {
		$this->loadModel('MailManager.Mail');
		$mail=$this->Mail->read(null,$mail_id);

		$key = 'RcGToklPpGQ56uCAkEpY5A';
		$from = $this->setting['site']['site_contact_email'];
		$from_name = $mail['Mail']['mail_from'];
		$subject = 'Thank you for registration with us';
		$to = $mail_data['Vendor']['email'];
		$to_name = $mail_data['Vendor']['fname'];
		$template_name = 'vendor_sign_up';

		$global_merge_vars = '[';
        $global_merge_vars .= '{"name": "NAME", "content": "'.$mail_data['Vendor']['fname'].'"},';
        $global_merge_vars .= '{"name": "BNAME", "content": "'.$mail_data['Vendor']['bname'].'"},';
        $global_merge_vars .= '{"name": "EMAIL", "content": "'.$mail_data['Vendor']['email'].'"},';
        $global_merge_vars .= '{"name": "PHONE", "content": "'.$mail_data['Vendor']['phone'].'"},';
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
		curl_close($ch);

    }
    
    private function __mail_approve_send($mail_id=null,$mail_data) {
		$this->loadModel('MailManager.Mail');
		$vendordetail=$this->Vendor->read(null,$mail_data['Vendor']['id']);

		$global_merge_vars = '[';
        $global_merge_vars .= '{"name": "USER_NAME", "content": "'.$vendordetail['Vendor']['fname'].'"},';
        $global_merge_vars .= '{"name": "URL", "content": "'.$this->setting['site']['site_url'].Router::url(array('plugin'=>'vendor_manager','admin'=>false,'controller'=>'vendors','action'=>'registration')).'"}';
        $global_merge_vars .= ']';


        $key = 'RcGToklPpGQ56uCAkEpY5A';
		$from = $this->setting['site']['site_contact_email'];
		$from_name = "Waterspot Admin";
		$subject = 'Your vendor registration with WaterSpot has been approved';
		$to = $vendordetail['Vendor']['email'];
		$to_name = $vendordetail['Vendor']['fname'];
		$template_name = 'vendor_registration_approved';


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
    
	function RandomString() {
		$characters = '139abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randstring = '';
		for ($i = 0; $i < 9; $i++) {
			$arr1 = str_split($characters);
			$randstring .= $arr1[rand(0, $i)];
		}
		return $randstring;
	}
	
	function admin_delete($id=null){
		$this->autoRender = false;
	 	$data=$this->request->data['Vendor']['id'];
		$action = $this->request->data['Vendor']['action'];
		$ans="0";
		foreach($data as $value){
			if($value!='0'){
				if($action=='Activate'){
					$vendor['Vendor']['id'] = $value;
					$vendor['Vendor']['active']=1;
					$this->Vendor->create();
					$this->Vendor->save($vendor);
					$ans="1";
				}
				if($action=='Deactivate'){
					$vendor['Vendor']['id'] = $value;
					$vendor['Vendor']['active']=0;
					$this->Vendor->create();
					$this->Vendor->save($vendor);
					$ans="1";
				}
				if($action=='Delete'){
					self::beforeDeleteRecords($value);
					$ans="2";
				}
			}
		}
		if($ans=="1"){
			$this->Session->setFlash(__('Vendor has been '.$this->data['Vendor']['action'].'d successfully', true));
		}
		else if($ans=="2"){
			$this->Session->setFlash(__('Vendor has been '.$this->data['Vendor']['action'].'d successfully', true));
		}else{
			$this->Session->setFlash(__('Please Select any Vendor', true),'default','','error');
		}
		$this->redirect($this->request->data['Vendor']['redirect']);
    }
	
	function validation($action=null){
		if($this->request->data['Vendor']['form-name']=='LoginForm') {
			$this->Vendor->setValidation('LoginForm');
		}
		else if($this->request->data['Vendor']['form-name']=='RegistrationForm') {
			$this->Vendor->setValidation('Register');
		}
		else if($this->request->data['Vendor']['form-name']=='Admin-vendor-registration'){
			$this->Vendor->setValidation('Admin-register');
		}
		else if($this->request->data['Vendor']['form-name']=='Change-Password') {
			$this->Vendor->setValidation('Change-Password');
		}
		$this->Vendor->set($this->request->data);
		$result = array();
		if ($this->Vendor->validates()) {
			$result['error'] = 0;
		}else{
			$result['error'] = 1;
		}
		if($this->request->is('ajax')) {
			$this->autoRender = false;
			$result['errors'] = $this->Vendor->validationErrors;
			$errors = array();
			foreach($result['errors'] as $field => $data){
				$errors['Vendor'.Inflector::camelize($field)] = array_pop($data);
			}
			$result['errors'] = $errors;
			echo json_encode($result);
			return;
		}
		return (int)($result['error'])?0:1;
	}
	
	function admin_view($id = null) {
		$this->layout = '';
		$criteria = array();
		$criteria['conditions'] = array('Vendor.id'=>$id);
		$vendor_details =  $this->Vendor->find('first', $criteria);
		$this->set('vendor', $vendor_details);
    }
    
	function ajax_sort(){
		$this->autoRender = false;
		foreach($_POST['sort'] as $order => $id){
			$vendor= array();
			$vendor['Vendor']['id'] = $id;
			$vendor['Vendor']['reorder'] = $order;
			$this->Vendor->create();
			$this->Vendor->save($vendor);
		}
    }
    
    function logout(){
		$this->VendorAuth->logout(array('plugin'=>false,'controller'=>'pages','action'=>'home'));
	}
	
	private function _manage_image($image = array(),$vendor_id=null) {
        if ($image['error'] > 0) {
            return null;
        } else {
            $existing_image = array();
            if ($image['error'] > 0) {
                return $existing_image['Vendor']['image'];
            } else {
                $destination = Configure::read('VendorProfile.SourcePath');
                $ext = explode('.', $image['name']);
                $image_name =time()."_" .$vendor_id. '.' . array_pop($ext);
                move_uploaded_file($image['tmp_name'], $destination . $image_name);
                if (!empty($existing_image)) {
                    unlink($destination . $existing_image['Vendor']['image']);
                }
                return $image_name;
            }
        }
    }
	
	Private function beforeDeleteRecords($vendor_id = null) {
		$this->loadModel('VendorManager.Service');
		$this->loadModel('VendorManager.ServiceImage');
		$this->loadModel('ServiceManager.ServiceSlot');
		$this->loadModel('VendorManager.VendorServiceAvailability');
		$this->loadModel('VendorManager.ValueAddedService');
		$service_id = $this->Service->find("list",array('fields'=>array('Service.id'),'conditions'=>array('Service.vendor_id'=>$vendor_id)));
		$this->Vendor->bindModel(
			array('hasMany' => array(
				'Service' => array(
					'className' => 'Service',
					'foreignKey' => '',
					'conditions'=>array('Service.vendor_id'=>$vendor_id),
					'dependent' => True
					),
				'ServiceSlot' => array(
					'className' => 'ServiceSlot',
					'foreignKey' => false,
					'conditions'=>array('ServiceSlot.service_id'=> $service_id),
					'dependent' => True
					),
				'ServiceImage' => array(
					'className' => 'ServiceImage',
					'foreignKey' => false,
					'conditions'=>array('ServiceImage.service_id'=> $service_id),
					'dependent' => True
					),
				'ValueAddedService' => array(
					'className' => 'ValueAddedService',
					'foreignKey' => false,
					'conditions'=>array('ValueAddedService.service_id'=> $service_id),
					'dependent' => True
					),
				'VendorServiceAvailability' => array(
					'className' => 'VendorServiceAvailability',
					'foreignKey' => '',
					'conditions'=>array('VendorServiceAvailability.vendor_id'=>$vendor_id),
					'dependent' => True
					),
					
				)
			)
		);
		$vendor_details = $this->Vendor->find("first",array('conditions'=>array('Vendor.id'=>$vendor_id)));
		$image_path= Configure::read('Image.SourcePath');
		$Vendor_profile_path= Configure::read('VendorProfile.SourcePath');
		// Delete vendor profile images
		if(!empty($vendor_details['Vendor']['image'])){
			@unlink($Vendor_profile_path.$vendor_details['Vendor']['image']);
		}
		// delete all service images 
		if(!empty($vendor_details['ServiceImage'])) {
			foreach($vendor_details['ServiceImage'] as $service_image){
				@unlink($image_path.$service_image['image']);
				$this->ServiceImage->delete($service_image['id'],true,false);
			}
		}
		if(!empty($vendor_details['Service'])) {
			foreach($vendor_details['Service'] as $service){
				$this->Service->delete($service['id'],true,false);
			}
		}
		if(!empty($vendor_details['ServiceSlot'])) {
			foreach($vendor_details['ServiceSlot'] as $service_slot){
				 $this->ServiceSlot->delete($service_slot['id'],true,false);
			}
		}
		if(!empty($vendor_details['ValueAddedService'])) {
			foreach($vendor_details['ValueAddedService'] as $value_added_service){
				 $this->ValueAddedService->delete($value_added_service['id'],true,false);
			}
		}
		if(!empty($vendor_details['VendorServiceAvailability'])) {
			foreach($vendor_details['VendorServiceAvailability'] as $vendor_service_availability){
				$this->VendorServiceAvailability->delete($vendor_service_availability['id'],true,false);
			}
		}
		$this->Vendor->delete($vendor_id,true, false); 
		return true; 
	}
	/* Vendor list by vendor id */
	
	function activities($vendor_id=null,$service_type_id=null,$sort_by_price=null){
		// load model
		$this->loadModel('ServiceManager.ServiceType');
		$this->loadModel('VendorManager.Service');
		$this->loadModel('VendorManager.Vendor');
		$this->loadModel('VendorManager.ServiceImage');
		array_push(self::$script_for_layout,'jquery.contenthover.min.js',$this->setting['site']['jquery_plugin_url'].'ratings/jquery.rating.js');
		array_push(self::$css_for_layout,$this->setting['site']['jquery_plugin_url'].'ratings/jquery.rating.css');
		array_push(self::$css_for_layout,'pages.css');
		// searching list
		$service_name='';
		$conditions=array();
		$vendor_list=array();
		$service_type_list=array();
		if ($vendor_id != null && $vendor_id != 'vendor_id') {
			$this->request->data['Search']['vendor_list']=$vendor_id;
		 	$conditions['Service.vendor_id ='] = $vendor_id;
		}
		if ($service_type_id != null && $service_type_id != 'service_type') {
			$this->request->data['Search']['service_type_list']=$service_type_id;
			$conditions['Service.service_type_id ='] = $service_type_id;
		}
		if ($sort_by_price != null && $sort_by_price != 'sortbyprice') {
		 	$price_range=explode('-',$sort_by_price);
		 	$this->request->data['Search']['sort_price']=$sort_by_price;
			$conditions[] = array('OR'=>array(
			array('Service.service_price BETWEEN ? AND ?'=>array($price_range[0],$price_range[1])))); 	 
		}
		$conditions[]=array('AND'=>array('Vendor.active'=>1,'Service.status'=>1),'OR'=>array('Vendor.payment_status'=>1 ,'Vendor.account_type'=>0));
		$this->paginate = array();
		$subQuery = "(SELECT AVG(ifnull((`ServiceReview`.`rating`), 0)) FROM service_reviews AS `ServiceReview` WHERE `ServiceReview`.`service_id` = `Service`.`id` and `ServiceReview`.`status` = 1 GROUP BY `ServiceReview`.`service_id`) AS rating ";
		$this->paginate['fields'] = array('Service.id','Service.service_title','Service.service_price','Service.description',$subQuery);
		$this->paginate['joins'] = array(
						array(
							'table' => 'vendors',
							'alias' => 'Vendor',
							'type' => 'inner',
							'conditions' => array('Vendor.id = Service.vendor_id')
						)
					);
		$this->paginate['conditions'][] = $conditions;
		$this->paginate['limit'] = Configure::read('Activiy.Limit');
		//$this->paginate['order'] = array('`rating`'=>'DESC','Service.reorder'=>'ASC');
		$this->paginate['order'] = '`rating` DESC,Service.id';
		$this->paginate['group'] = array('Service.id');
		$vendor_service_list= $this->paginate('Service');
		$new_vendor_service_list =array();
		foreach($vendor_service_list as $key=>$service_list) {
			$service_list['image']=$this->ServiceImage->getOneimageServiceImageByservice_id($service_list['Service']['id']);
			$service_list['rating'] = (round($service_list[0]['rating']));
			$new_vendor_service_list[$key]=$service_list;
		}
		// get vendor detils from Model
		$vendor_detail=$this->Vendor->vendorDetailId($vendor_id);
		$this->set('vendor_detail',$vendor_detail); 
		$this->set('service_type_list',$service_type_list); 
		$this->set('vendor_service_list',$new_vendor_service_list); 
		// set breadcrumbs 
		$this->breadcrumbs[] = array(
                'url'=>Router::url('/'),
                'name'=>'Home'
            );
            $this->breadcrumbs[] = array(
                'url'=>'#',
                'name'=>(!empty($vendor_detail['Vendor']['bname'])?$vendor_detail['Vendor']['bname']:$vendor_detail['Vendor']['fname']." ".$vendor_detail['Vendor']['lname'])
            );
            $this->breadcrumbs[] = array(
                'url'=>Router::url(array('controller' => 'activity', 'action' => 'activities','plugin'=>false)),
                'name'=>"Activities"
            );
        $this->set('vendor_id',$vendor_id);
        $this->set('sort_by_price',$sort_by_price);
        // set page title and description 
        $this->title_for_layout = "Vendor Activities: ".ucfirst(!empty($vendor_detail['Vendor']['bname'])?$vendor_detail['Vendor']['bname']:$vendor_detail['Vendor']['fname']." ".$vendor_detail['Vendor']['lname']);
		$this->metakeyword = strip_tags($vendor_detail['Vendor']['about_us']);
		$this->metadescription =strip_tags($vendor_detail['Vendor']['about_us']);
        if($this->request->is('ajax')){
                $this->layout = '';
                $this -> Render('ajax_activities');
        }  
  	}

  	function messages($member_id = null)
	{
		$this->loadModel('Message');
		$this->loadModel('VendorManager.Vendor');
		$this->loadModel('MemberManager.Member');
		array_push(self::$css_for_layout,'member/member-panel.css');
		// javascript set
		array_push(self::$script_for_layout,'https://code.jquery.com/jquery-1.9.1.js','https://code.jquery.com/ui/1.10.3/jquery-ui.js');
		array_push(self::$css_for_layout,'https://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css');
		$vendor_id = $this->VendorAuth->id();
		$this->set('member_id', $member_id);
		if ($member_id == null) {
			$all_messages = $this->Message->vendorGetAllListById($vendor_id);
			$messages = [];
			foreach ($all_messages as $message) {
				$message['vendor_name'] = $this->Vendor->find('first', ['fields' => 'CONCAT(Vendor.fname, " ", Vendor.lname) as vendor_name', 'conditions' => ['Vendor.id' => $message['vendor_id']]])[0]['vendor_name'];
				$message['member_name'] = $this->Member->find('first', ['fields' => 'CONCAT(Member.first_name, " ", Member.last_name) as member_name', 'conditions' => ['Member.id' => $message['member_id']]])[0]['member_name'];
				$messages[] = $message;
			}
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
