<div class="container-fluid wrapper carts-page">
    <section class="content">


        <header class="page-header text-center">
            <p class="beforeHeader">Reserve your spot</p>

            <h1 class=" headerAlt">Book Now</h1>
        </header>
        <div class="container">
            <div class="col-sm-6 col-sm-offset-3 col-xs-12">
                <?php echo $this->element('message'); ?>

                <div class="<?= (!empty($cart_details)) ? 'ch-out' : 'middle-area' ?>">

                    <? $sub_total = 0;
                    if (!empty($cart_details)) { ?>
                        <? if (empty($redirect_login)) { ?>

                            <div class="mark">We just need a few details from you to complete this transaction. Required
                                fields marked (<span style="color:#F00;">*</span>)
                            </div>
                            <?php echo $this->element('message'); ?>
                            <div class="registration-form-box">
                                <?php echo $this->Form->create('Cart', array('name' => 'check_out', 'action' => 'booking_request', 'class' => 'registration-form', 'id' => 'CartId')) ?>
                                <?php echo $this->Form->input('id', array('value' => $cart_details[0]["Cart"]["id"])); ?>

                                <div class="registration-form-row">
                                    <div class="labelbox">
                                        <label>Name : <span style="color:#ff4142;">*</span></label>
                                    </div>

                                    <div class="fieldbox">
                                        <?= $this->Form->input('fname', array('type' => 'text', 'label' => false, 'div' => false, 'class' => 'form-control')); ?>
                                        <?= $this->Form->error('fname', null, array('wrap' => 'div', 'class' => 'error-message')); ?>
                                    </div>
                                </div>
                                <div class="registration-form-row">
                                    <div class="labelbox">
                                        <label>Email address : <span style="color:#ff4142;">*</span></label>
                                    </div>
                                    <div class="fieldbox">
                                        <?= $this->Form->input('email', array('type' => 'text', 'label' => false, 'div' => false, 'class' => 'form-control')); ?>
                                        <?= $this->Form->error('email', null, array('wrap' => 'div', 'class' => 'error-message')); ?>
                                    </div>
                                </div>
                                <div class="registration-form-row">
                                    <div class="labelbox">
                                        <label>Phone : <span style="color:#ff4142;"> *</span></label>
                                    </div>
                                    <div class="fieldbox">
                                        <?= $this->Form->input('phone', array('type' => 'text', 'label' => false, 'div' => false, 'class' => 'form-control')); ?>
                                        <?= $this->Form->error('phone', null, array('wrap' => 'div', 'class' => 'error-message')); ?>
                                    </div>
                                </div>
                                <div class="registration-form-row" style="text-align: right;">
                                    <!--<input class="submit-button addtocart-button" value="Pay Now" type="submit">-->
                                    <input class="btn btnDefaults btnFillOrange" value="Book Now" type="submit">
                                </div>
                                <div class="registration-form-row" style="margin: 15px 0 0 175px; text-align: left;">
                                    <?= $this->Html->image('asia-payment-logos.jpg', array('alt' => 'asiapay')); ?>
                                </div>
                                <?php echo $this->Form->end(); ?>
                            </div>
                            <?php if (!isset($coupon)) { ?>
                                <div class="registration-form-box">
                                    <div class="registration-form-row">
                                        <div class="labelbox">
                                            <label>Coupon code: </label>
                                        </div>
                                        <div class="fieldbox">
                                            <?= $this->Form->input('coupon', array('id' => 'code', 'type' => 'text', 'label' => false, 'div' => false, 'class' => 'form-control')); ?>
                                            <div id="validate_message" style="visibility:hidden;"></div>
                                        </div>
                                    </div>

                                    <div class="registration-form-row" style="text-align: right;">
                                        <button id="validate_code" class="btn btnDefaults btnFillOrange" type="button">Validate Code</button>
                                    </div>
                                </div>
                            <?php } else {
                                    echo "<h3>Coupon Used:</h3>";
                                    echo "<h4>" . $coupon['Coupon']['discount'] * 100 . "% discount to your total price.</h4>";
                                } ?>
                        <? } else { ?>
                            <div style="padding: 10px;">
                                <?= $this->element('message'); ?>
                                <p style=margin:0;>
                                    Please <?= $this->Html->link('Login', array('plugin' => 'member_manager', 'controller' => 'members', 'action' => 'login?redirect_url=' . $redirect_login)) ?>
                                    or <?= $this->Html->link('Register', array('plugin' => 'member_manager', 'controller' => 'members', 'action' => 'registration?redirect_url=' . $redirect_login)) ?>
                                    to continue...</p>
                            </div>
                        <? } //end if else condition ?>
                    <? } else { ?>
                        <div>
                            <div class="service-hd">Your cart is empty</div>
                            <?php echo $cart_page['Page']['page_longdescription']; ?>
                            <p class="empty">
                                <?= $this->Html->link("Click here to add new activities.", array('controller' => 'activity', 'action' => 'activities'), array('class' => 'btn btnDefaults btnFillOrange')); ?>
                            </p>
                        </div>
                    <? } ?>
                </div>
            </div>
        </div>

        <div class="col-sm-8 col-sm-offset-2 col-xs-12">
            <? if (!empty($cart_details)) { ?>
                <h3 class="text-center booking-section-title">Your Booking</h3>
                <div class="your-booking">
                    <!--		<p class="add-more-to-cart text-center">-->
                    <? //=$this->Html->link("Add More Activities",array('controller'=>'activity','action'=>'activities'),array('class'=>'btn btnDefaults btnFillOrange')); ?><!--</p>-->

                    <div class="row">
                        <? if (!empty($cart_details)) {
                            $sub_total = 0;
                            foreach ($cart_details as $key => $cart_detail) {
                                $slot_price = 0;
                                $value_added_price = 0; ?>
                                <div class="checkout-activity col-sm-6 col-xs-6">
                                    <div class="checkout-activity-header">
                                        <? $path = WWW_ROOT . 'img' . DS . 'service_images' . DS;
                                        $imgArr = array('source_path' => $path, 'img_name' => $cart_detail['image'], 'width' => 600, 'height' => 400, 'noimg' => $setting['site']['site_noimage']);
                                        $resizedImg = $this->ImageResize->ResizeImage($imgArr);
                                        echo $this->Html->image($resizedImg, array('border' => '0', 'alt' => $cart_detail['Service']['service_title'])); ?>

                                    </div>

                                </div>
                                <div class="col-sm-6 col-xs-12">
                                    <div class="checkout-activity-header-content" style="margin-top: 20px">
                                        <span style="float:right"><button class="delete-cart"
                                                data-url="/carts/delete_cart/<?php echo $cart_detail['Cart']['id']; ?>" data-toggle="modal" data-target="#myModal"><i class="fa fa-times"></i></button></span>
                                        <h6><?= $cart_detail['Service']['service_title']; ?></h6>

                                        <div class="checkout-activity-date">
                                            <strong><?= date(Configure::read('Calender_format_php'), strtotime($cart_detail['Cart']['start_date'])); ?>
                                                To <?= date(Configure::read('Calender_format_php'), strtotime($cart_detail['Cart']['end_date'])); ?></strong>
                                        </div>
                                    </div>
                                    <div class="checkout-activity-details">
                                        <?php $total_price = 0; ?>
                                        <? if (!empty($cart_detail['Cart']['slots'])) { ?>
                                            <? foreach ($cart_detail['Cart']['slots'] as $slot_key => $slot_time) { ?>
                                                <div class="checkout-activity-row">
                                                    <div class="checkout-activity-left">
                                                        Slot <?php echo $this->Time->meridian_format($slot_time['start_time']) . " To " . $this->Time->end_meridian_format($slot_time['end_time']); ?> </div>
                                                    <div
                                                        class="checkout-activity-right"><?= "$" . number_format($slot_time['price'], 2) ?></div>
                                                </div>
                                                <?php $total_price += $slot_time['price']; ?>
                                            <?php } ?>
                                        <? } ?>

                                        <div class="checkout-activity-row">
                                            <div class="checkout-activity-left">No. of Days</div>
                                            <div
                                                class="checkout-activity-right"><?= $cart_detail['Cart']['no_of_booking_days']; ?></div>
                                        </div>
                                        <div class="checkout-activity-row">
                                            <div
                                                class="checkout-activity-left"><?= $cart_detail['Cart']['no_participants'] - count(json_decode($cart_detail['Cart']['invite_friend_email'])) ?>
                                                x Adult(s)
                                            </div>
                                            <div
                                                class="checkout-activity-right"><?= $cart_detail['Cart']['no_participants'] - count(json_decode($cart_detail['Cart']['invite_friend_email'])) ?></div>
                                        </div>
                                        <? if (!empty($cart_detail['Cart']['value_added_price'])) { ?>
                                            <div class="checkout-activity-row">
                                                <div class="checkout-activity-left">Value Added Total(
                                                    $<?= number_format($cart_detail['Cart']['value_added_price'], 2); ?>
                                                    x<?= ($cart_detail['Cart']['invite_payment_status'] == 1) ? $cart_detail['Cart']['no_participants'] : 1 ?>
                                                    )
                                                </div>
                                                <div class="checkout-activity-right">
                                                    $<?= number_format(($cart_detail['Cart']['value_added_price'] * (($cart_detail['Cart']['invite_payment_status'] == 1) ? $cart_detail['Cart']['no_participants'] : 1)), 2); ?></div>
                                            </div>
                                        <? } ?>
                                        <? if (!empty($cart_detail['Cart']['price'])) { ?>
                                            <?
                                            if ($cart_detail['Cart']['no_of_booking_days'] == 1) {
                                                $no_interval = count($cart_detail['Cart']['slots']);
                                                $interval_msg = ($no_interval == 1) ? " Slot" : " Slots";
                                            } else {
                                                $no_interval = $cart_detail['Cart']['no_of_booking_days'];
                                                $interval_msg = ($no_interval == 1) ? " Day" : " Days";

                                            }



                                            ?>
                                            <div class="checkout-activity-row">
                                                <div class="checkout-activity-left">Service Price(
                                                    $<?= number_format($total_price, 2); ?>
                                                    x <?= ($cart_detail['Cart']['invite_payment_status'] == 1) ? $cart_detail['Cart']['no_participants'] : 1 ?>
                                                    <?= $interval_msg; ?>)
                                                </div>
                                                <div class="checkout-activity-right">
                                                    $<?= number_format(($total_price * (($cart_detail['Cart']['invite_payment_status'] == 1) ? $cart_detail['Cart']['no_participants'] : 1)), 2); ?></div>
                                            </div>
                                        <? } ?>
                                        <div class="checkout-activity-row checkout-activity-row-subtotal">
                                            <div class="checkout-activity-left">Subtotal</div>
                                            <div class="checkout-activity-right">
                                                $<? $slot_price = $cart_detail['Cart']['price'] + $value_added_price;
                                                echo number_format($cart_detail['Cart']['total_amount'], 2);?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="checkout-activity-cancel">
                                        <?= $this->Html->link('<i class=\"fa fa-times\"></i>', array('plugin' => false, 'controller' => 'carts', 'action' => 'delete_cart', $cart_detail['Cart']['id']), array('escape' => false, "onclick" => "return confirm('Are you sure want to remove this services?')")); ?>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <?
                                // total record count
                                $sub_total += $cart_detail['Cart']['total_amount'];
                            } //end of foreach ?>
                        <? } ?>
                    </div>
                    <?php if (isset($coupon)) { ?>
                        <div style="width:30%; margin:auto;">
                            <h4>Sub Total: <span style="float:right; clear:both"><?php echo '$'.number_format($sub_total, 2); ?></span></h4>
                            <h4>Discount: <span style="color:#F00; float:right; clear:both;"><?php echo '-$'.number_format($sub_total * ($coupon['Coupon']['discount']), 2); ?></span></h4>                        
                        </div>
                    <?php } ?>
                    <div class="checkout-activity-totals checkout-activity-row-total">
                        <div class="box-center">
                            <div class="checkout-activity-left">Grand Total</div>
                            <div class="checkout-activity-right">$<?php echo isset($coupon) ? number_format($sub_total * (1 - $coupon['Coupon']['discount']), 2) : number_format($sub_total, 2); ?></div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            <? } ?>

            <? if (empty($check_guest_status)) { ?>
                <?php echo $this->element('activity/cart_guest'); ?>
            <? } ?>
        </div>
    </section>
</div>
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" id="cancel-delete" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Do you really want to delete this activity from your cart?</h4>
            </div>
            <div class="modal-body">
                <p>If you already paid for this cart, deleting this before we receive the transaction will have your booking void, please wait when we receive the transaction from Paypal.
                Click CONTINUE if you wish to delete, or close this dialog to cancel.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" id="proceed-to-delete"  class="btn btnDefaults btnFillOrange" data-dismiss="modal">CONTINUE</button>
            </div>
        </div>

    </div>
</div>
<script type="text/javascript">
    $('#validate_code').on('click', function(){
        $.ajax({
            url: '/carts/ajax_validate_code',
            data: {'code':$('#code').val()},
            method: 'post',
            success: function(data) {
                if (data == 'true') {
                    $('#validate_message').css('visibility', 'visible').css('color', '#0F0').html('Code is valid.');
                    // reload the checkout page
                    location.reload();
                } else if (data == 'invalid') {
                    $('#validate_message').css('visibility', 'visible').css('color', '#F00').html('Code is invalid.');
                } else if (data == 'max_reached') {
                    $('#validate_message').css('visibility', 'visible').css('color', '#F00').html('Code has already reached max usage.');
                } else if (data == 'empty') {
                    $('#validate_message').css('visibility', 'visible').css('color', '#F00').html('Code is empty.');
                } else {
                    $('#validate_message').css('visibility', 'visible').css('color', '#F00').html('Error has occurred.');
                }
            }
        });
    });
</script>
<script type="text/javascript">
    <?php $path = $this->Html->webroot; ?>
    $(document).ready(function () {

        var deleteCartURL = "";
       $('.delete-cart').click(function(e){
           deleteCartURL = $(this).data('url');

       });



        $('#proceed-to-delete').click(function(){
            window.open(deleteCartURL,"_self");

        });
        $('#cancel-delete').click(function(){
            deleteCartURL = "";
        });

        $('#CartId').submit(function () {

            //var data = $(this).serializeArray();
            var data = new FormData(this);
            var formData = $(this);
            var status = 0;

            $.each(this, function (i, v) {
                $(v).removeClass('invalid form-error');
            });
            $('.error-message').remove();
            $('#CartId > span#for_owner_cms').show();
            $('#CartId > button[type=submit]').attr({'disabled': true});

            $.ajax({
                url: '<?=$path?>carts/validation/check_out',
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
                            $('#' + i).bind('click', function () {
                                $(this).removeClass('invalid form-error');
                                $(this).next().remove();
                            });
                        });
                        $("html, body").animate({scrollTop: 0}, "slow");
                        $('#CartId > button[type=submit]').attr({'disabled': false});
                        $('#CartId > span#for_owner_cms').hide();
                    } else {
                        status = 1;
                        console.log('success');
                        return true;
                    }

                }
            });


        });
        <? if(empty($guest_email)){ ?>
        $('#guest_login').submit(function () {

            //var data = $(this).serializeArray();
            var data = new FormData(this);
            var formData = $(this);

            var status = 0;
            $.each(this, function (i, v) {
                $(v).removeClass('invalid form-error');
            });

            $('.error-message').remove();
            $('#add_invite > span#for_owner_cms').show();
            $('input[type="submit"]').attr({'disabled': true});

            $.ajax({
                url: '<?=$path?>carts/guest_validation',
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
                            $('#' + i).addClass("invalid form-error").after('<div class="error-message" style="width:215px; margin-left:112px; text-align:left;">' + v + '</div>');
                            $('#' + i).bind('submit', function () {
                                $(this).removeClass('invalid form-error');
                                $(this).next().remove();
                            });
                        });
                        $("html, body").animate({scrollTop: 0}, "slow");
                        $('input[type="submit"]').attr({'disabled': false});
                    } else {
                        status = 1;
                        return true;
                    }

                }
            });


        });
        <? } ?>

    });
    function togelshow(id) {

        if (id == 'GuestLoginGuestLogin1') {

            $("#show_password").show();

            $(".guest-login-button-box #loginButton").val('Sign In');
            $("label[for=GuestLoginGuestLogin0]").removeClass('current');
            $("label[for=GuestLoginGuestLogin1]").addClass('current');

        } else {
            $("#show_password").hide();
            $(".guest-login-button-box #loginButton").val('Continue');
            $("label[for=GuestLoginGuestLogin1]").removeClass('current');
            $("label[for=GuestLoginGuestLogin0]").addClass('current');
        }

    }
</script>

