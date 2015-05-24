<?php
Class Service extends VendorManagerAppModel{
	public $name = "services";
	
    /*public $hasOne = array(
	   'ServiceImage'=>array(
	   'className' => 'ServiceImage',
	   'order' => 'ServiceImage.id ASC', // order by descending time of Subjects
	   'foreignKey' => 'service_id',
	   'fields'=>array('ServiceImage.*'))
    );*/
	//var $hasMany =array('ServiceImage'=>array('className'=>'ServiceImage','foreignKey'=>'service_id'));
	
	public $validate = array(
			'service_type_id' =>
				array(
					 array(
						'rule' => 'notEmpty',
						'message' => 'Please select service.'
					 ),
				 ),
			'service_price' =>
					array(
					'rule1' =>
						array(
							'rule' => 'notEmpty',
							'message' => 'Please enter price.'
                
						),
						array(
							'rule'    => 'numeric',
							'message' => 'Please enter price in numbers.'
						),
					),
			'full_day_amount' =>
					array(
					'rule1' =>
						array(
							'rule' => 'notEmpty',
							'message' => 'Please enter full day price.'
                
						),
						array(
							'rule'    => 'numeric',
							'message' => 'Please enter price in numbers.'
						),
					),
			'no_person'=>
					array(
					'rule1' =>
						array(
							'rule' => 'notEmpty',
							'message' => 'Please enter no of persons.'
                
						),
						array(
							'rule'    => 'numeric',
							'message' => 'No of persons should be numeric.'
						),
						array(
							'rule' => array('range', 0, 21),
							'message' => 'No of persons range 1 to 2000.'
						),
					),
					
			'service_title' =>
					array(
					'rule1' =>
						array(
							'rule' => 'notEmpty',
							'message' => 'Please enter service title.'
                
						),
						array(
							'rule'    => array('between', 3, 255),
							'message' => 'Please enter service title between 3 to 255 characters.'
						),
					),
					
			'location_id' =>
				array(
					 array(
						'rule' => 'notEmpty',
						'message' => 'Please select Location.'
					 ),
				 ),
		);
	
	function service_type_list_admin($vendor_id=null) {		
		$criteria=array();
		$criteria['joins']=array(
								array(
										'table'=>'service_types',
										'alias' => 'ServiceType',
										'type' => 'left',
										/*'foreignKey' => false,*/
										'conditions'=> array('ServiceType.id=Service.service_type_id')
									),
								);
		$criteria['fields'] = array('Service.*','ServiceType.name');
					$criteria['conditions']['AND']['ServiceType.status = '] = 1;	
					$criteria['conditions']['AND']['Service.status = '] = 1;
					$criteria['conditions']['AND']['Service.vendor_id = '] = $vendor_id;
					$criteria['group'] =array('Service.service_type_id');
				
					
		 			
		$service_type_list=$this->find('all',$criteria);
		
		return $service_type_list;
	}
	function findRelatedService($location_id=null,$service_id=null) {		
		$criteria=array();
		$criteria['fields'] = array('Service.id','Service.service_price','Service.service_title');
		$criteria['joins']=array(
								array(
										'table'=>'vendors',
										'alias' => 'Vendor',
										'type' => 'left',
										//'foreignKey' => false,
										'conditions'=> array('Vendor.id=Service.vendor_id'),
									),
								);
		$criteria['conditions'] =array('Service.status'=>1,'Service.location_id'=>$location_id,'Service.id  !='=>$service_id,'Vendor.active'=>1);//'Vendor.active'=>1
		$criteria['group'] =array('Service.id');
		$criteria['order'] =array('Service.id'=>'ASC');
		$service_list=$this->find('all',$criteria);
		return $service_list;
	}
	function findRelatedServiceByVendor($vendor_id=null,$service_id=null) {		
		$criteria=array();
		$criteria['fields'] = array('Service.id','Service.service_price','Service.service_title');
		$criteria['joins']=array(
								array(
										'table'=>'vendors',
										'alias' => 'Vendor',
										'type' => 'left',
										//'foreignKey' => false,
										'conditions'=> array('Vendor.id=Service.vendor_id'),
									),
								);
		$criteria['conditions'] =array('Service.status'=>1,'Service.vendor_id'=>$vendor_id,'Service.id  !='=>$service_id,'Vendor.active'=>1);//'Vendor.active'=>1
		$criteria['group'] =array('Service.id');
		$criteria['order'] =array('Service.id'=>'ASC');
		$service_list=$this->find('all',$criteria);
		return $service_list;
	}
	function CheckServiceId($service_id=null) {		
		$criteria=array();
		$criteria['joins']=array(
								array(
										'table'=>'vendors',
										'alias' => 'Vendor',
										'type' => 'left',
										//'foreignKey' => false,
										'conditions'=> array('Vendor.id=Service.vendor_id'),
									),
								);
		$criteria['conditions'] =array('AND'=>array('Service.status'=>1,'Service.id'=>$service_id,'Vendor.active'=>1),'OR'=>array('Vendor.payment_status'=>1 ,'Vendor.account_type'=>0));
		$criteria['group'] =array('Service.id');
		$criteria['order'] =array('Service.id'=>'ASC');
		$check_service_status=$this->find('count',$criteria);
		return $check_service_status;
	}
	
	function serviceListVendorById($vendor_id=null) {		
		$criteria=array();
		/*$this->bindModel(array('hasMany' => array(
	   'ServiceImage'=>array(
	   'className' => 'ServiceImage',
	   'order' => 'id ASC', // order by descending time of Subjects
	   'foreignKey' => 'service_id',
	   'fields'=>array('image'),
	   'limit'=> '1'))));*/
	   
		$criteria['fields'] = array('Service.service_title','Service.id');
		/*$criteria['joins']=array(
								array(
										'table'=>'service_images',
										'alias' => 'ServiceImage',
										'type' => 'left',
										//'foreignKey' => false,
										'conditions'=> array('ServiceImage.service_id=Service.id'),
									),
								);
								*/
		$criteria['conditions']['AND']['Service.status = '] = 1;
		$criteria['conditions']['AND']['Service.vendor_id = '] =$vendor_id;
		//$criteria['order']='ServiceImage.datetime ASC';
		$criteria['group'] =array('Service.id');
		$criteria['limit'] =6;
		//$criteria['order'] =array('ServiceImage.id'=>'ASC','ServiceImage.service_id'=>'ASC');
		
		
		$service_list=$this->find('all',$criteria);
		//pr($service_list); die;
		return $service_list;
	}
	function servieDetailByService_id($service_id=null){
		$service_detail=$this->find('first',array('conditions'=>array('Service.id'=>$service_id)));
		return $service_detail; 
	}
	function getNoParticipantByserviceId($service_id=null){
		$service_detail=$this->find('first',array('fields'=>array('Service.no_person'),'conditions'=>array('Service.id'=>$service_id)));
		return $service_detail['Service']['no_person']; 
	}
	function serviePriceByService_id($service_id=null){
		$service_detail=$this->find('first',array('fields'=>array('service_price'),'conditions'=>array('Service.id'=>$service_id)));
		$price=(!empty($service_detail['Service']['service_price']))?$service_detail['Service']['service_price']:0;
		return $price; 
	}
	function servieTitleByService_id($service_id=null){
		$service_detail=$this->find('first',array('fields'=>array('service_title'),'conditions'=>array('Service.id'=>$service_id)));
		$service_title=(!empty($service_detail['Service']['service_title']))?$service_detail['Service']['service_title']:'Not available';
		return $service_title; 
	}
	function checkServiceById($vendor_id = null , $service_id=null) {
		$service_status=$this->find('count',array('conditions'=>array('Service.id'=>$service_id,'Service.vendor_id'=>$vendor_id)));
		return $service_status; 
	}
	function getVendor_idByService_id($service_id=null) {
		$vendor_id=array();
		$vendor_id=$this->find('first',array('conditions'=>array('Service.id'=>$service_id)));
		return (!empty($vendor_id))?$vendor_id['Service']['vendor_id']:null; 
	}
	/*function serviceTypeListVendorById($vendor_id=null) {		
		$criteria=array();
		$criteria['fields'] = array('ServiceType.service_title','Service.id');
		 $criteria['joins']=array(
								array(
										'table'=>'service_images',
										'alias' => 'ServiceImage',
										'type' => 'left',
										//'foreignKey' => false,
										'conditions'=> array('ServiceImage.service_id=Service.id'),
									),
								);
								
		$criteria['conditions']['AND']['Service.status = '] = 1;
		$criteria['conditions']['AND']['Service.vendor_id = '] =$vendor_id;
		//$criteria['order']='ServiceImage.datetime ASC';
		$criteria['group'] =array('Service.id');
		$criteria['limit'] =6;
		//$criteria['order'] =array('ServiceImage.id'=>'ASC','ServiceImage.service_id'=>'ASC');
		
		
		$service_list=$this->find('all',$criteria);
		//pr($service_list); die;
		return $service_list;
	}*/
}
?>
