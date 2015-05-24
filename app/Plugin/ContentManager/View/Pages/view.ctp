<div class="hr-line"></div>
<div class="clear"></div>
      <!-- For breadcrumbs-->
      <?=$this->element('breadcrumbs');?>
      <? $name=explode(' ',$page['Page']['name']);?>
      <h2 class="page-title"><?=$name[0]?> <span style="color:#000;"><strong> <?=strstr($page['Page']['name'], ' ')?></strong></span></h2>
      <div class="middle-area">
	    <?php if($page['Page']['id']=='6') { ?>
		  <?=$this->element('contact');?>
	    <?php } else { ?>
		  <?=$page['Page']['page_longdescription'];?>
	    <?php } ?>
	    <div class="clear"></div>
      </div>
