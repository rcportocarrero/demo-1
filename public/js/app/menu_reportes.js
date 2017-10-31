
function menu_graficos() {
    load_menu_reportes();
    jQuery('.left_menu li').removeClass('clicked');
    jQuery('#menuGraficos').addClass('clicked');
}

function load_menu_reportes(ops) {
    if (ops === undefined) {
        ops = 0;
    }

    if (valida_sesion() === false) {
        return;
    }

    BaseX.load_html(root + '/seleccion/consulta/reportes', {
        data: ops,
        success: function (xhr) {
            jQuery('#workArea').html(xhr);

            init_pastel('dv_container_pastel');
            init_barras('dv_container_barras');
        }
    });
}


function show_tplinfo(obj) {

    jQuery('#modal_list_info').modal({
        keyboard: false,
        backdrop: 'static'
    });
    var modal_lista_region = jQuery('#modal_list_info');
    modal_lista_region.on('shown.bs.modal', function (e) {
        jQuery(this).off('shown.bs.modal');

        BaseX.load_html(root + '/seleccion/index/tplinfo', {
            data: {
                a: obj.id,
                b: obj.t,
                c: 1,
                d: 2
            },
            success: function (xhr) {
                jQuery('#tpl_info').html(xhr);
            }
        });
    });
}

function init_resumen(id, datos) {
Highcharts.setOptions({
    colors: ['#ED561B', '#50B432', '#DDDF00']
});
//    Highcharts.chart(id, {
//        chart: {
//            plotBackgroundColor: null,
//            plotBorderWidth: null,
//            plotShadow: false,
//            type: 'pie'
//        },
//        title: {
//            text: 'RESUMEN'
//        },
//        credits: {
//            enabled: false
//        },
//        series: [{
//                name: 'total',
//                colorByPoint: true,
//                data: datos
//            }]
//    });

 Highcharts.chart(id, {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'RESUMEN'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                }
            }
        },
        credits: {
            enabled: false
        },
        series: [{
        name: 'Brands',
        colorByPoint: true,
        data: datos
    }]
    });
}
function init_pastel(id) {
    Highcharts.chart(id, {
        chart: {
            type: 'pie',
            options3d: {
                enabled: true,
                alpha: 45,
                beta: 0
            }
        },
        title: {
            text: 'Progreso por región'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                depth: 35,
                dataLabels: {
                    enabled: true,
                    format: '{point.name}'
                }
            }
        },
        series: [{
                type: 'pie',
                name: 'Avance al',
                data: [
                    ['Región 1', 45.0],
                    ['Región 2', 26.8],
                    {
                        name: 'Región 6',
                        y: 12.8,
                        sliced: true,
                        selected: true
                    },
                    ['Región 3', 8.5],
                    ['Región 4', 6.2],
                    ['Región 5', 0.7]
                ]
            }]
    });
}

function init_barras(id) {
    // Create the chart
    Highcharts.chart(id, {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Reporte de barras'
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            type: 'category'
        },
        yAxis: {
            title: {
                text: 'Total percent market share'
            }

        },
        legend: {
            enabled: false
        },
        plotOptions: {
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    format: '{point.y:.1f}%'
                }
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>'
        },
        series: [{
                name: 'Avance',
                colorByPoint: true,
                data: [{
                        name: 'Región 1',
                        y: 56.33,
                        drilldown: 'Región 1'
                    }, {
                        name: 'Región 2',
                        y: 24.03,
                        drilldown: 'Región 2'
                    }, {
                        name: 'Región 3',
                        y: 10.38,
                        drilldown: 'Región 3'
                    }, {
                        name: 'Región 4',
                        y: 4.77,
                        drilldown: 'Región 4'
                    }, {
                        name: 'Región 5',
                        y: 0.91,
                        drilldown: 'Región 5'
                    }, {
                        name: 'Región 6',
                        y: 0.2,
                        drilldown: 'Región 6'
                    }]
            }],
        drilldown: {
            series: [{
                    name: 'Microsoft Internet Explorer',
                    id: 'Microsoft Internet Explorer',
                    data: [
                        [
                            'v11.0',
                            24.13
                        ],
                        [
                            'v8.0',
                            17.2
                        ],
                        [
                            'v9.0',
                            8.11
                        ],
                        [
                            'v10.0',
                            5.33
                        ],
                        [
                            'v6.0',
                            1.06
                        ],
                        [
                            'v7.0',
                            0.5
                        ]
                    ]
                }, {
                    name: 'Chrome',
                    id: 'Chrome',
                    data: [
                        [
                            'v40.0',
                            5
                        ],
                        [
                            'v41.0',
                            4.32
                        ],
                        [
                            'v42.0',
                            3.68
                        ],
                        [
                            'v39.0',
                            2.96
                        ],
                        [
                            'v36.0',
                            2.53
                        ],
                        [
                            'v43.0',
                            1.45
                        ],
                        [
                            'v31.0',
                            1.24
                        ],
                        [
                            'v35.0',
                            0.85
                        ],
                        [
                            'v38.0',
                            0.6
                        ],
                        [
                            'v32.0',
                            0.55
                        ],
                        [
                            'v37.0',
                            0.38
                        ],
                        [
                            'v33.0',
                            0.19
                        ],
                        [
                            'v34.0',
                            0.14
                        ],
                        [
                            'v30.0',
                            0.14
                        ]
                    ]
                }, {
                    name: 'Firefox',
                    id: 'Firefox',
                    data: [
                        [
                            'v35',
                            2.76
                        ],
                        [
                            'v36',
                            2.32
                        ],
                        [
                            'v37',
                            2.31
                        ],
                        [
                            'v34',
                            1.27
                        ],
                        [
                            'v38',
                            1.02
                        ],
                        [
                            'v31',
                            0.33
                        ],
                        [
                            'v33',
                            0.22
                        ],
                        [
                            'v32',
                            0.15
                        ]
                    ]
                }, {
                    name: 'Safari',
                    id: 'Safari',
                    data: [
                        [
                            'v8.0',
                            2.56
                        ],
                        [
                            'v7.1',
                            0.77
                        ],
                        [
                            'v5.1',
                            0.42
                        ],
                        [
                            'v5.0',
                            0.3
                        ],
                        [
                            'v6.1',
                            0.29
                        ],
                        [
                            'v7.0',
                            0.26
                        ],
                        [
                            'v6.2',
                            0.17
                        ]
                    ]
                }, {
                    name: 'Opera',
                    id: 'Opera',
                    data: [
                        [
                            'v12.x',
                            0.34
                        ],
                        [
                            'v28',
                            0.24
                        ],
                        [
                            'v27',
                            0.17
                        ],
                        [
                            'v29',
                            0.16
                        ]
                    ]
                }]
        }
    });
}