<div class="hr-line"></div>
<div class="clear"></div>
<div class="bredcrum"><?=$this->element('breadcrumbs');?></div>
<h2 class="page-title">Vendors</h2>
<div class="middle-area">
	<div class="make-pay">
		<h6>Make Your Payment Here</h6>
		<p>Please pay now to activate your account</p>
		<div class="form-row">

		<label><?=$this->Form->hidden('vendor_id',array('required'=>false,'value'=>$vendor['Vendor']['id'])); ?></label>
		</div>
		<div class="form-row"><label>Amount: </span></label>$
			<?=$vendor_amount; ?>
		</div> 
				
		<div class="pay-now"><?=$this->Html->link('Pay Now',array('plugin'=>'vendor_manager','controller'=>'payments','action'=>'payment_process'));?></div>
				
		<div class="form-row"><label><?=$this->Form->hidden('lname',array('required'=>false)); ?></label>
		</div>
		<div class="form-row paypal"><label>
		  <?php echo $this->Html->image('paypal_logo.png');?></label>
		</div>
     </div>
</div>
<div class="clear"></div> 

  
