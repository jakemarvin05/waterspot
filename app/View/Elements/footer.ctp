<section id="footer">
    <div class="container-fluid">
        <div class="row">

            <!-- contacts block -->
            <div class="footerCol col-sm-4 col-sm-offset-1">
                <div class="footerContactBlock">
                    <p class="footerContactSmallHeader">call us</p>

                    <p class="footerContactAfterHeader">+65 8299 2678</p>
                </div>
                <div class="footerContactBlock">
                    <p class="footerContactSmallHeader">send an enquiry</p>

                    <p class="footerContactAfterHeader">enquiry@waterspot.com.sg</p>
                </div>
                <div class="footerContactBlock">
                    <p class="footerContactSmallHeader">FOLLOW US</p>

                    <div id="footerSocialRow">
                        <?php
                            if ($setting['social']['facebook'] != '') {
                                echo '<a class="smIcons" href="' . $setting['social']['facebook'] . '"><img src="/img/sm-icons/facebook.png"><img src="/img/sm-icons/facebook.png"></a>';
                            }
                            if ($setting['social']['instagram'] != '') {
                                echo '<a class="smIcons" href="' . $setting['social']['instagram'] . '"><img src="/img/sm-icons/instagram.png"><img src="/img/sm-icons/instagram.png"></a>';
                            }
                            if ($setting['social']['twitter'] != '') {
                                echo '<a class="smIcons" href="' . $setting['social']['twitter'] . '"><img src="/img/sm-icons/twitter.png"><img src="/img/sm-icons/twitter.png"></a>';
                            }
                            if ($setting['social']['google_plus'] != '') {
                                echo '<a class="smIcons" href="' . $setting['social']['google_plus'] . '"><img src="/img/sm-icons/googleplus.png"><img src="/img/sm-icons/googleplus.png"></a>';
                            }
                            if ($setting['social']['linkedin'] != '') {
                                echo '<a class="smIcons" href="' . $setting['social']['linkedin'] . '"><img src="/img/sm-icons/linkedin.png"><img src="/img/sm-icons/linkedin.png"></a>';
                            }
                        ?>
                    </div>
                </div>
            </div>

            <!-- subscribe block -->
            <div class="footerCol col-sm-4">
                <p class="footerContactSmallHeader">SUBSCRIBE</p>
                <!-- Begin MailChimp Signup Form -->
                <link href="//cdn-images.mailchimp.com/embedcode/classic-081711.css" rel="stylesheet" type="text/css">
                <style type="text/css">
                    /* Add your own MailChimp form style overrides in your site stylesheet or in this style block.
                       We recommend moving this block and the preceding CSS link to the HEAD of your HTML file. */
                </style>
                <div id="mc_embed_signup">
                <form action="//waterspot.us11.list-manage.com/subscribe/post?u=5931912452094114bc63c4074&amp;id=750e43a4a4" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate style="padding:0;">
                    <div id="mc_embed_signup_scroll">
                <div class="mc-field-group" style="width:100%;">
                    <label for="mce-EMAIL" class="footerContactSmallHeader" style="margin-bottom:9px">Email Address  <span class="asterisk">*</span>
                </label>
                    <input type="email" value="" name="EMAIL" class="form-control required email" id="mce-EMAIL" style="
  color: #999;
  background-color: rgba(0,0,0,.2);
  border-color: rgba(0,0,0,.25);">
                </div>
                    <div id="mce-responses" class="clear">
                        <div class="response" id="mce-error-response" style="display:none"></div>
                        <div class="response" id="mce-success-response" style="display:none"></div>
                    </div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                    <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_5931912452094114bc63c4074_750e43a4a4" tabindex="-1" value=""></div>
                    <div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="btn btnFillOrange" style="width:100%; margin:0;"></div>
                    </div>
                </form>
                </div>
                <script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script><script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';}(jQuery));var $mcj = jQuery.noConflict(true);</script>
                <!--End mc_embed_signup-->
                </div>
            </div>
            <!-- popular block -->
            <!--<div class="footerCol col-sm-4">
                <h4 class="headerAlt">POPULAR ACTIVITIES</h4>
                <ul id="footerPopularList">
                    <li><a href="/service-type-details/39">Stand Up Paddle</a></li>
                    <li><a href="/service-type-details/27">Diving</a></li>
                    <li><a href="/service-type-details/42">Kitesurfing</a></li>
                    <li><a href="/service-type-details/41">Kayaking</a></li>
                    <li><a href="/service-type-details/26">Boat Charter</a></li>
                </ul>
            </div>-->
        </div>
    </div>
</section>

<!-- bottommost row -->
<section id="copyright">
    <div class="container-fluid">
        <div class="row">
            <div id="copyrightLeftCol" class="col-sm-6">
                <p>Copyright © 2015 WaterSpot LLP. All rights reserved.</p>

                <p><a href="/terms">Terms & Conditions</a> / <a href="privacypolicy">Privacy Policy</a>
            </div>
            <div id="paymentLogoCont" class="col-sm-6">
                <img src="/img/footer-payment.png">
            </div>
        </div>
    </div>
</section>