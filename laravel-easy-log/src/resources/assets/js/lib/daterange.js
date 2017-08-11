export const lel_daterange = {
    init: function () {

        // daterange
        $('#result-daterange').daterangepicker({
            autoUpdateInput: false,
            startDate: new Date("07/21/2017"),
            endDate: new Date(),
            locale: {
                cancelLabel: 'Clear'
            }
        });
        $('#result-daterange').on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
        });

        $('#result-daterange').on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
        });


        // daterange was set
        if ($('#date-range-for-JS').length) {
            let range = $('#date-range-for-JS').val().split('-');
            $('#result-daterange').daterangepicker({startDate: range[0].trim(), endDate: range[1].trim()});
        }

    }

}
