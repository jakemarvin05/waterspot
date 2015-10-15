<?php if (count($amenities_attributes) > 0): ?>
<div class="labelbox">
    <label>Amenities:</label>
</div>
<?php foreach ($amenities_attributes as $attribute): ?>
    <label for="attribute<?php echo $attribute['Attribute']['id'] ?>"> <?php echo $attribute['Attribute']['name'] ?> </label> <?php echo $this->Form->checkbox('attributes][', ['value' => $attribute['Attribute']['id'], 'id' => 'attribute' . $attribute['Attribute']['id'], 'checked' => in_array($attribute['Attribute']['id'], $selected_attributes) ]); ?> <br />
<?php endforeach ?>
<?php endif ?>

<?php if (count($include_attributes) > 0): ?>
<div class="labelbox">
    <label>Includes:</label>
</div>
<?php foreach ($include_attributes as $attribute): ?>
    <label for="attribute<?php echo $attribute['Attribute']['id'] ?>"> <?php echo $attribute['Attribute']['name'] ?> </label> <?php echo $this->Form->checkbox('attributes][', ['value' => $attribute['Attribute']['id'], 'id' => 'attribute' . $attribute['Attribute']['id'], 'checked' => in_array($attribute['Attribute']['id'], $selected_attributes) ]); ?> <br />
<?php endforeach ?>
<?php endif ?>

<?php if (count($extra_attributes) > 0): ?>
<div class="labelbox">
    <label>Extra:</label>
</div>
<?php foreach ($extra_attributes as $attribute): ?>
    <label for="attribute<?php echo $attribute['Attribute']['id'] ?>"> <?php echo $attribute['Attribute']['name'] ?> </label> <?php echo $this->Form->checkbox('attributes][', ['value' => $attribute['Attribute']['id'], 'id' => 'attribute' . $attribute['Attribute']['id'], 'checked' => in_array($attribute['Attribute']['id'], $selected_attributes) ]); ?> <br />
<?php endforeach ?>
<?php endif ?>