<?php
class PaymentManagerAppController extends AppController{
	public $components = array();
	public $helpers = array();
	public function beforeFilter(){
		parent::beforeFilter();
		//Configure::load('PaymentManager.config');	
	}
}
?>
