<?php  
App::uses('VendorManagerAppController','VendorManager.Controller'); 
App::uses('PaymentManagerAppController','PaymentManager.Controller'); 
class PaymentsController extends VendorManagerAppController{
	public $uses = array('VendorManager.Payment','VendorManager.Vendor');
	public $components=array('Email','VendorManager.VendorAuth','Session');

    function paynow(){	
		$id=$this->VendorAuth->id;
		$vendorinfo=$this->Vendor->find('first',array('conditions'=>array('Vendor.id'=>$id)));
		$this->set('vendorinfo',$vendorinfo);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/'),
			'name'=>'Home'
		    );
			$this->breadcrumbs[] = array(
				'url'=>Router::url(array('plugin'=>'vendor_manager','action'=>'payments','action'=>'paynow')),
				'name'=>'Pay Now'
			);	  
	} 
	
	function make_payment() {
		$id=$this->VendorAuth->id;
		$vendorinfo=$this->Vendor->find('first',array('conditions'=>array('Vendor.id'=>$id)));
		$this->loadModel('VendorManager.Setting');
		$amount=$this->Setting->find('first',array('conditions'=>array('Setting.id'=>24)));
		$this->set('vendor',$vendorinfo);
		if(!empty($vendorinfo['Vendor']['payment_amount'])){
			$vendor_amount=number_format($vendorinfo['Vendor']['payment_amount'],2);
		}
		else{
			$vendor_amount==number_format($amount['Setting']['values']);
		}
		$this->set('vendor_amount',$vendor_amount);
		$this->set('vendor_id',$id);
				
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/'),
			'name'=>'Home'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/vendor_manager/payments/make_payment'),
			'name'=>'Make Your Payment'
		);	  
	}
	
	function payment_process() { 
		$this->loadModel('VendorManager.Vendor');
		$vendor_id=$this->VendorAuth->id;
		$vendorinfo=array();
		$vendorinfo=$this->Vendor->find('first',array('conditions'=>array('Vendor.id'=>$vendor_id)));
		if(empty($vendorinfo)){
			throw new NotFoundException('Vendor detail is not found');
		}
		$siteurl=$this->setting['site']['site_url'];
		$payment_data=array();
		$payment_data['amount']=$vendorinfo['Vendor']['payment_amount'];
		$payment_data['orderRef']=time();
		$payment_data['successUrl']=$siteurl.Router::url(array('plugin'=>'vendor_manager','controller'=>'payments','action'=>'return_payment'));
		$payment_data['failUrl']=$siteurl.Router::url(array('plugin'=>'vendor_manager','controller'=>'payments','action'=>'failled_url'));
		$payment_data['cancelUrl']=$siteurl.Router::url(array('plugin'=>'vendor_manager','controller'=>'payments','action'=>'cancelled_url'));
		// 1 for simple user booking
		// 2 for user inviter booking
		$payment_data['remark']="&vendor_id=".$vendor_id."&amount=".$vendorinfo['Vendor']['payment_amount']."&type=3"; 
		self :: asiapay($payment_data);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/'),
			'name'=>'Home'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/'),
			'name'=>'Vendor Payment'
		);
	}
	
	function validation(){
		$this->Payment->set($this->request->data);
		$result = array();
		if ($this->Payment->validates()) {
		    $result['error'] = 0;
		}else{
			$result['error'] = 1;
		}
		if($this->request->is('ajax')) {
			$this->autoRender = false;
			$result['errors'] = $this->Payment->validationErrors;
			$errors = array();
			 
			foreach($result['errors'] as $field => $data){
			    $errors['Payment'.Inflector::camelize($field)] = array_pop($data);
			}
				$result['errors'] = $errors;
			echo json_encode($result);
			return;
		  }
		return $result['error']; 
    }

    private function asiapay($payment_data){
		App::import('Vendor', 'assiapay', array('file' => 'assiapay' . DS . 'SHAPaydollarSecure.php'));
		$assiapay = new SHAPaydollarSecure();
		$payment_data['merchantId']=Configure::read('AsiaPay.merchantId');
		$payment_data['orderRef']=$payment_data['orderRef'];
		//$payment_data['orderRef']=time();
		$payment_data['currCode']=Configure::read('AsiaPay.currCode');
		//“344” – HKD  “840” – USD “702” – SGD “156” – CNY (RMB) “392” – JPY “901” – TWD “036” – AUD “978” – EUR “826” – GBP “124” – CAD
		$payment_data['amount']=$payment_data['amount'];
		$payment_data['payType']='N';
		$payment_data['mpsMode']="NIL";
		$payment_data['payMethod']="ALL";
		$payment_data['remark']=$payment_data['remark'];
		$payment_data['lang']=Configure::read('AsiaPay.lang');
		$payment_data['successUrl']=$payment_data['successUrl'];
		$payment_data['failUrl']=$payment_data['failUrl'];
		$payment_data['cancelUrl']=$payment_data['cancelUrl'];
		//Optional Parameter for connect to our payment page
		//$payment_data['remark']="";  
		$payment_data['redirect']="";
		$payment_data['oriCountry']="";
		$payment_data['destCountry']=""; 
		$payment_data['secureHash']=$assiapay->generatePaymentSecureHash($payment_data['merchantId'], $payment_data['orderRef'], $payment_data['currCode'], $payment_data['amount'], $payment_data['payType'], Configure::read('AsiaPay.secureHashSecret'));
		$this->set('payment_action',Configure::read('AsiaPay.payment_action'));
		$this->set('payment_data',$payment_data);
	}
	
	function return_payment() {
		// load model 
		$this->loadModel('ServiceManager.Payment');
		// get payment ref 
		$payment_ref=$this->params->query['Ref'];
		if(empty($payment_ref)) {
			throw new NotFoundException('Could not find that payment reference');
		}
		$payment_detail=$this->Payment->find('first',array('conditions'=>array('Payment.payment_ref'=>$payment_ref),'order'=>array('Payment.id DESC')));
		
		if(empty($payment_detail)) {
			throw new NotFoundException('Could not find that payment details');
		}
		if($payment_detail['Payment']['status']=='1') {
			$this->Session->write('VendorAuth.VendorAuth.payment_status',1);
		}
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/'),
			'name'=>'Home'
		    );
		$this->breadcrumbs[] = array(
                    'url'=>Router::url(array('plugin'=>'vendor_manager','controller'=>'vendors','action'=>'dashboard')),
                    'name'=>'Dashboard'
			);
		$this->set('printrecord',$payment_detail);
	}
	
	function failled_url(){
		$this->loadModel('Booking');
		//for cancel page
		$this->redirect(array('plugin'=>'content_manager','controller'=>'pages','action'=>'view',20));
	}
	
	// this is used to after payment cancelled 
	function cancelled_url(){
		$this->loadModel('Booking');
		//for cancel page
		$this->redirect(array('plugin'=>'content_manager','controller'=>'pages','action'=>'view',17));
	}
}
?>
