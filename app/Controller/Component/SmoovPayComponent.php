<?php 
class SmoovPayComponent extends Component {

	public function enableTestMode() {
		$this->testMode = TRUE;
		$this->gatewayUrl = 'https://sandbox.smoovpay.com/redirecturl';
		$this->url_parsed=parse_url($this->gatewayUrl);
		
	}

	public function encryption($dataToBeHashed=null){
		$utfString = mb_convert_encoding($dataToBeHashed, "UTF-8");
		$signature = sha1($utfString, false);
		return $signature;
	}

	public function validateIpn($postData=array()){
		$secret_key = $secret_key = Configure::read('Payment.secret_key'); 
		$merchant = $postData['merchant'];
		$ref_id = $postData['ref_id']; 
		$reference_code = $postData['reference_code']; 
		$response_code = $postData['response_code']; 
		$currency = $postData['currency'];
		$total_amount = $postData['total_amount'];
		$signature = $postData['signature'];
		$signature_algorithm = $postData['signature_algorithm'];
		$dataToBeHashed = $secret_key
						.$merchant
						.$ref_id
						.$reference_code
						.$response_code
						.$currency
						.$total_amount;
		$utfString = mb_convert_encoding($dataToBeHashed, "UTF-8");
		$check_signature = sha1($utfString, false);
		if ($signature == $check_signature) {
			return 1;
		} else {
			return 0;
		}
	}
}
?>
