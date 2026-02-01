{{-- harvest grade chart --}}
<div class="card pb-10" style="min-height: 605px;">
    <div class="card-header">
        <h3 class="card-title">{{ __('messages.harvest_grade') }}</h3>
    </div>
    @php
        $harvestGradeData = $this->loadHarvestGradeData();
        $hasData = collect($harvestGradeData)->sum() > 0;
    @endphp

    @if ($hasData)
        <div class="card-body p-5" id="harvest-grade-chart"></div>
    @else
        <div class="card-body p-5">
            <p class="text-center">{{ __('messages.no_data_available') }}</p>
        </div>
    @endif

</div>

@push('scripts')
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
                alignLabels: true,
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

            var exporting = am5plugins_exporting.Exporting.new(root, {
                menu: am5plugins_exporting.ExportingMenu.new(root, {}),
                filePrefix: "{{ __('messages.harvest_grade') }}"
            });

            series.appear(1000, 100);

        });
    </script>
@endpush
