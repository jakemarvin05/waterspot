<?php 
Class VendorServiceAvailabilitiesController extends VendorManagerAppController{
	public $uses = array('VendorManager.VendorServiceAvailability');
	public $components = array('Email');
	public $paginate = array();
	public $id = null;

	function beforeFilter(){
		parent::beforeFilter();
		$this->VendorAuth->deny_action =array('index');
	}
	
	function index($service_id=null,$availability_id=null) {
		// load model
            array_push(self::$css_for_layout,'vendor/vendor-panel.css');
		$this->loadModel('VendorManager.Service');
		$this->loadModel('VendorManager.ServiceSlot');
		$vendor_id=$this->VendorAuth->id();
		// check owner service id by vendor id 
		if($this->Service->checkServiceById($vendor_id,$service_id)<=0) {
			$this->Session->setFlash(__('Are you doing something wrong?', false));
			$this->redirect($this->VendorAuth->loginRedirect);
		} 
		// check validation and request data
		if(!empty($this->request->data) && $this->validation()){
			if(empty($id)){
				$this->request->data['VendorServiceAvailability']['created_at']=date('Y-m-d H:i:s');
				$this->request->data['VendorServiceAvailability']['status']=1;
			}
			else{
				$this->request->data['VendorServiceAvailability']['updated_at']=date('Y-m-d H:i:s');
			}
			// slot change in json encode
			$filter_slots=array();
			$cc = '1';
			foreach($this->request->data['VendorServiceAvailability']['slots'] as $slot_time){
				if(empty($slot_time)){
					continue;
				}
				$times = explode('_', $slot_time);
				$new_slot = new stdClass();
				$new_slot->start_time = $times[0];
				$new_slot->end_time = $times[1];
				$new_slot->price = (int) $times[2];
				$filter_slots[$cc]=$new_slot;
				$cc = $cc + 1;
				$cc = (string) $cc;
			}
			
			if(!empty($this->request->data['VendorServiceAvailability']['start_date'])){
				$this->request->data['VendorServiceAvailability']['start_date']=date('Y-m-d',strtotime($this->request->data['VendorServiceAvailability']['start_date']));
				$this->request->data['VendorServiceAvailability']['end_date']=date('Y-m-d',strtotime($this->request->data['VendorServiceAvailability']['end_date']));
			}
			$this->request->data['VendorServiceAvailability']['vendor_id']=$vendor_id;
			$this->request->data['VendorServiceAvailability']['slots']='['.substr(json_encode($filter_slots), 1, -1).']';
			// data clear of on date range to particular field 
			if(!empty($this->request->data['VendorServiceAvailability']['p_date'])) {
				$this->request->data['VendorServiceAvailability']['start_date']=NULL;
				$this->request->data['VendorServiceAvailability']['end_date']=NULL;
				 
			} else{
				// if start date then particular field will be set null 
				$this->request->data['VendorServiceAvailability']['p_date']=NULL;
			}
			// save vendorservice availability here
			$this->VendorServiceAvailability->create();
			$this->VendorServiceAvailability->save($this->request->data,array('validate' => false));
			if ($this->request->data['VendorServiceAvailability']['id']) {
				$this->Session->setFlash(__('Vendor Service Availability has been updated successfully'));
				} 
				else {
					$this->Session->setFlash(__('Vendor Service Availability has been added successfully'));
				}
			$this->redirect(array('plugin'=>'vendor_manager','controller'=>'vendor_service_availabilities','action'=>'index',$service_id));
		}
		if(!empty($availability_id)) {
			$data=$this->VendorServiceAvailability->read(null,$availability_id);
			$this->request->data['VendorServiceAvailability']['id']=$data['VendorServiceAvailability']['id'];
			$this->request->data['VendorServiceAvailability']['slots']= json_decode($data['VendorServiceAvailability']['slots']);
			if(empty($data['VendorServiceAvailability']['p_date'])){
				$this->request->data['VendorServiceAvailability']['start_date']=date(Configure::read('Calender_format_php'),strtotime($data['VendorServiceAvailability']['start_date']));
				$this->request->data['VendorServiceAvailability']['end_date']=date(Configure::read('Calender_format_php'),strtotime($data['VendorServiceAvailability']['end_date']));
				
			}
			else{
				$this->request->data['VendorServiceAvailability']['start_date']=date(Configure::read('Calender_format_php'),strtotime($data['VendorServiceAvailability']['p_date']));
			}
		}
		// listing recent availability
		$service_availabity_details=$this->VendorServiceAvailability->find('all',array('conditions'=>array('VendorServiceAvailability.service_id'=>$service_id,'Or'=>array(array('VendorServiceAvailability.p_date >='=>date('Y-m-d')),array('VendorServiceAvailability.end_date >='=>date('Y-m-d'))))));
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/'),
			'name'=>'Home'
		    );
		    $this->breadcrumbs[] = array(
				'url'=>Router::url('/vendor/dashboard/'),
				'name'=>'Dashboard'
			);
			$this->breadcrumbs[] = array(
                'url'=>Router::url('vendor/vendor_service_availabilities/'),
                'name'=>'My Slot Availability'
		    );
		    $service_slots=$this->ServiceSlot->getSlotByServiceID($service_id);
		   
		    $this->set('service_availabity_details',$service_availabity_details);
		    $this->set('service_slots',$service_slots);
		    $this->set('service_id',$service_id);
		    if ($availability_id != null) {
			    $serviceAvailabilitySlots = [];
			    foreach (json_decode('{' . substr($data['VendorServiceAvailability']['slots'], 1, -1) . '}') as $slot) {
			    	$serviceAvailabilitySlots[] = $slot->start_time . '_' . $slot->end_time . '_' . $slot->price;
			    }
			    $this->set('serviceAvailabilitySlots', $serviceAvailabilitySlots);
			}
	}
	function admin_index($vendor_id=null,$service_id=null,$availability_id=null) {
		// load model
		$this->loadModel('VendorManager.Service');
		$this->loadModel('VendorManager.ServiceSlot');
		// check owner service id by vendor id 
		if($this->Service->checkServiceById($vendor_id,$service_id)<=0) {
			$this->Session->setFlash(__('Are you doing something wrong?', false));
			$this->redirect(array('plugin'=>'vendor_manager','controller'=>'vendors', 'action' => 'index'));	 
		} 
		
		if(!empty($this->request->data) && $this->validation()){
			if(empty($id)){
				$this->request->data['VendorServiceAvailability']['created_at']=date('Y-m-d H:i:s');
				$this->request->data['VendorServiceAvailability']['status']=1;
			}
			else{
				$this->request->data['VendorServiceAvailability']['updated_at']=date('Y-m-d H:i:s');
			}

			// slot change in json encode
			$filter_slots=array();
			$cc = '1';
			foreach($this->request->data['VendorServiceAvailability']['slots'] as $slot_time){
				if(empty($slot_time)){
					continue;
				}
				$times = explode('_', $slot_time);
				$new_slot = new stdClass();
				$new_slot->start_time = $times[0];
				$new_slot->end_time = $times[1];
				$new_slot->price = (int) $times[2];
				$filter_slots[$cc]=$new_slot;
				$cc = $cc + 1;
				$cc = (string) $cc;
			}

			if(!empty($this->request->data['VendorServiceAvailability']['start_date'])){
				$this->request->data['VendorServiceAvailability']['start_date']=date('Y-m-d',strtotime($this->request->data['VendorServiceAvailability']['start_date']));
				$this->request->data['VendorServiceAvailability']['end_date']=date('Y-m-d',strtotime($this->request->data['VendorServiceAvailability']['end_date']));
			}
			$this->request->data['VendorServiceAvailability']['vendor_id']=$vendor_id;
			$this->request->data['VendorServiceAvailability']['slots']=json_encode($filter_slots);
			// data clear of on date range to particular field 
			if(!empty($this->request->data['VendorServiceAvailability']['p_date'])) {
				$this->request->data['VendorServiceAvailability']['start_date']=NULL;
				$this->request->data['VendorServiceAvailability']['end_date']=NULL;
			} else{
				// if start date then particular field will be set null 
				$this->request->data['VendorServiceAvailability']['p_date']=NULL;
			}
			// save vendorservice availability here
			$this->VendorServiceAvailability->create();
			$this->VendorServiceAvailability->save($this->request->data,array('validate' => false));
			
			if($this->request->data['VendorServiceAvailability']['id']) {
				$this->Session->setFlash(__('Vendor Service Availability has been updated successfully'));
			} 
			else {
				$this->Session->setFlash(__('Vendor Service Availability has been added successfully'));
			}
				
			$this->redirect(array('plugin'=>'vendor_manager','controller'=>'vendor_service_availabilities','action'=>'index',$vendor_id,$service_id,$availability_id));
		}
		if(!empty($availability_id)) {
			$data=$this->VendorServiceAvailability->read(null,$availability_id);
			$this->request->data['VendorServiceAvailability']['id']=$data['VendorServiceAvailability']['id'];
			$this->request->data['VendorServiceAvailability']['slots']= json_decode($data['VendorServiceAvailability']['slots']);
			if(empty($data['VendorServiceAvailability']['p_date'])){
				$this->request->data['VendorServiceAvailability']['start_date']=date(Configure::read('Calender_format_php'),strtotime($data['VendorServiceAvailability']['start_date']));
				$this->request->data['VendorServiceAvailability']['end_date']=date(Configure::read('Calender_format_php'),strtotime($data['VendorServiceAvailability']['end_date']));
			}
			else{
				$this->request->data['VendorServiceAvailability']['start_date']=date(Configure::read('Calender_format_php'),strtotime($data['VendorServiceAvailability']['p_date']));
			}
		}
		// listing recent availability
		$service_availabity_details=$this->VendorServiceAvailability->find('all',array('conditions'=>array('VendorServiceAvailability.service_id'=>$service_id,'Or'=>array(array('VendorServiceAvailability.p_date >='=>date('Y-m-d')),array('VendorServiceAvailability.end_date >='=>date('Y-m-d'))))));
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/'),
			'name'=>'Home'
			);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/home/'),
			'name'=>'Home'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/vendor_manager/services/servicelist/'.$vendor_id),
			'name'=>'Manage Vendor'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/vendor_manager/vendor_service_availabilities/index/'.$vendor_id.'/'.$service_id),
			'name'=>'Service Availability'
		);		   
		$service_slots=$this->ServiceSlot->getSlotByServiceID($service_id);
		$this->set('service_availabity_details',$service_availabity_details);
		$this->set('service_slots',$service_slots);
		$this->set('service_id',$service_id);
		$this->set('vendor_id',$vendor_id);
	}
		
	function validation($action=null){
		// set particular date if end time is not available
		if(empty($this->request->data['VendorServiceAvailability']['end_date'])){
			if(!empty($this->request->data['VendorServiceAvailability']['start_date'])){
				$this->request->data['VendorServiceAvailability']['form-name']='particular';
				$this->request->data['VendorServiceAvailability']['p_date']=date('Y-m-d',strtotime($this->request->data['VendorServiceAvailability']['start_date']));
			}
		}
		if($this->request->data['VendorServiceAvailability']['form-name'] =='date_range'){
			$this->VendorServiceAvailability->setValidation('date_range');
		}
		else if($this->request->data['VendorServiceAvailability']['form-name']=='particular'){
			$this->VendorServiceAvailability->setValidation('particular');
		}
		$this->VendorServiceAvailability->set($this->request->data);
		$result = array();
		if ($this->VendorServiceAvailability->validates()) {
			  $result['error'] = 0;
		}else{
			  $result['error'] = 1;
		}
		if($this->request->is('ajax')) {
			$this->autoRender = false;
			$result['errors'] = $this->VendorServiceAvailability->validationErrors;
			$errors = array();
			foreach($result['errors'] as $field => $data){
			  $errors['VendorServiceAvailability'.Inflector::camelize($field)] = array_pop($data);
			}
			$result['errors'] = $errors;
			echo json_encode($result);
				return;
		}
			return (int)($result['error'])?0:1;
	}
	
	function ajax_get_availbility_range() { 
		$this->autoRender=false;
		$this->loadModel('VendorManager.ServiceSlot');
		if(!empty($_POST)){
			//find the availability between date range
			$service_availabity_details=$this->VendorServiceAvailability->getServiceAvailablityByDates($_POST['service_id'],date('Y-m-d',strtotime($_POST['start_date'])),date('Y-m-d',strtotime($_POST['end_date'])));
			//$slots=$this->ServiceSlot->getSlotBySlotID($slots_id);
			$service_availabity_final_detals=array();
			foreach($service_availabity_details as $key=>$service_availabity_detail){
				$slots_id=json_decode($service_availabity_detail['VendorServiceAvailability']['slots']);
				 $service_availabity_final_detals[$key]['id']=$service_availabity_detail['VendorServiceAvailability']['id'];
				 $service_availabity_final_detals[$key]['date_range']=date(Configure::read('Calender_format_php'),strtotime($service_availabity_detail['VendorServiceAvailability']['start_date']))." to ".date(Configure::read('Calender_format_php'),strtotime($service_availabity_detail['VendorServiceAvailability']['end_date']));
				 $service_availabity_final_detals[$key]['slots']=implode(',',$this->ServiceSlot->getSlotBySlotID($slots_id));
			}  
			echo json_encode($service_availabity_final_detals);
			return true;
		} 
	}
	
	function availability_del($service_id=null,$availability_id=null) {
		if(!empty($availability_id)){
			$this->autoRender=false;
			$this->VendorServiceAvailability->delete($availability_id);
			$this->Session->setFlash(__('Vendor service availability slot has been deleted successfully'));
			$this->redirect(array('plugin'=>'vendor_manager','controller'=>'vendor_service_availabilities', 'action' =>'index',$service_id));	
		}
		else{
			 $this->redirect(array('plugin'=>'vendor_manager','controller'=>'vendor_service_availabilities', 'action' =>'index',$service_id));	
		}
	}
	
	function admin_availability_del($vendor_id=null,$service_id=null,$availability_id=null) {
		if(!empty($availability_id)){
			 
			$this->autoRender=false;
			$this->VendorServiceAvailability->delete($availability_id);
			$this->Session->setFlash(__('Vendor service availability slot has been deleted successfully'));
		}
		 else{
			 $this->Session->setFlash(__('Vendor service availability slot has not deleted ', true),'default','','error');
		 }
		 $this->redirect(array('plugin'=>'vendor_manager','controller'=>'vendor_service_availabilities', 'action' =>'index',$vendor_id,$service_id));
	}	
 }
?>
