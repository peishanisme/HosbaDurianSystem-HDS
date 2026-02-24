<?php

namespace App\Actions;

class FormatPhoneNumberAction
{
    public static function handle($phone): String
    {
        // Remove any non-digit characters
        $digits = preg_replace('/\D/', '', $phone);

        // Check if the number starts with country code '60' (Malaysia)
        if (substr($digits, 0, 2) !== '60') {
            // If it starts with '0', replace it with '60'
            if (substr($digits, 0, 1) === '0') {
                $digits = '60' . substr($digits, 1);
            } else {
                // Otherwise, just prepend '60'
                $digits = '60' . $digits;
            }
        }

        return $digits;
    }
}
