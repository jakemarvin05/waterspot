<ul class="header-new-login">
	<li class="parent-link"><a>Login | Signup</a>
		 <ul>
			  <li class="login">
				   <h3>Log-In</h3>
				   <?php echo $this->Form->create('Login',array('url'=>array('plugin'=>'member_manager','controller'=>'members','action'=>'vendor_member_login'),'class'=>'','id'=>'vendor_member_login','novalidate' => true))?>
				   
					<?php echo $this->Form->hidden('redirect_url',array('value'=>Router::url($this->here, true))); ?>
					<div>
						 <? $options = array('0' => '<span>Member</span><br style="clear:both;"/>','1'=> '<span>Vendor</span>');
						  $attributes = array(
							'legend' => false,
							'label' => true,
							'value' => false,
							'class'=>'radio-check-box',
							 
						);
						echo $this->Form->radio('login_type',$options, $attributes);
						?>
					</div>
					<div>
						  <?=$this->Form->email('email_id',array('required'=>false,'placeholder'=>'Email Address')); ?>
						  <?=$this->Form->error('email_id',null,array('wrap' => 'div', 'class' => 'error-message')); ?>
					</div>
					<div>
						 <?=$this->Form->password('password',array('required'=>false,'placeholder'=>'Password')); ?>
						 
						 <?=$this->Form->error('password',null,array('wrap'=>'div','class'=>'error-message'));?>
					</div>
					<div>
						 <a href="#" id="forgot" >Forgot your password?</a>
					</div>
					<div>
						
						<input class="smt2" value="Login" type="submit">
						<?php echo $this->Form->end();?>
						 
					</div>
				   </form>
			  </li>
			  
			  <li class="signup">
				   <h3>New to our website?</h3>
					<?php echo $this->Form->create('Signup',array('url'=>array('plugin'=>'member_manager','controller'=>'members','action'=>'vendor_member_signup'),'class'=>'','target'=>'_blank','id'=>'vendor_member_signup','novalidate' => true))?>
					<div>
						 <div class="radio-txt-desc">Dont't have an account with us. Create your account here</div>
						<? $options = array('0' => '<span>Member</span><br style="clear:both;"/>','1'=> '<span>Vendor</span>');
						  $attributes = array(
							'legend' => false,
							'label' => true,
							'separator' => '<div class="radio-txt-desc">Want to list on our website?</div>',
							'value' => false,
							'class'=>'',
							 
						);
						 echo $this->Form->radio('login_type',$options, $attributes);?>
					</div>
					<div>
						<input class="smt2" value="Create Account" type="submit">
				   </div>
				   <?php echo $this->Form->end();?>	
				   <div class="close-login">X</div>
			  </li>
		 </ul>
	</li>
	<li class="cart_menu">
		<?=$this->Html->link("My Cart (".$totalcart.")",array('plugin'=>false,'controller'=>'carts','action'=>'check_out'));?>
		 
	</li>
</ul>
<script type="text/javascript"> 
$(function() {
	 $('#forgot').click(function(event) {
		  
		if($("#LoginLoginType1").is(':checked'))
			window.open("<?=Router::url(array('controller' => 'accounts', 'action' => 'resetpassword','plugin'=>'vendor_manager'));?>","_blank");
		else
			
			window.open("<?=Router::url(array('controller' => 'members', 'action' => 'resetpassword','plugin'=>'member_manager'));?>","_blank");
	});
}); 
</script> 
