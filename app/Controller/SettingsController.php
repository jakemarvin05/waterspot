<?php
class SettingsController extends AppController {
	public $uses = array('Setting');
	public $paginate = array();
    public $id = null;    
	public function adminprofile($id=null){
		$this->layout='admin';
		$this->loadModel('User');
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
	function admin_social(){
		$this->loadModel('Setting');
		if(!empty($this->request->data) && self::_validate_social_media(1)){
			foreach($this->request->data['Setting'] as $key => $value){
				if(is_array($value)){
					if($value['error']==0){
						$ext = explode(".",$value['name']);
						$name = explode("_",$key);
						
					}else{
						continue;
						////	$value;
					}
				}
				if($this->Setting->find('count',array('conditions'=>array('Setting.key'=>$key,'Setting.module'=>'social')))){
					$this->Setting->query("UPDATE `settings` SET `values`=\"$value\" , module=\"social\" WHERE `key`=\"$key\"");
				} else{
					$this->Setting->query("INSERT `settings` SET `values`=\"$value\"  , `key`=\"$key\" , module=\"social\"");
				}
				$this->Session->setFlash(__('links has been Saved Successfully'));
			}
			Cache::delete('cake_settings');
			$this->redirect(array('action'=>'social'));
		}
		if(empty($this->request->data)){
			$this->request->data['Setting'] = $this->Setting->find('list',array('fields'=>array('Setting.key','Setting.values')));
		}else{
			$data = $this->Setting->find('list',array('fields'=>array('Setting.key','Setting.values')));
			$this->request->data['Setting']['facebook'] = $data['facebook'];
			$this->request->data['Setting']['google_plus'] = $data['google_plus'];
		}
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/home'),
			'name'=>'Home'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/settings/site'),
			'name'=>'Site Setting'
		);
	}
	public function admin_imagesetting() {
		if(!empty($this->request->data)){
			if(self::_validate_image_setting(1)) {
				Cache::delete('cake_settings');
				foreach($this->request->data['Setting'] as $key => $value){
					$this->Setting->query("UPDATE `settings` SET `values`=\"$value\" WHERE `key`=\"$key\"");
				}
				$this->Session->setFlash('Gallery Image setting has been saved successfully');
				$this->redirect(array('action'=>'imagesetting'));
			} else {
				$data = $this->request->data;
			}
		} else {
			$data['Setting'] = $this->Setting->find('list',array('fields'=>array('Setting.key','Setting.values'),'conditions'=>array('Setting.module'=>'image')));
		}
		$this->set('data',$data);
				
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/home'),
			'name'=>'Home'
		);
		$this->breadcrumbs[] = array(
			'url'=>'',
			'name'=>'Site Image Setting'
		);
	}
    public function admin_allcachedelete () {
		$this->autoRender=false;
		$view_cache_path=APP . 'tmp' . DS . 'cache';
		$dir = opendir($view_cache_path);
		while(false != ($file = readdir($dir))) {
			if(($file != ".") and ($file != "..")){
				if (strpos($file,'cake') !== false) {
					unlink($view_cache_path.DS.$file);
				}
				else{
					self :: listFolderDeleteChache($view_cache_path.DS.$file); 
				}
				
			}
		}
		$this->Session->setFlash(__('All cache memory has been deleted'));
		$this->redirect(Controller::referer());
	} 
	public function admin_allserviceimagedelete () {
		$this->autoRender=false;
		$images_path=array(WWW_ROOT . 'img'.DS.'service_type'.DS.'temp',WWW_ROOT . 'img'.DS.'vendor_profile_images'.DS.'temp',WWW_ROOT . 'img'.DS.'service_images'.DS.'temp',WWW_ROOT . 'img'.DS.'service_images'.DS.'ajax'.DS.'temp',WWW_ROOT . 'img'.DS.'slide'.DS.'temp'); 
		foreach($images_path as $path) {
			$dir = opendir($path);
			while(false != ($file = readdir($dir))) {
				if(($file != ".") and ($file != "..")){
					 	unlink($path.DS.$file);
					}
					  
				}
			}
		$this->Session->setFlash(__('All temp service images have been deleted '));
		$this->redirect(Controller::referer());
	} 
	private function listFolderDeleteChache($path) {
		$dir = opendir($path);
		while(false != ($file = readdir($dir))) {
			if(($file != ".") and ($file != "..")){
				if (strpos($file,'cake_') !== false) {
					unlink($path.DS.$file);
				}
			}
		}
		return true;
	}
	public function admin_site(){
		$this->loadModel('Setting');
		if(!empty($this->request->data) && self::_validate(1)){
			foreach($this->request->data['Setting'] as $key => $value){
				if(is_array($value)){
					if($value['error']==0){
						$ext = explode(".",$value['name']);
						$name = explode("_",$key);
						$img_name = array_pop($name).".".array_pop($ext);
						move_uploaded_file($value['tmp_name'],WWW_ROOT."img/site/$img_name");
						$value = $img_name;
					}else{
						continue;
					}
				}
				
				if($this->Setting->find('count',array('conditions'=>array('Setting.key'=>$key,'Setting.module'=>'site')))){
					
					$this->Setting->query("UPDATE `settings` SET `values`=\"$value\" , module=\"site\" WHERE `key`=\"$key\"");
				
				} else{
					$this->Setting->query("INSERT `settings` SET `values`=\"$value\"  , `key`=\"$key\" , module=\"site\"");
				}
				
				$this->Session->setFlash(__('Setting has been Saved Successfully'));
				
			}
			Cache::delete('cake_settings');
			
			
		}
		if(empty($this->request->data)){
			$this->request->data['Setting'] = $this->Setting->find('list',array('fields'=>array('Setting.key','Setting.values')));
		}else{
			$data = $this->Setting->find('list',array('fields'=>array('Setting.key','Setting.values')));
			
			$this->request->data['Setting']['site_logo'] = $data['site_logo'];
			$this->request->data['Setting']['site_icon'] = $data['site_icon'];
			$this->request->data['Setting']['site_noimage'] = $data['site_noimage'];
		}

		//Cache::write('site', $this->request->data);
		
		
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/home'),
			'name'=>'Home'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/settings/site'),
			'name'=>'Site Setting'
		);
		 
		
		
	}
	private function _validate($id = null){
		if($id==1){ // validation for admin_site function
			if(trim($this->request->data['Setting']['site_name'])==""){
				$this->Session->setFlash(__('Please Enter Site name'), 'default', array(), 'error');
				return false;
			}
			if(trim($this->request->data['Setting']['site_url'])==""){
				$this->Session->setFlash(__('Please Enter Valid Site url'), 'default', array(), 'error');
				return false;
			}
			if(trim($this->request->data['Setting']['site_contact_email'])==""){
				$this->Session->setFlash(__('Please Enter Valid contact email address'), 'default', array(), 'error');
				return false;
			}
			if (!filter_var($this->request->data['Setting']['site_contact_email'], FILTER_VALIDATE_EMAIL)) {
				$this->Session->setFlash(__('Please Enter Valid contact email address'), 'default', array(), 'error');
				return false;
			}
			if($this->request->data['Setting']['site_logo']['error']==0){
				if($this->request->data['Setting']['site_logo']['type']!="image/png" && $this->request->data['Setting']['site_logo']['type']!="image/jpeg" && $this->request->data['Setting']['site_logo']['type']!="image/jpg"  && $this->request->data['Setting']['site_logo']['type']!="image/gif"){
					$this->Session->setFlash(__('Please upload png , gif , jpeg format image'), 'default', array(), 'error');
					return false;
					
				}
				if($this->request->data['Setting']['site_logo']['size'] <= 5000 && $this->request->data['Setting']['site_logo']['size'] >=15360 ){
					$this->Session->setFlash(__('Logo image size is not valid'), 'default', array(), 'error');
					return false;
				}
			}
			
			if($this->request->data['Setting']['site_icon']['error']==0){
				if($this->request->data['Setting']['site_icon']['type']!="image/x-icon"){
					$this->Session->setFlash(__('Please upload only icon format image'), 'default', array(), 'error');
					return false;
					
				}
				if($this->request->data['Setting']['site_icon']['size'] <= 5000 && $this->request->data['Setting']['site_icon']['size'] >=10240 ){
					$this->Session->setFlash(__('Icon image size is not valid'), 'default', array(), 'error');
					return false;
				}
			}
			if($this->request->data['Setting']['site_noimage']['error']==0){
				if($this->request->data['Setting']['site_noimage']['type']!="image/png" && $this->request->data['Setting']['site_noimage']['type']!="image/jpeg" && $this->request->data['Setting']['site_noimage']['type']!="image/jpg"  && $this->request->data['Setting']['site_noimage']['type']!="image/gif"){
					$this->Session->setFlash(__('Please upload only no-image image format image'), 'default', array(), 'error');
					return false;
					
				}
				
			}
			if(trim($this->request->data['Setting']['site_title'])==""){
				$this->Session->setFlash(__('Please Enter Site title'), 'default', array(), 'error');
				return false;
			}
			
		}
                else if($id==2){
                    $this->loadModel('User');
                     $user = $this->User->read(null, $this->Auth->user('id'));
                     $encryptedPassword = $this->Auth->password($this->request->data['User']['oldpassword']);
                     if($encryptedPassword != $user['User']['password']){
                         $this->Session->setFlash('Your current password didn\'t match.','default','','error');
                         return false;
                    }
                    if(trim($this->request->data['User']['password'])==''){
						$this->Session->setFlash('Please Enter new password','default','','error');
                         return false;
					}
                    if($this->request->data['User']['password']!=$this->request->data['User']['password2']){
                         $this->Session->setFlash('Your new password and confirm password does not match','default','','error');
                         return false;
                    }
                }
		return true;
	}
	function validation(){
		$this->loadModel('User');
		$this->autoRender = false;
		$this->User->set($this->request->data);
		$result = array();
		if ($this->User->validates()) {
			$result['error'] = 0;
		}else{
			$result['error'] = 1;
		}
		$result['errors'] = $this->User->validationErrors;
		$errors = array();
	 
		foreach($result['errors'] as $field => $data){
			$errors['User'.Inflector::camelize($field)] = array_pop($data);
		}
	 
		$result['errors'] = $errors;
	 
		if($this->request->is('ajax')) {
			echo json_encode($result);
		 	return;
		} 
	}
	function paypal_validation(){
		$this->autoRender = false;
		$this->Setting->set($this->request->data);
		$result = array();
		if ($this->Setting->validates()) {
			$result['error'] = 0;
		}else{
			$result['error'] = 1;
		}
		$result['errors'] = $this->Setting->validationErrors;
		$errors = array();
	 
		foreach($result['errors'] as $field => $data){
			$errors['Setting'.Inflector::camelize($field)] = array_pop($data);
		}
	 
		$result['errors'] = $errors;
	 
		if($this->request->is('ajax')) {
			echo json_encode($result);
			return;
		} 
	}
    function admin_paypalsetting($id=null){
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/home'),
			'name'=>'Home'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/paypalsetting'),
			'name'=>'Manage Paypal Setting'
		);
		if(!empty($this->request->data)){
			foreach($this->request->data['Setting'] as $key => $value){
				if(is_array($value)){
					if($value['error']==0){
						$ext = explode(".",$value['name']);
						$name = explode("_",$key);
						
					}else{
						continue;
						$value;
					}
				}
				
				if($this->Setting->find('count',array('conditions'=>array('Setting.key'=>$key,'Setting.module'=>'paypal')))){
					
					$this->Setting->query("UPDATE `settings` SET `values`=\"$value\", module=\"paypal\" WHERE `key`=\"$key\"");
				
				} else{
					$this->Setting->query("INSERT `settings` SET `values`=\"$value\" , `key`=\"$key\", module=\"paypal\"");
				}
				
				$this->Session->setFlash(__('Paypal Setting has been Saved/Updated Successfully.'));
			}
			Cache::delete('cake_settings');
			$this->redirect(array('action'=>'paypalsetting'));
		}
		if(empty($this->request->data)){
			$this->request->data['Setting'] = $this->Setting->find('list',array('fields'=>array('Setting.key','Setting.values')));
		}else{
			$data = $this->Setting->find('list',array('fields'=>array('Setting.key','Setting.values')));
			$this->request->data['Setting']['business_email_paypal'] = $data['business_email_paypal'];
			$this->request->data['Setting']['sandbox_mode'] = $data['sandbox_mode'];
		}
	}
	private function _validate_social_media($id = null){
		if(trim($this->request->data['Setting']['facebook'])==""){
			$this->Session->setFlash(__('Please Enter Facebook Url'), 'default', array(), 'error');
			return false;
		}
		if(trim($this->request->data['Setting']['google_plus'])==""){
			$this->Session->setFlash(__('Please Enter Google Plus Url'), 'default', array(), 'error');
			return false;
		}
		return true;
	}
	private function _validate_image_setting($id = null) {
		if(trim($this->request->data['Setting']['galary_image_width'])==""){
				$this->Session->setFlash(__('Please enter gallery image width'), 'default', array(), 'error');
				return false;
		}
		elseif(!is_numeric($this->request->data['Setting']['galary_image_width'])) {
			$this->Session->setFlash(__('Please enter correct gallery image width'), 'default', array(), 'error');
			return false;
		}
		elseif(trim($this->request->data['Setting']['galary_image_height'])==""){
				$this->Session->setFlash(__('Please enter gallery image height'), 'default', array(), 'error');
				return false;
		}
		elseif(!is_numeric($this->request->data['Setting']['galary_image_height'])){
				$this->Session->setFlash(__('Please enter correct gallery image height'), 'default', array(), 'error');
				return false;
		}
		elseif(trim($this->request->data['Setting']['galary_admin_image_width'])==""){
				$this->Session->setFlash(__('Please enter gallery admin image width'), 'default', array(), 'error');
				return false;
		}
		elseif(!is_numeric($this->request->data['Setting']['galary_admin_image_width'])){
				$this->Session->setFlash(__('Please enter correct gallery admin image width'), 'default', array(), 'error');
				return false;
		}
		elseif(trim($this->request->data['Setting']['galary_admin_image_height'])==""){
				$this->Session->setFlash(__('Please enter gallery admin image height'), 'default', array(), 'error');
				return false;
		}
		elseif(!is_numeric($this->request->data['Setting']['galary_admin_image_height'])){
				$this->Session->setFlash(__('Please enter correct gallery admin image height'), 'default', array(), 'error');
				return false;
		}
		return true;
	}
	function admin_changepassword(){
		if (!empty($this->request->data) && self::_validate(2)){
			$data = $this->User->read(null, $this->Auth->user('id'));
			$data['User']['password'] = $this->request->data['User']['password'];
			$this->User->create();
			$this->User->save($data);
			$this->Session->setFlash('Your password changed successfully.');
		}
		$this->request->data = array();
		$this->breadcrumbs[] = array(
		'url'=>Router::url('/admin/home'),
		'name'=>'Home'
		);
		$this->breadcrumbs[] = array(
				'url'=>Router::url('/admin/settings/changepassword'),
				'name'=>'Change Password'
		);
		
	}
}
?>
