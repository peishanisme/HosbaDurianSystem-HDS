<div class="card pb-10">
    <div class="card-header">
        <h3 class="card-title">Harvest Species Overview</h3>
    </div>
    <div class="card-body p-5" id="harvest-species-chart"></div>
</div>

@push('scripts')
    <script>
        am5.ready(function() {

            // Create root element
            var root = am5.Root.new("harvest-species-chart");

            root.setThemes([
                am5themes_Animated.new(root)
            ]);

            // Create chart
            var chart = root.container.children.push(am5percent.PieChart.new(root, {
                layout: root.verticalLayout,
            }));

            var series = chart.series.push(
                am5percent.PieSeries.new(root, {
                    valueField: "value",
                    categoryField: "category",
                    alignLabels: true
                })
            );

            // Inject Livewire Data
            let speciesDataRaw = @json($harvestSpeciesData);

            // Convert to amCharts format
            let chartData = speciesDataRaw.map(item => ({
                category: item.species,
                value: Number(item.total)
            }));

            // Set data
            series.data.setAll(chartData);

            series.labels.template.setAll({
                text: "{category}: {value} pc(s)",
                fontSize: 12,
                fill: am5.color(0x000000)
            });

            // Animate
            series.appear(1000, 100);

        });
    </script>
@endpush
