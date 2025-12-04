<div class="card pb-10">
    <div class="card-header">
        <h3 class="card-title">{{ __('messages.total_harvested_fruits') }}</h3>
    </div>
    <div class="card-body p-5" id="total-harvested-fruits-chart" style="width: 100%; height: 400px;"></div>
</div>

@push('scripts')
    <script>
        am5.ready(function() {

            const harvestData = @json($totalHarvestedFruitsData);

            const chartData = harvestData.map(item => ({
                date: new Date(item.harvested_at).getTime(),
                value: item.total,
                event: item.event
            }));

            // Create root element
            var root = am5.Root.new("total-harvested-fruits-chart");

            // Set themes
            root.setThemes([am5themes_Animated.new(root)]);

            // Create chart
            var chart = root.container.children.push(
                am5xy.XYChart.new(root, {
                    panX: true,
                    panY: true,
                    wheelX: "panX",
                    wheelY: "zoomX",
                    pinchZoomX: true,
                    paddingLeft: 0
                })
            );

            // Add cursor
            var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {
                behavior: "none"
            }));
            cursor.lineY.set("visible", false);

            // Create axes
            var xAxis = chart.xAxes.push(
                am5xy.DateAxis.new(root, {
                    baseInterval: {
                        timeUnit: "day",
                        count: 1
                    },
                    renderer: am5xy.AxisRendererX.new(root, {
                        minorGridEnabled: true,
                        minGridDistance: 70
                    }),
                    tooltip: am5.Tooltip.new(root, {})
                })
            );

            var yAxis = chart.yAxes.push(
                am5xy.ValueAxis.new(root, {
                    renderer: am5xy.AxisRendererY.new(root, {})
                })
            );

            // Create series
            var series = chart.series.push(
                am5xy.LineSeries.new(root, {
                    name: "Total Fruits",
                    xAxis: xAxis,
                    yAxis: yAxis,
                    valueYField: "value",
                    valueXField: "date",
                    tooltip: am5.Tooltip.new(root, {
                        labelText: "{event}: {valueY} fruits"
                    })
                })
            );

            // Set data
            series.data.setAll(chartData);

            // Add scrollbar
            var scrollbar = chart.set(
                "scrollbarX",
                am5xy.XYChartScrollbar.new(root, {
                    orientation: "horizontal",
                    height: 60
                })
            );

            var sbDateAxis = scrollbar.chart.xAxes.push(
                am5xy.DateAxis.new(root, {
                    baseInterval: {
                        timeUnit: "day",
                        count: 1
                    },
                    renderer: am5xy.AxisRendererX.new(root, {})
                })
            );

            var sbValueAxis = scrollbar.chart.yAxes.push(
                am5xy.ValueAxis.new(root, {
                    renderer: am5xy.AxisRendererY.new(root, {})
                })
            );

            var sbSeries = scrollbar.chart.series.push(
                am5xy.LineSeries.new(root, {
                    valueYField: "value",
                    valueXField: "date",
                    xAxis: sbDateAxis,
                    yAxis: sbValueAxis
                })
            );

            sbSeries.data.setAll(chartData);

            // Animate on load
            series.appear(1000);
            chart.appear(1000, 100);

        });
    </script>
@endpush
