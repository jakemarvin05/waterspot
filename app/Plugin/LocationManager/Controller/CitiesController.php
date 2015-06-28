<?php
Class CitiesController extends LocationManagerAppController{
	public $name = 'Cities';
	public $helpers = array('Form');
	public $uses = array('LocationManager.Country','LocationManager.City');
	public $paginate = array();
	public $id = null;
	
	function admin_index($country_id=null,$search=NULL,$limit = 20) {
		if($country_id==null){		//$this->redirect(array('plugin'=>'content_manager','controller'=>'pages','action'=>'index'));
		}
		if($this->request->is('post')){
			$this->redirect(array('plugin'=>'location_manager','controller'=>'cities','action'=>'index',$country_id,$this->request->data['search']));
		}		
		/* pagination start*/
		$condition=array();
		//$this->paginate['conditions']['AND']= array('Gallery.parentId'=>$parent_id);
		if($search!=NULL && $search!="_blank"){
			$this->paginate['conditions']['AND'][] = array('City.name like'=>urldecode($search).'%');
		}else{
			$search = '';
		}
		if($country_id!=NULL && $country_id!="_blank"){
			$condition['City.country_id =']=$country_id;
		}else{
			$country_id = '';
		}
		if($limit!='ALL'){
			$this->paginate['limit'] = $limit;
		}
		$this->paginate['order'] = array('City.reorder'=>'ASC');
		$cities = $this->paginate('City',$condition);
		/* pagination end*/
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/home'),
			'name'=>'Home'
		    );
		$this->breadcrumbs[] = array(
				'url'=>Router::url('/admin/location_manager/countries'),
				'name'=>'Manage Country'
		);
		$this->breadcrumbs[] = array(
				'url'=>Router::url('/admin/location_manager/cities'),
				'name'=>'Manage City'
		);
		$country=$this->Country->read(null,$country_id);
		$redirect_url=(Controller::referer()=="/")? '/admin/location_manager/cities/':Controller::referer();
		$this->set('country',$country);
		$this->set('cities',$cities);
		$this->set('url',$redirect_url);
		$this->set('search', $search);
		$this->set('country_id', $country_id);
		if($this->request->is('ajax')){
			$this->layout = '';
			$this -> Render('ajax_admin_index');
		}
	}

	function ajax_sort(){
		$this->autoRender = false;
		foreach($_POST['sort'] as $order => $id){
			$city= array();
			$city['City']['id'] = $id;
			$city['City']['reorder'] = $order;
			$this->City->create();
			$this->City->save($city);
		}
	}
        
	function admin_add($country_id=null,$id=null){
		$this->breadcrumbs[] = array(
		'url'=>Router::url('/admin/home'),
		'name'=>'Home'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/location_manager/cities/index/'.$country_id),
			'name'=>'Manage City'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/location_manager/cities/add/'.$country_id),
			'name'=>($id==null)?'Add City':'Update City'
		);
		if(!empty($this->request->data)){
			if(!$id){
				$this->request->data['City']['created_at']=date('Y-m-d H:i:s');
				$this->request->data['City']['status']=1;
			}else{
				$this->request->data['City']['updated_at']=date('Y-m-d H:i:s');
			}
			$this->City->create();
			$this->City->save($this->request->data);
			if ($this->request->data['City']['id']) {
				$this->Session->setFlash(__('City has been updated successfully'));
				} 
				else {
					$this->Session->setFlash(__('City has been added successfully'));
				}
			$this->redirect($this->request->data['City']['redirect']);
		}
		else{
			if($id!=null){
				$this->request->data = $this->City->read(null,$id);
			}else{
				$this->request->data = array();
			}
		}
		$redirect_url=(Controller::referer()=="/")? Router::url('/admin/location_manager/cities') :Controller::referer();
		$this->request->data['City']['country_id']=$country_id;
		$this->set('country_id',$country_id);
		$this->set('url',$redirect_url);
		$countries = $this->Country->find('list',array('fields'=>array('Country.id','Country.name')));
		$this->set('countries',$countries);
	}
            
	function validation(){
		$this->autoRender = false;
		$this->City->set($this->request->data);
		$result = array();
		if ($this->City->validates()) {
			$result['error'] = 0;
		}else{
			$result['error'] = 1;
		}
		$result['errors'] = $this->City->validationErrors;
		$errors = array();
		foreach($result['errors'] as $field => $data){
			$errors['City'.Inflector::camelize($field)] = array_pop($data);
		}
		$result['errors'] = $errors;
		if($this->request->is('ajax')) {
			echo json_encode($result);
			return;
		} 
	}
	
	function admin_view($id=null){
		$this->layout = '';
		$city 	 = $this->City->read(null,$id);
		$country = $this->Country->find('first',array('conditions'=>array('Country.id'=>$city['City']['country_id']),'fields'=>array('Country.id','Country.name')));
		$this->set('city',$city);
		$this->set('country',$country);
	}
	
	function admin_delete($id=null){
        $data=$this->request->data['City']['id'];
        $action = $this->request->data['City']['action'];
        $ans="0";
		foreach($data as $value){
			if($value!='0'){
				if($action=='Publish'){
					$city['City']['id'] = $value;
					$city['City']['status']=1;
					$this->City->create();
					$this->City->save($city);
					$ans="1";
				}
				if($action=='Unpublish'){
					$city['City']['id'] = $value;
					$city['City']['status']=0;
					$this->City->create();
					$this->City->save($city);
					$ans="1";
				}
				 if($action=='Delete'){
					$this->City->delete($value);
					$ans="2";
				}
			}
		}
		if($ans=="1"){
			$this->Session->setFlash(__('City has been '.$this->data['City']['action'].'ed successfully', true));
		}
		else if($ans=="2"){
			$this->Session->setFlash(__('City  has been '.$this->data['City']['action'].'d successfully', true));
		}else{
			$this->Session->setFlash(__('Please Select any City', true),'default','','error');
		}
		$redirect_url=(Controller::referer()=="/")? '/admin/location_manager/cities/':Controller::referer();
		$this->redirect($redirect_url);
    }
}
?>
