<?php
   $i = $this->paginator->counter('{:start}');
foreach ($mails as $mail) {
    ?>
    <li id="sort_<?= $mail['Mail']['id'] ?>"  style="cursor:move" >
        <table width="100%">
            <tr>
                <td width="5%"><?php echo $this->Form->checkbox('Mail.id.'.$i, array('value' => $mail['Mail']['id'])); ?></td>
                <td width="6%"><?php echo $i++; ?></td>
                <td width="65%"><?php echo $mail['Mail']['mail_title']; ?></td>
                <td width="10%">
                    <?php
                    if ($mail['Mail']['status'] == '1')
                        echo $this->Html->image('admin/icons/icon_success.png', array());
                    else
                        echo $this->Html->image('admin/icons/icon_error.png', array());
                    ?>
                </td>
                <td width="50%">
                    <ul class="actions">
                              <li><?php echo $this->Html->link('edit', array('controller' => 'mail', 'action' => 'add', $mail['Mail']['id']), array('escape' => false, 'class' => 'edit', 'title' => 'Edit Mail', 'rel' => 'tooltip')); ?></li>
                                        <li>
                                        <?=$this->Html->link('view', array('controller' => 'mails', 'action' => 'view', $mail['Mail']['id']), array('escape' => false,'class'=>'view fancybox','title'=> __('View'),'rel'=>'tooltip'))?>
                                        
                                        </li>								
                    </ul >


                </td> 
            </tr>
        </table>
    </li>
<?php } ?>
