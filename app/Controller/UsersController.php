<?php
// app/Controller/UsersController.php
class UsersController extends AppController {
	var $helpers = array('Html', 'Form');
    var $components = array('Email');
    public $uses = array('User');

    public function beforeFilter() {
        parent::beforeFilter();
     //   $this->Auth->allow('add');
    }

    public function index() {
        $this->User->recursive = 0;
        $this->set('users', $this->paginate());
    }

    public function view($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        $this->set('user', $this->User->read(null, $id));
    }
    public function login() {
        $this->layout='';
        $this->redirect(array('controller'=>'admin','action'=>'index'));
        $this->title_for_layout .= " Login Panel";
        if ($this->request->is('post')) {
                if ($this->Auth->login()) {
                        $this->redirect($this->Auth->redirect());
                } else {
                        $this->Session->setFlash(__('Invalid username or password, try again'), 'default', array(), 'auth');
                }
        }
    }

    public function add() {
        if ($this->request->is('post')) {
            $this->User->create();
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('The user has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The user could not be saved. Please, try again.'));
            }
        }
    }

    public function edit($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('The user has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The user could not be saved. Please, try again.'));
            }
        } else {
            $this->request->data = $this->User->read(null, $id);
            unset($this->request->data['User']['password']);
        }
    }
    public function logout() {
		$this->redirect(array('controller'=>'admin','action'=>'logout'));
		//echo $this->Auth->user('id');die;
                print_r($this->Auth->logout());
		$this->redirect($this->Auth->logout());
		
	}

   public function admin_login(){
       $this->redirect('/users/login');
   }
   
   
	public function delete($id = null) 
	{
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->User->delete()) {
			$this->Session->setFlash(__('User deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('User was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
    
     function admin_index($keyword=null)
    {
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
	$this->set('COUNTRY',Configure::read('COUNTRY'));
    }
    
    function profile()
   {
	    $this->layout='admin';
	  
	   if(!empty($this->request->data) && self:: __validate())
	   {
		 
			$this->request->data['User']['id']=$this->Session->read('Auth.User.id');
		    $this->User->create();
			$this->User->save($this->request->data);
			if($this->request->data['User']['id']!=''){
				$this->Session->setFlash('Admin profile succesfully updated');
				}
			else{
				$this->Session->setFlash('Admin profile add succesfully ');
				}
			
			
		   }
		

		   $this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/home'),
			'name'=>'Home'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/settings/site'),
			'name'=>'Admin'
		);
	   
	}
       
    
    function admin_add($id=null){
	   self::__manageuser($id);
    }
    
    private function __manageuser($id=null){
	if(!empty($this->request->data)){
		//echo "<pre>";print_r($this->request->data);die;
	    if($this->request->data['User']['id']==''){
		//$data = $this->User->find('first',array('fields'=>'MAX(User.id) as id'));
		//$user_max_id = $data[0]['id'] + 1;
		if($this->request->data['User']['id']==''){
		    $this->request->data['User']['password2']=$this->RandomString();
		    $this->request->data['User']['password']=$this->request->data['User']['password2'];
		    
		    $this->request->data['User']['role'] = 'subadmin';
		    $this->request->data['User']['status'] = 1;
		}
		
		
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
	    //echo "<pre>";print_r($this->request->data);die;
	    $this->User->create();
	    if($this->User->save($this->request->data)){		
		$user_id = $this->User->id;
		if($this->request->data['User']['id']==''){
			$this->__send_mail(5,$this->request->data);//Send mail to New Subadmin 
			$this->Session->setFlash('Sub Admin has been added successfully');
			$this->redirect(array('controller'=>'users','action'=>'permission',$user_id));
		}else{
			$this->Session->setFlash('Sub Admin has been updated successfully');
			 $this->redirect(array('controller'=>'users','action'=>'index'));
		}
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
   
    function admin_permission($id=null)
    { 
	$modules = array();
	//if ($handle = opendir(realpath(dirname(__FILE__)))) {
	//	while (false !== ($entry = readdir($handle))) {			   
	//		if ($entry != "." && $entry != ".." && $entry!="AppController.php" && $entry!="UsersController.php" && $entry!="AdminController.php" && $entry!="SitesController.php" && $entry!="CronController.php" && $entry !="Component") {
	//		    $ext = pathinfo($entry, PATHINFO_EXTENSION);
	//		    if($ext=='php')
	//		    {
	//			$modules[] = array('file'=>trim($entry,".php"),'name'=>str_replace('Controller.php',' Manager',$entry));
	//		    }
	//		}
	//	}
	//	closedir($handle);
	//}
	$file=array('PagesController','GalleriesController','SlidesController','MailsController');
	foreach($file as $files)
	{
	    $modules[] = array('file'=>$files,'name'=>str_replace('Controller',' Manager',$files));
	}
	

	
	if(!empty($this->data))
	{
		
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
				$this->redirect(array('action'=>'permission',$this->data['User']['user_id']));
			}
	}
	
	$user = $this->User->read(null,$id);
	$this->request->data['content'] = json_decode($user['User']['permission'],true);
	$this->set('user_id',$id);
	$this->set('user_name',$user['User']['name'].' '.$user['User']['lname']);
	
	$this->set('modules',$modules);
	
    }
    
    function ajax_check_validation()
    {
	$this->autoRender = false;
	$this->User->set($this->request->data);
	
	$error = 0;
	
	if ($this->User->validates()) {
	    $error = 0;
	    
	}else{
	    $error = 1;
	    $message = __('Please fill required fields', true);
	}	
	 
	$User= $this->User->validationErrors;
	$errors = compact('User');
	$data = compact('message', 'errors','error');
	echo json_encode($data);
    }
    
    private function __send_mail($mail_id=null,$user=null)
    {
	$this->loadModel('MailManager.Mail');
	
	$mail=$this->Mail->read(null,$mail_id);
	$body=str_replace('{NAME}',$user['User']['name'].' '.$user['User']['lname'],$mail['Mail']['mail_body']);
	$body=str_replace('{USER}',$user['User']['username'],$body);
	$body=str_replace('{PASSWORD}',$user['User']['password2'],$body);
	
	$email = new CakeEmail();

	
	$email->to(trim($user['User']['email']));
	$email->subject($mail['Mail']['mail_subject']);
	$email->from(trim($this->setting['site']['site_contact_email']),trim($mail['Mail']['mail_from']));
	$email->emailFormat('html');
	$email->template('default');
	$this->mail_data['data'] = $body;
	$email->viewVars($this->mail_data);
	$email->send();
    }
    
    function admin_delete()
	{
		
		$ans="0";
		
		$data=$this->data['User'];
		array_splice($data,1,1); 
		foreach($data as $value)
		{
			if($value!='0')
			{
			
				if($this->data['User']['action']=='Publish')
				{
					$users=$this->User->read(null,$value);
					$users['User']['status']=1;
					
					if(!empty($users['User']['id']))
					{
					$this->User->create();
					$this->User->save($users);
					}
					$ans="1";
				}
				if($this->data['User']['action']=='Unpublish')
				{
					$users=$this->User->read(null,$value);
					$users['User']['status']=0;
					if(!empty($users['User']['id']))
					{
					$this->User->create();
					$this->User->save($users);
					
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
		
		switch($ans)
		{	
		case 1:
		$this->Session->setFlash(__('User has been '.$this->data['User']['action'].'ed successfully', true));
		break;
	        
		case 2:
		$this->Session->setFlash(__('User has been '.$this->data['User']['action'].'d successfully', true));
		break;
		
		default:
		$this->Session->setFlash(__('User Select any Image', true),'default','','error');
			
		}
		$this->redirect(array('action'=>'index'));		
	}
	
	function RandomString()
		{
			$characters = '$&@!0123456789abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$randstring = '';
			for ($i = 0; $i < 10; $i++) {
				$randstring .= $characters[rand(0, strlen($characters))];
			}
			return $randstring;
		}
}

?>
