<div class="card pb-10">
    <div class="card-header">
        <h3 class="card-title">{{ __('messages.top_selling_species') }}</h3>
    </div>
    <div class="card-body p-5" id="top-selling-species-chart" style="width: 100%; height: 400px;"></div>
</div>

@push('scripts')
    <script>
        am5.ready(function() {

            var root = am5.Root.new("top-selling-species-chart");

            root.setThemes([
                am5themes_Animated.new(root)
            ]);

            var chart = root.container.children.push(am5xy.XYChart.new(root, {
                panX: true,
                panY: true,
                wheelX: "panX",
                wheelY: "zoomX",
                pinchZoomX: true,
                paddingLeft: 0,
                paddingRight: 1
            }));

            var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {}));
            cursor.lineY.set("visible", false);

            // X AXIS (Species)
            var xRenderer = am5xy.AxisRendererX.new(root, {
                minGridDistance: 30,
                minorGridEnabled: false
            });

            xRenderer.labels.template.setAll({
                rotation: -45,
                centerY: am5.p50,
                centerX: am5.p100,
                paddingRight: 10
            });

            var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
                categoryField: "species",
                renderer: xRenderer,
                tooltip: am5.Tooltip.new(root, {})
            }));

            // Y AXIS (Total Sold)
            var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
                renderer: am5xy.AxisRendererY.new(root, {
                    strokeOpacity: 0.1
                })
            }));

            // SERIES
            var series = chart.series.push(am5xy.ColumnSeries.new(root, {
                name: "Top Selling Species",
                xAxis: xAxis,
                yAxis: yAxis,
                valueYField: "total",
                sequencedInterpolation: true,
                categoryXField: "species",
                tooltip: am5.Tooltip.new(root, {
                    labelText: "{valueY}"
                })
            }));

            series.columns.template.setAll({
                cornerRadiusTL: 5,
                cornerRadiusTR: 5,
                strokeOpacity: 0
            });

            series.columns.template.adapters.add("fill", (fill, target) => {
                return chart.get("colors").getIndex(series.columns.indexOf(target));
            });

            series.columns.template.adapters.add("stroke", (stroke, target) => {
                return chart.get("colors").getIndex(series.columns.indexOf(target));
            });

            var exporting = am5plugins_exporting.Exporting.new(root, {
                menu: am5plugins_exporting.ExportingMenu.new(root, {}),
                filePrefix: "{{ __('messages.top_selling_species') }}"
            });

            // Inject your backend data here
            var data = @json($topSellingSpecies);

            // Apply data
            xAxis.data.setAll(data);
            series.data.setAll(data);

            series.appear(1000);
            chart.appear(1000, 100);

        });
    </script>
@endpush
