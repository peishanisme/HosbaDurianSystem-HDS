{{-- growth log chart --}}
<div class="card pb-10">
    <div class="card-header">
        <h3 class="card-title">Growth Log Chart</h3>
    </div>
    <div class="card-body p-5" id="growth-log-chart"></div>
</div>

@push('scripts')
    <script>
        am5.ready(function() {

            const growthLogData = @json($growthLogData);

            var data = growthLogData.map(item => ({
                date: new Date(item.created_at).getTime(),
                height: Number(item.height),
                diameter: Number(item.diameter)
            }));

            // Root
            var root = am5.Root.new("growth-log-chart");

            root.setThemes([am5themes_Animated.new(root)]);

            // Chart
            var chart = root.container.children.push(
                am5xy.XYChart.new(root, {
                    panX: true,
                    panY: true,
                    wheelX: "panX",
                    wheelY: "zoomX",
                    pinchZoomX: true
                })
            );

            // Cursor
            var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {}));
            cursor.lineY.set("visible", false);

            // X-Axis
            var xAxis = chart.xAxes.push(
                am5xy.DateAxis.new(root, {
                    baseInterval: {
                        timeUnit: "day",
                        count: 1
                    },
                    renderer: am5xy.AxisRendererX.new(root, {
                        minorGridEnabled: true
                    }),
                    tooltip: am5.Tooltip.new(root, {})
                })
            );

            // Add X-Axis Label
            xAxis.children.push(
                am5.Label.new(root, {
                    text: "Date",
                    fontSize: 14,
                    paddingTop: 10,
                    centerX: am5.p50
                })
            );

            // Y-Axis
            var yAxis = chart.yAxes.push(
                am5xy.ValueAxis.new(root, {
                    renderer: am5xy.AxisRendererY.new(root, {})
                })
            );

            // Add Y-Axis Label
            yAxis.children.moveValue(
                am5.Label.new(root, {
                    rotation: -90,
                    text: "Measurement (m)",
                    fontSize: 14,
                    centerY: am5.p50,
                    y: am5.p50
                }),
                0
            );

            // ================================
            //    HEIGHT SERIES + BULLETS
            // ================================
            var seriesHeight = chart.series.push(
                am5xy.LineSeries.new(root, {
                    name: "Height",
                    xAxis: xAxis,
                    yAxis: yAxis,
                    valueYField: "height",
                    valueXField: "date",
                    tooltip: am5.Tooltip.new(root, {
                        labelText: "Height: {height}m"
                    })
                })
            );

            seriesHeight.strokes.template.setAll({
                strokeWidth: 2
            });

            // ⭐ Add bullet to Height
            seriesHeight.bullets.push(function() {
                return am5.Bullet.new(root, {
                    sprite: am5.Circle.new(root, {
                        radius: 4,
                        fill: seriesHeight.get("stroke"),
                        strokeWidth: 2,
                        stroke: root.interfaceColors.get("background")
                    })
                });
            });

            // ================================
            //   DIAMETER SERIES + BULLETS
            // ================================
            var seriesDiameter = chart.series.push(
                am5xy.LineSeries.new(root, {
                    name: "Diameter",
                    xAxis: xAxis,
                    yAxis: yAxis,
                    valueYField: "diameter",
                    valueXField: "date",
                    tooltip: am5.Tooltip.new(root, {
                        labelText: "Diameter: {diameter}m"
                    })
                })
            );

            seriesDiameter.strokes.template.setAll({
                strokeDasharray: [2, 2],
                strokeWidth: 2
            });

            // Add bullet to Diameter
            seriesDiameter.bullets.push(function() {
                return am5.Bullet.new(root, {
                    sprite: am5.Circle.new(root, {
                        radius: 4,
                        fill: seriesDiameter.get("stroke"),
                        strokeWidth: 2,
                        stroke: root.interfaceColors.get("background")
                    })
                });
            });

            // Set Data
            seriesHeight.data.setAll(data);
            seriesDiameter.data.setAll(data);

            var exporting = am5plugins_exporting.Exporting.new(root, {
                menu: am5plugins_exporting.ExportingMenu.new(root, {}),
                filePrefix: "{{ __('messages.tree_growth_log') }}"
            });

            // Animations
            seriesHeight.appear(1000);
            seriesDiameter.appear(1000);
            chart.appear(1000, 100);

        });
    </script>
@endpush
