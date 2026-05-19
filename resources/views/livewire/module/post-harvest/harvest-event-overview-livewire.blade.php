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
                <div class="my-5 d-flex gap-3 align-items-center">

                    <div class="input-group border border-gray-300 rounded">

                        <span class="input-group-text">
                            <i class="ki-duotone ki-calendar fs-3">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>

                        <input class="form-control form-control-solid" placeholder="Pick a date range"
                            id="kt_datepicker_7" />

                        @if ($this->showClearButton)
                            <button class="btn btn-light" wire:click="clearDateFilter">
                                Clear
                            </button>
                        @endif

                    </div>

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

@push('scripts')
    <script>
        const datePicker = flatpickr("#kt_datepicker_7", {

            mode: "range",
            dateFormat: "Y-m-d",

            onChange: function(selectedDates, dateStr) {

                @this.set('dateFilter', dateStr);
            }
        });

        window.addEventListener('clear-date-picker', () => {

            datePicker.clear();
        });
    </script>
@endpush

