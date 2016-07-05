<section id="activityPanorama" class="topResponsivePadding">
    <?php
    $isPanoramaImageExist = isset($service_detail['Service']['panorama_image']) && $service_detail['Service']['panorama_image'] != '';

    if ($panoramaImage = $isPanoramaImageExist ? $service_detail['Service']['panorama_image'] : 'default-panorama.jpg')
        ;
    ?>
    <img src="/img/service_images/<?php echo $panoramaImage; ?>">

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

            this.outerWrapper = this.imageToBeCropped.wrap('<div style="width: 100%;"></div>').parent();
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
            var heightOfContainer = this.outerWrapper.width() / this._getAspectRatio().decimal();
            this.outerWrapper.css('height', heightOfContainer);

            if (this._isImageTallerThanAspect()) {
                // taller image, set width to 100% and crop height
                var heightToCrop = this.imageToBeCropped.height() - heightOfContainer;

                // height to move up is half the amount
                this.imageToBeCropped.css({
                    'top': '-' + heightToCrop / 2 + 'px',
                    'right': ''
                });


            } else {
                // wide image, set height to 100% and crop width
                this.imageToBeCropped.css({
                    'width': '',
                    'height': '100%'
                });

                var widthToCrop = this.imageToBeCropped.width() - this.imageToBeCropped.height() * this._getAspectRatio().decimal();

                // width to move left is half the amount
                this.imageToBeCropped.css({
                    'top': '',
                    'left': '-' + widthToCrop / 2 + 'px',
                });
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

            return imageAspectRatio < this._getAspectRatio().decimal();
        }
    }

    var cropper = Object.create(autoCropper);
    cropper.init($('#activityPanorama img'));
</script>


<div class="wrapper">

    <div id="activityWhiteBg"></div>

    <div class="container-fluid content-wrapper">

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
                            <?php if ($min_price == $max_price): ?>
                                <div style="height: 100%; width: 100%;">
                                <span
                                    class="activity-price-price"><?= Configure::read('currency'); ?><?= number_format($min_price ? $min_price : $service_detail['Service']['service_price'], 2); ?>
                                    </span>
                                    <?php
                                    if ($service_detail['Service']['is_private'] == 0 && !preg_match('/yacht/i', $service_detail['service_type'])) {
                                        echo '<span class="unit">PER PAX</span>';
                                    }
                                    ?>
                                </div>
                            <?php else: ?>
                                <div style="height: 100%; width: 100%;">
                                <span
                                    class="activity-price-price"><?= Configure::read('currency'); ?><?= number_format(isset($min_price) ? $min_price : $service_detail['Service']['service_price'], 2); ?>
                                    - <?= Configure::read('currency'); ?><?= number_format(isset($max_price) ? $max_price : $service_detail['Service']['service_price'], 2); ?></span>
                                    <?php
                                    if ($service_detail['Service']['is_private'] == 0 && !preg_match('/yacht/i', $service_detail['service_type'])) {
                                        echo '<span class="unit">PER PAX</span>';
                                    }
                                    ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="sidebar-line"></div>

                        <div class="sidebar-inner-after-price">
                            <div id="rating" class="blocks">
                                <!--<h4>Rating:</h4>

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
                                        var rate = <?php echo(isset($service_detail['Rating']) ? $service_detail['Rating'] : 0); ?>;
                                        var crate = rate + 1;
                                        while (crate <= 5) {
                                            $('#rate-' + crate).html('<img src="/img/social-feed-logo-bw.jpg">');
                                            crate++;
                                        }
                                    });
                                </script>
                                <div class="clearfix"></div>-->


                                <?php

                                /* Minimum to go block */
                                $minimumParticipants = $service_detail['Service']['min_participants'];
                                if ($minimumParticipants > 1):

                                    ?>

                                    <p class="info">
                                        We need <?php echo $minimumParticipants; ?> participants for this event to
                                        start.
                                    </p>

                                    <div class="clearfix"></div>

                                <?php endif; ?>


                            </div>
                            <!-- /#ratings -->

                            <div class="blocks">
                                <div class="slot-booking-form">
                                    <?php $step = 1; ?>
                                    <?= $this->Form->create('Activity', array('url' => array('controller' => 'activity', 'action' => 'add_to_card'), 'name' => 'add_services', 'class' => 'quick-contacts5', 'id' => 'add_services', 'novalidate' => true)); ?>
                                    <?= $this->Form->text('service_id', array('type' => 'hidden', 'value' => $service_detail['Service']['id'])); ?>
                                    <br>

                                    <div class="startDate">
                                        <div class="start-date">
                                            <h4>Select Date</h4>
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
                                    <?php if ((!preg_match('/yacht/i', $service_detail['service_type']) && !(isset($service_detail['Service']['is_private']) && $service_detail['Service']['is_private'] == 1)) || (preg_match('/yacht/i', $service_detail['service_type'])) && $service_detail['Service']['num_pax_included'] > 0 && $rule_object['max_pax'] > 0): ?>
                                        <div class="select-participant">
                                            <h4 class="select-participant-txt">No. of Pax</h4>
                                            <div class="input-group">
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-default btn-number"
                                                            data-type="minus"
                                                            data-field="<?php echo(!preg_match('/yacht/i', $service_detail['service_type']) ? 'data[Activity][no_participants]' : 'data[Activity][no_of_pax]'); ?>">
                                                        <span class="glyphicon glyphicon-minus"></span>
                                                    </button>
                                                </span>
                                                <?= $this->Form->input((!preg_match('/yacht/i', $service_detail['service_type']) ? 'no_participants' : 'no_of_pax'), array('type' => 'text', 'class' => 'form-control input-number', 'value' => ($service_detail['Service']['num_pax_included'] > 1 ? $service_detail['Service']['num_pax_included'] : 0), 'max' => ($rule_object['max_pax'] > 0 ? $rule_object['max_pax'] : 50), 'min' => 1, 'div' => false, 'label' => false)); ?>
                                                <span class="input-group-btn">
                                                      <button type="button" class="btn btn-default btn-number"
                                                              data-type="plus"
                                                              data-field="<?php echo(!preg_match('/yacht/i', $service_detail['service_type']) ? 'data[Activity][no_participants]' : 'data[Activity][no_of_pax]'); ?>">
                                                          <span class="glyphicon glyphicon-plus"></span>
                                                      </button>
                                                </span>
                                            </div>

                                        </div>
                                        <?php echo $this->element('message'); ?>
                                    <?php else: ?>
                                        <?= $this->Form->input('no_participants', array('type' => 'hidden', 'div' => false, 'label' => false, 'value' => 1)); ?>
                                    <?php endif; ?>

                                    <?php if ( $rule_object['max_add_hour'] > 0): ?>
                                        <br>
                                        <div class="select-add-hour">
                                            <h4 class="select-participant-txt">Additional Hour</h4>
                                            <div class="input-group">
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-default btn-number"
                                                            disabled="disabled" data-type="minus"
                                                            data-field="data[Activity][add_hour]">
                                                        <span class="glyphicon glyphicon-minus"></span>
                                                    </button>
                                                </span>
                                                <?= $this->Form->input('add_hour', array('type' => 'text', 'class' => 'form-control input-number', 'value' => 0, 'max' => $rule_object['max_add_hour'], 'min' => 0, 'div' => false, 'label' => false)); ?>
                                                <span class="input-group-btn">
                                                      <button type="button" class="btn btn-default btn-number"
                                                              data-type="plus" data-field="data[Activity][add_hour]">
                                                          <span class="glyphicon glyphicon-plus"></span>
                                                      </button>
                                                </span>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <div class="calculator-output">
                                        <p>Subtotal: <span id="sub-total">$0</span></p>
                                    </div>
                                    <div class="check-terms">
                                        <label>
                                            <p>
                                                <input name="terms_and_condition" type="checkbox"/> I agree with the
                                                <a
                                                    href="/terms" target="_blank"> Terms & Conditions</a>
                                            </p>
                                        </label>
                                    </div>
                                    <div class="cart-btn">
                                        <input type="submit" value="Book A Spot"
                                               class="addtocart-button btn btnDefaults btnFillOrange"
                                               id="loginButton"/>
                                    </div>
                                    <?= $this->Form->end(); ?>
                                </div>
                            </div>

                            <div id="share" class="blocks">
                                <br><br>
                                <h4>Share: </h4>

                                <div class="socialicons">
                                    <a id="shareFB"
                                       href="https://www.facebook.com/sharer/sharer.php?app_id=1725992164290232&sdk=joey&u=<?php echo(isset($web_url) ? urlencode($web_url) : urlencode('http://www.waterspot.com.sg')); ?>&display=popup&ref=plugin&src=share_button">facebook</a>
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
<?php if (!empty($cart_id))
    echo $this->element('activity/cart_booking_invite'); ?>
<?php $path = $this->Html->webroot; ?>

<? if (!empty($cart_id)): ?>
    <script>
        $(document).ready(function () {
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
            var total = <?= number_format($cart_details['Cart']['total_amount'], 2);?>;
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
                var is_private = $('#CartIsPrivate').val();
                if (is_private == false) {
                    total = total * no_of_participant;
                }

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
    </script>
<? endif; ?>

<script type="text/javascript">
    //plugin bootstrap minus and plus
    //http://jsfiddle.net/laelitenetwork/puJ6G/
    $('.btn-number').click(function (e) {
        e.preventDefault();

        fieldName = $(this).attr('data-field');
        type = $(this).attr('data-type');
        var input = $("input[name='" + fieldName + "']");
        var currentVal = parseInt(input.val());
        if (!isNaN(currentVal)) {
            if (type == 'minus') {

                if (currentVal > input.attr('min')) {
                    input.val(currentVal - 1).change();
                }
                if (parseInt(input.val()) == input.attr('min')) {
                    $(this).attr('disabled', true);
                }

            } else if (type == 'plus') {

                if (currentVal < input.attr('max')) {
                    input.val(currentVal + 1).change();
                }
                if (parseInt(input.val()) == input.attr('max')) {
                    $(this).attr('disabled', true);
                }

            }
        } else {
            input.val(0);
        }
    });
    $('.input-number').focusin(function () {
        $(this).data('oldValue', $(this).val());
    });
    $('.input-number').change(function () {

        minValue = parseInt($(this).attr('min'));
        maxValue = parseInt($(this).attr('max'));
        valueCurrent = parseInt($(this).val());

        name = $(this).attr('name');
        if (valueCurrent >= minValue) {
            $(".btn-number[data-type='minus'][data-field='" + name + "']").removeAttr('disabled')
        } else {
            alert('Sorry, the minimum value was reached');
            $(this).val($(this).data('oldValue'));
        }
        if (valueCurrent <= maxValue) {
            $(".btn-number[data-type='plus'][data-field='" + name + "']").removeAttr('disabled')
        } else {
            alert('Sorry, the maximum value was reached');
            $(this).val($(this).data('oldValue'));
        }


    });
    $(".input-number").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
                // Allow: Ctrl+A
            (e.keyCode == 65 && e.ctrlKey === true) ||
                // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
            // let it happen, don't do anything
            return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

</script>

<script>
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
                $("#slots_form").html(result);


            }
        });
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


    // initialize preliminary variables
    var $rule_object_json = (<?php echo $rule_object_json; ?>).rules;
    var paxIncluded = <?php echo $service_detail['Service']['num_pax_included']?>;

    // Define the rule
    var Rule = {
        slotPrice: 0,
        slotIdentity: '',
        hasRule: false,
        pricePerPax: 0,
        pricePerHour: 0,
        additionalPax: 0,
        additionalHour: 0,
        maxAddHourAllowed: 0,
        slotInputVal: '',
        selectedSlot: $(),
        updatePrice: function () {
            var newPrice = 0;
            // calculate the new price if slotPrice is set
            if (this.slotPrice > 0) {
                // consider the rules when it is set
                if(this.hasRule){
                    newPrice = parseInt(this.slotPrice) + (parseInt(this.pricePerHour) * parseInt(this.additionalHour)) + (parseInt(this.additionalPax) * parseInt(this.pricePerPax));
                }
                else {
                    newPrice =  parseInt(this.slotPrice) * parseInt(this.additionalPax);
                }
            }
            // set the slot input value
            var newValChucks = this.slotInputVal.split('_');
            // delete the price object
            newValChucks.pop();
            // change it with new value
            newValChucks.push(newPrice);
            // add all the necessary parameters
            newValChucks.push(this.slotPrice);
            newValChucks.push(this.pricePerPax);
            newValChucks.push(this.pricePerHour);
            newValChucks.push(this.additionalPax);
            newValChucks.push(this.additionalHour);
            // rejoin the chuncks
            var newVal = newValChucks.join('_');
            // set the newVal to the selected slot input
            this.selectedSlot.val(newVal);
            // change the calculator subtotal value
            $('#sub-total').html('$' + newPrice);

        }
    };

    var rule = Rule;


    // detect the slot check action
    $('#slots_form ').on('change', 'input[type=checkbox]',
        function () {
            if ($(this).is(':checked')) {
                // Make the checkbox function like a radio button
                $('.check-box').prop('checked', false);
                $(this).prop('checked', true);
                // Set the rule parameters
                rule.selectedSlot = $(this);
                rule.slotPrice = $(this).data('price');

                // get the slot identity
                var thisVal =  $(this).val();
                var valProcessed = thisVal.split('_');
                var slotIdentity = valProcessed[0] + valProcessed[1] + valProcessed[2] + valProcessed[3] + valProcessed[4];
                if (rule.slotIdentity != slotIdentity) {
                    rule.slotIdentity = slotIdentity;
                    rule.slotInputVal = $(this).val();
                }


                // get the slotindex
                var slotIndex = $(this).data('slot');


                // Check the slot index and set the rule parameters from it
                switch (slotIndex) {
                    case 1:
                        // check for rules and assign
                        if (!$.isEmptyObject($rule_object_json.weekday_rules)) {
                            rule.pricePerPax = $rule_object_json.weekday_rules['price per pax'] ? $rule_object_json.weekday_rules['price per pax'] : 0;
                            rule.pricePerHour = $rule_object_json.weekday_rules['price per hour'] ? $rule_object_json.weekday_rules['price per hour'] : 0;
                            rule.maxAddHourAllowed = $rule_object_json.weekday_rules['max additional hour'] ? $rule_object_json.weekday_rules['max additional hour'] : 0;
                            rule.hasRule = true;
                        }
                        else {
                            rule.hasRule = false;
                        }
                        break;
                    case 2:
                        // check for rules and assign
                        if (!$.isEmptyObject($rule_object_json.weekend_rules)) {
                            rule.pricePerPax = $rule_object_json.weekend_rules['price per pax'] ? $rule_object_json.weekend_rules['price per pax'] : 0;
                            rule.pricePerHour = $rule_object_json.weekend_rules['price per hour'] ? $rule_object_json.weekend_rules['price per hour'] : 0;
                            rule.maxAddHourAllowed = $rule_object_json.weekend_rules['max additional hour'] ? $rule_object_json.weekend_rules['max additional hour'] : 0;
                            rule.hasRule = true;
                        }
                        else {
                            rule.hasRule = false;
                        }
                        break;
                    case 3:
                        // check for rules and assign
                        if (!$.isEmptyObject($rule_object_json.special_rules)) {
                            rule.pricePerPax = $rule_object_json.special_rules['price per pax'] ? $rule_object_json.special_rules['price per pax'] : 0;
                            rule.pricePerHour = $rule_object_json.special_rules['price per hour'] ? $rule_object_json.special_rules['price per hour'] : 0;
                            rule.maxAddHourAllowed = $rule_object_json.special_rules['max additional hour'] ? $rule_object_json.special_rules['max additional hour'] : 0;
                            rule.hasRule = true;
                        }
                        else {
                            rule.hasRule = false;
                        }
                        break;
                    default:
                        break;
                }
                // Update the rule Price
                rule.updatePrice();

                // Update the add_hour max value
                $('[name="data[Activity][add_hour]"]').attr('max', rule.maxAddHourAllowed);

            }
            else {
                // set slot price to 0
                rule.slotPrice = 0;
                // Update the rule Price
                rule.updatePrice();
            }

        }
    );

    // detect the changing of the addional option to do recalculation
    $('.select-add-hour ').on('change', '[name="data[Activity][add_hour]"]',
        function () {
            // update rule.additionalHour
            rule.additionalHour = $(this).val();
            // update rule Price
            rule.updatePrice();

        });


    $('#ActivityNoOfPax').change(
        function () {
            var paxSelected = $(this).val();
            // calculate additional hour
            var additionalPax = paxSelected - paxIncluded;
            // update rule Price if additional pax is positive
            if(additionalPax>-1) {
                rule.additionalPax = additionalPax;
            }
            rule.updatePrice();
        }
    );

    $('#ActivityNoParticipants').change(
        function () {
            var paxSelected = $(this).val();
            // calculate additional hour
            var additionalPax = paxSelected - paxIncluded;
            // update rule Price
            rule.additionalPax = additionalPax;
            rule.updatePrice();
        }
    );

    $(document).ready(function () {
        // for price update
        $('#add_services').submit(function () {
            var startdate = $("#ActivityStartDate").val();
            var enddate = $("#ActivityEndDate").val();
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


    $(window).load(function () {
        $('.right-section, .left-section').theiaStickySidebar({
            minWidth: 768,
            additionalMarginBottom: 20,
            additionalMarginTop: 180,
            scrollThrough: ['.left-section']
        });
        $('[name=terms_and_condition]').attr('checked', false);
        $('#loginButton').prop('disabled', true);

        $('[name=terms_and_condition]').change(function () {
            if ($(this).is(":checked")) {
                $('#loginButton').removeAttr('disabled')
            }
            else {
                $('#loginButton').attr('disabled', 'disabled');
            }
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
    $('#ActivityNoParticipants').val('1');
    $('#ActivityStartDate').val('<?php echo date("Y-m-d"); ?>');

    get_service_availability();

</script>
