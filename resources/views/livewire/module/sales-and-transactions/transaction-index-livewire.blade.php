<div>
    {{-- <div class="d-flex flex-wrap flex-stack">
        <div class="d-flex flex-column flex-grow-1 pe-8">
            <div class="d-flex flex-wrap">

                <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                    <div class="d-flex align-items-center">
                        <div class="fs-2 fw-bold" data-kt-countup="true"
                            data-kt-countup-value="100">0
                        </div>
                    </div>
                    <div class="fw-semibold fs-6 text-gray-500">Remaining Quantity</div>
                </div>

                <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                    <div class="d-flex align-items-center">
                        <div class="fs-2 fw-bold" data-kt-countup="true"
                            data-kt-countup-value="100"
                            data-kt-countup-prefix="RM">0</div>
                    </div>
                    <div class="fw-semibold fs-6 text-gray-500">Price Per Unit</div>
                </div>

                <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                    <div class="d-flex align-items-center">
                        <div class="fs-2 fw-bold">2024-06-01</div>
                    </div>
                    <div class="fw-semibold fs-6 text-gray-500">Latest Purchase Date</div>
                </div>

            </div>
        </div>
    </div> --}}

    <livewire:tables.transaction-listing-table />

    <livewire:module.sales-and-transactions.transaction-details-modal-livewire />

    <livewire:components.generate-report-modal model="App\Models\Transaction" />
</div>
