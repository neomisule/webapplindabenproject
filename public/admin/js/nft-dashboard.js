// for NFTs Statistics
var options = {
    series: [{
        name: "Price",
        data: [20, 38, 38, 72, 55, 63, 43, 76, 55, 80, 40, 80]
    }, {
        name: "Volume",
        data: [85, 65, 75, 38, 85, 35, 62, 40, 40, 64, 50, 89]
    }],
    chart: {
        height: 310,
        type: 'bar',
        zoom: {
            enabled: false
        },
    },
    plotOptions: {
        bar: {
            columnWidth: "30%",
            borderRadius: 3,
        }
    },
    dataLabels: {
        enabled: false
    },
    legend: {
        show: true,
        position: "top",
        offsetX: 0,
        offsetY: 8,
        markers: {
            width: 5,
            height: 5,
            strokeWidth: 0,
            strokeColor: '#fff',
            fillColors: undefined,
            radius: 12,
            customHTML: undefined,
            onClick: undefined,
            offsetX: 0,
            offsetY: 0
        },
    },
    stroke: {
        show: true,
        width: 2,
        colors: ['transparent']
    },
    grid: {
        borderColor: '#f1f1f1',
        strokeDashArray: 3
    },
    colors: ["var(--primary-color)", "rgb(244, 110, 244)"],
    yaxis: {
        title: {
            text: 'Statistics',
            style: {
                color: '#adb5be',
                fontSize: '14px',
                fontFamily: 'poppins, sans-serif',
                fontWeight: 600,
                cssClass: 'apexcharts-yaxis-label',
            },
        },
    },
    xaxis: {
        type: 'month',
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        axisBorder: {
            show: true,
            color: 'rgba(119, 119, 142, 0.05)',
            offsetX: 0,
            offsetY: 0,
        },
        axisTicks: {
            show: true,
            borderType: 'solid',
            color: 'rgba(119, 119, 142, 0.05)',
            width: 6,
            offsetX: 0,
            offsetY: 0
        },
        labels: {
            rotate: -90
        }
    }
};
var chart = new ApexCharts(document.querySelector("#nft-statistics"), options);
chart.render();
// for NFTs Statistics

/* Balance */
var total = {
    chart: {
        height: 120,
        sparkline: {
            enabled: true
        },
    },
    plotOptions: {
        bar: {
            columnWidth: '100%'
        }
    },
    stroke: {
        show: true,
        curve: 'smooth',
        lineCap: 'butt',
        colors: undefined,
        width: [1.5, 1.5],
        dashArray: [0, 0],
    },
    grid: {
        borderColor: 'transparent',
        padding: {
            top: 0,
            right: 0,
            bottom: 0,
            left: 0
        }
    },
    fill: {
        type: 'gradient',
        gradient: {
            opacityFrom: 0.5,
            opacityTo: 0.2,
            stops: [0, 60],
            colorStops: [
                [
                    {
                        offset: 0,
                        color: 'var(--primary04)',
                        opacity: 1
                    },
                    {
                        offset: 60,
                        color: 'var(--primary02)',
                        opacity: 1
                    },
                    {
                        offset: 100,
                        color: 'rgba(121, 97, 245, 0)',
                        opacity: 1
                    }
                ],
            ]
        },
    },
    series: [{
        name: 'This Year',
        data: [
            [0, 48.11708650372481],
            [1, 44.83834104995953],
            [2, 45.727409628208974],
            [3, 44.69213146554142],
            [4, 44.92113232835135],
            [5, 44.200874587557415],
            [6, 41.750527715312444],
            [7, 44.84511185791557],
            [8, 46.04672992189592],
            [9, 45.9480092098883],
            [10, 46.9249480823427],
            [11, 43.600609487921346],
            [12, 40.29988975207692],
            [13, 42.03310106988357],
            [14, 39.457750445961125],
            [15, 40.540159797957294],
            [16, 37.277912393740806],
            [17, 41.43887402339309],
            [18, 39.47430428214318],
            [19, 36.91189415889479],
            [20, 36.42847097453014],
            [21, 36.96844325047937],
            [22, 35.54647151074562],
            [23, 32.998974290143025],
            [24, 30.43526314490385],
            [25, 31.14797888879888],
            [26, 27.20589032036549],
            [27, 25.777592542626508],
            [28, 30.052675048145275],
            [29, 30.92837408600937],
            [30, 34.190241658736014],
            [31, 37.57718922878679],
            [32, 41.18083316913268],
            [33, 41.27110666976231],
            [34, 36.33819281943194],
            [35, 37.39239238651191],
            [36, 37.046485292242615],
            [37, 34.594801853250495],
            [38, 31.488044618299227],
            [39, 34.69970813498227],
            [40, 39.66083111892072],
            [41, 40.203292838001616],
            [42, 36.089709320758985],
            [43, 40.31141091738469],
            [44, 44.170004784953846],
            [45, 48.84998014705778],
            [46, 43.93624560052546],
            [47, 40.62473022491363],
            [48, 39.154068738786684],
            [49, 42.803089612673666],
            [50, 40.6511024461858],
            [51, 38.34516630158569],
            [52, 39.546885205159555],
            [53, 42.50715860274628],
            [54, 38.1455129028495],
            [55, 33.87761157196474],
            [56, 37.30125615378047],
            [57, 38.799409423316405],
            [58, 39.185431079286275],
            [59, 43.32737024276462],
            [60, 41.52185070435002],
            [61, 41.613587244137946],
            [62, 44.23763577861365],
            [63, 44.91439321362589],
            [64, 42.18546432611939],
            [65, 41.0624926886062],
            [66, 44.24453261527582],
            [67, 47.34794952778721],
            [68, 48.10833243543891],
            [69, 43.640893412371504],
            [70, 40.614056030997666],
            [71, 42.9374730102888],
            [72, 46.1355421298619],
            [73, 48.995759760197956],
            [74, 52.19926195857424],
            [75, 49.2778849176981],
            [76, 52.46274689069702],
            [77, 56.74969793098863],
            [78, 60.92623317241021],
            [79, 57.70969775380601],
            [80, 57.35168105637668],
        ],
        type: 'area'
    }],
    yaxis: {
        min: 0,
        show: false
    },
    xaxis: {
        axisBorder: {
            show: false
        },
    },
    yaxis: {
        axisBorder: {
            show: false
        },
    },
    colors: ["var(--primary-color)"],
    tooltip: {
        enabled: false,
    }
}
var total = new ApexCharts(document.querySelector("#balance"), total);
total.render();
/* Balance */

/* for trending-creator1 */
var spark1 = {
    chart: {
        type: 'line',
        height: 20,
        width: 80,
        sparkline: {
            enabled: true
        },
        dropShadow: {
            enabled: true,
            enabledOnSeries: undefined,
            top: 0,
            left: 0,
            blur: 3,
            color: '#000',
            opacity: 0.1
        }
    },
    tooltip: {
        enabled: false
    },
    grid: {
        show: false,
        xaxis: {
            lines: {
                show: false
            }
        },
        yaxis: {
            lines: {
                show: false
            }
        },
    },
    stroke: {
        show: true,
        curve: 'smooth',
        lineCap: 'butt',
        colors: undefined,
        width: 2,
        dashArray: 0,
    },
    fill: {
        gradient: {
            enabled: false
        }
    },
    series: [{
        name: 'Value',
        data: [54, 38, 56, 24, 65]
    }],
    yaxis: {
        min: 0,
        show: false
    },
    xaxis: {
        show: false,
        axisTicks: {
            show: false
        },
        axisBorder: {
            show: false
        }
    },
    yaxis: {
        axisBorder: {
            show: false
        },
    },
    colors: ['var(--primary-color)'],

}
var spark1 = new ApexCharts(document.querySelector("#trending-creator1"), spark1);
spark1.render();
/* for trending-creator1 */

/* for trending-creator2 */
var spark2 = {
    chart: {
        type: 'line',
        height: 20,
        width: 80,
        sparkline: {
            enabled: true
        },
        dropShadow: {
            enabled: true,
            enabledOnSeries: undefined,
            top: 0,
            left: 0,
            blur: 3,
            color: '#000',
            opacity: 0.1
        }
    },
    tooltip: {
        enabled: false
    },
    grid: {
        show: false,
        xaxis: {
            lines: {
                show: false
            }
        },
        yaxis: {
            lines: {
                show: false
            }
        },
    },
    stroke: {
        show: true,
        curve: 'smooth',
        lineCap: 'butt',
        colors: undefined,
        width: 2,
        dashArray: 0,
    },
    fill: {
        gradient: {
            enabled: false
        }
    },
    series: [{
        name: 'Value',
        data: [24, 54, 15, 42, 16]
    }],
    yaxis: {
        min: 0,
        show: false
    },
    xaxis: {
        show: false,
        axisTicks: {
            show: false
        },
        axisBorder: {
            show: false
        }
    },
    yaxis: {
        axisBorder: {
            show: false
        },
    },
    colors: ['rgb(133, 204, 65)'],

}
var spark2 = new ApexCharts(document.querySelector("#trending-creator2"), spark2);
spark2.render();
/* for trending-creator2 */

/* for trending-creator3 */
var spark3 = {
    chart: {
        type: 'line',
        height: 20,
        width: 80,
        sparkline: {
            enabled: true
        },
        dropShadow: {
            enabled: true,
            enabledOnSeries: undefined,
            top: 0,
            left: 0,
            blur: 3,
            color: '#000',
            opacity: 0.1
        }
    },
    tooltip: {
        enabled: false
    },
    grid: {
        show: false,
        xaxis: {
            lines: {
                show: false
            }
        },
        yaxis: {
            lines: {
                show: false
            }
        },
    },
    stroke: {
        show: true,
        curve: 'smooth',
        lineCap: 'butt',
        colors: undefined,
        width: 2,
        dashArray: 0,
    },
    fill: {
        gradient: {
            enabled: false
        }
    },
    series: [{
        name: 'Value',
        data: [15, 42, 16, 44, 24]
    }],
    yaxis: {
        min: 0,
        show: false
    },
    xaxis: {
        show: false,
        axisTicks: {
            show: false
        },
        axisBorder: {
            show: false
        }
    },
    yaxis: {
        axisBorder: {
            show: false
        },
    },
    colors: ['rgb(40, 200, 235)'],

}
var spark3 = new ApexCharts(document.querySelector("#trending-creator3"), spark3);
spark3.render();
/* for trending-creator3 */

/* for trending-creator4 */
var spark4 = {
    chart: {
        type: 'line',
        height: 20,
        width: 80,
        sparkline: {
            enabled: true
        },
        dropShadow: {
            enabled: true,
            enabledOnSeries: undefined,
            top: 0,
            left: 0,
            blur: 3,
            color: '#000',
            opacity: 0.1
        }
    },
    tooltip: {
        enabled: false
    },
    grid: {
        show: false,
        xaxis: {
            lines: {
                show: false
            }
        },
        yaxis: {
            lines: {
                show: false
            }
        },
    },
    stroke: {
        show: true,
        curve: 'smooth',
        lineCap: 'butt',
        colors: undefined,
        width: 2,
        dashArray: 0,
    },
    fill: {
        gradient: {
            enabled: false
        }
    },
    series: [{
        name: 'Value',
        data: [54, 38, 56, 24, 65]
    }],
    yaxis: {
        min: 0,
        show: false
    },
    xaxis: {
        show: false,
        axisTicks: {
            show: false
        },
        axisBorder: {
            show: false
        }
    },
    yaxis: {
        axisBorder: {
            show: false
        },
    },
    colors: ['rgb(244, 110, 244)'],

}
var spark4 = new ApexCharts(document.querySelector("#trending-creator4"), spark4);
spark4.render();
/* for trending-creator4 */

/* for trending-creator5 */
var spark5 = {
    chart: {
        type: 'line',
        height: 20,
        width: 80,
        sparkline: {
            enabled: true
        },
        dropShadow: {
            enabled: true,
            enabledOnSeries: undefined,
            top: 0,
            left: 0,
            blur: 3,
            color: '#000',
            opacity: 0.1
        }
    },
    tooltip: {
        enabled: false
    },
    grid: {
        show: false,
        xaxis: {
            lines: {
                show: false
            }
        },
        yaxis: {
            lines: {
                show: false
            }
        },
    },
    stroke: {
        show: true,
        curve: 'smooth',
        lineCap: 'butt',
        colors: undefined,
        width: 2,
        dashArray: 0,
    },
    fill: {
        gradient: {
            enabled: false
        }
    },
    series: [{
        name: 'Value',
        data: [15, 42, 16, 44, 24]
    }],
    yaxis: {
        min: 0,
        show: false
    },
    xaxis: {
        show: false,
        axisTicks: {
            show: false
        },
        axisBorder: {
            show: false
        }
    },
    yaxis: {
        axisBorder: {
            show: false
        },
    },
    colors: ['rgb(250, 182, 50)'],

}
var spark5 = new ApexCharts(document.querySelector("#trending-creator5"), spark5);
spark5.render();
/* for trending-creator5 */

/* for trending-creator6 */
var spark6 = {
    chart: {
        type: 'line',
        height: 20,
        width: 80,
        sparkline: {
            enabled: true
        },
        dropShadow: {
            enabled: true,
            enabledOnSeries: undefined,
            top: 0,
            left: 0,
            blur: 3,
            color: '#000',
            opacity: 0.1
        }
    },
    tooltip: {
        enabled: false
    },
    grid: {
        show: false,
        xaxis: {
            lines: {
                show: false
            }
        },
        yaxis: {
            lines: {
                show: false
            }
        },
    },
    stroke: {
        show: true,
        curve: 'smooth',
        lineCap: 'butt',
        colors: undefined,
        width: 2,
        dashArray: 0,
    },
    fill: {
        gradient: {
            enabled: false
        }
    },
    series: [{
        name: 'Value',
        data: [15, 42, 16, 44, 24]
    }],
    yaxis: {
        min: 0,
        show: false
    },
    xaxis: {
        show: false,
        axisTicks: {
            show: false
        },
        axisBorder: {
            show: false
        }
    },
    yaxis: {
        axisBorder: {
            show: false
        },
    },
    colors: ['rgb(250, 75, 66)'],

}
var spark5 = new ApexCharts(document.querySelector("#trending-creator6"), spark6);
spark5.render();
/* for trending-creator6 */