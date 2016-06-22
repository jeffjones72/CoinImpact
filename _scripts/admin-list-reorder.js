$(document).ready(function() {
    pos_field = 'tabindex';
    pos_col = null;
    cols = $('#result_list tbody tr:first').children()
    for (i = 0; i < cols.length; i++) {
        inputs = $(cols[i]).find('input[name*=' + pos_field + ']')
        if (inputs.length > 0) {
            pos_col = i;
            break;
        }
    }
    if (pos_col == null) {
        return;
    }
    header = $('#result_list thead tr').children()[pos_col]
    $(header).css('width', '1em')
    $(header).children('a').text('#')
    $('#result_list tbody tr').each(function(index) {
        pos_td = $(this).children()[pos_col]
        input = $(pos_td).children('input').first()
        input.hide()
        label = $('<strong>' + input.attr('value') + '</strong>')
        $(pos_td).append(label)
    });
    sorted = $('#result_list thead th.sorted')
    sorted_col = $('#result_list thead th').index(sorted)
    sort_order = sorted.hasClass('descending') ? 'desc' : 'asc';
    if (sorted_col != pos_col) {
        console.info("Sorted column is not %s, bailing out", pos_field);
        return;
    }
    $('#result_list tbody tr').css('cursor', 'move')
    $('#result_list tbody').sortable({
        axis: 'y',
        items: 'tr',
        cursor: 'move',
        update: function(event, ui) {
            item = ui.item
            items = $(this).find('tr').get()
            if (sort_order == 'desc') {
                // Reverse order
                items.reverse()
            }
            $(items).each(function(index) {
                pos_td = $(this).children()[pos_col]
                input = $(pos_td).children('input').first()
                label = $(pos_td).children('strong').first()
                input.attr('value', index)
                label.text(index)
            });
            $(this).find('tr').removeClass('row1').removeClass('row2')
            $(this).find('tr:even').addClass('row1')
            $(this).find('tr:odd').addClass('row2')
        }
    });
});
