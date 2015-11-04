 
<script language="javascript">
function saveform()
{
	document.getElementById('ServiceTypePublish').value=1;
	document.getElementById('ServiceType').submit();
}
</script>

<div>
    <article>
        <header>
            <h2>Edit Service Attribute</h2>
        </header>
    </article>
    <?php echo $this->element('admin/message');?>
    <?php echo $this->Form->create('Attribute',array('name'=>'servicetype','id'=>'ServiceType','action'=>'edit_attribute_save'))?>
    <?php echo $this->Form->hidden('id'); ?>
    <fieldset>
      <h3>Add New</h3>
      <dl>
        <dt>Name</dt>
        <dd><?php echo $this->Form->text('name', array('required'=>true)); ?></dd>
        
        <dt>Type</dt>
        <dd><?php echo $this->Form->select('type', array('options' => [0 => 'Detail', 1 => 'Amenity', 2 => 'Inlcuded', 3 => 'Extra'])); ?></dd>
        
        <dt>Accepts Value</dt>
        <dd><?php echo $this->Form->checkbox('has_input'); ?></dd>
        
        <dt>Icon Class</dt>
        <dd><?php echo $this->Form->text('icon_class', array('required'=>true)); ?></dd>
      </dl>
    </fieldset>
    <button type="submit">Add</button>
    <?php echo $this->Form->end();?>

</div>