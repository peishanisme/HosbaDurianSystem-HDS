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
                        <input type="number" step="0.01" wire:model.live="summary.{{ $key }}.price_per_kg" min="0"
                            class="form-control form-control-sm text-end" oninput="if(this.value === '') this.value = 0"/>
                    </td>
                    <td class="text-end">
                        {{ number_format(($item['total_weight'] ?? 0) * ($item['price_per_kg'] ?? 0), 2) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">No fruits scanned yet.</td>
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
                    <td colspan="5" class="text-end">
                        Discount (RM):
                    </td>
                    <td class="text-end">
                        <input type="number" step="0.01" wire:model.live="discount"
                            class="form-control form-control-sm text-end" />
                    </td>
                </tr>
                <tr class="bg-light">
                    <td colspan="5" class="text-end">Final Amount:</td>
                    <td class="text-end fs-5 fw-bold text-success">RM {{ number_format($this->finalAmount, 2) }}</td>
                </tr>
            </tfoot>
        @endif
    </table>
</div>
