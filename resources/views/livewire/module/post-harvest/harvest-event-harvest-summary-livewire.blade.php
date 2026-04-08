<div id="kt_app_content_container" class="container-fluid">
    <livewire:components.headers.harvest-event-header :harvestEvent="$harvestEvent" />

    <livewire:tables.harvest-record-table :harvestEvent="$harvestEvent" />

    <livewire:components.generate-report-modal model="App\Models\HarvestEvent" :harvestEvent="$harvestEvent"/>

    <livewire:module.post-harvest.harvest-qr-code-modal-livewire />

</div>
