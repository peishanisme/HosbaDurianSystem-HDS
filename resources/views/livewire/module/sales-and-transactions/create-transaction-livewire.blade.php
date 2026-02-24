<div class="container-fluid">
    <div class="card shadow-sm mt-10">
        <div class="card-header">
            <h3 class="card-title">{{ __('messages.create_new_transaction') }}</h3>
        </div>

        <div class="card-body">
            <div class="stepper stepper-pills" wire>
                <x-stepper :label1="'Buyer details'" :label2="'Scan fruit'" :label3="'Transaction details'" :label4="'Review'" :activeStep="$activeStep" />

                <form class="form px-lg-10 mx-auto" novalidate="novalidate">
                    <div class="mb-5">
                        <!-- Step 1 -->
                        @if ($activeStep === 1)
                            <div class="flex-column">
                                <div class="fv-row mb-10">
                                    <div class="fv-row mb-10">
                                        <x-input-label for="buyer" class="required mb-2" :value="__('messages.buyer')" />
                                        <x-input-select id="buyer" placeholder="{{ __('messages.select_buyer') }}"
                                            wire:model="form.buyer_uuid" :options="$buyerOptions" />
                                        <x-input-error :messages="$errors->get('form.buyer_uuid')" />
                                    </div>

                                    <div class="fv-row mb-10">
                                        <x-input-label for="date" class="mb-2 required" :value="__('messages.date')" />
                                        <x-input-text id="date" placeholder="{{ __('messages.date') }}" wire:model="form.date"
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
                                            <h5 class="card-title mb-0">{{ __('messages.scan_fruit_qr_code') }}</h5>

                                            <div wire:ignore>
                                                <small class="text-muted"> {{ __('messages.scan') }} <strong>{{ __('messages.fruit_qr_code') }}</strong>
                                                    {{ __('messages.here') }}</small>
                                                <div id="reader" class="mb-4" style="width:100%;max-width:600px;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-lg-9">
                                        <livewire:components.transaction-scanned-fruit-table :scannedFruits="$scannedFruits"
                                            wire:key="transaction-scanned-fruit-table" />
                                        <div class="mt-2"> <x-input-error :messages="$errors->get('scannedFruits')" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Step 3 -->
                        @if ($activeStep === 3)
                            <div class="flex-column">
                                <livewire:components.transaction-fruit-summary-table :scanned-fruits="$scannedFruits"
                                    :summary="$summary" :discount="$discount" wire:key="summary-table" />

                                <div class="mt-2">
                                    <x-input-error :messages="$errors->get('summaryPrices')" />
                                </div>
                            </div>
                        @endif

                        <!-- Step 4 -->
                        @if ($activeStep === 4)
                            <div class="flex-column">
                                <div class="card p-5 mb-10 shadow-sm">
                                    <h5 class="mb-3">{{ __('messages.transaction_summary') }}</h5>

                                    <div class="d-flex justify-content-between mb-2">
                                        <span>{{ __('messages.subtotal') }}</span>
                                        <span>RM {{ number_format($form->subtotal, 2) }}</span>
                                    </div>

                                    <div class="d-flex justify-content-between mb-2">
                                        <span>{{ __('messages.discount') }}</span>
                                        <span>- RM {{ number_format($form->discount, 2) }}</span>
                                    </div>

                                    <hr>

                                    <div class="d-flex justify-content-between fw-bold text-success fs-5">
                                        <span>{{ __('messages.final_amount') }}</span>
                                        <span>RM {{ number_format($form->total_price, 2) }}</span>
                                    </div>
                                </div>

                                <div class="fv-row mb-10">
                                    <x-input-label for="payment_method" class="mb-2 required" :value="__('messages.payment_method')" />
                                    <x-input-select id="payment_method" placeholder="{{ __('messages.select_payment_method') }}"
                                        wire:model="form.payment_method" :options="[
                                            'cash' => __('messages.cash'),
                                            'credit_card' => __('messages.credit_card'),
                                            'bank_transfer' => __('messages.bank_transfer'),
                                            'e_wallet' => __('messages.e_wallet'),
                                        ]" />
                                    <x-input-error :messages="$errors->get('form.payment_method')" />
                                </div>

                                <div class="fv-row mb-10">
                                    <x-input-label for="remark" class="mb-2" :value="__('messages.remark')" />
                                    <x-input-textarea id="remark" placeholder="{{ __('messages.remark') }}"
                                        wire:model="form.remark"></x-input-textarea>
                                    <x-input-error :messages="$errors->get('form.remark')" />
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="d-flex flex-stack">
                        <div class="me-2">
                            @if ($activeStep > 1)
                                <button type="button" class="btn btn-light btn-active-light-primary"
                                    wire:click="previousStep">
                                    {{ __('messages.back') }}
                                </button>
                            @endif
                        </div>

                        <div>
                            @if ($activeStep < 4)
                                <button type="button" class="btn btn-primary" wire:click="nextStep">
                                    {{ __('messages.continue') }}
                                </button>
                            @else
                                <button type="button" class="btn btn-primary" wire:click="create">
                                    <span class="indicator-label" wire:loading.remove wire:target="create">{{ __('messages.submit') }}</span>
                                    <span class="indicator-progress" wire:loading wire:target="create">
                                        {{ __('messages.please_wait') }}
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span>
                                </button>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
