<div class="wrapper">
   <div class="ft-box-left">
   <h4>navigation</h4>
   <div class="footer-menu">
     <?=$this->Menu->footer_menu();?>
     </div>
   </div>
   <div class="ft-box-middle">
    <h4>follow us</h4>
    <div class="social">
      <ul>
        <li><a href="<?=$setting['social']['facebook']?>" target="_blank"><img src="/img/facebook.png" alt="" /></a></li>
        <li><a href="<?=$setting['social']['twitter']?>" target="_blank"><img src="/img/twitter.png" alt="" /></a></li>
        <li><a href="<?=$setting['social']['google_plus']?>" target="_blank"><img src="/img/g+.png" alt="" /></a></li>
        <li style="background:none;"><a href="<?=$setting['social']['linkedin']?>" target="_blank"><img src="/img/lincked.png" alt=""/></a></li>
       </ul>
    
    </div>
   </div>
   <div class="ft-box-right">
   <h4>Contact details</h4>
   <span class="contact"> <? if(!empty($contact_data)){
	   echo $contact_data['Page']['page_shortdescription'];
	   }?> </span>
   </div>
 </div><div class="clear"></div>
 <div class="footers">
 <div class="wrapper">
	<div class="footer-link">
	<span class="footer-copyright-info"><?=$setting['site']['copyright']?></span>
	<div class="footer-payment-logos"><img src="/img/asia-payment-footer-logos.png" alt="" style="height:30px;" /></div>
	<span class="link"><?=$this->Menu->privacy_policy();?></span>
	</div>
	</div>
</div>
