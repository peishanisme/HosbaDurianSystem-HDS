<div class="container-fluid">

    <div class="card shadow-sm mt-10">
        <div class="card-header">
            <h3 class="card-title">Create New Transaction</h3>
        </div>

        <div class="card-body" wire:ignore>
            <!--begin::Stepper-->
            <div class="stepper stepper-pills" id="kt_stepper_example_clickable">
                <x-stepper :label1="'Buyer details'" :label2="'Scan fruit'" :label3="'Transaction details'" :label4="'Review'" />

                <!--begin::Form-->
                <form class="form px-lg-20 mx-auto" novalidate="novalidate" id="kt_stepper_example_basic_form">
                    <!--begin::Group-->
                    <div class="mb-5">
                        <!--begin::Step 1-->
                        <div class="flex-column current" data-kt-stepper-element="content">
                            <!--buyer details-->
                            <div class="fv-row mb-10">
                                <div class="fv-row mb-10">
                                    <x-input-label for="buyer" class="required mb-2" :value="__('Buyer')" />
                                    <x-input-select id="buyer" placeholder="Select Buyer"
                                        wire:model="form.buyer_uuid" :options="$buyerOptions" />
                                    <x-input-error :messages="$errors->get('form.buyer_uuid')" />
                                </div>

                                <div class="fv-row mb-10">
                                    <x-input-label for="date" class="mb-2 required" :value="__('Date')" />
                                    <x-input-text id="date" placeholder="Date" wire:model="form.date"
                                        type='date' />
                                    <x-input-error :messages="$errors->get('form.date')" />
                                </div>

                                <div class="fv-row mb-10">
                                    <x-input-label for="remarks" class="mb-2" :value="__('Remarks')" />
                                    <x-input-textarea id="remarks" placeholder="Remarks"
                                        wire:model="form.remarks"></x-input-textarea>
                                    <x-input-error :messages="$errors->get('form.remarks')" />
                                </div>
                            </div>

                        </div>

                        <!--scan fruit-->
                        <div class="flex-column" data-kt-stepper-element="content">
                            <div class="row g-3">
                                <div class="col-12 col-lg-4">
                                    <div class="h-100">
                                        <div>
                                            <h5 class="card-title mb-0">Scan Fruit QR Code</h5>
                                        </div>

                                        <div wire:ignore>
                                            {{-- qr scanner --}}
                                            <small class="text-muted">Scan <strong>Fruit QR code</strong> Here</small>
                                            <div id="reader" class="mb-4" style="width:100%;max-width:600px;">
                                            </div>

                                            {{-- 
                                            <span class="text-xs text-muted">Or Search Here Using <strong>Member Code</strong> or <strong>Phone Number</strong></span>
                                            <livewire:components.search-dropdown />
                                            <x-input-error error="form.user" />
                                            <x-button class="btn btn-primary mt-1" wire:click.prevent="create">Check In User</x-button>
                                            --}}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-lg-8">
                                    <livewire:components.transaction-scanned-fruit-table
                                        :scannedFruits="$scannedFruits" wire:key="transaction-scanned-fruit-table" />
                                </div>
                            </div>

                        </div>
                        <!--begin::Step 1-->

                        <!--begin::Step 1-->
                        <div class="flex-column" data-kt-stepper-element="content">
                            <!--begin::Input group-->
                            <div class="fv-row mb-10">
                                <!--begin::Label-->
                                <label class="form-label d-flex align-items-center">
                                    <span class="required">Input 1</span>
                                    <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                        title="Example tooltip"></i>
                                </label>
                                <!--end::Label-->

                                <!--begin::Input-->
                                <input type="text" class="form-control form-control-solid" name="input1"
                                    placeholder="" value="" />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="fv-row mb-10">
                                <!--begin::Label-->
                                <label class="form-label">
                                    Input 2
                                </label>
                                <!--end::Label-->

                                <!--begin::Input-->
                                <input type="text" class="form-control form-control-solid" name="input2"
                                    placeholder="" value="" />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                        </div>
                        <!--begin::Step 1-->

                        <!--begin::Step 1-->
                        <div class="flex-column" data-kt-stepper-element="content">
                            <!--begin::Input group-->
                            <div class="fv-row mb-10">
                                <!--begin::Label-->
                                <label class="form-label d-flex align-items-center">
                                    <span class="required">Input 1</span>
                                    <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                                        title="Example tooltip"></i>
                                </label>
                                <!--end::Label-->

                                <!--begin::Input-->
                                <input type="text" class="form-control form-control-solid" name="input1"
                                    placeholder="" value="" />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="fv-row mb-10">
                                <!--begin::Label-->
                                <label class="form-label">
                                    Input 2
                                </label>
                                <!--end::Label-->

                                <!--begin::Input-->
                                <input type="text" class="form-control form-control-solid" name="input2"
                                    placeholder="" value="" />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="fv-row mb-10">
                                <!--begin::Label-->
                                <label class="form-label">
                                    Input 3
                                </label>
                                <!--end::Label-->

                                <!--begin::Input-->
                                <input type="text" class="form-control form-control-solid" name="input3"
                                    placeholder="" value="" />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                        </div>
                        <!--begin::Step 1-->
                    </div>
                    <!--end::Group-->

                    <!--begin::Actions-->
                    <div class="d-flex flex-stack">
                        <!--begin::Wrapper-->
                        <div class="me-2">
                            <button type="button" class="btn btn-light btn-active-light-primary"
                                data-kt-stepper-action="previous">
                                Back
                            </button>
                        </div>
                        <!--end::Wrapper-->

                        <!--begin::Wrapper-->
                        <div>
                            <button type="button" class="btn btn-primary" data-kt-stepper-action="submit">
                                <span class="indicator-label">
                                    Submit
                                </span>
                                <span class="indicator-progress">
                                    Please wait... <span
                                        class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>

                            <button type="button" class="btn btn-primary" data-kt-stepper-action="next">
                                Continue
                            </button>
                        </div>
                        <!--end::Wrapper-->
                    </div>
                    <!--end::Actions-->
                </form>
                <!--end::Form-->
            </div>
            <!--end::Stepper-->

            {{-- <div class="fv-row mb-8">
                <div>
                    <x-input-label class="text-xl font-bold mb-4">Durian Sales Scanner</x-input-label>

                    <div class="card-body" wire:ignore>
                        <span class="text-sm text-gray-600">Scan <strong> QR code</strong> Here</span>
                        <div id="reader" width="400px" class="mb-4"></div>
                    </div>

                    @if (count($scannedFruits) > 0)
                        <table class="table-auto w-full mt-6 border">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th>Fruit Tag</th>
                                    <th>Species</th>
                                    <th>Grade</th>
                                    <th>Weight (kg)</th>
                                    <th>Price/kg (RM)</th>
                                    <th>Total (RM)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($scannedFruits as $index => $fruit)
                                    <tr>
                                        <td>{{ $fruit['tag'] }}</td>
                                        <td>{{ $fruit['species'] }}</td>
                                        <td>{{ $fruit['grade'] }}</td>
                                        <td>{{ $fruit['weight'] }}</td>
                                        <td>
                                            <input type="number"
                                                wire:model="scannedFruits.{{ $index }}.price_per_kg"
                                                class="border rounded p-1 w-24 text-right" step="0.1" />
                                        </td>
                                        <td>{{ number_format($fruit['weight'] * ($fruit['price_per_kg'] ?? 0), 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="text-right mt-4">
                            <p class="font-semibold">Total Amount: RM {{ number_format($this->calculateTotal(), 2) }}
                            </p>
                            <button wire:click="confirmTransaction" class="btn btn-success mt-2">Confirm Sale</button>
                        </div>
                    @endif
                </div>

            </div> --}}

        </div>
    </div>
</div>

@push('scripts')
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        function onScanSuccess(decodedText, decodedResult) {
            console.log(`Code Matched = ${decodedText}`, decodedResult);
            @this.set('decodedText', decodedText);
            Livewire.dispatch('scan-fruit');
        }

        function onScanFailure(error) {
            console.warn(`Code Scan Error = ${error}`);
        }

        function resumeScanner() {
            html5QrcodeScanner.resume();
        }

        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", {
                fps: 10,
                qrbox: {
                    width: 250,
                    height: 250,
                }
            },
            false);

        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    </script>
@endpush
