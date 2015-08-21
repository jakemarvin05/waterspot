<?php
class UsersController extends SubadminManagerAppController {
	var $uses = array('SubadminManager.User');
	var $helpers = array('Html', 'Form');
	var $components = array('Email');
	
	function admin_index($keyword=null){
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/home'),
			'name'=>'Home'
			);
			$this->breadcrumbs[] = array(
					'url'=>Router::url('/admin/subadmin_manager/users'),
					'name'=>'Manage User'
			);
	//$this->paginate['limit']=Configure::read('ADMIN_SIDE_PAGGING');
		$search_keyword=array();
		$condition=null;
		if($keyword!=null){
			$this->data['User']['keyword']=$keyword;
		}
		$condition['User.role <>'] = 'admin';		
		if(!empty($this->data) and $this->data['User']['keyword']!='Name,Username or Email')
		{			
			$condition['OR']['User.username like']= '%'.$this->data['User']['keyword'].'%';
			$condition['OR']['User.emailId like']= '%'.$this->data['User']['keyword'].'%';
			$condition['OR']['User.firstName like']= '%'.$this->data['User']['keyword'].'%';
			$search_keyword=array('url'=>array($this->data['User']['keyword']));
		}
		$this->User->recursive = 0;
		$this->set('search_keyword',$search_keyword);
		$users=$this->paginate("User",$condition);		
		$this->set('users', $users);
		$this->set('manager', "User");
	}
    
	function validation($action=null){
		
		if($this->request->data['User']['form-name']=='ResetPasswordForm'){
			$this->User->setValidation('ResetPassword');
		}
		if($this->request->data['User']['form-name']=='AdminLogin'){
			$this->User->setValidation('AdminLogin');
		}
		if($this->request->data['User']['form-name']=='NewUserForm'){
			$this->User->setValidation('NewUserForm');
		}
		if($this->request->data['User']['form-name']=='ResetRegistrationPasswordForm'){
			$this->User->setValidation('ResetRegistrationPasswordForm');
		}
		if($this->request->data['User']['form-name']=='PasswordChange'){
			$this->User->setValidation('PasswordChangeAdmin');
		}
		if($this->request->data['User']['form-name']=='UserProfileUpdate'){
			$this->User->setValidation('UserProfileUpdate');
		}
		$this->User->set($this->request->data);
		$result = array();
		if ($this->User->validates()) {
			$result['error'] = 0;
		}else{
			$result['error'] = 1;
		}
		if($this->request->is('ajax')) {
			$this->autoRender = false;
			$result['errors'] = $this->User->validationErrors;
			$errors = array();
			foreach($result['errors'] as $field => $data){
				$errors['User'.Inflector::camelize($field)] = array_pop($data);
			}
			$result['errors'] = $errors;
			echo json_encode($result);
			return;
		}
		return (int)($result['error'])?0:1;
	} 
    
    function admin_add($id=null){
	   self::__manageuser($id);
    }
    
	private function __manageuser($id=null){
			$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/home'),
			'name'=>'Home'
			);
            $this->breadcrumbs[] = array(
                    'url'=>Router::url('/admin/subadmin_manager/users'),
                    'name'=>'Manage User'
            );
            $this->breadcrumbs[] = array(
                    'url'=>Router::url('/admin/subadmin_manager/users/add'),
                    'name'=>($id==null)?'Add User':'Update User'
            );		
		if(!empty($this->request->data)){
			if($this->request->data['User']['id']==''){
				$this->request->data['User']['passwordurl'] = md5($this->RandomString());
				$this->request->data['User']['role'] = 'subadmin';
				$this->request->data['User']['status'] = 1;
				$modules = array();
				if ($handle = opendir(realpath(dirname(__FILE__)))) {
					while (false !== ($entry = readdir($handle))) {
						if ($entry != "." && $entry != ".." && $entry!="AppController.php" && $entry!="UsersController.php" && $entry !="Component") {
							 $modules[trim($entry,".php")] = "0";
						}
					}
					closedir($handle);
				}
				if($this->request->data['User']['id']==''){
					$this->request->data['User']['created'] = date('Y-m-d H:i:s');
				}
				else{
					$this->request->data['User']['modified'] = date('Y-m-d H:i:s');
				}
					$this->request->data['User']['permission'] = json_encode($modules);
			}
			$this->User->create();
			$this->User->save($this->request->data,array('validate'=>false));
			$user_id = $this->User->id;
			if(!empty($user_id)){
				$this->__send_mail(17,$this->request->data);//Send mail to New Subadmin 
				$this->Session->setFlash('Sub Admin has been added successfully');
				$this->redirect(array('controller'=>'users','action'=>'permission',$user_id));
			}else{
				$this->Session->setFlash('Sub Admin has been updated successfully');
				 $this->redirect(array('controller'=>'users','action'=>'index'));
			}
		}
		if(empty($this->request->data) && $id!=null){
			$this->request->data = $this->User->read(NULL,$id);
		}
	}
    
    function admin_view($id=null){
	$this->layout='';
	$user = $this->User->read(null,$id);
	$this->set('user',$user);
    }
   
    function admin_permission($id=null) { 
		$this->breadcrumbs[] = array(
		'url'=>Router::url('/admin/home'),
		'name'=>'Home'
		);
		$this->breadcrumbs[] = array(
				'url'=>Router::url('/admin/subadmin_manager/users'),
				'name'=>'Manage User'
		);
		$this->breadcrumbs[] = array(
				'url'=>Router::url('/admin/subadmin_manager/users/add'),
				'name'=>'Manage Permission'
		);	
            
		$modules = array();
		$file=array('PagesController','SlidesController','MailsController');
		foreach($file as $files){
			$modules[] = array('file'=>$files,'name'=>str_replace('Controller',' Manager',$files));
		}

		if(!empty($this->data)){
			$user['User']['id'] = $this->data['User']['user_id'];
		
			if(isset($this->data['content'])){
				$user['User']['permission'] = json_encode($this->data['content']);
			}else{
				$user['User']['permission'] = json_encode(array());
			}
			$this->User->create();
			if($this->User->save($user))
			{
				$this->Session->setFlash('Permission has been saved successfully');
				$this->redirect(array('plugin'=>'subadmin_manager','controller'=>'users', 'action' => 'index'));
			}
		}
		$user = $this->User->read(null,$id);
		$this->request->data['content'] = json_decode($user['User']['permission'],true);
		$this->set('user_id',$id);
		$this->set('user_name',$user['User']['name'].' '.$user['User']['lname']);
		$this->set('modules',$modules);
    }
    
    private function __send_mail($mail_id=null,$user=null) {
		$this->loadModel('MailManager.Mail');
		$mail=$this->Mail->read(null,$mail_id);
		$linkmerge=$this->setting['site']['site_url'].'/admin/passwordurl/'.$user['User']['passwordurl'];
		$body=str_replace('{NAME}',$user['User']['name'],$mail['Mail']['mail_body']);
		$body=str_replace('{USERNAME}',$user['User']['username'],$body);
		$body=str_replace('{url}',$linkmerge,$body);
		$email = new CakeEmail();

		$email->to(trim($user['User']['email']));
		$email->subject($mail['Mail']['mail_subject']);
		$email->from($this->setting['site']['site_contact_email'],$from['Mail']['mail_from']);
		$email->emailFormat('html');
		$email->template('default');
		$email->viewVars(array('data'=>$body,'logo'=>$this->setting['site']['logo'],'url'=>$this->setting['site']['site_url']));
		$email->send();
    }
    
    function admin_delete() {
		$ans="0";
		$data=$this->data['User'];
		array_splice($data,0,2); 
		foreach($data as $value) {
			if($value!='0') {
				if($this->data['User']['action']=='Publish') {
					$users=$this->User->read(null,$value);
					$users['User']['status']=1;
					if(!empty($users['User']['id'])){
						$this->User->create();
						$this->User->save($users);
					}
					$ans="1";
				}
				if($this->data['User']['action']=='Unpublish'){
					$users=$this->User->read(null,$value);
					$users['User']['status']=0;
					if(!empty($users['User']['id']))
					{
						$this->User->create();
						$this->User->save($users,array('validate'=>false));
					}
				        $ans="1";
				}
				if($this->data['User']['action']=='Delete')
				{
					$this->User->delete($value);
					$ans="2";
				}
			}
		}
		switch($ans){	
			case 1:
			$this->Session->setFlash(__('User has been '.$this->data['User']['action'].'ed successfully', true));
			break;
			case 2:
			$this->Session->setFlash(__('User has been '.$this->data['User']['action'].'d successfully', true));
			break;
			
			default:
			$this->Session->setFlash(__('User Select any user', true),'default','','error');
		}
		$this->redirect(array('action'=>'index'));		
	}
	
	function RandomString(){
		$characters = '0123456789abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randstring = '';
		for ($i = 0; $i < 10; $i++) {
			$randstring .= $characters[rand(0, strlen($characters))];
		}
		return $randstring;
	}
}
?>
