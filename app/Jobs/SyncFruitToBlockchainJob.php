<?php

namespace App\Jobs;

use App\Models\Fruit;
use App\Services\BlockchainService;
use App\Traits\FruitBlockchainHelper;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SyncFruitToBlockchainJob implements ShouldQueue
{
    use Dispatchable, Queueable, InteractsWithQueue, SerializesModels, FruitBlockchainHelper;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $fruitId)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(BlockchainService $blockchainService): void
    {
        
        $fruit = Fruit::findOrFail($this->fruitId);

        if (! $fruit->metadata_hash) {
            throw new \Exception('Metadata not ready');
            
        }

        $this->pushToBlockchain($fruit, $fruit->metadata_hash, $blockchainService);

        // $fruit = Fruit::findOrFail($this->fruitId);

        // if ($fruit->is_onchain) return;

        // if (! $fruit->metadata_hash) {
        //     throw new \Exception('Metadata not ready');
        // }

        // try {
        //     $this->pushToBlockchain($fruit, $fruit->metadata_hash, $blockchainService);
        // } catch (\Exception $e) {
        //     if ($this->attempts() < 5) {
        //         self::dispatch($fruit->id)->delay(now()->addMinutes(5));
        //     } else {
        //         throw $e;
        //     }
        // }
    }
}
