<div class="card pb-10">
    <div class="card-header">
        <h3 class="card-title">Top 10 Harvest Trees</h3>
    </div>
    <div class="card-body p-5" id="top10-harvest-trees-chart"></div>
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
            const rawData = @json($top5HarvestTreesData ?? []);

            // 2. Convert to chart-friendly format
            // Each object: {tree: "Tree 1", A: 5, B: 2, C: 1, D: 0}
            const chartData = rawData.map(item => ({
                tree: item.tree,
                S: Number(item.S || 0),
                A: Number(item.A || 0),
                B: Number(item.B || 0),
                C: Number(item.C || 0),
                D: Number(item.D || 0),
            }));

            const grades = ['S', 'A', 'B', 'C', 'D'];
            const colors = [
                am5.color(0x315E26),
                am5.color(0x7A9F79),
                am5.color(0x97CF8A),
                am5.color(0xB1DD9A),
                am5.color(0xD4E6B3)
            ];

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

            // 6. Add cursor
            const cursor = chart.set("cursor", am5xy.XYCursor.new(root, {}));
            cursor.lineX.set("visible", false);
            cursor.lineY.set("visible", false);

            // 7. Y-axis (category = tree)
            const yRenderer = am5xy.AxisRendererY.new(root, {});
            const yAxis = chart.yAxes.push(am5xy.CategoryAxis.new(root, {
                categoryField: "tree",
                renderer: yRenderer,
                tooltip: am5.Tooltip.new(root, {})
            }));
            yRenderer.grid.template.setAll({
                location: 1
            });
            yAxis.data.setAll(chartData);

            // 8. X-axis (value)
            const xAxis = chart.xAxes.push(am5xy.ValueAxis.new(root, {
                min: 0,
                renderer: am5xy.AxisRendererX.new(root, {})
            }));

            // 9. Legend
            const legend = chart.children.push(am5.Legend.new(root, {
                centerX: am5.percent(50),
                x: am5.percent(50),
                marginTop: 15,
                marginBottom: 15
            }));

            // 10. Function to create stacked series per grade
            function makeSeries(grade, color) {
                const series = chart.series.push(
                    am5xy.ColumnSeries.new(root, {
                        name: "Grade " + grade,
                        stacked: true,
                        xAxis: xAxis,
                        yAxis: yAxis,
                        valueXField: grade,
                        categoryYField: "tree",
                        fill: color,
                        stroke: color,
                        tooltip: am5.Tooltip.new(root, {
                            labelText: "{name} - {categoryY}: {valueX}"
                        })
                    })
                );

                // Add bullet to show value
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

                // Add series to legend
                legend.data.push(series);
            }

            // 11. Create series for each grade
            grades.forEach((grade, index) => {
                makeSeries(grade, colors[index]);
            });

            var exporting = am5plugins_exporting.Exporting.new(root, {
                menu: am5plugins_exporting.ExportingMenu.new(root, {}),
                filePrefix: "{{ __('messages.top_selling_trees') }}"
            });

            // 12. Animate chart
            chart.appear(1000, 100);

        });
    </script>
@endpush
