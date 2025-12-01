<div id="kt_app_content_container" class="container-fluid">
    <livewire:components.headers.tree-details-header :tree="$tree" />

    {{-- growth log chart --}}
    <div class="card pb-10">
        <div class="card-header">
            <h3 class="card-title">Growth Log Chart</h3>
        </div>
        <div class="card-body p-5" id="growth-log-chart"></div>
    </div>

    {{-- harvest grade chart --}}
    <div class="card pb-10">
        <div class="card-header">
            <h3 class="card-title">Harvest Grade</h3>
        </div>
        <div class="card-body p-5" id="harvest-grade-chart"></div>
    </div>

    {{-- tree disease chart --}}
    <div class="card pb-10">
        <div class="card-header">
            <h3 class="card-title">Tree Disease</h3>
        </div>
        <div class="card-body p-5" id="tree-disease-chart"></div>
    </div>

</div>

@push('styles')
    <style>
        #growth-log-chart,
        #harvest-grade-chart,
        #tree-disease-chart {
            width: 100%;
            height: 500px;
        }
    </style>
@endpush

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

            // ⭐ Add X-Axis Label
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

            // ⭐ Add Y-Axis Label
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

            // Animations
            seriesHeight.appear(1000);
            seriesDiameter.appear(1000);
            chart.appear(1000, 100);

        });
    </script>

    <script>
        am5.ready(function() {

            const harvestGradeData = @json($harvestGradeData);

            const chartData = Object.entries(harvestGradeData).map(([grade, count]) => ({
                category: grade,
                value: count
            }));

            var root = am5.Root.new("harvest-grade-chart");

            root.setThemes([
                am5themes_Animated.new(root)
            ]);

            var chart = root.container.children.push(am5percent.PieChart.new(root, {
                layout: root.verticalLayout,
                innerRadius: am5.percent(50)
            }));

            var series = chart.series.push(am5percent.PieSeries.new(root, {
                valueField: "value",
                categoryField: "category",
                alignLabels: false,
                legendLabelText: "{category}",
                legendValueText: "{value} pc(s)"
            }));

            series.labels.template.setAll({
                textType: "circular",
                centerX: 0,
                centerY: 0
            });

            series.data.setAll(chartData);

            // Create legend
            var legend = chart.children.push(am5.Legend.new(root, {
                centerX: am5.percent(50),
                x: am5.percent(50),
                marginTop: 15,
                marginBottom: 15
            }));

            legend.data.setAll(series.dataItems);

            series.appear(1000, 100);

        });
    </script>

    <script>
        am5.ready(function() {

            var root = am5.Root.new("tree-disease-chart");

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

            var xRenderer = am5xy.AxisRendererX.new(root, {
                minGridDistance: 30,
                minorGridEnabled: true
            });

            xRenderer.labels.template.setAll({
                rotation: -90,
                centerY: am5.p50,
                centerX: am5.p100,
                paddingRight: 15
            });

            var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
                maxDeviation: 0.3,
                categoryField: "country",
                renderer: xRenderer,
                tooltip: am5.Tooltip.new(root, {})
            }));

            var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
                maxDeviation: 0.3,
                renderer: am5xy.AxisRendererY.new(root, {})
            }));

            var series = chart.series.push(am5xy.ColumnSeries.new(root, {
                name: "Series 1",
                xAxis: xAxis,
                yAxis: yAxis,
                valueYField: "value",
                sequencedInterpolation: true,
                categoryXField: "country",
                tooltip: am5.Tooltip.new(root, {
                    labelText: "{valueY}"
                })
            }));

            series.columns.template.setAll({
                cornerRadiusTL: 5,
                cornerRadiusTR: 5,
                strokeOpacity: 0
            });

            // Inject Livewire data
            let chartDataRaw = @json($treeDiseaseData ?? []);

            let chartData = Object.entries(chartDataRaw).map(([name, count]) => ({
                country: name,
                value: count
            }));

            xAxis.data.setAll(chartData);
            series.data.setAll(chartData);

            series.appear(1000);
            chart.appear(1000, 100);

        });
    </script>
@endpush
