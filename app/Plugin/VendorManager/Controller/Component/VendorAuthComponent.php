<?php
App::uses('Component', 'Controller');
App::uses('Router', 'Routing');
App::uses('Security', 'Utility');
App::uses('Debugger', 'Utility');
App::uses('CakeSession', 'Model/Datasource');

class VendorAuthComponent extends Component{
	public 	$vendors = array(),
		$loginAction = array(),
		$loginRedirect = array('plugin'=>'vendor_manager','controller'=>'vendors','action'=>'dashboard'),
		$logoutRedirect = array('plugin'=>'vendor_manager','controller'=>'vendors','action'=>'registration'),
		$userScope = array(),
		$messages = array('direct_access'=>'','auth_fail'=>'','');
			
		public $id = null;
		public $model_use = 'VendorManager.Vendor';
		public $name = 'VendorAuth';
		public $fields = array('email','password','keep_login');
		public $components = array('Session', 'RequestHandler','Auth','Cookie');
		public static $sessionKey = 'VendorAuth';
		public $results = array();
		public $params = array() , $redirect = array();
		public $request;
		public $response;
		public $controller;
		public $deny_action = array();
		public $allow_action = array('login');
		public $activate_account_action = array();
	
	
	public function startup(Controller $controller){
		
		$this->request = $controller->request;
		$this->response = $controller->response;
		$this->controller = $controller;
		
		/*$user = $this->Cookie->read('rememberMe'); For Keep login
		$user_name = $user['user'];
		$pass = $user['pass'];
		if(!empty($user_name)){ // For checking cookie
			self::check_user();
		}*/
		//print_r($this->Session->read(self::$sessionKey));
		
		
		self::_setvar();
		self::_load();
		
	}
	public function login($action=null){

		$conditions = array();
		$model_name = self::_ext_model($this->model_use);
		
		$conditions[$model_name.'.'.$this->fields[0]] = $this->request->data[$model_name][$this->fields[0]];
		$conditions[$model_name.'.'.$this->fields[1]] = Security::hash(Configure::read('Security.salt').$this->request->data[$model_name][$this->fields[1]]);
		if(empty($conditions)){
			return false;
		}
		
		
		
		foreach($this->userScope as $field=>$value){
			$conditions[$field] = $value;
		}
		
		$Model = ClassRegistry::init($this->model_use);
		
		$criteria = array();
		$criteria['fields'] = array('*');
		$criteria['conditions'] = $conditions;
		
		$results = $Model->find('first',$criteria);
		
		if(!empty($results)){
			
			/**check for vendor approval, active, and payment details block**/
			if($results['Vendor']['approval']=='1' && $results['Vendor']['active']=='1'  && ($results['Vendor']['payment_status']=='1' || $results['Vendor']['account_type']=='0')){
				$this->updateMemberSession($results['Vendor']);
				if($this->Session->check($this->name.'.redirect')){
					$this->controller->redirect($this->Session->read($this->name.'.redirect'));
				}else{
					$this->controller->redirect($this->loginRedirect);
				}
			}
			/**end for vendor approval, active, and payment details block**/
			
			/**check for vendor approval, and payment details block, if payment not done redirect to payment gateway**/
			else if($results['Vendor']['approval']=='1' && $results['Vendor']['active']=='1' && $results['Vendor']['payment_status']!='1'){
				$this->updateMemberSession($results['Vendor']);
				$this->controller->redirect(array('plugin'=>'vendor_manager','controller'=>'payments', 'action' => 'paynow'));	
			}
			/**end of above else if statement**/
			
			/**below code is used to display error message if vendor not approved**/
			else{
				$this->Session->setFlash($this->messages['admin_approv'],'default',array(),'login_error');
				return false;
				//$this->redirect(array('controller'=>'vendors', 'action' => 'registration'));
			}	/**end of else statement**/
			
			
			
			
			
			
			/*
			if(isset($this->request->data[$model_name][$this->fields[2]]) && $this->request->data[$model_name][$this->fields[2]]=='1')
			{				
				$time=30*24*3600;
				$this->Cookie->write('rememberMe',array('user'=>$this->request->data[$model_name][$this->fields[0]],'pass'=>$this->request->data[$model_name][$this->fields[1]]), true, $time);				
			}			
			
			if($this->Session->check($this->name.'.redirect')){
					
				$this->controller->redirect($this->Session->read($this->name.'.redirect'));
			}else{
				$this->controller->redirect($this->loginRedirect);
			}
			*/
			
			
		}else{		
			//unset($criteria['conditions']['Vendor.active']);
			//$vendor_status=$Model->find('count',$criteria);
			 
			$this->Session->setFlash($this->messages['auth_fail'],'default','','login_error');
			$this->controller->redirect($this->logoutRedirect);
		}
		
		
	}
	public function check_user(){
		$conditions = array();
		$model_name = self::_ext_model($this->model_use);
		
		$user = $this->Cookie->read('rememberMe');
		$user_name = $user['user'];
		$pass = $user['pass'];
		
		$conditions[$model_name.'.'.$this->fields[0]] = $user_name;
		$conditions[$model_name.'.'.$this->fields[1]] = Security::hash(Configure::read('Security.salt').$pass);
		if(empty($conditions)){
			return false;
		}
		
		
		
		foreach($this->userScope as $field=>$value){
			$conditions[$field] = $value;
		}
		
		$Model = ClassRegistry::init($this->model_use);
		
		$criteria = array();
		$criteria['fields'] = array('*');
		$criteria['conditions'] = $conditions;
		
		$results = $Model->find('first',$criteria);
		if(!empty($results)){
			$this->Session->write(self::$sessionKey,$results);
		}
	}
	
	private function _check_payment_status(){
		if($this->results[$this->name]['payment_status']!=1 && 
		$this->params['action']!="make_payment" 
		&&$this->params['action']!="payment_process" && 
		$this->params['action']!="editProfile" && 
		$this->params['action']!="logout" && $this->params['action']!="vendor_list" && $this->params['action']!="activities" &&  $this->params['plugin']!="payment_manager" &&  $this->params['action']!="return_payment"  && $this->params['action']!="notify_payment"  && $this->params['action']!="cancel_payment" && $this->params['action']!="changepassword" ){
			$this->controller->redirect(array('plugin'=>'vendor_manager','controller'=>'payments', 'action' => 'make_payment'));	
			
		}
	}
	private function _load(){
		if(isset($this->params['admin']) && $this->params['admin']){
			return;
		}
		$model = self::_ext_model($this->model_use);
		$this->results = $this->Session->read(self::$sessionKey);
	
		if(!empty($this->results[$this->name])) {
			$this->id = $this->results[$this->name]['id'];
			$this->Session->delete('VendorAuth.redirect');
			if($this->results['VendorAuth']['account_type']!=0){
				self::_check_payment_status();
			}
		}
		else {
			if(in_array($this->params['action'],$this->deny_action)){
				if(!empty($this->loginAction)){
					$this->Session->setFlash($this->messages['direct_access'],'default','','login_error');
					$this->Session->write($this->name.'.redirect',$this->redirect);
					$this->controller->redirect($this->loginAction);
				}else{
					$this->Session->delete('VendorAuth.redirect');
				}
			}
		}
	}
	private function _ext_model($model=null){
		$data = explode('.',$model);
		return array_pop($data);
	}
	private function _setvar(){
		$this->params = $this->controller->request->params;
		$this->redirect = array('controller'=>$this->params['controller'],'action'=>$this->params['action'],'pass'=>$this->params['pass']);
	}
	public function id(){
		$vendor_id=(!empty($this->id))?$this->id:null;
		return $vendor_id;
	}
	public function updateMemberSession($detail = array()){
		$data[$this->name] = $detail;
		$this->removeMemberSession();
		$this->Session->write(self::$sessionKey,$data);
	}
	public function getActiveField($field){
		 $supplier=$this->controller->Session->read(self::$sessionKey);
		$model_name = self::_ext_model($this->model_use);
		return $supplier[$model_name][$field];
		
	}
	public function logout($redirect = array()){
		$this->removeMemberSession();
		 
		if(empty($redirect)){
			$redirect = $this->logoutRedirect;
		}
		$this->id=null;
		$this->Session->setFlash($this->messages['logout'],'default','','login_error');
		$this->controller->redirect($redirect);
		
		
	}
	public function removeMemberSession(){
		$this->Session->delete(self::$sessionKey);
	}
}
?>
