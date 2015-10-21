<article class="activity-description">
    <section class="activity-section">
        <h3>Description</h3>
        <?=$service_detail['Service']['description'] ?>
    </section>
    <section class="activity-section">
        <h3>Itinerary</h3>
        <?=$service_detail['Service']['itinerary'] ?>
    </section>
    <section class="activity-section">
        <h3>Attributes</h3>
        <?php if (count($amenities) > 0): ?>
            <h4>Amenities</h4>
            <?php foreach ($amenities as $attr): ?>
                <i class="<?php echo $attr['icon_class'] ?>"></i> <?php echo $attr['name'] . ($attr['has_input'] ? ' : ' . $attr['value'] : ''); ?> <br/>
            <?php endforeach; ?>
        <?php endif; ?>
        
        <?php if (count($included) > 0): ?>
            <h4>Included</h4>
            <?php foreach ($included as $attr): ?>
                <i class="<?php echo $attr['icon_class'] ?>"></i> <?php echo $attr['name'] . ($attr['has_input'] ? ' : ' . $attr['value'] : ''); ?> <br/>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if (count($extra) > 0): ?>
            <h4>Extra</h4>
            <?php foreach ($extra as $attr): ?>
                <i class="<?php echo $attr['icon_class'] ?>"></i> <?php echo $attr['name'] . ($attr['has_input'] ? ' : ' . $attr['value'] : ''); ?> <br/>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>

    <section class="activity-section">
        <h3>How to get There</h3>
        <?=$service_detail['Service']['how_get_review'] ?>
        <div id="map-canvas" style="height:400px; width:100%;"></div>
        <script src="https://maps.googleapis.com/maps/api/js"></script>
        <script>
        $(document).ready(function() {
          var mapper = Object.create(Mapper);
          mapper.previousLocation = "<?php echo str_replace(' ','+',(isset($service_detail['Service']['location_string'])?$service_detail['Service']['location_string']:$service_detail['location_name'])); ?>";
          mapper.init();
        });

        </script>
    </section>
</article>