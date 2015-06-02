 <?php
                 $i = $this->paginator->counter('{:start}');
                    //$i = 0;
                    foreach ($services as $service) {
                ?>
                    <li id="sort_<?= $service['ServiceType']['id'] ?>"  style="cursor:move" >
                        <table width="100%">
                            <tr>
                                <td width="5%"><?php echo $this->Form->checkbox('ServiceType.id.'.$i, array('value' => $service['ServiceType']['id'])); ?></td>
                                <td width="6%"><?php echo $i++; ?></td>
                               <td width="35%"><?php echo $service['ServiceType']['name']; ?></td>
                                <td width="35%"><? 
								$imgArr = array('source_path'=>'service_type','img_name'=>$service['ServiceType']['image'],'width'=>Configure::read('AdminConfig.image_edit_width'),'height'=>Configure::read('AdminConfig.image_edit_height'),'noimg'=>$setting['site']['site_noimage']);
								$resizedImg = $this->ImageResize->ResizeImage($imgArr);
								echo $this->Html->image($resizedImg,array('border'=>'0'));
								?>
								</td>
                                <td width="10%">
                                <?php
                                if ($service['ServiceType']['status'] == '1')
                                    echo $this->Html->image('admin/icons/icon_success.png', array());
                                else
                                    echo $this->Html->image('admin/icons/icon_error.png', array());
                                ?>
                                </td>
                                <td width="50%">
                                    <ul class="actions">
                                     <li><?php echo $this->Html->link('edit', array('controller' => 'service_types', 'action' => 'add', $service['ServiceType']['id']), array('escape' => false, 'class' => 'edit', 'title' => 'Edit Service', 'rel' => 'tooltip')); ?></li>
                                        <li>
                                        <?=$this->Html->link('view', array('controller' => 'service_types', 'action' => 'view', $service['ServiceType']['id']), array('escape' => false,'class'=>'view fancybox','title'=> __('View'),'rel'=>'tooltip'))?>
                                        
                                        </li>
                                    											
                                    </ul >


                                </td> 
                            </tr>
                        </table>
                    </li>
                    <?php } ?>
