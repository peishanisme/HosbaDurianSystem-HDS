<div class="card mb-5 mb-xl-10">
    <div class="card-body pt-9 pb-0">

        <div class="d-flex flex-wrap flex-sm-nowrap">
            <!--begin: Pic-->
            <div class="me-7 mb-4">
                <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                    <img class="object-fit-cover border"
                        src="{{ $tree->thumbnail ? secure_asset('storage/' . $tree->thumbnail) : secure_asset('assets/media/placeholder/placeholder.svg') }}"
                        alt="image" />
                </div>
            </div>

            <!--begin::Info-->
            <div class="flex-grow-1">
                <!--begin::Title-->
                <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                    <!--begin::User-->
                    <div class="d-flex flex-column">
                        <!--begin::Name-->
                        <div class="d-flex align-items-center mb-2">
                            <span class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">{{ $tree->tree_tag }}
                            </span>
                        </div>

                        <!--begin::Info-->
                        <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                            <span class="d-flex align-items-center text-gray-500 text-hover-primary me-5 mb-2">
                                <i class="ki-duotone ki-profile-circle fs-4 me-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>{{ $tree->uuid }}</span>
                            <span class="d-flex align-items-center text-gray-500 text-hover-primary me-5 mb-2">
                                <i class="ki-duotone ki-tree fs-4 me-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>{{ $tree->species->name }}</span>
                            <span class="d-flex align-items-center text-gray-500 text-hover-primary mb-2">
                                <i class="ki-duotone ki-time fs-4 me-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>{{ $tree->planted_at }}</span>
                        </div>
                        <!--end::Info-->
                    </div>
                    <!--end::User-->
                    <!--begin::Actions-->
                    <div class="d-flex my-4">
                        <a href="#" class="btn btn-sm btn-light me-2" data-bs-toggle="modal"
                            data-bs-target="#qrModal">
                            <i class="ki-duotone ki-check fs-3 d-none"></i>
                            <span class="indicator-label">Show QR Code</span>
                        </a>

                        {{-- tree qr modal --}}
                        <div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h5 class="modal-title" id="qrModalLabel">Tree QR Code</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body text-center">

                                        {!! QrCode::size(300)->generate($tree->uuid ?? 'No UUID') !!}

                                        
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="me-3">
                            <x-table-button modal="treeModalLivewire" dispatch="edit-tree" dataField="tree"
                                data="{{ $tree->id }}" />
                            <livewire:module.tree-management.tree-modal-livewire />
                        </div>

                        <!--begin::Menu-->
                        <div class="me-0">
                            <button class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary"
                                data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                <i class="ki-solid ki-dots-horizontal fs-2x"></i>
                            </button>
                            <!--begin::Menu 3-->
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px py-3"
                                data-kt-menu="true">
                                <!--begin::Heading-->
                                <div class="menu-item px-3">
                                    <div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">Payments
                                    </div>
                                </div>
                                <!--end::Heading-->
                                <!--begin::Menu item-->
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3">Create Invoice</a>
                                </div>
                                <!--end::Menu item-->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-wrap flex-stack">
                    <div class="d-flex flex-column flex-grow-1 pe-8">
                        <div class="d-flex flex-wrap">

                            <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="fs-2 fw-bold">{{ $tree->latestGrowthLog->height }} m</div>
                                </div>
                                <div class="fw-semibold fs-6 text-gray-500">Height</div>
                            </div>

                            <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="fs-2 fw-bold">{{ $tree->latestGrowthLog->diameter }} m</div>
                                </div>
                                <div class="fw-semibold fs-6 text-gray-500">Diameter</div>
                            </div>

                            <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="fs-2 fw-bold">{{ $tree->flowering_period }}</div>
                                </div>
                                <div class="fw-semibold fs-6 text-gray-500">Flowering Period</div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Details-->
        <!--begin::Navs-->
        <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">
            <x-show-navbar-navitem title="Overview" route="tree.trees.show" />
            <x-show-navbar-navitem title="Growth Logs" />
            <x-show-navbar-navitem title="Status History" />
            <x-show-navbar-navitem title="Health Records" />
            <x-show-navbar-navitem title="Fertilization" />
            <x-show-navbar-navitem title="Yield History" />
            <x-show-navbar-navitem title="Media" />
        </ul>
        <!--begin::Navs-->
    </div>
</div>
