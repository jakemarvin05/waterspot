<?php
class VendorHelper extends AppHelper {
	var $helpers = Array('Html','Session');
    var $sessionKey = "";
    var $id = null;
    var $vendor_data = array();
    
    public function isVendorLogin(){
		
		return $this->id;
	}
	
	public function beforeRender($viewFile=null){
		
		App::uses('VendorAuthComponent', 'VendorManager.Controller/Component');
		$this->sessionKey = VendorAuthComponent::$sessionKey;
		$this->vendor_data = $this->Session->read($this->sessionKey);
		
		 if(!empty($this->vendor_data[$this->sessionKey])){
			$this->id = $this->vendor_data[$this->sessionKey]['id'];
		 }
	}
	
	public function activeVendorDetails(){
		return $this->vendor_data[$this->sessionKey];
	}
	
	public function show(){
		$vendor = $this->vendor_data[$this->sessionKey];
		$totalcart = (!empty($this->_View->viewVars['totalcart']))?$this->_View->viewVars['totalcart']:0;
		$data= '<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu2">';
		//$data .= '<span style="float:left;">Welcome,</span>';
		//$data .= ' <li><a href="">'.$vendor['fname'].' '.$vendor['lname'].'</a></li>';
		$data .= '<li>'.$this->Html->link('Dashboard',array('plugin'=>'vendor_manager','controller'=>'vendors','action'=>'dashboard')).'</li>';
		$data .= '<li>'.$this->Html->link('My Services',array('plugin'=>'vendor_manager','controller'=>'services','action'=>'my_services')).'</li>';
		$data .= '<li>'.$this->Html->link('My Bookings',array('plugin'=>'vendor_manager','controller'=>'bookings','action'=>'booking_list')).'</li>';
		$data .= '<li>'.$this->Html->link('Edit Profile',array('plugin'=>'vendor_manager','controller'=>'accounts','action'=>'editProfile')).'</li>';
		$data .= '<li>'.$this->Html->link('Change Password',array('plugin'=>'vendor_manager','controller'=>'accounts','action'=>'changepassword')).'</li>';
		$data.= "</li>";
		$data .= '<li>'.$this->Html->link('Logout',array('plugin'=>'vendor_manager','controller'=>'vendors','action'=>'logout')).'</li>';
		$data .= '<li style="border:none;">'.$this->Html->link('My Cart ('.$totalcart.')',array('plugin'=>false,'controller'=>'carts','action'=>'check_out')).'</li>';
		$data.= "</ul>";
		
		return $data;
	}
}
?>
