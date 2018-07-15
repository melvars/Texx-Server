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


/**
 * AdminLTE Demo Menu
 * ------------------
 * You should not use this file in production.
 * This file is for demo purposes only.
 */
$(function () {
    'use strict';

    /**
     * Get access to plugins
     */

    $('[data-toggle="control-sidebar"]').controlSidebar()
    $('[data-toggle="push-menu"]').pushMenu()

    var $pushMenu = $('[data-toggle="push-menu"]').data('lte.pushmenu')
    var $controlSidebar = $('[data-toggle="control-sidebar"]').data('lte.controlsidebar')
    var $layout = $('body').data('lte.layout')

    /**
     * List of all the available skins
     *
     * @type Array
     */
    var mySkins = [
        'skin-blue',
        'skin-black',
        'skin-red',
        'skin-yellow',
        'skin-purple',
        'skin-green',
        'skin-blue-light',
        'skin-black-light',
        'skin-red-light',
        'skin-yellow-light',
        'skin-purple-light',
        'skin-green-light'
    ]

    /**
     * Get a prestored setting
     *
     * @param String name Name of of the setting
     * @returns String The value of the setting | null
     */
    function get(name) {
        if (typeof (Storage) !== 'undefined') {
            return localStorage.getItem(name)
        } else {
            window.alert('Please use a modern browser to properly view this template!')
        }
    }

    /**
     * Store a new settings in the browser
     *
     * @param String name Name of the setting
     * @param String val Value of the setting
     * @returns void
     */
    function store(name, val) {
        if (typeof (Storage) !== 'undefined') {
            localStorage.setItem(name, val)
        } else {
            window.alert('Please use a modern browser to properly view this template!')
        }
    }

    /**
     * Toggles layout classes
     *
     * @param String cls the layout class to toggle
     * @returns void
     */
    function changeLayout(cls) {
        $('body').toggleClass(cls)
        $layout.fixSidebar()
        if ($('body').hasClass('fixed') && cls == 'fixed') {
            $pushMenu.expandOnHover()
            $layout.activate()
        }
        $controlSidebar.fix()
    }

    /**
     * Replaces the old skin with the new skin
     * @param String cls the new skin class
     * @returns Boolean false to prevent link's default action
     */
    function changeSkin(cls) {
        $.each(mySkins, function (i) {
            $('body').removeClass(mySkins[i])
        })

        $('body').addClass(cls)
        store('skin', cls)
        return false
    }

    /**
     * Retrieve default settings and apply them to the template
     *
     * @returns void
     */
    function setup() {
        var tmp = get('skin')
        if (tmp && $.inArray(tmp, mySkins))
            changeSkin(tmp)

        // Add the change skin listener
        $('[data-skin]').on('click', function (e) {
            if ($(this).hasClass('knob'))
                return
            e.preventDefault()
            changeSkin($(this).data('skin'))
        })

        // Add the layout manager
        $('[data-layout]').on('click', function () {
            changeLayout($(this).data('layout'))
        })

        $('[data-controlsidebar]').on('click', function () {
            changeLayout($(this).data('controlsidebar'))
            var slide = !$controlSidebar.options.slide

            $controlSidebar.options.slide = slide
            if (!slide)
                $('.control-sidebar').removeClass('control-sidebar-open')
        })

        $('[data-sidebarskin="toggle"]').on('click', function () {
            var $sidebar = $('.control-sidebar')
            if ($sidebar.hasClass('control-sidebar-dark')) {
                $sidebar.removeClass('control-sidebar-dark')
                $sidebar.addClass('control-sidebar-light')
            } else {
                $sidebar.removeClass('control-sidebar-light')
                $sidebar.addClass('control-sidebar-dark')
            }
        })

        $('[data-enable="expandOnHover"]').on('click', function () {
            $(this).attr('disabled', true)
            $pushMenu.expandOnHover()
            if (!$('body').hasClass('sidebar-collapse'))
                $('[data-layout="sidebar-collapse"]').click()
        })

        //  Reset options
        if ($('body').hasClass('fixed')) {
            $('[data-layout="fixed"]').attr('checked', 'checked')
        }
        if ($('body').hasClass('layout-boxed')) {
            $('[data-layout="layout-boxed"]').attr('checked', 'checked')
        }
        if ($('body').hasClass('sidebar-collapse')) {
            $('[data-layout="sidebar-collapse"]').attr('checked', 'checked')
        }

    }

    // Create the new tab
    var $tabPane = $('<div />', {
        'id': 'control-sidebar-theme-demo-options-tab',
        'class': 'tab-pane active'
    })

    // Create the tab button
    var $tabButton = $('<li />', {
            'class': 'active'
        })
        .html('<a href=\'#control-sidebar-theme-demo-options-tab\' data-toggle=\'tab\'>' +
            '<i class="fa fa-wrench"></i>' +
            '</a>')

    // Add the tab button to the right sidebar tabs
    $('[href="#control-sidebar-home-tab"]')
        .parent()
        .before($tabButton)

    // Create the menu
    var $demoSettings = $('<div />')

    // Layout options
    $demoSettings.append(
        '<h4 class="control-sidebar-heading">' +
        'Layout Options' +
        '</h4>'
        // Fixed layout
        +
        '<div class="form-group">' +
        '<label class="control-sidebar-subheading">' +
        '<input type="checkbox"data-layout="fixed"class="pull-right"/> ' +
        'Fixed layout' +
        '</label>' +
        '<p>Activate the fixed layout. You can\'t use fixed and boxed layouts together</p>' +
        '</div>'
        // Boxed layout
        +
        '<div class="form-group">' +
        '<label class="control-sidebar-subheading">' +
        '<input type="checkbox"data-layout="layout-boxed" class="pull-right"/> ' +
        'Boxed Layout' +
        '</label>' +
        '<p>Activate the boxed layout</p>' +
        '</div>'
        // Sidebar Toggle
        +
        '<div class="form-group">' +
        '<label class="control-sidebar-subheading">' +
        '<input type="checkbox"data-layout="sidebar-collapse"class="pull-right"/> ' +
        'Toggle Sidebar' +
        '</label>' +
        '<p>Toggle the left sidebar\'s state (open or collapse)</p>' +
        '</div>'
        // Sidebar mini expand on hover toggle
        +
        '<div class="form-group">' +
        '<label class="control-sidebar-subheading">' +
        '<input type="checkbox"data-enable="expandOnHover"class="pull-right"/> ' +
        'Sidebar Expand on Hover' +
        '</label>' +
        '<p>Let the sidebar mini expand on hover</p>' +
        '</div>'
        // Control Sidebar Toggle
        +
        '<div class="form-group">' +
        '<label class="control-sidebar-subheading">' +
        '<input type="checkbox"data-controlsidebar="control-sidebar-open"class="pull-right"/> ' +
        'Toggle Right Sidebar Slide' +
        '</label>' +
        '<p>Toggle between slide over content and push content effects</p>' +
        '</div>'
        // Control Sidebar Skin Toggle
        +
        '<div class="form-group">' +
        '<label class="control-sidebar-subheading">' +
        '<input type="checkbox"data-sidebarskin="toggle"class="pull-right"/> ' +
        'Toggle Right Sidebar Skin' +
        '</label>' +
        '<p>Toggle between dark and light skins for the right sidebar</p>' +
        '</div>'
    )
    var $skinsList = $('<ul />', {
        'class': 'list-unstyled clearfix'
    })

    // Dark sidebar skins
    var $skinBlue =
        $('<li />', {
            style: 'float:left; width: 33.33333%; padding: 5px;'
        })
        .append('<a href="javascript:void(0)" data-skin="skin-blue" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">' +
            '<div><span style="display:block; width: 20%; float: left; height: 7px; background: #367fa9"></span><span class="bg-light-blue" style="display:block; width: 80%; float: left; height: 7px;"></span></div>' +
            '<div><span style="display:block; width: 20%; float: left; height: 20px; background: #222d32"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7"></span></div>' +
            '</a>' +
            '<p class="text-center no-margin">Blue</p>')
    $skinsList.append($skinBlue)
    var $skinBlack =
        $('<li />', {
            style: 'float:left; width: 33.33333%; padding: 5px;'
        })
        .append('<a href="javascript:void(0)" data-skin="skin-black" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">' +
            '<div style="box-shadow: 0 0 2px rgba(0,0,0,0.1)" class="clearfix"><span style="display:block; width: 20%; float: left; height: 7px; background: #fefefe"></span><span style="display:block; width: 80%; float: left; height: 7px; background: #fefefe"></span></div>' +
            '<div><span style="display:block; width: 20%; float: left; height: 20px; background: #222"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7"></span></div>' +
            '</a>' +
            '<p class="text-center no-margin">Black</p>')
    $skinsList.append($skinBlack)
    var $skinPurple =
        $('<li />', {
            style: 'float:left; width: 33.33333%; padding: 5px;'
        })
        .append('<a href="javascript:void(0)" data-skin="skin-purple" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">' +
            '<div><span style="display:block; width: 20%; float: left; height: 7px;" class="bg-purple-active"></span><span class="bg-purple" style="display:block; width: 80%; float: left; height: 7px;"></span></div>' +
            '<div><span style="display:block; width: 20%; float: left; height: 20px; background: #222d32"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7"></span></div>' +
            '</a>' +
            '<p class="text-center no-margin">Purple</p>')
    $skinsList.append($skinPurple)
    var $skinGreen =
        $('<li />', {
            style: 'float:left; width: 33.33333%; padding: 5px;'
        })
        .append('<a href="javascript:void(0)" data-skin="skin-green" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">' +
            '<div><span style="display:block; width: 20%; float: left; height: 7px;" class="bg-green-active"></span><span class="bg-green" style="display:block; width: 80%; float: left; height: 7px;"></span></div>' +
            '<div><span style="display:block; width: 20%; float: left; height: 20px; background: #222d32"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7"></span></div>' +
            '</a>' +
            '<p class="text-center no-margin">Green</p>')
    $skinsList.append($skinGreen)
    var $skinRed =
        $('<li />', {
            style: 'float:left; width: 33.33333%; padding: 5px;'
        })
        .append('<a href="javascript:void(0)" data-skin="skin-red" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">' +
            '<div><span style="display:block; width: 20%; float: left; height: 7px;" class="bg-red-active"></span><span class="bg-red" style="display:block; width: 80%; float: left; height: 7px;"></span></div>' +
            '<div><span style="display:block; width: 20%; float: left; height: 20px; background: #222d32"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7"></span></div>' +
            '</a>' +
            '<p class="text-center no-margin">Red</p>')
    $skinsList.append($skinRed)
    var $skinYellow =
        $('<li />', {
            style: 'float:left; width: 33.33333%; padding: 5px;'
        })
        .append('<a href="javascript:void(0)" data-skin="skin-yellow" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">' +
            '<div><span style="display:block; width: 20%; float: left; height: 7px;" class="bg-yellow-active"></span><span class="bg-yellow" style="display:block; width: 80%; float: left; height: 7px;"></span></div>' +
            '<div><span style="display:block; width: 20%; float: left; height: 20px; background: #222d32"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7"></span></div>' +
            '</a>' +
            '<p class="text-center no-margin">Yellow</p>')
    $skinsList.append($skinYellow)

    // Light sidebar skins
    var $skinBlueLight =
        $('<li />', {
            style: 'float:left; width: 33.33333%; padding: 5px;'
        })
        .append('<a href="javascript:void(0)" data-skin="skin-blue-light" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">' +
            '<div><span style="display:block; width: 20%; float: left; height: 7px; background: #367fa9"></span><span class="bg-light-blue" style="display:block; width: 80%; float: left; height: 7px;"></span></div>' +
            '<div><span style="display:block; width: 20%; float: left; height: 20px; background: #f9fafc"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7"></span></div>' +
            '</a>' +
            '<p class="text-center no-margin" style="font-size: 12px">Blue Light</p>')
    $skinsList.append($skinBlueLight)
    var $skinBlackLight =
        $('<li />', {
            style: 'float:left; width: 33.33333%; padding: 5px;'
        })
        .append('<a href="javascript:void(0)" data-skin="skin-black-light" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">' +
            '<div style="box-shadow: 0 0 2px rgba(0,0,0,0.1)" class="clearfix"><span style="display:block; width: 20%; float: left; height: 7px; background: #fefefe"></span><span style="display:block; width: 80%; float: left; height: 7px; background: #fefefe"></span></div>' +
            '<div><span style="display:block; width: 20%; float: left; height: 20px; background: #f9fafc"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7"></span></div>' +
            '</a>' +
            '<p class="text-center no-margin" style="font-size: 12px">Black Light</p>')
    $skinsList.append($skinBlackLight)
    var $skinPurpleLight =
        $('<li />', {
            style: 'float:left; width: 33.33333%; padding: 5px;'
        })
        .append('<a href="javascript:void(0)" data-skin="skin-purple-light" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">' +
            '<div><span style="display:block; width: 20%; float: left; height: 7px;" class="bg-purple-active"></span><span class="bg-purple" style="display:block; width: 80%; float: left; height: 7px;"></span></div>' +
            '<div><span style="display:block; width: 20%; float: left; height: 20px; background: #f9fafc"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7"></span></div>' +
            '</a>' +
            '<p class="text-center no-margin" style="font-size: 12px">Purple Light</p>')
    $skinsList.append($skinPurpleLight)
    var $skinGreenLight =
        $('<li />', {
            style: 'float:left; width: 33.33333%; padding: 5px;'
        })
        .append('<a href="javascript:void(0)" data-skin="skin-green-light" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">' +
            '<div><span style="display:block; width: 20%; float: left; height: 7px;" class="bg-green-active"></span><span class="bg-green" style="display:block; width: 80%; float: left; height: 7px;"></span></div>' +
            '<div><span style="display:block; width: 20%; float: left; height: 20px; background: #f9fafc"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7"></span></div>' +
            '</a>' +
            '<p class="text-center no-margin" style="font-size: 12px">Green Light</p>')
    $skinsList.append($skinGreenLight)
    var $skinRedLight =
        $('<li />', {
            style: 'float:left; width: 33.33333%; padding: 5px;'
        })
        .append('<a href="javascript:void(0)" data-skin="skin-red-light" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">' +
            '<div><span style="display:block; width: 20%; float: left; height: 7px;" class="bg-red-active"></span><span class="bg-red" style="display:block; width: 80%; float: left; height: 7px;"></span></div>' +
            '<div><span style="display:block; width: 20%; float: left; height: 20px; background: #f9fafc"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7"></span></div>' +
            '</a>' +
            '<p class="text-center no-margin" style="font-size: 12px">Red Light</p>')
    $skinsList.append($skinRedLight)
    var $skinYellowLight =
        $('<li />', {
            style: 'float:left; width: 33.33333%; padding: 5px;'
        })
        .append('<a href="javascript:void(0)" data-skin="skin-yellow-light" style="display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4)" class="clearfix full-opacity-hover">' +
            '<div><span style="display:block; width: 20%; float: left; height: 7px;" class="bg-yellow-active"></span><span class="bg-yellow" style="display:block; width: 80%; float: left; height: 7px;"></span></div>' +
            '<div><span style="display:block; width: 20%; float: left; height: 20px; background: #f9fafc"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7"></span></div>' +
            '</a>' +
            '<p class="text-center no-margin" style="font-size: 12px">Yellow Light</p>')
    $skinsList.append($skinYellowLight)

    $demoSettings.append('<h4 class="control-sidebar-heading">Skins</h4>')
    $demoSettings.append($skinsList)

    $tabPane.append($demoSettings)
    $('#control-sidebar-home-tab').after($tabPane)

    setup()

    $('[data-toggle="tooltip"]').tooltip()
})