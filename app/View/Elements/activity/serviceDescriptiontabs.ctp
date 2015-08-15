<article class="activity-description">
    <section class="activity-section">
        <h3>Description</h3>
        <?=$service_detail['Service']['description'] ?>
    </section>
    <section class="activity-section">
        <h3>Itenerary</h3>
        <?=$service_detail['Service']['itinerary'] ?>
    </section>

    <section class="activity-section">
        <h3>How to get There</h3>
        <?=$service_detail['Service']['how_get_review'] ?>
        <div id="map-canvas" style="height:400px; width:100%;"></div>
        <script src="https://maps.googleapis.com/maps/api/js"></script>
        <script>
          function initialize() {
            geocoder = new google.maps.Geocoder();
            var latlng = new google.maps.LatLng(-34.397, 150.644);
            var mapOptions = {
              zoom: 15,
              center: latlng,
                          scrollwheel: false
            }
            map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
            geocoder.geocode( { 'address': "<?php echo str_replace(' ','+',$service_detail['location_name']); ?>"}, function(results, status) {
              if (status == google.maps.GeocoderStatus.OK) {
                map.setCenter(results[0].geometry.location);
                var marker = new google.maps.Marker({
                    map: map,
                    position: results[0].geometry.location
                });
              } else {
                alert("Geocode was not successful for the following reason: " + status);
              }
            });
          }
          google.maps.event.addDomListener(window, 'load', initialize);
        </script>
    </section>
</article>