  <div class="hr-line"></div>
  <div class="clear"></div>
  <div class="bredcrum"><?=$this->element('breadcrumbs');?></div>
  <h2 class="page-title">Vendors</h2>
 <div class="middle-area">
   <div class="vender">
   <div class="vender-img"><?=$this->Html->image('vender-img.jpg');?></div>
   <div class="vender-hd">Welcome <?=$vendorinfo['Vendor']['fname'];?>,</div>
   <div class="text-vender">Thank you for joining SG Water Sports. Please complete you dues to post your services.</div>
    <div class="pay-now"><?=$this->Html->link('Pay Now',array('plugin'=>'vendor_manager','controller'=>'payments','action'=>'make_payment'));?></div>
    <div class="clear"></div>
   </div>
   <?=$this->Html->image('shadow-dash.png',array('class'=>'mr6'));?>    
   
 </div>
