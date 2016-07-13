<script>
    $(document).ready(function(){
        // Notification Close Button
        $('.close-notification').click(
            function () {
                $(this).parent().fadeTo(350, 0, function () {$(this).slideUp(600);});
                return false;
            }
        );
    })
</script>
<?php
if ($this->Session->check('Message.flash')): ?>
<div class="notification jake success">
<a href="#" class="close-notification">x</a>
<p><?=$this->Session->flash(); ?></p>
</div>
<?php endif;?>



<?php if ($this->Session->check('Message.error')): ?>
<div class="notification error">
<a href="#" class="close-notification">x</a>
<p><?=$this->Session->flash('error'); ?></p>
</div>
<?php endif;?>
