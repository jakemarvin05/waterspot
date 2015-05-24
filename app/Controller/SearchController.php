<?php
Class SearchController extends AppController{
	public $uses = array('ServiceManager.Service');
	public $components = array('VendorManager.ServiceFilter');
	public $paginate = array();
    
	function index($service_id=null,$start_date=null,$end_date=null,$no_of_participants=null,$sort_by_price=null,$sort_by_review=null){
		// load model 
		$this->loadModel('ServiceManager.ServiceType');
		$this->loadModel('VendorManager.Service');
		$this->loadModel('VendorManager.ServiceImage');
		// add javascript
		array_push(self::$script_for_layout,array('jquery.contenthover.min.js',$this->setting['site']['jquery_plugin_url'].'ratings/jquery.rating.js'));
		array_push(self::$css_for_layout,array($this->setting['site']['jquery_plugin_url'].'ratings/jquery.rating.css'));
		if (!empty($this->request->data)) {
			//session write 
			if(!empty($this->request->data['Search']['start_date'])) {
				$this->Session->write('Activity.start_date',$this->request->data['Search']['start_date']);
			}
			if(!empty($this->request->data['Search']['end_date'])) {
				$this->Session->write('Activity.end_date',$this->request->data['Search']['end_date']);
			}
			if(!empty($this->request->data['Search']['no_participants'])) {
				$this->Session->write('Activity.no_participants',$this->request->data['Search']['no_participants']);
			}
			if ($this->request->data['Search']['service_id']) {
				$service_id = $this->request->data['Search']['service_id'];
			}
			else {
				$service_id = 'service-id';
			}
			if ($this->request->data['Search']['start_date']) {
				$start_date = strtotime($this->request->data['Search']['start_date']);
			}else {
				$start_date = 'start-date';
			}
			if ($this->request->data['Search']['end_date']) {
				$end_date = strtotime($this->request->data['Search']['end_date']);
			}
			else{
				$end_date = 'end-date';
			}
			if ($this->request->data['Search']['no_participants']) {
				$no_of_participants = $this->request->data['Search']['no_participants'];
			}
			else {
				$no_of_participants = 'participant';
			}
			$this->redirect(array('plugin'=>false,'controller'=>'search','action'=>'index',$service_id,$start_date,$end_date,$no_of_participants));
		}
		// searching list 
		$service_name='';
		$conditions=array();
		$options = array();
		$booked_services = array();
		$service_type_id=$service_id;
		//final 
		if ($service_type_id != null && $service_type_id != 'service-id') {
			// this is ued for title in select defult by searvice typeid
			$service_name=$this->ServiceType->getServiceNameById($service_type_id);
			$this->request->data['Search']['service_type_list']=$conditions['Service.service_type_id'] = $service_type_id;
		}
		else{
			$this->request->data['Search']['service_type_list']='';
		}
		if(!empty($sort_by_price) && $sort_by_price != 'sortbyprice') {
			$price_range=explode('-',$sort_by_price);
			$conditions[] = array('OR'=>array(
				array('Service.service_price BETWEEN ? AND ?'=>array($price_range[0],$price_range[1])))); 	 
		}
		$searchData=array($service_id,$start_date,$end_date,$no_of_participants);
		$booked_services=$this->ServiceFilter->get_search_filter($searchData);
		 
		$subQuery = "(SELECT AVG(ifnull((`ServiceReview`.`rating`), 0)) FROM service_reviews AS `ServiceReview` WHERE `ServiceReview`.`service_id` = `Service`.`id` and `ServiceReview`.`status` = 1 GROUP BY `ServiceReview`.`service_id`) AS rating ";
		$this->paginate = array();
		//$this->paginate['fields'] = array('Service.id');
		$this->paginate['fields'] = array('Service.id','Service.service_title','Service.service_price','Service.description',$subQuery	);
		$this->paginate['joins'] = array(
						array(
							'table' => 'vendors',
							'alias' => 'Vendor',
							'type' => 'inner',
							'conditions' => array('Vendor.id = Service.vendor_id')
						),
						array(
							'table' => 'cities',
							'alias' => 'City',
							'type' => 'LEFT',
							'conditions' => array('City.id =Service.location_id')
						),
					);
		$this->paginate['limit'] = Configure::read('Activiy.Limit');
		$this->paginate['order'] = array('Service.reorder asc');
		$this->paginate['group'] = array('Service.id');
		if ($sort_by_review != null && $sort_by_review != 'sortbyreview') {
			$this->request->data['Search']['sort_review']=$sort_by_price;
			if(intval($sort_by_review)==1){ 
				$this->paginate['order'] = "rating DESC";
			}else{
				$this->paginate['order'] = "rating ASC";
			}
		}
		$conditions[]=array('AND'=>array('Vendor.active'=>1,'Service.status'=>1),'OR'=>array('Vendor.payment_status'=>1 ,'Vendor.account_type'=>0));
		//,'NOT' => array( 'Service.id' => $booked_services 
		$this->paginate['conditions'][] = $conditions;
		$search_service_lists = $this->paginate('Service');
		$new_search_service_lists =array();
		// tag 0- free ,1 for booked
		foreach($search_service_lists as $key=>$search_service_list) {
			$search_service_list['image']=$this->ServiceImage->getOneimageServiceImageByservice_id($search_service_list['Service']['id']);
			$search_service_list['rating']= (round($search_service_list[0]['rating']));
			$search_service_list['tag']=(in_array($search_service_list['Service']['id'], $booked_services))?1:0;
			$new_search_service_lists[$key]=$search_service_list;
		}
		// left side all service type
		$this->set('search_service_lists',$new_search_service_lists);
		if(empty($service_type_list)){
			$this->loadModel('ServiceManager.ServiceType');
			$service_type_list = $this->ServiceType->find('list',array('fields'=>array('ServiceType.id','ServiceType.name'),'conditions'=>array('ServiceType.status'=>1),'order'=>array('ServiceType.reorder ASC')));
			Cache::write('cake_service_list',$service_type_list);
		}
		$this->set('service_type_list',$service_type_list);
		// set css and script
		$this->breadcrumbs[] = array(
                'url'=>Router::url('/'),
                'name'=>'Home'
            );
            $this->breadcrumbs[] = array(
                'url'=>Router::url('search'),
                'name'=>$service_name
            );
        $this->set('service_id',$service_id);
        $this->set('sort_by_price',$sort_by_price);
        $this->set('start_date',$start_date);
        $this->set('end_date',$end_date);
        $this->set('no_of_participants',$no_of_participants);
        // set page title and description 
        $this->title_for_layout = "Search result of ". strtolower($service_type_list[$service_id]);
		$this->metakeyword = strtolower($service_type_list[$service_id]);
		$this->metadescription = strtolower($service_type_list[$service_id]);
        if($this->request->is('ajax')){
			$this->layout = '';
			$this -> Render('ajax_search_index');
        }  
	}
} 
?>
