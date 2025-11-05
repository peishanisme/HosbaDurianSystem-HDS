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
                <x-input-text id="date" placeholder="Date" wire:model="form.date" type='date' />
                <x-input-error :messages="$errors->get('form.date')" />
            </div>

            <div class="fv-row mb-8">
                <div>
                    <h2 class="text-xl font-bold mb-4">Durian Sales Scanner</h2>

                    {{-- QR Scanner Section --}}
                    <div class="mb-6">
                        <button wire:click="startScanner" class="btn btn-primary">Start QR Scanner</button>

                        <div id="reader" class="mt-4"></div>
                    </div>

                    {{-- Table for Scanned Fruits --}}
                    @if (count($scannedFruits) > 0)
                        <table class="table-auto w-full mt-6 border">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th>Fruit Tag</th>
                                    <th>Species</th>
                                    <th>Grade</th>
                                    <th>Weight (kg)</th>
                                    <th>Price/kg (RM)</th>
                                    <th>Total (RM)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($scannedFruits as $index => $fruit)
                                    <tr>
                                        <td>{{ $fruit['tag'] }}</td>
                                        <td>{{ $fruit['species'] }}</td>
                                        <td>{{ $fruit['grade'] }}</td>
                                        <td>{{ $fruit['weight'] }}</td>
                                        <td>
                                            <input type="number"
                                                wire:model="scannedFruits.{{ $index }}.price_per_kg"
                                                class="border rounded p-1 w-24 text-right" step="0.1" />
                                        </td>
                                        <td>{{ number_format($fruit['weight'] * ($fruit['price_per_kg'] ?? 0), 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="text-right mt-4">
                            <p class="font-semibold">Total Amount: RM {{ number_format($this->calculateTotal(), 2) }}
                            </p>
                            <button wire:click="confirmTransaction" class="btn btn-success mt-2">Confirm Sale</button>
                        </div>
                    @endif
                </div>

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

@push('scripts')
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            let html5QrCode = null;

            Livewire.on('startScanner', () => {
                const readerDiv = document.getElementById('reader');
                if (!readerDiv) {
                    console.error("QR reader div not found.");
                    return;
                }

                if (!html5QrCode) {
                    html5QrCode = new Html5Qrcode("reader");
                }

                const config = {
                    fps: 10,
                    qrbox: 250
                };
                html5QrCode.start({
                        facingMode: "environment"
                    },
                    config,
                    qrCodeMessage => Livewire.dispatch('qrScanned', {
                        code: qrCodeMessage
                    })
                ).catch(err => console.error("Unable to start scanning:", err));
            });
        });
    </script>
@endpush
