<?php
Class Country extends AppModel {
	public $name = "Country";
	
	  public $validate = array(
	
	'name' =>
        array(
            'rule1' =>
            array(
			'rule' => array('maxLength',100),
            'message' => 'Name should be less than 100 character(s).'
			 ),
             array(
             'rule' => 'notEmpty',
             'message' => 'Please enter country name.'
		     ),
            array(
            'rule' => '/^[A-Za-z ]*$/',
            'message' => 'Please enter country name in alphabet.'
            ),
            array(
			'rule'=>array('isUnique'),
			'message'=>'This country already has been added.',
						 
			)
         ),
         
	 'alpha_2' =>
        array(
            'rule1' =>
            array(
			'rule' => array('maxLength',100),
            'message' => 'Country code should be maximum 10 character long.'
			 ),
             array(
             'rule' => 'notEmpty',
             'message' => 'Please enter country code.'
		     ),
            array(
            'rule' => '/^[A-Za-z ]*$/',
            'message' => 'Please enter country code in alphabet.'
            ),
			array(
			'rule'=>array('isUnique'),
			'message'=>'This country code already has been added.',
						 
			)
            
         ),
          
    );
    
   
}
?>
