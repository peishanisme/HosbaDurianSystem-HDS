<div class="card mb-5 mb-xl-10">
    <div class="card-body pt-9 pb-0">

        <div class="d-flex flex-wrap flex-sm-nowrap">
            <!--begin: Pic-->
            <div class="me-7 mb-4">
                <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                    <img class="object-fit-cover border"
                        src="{{ $agrochemical->thumbnail ? app(\App\Services\MediaService::class)->get($agrochemical->thumbnail) : secure_asset('assets/media/placeholder/placeholder.svg') }}"
                        alt="image" />

                </div>
            </div>

            <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                    <div class="d-flex flex-column">
                        <div class="d-flex align-items-center mb-2">
                            <span class="text-gray-900 text-hover-primary fs-2 fw-bold me-7">{{ $agrochemical->name }}
                            </span>
                            <span class="badge badge-light-primary fs-7 fw-semibold ms-2">
                                {{ $agrochemical->type->label() }}
                            </span>
                        </div>

                        <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                            <span class="d-flex align-items-center text-gray-500 text-hover-primary me-5 mb-2">
                                <i class="ki-duotone ki-profile-circle fs-4 me-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>{{ $agrochemical->uuid }}</span>
                        </div>
                    </div>

                    <div class="d-flex my-4">
                        <button class="btn btn-sm btn-light me-2" data-bs-toggle="modal"
                            data-bs-target="#agrochemicalStockMovementModalLivewire"
                            wire:click="$dispatch('reset-stock', { agrochemical: {{ $agrochemical->id }} })">
                            <span class="indicator-label">Update Stock</span>
                        </button>

                        <div class="me-3">
                            <x-table-button modal="agrochemicalModalLivewire" dispatch="edit-agrochemical"
                                dataField="agrochemical" data="{{ $agrochemical->id }}" />
                            <livewire:module.agrochemical-management.agrochemical-modal-livewire />
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-wrap flex-stack">
                    <div class="d-flex flex-column flex-grow-1 pe-8">
                        <div class="d-flex flex-wrap">

                            <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="fs-2 fw-bold">{{ $agrochemical->getRemainingStock() }}
                                    </div>
                                </div>
                                <div class="fw-semibold fs-6 text-gray-500">Remaining Quantity</div>
                            </div>

                            <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="fs-2 fw-bold">{{ number_format($agrochemical->price, 2, '.', '') }}
                                    </div>
                                </div>
                                <div class="fw-semibold fs-6 text-gray-500">Price Per Unit</div>
                            </div>

                            <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="fs-2 fw-bold">{{ $agrochemical->getLatestPurchaseDate() }}</div>
                                </div>
                                <div class="fw-semibold fs-6 text-gray-500">Latest Purchase Date</div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">
            <x-show-navbar-navitem title="Overview" route="{{ route('agrochemical.show', $agrochemical->id) }}"
                :active="request()->routeIs('agrochemical.show')" />
            <x-show-navbar-navitem title="Purchase History"
                route="{{ route('agrochemical.purchase-history', $agrochemical->id) }}" :active="request()->routeIs('agrochemical.purchase-history')" />
            <x-show-navbar-navitem title="Application Records"
                route="{{ route('agrochemical.application-record', $agrochemical->id) }}" :active="request()->routeIs('agrochemical.application-record')" />
        </ul>

    </div>
</div>
