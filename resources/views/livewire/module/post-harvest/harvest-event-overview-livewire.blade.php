<div id="kt_app_content_container" class="container-fluid">
    <livewire:components.headers.harvest-event-header :harvestEvent="$harvestEvent" />


    <div class="mb-15">
        <x-charts.harvest-events.tree-observations-chart :treeObservationsData="$this->treeObservationsData" />
    </div>

    <div class="mb-15">
        <div class="card pb-10">
            <div class="card-header">
                <h3 class="card-title">{{ __('messages.harvest_species_overview') }}</h3>

                {{-- date filter --}}
                <div class="mt-5">
                    <livewire:components.date-filter>
                </div>
            </div>
            <x-charts.harvest-events.harvest-species-chart :harvestSpeciesData="$this->harvestSpeciesData" :harvestEventName="$harvestEvent->event_name" />

        </div>
    </div>

    <div class="mb-15">
        <div class="w-100 h-100">
            <x-charts.harvest-events.top5-harvest-trees-chart :top10HarvestTreesData="$this->top10HarvestTreesData" />
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


