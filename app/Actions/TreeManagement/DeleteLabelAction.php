<?php

namespace App\Actions\TreeManagement;

use App\Models\Label;
use Illuminate\Support\Facades\DB;

class DeleteLabelAction
{
    /**
     * Delete a label and remove it from all trees.
     * 
     * @param Label $label
     * @return bool
     */
    public function execute(Label $label): bool
    {
        return DB::transaction(function () use ($label) {
            // Delete all pivot records (tree_label associations)
            $label->trees()->detach();
            
            // Delete the label
            return $label->delete();
        });
    }
}
