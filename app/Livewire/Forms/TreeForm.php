<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Models\Tree;
use Livewire\Attributes\Validate;
use App\DataTransferObject\TreeDTO;
use App\Actions\UserManagement\CreateTreeAction;
use App\Actions\UserManagement\UpdateTreeAction;

class TreeForm extends Form
{
    public ?Tree $tree = null;
    public ?string $tree_tag, $planted_at, $thumbnail;
    public ?int $species_id = null;
    public float $latitude, $longitude;

    protected function rules(): array
    {
        return [
            'tree_tag'   => ['required', 'string', 'max:255', 'unique:trees,tree_tag,' . ($this->tree->id ?? 'NULL')],
            'species_id' => ['required', 'exists:species,id'],
            'planted_at' => ['required', 'date'],
            'thumbnail'  => ['nullable', 'string', 'max:255'],
            'latitude'   => ['nullable', 'numeric'],
            'longitude'  => ['nullable', 'numeric'],
        ];
    }

    public function edit(Tree $tree): void
    {
        $this->tree = $tree;
        $this->tree_tag = $tree->tree_tag;
        $this->species_id = $tree->species_id;
        $this->planted_at = $tree->planted_at->format('Y-m-d');
        $this->thumbnail = $tree->thumbnail ??  null;
        $this->latitude = $tree->latitude ?? null;
        $this->longitude = $tree->longitude ?? null;
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
