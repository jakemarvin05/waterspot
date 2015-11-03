<div class="container-fluid topResponsivePadding">

<script type="text/javascript">
	function formsubmit(action) {
		var flag = true;
	    if (flag)
        {
            document.getElementById('action').value = action;
            if (validate())
                document.getElementById('ServiceDeleteForm').submit();
        }
    }
    function validate() {
        var ans = "0";
        for (i = 0; i < document.service.elements.length; i++) {
            if (document.service.elements[i].type == "checkbox") {
                if (document.service.elements[i].checked) {
                    ans = "1";
                    break;
                }
            }
        }
        if (ans == "0") {
            alert("Please select service(s) to " + document.getElementById('action').value);
            return false;
        } else {
            var answer = confirm('Are you sure you want to ' + document.getElementById('action').value + ' Service(s)');
            if (!answer)
                return false;
        }
        return true;
    }

    function CheckAll(chk)
    {
        var fmobj = document.getElementById('ServiceDeleteForm');
        for (var i = 0; i < fmobj.elements.length; i++)
        {
            var e = fmobj.elements[i];
            if (e.type == 'checkbox')
                fmobj.elements[i].checked = document.getElementById('ServiceCheck').checked;
        }

    }
    
</script>

<link rel="stylesheet" type="text/css" href="/css/tooltipster.css" />
<link rel="stylesheet" type="text/css" href="/css/vendor/vendor-panel.css" />
<script type="text/javascript" src="/js/jquery.tooltipster.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		 $('.tooltip').tooltipster();
	});
</script>

<div class="hr-line"></div>
<div class="clear"></div>

<h2 class="page-title">My Services</h2>
<?=$this->element('VendorManager.left-vendor-panel');?>
	<div class="right-area col-sm-9 col-xs-12">
		<?=$this->element('message');?>
		<div class="dashboard-form-row special">
			<h3 class="dashboard-heading special" style="float: left;">Service Attribute</h3>
		</div>
		<div class="clearfix"></div>
		<?php if ( (count($details) + count($amenities) + count($included) + count($extra)) > 0 ): ?>
		<?php echo $this->Form->create('ServiceAttribute',array('name'=>'service_attribute','id'=>'ServiceType','action'=>'save_attributes'))?>
			<?php echo $this->Form->hidden('service_id', ['value' => $service_id]); ?>
			<table>
				<tr>
					<th>check</th>
					<th>Name</th>
					<th>value</th>
				</tr>

				<tr><td colspan="3"><h4>Details</h4></td></tr>
				<?php foreach ($details as $attr): ?>
					<tr>
						<td><?php echo $this->Form->checkbox('attributes][', ['value' => $attr['attribute_id'], 'checked' => $attr['is_checked']]); ?></td>
						<td><?php echo $attr['name']; ?></td>
						<td><?php echo $attr['has_input'] ? $this->Form->text('attribute_value_'.$attr['attribute_id'], ['value' => $attr['value']]) : 'N/A' ?></td>
					</tr>
				<?php endforeach; ?>

				<tr><td colspan="3"><h4>Amenities Provided</h4></td></tr>
				<?php foreach ($amenities as $attr): ?>
					<tr>
						<td><?php echo $this->Form->checkbox('attributes][', ['value' => $attr['attribute_id'], 'checked' => $attr['is_checked']]); ?></td>
						<td><?php echo $attr['name']; ?></td>
						<td><?php echo $attr['has_input'] ? $this->Form->text('attribute_value_'.$attr['attribute_id'], ['value' => $attr['value']]) : 'N/A' ?></td>
					</tr>
				<?php endforeach; ?>
				<tr><td colspan="3"><h4>What are Included</h4></td></tr>
				<?php foreach ($included as $attr): ?>
					<tr>
						<td><?php echo $this->Form->checkbox('attributes][', ['value' => $attr['attribute_id'], 'checked' => $attr['is_checked']]); ?></td>
						<td><?php echo $attr['name']; ?></td>
						<td><?php echo $attr['has_input'] ? $this->Form->text('attribute_value_'.$attr['attribute_id'], ['value' => $attr['value']]) : 'N/A' ?></td>
					</tr>
				<?php endforeach; ?>
				<tr><td colspan="3"><h4>Extra</h4></td></tr>
				<?php foreach ($extra as $attr): ?>
					<tr>
						<td><?php echo $this->Form->checkbox('attributes][', ['value' => $attr['attribute_id'], 'checked' => $attr['is_checked']]); ?></td>
						<td><?php echo $attr['name']; ?></td>
						<td><?php echo $attr['has_input'] ? $this->Form->text('attribute_value_'.$attr['attribute_id'], ['value' => $attr['value']]) : 'N/A' ?></td>
					</tr>
				<?php endforeach; ?>
				<tr>
					<td colspan="3">
						<button type="submit" class="dashboard-buttons dashboard-buttons btn orange">Save Attributes</button>
					</td>
				</tr>
			</table>
   		<?php echo $this->Form->end();?>
   		<?php else: ?>
   			<h4>There are no attributes that you can set.</h4>
   		<?php endif; ?>
	</div>
</div>
	
</div>
<script type='text/javascript'>
	$(document).ready(function () {
		sameHeight('left-area','right-area');
	});
</script>