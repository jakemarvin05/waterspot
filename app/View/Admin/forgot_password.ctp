<!doctype html>
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
<head>
	<meta charset="UTF-8">
	<title><?php echo $title_for_layout; ?></title>
	<meta name="description" content="">
	<meta name="author" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<link href="<?php echo Configure::read('HTTP_PATH');?>/favicon.ico" type="image/x-icon" rel="shortcut icon" />
	<link rel="apple-touch-icon" href="/apple-touch-icon.png">
	
	<!-- CSS Styles -->
	<?php echo $this->Html->css('admin/style.css');?>
	<?php echo $this->Html->css('admin/colors.css');?>
	<?php echo $this->Html->css('admin/jquery.tipsy.css');?>

	
	<!-- Google WebFonts -->
	<link href='http://fonts.googleapis.com/css?family=PT+Sans:regular,italic,bold,bolditalic' rel='stylesheet' type='text/css'>
	<?php echo $this->Html->script('admin/libs/modernizr-1.7.min.js'); ?>


</head>
<body class="login">
	<section role="main">
	
		
       			 <?php if(isset($site_setting['site_logo']))echo $this->Html->image('site/'.$site_setting['site_logo'],array('style'=>''));  ?>			
       
	
		<!-- Login box -->
		<article id="login-box">
		
			<div class="article-container">				
				
				              
				<h1>Forgot Password</h1>
				<?php echo $this->element('admin/message');?>
				
				<?php echo $this->Form->create('Admin', array('action' => 'forgot_password'));?>
					<fieldset>
						<dl>
							<dt>
								<label>E-Mail</label>
							</dt>
							<dd>
								<?php echo $this->Form->text('email', array('class' => 'fullwidth','size'=>'30')); ?>
							</dd>
							
						</dl>
					</fieldset>
					<button type="submit" class="right">Submit</button>
					<?php echo $this->Form->end(); ?>			
			</div>
		
		</article>
		<!-- /Login box -->
		<ul class="login-links">
			<li>
            <?php echo $this->Html->link('Return to Site Home Page', array('plugin'=>'content_manager', 'controller' => 'pages', 'action' => 'home'), array('class' => '', 'target' => '_blank')); ?>
            </li>
			<li><?php echo $this->Html->link('Login', array('controller'=>'admin','action'=>'index'), array('class'=>'leftnav'));?>
</li>
		</ul>
		
	</section>

	
</body>
</html>
