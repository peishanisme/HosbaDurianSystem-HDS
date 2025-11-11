<div class="container-fluid">
    <div class="card shadow-sm mt-10">
        <div class="card-header">
            <h3 class="card-title">Create New Transaction</h3>
        </div>

        <div class="card-body">
            <!--begin::Stepper-->
            <div class="stepper stepper-pills" wire>
                {{-- Stepper header --}}
                <x-stepper 
                    :label1="'Buyer details'" 
                    :label2="'Scan fruit'" 
                    :label3="'Transaction details'" 
                    :label4="'Review'"
                    :activeStep="$activeStep"
                />

                <!--begin::Form-->
                <form class="form px-lg-10 mx-auto" novalidate="novalidate">
                    <div class="mb-5">
                        <!-- Step 1 -->
                        @if ($activeStep === 1)
                            <div class="flex-column">
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
                                </div>
                            </div>
                        @endif

                        <!-- Step 2 -->
                        @if ($activeStep === 2)
                            <div class="flex-column">
                                <div class="row gx-10">
                                    <div class="col-12 col-lg-3">
                                        <div class="h-100">
                                            <h5 class="card-title mb-0">Scan Fruit QR Code</h5>

                                            <div wire:ignore>
                                                <small class="text-muted">Scan <strong>Fruit QR code</strong> Here</small>
                                                <div id="reader" class="mb-4" style="width:100%;max-width:600px;"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-lg-9">
                                        <livewire:components.transaction-scanned-fruit-table :scannedFruits="$scannedFruits"
                                            wire:key="transaction-scanned-fruit-table" />
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Step 3 -->
                        @if ($activeStep === 3)
                            <div class="flex-column">
                                <livewire:components.transaction-fruit-summary-table :scannedFruits="$scannedFruits" />
                            </div>
                        @endif

                        <!-- Step 4 -->
                        @if ($activeStep === 4)
                            <div class="flex-column">
                                <div class="mt-4">
                                    <p>Subtotal: RM {{ number_format($subtotal, 2) }}</p>
                                    <p>Discount: RM {{ number_format($discount, 2) }}</p>
                                    <p class="fw-bold text-success">Final Amount: RM {{ number_format($finalAmount, 2) }}</p>
                                </div>

                                <div class="fv-row mb-10">
                                    <x-input-label for="remarks" class="mb-2" :value="__('Remarks')" />
                                    <x-input-textarea id="remarks" placeholder="Remarks"
                                        wire:model="form.remarks"></x-input-textarea>
                                    <x-input-error :messages="$errors->get('form.remarks')" />
                                </div>
                            </div>
                        @endif
                    </div>

                    <!--begin::Actions-->
                    <div class="d-flex flex-stack">
                        <div class="me-2">
                            @if ($activeStep > 1)
                                <button type="button" class="btn btn-light btn-active-light-primary"
                                    wire:click="previousStep">
                                    Back
                                </button>
                            @endif
                        </div>

                        <div>
                            @if ($activeStep < 4)
                                <button type="button" class="btn btn-primary" wire:click="nextStep">
                                    Continue
                                </button>
                            @else
                                <button type="button" class="btn btn-primary" wire:click="submitForm" wire:loading.attr="disabled">
                                    <span class="indicator-label">Submit</span>
                                    <span class="indicator-progress">
                                        Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span>
                                </button>
                            @endif
                        </div>
                    </div>
                    <!--end::Actions-->
                </form>
                <!--end::Form-->
            </div>
            <!--end::Stepper-->
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

        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", {
                fps: 10,
                qrbox: { width: 250, height: 250 },
            },
            false
        );
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    </script>
@endpush
