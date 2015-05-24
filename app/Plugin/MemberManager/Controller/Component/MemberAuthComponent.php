<?php
App::uses('Component', 'Controller');
App::uses('Router', 'Routing');
App::uses('Security', 'Utility');
App::uses('Debugger', 'Utility');
App::uses('CakeSession', 'Model/Datasource');

class MemberAuthComponent extends Component{
	public $members = array() , $loginAction = array() , $loginRedirect = array(), $logoutRedirect = array() , $userScope = array() ,  $messages = array('direct_access'=>'','auth_fail'=>'');
	public $id = null;
	public $model_use = 'MemberManager.Member';
	public $fields = array('email_id','password');
	public $components = array('Session', 'RequestHandler','Auth','Cookie');
	public  $name = 'MemberAuth';
	public static $sessionKey = 'MemberAuth';
	public $results = array();
	public $params = array() , $redirect = array();
	public $request;
	public $response;
	public $controller;
	public $deny_action = array();
	
	public function startup(Controller $controller){
		$this->request = $controller->request;
		$this->response = $controller->response;
		$this->controller = $controller;
		$user = $this->Cookie->read('keep_me_login');
		if(!empty($user)) {
			self::check_user();
		}
		self::_setvar();
		self::_load();
	}
	
	public function login(){
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
			$this->updateMemberSession($results[$model_name]);
			if(isset($this->request->data[$model_name]['keep_me_login']) && $this->request->data[$model_name]['keep_me_login']=='1') {
				$time=30*24*3600;
				$this->Cookie->write('keep_me_login',array('email_id'=>$this->request->data[$model_name][$this->fields[0]],'pass'=>$this->request->data[$model_name][$this->fields[1]]), true, $time);
			}	
			// redirect after login	
			$this->controller->redirect($this->loginRedirect);
		}else{
			// for deactive message
			unset($criteria['conditions']['Member.active']);
			$member_status=$Model->find('count',$criteria);
			if($member_status==1){
				$this->Session->setFlash($this->messages['auth_deactivate'],'default','','error');
			}else{
				$this->Session->setFlash($this->messages['auth_fail'],'default','','error');
			}
				$refer_url=$this->loginRedirect;
				$this->controller->redirect(array('plugin'=>'member_manager','controller'=>'members','action'=>'registration','?'=>array('redirect_url'=>$refer_url)));	
		}
	}
	
	public function check_user() {
		$conditions = array();
		$model_name = self::_ext_model($this->model_use);
		$user = $this->Cookie->read('keep_me_login');
		$email = $user['email_id'];
		$pass = $user['pass'];		
		$conditions[$model_name.'.'.$this->fields[0]] = $email;
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
			$this->updateMemberSession($results[$model_name]);
		}
	}
	
	private function _load(){
		$model = self::_ext_model($this->model_use);
		$this->results = $this->Session->read(self::$sessionKey);
		if(!empty($this->results[$this->name])){
			$this->id = $this->results[$this->name]['id'];
			$this->Session->delete('MemberAuth.redirect');
			$this->Session->delete('redirect_url');
		}
		else{
			if(in_array($this->params['action'],$this->deny_action)){
				if(!empty($this->loginAction)){
					$this->Session->setFlash($this->messages['direct_access'],'default','','error');
					$this->Session->write('MemberAuth.redirect',$this->redirect);
					$this->controller->redirect($this->loginAction);
				}else{
					$this->Session->delete('MemberAuth.redirect');
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
		return $this->id;
	}
	
	public function logout(){
		$this->Session->delete(self::$sessionKey);
		$this->Cookie->delete('keep_me_login'); //Only for keep loged in
		$this->Session->setFlash(__('You\'re now logged out', true));
		$this->controller->redirect($this->logoutRedirect);
	}
	
	public function updateMemberSession($detail = array()){
		$data[$this->name] = $detail;
		$this->removeMemberSession();
		$this->Session->write(self::$sessionKey,$data);
	}
	
	public function getActiveField($field){
		 $member=$this->controller->Session->read(self::$sessionKey);
		$model_name = self::_ext_model($this->model_use);
		return $member[$model_name][$field];
	}
	
	public function removeMemberSession(){
		$this->Session->delete(self::$sessionKey);
	}
}
?>
