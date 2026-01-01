<div id="kt_app_content_container" class="container-fluid">
    <livewire:components.headers.buyer-header-livewire :buyer="$buyer" />

    <livewire:tables.transaction-listing-table :buyer="$buyer" />

    <livewire:module.sales-and-transactions.transaction-details-modal-livewire />

</div>
