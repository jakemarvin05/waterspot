<section id="footer">
    <div class="container-fluid">
        <div class="row">

            <!-- contacts block -->
            <div class="footerCol col-sm-4 col-sm-offset-1">
                <div class="footerContactBlock">
                    <p class="footerContactSmallHeader">call us</p>

                    <p class="footerContactAfterHeader">+65 9450 3790</p>
                </div>
                <div class="footerContactBlock">
                    <p class="footerContactSmallHeader">send an enquiry</p>

                    <p class="footerContactAfterHeader">admin@waterspot.com.sg</p>
                </div>
                <div class="footerContactBlock">
                    <p class="footerContactSmallHeader">FOLLOW US</p>

                    <div id="footerSocialRow">
                        <a class="smIcons" href="<?= $setting['social']['facebook'] ?>"><img
                                src="/img/sm-icons/facebook.png"><img src="/img/sm-icons/facebook.png"></a>
                        <!--<a class="smIcons" href="<?= $setting['social']['twitter'] ?>"><img
                                src="/img/sm-icons/instagram.png"><img src="/img/sm-icons/instagram.png"></a>
                        <a class="smIcons" href="<?= $setting['social']['twitter'] ?>"><img
                                src="/img/sm-icons/twitter.png"><img src="/img/sm-icons/twitter.png"></a>
                        <a class="smIcons" href="<?= $setting['social']['google_plus'] ?>"><img
                                src="/img/sm-icons/googleplus.png"><img src="/img/sm-icons/googleplus.png"></a>-->
                    </div>
                </div>
            </div>

            <!-- subscribe block -->
            <div class="footerCol col-sm-4 col-sm-offset-1">
                <h4 class="headerAlt $$$">SUBSCRIBE</h4>

                <p id="footerBeforeEmailInput">Get Updated on <strong>Exciting Activities</strong> & <strong>Promotions</strong>:
                </p>

                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-paper-plane"></i></span>
                    <input id="subscribeInput" type="email" class="form-control required email"
                           placeholder="Enter your Email" aria-required="true">
                        <span class="input-group-btn">
                            <button id="subscribeButton" type="button" class="btn btnFillOrange">Subscribe</button>
                        </span>
                </div>
            </div>
            <script type="text/javascript">
            $('#subscribeButton').click(function(){
                var email = $('#subscribeInput').val();
                var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
                if (re.test(email)) {
                    $.ajax({
                         url :'/Subscriber/subscribe',
                         type:'POST',
                         data:{'email':email},
                         success: function (result)
                         {
                             $('#footerBeforeEmailInput').html(result);
                         }
                    }); 
                } else {
                    $('#footerBeforeEmailInput').html('Invalid Email Format');
                }
            });
            </script>

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