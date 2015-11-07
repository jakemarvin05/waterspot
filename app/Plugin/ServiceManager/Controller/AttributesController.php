<?php
class AttributesController extends ServiceManagerAppController {
	public $uses = array('VendorManager.Attribute');
	public $paginate = array();
	public $id = null;

	function admin_add_attribute_save($id=null){
		$data = $this->request->data;
		$this->Attribute->create();
		$this->Attribute->save($this->request->data,array('validate' => false));
		$this->Session->setFlash(__('Service type Attribute has been added successfully'));
    	return $this->redirect(Controller::referer());
	}

	function admin_remove_attribute_save($id=null){
		$this->Attribute->delete($id);
		$this->Session->setFlash(__('Service type Attribute has been removed successfully'));
    	return $this->redirect(Controller::referer());
	}

	function admin_edit_attribute_save($id=null){
		if ($id == null) {
			$this->Session->setFlash(__('Attribute ID not found!'));
	    	return $this->redirect(Controller::referer());
		}
		$this->Attribute->id = $id;
		$this->Attribute->save($this->request->data);
		$this->Session->setFlash(__('Attribute has been successfully updated'));
		$url = '/admin/service_manager/service_types/add_attribute/' . $this->request->data['Attribute']['service_type_id'];
    	return $this->redirect($url);
	}
}
