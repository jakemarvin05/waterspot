<?php
Class Payment extends AppModel {
	public $name = "Payment";
	
	public $validate = array(
	
	    'amount' =>
        array(
            'rule1' =>
            array(
				  'rule' => 'notEmpty',
                'message' => 'Please enter amount.'
                
            ),
            array(
               'rule'    => 'numeric',
				'message' => 'Please enter amount in numbers.'
            ),
        ),
        
        );	
    
}
?>
