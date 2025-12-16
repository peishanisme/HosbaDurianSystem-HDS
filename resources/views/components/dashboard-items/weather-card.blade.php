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
            <h2 class="card-title mb-1">{{ __('messages.current_weather') }}</h2>
            <p class="text-white mb-5">Hosba Durian Farm, Kedah</p>

            <h1 class="fw-bold mb-0" style="font-size: 35px;">
                {{ $weather['temperature_2m'] ?? '--' }}°C
            </h1>

            <small class="text-white">
                {{ __('messages.humidity') }}: {{ $weather['relative_humidity_2m'] ?? '--' }}% |
                {{ __('messages.wind_speed') }}: {{ $weather['wind_speed_10m'] ?? '--' }} m/s |
                {{ __('messages.rain') }}: {{ $weather['precipitation'] ?? '0' }} mm
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
