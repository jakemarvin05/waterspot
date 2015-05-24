
<div class="clear"></div>
<?=$this->element('breadcrumbs');?>
 
	<div class="middle-area">
		<div style="text-align:center; padding-bottom:15px; padding-top:5%;">
			<form name="payFormCcard" id="payFormCcard" method="post" action="<?=$payment_action?>">
			<? foreach($payment_data as $key=>$data){ ?>
				<input type="hidden" name="<?=$key;?>" value="<?php echo $data;?>">
			<? }?>
			</form>	
			<?=$this->Html->image('processing-2.png',array('alt'=>'Processing'));?>
			
		</div>
		
	</div>
 

<script>
$(document).ready(function(){
     $("#payFormCcard").submit();
});
</script>
