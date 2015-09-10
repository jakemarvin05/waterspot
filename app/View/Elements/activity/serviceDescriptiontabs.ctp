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