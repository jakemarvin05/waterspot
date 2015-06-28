<?php
// app/Model/User.php
App::uses('AuthComponent', 'Controller/Component');
class User extends AppModel {
    public $name = "User";
    var $actsAs = array('Multivalidatable');
    public $validate = array(
	
    );
    
    
    public function beforeSave($options = array()) {
		if (isset($this->data[$this->alias]['password'])) {
			$this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
		}
		return true;
	}
    
    function checkpasswords()     // to check pasword and confirm password
	{  
		if(strcmp($this->data['User']['password'],$this->data['User']['password2']) == 0 ) 
		{
		    return true;
		}
        return false;
	}
	
		
	function checkpassword()     // to check pasword and confirm password
	{  
		if(strcmp($this->data['User']['password'],$this->data['User']['password2']) == 0 ) 
		{
		    return true;
		}
        return false;
	}
	
	var $validationSets=array(
		'ResetPassword'=>array(
			'email' =>	
				array(
					'rule1' =>
						array(
							 'rule' => 'notEmpty',
							'message' => 'Please enter your email address.'
						),
						array(
						   'rule' => array('email', true),
							'message' => 'Please enter valid email address e.g., abc@example.com'
					) 
				)
		),
		'AdminLogin'=>array(
			'username' =>	
				array(
					'rule1' =>
						array(
							 'rule' => 'notEmpty',
							'message' => 'Please enter user name.'
						)
			),
			'password' =>	
				array(
					'rule1' =>
						array(
							 'rule' => 'notEmpty',
							'message' => 'Please enter your password.'
						)
				)
		),
		'NewUserForm'=>array(
				'name' => array(
					'notEmpty' => array(
						'rule' => array('notEmpty'),
						'message' => 'Please enter user\'s first name.'
					)
				),
				
			'lname' => array(
					'notEmpty' => array(
						'rule' => array('notEmpty'),
						'message' => 'Please enter user\'s last name.'
					)
				),
				'email' => array(
						'notEmpty'=>array(
						'rule' =>array('notEmpty'),
						'message'=> 'Please enter user\'s email address.'
						),
						'isUnique'=>array(
						'rule'=>array('isUnique'),
						'message'=>'This email has already been registered.'
					), 
				'email'=>array(
					'rule' =>array('email'),
					'message'=> 'Please enter valid email address.'
						)
					),
				'username' => array(
					'notEmpty' => array(
						'rule' => array('notEmpty'),
						'message' => 'Please enter user\'s name.'
					),
				'isUnique'=>array(
					'rule'=>array('isUnique'),
					'message'=>'This username has already been registered.'
					)
				),
		),
		'ResetRegistrationPasswordForm'=>array(
			'password'=>  array( 
					array( 
						'rule' =>'notEmpty', 
						'message'=> 'Please enter password.'
						),
						array(
						'rule'    => array('minLength', 6),
						'message' => 'Password should be at least 6 characters.'
						)
						 
					),
				'password2'=>array( 
					array( 
						'rule' =>'notEmpty', 
						'message'=> 'Confirm your password here.'
						 ),
					array(
						'rule' => 'checkpassword',
						//'required' => true,
						'message' => 'Your password and confirm password does not match.'
						//'on'=>'create'
					)
				)
		),
		'PasswordChangeAdmin'=>array(
			'oldpassword'=>  array( 
					array( 
						'rule' =>'notEmpty', 
						'message'=> 'Please enter old password.'
						)
					),
			'password'=>array( 
				array( 
					'rule' =>'notEmpty', 
					'message'=> 'Please enter new password.'
					 ),
				array(
					'rule'    => array('minLength', 6),
					'message' => 'Password should be at least 6 characters.'
					)
			),
			'password2'=>array( 
					array( 
						'rule' =>'notEmpty', 
						'message'=> 'Confirm your password here.'
						 ),
					array(
						'rule' => 'checkpassword',
						//'required' => true,
						'message' => 'Your new password and confirm password does not match.'
						//'on'=>'create'
					)
				)
			
		),
		'UserProfileUpdate'=>array(
			'name'=>  array( 
					array( 
						'rule' =>'notEmpty', 
						'message'=> 'Please enter name.'
						),
					array(
						'rule' => '/^[A-Za-z ]*$/',
						'message' => 'Please enter name in alphabet.'
						)	
					),
			'lname'=>array( 
				array( 
					'rule' =>'notEmpty', 
					'message'=> 'Please enter last name.'
					 ),
				 array(
					'rule' => '/^[A-Za-z ]*$/',
					'message' => 'Please enter last name in alphabet.'
				)
			),
			'email' => array(
						'notEmpty'=>array(
						'rule' =>array('notEmpty'),
						'message'=> 'Please enter user\'s email address.'
						),
						'isUnique'=>array(
						'rule'=>array('isUnique'),
						'message'=>'This email has already been registered.'
					), 
				'email'=>array(
					'rule' =>array('email'),
					'message'=> 'Please enter valid email address.'
						)
					),
			
		),
	);
    
    
    
    
}


?>
