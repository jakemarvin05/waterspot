<div class="social-feeds">
    <h2>Connect <span style="color: #000;">with us</span></h2>
    <div class="facebook-feeds">
	<div class="title-header">
	    <h2>Facebook Feeds</h2>
	</div>
	<div class="feeds-content">
	    <div id="fb-root"></div>
	    <script>(function(d, s, id) {
	      var js, fjs = d.getElementsByTagName(s)[0];
	      if (d.getElementById(id)) return;
	      js = d.createElement(s); js.id = id;
	      js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.0";
	      fjs.parentNode.insertBefore(js, fjs);
	    }(document, 'script', 'facebook-jssdk'));</script>
	    <div class="fb-like-box" data-href="http://www.facebook.com/waterspotllp" data-width="450" data-height="350" data-colorscheme="light" data-show-faces="true" data-header="false" data-stream="false" data-show-border="false"></div>
	</div>
    </div>
    <div class="twitter-feeds">
	<div class="title-header">
	    <h2>Twitter Feeds</h2>
	</div>
	<div class="feeds-content">
	    <a class="twitter-timeline"  href="https://twitter.com/jacky599r"  data-widget-id="464265953534963712" width="450" height="350" data-header="false" data-chrome="transparent">Tweets by @jacky599r</a>
	    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
	</div>
    </div>
</div>

<div class="clear"></div>

<div class="box-left">
    <? if(!empty($about_us['Page']['name'])){
	    $name=explode(' ',$about_us['Page']['name']);?>
	    <h2 class="home-title"><?=$name[0]?> <span style="color:#000;"><strong> <?=strstr($about_us['Page']['name'], ' ')?></strong></span></h2>
	<? }?>
     <? if(!empty($about_us['Page']['page_shortdescription'])){
	echo $about_us['Page']['page_shortdescription'];
    }?>
    <div class="read-more">
	<a href="/<?=$about_us['Page']['url_key']?>">read more &raquo; </a>
    </div>
</div>

<div class="box-right">
    <h2 class="home-title">Client <span style="color:#000;"><strong>Reviews</strong></span></h2>
    <div class="home-reviews-box">
		<div class="home-reviews-row">
		<? if(!empty($service_reviews)){ 
			$i=1;
			foreach($service_reviews as $service_review){
				$review_class='';
				if($i%2==0){
					$review_class=" home-reviews-last";
				}
			?>
	    
			<div class="home-reviews<?=$review_class;?>">
				<h6><?=ucfirst(@$service_review['Member']['first_name']); ?></h6>
				<p><?=$this->Format->Headingsubstring(@$service_reviews[0]['ServiceReviews']['message'],60); ?></p>
				<p class="home-reviews-link"><?=$this->Html->link("more &raquo;",array('plugin'=>false,'controller'=>'activity','action'=>'index',@$service_reviews[0]['ServiceReviews']['service_id']),array('escape'=>false));?></p>
			</div> 
	    
	<? $i++; } }else{?>
		<div class="no-client-review">There are no client reviews.</div>
	<? }?>
	</div> 
    </div>
</div>  

<div class="clear"></div>
