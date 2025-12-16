<div class="container-fluid">
    <div class="card shadow-sm mt-10">
        <div class="card-header">
            <h3 class="card-title">Create New Transaction</h3>
        </div>

        <div class="card-body">
            <!--begin::Stepper-->
            <div class="stepper stepper-pills" wire>
                {{-- Stepper header --}}
                <x-stepper :label1="'Buyer details'" :label2="'Scan fruit'" :label3="'Transaction details'" :label4="'Review'" :activeStep="$activeStep" />

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
                                                <small class="text-muted">Scan <strong>Fruit QR code</strong>
                                                    Here</small>
                                                <div id="reader" class="mb-4" style="width:100%;max-width:600px;">
                                                </div>
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
                                <livewire:components.transaction-fruit-summary-table :scannedFruits="$scannedFruits"
                                    :summary="$summary" :discount="$form->discount" :subtotal="$form->subtotal" :finalAmount="$form->total_price" wire:key="summary-table" />
                                <div class="mt-2">
                                    <x-input-error :messages="$errors->get('summaryPrices')" />
                                </div>
                            </div>
                        @endif

                        <!-- Step 4 -->
                        @if ($activeStep === 4)
                            <div class="flex-column">
                                <div class="card p-5 mb-10 shadow-sm">
                                    <h5 class="mb-3">Transaction Summary</h5>

                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Subtotal</span>
                                        <span>RM {{ number_format($form->subtotal, 2) }}</span>
                                    </div>

                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Discount</span>
                                        <span>- RM {{ number_format($form->discount, 2) }}</span>
                                    </div>

                                    <hr>

                                    <div class="d-flex justify-content-between fw-bold text-success fs-5">
                                        <span>Final Amount</span>
                                        <span>RM {{ number_format($form->total_price, 2) }}</span>
                                    </div>
                                </div>

                                <!-- Payment Method -->
                                <div class="fv-row mb-10">
                                    <x-input-label for="payment_method" class="mb-2 required" :value="__('Payment Method')" />
                                    <x-input-select id="payment_method" placeholder="Select Payment Method"
                                        wire:model="form.payment_method" :options="[
                                            'cash' => 'Cash',
                                            'credit_card' => 'Credit Card',
                                            'bank_transfer' => 'Bank Transfer',
                                            'e_wallet' => 'E-Wallet',
                                        ]" />
                                    <x-input-error :messages="$errors->get('form.payment_method')" />
                                </div>

                                <div class="fv-row mb-10">
                                    <x-input-label for="remark" class="mb-2" :value="__('Remark')" />
                                    <x-input-textarea id="remark" placeholder="Remark"
                                        wire:model="form.remark"></x-input-textarea>
                                    <x-input-error :messages="$errors->get('form.remark')" />
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
                                <button type="button" class="btn btn-primary" wire:click="create"
                                    wire:loading.attr="disabled">
                                    <span class="indicator-label">Submit</span>
                                    <span class="indicator-progress">
                                        Please wait... <span
                                            class="spinner-border spinner-border-sm align-middle ms-2"></span>
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
        document.addEventListener("DOMContentLoaded", () => {
            let html5QrcodeScanner;

            function onScanSuccess(decodedText) {
                console.log('Raw scanned text:', decodedText);

                let uuid = null;

                try {
                    const url = new URL(decodedText);
                    const segments = url.pathname.split('/').filter(Boolean);
                    uuid = segments[segments.length - 1];
                } catch (e) {
                    // In case QR is not a valid URL
                    uuid = decodedText;
                }

                // Optional UUID format validation
                const uuidRegex =
                    /^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i;

                if (!uuidRegex.test(uuid)) {
                    Livewire.dispatch('toast', {
                        type: 'warning',
                        message: 'Invalid QR code detected'
                    });
                    return;
                }

                console.log('Extracted UUID:', uuid);

                Livewire.dispatch('scan-fruit', {
                    uuid
                });
            }

            let lastErrorAt = 0;

            function onScanFailure(error) {
                const now = Date.now();

                // only fire once every 3 seconds
                if (now - lastErrorAt < 3000) return;
                lastErrorAt = now;

                Livewire.dispatch('scan-error', {
                    error: 'Invalid QR code detected'
                });
            }

            Livewire.on('init-qr-scanner', () => {
                // Wait a bit to ensure #reader exists in DOM
                setTimeout(() => {
                    const readerElem = document.getElementById("reader");
                    if (!readerElem) {
                        console.error("QR Reader element not found.");
                        return;
                    }

                    // Clear old scanner if exists
                    if (html5QrcodeScanner) {
                        html5QrcodeScanner.clear().catch(err => console.warn(err));
                        html5QrcodeScanner = null;
                    }

                    html5QrcodeScanner = new Html5QrcodeScanner("reader", {
                        fps: 10,
                        qrbox: {
                            width: 250,
                            height: 250
                        }
                    }, false);

                    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
                }, 300);
            });
        });
    </script>
@endpush
