  <!-- Chart Configuration Script -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Period Toggle Logic
            const periodSelect = document.getElementById("period-select");
            const rangeInputs = document.querySelectorAll(".date-range-inputs");
            
            periodSelect.addEventListener("change", function () {
                if (this.value === "custom") {
                    rangeInputs.forEach(el => el.classList.remove("d-none"));
                } else {
                    rangeInputs.forEach(el => el.classList.add("d-none"));
                    // Auto submit when non-custom option is selected
                    document.getElementById("filter-form").submit();
                }
            });

            // CHART 1: Revenue & Order Trends
            const trendLabels = JSON.parse('{!! json_encode($chartData["trend"]["labels"]) !!}');
            const trendRevenues = JSON.parse('{!! json_encode($chartData["trend"]["revenues"]) !!}');
            const trendOrders = JSON.parse('{!! json_encode($chartData["trend"]["orders"]) !!}');

            if (window.ApexCharts) {
                new ApexCharts(document.getElementById('chart-revenue-trends'), {
                    chart: {
                        type: 'area',
                        height: 350,
                        fontFamily: 'inherit',
                        toolbar: { show: false },
                        zoom: { enabled: false }
                    },
                    colors: ['#10b981', '#3b82f6'],
                    series: [
                        {
                            name: 'Pendapatan (Rp)',
                            type: 'area',
                            data: trendRevenues
                        },
                        {
                            name: 'Jumlah Order',
                            type: 'line',
                            data: trendOrders
                        }
                    ],
                    fill: {
                        type: ['gradient', 'solid'],
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.4,
                            opacityTo: 0.1,
                            stops: [0, 90, 100]
                        }
                    },
                    stroke: {
                        width: [3, 3],
                        curve: 'smooth',
                        dashArray: [0, 0]
                    },
                    xaxis: {
                        categories: trendLabels,
                        labels: {
                            style: { colors: '#64748b' }
                        }
                    },
                    yaxis: [
                        {
                            title: {
                                text: 'Pendapatan (Rp)',
                                style: { color: '#10b981' }
                            },
                            labels: {
                                formatter: function (val) {
                                    return "Rp " + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                                },
                                style: { colors: '#10b981' }
                            }
                        },
                        {
                            opposite: true,
                            title: {
                                text: 'Jumlah Order',
                                style: { color: '#3b82f6' }
                            },
                            labels: {
                                formatter: function (val) {
                                    return Math.round(val);
                                },
                                style: { colors: '#3b82f6' }
                            }
                        }
                    ],
                    tooltip: {
                        shared: true,
                        intersect: false,
                        theme: 'light'
                    },
                    legend: {
                        position: 'top',
                        horizontalAlign: 'right'
                    },
                    grid: {
                        borderColor: '#f1f5f9',
                        strokeDashArray: 4
                    }
                }).render();

                // CHART 2: Payment Methods
                const paymentLabels = JSON.parse('{!! json_encode($chartData["payments"]["labels"]) !!}');
                const paymentCounts = JSON.parse('{!! json_encode($chartData["payments"]["counts"]) !!}');

                new ApexCharts(document.getElementById('chart-payment-methods'), {
                    chart: {
                        type: 'donut',
                        height: 350,
                        fontFamily: 'inherit'
                    },
                    series: paymentCounts.length ? paymentCounts : [0],
                    labels: paymentLabels.length ? paymentLabels : ['Tidak ada data'],
                    colors: ['#3b82f6', '#10b981', '#f59e0b', '#ec4899', '#6366f1'],
                    legend: {
                        position: 'bottom'
                    },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '70%',
                                labels: {
                                    show: true,
                                    total: {
                                        show: true,
                                        label: 'Total Order',
                                        formatter: function (w) {
                                            return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }).render();

                // CHART 3: Mechanic Performance
                const mechanicNames = JSON.parse('{!! json_encode($chartData["mechanics"]["names"]) !!}');
                const mechanicCounts = JSON.parse('{!! json_encode($chartData["mechanics"]["counts"]) !!}');

                new ApexCharts(document.getElementById('chart-mechanic-performance'), {
                    chart: {
                        type: 'bar',
                        height: 300,
                        fontFamily: 'inherit',
                        toolbar: { show: false }
                    },
                    colors: ['#06b6d4'],
                    series: [{
                        name: 'Order Ditangani',
                        data: mechanicCounts
                    }],
                    plotOptions: {
                        bar: {
                            borderRadius: 4,
                            columnWidth: '45%'
                        }
                    },
                    xaxis: {
                        categories: mechanicNames.length ? mechanicNames : ['Tidak ada data'],
                        labels: {
                            style: { colors: '#64748b' }
                        }
                    },
                    yaxis: {
                        title: { text: 'Jumlah Order' },
                        labels: {
                            formatter: function (val) { return Math.round(val); }
                        }
                    },
                    grid: {
                        borderColor: '#f1f5f9',
                        strokeDashArray: 4
                    }
                }).render();

                const topServiceNames = JSON.parse('{!! json_encode($chartData["services"]["names"]) !!}');
                const topServiceQtys = JSON.parse('{!! json_encode($chartData["services"]["quantities"]) !!}');

                new ApexCharts(document.getElementById('chart-top-services'), {
                    chart: {
                        type: 'bar',
                        height: 300,
                        fontFamily: 'inherit',
                        toolbar: { show: false }
                    },
                    colors: ['#f97316'],
                    series: [{
                        name: 'Jumlah Digunakan',
                        data: topServiceQtys
                    }],
                    plotOptions: {
                        bar: {
                            borderRadius: 4,
                            horizontal: true,
                            barHeight: '50%'
                        }
                    },
                    xaxis: {
                        categories: topServiceNames.length ? topServiceNames : ['Tidak ada data'],
                        labels: {
                            style: { colors: '#64748b' }
                        }
                    },
                    grid: {
                        borderColor: '#f1f5f9',
                        strokeDashArray: 4
                    }
                }).render();
            }
        });
    </script>