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
    <?php if (!empty($price_rules)) {
        $weekdays = '';
        $weekends = '';
        $special = '';
        ?>

        <?php foreach ($price_rules as $price_rule) {
            $table_str = '<tr>';
            $table_str .= '<td colspan="1">' . $price_rule['Price']['price'] . '</td>';
            $table_str .= '<td colspan="1">' . $price_rule['Price']['min_pax'] . '</td>';
            $table_str .= '<td colspan="1">' . $price_rule['Price']['max_pax'] . '</td>';
            $table_str .= '<td colspan="1">' . $price_rule['Price']['price_per_pax'] . '</td>';
            $table_str .= '<td colspan="1">' . $price_rule['Price']['price_per_add_hour'] . '</td>';
            $table_str .= '<td colspan="1">' . $price_rule['Price']['max_add_hour'] . '</td>';
            $table_str .= '<td>' . $this->Html->link('<i class="edit-icon fa fa-edit"></i>', array('plugin' => 'vendor_manager', 'controller' => 'services', 'action' => 'price_rule_edit', $service_id, $price_rule['Price']['id']), array('escape' => false)) . '</td>';
            $table_str .= '<td>' . $this->Html->link($this->Html->image('del.png'), array('plugin' => 'vendor_manager', 'controller' => 'services', 'action' => 'price_rule_delete', $service_id, $price_rule['Price']['id']), array('escape' => false, "onclick" => "return confirm('Are you sure you wish to delete this slot?')")) . '</td>';
            $table_str .= '</tr>';

            if ($price_rule['Price']['slot_type'] == 1) {
                $weekdays .= $table_str;
            } else if ($price_rule['Price']['slot_type'] == 2) {
                $weekends .= $table_str;
            } else {
                $special .= $table_str;
            }
        }
        ?>

        <h4>Weekday</h4>
        <table width="100%" style="margin-bottom:10px;">
            <tr>
                <td colspan="1"><strong>Price</strong></td>
                <td colspan="1"><strong>Min no. of Pax</strong></td>
                <td colspan="1"><strong>Max no. of Pax</strong></td>
                <td colspan="1"><strong>Price per Pax</strong></td>
                <td colspan="1"><strong>Price per Additional Hour</strong></td>
                <td colspan="1"><strong>Max Additional Hour</strong></td>
                <td><strong>Edit</strong></td>
                <td><strong>Cancel</strong></td>
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
                <td colspan="1"><strong>Price</strong></td>
                <td colspan="1"><strong>Min no. of Pax</strong></td>
                <td colspan="1"><strong>Max no. of Pax</strong></td>
                <td colspan="1"><strong>Price per Pax</strong></td>
                <td colspan="1"><strong>Price per Additional Hour</strong></td>
                <td colspan="1"><strong>Max Additional Hour</strong></td>
                <td><strong>Edit</strong></td>
                <td><strong>Cancel</strong></td>
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
                <td colspan="1"><strong>Price</strong></td>
                <td colspan="1"><strong>Min no. of Pax</strong></td>
                <td colspan="1"><strong>Max no. of Pax</strong></td>
                <td colspan="1"><strong>Price per Pax</strong></td>
                <td colspan="1"><strong>Price per Additional Hour</strong></td>
                <td colspan="1"><strong>Max Additional Hour</strong></td>
                <td><strong>Edit</strong></td>
                <td><strong>Cancel</strong></td>
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
        <td align="left" style="text-align: left; vertical-align: top; padding-top: 20px; padding-left: 20px; padding-right: 20px">
                Type:
                <?= $this->Form->input('slot_type', array('type' => 'select', 'style' => 'height:30px', 'label' => false, 'div' => false, 'options' => $price_rule_types)); ?>
                <?= $this->Form->error('slot_type', null, array('wrap' => 'div', 'class' => 'error-message')); ?>
            </td>
        </tr>
        <tr>
            <td colspan="3" align="left"
                style="text-align: left; vertical-align: top; padding-top: 15px; padding-bottom: 20px; padding-left: 20px; padding-right: 20px">


                <table id="priceRuleTable" style="border: none;">
                    <tbody style="border: none;">
                    <tr>
                        <td style="border: none;">
                            <button class="deleteRule"
                                    style="border-radius: 16px; width: 30px; height: 30px; padding: 0px;" disabled
                                    type="button"><i class="fa fa-times" aria-hidden="true"></i></button>
                        </td>
                        <td style="border: none; text-align: right">
                            Price:

                        </td>
                        <td style="border: none;">
                            <?= $this->Form->input('price_rule][0][service_id]', array('type' => 'hidden', 'label' => false, 'div' => false, 'value' => $service_id)); ?>
                            <?= $this->Form->input('price_rule][0][price]', array('type' => 'text', 'style' => 'width: 40px;','class' => 'price_rule_field', 'label' => false, 'div' => false, 'value' => $default_service_price)); ?>
                            <?= $this->Form->error('price_rule][0][price]', null, array('wrap' => 'div', 'class' => 'error-message')); ?>
                        </td>
                        <td style="border: none; text-align: center">Min no. of Pax:</td>
                        <td style="border: none; text-align: left">
                            <?= $this->Form->input('price_rule][0][min_pax]', array('type' => 'text','class' => 'price_rule_field', 'style' => 'width: 40px;', 'label' => false, 'div' => false, 'value' => '')); ?>
                            <?= $this->Form->error('price_rule][0][min_pax]', null, array('wrap' => 'div', 'class' => 'error-message')); ?>
                        </td>
                        <td style="border: none; text-align: center">Max no. of Pax:</td>
                        <td style="border: none; text-align: left">
                            <?= $this->Form->input('price_rule][0][max_pax]', array('type' => 'text','class' => 'price_rule_field', 'style' => 'width: 40px;', 'label' => false, 'div' => false, 'value' => '')); ?>
                            <?= $this->Form->error('price_rule][0][max_pax]', null, array('wrap' => 'div', 'class' => 'error-message')); ?>
                        </td>
                        <td style="border: none; text-align: center">Price per Pax:</td>
                        <td style="border: none; text-align: left">
                            <?= $this->Form->input('price_rule][0][price_per_pax]', array('type' => 'text','class' => 'price_rule_field', 'style' => 'width: 40px;', 'label' => false, 'div' => false, 'value' => '')); ?>
                            <?= $this->Form->error('price_rule][0][price_per_pax]', null, array('wrap' => 'div', 'class' => 'error-message')); ?>
                        </td>
                        <td style="border: none; text-align: center">Price per Additional Hour:</td>
                        <td style="border: none; text-align: left">
                            <?= $this->Form->input('price_rule][0][price_per_add_hour]', array('type' => 'text','class' => 'price_rule_field', 'style' => 'width: 40px;', 'label' => false, 'div' => false, 'value' => '')); ?>
                            <?= $this->Form->error('price_rule][0][price_per_add_hour]', null, array('wrap' => 'div', 'class' => 'error-message')); ?>
                        </td>
                        <td style="border: none; text-align: center">Max Additional Hour:</td>
                        <td style="border: none; text-align: left">
                            <?= $this->Form->input('price_rule][0][max_add_hour]', array('type' => 'text','class' => 'price_rule_field', 'style' => 'width: 40px;', 'label' => false, 'div' => false, 'value' => '')); ?>
                            <?= $this->Form->error('price_rule][0][max_add_hour]', null, array('wrap' => 'div', 'class' => 'error-message')); ?>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <br>
                <p style="text-align: center">
                    <button type="button" id="addRule">Add Price Rule</button>
                </p>
                <script>
                    var ruleCtr = 0;
                    $('#addRule').click(function () {
                        ruleCtr++;
                        var html = '<tr>'
                            + '<td style="border: none;"><button class="deleteRule" style="border-radius: 16px; width: 30px; height: 30px; padding: 0px;" type="button"><i class="fa fa-times" aria-hidden="true"></i></button></td>'
                            + '<td style="border: none; text-align: right">Price: </td>'
                            + '<td style="border: none;">'
                            + '<?=$this->Form->input('price_rule][0][service_id]', array('type' => 'hidden', 'label' => false, 'div' => false, 'value' => $service_id));?>'
                            + '<?=$this->Form->input('price_rule][0][price]', array('type' => 'text','class' => 'price_rule_field', 'style' => 'width: 40px;','class' => 'price_rule_field', 'label' => false, 'div' => false, 'value' => $default_service_price));?>'
                            + '<?=$this->Form->error('price_rule][0][price]', null, array('wrap' => 'div', 'class' => 'error-message')); ?>'
                            + '</td>'
                            + '<td align="right" style="border: none; text-align: center">Min no. of Pax:</td>'
                            + '<td style="border: none; text-align: left;">'
                            + '<?=$this->Form->input('price_rule][0][min_pax]', array('type' => 'text', 'style' => 'width: 40px;','class' => 'price_rule_field', 'label' => false, 'div' => false, 'value' => ''));?>'
                            + '<?=$this->Form->error('price_rule][0][min_pax]', null, array('wrap' => 'div', 'class' => 'error-message')); ?>'
                            + '</td>'
                            + '<td align="right" style="border: none; text-align: center">Max no. of Pax:</td>'
                            + '<td style="border: none; text-align: left;">'
                            + '<?=$this->Form->input('price_rule][0][max_pax]', array('type' => 'text', 'style' => 'width: 40px;','class' => 'price_rule_field', 'label' => false, 'div' => false, 'value' => ''));?>'
                            + '<?=$this->Form->error('price_rule][0][max_pax]', null, array('wrap' => 'div', 'class' => 'error-message')); ?>'
                            + '</td>'
                            + '<td style="border: none; text-align: center">Price per Pax:</td>'
                            + '<td style="border: none; text-align: left">'
                            + '<?=$this->Form->input('price_rule][0][price_per_pax]', array('type' => 'text', 'style' => 'width: 40px;','class' => 'price_rule_field', 'label' => false, 'div' => false, 'value' => ''));?>'
                            + '<?=$this->Form->error('price_rule][0][price_per_pax]', null, array('wrap' => 'div', 'class' => 'error-message')); ?>'
                            + '</td>'
                            + '<td style="border: none; text-align: center">Price per Additional Hour:</td>'
                            + '<td style="border: none; text-align: left">'
                            + '<?=$this->Form->input('price_rule][0][price_per_add_hour]', array('type' => 'text', 'style' => 'width: 40px;','class' => 'price_rule_field', 'label' => false, 'div' => false, 'value' => ''));?>'
                            + '<?=$this->Form->error('price_rule][0][price_per_add_hour]', null, array('wrap' => 'div', 'class' => 'error-message')); ?>'
                            + '</td>'
                            + '<td style="border: none; text-align: center">Max Additional Hour:</td>'
                            + '<td style="border: none; text-align: left">'
                            + '<?=$this->Form->input('price_rule][0][max_add_hour]', array('type' => 'text', 'style' => 'width: 40px;','class' => 'price_rule_field', 'label' => false, 'div' => false, 'value' => ''));?>'
                            + '<?=$this->Form->error('price_rule][0][max_add_hour]', null, array('wrap' => 'div', 'class' => 'error-message')); ?>'
                            + '</td>'
                            + '</tr>';


                        html = html.replace(/\[0]/g, "[" + ruleCtr + "]");
                        // adding another rule
                        $('#priceRuleTable tbody').append(html);


                        // deleting a rule
                        $('.deleteRule').click(function () {
                            console.log('delete rule clicked...');
                            $(this).parents('tr')[0].remove();
                        });

                        $('input.price_rule_field').focus(
                            function(){
                                console.log('focused');
                                $('form#add_price_rules button[type=submit]').removeAttr('disabled');
                            }
                        );

                    });
                    $('input.price_rule_field').focus(
                        function(){
                            console.log('focused');
                            $('form#add_price_rules button[type=submit]').removeAttr('disabled');
                        }
                    );
                </script>
            </td>
        </tr>
        </tbody>
    </table>
    <br/><br/>
    <button type="submit">
        <?php
        if (isset($this->request->data['ServiceSlot']['id']) && $this->request->data['ServiceSlot']['id']):
            echo __('Update Price Rule');
        else:
            echo __('Add Price Rule');
        endif;
        ?>
    </button>
    or
    <?php echo $this->Html->link('Cancel', array('controller' => 'services', 'action' => 'servicelist', $vendor_id)); ?>
    <p class="error"></p>
    <?php echo $this->Form->end(); ?>
</div>
<script type="text/javascript">
    <?php $path = $this->Html->webroot; ?>
    $(document).ready(function () {
        $('#add_price_rules').submit(function () {
            //var data = $(this).serializeArray();
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
                            var errorHtml = v+'<br>';

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

