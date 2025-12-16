<div id="kt_app_content_container" class="container-fluid">
    <livewire:components.headers.tree-details-header :tree="$tree" />

    <div class="row">
        <div class="col-md-6 mb-10">
            <x-charts.trees.growth-log-chart :growthLogData="$growthLogData" />
        </div>

        <div class="col-md-6 mb-10">
            <x-charts.trees.harvest-grade-chart :harvestGradeData="$harvestGradeData" />
        </div>
    </div>

    <!-- Other charts -->
    <div class="row mb-10">
        <div class="col-md-6">
            <x-charts.trees.tree-disease-chart :treeDiseaseData="$treeDiseaseData" />
        </div>

        <div class="col-md-6">
            <x-charts.trees.total-harvest-fruit-chart :totalHarvestData="$totalHarvestData" />
        </div>
    </div>
</div>

@push('styles')
    <style>
        #growth-log-chart,
        #harvest-grade-chart,
        #tree-disease-chart,
        #total-harvest-chart {
            width: 100%;
            height: 500px;
        }
    </style>
@endpush
