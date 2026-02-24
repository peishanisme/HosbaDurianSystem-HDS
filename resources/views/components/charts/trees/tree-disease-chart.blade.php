{{-- tree disease chart --}}
<div class="card pb-10" style="min-height: 605px;">
    <div class="card-header">
        <h3 class="card-title">{{ __('messages.tree_diseases') }}</h3>
    </div>
    @php
        $treeDiseaseData = $this->loadTreeDiseaseData();
        $hasData = collect($treeDiseaseData)->sum() > 0;
    @endphp

    @if ($hasData)
        <div class="card-body p-5" id="tree-disease-chart"></div>
    @else
        <div class="card-body p-5">
            <p class="text-center">{{ __('messages.no_data_available') }}</p>
        </div>
    @endif

</div>

@push('scripts')
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

            var exporting = am5plugins_exporting.Exporting.new(root, {
                menu: am5plugins_exporting.ExportingMenu.new(root, {}),
                filePrefix: "{{ __('messages.tree_diseases') }}"
            });

            series.appear(1000);
            chart.appear(1000, 100);

        });
    </script>
@endpush
