define(['jquery', 'report_graphic/Chart.bundle'], function($, Chart) {
    return {
        'init' : function(id) {

            var charttext = $(document.getElementById(id)).text();
            var chartdata = $.parseJSON(charttext);
            var canvaselement = $('#' + id + 'canvas');
            new Chart(canvaselement, chartdata);
        }
    };
});