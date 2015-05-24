<?php
Class ValueAddedService extends VendorManagerAppModel {
	public $name = "value_added_services";	
	
	function getValueaddedServiceByservice_id($service_id=null){
		$critria = array();
        $critria['fields'] = array('ValueAddedService.value_added_name','ValueAddedService.id','ValueAddedService.service_id','ValueAddedService.value_added_price');
        $critria['conditions'] = array('ValueAddedService.service_id' => $service_id);
        $value_added_service = $this->find('all', $critria);
        $value_added_services=array();
        foreach($value_added_service as $key=>$add_service){
			 
			$value_added_services[$key]['id']=$add_service['ValueAddedService']['id'];
			$value_added_services[$key]['service_id']=$add_service['ValueAddedService']['service_id'];
			$value_added_services[$key]['value_added_name']=$add_service['ValueAddedService']['value_added_name'];
			$value_added_services[$key]['value_added_price']=$add_service['ValueAddedService']['value_added_price'];
		 
		}
		  
        return $value_added_services;
        
	}
   
}
?>
