<?php
Class Page extends ContentManagerAppModel {
	public $name = 'Page';
	public $actsAs = array('Multivalidatable');
	public $validate = array(
	'name' =>
        array(
            'rule1' =>
            array(
                'rule' => array('maxLength', 255),
                'message' => 'Page name should be less than 255 charcter(s).'
            ),
            array(
                'rule' => 'notEmpty',
                'message' => 'Please enter name.'
            ) 
        ),
        'page_title' =>
        array(
            'rule1' =>
            array(
                'rule' => array('maxLength', 255),
                'message' => 'Page title should be less than 255 charcter(s).'
            ),
            array(
                'rule' => 'notEmpty',
                'message' => 'Please enter title name.'
            ) 
        ),
          
       //'show_top_menu' =>
        //array(
            //array(
                //'rule' => 'notEmpty',
                //'message' => 'Please Select Show Type.'
            //)
        //),
			
		//'show_footer_menu' =>
        //array(
            //array(
                //'rule' => 'notEmpty',
                //'message' => 'Please Select Show Type.'
            //)
        //),
       	
         'url_key' =>array(
               array(
                        'rule' =>'notEmpty',
                        'message'=>'Please enter url key.'
                      ),
                
                array(
                'rule' =>'notEmpty',
                'message'=>'Please enter url key.'
                      ),
                      array(
                'rule' => array('maxLength', 125),
                'message' => 'name should be less than 125 character(s).'
                    ),
                    array(
                'rule'     => '/^[a-zA-Z.-]{1,}$/i',
				'message'  => 'Only alphabets and dash, dot allowed'
				
                ),
                 'isUnique'=>array(
					'rule'=>array('isUnique'),
					'message'=>'This page url already been taken.',
					 
				),
			)
          
    );
	var $validationSets = array(
		'ContactForm'=>array(
			'name' =>
				array(
					'rule1' =>
					array(
					'rule' => array('maxLength',100),
					'message' => 'Name should be less than 100 character(s).'
					 ),
					 array(
					 'rule' => 'notEmpty',
					 'message' => 'Please enter name.'
					 ),
					array(
					'rule' => '/^[A-Za-z ]*$/',
					'message' => 'Please enter name in alphabet.'
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
			  
			'message' =>
			array(
				'rule1' =>
				array(
					'rule' => array('maxLength', 1000),
					'message' => 'Message should be less than 1000 charcter(s).'
				),
				array(
					'rule' => 'notEmpty',
					'message' => 'Please enter your message.'
				) 
			),
		  )
	);
   
    public function afterSave($options = array()){
		self :: cacheName();
		parent::afterSave();
	}
	public function afterDelete($options = array()){
		self :: cacheName();
		parent::afterDelete();
	}
	
	private function cacheName (){
		Cache::delete('cake_page_top_menu');
		Cache::delete('cake_page_footer_menu');
		Cache::delete('cake_page_routing');
		Cache::clear(); 
	}
	
	function isValidUSPhoneFormat($phone){
		$phone=$this->data['Page']['phone'];
		$errors = array();
	    if(empty($phone)) {
	       $errors [] = "Please enter phone number";
	    }
	    else if (!preg_match('/^\+?[0-9 \( \) \-]+$/', $phone)) {
	        $errors [] = "Please enter valid phone number.";
   	    } 
        if (!empty($errors))
	    return false;//implode("\n", $errors);
	    return true;
    }
}
?>
