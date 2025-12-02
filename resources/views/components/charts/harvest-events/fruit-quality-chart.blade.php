<div class="card pb-10">
    <div class="card-header">
        <h3 class="card-title">Fruit Quality</h3>
    </div>
    <div class="card-body p-5" id="fruit-quality-chart"></div>
</div>

@push('scripts')
    <script>
        am5.ready(function() {

            const fruitQualityData = @json($fruitQualityData);

            const chartData = Object.entries(fruitQualityData).map(([grade, count]) => ({
                category: grade,
                value: count
            }));

            var root = am5.Root.new("fruit-quality-chart");

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

            series.appear(1000, 100);

        });
    </script>
@endpush
