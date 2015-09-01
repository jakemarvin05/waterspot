<div class="container-fluid wrapper accounts-page">

    <header class="page-header row text-center">
        <p class="beforeHeader">Reset Your Password Here</p>

        <h1 class=" headerAlt">Account Settings</h1>
    </header>

    <div class="middle-area row">
        <div class="login-middle col-sm-6 col-sm-offset-3">
            <div class="login member-reg">
                <?= $this->element('message'); ?>
                <?php echo $this->Form->create('Vendor', array('name' => 'user', 'url' => array('plugin' => 'vendor_manager', 'controller' => 'accounts', 'action' => 'passwordurl/' . $str), 'class' => 'quick-contacts1', 'onSubmit' => '//return validatefields()')); ?>
                <div class="form-row">
                    <?= $this->Form->hidden('form-name', array('required' => false, 'value' => 'PasswordUrlForm')); ?>
                    <?= $this->Form->password('password', array('required' => false, 'class' => 'form-control', 'placeholder' => 'New Password:')); ?>

                </div>
                <div class="form-row">
                    <?= $this->Form->password('password2', array('required' => false, 'class' => 'form-control', 'placeholder' => 'Confirm Password:')); ?>
                </div>
                <input type="submit" value="Reset Now" class="smt2 btn btnDefaults btnFillOrange registration_button">
                <?php echo $this->Form->end(); ?>

            </div>
        </div>
    </div>
</div>
<div class="clear"></div>
</div>

<script type="text/javascript">
    <?php $path = $this->Html->webroot; ?>
    $(document).ready(function () {
        $('#VendorPasswordurlForm').submit(function () {
            var data = new FormData(this);
            var formData = $(this);
            var status = 0;
            $.each(this, function (i, v) {
                $(v).removeClass('invalid form-error');
            });
            $('.error-message').remove();
            $('#VendorPasswordurlForm > span#for_owner_cms').show();
            $('#VendorPasswordurlForm > button[type=submit]').attr({'disabled': true});

            $.ajax({
                url: '<?=$path?>vendor_manager/accounts/validation',
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
                            $('#' + i).addClass("invalid form-error").after('<div class="error-message">' + v + '</div>');
                            $('#' + i).bind('click', function () {
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
                $('#VendorPasswordurlForm > button[type=submit]').attr({'disabled': false});
                $('#VendorPasswordurlForm > span#for_owner_cms').hide();
            }
            return (status === 1) ? true : false;
        });
    });
</script>
</div>
