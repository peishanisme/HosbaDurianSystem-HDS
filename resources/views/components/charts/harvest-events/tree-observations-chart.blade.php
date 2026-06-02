<div class="card pb-10">
    <div class="card-header">
        <h3 class="card-title">{{ __('messages.tree_observations') }}</h3>
    </div>
    <div wire:ignore class="card-body p-5" id="tree-observations-chart" style="width: 100%; height: 500px;"></div>
</div>

@push('scripts')
    <script>
        am5.ready(function() {

            var root = am5.Root.new("tree-observations-chart");

            root.setThemes([
                am5themes_Animated.new(root)
            ]);

            // =============================
            // MAIN CONTAINER
            // =============================
            let mainContainer = root.container.children.push(
                am5.Container.new(root, {
                    width: am5.percent(100),
                    height: am5.percent(100),
                    layout: root.verticalLayout
                })
            );

            // =============================
            // TITLE
            // =============================
            mainContainer.children.push(
                am5.Label.new(root, {
                    text: "{{ __('messages.tree_observations') }} - {{ $harvestEventName }}",
                    fontSize: 21,
                    fontWeight: "500",
                    textAlign: "center",
                    x: am5.percent(50),
                    centerX: am5.percent(50),
                    marginBottom: 20,
                    paddingTop: 10
                })
            );

            // =============================
            // CHART ROW CONTAINER
            // =============================
            let chartContainer = mainContainer.children.push(
                am5.Container.new(root, {
                    width: am5.percent(100),
                    height: am5.percent(100),
                    layout: root.horizontalLayout
                })
            );

            // =============================
            // DATA
            // =============================
            var observationData = @json($treeObservationsData);

            var totalFloweringTrees = observationData
                .filter(item => item.status !== 'X')
                .reduce((sum, item) => sum + item.count, 0);

            var totalEstimatedFruits = observationData
                .reduce((sum, item) => sum + item.estimated, 0);

            var treeCountData = observationData.map(item => ({
                category: item.status,
                value: item.count
            }));

            var estimatedData = observationData.map(item => ({
                category: item.status,
                value: item.estimated
            }));

            // =============================
            // COLORS
            // =============================
            const statusColors = {
                A: am5.color(0xff4d4f),
                B: am5.color(0xffa940),
                C: am5.color(0x40a9ff),
                D: am5.color(0x73d13d),
                X: am5.color(0xd9d9d9)
            };

            // =============================
            // PIE CHART 1
            // =============================
            var chart1 = chartContainer.children.push(
                am5percent.PieChart.new(root, {
                    endAngle: 270,
                    radius: am5.percent(70),
                    innerRadius: am5.percent(60),
                    width: am5.percent(50)
                })
            );

            var series1 = chart1.series.push(
                am5percent.PieSeries.new(root, {
                    valueField: "value",
                    categoryField: "category",
                    endAngle: 270,
                    alignLabels: true
                })
            );

            series1.children.push(
                am5.Label.new(root, {
                    centerX: am5.percent(50),
                    centerY: am5.percent(50),
                    text: "{{ __('messages.flowering_trees') }}\n" + totalFloweringTrees,
                    textAlign: "center",
                    populateText: false,
                    fontSize: "1.2em"
                })
            );

            series1.slices.template.setAll({
                cornerRadius: 8
            });

            series1.states.create("hidden", {
                endAngle: -90
            });

            series1.labels.template.setAll({
                text: "{category}: {value}",
                textType: "circular"
            });

            series1.slices.template.adapters.add("fill", (fill, target) => {
                return statusColors[target.dataItem.dataContext.category] || fill;
            });

            // =============================
            // PIE CHART 2
            // =============================
            var chart2 = chartContainer.children.push(
                am5percent.PieChart.new(root, {
                    endAngle: 270,
                    radius: am5.percent(70),
                    innerRadius: am5.percent(60),
                    width: am5.percent(50)
                })
            );

            var series2 = chart2.series.push(
                am5percent.PieSeries.new(root, {
                    valueField: "value",
                    categoryField: "category",
                    endAngle: 270,
                    alignLabels: true,
                    tooltip: am5.Tooltip.new(root, {})
                })
            );

            series2.children.push(
                am5.Label.new(root, {
                    centerX: am5.percent(50),
                    centerY: am5.percent(50),
                    text: "{{ __('messages.estimated_fruits') }}\n" + totalEstimatedFruits,
                    textAlign: "center",
                    populateText: false,
                    fontSize: "1.2em"
                })
            );

            series2.slices.template.setAll({
                cornerRadius: 8
            });

            series2.states.create("hidden", {
                endAngle: -90
            });

            series2.labels.template.setAll({
                text: "{category}: {value}",
                textType: "circular"
            });

            series2.slices.template.adapters.add("fill", (fill, target) => {
                return statusColors[target.dataItem.dataContext.category] || fill;
            });

            // =============================
            // SET DATA
            // =============================
            series1.data.setAll(treeCountData);
            series2.data.setAll(estimatedData);

            // =============================
            // LINKED HOVER
            // =============================
            function getSlice(dataItem, series) {
                var otherSlice;

                am5.array.each(series.dataItems, function(di) {
                    if (di.get("category") === dataItem.get("category")) {
                        otherSlice = di.get("slice");
                    }
                });

                return otherSlice;
            }

            series1.slices.template.events.on("pointerover", function(ev) {
                var other = getSlice(ev.target.dataItem, series2);
                if (other) other.hover();
            });

            series1.slices.template.events.on("pointerout", function(ev) {
                var other = getSlice(ev.target.dataItem, series2);
                if (other) other.unhover();
            });

            series2.slices.template.events.on("pointerover", function(ev) {
                var other = getSlice(ev.target.dataItem, series1);
                if (other) other.hover();
            });

            series2.slices.template.events.on("pointerout", function(ev) {
                var other = getSlice(ev.target.dataItem, series1);
                if (other) other.unhover();
            });

            // =============================
            // LEGEND
            // =============================
            var legend = mainContainer.children.push(
                am5.Legend.new(root, {
                    x: am5.percent(50),
                    centerX: am5.percent(50),
                    marginTop: 15
                })
            );

            legend.data.setAll(series1.dataItems);

            // =============================
            // ANIMATION
            // =============================
            series1.appear(1000, 100);
            series2.appear(1000, 100);

            // =============================
            // EXPORT
            // =============================
            am5plugins_exporting.Exporting.new(root, {
                menu: am5plugins_exporting.ExportingMenu.new(root, {}),
                filePrefix: "{{ __('messages.tree_observations') }}"
            });

        });
    </script>
@endpush
