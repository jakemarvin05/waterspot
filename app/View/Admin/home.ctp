<article>
	<header>
		<h2 style="cursor: s-resize;">Dashboard</h2>                        
        </header>
</article>
<?=$this->element('admin/message'); ?>
<article style="float:left;" class="half-block nested clearrm">
		<div  class="article-container">
				<header><h2 style="cursor: s-resize;">Booking </h2></header>
				<section>
				<div class="table-form">					
				<div>
						<table cellspacing="0" cellpadding="0" border="1" style="border-collapse:collapse;" id="grdrecipe" rules="all">
						<tbody><tr>
								<th scope="col"></th>
								<th>SNo</th>
								<th> Name</th>
								<th> Date</th>
								<th> Booking Date</th>
								<th> Status</th>
								<th>Actions</th>	                                                      
						</tr>
				<?php  $i=1;  foreach($booking_details as $booking_detail){?>		
						<tr>		
								<td></td>
								<td><?php echo $i;?></td>
								<td><?php echo $booking_detail['Booking']['fname'].' '.$booking_detail['Booking']['lname'];?></td>
								
								<td><?=date(Configure::read('Calender_format_php'),strtotime($booking_detail['BookingOrder']['booking_date'])); ?></td>
								<td><?=date(Configure::read('Calender_format_php'),strtotime($booking_detail['BookingOrder']['start_date'])); ?></td>
								<td>
									<?php if($booking_detail['Booking']['status']=='1') {
										echo $this->Html->image('admin/icons/icon_success.png', array('alt'=>"Completed",'title'=>"Completed"));
									}else {
										echo $this->Html->image('admin/icons/icon_error.png', array('alt'=>"Not completed",'title'=>"Not completed"));
									}?>
								</td>
								<td>
									<?=$this->Html->link($this->Html->image('cemera-icon.png',array('alt'=>'View Detail','title'=>'View Detail','border'=>'0')),array('admin'=>true,'controller'=>'bookings','action'=>'booking_details',$booking_detail['Booking']['ref_no']),array('escape' => false));?> 
								</td>
						</tr>
				<?php  $i++; }  ?>
						</tbody>
						</table>
				</div>
				</div>
				</section>
				<footer>
				<p><?php echo $this->Html->link('View all', array('admin'=>true,'controller'=>'bookings', 'action' => 'index'), array('escape' => false));?></p>
				</footer>
		</div>		
</article>

<article style="float:left;" class="half-block nested clearrm">
		<div  class="article-container">
				<header><h2 style="cursor: s-resize;">Vendor </h2></header>
				<section>
				<div class="table-form">					
				<div>
						<table cellspacing="0" cellpadding="0" border="1" style="border-collapse:collapse;" id="grdrecipe" rules="all">
						<tbody><tr>
								<th scope="col"></th>
								<th>SNo</th>
								<th> Name</th>                                			                        
								<th>Actions</th>	                                                      
						</tr>
				<?php  $i=1;  foreach($vendors as $vendor){?>		
						<tr>		
								<td></td>
								<td><?php echo $i;?></td>
								<td><?php echo $vendor['Vendor']['fname'].' '.$vendor['Vendor']['lname'];?></td>
								<td>
									<ul class="actions">
									<li><?php echo $this->Html->link('edit', array('plugin'=>'admin/vendor_manager','controller'=>'vendors', 'action' => 'add/',$vendor['Vendor']['id']), array('escape' => false,'class'=>'edit','title'=>'Edit Vendor','rel'=>'tooltip'));?></li>                                                                                                                     
									</ul>
								</td>
						</tr>
				<?php  $i++; }  ?>
						</tbody>
						</table>
				</div>
				</div>
				</section>
				<footer>
				<p><?php echo $this->Html->link('View all', array('plugin'=>'admin/vendor_manager','controller'=>'vendors', 'action' => 'index'), array('escape' => false));?></p>
				</footer>
		</div>		
</article>

<article style="float:left;" class="half-block nested clearrm">
		<div  class="article-container">
				<header><h2 style="cursor: s-resize;">Member </h2></header>
				<section>
				<div class="table-form">					
				<div>
						<table cellspacing="0" cellpadding="0" border="1" style="border-collapse:collapse;" id="grdrecipe" rules="all">
						<tbody><tr>
								<th scope="col"></th>
								<th>SNo</th>
								<th> Name</th>                                			                        
								<th>Actions</th>	                                                      
						</tr>
				<?php  $i=1;  foreach($members as $member){?>		
						<tr>		
								<td></td>
								<td><?php echo $i;?></td>
								<td><?php echo $member['Member']['first_name'].' '.$member['Member']['last_name'];?></td>
								<td>
									<ul class="actions">
									<li><?php echo $this->Html->link('edit', array('plugin'=>'admin/member_manager','controller'=>'members', 'action' => 'add/',$member['Member']['id']), array('escape' => false,'class'=>'edit','title'=>'Edit Member','rel'=>'tooltip'));?></li>                                                                                                                     
									</ul>
								</td>
						</tr>
				<?php  $i++; }  ?>
						</tbody>
						</table>
				</div>
				</div>
				</section>
				<footer>
				<p><?php echo $this->Html->link('View all', array('plugin'=>'admin/member_manager','controller'=>'members', 'action' => 'index'), array('escape' => false));?></p>
				</footer>
		</div>		
</article>


<article style="float:left;" class="half-block nested clearrm">
		<div  class="article-container">
				<header><h2 style="cursor: s-resize;">Content </h2></header>
				<section>
				<div class="table-form">					
				<div>
						<table cellspacing="0" cellpadding="0" border="1" style="border-collapse:collapse;" id="grdrecipe" rules="all">
						<tbody><tr>
								<th scope="col"></th>
								<th>SNo</th>
								<th> Title</th>                                			                        
								<th>Actions</th>	                                                      
						</tr>
				<?php  $i=1;  foreach($pages as $Page){?>		
						<tr>		
								<td></td>
								<td><?php echo $i;?></td>
								<td><?php echo $Page['Page']['name'];?></td>
								<td>
									<ul class="actions">
									<li><?php echo $this->Html->link('edit', array('plugin'=>'admin/content_manager','controller'=>'pages', 'action' => 'add/0',$Page['Page']['id']), array('escape' => false,'class'=>'edit','title'=>'Edit Page','rel'=>'tooltip'));?></li>                                                                                                                     
									</ul>
								</td>
						</tr>
				<?php  $i++; }  ?>
						</tbody>
						</table>
				</div>
				</div>
				</section>
				<footer>
				<p><?php echo $this->Html->link('View all', array('plugin'=>'admin/content_manager','controller'=>'pages', 'action' => 'index'), array('escape' => false));?></p>
				</footer>
		</div>		
</article>

