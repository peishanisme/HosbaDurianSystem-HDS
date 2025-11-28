<?php

namespace App\Livewire\Module;

use App\Models\Fruit;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\FruitFeedback;

class PublicPortalLivewire extends Component
{
    public Fruit $fruit;
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
                'fruit_uuid' => $this->fruit->uuid,
                'feedback' => $this->feedback,
            ]);

            $this->reset('feedback');
            $this->dispatch('feedback-reset');

            $this->dispatch('show-success-modal');
        } catch (\Illuminate\Validation\ValidationException $e) {

            // send FIRST validation message to modal
            $message = $e->validator->errors()->first();
            $this->dispatch('show-error-modal', message: $message);
        } catch (\Throwable $e) {

            // generic error message
            $this->dispatch('show-error-modal', message: 'Failed to submit feedback. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.module.public-portal-livewire')->layout('components.layouts.site');
    }
}
