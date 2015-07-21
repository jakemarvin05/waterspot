 <script>
	$(document).ready(function(){
		// Notification Close Button
		$('.close-notification').click(
		function () {
			$(this).parent().fadeTo(350, 0, function () {$(this).slideUp(600);});
			return false;
			}
		);
	})
</script>

<?php if ($this->Session->check('Message.flash')): ?>
<div class="notification success-psw">
<a class="close-notification close" title="Hide Notification" rel="tooltip" href="#"><i class="fa fa-times"></i></a>
 <?=$this->Session->flash(); ?> 
</div>
<?php endif;?>



<?php if ($this->Session->check('Message.error')): ?>
<div class="notification error">

<a class="close-notification close" href="#" title="Hide Notification" rel="tooltip"><i class="fa fa-times"></i></a>
 <?=$this->Session->flash('error'); ?> 
</div>
<?php endif;?>
