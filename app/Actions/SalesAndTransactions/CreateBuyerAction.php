<?php

namespace App\Actions\SalesAndTransactions;

use App\DataTransferObject\BuyerDTO;
use App\Models\Buyer;
use App\Traits\PhoneNumberTrait;

class CreateBuyerAction
{
    use PhoneNumberTrait;
    public function handle(BuyerDTO $dto): Buyer
    {
        $dto->contact_number = self::formatForStorage($dto->contact_number);
        return Buyer::create($dto->toArray());
    }
}
