export const lel_multiselect = {
    init: function () {

        // multiselect
        $('#result-level').multiselect({
            includeSelectAllOption: true,
            selectAllValue: 'select-all-value',
            enableFiltering: true,
            filterPlaceholder: 'Filter level ...',
            buttonWidth: '50%'
        });
        $('#result-search-column').multiselect({
            includeSelectAllOption: true,
            selectAllValue: 'select-all-value',
            enableFiltering: true,
            filterPlaceholder: 'Columns to be search ...',
            buttonWidth: '50%'
        });

    }
}
