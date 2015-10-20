<?php
Class ServiceAttributesController extends VendorManagerAppController{
	public $uses = array('VendorManager.Attribute', 'VendorManager.ServiceAttribute', 'VendorManager.Service');
	public $paginate = array();
	public $id = null;

	public function index($service_id=null) {
		$service = $this->Service->find('first', ['conditions' => ['id' => $service_id]]);
		$attributes = [];
		$all_attributes = $this->Attribute->find('all', ['conditions' => ['service_type_id' => $service['Service']['service_type_id']]]);
		
		$this->x($all_attributes);
		foreach ($all_attributes as $attr) {
			$attribute = [];
			$attribute['name'] = $attr['Attribute']['name'];
			$attribute['type'] = $attr['Attribute']['type'] == 1 ? 'Amenity' : ($attr['Attribute']['type'] == 2 ? 'Included' : 'Extra');
			$attribute['has_input'] = $attr['Attribute']['has_input'];
			$attribute['icon_class'] = $attr['Attribute']['icon_class'] ? $attr['Attribute']['icon_class'] : 'fa fa-list';
			$attribute['value'] = $attr['ServiceAttribute']['value'];
			$attributes[] = $attribute;
		}

		// this is how we call the attributes
		// $attribute_list = $this->ServiceAttribute->find('all', ['conditions' => ['service_id' => $service_id]]);
		// $attributes = [];
		// foreach ($attribute_list as $attr) {
		// 	$attribute = [];
		// 	$attribute_detail = $this->Attribute->find('first', ['conditions' => ['id' => $attr['ServiceAttribute']['attribute_id']]]);
		// 	// $this->x($attribute_detail);
		// 	$attribute['name'] = $attribute_detail['Attribute']['name'];
		// 	$attribute['type'] = $attribute_detail['Attribute']['type'] == 1 ? 'Amenity' : ($attribute_detail['Attribute']['type'] == 2 ? 'Included' : 'Extra');
		// 	$attribute['has_input'] = $attribute_detail['Attribute']['has_input'];
		// 	$attribute['icon_class'] = $attribute_detail['Attribute']['icon_class'] ? $attribute_detail['Attribute']['icon_class'] : 'fa fa-list';
		// 	$attribute['value'] = $attr['ServiceAttribute']['value'];
		// 	$attributes[] = $attribute;
		// }
		// $this->set('attributes', $attributes);
		//end of calling the attributes
	}

	public function x($v) {
		echo '<pre>';
		print_r($v);
		echo '</pre>';
		die;
	}
}