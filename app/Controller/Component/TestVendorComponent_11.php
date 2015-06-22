<? class TestVendorComponent extends Component{
	public $slots = array();
	
	
	/*function __construct($option = array()){
		
	}*/
	
	
	function is_service_available($options = array()){
		echo "fddf";die; 
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
	
}

?>
