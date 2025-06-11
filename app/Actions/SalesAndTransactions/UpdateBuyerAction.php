<?php

namespace App\Actions\SalesAndTransactions;

use App\DataTransferObject\BuyerDTO;
use App\Models\Buyer;

class UpdateBuyerAction
{
    public function handle(Buyer $buyer, BuyerDTO $dto): Buyer
    {
        return tap($buyer)->update($dto->toArray());
    }
}
