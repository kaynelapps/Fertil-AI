document.addEventListener("DOMContentLoaded", function () {
    const siteColor = getComputedStyle(document.documentElement).getPropertyValue('--site-color').trim();

    function getShade(hex, percent) {
        const f = parseInt(hex.slice(1), 16),
            t = percent < 0 ? 0 : 255,
            p = percent < 0 ? percent * -1 : percent,
            R = f >> 16,
            G = (f >> 8) & 0x00FF,
            B = f & 0x0000FF;
        return "#" + (
            0x1000000 +
            (Math.round((t - R) * p) + R) * 0x10000 +
            (Math.round((t - G) * p) + G) * 0x100 +
            (Math.round((t - B) * p) + B)
        ).toString(16).slice(1);
    }

    fetch(window.routes.charts).then(response => response.json()).then(result => {
        window.renderCharts(result);
    }).catch(error => {
        console.error("Chart data fetch error:", error);
    });

    // Global region chart instance
    window.regionAreaChart = null;
    window.renderCharts = function (res) {
        const chartData = res.data;

        function isAllZeroOrEmpty(data) {
            return !data || !data.length || data.every(val => val === 0);
        }

        // Goal Chart
        const appUserData = chartData.goal_type_chart.app_user;
        const anonUserData = chartData.goal_type_chart.anonymous_user;
        const goalLabels = appUserData.labels;

        function createGoalRadialChart(containerId, userType, data, color) {
            const isEmpty = isAllZeroOrEmpty(data);
            const chartOptions = {
                series: isEmpty ? [] : data,
                chart: {
                    height: 320,
                    type: 'radialBar',
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800,
                        animateGradually: {
                            enabled: true,
                            delay: 150
                        },
                        dynamicAnimation: {
                            enabled: true,
                            speed: 350
                        }
                    },
                    dropShadow: {
                        enabled: true,
                        top: 2,
                        left: 0,
                        blur: 4,
                        opacity: 0.2
                    }
                },
                plotOptions: {
                    radialBar: {
                        offsetY: 0,
                        startAngle: 0,
                        endAngle: 360,
                        hollow: {
                            margin: 25,
                            size: '45%',
                            background: 'transparent'
                        },

                        dataLabels: {
                            name: {
                                show: true,
                                fontSize: '13px',
                                fontFamily: 'Inter, sans-serif',
                                fontWeight: '500',
                                offsetY: -10
                            },
                            value: {
                                show: true,
                                fontSize: '16px',
                                fontFamily: 'Inter, sans-serif',
                                fontWeight: '600',
                                offsetY: 6,
                                formatter: function (val) {
                                    return `${val}%`;
                                }
                            },
                            total: {
                                show: true,
                                label: 'Total',
                                fontSize: '14px',
                                color: siteColor,
                                formatter: w => w.globals.seriesTotals.reduce((a, b) => a + b, 0).toFixed(1) + "%"
                            }
                        }
                    }
                },
                labels: goalLabels,
                colors: goalLabels.map((_, i) => getShade(color, i * -0.4)),
                legend: {
                    show: true,
                    floating: true,
                    fontSize: '12px',
                    fontFamily: 'Inter, sans-serif',
                    position: 'bottom',
                    offsetX: 0,
                    offsetY: 5,
                    labels: {
                        useSeriesColors: true
                    },
                    markers: {
                        size: 6,
                        strokeWidth: 0,
                        radius: 12
                    },
                    formatter: function (seriesName, opts) {
                        return `${seriesName}: ${opts.w.globals.series[opts.seriesIndex]}%`;
                    },
                    itemMargin: {
                        horizontal: 8,
                        vertical: 2
                    }
                },
                stroke: {
                    lineCap: 'round'
                },
                title: {
                    text: userType,
                    align: 'center',
                    margin: 10,
                    offsetY: 0,
                    style: {
                        fontSize: '16px',
                        fontWeight: '600',
                        fontFamily: 'Inter, sans-serif',
                        color: color
                    }
                },
                tooltip: {
                    enabled: true,
                    custom: function ({ series, seriesIndex, dataPointIndex, w }) {
                        return `<div class="custom-tooltip">
                    <span class="tooltip-label">${w.globals.labels[seriesIndex]}</span>
                    <span class="tooltip-value">${series[seriesIndex]}%</span>
                </div>`;
                    },
                    style: {
                        fontSize: '12px',
                        fontFamily: 'Inter, sans-serif'
                    }
                },
                noData: {
                    text: 'No Data Available',
                    align: 'center',
                    verticalAlign: 'middle',
                    style: {
                        color: '#9CA3AF',
                        fontSize: '14px',
                        fontFamily: 'Inter, sans-serif'
                    }
                },
                responsive: [{
                    breakpoint: 768,
                    options: {
                        chart: {
                            height: 280
                        },
                        legend: {
                            position: 'bottom',
                            offsetY: 0
                        }
                    }
                }]
            };

            new ApexCharts(document.querySelector(containerId), chartOptions).render();
        }

        createGoalRadialChart("#apex-goal-chart-app", "App User", appUserData.data, siteColor);
        createGoalRadialChart("#apex-goal-chart-anon", "Anonymous User", anonUserData.data, siteColor);

        // REGION CHART
        const regionChartData = chartData.user_region_chart;
        function getRegionChartOptions(data, labels, title, color) {
            const empty = isAllZeroOrEmpty(data);
            return {
                series: empty ? [] : [{ name: title, data }],
                chart: {
                    type: 'area',
                    height: 300,
                },
                stroke: {
                    curve: 'smooth',
                    width: 3,
                    lineCap: 'round'
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'light',
                        type: 'vertical',
                        shadeIntensity: 0.5,
                        gradientToColors: [getShade(color, 0.3)],
                        inverseColors: false,
                        opacityFrom: 0.6,
                        opacityTo: 0.1,
                        stops: [0, 50, 100]
                    }
                },
                xaxis: {
                    categories: labels,
                    labels: {
                        style: {
                            colors: '#6B7280',
                            fontSize: '11px',
                            fontFamily: 'Inter, sans-serif',
                            fontWeight: '400'
                        },
                        trim: true,
                        maxHeight: 120
                    },
                    axisBorder: {
                        show: true,
                        color: '#e5e7eb',
                        height: 1,
                        width: '100%',
                        offsetX: 0,
                        offsetY: 0
                    },
                    axisTicks: {
                        show: true,
                        borderType: 'solid',
                        color: '#e5e7eb',
                        height: 6,
                        offsetX: 0,
                        offsetY: 0
                    }
                },
                yaxis: {
                    title: {
                        text: 'User Count',
                        style: {
                            color: '#6B7280',
                            fontSize: '12px',
                            fontFamily: 'Inter, sans-serif',
                            fontWeight: '500'
                        }
                    },
                    labels: {
                        style: {
                            colors: '#6B7280',
                            fontSize: '11px',
                            fontFamily: 'Inter, sans-serif',
                            fontWeight: '400'
                        },
                        formatter: function (val) {
                            return Math.floor(val);
                        }
                    }
                },
                colors: [color],
                title: {
                    text: title,
                    align: 'center',
                    margin: 10,
                    offsetY: 0,
                    style: {
                        fontSize: '16px',
                        fontWeight: '600',
                        fontFamily: 'Inter, sans-serif',
                        color: siteColor
                    }
                },
                markers: {
                    size: 5,
                    colors: [color],
                    strokeColors: '#fff',
                    strokeWidth: 2,
                    hover: {
                        size: 7,
                        sizeOffset: 2
                    }
                },
                tooltip: {
                    enabled: true,
                    theme: 'light',
                    style: {
                        fontSize: '12px',
                        fontFamily: 'Inter, sans-serif'
                    },
                    x: {
                        show: true,
                        format: 'dd MMM yyyy'
                    },
                    y: {
                        formatter: function (val) {
                            return `${val} users`;
                        }
                    },
                    marker: {
                        show: true
                    }
                },
                noData: {
                    text: 'No Data Available',
                    align: 'center',
                    verticalAlign: 'middle',
                    style: {
                        color: '#9CA3AF',
                        fontSize: '14px',
                        fontFamily: 'Inter, sans-serif'
                    }
                },
                responsive: [{
                    breakpoint: 768,
                    options: {
                        chart: {
                            height: 280
                        },
                        xaxis: {
                            labels: {
                                rotate: -90
                            }
                        },
                        dataLabels: {
                            enabled: false
                        }
                    }
                }]
            };
        }
        function renderRegionCharts(type) {
            const regionData = regionChartData[type];

            if (window.regionChartApp) window.regionChartApp.destroy();
            if (window.regionChartAnon) window.regionChartAnon.destroy();

            window.regionChartApp = new ApexCharts(
                document.querySelector("#apex-region-chart-app"),
                getRegionChartOptions(regionData.series.app_user, regionData.labels, "App User", siteColor)
            );
            window.regionChartAnon = new ApexCharts(
                document.querySelector("#apex-region-chart-anon"),
                getRegionChartOptions(regionData.series.anonymous_user, regionData.labels, "Anonymous User", getShade(siteColor, -0.4))
            );

            window.regionChartApp.render();
            window.regionChartAnon.render();
        }
        // Initial render
        renderRegionCharts("country");

        // Region filter event
        const regionFilter = document.getElementById("region-filter");
        if (!regionFilter.dataset.listenerAdded) {
            regionFilter.addEventListener("change", function () {
                renderRegionCharts(this.value);
            });
            regionFilter.dataset.listenerAdded = "true";
        }

        // Device Platform Chart (Donut Chart)
        const deviceData = chartData.device_platform_chart.data;
        const isAllZero = deviceData.reduce((sum, val) => sum + val, 0) === 0;
        new ApexCharts(document.querySelector("#apex-device-chart"), {
            series: isAllZero ? [] : deviceData,
            chart: { type: 'donut', height: 300 },
            labels: chartData.device_platform_chart.labels,
            fill: { type: 'gradient' },
            dataLabels: { enabled: false },
            tooltip: {
                y: {
                    formatter: val => val + "%",
                    title: { formatter: seriesName => seriesName }
                }
            },
            plotOptions: {
                pie: {
                    donut: {
                        labels: {
                            show: !isAllZero,
                            total: {
                                show: true,
                                label: 'Total',
                                fontSize: '14px',
                                color: siteColor,
                                formatter: w => w.globals.seriesTotals.reduce((a, b) => a + b, 0).toFixed(1) + "%"
                            }
                        }
                    }
                }
            },
            colors: [siteColor, getShade(siteColor, -0.4)],
            legend: {
                show: true,
                position: 'bottom',
                horizontalAlign: 'center',
                fontSize: '14px',
                formatter: function (seriesName, opts) {
                    const i = opts.seriesIndex;
                    return `${seriesName} (${deviceData[i]}%)`;
                },
                markers: { width: 12, height: 12 },
                itemMargin: { horizontal: 10, vertical: 5 }
            },
            noData: {
                text: 'No Data Available',
                align: 'center',
                verticalAlign: 'middle',
                style: { color: '#999', fontSize: '16px' }
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: { width: 200 },
                    legend: { position: 'bottom' }
                }
            }]
        }).render();

        // User Type Chart (Pie Chart)
        const userTypeData = chartData.user_type_chart.data;
        const userTypeEmpty = isAllZeroOrEmpty(userTypeData);
        new ApexCharts(document.querySelector("#apex-user-type-chart"), {
            series: userTypeEmpty ? [] : userTypeData,
            chart: { type: 'pie', height: 300 },
            labels: chartData.user_type_chart.labels,
            fill: { type: 'gradient' },
            dataLabels: {
                enabled: true,
                formatter: val => val.toFixed(1) + '%'
            },
            colors: [
                siteColor,
                getShade(siteColor, -0.2),
                getShade(siteColor, -0.4)
            ],
            tooltip: {
                y: { formatter: val => val + "%" }
            },
            legend: {
                show: true,
                position: 'bottom'
            },
            noData: {
                text: 'No Data Available',
                align: 'center',
                verticalAlign: 'middle',
                style: { color: '#999', fontSize: '16px' }
            }
        }).render();

        // Ask Expert Chart (Bar Chart)
        const askExpertData = chartData.ask_expert_chart;
        const askExpertEmpty = isAllZeroOrEmpty(askExpertData.data);
        new ApexCharts(document.querySelector("#apex-ask-expert-chart"), {
            series: askExpertEmpty ? [] : [{
                data: askExpertData.labels.map((label, i) => ({
                    x: label,
                    y: askExpertData.data[i]
                }))
            }],
            chart: { type: 'bar', height: 250, toolbar: { show: false } },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    borderRadius: 6,
                    endingShape: 'rounded'
                }
            },
            dataLabels: { enabled: true },
            stroke: { show: true, width: 2, colors: ['transparent'] },
            title: {
                align: 'center',
                style: { fontSize: '18px', color: siteColor }
            },
            colors: [siteColor, getShade(siteColor, -0.3), getShade(siteColor, -0.5)],
            xaxis: {
                categories: askExpertEmpty ? [] : askExpertData.labels,
                title: { text: 'Status' }
            },
            yaxis: { title: { text: 'Request Count' } },
            fill: { opacity: 1 },
            tooltip: {
                x: { show: false },
                y: { formatter: val => val + " Requests" }
            },
            legend: { show: false },
            noData: {
                text: 'No Data Available',
                align: 'center',
                verticalAlign: 'middle',
                style: { color: '#999', fontSize: '16px' }
            }
        }).render();

        // Age Chart
        const ageData = chartData.age_chart;
        const ageEmpty = isAllZeroOrEmpty(ageData.data);
        new ApexCharts(document.querySelector("#apex-age-chart"), {
            series: ageEmpty ? [] : [{
                name: 'User Count',
                data: ageData.data
            }],
            chart: {
                type: 'bar',
                height: 250,
                toolbar: { show: false }
            },
            xaxis: {
                categories: ageEmpty ? [] : ageData.labels,
                title: { text: 'Age Group' }
            },
            yaxis: {
                title: { text: 'User Count' }
            },
            colors: [getShade(siteColor, -0.2)],
            dataLabels: { enabled: true },
            tooltip: {
                y: {
                    formatter: val => `${val} users`
                }
            },
            noData: {
                text: 'No Data Available',
                align: 'center',
                style: { color: '#999', fontSize: '16px' }
            }
        }).render();
    };
});
