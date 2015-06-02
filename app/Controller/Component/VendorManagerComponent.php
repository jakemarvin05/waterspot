<? class VendorManagerComponent extends Component{
	public $slots = array();
	
	function __construct($option = array()){
		
	}
	
	function is_service_available($options = array()){
		$VendorServiceAvailability = ClassRegistry::init('VendorManager.VendorServiceAvailability');
		$this->booking = ClassRegistry::init('Booking');
		$options['order'] = array('VendorServiceAvailability.p_date'=>'ASC');
		$availabilites=$VendorServiceAvailability->get_all_slots($options);
		$dates = array();
		foreach($availabilites as $key=>$availability){
			$slots=$availability['VendorServiceAvailability']['slots'];
		}
		self:: __sanitize__slots($slots,$options);
		foreach($availabilites as $key=>$availability){
			$slot_dates = array();
			if(!empty($availability['VendorServiceAvailability']['start_date']) && !empty($availability['VendorServiceAvailability']['end_date'])){
				$slot_dates = self:: __sanitize_availability_dates($availability,$options);
				$dates =  array_merge($dates,self::__sanitize_availability_slots($slot_dates,$availability['VendorServiceAvailability']['slots']));
			}else if(!empty($availability['VendorServiceAvailability']['p_date'])){
				$dates =  array_merge($dates,self::__sanitize_availability_slots($availability['VendorServiceAvailability']['p_date'],$availability['VendorServiceAvailability']['slots']));
			}
		}
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
	
	private function __sanitize__slots($slots,$option=array()){
		if(!empty($slots)){
			$array_slots=json_decode($slots,true);
			foreach($array_slots as $key=>$slot){
				$slot_breaks = explode("_",$slot);
					$start_date_time=date("Y-m-d H:i:s",strtotime($option['start_date'].' '.$slot_breaks[0]));
					$end_date_time=date("Y-m-d H:i:s",strtotime($option['end_date'].' '.$slot_breaks[1]));
					$data[] = array(
							'start_date'=>$start_date_time,
							'end_date'=>$end_date_time,
							'available_participant'=>self::no_available_participant($start_date_time,$end_date_time,$option)
							);
				}
		} 
		
	}
	private function no_available_participant($start_date_time,$end_date_time,$option){
		echo $start_date_time.$end_date_time;
		$bookings=$this->booking->find('first',array());
		$criteria = array();
		$criteria['joins'] = array(
				array(
					'table' => 'booking_orders',
					'alias' => 'BookingOrder',
					'type' => 'INNER',
					'conditions' => array('BookingOrder.ref_no = Booking.ref_no')
				) ,
				array(
					'table' => 'booking_slots',
					'alias' => 'BookingSlot',
					'type' => 'INNER',
					'conditions' => array('BookingSlot.booking_order_id = BookingOrder.id')
				) 
                
			);
			/*$criteria['conditions'] = array('BookingSlot.service_id'=>$option['service_id'],
			'Or'=>array(
				array('BookingSlot.start_time BETWEEN ? AND ?'=>array($start_date_time,$end_date_time)),
				array('BookingSlot.end_time BETWEEN ? AND ? '=>array($start_date_time ,$end_date_time)),
				array('? BETWEEN BookingSlot.start_time AND BookingSlot.end_time '=>array($start_date_time)),
				array('? BETWEEN BookingSlot.start_time AND BookingSlot.end_time '=>array($end_date_time)),
               
           )            
        );*/
       $slots_available_status = $this->booking->find('all', $criteria);
		pr($slots_available_status);die;
		pr($start_date_time); 
		pr($option);die;
	}
}

?>
