<?php
class FormatHelper extends AppHelper {
	var $helpers = Array('Html','Session');
	
	public function Headingsubstring($string,$string_len){
		if(is_int($string_len)){
			return (strlen($string)>$string_len)?substr($string,0,$string_len).'...':$string;
		}
		else{
			return $string;
		}
	}
	 
}
?>
