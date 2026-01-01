<?php

namespace App\Livewire\Module;

use App\Models\Fruit;
use Livewire\Component;
use App\Models\FruitFeedback;
use App\Services\BlockchainService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class PublicPortalLivewire extends Component
{
    public Fruit $fruit;
    public ?string $feedback = '';
    public array $ipfsMetadata = [];
    public bool $isVerified = false;
    public ?string $verificationReasonCode = null;
    public ?string $verificationError = null;

    public function mount()
    {
        try {
            if (empty($this->fruit->metadata_cid)) {
                return $this->failVerification('not_published');
            }

            // 1. Fetch metadata from IPFS
            $response = Http::timeout(10)->get(
                "https://ipfs.io/ipfs/{$this->fruit->metadata_cid}"
            );

            if (!$response->successful()) {
                return $this->failVerification('temporarily_unavailable');
            }

            $this->ipfsMetadata = $response->json();

            // 2. Recalculate hash
            $metadataJson = json_encode(
                $this->ipfsMetadata,
                JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
            );

            $recalculatedHash = hash('sha256', $metadataJson);

            // 3. Read hash from blockchain
            $onChainHash = Cache::remember(
                "fruit_hash_{$this->fruit->fruit_tag}",
                now()->addMinutes(10),
                fn() => app(BlockchainService::class)
                    ->getFruitOnChain($this->fruit->fruit_tag)
            );            

            if (!$onChainHash) {
                return $this->failVerification('record_not_found');
            }

            // 4. Compare hashes
            if (
                $this->normalizeHash($recalculatedHash) !==
                $this->normalizeHash($onChainHash)
            ) {
                return $this->failVerification('data_mismatch');
            }

            // 5. Verified
            $this->isVerified = true;
            $this->verificationReasonCode = null;
        } catch (\Throwable $e) {
            $this->failVerification('temporarily_unavailable');
        }
    }

    private function failVerification(string $reason): void
    {
        $this->isVerified = false;
        $this->verificationReasonCode = $reason;
    }

    private function normalizeHash(string $hash): string
    {
        return strtolower(ltrim($hash, '0x'));
    }

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
