<?php
// app/Model/User.php
class Mail extends MailManagerAppModel {
  public $name = "Mail";
	
	public $validate = array(
	
	'heading' =>
        array(
            'rule1' =>
            array(
                'rule' => array('maxLength', 255),
                'message' => 'Heading should be less than 255 charcter(s)'
            ),
            array(
                'rule' => 'notEmpty',
                'message' => 'Please enter heading'
            ) 
        ),
	
	'mail_title' =>
        array(
            'rule1' =>
            array(
                'rule' => array('maxLength', 255),
                'message' => 'Mail Title name should be less than 255 charcter(s)'
            ),
            array(
                'rule' => 'notEmpty',
                'message' => 'Please enter Mail Title'
            ) 
        ),
        
        'mail_from' =>
        array(
            'rule1' =>
            array(
                'rule' => array('maxLength', 255),
                'message' => 'Mail From  should be less than 255 charcter(s)'
            ),
            array(
                'rule' => 'notEmpty',
                'message' => 'Please enter Mail From'
            ) 
        ),
          'mail_subject' =>
        array(
            'rule1' =>
				array(
				'rule' => array('maxLength',255),
				'message' => 'Mail Subject should be less than 255 character(s)'
				),
            array(
                'rule' => 'notEmpty',
                'message' => 'Please enter Mail Subject'
            ) 
        ),
        
          'mail_body' =>
        array(
            'rule1' =>
			
            array(
                'rule' => 'notEmpty',
                'message' => 'Please enter Mail Body'
            ) 
        ),
      
    );
   
}
?>
