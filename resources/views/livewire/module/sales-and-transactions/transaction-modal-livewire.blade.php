<x-modal-component :id="$modalID" :title="$modalTitle">
    <div class="fv-row mb-8">
        <x-input-label for="date" class="required mb-2" :value="__('messages.date')" />
        <x-input-text id="date" type="date" wire:model="form.date" />
        <x-input-error :messages="$errors->get('form.date')" />
    </div>

    <div class="fv-row mb-8">
        <x-input-label for="recordByGrade" class="required mb-4" :value="__('messages.record_by_grade')" />

        <div class="d-flex w-full gap-5">
            <label for="recordByGradeTrue"
                class="w-100 d-flex align-items-center gap-3 p-3 border border-dashed rounded cursor-pointer
           form-check-label text-gray-700 border-gray-400 "
                style="
        transition: all 0.2s;
    ">
                <input type="radio" class="form-check-input me-2" id="recordByGradeTrue" name="recordByGrade"
                    value="true" wire:model.live="form.recordByGrade" />
                <span class="fw-semibold small">{{ __('messages.yes') }}</span>
            </label>

            <label for="recordByGradeFalse"
                class="w-100 d-flex align-items-center gap-3 p-3 border border-dashed rounded cursor-pointer
           form-check-label text-gray-700 border-gray-400 "
                style="
        transition: all 0.2s;
    ">
                <input type="radio" class="form-check-input me-2" id="recordByGradeFalse" name="recordByGrade"
                    value="false" wire:model.live="form.recordByGrade" />
                <span class="fw-semibold small">{{ __('messages.no') }}</span>
            </label>

        </div>

        <x-input-error :messages="$errors->get('form.recordByGrade')" />
    </div>

    @if ($form->recordByGrade)

        <div class="mb-8">
            <x-input-label for="grade_breakdown" class="required mb-2" :value="__('messages.grade_breakdown')" />

            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>Grade</th>
                            <th>Total Weight (KG)</th>
                            <th>Total Amount (RM)</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach (App\Enum\TransactionGrade::values() as $grade)
                            <tr>
                                <td>{{ $grade }}</td>

                                <td>
                                    <x-input-text type="number" step="0.01"
                                        wire:model="form.grade_breakdown.{{ $grade }}.total_weight" />
                                </td>

                                <td>
                                    <x-input-text type="number" step="0.01"
                                        wire:model="form.grade_breakdown.{{ $grade }}.total_amount" />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <x-input-error :messages="$errors->get('form.grade_breakdown')" />
        </div>

    @endif

    @if (!$form->recordByGrade)
        <div class="fv-row mb-8">
            <x-input-label for="total_weight" class="mb-2" :value="__('messages.total_weight')" />
            <x-input-text id="total_weight" type="number" step="0.01" wire:model="form.total_weight" />
            <x-input-error :messages="$errors->get('form.total_weight')" />
        </div>
        <div class="fv-row mb-8">
            <x-input-label for="total_amount" class="mb-2" :value="__('messages.total_amount')" />
            <x-input-text id="total_amount" type="number" step="0.01" wire:model="form.total_amount" />
            <x-input-error :messages="$errors->get('form.total_amount')" />
        </div>
    @endif

    @slot('footer')
        <x-button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.cancel') }}</x-button>
        <x-button type="submit" class="btn btn-primary"
            wire:click="{{ $form->transaction ? 'update' : 'create' }}">{{ $form->transaction ? __('messages.update') : __('messages.add') }}</x-button>
    @endslot
</x-modal-component>
