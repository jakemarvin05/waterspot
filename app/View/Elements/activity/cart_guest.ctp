<div class="guest-user-login-wrapper">
	<div id="guest_email" class="guest-user-login">
		<? $guest_email=$this->Session->read('Guest_email');?>
		<? if(empty($guest_email) and empty($member_id)) { ?>
			<h3>Proceed with your booking</h3>
			<?=$this->Form->create('GuestLogin',array('url'=>array('plugin'=>false,'controller'=>'carts','action'=>'guest_login'),'class'=>'','id'=>'guest_login','novalidate' => true),array('placeholder'=>'Enter email address'));?>
				<div class="guest-login-option-box">
					<? $options = array('0' => 'Continue as a Guest.<br />','1'=> 'Already have an account.');
						$attributes = array(
							'legend' => false,
							'label' => true,
							'value' => false,
							'onclick'=>'togelshow(this.id);',
							);	
						echo $this->Form->radio('GuestLogin',$options, $attributes);?>								
				</div>
				<div class="guest-user-login-content">
					<div class="guest-login-input-box">
						<div style="float: left; width: 100%; display: block;">
							<label class="email-label">Email Address:</label>
							<?=$this->Form->text('email_id', array('div'=>false,'label'=>false,'placeholder'=>'Email id','class'=>'form-control')); ?>
						</div>
						<div style="float: left; width: 100%; display:none;" id="show_password">
							<label>Enter Password:</label>
							<?=$this->Form->text('password', array('div'=>false,'label'=>false,'type'=>'password','placeholder'=>'Password','class'=>'form-control')); ?>
							<div class="forgot-password">
								<?php echo $this->Html->link('Forgot Password?',array('controller'=>'members','action'=>'resetpassword','plugin'=>'member_manager'),array('alt'=>'Forgot Password','target'=>'_blank','title'=>'Forgot Password'));?>
							</div>
						</div>
					</div>
					<div class="guest-login-button-box">
						<div style="float: left; text-align: left; font-size: 13px;">
							Don't have an account? <?php echo $this->Html->link('Sign Up',array('controller'=>'members','action'=>'registration','plugin'=>'member_manager'),array('class'=>'signup-link','target'=>'_blank','alt'=>'Sign Up','title'=>'Sign Up'));?>
						</div>
						<input type="submit" value="Continue" id="loginButton" class="submit-button" />
					</div>
				</div>
			<?=$this->Form->end(); ?>	
		<?php } ?>
	</div>
</div>
<script type="text/javascript">
	$("label[for=GuestLoginGuestLogin0]").addClass('current');
</script>