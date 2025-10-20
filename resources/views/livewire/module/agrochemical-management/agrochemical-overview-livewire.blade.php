<div class="container-fluid">
    <livewire:components.agrochemical-details-header :agrochemical="$agrochemical" />
    <livewire:module.agrochemical-management.agrochemical-update-stock-modal-livewire :agrochemical="$agrochemical" />

    <div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
        <div class="card-header cursor-pointer">
            <div class="card-title m-0">
                <h3 class="fw-bold m-0">Agrochemical Inventory Details</h3>
            </div>
        </div>

        <div class="card-body p-9">
            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Agrochemical Name</label>
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ $agrochemical->name }}</span>
                </div>
            </div>

            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Type</label>

                <div class="col-lg-8 fv-row">
                    <span class="fw-semibold text-gray-800 fs-6">{{ $agrochemical->type->label() }}</span>
                </div>
            </div>

            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Quantity Per Unit (kg/litre)</label>

                <div class="col-lg-8 fv-row">
                    <span
                        class="fw-semibold text-gray-800 fs-6">{{ number_format($agrochemical->quantity_per_unit, 3) }}</span>
                </div>
            </div>

            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Price Per Unit(RM)</label>

                <div class="col-lg-8 fv-row">
                    <span class="fw-semibold text-gray-800 fs-6">RM{{ number_format($agrochemical->price, 2) }}</span>
                </div>
            </div>

            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Remaining Quantity (kg/litre)</label>

                <div class="col-lg-8 fv-row">
                    <span class="fw-semibold text-gray-800 fs-6">12.5</span>
                </div>
            </div>

            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Latest Purchase Date</label>

                <div class="col-lg-8 fv-row">
                    <span class="fw-semibold text-gray-800 fs-6">{{ $agrochemical->getLatestPurchaseDate() }}</span>
                </div>
            </div>

            <div class="row mb-7">
                <label class="col-lg-4 fw-semibold text-muted">Description</label>

                <div class="col-lg-8 fv-row">
                    <span class="fw-semibold text-gray-800 fs-6">{{ $agrochemical->description ?? '-' }}</span>
                </div>
            </div>

        </div>
    </div>
</div>