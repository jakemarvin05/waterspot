<?php
Class VendorManagerAppController extends AppController{
	public $components = array('VendorManager.VendorAuth','VendorManager.VendorManager');
	public $helpers = array('VendorManager.Vendor','VendorManager.Time');
	public function beforeFilter(){
		$this->loadModel('ContentManager.Page');
		$this->current_page_id = 5;
		$page=$this->Page->read(null,5);
		$this->title_for_layout = $page['Page']['page_title'];
		//$this->metakeyword = $page['Page']['page_metakeyword'];
		//$this->metadescription = $page['Page']['page_metadescription'];
		$this->VendorAuth->deny_action =array('dashboard','editProfile','changepassword','paynow','logout','make_payment','payment_process','add_slots','add_services','add_service_slots','my_services','cancel_booking','booking_slot_details','booking_member_invite_details','booking_vas_details','booking_details','booking_list','change_email','reviews');
		$this->VendorAuth->loginAction =  array('plugin'=>'vendor_manager','controller'=>'vendors','action'=>'registration');
		$this->VendorAuth->loginRedirect =  array('plugin'=>'vendor_manager','controller'=>'vendors','action'=>'dashboard');
		$this->VendorAuth->logoutRedirect = array('plugin'=>'vendor_manager','controller'=>'vendors','action'=>'registration');
		$this->VendorAuth->messages = array('logout'=>'You\'re now logged out','direct_access' =>'You are not authorized to access that location.', 'auth_fail' =>'Invalid email or password.','admin_approv'=>__('Sorry! Admin has not approved your registration.'));
		parent::beforeFilter();
		Configure::load('VendorManager.config');	
	}
}
?>
