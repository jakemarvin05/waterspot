<!doctype html>
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html lang="en" class="no-js"> 
    <!--<![endif]-->
    <head>
        <meta charset="UTF-8">
        <title><?=$title_for_layout?></title>
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link href="<?php echo Configure::read('HTTP_PATH'); ?>/img/favicon.ico" type="image/x-icon" rel="shortcut icon" />
        <link href="<?php echo Configure::read('HTTP_PATH'); ?>/img/favicon.ico" type="image/x-icon" rel="icon" />
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <!-- CSS Styles -->
        <?php echo $this->Html->css('admin/style.css'); ?>
        <?php echo $this->Html->css('admin/colors.css'); ?>
        <?php echo $this->Html->css('admin/jquery.tipsy.css'); ?>


        <!-- Google WebFonts -->
        <link href='http://fonts.googleapis.com/css?family=PT+Sans:regular,italic,bold,bolditalic' rel='stylesheet' type='text/css'>
        <?php echo $this->Html->script('admin/libs/modernizr-1.7.min.js'); ?>


    </head>
    <body class="login">
        <section role="main">

            <?php //if($ADMIN_DETAIL['siteimage'])echo $html->image('site_config/'.$ADMIN_DETAIL['siteimage'],array('style'=>'width:225px; height:65px'));?>


            <!-- Login box -->
            <article id="login-box">

                <div class="article-container">	
                    
                    

                    <!-- Notification -->
                     <?php //print_r($_SESSION); ?>
                    <?php if ($this->Session->check('Message.auth')): ?>
                      
                        <div class="notification error">
                            <a href="#" class="close-notification" title="Hide Notification" rel="tooltip">x</a>
                            <p><strong>Error notification</strong>
                                <?php echo $this->Session->flash('auth'); ?>
                            </p>
                        </div>
                    <?php endif; ?>
                    <!-- /Notification -->
                    <h1>ilchiostro! Administrator Login</h1>
                    <?= $this->AdminForm->create('User', array('url' =>'login')); ?>
                    <fieldset>
                        <dl>
                            <?php echo $this->AdminForm->input('username',array('required'=>false)); ?>
                            <?php echo $this->AdminForm->input('password',array('type'=>'password','required'=>false)); ?>
                            
                            
                        </dl>
                    </fieldset>
                    <button type="submit" class="right">Log in</button>					
                    </form>

                </div>

            </article>
            <!-- /Login box -->
            <ul class="login-links">
                <li><?php echo $this->Html->link('Lost password?', array('controller' => 'users', 'action' => 'forgotpassword'), array('class' => 'leftnav')); ?></li>
                <li><?php echo $this->Html->link('Return to Site Home Page', array('controller' => 'pages', 'action' => 'home'), array('class' => '', 'target' => '_blank')); ?></li>
            </ul>

        </section>

        <!-- JS Libs at the end for faster loading -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
        <script>!window.jQuery && document.write(unescape('%3Cscript src="js/jquery/jquery-1.5.1.min.js"%3E%3C/script%3E'))</script>
        <?php echo $this->Html->script('admin/libs/selectivizr.js'); ?>
        <?php echo $this->Html->script('admin/jquery/jquery.tipsy.js'); ?>
        <?php echo $this->Html->script('admin/login.js'); ?>
        <?php echo $this->element('sql_dump'); ?>

    </body>
</html>