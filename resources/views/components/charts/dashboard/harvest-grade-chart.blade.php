<div class="card pb-10" style="min-height: 505px">
    <div class="card-header">
        <h3 class="card-title">Harvest Grades</h3>
    </div>
    @if (empty($gradeDistributionData) || count($gradeDistributionData) === 0)
        <div class="card-body p-5">
            <p class="text-center">{{ __('messages.no_data_available') }}</p>
        </div>
    @else
        <div wire:ignore class="card-body p-5" id="harvest-grade-chart" style="width: 93%; height: 400px;"></div>
    @endif
</div>

@push('scripts')
    <script>
        let harvestGradeChartRoot = null;

        function initHarvestGradeChart(chartData) {

            if (harvestGradeChartRoot) {
                harvestGradeChartRoot.dispose();
            }

            harvestGradeChartRoot = am5.Root.new("harvest-grade-chart");

            let root = harvestGradeChartRoot;

            root.setThemes([
                am5themes_Animated.new(root)
            ]);

            // Chart
            let chart = root.container.children.push(
                am5xy.XYChart.new(root, {
                    panX: false,
                    panY: false,
                    wheelX: "none",
                    wheelY: "none",
                    layout: root.verticalLayout
                })
            );

            // Y Axis (Grades)
            let yAxis = chart.yAxes.push(
                am5xy.CategoryAxis.new(root, {
                    categoryField: "grade",
                    renderer: am5xy.AxisRendererY.new(root, {
                        inversed: true,
                        minGridDistance: 20
                    })
                })
            );

            yAxis.data.setAll(chartData);

            // X Axis (Weight)
            let xAxis = chart.xAxes.push(
                am5xy.ValueAxis.new(root, {
                    min: 0,
                    renderer: am5xy.AxisRendererX.new(root, {})
                })
            );

            // Series
            let series = chart.series.push(
                am5xy.ColumnSeries.new(root, {
                    name: "Weight",
                    xAxis: xAxis,
                    yAxis: yAxis,
                    valueXField: "weight",
                    categoryYField: "grade",
                    tooltip: am5.Tooltip.new(root, {
                        labelText: "{categoryY}: {valueX} kg"
                    })
                })
            );

            // Rounded bars
            series.columns.template.setAll({
                cornerRadiusTR: 8,
                cornerRadiusBR: 8,
                strokeOpacity: 0,
                height: am5.percent(70)
            });

            // Labels inside bar
            series.bullets.push(function() {
                return am5.Bullet.new(root, {
                    locationX: 1,
                    sprite: am5.Label.new(root, {
                        text: "{valueX} kg",
                        centerY: am5.p50,
                        populateText: true
                    })
                });
            });

            var exporting = am5plugins_exporting.Exporting.new(root, {
                menu: am5plugins_exporting.ExportingMenu.new(root, {}),
                filePrefix: "Harvest_Grade_Distribution"
            });

            series.data.setAll(chartData);

            // Animation
            series.appear(1000);
            chart.appear(1000, 100);
        }

        document.addEventListener('livewire:init', () => {

            initHarvestGradeChart(@js($gradeDistributionData));
            console.log('Initial Grade Distribution Data:', @js($gradeDistributionData));

            Livewire.on('refresh-grade-chart', (data) => {
                initHarvestGradeChart(data);
            });

        });
    </script>
@endpush
