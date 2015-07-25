<div class="container-fluid wrapper carts-page">
	<section class="content">

		<header class="page-header text-center">
			<p class="beforeHeader">Congratulations, See you in the event</p>
			<h1 class=" headerAlt">Booking Successful</h1>
		</header>

		<div class="container">
		<div class="col-sm-6 col-sm-offset-3 col-xs-12">
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
			<br><br><br>
<div class="clearfix"></div>
			</div></div>
		</section>
	</div>

<!--<script type="text/javascript">
<?php //$path = $this->Html->webroot; ?>
    $(document).ready(function(){
	 });
</script>-->

