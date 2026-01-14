<div>
    <table class="table table-bordered align-middle">
        <thead class="bg-light fw-bold">
            <tr>
                <th>Species</th>
                <th>Grade</th>
                <th class="text-end">Count</th>
                <th class="text-end">Total Weight (kg)</th>
                <th class="text-end">Price / kg (RM)</th>
                <th class="text-end">Subtotal (RM)</th>
            </tr>
        </thead>

        <tbody>
            @forelse($summary as $key => $item)
                <tr>
                    <td>{{ $item['species'] }}</td>
                    <td>{{ $item['grade'] }}</td>
                    <td class="text-end">{{ $item['count'] }}</td>
                    <td class="text-end">{{ number_format($item['total_weight'], 2) }}</td>

                    <td class="text-end">
                        <input type="text" inputmode="decimal" class="form-control form-control-sm text-end"
                            wire:input="updatePrice('{{ $key }}', $event.target.value)"
                            onblur="this.value = this.value === '' ? '0' : this.value">
                    </td>

                    <td class="text-end">
                        {{ number_format($item['subtotal'] ?? 0, 2) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        No fruits scanned yet.
                    </td>
                </tr>
            @endforelse
        </tbody>

        @if (!empty($summary))
            <tfoot class="fw-semibold">
                <tr>
                    <td colspan="5" class="text-end">Subtotal (Before Discount):</td>
                    <td class="text-end">RM {{ number_format($this->subtotal, 2) }}</td>
                </tr>

                <tr>
                    <td colspan="5" class="text-end">Discount (RM):</td>
                    <td class="text-end">
                        <input type="text" inputmode="decimal" wire:model.live="discountInput"
                            class="form-control form-control-sm text-end" onblur="if(this.value==='') this.value='0'">

                    </td>
                </tr>

                <tr class="bg-light">
                    <td colspan="5" class="text-end">Final Amount:</td>
                    <td class="text-end fs-5 fw-bold text-success">
                        RM {{ number_format($this->finalAmount, 2) }}
                    </td>
                </tr>
            </tfoot>
        @endif
    </table>
</div>
