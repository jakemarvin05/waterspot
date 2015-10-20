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
}
