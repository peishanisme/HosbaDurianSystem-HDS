<div id="kt_app_content_container" class="container-fluid">
    <livewire:components.headers.harvest-event-header :harvestEvent="$harvestEvent" />

    {{-- <div class="card mt-5">
        <div class="card-body">
            <h5 class="mb-4 fw-bold">Harvest Details</h5>

            <form wire:submit.prevent="save">
                <div class="row g-3">
                    <!-- Tree Selection -->
                    <div class="col-md-3">
                        <label for="treeSelect" class="form-label fw-semibold">Tree</label>
                        <select id="treeSelect" wire:model="tree_id" class="form-select">
                            <option value="">Select Tree</option>
                            @foreach ($trees as $tree)
                                <option value="{{ $tree->id }}">{{ $tree->tree_tag }}</option>
                            @endforeach
                        </select>
                        @error('tree_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Harvested Date -->
                    <div class="col-md-3">
                        <label for="harvestedDate" class="form-label fw-semibold">Harvested Date</label>
                        <input type="date" id="harvestedDate" wire:model="harvested_date" class="form-control">
                        @error('harvested_date')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Grade -->
                    <div class="col-md-3">
                        <label for="gradeSelect" class="form-label fw-semibold">Grade</label>
                        <select id="gradeSelect" wire:model="grade" class="form-select">
                            <option value="">Select Grade</option>
                            <option value="A">Grade A</option>
                            <option value="B">Grade B</option>
                            <option value="C">Grade C</option>
                        </select>
                        @error('grade')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Weight -->
                    <div class="col-md-3">
                        <label for="weightInput" class="form-label fw-semibold">Weight (kg)</label>
                        <input type="number" step="0.01" id="weightInput" wire:model="weight" class="form-control"
                            placeholder="Enter weight">
                        @error('weight')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary">
                        <span wire:loading.remove>Save Harvest</span>
                        <span wire:loading>Saving...</span>
                    </button>
                </div>

                @if (session()->has('message'))
                    <div class="alert alert-success mt-3 mb-0">{{ session('message') }}</div>
                @endif
            </form>
        </div>
    </div> --}}

    <x-charts.harvest-events.top5-harvest-trees-chart :top5HarvestTreesData="$top5HarvestTreesData" />

    <x-charts.harvest-events.harvest-species-chart :harvestSpeciesData="$harvestSpeciesData" />

    <x-charts.harvest-events.fruit-quality-chart :fruitQualityData="$fruitQualityData" />

    <x-charts.harvest-events.durian-selling-chart :sellingStatusData="$sellingStatusData" />

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
