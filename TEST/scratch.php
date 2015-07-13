<div class="left-area col-sm-3 col-xs-12">
	<div class="member-vendor-info">
		<p>
			Hello <br/><?php echo ucfirst($this->Session->read('VendorAuth.VendorAuth.fname'))." ".ucfirst($this->Session->read('VendorAuth.VendorAuth.lname')); ?>
		</p>
		 
	</div>
	<div class="membor-vendor-links">
		<ul>
			<li>
				<i class="fa fa-line-chart"></i> &nbsp; <?=$this->Html->link("Dashboard",array('controller' => 'members', 'action' => 'dashboard','plugin'=>'member_manager'),array('escape' => false,'class'=>($this->params->action=='dashboard')?'active':''));?>
			</li>
			<li>
				<i class="fa fa-book"></i> &nbsp; <?=$this->Html->link("Booking Requests",array('controller' => 'bookings', 'action' => 'booking_request','plugin'=>'member_manager'),array('escape' => false,'class'=>($this->params->action=='booking_request')?'active':''));?>
			</li>
			<li>
				<i class="fa fa-calendar"></i> &nbsp; <?=$this->Html->link("My Bookings",array('controller' => 'bookings', 'action' => 'booking_list','plugin'=>'member_manager'),array('escape' => false,'class'=>($this->params->action=='booking_list')?'active':''));?>
			</li>
			<li>
				<i class="fa fa-ship"></i> &nbsp; <?=$this->Html->link("My Services",array('controller' => 'services', 'action' => 'my_services','plugin'=>'member_manager'),array('escape' => false,'class'=>($this->params->controller=='member_service_availabilities' || $this->params->controller=='services')?'active':''));?>
			</li>
			<li>
				<i class="fa fa-comments-o"></i> &nbsp; <?=$this->Html->link("Service Reviews",array('controller' => 'service_reviews', 'action' => 'reviews','plugin'=>'member_manager'),array('escape' => false,'class'=>($this->params->action=='reviews')?'active':''));?>
			</li>
			<li>
				<i class="fa fa-user"></i> &nbsp; <?=$this->Html->link("Edit Profile",array('controller' => 'accounts', 'action' => 'editProfile','plugin'=>'member_manager'),array('escape' => false,'class'=>($this->params->action=='editProfile')?'active':''));?>
			</li>
			<li>
				<i class="fa fa-envelope-o"></i> &nbsp; <?=$this->Html->link("Edit Email",array('controller' => 'accounts', 'action' => 'change_email','plugin'=>'member_manager'),array('escape' => false,'class'=>($this->params->action=='change_email')?'active':''));?>
			</li>
			
			<li>
				<i class="fa fa-lock"></i> &nbsp; <?=$this->Html->link("Change Password",array('controller' => 'accounts', 'action' => 'changepassword','plugin'=>'member_manager'),array('escape' => false,'class'=>($this->params->action=='changepassword')?'active':''));?>
			</li>
			<li>
				<i class="fa fa-user-times"></i> &nbsp; <?=$this->Html->link("Logout",array('controller' => 'members', 'action' => 'logout','plugin'=>'member_manager'),array('escape' => false,'class'=>($this->params->action=='logout')?'active':''));?>
			</li>
		</ul>
	</div>
</div>

<i class="fa fa-user"></i> &nbsp; <?=$this->Html->link("Edit Profile",array('controller' => 'accounts', 'action' => 'edit_Profile','plugin'=>'member_manager'),array('escape' => false,'class'=>($this->params->action=='edit_Profile')?'active':''));?>
<?=$this->Html->link($this->Html->image('editprofile-icon.png',array('alt'=>'Edit Profile','title'=>'Edit Profile'))." Edit Profile",array('controller' => 'members', 'action' => 'edit_profile','plugin'=>'member_manager'),array('escape' => false,'class'=>($this->params->action=='edit_profile')?'active':''));?>
