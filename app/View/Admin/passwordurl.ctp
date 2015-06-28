<!doctype html>
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
<head>
	<meta charset="UTF-8">
	<title><?php echo $title_for_layout; ?> </title>
	<meta name="description" content="">
	<meta name="author" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<link href="<?= $this->Html->webroot.'img/site/'.$setting['site']['site_icon'];?>" type="image/x-icon" rel="shortcut icon" />
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
	
		<?//=$this->Html->image('admin-logo.jpg'); ?>	
		<?php if(isset($setting['site']['site_logo']))echo $this->Html->image('site/'.$setting['site']['site_logo'],array('style'=>''));  ?>
		<!-- Login box -->
		<article id="login-box">
		
			<div class="article-container">				
				<?php echo $this->element('admin/message');?>
				<?php echo $this->Form->create('User', array('name' => 'user','url' => array('controller'=>'admin','action'=>'passwordurl/'.$str),'onSubmit'=>'//return validatefields()'));?>
			<fieldset>
					<?=$this->Form->hidden('form-name',array('required'=>false,'value'=>'ResetRegistrationPasswordForm')); ?>
							
					<dt style="width:120px"><label>New Password <span style="color:red;">*</span></label></dt>
						<dd style="left:130px"><?php echo $this->Form->password('password', array('class'=>'mediaum','size' => 20,'required'=>false)); ?>
						<div id="password"></div>
							<?=$this->Form->error('password',null,array('wrap' => 'span', 'class' => 'error-message')); ?>
						</dd>
					<dt style="width:120px"><label>Confirm Password <span style="color:red;">*</span></label></dt>
						<dd style="left:130px"><?php echo $this->Form->password('password2', array('class'=>'mediaum','size' => 20,'required'=>false)); ?>
						<?=$this->Form->error('password2',null,array('wrap' => 'span', 'class' => 'error-message')); ?>
						<div id="password2"></div>
						</dd>
			</fieldset>
				<button type="submit">Save Password</button>
					<?php echo $this->Form->end();?>		
			</div>
		
		</article>
		<!-- /Login box -->
		
		
	</section>

	<!-- JS Libs at the end for faster loading -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
	<script>!window.jQuery && document.write(unescape('%3Cscript src="js/jquery/jquery-1.5.1.min.js"%3E%3C/script%3E'))</script>
	<?php echo $this->Html->script('admin/libs/selectivizr.js'); ?>
	<?php echo $this->Html->script('admin/jquery/jquery.tipsy.js'); ?>
	<?php echo $this->Html->script('admin/login.js'); ?>
	<script>
		var _gaq=[['_setAccount','UA-XXXXXX'],['_trackPageview']];
		(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];g.async=1;
		g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
		s.parentNode.insertBefore(g,s)}(document,'script'));
	</script>
</body>
</html>

<script type="text/javascript">
	<?php $path = $this->Html->webroot; ?>
    $(document).ready(function(){
		$('#UserPasswordurlForm').submit(function(){
			
			var data = $(this).serializeArray();
            var formData = $(this);
            var status = 0;
           
            $.each(this,function(i,v){
                $(v).removeClass('form-error');
                });
            $('.error-message').remove();
            $('#UserPasswordurlForm > span#for_owner_cms').show();
            $('#UserPasswordurlForm > button[type=submit]').attr({'disabled':true});
           
			$.ajax({
                url: '<?=$path?>subadmin_manager/users/validation',
                async: false,
				data: data,
                dataType:'json', 
                type:'post',
                success: function(data) {
                    if(data.error==1){
						 
                        $.each(data.errors,function(i,v){
							if(i=="UserPassword"){
								i="password";
							}
							if(i=="UserPassword2"){
								i="password2";
							}
							$('#'+i).addClass("form-error").after('<span class="error-message">'+v+'</span>');
                            $('#'+i).bind('click',function(){
								$(this).removeClass('invalid form-error');
								$(this).next().remove();
								});
                        });
                       
                    }else{
                        status = 1;
                    }
                   
                }

            });
            if(status==0){
               $("html, body").animate({ scrollTop: 0 }, "slow");
               $('#UserPasswordurlForm > button[type=submit]').attr({'disabled':false});
               $('#UserPasswordurlForm > span#for_owner_cms').hide();
            }
           return (status===1)?true:false; 
            
        });
        
        
    });
 </script>

