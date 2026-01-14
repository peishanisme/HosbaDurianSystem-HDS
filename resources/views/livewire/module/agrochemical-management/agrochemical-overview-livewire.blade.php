<div id="kt_app_content_container" class="container-fluid">
    <livewire:components.headers.agrochemical-details-header :agrochemical="$agrochemical" />
    <livewire:module.agrochemical-management.agrochemical-update-stock-modal-livewire :agrochemical="$agrochemical" />

    <div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
        <div class="card-header cursor-pointer">
            <div class="card-title m-0">
                <h3 class="fw-bold m-0">{{ __('messages.agrochemical_details') }}</h3>
            </div>
        </div>

        <div class="card-body p-9">
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">{{ __('messages.agrochemical_name') }}</label>
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ $agrochemical->name }}</span>
                </div>
            </div>

            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">{{ __('messages.type') }}</label>

                <div class="col-lg-8 fv-row">
                    <span class="fw-semibold text-gray-800 fs-6">{{ $agrochemical->type->label() }}</span>
                </div>
            </div>

            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">{{ __('messages.quantity_per_unit') }}</label>

                <div class="col-lg-8 fv-row">
                    <span
                        class="fw-semibold text-gray-800 fs-6">{{ number_format($agrochemical->quantity_per_unit, 3) }}</span>
                </div>
            </div>

            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">{{ __('messages.price_per_unit') }}</label>

                <div class="col-lg-8 fv-row">
                    <span class="fw-semibold text-gray-800 fs-6">RM{{ number_format($agrochemical->price, 2) }}</span>
                </div>
            </div>

            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">{{ __('messages.remaining_quantity') }}</label>

                <div class="col-lg-8 fv-row">
                    <span class="fw-semibold text-gray-800 fs-6">{{ $agrochemical->getRemainingStock() }}</span>
                </div>
            </div>

            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">{{ __('messages.latest_purchase_date') }}</label>

                <div class="col-lg-8 fv-row">
                    <span class="fw-semibold text-gray-800 fs-6">{{ $agrochemical->getLatestPurchaseDate() }}</span>
                </div>
            </div>

            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">{{ __('messages.description') }}</label>

                <div class="col-lg-8 fv-row">
                    <span class="fw-semibold text-gray-800 fs-6">{{ $agrochemical->description ?? '-' }}</span>
                </div>
            </div>

        </div>
    </div>
</div>