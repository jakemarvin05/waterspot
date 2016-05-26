<article class="activity-description">
    <?php if ( (count($amenities) + count($included) + count($extra) + count($details)) > 0 ): ?>
        <section class="activity-section details">
            <?php

            function splitContainers($data) {

                $counter = 0;
                $container1 = '';
                $container2 = '';

                $dataLength = count($data);


                foreach ($data as $attr):

                    if ($counter < $dataLength/2):
                        $container1 .= createAttributesHTML($attr);
                    else:
                        $container2 .= createAttributesHTML($attr);
                    endif;

                    $counter += 1;

                endforeach;


                $container1 = '<div class="activities-attributes-column-inner activities-attributes-column1">'.$container1.'</div>';
                $container2 = '<div class="activities-attributes-column-inner activities-attributes-column2">'.$container2.'</div>';

                $output = '<div class="activities-attributes-column-wrapper">'.$container1.$container2.'</div>';

                return $output;
            }

            function createAttributesHTML($attr) {
                $html = '';
                $html .= '<i class="'.$attr['icon_class'].'"></i> ';
                $html .= $attr['name'];
                $html .= ($attr['has_input'] ? ': <strong>' . $attr['value'] . '</strong>' : '');
                $html .= '<br>';

                return $html;
            }

            ?>

            <?php if (count($details) > 0): ?>

                <div class="activities-attributes-column activities-attributes-column0">
                    <p class="activities-attributes-header">About this <?php echo $header ? $header : 'activity' ?></p>
                </div>

                <?php echo splitContainers($details); ?>



            <?php endif; ?>

            <?php if (count($amenities) > 0): ?>
                <hr>
                <div class="activities-attributes-column activities-attributes-column0">
                    <p class="activities-attributes-header">Amenities provided</p>
                </div>

                <?php echo splitContainers($amenities); ?>

            <?php endif; ?>

            <?php if (count($included) > 0): ?>

                <hr>
                <div class="activities-attributes-column activities-attributes-column0">
                    <p class="activities-attributes-header">What are included</p>
                </div>
                <?php echo splitContainers($included); ?>

            <?php endif; ?>


            <?php if (count($extra) > 0): ?>
                <hr>
                <p style="color: #FC9524; font-weight: 100">*The extras listed below are not included in the price stated for this activity</p>
                <div class="activities-attributes-column activities-attributes-column0">
                    <p class="activities-attributes-header">Extras</p>
                </div>
                <?php echo splitContainers($extra); ?>
            <?php endif; ?>


        </section>
    <?php endif; ?>
    <section class="activity-section">
        <hr>
        <h3>Description</h3>
        <?=$service_detail['Service']['description'] ?>
    </section>
    <section class="activity-section">
        <hr>
        <h3>Itinerary</h3>
        <?=$service_detail['Service']['itinerary'] ?>
    </section>
    <section class="activity-section">
        <hr>
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