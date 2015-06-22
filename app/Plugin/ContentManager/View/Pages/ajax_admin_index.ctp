<?php
$i = $this->paginator->counter('{:start}');
foreach ($pages as $page) {
    ?>
    <li id="sort_<?= $page['Page']['id'] ?>"  style="cursor:move" >
        <table width="100%">
            <tr>
                <td width="5%"><?php echo $this->Form->checkbox('Page.id.'.$i, array('value' => $page['Page']['id'])); ?></td>
                <td width="6%"><?php echo $i++; ?></td>
                <td width="65%"><?php echo $page['Page']['name']; ?></td>
                <td width="10%">
                    <?php
                    if ($page['Page']['status'] == '1')
                        echo $this->Html->image('admin/icons/icon_success.png', array());
                    else
                        echo $this->Html->image('admin/icons/icon_error.png', array());
                    ?>
                </td>
                <td width="50%">
                    <ul class="actions">
                        <li><?php echo $this->Html->link('edit', array('controller' => 'pages', 'action' => 'edit', $page['Page']['parent_id'], $page['Page']['id']), array('escape' => false, 'class' => 'edit', 'title' => 'Edit Page', 'rel' => 'tooltip')); ?></li>
                        <li><a href="#view<?php echo $page['Page']['id']; ?>" id="various<?php echo $page['Page']['id']; ?>"  class="view" title="View Page" rel="tooltip">view</a></li>
                        <li><?php if (in_array($page['Page']['id'], array('36', '3', '2', '27', '50', '46', '48'))) { ?><?php echo $this->Html->link('Manage Sub Content', array('controller' => 'pages', 'action' => 'index', $page['Page']['id'], str_replace(' ', '_', strtolower($page['Page']['name']))), array('escape' => false, 'class' => 'subcontent', 'title' => 'Manage Sub Content', 'rel' => 'tooltip')); ?>
                            <?php } ?></li>										
                    </ul >


                </td> 
            </tr>
        </table>
    </li>
<?php } ?>
