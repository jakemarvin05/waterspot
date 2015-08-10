<?php
Class Cart extends AppModel {
	var $actsAs = array('Multivalidatable');
	public $validate = array();
	var $validationSets = array(
		'check_out'=>array(
			'fname' =>
				array(
					'rule1' =>
					array(
					'rule' => array('maxLength',50),
					'message' => 'First name should be less than 50 character(s).'
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
					'rule' => array('maxLength',50),
					'message' => 'Last name should be less than 50 character(s).'
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
				
				/*'post_code' =>array(
						'rule1' =>
								array(
									 'rule' => 'notEmpty',
									 'message' => 'Please enter post code.'
									 ),
								array(
									'rule' => array('minLength',4),
									'message' => 'Post code should be more than 4 character(s).'
									 ),
								 
				),*/
			),
		'cart'=>array(
			'email' =>
				array(
					'rule1' =>
					array(
						'rule' => array('CheckmultipleEmail'),
						'message' => 'Please enter all emails.'
						
					), 
					'rule2' =>
					array(
						'rule' => array('is_duplicate'),
						'message' => 'Email should not be same.'
						
					),
					'rule2' =>
					array(
						'rule' => array('is_vendorEmail'),
						'message' => 'Inviter email should not be same.'
						
					)
				),
			)
	);
	 function is_duplicate($email){
		 if(count($this->data['Cart']['email'])!=count(array_unique($this->data['Cart']['email']))){
			 return false;
		 }else{
			 return true;
		 }
		 
	}
	function is_vendorEmail($email){
		App::uses('CakeSession', 'Model/Datasource');
		$guest_email=CakeSession::read('Guest_email');
        $inviter_email=CakeSession::read('MemberAuth.MemberAuth.email_id');
        if(!empty($inviter_email)){
			$guest_email=$inviter_email;
		}
		foreach($this->data['Cart']['email'] as $email){
			if($guest_email==$email){
				return false;
			}
		
		}
		return true;
		 
		 
	}
	function isValidUSPhoneFormat($phone){
		$phone=$this->data['Cart']['phone'];
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
     
    function CheckmultipleEmail($email){
		foreach($email['email'] as $key=>$email1){
			if(empty($email1)) {
				$errors [] = "Please enter all emails";
			}
			else if (!preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/', $email1)) {
				$errors [] = "Please enter valid email id.";
			}
		}
		if (!empty($errors))
		return false;//implode("\n", $errors);
       
		return true;
		 
	 
    }
    // delete cart details by id
    
		
	
	 function DeleteCartByCartId($cart_id){
		$this->deleteAll(array('Cart.id'=>$cart_id));
		return true;
	}
	function CheckCartId($cart_id=null,$session_id=null) {
		$count=$this->find('count',array('conditions'=>array('Cart.id'=>$cart_id,'Cart.session_id'=>$session_id)));
		
		return $count;
	}
    function GetCartServiceAmountById($cart_id=null) {
		$service_price=$this->find('first',array('fields'=>array('Cart.price','Cart.no_participants','Cart.total_amount'),'conditions'=>array('Cart.id'=>$cart_id)));
		
		return $service_price;
	}

	function CountBookingByServiceId($service_id=null) {
		$booking_count = $this->find('all',array('fields'=>array('SUM(Cart.no_participants) as total_count'), 'conditions'=>array('Cart.service_id'=>$service_id,'Cart.status'=>0,'Cart.vendor_confirm'=>3)));
		return $booking_count[0][0]['total_count'];
	}
 }
?>
