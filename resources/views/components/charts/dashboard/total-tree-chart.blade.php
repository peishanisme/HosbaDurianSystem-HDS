<div class="card pb-10 h-100">
    <div class="card-header">
        <h3 class="card-title">{{ __('messages.total_trees_by_species') }}</h3>
        <div class="d-flex align-items-center justify-content-end">
            <a class="btn btn-secondary" style="height: 45px" href="{{ route('tree.trees.index') }}">{{ __('messages.view_trees') }}</a>
        </div>
    </div>
    <div class="card-body p-5" id="total-tree-chart" style="width: 100%; height: 350px;"></div>
</div>

@push('scripts')
    <script>
        am5.ready(function() {

            const chartData = @json($totalTreeData);

            var root = am5.Root.new("total-tree-chart");

            root.setThemes([
                am5themes_Animated.new(root)
            ]);

            var chart = root.container.children.push(am5percent.PieChart.new(root, {
                layout: root.verticalLayout,
                innerRadius: am5.percent(60)

            }));

            var series = chart.series.push(am5percent.PieSeries.new(root, {
                valueField: "value",
                categoryField: "category",
                alignLabels: true,
                legendLabelText: "{category}",
                legendValueText: "{value}"
            }));

            series.children.push(am5.Label.new(root, {
                centerX: am5.percent(50),
                centerY: am5.percent(50),
                text: "{{ __('messages.total_trees') }}: {valueSum}",
                populateText: true,
                fontSize: "1.3em"
            }));

            series.labels.template.setAll({
                textType: "circular",
                centerX: 0,
                centerY: 0
            });

            // Set the data
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
                filePrefix: "{{ __('messages.total_trees_by_species') }}"
            });

            series.appear(1000, 100);

        });
    </script>
@endpush
