<?php 
Class BookingsController extends MemberManagerAppController{
		public $uses = array('Booking','Cart');
		public $components = array('Email');
	    public $paginate = array();
	    public $ajax_session_name= "Ajax_Files";
        public $id = null;
	
	
	
	function bookingNotification() {
		$member_id=$this->MemberAuth->id();
		if(empty($member_id)) {
				$this->redirect($this->MemberAuth->loginRedirect);
		}
		$this->loadModel('Cart');
		$this->loadModel('MailManager.Mail');		
		
		$curr_date = date('Y-m-d h:m:s', time());
		$criteria = array();
		$criteria['conditions']= array('Cart.member_id'=>$member_id,'Cart.end_date <' =>$curr_date, 'vendor_confirm' => array(1,3));		
		$criteria['fields']=array('Cart.*');		
		$cartData=$this->Cart->find('all',$criteria);
		
		$mail=$this->Mail->read(null,29);		
		$this->loadModel('MemberManager.Member');
		
		$detail_row='';
		if(!empty($cartData)){
			foreach($cartData as $data){				
				$detail_row .= '<tr>
					<td>'.$data['Cart']['vendor_name'].'</td>
					<td>'.$data['Cart']['vendor_phone'].'</td>
					<td>'.$data["Cart"]["service_title"].'</td>
					<td>'.$data["Cart"]["service_title"].'</td>
					<td>'.date('Y-m-d',strtotime($data["Cart"]["booking_date"])).'</td>
					<td>'.date('Y-m-d',strtotime($data["Cart"]["start_date"])).'</td>
					<td>'.date('Y-m-d',strtotime($data["Cart"]["end_date"])).'</td>
					<td style="text-align: center;">'.$data["Cart"]["no_participants"].'</td>					
					<td>'.$data["Cart"]["total_amount"].'</td>
					</tr>';
			}
			$BookingDetailTxt = '<table border="0" cellpadding="0" cellspacing="0" height="71" style="padding: 0px; margin: 0px 0px 15px; border: 0px none; border-collapse: collapse; background: none repeat scroll 0% 0% rgb(252, 252, 252); color: rgb(120, 120, 120); font-family: &quot;Arial&quot;,Helvetica,sans-serif; font-size: 13px; font-weight: normal;" width="521">
			<tbody>
				<tr>
					<th style="background:#CECECE;border:solid 1px #E2E2E2;color:#212121;padding:3px 5px;text-align:left;font-weight:500;" width="12%">{USER-COLUMN}</th>
					<th style="background:#CECECE;border:solid 1px #E2E2E2;color:#212121;padding:3px 5px;text-align:left;font-weight:500;" width="16%">Phone</th>
					<th style="background:#CECECE;border:solid 1px #E2E2E2;color:#212121;padding:3px 5px;text-align:left;font-weight:500;" width="16%">Service</th>
					<th style="background:#CECECE;border:solid 1px #E2E2E2;color:#212121;padding:3px 5px;text-align:left;font-weight:500;" width="10%">Booked Date</th>
					<th style="background:#CECECE;border:solid 1px #E2E2E2;color:#212121;padding:3px 5px;text-align:left;font-weight:500;" width="10%">Start Date</th>
					<th style="background:#CECECE;border:solid 1px #E2E2E2;color:#212121;padding:3px 5px;text-align:left;font-weight:500;" width="10%">End Date</th>
					<th style="background:#CECECE;border:solid 1px #E2E2E2;color:#212121;padding:3px 5px;text-align:left;font-weight:500;" width="15%">Participant(s)</th>
					<th style="background:#CECECE;border:solid 1px #E2E2E2;color:#212121;padding:3px 5px;text-align:left;font-weight:500;" width="10%">Total Amount($)</th>
				</tr>{DETAILROW}
			</tbody>
			</table>';
			
			//Notify mail Variables for Vendor
			$DETAILS_vendor=str_replace('{DETAILROW}',$detail_row,$BookingDetailTxt);
			$DETAILS_vendor=str_replace('{USER-COLUMN}','Vendor Name',$DETAILS_vendor);
			//pr($this->MemberAuth);exit;
			$body=str_replace('{RECEIVER-NAME}',$this->MemberAut->results['MemberAuth']['first_name'].' '.$this->MemberAut->results['MemberAuth']['last_name'],$mail['Mail']['mail_body']);
			$body=str_replace('{BOOKINDETAILS}',$DETAILS_vendor,$body);
			
			//Notify mail for Vendor	
			$email = new CakeEmail();
$email->config('gmail');
			$email->to($this->MemberAut->results['MemberAuth']['email_id']);
			//$email->to($this->VendorAuth->results['VendorAuth']['email']);
			$email->subject($mail['Mail']['mail_subject']);
			$email->from($this->setting['site']['site_contact_email']);
	
			$email->emailFormat('html');
			$email->template('default');
			$email->viewVars(array('data'=>$body,'logo'=>$this->setting['site']['logo'],'url'=>$this->setting['site']['site_url']));
			$email->send();
			
			//Notify mail Variables for Members			
			foreach($cartData as $data1){				
				$memberinfo=$this->Member->read(null,$data1['Cart']['member_id']);				
				$detail_rows ='';
				$detail_rows .= '<tr>
					<td>'.$memberinfo["Member"]["first_name"].' '.$memberinfo["Member"]["last_name"].'</td>
					<td>'.$memberinfo["Member"]["phone"].'</td>
					<td>'.$data1["Cart"]["service_title"].'</td>
					<td>'.date('Y-m-d',strtotime($data1["Cart"]["booking_date"])).'</td>
					<td>'.date('Y-m-d',strtotime($data1["Cart"]["start_date"])).'</td>
					<td>'.date('Y-m-d',strtotime($data1["Cart"]["end_date"])).'</td>
					<td style="text-align: center;">'.$data1["Cart"]["no_participants"].'</td>					
					<td>'.$data1["Cart"]["total_amount"].'</td>
					</tr>';
				$DETAILS=str_replace('{DETAILROW}',$detail_rows,$BookingDetailTxt);
				$DETAILS=str_replace('{USER-COLUMN}','Member Name',$DETAILS);
				$bodyMember=str_replace('{RECEIVER-NAME}',$data1['Cart']['vendor_name'],$mail['Mail']['mail_body']);
				$bodyMember=str_replace('{BOOKINDETAILS}',$DETAILS,$bodyMember);
				
				//Notify mail for Members	
				$email = new CakeEmail();
$email->config('gmail');
				$email->to($data['Cart']['vendor_email']);
				$email->to($memberinfo["Member"]["email_id"]);
				$email->subject($mail['Mail']['mail_subject']);
				$email->from($this->setting['site']['site_contact_email']);
		
				$email->emailFormat('html');
				$email->template('default');
				$email->viewVars(array('data'=>$body,'logo'=>$this->setting['site']['logo'],'url'=>$this->setting['site']['site_url']));
				$email->send();
				
				$this->Cart->delete($data['Cart']['id']);
			}			
			
			//return;
		}
	}
	
	function booking_status(){
		//die('sdfsd');
            array_push(self::$css_for_layout,'member/member-panel.css');
		$this->bookingNotification();
		$this->loadModel('Cart');
		$criteria = array();
		$this->paginate = array();
		
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/'),
			'name'=>'Home'
		    );
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/member/dashboard/'),
			'name'=>'Dashboard'
		);
		$this->breadcrumbs[] = array(
                    'url'=>Router::url('/member/booking_status'),
                    'name'=>'Booking Requests'
			);
		$member_id=$this->MemberAuth->id();
		//$query = "DELETE from carts WHERE member_id='".$member_id."' AND ";
		$conditions = array(
				'conditions' => array(
				    'Cart.member_id' => $member_id,
				    'Cart.end_date <' => date('Y-m-d h:m:s'),
				    'Cart.status' => 0,
				    'Cart.vendor_confirm' => array(3,1),
					
				    
				)
		);
		//pr($conditions);
		$delete_cart_data = $this->Cart->find('all',$conditions);
		//pr($delete_cart_data); die('ds');
		$this->paginate = array(
				'conditions' => array('Cart.member_id'=>$member_id,'vendor_confirm'=>array(1,3),'status'=>0),			
				'fields'=>array('Cart.*'),
				'limit'=>20,
				'order'=>array('Cart.id'=>'DESC')
		);
		$data=$this->paginate("Cart");		
		$this->set('booking_details',$data);
		
	}
	
	function booking_list($search=null,$searchtext=null,$search_by_date=null,$searchbydate=null) {
                        array_push(self::$css_for_layout,'member/member-panel.css');

		$this->loadModel('BookingSlot');
		$conditions=null;
		$this->paginate = array();
		$member_id = $this->MemberAuth->id;
		if($this->request->is('post')){
			$search=(!empty($this->request->data['Booking']['search']))?$this->request->data['Booking']['search']:'_blank';
			$searchtext=(!empty($this->request->data['Booking']['searchtext']))?$this->request->data['Booking']['searchtext']:'_blank';
			$search_by_date=(!empty($this->request->data['Booking']['search_by_date']))?$this->request->data['Booking']['search_by_date']:'_blank';
			$searchbydate=(!empty($this->request->data['Booking']['search_by_date']))?$this->request->data['Booking']['searchbydate']:'_blank';
			$this->redirect(array('plugin'=>'member_manager','controller'=>'bookings','action'=>'booking_list',$search,$searchtext,$search_by_date,$searchbydate));
        }else{
			// conditions
			if(($search!=null && $search!='_blank' && $searchtext!=null && $searchtext!='_blank')) {
		        $searchtext = urldecode($searchtext);
                if($search=='fname' || $search=='lname') {
					$conditions['Booking.'.$search.' like'] = $searchtext.'%';
				}else {
					$conditions['Booking.'.$search] = $searchtext;
				}
				//assign in variable
				$this->request->data['Booking']['search']=$search;
				$this->request->data['Booking']['searchtext']=$searchtext;
			}
            if(($search_by_date!=null && $search_by_date!='_blank' && $searchbydate!=null && $searchbydate!='_blank')){
				$search = urldecode($search);
				if($search_by_date=='booking_date'){
					$conditions['CAST(Booking.booking_date as DATE)'] = date('Y-m-d',strtotime($searchbydate));
				}
				if($search_by_date=='start_date'){
					$conditions['BookingOrder.start_date'] = date('Y-m-d',strtotime($searchbydate));
				}
				//assign in variable
				$this->request->data['Booking']['search_by_date']=$search_by_date;
				$this->request->data['Booking']['searchbydate']=$searchbydate;
            }
		}
		$this->paginate = array('joins'=>
			array(
				array(
					'table'=>'booking_orders',
					'alias'=>'BookingOrder',
					'type'=>'LEFT',
					'conditions'=>array('BookingOrder.ref_no = Booking.ref_no')
				)
			),
			'conditions'=>array('Booking.member_id'=>$member_id,'Booking.status'=>array(0,1)),
			'group'=>'BookingOrder.ref_no',
		    'fields'=>array('Booking.*'),
			'limit'=>20,
			'order'=>array('Booking.ref_no'=>'DESC')
		);
		$booking_details=$this->paginate("Booking",$conditions);
		// javascript set
		array_push(self::$script_for_layout,'http://code.jquery.com/jquery-1.9.1.js','http://code.jquery.com/ui/1.10.3/jquery-ui.js');
		array_push(self::$css_for_layout,'http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css');
		self::$scriptBlocks[]='$(function() {
		$( "#BookingSearchbydate" ).datepicker({
		  dateFormat: "'.Configure::read('Calender_format').'",
		  changeMonth: true,
		  })
		});' ;
		$this->set('booking_details',$booking_details);
		if($this->request->is('ajax')){
                $this->layout = '';
                $this->Render('ajax_booking_list');
        }
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/'),
			'name'=>'Home'
		    );
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/members/dashboard/'),
			'name'=>'Dashboard'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/members/booking_list'),
			'name'=>'Booking List'
		);
	}
	
	function booking_details($ref_no=null){
		if(empty($ref_no)){
				 $this->redirect('/');
		} 
		$criteria = array();
		$this->loadModel('BookingOrder');
		$this->loadModel('BookingParticipate');
		$this->loadModel('LocationManager.City');
		$member_id=$this->MemberAuth->id;
		$criteria['joins'] = array(
				array(
					'table' => 'bookings',
					'alias' => 'Booking',
					'type' => 'INNER',
					'conditions' => array('Booking.ref_no = BookingOrder.ref_no')
				) 
			); 
		$criteria['conditions'][]=array('Booking.member_id'=>$member_id,'BookingOrder.ref_no'=>$ref_no);
		// get invite booking order details by ref_id
		$booking_order_ids=$this->BookingParticipate->find('list',array('conditions'=>array('BookingParticipate.status'=>1,'BookingParticipate.ref_no'=>$ref_no,'BookingParticipate.booking_member_id'=>$member_id)));
		if(!empty($booking_order_ids)){
			//$criteria['conditions'][]=array('BookingOrder.id'=>$booking_order_ids);
		}
		$criteria['group']=array('BookingOrder.id');
		$criteria['fields']=array('BookingOrder.*');
		$criteria['order']=array('BookingOrder.id ASC');
		$order_details=$this->BookingOrder->find('all',$criteria);
		$customer_detail=$this->Booking->find('first',array('conditions'=>array('Booking.ref_no'=>$ref_no, 'OR' =>array('Booking.member_id'=>$member_id))));
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/'),
			'name'=>'Home'
		    );
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/members/dashboard/'),
			'name'=>'Dashboard'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/members/booking_details/'.$ref_no),
			'name'=>'Booking Details'
		);
		foreach($order_details as $key=>$order_detail){
			$order_details[$key]['BookingOrder']['location_name']=(!empty($order_detail['BookingOrder']['location_id']))?$this->City->getLocationListCityID($order_detail['BookingOrder']['location_id']): "Location not available";
		}
		$this->set('customer_detail',$customer_detail);
		$this->set('order_details',$order_details);
	}

	function booking_vas_details($booking_order_id=null){
		$this->layout='';
		$this->loadModel('BookingOrder');
		$value_added_services=$this->BookingOrder->find('first',array('conditions'=>array('BookingOrder.id'=>$booking_order_id),'fields'=>array('BookingOrder.value_added_services')));
		$this->set('value_added_services',json_decode($value_added_services['BookingOrder']['value_added_services'],true));
		 
	}
	
	function booking_member_invite_details($booking_order_id=null){
		$this->layout='';
		$this->loadModel('BookingParticipate');
		$invites_friend_details=$this->BookingParticipate->find('all',array('conditions'=>array('BookingParticipate.booking_order_id'=>$booking_order_id)));
		$this->set('invites_friend_details',$invites_friend_details);
	}
	
	function booking_slot_details($booking_order_id=null){
		$this->layout='';
		$this->loadModel('BookingSlot');
		$booking_slots=$this->BookingSlot->find('all',array('conditions'=>array('BookingSlot.booking_order_id'=>$booking_order_id)));
		$this->set('booking_slots',$booking_slots);
	}
	
	function send_feedback($order_id=null){
		$this->loadModel('VendorManager.ServiceReview');
		$this->loadModel('VendorManager.Service');
		$this->loadModel('BookingOrder');
		$member_id = $this->MemberAuth->id;
		$booking_order=$this->BookingOrder->find('first',array('fields'=>array('BookingOrder.id','BookingOrder.ref_no','BookingOrder.service_id','BookingOrder.member_id'),'conditions'=>array('BookingOrder.id'=>$order_id)));
		$review_status=$this->ServiceReview->find('count',array('conditions'=>array('ServiceReview.member_id'=>$booking_order['BookingOrder']['member_id'],'ServiceReview.ref_no'=>$booking_order['BookingOrder']['ref_no'],'ServiceReview.service_id'=>$booking_order['BookingOrder']['service_id'])));
		if(empty($booking_order)){
			throw new NotFoundException('Could not find that booking');
		}
		array_push(self::$script_for_layout,array($this->setting['site']['jquery_plugin_url'].'ratings/jquery.js',$this->setting['site']['jquery_plugin_url'].'ratings/jquery.rating.js'));
		array_push(self::$css_for_layout,array($this->setting['site']['jquery_plugin_url'].'ratings/jquery.rating.css'));
		if(!empty($this->request->data) && self::validation()){
			$this->request->data['ServiceReview']['service_id']=$booking_order['BookingOrder']['service_id'];
			$this->request->data['ServiceReview']['ref_no']=$booking_order['BookingOrder']['ref_no'];
			$this->request->data['ServiceReview']['member_id']= $member_id;
			$this->request->data['ServiceReview']['vendor_id']= $this->Service->getVendor_idByService_id($booking_order['BookingOrder']['service_id']);
			$this->request->data['ServiceReview']['status']=0;
			$this->request->data['ServiceReview']['date']=date('Y-m-d H:i:s');
			$this->request->data['ServiceReview']['ip_address']=$_SERVER['REMOTE_ADDR'];
			$this->ServiceReview->create();
			$this->ServiceReview->save($this->request->data);
			if ($this->ServiceReview->id) {
				$this->Session->setFlash(__('Your feedback has been successfully sent'));
			} 
			else { 
				$this->Session->setFlash(__('Your feedback has not been successfully sent'));
			}
			$this->redirect(array('plugin'=>'member_manager','controller'=>'bookings','action'=>'send_feedback',$order_id));
		}
        $this->breadcrumbs[] = array(
			'url'=>Router::url('/'),
			'name'=>'Home'
		    );
		$this->breadcrumbs[] = array(
			'url'=>Router::url(array('controller'=>'members','action'=>'dashboard')),
			'name'=>'Dashboard'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url(array('controller'=>'bookings','action'=>'booking_details',$booking_order['BookingOrder']['ref_no'])),
			'name'=>'Booking Details'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url(array('controller'=>'bookings','action'=>'send_feedback',$order_id)),
			'name'=>'Feedback'
		);
		unset($booking_order['BookingOrder']['id']);
		$this->request->data['ServiceReview']=$booking_order['BookingOrder'];
        $this->set('order_id',$order_id);  
        $this->set('review_status',$review_status);  
	}

	function validation(){
		$this->autoRender = false;
		$this->loadModel('VendorManager.ServiceReview');
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
}
?>
