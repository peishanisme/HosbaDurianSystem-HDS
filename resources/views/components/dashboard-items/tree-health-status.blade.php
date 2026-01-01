<div class="card-body pt-6">

    <!-- Scrollable container -->
    <div class="table-responsive" style="max-height: 350px; overflow-y: auto;">

        <table class="table table-row-dashed align-middle gs-0 gy-3 my-0">

            <!-- Sticky table header -->
            <thead class="sticky-top bg-white" style="z-index: 1;">
                <tr class="fs-7 fw-bold text-gray-500 border-bottom-0">
                    <th class="p-0 pb-3 min-w-105px text-start">{{ __('messages.tree_tags') }}</th>
                    <th class="p-0 pb-3 min-w-170px text-end">{{ __('messages.disease') }}</th>
                    <th class="p-0 pb-3 min-w-100px text-end">{{ __('messages.status') }}</th>
                    <th class="p-0 pb-3 min-w-175px text-end pe-12">{{ __('messages.last_checked') }}</th>
                    <th class="p-0 pb-3 w-50px text-end">{{ __('messages.view') }}</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($treeHealthRecords as $item)
                    <tr>
                        <td class="text-start">{{ $item->treeTag }}</td>

                        <td class="text-end">{{ $item->diseaseName }}</td>

                        <td class="text-end">
                            <span class="badge 
                                {{ $item->status === 'Severe' ? 'badge-light-danger' : 
                                   ($item->status === 'Medium' ? 'badge-light-warning' : '') }}">
                                {{ __('messages.' . strtolower($item->status)) }}
                            </span>
                        </td>

                        <td class="text-end">{{ $item->updated_at->format('Y-m-d H:i') }}</td>

                        <td class="text-end">
                            <a href="{{ route('tree.health-record', $item->treeId) }}"
                                class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary w-30px h-30px">
                                <i class="ki-duotone ki-black-right fs-2 text-gray-500"></i>
                            </a>
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="5" class="text-center py-3 text-muted">
                            No severe health issues detected.
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>

    </div>
</div>
