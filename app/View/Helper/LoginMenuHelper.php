<?php
class LoginMenuHelper extends Helper {
	public $helpers = array('VendorManager.Vendor','MemberManager.Member');
	public $member_data = array();
	public $isLogin = null;
	public $data = "";
	public function isLogin(){
		return $this->isLogin;
	}
	function show1(){
		$data = "";
		if($this->Vendor->isVendorLogin()){
			$data = $this->Vendor->show();	 
		}
		if($data=="")
		{
			
		}
		return $data;
	}
	function show(){
		return $this->data;
	}
	
	function beforeRender($viewFile){
		$this->Vendor->beforeRender();
		$this->Member->beforeRender();		
		$id =null;
		if($this->Vendor->isVendorLogin()){
			$this->data = $this->Vendor->show();
			$this->isLogin = true;
		}else if($this->Member->isMemberLogin()){
			$this->data = $this->Member->show();
			$this->isLogin = true;
		}
		
	}
}
?>
