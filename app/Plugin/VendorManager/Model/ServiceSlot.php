<?php
Class ServiceSlot extends VendorManagerAppModel {
	public $name = "service_slots";
	public $validate = array(
		 
			'service_id' => array(
						'if_equal'=>array(
							'rule'=>array('is_equal'),
							'message'=>'Slots should not be same. Please change time slots.' 
						),
						'if_lessthan'=>array(
							'rule'=>array('is_lessthan'),
							'message'=>'Slots should be greater than first slot. Please change time slots.' 
						),
						'if_already_exits'=>array(
							'rule'=>array('checkslot'),
							'message'=>'You have already added these slots. Please change time slots.'
						)	
					),	  
			'start_time' =>
				array(
					 array(
						'rule' => 'notEmpty',
						'message' => 'Please select slot start time.'
					 ),
				 ),
				  
			'end_time' =>
				array(
					 array(
						'rule' => 'notEmpty',
						'message' => 'Please select slot end time.'
					 ),
				 ), 
		);
	
	function is_equal($service_id){
		
		$service_id=$this->data['ServiceSlot']['service_id'];
		$start_time=DATE("Hi",strtotime($this->data['ServiceSlot']['start_time']));
		$end_time=DATE("Hi", strtotime($this->data['ServiceSlot']['end_time'])+1);
		if($start_time==$end_time) {
			return false;
		}
		return true;
	}
	function is_lessthan($service_id){
		$service_id=$this->data['ServiceSlot']['service_id'];
		$start_time=DATE("Hi", strtotime($this->data['ServiceSlot']['start_time']));
		$end_time=DATE("Hi", strtotime($this->data['ServiceSlot']['end_time']));
		if($start_time >$end_time) {
			return false;
		}
		return true;
	}
	function checkslot($service_id){
		$service_id=$this->data['ServiceSlot']['service_id'];
		$start_time=date('H:i:s', strtotime($this->data['ServiceSlot']['start_time']) +1);
		$end_time=date('H:i:s', strtotime($this->data['ServiceSlot']['end_time']) - 1);
		
		/* check slot time IF both are not equals Start */
		$criteria=array();
		$critria['conditions'] = array('ServiceSlot.service_id' => $service_id,
		'ServiceSlot.price'=>$this->data['ServiceSlot']['price'],
		'ServiceSlot.slot_type'=>$this->data['ServiceSlot']['slot_type'],
		'Or'=>array(
				array('ServiceSlot.start_time BETWEEN ? AND ?'=>array($start_time,$end_time)),
				array('ServiceSlot.end_time BETWEEN ? AND ? '=>array($start_time,$end_time)),
				array('? BETWEEN ServiceSlot.start_time AND ServiceSlot.end_time '=>array($start_time)),
				array('? BETWEEN ServiceSlot.start_time AND ServiceSlot.end_time '=>array($end_time)),
			)   
		);
		
		$service_available_status= $this->find('count',$critria);

		if($service_available_status>0) {
		  return false;
		}
		/* check slot time IF both are not equals END */
		
		return true; 
	}
	function getService_slotByservice_id($service_id=null, $sort_by = 'start_time', $order = 'ASC') {
		$critria = array();
		$sort_by = 'ServiceSlot.'.$sort_by;
		// $critria['fields'] = array('ServiceSlot.id','ServiceSlot.end_time','ServiceSlot.start_time','ServiceSlot.price');
		$critria['conditions'] = array('ServiceSlot.service_id' => $service_id);
		$critria['order'] = array("{$sort_by} $order");
		$slots = $this->find('all', $critria);
		$service_slots=array();
        if(!empty($slots)) {
			foreach($slots as $key=>$slot){
				$service_slots[$key]=$slot['ServiceSlot'];
			}
		}  
        return $service_slots;
        
	}
	function getSlotByServiceID($service_id=null,$start_date=null,$end_date=null){
		$critria = array();
        //$critria['fields'] = array('ServiceSlot.start_time','ServiceSlot.end_time');
        $critria['conditions'] = array('ServiceSlot.service_id' => $service_id);
        $critria['order'] = array('ServiceSlot.start_time asc');
        $slots = $this->find('all', $critria);
        // time 24 to 12 formate
        $service_slots=array();
        $service_slots_index=array();
        foreach($slots as $key=>$slot) 
        {
			//$service_slots[$slot['ServiceSlot']['id']]=DATE("g:i A", STRTOTIME($slot['ServiceSlot']['start_time'].":"."00"))." To ".DATE("g:i A", STRTOTIME($slot['ServiceSlot']['end_time'].":"."00"));
			$service_slots_index[$slot['ServiceSlot']['id']]=$slot['ServiceSlot']['start_time']."_".$slot['ServiceSlot']['end_time']."_".$slot['ServiceSlot']['price'];
			$slot_types[$slot['ServiceSlot']['id']] = $slot['ServiceSlot']['slot_type'];
        }
        $slot_data['service_slots']=$service_slots;
        $slot_data['service_slots_index']=$service_slots_index;
        $slot_data['slot_types']=$slot_types;
       
        return $slot_data;
	}
	//get records by slot id
	function getSlotBySlotID($slots_id=null){
		
		$critria = array();
        //$critria['fields'] = array('ServiceSlot.start_time','ServiceSlot.end_time');
        $critria['conditions'] = array('ServiceSlot.id' => $slots_id);
        $critria['order'] = array('ServiceSlot.start_time asc');
        $slots = $this->find('all', $critria);
        // time 24 to 12 formate
        $service_slots=array();
        foreach($slots as $key=>$slot) {
			$service_slots[$slot['ServiceSlot']['id']]=DATE("g:i A", STRTOTIME($slot['ServiceSlot']['start_time'].":"."00"))." To ".DATE("g:i A", STRTOTIME($slot['ServiceSlot']['end_time'].":"."00"));
        }
        return $service_slots;
	}
 
}
?>
