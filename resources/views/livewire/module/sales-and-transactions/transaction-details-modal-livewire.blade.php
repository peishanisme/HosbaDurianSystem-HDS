<x-modal-component :id="$modalID" :title="$modalTitle">
    <div>
        @if ($transaction)
            @if ($transaction->is_cancelled)
                <span class="badge badge-light-danger">{{ __('messages.this_transaction_is_cancelled') }}</span>
            @endif

            <!-- Transaction Info -->
            <div class="mb-5 space-y-2">
                <div class="fs-5 my-3 text-gray-600">
                    <strong>{{ __('messages.transaction_ref_id') }}:</strong> {{ $transaction->reference_id ?? '-' }}
                </div>
                <div class="fs-5 my-3 text-gray-600">
                    <strong>{{ __('messages.date') }}:</strong> {{ \Carbon\Carbon::parse($transaction->date)->format('d M Y') }}
                </div>
                <div class="fs-5 my-3 text-gray-600">
                    <strong>{{ __('messages.buyer') }}:</strong> {{ $transaction->buyer->company_name ?? '-' }}
                </div>
                <div class="fs-5 my-3 text-gray-600">
                    <strong>{{ __('messages.buyer_ref_id') }}:</strong> {{ $transaction->buyer->reference_id ?? '-' }}
                </div>
                <div class="fs-5 my-3 text-gray-600">
                    <strong>{{ __('messages.payment_method') }}:</strong>
                    {{ $transaction->payment_method ? ucfirst(str_replace('_', ' ', $transaction->payment_method)) : '-' }}
                </div>
                <div class="fs-5 my-3 text-gray-600">
                    <strong>{{ __('messages.remark') }}:</strong> {{ $transaction->remark ?? '-' }}
                </div>
            </div>

            <!-- Fruits Summary Table -->
            <div class="overflow-x-auto">
                <table class="table table-striped table-row-bordered gy-3 gs-3 align-middle w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th>{{ __('messages.species') }}</th>
                            <th>{{ __('messages.grade') }}</th>
                            <th class="text-center">{{ __('messages.count') }}</th>
                            <th class="text-center">{{ __('messages.total_weight') }} (kg)</th>
                            <th class="text-center">{{ __('messages.price_per_kg') }} (RM)</th>
                            <th class="text-center">{{ __('messages.subtotal') }}</th>
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
                                <td colspan="6" class="text-center text-muted py-4">{{ __('messages.no_fruits_found_for_this_transaction') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if ($summary)
                        <tfoot class="fw-semibold">
                            @php
                                $calculatedSubtotal = isset($summary) ? collect($summary)->sum('subtotal') : 0;
                            @endphp
                            <tr>
                                <td colspan="5" class="text-end">{{ __('messages.subtotal_before_discount') }}:</td>
                                <td class="text-center">RM {{ number_format($calculatedSubtotal ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-end">{{ __('messages.discount_rm') }}:</td>
                                <td class="text-center">RM {{ number_format($transaction->discount ?? 0, 2) }}</td>
                            </tr>
                            <tr class="bg-light">
                                <td colspan="5" class="text-end">{{ __('messages.final_amount') }}:</td>
                                <td class="text-center text-success fw-bold fs-5">RM
                                    {{ number_format($transaction->total_price ?? 0, 2) }}</td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
                <div class="text-end">
                    @if ($blockchainStatus === 'confirmed' && $blockchainVerified)
                        <span class="badge badge-light-success">✔ {{ __('messages.verified_on_blockchain') }}</span>
                    @elseif ($blockchainStatus === 'confirmed' && !$blockchainVerified)
                        <span class="badge badge-light-danger">✖ {{ __('messages.data_tampered') }}</span>
                    @elseif ($blockchainStatus === 'canceled')
                        <span class="badge badge-light-warning text-dark">⚠ {{ __('messages.canceled_on_blockchain') }}</span>
                    @else
                        <span class="badge badge-light-secondary">⏳ {{ __('messages.not_yet_verified') }}</span>
                    @endif
                </div>

            </div>
        @else
            <div class="text-center py-10">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">{{ __('messages.loading') }}...</span>
                </div>
            </div>
        @endif
    </div>

    @slot('footer')
        <div class="d-flex justify-content-between align-items-center w-100">
            <!-- Left side -->
            @if ($transaction && !$transaction->is_cancelled)
                @can('delete-sale')
                    <div>
                        <x-button type="button" class="btn btn-light-danger" wire:click="cancelTransaction">
                            {{ __('messages.cancel_transaction') }}
                        </x-button>
                    </div>
                @endcan

                <!-- Right side -->
                <div class="d-flex gap-2">
                    <x-button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        {{ __('messages.close') }}
                    </x-button>

                    <x-button type="button" class="btn btn-primary" wire:click="printReceipt">
                        {{ __('messages.print_receipt') }}
                    </x-button>
                </div>
            @endif
        </div>
    @endslot

</x-modal-component>
