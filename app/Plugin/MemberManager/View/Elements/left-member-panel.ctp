<div class="left-area col-sm-3 col-xs-12">
	<div class="member-vendor-info">
		<p>
			Hello <br/><?php echo ucfirst($this->Session->read('MemberAuth.MemberAuth.first_name'))." ".ucfirst($this->Session->read('MemberAuth.MemberAuth.last_name')); ?>
		</p>		
	</div>
	<div class="membor-vendor-links">
		<ul>
			<li>
				<i class="fa fa-line-chart"></i> &nbsp; <?=$this->Html->link("Dashboard",array('controller' => 'members', 'action' => 'dashboard','plugin'=>'member_manager'),array('escape' => false,'class'=>($this->params->action=='dashboard')?'active':''));?>
                        </li>
                        <li>
				<i class="fa fa-book"></i> &nbsp; <?=$this->Html->link("Booking Response",array('controller' => 'bookings', 'action' => 'booking_status','plugin'=>'member_manager'),array('escape' => false,'class'=>($this->params->action=='booking_status')?'active':''));?>
			</li>
			<li>
				<i class="fa fa-calendar"></i> &nbsp; <?=$this->Html->link("My Bookings",array('controller' => 'bookings', 'action' => 'booking_list','plugin'=>'member_manager'),array('escape' => false,'class'=>($this->params->action=='booking_list')?'active':''));?>
			</li>
			<li>
				<i class="fa fa-comments-o"></i> &nbsp; <?=$this->Html->link("Messages",array('controller' => 'members', 'action' => 'messages','plugin'=>'member_manager'),array('escape' => false,'class'=>($this->params->action=='messages')?'active':''));?>
			</li>
			<li>
				<i class="fa fa-user"></i> &nbsp; <?=$this->Html->link("Edit Profile",array('controller' => 'members', 'action' => 'edit_profile','plugin'=>'member_manager'),array('escape' => false,'class'=>($this->params->action=='edit_profile')?'active':''));?>
			</li>
			<li>
				<i class="fa fa-envelope-o"></i> &nbsp; <?=$this->Html->link("Edit Email",array('controller' => 'members', 'action' => 'change_email','plugin'=>'member_manager'),array('escape' => false,'class'=>($this->params->action=='change_email')?'active':''));?>
			</li>
			<li>
				<i class="fa fa-lock"></i> &nbsp; <?=$this->Html->link("Change Password",array('controller' => 'members', 'action' => 'changepassword','plugin'=>'member_manager'),array('escape' => false,'class'=>($this->params->action=='changepassword')?'active':''));?>
			</li>
			<li>
				<i class="fa fa-user-times"></i> &nbsp; <?=$this->Html->link("Logout",array('controller' => 'members', 'action' => 'logout','plugin'=>'member_manager'),array('escape' => false,'class'=>($this->params->action=='logout')?'active':''));?>
			</li>
		</ul>
	</div>
</div>
