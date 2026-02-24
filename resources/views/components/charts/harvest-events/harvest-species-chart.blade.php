<div class="card pb-10">
    <div class="card-header">
        <h3 class="card-title">{{ __('messages.harvest_species_overview') }}</h3>
    </div>
    <div wire:ignore class="card-body p-5" id="harvest-species-chart" style="width: 100%;"></div>
</div>



@push('scripts')
    <script>
        am5.ready(function() {

            var root = am5.Root.new("harvest-species-chart");

            root.setThemes([
                am5themes_Animated.new(root)
            ]);

            root.container.set("layout", root.verticalLayout);

            // Create container to hold charts
            var chartContainer = root.container.children.push(am5.Container.new(root, {
                layout: root.horizontalLayout,
                width: am5.p100,
                height: am5.p100
            }));

            // Create the 1st chart
            var chart = chartContainer.children.push(
                am5percent.PieChart.new(root, {
                    endAngle: 270,
                    radius: am5.percent(60),
                    innerRadius: am5.percent(50)

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

            series.children.push(am5.Label.new(root, {
                centerX: am5.percent(50),
                centerY: am5.percent(50),
                text: "{{ __('messages.pieces') }}\n{valueSum} {{ __('messages.pieces') }}",
                populateText: true,
                fontSize: "1.2em"
            }));

            series.slices.template.setAll({
                cornerRadius: 8
            })

            series.states.create("hidden", {
                endAngle: -90
            });

            series.labels.template.setAll({
                textType: "circular"
            });

            // Create the 2nd chart
            var chart2 = chartContainer.children.push(
                am5percent.PieChart.new(root, {
                    endAngle: 270,
                    radius: am5.percent(60),
                    innerRadius: am5.percent(50)

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

            series2.children.push(am5.Label.new(root, {
                centerX: am5.percent(50),
                centerY: am5.percent(50),
                text: "{{ __('messages.total_weight') }}\n{valueSum} kg",
                populateText: true,
                fontSize: "1.2em"
            }));

            series2.slices.template.setAll({
                cornerRadius: 8
            })

            series2.states.create("hidden", {
                endAngle: -90
            });

            series2.labels.template.setAll({
                textType: "circular"
            });


            // Duplicate interaction
            // Must be added before setting data
            series.slices.template.events.on("pointerover", function(ev) {
                var slice = ev.target;
                var dataItem = slice.dataItem;
                var otherSlice = getSlice(dataItem, series2);

                if (otherSlice) {
                    otherSlice.hover();
                }
            });

            series.slices.template.events.on("pointerout", function(ev) {
                var slice = ev.target;
                var dataItem = slice.dataItem;
                var otherSlice = getSlice(dataItem, series2);

                if (otherSlice) {
                    otherSlice.unhover();
                }
            });

            series.slices.template.on("active", function(active, target) {
                var slice = target;
                var dataItem = slice.dataItem;
                var otherSlice = getSlice(dataItem, series2);

                if (otherSlice) {
                    otherSlice.set("active", active);
                }
            });

            // Same for the 2nd series
            series2.slices.template.events.on("pointerover", function(ev) {
                var slice = ev.target;
                var dataItem = slice.dataItem;
                var otherSlice = getSlice(dataItem, series);

                if (otherSlice) {
                    otherSlice.hover();
                }
            });

            series2.slices.template.events.on("pointerout", function(ev) {
                var slice = ev.target;
                var dataItem = slice.dataItem;
                var otherSlice = getSlice(dataItem, series);

                if (otherSlice) {
                    otherSlice.unhover();
                }
            });

            series2.slices.template.on("active", function(active, target) {
                var slice = target;
                var dataItem = slice.dataItem;
                var otherSlice = getSlice(dataItem, series);

                if (otherSlice) {
                    otherSlice.set("active", active);
                }
            });

            var speciesData = @json($harvestSpeciesData);

            // Data for pieces
            var piecesData = speciesData.map(item => ({
                category: item.species,
                value: item.total_pieces

            }));

            // Data for weight
            var weightData = speciesData.map(item => ({
                category: item.species,
                value: item.total_weight
            }));

            series.data.setAll(piecesData);
            series2.data.setAll(weightData);

            function getSlice(dataItem, series) {
                var otherSlice;
                am5.array.each(series.dataItems, function(di) {
                    if (di.get("category") === dataItem.get("category")) {
                        otherSlice = di.get("slice");
                    }
                });

                return otherSlice;
            }

            // Create legend
            var legend = root.container.children.push(am5.Legend.new(root, {
                x: am5.percent(50),
                centerX: am5.percent(50)
            }));


            // Trigger all the same for the 2nd series
            legend.itemContainers.template.events.on("pointerover", function(ev) {
                var dataItem = ev.target.dataItem.dataContext;
                var slice = getSlice(dataItem, series2);
                slice.hover();
            });

            legend.itemContainers.template.events.on("pointerout", function(ev) {
                var dataItem = ev.target.dataItem.dataContext;
                var slice = getSlice(dataItem, series2);
                slice.unhover();
            });

            legend.itemContainers.template.on("disabled", function(disabled, target) {
                var dataItem = target.dataItem.dataContext;
                var slice = getSlice(dataItem, series2);
                if (disabled) {
                    series2.hideDataItem(slice.dataItem);
                } else {
                    series2.showDataItem(slice.dataItem);
                }
            });

            legend.data.setAll(series.dataItems);

            series.appear(1000, 100);

            var exporting = am5plugins_exporting.Exporting.new(root, {
                menu: am5plugins_exporting.ExportingMenu.new(root, {}),
                filePrefix: "{{ __('messages.harvest_species') }}"
            });

        });
    </script>
@endpush
