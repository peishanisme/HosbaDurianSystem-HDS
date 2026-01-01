<div class="table-responsive">
    <table class="table table-striped align-middle">
        <thead>
            <tr>
                <th>Tag</th>
                <th>Species</th>
                <th>Grade</th>
                <th>Weight (kg)</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($scannedFruits as $fruit)
                <tr wire:key="fruit-{{ $fruit['uuid'] }}">
                    <td>{{ $fruit['tag'] }}</td>
                    <td>{{ $fruit['species'] }}</td>
                    <td>{{ $fruit['grade'] }}</td>
                    <td>{{ $fruit['weight'] }}</td>
                    <td>
                        <button type="button" wire:click="remove('{{ $fruit['uuid'] }}')" class="btn btn-sm btn-danger">
                            Remove
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">No fruits scanned yet</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
