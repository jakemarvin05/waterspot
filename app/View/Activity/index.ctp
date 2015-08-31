<section id="activityPanorama" class="topResponsivePadding">
    <img
        src="/img/service_images/<?php echo(isset($service_detail['Service']['panorama_image']) ? $service_detail['Service']['panorama_image'] : 'panorama.png'); ?>">
</section>

<script>
    var autoCropper = {

        /* configurables */

        imageToBeCropped: null,
        listenToResize: true,
        aspectRatio: "2.35:1",

        /* main function */
        init: function (imageToBeCropped) {
            if (!imageToBeCropped) throw Error('Needs a jQuery obj to crop.');

            var self = this;

            this.imageToBeCropped = imageToBeCropped;
            this.imageToBeCropped.css({
                'width': '100%', // set 100% initially
                'position': 'relative'
            });

            this.outerWrapper = this.imageToBeCropped.wrap('<div></div>').parent();
            this.outerWrapper.css({
                'position': 'relative',
                'width': '100%',
                'overflow': 'hidden'
            });

            this.imageToBeCropped[0].onload = function () {
                self._cropper()
            };

            // force refresh to trigger the onload event should the image raced ahead of this script.
            this.imageToBeCropped[0].src = this.imageToBeCropped[0].src;

            // bind to window resize listener
            if (this.listenToResize) {
                $(window).on('resize', function () {
                    self._cropper();
                });
            }
        },

        _cropper: function () {

            if (this._isImageTallerThanAspect) {
                // taller image, set width to 100% and crop height
                var heightOfContainer = this.imageToBeCropped.width() / this._getAspectRatio().decimal();
                var heightToCrop = this.imageToBeCropped.height() - heightOfContainer;

                // height to move up is half the amount
                this.imageToBeCropped.css({
                    'top': '-' + heightToCrop / 2 + 'px',
                    'right': null
                });
                this.outerWrapper.css('height', heightOfContainer);

            } else {
                // width image, set height to 100% and crop width
                console.log(this)
                this.imageToBeCropped.css({
                    'width': null,
                    'height': '100%'
                });

                var widthToCrop = this.imageToBeCropped.width() - this.imageToBeCropped.height() * this._getAspectRatio().decimal();

                // width to move left is half the amount
                this.imageToBeCropped.css({
                    'top': null,
                    'right': '-' + widthToCrop / 2 + 'px',
                });
                this.outerWrapper.css('height', null)
            }

        },

        /* helpers */
        _getAspectRatio: function () {
            if (this.aspects) return this.aspects;

            var aspects = this.aspectRatio.split(':');
            if (aspects.length !== 2) throw Error('aspectRatio is not valid.');

            this.aspects = {
                width: parseFloat(aspects[0]),
                height: parseFloat(aspects[1]),
                decimal: function () {
                    if (this._decimal) return this._decimal;
                    return this._decimal = this.width / this.height;
                }
            };

            return this.aspects;
        },
        _isImageTallerThanAspect: function () {
            var imageAspectRatio = this.imageToBeCropped.width() / this.imageToBeCropped.height();

            return imageAspectRatio < this._getAspectRatio();
        }
    }

    var cropper = Object.create(autoCropper);
    cropper.init($('#activityPanorama img'));
</script>


<div class="wrapper">

    <div id="activityWhiteBg"></div>

    <div class="container-fluid">

        <section class="left-section col-sm-8">
            <div class="theiaStickySidebar">

                <div id="headerAndPhotos">
                    <h2 class="activityTitle headerAlt"><?= ucfirst($service_detail['Service']['service_title']); ?></h2>

                    <div class="slider-holder">
                        <?= $this->element('activity/slider'); ?>
                    </div>
                </div>

                <?= $this->element('activity/serviceDescriptiontabs'); ?>

            </div>

        </section>

        <section id="sidebar" class="right-section col-sm-4 col-xs-12">
            <div class="theiaStickySidebar">
                <aside class="sidebar-inner">
                    <div class="sidebar-inner-wrapper">
                        <div class="activity-price-info">
                            <div style="height: 100%; width: 100%;">
                                <span class="activity-price-price"><?= Configure::read('currency'); ?><?= number_format($min_price, 2); ?>
                                    - <?= Configure::read('currency'); ?><?= number_format($max_price, 2); ?></span> <span
                                    class="unit">PER PAX</span>
                            </div>
                        </div>

                        <div class="sidebar-line"></div>

                        <div class="sidebar-inner-after-price">
                            <div id="rating" class="blocks">
                                <h4>Rating:</h4>

                                <div class="rating" style="background:none;">
                                    <button class="rate" id="rate-1" data-rate="1" style="background:none"><img
                                            src="/img/social-feed-logo.jpg"></button>
                                    <button class="rate" id="rate-2" data-rate="2" style="background:none"><img
                                            src="/img/social-feed-logo.jpg"></button>
                                    <button class="rate" id="rate-3" data-rate="3" style="background:none"><img
                                            src="/img/social-feed-logo.jpg"></button>
                                    <button class="rate" id="rate-4" data-rate="4" style="background:none"><img
                                            src="/img/social-feed-logo.jpg"></button>
                                    <button class="rate" id="rate-5" data-rate="5" style="background:none"><img
                                            src="/img/social-feed-logo.jpg"></button>
                                </div>
                                <script type="text/javascript">
                                    $(document).ready(function () {
                                        var rate = <?php echo (isset($service_detail['Rating'])?$service_detail['Rating']:0); ?>;
                                        var crate = rate + 1;
                                        while (crate <= 5) {
                                            $('#rate-' + crate).html('<img src="/img/social-feed-logo-bw.jpg">');
                                            crate++;
                                        }
                                    });
                                </script>
                                <div class="clearfix"></div>


                                <?php
                                /* Minimum to go block */
                                $minimumParticipants = $service_detail['Service']['min_participants'];
                                if ($minimumParticipants > 0) {
                                    $percent = round($booking_count * 100 / $minimumParticipants);
                                    ?>

                                    <p class="info">
                                        Event has a minimum-to-go of <?php echo $minimumParticipants; ?> pax.
                                    </p>
                                    <div class="completion">
                                        <div class="progressbar" style="width:<?php echo $percent; ?>%;"></div>
                                    </div>
                                    <div class="progressinfo"><span
                                            class="current"><?php echo(isset($booking_count) ? $booking_count : "0"); ?></span>
                                        out of <?php echo $minimumParticipants; ?></div>
                                    <div class="clearfix"></div>

                                    <?php
                                    /* End minimum to go block */
                                }
                                ?>
                            </div>
                            <!-- /#ratings -->

                            <div class="blocks">
                                <div class="slot-booking-form">
                                    <?= $this->Form->create('Activity', array('url' => array('controller' => 'activity', 'action' => 'add_to_card'), 'name' => 'add_services', 'class' => 'quick-contacts5', 'id' => 'add_services', 'novalidate' => true)); ?>
                                    <div class="select-participant">
                                        <h4 class="select-participant-txt">1. Select No. of Pax</h4>
                                        <?
                                        $no_participants = array();
                                        foreach (range(1, $service_detail['Service']['no_person']) as $r) {
                                            $no_participants[$r] = $r;
                                        }
                                        ?>
                                        <?= $this->Form->input('no_participants', array('type' => 'select', 'options' => $no_participants, 'empty' => 'Select', 'div' => false, 'label' => false)); ?>
                                    </div>
                                    <?php echo $this->element('message'); ?>


                                    <?= $this->Form->text('service_id', array('type' => 'hidden', 'value' => $service_detail['Service']['id'])); ?>
                                    <br>

                                    <div class="startDate">
                                        <div class="start-date">
                                            <h4>2. Start Date</h4>
                                            <br/>
                                            <?= $this->Form->text('start_date', array('type' => 'hidden', 'class' => 'date-icon', 'autocomplete' => 'off')); ?>
                                        </div>
                                        <div id="startdatepicker"></div>
                                        <br>
                                    </div>

                                    <div class="clear"></div>
                                    <div class="clear"></div>
                                    <div id='loader_slots' class="ajax-loder" style="display:none">
                                        <?php echo $this->Html->image('loader-2.gif', array('alt' => 'loading..')); ?>
                                    </div>
                                    <div id='slots_form' style="display:none"></div>
                                    <div class="cart-btn">
                                        <input type="submit" value="Book Now"
                                               class="addtocart-button btn btnDefaults btnFillOrange" id="loginButton"/>
                                    </div>
                                    <?= $this->Form->end(); ?>
                                </div>
                            </div>

                            <div id="share" class="blocks">
                                <br><br>
                                <h4>Share: </h4>

                                <div class="socialicons">
                                    <a id="shareFB"
                                       href="https://www.facebook.com/sharer/sharer.php?app_id=1725992164290232&sdk=joey&u=<?php echo(isset($web_url) ? urlencode($web_url) : urlencode('http://128.199.214.85')); ?>&display=popup&ref=plugin&src=share_button">facebook</a>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>

                </aside>
            </div>
        </section>

        <div class="clear spacer"></div>

    </div>
    <div class="container-fluid suggestion" id="recommended_slots">

    </div>
</div>


<div class="clear"></div>

<!-- NEW DESIGN FOR CART MODEL BOX BEGINS -->
<? if (!empty($cart_id)) { ?>
    <?php echo $this->element('activity/cart_booking_invite'); ?>
<? } ?>
<?php $path = $this->Html->webroot; ?>
<script type="text/javascript">
    <? if(!empty($cart_id)) {  ?>
    $(document).ready(function () {
        //$('#add_services input#loginButton').attr('disabled',true);

        $("#add_invite input[type=checkbox]").click(function (event) {
            updateTotal();
        });
        $("#add_invite input[type=radio]").click(function (event) {
            updateTotal();
        });
    });


    function updateTotal() {
        var value_added_service = 0;
        var no_of_booking_msg = '';
        var service_amount = parseFloat(<?=$cart_details['Cart']['price'];?>);
        var total = parseFloat(<?=$cart_details['Cart']['total_amount'];?>);
        // no of booking date or slot
        var no_of_interval = parseInt(<?=$no_of_booking_days;?>);
        if (no_of_interval == 1) {
            // overight no_of_interval
            var no_of_slots = no_of_interval = parseInt(<?=count($cart_details['Cart']['slots']); ?>);
            if (no_of_slots == 1) {
                no_of_booking_msg = no_of_slots + " Slot";
            } else {
                no_of_booking_msg = no_of_slots + " Slots";
            }

        } else {
            no_of_booking_msg = no_of_interval + " Days";
        }

        var invite_p_status = $(".cart-payment-method input:radio:checked").val();
        $("#add_invite input:checkbox:checked").each(function () {
            total += parseFloat(this.value);
            value_added_service += parseFloat(this.value);
        });


        if (invite_p_status == 1) {
            var no_of_participant = $('#CartNoParticipants').val();
            total = total * no_of_participant;

        } else {
            var no_of_participant = 1;
        }

        var value_added_total = (value_added_service * no_of_participant).toFixed(2);
        $('.subtotal').show();
        $('#Vas_detail').html("( $" + value_added_service.toFixed(2) + 'x' + no_of_participant + ")");
        $('#Vas_total').html("$" + value_added_total);
        $('#no_of_booking_days').html(no_of_booking_msg);
        $('#Vas_total').html("$" + value_added_total);
        $('#total_amount').html("$" + (no_of_participant * service_amount).toFixed(2));
        $('#total_participate').html(no_of_participant);
        $('#total_participate_amount').html((no_of_participant * service_amount * no_of_interval).toFixed(2));
        $('#sub_total').html("$" + total.toFixed(2));

    }
    <? }?>
    function get_service_availability() {

        var service_id = $("#ActivityServiceId").val();
        var startdate = $("#ActivityStartDate").val();
        var no_participants = $("#ActivityNoParticipants").val();

        if (startdate == '' || service_id == '' || no_participants <= 0) {
            return;
        }


        $('#loader_slots').show();

        $.ajax({
            url: '<?=$path?>activity/ajax_get_availbility_range',
            type: 'POST',
            data: {'service_id': service_id, 'start_date': startdate, 'no_participants': no_participants},
            success: function (result) {

                $('#slots_form').show();
                $('#loader_slots').hide();
                //$('#add_services input#loginButton').attr({'disabled':false});
                $("#slots_form").html(result);


            }
        });
//alert(service_id+startdate+enddate)
    }

    function get_recommended_dates() {
        var service_id = $("#ActivityServiceId").val();
        var startdate = $("#ActivityStartDate").val();
        $.ajax({
            url: '<?=$path?>activity/ajax_get_recommended_dates',
            type: 'POST',
            data: {'service_id': service_id, 'start_date': startdate},
            success: function (result) {
                $("#recommended_slots").html(result);
            }
        });
    }
</script>

<script>
    <?php $path = $this->Html->webroot; ?>
    $(document).ready(function () {
        // for price update
        $('#add_services').submit(function () {
            var startdate = $("#ActivityStartDate").val();
            var enddate = $("#ActivityEndDate").val();
            /*if(enddate==null || enddate==''){
             var data = $(this).serializeArray();
             if($('.check-box').length < 1){
             return false;
             }
             }*/
            var data = new FormData(this);
            var formData = $(this);
            var status = 0;
            $.each(this, function (i, v) {
                $(v).removeClass('invalid form-error');
            });

            $('.error-message').remove();
            $('#add_services > span#for_owner_cms').show();
            //$('input[type="submit"]').attr({'disabled':true});

            $.ajax({
                url: '<?=$path?>activity/validation',
                async: false,
                data: data,
                dataType: 'json',
                type: 'post',
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {

                    if (data.error == 1) {
                        $.each(data.errors, function (i, v) {
                            $('#' + i).addClass("invalid form-error").after('<span class="error-message" style="width:200px; margin-left:0;">' + v + '</span>');
                            $('#' + i).bind('submit', function () {
                                $(this).removeClass('invalid form-error');
                                $(this).next().remove();
                            });
                        });
                    } else {
                        status = 1;
                    }

                }
            });
            if (status == 0) {
                $("html, body").animate({scrollTop: 0}, "slow");
                $('input[type="submit"]').attr({'disabled': false});
                $('#add_services > span#for_owner_cms').hide();
            }

            return (status === 1) ? true : false;

        });

        // invite friends

        $('#add_invite').submit(function (e) {
            //var data = $(this).serializeArray();
            var data = new FormData(this);
            var formData = $(this);

            var status = 0;
            $.each(this, function (i, v) {
                $(v).removeClass('invalid form-error');
            });

            $('.error-message').remove();
            $('#add_invite > span#for_owner_cms').show();
            //$('input[type="submit"]').attr({'disabled':true});

            $.ajax({
                url: '<?=$path?>carts/validation/cart',
                async: true,
                data: data,
                dataType: 'json',
                type: 'post',
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    if (data.error == 1) {
                        $.each(data.errors, function (i, v) {
                            $('#' + i).addClass("invalid form-error").after('<div class="error-message">' + v + '</div>');
                            $('#' + i).bind('submit', function () {
                                $(this).removeClass('invalid form-error');
                                $(this).next().remove();
                            });
                        });
                        $("html, body").animate({scrollTop: 0}, "slow");
                        $('input[type="submit"]').attr({'disabled': false});
                        $('#add_invite > span#for_owner_cms').hide();
                    } else {
                        status = 1;
                        return true;

                    }

                }
            });


        });

    });


    $(document).ready(function () {
        $('.left-section, .right-section').theiaStickySidebar({
            minWidth: 768,
            additionalMarginBottom: 20,
            additionalMarginTop: 120,
            scrollThrough: ['.left-section']
        });


    });

</script>
<script type="application/javascript">
    $(window).load(function () {

        $('#activityWhiteBg').height($('#headerAndPhotos').height() + 25);

    })

</script>


<script language="javascript" type="text/javascript">

    function openInPopUp(url) {
        newwindow = window.open(url, 'name', 'height=500,width=550');
        if (window.focus) {
            newwindow.focus()
        }
        return false;
    }

    $('#shareFB').click(function (e) {
        e.preventDefault();
        openInPopUp($(this).attr("href"));

    });

</script>

<script type="text/javascript">
    $('#ActivityNoParticipants').selectpicker().hide();


</script>
