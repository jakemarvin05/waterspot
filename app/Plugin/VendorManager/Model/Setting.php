<?php
class Setting extends AppModel 
{
    public $name = "Setting";
    
    public $validate = array(
	'sales_commission_amount' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please enter amount.'
            )
        ),
    );
   
}
?>
