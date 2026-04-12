<?php

namespace App\Livewire\Module;

use App\Models\FruitFeedback;
use App\Models\Tree;
use Livewire\Component;

class PublicPortalLivewire extends Component
{
    public Tree $tree;
    public ?string $feedback = '';

    public function submit()
    {
        try {
            // VALIDATION
            $this->validate([
                'feedback' => 'required|string|max:1000',
            ], [
                'feedback.required' => 'Please enter your feedback before submitting.',
                'feedback.max' => 'Feedback cannot exceed 1000 characters.',
            ]);

            // SAVE
            FruitFeedback::create([
                'tree_uuid' => $this->tree->uuid,
                'feedback' => $this->feedback,
            ]);

            $this->reset('feedback');
            $this->dispatch('feedback-reset');

            $this->dispatch('show-success-modal');
        } catch (\Illuminate\Validation\ValidationException $e) {

            $message = $e->validator->errors()->first();
            $this->dispatch('show-error-modal', message: $message);
        } catch (\Throwable $e) {

            $this->dispatch('show-error-modal', message: 'Failed to submit feedback. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.module.public-portal-livewire')->layout('components.layouts.site');
    }
}
