<div class="container-fluid">

    <div class="card shadow-sm mt-10">
        <div class="card-header">
            <h3 class="card-title">Create New Transaction</h3>
        </div>
        <div class="card-body">

            <div class="fv-row mb-8">
                <x-input-label for="buyer" class="required mb-2" :value="__('Buyer')" />
                <x-input-select id="buyer" placeholder="Select Buyer" wire:model="form.buyer_uuid"
                    :options="$buyerOptions" />
                <x-input-error :messages="$errors->get('form.buyer_uuid')" />
            </div>

            <div class="fv-row mb-8">
                <x-input-label for="date" class="mb-2 required" :value="__('Date')" />
                <x-input-text id="date" placeholder="Date" wire:model="form.date" type='date'/>
                <x-input-error :messages="$errors->get('form.date')" />
            </div>

            <div class="fv-row mb-8">
                //add single durian record
            </div>

            <div class="fv-row mb-8">
                <x-input-label for="price" class="mb-2 required" :value="__('Total Price(RM)')" />
                <x-input-text id="total_price" placeholder="Total Price" wire:model="form.total_price"></x-input-text>
                <x-input-error :messages="$errors->get('form.total_price')" />
            </div>

        </div>
        <div class="card-footer text-end">
            <a href="{{ route('sales.transaction.index') }}" class="btn btn-secondary me-5">Cancel</a>
            <x-button type="submit" class="btn btn-primary" wire:click="create">Create</x-button>
        </div>
    </div>
</div>
