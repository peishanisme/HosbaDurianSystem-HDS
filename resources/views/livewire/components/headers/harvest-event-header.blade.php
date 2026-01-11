<div class="card mb-5 mb-xl-10">
    <div class="card-body pt-9 pb-0">

        <div class="d-flex flex-wrap flex-sm-nowrap">

            <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                    <div class="d-flex flex-column">
                        <div class="d-flex align-items-center mb-2">
                            <span
                                class="text-gray-900 text-hover-primary fs-2 fw-bold me-7">{{ $harvestEvent->event_name }}
                            </span>
                            {{-- <span class="badge badge-light-primary fs-7 fw-semibold ms-2">
                                {{ $agrochemical->type->label() }}
                            </span> --}}
                        </div>

                        <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                            <span class="d-flex align-items-center text-gray-500 text-hover-primary me-5 mb-2">
                                <i class="ki-duotone ki-profile-circle fs-4 me-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>{{ $harvestEvent->uuid }}</span>
                        </div>
                    </div>

                    <div class="d-flex my-4">
                        <button class="btn btn-sm btn-light me-2" {{-- data-bs-toggle="modal"
                            data-bs-target="#agrochemicalStockMovementModalLivewire" --}}
                            wire:click="$dispatch({{ json_encode($harvestEvent->end_date ? 'reopen-event' : 'close-event') }}, { harvestEvent: {{ $harvestEvent->id }} })">
                            <span
                                class="indicator-label">{{ $harvestEvent->end_date ? 'Reopen Event' : 'Close Event' }}</span>
                        </button>

                        <div class="me-3">
                            <livewire:components.modal-button  :dispatch="'reset-generator'" :target="'generateReportModalLivewire'"
                                :label="'Generate Report'" />
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-wrap flex-stack">
                    <div class="d-flex flex-column flex-grow-1 pe-8">
                        <div class="d-flex flex-wrap">

                            <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="fs-2 fw-bold">{{ $harvestEvent->start_date }}</div>
                                </div>
                                <div class="fw-semibold fs-6 text-gray-500">Start Date</div>
                            </div>

                            <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="fs-2 fw-bold">{{ $harvestEvent->end_date ?? '-' }}</div>
                                </div>
                                <div class="fw-semibold fs-6 text-gray-500">End Date</div>
                            </div>

                            <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="fs-2 fw-bold">
                                        @if ($harvestEvent->end_date)
                                            {{ \Carbon\Carbon::parse($harvestEvent->start_date)->diffInDays(\Carbon\Carbon::parse($harvestEvent->end_date)) }}
                                            days
                                        @else
                                            -
                                        @endif
                                    </div>
                                </div>
                                <div class="fw-semibold fs-6 text-gray-500">Duration</div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold" wire:ignore>
            <x-show-navbar-navitem title="Overview" route="{{ route('harvest.show', $harvestEvent->id) }}"
                :active="request()->routeIs('harvest.show')" />
            <x-show-navbar-navitem title="Harvest Summary"
                route="{{ route('harvest.harvest-summary', $harvestEvent->id) }}" :active="request()->routeIs('harvest.harvest-summary')" />
        </ul>
    </div>
</div>
