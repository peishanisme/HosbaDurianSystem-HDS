<div>
    {{-- weather card --}}
    @php
        $rain = $weather['precipitation'] ?? 0;
        $temp = $weather['temperature_2m'] ?? 0;

        // Weather icon
        $icon = '☀️';
        $bgClass = 'weather-sunny';

        if ($rain > 0) {
            $icon = '🌧️';
            $bgClass = 'weather-rain';
        } elseif ($temp < 22) {
            $icon = '⛅';
            $bgClass = 'weather-cloudy';
        }
    @endphp

    <div class="card shadow-sm mb-10">
        <div class="card-body d-flex justify-content-between align-items-center {{ $bgClass }}"
            style=" border-radius: 8px;">
            <div>
                <h2 class="card-title mb-1">Current Weather</h2>
                <p class="text-white mb-5">Hosba Durian Farm, Kedah</p>

                <h1 class="fw-bold mb-0" style="font-size: 35px;">
                    {{ $weather['temperature_2m'] ?? '--' }}°C
                </h1>

                <small class="text-white">
                    Humidity: {{ $weather['relative_humidity_2m'] ?? '--' }}% |
                    Wind: {{ $weather['wind_speed_10m'] ?? '--' }} m/s |
                    Rain: {{ $weather['precipitation'] ?? '0' }} mm
                </small>
            </div>

            <div>
                @php
                    $rain = $weather['precipitation'] ?? 0;
                    $temp = $weather['temperature_2m'] ?? 0;
                    $icon = '☀️';

                    if ($rain > 0) {
                        $icon = '🌧️';
                    } elseif ($temp < 22) {
                        $icon = '⛅';
                    }
                @endphp

                <span style="font-size: 55px;">{{ $icon }}</span>
            </div>
        </div>
    </div>
    {{-- end weather card --}}

    <div class="row g-4 mb-10">
        <!-- Total Tree Chart Card -->
        <div class="col-md-6">
            <x-charts.dashboard.total-tree-chart :totalTreeData="$totalTreeData" />
        </div>

        <!-- Health Status of Trees Card -->
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <h3 class="card-title">Health Status of Trees</h3>
                </div>
                <x-dashboard-items.tree-health-status :treeHealthRecords="$treeHealthRecords" />
            </div>
        </div>
    </div>

    <div class="mb-10">
        <x-charts.dashboard.total-harvested-fruits-chart :totalHarvestedFruitsData="$totalHarvestedFruitsData" />
    </div>

    <div class="mb-10">
        <x-charts.dashboard.top-selling-species-chart :topSellingSpecies="$topSellingSpecies" />
    </div>

    <x-charts.dashboard.total-transaction-chart :totalTransactionData="$totalTransactionData" />
</div>

@push('styles')
    <style>
        .weather-sunny {
            background: linear-gradient(90deg, #f8d66d 0%, #fff7d1 100%);
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
