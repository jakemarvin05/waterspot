<div class="hr-line"></div>
<div class="clear"></div>
<div class="bredcrum"><?=$this->element('breadcrumbs');?></div>
<h2 class="page-title">Vendors</h2>
<div class="middle-area">
  <div class="vender">
    <div class="vender-img"><?=$this->Html->image('vender-img.jpg');?></div>
    <div class="text-vender">Thank you for your payment of <span style="color:#007ebf;">$<?=$printrecord['Payment']['payment_amount']?></span>. This is a one time payment.</div>
    <div class="dash"><?=$this->Html->link('Go To My Dashboard',array('plugin'=>'vendor_manager','controller'=>'vendors','action'=>'dashboard'));?></div>
    <div class="clear"></div>
  </div>
  <?=$this->Html->image('shadow-dash.png',array('class'=>'mr6'));?>
</div>
