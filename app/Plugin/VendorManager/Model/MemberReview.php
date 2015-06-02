<?php
Class MemberReview extends AppModel{
	public $name = "MemberReview";
	
		function reviewListVendorById($vendor_id=null) {	
		$criteria=array();
		$criteria['fields'] = array('MemberReview.*','Member.first_name','Member.last_name');
		$criteria['joins']=array(
								array(
										'table'=>'members',
										'alias' => 'Member',
										'type' => 'left',
										/*'foreignKey' => false,*/
										'conditions'=> array('Member.id=MemberReview.member_id')
									),
								);
		
		$criteria['conditions']['AND']['MemberReview.status = '] = 1;	
		$criteria['conditions']['AND']['MemberReview.vendor_id = '] = $vendor_id;
		$criteria['group'] = array('MemberReview.id');
		$review_list=$this->find('all',$criteria);
		//echo"<pre>";print_r($review_list);die;
		return $review_list;
		}
}
?>
