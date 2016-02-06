<?php
Class City extends AppModel {
	public $name = "City";
	public $belongsTo = array('Country');
   
   public $validate = array(
	
	'name' =>
        array(
            'rule1' =>
            array(
			'rule' => array('maxLength',100),
            'message' => 'Name should be less than 100 character(s).'
			 ),
             array(
             'rule' => 'notEmpty',
             'message' => 'Please enter city name.'
		     ),
            array(
            'rule' => '/^[A-Za-z ]*$/',
            'message' => 'Please enter city name in alphabet.'
            )
         ),
          
    );
     
	// uesed this funtion in VendorManager/service controller
	function getLocationList() { //getLocationList
		
		$criteria['fields'] = array('City.id','CONCAT(City.name,", ",Country.name) as cityname');
		$cities= $this->find('all',$criteria);
		
		$city_list=array();
		foreach($cities as $city) {
			$city_list[$city['City']['id']]=$city[0]['cityname'];
		}
		return $city_list;
	}
	function getLocationListByID($location_id_lists=null) { //getLocationList
		
		$criteria['fields'] = array('City.id','CONCAT(City.name,", ",Country.name) as cityname');
		$criteria['conditions'] = array('City.id'=>$location_id_lists);
		//echo "<pre>";print_r($service_id_list);die;
		$cities= $this->find('all',$criteria);
		$city_list=array();
		foreach($cities as $city) {
			$city_list[$city['City']['id']]=$city[0]['cityname'];
		}
		return $city_list;
	}
	
	//Get city and country name by location id 
	function getLocationListCityID($location_id=null) { 
		
		$criteria['fields'] = array('City.id','CONCAT(City.name,", ",Country.name) as cityname');
		$criteria['conditions'] = array('City.id'=>$location_id);
		 
		$city_name= $this->find('first',$criteria);
		
		return $city_name?$city_name[0]['cityname']:null;
	}
}
?>
