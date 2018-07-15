window.$ = window.jQuery = require("../../../vendor/almasaeed2010/adminlte/bower_components/jquery/dist/jquery.min.js");
require("../../../vendor/almasaeed2010/adminlte/bower_components/bootstrap/dist/js/bootstrap.min.js");
require("../../../vendor/almasaeed2010/adminlte/bower_components/fastclick/lib/fastclick.js");
require("../../../vendor/almasaeed2010/adminlte/dist/js/adminlte.min.js");
require("../../../vendor/almasaeed2010/adminlte/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js");
require("../../../vendor/almasaeed2010/adminlte/plugins/jvectormap/jquery-jvectormap-world-mill-en.js");
require("../../../vendor/almasaeed2010/adminlte/bower_components/jquery-slimscroll/jquery.slimscroll.min.js");
window.Chart = require("../../../vendor/almasaeed2010/adminlte/bower_components/chart.js/Chart.js");


$(function () {
    'use strict';
    /* ChartJS
     * -------
     * Charts
     */

    // sales example chart
    var salesChartCanvas = $('#salesChart').get(0).getContext('2d');
    var salesChart = new Chart(salesChartCanvas);
    var salesChartData = {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
        datasets: [{
                label: 'Electronics',
                fillColor: 'rgb(210, 214, 222)',
                strokeColor: 'rgb(210, 214, 222)',
                pointColor: 'rgb(210, 214, 222)',
                pointStrokeColor: '#c1c7d1',
                pointHighlightFill: '#fff',
                pointHighlightStroke: 'rgb(220,220,220)',
                data: [65, 59, 80, 81, 56, 55, 40]
            },
            {
                label: 'Digital Goods',
                fillColor: 'rgba(60,141,188,0.9)',
                strokeColor: 'rgba(60,141,188,0.8)',
                pointColor: '#3b8bba',
                pointStrokeColor: 'rgba(60,141,188,1)',
                pointHighlightFill: '#fff',
                pointHighlightStroke: 'rgba(60,141,188,1)',
                data: [28, 48, 40, 19, 86, 27, 90]
            }
        ]
    };
    var salesChartOptions = {
        showScale: true,
        scaleShowGridLines: false,
        scaleGridLineColor: 'rgba(0,0,0,.05)',
        scaleGridLineWidth: 1,
        scaleShowHorizontalLines: true,
        scaleShowVerticalLines: true,
        bezierCurve: true,
        bezierCurveTension: 0.3,
        pointDot: false,
        pointDotRadius: 4,
        pointDotStrokeWidth: 1,
        pointHitDetectionRadius: 20,
        datasetStroke: true,
        datasetStrokeWidth: 2,
        datasetFill: true,
        legendTemplate: '<ul class=\'<%=name.toLowerCase()%>-legend\'><% for (var i=0; i<datasets.length; i++){%><li><span style=\'background-color:<%=datasets[i].lineColor%>\'></span><%=datasets[i].label%></li><%}%></ul>',
        maintainAspectRatio: true,
        responsive: true
    };
    salesChart.Line(salesChartData, salesChartOptions);

    // -------------
    // - PIE CHART -
    // -------------
    var pieChartCanvas = $('#pieChart').get(0).getContext('2d');
    var pieChart = new Chart(pieChartCanvas);
    var PieData = [{
            value: 700,
            color: '#f56954',
            highlight: '#f56954',
            label: 'Chrome'
        },
        {
            value: 500,
            color: '#00a65a',
            highlight: '#00a65a',
            label: 'IE'
        },
        {
            value: 400,
            color: '#f39c12',
            highlight: '#f39c12',
            label: 'FireFox'
        },
        {
            value: 600,
            color: '#00c0ef',
            highlight: '#00c0ef',
            label: 'Safari'
        },
        {
            value: 300,
            color: '#3c8dbc',
            highlight: '#3c8dbc',
            label: 'Opera'
        },
        {
            value: 100,
            color: '#d2d6de',
            highlight: '#d2d6de',
            label: 'Navigator'
        }
    ];
    var pieOptions = {
        segmentShowStroke: true,
        segmentStrokeColor: '#fff',
        segmentStrokeWidth: 1,
        percentageInnerCutout: 50,
        animationSteps: 100,
        animationEasing: 'easeOutBounce',
        animateRotate: true,
        animateScale: false,
        responsive: true,
        maintainAspectRatio: false,
        legendTemplate: '<ul class=\'<%=name.toLowerCase()%>-legend\'><% for (var i=0; i<segments.length; i++){%><li><span style=\'background-color:<%=segments[i].fillColor%>\'></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>',
        tooltipTemplate: '<%=value %> <%=label%> users'
    };
    pieChart.Doughnut(PieData, pieOptions);


    /* jVector Maps
     * ------------
     * Create a world map with markers
     */
    $('#world-map-markers').vectorMap({
        map: 'world_mill_en',
        normalizeFunction: 'polynomial',
        hoverOpacity: 0.7,
        hoverColor: false,
        backgroundColor: 'transparent',
        regionStyle: {
            initial: {
                fill: 'rgba(210, 214, 222, 1)',
                'fill-opacity': 1,
                stroke: 'none',
                'stroke-width': 0,
                'stroke-opacity': 1
            },
            hover: {
                'fill-opacity': 0.7,
                cursor: 'pointer'
            },
            selected: {
                fill: 'yellow'
            },
            selectedHover: {}
        },
        markerStyle: {
            initial: {
                fill: '#00a65a',
                stroke: '#111'
            }
        },
        markers: [{
                latLng: [41.90, 12.45],
                name: 'Vatican City'
            },
            {
                latLng: [43.73, 7.41],
                name: 'Monaco'
            },
            {
                latLng: [-0.52, 166.93],
                name: 'Nauru'
            },
            {
                latLng: [-8.51, 179.21],
                name: 'Tuvalu'
            },
            {
                latLng: [43.93, 12.46],
                name: 'San Marino'
            },
            {
                latLng: [47.14, 9.52],
                name: 'Liechtenstein'
            },
            {
                latLng: [7.11, 171.06],
                name: 'Marshall Islands'
            },
            {
                latLng: [17.3, -62.73],
                name: 'Saint Kitts and Nevis'
            },
            {
                latLng: [3.2, 73.22],
                name: 'Maldives'
            },
            {
                latLng: [35.88, 14.5],
                name: 'Malta'
            },
            {
                latLng: [12.05, -61.75],
                name: 'Grenada'
            },
            {
                latLng: [13.16, -61.23],
                name: 'Saint Vincent and the Grenadines'
            },
            {
                latLng: [13.16, -59.55],
                name: 'Barbados'
            },
            {
                latLng: [17.11, -61.85],
                name: 'Antigua and Barbuda'
            },
            {
                latLng: [-4.61, 55.45],
                name: 'Seychelles'
            },
            {
                latLng: [7.35, 134.46],
                name: 'Palau'
            },
            {
                latLng: [42.5, 1.51],
                name: 'Andorra'
            },
            {
                latLng: [14.01, -60.98],
                name: 'Saint Lucia'
            },
            {
                latLng: [6.91, 158.18],
                name: 'Federated States of Micronesia'
            },
            {
                latLng: [1.3, 103.8],
                name: 'Singapore'
            },
            {
                latLng: [1.46, 173.03],
                name: 'Kiribati'
            },
            {
                latLng: [-21.13, -175.2],
                name: 'Tonga'
            },
            {
                latLng: [15.3, -61.38],
                name: 'Dominica'
            },
            {
                latLng: [-20.2, 57.5],
                name: 'Mauritius'
            },
            {
                latLng: [26.02, 50.55],
                name: 'Bahrain'
            },
            {
                latLng: [0.33, 6.73],
                name: 'São Tomé and Príncipe'
            }
        ]
    });
});



//---------------------------------------------------------END OF CHARTS------------------------------------------------------