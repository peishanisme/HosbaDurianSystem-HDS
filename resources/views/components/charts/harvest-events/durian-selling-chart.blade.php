<div class="card pb-10">
    <div class="card-header">
        <h3 class="card-title">Fruit Selling Status</h3>
    </div>
    <div class="card-body p-5" id="fruit-selling-chart"></div>
</div>

@push('scripts')
    <script>
        am5.ready(function() {
            const selling = @json($sellingStatusData);

            const sold = selling.sold_percentage; // %
            const unsold = selling.unsold_percentage; // %

            // Build dynamic chart data using stacked segments
            const data = [{
                    category: "",
                    from: 0,
                    to: unsold,
                    name: "Unsold (" + selling.unsold + " pcs)",
                    columnSettings: {
                        fill: am5.color(0xffd100)
                    }
                },
                {
                    category: "",
                    from: unsold,
                    to: unsold + sold,
                    name: "Sold (" + selling.sold + " pcs)",
                    columnSettings: {
                        fill: am5.color(0x0ca948)
                    }
                }
            ];

            var root = am5.Root.new("fruit-selling-chart");

            root.setThemes([
                am5themes_Animated.new(root)
            ]);

            var chart = root.container.children.push(
                am5xy.XYChart.new(root, {
                    panX: false,
                    panY: false,
                    layout: root.verticalLayout
                })
            );

            var yAxis = chart.yAxes.push(
                am5xy.CategoryAxis.new(root, {
                    categoryField: "category",
                    renderer: am5xy.AxisRendererY.new(root, {})
                })
            );
            yAxis.data.setAll([{
                category: ""
            }]);

            var xAxis = chart.xAxes.push(
                am5xy.ValueAxis.new(root, {
                    min: 0,
                    max: 100,
                    numberFormat: "#'%'",
                    renderer: am5xy.AxisRendererX.new(root, {}),
                })
            );

            var series = chart.series.push(
                am5xy.ColumnSeries.new(root, {
                    xAxis: xAxis,
                    yAxis: yAxis,
                    valueXField: "to",
                    openValueXField: "from",
                    categoryYField: "category",
                    categoryXField: "name"
                })
            );

            series.columns.template.setAll({
                strokeWidth: 0,
                strokeOpacity: 0,
                height: am5.percent(100),
                templateField: "columnSettings"
            });

            series.data.setAll(data);

            // Legend
            var legend = chart.children.push(
                am5.Legend.new(root, {
                    nameField: "categoryX",
                    centerX: am5.percent(50),
                    x: am5.percent(50),
                    clickTarget: "none"
                })
            );

            legend.markerRectangles.template.setAll({
                strokeOpacity: 0
            });

            legend.data.setAll(series.dataItems);

            var exporting = am5plugins_exporting.Exporting.new(root, {
                menu: am5plugins_exporting.ExportingMenu.new(root, {}),
                filePrefix: "{{ __('messages.fruit_selling_status') }}"
            });

            series.appear();
            chart.appear(800, 100);
        });
    </script>
@endpush
