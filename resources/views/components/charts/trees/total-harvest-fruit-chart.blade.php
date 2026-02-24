{{-- total harvest in each event chart --}}
<div class="card pb-10" style="min-height: 605px;">
    <div class="card-header">
        <h3 class="card-title">{{ __('messages.total_harvest_in_each_event') }}</h3>
    </div>

    @if (!empty($totalHarvestData))
        <div class="card-body p-5" id="total-harvest-chart" style="width: 93%;"></div>
    @else
        <div class="card-body p-5">
            <p class="text-center">{{ __('messages.no_data_available') }}</p>
        </div>
    @endif

</div>

@push('scripts')
    <script>
        am5.ready(function() {

            var root = am5.Root.new("total-harvest-chart");

            root.setThemes([
                am5themes_Animated.new(root)
            ]);

            // HORIZONTAL CHART
            var chart = root.container.children.push(am5xy.XYChart.new(root, {
                panX: false,
                panY: false,
                wheelX: "none",
                wheelY: "none",
                layout: root.verticalLayout
            }));

            var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {}));
            cursor.lineX.set("visible", false);
            cursor.lineY.set("visible", false);

            // Inject Livewire Data
            let harvestDataRaw = @json($totalHarvestData ?? []);

            // Convert to amCharts format
            let chartData = Object.entries(harvestDataRaw).map(([event, total]) => ({
                event: event,
                total: total
            }));

            // Y-AXIS (Category)
            var yAxis = chart.yAxes.push(am5xy.CategoryAxis.new(root, {
                categoryField: "event",
                renderer: am5xy.AxisRendererY.new(root, {
                    minGridDistance: 20,
                    inversed: true
                }),
                tooltip: am5.Tooltip.new(root, {})
            }));

            // X-AXIS (Values)
            var xAxis = chart.xAxes.push(am5xy.ValueAxis.new(root, {
                renderer: am5xy.AxisRendererX.new(root, {})
            }));

            // SERIES (Horizontal Bars)
            var series = chart.series.push(am5xy.ColumnSeries.new(root, {
                name: "Harvest Total",
                xAxis: xAxis,
                yAxis: yAxis,
                valueXField: "total",
                categoryYField: "event",
                sequencedInterpolation: true,
                tooltip: am5.Tooltip.new(root, {
                    labelText: "{categoryY}: {valueX}"
                })
            }));

            chart.get("colors").set("colors", [
                am5.color(0x97CF8A),
                am5.color(0xB1DD9A),
                am5.color(0x315E26),
                am5.color(0x7A9F79),
                am5.color(0xACD1AF),
                am5.color(0x315A39)
            ]);

            // Set data
            yAxis.data.setAll(chartData);
            series.data.setAll(chartData);

            var exporting = am5plugins_exporting.Exporting.new(root, {
                menu: am5plugins_exporting.ExportingMenu.new(root, {}),
                filePrefix: "{{ __('messages.total_harvest_in_each_event') }}"
            });

            series.appear(1000);
            chart.appear(1000, 100);

        });
    </script>
@endpush
