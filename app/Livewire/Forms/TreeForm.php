<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Models\Tree;
use App\Models\Species;
use Livewire\Attributes\Validate;
use App\DataTransferObject\TreeDTO;
use App\Actions\TreeManagement\CreateTreeAction;
use App\Actions\TreeManagement\UpdateTreeAction;

class TreeForm extends Form
{
    public ?Tree $tree = null;
    public ?string $planted_at, $thumbnail;
    public ?int $species_id = null, $flowering_period = null;
    public ?float $height = null, $diameter = null;
    public ?string $tree_tag = null;

    protected function rules(): array
    {
        return [
            'species_id'        => ['required', 'exists:species,id'],
            'planted_at'        => ['required', 'date', 'before_or_equal:today'],
            'thumbnail'         => ['nullable', 'string', 'max:255'],
            'height'            => ['required', 'numeric', 'min:0'],
            'diameter'          => ['required', 'numeric', 'min:0'],
            'flowering_period'  => ['required', 'numeric', 'min:1'],
        ];
    }

    public function edit(Tree $tree): void
    {
        $this->tree = $tree;
        $this->tree_tag = $tree->tree_tag;
        $this->species_id = $tree->species_id;
        $this->planted_at = $tree->planted_at;
        $this->thumbnail = $tree->thumbnail ??  null;
        $this->height = $tree->growthLogs->first()->height ?? null;
        $this->diameter = $tree->growthLogs()->first()->diameter ?? null;
        $this->flowering_period = $tree->flowering_period;
    }

    public function getSpeciesOptions(): array
    {
        return Species::pluck('name', 'id')->toArray();
        //scope active
    }

    public function create(array $validatedData): void
    {
        app(CreateTreeAction::class)->handle(TreeDTO::fromArray($validatedData));
    }

    public function update($validatedData): void
    {
        app(UpdateTreeAction::class)->handle($this->tree,TreeDTO::fromArray($validatedData));
    }
}
