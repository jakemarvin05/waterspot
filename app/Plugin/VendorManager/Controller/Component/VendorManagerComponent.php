<?php

class VendorManagerComponent extends Component{
	public $slots = array();
	
	
	function __construct($option = array()){
		
	}
	
	function is_service_available($options = array()){
		$VendorServiceAvailability = ClassRegistry::init('VendorManager.VendorServiceAvailability');
		$options['order'] = array('VendorServiceAvailability.p_date'=>'ASC');
		$availabilites=$VendorServiceAvailability->get_all_slots($options);
		$dates = array();
		
		foreach($availabilites as $key=>$availability){
			$slot_dates = array();
			if(!empty($availability['VendorServiceAvailability']['start_date']) && !empty($availability['VendorServiceAvailability']['end_date'])){
				$slot_dates = self:: __sanitize_availability_dates($availability,$options);
				$dates =  array_merge($dates,self::__sanitize_availability_slots($slot_dates,$availability['VendorServiceAvailability']['slots']));
			}else if(!empty($availability['VendorServiceAvailability']['p_date'])){
				$dates =  array_merge($dates,self::__sanitize_availability_slots($availability['VendorServiceAvailability']['p_date'],$availability['VendorServiceAvailability']['slots']));
			}
		}
		
		
		
		
		echo  '<pre>';print_r($dates);die;
	}
	
	private function __sanitize_availability_dates($availability,$options = array()){
		$v_start_date = strtotime($availability['VendorServiceAvailability']['start_date']);
		$v_end_date = strtotime($availability['VendorServiceAvailability']['end_date']);
		$dates = array();
		//echo $v_start_date.'--'.$v_end_date;
		while($v_start_date<=$v_end_date){
			
			if(isset($options['start_date']) && ($v_start_date >strtotime($options['end_date']))){
				break;
			}
			$dates[] = date(Configure::read('Calender_format_php'),$v_start_date);
			$v_start_date += 86400;// increament 1 day
		}
		return $dates;
	}
	
	private function __sanitize_availability_slots($dates,$slots){
		$slots = json_decode($slots);
		$data = array();
		foreach($slots as $slot){
			$slot_breaks = explode("_",$slot);
				if(is_array($dates)){
					foreach($dates as $date){
						$data[] = array(
								'start_date'=>date("Y-m-d H:i:s",strtotime($date.' '.$slot_breaks[0])),
								'end_date'=>date("Y-m-d H:i:s",strtotime($date.' '.$slot_breaks[1]))
								);
					}
				}else{
					$data[] = array(
								'start_date'=>date("Y-m-d H:i:s",strtotime($dates.' '.$slot_breaks[0])),
								'end_date'=>date("Y-m-d H:i:s",strtotime($dates.' '.$slot_breaks[1]))
								);
				}
		}
		return $data;
	}
	
	function check_vendor_availability($options = array()){
		
	}
	
	function get_service_availability($options = array()){
		$VendorServiceAvailability = ClassRegistry::init('VendorManager.VendorServiceAvailability');
		//$search_start_date=date('Y-m-d',$options['start_date']); 
		$search_start_date=$options['start_date']; 
		//$search_end_date=date('Y-m-d',$options['end_date']); 
		$search_end_date=$options['end_date']; 
		//$conditions['Service.service_type_id'] = $date;
		
		$vendor_conditions[] = array('OR'=>array(
			array('VendorServiceAvailability.start_date BETWEEN ? AND ?'=>array($search_start_date,$search_end_date)),
			array('VendorServiceAvailability.end_date BETWEEN ? AND ?'=>array($search_start_date ,$search_end_date)),
			array('? BETWEEN VendorServiceAvailability.start_date AND VendorServiceAvailability.end_date'=>array($search_start_date)),
			array('? BETWEEN VendorServiceAvailability.start_date AND VendorServiceAvailability.end_date'=>array($search_end_date)),
			),
		);
		$vendor_conditions[] = array('unavailable'=>1);
		if(!empty($options['service_id'])){
			$vendor_conditions[] = array('service_id'=>$options['service_id']);
		}
		
		$vendor_a = $VendorServiceAvailability->find('all',array('conditions'=>$vendor_conditions));
		return $vendor_a;
	}
	
	function get_booked_service($options = array()){
		
		$BookingSlot = ClassRegistry::init('BookingSlot');
		//$search_start_date=date('Y-m-d',$options['start_date']); 
		$search_start_date=$options['start_date']; 
		//$search_end_date=date('Y-m-d',$options['end_date']); 
		$search_end_date=$options['end_date'];
		$criteria['joins']  = array(
			array(
				'table' => 'booking_participates',
				'alias' => 'BookingParticipant',
				'type' => 'LEFT',
				'conditions' => array('BookingParticipant.booking_order_id =BookingSlot.booking_order_id')
			)
		);
		if(!empty($options['service_id'])){
			$criteria['BookingSlot.service_id'] = $options['service_id'];
		}
		$criteria['BookingParticipant.status'] = 1;
		$data =  $BookingSlot->find('all',$criteria);
		return $data;
	}
	
}

?>
