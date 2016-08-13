<?php echo $this->element('activity/listing', array('search_service_lists' => $activity_service_list)); ?>
<script type="text/javascript">
    page = <?=$this->paginator->counter('{:page}')?>;
    pages = <?=$this->paginator->counter('{:pages}')?>;
    $(function () {
        $('input.star').rating();
    });

    $(document).ready(function () {

        // Store the heights into a variable
        var heights = $(".activities-listing").map(function () {
            return $(this).height();
        }).get();
        // Get the max heights into a variable
        var maxHeight = Math.max.apply(null, heights);
        // Set the max heights into a variable
        $('.activities-listing').height(maxHeight);
    });
</script>

