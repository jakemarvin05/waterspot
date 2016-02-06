<?php
Class ServiceReview extends VendorManagerAppModel {
	public $name = "service_reviews";
	public $validate = array(
				'message' => array(
					'checkreviws'=>array(
						'rule'=>array('checkreviws'),
						'message'=>'you have been already review this activities.',
						 
					),
					'loginRule-1' => array(
						'rule' => 'notEmpty',
						'message' => 'Please enter feedback message.',
						'last' => true
					 ),
					'loginRule-2' => array(
						'rule' => array('minLength', 8),
						'message' => 'Feedback message should be minimum length of 8 characters'
					),
					'checkRating'=>array(
						'rule'=>array('checkRating'),
						'message'=>'Please select rating.',
						 
					)
					
				)
			);
	
	
		function getServiceReviewByservice_id($service_id=null){
			$critria = array();
			$service_reviews = array();
			$critria['fields'] = array('ServiceReview.*','Member.first_name','Member.last_name');
			$critria['joins']=array(
								array(
										'table'=>'members',
										'alias' => 'Member',
										'type' => 'left',
										//'foreignKey' => false,
										'conditions'=> array('Member.id=ServiceReview.member_id'),
									),
								);
			$critria['conditions'] = array('ServiceReview.service_id' => $service_id,'ServiceReview.status' => 1);
			$critria['order'] = array('ServiceReview.id' => 'DESC');
			$critria['Limit'] = 500;
			$service_reviews = $this->find('all', $critria);
			return $service_reviews;
			
		}

		function getServiceRatings($service_id=null)
		{
			$critria = array();
			$service_reviews = array();
			$critria['fields'] =  array('ROUND(AVG(ifnull((`ServiceReview`.`rating`), 0))) as rating');
			$critria['conditions'] = array('ServiceReview.service_id' => $service_id,'ServiceReview.status' => 1);
			$critria['group'] = array('ServiceReview.service_id');
			$service_reviews = $this->find('all', $critria);
			return $service_reviews?$service_reviews[0][0]['rating']:null;
		}
		
		function getVendorRatings($vendor_id = null){
			$critria = array();
			$service_reviews = array();
			$critria['fields'] = array('AVG(ifnull((`ServiceReview`.`rating`), 0)) as rating');
			$critria['joins']=array(
								array(
										'table'=>'services',
										'alias' => 'Service',
										'type' => 'right',
										//'foreignKey' => false,
										'conditions'=> array('Service.id=ServiceReview.service_id','Service.status = 1'),
									),
								);
			$critria['conditions'] = array('Service.vendor_id'=>$vendor_id,'ServiceReview.status'=>1);
			$critria['group'] = array('Service.vendor_id');
			$result = $this->find('first', $critria);
			return (!empty($result))?round($result[0]['rating']):0;
		}
		function checkreviws(){
			$review_status=$this->find('count',array('conditions'=>array('ServiceReview.member_id'=>$this->data['ServiceReview']['member_id'],'ServiceReview.ref_no'=>$this->data['ServiceReview']['ref_no'],'ServiceReview.service_id'=>$this->data['ServiceReview']['service_id'])));	
			if($review_status==0) { 
				return true;
			}
			else {
				return false;
			}
			
		}
		function checkRating(){
			if(!empty($this->data['ServiceReview']['rating'])) { 
				return true;
			}
			else {
				return false;
			}
			
		}
	}
?>
