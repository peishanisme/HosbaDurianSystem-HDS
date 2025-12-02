<div class="card pb-10">
    <div class="card-header">
        <h3 class="card-title">Harvest Species Overview</h3>
    </div>
    <div class="card-body p-5" id="harvest-species-chart"></div>
</div>



@push('scripts')
    <script>
        am5.ready(function() {

            var root = am5.Root.new("harvest-species-chart");

            root.setThemes([
                am5themes_Animated.new(root)
            ]);

            root.container.set("layout", root.verticalLayout);

            var chartContainer = root.container.children.push(
                am5.Container.new(root, {
                    layout: root.horizontalLayout,
                    width: am5.p100,
                    height: am5.p100
                })
            );

            // ----------------------------------------
            //  Donut Chart: TOTAL PIECES
            // ----------------------------------------
            var chartPieces = chartContainer.children.push(
                am5percent.PieChart.new(root, {
                    innerRadius: am5.percent(60)
                })
            );

            var seriesPieces = chartPieces.series.push(
                am5percent.PieSeries.new(root, {
                    valueField: "value",
                    categoryField: "category",
                    alignLabels: false
                })
            );

            seriesPieces.children.push(
                am5.Label.new(root, {
                    centerX: am5.percent(50),
                    centerY: am5.percent(50),
                    text: "Total Pieces\n{valueSum}",
                    populateText: true,
                    fontSize: "1.2em",
                    textAlign: "center"
                })
            );

            // ----------------------------------------
            //  Donut Chart: TOTAL WEIGHT
            // ----------------------------------------
            var chartWeight = chartContainer.children.push(
                am5percent.PieChart.new(root, {
                    innerRadius: am5.percent(60)
                })
            );

            var seriesWeight = chartWeight.series.push(
                am5percent.PieSeries.new(root, {
                    valueField: "value",
                    categoryField: "category",
                    alignLabels: false
                })
            );

            seriesWeight.children.push(
                am5.Label.new(root, {
                    centerX: am5.percent(50),
                    centerY: am5.percent(50),
                    text: "Total Weight\n{valueSum} kg",
                    populateText: true,
                    fontSize: "1.2em",
                    textAlign: "center"
                })
            );

            function syncHover(seriesA, seriesB) {
                seriesA.slices.template.events.on("pointerover", function(ev) {
                    var category = ev.target.dataItem.get("category");
                    seriesB.dataItems.each(di => {
                        if (di.get("category") === category) di.get("slice").hover();
                    });
                });

                seriesA.slices.template.events.on("pointerout", function(ev) {
                    var category = ev.target.dataItem.get("category");
                    seriesB.dataItems.each(di => {
                        if (di.get("category") === category) di.get("slice").unhover();
                    });
                });
            }

            syncHover(seriesPieces, seriesWeight);
            syncHover(seriesWeight, seriesPieces);

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

            seriesPieces.data.setAll(piecesData);
            seriesWeight.data.setAll(weightData);

            // ----------------------------------------
            // 🏷 Shared Legend
            // ----------------------------------------
            var legend = root.container.children.push(
                am5.Legend.new(root, {
                    x: am5.percent(50),
                    centerX: am5.percent(50),
                    layout: root.horizontalLayout
                })
            );

            legend.data.setAll(seriesPieces.dataItems);

        }); 
    </script>
@endpush
