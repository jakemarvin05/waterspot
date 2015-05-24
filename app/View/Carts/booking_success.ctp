<div class="hr-line"></div>
<div class="clear"></div>
<?=$this->element('breadcrumbs');?>
<h2 class="page-title">Booking <span style="color:#000;">Successful</span></h2>
<?php echo $this->element('message');?>

<div class="<?=(!empty($cart_details))?'ch-out':'middle-area'?>"> 

<? $sub_total=0;
 if(empty($cart_details)) {?>
	<div>
		<div class="service-hd">Booking request sent</div>
			Thanks for showing interest.<br> We will send you invoice to pay once vendor confirms availability.<br>
			<!--<p class="empty">
				<?//=$this->Html->link("Click here to view booking request status.",array('controller' => 'bookings', 'action' => 'booking_status','plugin'=>'member_manager')); ?>
			</p>-->
	</div>
 <? } ?>
</div>

<div class="clear"></div>

<!--<script type="text/javascript">
<?php //$path = $this->Html->webroot; ?>
    $(document).ready(function(){
	 });
</script>-->

