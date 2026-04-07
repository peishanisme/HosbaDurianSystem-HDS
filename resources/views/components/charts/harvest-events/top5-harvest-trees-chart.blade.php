<div class="card pb-10">
    <div class="card-header">
        <h3 class="card-title">{{ __('messages.top_10_harvest_trees') }}</h3>
    </div>
    <div wire:ignore class="card-body p-5" id="top10-harvest-trees-chart" style="width: 95%;"></div>
</div>

@push('styles')
    <style>
        #top10-harvest-trees-chart {
            width: 100%;
            height: 600px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        am5.ready(function() {

            // 1. Load Livewire data
            const rawData = @json($top10HarvestTreesData ?? []);

            // 2. Convert format
            const chartData = rawData.map(item => ({
                tree: item.tree,
                total: Number(item.total || 0),
            }));

            // 3. Create root
            const root = am5.Root.new("top10-harvest-trees-chart");

            // 4. Themes
            root.setThemes([
                am5themes_Animated.new(root)
            ]);

            // 5. Create chart
            const chart = root.container.children.push(
                am5xy.XYChart.new(root, {
                    panX: false,
                    panY: false,
                    wheelX: "none",
                    wheelY: "none",
                    layout: root.verticalLayout
                })
            );

            // 6. Cursor
            const cursor = chart.set("cursor", am5xy.XYCursor.new(root, {}));
            cursor.lineX.set("visible", false);
            cursor.lineY.set("visible", false);

            // 7. Y-axis (trees)
            const yAxis = chart.yAxes.push(am5xy.CategoryAxis.new(root, {
                categoryField: "tree",
                renderer: am5xy.AxisRendererY.new(root, {})
            }));
            yAxis.data.setAll(chartData);

            // 8. X-axis (total fruits)
            const xAxis = chart.xAxes.push(am5xy.ValueAxis.new(root, {
                min: 0,
                renderer: am5xy.AxisRendererX.new(root, {})
            }));

            // 9. Create single series
            const series = chart.series.push(
                am5xy.ColumnSeries.new(root, {
                    name: "Total Fruits",
                    xAxis: xAxis,
                    yAxis: yAxis,
                    valueXField: "total",
                    categoryYField: "tree",
                    tooltip: am5.Tooltip.new(root, {
                        labelText: "{categoryY}: {valueX}"
                    })
                })
            );

            // Optional: color
            series.columns.template.setAll({
                fill: am5.color(0x7A9F79),
                stroke: am5.color(0x7A9F79)
            });

            // Add value labels
            series.bullets.push(function() {
                return am5.Bullet.new(root, {
                    sprite: am5.Label.new(root, {
                        text: "{valueX}",
                        fill: root.interfaceColors.get("alternativeText"),
                        centerY: am5.p50,
                        centerX: am5.p50,
                        populateText: true
                    })
                });
            });

            series.data.setAll(chartData);

            // 10. Export
            var exporting = am5plugins_exporting.Exporting.new(root, {
                menu: am5plugins_exporting.ExportingMenu.new(root, {}),
                filePrefix: "{{ __('messages.top_10_harvest_trees') }}"
            });

            // 11. Animate
            chart.appear(1000, 100);

        });
    </script>
@endpush
