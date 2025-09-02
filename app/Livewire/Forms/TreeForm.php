<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Models\Tree;
use App\Models\Species;
use App\DataTransferObject\TreeDTO;
use App\Actions\TreeManagement\CreateTreeAction;
use App\Actions\TreeManagement\UpdateTreeAction;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class TreeForm extends Form
{
    public ?Tree $tree = null;
    public ?string $planted_at = null;
    public TemporaryUploadedFile|string|null $thumbnail = null; 
    public ?int $species_id = null, $flowering_period = null;
    public ?float $height = null, $diameter = null;
    public ?string $tree_tag = null;

    protected function rules(): array
    {
        return [
            'species_id'        => ['required', 'exists:species,id'],
            'planted_at'        => ['required', 'date', 'before_or_equal:today'],
            'thumbnail'         => ['nullable'], 
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
        $this->thumbnail = $tree->thumbnail; 
        $this->height = $tree->growthLogs->first()->height ?? null;
        $this->diameter = $tree->growthLogs->first()->diameter ?? null;
        $this->flowering_period = $tree->flowering_period;
    }

    public function getSpeciesOptions(): array
    {
        return Species::pluck('name', 'id')->toArray();
    }

    public function create(array $validatedData): void
    {
        app(CreateTreeAction::class)->handle(TreeDTO::fromArray($validatedData));
    }

    public function update($validatedData): void
    {
        app(UpdateTreeAction::class)->handle($this->tree, TreeDTO::fromArray($validatedData));
    }
}
