<div class="card pb-10">
    <div class="card-header">
        <h3 class="card-title">{{ __('messages.total_transactions') }}</h3>
    </div>
    <div class="card-body p-5" id="total-transaction-chart" style="width: 100%; height: 400px;"></div>
</div>

@push('scripts')
    <script>
        am5.ready(function() {
            var root = am5.Root.new("total-transaction-chart");

            root.setThemes([
                am5themes_Animated.new(root)
            ]);

            // Insert backend data
            var data = @json($totalTransactionData);

            // Create chart
            var chart = root.container.children.push(am5xy.XYChart.new(root, {
                focusable: true,
                panX: true,
                panY: false,
                wheelX: "panX",
                wheelY: "zoomX",
                pinchZoomX: true,
                paddingLeft: 0
            }));

            // X Axis (Date)
            var xAxis = chart.xAxes.push(am5xy.DateAxis.new(root, {
                baseInterval: {
                    timeUnit: "day",
                    count: 1
                },
                renderer: am5xy.AxisRendererX.new(root, {
                    minorGridEnabled: true,
                    minGridDistance: 70
                }),
                tooltip: am5.Tooltip.new(root, {})
            }));

            // Y Axis
            var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
                renderer: am5xy.AxisRendererY.new(root, {})
            }));

            // Line series
            var series = chart.series.push(am5xy.LineSeries.new(root, {
                name: "Transactions",
                xAxis: xAxis,
                yAxis: yAxis,
                valueYField: "value",
                valueXField: "date",
                tooltip: am5.Tooltip.new(root, {
                    pointerOrientation: "horizontal",
                    labelText: "{valueY}"
                })
            }));

            // Enable smooth curve
            series.strokes.template.setAll({
                strokeWidth: 2,
                stroke: am5.color(0x4e73df)
            });

            // Light area fill under line
            series.fills.template.setAll({
                fillOpacity: 0.2,
                visible: true
            });

            // Convert date strings ("YYYY-MM-DD") to timestamps
            series.data.processor = am5.DataProcessor.new(root, {
                dateFormat: "yyyy-MM-dd",
                dateFields: ["date"]
            });

            // Load backend data into the chart
            series.data.setAll(data);

            // Bullet circles
            series.bullets.push(function() {
                return am5.Bullet.new(root, {
                    sprite: am5.Circle.new(root, {
                        radius: 4,
                        stroke: series.get("stroke"),
                        strokeWidth: 2,
                        fill: root.interfaceColors.get("background")
                    })
                });
            });

            // Cursor
            var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {
                xAxis: xAxis,
                behavior: "none"
            }));
            cursor.lineY.set("visible", false);

            // Scrollbar
            chart.set("scrollbarX", am5.Scrollbar.new(root, {
                orientation: "horizontal"
            }));
            
            var exporting = am5plugins_exporting.Exporting.new(root, {
                menu: am5plugins_exporting.ExportingMenu.new(root, {}),
                filePrefix: "{{ __('messages.total_transactions') }}"
            });

            chart.appear(1000, 100);
        });
    </script>
@endpush
