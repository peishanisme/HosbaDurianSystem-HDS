<x-modal-component :id="$modalID" :title="$modalTitle">
    <div>
        @if ($transaction)
            @if ($transaction->is_cancelled)
                <span class="badge badge-light-danger">This Transaction is Cancelled</span>
            @endif

            <!-- Transaction Info -->
            <div class="mb-5 space-y-2">
                <div class="fs-5 my-3 text-gray-600">
                    <strong>Transaction Ref ID:</strong> {{ $transaction->reference_id ?? '-' }}
                </div>
                <div class="fs-5 my-3 text-gray-600">
                    <strong>Date:</strong> {{ \Carbon\Carbon::parse($transaction->date)->format('d M Y') }}
                </div>
                <div class="fs-5 my-3 text-gray-600">
                    <strong>Buyer:</strong> {{ $transaction->buyer->company_name ?? '-' }}
                </div>
                <div class="fs-5 my-3 text-gray-600">
                    <strong>Buyer Ref ID:</strong> {{ $transaction->buyer->reference_id ?? '-' }}
                </div>
                <div class="fs-5 my-3 text-gray-600">
                    <strong>Payment Method:</strong>
                    {{ $transaction->payment_method ? ucfirst(str_replace('_', ' ', $transaction->payment_method)) : '-' }}
                </div>
                <div class="fs-5 my-3 text-gray-600">
                    <strong>Remark:</strong> {{ $transaction->remark ?? '-' }}
                </div>
            </div>

            <!-- Fruits Summary Table -->
            <div class="overflow-x-auto">
                <table class="table table-striped table-row-bordered gy-3 gs-3 align-middle w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th>Species</th>
                            <th>Grade</th>
                            <th class="text-center">Count</th>
                            <th class="text-center">Total Weight (kg)</th>
                            <th class="text-center">Price / kg (RM)</th>
                            <th class="text-center">Subtotal (RM)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($summary as $item)
                            <tr>
                                <td>{{ $item['species'] }}</td>
                                <td>{{ $item['grade'] }}</td>
                                <td class="text-center">{{ $item['count'] }}</td>
                                <td class="text-center">{{ number_format($item['total_weight'], 2) }}</td>
                                <td class="text-center">{{ number_format($item['price_per_kg'], 2) }}</td>
                                <td class="text-center">{{ number_format($item['subtotal'], 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No fruits found for this
                                    transaction.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if ($summary)
                        <tfoot class="fw-semibold">
                            @php
                                $calculatedSubtotal = isset($summary) ? collect($summary)->sum('subtotal') : 0;
                            @endphp
                            <tr>
                                <td colspan="5" class="text-end">Subtotal (Before Discount):</td>
                                <td class="text-center">RM {{ number_format($calculatedSubtotal ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-end">Discount (RM):</td>
                                <td class="text-center">RM {{ number_format($transaction->discount ?? 0, 2) }}</td>
                            </tr>
                            <tr class="bg-light">
                                <td colspan="5" class="text-end">Final Amount:</td>
                                <td class="text-center text-success fw-bold fs-5">RM
                                    {{ number_format($transaction->total_price ?? 0, 2) }}</td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
                <div class="text-end">
                    @if ($blockchainStatus === 'confirmed' && $blockchainVerified)
                        <span class="badge badge-light-success">✔ Verified on Blockchain</span>
                    @elseif ($blockchainStatus === 'confirmed' && !$blockchainVerified)
                        <span class="badge badge-light-danger">✖ Data Tampered</span>
                    @elseif ($blockchainStatus === 'canceled')
                        <span class="badge badge-light-warning text-dark">⚠ Canceled on Blockchain</span>
                    @else
                        <span class="badge badge-light-secondary">⏳ Not Yet Verified</span>
                    @endif
                </div>

            </div>
        @else
            <div class="text-center py-10">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        @endif
    </div>

    @slot('footer')
        <div class="d-flex justify-content-between align-items-center w-100">
            <!-- Left side -->
            @if ($transaction && !$transaction->is_cancelled)
                <div>
                    <x-button type="button" class="btn btn-light-danger" wire:click="cancelTransaction">
                        Cancel Transaction
                    </x-button>
                </div>

                <!-- Right side -->
                <div class="d-flex gap-2">
                    <x-button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </x-button>

                    <x-button type="button" class="btn btn-primary" wire:click="printReceipt">
                        Print Receipt
                    </x-button>
                </div>
            @endif
        </div>
    @endslot

</x-modal-component>
