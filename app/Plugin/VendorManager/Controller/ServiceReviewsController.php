<?php
class ServiceReviewsController extends VendorManagerAppController {
	public $uses = array('VendorManager.ServiceReview');
	public $paginate = array();
	public $id = null;

	function admin_index($vendor_id=null,$service_id=null,$search=null){
		$service_reviews=array();
		$condition = null;
		if($this->request->is('post')){
			$this->redirect(array('plugin'=>'vendor_manager','controller'=>'service_reviews','action'=>'index',$vendor_id,$service_id,$this->request->data['search']));
		}
		$this->paginate=array('joins'=>
				array(
					array(
						'alias' => 'Member',
						'table' => 'members',
						'type' => 'INNER',
						'conditions' => array('Member.id=ServiceReview.member_id')
					),
					array(
						'alias' => 'Vendor',
						'table' => 'vendors',
						'type' => 'INNER',
						'conditions' => array('Vendor.id = ServiceReview.vendor_id')
					),
					array(
						'alias' => 'Service',
						'table' => 'services',
						'type' => 'INNER',
						'conditions' => array('Service.id = ServiceReview.service_id')
					)
		
				),
			); 
		$this->paginate['fields']=array('ServiceReview.*','Member.first_name','Member.last_name','Vendor.fname','Vendor.lname','Service.service_title');	
		$this->paginate['order']=array('ServiceReview.id'=>'DESC');
		$this->paginate['limit']=20;		
		if($search!=null && $search!='_blank'){
			$search = urldecode($search);
			$condition['ServiceReview.message like'] = '%'.urldecode($search).'%';
		}else{
			$search='';
		}
		if($service_id!=null ){
			$service_id = urldecode($service_id);
			$condition['ServiceReview.service_id'] = $service_id;
		}
		if($vendor_id!=null ){
			$vendor_id = urldecode($vendor_id);
			$condition['ServiceReview.vendor_id'] = $vendor_id;
		}
		$service_reviews=$this->paginate("ServiceReview", $condition);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/home'),
			'name'=>'Home'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/vendor_manager/vendors/index'),
			'name'=>'Manage Vendor'
		);
		$this->breadcrumbs[] = array(
				'url'=>Router::url('/admin/vendor_manager/services/servicelist/'.$vendor_id),
				'name'=>'Manage Service list'
		);
		$this->breadcrumbs[] = array(
				'url'=>Router::url('/admin/vendor_manager/service_reviews/'.$vendor_id.'/'.$service_id),
				'name'=>'Manage Services Review'
		);
		 
		$this->set('service_reviews',$service_reviews);
		$this->set('search',$search);
		$this->set('vendor_id',$vendor_id);
		$this->set('service_id',$service_id);
		$this->set('url','/'.$this->params->url);
		if($this->request->is('ajax')){
			$this->layout = '';
			$this -> Render('ajax_admin_index');
		}
	}
	  
	function admin_add($vendor_id=null,$service_id=null,$review_id=null){ 
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/home'),
			'name'=>'Home'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/vendor_manager/vendors/index'),
			'name'=>'Manage Vendor'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/vendor_manager/service_reviews/'.$vendor_id.'/'.$service_id),
			'name'=>'Manage Services Review'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/vendor_manager/service_reviews/add'),
			'name'=>($review_id==null)?'Add Services Review':'Update Services Review'
		);
		
		if(!empty($this->request->data) && self::validation()){
			//echo $this->validation(); die;
			$this->ServiceReview->create();
			$this->ServiceReview->save($this->request->data);
			if ($this->request->data['ServiceReview']['id']) {
				$this->Session->setFlash(__('Service review has been updated successfully'));
				} 
				else {
					$this->Session->setFlash(__('Service review has been added successfully'));
				}
			$this->redirect($this->request->data['ServiceReview']['redirect']);
		}
		else{
			if($review_id!=null){
				$criteria=array();
				$criteria['joins']=array(
					array(
						'alias' => 'Member',
						'table' => 'members',
						'type' => 'INNER',
						'conditions' => array('Member.id=ServiceReview.member_id')
					),
					array(
						'alias' => 'Vendor',
						'table' => 'vendors',
						'type' => 'INNER',
						'conditions' => array('Vendor.id = ServiceReview.vendor_id')
					),
					array(
						'alias' => 'Service',
						'table' => 'services',
						'type' => 'INNER',
						'conditions' => array('Service.id = ServiceReview.service_id')
					)
				);
				$criteria['fields']=array('ServiceReview.*','Member.first_name','Member.last_name','Vendor.fname','Vendor.lname','Service.service_title');	
				$criteria['conditions'] = array('ServiceReview.id'=>$review_id);
				$this->request->data = $this->ServiceReview->find('first', $criteria);
			}else{
				$this->request->data = array();
			}
		}
		$redirect_url=(Controller::referer()=="/")? Router::url(array('controller'=>'service_reviews','action'=>'index',$this->request->data['ServiceReview']['vendor_id'],$this->request->data['ServiceReview']['service_id'])) :Controller::referer();
		 $this->set('url',$redirect_url);
		$this->set('vendor_id',$vendor_id);
		$this->set('service_id',$service_id);
	}
	
	function admin_delete($review_id=null){
            $this->autoRender = false;
		    $data=$this->request->data['ServiceReview']['id'];
		    $action = $this->request->data['ServiceReview']['action'];
            $ans="0";
            foreach($data as $value){
				if($value!='0'){
                    if($action=='Approve'){
                        $review['ServiceReview']['id'] = $value;
                        $review['ServiceReview']['status']=1;
                        $this->ServiceReview->create();
                        $this->ServiceReview->save($review);
                        $ans="1";
                    }
                    if($action=='Disapprove'){
                        $review['ServiceReview']['id'] = $value;
                        $review['ServiceReview']['status']=0;
                         
                        $this->ServiceReview->create();
                        $this->ServiceReview->save($review);
                        $ans="1";
                    }
                    if($action=='Delete'){
                        $this->ServiceReview->delete($value);
                        $ans="2";
                    }
                }
			}
		if($ans=="1"){
			$this->Session->setFlash(__('Service review has been '.$this->data['ServiceReview']['action'].'d successfully', true));
		}
		else if($ans=="2"){
			$this->Session->setFlash(__('Service review has been '.$this->data['ServiceReview']['action'].'d successfully', true));
		}else{
			$this->Session->setFlash(__('Please select any service review', true),'default','','error');
		}
		$this->redirect($this->request->data['ServiceReview']['redirect']);
	}
	
	function validation(){
		$this->autoRender = false;
		$this->ServiceReview->set($this->request->data);
		$result = array();
		if ($this->ServiceReview->validates()) {
			$result['error'] = 0;
		}else{
			  $result['error'] = 1;
		}
		$result['errors'] = $this->ServiceReview->validationErrors;
		$errors = array();
		foreach($result['errors'] as $field => $data){
			$errors['ServiceReview'.Inflector::camelize($field)] = array_pop($data);
		}
		$result['errors'] = $errors;
		if($this->request->is('ajax')) {
			echo json_encode($result);
			return;
		}
		 return (int)($result['error'])?0:1;
	}

	function admin_view($review_id = null) {
		$this->layout = '';
		$criteria=array();
		$criteria['joins']=array(
					array(
							'alias' => 'Member',
							'table' => 'members',
							'type' => 'INNER',
							'conditions' => array('Member.id=ServiceReview.member_id')
						),
						array(
							'alias' => 'Vendor',
							'table' => 'vendors',
							'type' => 'INNER',
							'conditions' => array('Vendor.id = ServiceReview.vendor_id')
						),
						array(
							'alias' => 'Service',
							'table' => 'services',
							'type' => 'INNER',
							'conditions' => array('Service.id = ServiceReview.service_id')
						)
					);
		$criteria['fields']=array('ServiceReview.*','Member.first_name','Member.last_name','Vendor.fname','Vendor.lname','Service.service_title');	
		$criteria['conditions'] = array('ServiceReview.id'=>$review_id);
        $service_review =  $this->ServiceReview->find('first', $criteria);
        $this->set('service_review', $service_review);
    }
    function view($review_id = null) {
		$this->layout = '';
		$criteria=array();
		$criteria['joins']=array(
					array(
							'alias' => 'Member',
							'table' => 'members',
							'type' => 'INNER',
							'conditions' => array('Member.id=ServiceReview.member_id')
						),
						array(
							'alias' => 'Vendor',
							'table' => 'vendors',
							'type' => 'INNER',
							'conditions' => array('Vendor.id = ServiceReview.vendor_id')
						),
						array(
							'alias' => 'Service',
							'table' => 'services',
							'type' => 'INNER',
							'conditions' => array('Service.id = ServiceReview.service_id')
						)
					);
		$criteria['fields']=array('ServiceReview.*','Member.first_name','Member.last_name','Vendor.fname','Vendor.lname','Service.service_title');	
		$criteria['conditions'] = array('ServiceReview.id'=>$review_id);
        $service_review =  $this->ServiceReview->find('first', $criteria);
        $this->set('service_review', $service_review);
        $this->render('admin_view');
    }
    
    function reviews($service_id=null,$search=null){
		array_push(self::$css_for_layout,'vendor/vendor-panel.css');
		$vendor_id=$this->VendorAuth->id();
		$service_reviews=array();
		$condition = null;
		if(empty($service_id)){
			$service_id="service_id";
		}
		if(empty($search)) {
			$search="_blank";
		}
		if($this->request->is('post')){
			$this->redirect(array('plugin'=>'vendor_manager','controller'=>'service_reviews','action'=>'reviews',$service_id,$this->request->data['ServiceReview']['searchtext']));
		}
		$this->paginate=array('joins'=>
				array(
					array(
						'alias' => 'Member',
						'table' => 'members',
						'type' => 'INNER',
						'conditions' => array('Member.id=ServiceReview.member_id')
					),
					array(
						'alias' => 'Vendor',
						'table' => 'vendors',
						'type' => 'INNER',
						'conditions' => array('Vendor.id = ServiceReview.vendor_id')
					),
					array(
						'alias' => 'Service',
						'table' => 'services',
						'type' => 'INNER',
						'conditions' => array('Service.id = ServiceReview.service_id')
					)
		
				),
			); 
		$this->paginate['fields']=array('ServiceReview.*','Member.first_name','Member.last_name','Vendor.fname','Vendor.lname','Service.service_title');	
		$this->paginate['order']=array('ServiceReview.id'=>'DESC');
		$condition['ServiceReview.vendor_id'] = $vendor_id;
		$this->paginate['limit']=20;		
		
		if($search!=null && $search!='_blank'){
			$search = urldecode($search);
			$condition['ServiceReview.message like'] = '%'.urldecode($search).'%';
			$this->request->data['ServiceReview']['searchtext']=$search;
		}else{
			$search='_blank';
		}
		if($service_id!=null && $service_id!='service_id' ){
			$service_id = urldecode($service_id);
			$condition['ServiceReview.service_id'] = $service_id;
		}
		$service_reviews=$this->paginate("ServiceReview", $condition);
		array_push(self::$script_for_layout,'jquery.fancybox.js');
		array_push(self::$css_for_layout,'fancybox/jquery.fancybox(new).css');
		
		$this->breadcrumbs[] = array(
		'url'=>Router::url('/'),
		'name'=>'Home'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url(array('controller' => 'vendors', 'action' => 'dashboard',
			'plugin'=>'vendor_manager')),
			'name'=>'Dashboard'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url(array('controller' => 'service_reviews', 'action' => 'reviews','plugin'=>'vendor_manager')),
			'name'=>'Service Reviews'
		);
		$this->set('service_reviews',$service_reviews);
		$this->set('service_id',$service_id);
		$this->set('search',$search);
		$this->set('url','/'.$this->params->url);
		if($this->request->is('ajax')){
			$this->layout = '';
			$this -> Render('ajax_reviews');
		}
	}
	function admin_reviews($vendor_id=null,$service_id=null,$search=null){
		$service_reviews=array();
		$condition = null;
		if(empty($vendor_id)){
			$vendor_id='_blank';
		}
		if(empty($service_id)){
			$service_id='_blank';
		}
		if($this->request->is('post')){
			$this->redirect(array('plugin'=>'vendor_manager','controller'=>'service_reviews','action'=>'reviews',$vendor_id,$service_id,$this->request->data['search']));
		}
		$this->paginate=array('joins'=>
				array(
					array(
						'alias' => 'Member',
						'table' => 'members',
						'type' => 'INNER',
						'conditions' => array('Member.id=ServiceReview.member_id')
					),
					array(
						'alias' => 'Vendor',
						'table' => 'vendors',
						'type' => 'INNER',
						'conditions' => array('Vendor.id = ServiceReview.vendor_id')
					),
					array(
						'alias' => 'Service',
						'table' => 'services',
						'type' => 'INNER',
						'conditions' => array('Service.id = ServiceReview.service_id')
					)
				),
			); 
		$this->paginate['fields']=array('ServiceReview.*','Member.first_name','Member.last_name','Vendor.fname','Vendor.lname','Service.service_title');	
		$this->paginate['order']=array('ServiceReview.id'=>'DESC');
		$this->paginate['limit']=20;		
		if($search!=null && $search!='_blank'){
			$search = urldecode($search);
			$condition['ServiceReview.message like'] = '%'.urldecode($search).'%';
		}else{
			$search='';
		}
		if($service_id!=null && $service_id!='_blank'){
			$service_id = urldecode($service_id);
			$condition['ServiceReview.service_id'] = $service_id;
		}
		if($vendor_id!=null && $vendor_id!='_blank'){
			$vendor_id = urldecode($vendor_id);
			$condition['ServiceReview.vendor_id'] = $vendor_id;
		}
		$service_reviews=$this->paginate("ServiceReview", $condition);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/admin/home'),
			'name'=>'Home'
		);
		$this->breadcrumbs[] = array(
				'url'=>Router::url('/admin/vendor_manager/service_reviews/'.$vendor_id.'/'.$service_id),
				'name'=>'Manage Services Review'
		);
		$this->set('service_reviews',$service_reviews);
		$this->set('search',$search);
		$this->set('vendor_id',$vendor_id);
		$this->set('service_id',$service_id);
		$this->set('url','/'.$this->params->url);
		if($this->request->is('ajax')){
			$this->layout = '';
			$this -> Render('ajax_admin_reviews');
		}
	}
}
?>
