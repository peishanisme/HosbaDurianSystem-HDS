<div id="kt_app_content_container" class="container-fluid">
    <livewire:components.headers.harvest-event-header :harvestEvent="$harvestEvent" />


    <div class="mb-15">
        <x-charts.harvest-events.tree-observations-chart :treeObservationsData="$treeObservationsData" />
    </div>

    <div class="mb-15">
        <x-charts.harvest-events.harvest-species-chart :harvestSpeciesData="$harvestSpeciesData" />
    </div>

    <div class="mb-15">
        <div class="w-100 h-100">
            <x-charts.harvest-events.top5-harvest-trees-chart :top10HarvestTreesData="$top10HarvestTreesData" />
        </div>
    </div>

    <livewire:components.generate-report-modal model="App\Models\HarvestEvent" :harvestEvent="$harvestEvent" />
</div>


@push('styles')
    <style>
        #harvest-species-chart,
        #fruit-quality-chart {
            width: 100%;
            height: 500px;
        }
    </style>
@endpush
