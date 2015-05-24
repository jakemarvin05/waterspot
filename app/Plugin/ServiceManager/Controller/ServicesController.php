<?php
class ServicesController extends ServiceManagerAppController {
	public $uses = array('ServiceManager.Service');
	public $paginate = array();
	public $id = null;
	
	function admin_index($search=null){
		$this->paginate = array();
		$condition = null;
		$this->paginate['limit']=20;
		if($this->request->is('post')){
			$this->redirect(array('plugin'=>'service_manager','controller'=>'services','action'=>'index' ,$this->request->data['search']));
		}
		$this->paginate['order']=array('Service.ordering '=>'ASC','.id'=>'DESC');		
		if($search!=null){
			$search = urldecode($search);
			$condition['Service.title like'] = $search.'%';
		}
		$services=$this->paginate("Service", $condition);	
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/home'),
			'name'=>'Home'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/service_manager/services'),
			'name'=>'Manage Services'
		);
		$this->set('services',$services);
		$this->set('search',$search);
		$this->set('url','/'.$this->params->url);
		if($this->request->is('ajax')){
			$this->layout = '';
			$this -> Render('ajax_admin_index');
		}
	}

	function ajax_sort(){
		$this->autoRender = false;
		foreach($_POST['sort'] as $order => $id){
			$mail= array();
			$mail['Service']['id'] = $id;
			$mail['Service']['ordering'] = $order;
			$this->Service->create();
			$this->Service->save($mail);
		}
	}

	function admin_add($id=null){ 
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/home'),
			'name'=>'Home'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/service_manager/services'),
			'name'=>'Manage Service'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/service_manager/mail/add'),
			'name'=>($id==null)?'Add Service':'Update Service'
		);
		if(!empty($this->request->data)) {
			$this->Service->create();
			$this->Service->save($this->request->data);
			if ($this->request->data['Service']['id']) {
				$this->Session->setFlash(__('Service has been updated successfully'));
				} 
				else {
					$this->Session->setFlash(__('Service has been added successfully'));
				}
			$this->redirect(array('controller'=>'services','action'=>'index'));
		}
		else{
			if($id!=null){
				$this->request->data = $this->Service->read(null,$id);
			}else{
				$this->request->data = array();
			}
		}
		$this->set('url',Controller::referer());
	}
	
	function admin_delete($id=null){
		$this->autoRender = false;
		$data=$this->request->data['Service']['id'];
		$action = $this->request->data['Service']['action'];
		$ans="0";
		foreach($data as $value){
			if($value!='0'){
				if($action=='Publish'){
					$mail['Service']['id'] = $value;
					$mail['Service']['status']=1;
					$this->Service->create();
					$this->Service->save($mail);
					$ans="1";
				}
				if($action=='Unpublish'){
					$mail['Service']['id'] = $value;
					$mail['Service']['status']=0;
					$this->Service->create();
					$this->Service->save($mail);
					$ans="1";
				}
				if($action=='Delete'){
					$this->Service->delete($value);
					$ans="2";
				}
			}
		}
		if($ans=="1"){
			$this->Session->setFlash(__('Service has been '.$this->data['Service']['action'].'ed successfully', true));
		}
		else if($ans=="2"){
			$this->Session->setFlash(__('Service has been '.$this->data['Service']['action'].'d successfully', true));
		}else{
			$this->Session->setFlash(__('Please Select any Service', true),'default','','error');
		}
		$this->redirect($this->request->data['Service']['redirect']);
	}

	function validation(){
		$this->autoRender = false;
		$this->Service->set($this->request->data);
		$result = array();
		if ($this->Service->validates()) {
			$result['error'] = 0;
		}else{
			$result['error'] = 1;
		}
		$result['errors'] = $this->Service->validationErrors;
		$errors = array();
		foreach($result['errors'] as $field => $data){
			$errors['Service'.Inflector::camelize($field)] = array_pop($data);
		}
		$result['errors'] = $errors;
		if($this->request->is('ajax')) {
			echo json_encode($result);
			return;
		} 
	}
	
	function admin_view($id = null) {
		$this->layout = '';
     	$criteria = array();
        $criteria['conditions'] = array('Service.id'=>$id);
        $srevices =  $this->Service->find('first', $criteria);
        $this->set('srevices', $srevices);
    }
}
?>
