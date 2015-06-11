<?php 
Class AccountsController extends VendorManagerAppController{
	public $uses = array('VendorManager.Vendor');
	public $components=array('Email','VendorManager.VendorAuth','Session');
	
	function editProfile(){
		$id=$this->VendorAuth->id;
		// email unset when edit.
		unset($this->request->data['Vendor']['email']);
		if(!empty($this->request->data) && $this->validation() && $id){
			$this->request->data['Vendor']['id'] = $id;
			$this->request->data['Vendor']['updated_at']=date('Y-m-d H:i:s');
			$this->Vendor->create();
			$this->Vendor->save($this->request->data);
			if($this->Vendor->save($this->request->data)) {
				$vendors = $this->Vendor->read(null,$id);
				$this->VendorAuth->updateMemberSession($vendors['Vendor']);	
				$this->Session->setFlash(__('Vendor has been updated successfully'));
				$this->redirect(array('controller'=>'accounts','action'=>'editProfile'));
				}else {
					unset($this->request->data['Vendor']['id']);
				}
			}else{
				if($id!=null){
					$this->request->data = $this->Vendor->read(null,$id);
				}else{
					$this->request->data = array();
			}
		}
		array_push(self::$script_for_layout, 'VendorManager.jQuery-custom-input-file.js','VendorManager.jquery.upload.js','frontEditor/ckeditor.js');
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/'),
			'name'=>'Home'
		    );
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/vendor/dashboard/'),
			'name'=>'Dashboard'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/vendor_manager/accounts/editProfile'),
			'name'=>'Edit Profile '
		);	 
	}
	
	function change_email(){
		// get vendor id whose is login
		$id = $this->VendorAuth->id;
		if(!empty($this->request->data) && $this->validation()){
			$this->Vendor->id = $id;
			$this->Vendor->saveField('email',$this->request->data['Vendor']['email']);
			$this->Session->setFlash('Your email id has been changed successfully.');
			$this->redirect(array('controller'=>'accounts','action'=>'change_email'));
		}
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/'),
			'name'=>'Home'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/vendors/dashboard/'),
			'name'=>'Dashboard'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/vendor_manager/accounts/change_email'),
			'name'=>'Change Email '
		);
	}
	
	function changepassword(){
		$id=$this->VendorAuth->id;
		if (!empty($this->request->data)){
			$data = $this->Vendor->read(null,$id);
			//echo"<pre>";print_r($data);die;	
			if($data['Vendor']['password']==(Security::hash(Configure::read('Security.salt').$this->request->data['Vendor']['old_password']))) {
				$data['Vendor']['password'] = Security::hash(Configure::read('Security.salt').$this->request->data['Vendor']['new_password']);
				$this->Vendor->create();
				$this->Vendor->save($data);
				$this->Session->setFlash('Your password changed successfully.');
				$this->redirect(array('controller'=>'accounts','action'=>'changepassword'));
			}else{
				$this->Session->setFlash('Your current password does not match.');
				$this->redirect(array('controller'=>'accounts','action'=>'changepassword'));	
			}
		}
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/'),
			'name'=>'Home'
		    );
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/vendor/dashboard/'),
			'name'=>'Dashboard'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/accounts/changepassword'),
			'name'=>'Change Password'
		);	  
	}
	
	private function __mail_send($mail_id=null,$vendor=null){
		$this->loadModel('MailManager.Mail');
		$mail=$this->Mail->read(null,$mail_id);
		$heading=$mail['Mail']['heading'];
		$linkmerge=$this->setting['site']['site_url'].'/accounts/passwordurl/'.$vendor['Vendor']['passwordurl'];
		$body=str_replace('{NAME}',$vendor['Vendor']['fname'],$mail['Mail']['mail_body']);
		$body=str_replace('{url}',$linkmerge,$body);
		$body=str_replace('{EMAIL}',$vendor['Vendor']['email'],$body);    
		$email = new CakeEmail();
		$email->to($vendor['Vendor']['email']);
		$email->subject($mail['Mail']['mail_subject']);
		$email->from($this->setting['site']['site_contact_email']);
		$email->emailFormat('html');
		$email->template('default');
		$email->viewVars(array('data'=>$body,'logo'=>$this->setting['site']['logo'],'url'=>$this->setting['site']['site_url']));
		$email->send();
    }
    
	function reset_password(){
		$this->layout='';
		  if(!empty($this->request->data) && $this->validation()){
				$vendor = $this->Vendor->find('first',array('conditions'=>array('Vendor.email'=>$this->request->data['Vendor']['email'])));
				if(!empty($vendor['Vendor']['id'])) {
					unset($vendor['Vendor']['password']);
					$vendor['Vendor']['passwordurl'] = Security::hash(Configure::read('Security.salt').$this->RandomString());	
					$this->__mail_send(10,$vendor);
					$this->Vendor->create();
					$this->Vendor->save($vendor,array('validate'=>false));
					$this->Session->setFlash('Mail with password reset link will be sent to '.$vendor['Vendor']['email'].'. Please follow the instructions to reset your password');
					$this->redirect(array('controller'=>'accounts','action'=>'resetpassword'));
				}else{	   
					$this->Session->setFlash('The email address you entered is not registered with us. Please try again using a different email address.','default','','error');
					$this->redirect(array('controller'=>'accounts','action'=>'resetpassword'));
				}
			}
			else
			{
				$this->Session->setFlash('Sorry! We cannot complete your request,please enter email address.','default','','error');
				$this->redirect(array('controller'=>'accounts','action'=>'resetpassword'));
				}
			$this->breadcrumbs[] = array(
			'url'=>Router::url('/'),
			'name'=>'Home'
			);
			$this->breadcrumbs[] = array(
					'url'=>Router::url('/vendor_manager/accounts/resetpassword'),
					'name'=>'Reset Password'
			);	  	
	}
		
	function RandomString(){
		$characters = '$&@!0123456789abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randstring = '';
		for ($i = 0; $i < 15; $i++) {
			$arr1 = str_split($characters);
			$randstring .= $arr1[rand(0, $i)];
		}
		return $randstring;
	}
	
	function resetpassword(){
		$this->breadcrumbs[] = array(
		'url'=>Router::url('/'),
		'name'=>'Home'
		);
		$this->breadcrumbs[] = array(
				'url'=>Router::url('/vendor_manager/accounts/reset_password'),
				'name'=>'Reset Password'
		);	  
	}
	
	function passwordurl($str=null){
		$checklink= $this->Vendor->find('first',array('conditions'=>array('Vendor.passwordurl'=>$str)));
		if(!empty($checklink)){
			if(!empty($this->request->data) && $this->validation()){
				$vendor = $this->Vendor->find('first',array('conditions'=>array('Vendor.passwordurl'=>$str)));
				if($this->request->data['Vendor']['password']==$this->request->data['Vendor']['password2']){
					$this->request->data['Vendor']['id']=$vendor['Vendor']['id'];
					$this->request->data['Vendor']['passwordurl']='';
					$this->request->data['Vendor']['password']=Security::hash(Configure::read('Security.salt').trim($this->request->data['Vendor']['password']));	
					$this->Vendor->create();
					$this->Vendor->save($this->request->data,array('validate'=>false));
					$this->Session->setFlash('Password has been changed succesfully ');
					$this->redirect(array('controller'=>'vendors','action'=>'registration'));
				}else{
					$this->Session->setFlash('New password and confirm password does not match., try again.','default','msg','error');
				}
			}
			$this->set('str',$str);
		}else{
			$this->redirect(array('controller'=>'vendors','action'=>'registration'));
		}
	}
		
	function validation($action=null){
		if($this->request->data['Vendor']['form-name']=='Change-Password'){
			$this->request->data['Vendor']['Password']=Security::hash(Configure::read('Security.salt').$this->request->data['Vendor']['old_password']);
			$this->Vendor->setValidation('Change-Password');
		}else if($this->request->data['Vendor']['form-name']=='change_email'){
			$this->Vendor->setValidation('change_email');
		}else if($this->request->data['Vendor']['form-name']=='RegistrationForm'){
			$this->Vendor->setValidation('Register');
		}else if($this->request->data['Vendor']['form-name']=='ChangeProfile'){
			unset($this->request->data['Vendor']['email']);
			$this->Vendor->setValidation('Register');
		}
		else if($this->request->data['Vendor']['form-name']=='ResetPasswordForm') {
			$this->Vendor->setValidation('ResetPassword');
		}
		else if($this->request->data['Vendor']['form-name']=='PasswordUrlForm'){
			$this->Vendor->setValidation('PasswordUrl');
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
	
	function images_handle(){
		$this->autoRender = false;
		$this->loadModel('VendorManager.Vendor');
		$vendor_id=$this->VendorAuth->id();
		App::uses('ImageResizeHelper', 'View/Helper');
		$ImageComponent = new ImageResizeHelper(new View());
		if(!empty($_FILES['uploadFile'])){
			$destination=Configure::read('VendorProfile.SourcePath');
			$image_name=self::_manage_image($_FILES['uploadFile']);
			$imgArr = array('source_path'=>$destination,'img_name'=>$image_name,'width'=>150,'height'=>150);
			// delete image
			$profile_image = $this->Vendor->find('first',array('fields'=>array('Vendor.image'),'conditions'=>array('Vendor.id'=>$vendor_id)));
			if(!empty($profile_image['Vendor']['image'])){
				unlink($destination.$profile_image['Vendor']['image']);
			}
			$this->request->data['Vendor']['id'] = $vendor_id;	
			$this->request->data['Vendor']['image'] = $image_name;	
			$this->request->data['Vendor']['updated_at']=date('Y-m-d H:i:s');
			// saving vendor profile image
			$this->Vendor->create();
			$this->Vendor->save($this->request->data);
			echo $this->webroot.'img'.DS.$ImageComponent->ResizeImage($imgArr);
		}
	}
	
	function image_delete(){
		$this->autoRender = false;
		unlink(Configure::read('VendorProfile.SourcePath').$_POST['image']);
	}
	
	private function _manage_image($image = array()) {
        $vendor_id=$this->VendorAuth->id();
		$destination=Configure::read('VendorProfile.SourcePath');
		if(!file_exists($destination)){
			mkdir($destination, 0777);
			$dir = new Folder();
			$dir->chmod($destination, 0777, true, array());	
		}
		if ($image['error'] > 0) {
            return null;
        } else {
            $existing_image = array();
            if ($image['error'] > 0) {
                return $image;
            } else {
                $ext = explode('.', $image['name']);
                $image_name = time() . '_' . $vendor_id .$ext[0]. '.' . array_pop($ext);
                move_uploaded_file($image['tmp_name'], $destination . $image_name);
                if (!empty($existing_image)) {
                    @unlink($destination . $existing_image['image']);
                }
                return $image_name;
            }
        }
    }
}
?>
