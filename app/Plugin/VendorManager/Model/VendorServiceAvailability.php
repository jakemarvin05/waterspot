<?php
Class VendorServiceAvailability extends VendorManagerAppModel {
	public $name = "VendorServiceAvailability";	
	var $actsAs = array('Multivalidatable');
	public $validate = array(); 
	var $validationSets = array(
		'date_range'=>array(
			'start_date' =>	array(
				array(
				'rule' =>'notEmpty',
				'message'=> 'Please enter start date.'
				)
			),
			
			'slots' =>	array(
				array(
				'rule' =>array('multiple'),
				'message'=> 'Please select any one slot.'
				)
			),
			'service_id' => array(
				'valid'=>array(
					'rule'=>array('CheckDateRangeAvailability'),
					'message'=>'You already have a schedule for the following range. Please select another dates.' 
				),
			),
		),
		'particular'=>array(
			'start_date' =>	array(
				array(
				'rule' =>'notEmpty',
				'message'=> 'Please enter start date.'
				)
			),
			'slots' =>	array(
				array(
				'rule' =>array('multiple'),
				'message'=> 'Please select any one slot.'
				)
			),
			'service_id' => array(
						'valid'=>array(
							'rule'=>array('CheckParticularAvailability'),
							'message'=>'You already have a schedule for the following range. Please select another dates.' 
						),
				),
		)
		 
	);
	
	function CheckDateRangeAvailability($service_id) {
		
		$availability_id=$this->data['VendorServiceAvailability']['id'];
		$service_id=$this->data['VendorServiceAvailability']['service_id'];
		$start_date=date('Y-m-d',strtotime($this->data['VendorServiceAvailability']['start_date']));
		$end_date=date('Y-m-d',strtotime($this->data['VendorServiceAvailability']['end_date']));
		
		$criteria = array();
		$criteria['conditions'] = array(
		'VendorServiceAvailability.service_id'=>$service_id,
		'OR'=>array(
			array('VendorServiceAvailability.start_date BETWEEN ? AND ?'=>array($start_date,$end_date)),
			array('VendorServiceAvailability.end_date BETWEEN ? AND ?'=>array($start_date ,$end_date)),
			array('? BETWEEN VendorServiceAvailability.start_date AND VendorServiceAvailability.end_date'=>array($start_date)),
			array('? BETWEEN VendorServiceAvailability.start_date AND VendorServiceAvailability.end_date'=>array($end_date)),
                
            ),
        );
        if($availability_id!=""){
			$criteria['conditions']['NOT']  = array('VendorServiceAvailability.id'=>$availability_id);
		}
        
		$slots_available_status = $this->find('count', $criteria);
		//echo $slots_available_status;die;
		
		if($slots_available_status>0) {
		   $errors [] = "You already have a schedule for this date. Please select another date.";
		}
	     
	   if (!empty($errors))
	   return false;//implode("\n", $errors);
       
	   return true; 
		
	}
	
	function CheckParticularAvailability() {
		$availability_id=$this->data['VendorServiceAvailability']['id'];
		$p_date=$this->data['VendorServiceAvailability']['p_date'];
		$service_id=$this->data['VendorServiceAvailability']['service_id'];
		
		$criteria = array();
		$criteria['conditions'] = array(
			'VendorServiceAvailability.service_id'=>$service_id,'VendorServiceAvailability.p_date'=>$p_date);
		if($availability_id!=""){
			$criteria['conditions']['NOT']  = array('VendorServiceAvailability.id'=>$availability_id);
		}	
		$slots_available_status = $this->find('count', $criteria);
    
		if($slots_available_status>0) {
		   $errors [] = "You already have a schedule for this date. Please select another date.";
		}
	     
	   if (!empty($errors))
	   return false;//implode("\n", $errors);
       
	   return true; 
		
	}
	
	function checkVendorServiceByDate($service_id=null,$start_date=null,$end_date=null) {
	 
	$criteria = array();
	$criteria['conditions'] = array(
		'VendorServiceAvailability.service_id'=>$service_id,
		'Or'=>array(
			array('VendorServiceAvailability.start_date BETWEEN ? AND ?'=>array($start_date,$end_date)),
			array('VendorServiceAvailability.end_date BETWEEN ? AND ? '=>array($start_date ,$end_date)),
			array('? BETWEEN VendorServiceAvailability.start_date AND VendorServiceAvailability.end_date '=>array($start_date)),
			array('? BETWEEN VendorServiceAvailability.start_date AND VendorServiceAvailability.end_date '=>array($end_date)),
               
           )            
        );
     $check_service_availability = $this->find('count', $criteria);
	 return $check_service_availability; 
	}
	// Get record between start and end date
	function getServiceAvailablityByDates($service_id=null,$start_date=null,$end_date=null){
		$criteria = array();
	$criteria['conditions'] = array(
           
            'VendorServiceAvailability.service_id'=>$service_id,
            'Or'=>array(
                array('VendorServiceAvailability.start_date BETWEEN ? AND ?'=>array($start_date,$end_date)),
                array('VendorServiceAvailability.end_date BETWEEN ? AND ? '=>array($start_date ,$end_date)),
                array('? BETWEEN VendorServiceAvailability.start_date AND VendorServiceAvailability.end_date '=>array($start_date)),
                array('? BETWEEN VendorServiceAvailability.start_date AND VendorServiceAvailability.end_date '=>array($end_date)),
                
            )
            
        );
     $check_service_availability = $this->find('all', $criteria);
	 return $check_service_availability; 
	}		
	function getSlotByServiceID($options = array()){
		$start_date=date('Y-m-d',strtotime($options['start_date']));
		$end_date=date('Y-m-d',strtotime($options['end_date']));
		$criteria=array();
		$available_slots=array();
		$filtre_slots=array();
		$new_service_slots=array();
		$criteria['conditions'] = array(
			'VendorServiceAvailability.service_id'=>$options['service_id'],
			'VendorServiceAvailability.unavailable'=>0, // 0 for available
			'Or'=>array(
				 array('VendorServiceAvailability.p_date BETWEEN ? AND ?'=>array($options['start_date'],$options['end_date'])),
				array('VendorServiceAvailability.start_date BETWEEN ? AND ?'=>array($options['start_date'],$options['end_date'])),
				array('VendorServiceAvailability.end_date BETWEEN ? AND ? '=>array($options['start_date'] ,$options['end_date'])),
				array('? BETWEEN VendorServiceAvailability.start_date AND VendorServiceAvailability.end_date '=>array($options['start_date'])),
				array('? BETWEEN VendorServiceAvailability.start_date AND VendorServiceAvailability.end_date '=>array($options['end_date'])),
               
				),
			 
			);
		//find all slots between start and end date
		$available_slots = $this->find('all', $criteria); 
		
		foreach($available_slots as $key=>$available_slot) {
			
			if(!empty($available_slot['VendorServiceAvailability']['start_date'])){
				$v_start_date = strtotime($available_slot['VendorServiceAvailability']['start_date']);
				$v_end_date = strtotime($available_slot['VendorServiceAvailability']['end_date'])+86400;
				$this->s_date = strtotime($start_date);
				$this->e_date = strtotime($end_date);
				
				while($v_start_date<=$v_end_date){ 
					 
					$v_start_date += 86400;// increament 1 day
					$index=$v_start_date-86400;
					if($this->s_date>=$v_start_date || $this->e_date<$index){
						continue;
					}
					 $filtre_slots[$index]['service_id']=$options['service_id'];
					$filtre_slots[$index]['start_date']=date(Configure::read('Calender_format_php'),$index);
					$filtre_slots[$index]['end_date']=date(Configure::read('Calender_format_php'),$index);
					$filtre_slots[$index]['slotindex']=(!empty($available_slot['VendorServiceAvailability']['slots']))?json_decode($available_slot['VendorServiceAvailability']['slots']):array();
				}
			}
			 
				
			if(!empty($available_slot['VendorServiceAvailability']['p_date'])){
				$index=strtotime($available_slot['VendorServiceAvailability']['p_date']);
				$filtre_slots[$index]['service_id']=$options['service_id'];
				$filtre_slots[$index]['start_date']=date(Configure::read('Calender_format_php'),$index);
				$filtre_slots[$index]['end_date']=date(Configure::read('Calender_format_php'),$index);
				$filtre_slots[$index]['slotindex']=(!empty($available_slot['VendorServiceAvailability']['slots']))?json_decode($available_slot['VendorServiceAvailability']['slots']):array();
			} 
		}	
		
		
		 
       
        return $filtre_slots;
	}
	
	function get_all_slots($options = array()){
		$criteria = array();
		$criteria['conditions'] = array(
			'VendorServiceAvailability.service_id'=>$options['service_id'],
			'VendorServiceAvailability.unavailable'=>0, // 0 for available
			'Or'=>array(
				 array('VendorServiceAvailability.p_date BETWEEN ? AND ?'=>array($options['start_date'],$options['end_date'])),
				array('VendorServiceAvailability.start_date BETWEEN ? AND ?'=>array($options['start_date'],$options['end_date'])),
				array('VendorServiceAvailability.end_date BETWEEN ? AND ? '=>array($options['start_date'] ,$options['end_date'])),
				array('? BETWEEN VendorServiceAvailability.start_date AND VendorServiceAvailability.end_date '=>array($options['start_date'])),
				array('? BETWEEN VendorServiceAvailability.start_date AND VendorServiceAvailability.end_date '=>array($options['end_date'])),
			),
		);
		if(!empty($options['order'])){
			$criteria['order'] = $options['order'];
		}
		return $this->find('all', $criteria); 
	}

}
?>
