<x-modal-component :id="$modalID" :title="$modalTitle">
    <div>
        @if ($transaction)
            <!-- Transaction Info -->
            <div class="mb-5 space-y-2">
                <div class="fs-5 my-3 text-gray-600">
                    <strong>Date:</strong> {{ \Carbon\Carbon::parse($transaction->date)->format('d M Y') }}
                </div>
                <div class="fs-5 my-3 text-gray-600">
                    <strong>Buyer:</strong> {{ $transaction->buyer->company_name ?? '-' }}
                </div>
                <div class="fs-5 my-3 text-gray-600">
                    <strong>Payment Method:</strong> {{ ucfirst($transaction->payment_method) ?? '-' }}
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
            </div>
        @else
            <div class="text-center py-10 text-gray-500">
                No transaction selected.
            </div>
        @endif
    </div>

    @slot('footer')
        <x-button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</x-button>
        {{-- <x-button type="button" class="btn btn-primary" onclick="window.open('{{ route('receipt.print', $transaction->uuid) }}', '_blank')">Print Receipt</x-button> --}}
    @endslot
</x-modal-component>
