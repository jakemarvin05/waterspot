<?php
Class SubscriberController extends AppController{
	public $uses = array('Subscriber');

	public function subscribe()
	{
		$this->layout = '';
		$email = $_POST['email'];
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		 	$this->set('result', 'Invalid Email, please provide proper email');
			return;
		}
		$found = $this->Subscriber->find('first', ['conditions' => ['email' => $email]]);
		if ($found) {
			$this->set('result', 'The email is already Subscribed!');
			return;
		}
		$this->Subscriber->create();
		$this->Subscriber->save(['Subscriber' => ['email' => $email, 'subscribe_date' => date('Y-m-d H:i:s')]]);
		$this->set('result', 'Thank you for subscribing, You will hear from us very soon!');
	}

}
?>