<?php
Class Activity extends AppModel{
	public $useTable = false; // This model does not use a database table
	public $validate = array(
			'start_date' =>
				array(
					 array(
						'rule' => 'notEmpty',
						'message' => 'Please select start date.'
					 ),
				 ),
		   'end_date' =>
				array(
					 array(
						'rule' => 'notEmpty',
						'message' => 'Please select end date.'
					 ),
					 array(
						'rule' => 'is_lessthan',
						'message' => 'Slots should be greater than start date or equal.'
					 ),
				 ), 
			'no_participants' =>
					array(
					'rule1' =>
						array(
							'rule' => 'notEmpty',
							'message' => 'Please enter no of participants.'
                
						),
						array(
							'rule'    => 'numeric',
							'message' => 'No of participants should be numeric.'
						),
						array(
							'rule' => array('range', 0, 21),
							'message' => 'No of participants range 1 to 20.'
						),
					),
			'slots' =>	array(
				array(
				'rule' =>array('multiple'),
				'message'=> 'Please select any one slot.'
				)
			),
			 
		);
		function is_lessthan(){
			$start_time= strtotime($this->data['Activity']['start_date']);
			$end_time=strtotime($this->data['Activity']['end_date']);
			if($start_time >$end_time) {
				return false;
			}
			return true;
		}
}
?>
