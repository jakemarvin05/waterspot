 
<script language="javascript">
function saveform()
{
	document.getElementById('SocialPublish').value=1;
	document.getElementById('Socialmedia').submit();
}
</script>

<div>
    <article>
        <header>
            <h2>
                <?php
                 
                     echo  __('Update Image Setting');
                  
                ?>
            </h2>
        </header>
    </article>
	
    <?php echo $this->element('admin/message');?>
    <?=$this->Form->create('Setting',array('name'=>'siteconfig','url'=>array('controller'=>'settings','action'=>'imagesetting'),'type' => 'file','onsubmit'=>'return validate();' ))?>
    <fieldset>
        <dl>
            <dt style="width: 150px;">
                <label>Gallery Image Size <span style="color:red;">*</span></label>
            </dt>
            <dd style="left: 160px;">
                <?=$this->Form->text('galary_image_width',array('class'=> 'small','size'=>'70','required'=>false,'style'=>'width:10%','value'=>$data['Setting']['galary_image_width'])); ?>
                <label>X</label>
                <?=$this->Form->text('galary_image_height',array('class'=> 'small','size'=>'70','required'=>false,'style'=>'width:10%','value'=>$data['Setting']['galary_image_height'])); ?>
             
            </dd>
                      
             <dt style="width: 150px;">
                <label>Gallery Admin Image Size<span style="color:red;">*</span></label>
            </dt>
            <dd style="left: 160px;">
                <?=$this->Form->text('galary_admin_image_width',array('class'=> 'small','size'=>'70','required'=>false,'style'=>'width:10%','value'=>$data['Setting']['galary_admin_image_width'])); ?>
                <label>X</label>
                <?=$this->Form->text('galary_admin_image_height',array('class'=> 'small','size'=>'70','required'=>false,'style'=>'width:10%','value'=>$data['Setting']['galary_admin_image_height'])); ?>
                
            </dd>
        
        </dl>
    </fieldset>
	<button type="submit"><?=__('Update');?></button>                              
	<?php echo $this->Form->end();?>
</div>

