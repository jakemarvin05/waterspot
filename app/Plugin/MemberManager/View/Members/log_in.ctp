<div class="container-fluid vendor-panel">

    <br/><br<br/><br/><br/><br><br>

    <div class="middle-area">
        <div class="registration-form-box">
            <?php if ($this->Session->check('Message.register_error')): ?>
                <div class="notification error">
                    <p><?= $this->Session->flash('register_error'); ?></p>
                </div>
            <?php endif; ?>
            <?php if ($this->Session->check('Message.error')): ?>
                <div class="error-message">
                    <p><?= $this->Session->flash('error'); ?></p>
                </div>
            <?php endif; ?>
            <br/>

            <p class="beforeHeader">Book a spot now!</p>

            <h1 class="headerAlt">MEMBER LOGIN</h1>
            <br/>

            <?php echo $this->Form->create('Member', array('action' => 'registration', 'name' => 'members', 'id' => 'MemberLoginPage', 'controller' => 'members', 'novalidate' => true, 'class' => 'login-form', 'url' => '/members/registration')); ?>
            <?= $this->Form->hidden('form-name', array('required' => false, 'value' => 'LoginForm')); ?>
            <div class="form-row">
                <?= $this->Form->email('email_id', array('required' => false, 'class' => 'form-control', 'placeholder' => 'Email')); ?><?= $this->Form->error('email_id', null, array('wrap' => 'div', 'class' => 'error-message')); ?>
            </div>
            <div class="form-row">

                <?= $this->Form->password('password', array('required' => false, 'class' => 'form-control', 'placeholder' => 'Password')); ?>
            </div>
            <div class="form-row">

                <button type="submit" class="btn btnDefaults btnFillOrange">Login</button>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>


    <script type="text/javascript">
        <?php $path = $this->Html->webroot; ?>
        $(document).ready(function () {
            $('#MemberLoginPage').submit(function () {
                //var data = $(this).serializeArray();
                var data = new FormData(this);
                var formData = $(this);
                var status = 0;

                $.each(this, function (i, v) {
                    $(v).removeClass('invalid form-error');
                });
                $('.error-message').remove();
                $('#MemberLoginPage > span#for_owner_cms').show();
                $('#MemberLoginPage > button[type=submit]').attr({'disabled': true});

                $.ajax({
                    url: '<?=$path?>member_manager/members/validation/login',
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
                    $('#MemberLoginPage > button[type=submit]').attr({'disabled': false});
                    $('#MemberLoginPage > span#for_owner_cms').hide();
                }
                return (status === 1) ? true : false;
            });
        });
    </script>

</div>

 
<script type="text/javascript">
    function run_test(){
    var params = {
                "key": "RcGToklPpGQ56uCAkEpY5A",
                "template_name": "dev_test_template",
                "template_content": [
                        {
                                "name": "TITLE",
                                "content": "test test test"
                        }
                ],
                "message": {
                        "subject": "test api email",
                        "from_email": "admin@waterspot.com.sg",
                        "from_name": "waterspot developer",
                        "to": [
                                {
                                        "email": "po.cruz17@gmail.com",
                                        "name": "po cruz",
                                        "type": "to"
                                }
                        ],
                        "global_merge_vars": [
                            {
                                "name": "USER_NAME",
                                "content": "Po Cruz"
                            },
                            {
                                "name": "REASON",
                                "content": "Because Pizza"
                            }
                        ]
                }
        };

    $.ajax({
        url: "https://mandrillapp.com/api/1.0/messages/send-template.json",
        type: "POST",
        crossDomain: true,
        data: params,
        dataType: "json",
        success: function (result) {
            alert(result);
        },
        error: function (xhr, ajaxOptions, thrownError) {
        alert(xhr.status);
        alert(thrownError);
        }
    });
}

// $('body').click(function(){
// // run_test();

// });

</script>
 



