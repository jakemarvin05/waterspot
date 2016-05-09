<?php
class ServiceType extends ServiceManagerAppModel {
 // public $name = "services";
	public $validate = array(
	/*'title' =>
        array(
            'rule1' =>
            array(
                'rule' => array('maxLength', 255),
                'message' => 'name should be less than 255 character(s)'
            ),
            array(
                'rule' => 'notEmpty',
                'message' => 'Please enter title'
            ) 
        ),*/
   'short_description' =>
	array(
		'rule1' =>
		array(
			'rule' => array('maxLength', 160),
			'message' => 'Short Description should be less than 160 character(s)'
		),
		array(
			'rule' => 'notEmpty',
			'message' => 'Please enter short description'
		) 
	),
   'name' =>
	array(
		'rule1' =>
		array(
			'rule' => array('maxLength', 255),
			'message' => 'Service type name should be less than 255 character(s)'
		),
		array(
			'rule' => 'notEmpty',
			'message' => 'Please enter service type name'
		) 
	),

 //   'youtube_url' =>
	// array(
	// 	'rule1' =>
	// 	array(
	// 		'rule' => array('maxLength', 255),
	// 		'message' => 'Youtube Url should be less than 255 character(s)'
	// 	)
	// ),
	
	'image' =>
		array(
			'rule1'=>
			array(
					'rule' =>'validate_image',
					'message' => 'Please upload a valid image.'
					//'on' => 'create'
				 ),
				'size' => array(
					'rule'    => array('check_size_image'),
					'message' => 'Please upload an image having dimension 294 X 186'			
				)	  
				  
	),   
	
	//'seo_keyword' =>
        //array(
            //'rule1' =>
            //array(
                //'rule' => array('maxLength', 255),
                //'message' => 'Seo Keyword should be less than 255 character(s)'
            //),
            //array(
                //'rule' => 'notEmpty',
                //'message' => 'Please enter Seo Keyword'
            //) 
        //),
        
        //'meta_keyword' =>
        //array(
            //'rule1' =>
            //array(
                //'rule' => array('maxLength', 255),
                //'message' => 'Meta Keyword should be less than 255 character(s)'
            //),
            //array(
                //'rule' => 'notEmpty',
                //'message' => 'Please enter Meta Keyword'
            //) 
        //),
        //'meta_description' =>
        //array(
            //'rule1' =>
				//array(
				//'rule' => array('maxLength',255),
				//'message' => 'Meta Description should be less than 255 character(s)'
				//),
            //array(
                //'rule' => 'notEmpty',
                //'message' => 'Please enter Meta Description'
            //) 
        //),
        
      
    );
	
	
	
	function servicelist() {
		$service_lists=array();
		$service_lists=$this->find('list',array('fields'=>array('ServiceType.id','ServiceType.name'),'conditions'=>array('ServiceType.status'=>1),'order'=>array('ServiceType.reorder')));
		return $service_lists;
	}
	function getServiceNameById($service_id=null) {
		$service_lists=array();
		$service_lists=$this->find('first',array('fields'=>array('ServiceType.name'),'conditions'=>array('ServiceType.status'=>1,'ServiceType.id'=>$service_id)));
		return (!empty($service_lists))?$service_lists['ServiceType']['name']:'';
	}
	function getServiceTypeDetailsById($service_id=null) {
		$service_lists=array();
		$service_lists=$this->find('first',array('fields'=>array('*'),'conditions'=>array('ServiceType.status'=>1,'ServiceType.id'=>$service_id)));
		return $service_lists;
	}
	function getServiceTypeIdBySlug($slug){
		$service_lists=array();
		$service_lists=$this->find('first',array('fields'=>array('ServiceType.id'),'conditions'=>array('ServiceType.status'=>1,'ServiceType.slug'=>$slug)));
		return (!empty($service_lists))?$service_lists['ServiceType']['id']:null;

	}
	function getServiceTypeNameById($service_type_id=null) {
		$service_lists=array();
		$criteria['joins']=array(
							array(
								'table'=>'services',
								'alias' => 'Service',
								'type' => 'LEFT',
								/*'foreignKey' => false,*/
								'conditions'=> array('Service.service_type_id=ServiceType.id')
								)
							);
		$criteria['fields']=array('ServiceType.name');
		$criteria['conditions']=array('ServiceType.id'=>$service_type_id);
		
		$service_lists=$this->find('first',$criteria);
		$service_lists['ServiceType']['name']=(!empty($service_lists['ServiceType']['name']))?$service_lists['ServiceType']['name']:'';
		
		return $service_lists['ServiceType']['name'];
	}
	function getServiceTypeNameByServiceId($service_id=null) {
		$service_lists=array();
		$criteria['joins']=array(
							array(
								'table'=>'services',
								'alias' => 'Service',
								'type' => 'LEFT',
								/*'foreignKey' => false,*/
								'conditions'=> array('Service.service_type_id=ServiceType.id')
								)
							);
		$criteria['fields']=array('ServiceType.name');
		$criteria['conditions']=array('Service.id'=>$service_id);
		
		$service_lists=$this->find('first',$criteria);
		$service_lists['ServiceType']['name']=(!empty($service_lists['ServiceType']['name']))?$service_lists['ServiceType']['name']:'';
		
		return $service_lists['ServiceType']['name'];
	}
	
	function service_type_list($vendor_id=null) {
		$criteria=array();
		$criteria['joins']=array(
								array(
									'table'=>'services',
									'alias' => 'Service',
									'type' => 'LEFT',
									/*'foreignKey' => false,*/
									'conditions'=> array('Service.service_type_id=ServiceType.id','Service.status = 1')
									),
									array(
										'table' => 'vendors',
										'alias' => 'Vendor',
										'type' => 'INNER',
										'conditions' => array('Vendor.id = Service.vendor_id')
									),
								);
		$criteria['fields'] = array('ServiceType.id','ServiceType.name');
		$criteria['conditions']=array('AND'=>array('ServiceType.status'=>1,'Vendor.active'=>1,'Service.vendor_id'=>$vendor_id),'OR'=>array('Vendor.payment_status'=>1 ,'Vendor.account_type'=>0));
		 
		$criteria['group'] =array('ServiceType.id');
		$service_type_list=$this->find('all',$criteria);
		return $service_type_list;
	}
	public function afterSave($options = array()){
		 self::chaheName();
	}
  
	public function beforeSave($options = array()){
		self::chaheName();
		parent::beforeSave();
	}
	public function afterDelete() {
		self::chaheName();
	}
	public function beforeDelete($cascade = true) {
		self::chaheName();
	}
	private function chaheName(){
		Cache::delete('cake_service_list');
		Cache::delete('cake_slide_booking_details');
	}
	
	
	
	function validate_image() {
		if((!empty($this->data['ServiceType']['id'])) && $this->data['ServiceType']['image']['name']=='') {
			return true;
		}else{
			if(!empty($this->data['ServiceType']['image']['name'])) {
				$file_part = explode('.',$this->data['ServiceType']['image']['name']);
				$ext = array_pop($file_part);		
				if(!in_array(strtolower($ext),array('gif', 'jpeg', 'png', 'jpg'))) {
					return false;
				}
			}
		return true;
		}
	}
	
	public function check_size_image(){
		if((!empty($this->data['ServiceType']['id'])) && $this->data['ServiceType']['image']['tmp_name']=='') {
			return true;
		}else {
			if($this->data['ServiceType']['image']['error'] < 1){
				$imgSize = @getImageSize($this->data['ServiceType']['image']['tmp_name']);
				if($imgSize[0]>=100 && $imgSize[1]>=100)
				{
					return true;
				}
			}
			return false;
		}
	}
	
}
?>
