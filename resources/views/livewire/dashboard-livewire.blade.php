<div>
    <x-dashboard-items.weather-card :weather="$weather" />

    <div class="row g-5 mb-15">
        <!-- Total Tree Chart Card -->
        <div class="col-md-6">
            <x-charts.dashboard.total-tree-chart :totalTreeData="$totalTreeData" />
        </div>

        <!-- Health Status of Trees Card -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <h3 class="card-title">{{ __('messages.health_status_of_trees') }}</h3>
                </div>
                <x-dashboard-items.tree-health-status :treeHealthRecords="$treeHealthRecords" />
            </div>
        </div>
    </div>

    <div class="row g-5 mb-15">

        <div class="col-md-6">
            <div>
                <x-charts.dashboard.total-harvested-fruits-chart :totalHarvestedFruitsData="$totalHarvestedFruitsData" />
            </div>
        </div>

        <div class="col-md-6">
            <div>
                <x-charts.dashboard.top-selling-species-chart :topSellingSpecies="$topSellingSpecies" />
            </div>
        </div>
    </div>

    <x-charts.dashboard.total-transaction-chart :totalTransactionData="$totalTransactionData" />
</div>

@push('styles')
    <style>
        .weather-sunny {
            background: linear-gradient(90deg, #e6b874 0%, #fff7d1 100%);
            color: #ffffff;
        }

        .weather-cloudy {
            background: linear-gradient(90deg, #a1b5c8 0%, #e1e8ef 100%);
            color: #ffffff;
        }

        .weather-rain {
            background: linear-gradient(90deg, #6aa5d8 0%, #d3e4f5 100%);
            color: #ffffff;
        }
    </style>
@endpush
