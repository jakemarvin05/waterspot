<?php
class TimeHelper extends AppHelper {
	var $helpers = Array('Html','Session');
    
   
	public function meridian_format($time){
		return DATE("g:i A", strtotime($time));
	}
	public function end_meridian_format($time){
		if($time=='23:59:59'){
			return DATE("g:i A", strtotime($time));
		}else{
			return DATE("g:i A", strtotime($time)+1);
		}
	}
}
?>
