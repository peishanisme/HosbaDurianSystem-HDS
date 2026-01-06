<div id="kt_app_content_container" class="container-fluid">
    <livewire:components.headers.buyer-header-livewire :buyer="$buyer" />

    <div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
        <div class="card-header cursor-pointer">
            <div class="card-title m-0">
                <h3 class="fw-bold m-0">Buyer Details</h3>
            </div>
        </div>

        <div class="card-body p-9">
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Company Name</label>
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ $buyer->company_name }}</span>
                </div>
            </div>

            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Contact Name</label>

                <div class="col-lg-8 fv-row">
                    <span class="fw-semibold text-gray-800 fs-6">{{ $buyer->contact_name ?? '-' }}</span>
                </div>
            </div>

            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Contact Number</label>

                <div class="col-lg-8 fv-row">
                    <span class="fw-semibold text-gray-800 fs-6">{{ $buyer->contact_number }}</span>
                </div>
            </div>

            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Email</label>

                <div class="col-lg-8 fv-row">
                    <span class="fw-semibold text-gray-800 fs-6">{{ $buyer->email ?? '-' }}</span>
                </div>
            </div>

            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Address</label>

                <div class="col-lg-8 fv-row">
                    <span class="fw-semibold text-gray-800 fs-6">{{ $buyer->address ?? '-' }}</span>
                </div>
            </div>

            {{-- seperator line --}}
            <div class="separator mb-7"></div>
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Total Transactions</label>

                <div class="col-lg-8 fv-row">
                    <span class="fw-semibold text-gray-800 fs-6">{{ $buyer->getTotalTransactionsAttribute() ?? '-'}}</span>
                </div>
            </div>

            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Total Quantity Purchased</label>

                <div class="col-lg-8 fv-row">
                    <span class="fw-semibold text-gray-800 fs-6">{{ $buyer->getQuantityPurchasedAttribute() ?? '-' }} piece(s)</span>
                </div>
            </div>

            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Total Weight Purchased</label>

                <div class="col-lg-8 fv-row">
                    <span class="fw-semibold text-gray-800 fs-6">{{ $buyer->getTotalWeightPurchasedAttribute() ?? '-' }}kg</span>
                </div>
            </div>

            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Total Revenue from Buyer</label>

                <div class="col-lg-8 fv-row">
                    <span class="fw-semibold text-gray-800 fs-6">RM{{ number_format($buyer->getTotalSpentAttribute(), 2) ?? '-' }}</span>
                </div>
            </div>


        </div>
    </div>
</div>
