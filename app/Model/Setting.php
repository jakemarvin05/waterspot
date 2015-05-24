<?php
// app/Model/User.php
class Setting extends AppModel 
{
    public $name = "Setting";
    
    public $validate=array(
		'business_email_paypal' =>
			array(
				'rule1' =>
				array(
					  'rule' => 'notEmpty',
					'message' => 'Please enter associated (business) email id.'
					
				),
				array(
				   'rule' => array('email', true),
					'message' => 'Please enter valid email address.'
				) 
        ),	
	);
}
?>
