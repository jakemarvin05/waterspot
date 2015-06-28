<?php
class Member extends MemberManagerAppModel {
	public $name = 'Member';
	var $actsAs = array('Multivalidatable');
	public  $validate = array();
	public  $validationSets = array(
			'Register' => array( 
				'first_name'=> array( 
						array( 
							'rule' =>'notEmpty', 
							'message'=> 'Please enter your first name.'
							),
						array(
							'rule' => '/^[A-Za-z ]*$/',
							'message' => 'Please enter first name in alphabet.'
							) 
						),
				'last_name'=>  array( 
						array( 
							'rule' =>'notEmpty', 
							'message'=> 'Please enter your last name.'
							),
						array(
							'rule' => '/^[A-Za-z ]*$/',
							'message' => 'Please enter last name in alphabet.'
							)  
						),
				'email_id'=>  array( 
						'notEmpty'=>array(
							'rule' =>array('notEmpty'),
							'message'=> 'Please enter your email address.'
							),
						'email'=>array(
							'rule' =>array('email'),
							'message'=> 'Please enter valid email address.'
							),
						'isUnique'=>array(
							'rule'=>array('isUnique'),
							'message'=>'This email has already been registered with us.'//,
							//'on'=>'create'
							)
						),
				'password'=>  array( 
						array( 
							'rule' =>'notEmpty', 
							'message'=> 'Please enter password.'
							//'on'=>'create'
							),
						array( 
							'rule' =>array('minLength',6),
							'message'=> 'Password must be at least 6 characters long.'
							)
						),
				'confirm_password'=>array( 
							array( 
								'rule' =>'notEmpty', 
								'message'=> 'Confirm your password here.'
								//'on'=>'create'
							     ),
							array(
								'rule' => 'checkpasswords',
								//'required' => true,
								'message' => 'Your password and confirm password does not match.'
								//'on'=>'create'
							)
						),
				'phone'=> array(
						'notEmpty'=>array(
							'rule' =>array('notEmpty'),
							'message'=> 'Please enter your phone number.'
							),
						 
						'valid'=>array(
							'rule'=>array('isValidUSPhoneFormat'),
							'message'=>'Please enter valid phone number.'
							),
						'between' => array(
								'rule' => array('between', 8,15 ),
								'message' => 'Phone number must be between 8 to 15 digits.'
							)
						),
				),			
			'Reset' => array( 
				'password'=>  array( 
						array( 
							'rule' =>'notEmpty', 
							'message'=> 'Please enter your new password.'
							//'on'=>'create'
							),
						array( 
							'rule' =>array('minLength',6),
							'message'=> 'Password must be at least 6 characters long.'
							)
					),
				'confirm_password'=>array( 
						array( 
							'rule' =>'notEmpty', 
							'message'=> 'Please confirm your password.'
							//'on'=>'create'
						     ),
						array(
							'rule' => 'checkpasswords',
							//'required' => true,
							'message' => 'Your new password and confirm password does not match.'
							//'on'=>'create'
						)
					)
				),
			'Changepassword' => array(
				'current_password'=>  array( 
						array( 
							'rule' =>'notEmpty', 
							'message'=> 'Please enter your current password.'
							),
						array(
							'rule' => 'checkcurrentpasswords',
							'message' => 'Current password is invalid.'
						)
					),
				'password'=>  array( 
						array( 
							'rule' =>'notEmpty', 
							'message'=> 'Please enter your new password.'
						),
						array( 
							'rule' =>array('minLength',6),
							'message'=> 'Password must be at least 6 characters long.'
						)
					),
				'confirm_password'=>array( 
						array( 
							'rule' =>'notEmpty', 
							'message'=> 'Please confirm your password.'
						     ),
						array(
							'rule' => 'checkpasswords',
							'message' => 'New Password & Confirm Password must be match.'
						)
					)
				),
			'Login'=>array(
				'email_id' =>	array(
						'rule1' =>
							array(
							 'rule' => 'notEmpty',
							'message' => 'Please enter email address.'
							),
						array(
						   'rule' => array('email', true),
							'message' => 'Please enter email address in a correct format.'
						) 
				),
				'password'=>  array( 
					array( 
					'rule' =>'notEmpty', 
					'message'=> 'Please enter password.'
					) 
				),
			),
			'Forgot'=>array(
				'email_id' =>	array(
						'rule1' =>
							array(
							 'rule' => 'notEmpty',
							'message' => 'Please enter email address.'
							),
						array(
						   'rule' => array('email', true),
							'message' => 'Please enter email address in a correct format.'
						) 
				),
				 
			),
			'PasswordUrl'=>array(
				'password'=>  array( 
						array( 
							'rule' =>'notEmpty', 
							'message'=> 'Please enter password.'
							),
							array(
							'rule'    => array('minLength', 6),
							'message' => 'Password should be at least 6 digit long.'
							)
							 
						),
					'password2'=>array( 
						array( 
							'rule' =>'notEmpty', 
							'message'=> 'Confirm your password here.'
							 ),
						array(
							'rule' => 'checkpasswordurl',
							//'required' => true,
							'message' => 'Your password and confirm password does not match.'
							//'on'=>'create'
						)
					)
			),
			'change_email' => array(
				'current_password'=>  array( 
						array( 
							'rule' =>'notEmpty', 
							'message'=> 'Please enter your current password.'
							),
						array(
							'rule' => 'checkcurrentpasswords',
							'message' => 'Current password is invalid.'
						)
					),
				'email_id'=>  array( 
						'notEmpty'=>array(
							'rule' =>array('notEmpty'),
							'message'=> 'Please enter your email address.'
							),
						'email'=>array(
							'rule' =>array('email'),
							'message'=> 'Please enter valid email address.'
							),
						'isUnique'=>array(
							'rule'=>array('isUnique'),
							'message'=>'This email has already been registered with us.'//,
							//'on'=>'create'
							)
						),
				
				'confirm_email_id'=>array( 
						array( 
							'rule' =>'notEmpty', 
							'message'=> 'Please enter your confirm email address.'
						     ),
						array(
							'rule' => 'check_confirm_email',
							'message' => 'New email & confirm email address must be match.'
						)
					)
				),
			
		);
	
	function checkpasswords(){
		// to check pasword and confirm password
		if(strcmp($this->data['Member']['password'],$this->data['Member']['confirm_password']) == 0 ){
			return true;
		}
		return false;
	}
	function check_confirm_email(){
		// to check email and confirm email
		if(strcmp($this->data['Member']['email_id'],$this->data['Member']['confirm_email_id']) == 0 ){
			return true;
		}
		return false;
	}
	function checkcurrentpasswords()// to check current password 
	{		
		App::uses('MemberAuthComponent', 'MemberManager.Controller/Component');
		$this->sessionKey = MemberAuthComponent::$sessionKey;
		$this->Member_data = CakeSession::read($this->sessionKey);		
		$this->id = $this->Member_data[$this->sessionKey]['id'];
		
		$member_password = $this->field('password');
		if($member_password == Security::hash(Configure::read('Security.salt').$this->data['Member']['current_password']))
		{ 
			return true;
		}
		else
		{
			return false;
		}
	} 
	
	
	
	function check()     // to check any Manufacturer selected or not
	{
		foreach($this->data['Member']['manufacturer_id'] as $key=>$value)
		{
			if(!empty($value))
			{
				return true;
			}
		}
		foreach($this->data['Member']['less_manufacturer_id'] as $key=>$value)
		{
			if(!empty($value))
			{
				return true;
			}
		}
        return false;
	}
	
	function isValidUSPhoneFormat($phone){
	$phone=$this->data['Member']['phone'];
	$errors = array();
	   if(empty($phone)) {
	       $errors [] = "Please enter phone number";
	   }
	   else if (!preg_match('/^\+?[0-9 \-]+$/', $phone)) {
	   
	       $errors [] = "Please enter valid phone number.";
	   } 
       
	   if (!empty($errors))
	   return false;//implode("\n", $errors);
       
	   return true;
       }
	
	function countMember($data = null)
	{
		
		$this->criteria = array();
		 
		if(isset($data['email']) && !empty($data['email'])){
			$this->criteria['conditions']['Member.email'] = $data['email'];
		}
		 
		if(isset($data['password']) && !empty($data['password'])){
			$this->criteria['conditions']['Member.password'] = $data['password'];
		}
		  $this->criteria['conditions']['Member.status'] = 1;
		
		return $this->find('count',$this->criteria);
	}
	
	function GetMemberEmailByid($member_id = null){
		$member_email=$this->find('first',array('fields'=>array('Member.email_id'),'conditions'=>array('Member.id'=>$member_id)));
		return (!empty($member_email['Member']['email_id']))?$member_email['Member']['email_id']:"";
	}
	function auth_supplier(){
		return $this->find('first',$this->criteria);
	}
	function checkpasswordurl()     // to check pasword and confirm password
	{  
		if(strcmp($this->data['Member']['password'],$this->data['Member']['password2']) == 0 ) 
		{
		    return true;
		}
        return false;
	}
	
}
?>
