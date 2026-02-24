<?php

namespace App\Actions\TreeManagement;

use App\Models\Label;
use App\Models\Tree;
use Illuminate\Support\Facades\DB;

class AttachLabelToTreeAction
{
    /**
     * Attach or create a label and attach it to a tree.
     * 
     * @param Tree $tree
     * @param string $labelText
     * @param string|null $color
     * @return array
     */
    public function execute(Tree $tree, string $labelText, ?string $color = null): array
    {
        return DB::transaction(function () use ($tree, $labelText, $color) {
            // Search if the label already exists
            $label = Label::where('label', $labelText)->first();

            $isNewLabel = false;

            if (!$label) {
                // Create a new label if it doesn't exist
                $label = Label::create([
                    'name' => $labelText,
                    'label' => $labelText,
                    'color' => $color,
                ]);
                $isNewLabel = true;
            }

            // Attach the label to the tree (if not already attached)
            if (!$tree->labels()->where('label_id', $label->id)->exists()) {
                $tree->labels()->attach($label->id);
            }

            return [
                'label' => $label,
                'is_new_label' => $isNewLabel,
                'message' => $isNewLabel ? 'Label created and attached successfully' : 'Label attached successfully',
            ];
        });
    }
}
