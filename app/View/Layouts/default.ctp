<!DOCTYPE html>
<!--[if lt IE 7 ]><html
class="ie ie6" lang="en-US"> <![endif]--> <!--[if IE 7 ]><html
class="ie ie7" lang="en-US"> <![endif]--> <!--[if IE 8 ]><html
class="ie ie8" lang="en-US"> <![endif]--> <!--[if (gte IE 9)|!(IE)]><!-->
<html lang="en-US"> <!--<![endif]-->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<!-- FACEBOOK -->
<meta property="og:title" content="<?php echo  (isset($web_title) ? $web_title : 'Waterspot'); ?>">
<meta property="og:type" content="<?php echo (isset($web_type) ? $web_type : 'website'); ?>">
<meta property="og:url" content="<?php echo (isset($web_url) ? $web_url : 'http://128.199.214.85'); ?>">
<? $imgArr = array('source_path'=>Configure::read('Image.SourcePath'),'img_name'=>(isset($web_image['image'])?$web_image['image']:''),'width'=>600,'height'=>400,'noimg'=>$setting['site']['site_noimage']);
$resizedImg = 'http://waterspot.local/img/'.$this->ImageResize->ResizeImage($imgArr);
?>
<meta property="og:image" content="<?php echo isset($web_image) ? $resizedImg : 'http://128.199.214.85/img/logo-colored.png'; ?>">
<meta property="og:site_name" content="<?php echo isset($web_site_name) ? $web_site_name : 'Waterspot'; ?>">
<!-- FACEBOOK -->
<title><?=$title_for_layout?></title>
<meta name="description" content="<?=$metadescription;?>" />
<meta name="viewport" content="initial-scale=1">
<meta name="keywords" content="<?=$metakeyword;?>" />
<link rel="icon" href="img/favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon" />


<!-- Bootstrap: JS is at the bottom -->
<link rel="stylesheet" href="/js/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="/css/bootstrap-datetimepicker.css">

<!-- Other Library style sheets -->
<link rel="stylesheet" href="/css/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" href="/css/simple-line-icons/simple-line-icons.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.5/css/bootstrap-select.min.css">

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

<!-- jQuery -->
<script src="/js/jquery-1.11.1/jquery.min.js"></script>

<!-- Page specific scripts required for early rendering -->
<script src="/js/bootstrap-select/js/bootstrap-select.min.js"></script>
<script src="/js/page-specifics/index.js"></script>
<script src="/js/app.js"></script>

<?=$this->Html->css('style.css');?>

<!-- Page specific style sheet -->
<link rel="stylesheet" href="/css/index.css">
<?=$this->Html->css($css_for_layout); ?>

<?=$this->Html->css('mobile.css');?>

<?=$this->Html->script($script_for_layout); ?>
<? 
foreach($scriptBlocks as $scriptBlock) {
	  echo $this->Html->scriptBlock($scriptBlock);
}
foreach($cssBlocks as $cssBlock) {
    echo $this->Html->cssBlock($cssBlock);
} 
?>

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

<style type="text/css">
  div.star-rating, div.star-rating a {
    background:url('/jQuery.Plugins/ratings/rating-water-spot.png') no-repeat 0 0px;
  }
</style>
	 

<body class="<?=$css?>">


  <section id="navWrapper" class="<?php if($this->params['controller']!="pages"){
      echo "stickyCollapsed stickyCollapsedFix";
  }
  else{
      if($this->params['action']!="home" ) {
          echo "stickyCollapsed stickyCollapsedFix";
      }
  }?>">
        <?=$this->element('header');?>
    </section>

	<?php if($this->params['controller']=="pages" && $this->params['action']=="home" ){?>
	 <section id="splashVideoCont">
            <div id="splashVideoCropper">
                <video autoplay loop muted poster="img/splash-statics/slide1.jpg">
                  <source src="media/watersports.mp4" type="video/mp4">
                  <img src="img/splash-statics/slide1.jpg">
                </video>
                <img src="img/splash-statics/slide1.jpg">
            </div>
            <div id="videoOverlayWrapper">
                <div id="searchOuterWrapper">
                    <div id="searchWrapper">
                    <form id="search" method="get" action="/search/index">
                        <div id="searchContainer">
                            <div id="searchBackground"></div>

                            <div class="searchInline" id="activityListWrap">
                                <select name="activity" class="selectpicker" title="What activity are you game for?" data-selected-text-format="count>2">
                                    <?php
                                        foreach($service_type_list as $key => $name) {
                                            echo "<option value='$key'>$name</option>";
                                        }
                                    ?>
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
                                <script type="text/javascript">
                                    $(document).ready(function(){

                                        $('form#search').submit(function(e){
                                                e.preventDefault();
                                                var form = $(this);
                                                var data = form.serializeArray();
                                                var date = new Date(data[1].value);
                                                var dateStamp = date.getTime() / 1000;
                                                var serviceId = data[0].value;
                                                var url = form.attr('action');
                                                console.log(url);
                                                url+='/'+serviceId+'/'+dateStamp;
                                                console.log(url);
                                                window.location.href=url;
                                            }
                                        );
                                    });
                                </script>

                            </div>

                            <div class="searchInline">
                                <input id="searchDate" name="date" type='text' class="form-control" placeholder="On which date?">

                                <script>
                                    $(function () {
                                        $('#searchDate').datetimepicker({
                                            format: 'DD-MMM-YYYY'
                                        }).on('dp.hide', function() {
                                            $(this).blur();
                                        }).on('keydown', function(e) {
                                            e.preventDefault();
                                        });
                                    });
                                </script>

                            </div>

                            <div class="searchInline">
                                <button id="startYourAdventure" class="btn btnDefaults btnFillOrange" type="submit">START YOUR ADVENTURE</button>
                            </div>
                        </div>
                    </form>
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
            { title: 'wakeboarding',urlPath:'/service-type-details/32', imagePath: '/img/activities/wakeboarding.jpg', blockSize: '2x1'},
            { title: 'diving',urlPath:'/service-type-details/27', imagePath: '/img/activities/diving.jpg'},
            { title: 'kayaking',urlPath:'/service-type-details/41', imagePath: '/img/activities/kayaking.jpg'},
            { title: 'fishing',urlPath:'/service-type-details/25', imagePath: '/img/activities/fishing.jpg'},
            { title: 'kitesurfing',urlPath:'/service-type-details/42', imagePath: '/img/activities/kitesurfing.jpg'},
            { title: 'boatcharter',urlPath:'/service-type-details/26', imagePath: '/img/activities/boatcharter.jpg', blockSize: '2x2'},
            { title: 'sailing',urlPath:'/service-type-details/44', imagePath: '/img/activities/sailing.jpg'},
            { title: 'stand up paddle',urlPath:'/service-type-details/39', imagePath: '/img/activities/stand-up-paddle.jpg'}
        ]);

        </script>


  <script type="text/javascript">
      <?php $path = $this->Html->webroot; ?>

      $(document).ready(function(){
          $('#VendorsLogin').submit(function(){

              //var data = $(this).serializeArray();
              var data = new FormData(this);
              var formData = $(this);
              var status = 0;

              $.each(this,function(i,v){
                  $(v).removeClass('invalid form-error');
              });
              $('.error-message').remove();
              $('#VendorLogin > span#for_owner_cms').show();
              $('#VendorLogin > button[type=submit]').attr({'disabled':true});

              $.ajax({
                  url: '<?=$path?>vendor_manager/vendors/validation/login',
                  async: false,
                  data: data,
                  dataType:'json',
                  type:'post',
                  cache: false,
                  contentType: false,
                  processData: false,
                  success: function(data) {

                      if(data.error==1){
                          $.each(data.errors,function(i,v){
                              $('#'+i).addClass("invalid form-error").after('<div class="error-message">'+v+'</div>');
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
                  $('#VendorLogin > button[type=submit]').attr({'disabled':false});
                  $('#VendorLogin > span#for_owner_cms').hide();
              }

              return (status===1)?true:false;

          });

          $('#VendorRegistration').submit(function(){

              //var data = $(this).serializeArray();
              var data = new FormData(this);
              var formData = $(this);
              var status = 0;

              $.each(this,function(i,v){
                  $(v).removeClass('invalid form-error');
              });
              $('.error-message').remove();
              $('#VendorRegistration > span#for_owner_cms').show();
              $('#VendorRegistration > button[type=submit]').attr({'disabled':true});

              $.ajax({
                  url: '<?=$path?>vendor_manager/vendors/validation',
                  async: false,
                  data: data,
                  dataType:'json',
                  type:'post',
                  cache: false,
                  contentType: false,
                  processData: false,
                  success: function(data) {

                      if(data.error==1){
                          $.each(data.errors,function(i,v){
                              $('#'+i).addClass("invalid form-error").after('<div class="error-message">'+v+'</div>');
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
                  $('#VendorRegistration > button[type=submit]').attr({'disabled':false});
                  $('#VendorRegistration > span#for_owner_cms').hide();
              }
              return (status===1)?true:false;

          });



      });
  </script>

        <!-- Javascripts -->
        <script src="/js/bootstrap/js/bootstrap.min.js"></script>
        <script src="/js/moment/min/moment.min.js"></script>
        <script src="/js/velocity/velocity.min.js"></script>
        <script src="/js/velocity/velocity.ui.min.js"></script>
        <script src="/js/iaStickySidebar.js"></script>
        <script src="/js/lib.js"></script>
        
        <!--Start of Zopim Live Chat Script-->
        <script type="text/javascript">
        window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
        d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
        _.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute('charset','utf-8');
        $.src='//v2.zopim.com/?22xiIuDbFx71G2grZvKsPLWDNEWrYeB6';z.t=+new Date;$.
        type='text/javascript';e.parentNode.insertBefore($,e)})(document,'script');
        </script>
        <!--End of Zopim Live Chat Script-->

</body>
</html>
