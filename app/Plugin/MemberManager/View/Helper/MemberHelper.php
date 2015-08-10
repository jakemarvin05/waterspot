<?php
class MemberHelper extends AppHelper {
	var $helpers = Array('Html','Session');
	var $sessionKey = "";
	var $id = null;
	var $member_data = array();
    
	public function isMemberLogin(){
		
		return (int)$this->id;
	}
	
	public function beforeRender($viewFile=null){
		
		App::uses('MemberAuthComponent', 'MemberManager.Controller/Component');
		$this->sessionKey = MemberAuthComponent::$sessionKey;		
		
		$this->member_data = $this->Session->read($this->sessionKey);
		
		 if(!empty($this->member_data[$this->sessionKey])){	
			$this->id = $this->member_data[$this->sessionKey]['id'];
		 }
	}
	
	public function activeMemberDetails(){
		return $this->member_data[$this->sessionKey];
	}
	
	public function show(){		
		$member = $this->member_data[$this->sessionKey];
		$totalcart = 0;
		if(!empty($this->_View->viewVars['totalcart'])){
			$totalcart = (int)$this->_View->viewVars['totalcart'];

		}
		$data = '<div class="navButtonOuter"><i class="fa fa-user"></i><span class="navTextLabel" style="font-weight:bold; ">Logged in as '. $member['first_name'].' '.$member['last_name'] . ' ' . $this->Html->link('LOGOUT',array('plugin'=>'member_manager','controller'=>'members','action'=>'logout')).'</div>';
		//return $data;
		$data= '<ul class="dropdown-menu" aria-labelledby="dropdownMenu2">';
		//$data .= '<span style="float:left;">Welcome,</span>';
		//$data .= ' <li><a href="">'.$member['first_name'].' '.$member['last_name'].'</a></li>';
		$data .= '<li>'.$this->Html->link('Dashboard',array('plugin'=>'member_manager','controller'=>'members','action'=>'dashboard')).'</li>';	
		$data.= '<li><a href="">My Account</a>';
		$data .= "<ul>";
		$data .= '<li>'.$this->Html->link('Edit Profile',array('plugin'=>'member_manager','controller'=>'members','action'=>'edit_profile')).'</li>';
		$data .= '<li>'.$this->Html->link('My Bookings',array('plugin'=>'member_manager','controller'=>'bookings','action'=>'booking_list')).'</li>';
		$data .= '<li>'.$this->Html->link('Change Password',array('plugin'=>'member_manager','controller'=>'members','action'=>'changepassword')).'</li>';
		$data .= "</ul>";
		$data.= "</li>";
		$data .= '<li>'.$this->Html->link('Logout','#', ['onclick' => 'setCookie("fb_remember", "false", -30); window.location = "/members/logout";']).'</li>';
		$data .= '<li style="border:none;">'.$this->Html->link('My Cart ('.$totalcart.')',array('plugin'=>false,'controller'=>'carts','action'=>'check_out')).'</li>';
		 
		$data.= "</ul>";
		
		return $data;
	}
}
?>
