<div class="card mb-5 mb-xl-10">
    <div class="card-body pt-9 pb-0">

        <div class="d-flex flex-wrap flex-sm-nowrap">
            <!--begin: Pic-->
            <div class="me-7 mb-4">
                <x-avatar :name="$buyer->company_name" size="80px" />
            </div>

            <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                    <div class="d-flex flex-column">
                        <div class="d-flex align-items-center my-2">
                            <span class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">{{ $buyer->company_name }}
                            </span>
                        </div>

                        <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                            <span class="d-flex align-items-center text-gray-500 text-hover-primary me-5 mb-2">
                                <i class="ki-duotone ki-profile-circle fs-4 me-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>{{ $buyer->reference_id }}</span>
                            <span class="d-flex align-items-center text-gray-500 text-hover-primary me-5 mb-2">
                                <i class="bi bi-telephone-fill me-1"></i>
                                {{ $buyer->contact_number }}</span>
                            @if ($buyer->email)
                                <span class="d-flex align-items-center text-gray-500 text-hover-primary mb-2">
                                    <i class="bi bi-envelope-fill me-1"></i>
                                    {{ $buyer->email }}</span>
                            @endif
                        </div>
                    </div>
                 
                    <div class="d-flex my-4">
                        <div class="me-3">
                            <x-table-button modal="buyerModalLivewire" dispatch="edit-buyer" dataField="buyer"
                                data="{{ $buyer->id }}" />
                            <livewire:module.sales-and-transactions.buyer-modal-livewire />
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-wrap flex-stack">
                    <div class="d-flex flex-column flex-grow-1 pe-8">
                        <div class="d-flex flex-wrap">

                            <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="fs-2 fw-bold" data-kt-countup="true" data-kt-countup-value="{{ $buyer->getTotalTransactionsAttribute() }}">0</div>
                                </div>
                                <div class="fw-semibold fs-6 text-gray-500">{{ __('messages.total_transactions') }}</div>
                            </div>

                            <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="fs-2 fw-bold" data-kt-countup="true" data-kt-countup-value="{{ $buyer->getTotalSpentAttribute() }}"
                                        data-kt-countup-prefix="RM">0</div>
                                </div>
                                <div class="fw-semibold fs-6 text-gray-500">{{ __('messages.total_amount') }}</div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
       
        <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold" wire:ignore>
            <x-show-navbar-navitem title="{{ __('messages.overview') }}" route="{{ route('sales.buyers.show', $buyer->id) }}"
                :active="request()->routeIs('sales.buyers.show')" />
            <x-show-navbar-navitem title="{{ __('messages.transactions') }}" route="{{ route('sales.buyers.transaction', $buyer->id) }}" :active="request()->routeIs('sales.buyers.transaction')"/>
        </ul>

    </div>
</div>
