<div class="card mb-5 mb-xl-10">
    <div class="card-body pt-9 pb-0">

        <div class="d-flex flex-wrap flex-sm-nowrap">

            <div class="me-7 mb-4">
                {!! QrCode::size(120)->generate($treeUrl) !!}
            </div>

            <!--begin::Info-->
            <div class="flex-grow-1">
                <!--begin::Title-->
                <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                    <!--begin::User-->
                    <div class="d-flex flex-column">
                        <!--begin::Name-->
                        <div class="d-flex align-items-center mb-2 gap-4">
                            <span class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">{{ $tree->tree_tag }}
                            </span>

                            <div id="tree_label_drawer_toggle" class="tree-label" style="cursor: pointer;">
                                @foreach ($tree->labels as $label)
                                    <x-tree-label-badge :label="$label->name" :color="$label->color" class="ms-2" />
                                @endforeach
                            </div>

                            {{-- tree labels drawer --}}
                            <div id="kt_tree_label_drawer" wire:ignore.self class="bg-white d-flex flex-column"
                                data-kt-drawer="true" data-kt-drawer-activate="true"
                                data-kt-drawer-toggle="#tree_label_drawer_toggle"
                                data-kt-drawer-close="#kt_tree_label_drawer_close" data-kt-drawer-width="400px">

                                <div class="p-5 border-bottom d-flex justify-content-between align-items-center">
                                    <h4 class="mb-0">Tree Labels</h4>
                                    <button id="kt_tree_label_drawer_close" class="btn btn-sm btn-light">✕</button>
                                </div>

                                <div class="p-5 flex-grow-1 overflow-auto">

                                    @foreach ($labelOptions as $label)
                                        <div class="form-check mb-8">
                                            <input class="form-check-input" type="checkbox" value="{{ $label->id }}"
                                                wire:model="selectedLabels" id="label_{{ $label->id }}">

                                            <label class="form-check-label" for="label_{{ $label->id }}">
                                                <span class="badge"
                                                    style="background-color: {{ $label->color ?? '#ccc' }}; color: #fff; font-size: 14px;">
                                                    {{ $label->name }}
                                                </span>
                                            </label>
                                        </div>
                                    @endforeach

                                </div>

                                <div class="p-5 border-top d-flex justify-content-end gap-4">
                                    <button class="btn btn-light-danger w-100" data-kt-drawer-dismiss="true">
                                        Cancel
                                    </button>

                                    <button wire:click="saveLabels" class="btn btn-primary w-100">
                                        Save
                                    </button>
                                </div>

                            </div>
                            {{-- end of tree labels drawer --}}

                            @php
                                match ($tree->active_flowering_status) {
                                    'A' => 'badge-light-danger',
                                    'B' => 'badge-light-warning',
                                    'C' => 'badge-light-primary',
                                    'D' => 'badge-light-info',
                                    default => 'badge-light-secondary',
                                };
                            @endphp
                            <x-table-badge :label="$tree->active_flowering_status ?? 'X'" badge="{{ $tree->active_flowering_status }}"
                                class="ms-2" />

                        </div>

                        <!--begin::Info-->
                        <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                            <span class="d-flex align-items-center text-gray-500 text-hover-primary me-5 mb-2">
                                <i class="ki-duotone ki-tree fs-4 me-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>{{ $tree->species->name }}</span>

                            <span class="d-flex align-items-center text-gray-500 text-hover-primary me-5 mb-2">
                                <i class="ki-duotone ki-calendar">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>{{ $tree->planted ? $tree->planted_at->format('Y-m-d') : 'N/A' }}</span>
                        </div>
                        <!--end::Info-->
                    </div>
                    <!--end::User-->
                    <!--begin::Actions-->
                    <div class="d-flex my-4">
                        {{-- <a href="#" class="btn btn-sm btn-light-success me-2" data-bs-toggle="modal"
                            data-bs-target="#qrModal">
                            <i class="bi bi-tree"></i> <span
                                class="indicator-label">{{ __('messages.tree_qr_code') }}</span>
                        </a> --}}

                        {{-- tree qr modal --}}
                        <div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h5 class="modal-title" id="qrModalLabel">{{ __('messages.tree_qr_code') }}
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body text-center">

                                        <div id="qrCodeWrapper">
                                            {!! QrCode::size(300)->generate($treeUrl) !!}
                                        </div>

                                    </div>

                                    <div class="modal-footer d-flex justify-content-center gap-2">
                                        <button class="btn btn-primary"
                                            onclick="downloadQR( 'qrCodeWrapper'
                                            , 'tree-{{ $tree->tree_tag }}.png' )">{{ __('messages.download') }}</button>
                                        <button class="btn btn-secondary"
                                            onclick="printQR('Tree {{ $tree->tree_tag }}')">{{ __('messages.print') }}</button>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="me-3">
                            <button type="button" class="btn btn-sm btn-light-info d-flex align-items-center gap-2"
                                data-bs-toggle="modal" data-bs-target="#harvestQrCodeModalLivewire"
                                wire:click="$dispatch('load-qr-code', { tree: {{ $tree->id }} })">
                                <i class="bi-qr-code"></i>
                                <span class="indicator-label">{{ __('messages.qr_code') }}</span>
                            </button>
                        </div>

                        <div class="me-3">
                            <x-table-button modal="treeModalLivewire" dispatch="edit-tree" dataField="tree"
                                data="{{ $tree->id }}" />
                            <livewire:module.tree-management.tree-modal-livewire />
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-wrap flex-stack">
                    <div class="d-flex flex-column flex-grow-1 pe-8">
                        <div class="d-flex flex-wrap">
                            <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="fs-2 fw-bold">{{ $tree->area }} </div>
                                </div>
                                <div class="fw-semibold fs-6 text-gray-500">{{ __('messages.area') }}</div>
                            </div>

                            <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="fs-2 fw-bold">{{ $tree->terrace }} </div>
                                </div>
                                <div class="fw-semibold fs-6 text-gray-500">{{ __('messages.terrace') }}</div>
                            </div>

                            <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="fs-2 fw-bold">{{ $tree->water_valve }} </div>
                                </div>
                                <div class="fw-semibold fs-6 text-gray-500">{{ __('messages.water_valve') }}</div>
                            </div>

                            <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="fs-2 fw-bold">{{ $tree->getFloweringPeriod() }}</div>
                                </div>
                                <div class="fw-semibold fs-6 text-gray-500">{{ __('messages.flowering_period') }}
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--begin::Navs-->
        <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold" wire:ignore>
            <x-show-navbar-navitem title="{{ __('messages.overview') }}" :route="route('tree.show', $tree->id)" :active="request()->routeIs('tree.show')" />
            <x-show-navbar-navitem title="{{ __('messages.growth_logs') }}" :route="route('tree.growth-log', $tree->id)" :active="request()->routeIs('tree.growth-log')" />
            <x-show-navbar-navitem title="{{ __('messages.health_records') }}" :route="route('tree.health-record', $tree->id)" :active="request()->routeIs('tree.health-record')" />
            <x-show-navbar-navitem title="{{ __('messages.agrochemical_usages') }}" :route="route('tree.agrochemical-usage', $tree->id)"
                :active="request()->routeIs('tree.agrochemical-usage')" />
            <x-show-navbar-navitem title="{{ __('messages.harvests') }}" :route="route('tree.harvest-record', $tree->id)" :active="request()->routeIs('tree.harvest-record')" />

        </ul>
        <!--begin::Navs-->
    </div>
</div>

@push('scripts')
    <script>
        window.addEventListener('closeDrawer', () => {
            const drawerEl = document.querySelector("#kt_tree_label_drawer");

            let drawer = KTDrawer.getInstance(drawerEl);

            if (!drawer) {
                drawer = new KTDrawer(drawerEl);
            }

            drawer.hide();
        });
    </script>
@endpush
