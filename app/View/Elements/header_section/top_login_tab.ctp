<div id="fb-root"></div>
<form id="fb_login" style="display:none;" action="/members/fb_login" method="post">
	<input type="hidden" name="first_name" id="fb_fname">
	<input type="hidden" name="last_name" id="fb_lname">
	<input type="hidden" name="email_id" id="fb_email">
	<input type="hidden" name="phone" id="fb_phone">
	<input type="hidden" name="fb_id" id="fb_id">
</form>
<script>
  // This is called with the results from from FB.getLoginStatus().
  function statusChangeCallback(response) {
    if (response.status === 'connected') {
    	FB.api('/me', function(response) {
    		//alert(JSON.stringify(response));
    		email = response.email;
    		first_name = response.first_name;
    		last_name = response.last_name;
    		fb_id = response.id;
    		//this is because in fb the user can use phone number to log in
    		if (email.indexOf('@') == -1) {
    			document.getElementById('fb_phone').value = email;
    		} else {
    			document.getElementById('fb_email').value = email;
    		}
    		document.getElementById('fb_id').value = fb_id;
    		document.getElementById('fb_fname').value = first_name;
    		document.getElementById('fb_lname').value = last_name;
    		document.getElementById('fb_login').submit();
    		document.getElementById('fb_login').submit();
		});
    } else if (response.status === 'not_authorized') {
    	// logged in facebook but not authorized
    } else {
    	// not logged ni facebook
    }
  }
  function checkLoginState() {
    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });
  }
  window.fbAsyncInit = function() {
  FB.init({
    appId      : '381957422009700',
    cookie     : true,  // enable cookies to allow the server to access 
                        // the session
    xfbml      : true,  // parse social plugins on this page
    version    : 'v2.2' // use version 2.2
  });
  // auto check if the user is logged in.
  // FB.getLoginStatus(function(response) {
  //   statusChangeCallback(response);
  // });

  };
  // Load the SDK asynchronously
  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));
</script>


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

<fb:login-button scope="public_profile,email,user_friends" onlogin="checkLoginState();">
</fb:login-button>

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
