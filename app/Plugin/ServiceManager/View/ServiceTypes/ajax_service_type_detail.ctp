<?php echo $this->element('activity/listing',array('search_service_lists'=>$activity_service_list)); ?>
<script type="text/javascript">
page = <?=$this->paginator->counter('{:page}')?>;
pages = <?=$this->paginator->counter('{:pages}')?>;
$(function(){ 
	 $('input.star').rating(); 
 });
</script>
