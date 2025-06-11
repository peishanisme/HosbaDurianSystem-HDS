<?php

namespace App\Actions\SalesAndTransactions;

use App\DataTransferObject\BuyerDTO;
use App\Models\Buyer;

class CreateBuyerAction
{
    public function handle(BuyerDTO $dto): Buyer
    {
        return Buyer::create($dto->toArray());
    }
}
