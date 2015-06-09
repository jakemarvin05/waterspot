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

<!-- Bootstrap: JS is at the bottom -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
<link rel="stylesheet" href="/css/bootstrap-datetimepicker.css">

<!-- Other Library style sheets -->
<link rel="stylesheet" href="/css/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" href="/css/simple-line-icons/simple-line-icons.css">

<?=$this->Html->css('style.css');?>
<link rel="stylesheet" type="text/css" href="/css/style.css" />

 <!-- Page specific style sheet -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.5/css/bootstrap-select.min.css">
<link rel="stylesheet" href="/css/index.css">

<!-- jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>


<?=$this->Html->css($css_for_layout); ?>
<?=$this->Html->script('jquery-1.10.2.min.js'); ?>
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


<!-- Page specific scripts required for early rendering -->
<script src="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.5/js/bootstrap-select.min.js"></script>
<script src="/js/page-specifics/index.js"></script>

<!--End of Zopim Live Chat Script-->
<!--[if IE]>
	<link rel="stylesheet" type="text/css" href="css/ie.css" />
<![endif]-->

    <!-- FaceBook Opengraph -->
    <meta property="og:image" content="">
    <meta property="og:site_name" content="Waterspot">
    <meta property="og:type" content="website">

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

  <section id="navWrapper">
        <?=$this->element('header');?>
    </section>

	<?php if($this->params['controller']=="pages" && $this->params['action']=="home" ){?>
	<section id="splashVideoCont">
                <div id="splashVideoCropper">
                    <video autoplay loop muted poster="/img/splash-statics/slide1.jpg">
                      <source src="/media/watersports.mp4" type="video/mp4">
                      <img src="/img/splash-statics/slide1.jpg">
                    </video>
                    <img src="/img/splash-statics/slide1.jpg">
                </div>
                <div id="videoOverlayWrapper">
                    <div id="searchOuterWrapper">
                        <div id="searchWrapper">
                            <div id="searchContainer">
                                <div id="searchBackground"></div>

                                <div class="searchInline" id="activityListWrap">
                                    <select class="selectpicker" multiple title="What activity are you game for?" data-selected-text-format="count>2">
                                        <option>Wakesurfing</option>
                                        <option>Kayaking</option>
                                        <option>Sailing</option>
                                        <option>Windsurfing</option>
                                        <option>Kitesurfing</option>
                                        <option>Stand Up Paddle</option>
                                        <option>Diving</option>
                                        <option>Wakeboarding</option>
                                        <option>Fishing</option>
                                        <option>Berth Side Party</option>
                                        <option>Chartering</option>
                                    </select>


                                    <script>
                                    // init the selectpicker
                                    $('.selectpicker').selectpicker();

                                    // bind selection to toggling of text colors on the select
                                    // so that the placeholder color is maintained
                                    $(function() {
                                        var $filterOption = $('#activityListWrap .filter-option');
                                        var selectPlaceholder = $('#activityListWrap select').attr('title');

                                        $('ul.dropdown-menu>li>a').on('click', function() {
                                            setTimeout(function() {
                                                if ($filterOption.html() === selectPlaceholder) $filterOption.css('color', '#ccc');
                                                else $filterOption.css('color', '#606060');
                                            },0);
                                        });

                                    });
                                    </script>

                                </div>

                                <div class="searchInline">
                                    <input id="searchDate" type='text' class="form-control" placeholder="On which date?">

                                    <script>
                                      /*  $(function () {
                                            $('#searchDate').datetimepicker({
                                                format: 'DD-MMM-YYYY'
                                            }).on('dp.hide', function() {
                                                $(this).blur();
                                            }).on('keydown', function(e) {
                                                e.preventDefault();
                                            });
                                        }); */
                                    </script>

                                </div>

                                <div class="searchInline">
                                    <button id="startYourAdventure" class="btn btnDefaults btnFillOrange" type="button">START YOUR ADVENTURE</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

	<?php } ?>

		<?=$content_for_layout;?>

		<div class="clear"></div>

	<div class="footer">
		<?=$this->element('footer',array(), array("cache" =>array('config' => '_cake_view_', 'key' => 'footer')));?>
		<? //=$this->element('footer');?>
	</div>

	  <script>
        var frontPageActivities = activitiesBlockMaker.init($('#activitiesContainer'), [
            { title: 'wakeboarding', imagePath: '/img/activities/wakeboarding.jpg', blockSize: '2x1'},
            { title: 'diving', imagePath: '/img/activities/diving.jpg'},
            { title: 'kayaking', imagePath: '/img/activities/kayaking.jpg'},
            { title: 'fishing', imagePath: '/img/activities/fishing.jpg'},
            { title: 'kitesurfing', imagePath: '/img/activities/kitesurfing.jpg'},
            { title: 'boatcharter', imagePath: '/img/activities/boatcharter.jpg', blockSize: '2x2'},
            { title: 'sailing', imagePath: '/img/activities/sailing.jpg'},
            { title: 'stand up paddle', imagePath: '/img/activities/stand-up-paddle.jpg'}
        ]);
        </script>

        <!-- Javascripts -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.3/moment.min.js"></script>
        <script src="/js/velocity/velocity.min.js"></script>
        <script src="/js/velocity/velocity.ui.min.js"></script>
        <script src="/js/lib.js"></script>

</body>
</html>
