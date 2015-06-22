<?php 
class ServiceManagerAppController extends AppController {
	public function beforeFilter(){
		parent::beforeFilter();
		Configure::load('ServiceManager.config');	
	}
}
?>
