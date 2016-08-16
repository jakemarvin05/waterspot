<?php echo $this->element('activity/listing', array('search_service_lists' => $activity_service_list)); ?>
<script type="text/javascript">
    page = <?=$this->paginator->counter('{:page}')?>;
    pages = <?=$this->paginator->counter('{:pages}')?>;
    $(function () {
        $('input.star').rating();
    });

    ( function( $, window, document, undefined )
    {
        'use strict';

        var $list       = $( '.activities' ),
            $items      = $list.find( '.activities-listing' ),
            setHeights  = function()
            {
                $items.css( 'height', 'auto' );

                var perRow = Math.floor( $list.width() / $items.width() );
                if( perRow == null || perRow < 2 ) return true;

                for( var i = 0, j = $items.length; i < j; i += perRow )
                {
                    var maxHeight   = 0,
                        $row        = $items.slice( i, i + perRow );

                    $row.each( function()
                    {
                        var itemHeight = parseInt( $( this ).outerHeight() );
                        if ( itemHeight > maxHeight ) maxHeight = itemHeight;
                    });
                    $row.css( 'height', maxHeight );
                }
            };

        setHeights();
        $( window ).on( 'resize', setHeights );
        $list.find( 'img' ).on( 'load', setHeights );

    })( jQuery, window, document );
</script>

