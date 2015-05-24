<?php
class SettingsController extends AppController {
	public $uses = array('VendorManager.Setting');
	public $paginate = array();
    public $id = null;    
	
	function admin_fees(){
		$this->layout='admin';
		if(!empty($this->request->data) && !$this->validation	()){
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
				if($this->Setting->find('count',array('conditions'=>array('Setting.key'=>$key,'Setting.module'=>'vendor')))){
					$this->Setting->query("UPDATE `settings` SET `values`=\"$value\" , module=\"vendor\" WHERE `key`=\"$key\"");
				} else{
					$this->Setting->query("INSERT `settings` SET `values`=\"$value\"  , `key`=\"$key\" , module=\"vendor\"");
				}
				$this->Session->setFlash(__('Fee amount has been Saved Successfully'));
			}
			Cache::delete('site');
			$this->redirect(array('action'=>'admin_fees'));			
		}
		if(empty($this->request->data)){
			$this->request->data['Setting'] = $this->Setting->find('list',array('fields'=>array('Setting.key','Setting.values')));
		}else{
			$data = $this->Setting->find('list',array('fields'=>array('Setting.key','Setting.values')));
			$this->request->data['Setting']['sales_commission_amount'] = $data['sales_commission_amount'];			
		}
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/home'),
			'name'=>'Home'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/vendor_manager/settings/admin_fees'),
			'name'=>'Fee Amount '
		);
	}	
	
	function validation(){
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
}
?>
