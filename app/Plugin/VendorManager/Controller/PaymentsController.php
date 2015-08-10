<?php  
App::uses('VendorManagerAppController','VendorManager.Controller'); 
App::uses('PaymentManagerAppController','PaymentManager.Controller'); 
class PaymentsController extends VendorManagerAppController{
	public $uses = array('VendorManager.Payment','VendorManager.Vendor');
	public $components=array('Email','VendorManager.VendorAuth','Session','SmoovPay');

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
	
	function payment_process_bk() { 
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
	/* Use SmoovPay for payment process */
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
		$vendor_email = $vendorinfo['Vendor']['email'];
		if(Configure::read('Payment.sandbox_mode')==1){
			$url = Configure::read('Payment.test_url');
		}
		else{
			$url = Configure::read('Payment.live_url');
		}
		$secret_key = Configure::read('Payment.secret_key');
		$merchant =Configure::read('Payment.merchant'); 
		$action = 'pay';
		$total_amount = $payment_data['amount'];
		$currency ="SGD"; 
		$ref_id = $vendor_id.time();
		$dataToBeHashed = $secret_key. $merchant. $action. $ref_id. $total_amount. $currency;
		$get_signature = $this->SmoovPay->encryption($dataToBeHashed);
		$html = '';
		if(!empty($payment_data['amount'])){
			
			/* Save payment detail */
			$payment_id = self::_save_payment_detail($vendor_id,$total_amount,$ref_id);
			
			$payment_data['successUrl']=$siteurl.Router::url(array('plugin'=>'vendor_manager','controller'=>'payments','action'=>'return_payment/'.$payment_id));
			$payment_data['failUrl']=$siteurl.Router::url(array('plugin'=>'vendor_manager','controller'=>'payments','action'=>'failled_url'));
			$payment_data['cancelUrl']=$siteurl.Router::url(array('plugin'=>'vendor_manager','controller'=>'payments','action'=>'paynow'));
			$payment_data['strUrl']=$siteurl.Router::url(array('plugin'=>'vendor_manager','controller'=>'payments','action'=>'process_ipn'));
			$html .= "<form name='payFormCcard' id='payFormCcard' method='post' action='$url'>";
			$html .= "<input type='hidden' name='version' value='2.0' />";
			$html .= "<input type='hidden' name='action' value='$action' />";
			$html .= "<input type='hidden' name='merchant' value='$merchant' />";
			$html .= "<input type='hidden' name='ref_id' value='$ref_id' />";
			$html .= "<input type='hidden' name='currency' value='SGD' />";
			$html .= "<input type='hidden' name='total_amount' value='$payment_data[amount]'/>";
			$html .= "<input type='hidden' name='success_url' value='$payment_data[successUrl]' />";
			$html .= "<input type='hidden' name='cancel_url' value='$payment_data[cancelUrl]' />";
			$html .= "<input type='hidden' name='str_url' value='$payment_data[strUrl]' />";
			$html .= "<input type='hidden' name='signature' value='$get_signature' />";
			$html .= "<input type='hidden' name='signature_algorithm' value='sha1' />";
			$html .="</form>";
		}
		$this->set('html',$html);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/'),
			'name'=>'Home'
		);
		$this->breadcrumbs[] = array(
			'url'=>Router::url('/'),
			'name'=>'Vendor Payment'
		);
	}
	
	private function _save_payment_detail($venderid=null,$amount=null,$payment_ref=null){
		$paymentData = array();
		$vendorinfo = $this->Vendor->find('first',array('conditions'=>array('Vendor.id'=>$venderid)));
		$paymentinfo = $this->Payment->find('first',array('conditions'=>array('Payment.vendor_id'=>$venderid)));
		if(!empty($paymentinfo)){
			$paymentData['Payment']['id'] = $paymentinfo['Payment']['id'];
		}
		$paymentData['Payment']['vendor_id'] = $venderid;
		$paymentData['Payment']['payment_mode'] = 'Smoov Pay';
		$paymentData['Payment']['payment_ref'] = $payment_ref;
		$paymentData['Payment']['total_amount'] = $amount;
		$paymentData['Payment']['payment_amount'] = $amount;
		$paymentData['Payment']['email'] = $vendorinfo['Vendor']['email'];
		$paymentData['Payment']['ip_address']= $_SERVER['REMOTE_ADDR'];
		$paymentData['Payment']['payment_date']= date('Y-m-d H:i:s');
		$this->Payment->create();
		$this->Payment->save($paymentData,array('validate'=>false));
		$id = $this->Payment->id;
		return $id;
	}

	function process_ipn(){
		if ($_SERVER["REQUEST_METHOD"]=="POST") {
			$flag = $this->SmoovPay->validateIpn($_POST);
			if($flag==1){
				$paymentData = array();
				$vendorData = array();
				$paymentinfo = $this->Payment->find('first',array('conditions'=>array('Payment.payment_ref'=>$_POST['ref_id'])));
				$paymentData['Payment']['id'] = $paymentinfo['Payment']['id'];
				$paymentData['Payment']['status'] = 1;
				$paymentData['Payment']['transaction_id'] = $_POST['reference_code'];
				$this->Payment->create();
				$this->Payment->save($paymentData,array('validate'=>false));
				$vendorData = array();
				$vendorData['Vendor']['id'] = $paymentinfo['Payment']['vendor_id'];
				$vendorData['Vendor']['payment_status']=1;
				$vendorData['Vendor']['payment_date']= date('Y-m-d H:i:s');
				$vendorData['Vendor']['updated_at']= date('Y-m-d H:i:s');
				$this->Vendor->create();
				$this->Vendor->save($vendorData,array('validate'=>false));
				self::_send_payment_mail($paymentinfo['Payment']['vendor_id'],$paymentinfo['Payment']['payment_amount']);
			}
			else{
				$paymentData = array();
				$paymentData['Payment']['id'] = $paymentinfo['Payment']['id'];
				$paymentData['Payment']['status'] = 2;
				$paymentData['Payment']['transaction_id'] = $_POST['signature'];
				$this->Payment->create();
				$this->Payment->save($paymentData,array('validate'=>false));
			}
		}
	}
	
	private function _send_payment_mail($vendor_id=null,$amount=null){
		$this->loadModel('MailManager.Mail');
		$this->loadModel('User');
		$this->loadModel('VendorManager.Vendor');
		$vendorinfo=$this->Vendor->read(null,$vendor_id);
		$mail=$this->Mail->read(null,9);

		$body=str_replace('{NAME}',$vendorinfo['Vendor']['fname'].' '.$vendorinfo['Vendor']['lname'],$mail['Mail']['mail_body']);
		$body=str_replace('{EMAIL}',$vendorinfo['Vendor']['email'],$body);
		$body=str_replace('{AMOUNT}',$amount,$body);
		///******Mail to Admin******/
		
		$email = new CakeEmail();
$email->config('gmail');
		//$email->to('pavans@bugeonsoft.net');
		$email->to($this->setting['site']['site_contact_email']);
		$email->subject($mail['Mail']['mail_subject']);
		$email->from($vendorinfo['Vendor']['email']);

		$email->emailFormat('html');
		$email->template('default');
		$email->viewVars(array('data'=>$body,'logo'=>$this->setting['site']['logo'],'url'=>$this->setting['site']['site_url']));
		$email->send();

		///******Mail to User******/ 
		$mail=$this->Mail->read(null,8);
		$body=str_replace('{NAME}',$vendorinfo['Vendor']['fname'].' '.$vendorinfo['Vendor']['lname'],$mail['Mail']['mail_body']);
		$body=str_replace('{AMOUNT}','$'.$amount,$body);
		
		$email = new CakeEmail();
$email->config('gmail');

		$email->to($vendorinfo['Vendor']['email']);
		$email->subject($mail['Mail']['mail_subject']);
		$email->from($this->setting['site']['site_contact_email']);
		$email->emailFormat('html');
		$email->template('default');
		$email->viewVars(array('data'=>$body,'logo'=>$this->setting['site']['logo'],'url'=>$this->setting['site']['site_url']));
		$email->send();
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
	
	function return_payment($paymentid=null) {
		// load model 

		$this->loadModel('ServiceManager.Payment');
		//$payment_ref=$this->params->query['Ref'];
		if(empty($paymentid)) {
			throw new NotFoundException('Could not find that payment reference');
		}
		$payment_detail=$this->Payment->find('first',array('conditions'=>array('Payment.id'=>$paymentid)));
		
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
