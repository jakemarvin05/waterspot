 
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
            <h2>Add Service Attribute - <?php echo $this->request->data['ServiceType']['title'] ?></h2>
        </header>
    </article>
    <?php echo $this->element('admin/message');?>
    <table>
      <tr>
        <th>Name</th>
        <th>Type</th>
        <th>Accepts Value</th>
        <th>Icon</th>
        <th>Action</th>
      </tr>
      <?php foreach ($attributes as $attr): ?>
      <tr>
        <td><?php echo $attr['Attribute']['name'] ?></td>
        <td><?php echo $attr['Attribute']['type'] == 1 ? 'Amenity' : ($attr['Attribute']['type'] == 2 ? 'Included' : ($attr['Attribute']['type'] == 3 ? 'Extra' : 'Detail') ) ?></td>
        <td><?php echo $attr['Attribute']['has_input'] == 1 ? 'True' : 'False' ?></td>
        <td><?php echo $attr['Attribute']['icon_class'] ? $attr['Attribute']['icon_class'] : 'N/A' ?></td>
        <td>
          <ul class="actions">
            <li><a href="/admin/service_manager/attributes/remove_attribute_save/<?php echo $attr['Attribute']['id']; ?>" title="remove" class="delete"></a></li>
            <li><a href="/admin/service_manager/service_types/edit_attribute/<?php echo $attr['Attribute']['id']; ?>" title="edit" class="edit">edit</a></li>
          </ul>          
        </td>
      </tr>
      <?php endforeach; ?>
    </table>

    <hr/>
    <?php echo $this->Form->create('ServiceType',array('name'=>'servicetype','id'=>'ServiceType','action'=>'set_header'))?>
    <fieldset>
      <h3>"About this" header</h3>
      <dl>
        <dt>About this</dt>
        <dd><?php echo $this->Form->text('header'); ?><button type="submit">Change Header</button></dd>
      </dl>
    </fieldset>
    <?php echo $this->Form->end();?>

    <hr/>
    <?php echo $this->Form->create('Attribute',array('name'=>'servicetype','id'=>'ServiceType','action'=>'add_attribute_save'))?>
    <?php echo $this->Form->hidden('service_type_id', ['value' => $this->request->data['ServiceType']['id']]); ?>
    <fieldset>
      <h3>Add New</h3>
      <dl>
        <dt>Name</dt>
        <dd><?php echo $this->Form->text('name', array('required'=>true)); ?></dd>
        
        <dt>Type</dt>
        <dd><?php echo $this->Form->select('type', array('options' => [0 => 'Detail', 1 => 'Amenity', 2 => 'Included', 3 => 'Extra'])); ?></dd>
        
        <dt>Accepts Value</dt>
        <dd><?php echo $this->Form->checkbox('has_input'); ?></dd>
        
        <dt>Icon Class</dt>
        <dd><?php echo $this->Form->text('icon_class', array('required'=>true)); ?></dd>
      </dl>
    </fieldset>
    <button type="submit">Add</button>
    <?php echo $this->Form->end();?>

</div>