<?php
Class SubscriberController extends AppController{
	public $uses = array('Subscriber');

	public function subscribe() {
		$this->layout = '';
		$email = $_POST['email'];
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