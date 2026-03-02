<?php

namespace App\Actions\TreeManagement;

use App\Models\Tree;
use App\Models\Label;
use Illuminate\Support\Facades\DB;

class DeleteTreeLabelAction
{
    /**
     * Delete a label from a tree.
     * If the label is not attached to any other trees, delete the label entirely.
     * 
     * @param Tree $tree
     * @param Label $label
     * @return array
     */
    public function execute(Tree $tree, Label $label): array
    {
        return DB::transaction(function () use ($tree, $label) {
            // Detach the label from the tree
            $tree->labels()->detach($label->id);

            // Check if the label is attached to any other trees
            $labelCount = $label->trees()->count();

            $labelDeleted = false;

            // If no trees are using this label, delete the label
            if ($labelCount === 0) {
                $label->delete();
                $labelDeleted = true;
            }

            return [
                'message' => $labelDeleted 
                    ? 'Label removed from tree and deleted (no longer used)' 
                    : 'Label removed from tree successfully',
                'label_deleted' => $labelDeleted,
            ];
        });
    }
}
