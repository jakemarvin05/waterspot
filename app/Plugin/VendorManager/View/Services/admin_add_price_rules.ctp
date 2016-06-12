<script language="javascript">
    function saveform() {
        document.getElementById('ServiceSlotPublish').value = 1;
        document.getElementById('ServiceSlot').submit();
    }
</script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<?= $this->Html->script('admin/ajax_upload.js'); ?>

<div>
    <article>
        <header>
            <h2>
                <?php
                if (isset($this->request->data['ServiceSlot']['id']) && $this->request->data['ServiceSlot']['id']):
                    echo __('Update price rules');
                else:
                    echo __('Add price rules');
                    $this->request->data['ServiceSlot']['status'] = 1;
                endif;
                ?>
                [<?= $service_title ?>]
            </h2>

        </header>
    </article>
    <?php echo $this->element('admin/message'); ?>

    <?php if (!empty($price_rule_data)) {
        $weekdays = '';
        $weekends = '';
        $special = '';

        $table_weekdays_has_no_data = true;
        $table_weekends_has_no_data = true;
        $table_special_has_no_data = true;
        $rule_ctr = 0;

        // Loop through the set of rules
        foreach ($price_rule as $price_rule_name => $price_rule_params) {
            $weekdays .= '<tr>';
            $weekends .= '<tr>';
            $special .= '<tr>';
            $table_weekdays = '';
            $table_weekends = '';
            $table_special = '';
            $table_weekdays_data = [];
            $table_weekends_data = [];
            $table_special_data = [];
            $weekdays_rule_type_id = '';
            $weekends_rule_type_id = '';
            $special_rule_type_id = '';

            $table_weekdays .= '<td>' . ucfirst($price_rule_name) . '</td><td>';
            $table_weekends .= '<td>' . ucfirst($price_rule_name) . '</td><td>';
            $table_special .= '<td>' . ucfirst($price_rule_name) . '</td><td>';

            foreach ($price_rule_data as $data) {
                // check if params is in the current rule
                if ($price_rule_name == $data['Price']['rule_type']) {
                    switch ($data['Price']['slot_type']) {
                        case 1:
                            $weekdays_rule_type_id = $data['Price']['rule_type'];
                            $table_weekdays .= ucfirst($data['Price']['rule_key']) . ': ' . $data['Price']['rule_value'] . '</br>';
                            $table_weekdays_data[] = $data['Price']['rule_key'];
                            $table_weekdays_has_no_data = false;
                            break;
                        case 2:
                            $weekends_rule_type_id = $data['Price']['rule_type'];
                            $table_weekends .= ucfirst($data['Price']['rule_key']) . ': ' . $data['Price']['rule_value'] . '</br>';
                            $table_weekends_data[] = $data['Price']['rule_key'];
                            $table_weekends_has_no_data = false;
                            break;
                        case 3:
                            $special_rule_type_id = $data['Price']['rule_type'];
                            $table_special .= ucfirst($data['Price']['rule_key']) . ': ' . $data['Price']['rule_value'] . '</br>';
                            $table_special_data[] = $data['Price']['rule_key'];
                            $table_special_has_no_data = false;
                            break;
                        default:
                            break;
                    }

                }

            }

            $table_weekdays .= '</td>';
            $table_weekends .= '</td>';
            $table_special .= '</td>';

            // check for rule data and show if there are some
            if (count($table_weekdays_data) > 0) {
                // append the table data with the closing table row tag
                $table_weekdays .= '<td>' . $this->Html->link($this->Html->image('del.png'), array('plugin' => 'vendor_manager', 'controller' => 'services', 'action' => 'price_rule_delete', $service_id, 1, $weekdays_rule_type_id), array('escape' => false, "onclick" => "return confirm('Are you sure you wish to delete this slot?')")) . '</td>';
                $weekdays .= $table_weekdays . '</tr>';
            } else {
                if ($table_weekdays_has_no_data && $rule_ctr > 0) {
                    $weekdays .= '<td colspan="4">No rule data to display</td></tr>';
                }
            }
            // check for rule data and show if there are some
            if (count($table_weekends_data) > 0) {
                // append the table data with the closing table row tag
                $table_weekends .= '<td>' . $this->Html->link($this->Html->image('del.png'), array('plugin' => 'vendor_manager', 'controller' => 'services', 'action' => 'price_rule_delete', $service_id, 2, $weekends_rule_type_id), array('escape' => false, "onclick" => "return confirm('Are you sure you wish to delete this slot?')")) . '</td>';
                $weekends .= $table_weekends . '</tr>';
            } else {
                if ($table_weekends_has_no_data && $rule_ctr > 0) {
                    $weekends .= '<td colspan="4">No rule data to display</td></tr>';
                }
            }

            // check for rule data and show if there are some
            if (count($table_special_data) > 0) {
                // append the table data with the closing table row tag
                $table_special .= '<td>' . $this->Html->link($this->Html->image('del.png'), array('plugin' => 'vendor_manager', 'controller' => 'services', 'action' => 'price_rule_delete', $service_id, 3, $special_rule_type_id), array('escape' => false, "onclick" => "return confirm('Are you sure you wish to delete this slot?')")) . '</td>';
                $special .= $table_special . '</tr>';
            } else {
                if ($table_special_has_no_data && $rule_ctr > 0) {
                    $special .= '<td colspan="4">No rule data to display</td></tr>';
                }
            }

            $rule_ctr++;
        }

        ?>

        <h4>Weekday</h4>
        <table width="100%" style="margin-bottom:10px;">
            <tr>
                <td width="40%"><strong>Rule Name</strong></td>
                <td width="40%"><strong>Rules</strong></td>
                <td width="20%"><strong>Cancel</strong></td>
            </tr>
            <?php
            if (strlen($weekdays)) {
                echo $weekdays;
            } else {
                echo '<tr><td colspan="9">No slots defined</td></tr>';
            }
            ?>
        </table>

        <h4>Weekend</h4>
        <table width="100%" style="margin-bottom:10px;">
            <tr>
                <td width="40%"><strong>Rule Name</strong></td>
                <td width="40%"><strong>Rules</strong></td>
                <td width="20%"><strong>Cancel</strong></td>
            </tr>
            <?php
            if (strlen($weekends)) {
                echo $weekends;
            } else {
                echo '<tr><td colspan="9">No slots defined</td></tr>';
            }
            ?>
        </table>

        <h4>Special</h4>
        <table width="100%" style="margin-bottom:10px;">
            <tr>
                <td width="40%"><strong>Rule Name</strong></td>
                <td width="40%"><strong>Rules</strong></td>
                <td width="20%"><strong>Cancel</strong></td>
            </tr>
            <?php
            if (strlen($special)) {
                echo $special;
            } else {
                echo '<tr><td colspan="9">No slots defined</td></tr>';
            }
            ?>
        </table>
    <?php } else { ?>
        <div class="no-record">No slot is available here</div>
    <?php } ?>

    <h2 style="font-size: 155%; margin-top: 15px;">Add New Price Rule</h2>
    <?php echo $this->Form->create('Price', array('name' => 'servicetype', 'id' => 'add_price_rules', 'url' => array('plugin' => 'vendor_manager', 'controller' => 'services', 'action' => 'add_price_rules', $vendor_id, $service_id), 'onsubmit' => '//return validatefields();', 'type' => 'file', 'novalidate' => true)); ?>
    <?php echo $this->Form->input('id'); ?>
    <?php echo $this->Form->hidden('service_id', array('value' => $service_id)); ?>
    <?= $this->Form->hidden('status'); ?>
    <table border="0" cellspacing="0" cellpadding="10" width="100%">
        <tbody style="vertical-align: top">
        <tr>
            <td align="left"
                style="width: 105px;text-align: left;vertical-align: top;padding-top: 10px;padding-left: 20px;padding-right: 20px;">
                Type:
                <?= $this->Form->input('slot_type', array('type' => 'select', 'style' => 'height:30px', 'label' => false, 'div' => false, 'options' => $price_rule_slot_types)); ?>
                <?= $this->Form->error('slot_type', null, array('wrap' => 'div', 'class' => 'error-message')); ?>
            </td>
            <td style="text-align: left;">
                Rule:
                <span id="rule_type_selector">
                <?= $this->Form->input('rule_type', array('type' => 'select', 'style' => 'height:30px', 'label' => false, 'div' => false, 'options' => $price_rule_types, 'empty' => 'Select rule type')); ?>
                <?= $this->Form->error('rule_type', null, array('wrap' => 'div', 'class' => 'error-message')); ?>
                </span>
            </td>
            <td style="padding-right: 20px; text-align: right;"> <?php echo $this->Html->link('Cancel', array('controller' => 'services', 'action' => 'servicelist', $vendor_id)); ?></td>

        </tr>
        <tr>
            <td colspan="3" align="left"
                style="text-align: left; vertical-align: top; padding-top: 15px; padding-bottom: 20px; padding-left: 20px; padding-right: 20px">


                <table id="priceRuleTable" style="border: none; width: 50%">
                    <tbody style="border: none;">
                    <tr>
                        <td style="text-align: right">Maximum number of Pax:</td>
                        <td style="text-align: left">
                            <?= $this->Form->input('rule][0][rule_key]', array('type' => 'hidden', 'class' => 'price_rule_field', 'style' => 'width: 80px; margin-left: 20px', 'label' => false, 'div' => false, 'value' => 'max_pax')); ?>
                            <?= $this->Form->input('rule][0][rule_value]', array('type' => 'text', 'class' => 'price_rule_field', 'style' => 'width: 80px; margin-left: 20px', 'label' => false, 'div' => false, 'value' => '')); ?>
                            <?= $this->Form->error('rule][0][rule_value]', null, array('wrap' => 'div', 'class' => 'error-message')); ?>
                        </td>
                        <td>
                            <button class="buttonSubmit" type="submit">
                                <?php
                                if (isset($this->request->data['ServiceSlot']['id']) && $this->request->data['ServiceSlot']['id']):
                                    echo __('Update Price Rule');
                                else:
                                    echo __('Add Price Rule');
                                endif;
                                ?>
                            </button>
                        </td>

                    </tr>
                    <tr>
                        <td style="text-align: right">Price per Pax:</td>
                        <td style="text-align: left">
                            <?= $this->Form->input('rule][1][rule_key]', array('type' => 'hidden', 'class' => 'price_rule_field', 'style' => 'width: 80px; margin-left: 20px', 'label' => false, 'div' => false, 'value' => 'price_per_pax')); ?>
                            <?= $this->Form->input('rule][1][rule_value]', array('type' => 'text', 'class' => 'price_rule_field', 'style' => 'width: 80px; margin-left: 20px', 'label' => false, 'div' => false, 'value' => '')); ?>
                            <?= $this->Form->error('rule][1][rule_value]', null, array('wrap' => 'div', 'class' => 'error-message')); ?>
                        </td>
                        <td>
                            <button class="buttonSubmit" type="submit">
                                <?php
                                if (isset($this->request->data['ServiceSlot']['id']) && $this->request->data['ServiceSlot']['id']):
                                    echo __('Update Price Rule');
                                else:
                                    echo __('Add Price Rule');
                                endif;
                                ?>
                            </button>
                        </td>


                    </tr>
                    </tbody>
                </table>
                <script>

                    $('input.price_rule_field').focus(
                        function () {
                            $('form#add_price_rules button[type=submit]').removeAttr('disabled');
                        }
                    );
                    $('input.price_rule_field').focus(
                        function () {
                            $('form#add_price_rules button[type=submit]').removeAttr('disabled');
                        }
                    );
                </script>
            </td>
        </tr>
        </tbody>
    </table>
    <p class="error"></p>
    <?php echo $this->Form->end(); ?>
</div>
<script type="text/javascript">
    <?php $path = $this->Html->webroot; ?>
    $('table tr:empty').remove();

    var price_rules = JSON.parse('<?php echo $price_rule_json; ?>');
    $(document).ready(function () {
        // checking the rules that needs to be filled out

        $('#PriceSlotType').change(function () {
            var formElement = document.getElementById("add_price_rules");
            var formData = new FormData(formElement);
            $.ajax({
                url: '<?=$path?>vendor_manager/services/check_for_rules_by_slot_type',
                async: false,
                data: formData,
                dataType: 'json',
                type: 'post',
                cache: false,
                contentType: false,
                processData: false,
                success: function (res) {

                    if (res.success) {

                        var htmlSelect = res.data.html.replace(/\\/g, "").slice(1).slice(0, -1);
                        $('#rule_type_selector').empty().append(htmlSelect);
                        $('#PriceRuleType').val('');
                        $('#priceRuleTable').hide();

                        $('#PriceRuleType').change(function () {
                            // get the rule_key selected
                            var rule_type = $(this).val();
                            $('#priceRuleTable tbody').empty();
                            var price_rule_reference = res.data.rule_types_reference;
                            if (rule_type != '') {
                                for (var x in price_rule_reference) {

                                    // filter the right set of rules
                                    if (x == rule_type) {
                                        var ctr = 0;
                                        for (v in price_rule_reference[x]) {
                                            var html = '<tr>'
                                                + '<td style="text-align: right">' + (price_rule_reference[x][v]).capitalizeFirstLetter() + ':</td>'
                                                + '<td style="text-align: left">'
                                                + '<input type="hidden" name="data[Price][rule][' + ctr + '][rule_key]]" class="price_rule_field" style="width: 80px; margin-left: 20px" value="' + price_rule_reference[x][v] + '" id="PriceRule][' + ctr + '][ruleKey]">'
                                                + '<input name="data[Price][rule][' + ctr + '][rule_value]]" class="price_rule_field" style="width: 80px; margin-left: 20px" value="" type="text" id="PriceRule][' + ctr + '][ruleValue]">'
                                                + '</td>'
                                                + '</tr>';
                                            $('#priceRuleTable tbody').append(html);
                                            ctr++;
                                        }
                                    }

                                }

                                var buttonHtml = '<tr><td colspan="2">'
                                    + '<button class="buttonSubmit" type="submit">Add Price Rule</button>'
                                    + '</td></tr>';
                                $('#priceRuleTable tbody').append(buttonHtml);
                                $('#priceRuleTable').show();
                            }
                            else {
                                $('#priceRuleTable').hide();
                            }

                        });
                    }

                }
            });
        });

        $('#PriceRuleType').change(function () {
            // get the rule_key selected
            var rule_type = $(this).val();
            $('#priceRuleTable tbody').empty();
            $('#priceRuleTable').show();

            for (var x in price_rules) {
                // filter the right set of rules
                if (x == rule_type) {
                    var ctr = 0;
                    for (v in price_rules[x]) {
                        var html = '<tr>'
                            + '<td style="text-align: right">' + (price_rules[x][v]).capitalizeFirstLetter() + ':</td>'
                            + '<td style="text-align: left">'
                            + '<input type="hidden" name="data[Price][rule][' + ctr + '][rule_key]]" class="price_rule_field" style="width: 80px; margin-left: 20px" value="' + price_rules[x][v] + '" id="PriceRule][' + ctr + '][ruleKey]">'
                            + '<input name="data[Price][rule][' + ctr + '][rule_value]]" class="price_rule_field" style="width: 80px; margin-left: 20px" value="" type="text" id="PriceRule][' + ctr + '][ruleValue]">'
                            + '</td>'
                            + '</tr>';
                        $('#priceRuleTable tbody').append(html);

                        ctr++;
                    }

                }


            }
            var buttonHtml = '<tr><td colspan="2">'
                + '<button class="buttonSubmit" type="submit">Add Price Rule</button>'
                + '</td></tr>';
            $('#priceRuleTable tbody').append(buttonHtml);
        });

// Capitalize string prototype function
        String.prototype.capitalizeFirstLetter = function () {
            return this.charAt(0).toUpperCase() + this.slice(1);
        };


        $('#add_price_rules').submit(function (e) {
            var data = new FormData(this);
            var formData = $(this);
            var status = 0;
            $.each(this, function (i, v) {
                $(v).removeClass('invalid form-error');
            });
            $('.error-message').remove();
            $('#add_price_rules > span#for_owner_cms').show();
            $('#add_price_rules > button[type=submit]').attr({'disabled': true});

            $.ajax({
                url: '<?=$path?>vendor_manager/services/price_rule_validation',
                async: false,
                data: data,
                dataType: 'json',
                type: 'post',
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    $('p.error').empty();
                    if (data.error == 1) {
                        $.each(data.errors, function (i, v) {
                            var errorHtml = v + '<br>';

                            $('p.error').append(errorHtml);
                        });
                    } else {
                        status = 1;
                    }

                }
            });
            if (status == 0) {
                $("html, body").animate({scrollTop: $('body').height()}, "slow");
                $('#add_slots > button[type=submit]').attr({'disabled': false});
                $('#add_slots > span#for_owner_cms').hide();
            }
            return (status === 1) ? true : false;

        });

    });
</script>

