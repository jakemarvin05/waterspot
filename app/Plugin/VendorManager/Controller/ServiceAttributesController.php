<?php
Class ServiceAttributesController extends VendorManagerAppController{
	public $uses = array('VendorManager.Attribute', 'VendorManager.ServiceAttribute', 'VendorManager.Service');
	public $paginate = array();
	public $id = null;

	public function index($service_id=null) {
		$service = $this->Service->find('first', ['conditions' => ['id' => $service_id]]);
		
		$selected_attributes = $this->ServiceAttribute->find('all', ['conditions' => ['service_id' => $service_id]]);
		$attribute_ids = [];
		foreach ($selected_attributes as $attr) {
			$attribute_ids[$attr['ServiceAttribute']['attribute_id']] = $attr['ServiceAttribute']['value'];
		}

		$attributes = [];
		$all_attributes = $this->Attribute->find('all', ['conditions' => ['service_type_id' => $service['Service']['service_type_id']]]);
		foreach ($all_attributes as $attr) {
			$attribute = [];
			$attribute['is_checked'] = array_key_exists($attr['Attribute']['id'], $attribute_ids);
			$attribute['attribute_id'] = $attr['Attribute']['id'];
			$attribute['name'] = $attr['Attribute']['name'];
			$attribute['type'] = $attr['Attribute']['type'] == 1 ? 'Amenity' : ($attr['Attribute']['type'] == 2 ? 'Included' : ($attr['Attribute']['type'] == 3 ? 'Extra' : 'Detail'));
			$attribute['has_input'] = $attr['Attribute']['has_input'];
			$attribute['icon_class'] = $attr['Attribute']['icon_class'] ? $attr['Attribute']['icon_class'] : 'fa fa-list';
			$attribute['value'] = isset($attribute_ids[$attr['Attribute']['id']]) ? $attribute_ids[$attr['Attribute']['id']] : '';
			$attributes[] = $attribute;
		}

		$amenities = [];
		$included = [];
		$extra = [];
		$details = [];
		foreach ($attributes as $attr) {
			if ($attr['type'] == 'Amenity') {
				$amenities[] = $attr;
			} elseif ($attr['type'] == 'Included') {
				$included[] = $attr;
			} elseif ($attr['type'] == 'Extra') {
				$extra[] = $attr;
			} else {
				$details[] = $attr;
			}
		}
		$this->set('amenities', $amenities);
		$this->set('included', $included);
		$this->set('extra', $extra);
		$this->set('details', $details);
		$this->set('service_id', $service_id);

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

	public function save_attributes()
	{
		$attribute_ids = array_filter($this->request->data['ServiceAttribute']['attributes']);
		$service_id = $this->request->data['ServiceAttribute']['service_id'];
		foreach ($attribute_ids as $id) {
			$service_attribute = $this->ServiceAttribute->find('first', ['conditions' => ['service_id' => $service_id, 'attribute_id' => $id]]);
			$value = isset($this->request->data['ServiceAttribute']['attribute_value_'.$id]) ? $this->request->data['ServiceAttribute']['attribute_value_'.$id] : null;
			$data = [
				'value' => $value,
				'service_id' => $service_id,
				'attribute_id' => $id,
			];
			if (!$service_attribute) {
				$this->ServiceAttribute->create();
			} else {
				$this->ServiceAttribute->id = $service_attribute['ServiceAttribute']['id'];
			}
			$this->ServiceAttribute->save($data);
		}

		$selected_attributes = $this->ServiceAttribute->find('all', ['conditions' => ['service_id' => $service_id]]);
		$attribute_ids_old = [];
		foreach ($selected_attributes as $attr) {
			$attribute_ids_old[] = $attr['ServiceAttribute']['attribute_id'];
		}
		foreach ($attribute_ids_old as $id) {
			if (!in_array($id, $attribute_ids)) {
				$service_attribute = $this->ServiceAttribute->find('first', ['conditions' => ['service_id' => $service_id, 'attribute_id' => $id]]);
				$this->ServiceAttribute->delete($service_attribute['ServiceAttribute']['id']);
			}
		}
		$this->Session->setFlash(__('Attributes has been saved successfully'));
    	return $this->redirect(Controller::referer());
	}

	public function admin_index($service_id=null) {
		$service = $this->Service->find('first', ['conditions' => ['id' => $service_id]]);
		
		$selected_attributes = $this->ServiceAttribute->find('all', ['conditions' => ['service_id' => $service_id]]);
		$attribute_ids = [];
		foreach ($selected_attributes as $attr) {
			$attribute_ids[$attr['ServiceAttribute']['attribute_id']] = $attr['ServiceAttribute']['value'];
		}

		$attributes = [];
		$all_attributes = $this->Attribute->find('all', ['conditions' => ['service_type_id' => $service['Service']['service_type_id']]]);
		foreach ($all_attributes as $attr) {
			$attribute = [];
			$attribute['is_checked'] = array_key_exists($attr['Attribute']['id'], $attribute_ids);
			$attribute['attribute_id'] = $attr['Attribute']['id'];
			$attribute['name'] = $attr['Attribute']['name'];
			$attribute['type'] = $attr['Attribute']['type'] == 1 ? 'Amenity' : ($attr['Attribute']['type'] == 2 ? 'Included' : ($attr['Attribute']['type'] == 3 ? 'Extra' : 'Detail'));
			$attribute['has_input'] = $attr['Attribute']['has_input'];
			$attribute['icon_class'] = $attr['Attribute']['icon_class'] ? $attr['Attribute']['icon_class'] : 'fa fa-list';
			$attribute['value'] = isset($attribute_ids[$attr['Attribute']['id']]) ? $attribute_ids[$attr['Attribute']['id']] : '';
			$attributes[] = $attribute;
		}

		$amenities = [];
		$included = [];
		$extra = [];
		$details = [];
		foreach ($attributes as $attr) {
			if ($attr['type'] == 'Amenity') {
				$amenities[] = $attr;
			} elseif ($attr['type'] == 'Included') {
				$included[] = $attr;
			} elseif ($attr['type'] == 'Extra') {
				$extra[] = $attr;
			} else {
				$details[] = $attr;
			}
		}
		$this->set('amenities', $amenities);
		$this->set('included', $included);
		$this->set('extra', $extra);
		$this->set('details', $details);
		$this->set('service_id', $service_id);
	}

	public function admin_save_attributes()
	{
		$attribute_ids = array_filter($this->request->data['ServiceAttribute']['attributes']);
		$service_id = $this->request->data['ServiceAttribute']['service_id'];
		foreach ($attribute_ids as $id) {
			$service_attribute = $this->ServiceAttribute->find('first', ['conditions' => ['service_id' => $service_id, 'attribute_id' => $id]]);
			$value = isset($this->request->data['ServiceAttribute']['attribute_value_'.$id]) ? $this->request->data['ServiceAttribute']['attribute_value_'.$id] : null;
			$data = [
				'value' => $value,
				'service_id' => $service_id,
				'attribute_id' => $id,
			];
			if (!$service_attribute) {
				$this->ServiceAttribute->create();
			} else {
				$this->ServiceAttribute->id = $service_attribute['ServiceAttribute']['id'];
			}
			$this->ServiceAttribute->save($data);
		}

		$selected_attributes = $this->ServiceAttribute->find('all', ['conditions' => ['service_id' => $service_id]]);
		$attribute_ids_old = [];
		foreach ($selected_attributes as $attr) {
			$attribute_ids_old[] = $attr['ServiceAttribute']['attribute_id'];
		}
		foreach ($attribute_ids_old as $id) {
			if (!in_array($id, $attribute_ids)) {
				$service_attribute = $this->ServiceAttribute->find('first', ['conditions' => ['service_id' => $service_id, 'attribute_id' => $id]]);
				$this->ServiceAttribute->delete($service_attribute['ServiceAttribute']['id']);
			}
		}
		$this->Session->setFlash(__('Attributes has been saved successfully'));
    	return $this->redirect(Controller::referer());
	}

	public function x($v) {
		echo '<pre>';
		print_r($v);
		echo '</pre>';
		die;
	}
}