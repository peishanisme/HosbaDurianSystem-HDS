<?php

namespace App\Jobs;

use App\Services\PinataService;
use App\Traits\FruitBlockchainHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class SyncFruitToPinataJob implements ShouldQueue
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
    public function handle(
        PinataService $pinataService,
    ): void {
        $fruit = \App\Models\Fruit::findOrFail($this->fruitId);

        $metadata = $this->buildMetadata($fruit);
        $hash = $this->computeMetadataHash($metadata);

        $cid = $this->uploadToPinata($metadata, $fruit->fruit_tag, $pinataService);
        $this->saveMetadata($fruit, $cid, $hash);

        SyncFruitToBlockchainJob::dispatch($fruit->id);
    }
}
