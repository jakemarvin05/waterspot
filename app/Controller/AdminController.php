<?php
class AdminController extends AppController {
    public $uses = array('User');
    var $components = array('Email');
    
    public function beforeFilter(){
		parent::beforeFilter();
		$this->title_for_layout = $this->setting['site']['site_name'];
		$this->Auth->deny('home','adminprofile');
	}
    
    public function index(){
        $this->layout='';
         if($this->Auth->user('id')){
			$this->redirect($this->Auth->redirect());
		}
        if ($this->request->is('post')) {
			if ($this->Auth->login()) {
					$this->redirect($this->Auth->redirect());
			} else {
				$this->Session->setFlash(__('Invalid username or password, try again'), 'default', array(), 'auth');
			}
        }
    }
    
    public function home(){
		$this->layout = 'admin';
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/home'),
			'name'=>'Home'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/home'),
			'name'=>'Home'
		);
		$this->loadModel('Page');
		$pages=$this->Page->find('all',array('limit'=>5,'order'=>array('Page.id'=>'desc')));
		$this->set('pages',$pages);
		$this->loadModel('Vendor');
		$vendors=$this->Vendor->find('all',array('limit'=>5,'order'=>array('Vendor.id'=>'desc')));
		$this->set('vendors',$vendors);
		$this->loadModel('Member');
		$members=$this->Member->find('all',array('limit'=>5,'order'=>array('Member.id'=>'desc')));
		$this->set('members',$members);
		// for booking list 
		$this->loadModel('BookingSlot');
		$this->loadModel('Booking');
		$criteria = array();
			$criteria['fields']= array('Booking.*','BookingOrder.booking_date','BookingOrder.start_date');
			$criteria['joins'] = array(
				array(
					'table' => 'booking_orders',
					'alias' => 'BookingOrder',
					'type' => 'LEFT',
					'conditions' => array('BookingOrder.ref_no = Booking.ref_no')
				) 
                
			);
			$criteria['group']= array('BookingOrder.ref_no');
			$criteria['order']= array('Booking.ref_no'=>'DESC');
			$criteria['limit']= 5;
			$booking_details=$this->Booking->find('all', $criteria);
			$this->set('booking_details',$booking_details);
    }

	public function logout() {
		$this->redirect($this->Auth->logout());
	}

	public function adminprofile($id=null){
		$this->layout='admin';
		if(!empty($this->request->data)){
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
		$this->request->data=$this->User->read(null,$this->Session->read('Auth.User.id'));
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/home'),
			'name'=>'Home'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/settings/site'),
			'name'=>'Admin'
		);
	}
	public function ajax_check_validation(){
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

	private function __mail_send($mail_id=null,$user=null){
		$this->loadModel('MailManager.Mail');
		$mail=$this->Mail->read(null,$mail_id);
		$heading=$mail['Mail']['heading'];
		$linkmerge=$this->setting['site']['site_url'].'/admin/passwordurl/'.$user['User']['passwordurl'];
		$body=str_replace('{NAME}',$user['User']['name'],$mail['Mail']['mail_body']);
		$body=str_replace('{url}',$linkmerge,$body);
		$body=str_replace('{USERNAME}',$user['User']['username'],$body);     
		$email = new CakeEmail();
		$email->to($user['User']['email']);
		$email->subject($mail['Mail']['mail_subject']);
		$email->from($user['User']['email']);
		$email->emailFormat('html');
		$email->template('default');
		$email->viewVars(array('data'=>$body,'logo'=>$this->setting['site']['logo'],'url'=>$this->setting['site']['site_url']));
		$email->send();
	}

    public function resetpassword(){
		$this->layout='';
		if(!empty($this->request->data)){
			//echo '<pre>';print_r($this->request->data);die;
			$user = $this->User->find('first',array('conditions'=>array('User.email'=>$this->request->data['User']['email'])));
			if(!empty($user)){
				$user['User']['passwordurl'] = md5($this->RandomString());
				unset($user['User']['password']);
				$this->User->create();
				$this->User->save($user,array('validate'=>false));
				$this->__mail_send(4,$user);
				$this->Session->setFlash('Mail with password reset link will be sent to '.$user['User']['email'].'. Please follow the instructions to reset your password');
				$this->redirect(array('controller'=>'admin','action'=>'resetpassword'));
			}
			else{
				$this->Session->setFlash('Sorry! We cannot complete your request, the email address you entered is not registered with us. Please try again using a different email address. We are sorry for the inconvenience.','default','','error');
			}
		}
		else{
			//$this->Session->setFlash('Sorry! We cannot complete your request,please enter email address.','default','','error');
		}
	}
	
	public function passwordurl($str=null){
		$this->layout='';
		$checklink = $this->User->find('first',array('conditions'=>array('User.passwordurl'=>$str)));
		if(!empty($checklink)){
			if(!empty($this->request->data)){
				$user = $this->User->find('first',array('conditions'=>array('User.passwordurl'=>$str)));
				if(!empty($user)){	
					if($this->request->data['User']['password']==$this->request->data['User']['password2']){
						$this->request->data['User']['id']=$user['User']['id'];
						$this->request->data['User']['passwordurl']='';
						$this->User->create();
						$this->User->save($this->request->data);
						$this->redirect(array('controller'=>'admin','action'=>'index'));
						$this->Session->setFlash('Password has been changed succesfully');
						
					}else{
						$this->Session->setFlash('password and confirm password not match, try again','default','msg','error');
					}
				}else{
					$this->Session->setFlash('Invalid link or password, try again','default','msg','error');
				}
			}
			$this->set('str',$str);
		}else{
			$this->redirect(array('controller'=>'admin','action'=>'index'));
			$this->Session->setFlash('Invalid link, try again','default','msg','error');
		}
	}
}
 
?>
