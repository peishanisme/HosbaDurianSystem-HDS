<div class="container-fluid">
    <livewire:components.agrochemical-details-header :agrochemical="$agrochemical" />

    <livewire:module.agrochemical-management.agrochemical-update-stock-modal-livewire :agrochemical="$agrochemical"/>

    <livewire:tables.purchase-history-table :agrochemical="$agrochemical" />
</div>
