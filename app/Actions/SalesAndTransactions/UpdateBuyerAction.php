<?php

namespace App\Actions\SalesAndTransactions;

use App\Models\Buyer;
use App\Traits\PhoneNumberTrait;
use App\DataTransferObject\BuyerDTO;

class UpdateBuyerAction
{
    use PhoneNumberTrait;

    public function handle(Buyer $buyer, BuyerDTO $dto): Buyer
    {
        $dto->contact_number = self::formatForStorage($dto->contact_number);
        return tap($buyer)->update($dto->toArray());
    }
}
