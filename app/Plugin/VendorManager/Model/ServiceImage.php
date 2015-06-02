<?php
Class ServiceImage extends VendorManagerAppModel{
	public $name = "service_images";
	public $validate = array();
	
	function getServiceImageByservice_id($service_id=null){
		$critria = array();
        $critria['fields'] = array('ServiceImage.image','ServiceImage.id');
        $critria['conditions'] = array('ServiceImage.service_id' => $service_id);
        $critria['order'] = array('ServiceImage.default_image' => 'DESC','ServiceImage.id' => 'ASC');
        $images = $this->find('all', $critria);
        $serviceimages=array();
        foreach($images as $key=>$image){
			 
			$serviceimages[$key]['id']=$image['ServiceImage']['id'];
			$serviceimages[$key]['image']=$image['ServiceImage']['image'];
		}
	    return $serviceimages;
    }
	function getServiceImageDetailsByservice_id($service_id=null){
		$critria = array();
        $critria['fields'] = array('ServiceImage.image','ServiceImage.id','ServiceImage.default_image');
        $critria['conditions'] = array('ServiceImage.service_id' => $service_id);
        $critria['order'] = array('ServiceImage.id' => 'ASC');
        $images = $this->find('all', $critria);
        $serviceimages=array();
        foreach($images as $key=>$image){
			 
			$serviceimages[$key]['id']=$image['ServiceImage']['id'];
			$serviceimages[$key]['image']=$image['ServiceImage']['image'];
			$serviceimages[$key]['default_image']=$image['ServiceImage']['default_image'];
		 
		}
		
		  
        return $serviceimages;
        
	}
	function getOneimageServiceImageByservice_id($service_id=null){
		$critria = array();
        $critria['fields'] = array('ServiceImage.image','ServiceImage.id');
        $critria['conditions'] = array('ServiceImage.service_id' => $service_id);
        $critria['order'] = array('ServiceImage.default_image' => 'DESC','ServiceImage.id' => 'ASC');
        $images = $this->find('first', $critria);
        $images['ServiceImage']['image']=(!empty($images['ServiceImage']['image']))?$images['ServiceImage']['image']:'';
        return $images['ServiceImage']['image'];
	}
	function getOneimageServiceImageByservice_id2($service_id=array()){
		$critria = array();
        $critria['fields'] = array('ServiceImage.image','ServiceImage.id');
        $critria['conditions'] = array('ServiceImage.service_id' => $service_id);
        $critria['order'] = array('ServiceImage.id' => 'ASC');
        $images = $this->find('all', $critria);
       return $images;
	}
}
?>
