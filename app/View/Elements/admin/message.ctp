<?php if ($this->Session->check('Message.flash')): ?>
<div class="notification success">
<a href="#" class="close-notification" title="Hide Notification" rel="tooltip">x</a>
<p><?=$this->Session->flash(); ?></p>
</div>
<?php endif;?>



<?php if ($this->Session->check('Message.error')): ?>
<div class="notification error">
<a href="#" class="close-notification" title="Hide Notification" rel="tooltip">x</a>
<p><?=$this->Session->flash('error'); ?></p>
</div>
<?php endif;?>
