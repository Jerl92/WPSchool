jQuery(document).ready(function ($) {
    var $grid = $('.grid').packery({
        itemSelector: '.grid-item',
        columnWidth: 250
    });
      
      // make all grid-items draggable
    $grid.find('.grid-item').each( function( i, gridItem ) {
        var draggie = new Draggabilly( gridItem );
        // bind drag events to Packery
        $grid.packery( 'bindDraggabillyEvents', draggie );
    });    
});