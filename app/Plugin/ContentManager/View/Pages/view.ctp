<div class="container-fluid member-panel">
<div class="hr-line"></div>
<div class="clear" style="margin-top:80px;"></div>

      <? $name=explode(' ',$page['Page']['name']);?>
      <h2 class="page-title edit"><?=$name[0]?> <?=strstr($page['Page']['name'], ' ')?></h2>
      <div class="middle-area edit">
      <div class="cont">
	    <?php if($page['Page']['id']=='6') { ?>
		  <?=$this->element('contact');?>
	    <?php } else { ?>
		  <?=$page['Page']['page_longdescription'];?>
	    <?php } ?>
	    <div class="clear"></div>
      </div>
      </div>
<br>

</div>
