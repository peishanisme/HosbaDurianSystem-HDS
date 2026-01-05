<x-modal-component :id="$modalID" :title="$modalTitle">

    <div class="card-body p-5">
        <div class="mb-10">
            <label class="form-label required">Date Range</label>
            <input class="form-control form-control-solid" placeholder="Pick date range" id="kt_daterangepicker_1" />

            <input type="hidden" wire:model="from" id="from-hidden" value="{{ $from }}">
            <input type="hidden" wire:model="to" id="to-hidden" value="{{ $to }}">

            <x-input-error :messages="$errors->get('from')" />
        </div>

        <div class="fv-row mb-10">
            <x-input-label for="format" class="required mb-4" :value="__('Format')" />
            <div class="d-flex w-full gap-5">
                <x-input-radio id="format" name="format" value="pdf" model="format" label="PDF" />
                <x-input-radio id="format" name="format" value="xlsx" model="format" label="Excel" />
            </div>
            <x-input-error :messages="$errors->get('format')" />
        </div>
    </div>

    @slot('footer')
        <x-button class="btn btn-primary" wire:click="generateReport">Generate Report</x-button>
    @endslot
</x-modal-component>


@push('scripts')
    <script>
        $("#kt_daterangepicker_1").daterangepicker({
            autoUpdateInput: false, // 
        }, function(start, end) {
            $('#kt_daterangepicker_1').val(
                start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD')
            );

            @this.set('from', start.format('YYYY-MM-DD'));
            @this.set('to', end.format('YYYY-MM-DD'));
        });
    </script>
@endpush
