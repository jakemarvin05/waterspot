<!doctype html>
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> 
<html lang="en" class="no-js"> <!--<![endif]-->
<head>
	<title><?=$title_for_layout; ?> | Admin Panel</title>
	<link href="<?= $this->Html->webroot.'img/site/'.$setting['site']['site_icon'];?>" type="image/x-icon" rel="shortcut icon" />
	<meta charset="UTF-8">
	<meta name="description" content="">
	<meta name="author" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<link rel="apple-touch-icon" href="/apple-touch-icon.png">
	<?php echo $this->Html->script('ckeditor/ckeditor.js');?>
	<?php echo $this->Html->script('ckfinder/ckfinder.js'); ?>
	<script src= 
	"//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js" 
	></script>
	
	 <script type='text/javascript' src= 
	'https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.m
	in.js'></script> 
	
	<link rel="stylesheet" type="text/css" href= 
	"http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/themes/base
	/jquery-ui.css" />
	
	<?php echo $this->Html->script('fancybox/jquery.fancybox-1.3.4.pack.js'); ?>
	
	<!-- CSS Styles -->
	<?php echo $this->Html->css('admin/style.css');?>
	<?php echo $this->Html->css('admin/colors.css');?>
	<?php //echo $this->Html->css('admin/jquery.tipsy.css');?>
	<?php //echo $this->Html->css('admin/jquery.wysiwyg.css');?>
	<?php //echo $this->Html->css('admin/jquery.datatables.css');?>
	<?php //echo $this->Html->css('admin/jquery.nyromodal.css');?>
	<?php //echo $this->Html->css('admin/jquery.datepicker.css');?>
	<?php echo $this->Html->css('admin/jquery.fileinput.css');?>
	<?php echo $this->Html->css('admin/jquery.fullcalendar.css');?>
	<?php //echo $this->Html->css('admin/jquery.visualize.css');?>
    <?php //echo $this->Html->css('admin/demo.css');?>
    <?php // echo $this->Html->css('../js/jscalendar/skins/aqua/theme');?>
    <?php //echo $this->Html->script('jquery.js'); ?>
    <?php //echo $this->Html->script('common.js'); ?>
	<?php //echo $this->Html->script('admin/managecontent.js'); ?>
    <?php //echo $this->Html->script('jscalendar/calendar.js'); ?>
    <?php //echo $this->Html->script('jscalendar/lang/calendar-en.js'); ?>
     <?php echo $this->Html->css('fancybox/jquery.fancybox-1.3.4.css');?>

	<!-- Google WebFonts -->
	<link href='http://fonts.googleapis.com/css?family=PT+Sans:regular,italic,bold,bolditalic' rel='stylesheet' type='text/css'>
	<?php //echo $this->Html->script('admin/libs/modernizr-1.7.min.js'); ?>
    <script type="text/javascript">
		$(document).ready(function(){
			$('.fancybox').fancybox();
			});
	</script>
	
        <script src="/js/bootstrap/js/bootstrap.min.js"></script>
        <script src="/js/moment/min/moment.min.js"></script>
        <script src="/js/velocity/velocity.min.js"></script>
        <script src="/js/velocity/velocity.ui.min.js"></script>
        <script src="/js/iaStickySidebar.js"></script>
        <script src="/js/lib.js"></script>


</head>

<!-- Add class .fixed for fixed layout. You would need also edit CSS file for width -->
<body>

	<div class="fixed-wraper">

	<!-- Aside Block -->
	<section role="navigation">
		<!-- Header with logo and headline -->
		<header>
			 <?php if(isset($setting['site']['site_logo']))echo $this->Html->image('logo1.png',array('style'=>'width:220px;'));  ?>			
		</header>
		
		<!-- User Info -->
			<section id="user-info">				
				<?=$this->Html->image('admin/sample_user.png');?>
				<div>
					<a href="#" title="Account Settings and Profile Page"><?php if(isset($loggedIn)): ?><? echo ucfirst($ADMIN_DETAIL['adminname']); ?><?php endif; ?></a>
		       
					<em>Hello Administrator</em>                
			
					<ul>
						<li><?php echo $this->Html->link('view website', '/',array('class'=>'button-link','rel'=>'tooltip','title'=>'view website','target'=>'_blank')); ?>
			    </li>
						<li><?php echo $this->Html->link('Logout', '/admin/logout', array('class'=>'button-link','rel'=>'tooltip','title'=>'Logout'));?>
						</li>
					</ul>
				</div>
			</section>
		<!-- /User Info -->
		
		<!-- Main Navigation -->
		<nav id="main-nav">
			<ul>
				<li class="<?php if($this->params['controller']=="users" && $this->params['action']=="admin_welcome") echo "current"; ?>">
				<?php echo $this->Html->link('Dashboard','/admin/home' ,array('class'=>'dashboard no-submenu'));?>
				</li>
				
				<li class="<?php if(($this->params['plugin']=="content_manager"))
					echo "current"; ?>"><?php if($this->params['controller']=="pages") ?><?php echo $this->Html->link('Content Manager','/admin/content_manager/pages/index',array('class'=>'content no-submenu') );?>
				</li>
				
				
				<?//php// if((isset($ADMIN_PERMISSIONS['SlidesController']) && $ADMIN_PERMISSIONS['SlidesController']) || $USERS['User']['role']=='admin'){ ?>

				<li class="<?php if(($this->params['plugin']=="slide_manager"))
					echo "current"; ?>"><a href="" title="" class="slider">Slide Manager</a>
					<ul>
						<li <?php if($this->params['controller']=="slides") echo "class=\"current\""; ?> ><?php echo $this->Html->link('Manage Slide','/admin/slide_manager/slides/index' );?></li>
					</ul>
				</li>
				
				<li class="<?php if(($this->params['plugin']=="location_manager"))
					echo "current"; ?>"><a href="" title="" class="location">Location Manager</a>
					<ul>
						<li <?php if($this->params['controller']=="countries") echo "class=\"current\""; ?> ><?php echo $this->Html->link('Manage Country','/admin/location_manager/countries/index' );?></li>
						
					</ul>
				</li>
				<li class="<?php if(($this->params['plugin']=="service_manager"))
					echo "current"; ?>"><a href="" title="" class="service">Service Manager</a>
					<ul>
						<li <?php if($this->params['controller']=="services") echo "class=\"current\""; ?> ><?php echo $this->Html->link('Manage Service Type','/admin/service_manager/service_types' );?>
						</li>
					</ul>
				</li>
				<?//php //} ?>
				
				<li class="<?php if(($this->params['plugin']=="vendor_manager"))
					echo "current"; ?>"><a href="" title="" class="vendor">Vendor Manager</a>
					<ul>
						<li <?php if($this->params['action']=="admin_index" || $this->params['action']=="admin_servicelist" || $this->params['action']=="admin_add_services" || $this->params['action']=="admin_add_service_slots") echo "class=\"current\""; ?> ><?php echo $this->Html->link('Manage Vendor','/admin/vendor_manager/vendors/index' );?>
						</li>
						<li <?php if($this->params['action']=="admin_vendorpayment") echo "class=\"current\""; ?> ><?php echo $this->Html->link('Vendor Payment List','/admin/vendor_manager/vendors/vendorpayment' );?>
						</li>
						<li <?php if($this->params['action']=="admin_reviews") echo "class=\"current\""; ?> ><?php echo $this->Html->link('Service Review List',array('plugin'=>'vendor_manager','controller'=>'service_reviews','action'=>'admin_reviews'));?>
						</li>
						 

					</ul>
				</li>
				
				<li class="<?php if($this->params['controller']=="bookings")
					echo "current"; ?>"><a href="" title="" class="booking">Booking Manager</a>
					<ul>
						<li <?php if($this->params['controller']=="bookings") echo "class=\"current\""; ?> ><?php echo $this->Html->link('Manage Booking','/admin/bookings/index' );?>
						</li>
						
					</ul>
				</li>
				<li class="<?php if($this->params['controller']=="carts")
					echo "current"; ?>"><a href="" title="" class="Abandonedcart">Abandoned Cart Manager</a>
					<ul>
						<li <?php if($this->params['controller']=="carts") echo "class=\"current\""; ?> ><?php echo $this->Html->link('Abandoned cart Manager','/admin/carts/abandon_cart' );?>
						</li>
						
					</ul>
				</li>
				<li class="<?php if(($this->params['plugin']=="member_manager"))
					echo "current"; ?>"><a href="" title="" class="member">Member Manager</a>
					<ul>
						<li <?php if($this->params['controller']=="members") echo "class=\"current\""; ?> ><?php echo $this->Html->link('Manage Member','/admin/member_manager/members/index');?>
						</li>
					</ul>
				</li>
				<li class="<?php if(($this->params['plugin']=="subadmin_manager"))
					echo "current"; ?>"><a href="" title="" class="sub_admin">Sub Admin Manager</a>
					<ul>
						<li <?php if($this->params['controller']=="users") echo "class=\"current\""; ?> ><?php echo $this->Html->link('Add Sub Admin','/admin/subadmin_manager/users/add' );?>
						</li>
						<li <?php if($this->params['controller']=="users") echo "class=\"current\""; ?> ><?php echo $this->Html->link('Manage Sub Admin','/admin/subadmin_manager/users/index' );?>
						</li>
						
					</ul>
				</li>
				<!--
				<li class="<?php if($this->params['controller']=="messages") echo "current"; ?> ">
				<?php echo $this->Html->link('Message Manager', array('controller'=>'messages', 'action' => 'index'),array('class'=>'massage no-submenu'));?>
					
				</li>
				-->
				<li class="<?php if(($this->params['plugin']=="mail_manager"))
					echo "current"; ?>"><a href="" title="" class="mail">Mail Format Manager</a>
					<ul>
						<li <?php if($this->params['controller']=="mails") echo "class=\"current\""; ?> ><?php echo $this->Html->link('Manage Mail Manger','/admin/mail_manager/mails/index' );?></li>
						
					</ul>
				</li>
				
				
				<li class="<?php if(($this->params['controller']=="settings"))
					echo "current"; ?>"><a href="" title="" class="settings">Settings</a>
					<ul>
						<li <?php if($this->params['action']=="admin_site") echo "class=\"current\""; ?> ><?php echo $this->Html->link('Site Configuration','/admin/settings/site' );?></li>
						
						<li class="<?php if($this->params['action']=="admin_changepassword") echo "current"; ?>"><?php echo $this->Html->link('Change Password', '/admin/settings/changepassword' );?></li>

						<li class="<?php if($this->params['controller']=="adminprofiles") echo "current"; ?>"><?php echo $this->Html->link('Change Profile', '/admin/settings/adminprofile' );?></li>
						<li class="<?php if($this->params['action']=="socialmedia") echo "current"; ?>"><?php echo $this->Html->link('Social Media Link', '/admin/settings/social' );?></li>
						<!--
						<li class="<?php if($this->params['action']=="fees") echo "current"; ?>"><?php echo $this->Html->link('Sales Commision Amount', '/admin/vendor_manager/settings/fees' );?></li> --!>
						
						<!--<li class="<?php // if($this->params['action']=="paypalsetting") echo "current"; ?>"><?php //echo $this->Html->link('Manage Paypal Setting', '/admin/settings/paypalsetting' );?></li>-->
						<li class="<?php if($this->params['action']=="allcachedelete") echo "current"; ?>"><?php echo $this->Html->link('Clear All Cache Memory', '/admin/settings/allcachedelete' );?></li>
						<li class="<?php if($this->params['action']=="allcachedelete") echo "current"; ?>"><?php echo $this->Html->link('Clear All Service Image', '/admin/settings/allserviceimagedelete' );?></li>
					</ul>
				</li> 
				
			</ul>
		</nav>
		<!-- /Main Navigation -->
		
	</section>
	<!-- /Aside Block -->
	
	<!-- Main Content -->
	<section role="main">
		<!--
		<section id="widgets-container">
			<div id="new-tasks" class="widget increase">
			<span>2</span>
			<p>
			<strong>New Vendors</strong>
			+2 waiting for approval
			</p>
			</div>
		</section>
		-->
		<?php echo $this->element('admin/breadcrumbs'); ?>
		<?php echo $content_for_layout; ?>
        
     </section> 
	<!-- /Fixed Layout Wrapper -->

	<!-- JS Libs at the end for faster loading -->
	
	

<!--
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
-->

	
	<?php echo $this->Html->script('admin/libs/selectivizr.js'); ?>
	<?php //echo $this->Html->script('admin/jquery/jquery.nyromodal.js'); ?>
	<?php echo $this->Html->script('admin/jquery/jquery.tipsy.js'); ?>
	<?php //echo $this->Html->script('admin/jquery/jquery.wysiwyg.js'); ?>
	<?php //echo $this->Html->script('admin/jquery/jquery.datatables.js'); ?>
	<?php //echo $this->Html->script('admin/jquery/jquery.datepicker.js'); ?>
	<?php echo $this->Html->script('admin/jquery/jquery.fileinput.js'); ?>
	<?php echo $this->Html->script('admin/jquery/jquery.fullcalendar.min.js'); ?>
	<?php echo $this->Html->script('admin/jquery/excanvas.js'); ?>
	<?php //echo $this->Html->script('admin/jquery/jquery.visualize.js'); ?>
	<?php //echo $this->Html->script('admin/jquery/jquery.visualize.tooltip.js'); ?>
	<?php echo $this->Html->script('admin/script.js'); ?>
	<?php //echo $this->element('sql_dump'); ?>
</body>
</html>
<?php //echo $this->element('sql_dump'); ?>
