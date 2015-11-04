<article class="activity-description">
    <section class="activity-section">
        <h3>Description</h3>
        <?=$service_detail['Service']['description'] ?>
    </section>
    <section class="activity-section">
        <h3>Itinerary</h3>
        <?=$service_detail['Service']['itinerary'] ?>
    </section>
    <?php if ( (count($amenities) + count($included) + count($extra) + count($details)) > 0 ): ?>
        <section class="activity-section">
            <h3>Details</h3>
            <?php echo $header ? '<h4>About this ' . $header . '</h4>' : '' ?>
            <?php if (count($details) > 0): ?>
                <?php foreach ($details as $attr): ?>
                    <i class="<?php echo $attr['icon_class'] ?>"></i> <?php echo $attr['name'] . ($attr['has_input'] ? ' : ' . $attr['value'] : ''); ?> <br/>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if (count($amenities) > 0): ?>
                <h4>Amenities provided</h4>
                <?php foreach ($amenities as $attr): ?>
                    <i class="<?php echo $attr['icon_class'] ?>"></i> <?php echo $attr['name'] . ($attr['has_input'] ? ' : ' . $attr['value'] : ''); ?> <br/>
                <?php endforeach; ?>
            <?php endif; ?>
            
            <?php if (count($included) > 0): ?>
                <h4>What are included</h4>
                <?php foreach ($included as $attr): ?>
                    <i class="<?php echo $attr['icon_class'] ?>"></i> <?php echo $attr['name'] . ($attr['has_input'] ? ' : ' . $attr['value'] : ''); ?> <br/>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if (count($extra) > 0): ?>
                <h4>Extras</h4>
                <?php foreach ($extra as $attr): ?>
                    <i class="<?php echo $attr['icon_class'] ?>"></i> <?php echo $attr['name'] . ($attr['has_input'] ? ' : ' . $attr['value'] : ''); ?> <br/>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
    <?php endif; ?>

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