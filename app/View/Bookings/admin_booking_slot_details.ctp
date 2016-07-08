<h3 class="small">Slots Details</h3>
<table width="100%" cellpadding="0" cellspacing="0" border="0" class="admin-content">
    <tr>
        <th width="10%">S.No.</th>
        <th width="30%">Date</th>
        <th width="20%">Slot Timings</th>
        <th width="25%">Additional Hour</th>
    </tr>
    <?

    $affected_slot_tr = '';

    if (!empty($booking_slots)) {
        ?>
        <? foreach ($booking_slots as $key => $booking_slot) { ?>
            <?php if ($booking_slot['BookingSlot']['status'] < 2) { ?>
                <tr>
                    <td class="align-center"><?= ($key + 1); ?>.</td>
                    <td class="align-center"><?= date(Configure::read('Calender_format_php'), strtotime($booking_slot['BookingSlot']['start_time'])); ?></td>
                    <td class="align-center"><? echo $this->Time->meridian_format($booking_slot['BookingSlot']['start_time']); ?>
                        To <?= $this->Time->end_meridian_format($booking_slot['BookingSlot']['end_time']); ?></td>
                    <td class="align-center"><? echo $booking_slot['BookingSlot']['add_hour']; ?></td>
                </tr>
                <?
            } else {
                $affected_slot_tr .= '<tr>';
                $affected_slot_tr .= '<td class="align-center">' . $key . '</td>';
                $affected_slot_tr .= '<td class="align-center">' . date(Configure::read('Calender_format_php'), strtotime($booking_slot['BookingSlot']['start_time'])) . '</td>';
                $affected_slot_tr .= '<td class="align-center">' . $this->Time->meridian_format($booking_slot['BookingSlot']['start_time']) . ' To ' . $this->Time->end_meridian_format($booking_slot['BookingSlot']['end_time']) . '</td>';
                $affected_slot_tr .= '<td class="align-center">&nbsp;</td>';
                $affected_slot_tr . '</tr>';
            }
        } // end of foreach
        ?>

    <? } else { ?>
        <tr class="no-details">
            <td class="align-center" colspan="3">There are no booking slots.</td>
        </tr>
    <? } ?>
</table>
<?php if ($affected_slot_tr != ''){ ?>

<h4 style="margin: 0px 0px 8px;">Affected Slots</h4>

<table width="100%" cellpadding="0" cellspacing="0" border="0" class="admin-content">
    <tr>
        <th width="10%">S.No.</th>
        <th width="30%">Date</th>
        <th width="20%">Slot Timings</th>
        <th width="25%">&nbsp;</th>
    </tr>
    <?php
    echo $affected_slot_tr;
    } ?>
