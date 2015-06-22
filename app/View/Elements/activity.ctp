<? foreach(range(1,20) as $r){
		$participant_range[$r] = $r;
	} 
		$participant_range[21]='>20';
?>

<div class="quick-contacts">
       <?=$this->Form->create('Search',array('url'=>array('plugin'=>false,'controller'=>'search','action'=>'index'),'novalidate' => true,'onSubmit'=>'return validate()'));?>
		    
		   <?=$this->Form->input('service_id',array('options'=>$service_type_list,'empty'=>'Select Activity','label'=>false,'div'=>false,'required'=>false));?>
           <!--<input type="text" value="Enter Location" class="activity" />-->
           <?=$this->Form->text('start_date',array('class'=>'date-icon','placeholder'=>'Select Start Date','autocomplete'=>'off'));?>
           <? //=$this->Form->text('end_date',array('class'=>'date-icon','placeholder'=>'Select End Date','autocomplete'=>'off'));?>
           <!--<input type="text" value="Enter Location" class="price" />-->
           <?=$this->Form->input('no_participants',array('options'=>$participant_range,'class'=>'member','empty'=>'Select Number of Participants','label'=>false,'div'=>false));?>
           
          <input name="submit" class="smt" value="start your adventure" type="submit" align="center" /> <span class="arrow1"></span>
       <?=$this->Form->end();?> 
   </div>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script>
	$(function(){
		$( "#SearchStartDate" ).datepicker({
			dateFormat: "<?=Configure::read('Calender_format'); ?>",
			minDate: 0,
			changeMonth: true,
			onSelect:function(selectedDate){
			$( "#SearchEndDate" ).datepicker( "option", "minDate", selectedDate );
		  }
		});
		$( "#SearchEndDate" ).datepicker({
			dateFormat: "<?=Configure::read('Calender_format'); ?>",
			minDate: 0,
			changeMonth: true,
			onSelect:function(selectedDate){
			$( "#SearchStartDate" ).datepicker( "option", "maxDate", selectedDate );
		  }
		});
	});
	
</script>

<script type="text/javascript">
	 
	function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
		alert('Enter Participants in numbers only.');
        return false;
     }   
    return true;
}
</script>

<script language="javascript">
function validate()
{ 
	var service_id =  $('#SearchServiceId :selected').val();
	if(service_id==null || service_id=='')
	 {
		 alert("Please select your Sports Activity.");
		//document.getElementById('SearchServiceId').focus();
		 return false;
	 } 
	 
}
</script>
	
