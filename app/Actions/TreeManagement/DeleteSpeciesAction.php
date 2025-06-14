<?php

namespace App\Actions\TreeManagement;

use App\Models\Species;

class DeleteSpeciesAction
{
    public static function handle(Species $species): Species
    {
        // Soft delete related trees
        $species->trees()->each(function ($tree) {
            $tree->delete();
        });

        // Soft delete the species itself
        $species->delete();

        return $species;
    }
}
