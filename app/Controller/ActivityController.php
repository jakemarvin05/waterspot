<?php
Class ActivityController extends AppController{
	public $uses = array('Activity');
	public $components = array('VendorManager','MemberManager.MemberAuth','VendorManager.ServiceFilter');
	public $paginate = array();
	public $id = null;
	
	public function beforeFilter(){
		parent::beforeFilter();
		Configure::load('VendorManager.config');	
	}
	
	function index($service_id=null,$cart_id=null){
		//load model
		$this->loadModel('VendorManager.Vendor');
		$this->loadModel('VendorManager.Service');
		$this->loadModel('VendorManager.ServiceImage');
		$this->loadModel('VendorManager.ServiceReview');
		$this->loadModel('VendorManager.ValueAddedService');
		$this->loadModel('LocationManager.City');
		$this->loadModel('Cart');
		$this->loadModel('ServiceManager.ServiceType'); 
		// check service 
		$no_of_booking_days=0;
		$service_status=$this->Service->CheckServiceId($service_id);
		if($service_status==0){ 
				throw new NotFoundException('This service is deactivated.');
		}
		// check cart valid 
		if(!empty($cart_id)) {
			$cart_id_status=$this->Cart->CheckCartId($cart_id,$this->Session->id());
			if($cart_id_status==0){ 
				throw new NotFoundException('Cart is empty or deactivated.');
			}
		 }
		 // load MemberAuth component 
		App::uses('MemberAuthComponent', 'MemberManager.Controller/Component');
		$this->sessionKey = MemberAuthComponent::$sessionKey;		
		$this->member_data = $this->Session->read($this->sessionKey);
		// Load java script and css
		array_push(self::$script_for_layout,'login.js','jquery.tools.min.js','jquery.mousewheel.js','jquery.jscrollpane.min.js','fotorama.js','http://code.jquery.com/ui/1.10.3/jquery-ui.js','jquery.fancybox.js','responsive-tabs.js',$this->setting['site']['jquery_plugin_url'].'ratings/jquery.rating.js','http://w.sharethis.com/button/buttons.js');
		array_push(self::$css_for_layout,'activity.css','jquery.jscrollpane.css','fotorama.css','http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css','responsive-tabs.css',$this->setting['site']['jquery_plugin_url'].'ratings/jquery.rating.css');
		self::$scriptBlocks[]='
		$( document ).ready(function() {
			 get_service_availability();
			});
		$(function() {
			$( "#ActivityStartDate" ).datepicker({
			dateFormat: "'.Configure::read('Calender_format').'",
			minDate: 0,
			changeMonth: true,
			onSelect:function(selectedDate){
			$( "#ActivityEndDate" ).datepicker( "option", "minDate", selectedDate );
			 $(this).change();
		  }
		});
		$( "#ActivityEndDate" ).datepicker({
			dateFormat: "'.Configure::read('Calender_format').'",
			minDate: 0,
			changeMonth: true,
			onSelect:function(selectedDate){
			$( "#ActivityStartDate" ).datepicker( "option", "maxDate", selectedDate );
			$(this).change();
		  }
		});	
		 
		// for ajax load slots
			$("#ActivityNoParticipants,#ActivityStartDate , #ActivityEndDate").bind("change",function(){
				var participant_no=$("#ActivityNoParticipants").val();
				if(0>=participant_no){
					$( "#ActivityNoParticipants").val(1);
					//alert("Participant no should be greater than zero");
				}
				get_service_availability();	
			});

		}); 
		$( document ).ready(function() {
		 $( "#ActivityStartDate" ).click();
		});
		$(document).ready(function() {
			$(\'.fancybox\').fancybox();
		});
		stLight.options({publisher: "5d0165c7-537f-40b4-8ecd-7ef5d49cceb2"});' ;
		$service_detail = array();
		$service_detail=$this->Service->servieDetailByService_id($service_id);
		
		// get vendor service details
		
		if(!empty($service_detail)){
			
			$vendor_details=$this->Vendor->vendorDetailId($service_detail['Service']['vendor_id']);
			$vendor_details['Vendor']['rating'] = $this->ServiceReview->getVendorRatings($service_detail['Service']['vendor_id']);
			//get services  
			 
			$vendor_details['Service']=$this->Service->serviceListVendorById($vendor_details['Vendor']['id']);
			// get related similar service
			
			if(!empty($service_detail['Service']['vendor_id'])){
				$related_services=array();
				$related_services=$this->Service->findRelatedServiceByVendor($service_detail['Service']['vendor_id'],$service_detail['Service']['id']);
				if(!empty($related_services)){
					foreach($related_services as $related_service){
						$related_service['Service']['image']=$this->ServiceImage->getOneimageServiceImageByservice_id($related_service['Service']['id']);
						$service_detail['VendorService'][]=$related_service;
					}
				}
			}
		}
		// get service review 
		$service_detail['Review']=$this->ServiceReview->getServiceReviewByservice_id($service_id);
		$service_detail['image']=$this->ServiceImage->getServiceImageByservice_id($service_id);
		$service_detail['location_name']= $this->City->getLocationListCityID($service_detail['Service']['location_id']);
		$service_detail['service_type']= $this->ServiceType->getServiceTypeNameById($service_detail['Service']['service_type_id']);
		// invite friend after add card table 
		$cart_details=array();
		$cart_slots=array();
		if(!empty($cart_id)) {
			$criteria = array();
			$criteria['fields']= array('Cart.*','Service.service_title');
			$criteria['joins'] = array(
				array(
					'table' => 'services',
					'alias' => 'Service',
					'type' => 'INNER',
					'conditions' => array('Service.id = Cart.service_id')
				) 
        	);
			$criteria['conditions'] =array('Cart.session_id'=>$this->Session->id(),'Cart.id'=>$cart_id);
			$criteria['order'] =array('Cart.id DESC');
			$cart_details=$this->Cart->find('first', $criteria);
			$cart_details['Cart']['image']=$this->ServiceImage->getOneimageServiceImageByservice_id($cart_details['Cart']['service_id']);
			$cart_slots=json_decode($cart_details['Cart']['slots'],true);
			if(!empty($cart_slots)){
				$cart_details['Cart']['slots']=$cart_slots['Slot'];
			}
			// get Value added Service
			$value_added_services=array();
			if(!empty($cart_details['Cart']['service_id'])) {
				$value_added_services=$this->ValueAddedService->getValueaddedServiceByservice_id($cart_details['Cart']['service_id']);
			}
			// assign value added services
			$cart_details['Cart']['value_added_services']=$value_added_services; 
			$diff = abs(strtotime($cart_details['Cart']['end_date']) - strtotime($cart_details['Cart']['start_date']));
				$years = floor($diff / (365*60*60*24));
				$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
				$no_of_booking_days =(floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24)))+1;
		} 
		// assign search value in input box
		$search_detais=$this->Session->read('Activity');
		if(!empty($search_detais)) {
			$this->request->data['Activity']['start_date']=$this->Session->read('Activity.start_date');
			$this->request->data['Activity']['end_date']=$this->Session->read('Activity.end_date');
			$this->request->data['Activity']['no_participants']=$this->Session->read('Activity.no_participants');
		}
		$this->Session->delete('Activity');
		$this->breadcrumbs[] = array(
                'url'=>Router::url('/'),
                'name'=>'Home'
            );
            $this->breadcrumbs[] = array(
                'url'=>Router::url(array('controller'=>'activity','action'=>'activities','vendor_id',$service_detail['Service']['service_type_id'])),
                'name'=>$service_detail['service_type']
            );
            $this->breadcrumbs[] = array(
                'url'=>Router::url('/'),
                'name'=>ucfirst($service_detail['Service']['service_title'])
            ); 
          
        // set page title and description 
        $this->title_for_layout = ucfirst($service_detail['Service']['service_title']);
		//$this->metakeyword = ucfirst($service_detail['Service']['description']);
		$this->metadescription = ucfirst(strip_tags($service_detail['Service']['description']));
        $this->set('no_of_booking_days',$no_of_booking_days);
        $this->set('cart_id',$cart_id);
		$this->set('service_id',$service_id);
		$this->set('cart_details',$cart_details);
		$this->set('vendor_details',$vendor_details);
		$this->set('service_detail',$service_detail);
		$this->set('member_id',$this->member_data['MemberAuth']['id']);
	}
	function ajax_get_availbility_range(){
		$this->layout='';
		$this->loadModel('VendorManager.ServiceSlot');
		$this->loadModel('VendorManager.VendorServiceAvailability');

		if(!empty($_POST)) {
			$_POST['start_date'] = date("Y-m-d",strtotime($_POST['start_date']));
			if(!empty($_POST['end_date'])){
				$_POST['end_date'] = date("Y-m-d",strtotime($_POST['end_date']));
			}else {
				$_POST['end_date'] = date("Y-m-d",strtotime($_POST['start_date']));
			}
			// component
			$new_service_slots=$this->ServiceFilter->activities_filter($_POST);
			$diff = abs(strtotime($_POST['end_date']) - strtotime($_POST['start_date']));
			$years = floor($diff / (365*60*60*24));
			$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
			$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
			if($days>0){
				//$this->render=false;
				//die; 
			}
			$new_service_slots=$this->VendorServiceAvailability->getSlotByServiceID($_POST);
			if (empty($new_service_slots)) {
				$recommended_dates = $this->VendorServiceAvailability->getRecomendedDates($_POST);
				$this->set('recommended_dates',$recommended_dates);
			}
			$this->set('service_slots',$new_service_slots);
		}
	}
		
	function add_to_card(){
		$this->autoRendor=false;
		
		$this->loadModel('VendorManager.Service');
		$this->loadModel('VendorManager.ServiceImage');
		$this->loadModel('Cart');
		$this->loadModel('VendorManager.Vendor');
		App::uses('MemberAuthComponent', 'MemberManager.Controller/Component');
		$this->sessionKey = MemberAuthComponent::$sessionKey;		
		$this->member_data = $this->Session->read($this->sessionKey);
		if(!empty($this->request->data) && $this->validation()) {
			//check Guest check email or  
			$guest_email=$this->Session->read('Guest_email');
			$data['Cart']=$this->request->data['Activity'];
			$slot_data=array();
			//get price of service  by id
			$service_detail=$this->Service->servieDetailByService_id($data['Cart']['service_id']);
			$service_price=(!empty($service_detail['Service']['service_price']))?$service_detail['Service']['service_price']:0;
			if(!empty($this->request->data['Activity']['slots'])) {
				foreach($this->request->data['Activity']['slots'] as $key=>$slot){
					 // if slot is not selected then contiue
					if($slot==0){
						continue;
					}
					$slot_booking_details=explode('_',$slot);
					// slot attributes
					$slot_booking_detail=array();
					foreach($slot_booking_details as $slot_key=>$slot_attb) {
						if($slot_key==0)$slot_booking_type='slot_date';
						if($slot_key==1)$slot_booking_type='service_id';
						if($slot_key==2)$slot_booking_type='slot_id';
						if($slot_key==3)$slot_booking_type='start_time';
						if($slot_key==4)$slot_booking_type='end_time'; 
						//
						$slot_booking_detail[$slot_booking_type]=$slot_attb;
					}
					// check slot booking
					$slotdata=array();
					$slotdata=$slot_booking_detail;
					$slotdata['no_participants']=$this->request->data['Activity']['no_participants'];
					$booking_status=$this->ServiceFilter->slot_filter($slotdata);
					if(empty($booking_status)){
						$this->Session->setFlash('Some slots have been booked. Please select another slots.','default','','error');
						$this->redirect($this->referer());
						throw new NotFoundException('Some slots have been booked. Please select another slots.');
					}
					$slot_data['Slot'][$key]=$slot_booking_detail;
				}
			// calculate no of slot price  
			$no_of_slots=count($slot_data['Slot']);
			$total_slot_price=($no_of_slots >0 &&  $service_price>0)?$service_price*$no_of_slots:0;
			$full_day_status=0;
			}else {
				$diff = abs(strtotime($data['Cart']['end_date']) - strtotime($data['Cart']['start_date']));
				$years = floor($diff / (365*60*60*24));
				$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
				$days =(floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24)))+1;
				if($days>0){
					$full_day_status=1;
					$service_price=$service_detail['Service']['full_day_amount'];
					$total_slot_price=$service_detail['Service']['full_day_amount']*$days;	
				}
			}
			if(!empty($this->member_data['MemberAuth']['id'])) {
				 $data['Cart']['member_id']=$this->member_data['MemberAuth']['id'];
			} 
			// service image by service id
			$service_image=$this->ServiceImage->getOneimageServiceImageByservice_id($data['Cart']['service_id']);
			App::uses('ImageResizeHelper','View/Helper');
			$ImageComponent = new ImageResizeHelper(new View());
			$path=WWW_ROOT.'img'.DS.'service_images'.DS;
			$siteurl=$this->setting['site']['site_url'];
			$imgArr = array('source_path'=>$path,'img_name'=>$service_image,'width'=>80,'height'=>80);
			$image_name = $siteurl."/img/".$ImageComponent->ResizeImage($imgArr);
			$data['Cart']['booking_date']=date('Y-m-d H:i:s');
			$data['Cart']['price']=$service_price;
			$data['Cart']['total_amount']=$total_slot_price;
			$data['Cart']['full_day_status']=$full_day_status;
			$data['Cart']['vendor_id']=$service_detail['Service']['vendor_id'];
			$data['Cart']['service_title']=$service_detail['Service']['service_title'];
			$data['Cart']['start_date']=date('Y-m-d',strtotime($data['Cart']['start_date']));
			$data['Cart']['end_date']=date('Y-m-d',strtotime($data['Cart']['end_date']));
			$data['Cart']['time_stamp']=date('Y-m-d H:i:s');
			$data['Cart']['mail_image']=$image_name;
			$data['Cart']['location_id']=$service_detail['Service']['location_id'];
			$data['Cart']['guest_email']=$guest_email;
			$data['Cart']['session_id']=$this->Session->id();
			// add vendor details
			$data1=$this->Vendor->vendorDetailId($service_detail['Service']['vendor_id']);
			if(!empty($data1)){
				$data['Cart']['vendor_name']=$data1['Vendor']['fname']." ".$data1[	'Vendor']['lname'];
				$data['Cart']['vendor_email']=$data1['Vendor']['email'];
				$data['Cart']['vendor_phone']=$data1['Vendor']['phone'];
			}
			if(!empty($slot_data)){
				$data['Cart']['slots']=json_encode($slot_data);
			} 
			$this->Cart->create();
			$this->Cart->save($data);
			$cart_id=$this->Cart->id;
			//$this->Session->setFlash(__('Activity has been added successfully'));
			$this->redirect(array('plugin'=>false,'controller'=>'activity', 'action' => 'index',$data['Cart']['service_id'],$cart_id));	
		}
		else {
			$this->Session->setFlash('Please select correct date.','default','','error');
			$this->redirect(Controller::referer());
		}
	}
	
	function validation(){
		if(empty($this->request->data['Activity']['end_date'])){
			$this->request->data['Activity']['end_date']=$this->request->data['Activity']['start_date'];
		}
		$this->Activity->set($this->request->data);
		$result = array();
		if ($this->Activity->validates()) {
		  $result['error'] = 0;
		}else{
		  $result['error'] = 1;
		}
		 if($this->request->is('ajax')) {
			 $this->autoRender = false;
			 $result['errors'] = $this->Activity->validationErrors;
			  $errors = array();
			  foreach($result['errors'] as $field => $data){
				$errors['Activity'.Inflector::camelize($field)] = array_pop($data);
			  }
			  $result['errors'] = $errors;
			  echo json_encode($result);
			  return;
		  }
		  return (int)($result['error'])?0:1;
	}
	
	function activities($vendor_id=null,$service_type_id=null,$sort_by_price=null,$sort_by_review=null){
		// load model
		$this->loadModel('ServiceManager.ServiceType');
		$this->loadModel('VendorManager.Service');
		$this->loadModel('VendorManager.Vendor');
		$this->loadModel('VendorManager.ServiceImage');
		$this->loadModel('VendorManager.ServiceReview');
		array_push(self::$script_for_layout,array('jquery.contenthover.min.js',$this->setting['site']['jquery_plugin_url'].'ratings/jquery.rating.js'));
		array_push(self::$css_for_layout,array($this->setting['site']['jquery_plugin_url'].'ratings/jquery.rating.css'));
		// searching list 
		$service_name='';
		$conditions=array();
		$vendor_list=array();
		$service_type_list=array();
		if ($vendor_id != null && $vendor_id != 'vendor_id') {
			$this->request->data['Search']['vendor_list']=$vendor_id;
		 	$conditions['Service.vendor_id ='] = $vendor_id;
		}
		if ($service_type_id != null && $service_type_id != 'service_type') {
			$this->request->data['Search']['service_type_list']=$service_type_id;
			$conditions['Service.service_type_id ='] = $service_type_id;
		}
		if ($sort_by_price != null && $sort_by_price != 'sortbyprice') {
		 	$price_range=explode('-',$sort_by_price);
		 	$this->request->data['Search']['sort_price']=$sort_by_price;
			$conditions[] = array('OR'=>array(
			array('Service.service_price BETWEEN ? AND ?'=>array($price_range[0],$price_range[1])))); 	 
		}
		$conditions[]=array('AND'=>array('Vendor.active'=>1,'Service.status'=>1),'OR'=>array('Vendor.payment_status'=>1 ,'Vendor.account_type'=>0));
		$this->paginate = array();
		$subQuery = "(SELECT AVG(ifnull((`ServiceReview`.`rating`), 0)) FROM service_reviews AS `ServiceReview` WHERE `ServiceReview`.`service_id` = `Service`.`id` and `ServiceReview`.`status` = 1 GROUP BY `ServiceReview`.`service_id`) AS \"rating\" ";
		$this->paginate['fields'] = array('Service.id','Service.service_title','Service.service_price','Service.description',$subQuery);
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
						array(
							'table' => 'vendor_service_availabilities',
							'alias' => 'VendorServiceAvailability',
							'type' => 'LEFT',
							'conditions' => array('VendorServiceAvailability.service_id =Service.id')
						),
					  
					);
		$this->paginate['conditions'][] = $conditions;
		$this->paginate['limit'] =Configure::read('Activiy.Limit');
		$this->paginate['group'] = array('Service.id');
		$this->paginate['order'] = array('Service.id'=>'DESC');
		if ($sort_by_review != null && $sort_by_review != 'sortbyreview') {
			$this->request->data['Search']['sort_review']=$sort_by_price;
			if(intval($sort_by_review)==1){ 
				$this->paginate['order'] = "rating DESC";
			}else{
				$this->paginate['order'] = "rating ASC";
			}
		}
		$activity_service_list = $this->paginate('Service');
		$new_activity_service_list =array();
		foreach($activity_service_list as $key=>$service_list) {
			$service_list['image']=$this->ServiceImage->getOneimageServiceImageByservice_id($service_list['Service']['id']);
			$service_list['rating']= (round($service_list[0]['rating']));
			$new_activity_service_list[$key]=$service_list;
		}
		//pr($new_activity_service_list);
		//all service type listing.
		$service_type_list=Cache::read('cake_service_list');
		if(empty($service_type_list)){
			$this->loadModel('ServiceManager.ServiceType');
			$service_type_list = $this->ServiceType->find('list',array('fields'=>array('ServiceType.id','ServiceType.name'),'conditions'=>array('ServiceType.status'=>1),'order'=>array('ServiceType.reorder ASC')));
			Cache::write('cake_service_list',$service_type_list);
		}
		// all vendor list
		$vendor_list=$this->Vendor->vendorList();
		$this->set('service_type_list',$service_type_list); 
		$this->set('vendor_list',$vendor_list); 
		$this->set('activity_service_list',$new_activity_service_list); 
		// set css and script
		$this->breadcrumbs[] = array(
                'url'=>Router::url('/'),
                'name'=>'Home'
            );
            $this->breadcrumbs[] = array(
                'url'=>Router::url(array('contorller'=>'activity','action'=>'index')),
                'name'=>"Activities"
            );
        $this->set('sort_by_price',$sort_by_price);
        if($this->request->is('ajax')){
                $this->layout = '';
                $this->Render('ajax_activities');
        }
        $this->loadModel('ContentManager.Page');    
		$page=$this->Page->read(null,4);
		if(!empty($page['Page']['page_title'])){
			$this->title_for_layout .= ": ". $page['Page']['page_title'];
		}
		if(!empty($page['Page']['page_metakeyword'])){
			$this->metakeyword = $page['Page']['page_metakeyword'];
		}
		if(!empty($page['Page']['page_metadescription'])){
			$this->metadescription = $page['Page']['page_metadescription'];
		}
	}
}
?>
