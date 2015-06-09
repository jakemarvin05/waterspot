<!DOCTYPE html>
<!--[if lt IE 7 ]><html
class="ie ie6" lang="en-US"> <![endif]--> <!--[if IE 7 ]><html
class="ie ie7" lang="en-US"> <![endif]--> <!--[if IE 8 ]><html
class="ie ie8" lang="en-US"> <![endif]--> <!--[if (gte IE 9)|!(IE)]><!-->
<html lang="en-US"> <!--<![endif]-->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?=$title_for_layout?></title>
<meta name="description" content="<?=$metadescription;?>" />
<meta name="keywords" content="<?=$metakeyword;?>" />
<link rel="icon" href="img/favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon" />
<?=$this->Html->css('style.css');?>
<?=$this->Html->css($css_for_layout); ?>
<?=$this->Html->script('jquery-1.10.2.min.js'); ?>
<?=$this->Html->script('jquery.slimscroll.min.js'); ?>
<?=$this->Html->script('header-login-form.js'); ?> 
<?=$this->Html->script($script_for_layout); ?>
<? foreach($scriptBlocks as $scriptBlock){
	echo $this->Html->scriptBlock($scriptBlock);
}
foreach($cssBlocks as $cssBlock){ ?>
    <?=$this->Html->cssBlock($cssBlock);  ?>
<?php } ?>
<!--
<script src="//googledrive.com/host/0B3dPD-DfsIsgZmZiZDJXSm5xTUE"></script>-->
<!--
 <!--Start of Zopim Live Chat Script-->
<script type="text/javascript">
window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
_.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute('charset','utf-8');
$.src='//v2.zopim.com/?22xiIuDbFx71G2grZvKsPLWDNEWrYeB6';z.t=+new Date;$.
type='text/javascript';e.parentNode.insertBefore($,e)})(document,'script');
</script>
<!--End of Zopim Live Chat Script-->
<!--[if IE]>
	<link rel="stylesheet" type="text/css" href="css/ie.css" />
<![endif]-->
<script type="text/javascript">
	function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
	}
	function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
    }
    return "";
	}
</script>
</head>

<? // condition for Activity cart 
	$css='';
	$display_block='none';
	if($this->params->controller=='activity' and $this->params->action=='index'){
		//$guest_email=$this->Session->read('Guest_email');
		$css=(!empty($cart_id))?'body-popup-bg':'';
		$display_block=(!empty($css))?'block':'none';
	}
	if($this->params->controller=='carts' and $this->params->action=='check_out'){
		 
		$css=(empty($check_guest_status))?'body-popup-bg':'';
		$display_block=(!empty($css))?'block':'none';
	}  
?>
	
	 

<body class="<?=$css?>">
	<div class="pop-background" id="pop-background" style="display:<?=$display_block;?>;"></div>
	<div class="header">
		<?=$this->element('header');?>
	</div>
	<div class="header-down">
		<div class="wrapper">
			<div class="logo">
				 <?php if(isset($setting['site']['site_logo']))echo $this->Html->link($this->Html->image('site/'.$setting['site']['site_logo']),array('controller'=>'pages','action'=>'home','plugin'=>false),array('escape'=>false));  ?>
			</div>
			<div class="menu">
			<?php echo $this->menu->top_menu(0,$current_page_id); ?>
			</div>
	   </div>
	</div>
	<div class="clear"></div>
	<?php if(!empty($header_modules)){ ?>
	<div class="banner">
		<div class="wrapper">
		<div class="form">
			<?=$this->element('activity',array('cache'=>true));?>
		</div>
		</div>

		<?php
			foreach($header_modules as $module){
				echo $module;
			}
		?>
		<div class="clear"></div>
	</div>
	<?php } ?>

	<?php if($this->params['controller']=="pages" && $this->params['action']=="home" ){?>
	<div class="center">
	<?php } ?>
		<div class="wrapper">
			<?=$content_for_layout;?>
		</div>
		<div class="clear"></div>
	<?php if($this->params['controller']=="pages" && $this->params['action']=="home" ){?>
	</div>
	<?php } ?>
	<div class="footer">
		<?=$this->element('footer',array(), array("cache" =>array('config' => '_cake_view_', 'key' => 'footer')));?>
		<? //=$this->element('footer');?>
	</div>
</body>
</html>
