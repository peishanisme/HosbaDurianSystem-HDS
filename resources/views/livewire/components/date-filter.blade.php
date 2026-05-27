<div class="mb-0">

    <div class="input-group border border-gray-300 rounded">

        <span class="input-group-text">
            <i class="ki-duotone ki-calendar fs-3">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </span>

        <input
            wire:model.live="dateFilter"
            class="form-control form-control-solid"
            placeholder="Pick a date range"
            id="kt_datepicker_7"
        />

        @if ($this->showClearButton)
            <button
                type="button"
                class="btn btn-light"
                wire:click="clearDateFilter"
            >
                Clear
            </button>
        @endif

    </div>

</div>

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