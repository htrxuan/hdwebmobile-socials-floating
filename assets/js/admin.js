jQuery(document).ready(function($){
    if ($('.hdsocials-color-picker').length) {
        $('.hdsocials-color-picker').wpColorPicker();
    }

    // Initialize jQuery UI Sortable for Social Links
    var $sortableTable = $('.hdsocials-drag-handle').closest('tbody');
    if ($sortableTable.length) {
        $sortableTable.sortable({
            handle: '.hdsocials-drag-handle',
            items: '> tr',
            placeholder: 'hdsocials-sortable-placeholder',
            forcePlaceholderSize: true,
            axis: 'y',
            helper: function(e, ui) {
                // Return a helper with the original widths preserved
                ui.children().each(function() {
                    $(this).width($(this).width());
                });
                return ui;
            },
            update: function(event, ui) {
                // Update hidden inputs after drop
                $sortableTable.find('tr').each(function(index) {
                    // index + 1 is the new order
                    $(this).find('.hdsocials-order-input').val(index + 1);
                });
            }
        });
    }

    // Handle Business Hours Status Changes
    $('.hdsocials-bh-status').on('change', function() {
        var $row = $(this).closest('tr');
        var status = $(this).val();
        var $times = $row.find('.hdsocials-bh-times');
        
        if (status === 'open') {
            $times.css({
                'opacity': '1',
                'pointer-events': 'auto'
            });
        } else {
            $times.css({
                'opacity': '0.4',
                'pointer-events': 'none'
            });
        }
    });

});
