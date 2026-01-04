<div id="kt_app_content_container" class="container-fluid">
    <livewire:components.headers.agrochemical-details-header :agrochemical="$agrochemical" />

    <livewire:module.agrochemical-management.agrochemical-update-stock-modal-livewire :agrochemical="$agrochemical"/>

    <livewire:tables.agrochemical-purchase-history-table :agrochemical="$agrochemical" />
</div>
