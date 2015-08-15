
<div class="clear"></div>
<?=$this->element('breadcrumbs');?>
 
	<div class="middle-area">
		<div style="text-align:center; padding-bottom:15px; padding-top:5%;">
			<?php echo $formData;?>
			<?=$this->Html->image('loader-2.gif',array('alt'=>'Processing'));?>
			
		</div>
		
	</div>
 

<script>
$(document).ready(function(){
     $("#payFormCcard").submit();
});
</script>
