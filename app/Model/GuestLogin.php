<?php
Class GuestLogin extends AppModel{
	public $useTable = 'members'; // This model does not use a database table
	public $validate = array(
			'email_id' =>
				array(
					'rule1' =>
					array(
						  'rule' => 'notEmpty',
						'message' => 'Please enter email id.'
						
					),
					array(
					   'rule' => array('email', true),
						'message' => 'Please enter valid email address.'
					)
				),
		   'GuestLogin' =>
				array(
					 array(
						'rule' => 'notEmpty',
						'message' => 'Please select guest or login.'
					 ),
					
				 ), 
			 'password' =>
				array(
					 array(
						'rule' => 'Check_password',
						'message' => 'Please enter your password.'
					 ),
					  array(
						'rule' => 'Check_login',
						'message' => 'Please enter correct email/password.'
					 ),
				 ), 
			 
		);
		function Check_password(){
			if($this->data['GuestLogin']['GuestLogin']==1) {
				
				if(!empty($this->data['GuestLogin']['password'])){
					return true;
				}
				 
			}else{
				return true;
			}
			
			
		}
		function Check_login(){
			 
			if($this->data['GuestLogin']['GuestLogin']==1) {
				 	
				if(!empty($this->data['GuestLogin']['password'])){
					
					$this->data['GuestLogin']['password']=Security::hash(Configure::read('Security.salt').$this->data['GuestLogin']['password']);
					
				 	$check_login=$this->find('count',array('conditions'=>array('GuestLogin.email_id'=>$this->data['GuestLogin']['email_id'],'GuestLogin.password'=>$this->data['GuestLogin']['password'],
				 	'GuestLogin.active'=>1)));
					return ($check_login==1)?true:false;
				}
				 
			}else{
				return true;
			}
			
			
		}
	}
?>
