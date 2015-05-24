<div class="left-area">
	<div class="member-vendor-info">
		<p>
			Hello <br/><?php echo ucfirst($this->Session->read('MemberAuth.MemberAuth.first_name'))." ".ucfirst($this->Session->read('MemberAuth.MemberAuth.last_name')); ?>
		</p>		
	</div>
	<div class="membor-vendor-links">
		<ul>
			<li>
				<?=$this->Html->link($this->Html->image('dashboard-icon.png',array('alt'=>'Dashboard','title'=>'Dashboard'))." Dashboard",array('controller' => 'members', 'action' => 'dashboard','plugin'=>'member_manager'),array('escape' => false,'class'=>($this->params->action=='dashboard')?'active':''));?></li>
			<li>
				<?=$this->Html->link($this->Html->image('mybooking-icon.png',array('alt'=>'My Bookings','title'=>'My Bookings'))." Booking Response",array('controller' => 'bookings', 'action' => 'booking_status','plugin'=>'member_manager'),array('escape' => false,'class'=>($this->params->action=='booking_status')?'active':''));?>
			</li>
			<li>
				<?=$this->Html->link($this->Html->image('mybooking-icon.png',array('alt'=>'My Bookings','title'=>'My Bookings'))." My Bookings",array('controller' => 'bookings', 'action' => 'booking_list','plugin'=>'member_manager'),array('escape' => false,'class'=>($this->params->action=='booking_list')?'active':''));?>
			</li>
			<li>
				<?=$this->Html->link($this->Html->image('editprofile-icon.png',array('alt'=>'Edit Profile','title'=>'Edit Profile'))." Edit Profile",array('controller' => 'members', 'action' => 'edit_profile','plugin'=>'member_manager'),array('escape' => false,'class'=>($this->params->action=='edit_profile')?'active':''));?>
			</li>
			<li>
				<?=$this->Html->link($this->Html->image('editemail-icon.png',array('alt'=>'Edit Email','title'=>'Edit Email'))." Edit Email",array('controller' => 'members', 'action' => 'change_email','plugin'=>'member_manager'),array('escape' => false,'class'=>($this->params->action=='change_email')?'active':''));?>
			</li>
			<li>
				<?=$this->Html->link($this->Html->image('changepwd-icon.png',array('alt'=>'Change Password','title'=>'Change Password'))." Change Password",array('controller' => 'members', 'action' => 'changepassword','plugin'=>'member_manager'),array('escape' => false,'class'=>($this->params->action=='changepassword')?'active':''));?>
			</li>
			<li>
				<?=$this->Html->link($this->Html->image('logout-icon.png',array('alt'=>'Logout','title'=>'Logout'))." Logout",array('controller' => 'members', 'action' => 'logout','plugin'=>'member_manager'),array('escape' => false,'class'=>($this->params->action=='logout')?'active':''));?>
			</li>
		</ul>
	</div>
</div>
