<div class="left-area">
	<div class="member-vendor-info">
		<p>
			Hello <br/><?php echo ucfirst($this->Session->read('VendorAuth.VendorAuth.fname'))." ".ucfirst($this->Session->read('VendorAuth.VendorAuth.lname')); ?>
		</p>
		 
	</div>
	<div class="membor-vendor-links">
		<ul>
			<li> 
				<?=$this->Html->link($this->Html->image('dashboard-icon.png',array('alt'=>'Dashboard','title'=>'Dashboard'))." Dashboard",array('controller' => 'vendors', 'action' => 'dashboard','plugin'=>'vendor_manager'),array('escape' => false,'class'=>($this->params->action=='dashboard')?'active':''));?>
			</li>
			<li>
				<?=$this->Html->link($this->Html->image('mybooking-icon.png',array('alt'=>'Booking Request','title'=>'Booking Request'))." Booking Requests",array('controller' => 'bookings', 'action' => 'booking_request','plugin'=>'vendor_manager'),array('escape' => false,'class'=>($this->params->action=='booking_request')?'active':''));?>
			</li>
			<li>
				<?=$this->Html->link($this->Html->image('mybooking-icon.png',array('alt'=>'My Bookings','title'=>'My Bookings'))." My Bookings",array('controller' => 'bookings', 'action' => 'booking_list','plugin'=>'vendor_manager'),array('escape' => false,'class'=>($this->params->action=='booking_list')?'active':''));?>
			</li>
			<li>
				<?=$this->Html->link($this->Html->image('myservice-icon.png',array('alt'=>'My Services','title'=>'My Services'))." My Services",array('controller' => 'services', 'action' => 'my_services','plugin'=>'vendor_manager'),array('escape' => false,'class'=>($this->params->controller=='vendor_service_availabilities' || $this->params->controller=='services')?'active':''));?>
			</li>
			<li>
				<?=$this->Html->link($this->Html->image('service_review-icon.png',array('alt'=>'Service Reviews','title'=>'Service Reviews'))."Service Reviews",array('controller' => 'service_reviews', 'action' => 'reviews','plugin'=>'vendor_manager'),array('escape' => false,'class'=>($this->params->action=='reviews')?'active':''));?>
			</li>
			<li>
				<?=$this->Html->link($this->Html->image('editprofile-icon.png',array('alt'=>'Edit Profile','title'=>'Edit Profile'))." Edit Profile",array('controller' => 'accounts', 'action' => 'editProfile','plugin'=>'vendor_manager'),array('escape' => false,'class'=>($this->params->action=='editProfile')?'active':''));?>
			</li>
			<li>	
				<?=$this->Html->link($this->Html->image('editemail-icon.png',array('alt'=>'Edit Email','title'=>'Edit Email'))." Edit Email",array('controller' => 'accounts', 'action' => 'change_email','plugin'=>'vendor_manager'),array('escape' => false,'class'=>($this->params->action=='change_email')?'active':''));?>
			</li>
			
			<li>
				<?=$this->Html->link($this->Html->image('changepwd-icon.png',array('alt'=>'Change Password','title'=>'Change Password'))." Change Password",array('controller' => 'accounts', 'action' => 'changepassword','plugin'=>'vendor_manager'),array('escape' => false,'class'=>($this->params->action=='changepassword')?'active':''));?>
			</li>
			<li>
				<?=$this->Html->link($this->Html->image('logout-icon.png',array('alt'=>'Logout','title'=>'Logout'))." Logout",array('controller' => 'vendors', 'action' => 'logout','plugin'=>'vendor_manager'),array('escape' => false,'class'=>($this->params->action=='logout')?'active':''));?>
			</li>
		</ul>
	</div>
</div>
