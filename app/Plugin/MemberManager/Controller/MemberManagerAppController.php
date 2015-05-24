<?php 
class MemberManagerAppController extends AppController{
	var $components = array('MemberManager.MemberAuth');
	var $helpers = array('Time');
	
	function beforeFilter(){
		$this->MemberAuth->deny_action =array('home','changepassword','edit_profile','booking_list','booking_details','dashboard','invite_booking','change_email');
		$this->MemberAuth->loginAction = array('controller' => 'members', 'action' => 'registration','plugin'=>'member_manager');
		$this->MemberAuth->loginRedirect = array('controller' => 'members', 'action' => 'dashboard','plugin'=>'member_manager');
		$this->MemberAuth->logoutRedirect = array('controller' => 'members', 'action' => 'registration','plugin'=>'member_manager');
		$this->MemberAuth->userScope = array('Member.active' => '1');
		$this->MemberAuth->messages = array('direct_access' =>'You are not authorized to access that location', 'auth_fail' =>'Invalid email or password','auth_deactivate'=>'Your account has been disabled from administrator. Please contact us.');
		parent::beforeFilter();
	}
}
?>
