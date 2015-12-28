<?php
Class ServicesController extends VendorManagerAppController{
	public $uses = array('VendorManager.Service');
	public $components = array('Email');
	public $paginate = array();
	public $ajax_session_name= "Ajax_Files";
	public $id = null;

	function add_slots($service_id=null){
		$this->loadModel('VendorManager.ValueAddedService');
		$this->loadModel('LocationManager.City');
		$this->loadModel('VendorManager.ServiceSlot');
		// check service_id owner 
		$vendor_id=$this->VendorAuth->id();
		if(!empty($service_id)){
			if($this->Service->checkServiceById($vendor_id,$service_id)<=0) {
				$this->Session->setFlash(__('Are you doing something wrong?', false));
				$this->redirect($this->VendorAuth->loginRedirect);
			} 
		}
		$criteria = array();
		$criteria['fields']= array('Service.*','ServiceType.name');
		$criteria['joins'] = array(
            array(
                'table' => 'service_types',
                'alias' => 'ServiceType',
                'type' => 'LEFT',
                'conditions' => array('ServiceType.id = Service.service_type_id')
            )
        );
		$criteria['conditions'] =array('Service.vendor_id'=> $this->VendorAuth->id(),'Service.id'=> $service_id);
		$service=$this->Service->find('first', $criteria);
		// Get value added service
		$service['ValueAddedService']=$this->ValueAddedService->getValueaddedServiceByservice_id($service_id);
		//Get city and country by location_id 
		$service['Service']['location_name']=(!empty($service['Service']['id']))?$this->City->getLocationListCityID($service['Service']['location_id']): "Location not available";
		// Get slots
		$service['ServiceSlot']=$this->ServiceSlot->getService_slotByservice_id($service_id);
		array_push(self::$script_for_layout,'fancybox/jquery-1.7.2.min.js');
		array_push(self::$script_for_layout,'fancybox/jquery.fancybox.js');
		array_push(self::$css_for_layout,'fancybox/jquery.fancybox.css');
		self::$scriptBlocks[]=
			'$(document).ready(function(){
				$(\'.fancybox\').fancybox();
			});
			.fancybox-custom .fancybox-skin {
			box-shadow: 0 0 50px #222;
			}';
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/'),
			'name'=>'Home'
		    );
		$this->breadcrumbs[] = array(
			'url'=>Router::url(array('plugin'=>'vendor_manager','controller'=>'vendors','action'=>'dashboard')),
			'name'=>"Dashboard"
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url(array('plugin'=>'vendor_manager','controller'=>'services','action'=>'add_slots')),
			'name'=>'View Services'
		);
		$this->set(compact('service'));
	}

	function admin_servicelist($vendor_id=null,$search=null,$limit=20){
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/home'),
			'name'=>'Home'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/vendor_manager/vendors'),
			'name'=>'Manage Vendor'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/vendor_manager/services/servicelist'),
			'name'=>'Vendor Service'
		);
		$this->paginate = array();
		if($this->request->is('post')){
			$this->redirect(array('plugin'=>'vendor_manager','controller'=>'services','action'=>'servicelist',$vendor_id,$this->request->data['search']));
		}
		$this->paginate = array('joins'=>
			array(
				array(
					'table'=>'service_types',
					'alias'=>'ServiceType',
					'type'=>'LEFT',
					'conditions'=>array('ServiceType.id = Service.service_type_id')
				)
	
			),
			'conditions'=>array('Service.vendor_id'=>$vendor_id),
			'group'=>'Service.id',
		    'fields'=>array('Service.*','ServiceType.id','ServiceType.name'),
			'limit'=>$limit,
			'order'=>array('Service.reorder'=>'ASC','Service.id'=>'DESC')
		);
		if($search!=NULL && $search!="_blank"){
			$conditions['OR'] = array(
						        array('Service.service_title like'=>urldecode($search).'%'),
						        array('ServiceType.name like'=>urldecode($search).'%'),
								array('Service.service_price like'=>urldecode($search).'%'),
							);
		}else{
			$search = '';
			$conditions=null;
		}
		$vendor_services=$this->paginate("Service",$conditions);
		$this->set('vendor_services',$vendor_services);
		$this->set('vendor_id',$vendor_id);
		$this->set('search',$search);
		$this->set('url','/'.$this->params->url);
		if($this->request->is('ajax')){
			$this->layout = '';
			$this -> Render('ajax_admin_servicelist');
	 	}
	}
	
	function admin_delete($id=null){
		self::serviceDelete($id);
	}
	
	function delete($id=null){
		self::serviceDelete($id);
	}
	
	private function serviceDelete($id=null){
		$this->autoRender = false;
	 	$data=$this->request->data['Service']['id'];
		$action = $this->request->data['Service']['action'];
		$ans="0";
		foreach($data as $value){
			if($value!='0'){
				if($action=='Delete'){
					$this->Service->delete($value);
					// delete image by serive id where value is service id
					self::DeleteServiceImage($value);
					self::DeleteServiceReview($value);
					$ans="2";
				}
				if($action=='Deactivate'){
					$data['Service']['id'] = $value;
					$data['Service']['status']=0;
					$this->Service->create();
					$this->Service->save($data);
					$ans="1";
                }
                if($action=='Activate'){
					$data['Service']['id'] = $value;
					$data['Service']['status']=1;
					$this->Service->create();
					$this->Service->save($data);
					$ans="1";
                }
			}
		}
		if($ans=="1"){
			$this->Session->setFlash(__('Service review has been '.$this->data['Service']['action'].'d successfully', true));
		}else if($ans=="2"){
			$this->Session->setFlash(__('Service has been '.$action.'d successfully', true));
		}else{
			$this->Session->setFlash(__('Please Select any service', true),'default','','error');
		}
		$this->redirect($this->request->data['Service']['redirect']);
	}
	
	function add_services($service_id=null){
            array_push(self::$css_for_layout,'vendor/vendor-panel.css');
		$this->loadModel('ServiceManager.ServiceType');
		$this->loadModel('LocationManager.City');
		$this->loadModel('VendorManager.ValueAddedService');
		$this->loadModel('VendorManager.ServiceImage');
		$vendor_id=$this->VendorAuth->id();
		// check service_id owner



		if(!empty($service_id)){
			if($this->Service->checkServiceById($vendor_id,$service_id)<=0) {
				$this->Session->setFlash(__('Are you doing something wrong?', false));
				$this->redirect($this->VendorAuth->loginRedirect);
			}
			$service_detail=$this->Service->servieDetailByService_id($service_id);
			$this->set('service_detail',$service_detail);
		}
		if(!empty($this->request->data) && self::validation()){
			$this->request->data['Service']['vendor_id']=$this->VendorAuth->id();
			if(empty($service_id)){
				$this->request->data['Service']['created_at']=date('Y-m-d H:i:s');
				$this->request->data['Service']['status']=1;
				$this->request->data['Service']['youtube_url']=serialize($this->request->data['Service']['youtube_url']);
				$savemsg="added";
			}else{
				$this->request->data['Service']['youtube_url']=serialize(array_filter($this->request->data['Service']['youtube_url']));
				$this->request->data['Service']['updated_at']=date('Y-m-d H:i:s');
				$savemsg="updated";
			}
			if ($this->request->data['Service']['is_private'] == 1) {
				$this->request->data['Service']['min_participants'] = 0;
				$this->request->data['Service']['no_person'] = 1;
			}
			if ($this->request->data['Service']['is_minimum_to_go'] == 1) {
				$this->request->data['Service']['min_participants'] = 0;
			}

			// saving service 
			$this->Service->create();
			$this->Service->save($this->request->data);
			// saving vas services
			self::_vendor_add_service($this->Service->id);
			// saving services images 
			self::_add_service_image($this->Service->id);

			$this->Session->setFlash(__('Service has been '.$savemsg. ' successfully.'));
			$this->redirect(array('plugin'=>'vendor_manager','controller'=>'services', 'action' => 'my_services'));	
		}
		else{
			if($service_id!=null) {
				$this->request->data = $this->Service->read(null,$service_id);
				$breadcrumbs_msg="Update Service";
				$this->request->data['ValueAddedService']=$this->ValueAddedService->getValueaddedServiceByservice_id($service_id);
				$this->request->data['ServiceImage']=$this->ServiceImage->getServiceImageDetailsByservice_id($service_id);
            }else{
				$this->request->data = array();
				$breadcrumbs_msg="Add Service";
            }
            $ajax_images = $this->Session->read($this->ajax_session_name);
            $file_paths=Configure::read('Image.AjaxPath');
            if(!empty($ajax_images)){
				foreach($ajax_images as $image){
					@unlink(Configure::read('Image.AjaxPath').$image);
				}
			}
			$this->Session->delete($this->ajax_session_name);
		}   
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/'),
			'name'=>'Home'
		    );
		$this->breadcrumbs[] = array(
			'url'=>Router::url(array('plugin'=>'vendor_manager','controller'=>'vendors','action'=>'dashboard')),
			'name'=>"Dashboard"
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url(array('plugin'=>'vendor_manager','controller'=>'services','action'=>'add_services')),
			'name'=>$breadcrumbs_msg
		);
		// list of services 
		$service_types=$this->ServiceType->servicelist();
		$city_list=$this->City->getLocationList();
		array_push(self::$script_for_layout,'https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js','frontEditor/ckeditor.js','VendorManager.ajax_upload.js');
		$this->set('service_types',$service_types);
		$participantsNumList= [];
		for($x=1; $x<=100; $x++){
			$participantsNumList[$x] = $x;
		}
		$this->set('participants_num_list',$participantsNumList);
		$this->set('city_list',$city_list);
	}

	private function _vendor_add_service($service_id){
		// load model
		$this->loadModel('VendorManager.ValueAddedService');
		$this->ValueAddedService->deleteAll(array('ValueAddedService.service_id' => $service_id));
		if (!empty($this->request->data['ValueAddedService'])) {

			foreach ($this->request->data['ValueAddedService']['value_added_name'] as $key=>$service) {
				if(empty($this->request->data['ValueAddedService']['value_added_price'][$key])) {
					continue;
				} 
				$data = array();
				$data['ValueAddedService']['id'] = '';
				$data['ValueAddedService']['service_id'] = $service_id;
				$data['ValueAddedService']['value_added_name'] =$this->request->data['ValueAddedService']['value_added_name'][$key];
				$data['ValueAddedService']['value_added_price'] =$this->request->data['ValueAddedService']['value_added_price'][$key];
				$this->ValueAddedService->create();
				$this->ValueAddedService->save($data);
			}
		}
    } 
    
	private function _add_service_image($service_id){
		$this->loadModel('VendorManager.ServiceImage');
		$existing_images = $this->ServiceImage->find('all',array('conditions'=>array('ServiceImage.service_id' => $service_id)));
		/*Delete Existing Image which is deleleted*/
		if(!empty($existing_images)){
			foreach($existing_images as $images){
				if(!in_array($images['ServiceImage']['image'],$this->request->data['ServiceImage']['images'])){
					@unlink(Configure::read('Image.SourcePath').$images['ServiceImage']['image']);
				}
			}
		}
		$this->ServiceImage->deleteAll(array('ServiceImage.service_id' => $service_id));
		/*Delete Existing Image which is deleleted*/
		
		 
		$ajax_images = $this->Session->read($this->ajax_session_name);
		if (!empty($this->request->data['ServiceImage'])) {
			foreach ($this->request->data['ServiceImage']['images'] as $key=>$service) {
			 	if(!empty($ajax_images)){
					if(in_array($service,$ajax_images)){
						if(file_exists(Configure::read('Image.AjaxPath').$service)){
							rename(Configure::read('Image.AjaxPath').$service,Configure::read('Image.SourcePath').$service);
						}
					}
				}
				$data = array();
				if($this->request->data['ServiceImage']['default_image']==$service){
					$data['ServiceImage']['default_image']=$this->request->data['ServiceImage']['default_image'];
				}else{
					$data['ServiceImage']['default_image']=Null;
				}
				
				$data['ServiceImage']['id'] = '';
				$data['ServiceImage']['service_id'] = $service_id;
				$data['ServiceImage']['image'] =$service;
				$this->ServiceImage->create();
				$this->ServiceImage->save($data); 
			}
			$this->Session->delete($this->ajax_session_name);
		}
	} 
   
    function my_services(){
		array_push(self::$css_for_layout,'vendor/vendor-panel.css');
		$sort_by = isset($_GET['sort_by']) ? 'Service.'.$_GET['sort_by'] : false;
		// checking login 
		$vendor_id=$this->VendorAuth->id();
		$this->loadModel('LocationManager.City');
		$this->loadModel('VendorManager.ServiceImage');
		$condition = null;
		$criteria = array();
		$this->paginate = array();
		$this->paginate['limit']=20;
		$this->paginate['fields']= array('*');
		$this->paginate['joins'] = array(
            array(
                'table' => 'service_types',
                'alias' => 'ServiceType',
                'type' => 'LEFT',
                'conditions' => array('ServiceType.id = Service.service_type_id')
            )
        );
		$condition['Service.vendor_id'] = $this->VendorAuth->id();

		if ($sort_by) {
			$order = isset($_GET['order']) ? $_GET['order'] : 'ASC';
			$this->paginate['order'] = array($sort_by => $order);
		}

		$this->paginate['group'] = array('Service.id');
		$my_services=$this->paginate("Service",$condition);
		$service_id_list=array();
		$service_lists_filter=array();
		$service_lists_filter1=array();
		 // getall service location id
		foreach($my_services as $service) {
			$service_id_list[]=$service['Service']['location_id'];
		}
		$location_lists=$this->City->getLocationListByID($service_id_list);
		foreach($my_services as $service) {
			$service_id_list[]=$service['Service']['location_id'];
			$service_lists_filter1['id']=$service['Service']['id'];
			$service_lists_filter1['service_name']=$service['ServiceType']['name'];
			$service_lists_filter1['service_title']=$service['Service']['service_title'];
			$service_lists_filter1['status']=$service['Service']['status'];
			$service_lists_filter1['location_id']=$service['Service']['location_id'];
			$service_lists_filter1['location_details']=(!empty($service['Service']['location_string']))?$service['Service']['location_string']:((!empty($location_lists[$service['Service']['location_id']]))?$location_lists[$service['Service']['location_id']]:"Location Not availble");
			$service_lists_filter1['service_price']=$service['Service']['service_price'];
			//$service_lists_filter1['description']=$service['Service']['description'];
			$service_lists_filter1['image']=$this->ServiceImage->getOneimageServiceImageByservice_id($service['Service']['id']);
			$service_lists_filter[]=$service_lists_filter1;
		}
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/'),
			'name'=>'Home'
		    );
		$this->breadcrumbs[] = array(
			'url'=>Router::url(array('plugin'=>'vendor_manager','controller'=>'vendors','action'=>'dashboard')),
			'name'=>"Dashboard"
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url(array('plugin'=>'vendor_manager','controller'=>'services','action'=>'my_services')),
			'name'=>'My Services'
		);
		$this->set('url','/'.$this->params->url);
		$this->set('service_lists',$service_lists_filter);

		if($this->request->is('ajax')){
			$this->layout = '';
			$this -> Render('ajax_my_services');
		}
	}

	function validation(){
		$this->Service->set($this->request->data);
		$result = array();
		if ($this->Service->validates()) {
		  $result['error'] = 0;
		}else{
			$result['error'] = 1;
		}
		if($this->request->is('ajax')) {
			$this->autoRender = false;
			$result['errors'] = $this->Service->validationErrors;
			$errors = array();
			foreach($result['errors'] as $field => $data){
				$errors['Service'.Inflector::camelize($field)] = array_pop($data);
			}
			$result['errors'] = $errors;
			echo json_encode($result);
			return;
		}
		return (int)($result['error'])?0:1;
	}

	function slot_validation(){
		$this->loadModel('VendorManager.ServiceSlot');
		$this->ServiceSlot->set($this->request->data);
		$result = array();
		if ($this->ServiceSlot->validates()) {
			$result['error'] = 0;
		}else{
			$result['error'] = 1;
		}
		if($this->request->is('ajax')) {
			$this->autoRender = false;
			$result['errors'] = $this->ServiceSlot->validationErrors;
			$errors = array();
			foreach($result['errors'] as $field => $data){
				$errors['ServiceSlot'.Inflector::camelize($field)] = array_pop($data);
			}
			$result['errors'] = $errors;
			echo json_encode($result);
			return;
		}
		return (int)($result['error'])?0:1;
	}

	function images_handle(){
		$this->autoRender = false;
		App::uses('ImageResizeHelper', 'View/Helper');
		$ImageComponent = new ImageResizeHelper(new View());
		if(!empty($this->request->data['images'])){
			$total_image= count($this->request->data['images']);
			$ajax_images = array();
			$ajax_images = $this->Session->read($this->ajax_session_name);
			foreach($this->request->data['images'] as $key=>$image) {
				$image_name[$key]['image'] = $image = self::_manage_image($image,Configure::read('Image.AjaxPath'));
				if(!empty($ajax_images)){
					array_push($ajax_images,$image_name[$key]['image']);
				}else{
					$ajax_images[] = $image_name[$key]['image'];
				}
				$imgArr = array('source_path'=>Configure::read('Image.AjaxPath'),'img_name'=>$image,'width'=>80,'height'=>80);
				$image_name[$key]['temp_name'] = $this->webroot.'img'.DS.$ImageComponent->ResizeImage($imgArr);
			}
			$this->Session->write($this->ajax_session_name,$ajax_images);
			echo json_encode($image_name);
		}
	}

	function panorama_image_handle(){
		$this->autoRender = false;
		App::uses('ImageResizeHelper', 'View/Helper');
		$ImageComponent = new ImageResizeHelper(new View());
		if(!empty($this->request->data['panorama'])){
			if ($this->Session->read('panorama_image') && file_exists(Configure::read('Image.SourcePath') . $this->Session->read('panorama_image'))) {
				@unlink(Configure::read('Image.SourcePath') . $this->Session->read('panorama_image'));
			}
			$panorama = $this->request->data['panorama'];
			$image = self::_manage_image($panorama,Configure::read('Image.SourcePath'));
			$this->Session->write('panorama_image', $image);
			echo $image;
		}
	}

	function image_delete(){
		$this->autoRender = false;
		@unlink(Configure::read('Image.AjaxPath').$_POST['image']);
	}

	function add_service_slots($service_id=null) {
            array_push(self::$css_for_layout,'vendor/vendor-panel.css');
		
		$this->loadModel('VendorManager.ServiceSlot');
		$this->loadModel('VendorManager.Service');
		// checking vendor is login or not
		$vendor_id=$this->VendorAuth->id();
		// check service_id owner 
		if($this->Service->checkServiceById($vendor_id,$service_id)<=0) {
			$this->Session->setFlash(__('Are you doing something wrong?', false));
			$this->redirect($this->VendorAuth->loginRedirect);
		}
		$service = $this->Service->find('first',['conditions'=>['id'=>$service_id]]);
		$default_service_price = $service['Service']['service_price'];
		//$this->layout='';
		$hours=range(0,23);		
		$hours_format=array();
		$end_hours_format=array();
		$service_slots=array();
		foreach($hours as $key=>$hour) {
			$hours_format[$key.":00:00"]=DATE("g:i A", STRTOTIME($hour.":"."00"));
			$hours_format[$key.":30:00"]=DATE("g:i A", STRTOTIME('+30mins',strtotime($hour.":"."00")));
		} 
		foreach($hours as $key=>$hour) {
			if($key==0){
				$index=date('H:i:s', strtotime($key.":00:00"));
			}else{
				$index=date('H:i:s', strtotime($key.":00:00") -1);
			}
			$end_hours_format[$index]=DATE("g:i A", STRTOTIME($hour.":"."00"));
			$end_hours_format[$key.":29:59"]=DATE("g:i A", STRTOTIME('+30mins',strtotime($hour.":"."00")));
			if($key==23){
					$end_hours_format[$key.":59:59"]=DATE("g:i A",strtotime($hour.":"."59"));
			}
		} 
		if(!empty($service_id)) {
			$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'start_time';
			$order = isset($_GET['order']) ? $_GET['order'] : 'ASC';
			$service_slots=$this->ServiceSlot->getService_slotByservice_id($service_id, $sort_by, $order);
			$service_title=$this->Service->servieTitleByService_id($service_id);
		} 
		//save slots
		if(!empty($this->request->data) && $this->slot_validation()){
			
			$this->ServiceSlot->create(); 
			$this->ServiceSlot->save($this->request->data); 
			$this->redirect(array('action'=>'add_service_slots',$service_id));
			if(!empty($this->ServiceSlot->id)) {
				$this->Session->setFlash(__('Service slots has been added successfully.'));
			}
			else{
				$this->Session->setFlash(__('Service slots has been not added.', false));
			}
		}
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/'),
			'name'=>'Home'
		    );
		    
		$this->breadcrumbs[] = array(
			'url'=>Router::url(array('plugin'=>'vendor_manager','controller'=>'vendors','action'=>'dashboard')),
			'name'=>"Dashboard"
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url(array('plugin'=>'vendor_manager','controller'=>'services','action'=>'add_slots')),
			'name'=>'Add Slots'
		);
		 
		$this->set('service_id',$service_id);
		$this->set('default_service_price',$default_service_price);
		$this->set('service_title',$service_title);
		$this->set('hours_format',$hours_format);
		$this->set('end_hours_format',$end_hours_format);
		$this->set('service_slots',$service_slots);
	}

	// admin
	function admin_add_service_slots($vendor_id=null,$service_id=null) {
		$this->loadModel('VendorManager.ServiceSlot');
		// checking vendor is login or not
		// check service_id owner 
		if($this->Service->checkServiceById($vendor_id,$service_id)<=0) {
			$this->Session->setFlash(__('Are you doing something wrong?', false));
			
			$this->redirect(array('plugin'=>'vendor_manager','controller'=>'vendors', 'action' => 'index'));	 
		}
		$hours=range(0,23);		
		$hours_format=array();
		$end_hours_format=array();
		$service_slots=array();
		foreach($hours as $key=>$hour) {
			$hours_format[$key.":00:00"]=DATE("g:i A", STRTOTIME($hour.":"."00"));
			$hours_format[$key.":30:00"]=DATE("g:i A", STRTOTIME('+30mins',strtotime($hour.":"."00")));
		} 
		foreach($hours as $key=>$hour) {
			if($key==0){
				$index=date('H:i:s', strtotime($key.":00:00"));
			}else{
				$index=date('H:i:s', strtotime($key.":00:00") -1);
			}
			$end_hours_format[$index]=DATE("g:i A", STRTOTIME($hour.":"."00"));
			$end_hours_format[$key.":29:59"]=DATE("g:i A", STRTOTIME('+30mins',strtotime($hour.":"."00")));
			if($key==23){
					$end_hours_format[$key.":59:59"]=DATE("g:i A",strtotime($hour.":"."59"));
			}
		} 
		if(!empty($service_id)) {
			$service_slots=$this->ServiceSlot->getService_slotByservice_id($service_id);
			$service_title=$this->Service->servieTitleByService_id($service_id);
		} 
		//save slots
		if(!empty($this->request->data) && $this->slot_validation()){
			$this->ServiceSlot->create(); 
			$this->ServiceSlot->save($this->request->data); 
			 $this->redirect(array('action'=>'add_service_slots',$vendor_id,$service_id));
			if(!empty($this->ServiceSlot->id)) {
				$this->Session->setFlash(__('Service slots has been added successfully.'));
			}
			else{
				$this->Session->setFlash(__('Service slots has been not added.', false));
			}
		}
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/'),
			'name'=>'Home'
		    );
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/home/'),
			'name'=>'Home'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/vendor_manager/vendors'),
			'name'=>'Manage Vendor'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/vendor_manager/services/servicelist/'.$vendor_id),
			'name'=>$service_title
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/services/add_slots/'),
			'name'=>'Add Slots'
		);
		$service = $this->Service->find('first',['conditions'=>['id'=>$service_id]]);
		$default_service_price = $service['Service']['service_price'];
		$this->set('default_service_price',$default_service_price);
		$this->set('service_id',$service_id);
		$this->set('vendor_id',$vendor_id);
		$this->set('service_title',$service_title);
		$this->set('hours_format',$hours_format);
		$this->set('end_hours_format',$end_hours_format);
		$this->set('service_slots',$service_slots);
	}

	function slot_delete($service_id=null,$slot_id=null) {
		$this->loadModel('VendorManager.ServiceSlot');
		$vendor_id=$this->VendorAuth->id();
		if($this->Service->checkServiceById($vendor_id,$service_id)<=0) {
			$this->Session->setFlash(__('Are you doing something wrong?', false));
			$this->redirect($this->VendorAuth->loginRedirect);
		}
		$this->autoRender=false;
		$this->ServiceSlot->delete($slot_id);
		$this->Session->setFlash(__('Service slots has been deleted successfully'));
		$this->redirect(array('plugin'=>'vendor_manager','controller'=>'services', 'action' =>'add_service_slots',$service_id));	
	}

	function admin_slot_delete($service_id=null,$slot_id=null) {
		$this->autoRender=false;
		$this->loadModel('VendorManager.ServiceSlot');
		$this->ServiceSlot->delete($slot_id);
		$this->Session->setFlash(__('Service slots has been deleted successfully'));
		$this->redirect($this->referer());	
	}

	function value_added_delete($value_added_id=null,$service_id=null) {
		$this->loadModel('VendorManager.ValueAddedService');
		$vendor_id=$this->VendorAuth->id();
		if($this->Service->checkServiceById($vendor_id,$service_id)<=0) {
			$this->Session->setFlash(__('Are you doing something wrong?', false));
			$this->redirect($this->VendorAuth->loginRedirect);
		}
		$this->autoRender=false;
		$this->ValueAddedService->delete($value_added_id);
		$this->Session->setFlash(__('Value added service has been deleted successfully'));
		$this->redirect(array('plugin'=>'vendor_manager','controller'=>'services', 'action' =>'add_services',$service_id));	
	}

	function admin_value_added_delete($value_added_id=null,$service_id=null) {
		$this->loadModel('VendorManager.ValueAddedService');
		$this->autoRender=false;
		$this->ValueAddedService->delete($value_added_id);
		$this->Session->setFlash(__('Value added service has been deleted successfully'));
		$this->redirect($this->referer());	
	}

	private function _manage_image($image = array(),$destination=null) {
        if($destination==null){
			$destination=Configure::read('Image.SourcePath');
		}
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
                $image_name = time() . '_' . time() .$ext[0]. '.' . array_pop($ext);
                move_uploaded_file($image['tmp_name'], $destination . $image_name);
                if (!empty($existing_image)) {
                    @unlink($destination . $existing_image['image']);
                }
                return $image_name;
            }
        }
    }
  
    function admin_view_service($service_id=null){
		$this->layout='';
		$this->loadModel('ServiceManager.ServiceType');
		$this->loadModel('LocationManager.City');
		$this->loadModel('VendorManager.ServiceSlot');
		$this->loadModel('VendorManager.ValueAddedService');
		$this->loadModel('VendorManager.ServiceImage');
		$this->request->data = $this->Service->read(null,$service_id);
		$this->request->data['ValueAddedService']=$this->ValueAddedService->getValueaddedServiceByservice_id($service_id);
		$this->request->data['ServiceImage']=$this->ServiceImage->getServiceImageByservice_id($service_id);
		$this->request->data['ServiceSlot']=$this->ServiceSlot->getService_slotByservice_id($service_id);
		$service_types=$this->ServiceType->servicelist();
		$city_list=$this->City->getLocationList();
		$this->set('service_types',$service_types);	
		$this->set('city_list',$city_list);	
	}
	
	function admin_add_services($vendor_id=null,$service_id=null){
		$this->loadModel('ServiceManager.ServiceType');
		$this->loadModel('LocationManager.City');
		$this->loadModel('VendorManager.ValueAddedService');
		$this->loadModel('VendorManager.ServiceImage');
		// check service_id owner 
		if(!empty($service_id)){
			if($this->Service->checkServiceById($vendor_id,$service_id)<=0) {
				$this->Session->setFlash(__('Are you doing something wrong?', false));
				$this->redirect(array('plugin'=>'vendor_manager','controller'=>'vendors', 'action' => 'index'));	 
			} 
		}
		if(!empty($this->request->data) && self::validation()){
			if(empty($service_id)){
				$this->request->data['Service']['created_at']=date('Y-m-d H:i:s');
				$this->request->data['Service']['status']=1;
				$this->request->data['Service']['vendor_id']=$vendor_id;
				$this->request->data['Service']['youtube_url']=serialize($this->request->data['Service']['youtube_url']);
				$savemsg="added";
			}else{
				$this->request->data['Service']['updated_at']=date('Y-m-d H:i:s');
				$this->request->data['Service']['vendor_id']=$vendor_id;
				$this->request->data['Service']['youtube_url']=serialize(array_filter($this->request->data['Service']['youtube_url']));
				$savemsg="updated";
			}
			$this->Service->create();
			$this->Service->save($this->request->data);
			self::_vendor_add_service($this->Service->id);
			self::_add_service_image($this->Service->id);
			$this->Session->setFlash(__('Service has been '.$savemsg. ' successfully.'));
			$this->redirect(array('plugin'=>'vendor_manager','controller'=>'services', 'action' => 'admin_servicelist',$vendor_id));	
		}
		else{
			if($service_id!=null) {
				$this->request->data = $this->Service->read(null,$service_id);
				$breadcrumbs_msg="Update Service ";
				$this->request->data['ValueAddedService']=$this->ValueAddedService->getValueaddedServiceByservice_id($service_id);
				$this->request->data['ServiceImage']=$this->ServiceImage->getServiceImageDetailsByservice_id($service_id);
            }else{
				$this->request->data = array();
				$breadcrumbs_msg="Add Service";
            }
            $ajax_images = $this->Session->read($this->ajax_session_name);
            $file_paths=Configure::read('Image.AjaxPath');
            if(!empty($ajax_images)){
				foreach($ajax_images as $image){
					@unlink(Configure::read('Image.AjaxPath').$image);
				}
			}
			$this->Session->delete($this->ajax_session_name);
		}   
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/home/'),
			'name'=>'Home'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/vendor_manager/vendors'),
			'name'=>'Manage Vendor'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/services/add_services/'),
			'name'=>$breadcrumbs_msg
		);
		// list of services 
		$service_types=$this->ServiceType->servicelist();
		$city_list=$this->City->getLocationList();
		$this->set('service_types',$service_types);	
		$this->set('city_list',$city_list);	
		$this->set('service_id',$service_id);
		$this->set('vendor_id',$vendor_id);
		$participantsNumList= [];
		for($x=1; $x<=100; $x++){
			$participantsNumList[$x] = $x;
		}
		$this->set('participants_num_list',$participantsNumList);
	}
	
	private function DeleteServiceImage($service_id=null){
		$image_path= Configure::read('Image.SourcePath');
		$this->loadModel('VendorManager.ServiceImage');
		$serviceimages=$this->ServiceImage->find('all',array('conditions'=>array('ServiceImage.service_id'=>$service_id)));
		// delete all service images here
		if(!empty($serviceimages)) {
			foreach($serviceimages as $service_image){
				@unlink($image_path.$service_image['ServiceImage']['image']);
				$this->ServiceImage->delete($service_image['ServiceImage']['id'],true,false);
			}
		}
	}
	
	private function DeleteServiceReview($service_id=null){
		$this->loadModel('VendorManager.ServiceReview');
		//Deleted all service Review by service id
		$this->ServiceReview->deleteAll(array('ServiceReview.service_id'=>$service_id));
	}
	
	function ajax_sort(){
		$this->autoRender = false;
		foreach($_POST['sort'] as $order => $id){
			$service= array();
			$service['Service']['id'] = $id;
			$service['Service']['reorder'] = $order;
			$this->Service->create();
			$this->Service->save($service,array('validate' => false));
		}
    }
}
?>
