<div class="book-my-cart-wrapper">
	<div id="invite_participant" class="book-my-cart">
		<h3>Book Now</h3>
		<div class="cart-activity">
			<h6><?=$cart_details['Service']['service_title']; ?></h6>
			<? $path=WWW_ROOT.'img'.DS.'service_images'.DS;
			 $imgArr = array('source_path'=>$path,'img_name'=>$cart_details['Cart']['image'],'width'=>101,'height'=>64,'noimg'=>$setting['site']['site_noimage']);
			 $resizedImg = $this->ImageResize->ResizeImage($imgArr);
			 echo $this->Html->image($resizedImg,array('border'=>'0','alt'=>$cart_details['Service']['service_title'])) ; ?>
			<div class="cart-activity-txt">
				<strong><?=date(Configure::read('Calender_format_php'),strtotime($cart_details['Cart']['start_date'])); ?>&nbsp;&nbsp;To&nbsp;&nbsp;<?=date(Configure::read('Calender_format_php'),strtotime($cart_details['Cart']['end_date'])); ?></strong>
				<div class="cart-activity-slot-box">
					<? if(is_array($cart_details['Cart']['slots'])) {?> 
						<? foreach($cart_details['Cart']['slots'] as $slot_key=>$slot_time) {?>
							<div class="cart-activity-slot">
								<?php echo $this->Time->meridian_format($slot_time['start_time']). " To ".$this->Time->end_meridian_format($slot_time['end_time']);?>
							</div>
						<?php } ?>	
					<? } ?>
				</div>
			</div>
		</div>
		<div class="book-my-cart-content">
			<?=$this->Form->create('Cart',array('url'=>array('plugin'=>false,'controller'=>'carts','action'=>'add_invite',$service_id,$cart_id),'id'=>'add_invite','novalidate' => true));
			echo $this->Form->hidden('no_participants',array('value'=>$cart_details['Cart']['no_participants']));
			?>
				<? if($cart_details['Cart']['no_participants']>1){
					$cart_css="";
				?>
				<div class="leftContentBox">
					<div class="cart-payment-method">
						<h4>Cost Sharing</h4>
						<? $options = array('0' => '<span>Friends will pay their share</span><br />','1'=> '<span>I will pay for my friends</span>');
						$attributes = array(
							'legend' => false,
							'label' => true,
							'value' => false,
							'css'=>'radio-check-box',
							//'checked'=> ($foo == "0") ? FALSE : TRUE,
						);
						echo $this->Form->radio('invite_payment_status',$options, $attributes);
						?>
					</div>
					
					<div class="cart-invite-frnds">
						<h4>Invite your Friends</h4>
						<span id='CartEmail'></span>
						<div id="cart-email-scrollable" style="max-height:152px;">
							<span id='CartEmail'></span>
							<? for($i=1;$i<$cart_details['Cart']['no_participants'];$i++) { ?>
								<?=$this->Form->text('email.',array('placeholder'=>'Enter email address')); ?>
							<? }?>
						</div>
					</div>
					
				</div>
			<? }else{
				$cart_css="centeredContentBox";
				echo $this->Form->hidden('invite_payment_status',array('value'=>1));
			}?>
				<div class="rightContentBox <?php echo $cart_css;?>">
					<? $count=0;$no_scroll_css='';
					if(!empty($cart_details['Cart']['value_added_services'])){
						$count=1;
					}
					if($count<=0){
						$no_scroll_css="no-cart-vas-scroll";
					}
					?>
					<div class="cart-vas <?=$no_scroll_css; ?>">
						<h4>Value Added Services</h4>
						<div id="cart-vas-scrollable" style="max-height:95px;">
							<? if($count>0){
							?>
								<table class="cart-vas-desc">
									<tr>
										<th width="70%">Service Name</th>
										<th width="27%" style="padding-right:12px; text-align:right;">Price ($)</th>
									</tr>
									<? foreach($cart_details['Cart']['value_added_services'] as $key=>$value_added_service) {
									$value_added_list=$value_added_service['value_added_price']."@_".$value_added_service['service_id']."@_".$value_added_service['value_added_name'];	
									?>
										<tr>
											<td>
												<?=$this->Form->checkbox('value_added_services.',array('esacpe'=>false,"id"=>"CartValueAddedServices$key",'value'=>$value_added_list,'class'=>'valueadd_check','hiddenField' => false)); ?>
												<label for="CartValueAddedServices<?=$key?>" class="srvc-name"><?= $value_added_service['value_added_name'];?></label>
											</td>
											<td style="padding-right:12px; text-align:right;">
												$<?= $value_added_service['value_added_price'];?>
											</td>
										</tr>
									<? }?>	 
								</table>
							<? } else {?>
								<div class="cart-vas-no-details">There are no value added services</div>
							<? } ?>
						</div>
					</div>
					<div class="cart-payment-details">
						<h4>Payment Details</h4>
						<table class="cart-payment-desc">
							<tr>
								<th width="70%">Total No. of Participants:</th>
								<td width="27%"><?=$cart_details['Cart']['no_participants'];?></td>
							</tr>
							<?php if($no_of_booking_days>1) { ?>
								<tr>
									<th>No. of Days:</th>
									<td><?=$no_of_booking_days;?></td>
								</tr>
							<?php } ?>
							<tr>
								<th>Per Slot/Day Price :</th>
								<td><?="$".number_format($cart_details['Cart']['price'],2)?></td>
							</tr>
							<tr>
								<th>VAS Total <span id="Vas_detail"></span>:</th>
								<td><span id='Vas_total'></span></td>
							</tr>
							<tr>
								<th>Service Price (<?="$".number_format($cart_details['Cart']['price'],2)?>&nbsp;x&nbsp;<span id="total_participate">1</span>&nbsp;x&nbsp;<span id="no_of_booking_days">&nbsp;1&nbsp;</span>):</th>
								<td>$<span id="total_participate_amount"><?=number_format($cart_details['Cart']['price'],2);?></span></td>
							</tr>
							<tr>
								<th>Total:</th>
								<td><span id='sub_total'> $<?=number_format($cart_details['Cart']['total_amount'],2);?></span></td>
							</tr>
						</table>
					</div>
				</div>
				<div class="cart-button-box">
					<input type="submit" value="Proceed" id="loginButton" class="submit-button addtocart-button" />
					<?=$this->Html->link('Cancel', array('plugin'=>false,'controller'=>'carts','action'=>'cancel_cart',$service_detail['Service']['id'],$cart_details['Cart']['id']), array('class'=>'cancel-button','escape' => false));?>
				</div>
			<?=$this->Form->end(); ?>
		</div>
	</div>
</div>
<script>
 $(document).ready(function(){
		// for price update
			updateTotal();
		});
</script>
