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

            root.container.set("layout", root.verticalLayout);

            // Container
            var chartContainer = root.container.children.push(am5.Container.new(root, {
                layout: root.horizontalLayout,
                width: am5.p100,
                height: am5.p100
            }));

            // -----------------------------
            // DATA
            // -----------------------------
            var observationData = @json($treeObservationsData);

            // Total flowering trees (exclude X)
            var totalFloweringTrees = observationData
                .filter(item => item.status !== 'X')
                .reduce((sum, item) => sum + item.count, 0);

            // Total estimated fruits
            var totalEstimatedFruits = observationData
                .reduce((sum, item) => sum + item.estimated, 0);

            // Chart 1 data (tree count)
            var treeCountData = observationData.map(item => ({
                category: item.status,
                value: item.count
            }));

            // Chart 2 data (estimated fruits)
            var estimatedData = observationData.map(item => ({
                category: item.status,
                value: item.estimated
            }));

            // -----------------------------
            // COLOR MAP
            // -----------------------------
            const statusColors = {
                A: am5.color(0xff4d4f),
                B: am5.color(0xffa940),
                C: am5.color(0x40a9ff),
                D: am5.color(0x73d13d),
                X: am5.color(0xd9d9d9)
            };

            // -----------------------------
            // CHART 1 (Tree Count)
            // -----------------------------
            var chart = chartContainer.children.push(
                am5percent.PieChart.new(root, {
                    endAngle: 270,
                    radius: am5.percent(60),
                    innerRadius: am5.percent(60)
                })
            );

            var series = chart.series.push(
                am5percent.PieSeries.new(root, {
                    valueField: "value",
                    categoryField: "category",
                    endAngle: 270,
                    alignLabels: true
                })
            );

            // Center label
            series.children.push(am5.Label.new(root, {
                centerX: am5.percent(50),
                centerY: am5.percent(50),
                text: "{{ __('messages.flowering_trees') }}\n" + totalFloweringTrees,
                populateText: false,
                fontSize: "1.2em"
            }));

            // Styling
            series.slices.template.setAll({
                cornerRadius: 8
            });
            series.states.create("hidden", {
                endAngle: -90
            });
            series.labels.template.setAll({
                text: "{category}: {value}",
                textType: "circular"
            });

            // Colors
            series.slices.template.adapters.add("fill", (fill, target) => {
                return statusColors[target.dataItem.dataContext.category] || fill;
            });

            // -----------------------------
            // CHART 2 (Estimated Fruits)
            // -----------------------------
            var chart2 = chartContainer.children.push(
                am5percent.PieChart.new(root, {
                    endAngle: 270,
                    radius: am5.percent(60),
                    innerRadius: am5.percent(60)
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

            // Center label
            series2.children.push(am5.Label.new(root, {
                centerX: am5.percent(50),
                centerY: am5.percent(50),
                text: "{{ __('messages.estimated_fruits') }}\n" + totalEstimatedFruits,
                populateText: false,
                fontSize: "1.2em"
            }));

            // Styling
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

            // Colors
            series2.slices.template.adapters.add("fill", (fill, target) => {
                return statusColors[target.dataItem.dataContext.category] || fill;
            });

            // -----------------------------
            // DATA SET
            // -----------------------------
            series.data.setAll(treeCountData);
            series2.data.setAll(estimatedData);

            // -----------------------------
            // LINKED HOVER EFFECT
            // -----------------------------
            function getSlice(dataItem, series) {
                var otherSlice;
                am5.array.each(series.dataItems, function(di) {
                    if (di.get("category") === dataItem.get("category")) {
                        otherSlice = di.get("slice");
                    }
                });
                return otherSlice;
            }

            series.slices.template.events.on("pointerover", function(ev) {
                var other = getSlice(ev.target.dataItem, series2);
                if (other) other.hover();
            });

            series.slices.template.events.on("pointerout", function(ev) {
                var other = getSlice(ev.target.dataItem, series2);
                if (other) other.unhover();
            });

            series2.slices.template.events.on("pointerover", function(ev) {
                var other = getSlice(ev.target.dataItem, series);
                if (other) other.hover();
            });

            series2.slices.template.events.on("pointerout", function(ev) {
                var other = getSlice(ev.target.dataItem, series);
                if (other) other.unhover();
            });

            // -----------------------------
            // LEGEND
            // -----------------------------
            var legend = root.container.children.push(am5.Legend.new(root, {
                x: am5.percent(50),
                centerX: am5.percent(50)
            }));

            legend.data.setAll(series.dataItems);

            // -----------------------------
            // ANIMATION + EXPORT
            // -----------------------------
            series.appear(1000, 100);

            var exporting = am5plugins_exporting.Exporting.new(root, {
                menu: am5plugins_exporting.ExportingMenu.new(root, {}),
                filePrefix: "{{ __('messages.tree_observations') }}"
            });

        });
    </script>
@endpush
