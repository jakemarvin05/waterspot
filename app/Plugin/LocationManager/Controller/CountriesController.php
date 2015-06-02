<?php
Class CountriesController extends LocationManagerAppController{
		public $uses = array('LocationManager.Country');
		public $paginate = array();
        public $id = null;
	
	function admin_index($search=null){
		$this->paginate = array();
		$parent_detail = array();
		$condition = null;
		$this->paginate['limit']=20;
		if($this->request->is('post')){
			$this->redirect(array('plugin'=>'location_manager','controller'=>'countries','action'=>'index',$this->request->data['search']));
		}
		$this->paginate['order']=array('Country.id'=>'ASC');		
		if($search!=null){
			$search = urldecode($search);
			$condition['Country.name like'] = $search.'%';
		}
		$countries=$this->paginate("Country", $condition);	
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/home'),
			'name'=>'Home'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/location_manager/countries'),
			'name'=>'Manage Country'
		);
		$this->set('countries',$countries);
		$this->set('search',$search);
		$this->set('url','/'.$this->params->url);
		if($this->request->is('ajax')){
			$this->layout = '';
			$this -> Render('ajax_admin_index');
		}
	}
        
	function validation(){
		$this->autoRender = false;
		$this->Country->set($this->request->data);
		$result = array();
		if ($this->Country->validates()) {
			$result['error'] = 0;
		}else{
			$result['error'] = 1;
		}
		$result['errors'] = $this->Country->validationErrors;
		$errors = array();
		foreach($result['errors'] as $field => $data){
			$errors['Country'.Inflector::camelize($field)] = array_pop($data);
		}
		$result['errors'] = $errors;
		if($this->request->is('ajax')) {
			echo json_encode($result);
			return;
		} 
	}
	
	function admin_add($id=null){
		$this->breadcrumbs[] = array(
		'url'=>Router::url('/admin/home'),
		'name'=>'Home'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/location_manager/countries'),
			'name'=>'Manage Country'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/location_manager/countries/add'),
			'name'=>($id==null)?'Manage Country':'Update Country'
		);
		if(!empty($this->request->data)){
			if(!$id){
				$this->request->data['Country']['created_at']=date('Y-m-d H:i:s');
				$this->request->data['Country']['status']=1;
			}else{
				$this->request->data['Country']['updated_at']=date('Y-m-d H:i:s');
			}	
			$this->Country->create();
			$this->Country->save($this->request->data);
			if ($this->request->data['Country']['id']) {
				$this->Session->setFlash(__('Country has been updated successfully'));
			} 
			else {
				$this->Session->setFlash(__('Country has been added successfully'));
			}
			$this->redirect(array('action'=>'admin_index')); 
		}
		else{
			if($id!=null){
				$this->request->data = $this->Country->read(null,$id);
			}else{
				$this->request->data = array();
			}
		} 
		$this->set('url',Controller::referer());
	}
	
	function ajax_sort(){
		$this->autoRender = false;
		foreach($_POST['sort'] as $order => $id){
			$country= array();
			$country['Country']['id'] = $id;
			$country['Country']['reorder'] = $order;
			$this->Country->create();
			$this->Country->save($country);
		}
	}
       
	function admin_delete($id=null){
		$this->autoRender = false;
		$data=$this->request->data['Country']['id'];
		$action = $this->request->data['Country']['action'];
		$ans="0";
		foreach($data as $value){
			if($value!='0'){
				if($action=='Publish'){
					$country['Country']['id'] = $value;
					$country['Country']['status']=1;
					$this->Country->create();
					$this->Country->save($country);
					$ans="1";
				}
				if($action=='Unpublish'){
					$country['Country']['id'] = $value;
					$country['Country']['status']=0;
					$this->Country->create();
					$this->Country->save($country);
					$ans="1";
				}
				if($action=='Delete'){
					$this->Country->delete($value);
					$ans="2";
				}
			}
		}
		if($ans=="1"){
			$this->Session->setFlash(__('Country has been '.$this->data['Country']['action'].'ed successfully', true));
		}
		else if($ans=="2"){
			$this->Session->setFlash(__('Country has been '.$this->data['Country']['action'].'d successfully', true));
		}else{
			$this->Session->setFlash(__('Please Select any Country', true),'default','','error');
		}
		$this->redirect($this->request->data['Country']['redirect']);
	}
}
?>
