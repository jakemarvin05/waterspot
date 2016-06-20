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
					
				</div>
			</div>
		</div>
		<div class="book-my-cart-content">
			<?=$this->Form->create('Cart',array('url'=>array('plugin'=>false,'controller'=>'carts','action'=>'add_invite',$service_id,$cart_id),'id'=>'add_invite','novalidate' => true));
			echo $this->Form->hidden('no_participants',array('value'=>$cart_details['Cart']['no_participants']));
			echo $this->Form->hidden('no_of_pax',array('value'=>$cart_details['Cart']['no_of_pax']));
			echo $this->Form->hidden('is_private',array('value'=>($cart_details['Cart']['no_of_pax']>0?'true':'false')));
			echo $this->Form->hidden('additional_hour',array('value'=>($cart_details['Cart']['additional_hour']>0?$cart_details['Cart']['additional_hour']:0)));
			?>
				<? if($cart_details['Cart']['no_participants']>1){
					$cart_css="";
				?>
				<div class="leftContentBox">
					<div class="cart-payment-method" style="display:none">
						<!-- <h4>Cost Sharing</h4> -->
						<? $options = array('1'=> '<span>Single Payment</span>','0' => '<span>Go Dutch</span><br />');
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
					
					<div class="cart-invite-frnds" id="go_dutch_field" style="display:none;">
						<span id='CartEmail'></span>
						<div id="cart-email-scrollable" style="max-height:152px;">
							<span id='CartEmail'></span>
							I will pay for <select id="participants_count"></select> of my friends
							
							<h5>Enter email address of friend to "go-dutch":</h5>
							<div id='email_inputs'>
							</div>
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
						<!-- <h4>Value Added Services</h4>
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
						</div> -->
					</div>
					<div class="cart-payment-details">
						<h4>Payment Details</h4>
						<table class="cart-payment-desc" border="1" cellspacing="10px">
							<tr>
								<th width="70%">Total No. of Participants:</th>
								<td width="27%"><?php echo ($cart_details['Cart']['no_participants']?$cart_details['Cart']['no_participants']:$cart_details['Cart']['no_of_pax']);?></td>
							</tr>
							<?php if($no_of_booking_days>1) { ?>
								<tr>
									<th>No. of Days:</th>
									<td><?=$no_of_booking_days;?></td>
								</tr>
							<?php } ?>
							<? if(is_array($cart_details['Cart']['slots'])) {?> 
								<? foreach($cart_details['Cart']['slots'] as $slot_key=>$slot_time) {?>
									<tr>
										<th>Slot <?php echo $this->Time->meridian_format($slot_time['start_time']). " To ".$this->Time->end_meridian_format($slot_time['end_time']);?> : </th>
										<td><?="$".number_format($slot_time['price'],2)?></td>
									</tr>
								<?php } ?>	
							<? } ?>
							<!-- <tr>
								<th>VAS Total <span id="Vas_detail"></span>:</th>
								<td><span id='Vas_total'></span></td>
							</tr> -->
							<tr>


								<th>Total:</th>
								<td><span id='sub_total'> $<?=number_format($cart_details['Cart']['total_amount'],2);?></span></td>
							</tr>
						</table>
					</div>
				</div>
				<div class="cart-button-box">
					<input type="submit" value="Proceed" id="" class="btn-block btnDefaults btnFillOrange" style="margin-top:5px;" />
					<?=$this->Html->link('Cancel', array('plugin'=>false,'controller'=>'carts','action'=>'cancel_cart',$service_detail['Service']['id'],$cart_details['Cart']['id']), array('class'=>'cancel-button btn-block text-center btnDefaults btnFillGrey','escape' => false));?>
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
<script type="text/javascript">
						//for the go dutch script
						function payment_method(method) {
							if (method == 'go_dutch') {
								var participants = <?php echo $cart_details['Cart']['no_participants'] - 2; ?>;
								var options = '';
								for(i = 0; i <= participants; i++) {
									if (i == 0) {
										options += '<option value="'+ i +'">none</option>';
									} else {
										options += '<option value="'+ i +'">'+ i +'</option>';
									}
								}
								$('#participants_count').html(options);
								$('#go_dutch_field').css('display','block');
								$('#participants_count').val('0');
								email_inputs();
							} else {
								$('#go_dutch_field').css('display','none');
								$('#email_inputs').html('');
								$('#participants_count').html('');
							}
						}

						$('#CartInvitePaymentStatus0').click(function(){payment_method('go_dutch');});
						$('#CartInvitePaymentStatus1').click(function(){payment_method('pay_all');});
						$('#CartInvitePaymentStatus1').click();

						function email_inputs(){
							var participants = <?php echo $cart_details['Cart']['no_participants'] - 1; ?>;
							count = $('#participants_count').val()*1;
							var is_private = $('#CartIsPrivate').val();
							var texts = '';
							for(i = count; i < participants; i++) {
								texts += '<input name="data[Cart][email][]" placeholder="Enter email address" type="text" ><br />';
							}
							$('#email_inputs').html(texts);
							var current_total = <?php echo $cart_details['Cart']['total_amount']; ?>;
							if(is_private==false) {
								$('#sub_total').html('$' + (current_total * (count + 1)));
							}
							}

						$('#participants_count').change(function(){email_inputs();});

					</script>
