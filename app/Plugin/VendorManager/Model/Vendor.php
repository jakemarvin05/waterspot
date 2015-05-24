<?php
Class Vendor extends VendorManagerAppModel {
	public $name = "Vendor";
	var $actsAs = array('Multivalidatable');
	public $virtualFields = array(
		'name' => 'CONCAT(Vendor.fname, " ", Vendor.lname)'
	);
	 
	public $validate = array(
			'bname' =>
				array(
					'rule1' =>
					array(
					'rule' => array('maxLength',100),
					'message' => 'Business Name should be less than 100 character(s).'
					 ),
					 array(
					 'rule' => 'notEmpty',
					 'message' => 'Please enter business name.'
					 ) 
				 ),
			'fname' =>
				array(
					'rule1' =>
					array(
					'rule' => array('maxLength',100),
					'message' => 'First name should be less than 100 character(s).'
					 ),
					 array(
					 'rule' => 'notEmpty',
					 'message' => 'Please enter first name.'
					 ),
					array(
					'rule' => '/^[A-Za-z ]*$/',
					'message' => 'Please enter first name in alphabet.'
					)
				 ),
				'lname' =>
				array(
					'rule1' =>
					array(
					'rule' => array('maxLength',100),
					'message' => 'Last name should be less than 100 character(s).'
					 ),
					 array(
					 'rule' => 'notEmpty',
					 'message' => 'Please enter last name.'
					 ),
					array(
					'rule' => '/^[A-Za-z ]*$/',
					'message' => 'Please enter last name in alphabet.'
					)
				 ),
			
				'email' =>
				array(
					'rule1' =>
					array(
						  'rule' => 'notEmpty',
						'message' => 'Please enter email id.'
						
					),
					array(
					   'rule' => array('email', true),
						'message' => 'Please enter email address in a correct format.'
					),
					'isUnique'=>array(
						'rule'=>array('isUnique'),
						'message'=>'This email is already registered.',
						 
					) 
				),
				'phone' => array(
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
				
				'password'=>  array( 
					array( 
						'rule' =>'notEmpty', 
						'message'=> 'Please enter password.'
						) 
					),
				'confirm_password'=>array( 
					array( 
						'rule' =>'notEmpty', 
						'message'=> 'Confirm your password here.'
						 ),
					array(
						'rule' => 'checkpasswords',
						//'required' => true,
						'message' => 'Your password and confirm password does not match.'
						//'on'=>'create'
					)
				),
				
				//'approval' => array(
					//'notempty' => array(
						//'rule' => array('comparison', '!=', 0),//'checkAgree',
						//'message' => 'Please check to approve vendor.',
						//'allowEmpty' => false,
						//'required' => true,
						//'last' => true, // Stop validation after this rule
					//),
				//),
				
		
	 
    );
    
    function isValidUSPhoneFormat($phone){
	$phone=$this->data['Vendor']['phone'];
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
       
	function checkpasswords()     // to check pasword and confirm password
	{  
		if(strcmp($this->data['Vendor']['password'],$this->data['Vendor']['confirm_password']) == 0 ) 
		{
		    return true;
		}
        return false;
	}
	
	function checkpasswordurl()     // to check pasword and confirm password
	{  
		if(strcmp($this->data['Vendor']['password'],$this->data['Vendor']['password2']) == 0 ) 
		{
		    return true;
		}
        return false;
	}
	
	function checkpasswordschange()     // to check pasword and confirm password during change password
	{  
		if(strcmp($this->data['Vendor']['new_password'],$this->data['Vendor']['confirm_password1']) == 0 ) 
		{
		    return true;
		}
        return false;
	}
	
	var $validationSets = array(
		'LoginForm'=>array(
			'emailid' =>	array(
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
			'pass'=>  array( 
				array( 
					'rule' =>'notEmpty', 
					'message'=> 'Please enter password.'
					) 
			),
		),
		
		'ResetPassword'=>array(
			'email' =>	
				array(
					'rule1' =>
						array(
							 'rule' => 'notEmpty',
							'message' => 'Please enter email address.'
						),
						array(
						   'rule' => array('email', true),
							'message' => 'Please enter email address in a correct format.'
					) 
				)
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
		
		'Register'=>array(
			'bname' =>
				array(
					'rule1' =>
					array(
					'rule' => array('maxLength',100),
					'message' => 'Business Name should be less than 100 character(s).'
					 ),
					 array(
					 'rule' => 'notEmpty',
					 'message' => 'Please enter business name.'
					 ) 
				 ),
			'fname' =>
				array(
					'rule1' =>
					array(
					'rule' => array('maxLength',100),
					'message' => 'First name should be less than 100 character(s).'
					 ),
					 array(
					 'rule' => 'notEmpty',
					 'message' => 'Please enter first name.'
					 ),
					array(
					'rule' => '/^[A-Za-z ]*$/',
					'message' => 'Please enter first name in alphabet.'
					)
				 ),
				'lname' =>
				array(
					'rule1' =>
					array(
					'rule' => array('maxLength',100),
					'message' => 'Last name should be less than 100 character(s).'
					 ),
					 array(
					 'rule' => 'notEmpty',
					 'message' => 'Please enter last name.'
					 ),
					array(
					'rule' => '/^[A-Za-z ]*$/',
					'message' => 'Please enter last name in alphabet.'
					)
				 ),
			
				'email' =>
				array(
					'rule1' =>
					array(
						  'rule' => 'notEmpty',
						'message' => 'Please enter email address.'
						
					),
					array(
					   'rule' => array('email', true),
						'message' => 'Please enter email address in a correct format.'
					),
					'isUnique'=>array(
						'rule'=>array('isUnique'),
						'message'=>'This email is already registered.',
						 
					) 
				),
				'phone' => array(
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
				'confirm_password'=>array( 
					array( 
						'rule' =>'notEmpty', 
						'message'=> 'Confirm your password here.'
						 ),
					array(
						'rule' => 'checkpasswords',
						//'required' => true,
						'message' => 'Your password and confirm password does not match.'
						//'on'=>'create'
					)
				),
				
				//'approval' => array(
					//'notempty' => array(
						//'rule' => array('comparison', '!=', 0),//'checkAgree',
						//'message' => 'Please check to approve vendor.',
						//'allowEmpty' => false,
						//'required' => true,
						//'last' => true, // Stop validation after this rule
					//),
				//),
				
		),
		
		'Admin-register'=>array(
			'bname' =>
				array(
					'rule1' =>
					array(
					'rule' => array('maxLength',100),
					'message' => 'Business Name should be less than 100 character(s).'
					 ),
					 array(
					 'rule' => 'notEmpty',
					 'message' => 'Please enter business name.'
					 ) 
				 ),
			'fname' =>
				array(
					'rule1' =>
					array(
					'rule' => array('maxLength',100),
					'message' => 'First name should be less than 100 character(s).'
					 ),
					 array(
					 'rule' => 'notEmpty',
					 'message' => 'Please enter first name.'
					 ),
					array(
					'rule' => '/^[A-Za-z ]*$/',
					'message' => 'Please enter first name in alphabet.'
					)
				 ),
				'lname' =>
				array(
					'rule1' =>
					array(
					'rule' => array('maxLength',100),
					'message' => 'Last name should be less than 100 character(s).'
					 ),
					 array(
					 'rule' => 'notEmpty',
					 'message' => 'Please enter last name.'
					 ),
					array(
					'rule' => '/^[A-Za-z ]*$/',
					'message' => 'Please enter last name in alphabet.'
					)
				 ),
			
				'email' =>
				array(
					'rule1' =>
					array(
						  'rule' => 'notEmpty',
						'message' => 'Please enter email id.'
						
					),
					array(
					   'rule' => array('email', true),
						'message' => 'Please enter email address in a correct format.'
					),
					'isUnique'=>array(
						'rule'=>array('isUnique'),
						'message'=>'This email is already registered.',
						 
					) 
				),
				'phone' => array(
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
				'account_type' => array(
							'notEmpty'=>array(
								'rule' =>array('notEmpty'),
								 'allowEmpty' => false,
								'message'=> 'Please select vendor acount type.'
								),
								'chackamount'=>array(
									'rule'=>array('chackamount'),
									'message'=>'Please enter amount.'
								), 
				),
				'commission'=>array(
							'notEmpty'=>array(
								'rule' =>array('money', 'left'),
								'allowEmpty' => true,
								'message'=> 'Please correct commission.'
								),
								
							)		 
				/*'payment_amount' => array(
						'notEmpty'=>array(
								'rule' =>array('money', 'left'),
								'message'=> 'Please enter payment amount.'
								),
					'chackamount'=>array(
								'rule'=>array('comparison', '>=', 1),
								'message'=>'Amount should be greater than 1.'
								),
						 
							 	
								
				),*/	
				
				//'approval' => array(
					//'notempty' => array(
						//'rule' => array('comparison', '!=', 0),//'checkAgree',
						//'message' => 'Please check to approve vendor.',
						//'allowEmpty' => false,
						//'required' => true,
						//'last' => true, // Stop validation after this rule
					//),
				//),
				
		),
		
		'Change-Password'=>array(
			'new_password'=>  array( 
					array( 
						'rule' =>'notEmpty', 
						'message'=> 'Please enter new password.'
						),
					array(
						'rule'    => array('minLength', 6),
						'message' => 'Password should be at least 6 digit long.'
						)	 
					),
				'confirm_password1'=>array( 
					array( 
						'rule' =>'notEmpty', 
						'message'=> 'Confirm your password here.'
						 ),
					array(
						'rule' => 'checkpasswordschange',
						//'required' => true,
						'message' => 'Password and confirm password are not same.'
						//'on'=>'create'
					)
				),
			'old_password'=>  array( 
				array( 
					'rule' =>'notEmpty', 
					'message'=> 'Please enter password.'
					),
				array(
						'rule' => 'checkcurrentpasswords',
						'message' => 'Your Current Password does not match.'
					)
			),
		),
		'change_email' => array(
				'old_password'=>  array( 
						array( 
							'rule' =>'notEmpty', 
							'message'=> 'Please enter your current password.'
							),
						array(
							'rule' => 'checkcurrentpasswords',
							'message' => 'Current password is invalid.'
						)
					),
				'email'=>  array( 
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
				
				'confirm_email'=>array( 
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
	function vendorNameEmailById($vendor_id = null) {
		$vendor_details=$this->find('first',array('conditions'=>array('Vendor.id'=>$vendor_id),'fields'=>array('Vendor.fname','Vendor.email')));
		return $vendor_details; 
	} 
	
	/*function vendorNameById($vendor_id = null) {
		$vendor_name=$this->find('first',array('conditions'=>array('Vendor.id'=>$vendor_id),'fields'=>array('Vendor.fname','Vendor.lname')));
		return (!empty($vendor_name))?$vendor_name['Vendor']['fname']." ".$vendor_name['Vendor']['lname']:""; 
	} */
	function vendorDetalId($vendor_id = null) {
		$vendor_details=$this->find('first',array('conditions'=>array('Vendor.id'=>$vendor_id),'fields'=>array('Vendor.*')));
		return $vendor_details; 
	} 
	function vendorDetailId($vendor_id = null) {
		$vendor_details=$this->find('first',array('conditions'=>array('Vendor.id'=>$vendor_id),'fields'=>array('Vendor.*')));
		return $vendor_details; 
	} 
	function vendorList() {
		$vendor_lists=array();
		$vendor_lists=$this->find('all',array('conditions'=>array('Vendor.active'=>1,'Vendor.approval'=>1),'fields'=>array('Vendor.name','Vendor.bname'),'order'=>array('Vendor.bname'=>'ASC')));
		$new_vendor_list=array();
		foreach($vendor_lists as $key=>$vendor_list){
			$new_vendor_list[$key]=(!empty($vendor_list['Vendor']['bname']))?$vendor_list['Vendor']['bname']:$vendor_list['Vendor']['name'];
		}
		return $new_vendor_list; 
	}
	function checkcurrentpasswords()// to check current password 
	{	App::uses('VendorAuthComponent', 'VendorManager.Controller/Component');
		$this->sessionKey = VendorAuthComponent::$sessionKey;
		$this->Vendor_data = CakeSession::read($this->sessionKey);		
		 $this->id = $this->Vendor_data[$this->sessionKey]['id'];
		 $vendor_password = $this->field('password');
		if($vendor_password == Security::hash(Configure::read('Security.salt').$this->data['Vendor']['old_password']))
		{ 
			return true;
		}
		else
		{
			return false;
		}
	}
	function check_confirm_email(){
		// to check email and confirm email
		if(strcmp($this->data['Vendor']['email'],$this->data['Vendor']['confirm_email']) == 0 ){
			return true;
		}
		return false;
	} 
	function chackamount(){
	
		if($this->data['Vendor']['account_type']==1){
			if($this->data['Vendor']['payment_amount']>0){
				return true;
			}else{
				return false;
			}
			
		}else{
			return true;
		}
		//pr($this->data);die;
		
	}
    
}
?>
