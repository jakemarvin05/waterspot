<div class="hr-line"></div>
<div class="clear"></div>
	<? $name=explode(' ',$page['Page']['name']);?>
	<h2><?=$name[0]?> <span style="color:#000;"><strong> <?=strstr($page['Page']['name'], ' ')?></strong></span></h2>
   <!-- For breadcrumbs-->
   <?=$this->element('breadcrumbs');?>
  <div class="middle-area">
	<?=$page['Page']['page_longdescription'];?>
	<div class="clear"></div>
  </div>

