<?php
class CommonfunctionHelper extends Helper {
	public $helpers = array();
	
	function get_day_diff($start_date=null,$end_date=null){
		echo $start_date;die;
		$diff = abs(strtotime($_POST['end_date']) - strtotime($_POST['start_date']));
		$years = floor($diff / (365*60*60*24));
		$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
		$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
			
	}
	
}
?>
