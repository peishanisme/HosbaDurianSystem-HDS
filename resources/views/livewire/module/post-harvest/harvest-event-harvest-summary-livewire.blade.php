<div id="kt_app_content_container" class="container-fluid">
    <livewire:components.headers.harvest-event-header :harvestEvent="$harvestEvent" />

    <livewire:components.harvest-tree-fruit-details :harvestEvent="$harvestEvent" />

    <livewire:components.generate-report-modal model="App\Models\HarvestEvent" :harvestEvent="$harvestEvent"/>

</div>
