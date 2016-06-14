<?php
Class ServiceFilterComponent extends Component{
	public $options = array(
		'service_type_id'=>null,
		'start_date'=>null,
		'end_date'=>null,
	);
	private function _load($options = array()){
		$this->options = array_merge($this->options, $options);
		$this->options['start_date_F'] = date('Y-m-d',$this->options['start_date']); 
		$this->options['end_date_F'] = date('Y-m-d',$this->options['end_date']); 
	}
	// initialization argument [0] =>for service_type_id,[1] =>for start date,[2] =>for end date,[3] =>for participant
	
	private function _loadSearchData($options = array()){
		$this->service=array();
		$this->options =$options;
		
		$this->options[0]= ($options[0] != null && $options[0] != 'service-id')?$options[0]:'';
		if ($options[1] != null && $options[1] != 'start-date') {
			//change date format
			$this->options[1]=date('Y-m-d',$options[1]);
			$this->options[2]= $options[2] =($options[2] == null || $options[2] == 'end-date')?date('Y-m-d',$options[1]):date('Y-m-d',$options[2]);
		}
		if ($options[2] != null && $options[2] != 'end-date') {
			if($options[1]=='start-date'){
				$this->options[2]=date('Y-m-d',$options[2]);
			}
			$this->options[1] =($options[1]== null || $options[1] == 'start-date')?date('Y-m-d',$options[2]):date('Y-m-d',$options[1]);
		}
		 
	}
	
	function get_filter($options = array()){
		self::_load($options);
		//$obj_service = ClassRegistry::init('VendorManager.ServiceSlot');
		
	}
	function get_search_filter($options = array()){ 
		self::_loadSearchData($options);
		
		return self::__FilterServices();
	} 
	private function __FilterServices(){
		$tmp_services=array();
		$tmp_services = self::_getServices();
		$filter_service_availabilites=self::__FilterServiceAvailabilities($tmp_services);
		
		 // start date and end date blank then show all records
		if($this->options[1]=='start-date' && $this->options[2]=='end-date'){
			return array();
		}
		// slot base search
		if($this->options[1]==$this->options[2]){
			if($this->options[1] != null && $this->options[1] != 'start-date' && $this->options[2] != null && $this->options[2] != 'end-date'){
				return self::__FilterServiceBookingSlots($tmp_services,$filter_service_availabilites);
			}
		}
		else{
			// get service availablity in date range
			return self::__FilterServiceInRangeBookingSlots($tmp_services,$filter_service_availabilites);
		}	
	}
	
	private function __FilterServiceInRangeBookingSlots($tmp_services,$service_availabilities){
		$booked_services=array();
		$booking_orders_service=array();
		$BookingOrder = ClassRegistry::init('BookingOrder');
		
		$criteria = array();
		$criteria['fields']=array('BookingOrder.id','BookingOrder.ref_no','sum(BookingOrder.no_participants) as total_participant','BookingOrder.service_id');
		$criteria['conditions'] = array(
		'BookingOrder.service_id'=>$tmp_services,'BookingOrder.status'=> array(1,4),
		'OR'=>array(
			array('BookingOrder.start_date BETWEEN ? AND ?'=>array($this->options[1],$this->options[2])),
			array('BookingOrder.end_date BETWEEN ? AND ?'=>array($this->options[1],$this->options[2])),
			array('? BETWEEN BookingOrder.start_date AND BookingOrder.end_date'=>array($this->options[1])),
			array('? BETWEEN BookingOrder.start_date AND BookingOrder.end_date'=>array($this->options[2])),
            ),
        );
        $criteria['group'] =array('BookingOrder.service_id');
		$booking_orders=$BookingOrder->find('all',$criteria);
		foreach($booking_orders as $key=>$service){
			
			$booking_orders_service[$service['BookingOrder']['service_id']]['total_participant']=$service[0]['total_participant'];
			$booking_orders_service[$service['BookingOrder']['service_id']]['ref_no']=$service['BookingOrder']['ref_no'];
		}
		foreach($tmp_services as $id){
			$cancel_seats=0;
			$avialabilities = self::get_service_availability($id,$service_availabilities);
			if(empty($avialabilities)){
				$booked_services[] = $id;
				continue;
			}
			if(!empty($booking_orders_service[$id])){
				//get if booked cancel or auto cancel from booked participant by order ref
				$cancel_seats=self::get_cancelled_participant($booking_orders_service[$id]['ref_no']);
				//then substract
				$available_seats=$this->service[$id]-($booking_orders_service[$id]['total_participant']-$cancel_seats);
			}else{
				$available_seats=$this->service[$id];
			}
			if ($this->options[3] != null && $this->options[3]  != 'participant') {
				if($this->options[3] > $available_seats){
					$booked_services[] = $id;
				}
			}else{
				if((int)$available_seats<= 0){
					$booked_services[] = $id;
				}
			}
		}
		return $booked_services;
	}
	private function get_cancelled_participant($booking_orders){
		
		$BookingParticipate = ClassRegistry::init('BookingParticipate');
		return  $BookingParticipate->find('count',array('conditions'=>array('BookingParticipate.ref_no','BookingParticipate.status'=>array(0))));
	}
	private function _getServices(){
		$Service = ClassRegistry::init('VendorManager.Service');
			$critria  =array();
			$tmp_services = array();
			
			$critria['joins'] = array(
					array(
							'table' => 'vendors',
							'alias' => 'Vendor',
							'type' => 'inner',
							'conditions' => array('Vendor.id = Service.vendor_id')
						)
					);
			$critria['fields'] = array('Service.id','Service.no_person');
			$critria['conditions'] = array('AND'=>array('Service.service_type_id'=>$this->options[0],'Service.status'=>1,'Vendor.active'=>1),'OR'=>array('Vendor.payment_status'=>1,'Vendor.account_type'=>0));
			$this->service=$services=$Service->find('list',$critria);
			if(!empty($services)){
				foreach($services as $key=>$service_id){
					$tmp_services[$key] = $key;
				}
			}
			return $tmp_services;
	}
	
	private function __FilterServiceAvailabilities($tmp_service_ids){
		
		$service_availabilities=array();
		$VendorServiceAvailability = ClassRegistry::init('VendorManager.VendorServiceAvailability');
		$service_availabilities = $VendorServiceAvailability->find('all',array('conditions'=>array('service_id'=>$tmp_service_ids,'OR'=>array('? BETWEEN start_date AND end_date'=>array($this->options[1]),'p_date'=>$this->options[1]),'unavailable'=>0),'order'=>array('p_date'=>'DESC')));
		return $service_availabilities;
	}
	
	
	private function get_service_availability($service_id,$service_availabilities = array()){
		$slots = array();
		foreach($service_availabilities as $availability){
			if($availability['VendorServiceAvailability']['service_id']== $service_id){
				$slots = json_decode($availability['VendorServiceAvailability']['slots'],true);
				break;
			}
		}
		return $slots;
	}
	private function get_service_booking($service_id,$service_bookings = array()){
		$bookings = array();
		foreach($service_bookings as $key=>$booking){
			if($booking['BookingSlot']['service_id']== $service_id){
				$bookings[] = $booking['BookingSlot'];
			}
		}
		return $bookings;
	}
	
	private function check_free_slot($id=null,$slot_availability,$bookings){
		$free_booked_service_id=array();
		$t_person = 0;
		foreach($bookings as $booking){
			if((strtotime($booking['start_time']) >= $slot_availability['start_time'] && strtotime($booking['end_time'])>=$slot_availability['end_time'])){
			}
			if((strtotime($booking['start_time']) > $slot_availability['start_time']  && strtotime($booking['end_time'])<=$slot_availability['start_time']) || (strtotime($booking['end_time']) > $slot_availability['start_time']  && strtotime($booking['start_time'])<=$slot_availability['end_time'])){
				$t_person += $booking['no_participants'];
			}
		}
		return $t_person;
	}
	private function __FilterServiceBookingSlots($tmp_service_ids,$service_availabilities){
		$booked_services=array();
		$BookingSlot = ClassRegistry::init('BookingSlot');
		$join_bookingorder = array(
						array(
						'table' => 'booking_orders',
						'alias' => 'BookingOrder',
						'type' => 'left',
						'conditions' => array('BookingOrder.id   =BookingSlot.booking_order_id','BookingOrder.status in (1,4)'
						)
					)
			);
		$service_booking_slots=$BookingSlot->find('all',array('fields'=>array('BookingSlot.*'),'joins'=>$join_bookingorder,'conditions'=>array('? BETWEEN DATE_FORMAT(BookingSlot.start_time,\'%Y-%m-%d\')  AND DATE_FORMAT(BookingSlot.end_time,\'%Y-%m-%d\')'=>$this->options[1],'BookingSlot.service_id'=>$tmp_service_ids)));
		
		foreach($tmp_service_ids as $id){
			$is_service_available = false;
			$avialabilities = self::get_service_availability($id,$service_availabilities);
			if(empty($avialabilities)){
				$booked_services[] = $id;
				continue;
			}
			$a_dates = array();
			$booking_slots	 = self::get_service_booking($id,$service_booking_slots);
			
			if(!empty($avialabilities)){
				foreach($avialabilities as $availability){
					$start_date=strtotime($this->options[1]);
					$end_date=strtotime($this->options[2]);
					 
					$date = explode("_",$availability);
					$split_start_date = explode(":",$date[0]); 
					$split_end_date = explode(":",$date[1]); 
					$start_date_str = mktime($split_start_date[0],$split_start_date[1],$split_start_date[2]+1,date('m',$start_date),date('d',$start_date),date('Y',$start_date));
					$end_date_str = mktime($split_end_date[0],$split_end_date[1],$split_end_date[2],date('m',$end_date),date('d',$end_date),date('Y',$end_date));
					
					$a_dateaas[]=$slot = array(
						'start_time'=>$start_date_str,
						'end_time'=>$end_date_str
					);
					
					$booked_seats= self::check_free_slot($id,$slot,$booking_slots);
					
					$available_seats=$this->service[$id]-$booked_seats;
					if ($this->options[3] != null && $this->options[3]  != 'participant') {
						if($this->options[3] <= $available_seats){
							$is_service_available = true;
							break;
						}
					}else{
						if((int)$available_seats > 0){
							$is_service_available = true;
							break;
						}
					}
					
				}
			} 
			 
			if($is_service_available==false){
				$booked_services[] = $id;
			}
		}
		return $booked_services;
	}
	
	function activities_filter($data){
		$VendorServiceAvailability = ClassRegistry::init('VendorManager.VendorServiceAvailability');
		$Service = ClassRegistry::init('VendorManager.Service');
		$filter_slots=array();
		
		if(strtotime($data['start_date'])==strtotime($data['end_date'])){
			$vendorslots=$VendorServiceAvailability->getSlotByServiceID($_POST);
			foreach($vendorslots as $vendorslot){
				$slots=$vendorslot['slotindex'];
			}	
			
			if(!empty($slots)){
				
				// get Total seat of service
				$total_no_of_participants=$Service->getNoParticipantByserviceId($data['service_id']);
				foreach($slots as $slot){
					 
					$start_date=strtotime($data['start_date']);
					$end_date=strtotime($data['end_date']);
					$date = explode("_",$slot);
					$split_start_date = explode(":",$date[0]); 
					$split_end_date = explode(":",$date[1]); 
					$start_date_str = mktime($split_start_date[0],$split_start_date[1],$split_start_date[2]+1,date('m',$start_date),date('d',$start_date),date('Y',$start_date));
					$end_date_str = mktime($split_end_date[0],$split_end_date[1],$split_end_date[2],date('m',$end_date),date('d',$end_date),date('Y',$end_date));
					
					$slotdata=array('service_id'=>$data['service_id'],'start_time'=>date('Y-m-d H:i:s',$start_date_str),'end_time'=>date('Y-m-d H:i:s',$end_date_str));
					 
					// get booked activities self :: getSlotBooked($slotdata
					 
					//get available slots
					$total_no_of_participants=$Service->getNoParticipantByserviceId($data['service_id']);
					$available_slots=$total_no_of_participants-self :: getSlotBooked($slotdata);
					
					if($available_slots<$data['no_participants']){
						continue;
					}
					// remove past time
					$current_time=strtotime(Configure::read('Booking.minmum_time'));
					if($start_date_str<$current_time){
						continue;
					}
					$filter_slots[]=$slot;  
				}
			}
		}
		
		$data['slotindex']=$filter_slots;
		return $data;
	}
	
	function getSlotBooked($slotdata){
		$BookingSlot = ClassRegistry::init('BookingSlot');
		$no_of_participants=array();
		$criteria['conditions'] = array(
		'BookingSlot.service_id'=>$slotdata['service_id'],'BookingSlot.status'=>1,
		'OR'=>array(
			array('BookingSlot.start_time BETWEEN ? AND ?'=>array($slotdata['start_time'],$slotdata['end_time'])),
			array('BookingSlot.end_time BETWEEN ? AND ?'=>array($slotdata['start_time'],$slotdata['end_time'])),
			array('? BETWEEN BookingSlot.start_time AND BookingSlot.end_time'=>array($slotdata['start_time'])),
			array('? BETWEEN BookingSlot.start_time AND BookingSlot.end_time'=>array($slotdata['end_time'])),
            ),
        );
       /* $criteria['joins'] = array(
				array(
					'table'=>'booking_orders',
					'alias' => 'BookingOrder',
					'type' => 'INNER',
					'conditions'=> array('BookingOrder.id=BookingSlot.booking_order_id')
				),
				
        );*/
        
		$criteria['fields']=array('sum(BookingSlot.no_participants) as total_participant');
		$criteria['group'] =array('BookingSlot.service_id');
		
        $no_of_participants=$BookingSlot->find('all',$criteria);
        return intval(@$no_of_participants[0][0]['total_participant']); 
        
      
	}
	
	// check add to card in activites slots
	function slot_filter($slotdata){
		$data=array();
		// load service model 
		$Service = ClassRegistry::init('VendorManager.Service');
		$booking_status=true;
		$split_start_date = explode(":",$slotdata['start_time']); 
		$split_end_date = explode(":",$slotdata['end_time']); 
		$start_date_str = mktime($split_start_date[0],$split_start_date[1],$split_start_date[2]+1,date('m',$slotdata['slot_date']),date('d',$slotdata['slot_date']),date('Y',$slotdata['slot_date']));
		$end_date_str = mktime($split_end_date[0],$split_end_date[1],$split_end_date[2],date('m',$slotdata['slot_date']),date('d',$slotdata['slot_date']),date('Y',$slotdata['slot_date']));
		
		$data=array('service_id'=>$slotdata['service_id'],'start_time'=>date('Y-m-d H:i:s',$start_date_str),'end_time'=>date('Y-m-d H:i:s',$end_date_str));
		
		$total_no_of_participants=$Service->getNoParticipantByserviceId($slotdata['service_id']);
		
		$available_slots=$total_no_of_participants-self :: getSlotBooked($data);
		if($available_slots<$slotdata['no_participants']){
			$booking_status=false;
		}
		// remove past time
		$current_time=strtotime(Configure::read('Booking.minmum_time'));
		if($start_date_str<$current_time){
			$booking_status=false;
		}

		if($slotdata['no_of_pax']>0){
			$booking_status = true;
		}
		return $booking_status;
	}
	 
} 
?>
