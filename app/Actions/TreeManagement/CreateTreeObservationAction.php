<?php

namespace App\Actions\TreeManagement;

use App\Models\Tree;
use App\Models\TreeObservation;
use App\Models\HarvestRecord;
use App\Models\HarvestEvent;
use Illuminate\Support\Facades\DB;

class CreateTreeObservationAction
{
    /**
     * Create or update a TreeObservation for a harvest event.
     *
     * @param Tree $tree
     * @param string $harvestUuid
     * @param string $floweringStatus
     * @return TreeObservation
     *
     * @throws \InvalidArgumentException when flowering status is invalid or harvest not found
     */
    public function execute(Tree $tree, string $harvestUuid, string $floweringStatus): TreeObservation
    {
        $validStatuses = ['A', 'B', 'C', 'D', 'X'];

        if (!in_array($floweringStatus, $validStatuses, true)) {
            throw new \InvalidArgumentException('Invalid flowering status. Valid values: ' . implode(',', $validStatuses));
        }

        // Ensure harvest record exists. If not, try to find a HarvestEvent and
        // create a minimal HarvestRecord for this tree using that event's uuid.
        $harvest = HarvestRecord::where('harvest_uuid', $harvestUuid)->first();

        if (!$harvest) {
            $harvestEvent = HarvestEvent::where('uuid', $harvestUuid)->first();

            if ($harvestEvent) {
                $harvest = HarvestRecord::create([
                    'harvest_uuid' => $harvestEvent->uuid,
                    'tree_uuid' => $tree->uuid,
                    'harvest_date' => $harvestEvent->start_date ?? now()->toDateString(),
                    'num_of_fruits' => 0,
                    'weight' => null,
                    'spoilt' => false,
                ]);
            }
        }

        if (!$harvest) {
            throw new \InvalidArgumentException('Harvest record not found for provided harvest_uuid');
        }

        return DB::transaction(function () use ($tree, $harvestUuid, $floweringStatus) {
            $observation = TreeObservation::firstOrNew([
                'tree_uuid' => $tree->uuid,
                'harvest_uuid' => $harvestUuid,
            ]);

            $observation->flowering_status = $floweringStatus;
            $observation->save();

            return $observation;
        });
    }
}
