 
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
            <h2>Manage Service Attribute</h2>
        </header>
    </article>
    <?php echo $this->element('admin/message');?>
    <?php if ( (count($details) + count($amenities) + count($included) + count($extra)) > 0 ): ?>
    <?php echo $this->Form->create('ServiceAttribute',array('name'=>'service_attribute','id'=>'ServiceType','action'=>'save_attributes'))?>
      <?php echo $this->Form->hidden('service_id', ['value' => $service_id]); ?>
      <table style="text-align:left;">
        <tr>
          <th style="text-align:left;">check</th>
          <th style="text-align:left;">Name</th>
          <th style="text-align:left;">value</th>
        </tr>

        <tr><td colspan="3" style="text-align:left;"><h4>Details</h4></td></tr>
        <?php foreach ($details as $attr): ?>
          <tr>
            <td style="text-align:left;"><?php echo $this->Form->checkbox('attributes][', ['value' => $attr['attribute_id'], 'checked' => $attr['is_checked']]); ?></td>
            <td style="text-align:left;"><?php echo $attr['name']; ?></td>
            <td style="text-align:left;"><?php echo $attr['has_input'] ? $this->Form->text('attribute_value_'.$attr['attribute_id'], ['value' => $attr['value']]) : 'N/A' ?></td>
          </tr>
        <?php endforeach; ?>

        <tr><td colspan="3" style="text-align:left;"><h4>Amenities Provided</h4></td></tr>
        <?php foreach ($amenities as $attr): ?>
          <tr>
            <td style="text-align:left;"><?php echo $this->Form->checkbox('attributes][', ['value' => $attr['attribute_id'], 'checked' => $attr['is_checked']]); ?></td>
            <td style="text-align:left;"><?php echo $attr['name']; ?></td>
            <td style="text-align:left;"><?php echo $attr['has_input'] ? $this->Form->text('attribute_value_'.$attr['attribute_id'], ['value' => $attr['value']]) : 'N/A' ?></td>
          </tr>
        <?php endforeach; ?>
        <tr><td colspan="3" style="text-align:left;"><h4>What are Included</h4></td></tr>
        <?php foreach ($included as $attr): ?>
          <tr>
            <td style="text-align:left;"><?php echo $this->Form->checkbox('attributes][', ['value' => $attr['attribute_id'], 'checked' => $attr['is_checked']]); ?></td>
            <td style="text-align:left;"><?php echo $attr['name']; ?></td>
            <td style="text-align:left;"><?php echo $attr['has_input'] ? $this->Form->text('attribute_value_'.$attr['attribute_id'], ['value' => $attr['value']]) : 'N/A' ?></td>
          </tr>
        <?php endforeach; ?>
        <tr><td colspan="3" style="text-align:left;"><h4>Extra</h4></td></tr>
        <?php foreach ($extra as $attr): ?>
          <tr>
            <td style="text-align:left;"><?php echo $this->Form->checkbox('attributes][', ['value' => $attr['attribute_id'], 'checked' => $attr['is_checked']]); ?></td>
            <td style="text-align:left;"><?php echo $attr['name']; ?></td>
            <td style="text-align:left;"><?php echo $attr['has_input'] ? $this->Form->text('attribute_value_'.$attr['attribute_id'], ['value' => $attr['value']]) : 'N/A' ?></td>
          </tr>
        <?php endforeach; ?>
        <tr>
          <td colspan="3" style="text-align:left;">
            <button type="submit" class="dashboard-buttons dashboard-buttons btn orange">Save Attributes</button>
          </td>
        </tr>
      </table>
      <?php echo $this->Form->end();?>
      <?php else: ?>
        <h4>There are no attributes that you can set.</h4>
      <?php endif; ?>

</div>