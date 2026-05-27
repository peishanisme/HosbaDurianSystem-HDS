<div>
    <div class="card shadow-sm rounded">
        <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center">
            <div class="flex-grow-1 mt-4">
                <div class="d-flex flex-wrap flex-stack">
                    <div class="d-flex flex-column flex-grow-1 pe-8">
                        <div class="d-flex flex-wrap">
                            <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-8 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="fs-2 fw-bold">{{ $this->totalTransactions }} </div>
                                </div>
                                <div class="fw-semibold fs-6 text-gray-500">{{ __('messages.total_transactions') }}
                                </div>
                            </div>

                            <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-8 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="fs-2 fw-bold">{{ $this->totalWeight }} kg </div>
                                </div>
                                <div class="fw-semibold fs-6 text-gray-500">{{ __('messages.total_weight') }}</div>
                            </div>

                            <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-8 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="fs-2 fw-bold">RM {{ $this->totalAmount }} </div>
                                </div>
                                <div class="fw-semibold fs-6 text-gray-500">{{ __('messages.total_amount') }}</div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-wrap gap-3 mb-5">
                {{-- date filter --}}
                <livewire:components.date-filter />

                {{-- create transaction button --}}
                <button type="button" class="btn btn-primary btn-sm py-3" wire:click="$dispatch('reset-transaction')"
                    data-bs-toggle="modal" data-bs-target="#transactionModalLivewire">
                    {{ __('messages.create_transaction') }}
                </button>
            </div>

        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr class="text-muted small text-uppercase">
                            <th>{{ __('messages.date') }}</th>
                            <th>{{ __('messages.total_transactions') }}</th>
                            <th>{{ __('messages.total_amount') }}</th>
                            <th>{{ __('messages.total_weight') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($this->transactionByDate as $date => $group)
                            <tr wire:key="date-{{ $date }}">
                                <td class="align-middle py-4">
                                    <button wire:click="toggleExpand('{{ $date }}')"
                                        class="btn btn-link p-0 d-inline-flex align-items-center">
                                        <span class="me-2"
                                            style="display:inline-block; transition: transform .2s; {{ in_array($date, $expandedDates) ? 'transform: rotate(90deg);' : '' }}">
                                            ▶
                                        </span>
                                    </button>
                                    <span class="fw-bold">{{ $date }}</span>
                                </td>

                                <td class="py-4">
                                    {{ $group->count() }}
                                </td>

                                <td class="py-4">
                                    {{ number_format($group->sum('total_amount'), 2) }}
                                </td>

                                <td class="py-4">
                                    {{ number_format($group->sum('total_weight'), 2) }}
                                </td>
                            </tr>

                            @if (in_array($date, $expandedDates))
                                <tr class="table-active" wire:key="expand-{{ $date }}">
                                    <td colspan="4" class="p-0">
                                        <div class="expand-wrapper" wire:key="expand-{{ $date }}">

                                            <div class="expand-inner">

                                                <table class="table table-sm mb-0">
                                                    <thead class="table-light">
                                                        <tr class="small text-uppercase text-muted">
                                                            <th>Total Weight</th>
                                                            <th>Total Amount</th>
                                                            <th>Grade Breakdown</th>
                                                            <th>Created At</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>

                                                        @forelse ($group as $transaction)
                                                            <tr wire:key="transaction-{{ $transaction->id }}"
                                                                class="border-bottom">

                                                                <td>
                                                                    {{ number_format($transaction->total_weight, 2) }}
                                                                </td>

                                                                <td>
                                                                    {{ number_format($transaction->total_amount, 2) }}
                                                                </td>

                                                                <td>

                                                                    @if ($transaction->grade_breakdown)
                                                                        @foreach ($transaction->grade_breakdown as $grade => $details)
                                                                            <div class="mb-1">
                                                                                <strong>{{ $grade }}</strong> :
                                                                                {{ $details['total_weight'] ?? 0 }} KG
                                                                                /
                                                                                RM
                                                                                {{ number_format($details['total_amount'] ?? 0, 2) }}
                                                                            </div>
                                                                        @endforeach
                                                                    @else
                                                                        -
                                                                    @endif

                                                                </td>

                                                                <td>
                                                                    {{ $transaction->created_at->format('h:i a') }}
                                                                </td>

                                                                <td>
                                                                    <div class="d-flex gap-2 flex-wrap">
                                                                        <x-table-com-button label1="Edit"
                                                                            modal="transactionModalLivewire"
                                                                            dispatch1="edit-transaction"
                                                                            dataField="transaction" icon2="bi bi-trash3"
                                                                            dispatch2="delete-transaction"
                                                                            label2="Delete"
                                                                            data="{{ $transaction->id }}" />

                                                                    </div>
                                                                </td>
                                                            </tr>

                                                        @empty

                                                            <tr>
                                                                <td colspan="5" class="text-center text-muted">
                                                                    No transactions found
                                                                </td>
                                                            </tr>
                                                        @endforelse

                                                    </tbody>


                                                </table>

                                            </div>

                                        </div>

                                    </td>
                                </tr>
                            @endif

                        @empty

                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    {{ __('messages.no_transactions_found') }}
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>

            </div>
        </div>
    </div>

    {{-- <livewire:tables.transaction-listing-table /> --}}
    <livewire:module.sales-and-transactions.transaction-modal-livewire />

    <livewire:module.sales-and-transactions.transaction-details-modal-livewire />

    {{-- <livewire:components.generate-report-modal model="App\Models\Transaction" /> --}}

</div>
