$(function () {
    "use strict";

    // Re-trigger feather icons replacement
    if (typeof feather !== 'undefined') {
        feather.replace();
    }

    // Initialize tooltips
    if ($.fn.tooltip) {
        $('[data-toggle="tooltip"]').tooltip();
    }

    // Wait for Chart.js to be available
    var chartAttempts = 0;
    function checkAndInitCharts() {
        if (typeof Chart !== 'undefined') {
            initDashboardCharts();
        } else if (chartAttempts < 30) {
            chartAttempts++;
            setTimeout(checkAndInitCharts, 100);
        } else {
            console.warn("Chart.js could not be loaded within timeout.");
        }
    }

    checkAndInitCharts();

    function initDashboardCharts() {
        if (!window.DASH_DATA) return;

        // ==============================================================
        // 1. Chart: Items Published by Year
        // ==============================================================
        var yearCanvas = document.getElementById("chart-items-year");
        if (yearCanvas && window.DASH_DATA.yearsLabels && window.DASH_DATA.yearsLabels.length > 0) {
            var yearLabels = window.DASH_DATA.yearsLabels;
            var yearValues = window.DASH_DATA.yearsData || [];

            new Chart(yearCanvas, {
                type: 'bar',
                data: {
                    labels: yearLabels,
                    datasets: [{
                        label: 'จำนวนผลงาน (รายการ)',
                        data: yearValues,
                        backgroundColor: 'rgba(95, 118, 232, 0.85)',
                        hoverBackgroundColor: '#5f76e8',
                        borderColor: '#5f76e8',
                        borderWidth: 1,
                        borderRadius: 6,
                        maxBarThickness: 45
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: false
                    },
                    tooltips: {
                        backgroundColor: '#1e293b',
                        titleFontSize: 13,
                        titleFontFamily: "'Sarabun', sans-serif",
                        bodyFontSize: 13,
                        bodyFontFamily: "'Sarabun', sans-serif",
                        xPadding: 12,
                        yPadding: 10,
                        cornerRadius: 6,
                        displayColors: false,
                        callbacks: {
                            label: function (tooltipItem) {
                                return 'จำนวน: ' + Number(tooltipItem.yLabel).toLocaleString() + ' รายการ';
                            }
                        }
                    },
                    scales: {
                        xAxes: [{
                            gridLines: {
                                display: false,
                                drawBorder: false
                            },
                            ticks: {
                                fontFamily: "'Sarabun', sans-serif",
                                fontSize: 12,
                                fontColor: '#64748b'
                            }
                        }],
                        yAxes: [{
                            gridLines: {
                                color: '#f1f5f9',
                                zeroLineColor: '#e2e8f0',
                                drawBorder: false
                            },
                            ticks: {
                                fontFamily: "'Sarabun', sans-serif",
                                fontSize: 12,
                                fontColor: '#64748b',
                                beginAtZero: true,
                                precision: 0,
                                callback: function (value) {
                                    return value.toLocaleString();
                                }
                            }
                        }]
                    }
                }
            });
        }

        // ==============================================================
        // 2. Chart: Items Distribution by Community / Scope (ขอบเขตเนื้อหา)
        // ==============================================================
        var commCanvas = document.getElementById("chart-items-community") || document.getElementById("chart-items-type");
        var commLabels = window.DASH_DATA.communityLabels || window.DASH_DATA.typesLabels;
        var commValues = window.DASH_DATA.communityData || window.DASH_DATA.typesData || [];

        if (commCanvas && commLabels && commLabels.length > 0) {
            var palette = [
                '#5f76e8', // Primary Blue
                '#01caf1', // Cyan
                '#22ca80', // Green
                '#ff8040', // Orange
                '#ff4f70', // Pink / Red
                '#9061f9', // Purple
                '#6c757d'  // Gray
            ];

            new Chart(commCanvas, {
                type: 'doughnut',
                data: {
                    labels: commLabels,
                    datasets: [{
                        data: commValues,
                        backgroundColor: palette.slice(0, commLabels.length),
                        borderWidth: 2,
                        borderColor: '#ffffff',
                        hoverBorderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutoutPercentage: 65,
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            padding: 12,
                            fontFamily: "'Sarabun', sans-serif",
                            fontSize: 12,
                            fontColor: '#475569'
                        }
                    },
                    tooltips: {
                        backgroundColor: '#1e293b',
                        titleFontSize: 13,
                        titleFontFamily: "'Sarabun', sans-serif",
                        bodyFontSize: 13,
                        bodyFontFamily: "'Sarabun', sans-serif",
                        xPadding: 12,
                        yPadding: 10,
                        cornerRadius: 6,
                        callbacks: {
                            label: function (tooltipItem, data) {
                                var dataset = data.datasets[tooltipItem.datasetIndex];
                                var currentValue = dataset.data[tooltipItem.index];
                                var label = data.labels[tooltipItem.index] || '';
                                
                                var total = dataset.data.reduce(function (prev, curr) {
                                    return prev + curr;
                                }, 0);
                                var percentage = total > 0 ? Math.round((currentValue / total) * 100) : 0;

                                return label + ': ' + Number(currentValue).toLocaleString() + ' รายการ (' + percentage + '%)';
                            }
                        }
                    }
                }
            });
        }
    }
});
