<div class="container-fluid vendor-panel">
    <? $payment_status = Configure::read('payment_status'); ?>
    <link rel="stylesheet" type="text/css" href="/css/fancybox/jquery.fancybox(new).css"/>
    <script type="text/javascript" src="/js/jquery.fancybox.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#ActivityStartDate").click();
        });
        $(document).ready(function () {
            $('.fancybox').fancybox();
        });
    </script>

    <div class="hr-line"></div>
    <div class="clear"></div>
    <?= $this->element('breadcrumbs'); ?>
    <h2 class="page-title">Booking <span style="color: #000;">Details</span></h2>
    <?= $this->element('VendorManager.left-vendor-panel'); ?>

    <div class="right-area col-sm-9 col-xs-12">
        <h3 class="dashboard-heading">Order Details</h3>
        <?= $this->element('message'); ?>
        <p class="details"><span>Order Number:</span> <? if (!empty($customer_detail['Booking']['ref_no'])) {
                echo $customer_detail['Booking']['ref_no'];
            } ?>
        </p>

        <p class="details"><span>Order Status:</span> <?= ($payment_status[$customer_detail['Booking']['status']]); ?>
        </p>

        <p class="details"><span>Transction ID:</span> <? if (!empty($customer_detail['Booking']['transaction_id'])) {
                echo $customer_detail['Booking']['transaction_id'];
            } ?>
        </p>
        <br>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="dashboard-content">
            <tr>
                <th width="5%">S.No.</th>
                <th width="20%">Service Name</th>
                <th width="15%">Location</th>
                <th width="10%">From</th>
                <th width="10%">To</th>
                <th width="15%">Invited Members</th>
                <th width="10%">Price ($)</th>
                <th width="5%">Slots</th>
                <!--	<th width="5%">VAS</th>-->
                <th width="5%">Status</th>

            </tr>
            <? if (!empty($order_details)) { ?>
                <? foreach ($order_details as $key => $order_detail) { ?>
                    <tr>
                        <td class="align-center"><?= $key + 1; ?>.</td>
                        <td><?= $order_detail['BookingOrder']['service_title'] ?></td>
                        <td><?= $order_detail['BookingOrder']['location_name'] ?></td>
                        <td><?= date(Configure::read('Calender_format_php'), strtotime($order_detail['BookingOrder']['start_date'])); ?></td>
                        <td><?= date(Configure::read('Calender_format_php'), strtotime($order_detail['BookingOrder']['end_date'])); ?></td>
                        <td>
                            <? if ($order_detail['BookingOrder']['no_participants'] != 1) { ?>
                                <?= $this->Html->link("<i class=\"fa fa-search\"></i>", array('plugin' => 'vendor_manager', 'controller' => 'bookings', 'action' => 'booking_member_invite_details', $order_detail['BookingOrder']['id']), array('escape' => false, 'class' => 'fancybox fancybox.iframe')); ?>
                                <span class="number">(<?= ($order_detail['BookingOrder']['no_participants'] - 1) ?>
                                    )</span>
                            <? } else {
                                echo "No Invited.";
                            } ?>
                        </td>
                        <td class="align-right">
                            $<?= number_format(($order_detail['BookingOrder']['total_amount']), 2); ?></td>
                        <td class="align-center"><?= $this->Html->link("<i class=\"fa fa-search\"></i>", array('plugin' => 'vendor_manager', 'controller' => 'bookings', 'action' => 'booking_slot_details', $order_detail['BookingOrder']['id']), array('escape' => false, 'class' => 'fancybox fancybox.iframe')); ?></td>
                        <!--						<td class="align-center">-->
                        <? //=$this->Html->link("<i class=\"fa fa-search\"></i>",array('plugin'=>'vendor_manager','controller'=>'bookings','action'=>'booking_vas_details',$order_detail['BookingOrder']['id']),array('escape' => false,'class'=>'fancybox fancybox.iframe'));?><!--</td>-->
                        <td><?= ($payment_status[$order_detail['BookingOrder']['status']]); ?></td>


                    </tr>
                <? } ?>
            <? } else { ?>
                <tr class="no-details">
                    <td colspan="11">There are no booking</td>
                </tr>
            <? } ?>
        </table>
        <? if (!empty($order_details)) { ?>
            <h3 class="dashboard-heading">Customer Information</h3>
            <table width="100%" border="0" cellpadding="0" cellspacing="0" class="dashboard-content">
                <tr class="text-center">
                    <th width="32%">Name</th>
                    <!--<th width="24%">Last Name</th>-->
                    <th width="32%">Email</th>
                    <th width="31%">Phone</th>
                </tr>
                <tr>
                    <td class="align-left"><?= $customer_detail['Booking']['fname'] ?></td>
                    <!--					<td class="align-center">-->
                    <? //=$customer_detail['Booking']['lname']?><!--</td>-->
                    <td class="align-left"><a
                            href="mailto:<?= $customer_detail['Booking']['email']; ?>"><?= $customer_detail['Booking']['email']; ?></a>
                    </td>
                    <td class="align-left"><a
                            href="callto:<?= $customer_detail['Booking']['phone']; ?>"><?= $customer_detail['Booking']['phone']; ?></a>
                    </td>
                </tr>
            </table>
        <? } ?>
        <? if (!empty($order_details)) { ?>
            <h3 class="dashboard-heading">Comment</h3>
            <div class="no-details">
                <? echo (!empty($customer_detail['Booking']['order_message'])) ? $customer_detail['Booking']['order_message'] : 'There are no order message'; ?>
            </div>
        <? } ?>
    </div>
</div>

<script type='text/javascript'>
    $(document).ready(function () {
        sameHeight('left-area', 'right-area');
    });
</script>